<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Pages\SubmissionView\Actions\SubmissionViewActions;
use App\Features\DocumentSubmissions\Livewire\DocumentDetailsPanel;
use App\Features\DocumentSubmissions\Livewire\DocumentPreview;
use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class SubmissionView extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = '';

    public DocumentSubmission $submission;

    public function mount(int $record): void
    {
        $this->submission = DocumentSubmission::with([
            'documentCategory.fields',
            'documentProcess.stages.role',
            'currentProcessStage.role',
            'createdBy',
            'uploaders',
            'approvals.documentProcessStage',
            'approvals.user',
        ])->findOrFail($record);

        abort_unless(
            $this->submission->isUploaderOrCreator(auth()->user())
                || $this->submission->currentProcessStage?->isAssignableTo(auth()->user()),
            403
        );
    }

    public function getTitle(): string
    {
        return $this->submission->card_title;
    }

    public function getHeaderActions(): array
    {
        return SubmissionViewActions::configure($this->submission);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Livewire::make(DocumentDetailsPanel::class, ['submission' => $this->submission])
                    ->columnSpan(1),

                Livewire::make(DocumentPreview::class, ['submission' => $this->submission])
                    ->columnSpan(1),
            ]);
    }

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
        ];
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return 'submissions/{record}';
    }
}