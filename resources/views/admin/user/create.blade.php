@extends('admin.layout.main')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <h4 class="card-title">Tambah User Baru</h4>
                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-right">
                                            <a href="#" class="toggle-password" data-target="#password"><em class="icon ni ni-eye-off" id="toggleIcon"></em></a>
                                        </div>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Role</label>
                                    <select name="role_id" class="form-control" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        Simpan
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-light">
                                        Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelector('.toggle-password').addEventListener('click', function(e) {
        e.preventDefault();
        const passwordInput = document.querySelector(this.dataset.target);
        const icon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('ni-eye-off');
            icon.classList.add('ni-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('ni-eye');
            icon.classList.add('ni-eye-off');
        }
    });
</script>

@if ($errors->any())
<script>
    let errorMessages = '';
    @foreach ($errors->all() as $error)
        errorMessages += `{{ $error }}\n`;
    @endforeach

    Swal.fire({
        icon: 'error',
        title: 'Gagal Menambahkan User!',
        text: errorMessages,
        confirmButtonText: 'Oke'
    });
</script>
@endif
@endsection
