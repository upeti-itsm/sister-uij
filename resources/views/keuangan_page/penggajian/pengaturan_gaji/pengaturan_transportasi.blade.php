@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Penggajian</li>
            <li class="breadcrumb-item active">Pengaturan Transportasi S2</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">{{ $menu }}</h1>
                <small>Halaman ini digunakan untuk melakukan pengaturan transportasi S2</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Pengaturan Transportasi S2</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            @include('partials.pengaturan_gaji_dropdown', ['title' => 'Pengaturan Transportasi S2'])
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Nomor</th>
                                    <th class="text-left align-middle">Jabatan Fungsional</th>
                                    <th class="text-left align-middle">Nominal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-edit-data-fungsional" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="editLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="width: 100%;">
                        <div class="row" style="padding: 0 0 0 0">
                            <div class="col-md-5">
                                <label class="font-weight-bold">Jabatan Fungsional</label>
                                <input type="text" class="form-control" placeholder="Masukkan Jabatan Fungsional"
                                    id="add_jabatan_fungsional">
                            </div>
                            <div class="col-md-7">
                                <label class="font-weight-bold">Nominal Transportasi S2</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp.</div>
                                    </div>
                                    <input type="text" class="form-control number" id="add_nominal_tunjangan"
                                        placeholder="Masukkan Nominal Transportasi S2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="add_form" style="display: none"
                        action="{{ route('keuangan.penggajian.pengaturan_gaji.pengaturan_transportasi.edit') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="id_jabatan_fungsional" id="id_jabatan_fungsional">
                        <input type="hidden" name="jabatan_fungsional" id="jabatan_fungsional">
                        <input type="hidden" name="nominal_tunjangan" id="nominal_tunjangan">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" disabled class="btn btn-secondary disabled" id="btn-simpan-data">Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/numeral/numeral.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/keuangan_page/penggajian/pengaturan_gaji/pengaturan_transportasi_s2.js') }}">
    </script>
    <script>
        function keyUpNumber(id) {
            var $this = document.getElementById(id);
            var input = $this.value;
            input = input.replace(/[\D\s\\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.value = input.toLocaleString("id-ID");
        }
    </script>
@endpush
