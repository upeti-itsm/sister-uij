<?php

namespace App\Models\SertifikatUpeti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanSertifikat extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengajuan_sertifikat';
    protected $table = 'sertifikat_upeti.pengajuan_sertifikat';
    public $timestamps = false;
    protected $casts = [
        'id_pengajuan_sertifikat' => 'string'
    ];
    protected $keyType = 'string';

    public static function get_daftar_pengajuan_sertifikat($id = '00000000-0000-0000-0000-000000000000', $search = '', $offset = 0, $limit = 10, $status = 0)
    {
        return DB::select('SELECT * FROM sertifikat_upeti.get_daftar_pengajuan_sertifikat(:id, :search, :offset, :limit, :status)', [
            'id' => $id,
            'search' => $search,
            'offset' => $offset,
            'limit' => $limit,
            'status' => $status,
        ]);
    }

    public static function add_pengajuan($nim)
    {
        return DB::select('select * from sertifikat_upeti.add_pengajuan_sertifikat(:nim_pengaju)', [
            'nim_pengaju' => $nim
        ])[0];
    }

    public static function denied_pengajuan($id_pengajuan, $alasan)
    {
        return DB::select('select * from sertifikat_upeti.reject_pengajuan_sertifikat(:id_pengajuan_sertifikat, :alasan_penolakan)', ['id_pengajuan_sertifikat' => $id_pengajuan, 'alasan_penolakan' => $alasan])[0];
    }

    public static function accept_pengajuan($id_pengajuan, $nilai_huruf, $nilai_angka)
    {
        return DB::select('select * from sertifikat_upeti.accept_pengajuan_sertifikat(:id_pengajuan_sertifikat, :nilai_huruf, :nilai_angka)', ['id_pengajuan_sertifikat' => $id_pengajuan, 'nilai_huruf' => $nilai_huruf, 'nilai_angka' => $nilai_angka])[0];
    }
}
