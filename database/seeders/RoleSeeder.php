<?php

namespace Database\Seeders;

use App\Features\Roles\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->roles() as $name => $description) {
            Role::updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $description]
            );
        }
    }

    /**
     * @return array<string, string>
     */
    private function roles(): array
    {
        return [
            'Employee' => 'Rank-and-file staff who submit requests for approval.',
            'Supervisor' => 'Immediate supervisor who endorses a subordinate\'s request.',
            'Department Head' => 'Head of department who reviews requests originating from their department.',
            'HR Manager' => 'Human resources manager who approves leave and personnel-related requests.',
            'Finance' => 'Finance staff who review requisitions and expenses for budget compliance.',
            'Finance Manager' => 'Finance manager who gives final approval on financial requests.',
            'Accounting' => 'Accounting staff who verify expense claims before approval.',
            'Director' => 'Director who gives final approval on travel and high-level requests.',
        ];
    }
}