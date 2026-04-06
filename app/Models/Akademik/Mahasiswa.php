<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mahasiswa';
    protected $table = 'akademik.mahasiswa';
    public $timestamps = false;
    protected $casts = [
        'id_mahasiswa' => 'string'
    ];
    protected $keyType = 'string';

    public static function get_mahasiswa($kd_prodi = '0', $jenjang_didik = '0', $offset = 0, $limit = 10, $search = "", $jenis_pendanaan = 'x', $angkatan = "0", $is_lp3i = -1, $status = '1')
    {
        return DB::select('SELECT * FROM akademik.get_mahasiswa(:kd_prodi, :id_jenjang_didik, :offset, :limit, :search, :jenis_pendanaan, :angkatan, :is_lp3i, :status)', [
            'kd_prodi' => $kd_prodi,
            'id_jenjang_didik' => $jenjang_didik,
            'search' => $search,
            'offset' => $offset,
            'limit' => $limit,
            'jenis_pendanaan' => $jenis_pendanaan,
            'angkatan' => $angkatan,
            'is_lp3i' => $is_lp3i,
            'status' => $status
        ]);
    }

    public static function insup_mahasiswa($nim, $nisn, $id_program_studi, $id_mahasiswa, $status_feeder, $id_perguruan_tinggi,
                                           $id_registrasi_mahasiswa, $id_semester_masuk, $nama_mahasiswa,
                                           $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $id_agama, $nik,
                                           $npwp, $kd_negara, $alamat, $dusun, $rt, $rw, $kelurahan, $kode_pos,
                                           $id_wilayah, $id_jenis_tinggal, $id_alat_transportasi, $telepon,
                                           $handphone, $email, $penerima_kps, $nomor_kps, $nik_ayah, $nama_ayah,
                                           $tanggal_lahir_ayah, $id_jenjang_pendidikan_ayah,
                                           $id_pekerjaan_ayah, $id_penghasilan_ayah, $nik_ibu,
                                           $nama_ibu, $tanggal_lahir_ibu, $id_jenjang_pendidikan_ibu,
                                           $id_pekerjaan_ibu, $id_penghasilan_ibu,
                                           $nama_wali, $tanggal_lahir_wali, $id_jenjang_pendidikan_wali,
                                           $id_pekerjaan_wali, $id_penghasilan_wali, $id_kebutuhan_khusus_mahasiswa, $id_kebutuhan_khusus_ayah, $id_kebutuhan_khusus_ibu)
    {
        return DB::select('SELECT * FROM akademik.sync_biodata_mahasiswa_with_feeder(:nim::character varying, :nisn::character varying, :id_prodi::uuid, :id_mahasiswa::uuid, :status_feeder::character varying, :id_perguruan_tinggi::uuid,
                                           :id_registrasi_mahasiswa::uuid, :id_semester_masuk::character varying, :nama_mahasiswa::character varying,
                                           :jenis_kelamin::character varying, :tempat_lahir::character varying, :tanggal_lahir::date, :id_agama::integer, :nik::character varying,
                                           :npwp::character varying, :kd_negara::character varying, :alamat::text, :dusun::character varying, :rt::integer, :rw::integer, :kelurahan::character varying, :kode_pos::character varying,
                                           :id_wilayah::character varying, :id_jenis_tinggal::integer, :id_alat_transportasi::integer, :telepon::character varying,
                                           :handphone::character varying, :email::character varying, :penerima_kps::character varying, :nomor_kps::character varying, :nik_ayah::character varying, :nama_ayah::character varying,
                                           :tanggal_lahir_ayah::date, :id_jenjang_pendidikan_ayah::integer,
                                           :id_pekerjaan_ayah::integer, :id_penghasilan_ayah::integer, :nik_ibu::character varying,
                                           :nama_ibu::character varying, :tanggal_lahir_ibu::date, :id_jenjang_pendidikan_ibu::integer,
                                           :id_pekerjaan_ibu::integer, :id_penghasilan_ibu::integer,
                                           :nama_wali::character varying, :tanggal_lahir_wali::date, :id_jenjang_pendidikan_wali::integer,
                                           :id_pekerjaan_wali::integer, :id_penghasilan_wali::integer)',
            [
                'nim' => $nim, 'nisn' => $nisn, 'id_prodi' => $id_program_studi, 'id_mahasiswa' => $id_mahasiswa, 'status_feeder' => $status_feeder, 'id_perguruan_tinggi' => $id_perguruan_tinggi,
                'id_registrasi_mahasiswa' => $id_registrasi_mahasiswa, 'id_semester_masuk' => $id_semester_masuk, 'nama_mahasiswa' => $nama_mahasiswa,
                'jenis_kelamin' => $jenis_kelamin, 'tempat_lahir' => $tempat_lahir, 'tanggal_lahir' => $tanggal_lahir, 'id_agama' => $id_agama, 'nik' => $nik,
                'npwp' => $npwp, 'kd_negara' => $kd_negara, 'alamat' => $alamat, 'dusun' => $dusun, 'rt' => $rt, 'rw' => $rw, 'kelurahan' => $kelurahan, 'kode_pos' => $kode_pos,
                'id_wilayah' => $id_wilayah, 'id_jenis_tinggal' => $id_jenis_tinggal, 'id_alat_transportasi' => $id_alat_transportasi, 'telepon' => $telepon,
                'handphone' => $handphone, 'email' => $email, 'penerima_kps' => $penerima_kps, 'nomor_kps' => $nomor_kps, 'nik_ayah' => $nik_ayah, 'nama_ayah' => $nama_ayah,
                'tanggal_lahir_ayah' => $tanggal_lahir_ayah, 'id_jenjang_pendidikan_ayah' => $id_jenjang_pendidikan_ayah,
                'id_pekerjaan_ayah' => $id_pekerjaan_ayah, 'id_penghasilan_ayah' => $id_penghasilan_ayah, 'nik_ibu' => $nik_ibu,
                'nama_ibu' => $nama_ibu, 'tanggal_lahir_ibu' => $tanggal_lahir_ibu, 'id_jenjang_pendidikan_ibu' => $id_jenjang_pendidikan_ibu,
                'id_pekerjaan_ibu' => $id_pekerjaan_ibu, 'id_penghasilan_ibu' => $id_penghasilan_ibu,
                'nama_wali' => $nama_wali, 'tanggal_lahir_wali' => $tanggal_lahir_wali, 'id_jenjang_pendidikan_wali' => $id_jenjang_pendidikan_wali,
                'id_pekerjaan_wali' => $id_pekerjaan_wali, 'id_penghasilan_wali' => $id_penghasilan_wali
            ])[0];
        //, :id_kebutuhan_khusus_mahasiswa, :id_kebutuhan_khusus_ayah, :id_kebutuhan_khusus_ibu
        //, 'id_kebutuhan_khusus_mahasiswa' => $id_kebutuhan_khusus_mahasiswa, 'id_kebutuhan_khusus_ayah' => $id_kebutuhan_khusus_ayah, 'id_kebutuhan_khusus_ibu' => $id_jenjang_pendidikan_ibu
    }

    public static function sync_mahasiswa_with_siakad($npk, $inf_nisn, $dosen_wali, $tgl_lulus_sma, $inf_jurusan_sma, $sekolah_asal, $inf_tgl_lulus, $inf_nomor_ijazah, $nomor_transkrip, $status_aktif, $program_id, $konsentrasi_id, $nama_wali, $pekerjaan_wali, $jenis_mahasiswa, $jenis_pendanaan, $nomor_seri_ijazah, $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $agama_id, $status_menikah, $hp, $telepon_rumah, $alamat_rumah, $kode_pos_rumah, $inf_warga_negara, $email, $nik, $rt, $rw, $ds_kel, $nama_ibu, $password, $angkatan, $jenis_kelas, $judul_skripsi, $ipk, $kota_rumah)
    {
        if ($tgl_lulus_sma == "1900-00-00" || $tgl_lulus_sma == "1999-00-00" || $tgl_lulus_sma == "0000-00-00") {
            $tgl_lulus_sma = "0001-01-01";
        }
        if ($inf_tgl_lulus == "1900-00-00" || $inf_tgl_lulus == "1999-00-00" || $inf_tgl_lulus == "0000-00-00") {
            $inf_tgl_lulus = "0001-01-01";
        }
        if ($tanggal_lahir == "1900-00-00" || $tanggal_lahir == "1999-00-00" || $tanggal_lahir == "0000-00-00") {
            $tanggal_lahir = "0001-01-01";
        }

        return DB::select('SELECT * FROM akademik.sync_biodata_mahasiswa_with_siakad(:npk, :inf_nisn, :dosen_wali, :tgl_lulus_sma, :inf_jurusan_sma, :sekolah_asal, :inf_tgl_lulus, :inf_nomor_ijazah, :nomor_transkrip, :status_aktif, :program_id, :konsentrasi_id, :nama_wali, :pekerjaan_wali, :jenis_mahasiswa, :jenis_pendanaan, :nomor_seri_ijazah, :nama_lengkap, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :agama_id, :status_menikah, :hp, :telepon_rumah, :alamat_rumah, :kode_pos_rumah, :inf_warga_negara, :email, :nik, :rt, :rw, :ds_kel, :nama_ibu, :password, :angkatan, :jenis_kelas, :judul_skripsi, :ipk, :kota_rumah)', [
            'npk' => $npk, 'inf_nisn' => $inf_nisn, 'dosen_wali' => $dosen_wali, 'tgl_lulus_sma' => $tgl_lulus_sma,
            'inf_jurusan_sma' => $inf_jurusan_sma, 'sekolah_asal' => $sekolah_asal, 'inf_tgl_lulus' => $inf_tgl_lulus,
            'inf_nomor_ijazah' => $inf_nomor_ijazah, 'nomor_transkrip' => $nomor_transkrip, 'status_aktif' => $status_aktif,
            'program_id' => $program_id, 'konsentrasi_id' => $konsentrasi_id, 'nama_wali' => $nama_wali,
            'pekerjaan_wali' => $pekerjaan_wali, 'jenis_mahasiswa' => $jenis_mahasiswa, 'jenis_pendanaan' => $jenis_pendanaan,
            'nomor_seri_ijazah' => $nomor_seri_ijazah, 'nama_lengkap' => $nama_lengkap, 'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir, 'jenis_kelamin' => $jenis_kelamin, 'agama_id' => $agama_id,
            'status_menikah' => $status_menikah, 'hp' => $hp, 'telepon_rumah' => $telepon_rumah, 'alamat_rumah' => $alamat_rumah,
            'kode_pos_rumah' => $kode_pos_rumah, 'inf_warga_negara' => $inf_warga_negara, 'email' => $email, 'nik' => $nik,
            'rt' => $rt, 'rw' => $rw, 'ds_kel' => $ds_kel, 'nama_ibu' => $nama_ibu, 'password' => $password, 'angkatan' => $angkatan,
            'jenis_kelas' => $jenis_kelas, 'judul_skripsi' => $judul_skripsi, 'ipk' => $ipk, 'kota_rumah' => $kota_rumah
        ])[0];
    }

    public static function get_list_angkatan()
    {
        return DB::select('SELECT * FROM akademik.get_list_angkatan()');
    }

    public static function is_in_suspended_criteria($nim)
    {
        return DB::select('SELECT * FROM akademik.is_in_suspended_criteria(:nim)', [
            'nim' => $nim
        ])[0];
    }

    public static function set_mahasiswa_lp3i($nim, $status){
        return DB::select('SELECT * FROM akademik.set_mahasiswa_lp3i(:nim, :status)', [
            'nim' => $nim, 'status' => $status
        ])[0];
    }

    public static function get_mahasiswa_by_id_personal_dosen_wali($id_personal, $id_jenis = 1, $id_semester = 0, $offset = -1, $limit = 10, $search = ''){
        return DB::select('SELECT * FROM akademik.get_mahasiswa_dosen_wali(:id_personal, :id_jenis, :id_semester, :offset, :limit, :search)', [
            'id_personal' => $id_personal, 'id_jenis' => $id_jenis, 'id_semester' => $id_semester, 'offset' => $offset, 'limit' => $limit, 'search' => $search
        ]);
    }

    public static function get_informasi_mahasiswa(){
        return DB::select('SELECT * FROM akademik.get_rekap_informasi_mahasiswa()')[0];
    }

    public static function get_student_body($tahun, $id_prodi){
        return DB::select('SELECT * FROM akademik.get_daftar_student_body(?,?)', [
            $tahun, $id_prodi
        ]);
    }

    public static function get_detail_student_body($jenis, $tahun, $prodi = '00000000'){
        return DB::select('SELECT * FROM akademik.get_detail_calon_mahasiswa_student_body(?,?,?)', [
            $jenis, $tahun, $prodi
        ]);
    }
    public static function sync_mahasiswa_from_pmb($tahun_seleksi)
    {
        return DB::select('SELECT * FROM akademik.sync_biodata_mahasiswa_from_pmb(:tahun)', [
            'tahun' => $tahun_seleksi
        ])[0];
    }

    public static function update_status_mahasiswa($nim, $status, $alasan = '')
    {
        return DB::select('SELECT * FROM akademik.update_status_kuliah_mahasiswa(:nim, :status, :id_personal, :alasan)', [
            'nim' => $nim,
            'status' => $status,
            'id_personal' => Session::get('user')->id_personal,
            'alasan' => $alasan
        ])[0];
    }

    public static function get_mahasiswa_by_nim($nim)
    {
        $result = DB::select('SELECT * FROM akademik.get_detail_mahasiswa_by_nim(:nim)', [
            'nim' => $nim
        ]);
        return $result ?  $result[0] :null;
    }

    public static function update_profil_mahasiswa_by_mhs($nim, $email, $telp, $path_photo, $alamat)
    {
        return DB::selectOne('SELECT * FROM akademik.update_profil_mahasiswa_by_mhs(
            :p_nim,
            :p_email,
            :p_telp,
            :p_path_photo,
            :p_alamat
        )', [
            'p_nim' => $nim,
            'p_email' => $email,
            'p_telp' => $telp,
            'p_path_photo' => $path_photo,
            'p_alamat' => $alamat,
        ]);
    }

    public static function update_mahasiswa(
        $nim,
        $nama_mahasiswa = null,
        $nik = null,
        $nisn = null,
        $kd_jenis_kelamin = null,
        $tempat_lahir = null,
        $tanggal_lahir = null,
        $id_agama = null,
        $is_kawin = null,
        $kewarganegaraan = null,
        // Kontak
        $email = null,
        $handphone = null,
        $telepon = null,
        // Alamat
        $alamat = null,
        $rt = null,
        $rw = null,
        $kelurahan = null,
        $kode_pos = null,
        $kota_rumah = null,
        // Data Keluarga
        $nama_ibu = null,
        $nama_wali = null,
        // Data Akademik
        $kd_prodi = null,
        $angkatan = null,
        $kd_status_mahasiswa = null,
        $kd_jenis_mahasiswa = null,
        $kd_jenis_pendanaan = null,
        $jenis_kelas_siakad = null,
        $dosen_wali = null,
        $jenis_tinggal = null,
        $alat_transportasi,
        // Data Pendidikan Sebelumnya
        $sekolah_asal = null,
        $jurusan_sma = null,
        $tgl_lulus_sma = null,
        $nomor_ijazah = null,
        $nomor_seri_ijazah = null,
        $nomor_transkrip = null,
        // Data Kelulusan
        $tgl_lulus = null,
        $judul_skripsi = null,
        $ipk = null
    ) {
        try {
            $result = DB::selectOne('SELECT * FROM akademik.update_mahasiswa_by_nim(
            :p_nim,
            :p_nama_mahasiswa,
            :p_nik,
            :p_nisn,
            :p_kd_jenis_kelamin,
            :p_tempat_lahir,
            :p_tanggal_lahir,
            :p_id_agama,
            :p_is_kawin,
            :p_kewarganegaraan,
            :p_email,
            :p_handphone,
            :p_telepon,
            :p_alamat,
            :p_rt,
            :p_rw,
            :p_kelurahan,
            :p_kode_pos,
            :p_kota_rumah,
            :p_nama_ibu,
            :p_nama_wali,
            :p_kd_prodi,
            :p_angkatan,
            :p_kd_status_mahasiswa,
            :p_kd_jenis_mahasiswa,
            :p_kd_jenis_pendanaan,
            :p_jenis_kelas_siakad,
            :p_dosen_wali,
            :p_sekolah_asal,
            :p_jurusan_sma,
            :p_tgl_lulus_sma,
            :p_nomor_ijazah,
            :p_nomor_seri_ijazah,
            :p_nomor_transkrip,
            :p_tgl_lulus,
            :p_judul_skripsi,
            :p_ipk,
            :p_jenis_tinggal,
            :p_alat_transportasi
        )', [
                'p_nim' => $nim,
                'p_nama_mahasiswa' => $nama_mahasiswa,
                'p_nik' => $nik,
                'p_nisn' => $nisn,
                'p_kd_jenis_kelamin' => $kd_jenis_kelamin,
                'p_tempat_lahir' => $tempat_lahir,
                'p_tanggal_lahir' => $tanggal_lahir,
                'p_id_agama' => $id_agama,
                'p_is_kawin' => $is_kawin,
                'p_kewarganegaraan' => $kewarganegaraan,
                'p_email' => $email,
                'p_handphone' => $handphone,
                'p_telepon' => $telepon,
                'p_alamat' => $alamat,
                'p_rt' => $rt,
                'p_rw' => $rw,
                'p_kelurahan' => $kelurahan,
                'p_kode_pos' => $kode_pos,
                'p_kota_rumah' => $kota_rumah,
                'p_nama_ibu' => $nama_ibu,
                'p_nama_wali' => $nama_wali,
                'p_kd_prodi' => $kd_prodi,
                'p_angkatan' => $angkatan,
                'p_kd_status_mahasiswa' => $kd_status_mahasiswa,
                'p_kd_jenis_mahasiswa' => $kd_jenis_mahasiswa,
                'p_kd_jenis_pendanaan' => $kd_jenis_pendanaan,
                'p_jenis_kelas_siakad' => $jenis_kelas_siakad,
                'p_jenis_tinggal' => $jenis_tinggal,
                'p_alat_transportasi' => $alat_transportasi,
                'p_dosen_wali' => $dosen_wali,
                'p_sekolah_asal' => $sekolah_asal,
                'p_jurusan_sma' => $jurusan_sma,
                'p_tgl_lulus_sma' => $tgl_lulus_sma,
                'p_nomor_ijazah' => $nomor_ijazah,
                'p_nomor_seri_ijazah' => $nomor_seri_ijazah,
                'p_nomor_transkrip' => $nomor_transkrip,
                'p_tgl_lulus' => $tgl_lulus,
                'p_judul_skripsi' => $judul_skripsi,
                'p_ipk' => $ipk
            ]);

            if (!empty($result)) {
                return (object)[
                    'is_success' => $result->status,
                    'result' => $result->keterangan
                ];
            }
            return (object)[
                'is_success' => false,
                'result' => 'Tidak ada response dari database'
            ];
        } catch (\Exception $e) {
            return (object)[
                'is_success' => false,
                'result' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public static function get_status_mahasiswa()
    {
        return DB::select("SELECT * FROM akademik.get_status_kuliah_mahasiswa()");
    }
}
