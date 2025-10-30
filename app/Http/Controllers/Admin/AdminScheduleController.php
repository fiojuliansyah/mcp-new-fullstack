<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Form;
use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Classroom;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class AdminScheduleController extends Controller
{
    private function getZoomAccessToken(): string
    {
        if (Cache::has('zoom_access_token')) {
            return Cache::get('zoom_access_token');
        }

        $response = Http::asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => env('ZOOM_ACCOUNT_ID'),
            'client_id' => env('ZOOM_CLIENT_ID'),
            'client_secret' => env('ZOOM_CLIENT_SECRET'),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Gagal mendapatkan Zoom Access Token. Periksa kredensial Anda di file .env.');
        }

        $data = $response->json();
        $accessToken = $data['access_token'];

        Cache::put('zoom_access_token', $accessToken, now()->addMinutes(59));

        return $accessToken;
    }

    public function edit(string $id)
    {
        $schedule = Schedule::with('classroom.subject', 'form')->findOrFail($id);
        
        $user = Auth::user();
        $selectedSubject = $schedule->classroom->subject;

        $classes = Classroom::where('user_id', $user->id)
                            ->where('subject_id', $selectedSubject->id)
                            ->get();
        
        $forms = Form::all();

        return view('admin.schedules.edit', compact('schedule', 'user', 'classes', 'forms', 'selectedSubject'));
    }

    public function update(Request $request, string $id)
    {
        $schedule = Schedule::findOrFail($id);

        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'form_id' => 'required|exists:forms,id',
            'topic' => 'required|string|max:255',
            'agenda'  => 'required|string|max:1000',
            'time' => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);

        try {
            $accessToken = $this->getZoomAccessToken();
            
            $defaultBooleanSettings = [
                'host_video'        => false,
                'participant_video' => false,
                'join_before_host'  => false,
                'mute_upon_entry'   => false,
                'waiting_room'      => false,
            ];

            $rawSettings = $request->input('settings', []);
            $processedSettings = $schedule->settings ?? [];
            
            foreach ($defaultBooleanSettings as $key => $defaultValue) {
                $processedSettings[$key] = isset($rawSettings[$key]);
            }

            if (isset($rawSettings['audio'])) {
                $processedSettings['audio'] = $rawSettings['audio'];
            }
            if (isset($rawSettings['auto_recording'])) {
                $processedSettings['auto_recording'] = $rawSettings['auto_recording'];
            }
            if (isset($rawSettings['approval_type'])) {
                $processedSettings['approval_type'] = (int)$rawSettings['approval_type'];
            }

            $scheduleData = [
                'classroom_id'   => $request->classroom_id,
                'topic'          => $request->topic,
                'form_id'        => $request->form_id,
                'agenda'         => $request->agenda,
                'duration'       => $request->duration,
                'time'           => Carbon::parse($request->time, 'Asia/Jakarta'),
                'settings'       => $processedSettings,
            ];
            
            $schedule->update($scheduleData);

            if ($schedule->zoom_meeting_id) {
                $response = Http::withToken($accessToken)
                    ->patch("https://api.zoom.us/v2/meetings/{$schedule->zoom_meeting_id}", [
                        'topic'        => $request->topic,
                        'agenda'     => $request->agenda,
                        'start_time' => Carbon::parse($request->time)->toIso8601String(),
                        'duration'   => (int) $request->duration,
                        'settings'   => $processedSettings,
                    ]);

                if (!$response->successful()) {
                    \Log::error('Zoom Meeting Update Failed', [
                        'schedule_id' => $schedule->id,
                        'response_status' => $response->status(),
                        'response_body' => $response->json(),
                    ]);
                }
            }

            $redirectSlug = $schedule->classroom->id;
            return redirect()->route('admin.schedules', $redirectSlug)->with('success', 'Schedule has been successfully updated.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred while updating: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $schedule = Schedule::findOrFail($id);

        try {
            if ($schedule->zoom_meeting_id) {
                $accessToken = $this->getZoomAccessToken();
                
                $response = Http::withToken($accessToken)
                    ->delete("https://api.zoom.us/v2/meetings/{$schedule->zoom_meeting_id}");

                if (!$response->successful()) {
                    $errorInfo = $response->json();
                    $errorMessage = $errorInfo['message'] ?? 'Status: ' . $response->status();
                    
                    if ($response->status() == 404) {
                    } else {
                        return back()->with('error', 'Failed to delete meeting in Zoom. Message: ' . $errorMessage);
                    }
                }
            }
            
            $redirectSlug = $schedule->classroom->subject->slug;
            $schedule->delete();

            return redirect()->back()->with('success', 'Schedule has been successfully deleted.');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
}
