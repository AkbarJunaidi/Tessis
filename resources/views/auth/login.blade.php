@extends('layouts.auth')

@section('title', 'Masuk Aplikasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="submit" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">MIS PROJECT</h3>
            <p class="text-muted">Management Information System</p>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold text-center mb-4">LOGIN</h5>

                <form action="{{ url('/login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="nama@email.com"
                               value="{{ old('email') }}"
                               required
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check d-flex justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
                            <label class="form-check-label text-secondary small" for="remember">Remember Me</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-medium rounded-2">
                        MASUK
                    </button>
                </form>

            </div>
        </div>

        <div class="text-center mt-4">
            <small class="text-muted">&copy; {{ date('Y') }} - MIS Developer Team</small>
        </div>

    </div>
</div>
@endsection
