<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Schema;
use Exception;

class BackupController extends Controller
{
    public function download()
    {
        set_time_limit(0);
        $tempDir = storage_path('app/temp_backup_' . time());
        File::makeDirectory($tempDir);

        try {
            // 1. Generate SQL Dump
            $sqlFilename = 'database.sql';
            $sqlPath = $tempDir . '/' . $sqlFilename;
            $this->generateSqlDump($sqlPath);

            // 2. Prepare Storage Files
            $publicStoragePath = storage_path('app/public');
            $backupStoragePath = $tempDir . '/storage';
            
            if (File::exists($publicStoragePath)) {
                File::copyDirectory($publicStoragePath, $backupStoragePath);
            }

            // 3. Create ZIP
            $zipFilename = 'backup-' . date('Y-m-d-H-i-s') . '.zip';
            $zipPath = storage_path('app/' . $zipFilename);
            
            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Add SQL file
                $zip->addFile($sqlPath, $sqlFilename);

                // Add Storage files
                if (File::exists($backupStoragePath)) {
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($backupStoragePath),
                        RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = 'storage/' . substr($filePath, strlen($backupStoragePath) + 1);
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                }
                
                $zip->close();
            } else {
                throw new Exception("Could not create ZIP file");
            }

            // 4. Cleanup Temp Dir
            File::deleteDirectory($tempDir);

            // 5. Download ZIP
            return response()->download($zipPath)->deleteFileAfterSend(true);

        } catch (Exception $e) {
            File::deleteDirectory($tempDir);
            if (isset($zipPath) && File::exists($zipPath)) {
                File::delete($zipPath);
            }
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        set_time_limit(0);
        $request->validate([
            'backup_file' => 'required|file|mimes:zip,sql,txt',
        ]);

        $file = $request->file('backup_file');
        $extension = $file->getClientOriginalExtension();
        $tempDir = storage_path('app/temp_restore_' . time());

        try {
            if ($extension === 'zip') {
                // Handle ZIP Restore
                $zip = new ZipArchive;
                if ($zip->open($file->getRealPath()) === TRUE) {
                    File::makeDirectory($tempDir);
                    $zip->extractTo($tempDir);
                    $zip->close();

                    // 1. Restore Database
                    $sqlFile = $tempDir . '/database.sql';
                    if (File::exists($sqlFile)) {
                        $this->restoreDatabase($sqlFile);
                    } else {
                        // Try to find any .sql file
                        $sqlFiles = glob($tempDir . '/*.sql');
                        if (!empty($sqlFiles)) {
                            $this->restoreDatabase($sqlFiles[0]);
                        }
                    }

                    // 2. Restore Storage
                    $sourceStorage = $tempDir . '/storage';
                    if (File::exists($sourceStorage)) {
                        $destStorage = storage_path('app/public');
                        // Ensure destination exists
                        if (!File::exists($destStorage)) {
                            File::makeDirectory($destStorage, 0755, true);
                        }
                        File::copyDirectory($sourceStorage, $destStorage);
                    }
                } else {
                    throw new Exception("Could not open ZIP file");
                }
            } else {
                // Handle SQL Restore (Legacy support)
                $this->restoreDatabase($file->getRealPath());
            }

            // Cleanup
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }

            return back()->with('success', 'System restored successfully (Database & Files).');

        } catch (Exception $e) {
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }



    private function generateSqlDump($path)
    {
        $handle = fopen($path, 'w+');
        
        // Disable foreign key checks
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");

        $tables = DB::select('SHOW TABLES');
        $databaseName = DB::getDatabaseName();
        $tablesKey = "Tables_in_" . $databaseName;

        foreach ($tables as $table) {
            $tableName = $table->$tablesKey;

            // Get create table statement
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
            $createTableSql = $createTable[0]->{'Create Table'};

            fwrite($handle, "\n\n" . "DROP TABLE IF EXISTS `$tableName`;" . "\n");
            fwrite($handle, $createTableSql . ";\n\n");

            // Get data
            if (Schema::hasColumn($tableName, 'id')) {
                // Use chunking for tables with ID
                DB::table($tableName)->orderBy('id')->chunk(200, function ($rows) use ($handle, $tableName) {
                    $this->writeRows($handle, $tableName, $rows);
                });
            } else {
                // For tables without ID (usually small), load all
                $rows = DB::table($tableName)->get();
                $this->writeRows($handle, $tableName, $rows);
            }
        }

        // Enable foreign key checks
        fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    private function writeRows($handle, $tableName, $rows)
    {
        foreach ($rows as $row) {
            $values = array_map(function ($value) {
                if (is_null($value)) {
                    return "NULL";
                }
                return DB::connection()->getPdo()->quote($value);
            }, (array) $row);

            $sql = "INSERT INTO `$tableName` VALUES (" . implode(", ", $values) . ");\n";
            fwrite($handle, $sql);
        }
    }

    private function restoreDatabase($path)
    {
        $sql = file_get_contents($path);

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Execute SQL
        // Note: DB::unprepared might fail on very large files if memory limit is hit.
        // For production, using mysql command line tool is better, but this works for PHP-only.
        DB::unprepared($sql);

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
