<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Form;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Replay;
use App\Models\ReplayVideo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class TutorReplayVideoController extends Controller
{
    public function create($slug)
    {
        $user = Auth::user();

        $selectedSubject = Subject::where('slug', $slug)
            ->firstOrFail();

        $classes = Classroom::where('user_id', $user->id)
                            ->where('subject_id', $selectedSubject->id)
                            ->get();

        $forms = Form::all();

        return view('tutor.replays.create', compact('selectedSubject', 'user', 'classes', 'forms'));
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

    public function store(Request $request)
    {
        $request->validate([
            'form_id' => 'required|exists:forms,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'schedule_id' => 'required|exists:schedules,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'uploaded_video' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $replay = Replay::create([
                'form_id' => $request->form_id,
                'classroom_id' => $request->classroom_id,
                'schedule_id' => $request->schedule_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $videos = json_decode($request->uploaded_video, true);

            if (is_array($videos)) {
                foreach ($videos as $videoPath) {
                    ReplayVideo::create([
                        'replay_id' => $replay->id,
                        'video_url' => $videoPath,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('tutor.dashboard')->with('success', 'Video replay successfully uploaded!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to store replay: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload video replay. Please try again.');
        }
    }

    public function uploadChunk(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            $file = $save->getFile();
            $user = auth()->user();

            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';

            $folder = 'replays';
            $filename = uniqid() . '_' . $file->getClientOriginalName();

            $path = Storage::disk($disk)->putFileAs($folder, $file, $filename);

            unlink($file->getPathname());

            return response()->json([
                'path' => $path,
                'url'  => Storage::disk($disk)->url($path),
                'disk' => $disk
            ]);
        }

        $handler = $save->handler();
        return response()->json([
            'done' => $handler->getPercentageDone(),
        ]);
    }
}