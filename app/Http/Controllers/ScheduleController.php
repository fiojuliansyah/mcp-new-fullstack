<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
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
                        return back()->with('error', 'Gagal menghapus meeting di Zoom. Pesan: ' . $errorMessage);
                    }
                }
            }

            $schedule->delete();

            return redirect()->route('admin.live-classes.index')->with('success', 'Live Class berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }
}
