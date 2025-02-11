<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class tblMahasiswa extends Model
{
    use HasFactory;

    protected $connection = "siakad";

    public static function getAngkatan()
    {
        return DB::connection('siakad')->select("SELECT DISTINCT t1.thnakademikmasuk FROM akademik.mahasiswa t1");
    }

    public static function getStatusAktif()
    {
        return DB::connection('siakad')->select("SELECT t1.kdstatusakademik, t1.statusakademik FROM akademik.statusakademik t1");
    }

    public static function getMahasiswaAktif($angkatan)
    {
        if ($angkatan == "all") {
            return DB::connection('siakad')->select('SELECT t1.jenis_kelas, t1.NPK, t1.nama_lengkap, t1.angkatan, t1.password, t1.program_id, t1.alamat_rumah, t1.hp, t1.kota_rumah, t1.email, t2.nama_program, t1.jenis_pendanaan FROM tblMahasiswa t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE UPPER(t1.status_aktif) LIKE "A%"');
        } else {
            return DB::connection('siakad')->select('SELECT t1.jenis_kelas, t1.NPK, t1.nama_lengkap, t1.angkatan, t1.password, t1.program_id, t1.alamat_rumah, t1.hp, t1.kota_rumah, t1.email, t2.nama_program, t1.jenis_pendanaan FROM tblMahasiswa t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE UPPER(t1.status_aktif) LIKE "A%" AND t1.angkatan = :angkatan', [
                'angkatan' => $angkatan
            ]);
        }
    }

    public static function getMahasiswaByAngkatan($angkatan)
    {
        return DB::connection('siakad')->select('SELECT t1.jenis_kelas, t1.NPK, t1.nama_lengkap, t1.angkatan, t1.password, t1.program_id, t1.alamat_rumah, t1.hp, t1.kota_rumah, t1.email, t2.nama_program, t1.jenis_pendanaan, t1.inf_NISN as nisn, t1.dosen_wali, t1.tgl_lulus_sma, t1.inf_jurusan_sma, t1.sekolah_asal, t1.inf_tgl_lulus, t1.inf_nomor_ijazah, t1.inf_nomor_transkrip, t1.status_aktif, t1.program_id, t1.konsentrasi_id, t1.nama_wali, t1.pekerjaan_wali, t1.jenis_mahasiswa, t1.jenis_pendanaan, t1.no_seri_ijazah, t1.tempat_lahir, t1.tgl_lahir, t1.jenis_kelamin, t1.agama_id, t1.status_menikah, t1.hp, t1.telepon_rumah, t1.kode_pos_rumah, t1.kewarganegaraan, t1.nik, t1.rt, t1.rw, t1.ds_kel, t1.nama_ibu, t1.judul_skripsi, t1.ipk, t1.kota_rumah FROM tblMahasiswa t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE angkatan = :angkatan AND TRIM(NPK) <> ""', [
            'angkatan' => $angkatan
        ]);
    }

    public static function getDaftarMahasiswa($angkatan = "all", $status = "all", $search = "", $limit = -1, $offset = 0)
    {
        if ($angkatan == "all") {
            if ($status == "all") {
                if ($limit == -1)
                    return DB::connection('siakad')->select("SELECT t2.nim ,t2.idpersonal ,t2.kdprogramstudi ,t2.kdseleksi ,t2.tglmasuk ,t2.thnakademikmasuk ,t2.kdstatusakademikterakhir ,t2.thnakademikstatusakademikterakhir ,t2.nama ,t2.kdjeniskelamin ,t2.tgllahir ,t2.idkotalahir ,t2.kdagama ,t2.kdwarganegara ,t2.alamattinggal ,t2.idkotatinggal ,t2.namaibukandung ,t2.kdstatussipil ,t2.kdgolongandarah ,t2.noktp ,t2.nohp ,t2.kdstatusperson ,t2.kdkeberadaanperson ,t2.kdstatusdata ,t2.tgldata ,t2.seleksi ,t2.statuskuliahmhs ,t2.namafakultas ,t2.namasingkatfakultas ,t2.namaprogramstudi ,t2.namasingkatps , COUNT(*) OVER()::integer AS jml_record FROM (SELECT t1.nim FROM akademik.statusakademikmahasiswa t1) T1 INNER JOIN ( select T1.nim, T1.idpersonal, T1.kdprogramstudi, T1.kdseleksi, T1.tglmasuk, T1.thnakademikmasuk, T1.kdstatusakademikterakhir, T1.thnakademikstatusakademikterakhir, T2.nama, T2.kdjeniskelamin, T2.tgllahir, T2.idkotalahir, T2.kdagama, T2.kdwarganegara, T2.alamattinggal, T2.idkotatinggal, T2.namaibukandung, T2.kdstatussipil, T2.kdgolongandarah, T2.noktp, T2.nohp, T2.kdstatusperson, T2.kdkeberadaanperson, T2.kdstatusdata, T2.tgldata, T3.seleksi, T7.statusakademik AS statuskuliahmhs, T5.namafakultas, T5.namasingkatfakultas, T6.namaprogramstudi, T6.namasingkatps, T4.jumlahsaudara, T4.kdbiayastudi, T4.thnlulus, T4.jumlahuan, T4.nilaiuan, T4.kotalahirijazah, T8.pendidikanortu, T9.pekerjaanortu, T10.penghasilanortu, T4.kdpenghasilanortu, T4.extrakurikuler, T4.idslta, T11.jurusanslta, T4.namaayah, T4.alamatortu, T4.rt, T4.rw, T4.kelurahan, T4.kecamatan, T4.idkotaortu, T4.kodepos, T4.notelportu from akademik.mahasiswa T1 INNER JOIN person.identitas T2 ON T1.idpersonal=T2.idpersonal LEFT JOIN support.seleksi T3 ON T1.kdseleksi=T3.kdseleksi LEFT JOIN support.fakultas T5 ON t1.kdfakultas=T5.kdfakultas LEFT JOIN support.programstudi T6 ON t1.kdprogramstudi=T6.kdprogramstudi LEFT JOIN akademik.statusakademik T7 ON T1.thnakademikstatusakademikterakhir=T7.statusakademik LEFT JOIN akademik.identitasmahasiswaS0_S1 T4 ON T1.nim=T4.nim LEFT JOIN akademik.pendidikanorangTua T8 ON T4.kdpendidikanortu=T8.kdpendidikanortu LEFT JOIN akademik.pekerjaanorangtua T9 ON T4.kdpekerjaanortu=T9.kdpekerjaanortu LEFT JOIN akademik.penghasilanortu T10 ON T4.kdpenghasilanortu=T10.kdpenghasilanortu LEFT JOIN support.jurusanslta T11 ON T4.kdjurusanslta=T11.kdjurusanslta WHERE (LOWER(T1.nim) ~* LOWER(?) OR LOWER(T2.nama) ~* ?)) T2 ON T1.nim = T2.nim", [
                        $search, $search
                    ]);
                else
                    return DB::connection('siakad')->select("SELECT t2.nim ,t2.idpersonal ,t2.kdprogramstudi ,t2.kdseleksi ,t2.tglmasuk ,t2.thnakademikmasuk ,t2.kdstatusakademikterakhir ,t2.thnakademikstatusakademikterakhir ,t2.nama ,t2.kdjeniskelamin ,t2.tgllahir ,t2.idkotalahir ,t2.kdagama ,t2.kdwarganegara ,t2.alamattinggal ,t2.idkotatinggal ,t2.namaibukandung ,t2.kdstatussipil ,t2.kdgolongandarah ,t2.noktp ,t2.nohp ,t2.kdstatusperson ,t2.kdkeberadaanperson ,t2.kdstatusdata ,t2.tgldata ,t2.seleksi ,t2.statuskuliahmhs ,t2.namafakultas ,t2.namasingkatfakultas ,t2.namaprogramstudi ,t2.namasingkatps , COUNT(*) OVER()::integer AS jml_record FROM (SELECT t1.nim FROM akademik.statusakademikmahasiswa t1) T1 INNER JOIN ( select T1.nim, T1.idpersonal, T1.kdprogramstudi, T1.kdseleksi, T1.tglmasuk, T1.thnakademikmasuk, T1.kdstatusakademikterakhir, T1.thnakademikstatusakademikterakhir, T2.nama, T2.kdjeniskelamin, T2.tgllahir, T2.idkotalahir, T2.kdagama, T2.kdwarganegara, T2.alamattinggal, T2.idkotatinggal, T2.namaibukandung, T2.kdstatussipil, T2.kdgolongandarah, T2.noktp, T2.nohp, T2.kdstatusperson, T2.kdkeberadaanperson, T2.kdstatusdata, T2.tgldata, T3.seleksi, T7.statusakademik AS statuskuliahmhs, T5.namafakultas, T5.namasingkatfakultas, T6.namaprogramstudi, T6.namasingkatps, T4.jumlahsaudara, T4.kdbiayastudi, T4.thnlulus, T4.jumlahuan, T4.nilaiuan, T4.kotalahirijazah, T8.pendidikanortu, T9.pekerjaanortu, T10.penghasilanortu, T4.kdpenghasilanortu, T4.extrakurikuler, T4.idslta, T11.jurusanslta, T4.namaayah, T4.alamatortu, T4.rt, T4.rw, T4.kelurahan, T4.kecamatan, T4.idkotaortu, T4.kodepos, T4.notelportu from akademik.mahasiswa T1 INNER JOIN person.identitas T2 ON T1.idpersonal=T2.idpersonal LEFT JOIN support.seleksi T3 ON T1.kdseleksi=T3.kdseleksi LEFT JOIN support.fakultas T5 ON t1.kdfakultas=T5.kdfakultas LEFT JOIN support.programstudi T6 ON t1.kdprogramstudi=T6.kdprogramstudi LEFT JOIN akademik.statusakademik T7 ON T1.thnakademikstatusakademikterakhir=T7.statusakademik LEFT JOIN akademik.identitasmahasiswaS0_S1 T4 ON T1.nim=T4.nim LEFT JOIN akademik.pendidikanorangTua T8 ON T4.kdpendidikanortu=T8.kdpendidikanortu LEFT JOIN akademik.pekerjaanorangtua T9 ON T4.kdpekerjaanortu=T9.kdpekerjaanortu LEFT JOIN akademik.penghasilanortu T10 ON T4.kdpenghasilanortu=T10.kdpenghasilanortu LEFT JOIN support.jurusanslta T11 ON T4.kdjurusanslta=T11.kdjurusanslta WHERE (LOWER(T1.nim) ~* LOWER(?) OR LOWER(T2.nama) ~* ?)) T2 ON T1.nim = T2.nim LIMIT ? OFFSET ?", [
                        $search, $search, $limit, $offset
                    ]);
            } else {
                if ($limit == -1)
                    return DB::connection('siakad')->select("SELECT t2.nim ,t2.idpersonal ,t2.kdprogramstudi ,t2.kdseleksi ,t2.tglmasuk ,t2.thnakademikmasuk ,t2.kdstatusakademikterakhir ,t2.thnakademikstatusakademikterakhir ,t2.nama ,t2.kdjeniskelamin ,t2.tgllahir ,t2.idkotalahir ,t2.kdagama ,t2.kdwarganegara ,t2.alamattinggal ,t2.idkotatinggal ,t2.namaibukandung ,t2.kdstatussipil ,t2.kdgolongandarah ,t2.noktp ,t2.nohp ,t2.kdstatusperson ,t2.kdkeberadaanperson ,t2.kdstatusdata ,t2.tgldata ,t2.seleksi ,t2.statuskuliahmhs ,t2.namafakultas ,t2.namasingkatfakultas ,t2.namaprogramstudi ,t2.namasingkatps , COUNT(*) OVER()::integer AS jml_record FROM (SELECT t1.nim FROM akademik.statusakademikmahasiswa t1 WHERE kdstatusakademik=?) T1 INNER JOIN ( select T1.nim, T1.idpersonal, T1.kdprogramstudi, T1.kdseleksi, T1.tglmasuk, T1.thnakademikmasuk, T1.kdstatusakademikterakhir, T1.thnakademikstatusakademikterakhir, T2.nama, T2.kdjeniskelamin, T2.tgllahir, T2.idkotalahir, T2.kdagama, T2.kdwarganegara, T2.alamattinggal, T2.idkotatinggal, T2.namaibukandung, T2.kdstatussipil, T2.kdgolongandarah, T2.noktp, T2.nohp, T2.kdstatusperson, T2.kdkeberadaanperson, T2.kdstatusdata, T2.tgldata, T3.seleksi, T7.statusakademik AS statuskuliahmhs, T5.namafakultas, T5.namasingkatfakultas, T6.namaprogramstudi, T6.namasingkatps, T4.jumlahsaudara, T4.kdbiayastudi, T4.thnlulus, T4.jumlahuan, T4.nilaiuan, T4.kotalahirijazah, T8.pendidikanortu, T9.pekerjaanortu, T10.penghasilanortu, T4.kdpenghasilanortu, T4.extrakurikuler, T4.idslta, T11.jurusanslta, T4.namaayah, T4.alamatortu, T4.rt, T4.rw, T4.kelurahan, T4.kecamatan, T4.idkotaortu, T4.kodepos, T4.notelportu from akademik.mahasiswa T1 INNER JOIN person.identitas T2 ON T1.idpersonal=T2.idpersonal LEFT JOIN support.seleksi T3 ON T1.kdseleksi=T3.kdseleksi LEFT JOIN support.fakultas T5 ON t1.kdfakultas=T5.kdfakultas LEFT JOIN support.programstudi T6 ON t1.kdprogramstudi=T6.kdprogramstudi LEFT JOIN akademik.statusakademik T7 ON T1.thnakademikstatusakademikterakhir=T7.statusakademik LEFT JOIN akademik.identitasmahasiswaS0_S1 T4 ON T1.nim=T4.nim LEFT JOIN akademik.pendidikanorangTua T8 ON T4.kdpendidikanortu=T8.kdpendidikanortu LEFT JOIN akademik.pekerjaanorangtua T9 ON T4.kdpekerjaanortu=T9.kdpekerjaanortu LEFT JOIN akademik.penghasilanortu T10 ON T4.kdpenghasilanortu=T10.kdpenghasilanortu LEFT JOIN support.jurusanslta T11 ON T4.kdjurusanslta=T11.kdjurusanslta WHERE (LOWER(T1.nim) ~* LOWER(?) OR LOWER(T2.nama) ~* ?)) T2 ON T1.nim = T2.nim", [
                        $status, $search, $search
                    ]);
                else
                    return DB::connection('siakad')->select("SELECT t2.nim ,t2.idpersonal ,t2.kdprogramstudi ,t2.kdseleksi ,t2.tglmasuk ,t2.thnakademikmasuk ,t2.kdstatusakademikterakhir ,t2.thnakademikstatusakademikterakhir ,t2.nama ,t2.kdjeniskelamin ,t2.tgllahir ,t2.idkotalahir ,t2.kdagama ,t2.kdwarganegara ,t2.alamattinggal ,t2.idkotatinggal ,t2.namaibukandung ,t2.kdstatussipil ,t2.kdgolongandarah ,t2.noktp ,t2.nohp ,t2.kdstatusperson ,t2.kdkeberadaanperson ,t2.kdstatusdata ,t2.tgldata ,t2.seleksi ,t2.statuskuliahmhs ,t2.namafakultas ,t2.namasingkatfakultas ,t2.namaprogramstudi ,t2.namasingkatps , COUNT(*) OVER()::integer AS jml_record FROM (SELECT t1.nim FROM akademik.statusakademikmahasiswa t1 WHERE kdstatusakademik=?) T1 INNER JOIN ( select T1.nim, T1.idpersonal, T1.kdprogramstudi, T1.kdseleksi, T1.tglmasuk, T1.thnakademikmasuk, T1.kdstatusakademikterakhir, T1.thnakademikstatusakademikterakhir, T2.nama, T2.kdjeniskelamin, T2.tgllahir, T2.idkotalahir, T2.kdagama, T2.kdwarganegara, T2.alamattinggal, T2.idkotatinggal, T2.namaibukandung, T2.kdstatussipil, T2.kdgolongandarah, T2.noktp, T2.nohp, T2.kdstatusperson, T2.kdkeberadaanperson, T2.kdstatusdata, T2.tgldata, T3.seleksi, T7.statusakademik AS statuskuliahmhs, T5.namafakultas, T5.namasingkatfakultas, T6.namaprogramstudi, T6.namasingkatps, T4.jumlahsaudara, T4.kdbiayastudi, T4.thnlulus, T4.jumlahuan, T4.nilaiuan, T4.kotalahirijazah, T8.pendidikanortu, T9.pekerjaanortu, T10.penghasilanortu, T4.kdpenghasilanortu, T4.extrakurikuler, T4.idslta, T11.jurusanslta, T4.namaayah, T4.alamatortu, T4.rt, T4.rw, T4.kelurahan, T4.kecamatan, T4.idkotaortu, T4.kodepos, T4.notelportu from akademik.mahasiswa T1 INNER JOIN person.identitas T2 ON T1.idpersonal=T2.idpersonal LEFT JOIN support.seleksi T3 ON T1.kdseleksi=T3.kdseleksi LEFT JOIN support.fakultas T5 ON t1.kdfakultas=T5.kdfakultas LEFT JOIN support.programstudi T6 ON t1.kdprogramstudi=T6.kdprogramstudi LEFT JOIN akademik.statusakademik T7 ON T1.thnakademikstatusakademikterakhir=T7.statusakademik LEFT JOIN akademik.identitasmahasiswaS0_S1 T4 ON T1.nim=T4.nim LEFT JOIN akademik.pendidikanorangTua T8 ON T4.kdpendidikanortu=T8.kdpendidikanortu LEFT JOIN akademik.pekerjaanorangtua T9 ON T4.kdpekerjaanortu=T9.kdpekerjaanortu LEFT JOIN akademik.penghasilanortu T10 ON T4.kdpenghasilanortu=T10.kdpenghasilanortu LEFT JOIN support.jurusanslta T11 ON T4.kdjurusanslta=T11.kdjurusanslta WHERE (LOWER(T1.nim) ~* LOWER(?) OR LOWER(T2.nama) ~* ?)) T2 ON T1.nim = T2.nim LIMIT ? OFFSET ?", [
                        $status, $search, $search, $limit, $offset
                    ]);
            }
        } else {
            if ($status == "all") {
                if ($limit == -1)
                    return DB::connection('siakad')->select("SELECT t2.nim ,t2.idpersonal ,t2.kdprogramstudi ,t2.kdseleksi ,t2.tglmasuk ,t2.thnakademikmasuk ,t2.kdstatusakademikterakhir ,t2.thnakademikstatusakademikterakhir ,t2.nama ,t2.kdjeniskelamin ,t2.tgllahir ,t2.idkotalahir ,t2.kdagama ,t2.kdwarganegara ,t2.alamattinggal ,t2.idkotatinggal ,t2.namaibukandung ,t2.kdstatussipil ,t2.kdgolongandarah ,t2.noktp ,t2.nohp ,t2.kdstatusperson ,t2.kdkeberadaanperson ,t2.kdstatusdata ,t2.tgldata ,t2.seleksi ,t2.statuskuliahmhs ,t2.namafakultas ,t2.namasingkatfakultas ,t2.namaprogramstudi ,t2.namasingkatps , COUNT(*) OVER()::integer AS jml_record FROM (SELECT t1.nim FROM akademik.statusakademikmahasiswa t1) T1 INNER JOIN ( select T1.nim, T1.idpersonal, T1.kdprogramstudi, T1.kdseleksi, T1.tglmasuk, T1.thnakademikmasuk, T1.kdstatusakademikterakhir, T1.thnakademikstatusakademikterakhir, T2.nama, T2.kdjeniskelamin, T2.tgllahir, T2.idkotalahir, T2.kdagama, T2.kdwarganegara, T2.alamattinggal, T2.idkotatinggal, T2.namaibukandung, T2.kdstatussipil, T2.kdgolongandarah, T2.noktp, T2.nohp, T2.kdstatusperson, T2.kdkeberadaanperson, T2.kdstatusdata, T2.tgldata, T3.seleksi, T7.statusakademik AS statuskuliahmhs, T5.namafakultas, T5.namasingkatfakultas, T6.namaprogramstudi, T6.namasingkatps, T4.jumlahsaudara, T4.kdbiayastudi, T4.thnlulus, T4.jumlahuan, T4.nilaiuan, T4.kotalahirijazah, T8.pendidikanortu, T9.pekerjaanortu, T10.penghasilanortu, T4.kdpenghasilanortu, T4.extrakurikuler, T4.idslta, T11.jurusanslta, T4.namaayah, T4.alamatortu, T4.rt, T4.rw, T4.kelurahan, T4.kecamatan, T4.idkotaortu, T4.kodepos, T4.notelportu from akademik.mahasiswa T1 INNER JOIN person.identitas T2 ON T1.idpersonal=T2.idpersonal LEFT JOIN support.seleksi T3 ON T1.kdseleksi=T3.kdseleksi LEFT JOIN support.fakultas T5 ON t1.kdfakultas=T5.kdfakultas LEFT JOIN support.programstudi T6 ON t1.kdprogramstudi=T6.kdprogramstudi LEFT JOIN akademik.statusakademik T7 ON T1.thnakademikstatusakademikterakhir=T7.statusakademik LEFT JOIN akademik.identitasmahasiswaS0_S1 T4 ON T1.nim=T4.nim LEFT JOIN akademik.pendidikanorangTua T8 ON T4.kdpendidikanortu=T8.kdpendidikanortu LEFT JOIN akademik.pekerjaanorangtua T9 ON T4.kdpekerjaanortu=T9.kdpekerjaanortu LEFT JOIN akademik.penghasilanortu T10 ON T4.kdpenghasilanortu=T10.kdpenghasilanortu LEFT JOIN support.jurusanslta T11 ON T4.kdjurusanslta=T11.kdjurusanslta WHERE (LOWER(T1.nim) ~* LOWER(?) OR LOWER(T2.nama) ~* ?) AND T1.thnakademikmasuk = ? ) T2 ON T1.nim = T2.nim", [
                        $search, $search, $angkatan
                    ]);
                else
                    return DB::connection('siakad')->select("SELECT t2.nim ,t2.idpersonal ,t2.kdprogramstudi ,t2.kdseleksi ,t2.tglmasuk ,t2.thnakademikmasuk ,t2.kdstatusakademikterakhir ,t2.thnakademikstatusakademikterakhir ,t2.nama ,t2.kdjeniskelamin ,t2.tgllahir ,t2.idkotalahir ,t2.kdagama ,t2.kdwarganegara ,t2.alamattinggal ,t2.idkotatinggal ,t2.namaibukandung ,t2.kdstatussipil ,t2.kdgolongandarah ,t2.noktp ,t2.nohp ,t2.kdstatusperson ,t2.kdkeberadaanperson ,t2.kdstatusdata ,t2.tgldata ,t2.seleksi ,t2.statuskuliahmhs ,t2.namafakultas ,t2.namasingkatfakultas ,t2.namaprogramstudi ,t2.namasingkatps , COUNT(*) OVER()::integer AS jml_record FROM (SELECT t1.nim FROM akademik.statusakademikmahasiswa t1) T1 INNER JOIN ( select T1.nim, T1.idpersonal, T1.kdprogramstudi, T1.kdseleksi, T1.tglmasuk, T1.thnakademikmasuk, T1.kdstatusakademikterakhir, T1.thnakademikstatusakademikterakhir, T2.nama, T2.kdjeniskelamin, T2.tgllahir, T2.idkotalahir, T2.kdagama, T2.kdwarganegara, T2.alamattinggal, T2.idkotatinggal, T2.namaibukandung, T2.kdstatussipil, T2.kdgolongandarah, T2.noktp, T2.nohp, T2.kdstatusperson, T2.kdkeberadaanperson, T2.kdstatusdata, T2.tgldata, T3.seleksi, T7.statusakademik AS statuskuliahmhs, T5.namafakultas, T5.namasingkatfakultas, T6.namaprogramstudi, T6.namasingkatps, T4.jumlahsaudara, T4.kdbiayastudi, T4.thnlulus, T4.jumlahuan, T4.nilaiuan, T4.kotalahirijazah, T8.pendidikanortu, T9.pekerjaanortu, T10.penghasilanortu, T4.kdpenghasilanortu, T4.extrakurikuler, T4.idslta, T11.jurusanslta, T4.namaayah, T4.alamatortu, T4.rt, T4.rw, T4.kelurahan, T4.kecamatan, T4.idkotaortu, T4.kodepos, T4.notelportu from akademik.mahasiswa T1 INNER JOIN person.identitas T2 ON T1.idpersonal=T2.idpersonal LEFT JOIN support.seleksi T3 ON T1.kdseleksi=T3.kdseleksi LEFT JOIN support.fakultas T5 ON t1.kdfakultas=T5.kdfakultas LEFT JOIN support.programstudi T6 ON t1.kdprogramstudi=T6.kdprogramstudi LEFT JOIN akademik.statusakademik T7 ON T1.thnakademikstatusakademikterakhir=T7.statusakademik LEFT JOIN akademik.identitasmahasiswaS0_S1 T4 ON T1.nim=T4.nim LEFT JOIN akademik.pendidikanorangTua T8 ON T4.kdpendidikanortu=T8.kdpendidikanortu LEFT JOIN akademik.pekerjaanorangtua T9 ON T4.kdpekerjaanortu=T9.kdpekerjaanortu LEFT JOIN akademik.penghasilanortu T10 ON T4.kdpenghasilanortu=T10.kdpenghasilanortu LEFT JOIN support.jurusanslta T11 ON T4.kdjurusanslta=T11.kdjurusanslta WHERE (LOWER(T1.nim) ~* LOWER(?) OR LOWER(T2.nama) ~* ?) AND T1.thnakademikmasuk = ? ) T2 ON T1.nim = T2.nim LIMIT ? OFFSET ?", [
                        $search, $search, $angkatan, $limit, $offset
                    ]);
            } else {
                if ($limit == -1)
                    return DB::connection('siakad')->select("SELECT t2.nim ,t2.idpersonal ,t2.kdprogramstudi ,t2.kdseleksi ,t2.tglmasuk ,t2.thnakademikmasuk ,t2.kdstatusakademikterakhir ,t2.thnakademikstatusakademikterakhir ,t2.nama ,t2.kdjeniskelamin ,t2.tgllahir ,t2.idkotalahir ,t2.kdagama ,t2.kdwarganegara ,t2.alamattinggal ,t2.idkotatinggal ,t2.namaibukandung ,t2.kdstatussipil ,t2.kdgolongandarah ,t2.noktp ,t2.nohp ,t2.kdstatusperson ,t2.kdkeberadaanperson ,t2.kdstatusdata ,t2.tgldata ,t2.seleksi ,t2.statuskuliahmhs ,t2.namafakultas ,t2.namasingkatfakultas ,t2.namaprogramstudi ,t2.namasingkatps , COUNT(*) OVER()::integer AS jml_record FROM (SELECT t1.nim FROM akademik.statusakademikmahasiswa t1 WHERE kdstatusakademik=?) T1 INNER JOIN ( select T1.nim, T1.idpersonal, T1.kdprogramstudi, T1.kdseleksi, T1.tglmasuk, T1.thnakademikmasuk, T1.kdstatusakademikterakhir, T1.thnakademikstatusakademikterakhir, T2.nama, T2.kdjeniskelamin, T2.tgllahir, T2.idkotalahir, T2.kdagama, T2.kdwarganegara, T2.alamattinggal, T2.idkotatinggal, T2.namaibukandung, T2.kdstatussipil, T2.kdgolongandarah, T2.noktp, T2.nohp, T2.kdstatusperson, T2.kdkeberadaanperson, T2.kdstatusdata, T2.tgldata, T3.seleksi, T7.statusakademik AS statuskuliahmhs, T5.namafakultas, T5.namasingkatfakultas, T6.namaprogramstudi, T6.namasingkatps, T4.jumlahsaudara, T4.kdbiayastudi, T4.thnlulus, T4.jumlahuan, T4.nilaiuan, T4.kotalahirijazah, T8.pendidikanortu, T9.pekerjaanortu, T10.penghasilanortu, T4.kdpenghasilanortu, T4.extrakurikuler, T4.idslta, T11.jurusanslta, T4.namaayah, T4.alamatortu, T4.rt, T4.rw, T4.kelurahan, T4.kecamatan, T4.idkotaortu, T4.kodepos, T4.notelportu from akademik.mahasiswa T1 INNER JOIN person.identitas T2 ON T1.idpersonal=T2.idpersonal LEFT JOIN support.seleksi T3 ON T1.kdseleksi=T3.kdseleksi LEFT JOIN support.fakultas T5 ON t1.kdfakultas=T5.kdfakultas LEFT JOIN support.programstudi T6 ON t1.kdprogramstudi=T6.kdprogramstudi LEFT JOIN akademik.statusakademik T7 ON T1.thnakademikstatusakademikterakhir=T7.statusakademik LEFT JOIN akademik.identitasmahasiswaS0_S1 T4 ON T1.nim=T4.nim LEFT JOIN akademik.pendidikanorangTua T8 ON T4.kdpendidikanortu=T8.kdpendidikanortu LEFT JOIN akademik.pekerjaanorangtua T9 ON T4.kdpekerjaanortu=T9.kdpekerjaanortu LEFT JOIN akademik.penghasilanortu T10 ON T4.kdpenghasilanortu=T10.kdpenghasilanortu LEFT JOIN support.jurusanslta T11 ON T4.kdjurusanslta=T11.kdjurusanslta WHERE (LOWER(T1.nim) ~* LOWER(?) OR LOWER(T2.nama) ~* ?) AND T1.thnakademikmasuk = ? ) T2 ON T1.nim = T2.nim", [
                        $status, $search, $search, $angkatan
                    ]);
                else
                    return DB::connection('siakad')->select("SELECT t2.nim ,t2.idpersonal ,t2.kdprogramstudi ,t2.kdseleksi ,t2.tglmasuk ,t2.thnakademikmasuk ,t2.kdstatusakademikterakhir ,t2.thnakademikstatusakademikterakhir ,t2.nama ,t2.kdjeniskelamin ,t2.tgllahir ,t2.idkotalahir ,t2.kdagama ,t2.kdwarganegara ,t2.alamattinggal ,t2.idkotatinggal ,t2.namaibukandung ,t2.kdstatussipil ,t2.kdgolongandarah ,t2.noktp ,t2.nohp ,t2.kdstatusperson ,t2.kdkeberadaanperson ,t2.kdstatusdata ,t2.tgldata ,t2.seleksi ,t2.statuskuliahmhs ,t2.namafakultas ,t2.namasingkatfakultas ,t2.namaprogramstudi ,t2.namasingkatps , COUNT(*) OVER()::integer AS jml_record FROM (SELECT t1.nim FROM akademik.statusakademikmahasiswa t1 WHERE kdstatusakademik=?) T1 INNER JOIN ( select T1.nim, T1.idpersonal, T1.kdprogramstudi, T1.kdseleksi, T1.tglmasuk, T1.thnakademikmasuk, T1.kdstatusakademikterakhir, T1.thnakademikstatusakademikterakhir, T2.nama, T2.kdjeniskelamin, T2.tgllahir, T2.idkotalahir, T2.kdagama, T2.kdwarganegara, T2.alamattinggal, T2.idkotatinggal, T2.namaibukandung, T2.kdstatussipil, T2.kdgolongandarah, T2.noktp, T2.nohp, T2.kdstatusperson, T2.kdkeberadaanperson, T2.kdstatusdata, T2.tgldata, T3.seleksi, T7.statusakademik AS statuskuliahmhs, T5.namafakultas, T5.namasingkatfakultas, T6.namaprogramstudi, T6.namasingkatps, T4.jumlahsaudara, T4.kdbiayastudi, T4.thnlulus, T4.jumlahuan, T4.nilaiuan, T4.kotalahirijazah, T8.pendidikanortu, T9.pekerjaanortu, T10.penghasilanortu, T4.kdpenghasilanortu, T4.extrakurikuler, T4.idslta, T11.jurusanslta, T4.namaayah, T4.alamatortu, T4.rt, T4.rw, T4.kelurahan, T4.kecamatan, T4.idkotaortu, T4.kodepos, T4.notelportu from akademik.mahasiswa T1 INNER JOIN person.identitas T2 ON T1.idpersonal=T2.idpersonal LEFT JOIN support.seleksi T3 ON T1.kdseleksi=T3.kdseleksi LEFT JOIN support.fakultas T5 ON t1.kdfakultas=T5.kdfakultas LEFT JOIN support.programstudi T6 ON t1.kdprogramstudi=T6.kdprogramstudi LEFT JOIN akademik.statusakademik T7 ON T1.thnakademikstatusakademikterakhir=T7.statusakademik LEFT JOIN akademik.identitasmahasiswaS0_S1 T4 ON T1.nim=T4.nim LEFT JOIN akademik.pendidikanorangTua T8 ON T4.kdpendidikanortu=T8.kdpendidikanortu LEFT JOIN akademik.pekerjaanorangtua T9 ON T4.kdpekerjaanortu=T9.kdpekerjaanortu LEFT JOIN akademik.penghasilanortu T10 ON T4.kdpenghasilanortu=T10.kdpenghasilanortu LEFT JOIN support.jurusanslta T11 ON T4.kdjurusanslta=T11.kdjurusanslta WHERE (LOWER(T1.nim) ~* LOWER(?) OR LOWER(T2.nama) ~* ?) AND T1.thnakademikmasuk = ? ) T2 ON T1.nim = T2.nim LIMIT ? OFFSET ?", [
                        $status, $search, $search, $angkatan, $limit, $offset
                    ]);
            }
        }
    }

    public static function getTotalRecordsMahasiswa($angkatan = "all", $status = "all", $search = "")
    {
        if ($angkatan == "all") {
            if ($status == "all") {
                return DB::connection('siakad')->select('SELECT COUNT(t1.NPK) AS jml_record FROM tblMahasiswa t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:search), "%") OR LOWER(t1.NPK) LIKE CONCAT("%", LOWER(:npk), "%")', [
                    'search' => $search,
                    'npk' => $search
                ]);
            } else {
                return DB::connection('siakad')->select('SELECT COUNT(t1.NPK) AS jml_record FROM tblMahasiswa t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE (LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:search), "%") OR LOWER(t1.NPK) LIKE CONCAT("%", LOWER(:npk), "%")) AND t1.status_aktif = :status', [
                    'status' => $status,
                    'search' => $search,
                    'npk' => $search
                ]);
            }
        } else {
            if ($status == "all") {
                return DB::connection('siakad')->select('SELECT COUNT(t1.NPK) AS jml_record FROM tblMahasiswa t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE (LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:search), "%") OR LOWER(t1.NPK) LIKE CONCAT("%", LOWER(:npk), "%")) AND t1.angkatan = :angkatan', [
                    'angkatan' => $angkatan,
                    'search' => $search,
                    'npk' => $search
                ]);
            } else {
                return DB::connection('siakad')->select('SELECT COUNT(t1.NPK) AS jml_record FROM tblMahasiswa t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE (LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:search), "%") OR LOWER(t1.NPK) LIKE CONCAT("%", LOWER(:npk), "%")) AND t1.status_aktif = :status AND t1.angkatan = :angkatan', [
                    'angkatan' => $angkatan,
                    'status' => $status,
                    'search' => $search,
                    'npk' => $search
                ]);
            }
        }
    }

    public static function getMahasiswaByNpk($npk)
    {
        $mahasiswa = DB::connection('siakad')->select('SELECT t1.jenis_kelas, t1.NPK, t1.nama_lengkap, t1.angkatan, t1.password, t1.program_id, t1.alamat_rumah, t1.hp, t1.kota_rumah, t1.email, t2.nama_program, t1.jenis_pendanaan, t1.inf_NISN as nisn, t1.dosen_wali, t1.tgl_lulus_sma, t1.inf_jurusan_sma, t1.sekolah_asal, t1.inf_tgl_lulus, t1.inf_nomor_ijazah, t1.inf_nomor_transkrip, t1.status_aktif, t1.program_id, t1.konsentrasi_id, t1.nama_wali, t1.pekerjaan_wali, t1.jenis_mahasiswa, t1.jenis_pendanaan, t1.no_seri_ijazah, t1.tempat_lahir, t1.tgl_lahir, t1.jenis_kelamin, t1.agama_id, t1.status_menikah, t1.hp, t1.telepon_rumah, t1.kode_pos_rumah, t1.kewarganegaraan, t1.nik, t1.rt, t1.rw, t1.ds_kel, t1.nama_ibu, t1.judul_skripsi, t1.ipk, t1.kota_rumah FROM tblMahasiswa t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE t1.NPK = :npk', [
            'npk' => $npk
        ]);
        return (sizeof($mahasiswa) > 0) ? $mahasiswa[0] : $mahasiswa;
    }

    public static function getNilaiLabkom($npk)
    {
        return DB::connection('siakad')->select("SELECT t.n_hrf AS nilai_huruf, FLOOR(t.n_tot) AS nilai_angka, tM.nama_mata_kuliah, CONCAT(t2.tempat_lahir, ', ', date_format(t2.tgl_lahir, '%d %M %Y')) AS ttl FROM (tblMataKuliah tM LEFT JOIN tblNilai t ON((t.mata_kuliah_id = tM.mata_kuliah_id)) JOIN tblMahasiswa t2 ON t.NPK = t2.NPK) WHERE t.NPK = :npk AND upper(nama_mata_kuliah) = 'LABORATORIUM KOMPUTER' ORDER BY LEFT(jadwal_kuliah_id, 5) DESC LIMIT 1", [
            'npk' => $npk
        ]);
    }

    public static function getNilaiSkripsi($npk)
    {
        return DB::connection('siakad')->select("SELECT t.n_hrf AS nilai_huruf, FLOOR(t.n_tot) AS nilai_angka, tM.nama_mata_kuliah, t2.status_aktif, CONCAT(t2.tempat_lahir, ', ', date_format(t2.tgl_lahir, '%d %M %Y')) AS ttl, t2.judul_skripsi FROM (tblMataKuliah tM LEFT JOIN tblNilai t ON((t.mata_kuliah_id = tM.mata_kuliah_id)) JOIN tblMahasiswa t2 ON t.NPK = t2.NPK) WHERE t.NPK = :npk AND lower(t.n_hrf) != 'e' AND (upper(nama_mata_kuliah) = 'SKRIPSI' OR upper(nama_mata_kuliah) = 'TUGAS AKHIR' OR upper(nama_mata_kuliah) = 'TESIS') ORDER BY LEFT(jadwal_kuliah_id, 5) DESC LIMIT 1", [
            'npk' => $npk
        ]);
    }

    public static function getIndexPrestasi($npk)
    {
        return DB::connection('siakad')->select("SELECT tahun_akademik, IPK AS ipk, IPS AS ips, sks_smt FROM tblIndexPrestasi WHERE NPK = :npk ORDER BY tahun_akademik ASC", [
            'npk' => $npk
        ]);
    }
}
