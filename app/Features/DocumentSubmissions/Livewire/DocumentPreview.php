<?php

namespace App\Features\DocumentSubmissions\Livewire;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DocumentPreview extends Component
{
    public DocumentSubmission $submission;

    public function getFileUrl(): ?string
    {
        if (! $this->submission->file_path) {
            return null;
        }

        return asset('storage/' . $this->submission->file_path);
    }

    public function getFileExtension(): ?string
    {
        if (! $this->submission->file_path) {
            return null;
        }

        return strtolower(pathinfo($this->submission->file_path, PATHINFO_EXTENSION));
    }

    public function getPreviewType(): string
    {
        return match ($this->getFileExtension()) {
            'pdf'        => 'pdf',
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'image',
            null         => 'none',
            default      => 'download',
        };
    }

    public function render(): View
    {
        return view('DocumentPreview', [
            'fileUrl'     => $this->getFileUrl(),
            'previewType' => $this->getPreviewType(),
            'extension'   => $this->getFileExtension(),
        ]);
    }
}