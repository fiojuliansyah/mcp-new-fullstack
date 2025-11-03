<?php

namespace App\Http\Controllers\Parent;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $parent = Auth::user(); 
        $parent->load('children');
        
        return view('parent.dashboard', compact('parent'));
    }

    public function show($slug)
    {
        $parent = Auth::user(); 
        $parent->load('children');
        
        $selectedChild = User::where('slug', $slug)
                    ->where('parent_id', $parent->id)
                    ->firstOrFail();

        $childMetrics = $this->calculateChildMetrics($selectedChild);

        $attendances = Attendance::where('user_id', $selectedChild->id)
            ->with('schedule')
            ->get();

        return view('parent.dashboard', compact('parent', 'selectedChild', 'childMetrics'));
    }

    private function calculateChildMetrics(User $child)
    {
        $subscriptions = $child->subscriptions()
            ->with([
                'classrooms.schedules' => function($query) use ($child) {
                    $query->where('form_id', $child->form_id); 
                },
                'classrooms.schedules.attendances' => fn($q) => $q->where('user_id', $child->id)->whereIn('status', ['present', 'late']),
                
                'classrooms.schedules.quizzes.attempts' => fn($q) => $q->where('user_id', $child->id),
                'classrooms.schedules.replays.replayVideos.views' => fn($q) => $q->where('user_id', $child->id),
                
                'classrooms.user', 
                'classrooms.subject', 
            ])
            ->get();

        $totalSchedules = 0;
        $attendedCount = 0;
        $totalQuizScore = 0;
        $totalQuizCount = 0;
        $totalReplayPercentage = 0;
        $totalReplayCount = 0;
        $topicsCoveredCount = 0;
        $primarySubject = ''; 
        $tutorName = 'N/A';

        $allSchedules = collect();

        foreach ($subscriptions as $subscription) {
            foreach ($subscription->classrooms as $classroom) {
                if (empty($primarySubject)) {
                    $primarySubject = $classroom->subject->name ?? 'Primary Subject';
                    $tutorName = $classroom->user->name ?? 'Tutor N/A';
                }
                $allSchedules = $allSchedules->merge($classroom->schedules);
            }
        }

        $totalSchedules = $allSchedules->count();
        if ($totalSchedules === 0) {
            return $this->getDefaultMetrics($child->form->name ?? 'N/A');
        }

        foreach ($allSchedules as $schedule) {
            $isAttended = $schedule->attendances->isNotEmpty();
            
            $hasAnyAttempt = $schedule->quizzes->contains(fn($q) => $q->attempts->where('user_id', $child->id)->isNotEmpty());
            $hasAnyViewedReplay = $schedule->replays->contains(fn($r) => $r->replayVideos->contains(fn($v) => $v->views->where('user_id', $child->id)->isNotEmpty()));


            if ($isAttended) {
                $attendedCount++;
            }
            
            foreach ($schedule->quizzes as $quiz) {
                foreach ($quiz->attempts->where('user_id', $child->id) as $attempt) {
                    $totalQuizScore += $attempt->score ?? 0; 
                    $totalQuizCount++;
                }
            }

            foreach ($schedule->replays as $replay) {
                foreach ($replay->replayVideos as $video) {
                    $videoViews = $video->views->where('user_id', $child->id);
                    $maxWatchedPosition = $videoViews->max('duration_watched') ?? 0;
                    $videoDuration = $video->duration ?? 1; 
                    
                    if ($videoDuration > 0) {
                         $watchPercentage = min(100, round(($maxWatchedPosition / $videoDuration) * 100));
                         $totalReplayPercentage += $watchPercentage;
                         $totalReplayCount++;
                    }
                }
            }
            
            if ($isAttended || $hasAnyAttempt || $hasAnyViewedReplay) {
                $topicsCoveredCount++;
            }
        }

        $absentCount = $totalSchedules - $attendedCount;

        $avgQuizScore = $totalQuizCount > 0 ? round($totalQuizScore / $totalQuizCount) : 0;
        $avgReplayWatch = $totalReplayCount > 0 ? round($totalReplayPercentage / $totalReplayCount) : 0;
        $attendanceRate = $totalSchedules > 0 ? round(($attendedCount / $totalSchedules) * 100) : 0;
        $overallProgress = $totalSchedules > 0 ? round(($topicsCoveredCount / $totalSchedules) * 100) : 0;


        return [
            'subject_name' => $primarySubject, 
            'form' => $child->form->name ?? 'N/A',
            'tutor_name' => $tutorName, 
            'total_schedules' => $totalSchedules,
            'topics_covered' => $topicsCoveredCount,
            'overall_progress' => $overallProgress,
            'avg_quiz_score' => $avgQuizScore,
            'avg_replay_watch' => $avgReplayWatch,
            'attendance_rate' => $attendanceRate,
            'attendance_count' => $attendedCount,
            'absent_count' => $absentCount,
        ];
    }

    private function getDefaultMetrics($formName) {
        return [
            'subject_name' => 'No Active Subject', 
            'form' => $formName,
            'tutor_name' => 'N/A',
            'total_schedules' => 0,
            'topics_covered' => 0,
            'overall_progress' => 0,
            'avg_quiz_score' => 0,
            'avg_replay_watch' => 0,
            'attendance_rate' => 0,
            'attendance_count' => 0,
            'absent_count' => 0,
        ];
    }

}
