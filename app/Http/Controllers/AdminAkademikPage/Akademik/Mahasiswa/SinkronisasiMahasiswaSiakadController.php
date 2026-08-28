<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Dosen;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\PMB\Pendaftar;
use App\Models\Referensi\Agama;
use App\Models\Referensi\AlatTransportasi;
use App\Models\Referensi\JenisPendanaan;
use App\Models\Referensi\JenisTinggal;
use App\Models\Referensi\Pekerjaan;
use App\Models\Referensi\Pendidikan;
use App\Models\Referensi\Penghasilan;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Session;

class SinkronisasiMahasiswaSiakadController extends Controller
{
    public function index()
    {
        $menu = 'Sinkronisasi Mahasiswa dengan Siakad';
        $kd_fakultas = Session::get('user')->kd_fakultas ?? '0';
        if (in_array(Session::get('peran')['aktif'], [39])) {
            $kd_fakultas = "0";
        }
        $program_studi = ProgramStudi::get_program_studi("0", $kd_fakultas, "", true, -1);
        $angkatan = Mahasiswa::get_list_angkatan();
        $angkatan_siakad = tblMahasiswa::getAngkatan();
        $tahun_pmb = Pendaftar::get_tahun_seleksi();
        $status_mahasiswa = Mahasiswa::get_status_mahasiswa();
        return view('admin_akademik_page.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad', compact('menu', 'program_studi', 'angkatan', 'angkatan_siakad', 'tahun_pmb', 'status_mahasiswa'));
    }

    public function json_get_daftar_mahasiswa(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
            'status' => 'required',
        ]);
        $kd_fakultas = Session::get('user')->kd_fakultas ?? null;
        if (in_array(Session::get('peran')['aktif'], [39])) {
            $kd_fakultas = null;
        }
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Mahasiswa::get_mahasiswa($request->prodi, "0", $start, $length, $search, 'x', $request->angkatan, -1, $request->status, $kd_fakultas);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_mahasiswa_by_angkatan(Request $request)
    {
        $request->validate([
            'angkatan' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\tblMahasiswa::getMahasiswaByAngkatan($request->angkatan);
        return response()->json($data, 200);
    }

    public function json_get_mahasiswa_by_nim(Request $request)
    {
        $request->validate([
            'nim' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\tblMahasiswa::getMahasiswaByNpk($request->nim);
        return response()->json($data, 200);
    }

    public function json_syncron_data(Request $request)
    {
        $pass = 'null';
        if (!empty($request->password))
            $pass = $request->password;
        $request->validate([
            'npk' => 'required',
            'status_aktif' => 'required',
            'program_id' => 'required',
            'nama_lengkap' => 'required',
            'angkatan' => 'required'
        ]);
        $data = \App\Models\Akademik\Mahasiswa::sync_mahasiswa_with_siakad(trim($request->npk), $request->inf_nisn, $request->dosen_wali, $request->tgl_lulus_sma, $request->inf_jurusan_sma, $request->sekolah_asal, $request->inf_tgl_lulus, $request->inf_nomor_ijazah, $request->inf_nomor_transkrip, $request->status_aktif, trim($request->program_id), $request->konsentrasi_id,
            $request->nama_wali, $request->pekerjaan_wali, $request->jenis_mahasiswa, $request->jenis_pendanaan, $request->nomor_seri_ijazah, $request->nama_lengkap, $request->tempat_lahir, $request->tanggal_lahir, $request->jenis_kelamin, $request->agama_id, $request->status_menikah, $request->hp, $request->telepon_rumah, $request->alamat_rumah,
            $request->kode_pos_rumah, $request->inf_warga_negara, $request->email, $request->nik, $request->rt, $request->rw, $request->ds_kel, $request->nama_ibu, $pass, $request->angkatan, $request->jenis_kelas, $request->judul_skripsi, $request->ipk, $request->kota_rumah);
        if ($data->is_success)
            return response()->json($data);
        else
            return response()->json($data, 500);
    }

    public function json_syncron_data_pmb(Request $request)
    {
        $request->validate([
            'tahun' => 'required'
        ]);

        try {
            $result = Mahasiswa::sync_mahasiswa_from_pmb($request->tahun);
            if ($result->status == 1) {
                return response()->json([
                    'is_success' => true,
                    'result' => $result->result,
                    'total_processed' => $result->total_processed ?? 0,
                    'total_inserted' => $result->total_inserted ?? 0,
                    'total_updated' => $result->total_updated ?? 0,
                    'total_failed' => $result->total_failed ?? 0,
                ]);
            } else {
                return response()->json([
                    'is_success' => false,
                    'result' => $result->result ?? 'Gagal melakukan sinkronisasi'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'result' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function change_status_mahasiswa(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'status' => 'required'
        ]);

        try {
            $result = Mahasiswa::update_status_mahasiswa(
                $request->nim,
                $request->status,
                $request->alasan ?? ''
            );

            if ($result->status) {
                return response()->json([
                    'is_success' => true,
                    'result' => $result->result ?? 'Status mahasiswa berhasil diubah'
                ]);
            } else {
                return response()->json([
                    'is_success' => false,
                    'result' => $result->result ?? 'Gagal mengubah status mahasiswa'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'result' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($nim)
    {
        $menu = 'Sinkronisasi Mahasiswa dengan Siakad';

        // Get data mahasiswa
        $mahasiswa = Mahasiswa::get_mahasiswa_by_nim($nim);
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Get master data
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        $agama = Agama::list_agama();
        $jenis_tinggal = JenisTinggal::get_jenis_tinggal();
        $alat_transportasi = AlatTransportasi::get_data();
        $jenjang_pendidikan = Pendidikan::get_list_jenjang_pendidikan();
        $pekerjaan = Pekerjaan::get_data();
        $penghasilan = Penghasilan::get_data();
        $jenis_pendanaan = JenisPendanaan::get_data();
        $dosen = Dosen::get_dosen();
        return view('admin_akademik_page.akademik.mahasiswa.edit_mahasiswa', compact(
            'menu',
            'mahasiswa',
            'program_studi',
            'agama',
            'jenis_tinggal',
            'alat_transportasi',
            'jenjang_pendidikan',
            'pekerjaan',
            'penghasilan',
            'jenis_pendanaan',
            'dosen',
        ));
    }

    public function update(Request $request, $nim)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'kd_jenis_kelamin' => 'required|in:lk,pr',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before: today',
            'id_agama' => 'required|integer',
            'kd_prodi' => 'required|string',
            'dosen_wali' => 'required|string',
            'email' => 'nullable|email|max:255',
            'nik' => 'nullable|string|size:16',
            'nisn' => 'nullable|string|max:10',
            'handphone' => 'nullable|string|max:15',
            'ipk' => 'nullable|numeric|min:0|max:4',
        ], [
            'nama_mahasiswa.required' => 'Nama lengkap wajib diisi',
            'kd_jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh di masa depan',
            'id_agama.required' => 'Agama wajib dipilih',
            'kd_prodi.required' => 'Program studi wajib dipilih',
            'dosen_wali.required' => 'Dosen Wali wajib dipilih',
            'email.email' => 'Format email tidak valid',
            'nik.size' => 'NIK harus 16 digit',
            'ipk.numeric' => 'IPK harus berupa angka',
            'ipk.max' => 'IPK maksimal 4.00',
        ]);

        try {
            // Convert boolean is_kawin
            $is_kawin = null;
            if ($request->has('is_kawin') && $request->is_kawin !== '') {
                $is_kawin = $request->is_kawin == '1' ? true : false;
            }

            $result = Mahasiswa::update_mahasiswa(
                $nim,
                // Data Pribadi
                $request->nama_mahasiswa,
                $request->nik,
                $request->nisn,
                $request->kd_jenis_kelamin,
                $request->tempat_lahir,
                $request->tanggal_lahir,
                $request->id_agama,
                $is_kawin,
                $request->kewarganegaraan,
                // Kontak
                $request->email,
                $request->handphone,
                $request->telepon,
                // Alamat
                $request->alamat,
                $request->rt,
                $request->rw,
                $request->kelurahan,
                $request->kode_pos,
                $request->kota_rumah,
                // Data Keluarga
                $request->nama_ibu,
                $request->nama_wali,
                // Data Akademik
                $request->kd_prodi,
                $request->angkatan,
                $request->kd_status_mahasiswa,
                $request->kd_jenis_mahasiswa,
                $request->kd_jenis_pendanaan,
                $request->jenis_kelas_siakad,
                $request->dosen_wali,
                $request->id_jenis_tinggal,
                $request->id_alat_transportasi,
                // Data Pendidikan Sebelumnya
                $request->sekolah_asal,
                $request->jurusan_sma,
                $request->tgl_lulus_sma,
                $request->nomor_ijazah,
                $request->nomor_seri_ijazah,
                $request->nomor_transkrip,
                // Data Kelulusan
                $request->tgl_lulus,
                $request->judul_skripsi,
                $request->ipk
            );

            if ($result->is_success) {
                return response()->json([
                    'is_success' => true,
                    'result' => $result->result ?? 'Data mahasiswa berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'is_success' => false,
                    'result' => $result->result ?? 'Gagal memperbarui data mahasiswa'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'result' => 'Terjadi kesalahan:  ' . $e->getMessage()
            ], 500);
        }
    }
}
