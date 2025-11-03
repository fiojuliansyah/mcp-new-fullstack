<?php

namespace App\Http\Controllers\Student;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class StudentPaymentController extends Controller
{
    public function index($id)
    {
        $subscription = Subscription::with('plan')->findOrFail($id);

        $fpxBanks = collect();  
        $eWallets = collect();
        $cards = collect();

        try {
            $apiKey = env('BILLPLZ_API_KEY');
            $xSignatureKey = env('BILLPLZ_X_SIGNATURE_KEY');

            if ($apiKey && $xSignatureKey) {
                $epoch = time();
                $checksum = hash_hmac('sha512', $epoch, $xSignatureKey);
                $url = env('BILLPLZ_URL');

                $response = Http::withBasicAuth($apiKey, '')
                                ->get($url, [
                                    'epoch' => $epoch,
                                    'checksum' => $checksum,
                                ]);

                if ($response->successful()) {
                    $allGateways = collect($response->json()['payment_gateways']);
                    $activeGateways = $allGateways->filter(fn($g) => $g['active'] && $g['extras']['visibility']);

                    $fpxBanks = $activeGateways->filter(fn($g) => $g['extras']['isFpx']);
                    $eWallets = $activeGateways->filter(fn($g) => $g['extras']['isObw'] && !$g['extras']['isFpx']);
                    $cards = $activeGateways->filter(fn($g) => str_contains($g['extras']['name'] ?? '', 'Visa') || str_contains($g['extras']['name'] ?? '', 'Mastercard'));
                }
            }
        } catch (\Exception $e) {
            Log::error('Error koneksi ke API Billplz: ' . $e->getMessage());
        }

        $paymentGateways = [
            'FPX Banks' => $fpxBanks,
            'E-Wallets' => $eWallets,
            'Card' => $cards,
        ];

        return view('student.enrollments.payment', compact(
            'subscription', 'fpxBanks', 'eWallets', 'cards'
        ));
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_code' => 'required|string'
        ]);

        $subscription = Subscription::with('plan')->findOrFail($id);
        $totalAmount = $subscription->subtotal;

        $apiKey = env('BILLPLZ_API_KEY');
        $collectionId = env('BILLPLZ_COLLECTION_ID');
        $billUrl = env('BILLPLZ_BILL_URL', 'https://www.billplz-sandbox.com/api/v3/bills');

        $description = 'Payment for subscription: ' . $subscription->plan->name;

        try {
            $response = Http::asForm()->withBasicAuth($apiKey, '')
                ->post($billUrl, [
                    'collection_id' => $collectionId,
                    'email' => $subscription->user->email,
                    'name' => $subscription->user->name,
                    'amount' => $totalAmount * 100,
                    'description' => $description,
                    'callback_url' => route('student.enrollment.payment.paymentCallback', $subscription),
                    'redirect_url' => route('student.enrollment.payment.paymentSuccess', $subscription),
                    'payment_channel' => $request->payment_code
                ]);

            if ($response->successful()) {
                $billData = $response->json();

                $subscription->payment_gateway_bill_id = $billData['id'];
                $subscription->payment_method = 'FPX';
                $subscription->status = 'pending';
                $subscription->save();

                return redirect($billData['url']);
            }

            Log::error('Billplz payment failed: ' . $response->body());
            return redirect()->back()->with('error', 'Failed to generate payment link.');
            
        } catch (\Exception $e) {
            Log::error('Billplz payment exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing payment.');
        }
    }


    public function billplzWebhook(Request $request)
    {
        $data = $request->all();
        $invoiceNumber = $data['bill']['passthrough']['invoice_number'] ?? null;
        $payment_status = $data['bill']['paid'] ?? false;

        if ($invoiceNumber) {
            $sub = Subscription::where('invoice_number', $invoiceNumber)->get();
            $sub->payment_status = $payment_status ? 'paid' : 'failed';
            $sub->status = $payment_status ? 'active' : 'pending';
            $sub->save();
        }

        return response()->json(['status'=>'ok']);
    }


    public function paymentCallback(Request $request, $invoiceNumber)
    {
        $paid = $request->query('paid', false);

        $subscriptions = Subscription::where('invoice_number', $invoiceNumber)->get();

        foreach ($subscriptions as $sub) {
            $sub->payment_status = $paid ? 'paid' : 'failed';
            $sub->status = $paid ? 'active' : 'pending';
            $sub->save();

            if ($paid && !empty($sub->classroom_id)) {
                $classroomIds = json_decode($sub->classroom_id, true);
                $sub->classrooms()->sync($classroomIds);
            }
        }

        return redirect()->route('student.dashboard')
                        ->with($paid ? 'success' : 'error', $paid ? 'Payment successful!' : 'Payment failed.');
    }


    public function paymentSuccess($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);

        $subscription->payment_status = 'paid';
        $subscription->status = 'active';
        $subscription->save();

        if (!empty($subscription->classroom_id)) {
            $classroomIds = json_decode($subscription->classroom_id, true);
            $subscription->classrooms()->sync($classroomIds);
        }

        return redirect()->route('student.dashboard')->with('success', 'Payment successful!');
    }



}
