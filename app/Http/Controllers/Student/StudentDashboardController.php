<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userFormId = $user->form_id;

        $subscriptions = $user->subscriptions()
            ->with([
                'classrooms.schedules' => function($query) use ($userFormId) {
                    $query->where('form_id', $userFormId);
                },
                'classrooms.schedules.attendances' => function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->where('status', 'Completed'); 
                },
                'classrooms.schedules.quizzes.attempts' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'classrooms.schedules.replays.replayVideos.views' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ])
            ->get();

        $calculatedMetrics = [];

        foreach ($subscriptions as $subscription) {
            $subjectName = $subscription->classrooms->first()->subject->name ?? 'Undefined Subject'; 
            $schedules = collect();

            foreach ($subscription->classrooms as $classroom) {
                $schedules = $schedules->merge($classroom->schedules);
            }

            $totalSchedules = $schedules->count();
            if ($totalSchedules === 0) continue; 

            $attendedSchedulesCount = 0;
            $totalQuizScore = 0;
            $totalQuizCount = 0;
            $totalReplayPercentage = 0;
            $totalReplayCount = 0;
            $topicsCoveredCount = 0;
            
            foreach ($schedules as $schedule) {
                if ($schedule->attendances->isNotEmpty()) {
                    $attendedSchedulesCount++;
                }

                foreach ($schedule->quizzes as $quiz) {
                    foreach ($quiz->attempts as $attempt) {
                        $totalQuizScore += $attempt->score ?? 0;
                        $totalQuizCount++;
                    }
                }

                foreach ($schedule->replays as $replay) {
                    foreach ($replay->replayVideos as $video) {
                        $maxWatchedPosition = $video->views->max('duration_watched') ?? 0;
                        $videoDuration = $video->duration ?? 1;
                        
                        if ($videoDuration > 0) {
                             $watchPercentage = min(100, round(($maxWatchedPosition / $videoDuration) * 100));
                             $totalReplayPercentage += $watchPercentage;
                             $totalReplayCount++;
                        }
                    }
                }
                
                $isTopicCovered = $schedule->attendances->isNotEmpty() 
                                    || $schedule->quizzes->contains(fn($q) => $q->attempts->isNotEmpty())
                                    || $schedule->replays->contains(fn($r) => $r->replayVideos->contains(fn($v) => $v->views->isNotEmpty()));
                                    
                if ($isTopicCovered) {
                    $topicsCoveredCount++;
                }
            }
            
            $avgQuizScore = $totalQuizCount > 0 ? round($totalQuizScore / $totalQuizCount) : 0;
            $avgReplayWatch = $totalReplayCount > 0 ? round($totalReplayPercentage / $totalReplayCount) : 0;
            $attendanceRate = $totalSchedules > 0 ? round(($attendedSchedulesCount / $totalSchedules) * 100) : 0;
            $overallProgress = $totalSchedules > 0 ? round(($topicsCoveredCount / $totalSchedules) * 100) : 0;


            $calculatedMetrics[$subscription->id] = [
                'subject' => $subjectName, 
                'form' => $userFormId,
                'total_schedules' => $totalSchedules,
                'topics_covered' => $topicsCoveredCount,
                'overall_progress' => $overallProgress,
                'avg_quiz_score' => $avgQuizScore,
                'avg_replay_watch' => $avgReplayWatch,
                'attendance_rate' => $attendanceRate,
            ];
        }
        
        $mockMetrics = $calculatedMetrics ?: [
            'generic' => [
                'subject' => 'Data Not Found',
                'form' => $userFormId,
                'total_schedules' => 0,
                'topics_covered' => 0,
                'overall_progress' => 0,
                'avg_quiz_score' => 0,
                'avg_replay_watch' => 0,
                'attendance_rate' => 0,
            ]
        ];

        return view('student.dashboard', compact('subscriptions', 'mockMetrics'));
    }
}
