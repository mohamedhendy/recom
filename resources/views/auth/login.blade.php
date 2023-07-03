<x-guest-layout>
    <div class="f-page">
        <div class="side-img">
            <img src = "{{ asset('/images/login.png') }}" />
        </div>
        <div class="test-f">
            <x-jet-authentication-card>
                
        
                <x-jet-validation-errors class="mb-4" />
        
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif
                <x-slot name="logo">
                    <x-jet-authentication-card-logo />
                </x-slot>
               <div>
                   <h2 class="welcome-title"> Welcome back  <img src="https://img.icons8.com/emoji/48/000000/waving-hand-emoji.png"/></h2>
                   <div class="or-option">
                       <hr>
                       <span>Or</span>
                       <hr>
                   </div>
                <form method="POST" action="{{ route('login') }}" class="selected-login">
                    @csrf
        
                    <div>
                        <x-jet-input placeholder="{{ __('Your Email') }}" id="email" class="block mt-1 w-full reg-input" type="email" name="email" :value="old('email')" required autofocus />
                    </div>
        
                    <div class="mt-4">
                        <div style="position: relative">
                            <x-jet-input placeholder="{{ __('Your Password') }}" id="password" class="block mt-1 w-full reg-input" type="password" name="password" required autocomplete="current-password" />
                            @if (Route::has('password.request'))
                            <a class="underline forget-pass  text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                <small>{{ __('Forgot  password?') }}</small>
                            </a>
                        </div>
                    @endif
                    </div>
        
                    <div class="block mt-4">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>
        
                    <div class=" items-center  mt-4">
                        <div class="text-center">
                            <x-jet-button class="ml-4 log-btn">
                                {{ __('Login') }}
                            </x-jet-button>
                        </div> 
                    </div>
                </form>
               </div>
            </x-jet-authentication-card>
            </div>
    </div>
</x-guest-layout>
