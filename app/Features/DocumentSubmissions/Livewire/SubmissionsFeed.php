<?php

namespace App\Features\DocumentSubmissions\Livewire;

use App\Features\DocumentSubmissions\Services\UserDashboardFeedResolver;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SubmissionsFeed extends Component
{
    public function render(UserDashboardFeedResolver $resolver): View
    {
        $submissions = $resolver->forUser(auth()->user());

        return view('SubmissionsFeed', [
            'submissions' => $submissions,
        ]);
    }
}