@extends('layouts.guest')

@php
    $settings = App\Models\Utility::settings();
@endphp
@section('title')
    {{ __('Login') }}
@endsection


@section('content')
    <div class="card-body">
        <div>
            <h2 class="mb-3 f-w-600">{{ __('Login') }}</h2>
        </div>
        @if(session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}" id="form_data" class="needs-validation" novalidate="">
            @csrf
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror"
                        name="email" placeholder="{{ __('Enter your email') }}"
                        required autofocus>
                    @error('email')
                        <span class="error invalid-email text-danger" role="alert">
                            <small>{{ $message }}</small>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3 pss-field">
                    <label class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required>
                    @error('password')
                        <span class="error invalid-password text-danger" role="alert">
                            <small>{{ $message }}</small>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        @if (Route::has('change.langPass'))
                            <span>
                                <a href="{{ route('change.langPass', $lang) }}" tabindex="0">{{ __('Forgot Your Password?') }}</a>
                            </span>
                        @endif
                    </div>
                </div>
                @if (isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes')
                    @if (isset($settings['google_recaptcha_version']) && $settings['google_recaptcha_version'] == 'v2')
                        <div class="form-group mb-3">
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                                <span class="small text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @else
                        <div class="form-group col-lg-12 col-md-12 mt-3">
                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="form-control">
                            @error('g-recaptcha-response')
                                <span class="error small text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif
                @endif

                <div class="d-grid">
                    <button type="submit" class="btn btn-secondary bg-black btn-block mt-2"
                        id="login_button">{{ __('Login') }}</button>
                </div>
                @if ((!empty($settings['signup_button']) ? $settings['signup_button'] : '') == 'on')
                    <div class="my-4 text-xs text-center">
                        <p>
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}">{{ __('Register') }}</a>
                        </p>
                    </div>
                @endif
        </form>
    </div>
@endsection
