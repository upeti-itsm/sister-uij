<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PendaftarWisuda extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengajuan_sertifikat';
    protected $table = 'sertifikat_upeti.pengajuan_sertifikat';
    public $timestamps = false;
    protected $casts = [
        'id_pengajuan_sertifikat' => 'string'
    ];
    protected $keyType = 'string';

    public static function get_daftar_pengajuan_wisuda($id = '00000000-0000-0000-0000-000000000000', $search = '', $offset = 0, $limit = 10, $status = 0, $kd_prodi = 'all', $kd_konsen = 'all')
    {
        return DB::select('SELECT * FROM akademik.get_daftar_pendaftaran_wisuda(:id, :search, :offset, :limit, :status, :kd_prodi, :kd_konsen)', [
            'id' => $id,
            'search' => $search,
            'offset' => $offset,
            'limit' => $limit,
            'status' => $status,
            'kd_prodi' => $kd_prodi,
            'kd_konsen' => $kd_konsen,
        ]);
    }

    public static function add_pengajuan($id_mhs, $path_dokumen, $dpu, $dpa, $kesan_pesan)
    {
        return DB::select('SELECT * FROM akademik.insert_pendaftaran_wisuda(:id_mhs, :path_dokumen, :dpu, :dpa, :kesan_pesan)', [
            'id_mhs' => $id_mhs,
            'path_dokumen' => $path_dokumen,
            'dpu' => $dpu,
            'dpa' => $dpa,
            'kesan_pesan' => $kesan_pesan
        ])[0];
    }

    public static function validasi_pendaftaran_wisuda($id_pendaftaran, $id_personal_verifikator, $id_status_persetujuan, $alasan_penolakan = '-', $ipk = '0.0', $dpu = '00000000-0000-0000-0000-000000000000', $dpa = '00000000-0000-0000-0000-000000000000')
    {
        return DB::select('SELECT * FROM akademik.validasi_pendaftaran_wisuda(:id_pendaftaran, :id_personal_verifikator, :id_status_persetujuan, :alasan, :ipk, :dpu, :dpa)', [
            'id_pendaftaran' => $id_pendaftaran, 'id_personal_verifikator' => $id_personal_verifikator,
            'id_status_persetujuan' => $id_status_persetujuan, 'alasan' => $alasan_penolakan, 'ipk' => $ipk,
            'dpu' => $dpu, 'dpa' => $dpa
        ])[0];
    }

    public static function get_identitas_kartu_wisuda($nim)
    {
        return DB::select('SELECT * FROM akademik.get_identitas_kartu_wisuda(:nim)', [
            'nim' => $nim
        ])[0];
    }

    public static function get_last_pengajuan($nim){
        return DB::select('SELECT * FROM akademik.get_last_pengajuan(:nim)', [
            'nim' => $nim
        ]);
    }
}
