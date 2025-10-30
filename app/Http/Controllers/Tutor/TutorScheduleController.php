<?php

namespace App\Http\Controllers\Tutor;

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

class TutorScheduleController extends Controller
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

    public function create($slug)
    {
        $user = Auth::user();

        $selectedSubject = Subject::where('slug', $slug)
            ->firstOrFail();
        
        $classes = Classroom::where('user_id', $user->id)
                            ->where('subject_id', $selectedSubject->id)
                            ->get();
        
        $forms = Form::all();

        return view('tutor.schedules.create', compact('selectedSubject', 'user', 'classes', 'forms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'form_id' => 'required|exists:forms,id',
            'topic' => 'required|string|max:255',
            'agenda'  => 'required|string|max:1000',
            'time' => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);

        try {
            $classroom = Classroom::findOrFail($request->classroom_id);
            $tutor = User::findOrFail($classroom->user_id);

            if (!$tutor->email) {
                return back()->withInput()->with('error', 'The tutor of this class does not have a registered email address.');
            }

            $accessToken = $this->getZoomAccessToken();

            $rawSettings = $request->input('settings', []);

            $defaultBooleanSettings = [
                'host_video'        => false,
                'participant_video' => false,
                'join_before_host'  => false,
                'mute_upon_entry'   => false,
                'waiting_room'      => false,
            ];

            $processedSettings = [];

            foreach ($defaultBooleanSettings as $key => $defaultValue) {
                $processedSettings[$key] = (bool)($rawSettings[$key] ?? $defaultValue);
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

            $schedule = Schedule::create([
                'user_id'        => $tutor->id,
                'classroom_id'   => $request->classroom_id,
                'topic'        => $request->topic,
                'form_id'    => $request->form_id,
                'agenda'     => $request->agenda,
                'type'       => 1,
                'duration'   => $request->duration,
                'time' => Carbon::parse($request->time, 'Asia/Jakarta'),
                'timezone'   => 'Asia/Jakarta',
                'password'   => $request->password ?? Str::random(8),
                'status'     => 'scheduled',
                'settings'   => $processedSettings,
            ]);

            $response = Http::withToken($accessToken)
                ->post("https://api.zoom.us/v2/users/{$tutor->email}/meetings", [
                    'topic'        => $request->topic,
                    'agenda'     => $request->agenda,
                    'type'       => 2,
                    'start_time' => $schedule->time->toIso8601String(),
                    'duration'   => (int) $request->duration,
                    'password'   => $schedule->password,
                    'settings'   => $processedSettings,
                ]);

            if (!$response->successful()) {
                \Log::error('Zoom Meeting Creation Failed', [
                    'tutor_email' => $tutor->email,
                    'response_status' => $response->status(),
                    'response_body' => $response->json(),
                ]);

                $schedule->delete();
                $errorMessage = $response->json()['message'] ?? 'An unknown error occurred while communicating with Zoom.';
                return back()->withInput()->with('error', 'Failed to create a meeting on Zoom: ' . $errorMessage);
            }

            $meeting = $response->json();

            $schedule->update([
                'zoom_meeting_id' => (string) $meeting['id'],
                'zoom_join_url'   => $meeting['join_url'],
                'zoom_start_url'  => $meeting['start_url'],
            ]);

            return redirect()->route('tutor.dashboard', $classroom->subject->slug)->with('success', 'Schedule has been successfully created.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return back()->withInput()->with('error', 'The selected class or its tutor could not be found. Please check your selection.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'There is an error: ' . $e->getMessage());
        }
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'form_id' => 'required|exists:forms,id',
            'agenda'  => 'required|string|max:1000',
            'time' => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);

        $schedule = Schedule::findOrFail($id);

        try {
            $accessToken = $this->getZoomAccessToken();

            $defaultSettings = [
                'host_video'        => false,
                'participant_video' => false,
                'join_before_host'  => false,
                'mute_upon_entry'   => false,
                'waiting_room'      => false,
            ];

            $rawSettings = $request->input('settings', []);
            
            $processedSettings = array_merge($defaultSettings, $rawSettings);
            foreach ($processedSettings as $key => $value) {
                if (array_key_exists($key, $defaultSettings)) {
                    $processedSettings[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
            }
            
            $updateData = $request->except(['_token', '_method', 'settings']);
            $updateData['settings'] = $processedSettings;

            $schedule->update($updateData);


            if ($schedule->zoom_meeting_id) {
                Http::withToken($accessToken)
                    ->patch("https://api.zoom.us/v2/meetings/{$schedule->zoom_meeting_id}", [
                        'topic'        => $request->topic,
                        'agenda'     => $request->agenda,
                        'time' => Carbon::parse($request->time)->toIso8601String(),
                        'duration'   => $request->duration,
                        'settings'   => $processedSettings,
                    ]);
            }

            return redirect()->route('tutor.dashboard')->with('success', 'Schedule has been successfully updated.');

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

            $schedule->delete();

            return redirect()->route('tutor.dashboard')->with('success', 'Schedule has been successfully deleted.');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
}
