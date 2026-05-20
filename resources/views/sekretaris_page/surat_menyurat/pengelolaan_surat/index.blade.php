@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Sekretaris</li>
            <li class="breadcrumb-item active">{{ $menu }}</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-file-alt"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">
                    {{ $menu }}
                </h1>
                <small>
                    Halaman ini digunakan untuk mengelola surat menyurat yang digunakan oleh sekretaris dalam menjalankan
                    tugasnya.
                </small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
@endsection

@section('modal')
@endsection

@push('scripts')
    <script>
        var user = @json(session()->get('user'));
    </script>

    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/moment/moment.min.js') }}"></script>
@endpush
