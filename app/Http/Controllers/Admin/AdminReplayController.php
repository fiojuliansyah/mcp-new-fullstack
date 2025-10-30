<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\Replay;
use App\Models\Schedule;
use App\Models\ReplayVideo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class AdminReplayController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $schedules = Schedule::with(['classroom', 'form', 'replays'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('classroom', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.replays.index', compact('schedules', 'search'));
    }

    public function edit(Replay $replay)
    {
        $forms = Form::all();
        $replay->load('replayVideos');
        $replay->load(['classroom', 'schedule']); 
        return view('admin.replays.edit', compact('replay', 'forms'));
    }

    public function update(Request $request, Replay $replay)
    {
        $request->validate([
            'form_id' => 'required|exists:forms,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'schedule_id' => 'required|exists:schedules,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'uploaded_video' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $replay->update([
                'form_id' => $request->form_id,
                'classroom_id' => $request->classroom_id,
                'schedule_id' => $request->schedule_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $videos = json_decode($request->uploaded_video, true);

            if (is_array($videos) && count($videos) > 0) {
                foreach ($videos as $videoPath) {
                    ReplayVideo::create([
                        'replay_id' => $replay->id,
                        'video_url' => $videoPath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.replays.edit', $replay->id)
                ->with('success', 'Replay successfully updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update replay: ' . $e->getMessage());
            return back()->with('error', 'Failed to update replay. (' . $e->getMessage() . ')');
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

    public function deleteVideo(ReplayVideo $video)
    {
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        
        try {
            if ($video->video_url) {
                if (Storage::disk($disk)->exists($video->video_url)) {
                    Storage::disk($disk)->delete($video->video_url);
                }
            }

            $video->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error("Failed to delete video ID {$video->id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'error' => 'Failed to delete video. ' . $e->getMessage()
            ], 500); 
        }
    }

    public function show($schedule_id)
    {
        $schedule = Schedule::with(['classroom', 'form'])->findOrFail($schedule_id);

        $replays = Replay::with(['replayVideos', 'schedule', 'form', 'classroom'])
            ->where('schedule_id', $schedule_id)
            ->paginate(10);

        return view('admin.replays.show', compact('schedule', 'replays'));
    }

    public function destroy(Replay $replay)
    {
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';

        try {
            DB::beginTransaction();

            foreach ($replay->replayVideos as $video) {
                if ($video->video_url && Storage::disk($disk)->exists($video->video_url)) {
                    Storage::disk($disk)->delete($video->video_url);
                }
                $video->delete();
            }

            $replay->delete();

            DB::commit();

            return back()->with('success', 'Replay successfully deleted!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete replay: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete replay. An error occurred: ' . $e->getMessage());
        }
    }
}
