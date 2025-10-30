<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZoomWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $signature = $request->header('x-zm-signature');
        $timestamp = $request->header('x-zm-request-timestamp');
        $secret = env('ZOOM_WEBHOOK_SECRET_TOKEN');

        if (!$signature || !$timestamp || !$secret) {
            Log::warning('Zoom Webhook: Missing signature, timestamp, or secret.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $message = "v0:{$timestamp}:" . $request->getContent();
        $hash = "v0=" . hash_hmac('sha256', $message, $secret);

        if (!hash_equals($hash, $signature)) {
            Log::error('Zoom Webhook: Invalid signature.');
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $event = $request->input('event');
        $payload = $request->input('payload');

        if (!$event || !$payload) {
             Log::info('Zoom Webhook: Received an empty event or payload.');
             return response()->json(['status' => 'Payload or event missing.']);
        }
        
        Log::info('Zoom Webhook Received:', ['event' => $event, 'payload' => $payload]);

        if ($event === 'meeting.ended') {
            $this->handleMeetingEnded($payload);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleMeetingEnded(array $payload)
    {
        $meetingId = $payload['object']['id'] ?? null;
        if (!$meetingId) {
            Log::warning('Zoom Webhook: meeting.ended event did not contain a meeting ID.');
            return;
        }

        $schedule = Schedule::where('zoom_meeting_id', $meetingId)->first();

        if ($schedule) {
            $schedule->status = 'ended';
            $schedule->save();
            Log::info("Zoom Webhook: Schedule ID {$schedule->id} status updated to 'ended'.");
        } else {
            Log::warning("Zoom Webhook: Received meeting.ended for a meeting not found in database.", ['zoom_meeting_id' => $meetingId]);
        }
    }
}