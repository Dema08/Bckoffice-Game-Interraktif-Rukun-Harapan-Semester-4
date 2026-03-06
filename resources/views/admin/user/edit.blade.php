@extends('admin.layout.main')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <h4 class="card-title">Edit User</h4>
                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" value="{{ $user->username }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-right">
                                            <a href="#" class="toggle-password" data-target="#password"><em class="icon ni ni-eye-off" id="toggleIcon"></em></a>
                                        </div>
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Role</label>
                                    <select name="role_id" class="form-control" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Perbarui</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
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
        title: 'Gagal Memperbarui User!',
        text: errorMessages,
        confirmButtonText: 'Oke'
    });
</script>
@endif
@endsection
