<?php

namespace Database\Seeders;

use App\Features\DocumentProcesses\Models\DocumentProcess;
use App\Features\DocumentProcesses\Models\DocumentProcessStage;
use App\Features\Roles\Models\Role;
use Illuminate\Database\Seeder;

class DocumentProcessSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->processes() as $definition) {
            $process = DocumentProcess::updateOrCreate(
                ['name' => $definition['name']],
                ['description' => $definition['description']]
            );

            foreach ($definition['stages'] as $order => $stage) {
                $role = Role::findOrCreate($stage['role'], 'web');

                DocumentProcessStage::updateOrCreate(
                    [
                        'document_process_id' => $process->id,
                        'stage_order' => $order + 1,
                    ],
                    [
                        'stage_name' => $stage['stage_name'],
                        'assigned_role_id' => $role->id,
                        'action_label' => $stage['action_label'],
                        'approve_status' => $stage['approve_status'],
                        'reject_status' => $stage['reject_status'],
                    ]
                );
            }
        }
    }

    /**
     * @return array<int, array{
     *     name: string,
     *     description: string,
     *     stages: array<int, array{stage_name: string, role: string, action_label: string, approve_status: string, reject_status: string}>
     * }>
     */
    private function processes(): array
    {
        return [
            [
                'name' => 'Leave Request Approval',
                'description' => 'Approval flow for employee leave applications, from immediate supervisor up to HR.',
                'stages' => [
                    [
                        'stage_name' => 'Supervisor Endorsement',
                        'role' => 'Supervisor',
                        'action_label' => 'Endorse',
                        'approve_status' => 'endorsed',
                        'reject_status' => 'returned',
                    ],
                    [
                        'stage_name' => 'HR Approval',
                        'role' => 'HR Manager',
                        'action_label' => 'Approve',
                        'approve_status' => 'approved',
                        'reject_status' => 'rejected',
                    ],
                ],
            ],
            [
                'name' => 'Purchase Requisition Approval',
                'description' => 'Approval flow for procuring goods or services, from department head through finance.',
                'stages' => [
                    [
                        'stage_name' => 'Department Head Review',
                        'role' => 'Department Head',
                        'action_label' => 'Endorse',
                        'approve_status' => 'endorsed',
                        'reject_status' => 'returned',
                    ],
                    [
                        'stage_name' => 'Finance Review',
                        'role' => 'Finance',
                        'action_label' => 'Approve',
                        'approve_status' => 'approved',
                        'reject_status' => 'rejected',
                    ],
                ],
            ],
            [
                'name' => 'Travel Order Approval',
                'description' => 'Approval flow for official business trips, from supervisor up to director.',
                'stages' => [
                    [
                        'stage_name' => 'Supervisor Endorsement',
                        'role' => 'Supervisor',
                        'action_label' => 'Endorse',
                        'approve_status' => 'endorsed',
                        'reject_status' => 'returned',
                    ],
                    [
                        'stage_name' => 'Director Approval',
                        'role' => 'Director',
                        'action_label' => 'Approve',
                        'approve_status' => 'approved',
                        'reject_status' => 'rejected',
                    ],
                ],
            ],
            [
                'name' => 'Expense Reimbursement Approval',
                'description' => 'Approval flow for reimbursing out-of-pocket business expenses, from accounting through finance manager.',
                'stages' => [
                    [
                        'stage_name' => 'Accounting Verification',
                        'role' => 'Accounting',
                        'action_label' => 'Verify',
                        'approve_status' => 'verified',
                        'reject_status' => 'returned',
                    ],
                    [
                        'stage_name' => 'Finance Manager Approval',
                        'role' => 'Finance Manager',
                        'action_label' => 'Approve',
                        'approve_status' => 'approved',
                        'reject_status' => 'rejected',
                    ],
                ],
            ],
        ];
    }
}