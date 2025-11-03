@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
    <div class="w-full max-w-screen-xl mx-auto">
        <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
            <div class="flex items-center gap-3">
                <img src="/frontend/assets/images/student-profile-vector.svg" alt="Tutor Avatar" class="w-28" />
                <div>
                    <span class="text-gray-250">Student</span>
                    <h1 class="text-4xl font-bold tracking-tight text-white">Subject Enrollment</h1>
                </div>
            </div>
            <div class="flex items-center gap-1 mb-3">
                <span class="text-gray-910 text-[15px] font-medium">My Profile </span>
                <span class="text-white text-[15px font-medium">> Payment</span>
            </div>
        </div>

        <div class="space-y-10 divide-y divide-zinc-700 pt-10">
            <div class="grid grid-cols-12 gap-10">
                <div class="col-span-12">
                    <div class="flex items-center gap-10">
                        <a href="{{ route('student.enrollment.summary', $subscription->id) }}"
                            class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                            <img src="/frontend/assets/icons/arrow-left.svg" alt="Back" class="size-4">
                        </a>
                        <h6 class="text-[20px] text-gray-75 font-semibold">Payment Summary</h6>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <div class="flex flex-col gap-5 bg-gray-990 rounded-[21px] py-10 px-8">
                        @php
                            $classesCount = count(json_decode($subscription->classroom_id, true) ?? []);
                            $pricePerClass = $subscription->plan->price ?? 0;

                            $voucherValue = 0;
                            if($subscription->coupon){
                                $voucher = $subscription->coupon;
                                $voucherValue = $voucher->type === 'percentage'
                                    ? ($subscription->total * $voucher->value / 100)
                                    : $voucher->value;
                            }

                            $plusianValue = $subscription->plusian_coupon_id ? $subscription->plusianCoupon->value : 0;
                            $amountToPay = max(0, $subscription->total - $voucherValue - $plusianValue);
                        @endphp

                        <div class="flex items-center justify-between border-b border-[#1F1F1F] pb-5">
                            <span class="text-[#868484]">Price per class</span>
                            <span class="text-gray-50">RM{{ number_format($pricePerClass,2) }}</span>
                        </div>

                        <div class="flex items-center justify-between border-b border-[#1F1F1F] pb-5">
                            <span class="text-[#868484]">Classes Count</span>
                            <span class="text-gray-50">{{ $classesCount }} Classes</span>
                        </div>

                        <div class="flex items-center justify-between border-b border-[#1F1F1F] pb-5">
                            <span class="text-[#868484]">Subtotal</span>
                            <span class="text-gray-50">RM{{ number_format($subscription->total,2) }}</span>
                        </div>

                        <div class="flex items-center justify-between border-b border-[#1F1F1F] pb-5">
                            <span class="text-[#868484]">Voucher</span>
                            <span class="text-gray-50">-RM{{ number_format($voucherValue,2) }}</span>
                        </div>

                        <div class="flex items-center justify-between border-b border-[#1F1F1F] pb-5">
                            <span class="text-[#868484]">Plusian Preneur Code</span>
                            <span class="text-gray-50">-RM{{ number_format($plusianValue,2) }}</span>
                        </div>

                        <div class="flex items-center justify-between bg-black rounded-[21px] text-gray-75 px-5 py-6 mb-5">
                            <span class="text-[24px]">Amount To Pay</span>
                            <span class="text-[24px]">RM{{ number_format($amountToPay,2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="flex flex-col items-center gap-8 w-full">
                        <h6 class="text-[20px] text-gray-75 font-semibold mb-4">Payment Options</h6>
                        <div class="flex flex-col gap-5 w-full lg:w-[80%] max-h-[500px] overflow-y-auto p-2">

                            <form action="{{ route('student.enrollment.processPayment', $subscription->id) }}" method="POST" class="w-full">
                                @csrf

                                @if($fpxBanks->isNotEmpty())
                                    <h6 class="text-gray-300 font-medium mb-2">Perbankan Online (FPX)</h6>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5">
                                        @foreach($fpxBanks as $bank)
                                            <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:border-gray-400 payment-method">
                                                <input type="radio" name="payment_code" value="{{ $bank['code'] }}" class="hidden peer" required>
                                                <img src="{{ $bank['logo'] ?? '/frontend/assets/images/sample/fpx.png' }}" alt="{{ $bank['extras']['name'] ?? $bank['code'] }}" class="h-12 mb-2 object-contain">
                                                <span class="text-gray-50 text-sm text-center">{{ $bank['extras']['name'] ?? $bank['code'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                                @if($eWallets->isNotEmpty())
                                    <h6 class="text-gray-300 font-medium mb-2">E-Wallet & Duitnow QR</h6>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5">
                                        @foreach($eWallets as $wallet)
                                            <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:border-gray-400 payment-method">
                                                <input type="radio" name="payment_code" value="{{ $wallet['code'] }}" class="hidden peer" required>
                                                <img src="{{ $wallet['logo'] ?? '/frontend/assets/images/sample/ewallet.png' }}" alt="{{ $wallet['extras']['name'] ?? $wallet['code'] }}" class="h-12 mb-2 object-contain">
                                                <span class="text-gray-50 text-sm text-center">{{ $wallet['extras']['name'] ?? $wallet['code'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                                @if($cards->isNotEmpty())
                                    <h6 class="text-gray-300 font-medium mb-2">Kartu Kredit / Debit</h6>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5">
                                        @foreach($cards as $card)
                                            <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:border-gray-400 payment-method">
                                                <input type="radio" name="payment_code" value="{{ $card['code'] }}" class="hidden peer" required>
                                                <img src="{{ $card['logo'] ?? '/frontend/assets/images/sample/card.png' }}" alt="{{ $card['extras']['name'] ?? $card['code'] }}" class="h-12 mb-2 object-contain">
                                                <span class="text-gray-50 text-sm text-center">{{ $card['extras']['name'] ?? $card['code'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                                <button type="submit"
                                    class="bg-gray-50 hover:bg-gray-200 text-black text-[16px] font-semibold rounded-full w-full py-3 mt-3">
                                    Pay Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const labels = document.querySelectorAll('label.payment-method');

        labels.forEach(label => {
            const input = label.querySelector('input[type="radio"]');
            input.addEventListener('change', () => {
                labels.forEach(l => l.classList.remove('border-blue-500', 'bg-blue-600/20'));
                if (input.checked) {
                    label.classList.add('border-blue-500', 'bg-blue-600/20');
                }
            });
        });
    });
</script>

@endpush