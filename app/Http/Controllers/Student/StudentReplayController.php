<?php

namespace App\Http\Controllers\Student;

use App\Models\Replay;
use App\Models\ReplayVideo;
use App\Models\ReplayView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentReplayController extends Controller
{
    public function show(Replay $replay, Request $request)
    {
        $replay->load('replayVideos');
        $requestedVideoId = $request->query('video_id');
        $currentVideo = $replay->replayVideos->firstWhere('id', $requestedVideoId) ?? $replay->replayVideos->first();

        $resumePosition = 0;
        if ($currentVideo && auth()->check()) {
            $resumePosition = ReplayView::where('replay_video_id', $currentVideo->id)
                ->where('user_id', auth()->id())
                ->orderByDesc('id')
                ->value('last_position') ?? 0;
        }

        return view('student.replays.show', compact('replay', 'requestedVideoId', 'currentVideo', 'resumePosition'));
    }


    public function trackProgress(Request $request, ReplayVideo $replay_video)
    {
        $userId = auth()->id();
        if (!$userId) {
            \Log::warning('Track progress attempted without login');
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $currentTime = (int) $request->input('current_time', 0);
        $duration = (int) $request->input('duration', 0);

        $view = ReplayView::where('replay_video_id', $replay_video->id)
            ->where('user_id', $userId)
            ->whereNull('ended_at')
            ->orderByDesc('id')
            ->first();

        if (!$view) {
            $lastNumber = ReplayView::where('replay_video_id', $replay_video->id)
                ->where('user_id', $userId)
                ->max('view_number') ?? 0;

            \Log::info('Creating new replay view', [
                'user_id' => $userId,
                'video_id' => $replay_video->id,
                'view_number' => $lastNumber + 1,
            ]);

            $view = ReplayView::create([
                'replay_video_id' => $replay_video->id,
                'user_id' => $userId,
                'view_number' => $lastNumber + 1,
                'started_at' => now(),
                'last_position' => 0,
                'duration_watched' => 0,
            ]);
        }

        $view->last_position = $currentTime;
        $view->duration_watched = max($view->duration_watched ?? 0, $currentTime);

        if ($currentTime >= ($duration - 5)) {
            $view->ended_at = now();
        }

        $view->save();

        \Log::info('Replay progress updated', [
            'user_id' => $userId,
            'video_id' => $replay_video->id,
            'current_time' => $currentTime,
            'duration' => $duration,
            'view_id' => $view->id,
            'last_position' => $view->last_position,
        ]);

        return response()->json(['status' => 'ok']);
    }
}
