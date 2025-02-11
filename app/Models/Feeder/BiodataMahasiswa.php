<?php

namespace App\Models\Feeder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BiodataMahasiswa extends Model
{
    use HasFactory;

    public static function sync_biodata_mahasiswa($nama_mahasiswa, $jenis_kelamin, $jalan, $rt, $rw, $dusun, $kelurahan, $kode_pos, $nisn, $nik, $tempat_lahir, $tanggal_lahir, $nama_ayah, $tanggal_lahir_ayah, $id_jenjang_pendidikan_ayah, $id_pekerjaan_ayah, $id_penghasilan_ayah, $id_kebutuhan_khusus_ayah, $nik_ayah, $nama_ibu_kandung, $tanggal_lahir_ibu, $nik_ibu, $id_jenjang_pendidikan_ibu, $id_pekerjaan_ibu, $id_penghasilan_ibu, $id_kebutuhan_khusus_ibu, $nama_wali, $tanggal_lahir_wali, $id_jenjang_pendidikan_wali, $id_pekerjaan_wali, $id_penghasilan_wali, $id_kebutuhan_khusus_mahasiswa, $telepon, $handphone, $email, $penerima_kps, $no_kps, $npwp, $id_wilayah, $id_jenis_tinggal, $id_agama, $id_alat_trasnportasi, $kewarganegaraan, $id_mahasiswa){
        return DB::select('SELECT * FROM feeder.sync_biodata_mahasiswa_with_feeder(:id_mahasiswa, :nama_mahasiswa, :jenis_kelamin, :jalan, :rt, :rw, :dusun, :kelurahan, :kode_pos, :nisn, :nik, :tempat_lahir, :tanggal_lahir, :nama_ayah, :tanggal_lahir_ayah, :id_jenjang_pendidikan_ayah, :id_pekerjaan_ayah, :id_penghasilan_ayah, :id_kebutuhan_khusus_ayah, :nik_ayah, :nama_ibu_kandung, :tanggal_lahir_ibu, :nik_ibu, :id_jenjang_pendidikan_ibu, :id_pekerjaan_ibu, :id_penghasilan_ibu, :id_kebutuhan_khusus_ibu, :nama_wali, :tanggal_lahir_wali, :id_jenjang_pendidikan_wali, :id_pekerjaan_wali, :id_penghasilan_wali, :id_kebutuhan_khusus_mahasiswa, :telepon, :handphone, :email, :penerima_kps, :no_kps, :npwp, :id_wilayah, :id_jenis_tinggal, :id_agama, :id_alat_trasnportasi, :kewarganegaraan)', [
            'id_mahasiswa' => $id_mahasiswa, 'nama_mahasiswa' => $nama_mahasiswa, 'jenis_kelamin' => $jenis_kelamin,
            'jalan' => $jalan, 'rt' => $rt, 'rw' => $rw, 'dusun' => $dusun, 'kelurahan' => $kelurahan,
            'kode_pos' => $kode_pos, 'nisn' => $nisn, 'nik' => $nik, 'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir, 'nama_ayah' => $nama_ayah, 'tanggal_lahir_ayah' => $tanggal_lahir_ayah,
            'id_jenjang_pendidikan_ayah' => $id_jenjang_pendidikan_ayah, 'id_pekerjaan_ayah' => $id_pekerjaan_ayah,
            'id_penghasilan_ayah' => $id_penghasilan_ayah, 'id_kebutuhan_khusus_ayah' => $id_kebutuhan_khusus_ayah,
            'nik_ayah' => $nik_ayah, 'nama_ibu_kandung' => $nama_ibu_kandung, 'tanggal_lahir_ibu' => $tanggal_lahir_ibu,
            'nik_ibu' => $nik_ibu, 'id_jenjang_pendidikan_ibu' => $id_jenjang_pendidikan_ibu,
            'id_pekerjaan_ibu' => $id_pekerjaan_ibu, 'id_penghasilan_ibu' => $id_penghasilan_ibu,
            'id_kebutuhan_khusus_ibu' => $id_kebutuhan_khusus_ibu, 'nama_wali' => $nama_wali,
            'tanggal_lahir_wali' => $tanggal_lahir_wali, 'id_jenjang_pendidikan_wali' => $id_jenjang_pendidikan_wali,
            'id_pekerjaan_wali' => $id_pekerjaan_wali, 'id_penghasilan_wali' => $id_penghasilan_wali,
            'id_kebutuhan_khusus_mahasiswa' => $id_kebutuhan_khusus_mahasiswa, 'telepon' => $telepon,
            'handphone' => $handphone, 'email' => $email, 'penerima_kps' => $penerima_kps, 'no_kps' => $no_kps,
            'npwp' => $npwp, 'id_wilayah' => $id_wilayah, 'id_jenis_tinggal' => $id_jenis_tinggal, 'id_agama' => $id_agama,
            'id_alat_trasnportasi' => $id_alat_trasnportasi, 'kewarganegaraan' => $kewarganegaraan
        ])[0];
    }
}
