<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\Material;
use App\Models\Schedule;
use App\Models\MaterialFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Illuminate\Http\UploadedFile;

class AdminMaterialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $schedules = Schedule::with(['classroom', 'form', 'materials'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('classroom', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.materials.index', compact('schedules', 'search'));
    }

    public function edit(Material $material)
    {
        $forms = Form::all();
        $material->load(['materialFiles', 'classroom', 'schedule']); 
        return view('admin.materials.edit', compact('material', 'forms'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'form_id' => 'required|exists:forms,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'schedule_id' => 'required|exists:schedules,id',
            'uploaded_file' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $material->update([
                'form_id' => $request->form_id,
                'classroom_id' => $request->classroom_id,
                'schedule_id' => $request->schedule_id,
            ]);

            $files = json_decode($request->uploaded_file, true);

            if (is_array($files) && count($files) > 0) {
                foreach ($files as $filePath) {
                    MaterialFile::create([
                        'material_id' => $material->id,
                        'file_url' => $filePath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.materials.edit', $material->id)
                ->with('success', 'Material successfully updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update material: ' . $e->getMessage());
            return back()->with('error', 'Failed to update material. (' . $e->getMessage() . ')');
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
            $folder = 'materials';
            $filename = $file->getClientOriginalName();

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
    
    public function deleteFile(MaterialFile $file)
    {
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        
        try {
            if ($file->file_url) {
                if (Storage::disk($disk)->exists($file->file_url)) {
                    Storage::disk($disk)->delete($file->file_url);
                }
            }
            
            $file->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error("Failed to delete material file ID {$file->id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'error' => 'Failed to delete file from storage.'
            ], 500); 
        }
    }

    public function show($schedule_id)
    {
        $schedule = Schedule::with(['classroom', 'form'])->findOrFail($schedule_id);

        $materials = Material::with(['materialFiles', 'schedule', 'form', 'classroom'])
            ->where('schedule_id', $schedule_id)
            ->paginate(10);

        return view('admin.materials.show', compact('schedule', 'materials'));
    }
    
    public function destroy(Material $material)
    {
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';

        try {
            DB::beginTransaction();

            foreach ($material->materialFiles as $file) {
                if ($file->file_url && Storage::disk($disk)->exists($file->file_url)) {
                    Storage::disk($disk)->delete($file->file_url);
                }
                $file->delete();
            }

            $material->delete();

            DB::commit();

            return back()->with('success', 'Material successfully deleted!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete material: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete material. An error occurred: ' . $e->getMessage());
        }
    }
}