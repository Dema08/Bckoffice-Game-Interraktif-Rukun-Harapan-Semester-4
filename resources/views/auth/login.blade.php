<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Backoffice Game</title>
    <link rel="stylesheet" href="/admin_assets/css/dashlite.css">
    <link rel="stylesheet" href="/admin_assets/css/theme.css">
    <style>
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }
        .form-control-wrapper {
            position: relative;
        }
        .text-danger {
            color: #e85347;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="nk-wrap nk-wrap-nosidebar">
        <div class="nk-content">
            <div class="nk-block nk-block-middle nk-auth-body wide-xs">
                <div class="card card-bordered">
                    <div class="card-inner card-inner-lg">
                        <h3 class="nk-block-title text-center mb-4">Login ke Akun Anda</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ url('/login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" required>
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <div class="form-control-wrapper">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                    <span class="toggle-password icon ni ni-eye" id="togglePassword"></span>
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/admin_assets/js/bundle.js"></script>
    <script src="/admin_assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        toggle.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('ni-eye-off');
        });
    </script>

    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ session('error') }}',
            confirmButtonText: 'Coba Lagi'
        });
    </script>
    @endif

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonText: 'Lanjutkan'
        });
    </script>
    @endif
</body>
</html>
