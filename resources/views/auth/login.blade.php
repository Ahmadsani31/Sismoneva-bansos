@extends('layouts.guest')
@section('content')
    <!-- Session Status -->
    <div class="row align-items-center justify-content-center g-0  min-vh-100">
        <div class="col-12 col-md-8 col-lg-6 col-xxl-4 py-8 py-xl-0">
            <!-- Card -->
            <div class="card smooth-shadow-md">
                <!-- Card body -->
                <div class="card-body p-6">
                    <div class="mb-4 text-center">
                        <h2 style="font-weight:800 ">WEBSITE</h2>
                        <h5>SISTEM MONITORING DAN EVALUASI PROGRAM BANTUAN SOSIAL</h5>
                    </div>

                    @error('login_failed')
                        <div class="fade alert alert-danger show d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-triangle-exclamation fa-2xl me-2"></i>
                            <div class="text-center">
                                {{ $message }}
                            </div>
                        </div>
                    @enderror

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Username -->
                        <div class="mb-3">
                            <x-input-label for="email" :value="__('Email / Username')" />
                            <x-input id="email" type="email" name="email"
                                class="{{ $errors->has('email') ? 'is-invalid' : '' }}" :value="old('email')" autofocus
                                autocomplete="username" placeholder="Email address here" required />
                            <x-input-error :messages="$errors->get('email')" />

                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <x-input-label for="email" value="{{ __('Password') }}" />
                            <x-input id="password" name="password"
                                class="{{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" placeholder="***"
                                required />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>
                        <!-- Checkbox -->

                        <div>
                            <!-- Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Sign
                                    in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
