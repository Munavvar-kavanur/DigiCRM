<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeType;
use App\Models\PayrollType;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $employeeTypes = [
            ['name' => 'Permanent / Full-Time', 'description' => 'Long-term, full benefits'],
            ['name' => 'Temporary / Contract', 'description' => 'Fixed period, limited benefits'],
            ['name' => 'Part-Time', 'description' => 'Fewer hours, limited benefits'],
            ['name' => 'Intern / Trainee', 'description' => 'Learning role, stipend'],
            ['name' => 'Probationary', 'description' => 'Trial period before permanent'],
            ['name' => 'Freelancer / Consultant', 'description' => 'Project-based, independent'],
            ['name' => 'Seasonal', 'description' => 'Hired for specific seasons or projects'],
        ];

        foreach ($employeeTypes as $type) {
            EmployeeType::firstOrCreate(['name' => $type['name']], $type);
        }

        $payrollTypes = [
            ['name' => 'Hourly', 'description' => 'Paid based on hours worked'],
            ['name' => 'Monthly Salary', 'description' => 'Fixed monthly pay'],
            ['name' => 'Project-Based / Freelance', 'description' => 'Paid per project completed'],
            ['name' => 'Commission / Incentive', 'description' => 'Bonus based on performance or sales'],
            ['name' => 'Stipend', 'description' => 'Fixed small payment (for interns or trainees)'],
            ['name' => 'Overtime Pay', 'description' => 'Extra pay for hours beyond regular work'],
        ];

        foreach ($payrollTypes as $type) {
            PayrollType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
