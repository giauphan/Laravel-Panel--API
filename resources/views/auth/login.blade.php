@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-center">
        <div class="w-full sm:w-8/12">
            <div class="bg-white shadow-md rounded-lg">
                <div class="p-6">{{ __('Login') }}</div>

                <div class="p-6">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="block text-right">{{ __('Email Address') }}</label>

                            <div>
                                <input id="email" type="email" class="w-full px-4 py-2 border @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="text-red-500" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="block text-right">{{ __('Password') }}</label>

                            <div>
                                <input id="password" type="password" class="w-full px-4 py-2 border @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="text-red-500" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div>
                                <input class="mr-1" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label for="remember">{{ __('Remember Me') }}</label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div>
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="text-blue-500" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
