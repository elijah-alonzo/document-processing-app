<?php

namespace Database\Seeders;

use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\DocumentProcesses\Models\DocumentProcess;
use App\Features\Roles\Models\Role;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->categories() as $definition) {
            $process = DocumentProcess::where('name', $definition['process'])->first();

            if (! $process) {
                $this->command?->warn("Skipping category \"{$definition['name']}\": process \"{$definition['process']}\" not found. Run DocumentProcessSeeder first.");

                continue;
            }

            foreach ([
                ...$definition['allowed_creator_roles'],
                ...$definition['allowed_uploader_roles'],
            ] as $roleName) {
                Role::findOrCreate($roleName, 'web');
            }

            $category = DocumentCategory::updateOrCreate(
                ['name' => $definition['name']],
                [
                    'description' => $definition['description'],
                    'document_process_id' => $process->id,
                    'is_active' => true,
                    'allowed_creator_roles' => $definition['allowed_creator_roles'],
                    'allowed_uploader_roles' => $definition['allowed_uploader_roles'],
                ]
            );

            foreach ($definition['fields'] as $field) {
                $category->fields()->updateOrCreate(
                    ['field_key' => $field['field_key']],
                    [
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'options' => $field['options'] ?? null,
                        'help_text' => $field['help_text'] ?? null,
                        'is_required' => $field['is_required'] ?? false,
                        'sort_order' => $field['sort_order'],
                    ]
                );
            }
        }
    }

    /**
     * @return array<int, array{
     *     name: string,
     *     description: string,
     *     process: string,
     *     allowed_creator_roles: array<int, string>,
     *     allowed_uploader_roles: array<int, string>,
     *     fields: array<int, array{field_key: string, label: string, type: string, options?: array|null, help_text?: string|null, is_required?: bool, sort_order: int}>
     * }>
     */
    private function categories(): array
    {
        return [
            [
                'name' => 'Leave Request',
                'description' => 'Application for vacation, sick, or emergency leave.',
                'process' => 'Leave Request Approval',
                'allowed_creator_roles' => ['Employee'],
                'allowed_uploader_roles' => ['Employee'],
                'fields' => [
                    [
                        'field_key' => 'leave_type',
                        'label' => 'Leave Type',
                        'type' => 'select',
                        'options' => [
                            'vacation' => 'Vacation Leave',
                            'sick' => 'Sick Leave',
                            'emergency' => 'Emergency Leave',
                            'unpaid' => 'Unpaid Leave',
                        ],
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'field_key' => 'start_date',
                        'label' => 'Start Date',
                        'type' => 'date',
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'field_key' => 'end_date',
                        'label' => 'End Date',
                        'type' => 'date',
                        'is_required' => true,
                        'sort_order' => 3,
                    ],
                    [
                        'field_key' => 'reason',
                        'label' => 'Reason',
                        'type' => 'textarea',
                        'help_text' => 'Briefly explain the reason for the leave.',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ],
            ],
            [
                'name' => 'Purchase Requisition',
                'description' => 'Request to purchase supplies, equipment, or services.',
                'process' => 'Purchase Requisition Approval',
                'allowed_creator_roles' => ['Employee', 'Department Head'],
                'allowed_uploader_roles' => ['Employee'],
                'fields' => [
                    [
                        'field_key' => 'item_description',
                        'label' => 'Item / Service Description',
                        'type' => 'textarea',
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'field_key' => 'quantity',
                        'label' => 'Quantity',
                        'type' => 'number',
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'field_key' => 'estimated_cost',
                        'label' => 'Estimated Cost',
                        'type' => 'number',
                        'is_required' => true,
                        'sort_order' => 3,
                    ],
                    [
                        'field_key' => 'supplier',
                        'label' => 'Preferred Supplier',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ],
            ],
            [
                'name' => 'Travel Order',
                'description' => 'Request for authorization to travel on official business.',
                'process' => 'Travel Order Approval',
                'allowed_creator_roles' => ['Employee'],
                'allowed_uploader_roles' => ['Employee'],
                'fields' => [
                    [
                        'field_key' => 'destination',
                        'label' => 'Destination',
                        'type' => 'text',
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'field_key' => 'purpose',
                        'label' => 'Purpose of Travel',
                        'type' => 'textarea',
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'field_key' => 'departure_date',
                        'label' => 'Departure Date',
                        'type' => 'date',
                        'is_required' => true,
                        'sort_order' => 3,
                    ],
                    [
                        'field_key' => 'return_date',
                        'label' => 'Return Date',
                        'type' => 'date',
                        'is_required' => true,
                        'sort_order' => 4,
                    ],
                    [
                        'field_key' => 'needs_cash_advance',
                        'label' => 'Requires Cash Advance',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 5,
                    ],
                ],
            ],
            [
                'name' => 'Expense Reimbursement',
                'description' => 'Claim for reimbursement of business-related out-of-pocket expenses.',
                'process' => 'Expense Reimbursement Approval',
                'allowed_creator_roles' => ['Employee'],
                'allowed_uploader_roles' => ['Employee'],
                'fields' => [
                    [
                        'field_key' => 'expense_category',
                        'label' => 'Expense Category',
                        'type' => 'select',
                        'options' => [
                            'transportation' => 'Transportation',
                            'meals' => 'Meals',
                            'accommodation' => 'Accommodation',
                            'supplies' => 'Supplies',
                            'other' => 'Other',
                        ],
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'field_key' => 'amount',
                        'label' => 'Amount',
                        'type' => 'number',
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'field_key' => 'expense_date',
                        'label' => 'Date Incurred',
                        'type' => 'date',
                        'is_required' => true,
                        'sort_order' => 3,
                    ],
                    [
                        'field_key' => 'notes',
                        'label' => 'Notes',
                        'type' => 'textarea',
                        'help_text' => 'Attach the receipt as the submission file.',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ],
            ],
        ];
    }
}