<?php

namespace App\Http\Controllers\Student;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Answer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentQuizController extends Controller
{
    // Preview sebelum mulai
    public function show(Quiz $quiz)
    {
        $quiz->load('questions');
        return view('student.quizzes.show', compact('quiz'));
    }

    // Mulai quiz
    public function start(Quiz $quiz)
    {
        $user = Auth::user();

        $attemptNumber = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->count() + 1;

        if ($quiz->attempts_time && $attemptNumber > $quiz->attempts_time) {
            return redirect()->back()->with('error', 'You have reached the maximum attempts for this quiz.');
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'attempt_number' => $attemptNumber,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        $quiz->load('questions');

        return view('student.quizzes.start', compact('quiz', 'attempt'));
    }

    public function storeAnswer(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->latest()
            ->firstOrFail();

        $answersInput = $request->input('answer', []);

        DB::transaction(function () use ($answersInput, $attempt, $quiz) {
            $totalScore = 0;
            $maxQuestionScoreSum = $quiz->questions->sum('score');

            foreach ($quiz->questions as $question) {
                $userAnswer = $answersInput[$question->id] ?? null;
                $score = null;

                if ($quiz->auto_mark === 'yes') {
                    if ($question->type_of_answer === 'multiple_choice') {
                        $options = $question->answer ?? [];
                        $score = 0;

                        if (isset($options[$userAnswer]) && isset($options[$userAnswer]['correct'])) {
                            $score = $question->answer_point_mark;
                            $totalScore += $score;
                        }

                    } elseif ($question->type_of_answer === 'true_false') {
                        if ((string) $userAnswer === (string) $question->correct_answer) {
                            $score = $question->answer_point_mark;
                            $totalScore += $score;
                        }
                    }
                }

                Answer::updateOrCreate(
                    ['quiz_attempt_id' => $attempt->id, 'question_id' => $question->id],
                    ['answer' => $userAnswer, 'score' => $score]
                );
            }

            if ($maxQuestionScoreSum > 0 && $quiz->max_score) {
                $totalScore = round($totalScore / $maxQuestionScoreSum * $quiz->max_score, 2);
            }

            $attempt->update([
                'ended_at' => now(),
                'time_taken' => $attempt->started_at ? now()->diffInMinutes($attempt->started_at) : null,
                'score' => $totalScore,
                'status' => 'completed',
            ]);
        });

        return redirect()->route('student.quizzes.preview', $quiz->id)
            ->with('success', 'Quiz submitted successfully!');
    }


    public function preview(Quiz $quiz)
    {
        $user = auth()->user();

        $quiz->load('questions');

        $lastAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('student.quizzes.preview', compact('quiz', 'lastAttempt'));
    }

    public function viewAnswer(Quiz $quiz, QuizAttempt $attempt)
    {
        $quiz->load('questions');
        $attempt->load('answers');

        return view('student.quizzes.view-answer', compact('quiz', 'attempt'));
    }
}
