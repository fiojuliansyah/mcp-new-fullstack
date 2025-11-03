@extends('layouts.app')

@section('content')
<section class="w-full bg-primary text-white px-4 py-10">
    <div class="w-full max-w-screen-xl mx-auto">
        <!-- HEADER -->
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
                <span class="text-white text-[15px font-medium">> Subject Enrolment</span>
            </div>
        </div>

        <div class="space-y-10 divide-y divide-zinc-700">
            <div class="pt-10">
                <div class="grid grid-cols-12 gap-10 mb-10">

                    <!-- LEFT PANEL -->
                    <div class="col-span-12 lg:col-span-6">
                        <div class="flex flex-col gap-5 bg-gray-990 rounded-[21px] py-5 px-8">
                            <!-- Duration -->
                            <div class="flex items-center border-b border-[#1F1F1F] pb-5">
                                <div class="w-[50px]"></div>
                                <div class="flex flex-col">
                                    <span class="text-[#868484]">Type</span>
                                    <span class="text-gray-50" id="plan-duration">{{ $subscription->type ?? 'Normal Class' }}</span>
                                </div>
                            </div>
                            
                            <!-- Level -->
                            <div class="flex items-center border-b border-[#1F1F1F] pb-5">
                                <div class="w-[50px]"></div>
                                <div class="flex flex-col">
                                    <span class="text-[#868484]">Level</span>
                                    <span class="text-gray-50">{{ $subscription->user->form->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Type -->
                            <div class="flex items-center border-b border-[#1F1F1F] pb-5">
                                <div class="w-[50px]">
                                    <button id="edit-plan-btn" class="bg-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer">
                                        <img src="/frontend/assets/icons/pencil.svg" alt="Icon" class="size-3">
                                    </button>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[#868484]">Duration</span>
                                    <span class="text-gray-50" id="plan-name">{{ $subscription->plan?->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Classes -->
                            <div class="flex items-center border-b border-white pb-5">
                                <div class="w-[50px]"></div>
                                <div class="flex flex-col">
                                    <span class="text-[#868484]">Classes</span>
                                    <span class="text-gray-50" id="class-count">{{ count(json_decode($subscription->classroom_id,true)) }} Classes per week</span>
                                </div>
                            </div>

                            <!-- Total Billed & Voucher -->
                            <div class="flex flex-col gap-2 w-full mt-3">
                                <div class="flex items-center justify-between lg:pr-3">
                                    <span class="text-[#868484]">Total Billed</span>
                                    <span class="text-gray-50" id="total-billed">RM{{ number_format($subscription->total,2) }}</span>
                                </div>

                                <!-- Voucher -->
                                <span class="text-gray-50 mt-3">Voucher</span>
                                <div id="voucher-section">
                                    @if($subscription->coupon)
                                        <div class="flex items-center justify-between lg:pr-3">
                                            <span class="text-[#868484]" id="coupon-code">
                                                {{ $subscription->coupon->code }}
                                                @if($subscription->coupon->type==='percentage')
                                                    (-{{ $subscription->coupon->value }}%)
                                                @else
                                                    (-RM{{ number_format($subscription->coupon->value,2) }})
                                                @endif
                                            </span>
                                            <button id="remove-voucher" class="text-red-500 hover:text-red-700 font-semibold">Remove</button>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between lg:pr-3">
                                            <span class="text-[#868484]">No voucher applied</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Plusian Preneur -->
                                <span class="text-gray-50 mt-3">Plusian Preneur</span>
                                <div id="plusian-section">
                                    @if($subscription->plusian_coupon_id)
                                        @php $plusian = $subscription->plusianCoupon; @endphp
                                        <div class="flex items-center justify-between lg:pr-3">
                                            <span class="text-[#868484]" id="plusian-code">
                                                {{ $plusian->code }} (-RM{{ number_format($plusian->value,2) }})
                                            </span>
                                            <button id="remove-plusian" class="text-red-500 hover:text-red-700 font-semibold">Remove</button>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between lg:pr-3">
                                            <span class="text-[#868484]">No Plusian applied</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Total Amount -->
                                <div class="flex items-center justify-between bg-black rounded-[21px] text-gray-75 px-5 py-6 mb-5 mt-3">
                                    <span class="text-[24px]">Total Amount</span>
                                    <span class="text-[24px]" id="total-amount">RM{{ number_format($subscription->subtotal ?? $subscription->total,2) }}</span>
                                </div>

                                <a href="{{ route('student.enrollment.payment', $subscription->id) }}" class="bg-gray-50 hover:bg-gray-200 rounded-full text-center text-sm px-5 py-3 w-full cursor-pointer">
                                    <span class="text-black text-[16px] font-semibold">Continue</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT PANEL -->
                    <div class="col-span-12 lg:col-span-6">
                        <div class="flex flex-col items-end gap-8 w-full">
                            <!-- Voucher input -->
                            <div class="flex flex-col lg:flex-row lg:items-center justify-end w-full gap-3">
                                <label class="text-[#868484] lg:w-[200px]">Voucher</label>
                                <div class="flex items-center w-full lg:w-[60%] bg-gray-1000 border border-gray-950 rounded-[14px] overflow-hidden px-5">
                                    <button type="button" id="apply-voucher-btn" class="flex items-center justify-center w-8 h-8 ml-2 rounded-full bg-white text-black font-bold shadow">
                                        <img src="/frontend/assets/icons/plus.svg" alt="Icon">
                                    </button>
                                    <input type="text" id="voucher-input" placeholder="Add voucher" class="flex-1 bg-gray-1000 text-gray-75 placeholder:text-gray-500 text-center px-4 py-3 focus:outline-none" />
                                </div>
                            </div>

                            <!-- Plusian Preneur input -->
                            <div class="flex flex-col lg:flex-row lg:items-center justify-end w-full gap-3 mt-5">
                                <label class="text-[#868484] lg:w-[200px]">Plusian Preneur Code</label>
                                <div class="flex items-center w-full lg:w-[60%] bg-gray-1000 border border-gray-950 rounded-[14px] overflow-hidden px-5">
                                    <button type="button" id="apply-plusian-btn" class="flex items-center justify-center w-8 h-8 ml-2 rounded-full bg-white text-black font-bold shadow">
                                        <img src="/frontend/assets/icons/plus.svg" alt="Icon">
                                    </button>
                                    <input type="text" id="plusian-input" placeholder="Add Plusian Code" class="flex-1 bg-gray-1000 text-gray-75 placeholder:text-gray-500 text-center px-4 py-3 focus:outline-none" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</section>

<!-- Edit Plan Modal -->
<div id="edit-plan-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-990 rounded-[21px] p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4 text-white">Edit Plan</h2>
        <select id="plan-select" class="w-full bg-gray-700 text-white border border-gray-300 rounded px-3 py-2 mb-4">
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" {{ $subscription->plan_id==$plan->id?'selected':'' }}>
                    {{ $plan->name }} - {{ $plan->duration_value }} month
                </option>
            @endforeach
        </select>
        <div class="flex justify-end gap-3">
            <button id="cancel-edit" class="px-4 py-2 bg-gray-300 text-white rounded hover:bg-gray-400">Cancel</button>
            <button id="save-edit" class="px-4 py-2 bg-white text-gray-400 rounded hover:bg-blue-700">Save</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    const editBtn=document.getElementById('edit-plan-btn');
    const modal=document.getElementById('edit-plan-modal');
    const cancelBtn=document.getElementById('cancel-edit');
    const saveBtn=document.getElementById('save-edit');
    const planSelect=document.getElementById('plan-select');
    const totalBilled=document.getElementById('total-billed');
    const totalAmount=document.getElementById('total-amount');

    // Edit Plan
    editBtn.addEventListener('click',()=>{ modal.classList.remove('hidden'); modal.classList.add('flex'); });
    cancelBtn.addEventListener('click',()=>{ modal.classList.add('hidden'); modal.classList.remove('flex'); });
    saveBtn.addEventListener('click',()=>{
        const planId=planSelect.value;
        fetch("{{ route('student.enrollment.updatePlan',$subscription->id) }}",{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:JSON.stringify({plan_id:planId})
        }).then(res=>res.json()).then(data=>{
            if(data.success){
                document.getElementById('plan-name').textContent=data.plan.name;
                document.getElementById('plan-duration').textContent=data.plan.duration_value+' month';
                totalBilled.textContent='RM'+parseFloat(data.total).toFixed(2);
                totalAmount.textContent='RM'+parseFloat(data.subtotal).toFixed(2);
                modal.classList.add('hidden'); modal.classList.remove('flex');
            }else{ alert('Failed to update plan'); }
        });
    });

    // Helper
    function updateTotalAmount(subtotal){ totalAmount.textContent='RM'+parseFloat(subtotal).toFixed(2); }

    // Voucher
    const applyVoucherBtn=document.getElementById('apply-voucher-btn');
    const voucherInput=document.getElementById('voucher-input');
    const voucherSection=document.getElementById('voucher-section');
    function attachRemoveVoucher(){
        const removeBtn=document.getElementById('remove-voucher');
        if(removeBtn){
            removeBtn.addEventListener('click',()=>{
                fetch("{{ route('student.enrollment.removeVoucher',$subscription->id) }}",{
                    method:'POST',
                    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
                }).then(res=>res.json()).then(data=>{
                    if(data.success){
                        voucherSection.innerHTML=`<div class="flex items-center justify-between lg:pr-3">
                            <span class="text-[#868484]">No voucher applied</span>
                        </div>`;
                        updateTotalAmount(data.subtotal);
                    }
                });
            });
        }
    }
    attachRemoveVoucher();
    applyVoucherBtn.addEventListener('click',()=>{
        const code=voucherInput.value.trim();
        if(!code){ alert('Please enter a voucher code'); return; }
        fetch("{{ route('student.enrollment.applyVoucher',$subscription->id) }}",{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:JSON.stringify({coupon_code:code})
        }).then(res=>res.json()).then(data=>{
            if(data.success){
                let displayValue=data.coupon_type==='percentage'?'(-'+data.coupon_value+'%)':'(-RM'+parseFloat(data.coupon_value).toFixed(2)+')';
                voucherSection.innerHTML=`<div class="flex items-center justify-between lg:pr-3">
                    <span class="text-[#868484]" id="coupon-code">${data.coupon_code} ${displayValue}</span>
                    <button id="remove-voucher" class="text-red-500 hover:text-red-700 font-semibold">Remove</button>
                </div>`;
                updateTotalAmount(data.subtotal);
                voucherInput.value='';
                attachRemoveVoucher();
            }else{ alert(data.message || 'Failed to apply voucher'); }
        });
    });

    // Plusian Preneur
    const applyPlusianBtn=document.getElementById('apply-plusian-btn');
    const plusianInput=document.getElementById('plusian-input');
    const plusianSection=document.getElementById('plusian-section');
    function attachRemovePlusian(){
        const removeBtn=document.getElementById('remove-plusian');
        if(removeBtn){
            removeBtn.addEventListener('click',()=>{
                fetch("{{ route('student.enrollment.removePlusian',$subscription->id) }}",{
                    method:'POST',
                    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
                }).then(res=>res.json()).then(data=>{
                    if(data.success){
                        plusianSection.innerHTML=`<div class="flex items-center justify-between lg:pr-3">
                            <span class="text-[#868484]">No Plusian applied</span>
                        </div>`;
                        updateTotalAmount(data.subtotal);
                    }
                });
            });
        }
    }
    attachRemovePlusian();
    applyPlusianBtn.addEventListener('click',()=>{
        const code=plusianInput.value.trim();
        if(!code){ alert('Please enter Plusian Preneur code'); return; }
        fetch("{{ route('student.enrollment.applyPlusian',$subscription->id) }}",{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:JSON.stringify({coupon_code:code})
        }).then(res=>res.json()).then(data=>{
            if(data.success){
                plusianSection.innerHTML=`<div class="flex items-center justify-between lg:pr-3">
                    <span class="text-[#868484]" id="plusian-code">${data.coupon_code} (-RM${parseFloat(data.coupon_value).toFixed(2)})</span>
                    <button id="remove-plusian" class="text-red-500 hover:text-red-700 font-semibold">Remove</button>
                </div>`;
                updateTotalAmount(data.subtotal);
                plusianInput.value='';
                attachRemovePlusian();
            }else{ alert(data.message || 'Failed to apply Plusian code'); }
        });
    });
});
</script>
@endpush
