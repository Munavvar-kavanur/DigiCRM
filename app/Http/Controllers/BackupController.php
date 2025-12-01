<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Exception;

class BackupController extends Controller
{
    public function download()
    {
        set_time_limit(0);
        try {
            $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
            $handle = fopen('php://temp', 'r+');

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
                $rows = DB::table($tableName)->get();
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

            // Enable foreign key checks
            fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=1;\n");

            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);

            return Response::make($content, 200, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);

        } catch (Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        set_time_limit(0);
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt',
        ]);

        try {
            $file = $request->file('backup_file');
            $sql = file_get_contents($file->getRealPath());

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Split SQL into statements
            // This is a simple split, might need more robust parsing for complex SQL
            // But for our generated backup, it should be fine if we ensure statements end with ;
            // However, DB::unprepared can execute multiple statements
            
            DB::unprepared($sql);

            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return back()->with('success', 'Database restored successfully.');

        } catch (Exception $e) {
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }
}
