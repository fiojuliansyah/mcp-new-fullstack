<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Form;
use App\Models\Subject;
use App\Models\Material;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\MaterialFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorMaterialController extends Controller
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

        return view('tutor.materials.create', compact('selectedSubject', 'user', 'classes', 'forms'));
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

    public function uploadChunk(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            $file = $request->file('file');
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $folder = 'materials';
            $originalName = $file->getClientOriginalName();
            $path = $file->storeAs($folder, $originalName, $disk);

            Log::info("Material uploaded: " . $path);

            return response()->json(['path' => $path], 200);
        } catch (\Exception $e) {
            Log::error('Material upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Upload failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'form_id' => 'required|exists:forms,id',
                'classroom_id' => 'required|exists:classrooms,id',
                'schedule_id' => 'required|exists:schedules,id',
                'uploaded_files' => 'required|array',
            ]);

            $material = Material::create([
                'form_id' => $request->form_id,
                'classroom_id' => $request->classroom_id,
                'schedule_id' => $request->schedule_id,
            ]);

            foreach ($request->uploaded_files as $fileUrl) {
                Log::debug('Processing file', ['file_url' => $fileUrl]);

                if (!empty($fileUrl)) {
                    $file = MaterialFile::create([
                        'material_id' => $material->id,
                        'file_url' => $fileUrl,
                    ]);

                    Log::debug('MaterialFile created', [
                        'id' => $file->id,
                        'file_url' => $file->file_url
                    ]);
                }
            }

            return redirect()->route('tutor.dashboard')
                ->with('success', 'Material uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Error storing material: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Failed to save material.']);
        }
    }

}
