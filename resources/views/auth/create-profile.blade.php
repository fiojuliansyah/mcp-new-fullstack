@extends('layouts.auth')

@section('content')
    <header class="relative w-full md:max-h-[900px] flex items-center font-inter overflow-hidden px-4 py-48 md:py-56"> <img
            src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />
        <div class="w-full max-w-screen-lg mx-auto flex justify-center items-center space-y-3">
            <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20">
                @csrf 
                <h1 class="text-xl md:text-3xl text-black">Create profile account</h1>
                <div class="w-full space-y-4 py-3 pt-5">

                   @if (Auth::user()->account_type === 'student')
                    <div class="w-full flex justify-center pt-10">
                        <div class="group">
                            <label for="avatar_url" class="relative w-20 h-20 flex justify-center items-center cursor-pointer transition-all duration-300 bg-black group-hover:bg-black/80 ring-1 ring-offset-2 ring-black rounded-full overflow-hidden">
                                
                                <img id="avatar_url_preview" 
                                    src="{{ Auth::user()->avatar_url }}"
                                    alt="Profile Picture" 
                                    class="w-full h-full object-cover absolute inset-0 @if(Auth::user()->avatar_url) block @else hidden @endif" />
                                
                                <svg id="camera_icon" width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="@if(Auth::user()->avatar_url) hidden @else block @endif">
                                    <path d="M22.8647 22.4902H2.86475C2.33431 22.4902 1.82561 22.2795 1.45053 21.9044C1.07546 21.5294 0.864746 21.0207 0.864746 20.4902V6.49023C0.864746 5.9598 1.07546 5.45109 1.45053 5.07602C1.82561 4.70095 2.33431 4.49023 2.86475 4.49023H6.86475L8.86475 1.49023H16.8647L18.8647 4.49023H22.8647C23.3952 4.49023 23.9039 4.70095 24.2790 5.07602C24.654 5.45109 24.8647 5.9598 24.8647 6.49023V20.4902C24.8647 21.0207 24.654 21.5294 24.2790 21.9044C23.9039 22.2795 23.3952 22.4902 22.8647 22.4902Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.8647 17.4902C15.35 17.4902 17.3647 15.4755 17.3647 12.9902C17.3647 10.505 15.35 8.49023 12.8647 8.49023C10.3795 8.49023 8.36475 10.505 8.36475 12.9902C8.36475 15.4755 10.3795 17.4902 12.8647 17.4902Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>                            
                                <input type="file" name="avatar_url" id="avatar_url" accept="image/*" class="w-full h-full absolute inset-0 z-10 opacity-0" />
                            </label>
                        </div>
                    </div>
                @endif
                    
                    <div class="w-full text-left"> <label for="name" class="text-sm text-zinc-600 pb-2">Name</label>
                        <input type="text" id="name" name="name"
                            class="w-full text-white bg-black rounded-md p-3 mt-1 border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400" />
                    </div>
                    
                    <div class="w-full text-left"> <label for="ic-number" class="text-sm text-zinc-600 pb-2">IC
                            Number</label> <input type="text" id="ic-number" name="ic_number"
                            class="w-full text-white bg-black rounded-md p-3 mt-1 border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400" />
                    </div>

                    <div class="w-full text-left grid grid-cols-1 @if (Auth::user()->account_type === 'student') md:grid-cols-2 @endif gap-4">
                        
                        <div class="w-full">
                            <label for="gender" class="text-sm text-zinc-600 pb-2 block">Gender</label>
                            <select id="gender" name="gender"
                                class="w-full text-white bg-black rounded-md p-3 mt-1 border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400">
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        @if (Auth::user()->account_type === 'student')
                            <div class="w-full">
                                <label for="level" class="text-sm text-zinc-600 pb-2 block">Level</label>
                                <select id="level" name="form_id"
                                    class="w-full text-white bg-black rounded-md p-3 mt-1 border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400">
                                    <option value="" disabled selected>Select Level</option>
                                    @foreach ($forms as $form)   
                                        <option value="{{ $form->id }}">{{ $form->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="w-full text-left">
                        <label for="phone-number" class="text-sm text-zinc-600 pb-2">Phone Number</label>
                        <div class="w-full grid grid-cols-10 gap-2">
                            <input type="text" id="phone-prefix" name="phone_prefix"
                                class="w-full col-span-2 text-white bg-black rounded-md p-3 border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400"
                                placeholder="+60" value="+60" />
                            <input type="text" id="phone-number" name="phone"
                                class="w-full col-span-8 text-white bg-black rounded-md p-3 border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400"
                                placeholder="812345678" />
                        </div>
                    </div>
                    
                    @if (Auth::user()->account_type === 'parent')   
                        <div class="w-full text-left"> 
                            <label for="post-code" class="text-sm text-zinc-600 pb-2">Postcode</label> 
                            <input type="text" id="post-code" name="postal_code"
                                class="w-full text-white bg-black rounded-md p-3 mt-1 border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400" />
                        </div>
                    @endif

                    <button type="submit"
                        class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 text-black hover:bg-zinc-300 rounded-lg hover:cursor-pointer p-3">Create</button>
                </div>
            </form>
        </div>
    </header>
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilePictureInput = document.getElementById('avatar_url');
        const profilePicturePreview = document.getElementById('avatar_url_preview');
        const cameraIcon = document.getElementById('camera_icon');

        if (profilePictureInput && profilePicturePreview && cameraIcon) {
            profilePictureInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePicturePreview.src = e.target.result;
                        profilePicturePreview.classList.remove('hidden');
                        cameraIcon.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    profilePicturePreview.src = '';
                    profilePicturePreview.classList.add('hidden');
                    cameraIcon.classList.remove('hidden');
                }
            });
        }
    });
</script>
@endpush