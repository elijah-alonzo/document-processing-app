<div class="space-y-3">

    {{-- Loading state --}}
    <div wire:loading class="space-y-3">
        @foreach (range(1, 3) as $i)
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm animate-pulse space-y-3">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-1/3"></div>
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-1/4"></div>
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-1/2"></div>
            </div>
        @endforeach
    </div>

    {{-- Feed --}}
    <div wire:loading.remove>
        @forelse ($submissions as $submission)
            @include('SubmissionCard', ['submission' => $submission])
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <x-heroicon-o-inbox class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" />
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No submissions yet</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    Submissions assigned to you or awaiting your action will appear here.
                </p>
            </div>
        @endforelse
    </div>

</div>