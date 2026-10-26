<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => 'admin@task.local'],
            ['name' => 'Alex Morgan', 'password' => 'Password123!'],
        );
        $admin->syncRoles('admin');

        $employee = User::updateOrCreate(
            ['email' => 'jordan@task.local'],
            ['name' => 'Jordan Lee', 'password' => 'Password123!'],
        );
        $employee->syncRoles('employee');

        $secondEmployee = User::updateOrCreate(
            ['email' => 'sam@task.local'],
            ['name' => 'Sam Rivera', 'password' => 'Password123!'],
        );
        $secondEmployee->syncRoles('employee');

        $project = Project::firstOrCreate(
            ['name' => 'Website refresh'],
            ['description' => 'A sample project for the team.', 'created_by' => $admin->id],
        );

        Task::firstOrCreate(
            ['title' => 'Review the current homepage'],
            [
                'project_id' => $project->id,
                'assigned_to' => $employee->id,
                'created_by' => $admin->id,
                'description' => 'List the main improvements needed for the next release.',
                'priority' => 'high',
                'status' => 'in_progress',
                'due_date' => now()->addDays(5)->toDateString(),
            ],
        );

        Task::firstOrCreate(
            ['title' => 'Prepare updated product copy'],
            [
                'project_id' => $project->id,
                'assigned_to' => $secondEmployee->id,
                'created_by' => $admin->id,
                'description' => 'Draft concise copy for the key product pages.',
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => now()->addDays(10)->toDateString(),
            ],
        );
    }
}
