<div class="space-y-6">

    {{-- Document Details Card --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm space-y-4">

        {{-- Title --}}
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white leading-snug">
            {{ $submission->card_title }}
        </h2>

        {{-- Document Type --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <x-heroicon-o-document-text class="w-4 h-4 shrink-0" />
            <span>{{ $submission->card_type }}</span>
        </div>

        {{-- Status --}}
        <div class="flex items-center gap-2">
            <span @class([
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                'bg-success-100 text-success-700 dark:bg-success-500/20 dark:text-success-400' => $submission->card_status_color === 'success',
                'bg-danger-100 text-danger-700 dark:bg-danger-500/20 dark:text-danger-400'     => $submission->card_status_color === 'danger',
                'bg-warning-100 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400' => $submission->card_status_color === 'warning',
                'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'               => $submission->card_status_color === 'gray',
            ])>
                {{ ucfirst($submission->status) }}
            </span>
        </div>

        {{-- Assigned Uploaders --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 min-w-0">
            <x-heroicon-o-user-group class="w-4 h-4 shrink-0" />
            <span class="truncate">{{ $submission->card_uploader_label }}</span>
        </div>

        {{-- Metadata Fields --}}
        @if ($submission->documentCategory?->fields->isNotEmpty())
            <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-3">
                @foreach ($submission->documentCategory->fields as $field)
                    @php $value = $submission->metadata[$field->field_key] ?? null; @endphp
                    @if (filled($value))
                        <div>
                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                                {{ $field->label }}
                            </p>
                            <p class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">
                                {{ $value }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- File Upload --}}
        @if ($canEdit)
            <div class="border-t border-gray-100 dark:border-gray-800 pt-4">
                <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-2">
                    Upload Document
                </p>
                <form wire:submit="uploadFile" class="space-y-3">
                    <div>
                        <input
                            type="file"
                            wire:model="file"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="block w-full text-sm text-gray-500 dark:text-gray-400
                                   file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                   file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700
                                   dark:file:bg-primary-500/10 dark:file:text-primary-400
                                   hover:file:bg-primary-100 dark:hover:file:bg-primary-500/20"
                        />

                        {{-- Upload progress --}}
                        <div wire:loading wire:target="file" class="mt-2 flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                            <svg class="animate-spin h-3.5 w-3.5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Uploading...
                        </div>

                        @error('file')
                            <p class="mt-1 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60 cursor-not-allowed"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors"
                    >
                        <span wire:loading.remove wire:target="uploadFile">
                            <x-heroicon-o-arrow-up-tray class="w-4 h-4 inline mr-1" />
                            Upload
                        </span>
                        <span wire:loading wire:target="uploadFile" class="flex items-center gap-1.5">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </form>
            </div>

        @elseif ($submission->file_path)
            <div class="border-t border-gray-100 dark:border-gray-800 pt-4 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <x-heroicon-o-paper-clip class="w-4 h-4 shrink-0" />
                <span>File uploaded</span>
                @if (! $submission->canEditFile())
                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">
                        (locked — approval recorded)
                    </span>
                @endif
            </div>

        @else
            <div class="border-t border-gray-100 dark:border-gray-800 pt-4 text-sm text-gray-400 dark:text-gray-500 italic">
                No file uploaded yet.
            </div>
        @endif


// Test statement
        @if ($uploadSuccess)
            <div class="mb-3 flex items-center gap-1.5 text-xs text-success-600 dark:text-success-400">
                <x-heroicon-o-check-circle class="w-3.5 h-3.5" />
                File uploaded successfully.
            </div>
        @endif

    </div>

    {{-- Status Timeline --}}
    <livewire:status-timeline
        :submission="$submission"
        :timeline-entries="$timelineEntries"
        :key="'timeline-' . $submission->id"
    />

</div>