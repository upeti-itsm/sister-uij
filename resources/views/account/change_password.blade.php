@extends('sidebar')

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akun</li>
            <li class="breadcrumb-item active">Ganti Password</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-key"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Ganti Password</h1>
                <small>Halaman ini digunakan untuk mengubah password akun</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('account.password.update') }}">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="old_pass" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_pass" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Ulangi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="retype_new_pass" required>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
