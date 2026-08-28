<div class="dropdown action-item" data-toggle="dropdown" aria-expanded="true">
    <!-- Variabel $title agar nama tombol dinamis -->
    <a href="#" class="action-item">{{ $title ?? 'Pengaturan Gaji' }} <i class="fas fa-bars fa-fw"></i></a>
    <div class="dropdown-menu dropdown-menu-right" id="sub-menu">
        @php($peran = Session::get('peran')['aktif'])
        @if(in_array($peran, [54]))
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.pengaturan_gaji_pokok.index') }}" class="dropdown-item">Pengaturan Insentif Masa Kerja</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.index') }}" class="dropdown-item">Pengaturan Gaji Individu</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.index') }}" class="dropdown-item">Pengaturan BPJS</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.gaji_pokok.index') }}" class="dropdown-item">Pengaturan Gaji Pokok</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.index') }}" class="dropdown-item">Pengaturan UMR</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.pengaturan_hr.index') }}" class="dropdown-item">Pengaturan HR Mengajar</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.pengaturan_transportasi.index') }}" class="dropdown-item">Pengaturan Transportasi S2</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_struktural.index') }}" class="dropdown-item">Tunjangan Jabatan</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_kinerja.index') }}" class="dropdown-item">Tunjangan Kinerja</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_fungsional.index') }}" class="dropdown-item">Tunjangan Fungsional</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.insentif_lainnya.index') }}" class="dropdown-item">Insentif Lainnya</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.index') }}" class="dropdown-item">Potongan Pinjaman</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.index') }}" class="dropdown-item">Potongan Lainnya</a>
        @else
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.index') }}" class="dropdown-item">Pengaturan Gaji Individu</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.pengaturan_gaji_pokok.index') }}" class="dropdown-item">Pengaturan Insentif Masa Kerja</a>
            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_struktural.index') }}" class="dropdown-item">Tunjangan Jabatan</a>
        @endif
        <!-- Menu yang Disembunyikan Sesuai Kesepakatan -->
        <a style="display: none" href="{{ route('keuangan.penggajian.pengaturan_gaji.gaji_umum.index') }}" class="dropdown-item">Pengaturan Gaji Umum</a>
        <a style="display: none" href="{{ route('keuangan.penggajian.pengaturan_gaji.potongan_qurban.index') }}" class="dropdown-item">Potongan Qurban</a>
        <a style="display: none" href="{{ route('keuangan.penggajian.pengaturan_gaji.potongan_arisan.index') }}" class="dropdown-item">Potongan Arisan</a>
    </div>
</div>
