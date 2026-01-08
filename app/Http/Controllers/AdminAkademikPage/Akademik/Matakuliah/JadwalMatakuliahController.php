<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalMataKuliah;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Semester;
use App\Models\MOODLE_MODEL\JadwalDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class JadwalMatakuliahController extends Controller
{
    public function index($filter = -1)
    {
        $menu = 'Sinkronisasi Jadwal Kuliah dengan Siakad';
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        $tahun_akademik_siakad = Semester::get_semester();
        $tahun_akademik = JadwalMataKuliah::get_tahun_akademik();
        return view('admin_akademik_page.akademik.matakuliah.jadwal_matakuliah', compact('menu', 'program_studi', 'tahun_akademik', 'tahun_akademik_siakad', 'filter'));
    }

    public function json_get_daftar(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'tahun_akademik' => 'required',
            'status' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = JadwalMataKuliah::get_jadwal_matakuliah($request->prodi, $request->tahun_akademik, $search, $start, $length, $request->status);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_jadwal_kuliah_siakad(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\JadwalMataKuliah::get_jadwal_mata_kuliah($request->tahun_akademik);
        return response()->json($data, 200);
    }

    public function json_get_jadwal_by_id(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required'
            ]);

            Log::info('Get jadwal by id', [
                'id' => $request->id
            ]);

            $jadwal = JadwalMataKuliah::get_detail_jadwal_kuliah($request->id);

            if (!$jadwal) {
                Log::warning('Jadwal tidak ditemukan', [
                    'id' => $request->id
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            Log::info('Jadwal berhasil diambil', [
                'id' => $request->id,
                'data' => $jadwal
            ]);

            return response()->json($jadwal, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error get jadwal by id', [
                'message' => $e->getMessage(),
                'id' => $request->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function json_syncron_data(Request $request)
    {
        $request->validate([
            'jadwal_kuliah_id' => 'required',
            'tahun_akademik' => 'required',
            'kelas_id' => 'required',
            'nama_kelas' => 'required',
            'ruang_id' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'matakuliah_id' => 'required',
            'nama_mata_kuliah' => 'required',
            'kapasitas' => 'required',
            'dosen_id' => 'required',
            'kd_prodi' => 'required',
            'jumlah_sks' => 'required',
            'is_lab' => 'required',
            'jenis_kelas' => 'required',
            'kd_matkul' => 'required'
        ]);

        $data = JadwalMataKuliah::sync_jadwal_matakuliah_with_siakad($request->jadwal_kuliah_id, $request->tahun_akademik, $request->kelas_id.';'.$request->nama_kelas,
            $request->ruang_id, $request->hari, $request->jam_mulai, $request->jam_selesai, $request->matakuliah_id,
            $request->nama_mata_kuliah, $request->kapasitas, $request->dosen_id, $request->asisten_id,
            $request->kd_prodi, $request->jumlah_sks, $request->is_lab, $request->jenis_kelas, $request->kd_matkul);

        if ($data->status)
            return response()->json($data);
        else
            return response()->json($data, 500);
    }

    public function detail($id)
    {
        $menu = 'Sinkronisasi Jadwal Kuliah dengan Siakad';
        $jadwal = JadwalMataKuliah::get_detail_jadwal_kuliah($id);
        return view('admin_akademik_page.akademik.matakuliah.detail_jadwal_kuliah', compact('menu', 'jadwal'));
    }

    public function set_jenis_jadwal(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'jenis_jadwal' => 'required',
        ]);
        if ($request->jenis_jadwal == 2)
            $request->validate([
                'koordinator' => 'required'
            ]);
        $jadwal = JadwalMataKuliah::set_jenis_jadwal_kuliah($request->id, $request->jenis_jadwal, $request->koordinator);
        if ($jadwal->status == 1) {
            Session::flash("success_message", $jadwal->keterangan);
            return redirect(route('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.index', ['filter' => 0]));
        } else {
            Session::flash("failed_message", $jadwal->keterangan);
            return redirect(route('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.detail', ['id' => $request->id]));
        }
    }

    public function generate_jadwal(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'tahun_akademik' => 'required|string',
            ]);

            // Log untuk tracking
            Log::info('Generate jadwal dimulai', [
                'tahun_akademik' => $request->tahun_akademik,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            // Panggil method generate_jadwal dari model
            $result = JadwalMataKuliah::generate_jadwal($request->tahun_akademik);

            // Konversi format response model ke format yang diharapkan frontend
            $status = ($result->status == 1 || $result->status === true) ? 'success' : 'error';
            $message = $result->keterangan ?? 'Proses generate jadwal selesai';

            // Log hasil
            Log::info('Generate jadwal selesai', [
                'tahun_akademik' => $request->tahun_akademik,
                'status' => $status,
                'message' => $message,
                'raw_status' => $result->status
            ]);

            // Return response dengan status HTTP yang sesuai
            $httpStatus = $status === 'success' ? 200 : 422;

            return response()->json([
                'status' => $status,
                'message' => $message,
                'raw_result' => [
                    'status' => $result->status,
                    'keterangan' => $result->keterangan
                ],
                'timestamp' => now()->toISOString()
            ], $httpStatus);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error pada generate jadwal', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all()),
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error pada generate jadwal', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'tahun_akademik' => $request->tahun_akademik ?? null,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat generate jadwal: ' . $e->getMessage(),
                'error_code' => 'GENERATE_JADWAL_ERROR'
            ], 500);

        } catch (\Throwable $e) {
            Log::critical('Critical error pada generate jadwal', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem yang tidak terduga',
                'error_code' => 'SYSTEM_ERROR'
            ], 500);
        }
    }

    public function update_jadwal(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'hari' => 'required',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required'
            ]);

            Log::info('Update jadwal dimulai', [
                'id' => $request->id,
                'data' => $request->all()
            ]);

            // Helper function untuk convert empty string ke null
            $toNullIfEmpty = function($value) {
                return ($value === '' || $value === 'null') ? null : $value;
            };

            // Gunakan fungsi testing yang baru dengan parameter yang disederhanakan
            $result = JadwalMataKuliah::update_jadwal_kuliah_testing(
                $request->id,
                $toNullIfEmpty($request->ruang_id),
                $toNullIfEmpty($request->hari),
                $toNullIfEmpty($request->jam_mulai),
                $toNullIfEmpty($request->jam_selesai),
                $toNullIfEmpty($request->sts_aktif)
            );

            $status = ($result->status == 1 || $result->status === true) ? 'success' : 'error';
            $message = $result->keterangan ?? 'Jadwal berhasil diupdate';

            Log::info('Update jadwal selesai', [
                'status' => $status,
                'message' => $message
            ]);

            $httpStatus = $status === 'success' ? 200 : 422;

            return response()->json([
                'status' => $status,
                'message' => $message
            ], $httpStatus);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error update jadwal', [
                'message' => $e->getMessage(),
                'id' => $request->id ?? null
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat update jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete_jadwal(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required'
            ]);

            Log::info('Delete jadwal dimulai', [
                'id' => $request->id
            ]);

            $result = JadwalMataKuliah::delete_jadwal_kuliah($request->id);

            $status = ($result->status == 1 || $result->status === true) ? 'success' : 'error';
            $message = $result->keterangan ?? 'Jadwal berhasil dihapus';

            Log::info('Delete jadwal selesai', [
                'status' => $status,
                'message' => $message
            ]);

            $httpStatus = $status === 'success' ? 200 : 422;

            return response()->json([
                'status' => $status,
                'message' => $message
            ], $httpStatus);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error delete jadwal', [
                'message' => $e->getMessage(),
                'id' => $request->id ?? null
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function set_status_aktif(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'sts_aktif' => 'required|boolean'
            ]);

            Log::info('Set status aktif jadwal dimulai', [
                'id' => $request->id,
                'sts_aktif' => $request->sts_aktif
            ]);

            $result = JadwalMataKuliah::set_status_aktif_jadwal_kuliah(
                $request->id,
                $request->sts_aktif
            );

            $status = ($result->status == 1 || $result->status === true) ? 'success' : 'error';
            $message = $result->message ?? ($request->sts_aktif ? 'Jadwal berhasil diaktifkan' : 'Jadwal berhasil dinonaktifkan');

            Log::info('Set status aktif jadwal selesai', [
                'status' => $status,
                'message' => $message
            ]);

            $httpStatus = $status === 'success' ? 200 : 422;

            return response()->json([
                'status' => $status,
                'message' => $message
            ], $httpStatus);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error set status aktif jadwal', [
                'message' => $e->getMessage(),
                'id' => $request->id ?? null
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengubah status jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update_status_kuliah_mahasiswa(Request $request)
    {
        try {
            $request->validate([
                'nim' => 'required|string',
                'status_kuliah' => 'required|string|size:1'
            ]);

            Log::info('Update status kuliah mahasiswa dimulai', [
                'nim' => $request->nim,
                'status_kuliah' => $request->status_kuliah
            ]);

            $result = JadwalMataKuliah::update_status_kuliah_mahasiswa(
                $request->nim,
                $request->status_kuliah
            );

            $status = ($result->status == 1 || $result->status === true) ? 'success' : 'error';
            $message = $result->keterangan ?? 'Status kuliah mahasiswa berhasil diupdate';

            Log::info('Update status kuliah mahasiswa selesai', [
                'status' => $status,
                'message' => $message
            ]);

            $httpStatus = $status === 'success' ? 200 : 422;

            return response()->json([
                'status' => $status,
                'message' => $message
            ], $httpStatus);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error update status kuliah mahasiswa', [
                'message' => $e->getMessage(),
                'nim' => $request->nim ?? null
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat update status kuliah mahasiswa: ' . $e->getMessage()
            ], 500);
        }
    }
}
