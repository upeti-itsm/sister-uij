<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanSurat extends Model
{
    use HasFactory;
    protected $table = 'akademik.pengajuan_surat';
    public $timestamps = false;

    public static function get_list_mahasiswa($nim, $status = null, $jenis = null, $search = '', $offset = -1, $limit = 10)
    {
        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_cuti_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status' => $status,
            'p_tahun_akademik' => null,
            'p_nim' => $nim,
            'p_id_personal_akses' => null,
            'p_peran' => null,
            'p_param_search' => $search ?? '',
            'p_no_page' => $offset,
            'p_jml_record_perpage' => $limit,
        ]);

        return [
            'total' => count($data) > 0 ? $data[0]->jml_record ?? count($data) : 0,
            'data' => $data,
        ];
    }

    public static function insup_pengajuan($nim, $keperluan, $tahunAkademik = null, $idRiwayat = null)
    {
        return DB::selectOne('SELECT * FROM akademik.insup_pengajuan_cuti_mahasiswa(:p_id_riwayat_pengajuan_cuti_mahasiswa, :p_nim, :p_keperluan, :p_tahun_akademik, :p_status_pengajuan)', [
            'p_id_riwayat_pengajuan_cuti_mahasiswa' => $idRiwayat,
            'p_nim' => $nim,
            'p_keperluan' => $keperluan,
            'p_tahun_akademik' => $tahunAkademik,
            'p_status_pengajuan' => '1',
        ]);
    }

    public static function set_status($idRiwayat, $status, $idPersonalMenyetujui = null, $catatan = null)
    {
        return DB::selectOne(
            'SELECT * FROM akademik.set_status_pengajuan_cuti_mahasiswa(
            :p_id_riwayat_pengajuan_cuti_mahasiswa,
            :p_status,
            :p_id_personal_menyetujui,
            :p_catatan
        )',
            [
                'p_id_riwayat_pengajuan_cuti_mahasiswa' => $idRiwayat,
                'p_status' => $status,
                'p_id_personal_menyetujui' => $idPersonalMenyetujui,
                'p_catatan' => $catatan,
            ],
        );
    }

    public static function get_detail($idRiwayat)
    {
        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_cuti_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status'               => null,
            'p_tahun_akademik'       => null,
            'p_nim'                  => null,
            'p_id_personal_akses'    => null,
            'p_peran'                => null,
            'p_param_search'         => '',
            'p_no_page'              => -1,
            'p_jml_record_perpage'   => 9999,
        ]);

        foreach ($data as $item) {
            if ((string) $item->id_riwayat_pengajuan_cuti_mahasiswa === (string) $idRiwayat) {
                return $item;
            }
        }

        return null;
    }

    public static function get_detail_with_access($idRiwayat, $idPersonal)
    {
        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_cuti_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status'               => null,
            'p_tahun_akademik'       => null,
            'p_nim'                  => null,
            'p_id_personal_akses'    => $idPersonal,
            'p_peran'                => null,
            'p_param_search'         => '',
            'p_no_page'              => -1,
            'p_jml_record_perpage'   => 9999,
        ]);

        foreach ($data as $item) {
            if ((string) $item->id_riwayat_pengajuan_cuti_mahasiswa === (string) $idRiwayat) {
                return $item;
            }
        }

        return null;
    }

    public static function get_list_dosen($idPersonal, $status = null, $search = '', $offset = -1, $limit = 10)
    {
        $statusDosenAllowed = ['1', '2', '3'];

        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_cuti_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status'               => null,
            'p_tahun_akademik'       => null,
            'p_nim'                  => null,
            'p_id_personal_akses'    => $idPersonal,
            'p_peran'    => 'dpa',
            'p_param_search'         => $search ?? '',
            'p_no_page'              => -1,
            'p_jml_record_perpage'   => 9999,
        ]);

        $data = array_values(
            array_filter($data, function ($row) use ($statusDosenAllowed, $status) {
                $rowStatus = (string) ($row->status_pengajuan ?? '');

                if ($status !== null && $status !== '') {
                    return $rowStatus === (string) $status && in_array($rowStatus, $statusDosenAllowed);
                }

                return in_array($rowStatus, $statusDosenAllowed);
            }),
        );

        $total = count($data);

        $offset = max(0, (int) $offset);
        $limit  = max(1, (int) $limit);
        $data   = array_slice($data, $offset, $limit);

        return [
            'total' => $total,
            'data'  => array_values($data),
        ];
    }

    public static function get_list_dekan($idPersonal, $status = null, $search = '', $offset = -1, $limit = 10)
    {
        // Status yang boleh dilihat dekan: 2=menunggu dekan, 4=disetujui dekan, 5=ditolak dekan
        $statusDekanAllowed = ['2', '4', '5'];

        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_cuti_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status'               => null,
            'p_tahun_akademik'       => null,
            'p_nim'                  => null,
            'p_id_personal_akses'    => $idPersonal,
            'p_peran'    => 'dekan',
            'p_param_search'         => $search ?? '',
            'p_no_page'              => -1,
            'p_jml_record_perpage'   => 9999,
        ]);

        $data = array_values(
            array_filter($data, function ($row) use ($statusDekanAllowed, $status) {
                $rowStatus = (string) ($row->status_pengajuan ?? '');

                if ($status !== null) {
                    return $rowStatus === (string) $status && in_array($rowStatus, $statusDekanAllowed);
                }

                return in_array($rowStatus, $statusDekanAllowed);
            }),
        );

        $total = count($data);

        $offset = max(0, (int) $offset);
        $limit  = max(1, (int) $limit);
        $data   = array_slice($data, $offset, $limit);

        return [
            'total' => $total,
            'data'  => array_values($data),
        ];
    }

    // SURAT AKTIF — LIST MAHASISWA
    public static function get_list_aktif_mahasiswa($nim, $status = null, $jenis = null, $search = '', $offset = -1, $limit = 10)
    {
        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_surat_aktif_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status'             => $status,
            'p_tahun_akademik'     => null,
            'p_nim'                => $nim,
            'p_id_personal_akses'  => null,
            'p_peran'              => null,
            'p_param_search'       => $search ?? '',
            'p_no_page'            => $offset,
            'p_jml_record_perpage' => $limit,
        ]);

        return [
            'total' => count($data) > 0 ? $data[0]->jml_record ?? count($data) : 0,
            'data'  => $data,
        ];
    }

    // SURAT AKTIF — LIST DOSEN
    public static function get_list_aktif_dosen($idPersonal, $status = null, $search = '', $offset = -1, $limit = 10)
    {
        $statusDosenAllowed = ['1', '2', '3'];

        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_surat_aktif_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status'             => null,
            'p_tahun_akademik'     => null,
            'p_nim'                => null,
            'p_id_personal_akses'  => $idPersonal,
            'p_peran'              => 'dpa',
            'p_param_search'       => $search ?? '',
            'p_no_page'            => -1,
            'p_jml_record_perpage' => 9999,
        ]);

        $data = array_values(
            array_filter($data, function ($row) use ($statusDosenAllowed, $status) {
                $rowStatus = (string) ($row->status_pengajuan ?? '');

                if ($status !== null && $status !== '') {
                    return $rowStatus === (string) $status && in_array($rowStatus, $statusDosenAllowed);
                }

                return in_array($rowStatus, $statusDosenAllowed);
            }),
        );

        $total  = count($data);
        $offset = max(0, (int) $offset);
        $limit  = max(1, (int) $limit);
        $data   = array_slice($data, $offset, $limit);

        return [
            'total' => $total,
            'data'  => array_values($data),
        ];
    }

    // SURAT AKTIF — LIST DEKAN
    public static function get_list_aktif_dekan($idPersonal = null, $status = null, $search = '', $offset = -1, $limit = 10)
    {
        $statusDekanAllowed = ['2', '4', '5'];

        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_surat_aktif_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status'             => null,
            'p_tahun_akademik'     => null,
            'p_nim'                => null,
            'p_id_personal_akses'  => null,
            'p_peran'  => 'dekan',
            'p_param_search'       => $search ?? '',
            'p_no_page'            => -1,
            'p_jml_record_perpage' => 9999,
        ]);

        $data = array_values(
            array_filter($data, function ($row) use ($statusDekanAllowed, $status) {
                $rowStatus = (string) ($row->status_pengajuan ?? '');

                if ($status !== null) {
                    return $rowStatus === (string) $status && in_array($rowStatus, $statusDekanAllowed);
                }

                return in_array($rowStatus, $statusDekanAllowed);
            }),
        );

        $total  = count($data);
        $offset = max(0, (int) $offset);
        $limit  = max(1, (int) $limit);
        $data   = array_slice($data, $offset, $limit);

        return [
            'total' => $total,
            'data'  => array_values($data),
        ];
    }

    // SURAT AKTIF — INSUP
    public static function insup_pengajuan_aktif($nim, $keperluan, $tahunAkademik = null, $id = null)
    {
        return DB::selectOne('SELECT * FROM akademik.insup_pengajuan_surat_aktif_mahasiswa(:p_id_riwayat_pengajuan_surat_aktif_mahasiswa, :p_nim, :p_keperluan, :p_tahun_akademik, :p_status_pengajuan)', [
            'p_id_riwayat_pengajuan_surat_aktif_mahasiswa' => $id,
            'p_nim'               => $nim,
            'p_keperluan'         => $keperluan,
            'p_tahun_akademik'    => $tahunAkademik,
            'p_status_pengajuan'  => '1',
        ]);
    }

    // SURAT AKTIF — GET DETAIL
    public static function get_detail_aktif($id)
    {
        $data = DB::select('SELECT * FROM akademik.get_list_pengajuan_surat_aktif_mahasiswa(:p_status, :p_tahun_akademik, :p_nim, :p_id_personal_akses, :p_peran, :p_param_search, :p_no_page, :p_jml_record_perpage)', [
            'p_status'             => null,
            'p_tahun_akademik'     => null,
            'p_nim'                => null,
            'p_id_personal_akses'  => null,
            'p_peran'              => null,
            'p_param_search'       => '',
            'p_no_page'            => -1,
            'p_jml_record_perpage' => 9999,
        ]);

        foreach ($data as $item) {
            if ((string) $item->id_riwayat_pengajuan_surat_aktif_mahasiswa === (string) $id) {
                return $item;
            }
        }

        return null;
    }

    // SURAT AKTIF — SET STATUS
    public static function set_status_aktif($id, $status, $idPersonal = null, $catatan = null)
    {
        return DB::selectOne(
            'SELECT * FROM akademik.set_status_pengajuan_surat_aktif_mahasiswa(
            :p_id_riwayat_pengajuan_surat_aktif_mahasiswa,
            :p_status,
            :p_id_personal_menyetujui,
            :p_catatan
        )',
            [
                'p_id_riwayat_pengajuan_surat_aktif_mahasiswa' => $id,
                'p_status'                => $status,
                'p_id_personal_menyetujui' => $idPersonal,
                'p_catatan'               => $catatan,
            ],
        );
    }
}
