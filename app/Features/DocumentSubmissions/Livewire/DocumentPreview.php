<?php

namespace App\Features\DocumentSubmissions\Livewire;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DocumentPreview extends Component
{
    public DocumentSubmission $submission;

    protected $listeners = [
        'document-uploaded' => '$refresh',
    ];

    public function render(): View
    {
        $path = $this->submission->file_path;

        // Extract metadata safely if a file path exists
        $fileUrl = $path ? Storage::disk('public')->url($path) : null;
        $extension = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null;

        return view('DocumentPreview', [
            'fileUrl' => $fileUrl,
            'extension' => $extension,     // Fixes Undefined variable $extension on line 63
            'previewType' => $extension,   // Satisfies the $previewType conditional on line 21
        ]);
    }
}
