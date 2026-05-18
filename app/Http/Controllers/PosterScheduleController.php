<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use App\Jobs\PublishPosterJob;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PosterScheduleController extends Controller
{   
    use AuthorizesRequests;
    public function store(Request $request, Poster $poster)
    { 
        $this->authorize('update', $poster);

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
            'action' => ['required', 'in:publish,unpublish'],
        ]);

        $schedule = $poster->schedules()->create([
            'scheduled_at' => $validated['scheduled_at'],
            'action' => $validated['action'],
            'status' => 'pending',
        ]);

        PublishPosterJob::dispatch($schedule)
            ->delay($schedule->scheduled_at);

        return response()->json([
            'message' => 'Poster scheduled successfully.',
            'data' => $schedule
        ]);
    }
}