<?php

namespace App\Features\DocumentSubmissions\Livewire;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\DocumentSubmissions\Services\SubmissionTimelineBuilder;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentDetailsPanel extends Component
{
    use WithFileUploads;

    public DocumentSubmission $submission;

    public $file = null;

    public function uploadFile(): void
    {
        abort_unless($this->canEdit(), 403);

        $this->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $path = $this->file->store('documents', 'public');

        $this->submission->update(['file_path' => $path]);

        $this->file = null;
        $this->submission->refresh();
    }

    protected function canEdit(): bool
    {
        return $this->submission->isUploaderOrCreator(auth()->user())
            && $this->submission->canEditFile();
    }

    public function render(SubmissionTimelineBuilder $timelineBuilder): View
    {
        return view('DocumentDetailsPanel', [
            'canEdit' => $this->canEdit(),
            'timelineEntries' => $timelineBuilder->build($this->submission),
        ]);
    }
}