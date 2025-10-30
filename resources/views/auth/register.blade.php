@extends('layouts.auth')

@section('content')
<header class="relative w-full md:max-h-[900px] flex items-center font-inter overflow-hidden px-4 py-48 md:py-56">
    <img src="/frontend/assets/images/header-bg.svg" alt="" class="w-full absolute top-0 right-0 -z-10" />

    <div class="w-full max-w-screen-lg mx-auto flex justify-center items-center space-y-3">
        <form method="POST" action="{{ route('register') }}" class="w-full max-w-lg mx-auto bg-white text-black text-center shadow-lg rounded-2xl md:rounded-3xl px-5 py-8 md:p-10 lg:p-20 relative">
             @csrf

            <a href="{{ route('started') }}" class="absolute top-4 left-4 z-10 p-2 bg-white rounded-full hover:bg-zinc-100 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <h1 class="text-xl md:text-3xl pt-6">Create an Account</h1>

            <div class="w-full space-y-3 py-3 pt-5">
                <div class="w-full text-left">
                    <label for="email" class="text-sm text-zinc-500 pb-2">Email</label>
                    <input type="text" id="email" name="email" class="w-full text-white bg-black rounded-md p-3 mt-1 @error('email') border-2 border-red-500 @enderror" value="{{ old('email') }}" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="w-full text-left">
                    <label for="password" class="text-sm text-zinc-500 pb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="w-full text-white bg-black rounded-md p-3 mt-1 pr-10 @error('password') border-2 border-red-500 @enderror" />
                        <span id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon-password" class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878c-3.207 2.058-4.33 4.935-4.57 6.452A6.5 6.5 0 0012 19c2.5 0 4.7-1 6.5-2.5-3.207-2.058-4.33-4.935-4.57-6.452z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-2" id="password-strength-container">
                        <div class="h-2 w-full bg-zinc-200 rounded-full overflow-hidden">
                            <div id="password-strength-bar" class="h-full bg-red-500 transition-all duration-300 w-0"></div>
                        </div>
                        <p id="strength-text" class="text-xs mt-1 text-zinc-500"></p>
                    </div>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="w-full text-left">
                    <label for="password_confirmation" class="text-sm text-zinc-500 pb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full text-white bg-black rounded-md p-3 mt-1 pr-10 @error('password_confirmation') border-2 border-red-500 @enderror" />
                        <span id="toggleConfirmPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon-confirm" class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878c-3.207 2.058-4.33 4.935-4.57 6.452A6.5 6.5 0 0012 19c2.5 0 4.7-1 6.5-2.5-3.207-2.058-4.33-4.935-4.57-6.452z" />
                            </svg>
                        </span>
                    </div>
                    <p id="confirmation-text" class="text-xs mt-1"></p>

                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="pt-5">
                    <button type="submit" id="continueButton" class="w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 text-black rounded-lg p-3 cursor-not-allowed" disabled>Continue</button>
                </div>
                <p class="text-sm md:text-base">Already have an account? <a href="{{ route('started') }}" class="underline hover:text-zinc-300">Login</a></p>
            </div>
        </form>
    </div>
</header>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('strength-text');
        const confirmationText = document.getElementById('confirmation-text');
        const continueButton = document.getElementById('continueButton');
        
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

        let isStrong = false;
        let isConfirmed = false;

        function setupToggle(toggleElement, inputFieldId, iconId) {
            toggleElement.addEventListener('click', function() {
                const inputField = document.getElementById(inputFieldId);
                const icon = document.getElementById(iconId);
                
                const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
                inputField.setAttribute('type', type);
                
                if (type === 'password') {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878c-3.207 2.058-4.33 4.935-4.57 6.452A6.5 6.5 0 0012 19c2.5 0 4.7-1 6.5-2.5-3.207-2.058-4.33-4.935-4.57-6.452z" />';
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 10.5h.008v.008h-.008v-.008z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5h.008v.008h-.008v-.008z" />';
                }
            });
        }
        setupToggle(togglePassword, 'password', 'eye-icon-password');
        setupToggle(toggleConfirmPassword, 'password_confirmation', 'eye-icon-confirm');

        function checkPasswordStrength(password) {
            let strength = 0;
            const reasons = [];

            if (password.length >= 8) {
                strength += 25;
            } else {
                reasons.push("Must be at least 8 characters");
            }

            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
                strength += 25;
            } else {
                reasons.push("Needs uppercase and lowercase letters");
            }

            if (password.match(/\d/)) {
                strength += 25;
            } else {
                reasons.push("Needs at least one number");
            }

            if (password.match(/[^a-zA-Z\d]/)) {
                strength += 25;
            } else {
                reasons.push("Needs at least one symbol (!@#$...)");
            }

            return { strength: strength, reasons: reasons };
        }

        function updateStrengthMeter(result) {
            strengthBar.style.width = result.strength + '%';
            
            let colorClass = 'bg-red-500';
            let text = 'Weak';
            
            if (result.strength === 100) {
                colorClass = 'bg-green-500';
                text = 'Strong 💪';
                isStrong = true;
            } else if (result.strength >= 75) {
                colorClass = 'bg-yellow-500';
                text = 'Medium';
                isStrong = false;
            } else if (result.strength > 0) {
                colorClass = 'bg-red-500';
                text = 'Weak (' + result.reasons.join(', ') + ')';
                isStrong = false;
            } else {
                text = '';
                isStrong = false;
            }
            
            strengthBar.className = 'h-full transition-all duration-300 ' + colorClass;
            strengthText.textContent = text;
            strengthText.className = 'text-xs mt-1 ' + (result.strength > 0 ? 'text-black' : 'text-zinc-200');

            if (result.strength > 0 && result.strength < 100) {
                 strengthText.className = 'text-xs mt-1 text-red-500';
            }

            if(result.strength === 0) {
                 strengthText.textContent = '';
            }
        }

        function checkConfirmation() {
            if (passwordField.value === confirmField.value && confirmField.value !== '') {
                confirmationText.textContent = 'Passwords match!';
                confirmationText.className = 'text-xs mt-1 text-green-500';
                isConfirmed = true;
            } else if (confirmField.value !== '') {
                confirmationText.textContent = 'Passwords do not match!';
                confirmationText.className = 'text-xs mt-1 text-red-500';
                isConfirmed = false;
            } else {
                confirmationText.textContent = '';
                isConfirmed = false;
            }
        }

        function updateButtonState() {
            if (isStrong && isConfirmed) {
                continueButton.disabled = false;
                continueButton.className = 'w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 text-black hover:bg-zinc-300 rounded-lg hover:cursor-pointer p-3';
            } else {
                continueButton.disabled = true;
                continueButton.className = 'w-full flex justify-center items-center text-sm md:text-base transition-all duration-300 bg-zinc-200 text-white rounded-lg p-3 cursor-not-allowed';
            }
        }

        passwordField.addEventListener('input', function() {
            updateStrengthMeter(checkPasswordStrength(this.value));
            checkConfirmation();
            updateButtonState();
        });

        confirmField.addEventListener('input', function() {
            checkConfirmation();
            updateButtonState();
        });

        updateButtonState();
    });
</script>
@endpush