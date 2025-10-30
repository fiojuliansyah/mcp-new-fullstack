<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Form;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Schedule;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorQuizController extends Controller
{
    public function create($slug)
    {
        $user = Auth::user();

        $selectedSubject = Subject::where('slug', $slug)->firstOrFail();

        $classes = Classroom::where('user_id', $user->id)
            ->where('subject_id', $selectedSubject->id)
            ->get();

        $forms = Form::all();

        return view('tutor.quizzes.create', compact('selectedSubject', 'user', 'classes', 'forms'));
    }

    public function getClassrooms($formId)
    {
        $userId = Auth::id();

        $classrooms = Classroom::where('user_id', $userId)
            ->whereHas('schedules', function ($query) use ($formId) {
                $query->where('form_id', $formId);
            })
            ->select('id', 'name')
            ->get();

        return response()->json($classrooms);
    }

    public function getTopics($classroomId)
    {
        $topics = Schedule::where('classroom_id', $classroomId)
            ->select('id', 'topic AS topic_name')
            ->get();

        return response()->json($topics);
    }

    public function uploadMedia(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            $file = $request->file('file');
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $folder = 'quiz_media/' . Auth::id();
            $path = $file->store($folder, $disk);

            Log::info("Quiz media uploaded: " . $path);

            return response()->json(['path' => $path], 200);
        } catch (\Exception $e) {
            Log::error('Quiz media upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Upload failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'form_id' => 'required|exists:forms,id',
                'classroom_id' => 'required|exists:classrooms,id',
                'schedule_id' => 'required|exists:schedules,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'estimated_time' => 'nullable|integer',
                'attempts_time' => 'nullable|integer',
                'max_score' => 'nullable|integer',
                'total_question' => 'nullable|integer',
                'auto_mark' => 'required|in:yes,no',
            ]);

            $quiz = Quiz::create([
                'form_id' => $validated['form_id'],
                'classroom_id' => $validated['classroom_id'],
                'schedule_id' => $validated['schedule_id'],
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'estimated_time' => $validated['estimated_time'] ?? null,
                'attempts_time' => $validated['attempts_time'] ?? null,
                'max_score' => $validated['max_score'] ?? null,
                'total_question' => $validated['total_question'] ?? null,
                'auto_mark' => $validated['auto_mark'] ?? 'yes',
            ]);

            Log::info('Quiz created', ['quiz_id' => $quiz->id]);

            return redirect()
                    ->route('tutor.quiz.question.create', $quiz->id)
                    ->with('success', 'Quiz created successfully! Now add your questions.');
        } catch (\Exception $e) {
            Log::error('Error storing quiz: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return back()->withErrors(['error' => 'Failed to save quiz.']);
        }
    }

    public function createQuestion($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with('classroom', 'form', 'schedule')->findOrFail($quizId);
        return view('tutor.quizzes.questions.create', compact('quiz','user'));
    }

    public function storeQuestion(Request $request, $quizId)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'questions.*.point' => 'nullable|integer',
            'questions.*.answer' => 'nullable|array',
            'questions.*.correct' => 'nullable|string',
            'questions.*.media' => 'nullable|file',
        ]);

        foreach ($request->questions as $index => $q) {
            $mediaPath = null;

            if ($request->hasFile("questions.$index.media")) {
                $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
                $folder = 'quiz_media/' . $quizId;
                $mediaPath = $request->file("questions.$index.media")->store($folder, $disk);
            }

            Question::create([
                'quiz_id' => $quizId,
                'question' => $q['text'],
                'media_url' => $mediaPath,
                'type_of_answer' => $q['type'],
                'answer_point_mark' => $q['point'] ?? 1,
                'answer' => $q['answer'] ?? null,
                'correct_answer' => $q['correct'] ?? null,
            ]);
        }


        return redirect()->route('tutor.quiz.question.preview', $quizId)->with('success', 'Questions added successfully!');
    }

    public function preview($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['classroom', 'form', 'schedule', 'questions'])->findOrFail($quizId);
        return view('tutor.quizzes.preview', compact('quiz','user'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'publish_date' => 'required|date',
            'status' => 'required|in:draft,published',
        ]);

        $quiz->update([
            'publish_date' => $validated['publish_date'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Quiz updated successfully.');
    }

}
