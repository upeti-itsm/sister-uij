<?php

namespace App\Http\Controllers;

use App\Models\Feeder\BiodataMahasiswa;
use App\Models\SIAKAD_MODEL\JadwalMataKuliah;
use App\Models\temp_model;
use App\Models\Ws_Feeder;
use Illuminate\Http\Request;

class tempFeederMigrasiController extends Controller
{
    public function temp()
    {
        return view('temp');
    }

    public function getKelasKuliah($ta)
    {
        $data = Ws_Feeder::getData('GetListKelasKuliah', "id_semester = '" . $ta . "'");
        return response()->json($data);
    }

    public function getPesertaKelasKuliah($id)
    {
        $data = Ws_Feeder::getData('GetPesertaKelasKuliah', "id_kelas_kuliah = '" . $id . "'");
        return response()->json($data);
    }

    public function getAktifitasMahasiswa($id)
    {
        $data = Ws_Feeder::getData('GetListAktivitasMahasiswa', "id_semester = '" . $id . "'");
        return response()->json($data);
    }

    public function deletePesertaKuliah(Request $request)
    {
        $data['id_registrasi_mahasiswa'] = $request->id_mahasiswa;
        $data['id_kelas_kuliah'] = $request->id_kelas;
        $data = Ws_Feeder::deleteData('DeletePesertaKelasKuliah', json_encode($data));
        return response()->json($data);
    }

    public function cekKelasFeeder($id)
    {
        $data = Ws_Feeder::getData('GetListKelasKuliah', "id_kelas_kuliah = '" . $id . "'");
        return response()->json(sizeof($data) == 1 ? true : false);
    }

    public function cekKelasFeederPost(Request $request)
    {
        $data = Ws_Feeder::getData('GetListKelasKuliah', "id_kelas_kuliah = '" . $request->id . "'");
        return response()->json(sizeof($data) == 1 ? true : false);
    }

    public function getMahasiswa($ta)
    {
        if ($ta != "all")
            $data = Ws_Feeder::getData('GetListMahasiswa', "id_periode = '" . $ta . "'");
        else
            $data = Ws_Feeder::getData('GetListMahasiswa');
        return response()->json($data);
    }

    public function syncMahasiswa(Request $request)
    {
        $data = BiodataMahasiswa::sync_biodata_mahasiswa($request->nama_mahasiswa, $request->jenis_kelamin, $request->jalan,
            $request->rt, $request->rw, $request->dusun, $request->kelurahan, $request->kode_pos, $request->nisn, $request->nik,
            $request->tempat_lahir, $request->tanggal_lahir, $request->nama_ayah, $request->tanggal_lahir_ayah, $request->id_jenjang_pendidikan_ayah, $request->id_pekerjaan_ayah, $request->id_penghasilan_ayah, $request->id_kebutuhan_khusus_ayah, $request->nik_ayah, $request->nama_ibu_kandung, $request->tanggal_lahir_ayah, $request->nik_ibu, $request->id_jenjang_pendidikan_ibu, $request->id_pekerjaan_ibu, $request->id_penghasilan_ibu, $request->id_kebutuhan_khusus, $request->nama_wali, $request->tanggal_lahir_wali, $request->id_jenjang_pendidikan_wali, $request->id_pekerjaan_wali, $request->id_penghasilan_wali, $request->id_kebutuhan_khusus_mahasiswa, $request->telepon, $request->handphone, $request->email, $request->penerima_kps, $request->no_kps, $request->npwp, $request->id_wilayah, $request->id_jenis_tinggal, $request->id_agama, $request->id_alat_transportasi, $request->kewarganegaraan, $request->id_mahasiswa);
        return response()->json($data);
    }

    public function deleteMahasiswa(Request $request)
    {
        $data['id_mahasiswa'] = $request->id_mahasiswa;
        $data = Ws_Feeder::deleteData('DeleteBiodataMahasiswa', json_encode($data));
        return response()->json($data);
    }

    public function getListAnggotaAM($id)
    {
        $data = Ws_Feeder::getData('GetListAnggotaAktivitasMahasiswa', "id_aktivitas = '" . $id . "'");
        return response()->json($data);
    }

    public function deleteAnggotaAM(Request $request)
    {
        $data['id_anggota'] = $request->id_anggota;
        $data = Ws_Feeder::deleteData('DeleteAnggotaAktivitasMahasiswa', json_encode($data));
        $code = 500;
        if ($data['error_code'] == 0) {
            $update = temp_model::delete_anggota_feeder($request->id_anggota);
            $code = 200;
        }
        return response()->json($data, $code);
    }

    public function getKebutuhanKhusus()
    {
        $data = Ws_Feeder::getData('GetKebutuhanKhusus');
        return response()->json($data);
    }

    public function getNilaiKelasKuliah()
    {
        $data = temp_model::get_nilai_kelas_kuliah();
        return response()->json($data);
    }

    public function getBackupAM()
    {
        $data = temp_model::get_am();
        return response()->json($data);
    }

    public function getBackupAMPembimbing()
    {
        $data = temp_model::get_am_pembimbing();
        return response()->json($data);
    }

    public function getBackupAMPenguji()
    {
        $data = temp_model::get_am_penguji();
        return response()->json($data);
    }

    public function getBackupAMAnggota()
    {
        $data = temp_model::get_am_peserta();
        return response()->json($data);
    }

    public function getKurikulum()
    {
        $data = temp_model::get_kurikulum();
        return response()->json($data);
    }

    public function getMatkulKurikulum()
    {
        $data = temp_model::get_matkul_kurikulum();
        return response()->json($data);
    }

    public function getPesertaKelasKuliahLokal()
    {
        $data = temp_model::get_peserta_kelas_kuliah();
        return response()->json($data);
    }

    public function getMahasiswaBaru()
    {
        $data = temp_model::get_mahasiswa();
        return response()->json($data);
    }

    public function getPengampuKelasKuliah()
    {
        $data = temp_model::get_pengampu_kelas_kuliah();
        return response()->json($data);
    }

    public function getIndeksPrestasi()
    {
        $data = temp_model::get_indeks_prestasi();
        return response()->json($data);
    }

    public function insertAktivitasMahasiswa(Request $request)
    {
        $data['program_mbkm'] = 0;
        $data['jenis_anggota'] = $request->jenis_anggota;
        $data['id_jenis_aktivitas'] = $request->id_jenis_aktivitas;
        $data['id_prodi'] = $request->id_prodi;
        $data['id_semester'] = $request->id_semester;
        $data['judul'] = $request->judul;
        $data['keterangan'] = $request->keterangan;
        $data['lokasi'] = $request->lokasi;
        $data['sk_tugas'] = $request->sk_tugas;
        $result = Ws_Feeder::insertData('InsertAktivitasMahasiswa', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_id_aktivitas($request->id, $result['data']['id_aktivitas']);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function updateAktivitasMahasiswa(Request $request)
    {
        $key['id_aktivitas'] = $request->id_aktivitas;
        $key['id_semester'] = $request->id_semester;
        $data['program_mbkm'] = 0;
        $data['jenis_anggota'] = $request->jenis_anggota;
        $data['id_jenis_aktivitas'] = $request->id_jenis_aktivitas;
        $data['id_prodi'] = $request->id_prodi;
        $data['id_semester'] = $request->id_semester;
        $data['judul'] = $request->judul;
        $data['keterangan'] = $request->keterangan;
        $data['lokasi'] = $request->lokasi;
        $data['sk_tugas'] = $request->sk_tugas;
        $data['tanggal_sk_tugas'] = $request->tanggal_sk_tugas;
        $data['tanggal_mulai'] = $request->tanggal_mulai;
        $data['tanggal_selesai'] = $request->tanggal_selesai;
        $result = Ws_Feeder::updateData('UpdateAktivitasMahasiswa', json_encode($key), json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_id_aktivitas($request->id, $result['data']['id_aktivitas']);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function insertPembimbing(Request $request)
    {
        $data['id_aktivitas'] = $request->id_aktivitas;
        $data['id_kategori_kegiatan'] = $request->id_kategori_kegiatan;
        $data['id_dosen'] = $request->id_dosen;
        $data['pembimbing_ke'] = $request->pembimbing_ke;
        $result = Ws_Feeder::insertData('InsertBimbingMahasiswa', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_id_bimbing($request->id, $result['data']['id_bimbing_mahasiswa']);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function insertPenguji(Request $request)
    {
        $data['id_aktivitas'] = $request->id_aktivitas;
        $data['id_kategori_kegiatan'] = $request->id_kategori_kegiatan;
        $data['id_dosen'] = $request->id_dosen;
        $data['penguji_ke'] = $request->penguji_ke;
        $result = Ws_Feeder::insertData('InsertUjiMahasiswa', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_id_uji($request->id, $result['data']['id_uji']);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function insertAnggota(Request $request)
    {
        $data['id_aktivitas'] = $request->id_aktivitas;
        $data['id_registrasi_mahasiswa'] = $request->id_registrasi_mahasiswa;
        $data['jenis_peran'] = $request->jenis_peran;
        $result = Ws_Feeder::insertData('InsertAnggotaAktivitasMahasiswa', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_id_anggota($request->id, $result['data']['id_anggota']);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function insertKurikulum(Request $request)
    {
        $data['nama_kurikulum'] = $request->nama_kurikulum;
        $data['id_prodi'] = $request->id_prodi;
        $data['id_semester'] = $request->id_semester;
        $data['jumlah_sks_lulus'] = $request->jumlah_sks_lulus;
        $data['jumlah_sks_wajib'] = $request->jumlah_sks_wajib;
        $data['jumlah_sks_pilihan'] = $request->jumlah_sks_pilihan;
        $result = Ws_Feeder::insertData('InsertKurikulum', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_id_kurikulum($request->id, $result['data']['id_kurikulum']);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function insertMatkulKurikulum(Request $request)
    {
        $data['id_kurikulum'] = $request->id_kurikulum;
        $data['id_matkul'] = $request->id_matkul;
        $data['semester'] = $request->semester;
        $data['sks_mata_kuliah'] = $request->sks_mata_kuliah;
        $data['sks_tatap_muka'] = $request->sks_tatap_muka;
        $data['sks_praktek'] = $request->sks_praktek;
        $data['sks_praktek_lapangan'] = $request->sks_praktek_lapangan;
        $data['sks_simulasi'] = $request->sks_simulasi;
        $data['apakah_wajib'] = $request->apakah_wajib;
        $result = Ws_Feeder::insertData('InsertMatkulKurikulum', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_inserted_matkul_kurikulum($request->id_kurikulum, $request->id_matkul);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function insertPesertaKelasKuliah(Request $request)
    {
        $data['id_kelas_kuliah'] = $request->id_kelas_kuliah;
        $data['id_registrasi_mahasiswa'] = $request->id_registrasi_mahasiswa;
        $result = Ws_Feeder::insertData('InsertPesertaKelasKuliah', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_inserted_peserta_kuliah($request->id_kelas_kuliah, $request->id_registrasi_mahasiswa);
            $code = 200;
        } elseif ($result['error_code'] == 106) {
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function insertMahasiswaBaru(Request $request)
    {
        $data['nama_mahasiswa'] = $request->nama_mahasiswa;
        $data['jenis_kelamin'] = $request->jenis_kelamin;
        $data['jalan'] = $request->jalan;
        $data['rt'] = $request->rt;
        $data['rw'] = $request->rw;
        $data['dusun'] = $request->dusun;
        $data['kelurahan'] = $request->kelurahan;
        $data['kode_pos'] = $request->kode_pos;
        $data['nisn'] = $request->nisn;
        $data['nik'] = $request->nik;
        $data['tempat_lahir'] = $request->tempat_lahir;
        $data['tanggal_lahir'] = $request->tanggal_lahir;
        $data['nama_ayah'] = $request->nama_ayah;
        $data['tanggal_lahir_ayah'] = $request->tanggal_lahir_ayah;
        $data['nik_ayah'] = $request->nik_ayah;
        $data['id_jenjang_pendidikan_ayah'] = $request->id_jenjang_pendidikan_ayah;
        $data['id_pekerjaan_ayah'] = $request->id_pekerjaan_ayah;
        $data['id_penghasilan_ayah'] = $request->id_penghasilan_ayah;
        $data['id_kebutuhan_khusus_ayah'] = $request->id_kebutuhan_khusus_ayah;
        $data['nama_ibu_kandung'] = $request->nama_ibu_kandung;
        $data['tanggal_lahir_ibu'] = $request->tanggal_lahir_ibu;
        $data['nik_ibu'] = $request->nik_ibu;
        $data['id_jenjang_pendidikan_ibu'] = $request->id_jenjang_pendidikan_ibu;
        $data['id_pekerjaan_ibu'] = $request->id_pekerjaan_ibu;
        $data['id_penghasilan_ibu'] = $request->id_penghasilan_ibu;
        $data['id_kebutuhan_khusus_ibu'] = $request->id_kebutuhan_khusus_ibu;
        $data['nama_wali'] = $request->nama_wali;
        $data['tanggal_lahir_wali'] = $request->tanggal_lahir_wali;
        $data['id_jenjang_pendidikan_wali'] = $request->id_jenjang_pendidikan_wali;
        $data['id_pekerjaan_wali'] = $request->id_pekerjaan_wali;
        $data['id_penghasilan_wali'] = $request->id_penghasilan_wali;
        $data['id_kebutuhan_khusus_mahasiswa'] = $request->id_kebutuhan_khusus_mahasiswa;
        $data['telepon'] = $request->telepon;
        $data['handphone'] = $request->handphone;
        $data['email'] = $request->email;
        $data['penerima_kps'] = $request->penerima_kps;
        $data['no_kps'] = $request->no_kps;
        $data['npwp'] = $request->npwp;
        $data['id_wilayah'] = $request->id_wilayah;
        $data['id_jenis_tinggal'] = $request->id_jenis_tinggal;
        $data['id_agama'] = $request->id_agama;
        $data['id_alat_transportasi'] = $request->id_alat_transportasi;
        $data['kewarganegaraan'] = $request->kewarganegaraan;
        $result = Ws_Feeder::insertData('InsertBiodataMahasiswa', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_id_mahasiswa($request->id, $request->id_mahasiswa);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function insertPengampuKelasKuliah(Request $request)
    {
        $data['id_registrasi_dosen'] = $request->id_registrasi_dosen;
        $data['id_kelas_kuliah'] = $request->id_kelas_kuliah;
        $data['id_substansi'] = $request->id_substansi;
        $data['sks_substansi_total'] = $request->sks_substansi_total;
        $data['rencana_minggu_pertemuan'] = $request->rencana_minggu_pertemuan;
        $data['realisasi_minggu_pertemuan'] = $request->realisasi_minggu_pertemuan;
        $data['id_jenis_evaluasi'] = $request->id_jenis_evaluasi;
        $result = Ws_Feeder::insertData('InsertDosenPengajarKelasKuliah', json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_id_aktivitas_mengajar($request->id, $result['data']['id_aktivitas_mengajar']);
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function updateNilaiPerkuliahan(Request $request)
    {
        $key['id_kelas_kuliah'] = $request->id_kelas_kuliah;
        $key['id_registrasi_mahasiswa'] = $request->id_registrasi_mahasiswa;
        $data['nilai_angka'] = $request->nilai_angka;
        $data['nilai_huruf'] = $request->nilai_huruf;
        $data['nilai_indeks'] = $request->nilai_indeks;
        $result = Ws_Feeder::updateData('UpdateNilaiPerkuliahanKelas', json_encode($key), json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_inserted_nilai_kelas($request->id);
            $code = 200;
        } elseif ($result['error_code'] == 106) {
            $code = 200;
        }
        return response()->json($result, $code);
    }

    public function updatePerkuliahanMahasiswa(Request $request)
    {
        $data['id_registrasi_mahasiswa'] = $request->id_registrasi_mahasiswa;
        $key['id_registrasi_mahasiswa'] = $request->id_registrasi_mahasiswa;
        $data['id_semester'] = $request->id_semester;
        $key['id_semester'] = $request->id_semester;
        $data['id_status_mahasiswa'] = $request->id_status_mahasiswa;
        $data['ips'] = $request->ips;
        $data['ipk'] = $request->ipk;
        $data['sks_semester'] = $request->sks_semester;
        $data['total_sks'] = $request->total_sks;
        $data['biaya_kuliah_smt'] = $request->biaya_kuliah_smt;
        $data['id_pembiayaan'] = $request->id_pembiayaan;
        $result = Ws_Feeder::insertData('InsertPerkuliahanMahasiswa', json_encode($data));
        //$result = Ws_Feeder::updateData('UpdatePerkuliahanMahasiswa',json_encode($key), json_encode($data));
        $code = 500;
        if ($result['error_code'] == 0) {
            $update = temp_model::set_inserted_indeks_prestasi($request->id);
            $update = temp_model::set_error_indeks_prestasi($request->id, null);
            $code = 200;
        } elseif ($result['error_code'] == 106) {
            $code = 200;
        } else {
            $update = temp_model::set_error_indeks_prestasi($request->id, $result['error_desc']);
        }
        return response()->json($result, $code);
    }
}
