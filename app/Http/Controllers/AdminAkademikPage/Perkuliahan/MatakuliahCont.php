<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JenisMatakuliah;
use App\Models\Akademik\JenisPelaksanaanKuliah;
use App\Models\Akademik\KonsentrasiJurusan;
use App\Models\Akademik\Kurikulum;
use App\Models\Akademik\Matakuliah;
use App\Models\Akademik\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class MatakuliahCont extends Controller
{
    public function index()
    {
        try {
            $menu = "Pengelolaan Matakuliah";
            $program_studi = ProgramStudi::get_program_studi();
            $jenis_matakuliah = JenisMatakuliah::get_daftar();
            $jenis_pelaksanaan = JenisPelaksanaanKuliah::get_daftar();

            // Load all kurikulum initially (akan di-filter via cascade)
            $kurikulum = Kurikulum::get_daftar_kurikulum('all');

            // Load matakuliah dari kurikulum pertama jika ada
            $matakuliah = collect();
            if (!is_null($kurikulum)) {
                $matakuliah = Matakuliah::get_daftar('all', $kurikulum[0]->id_kurikulum);
            }

            // Load all konsentrasi (akan di-filter sesuai kebutuhan)
            $konsentrasi = KonsentrasiJurusan::get_konsentrasi_jurusan('00000000-0000-0000-0000-000000000000', 'all');

            return view('admin_akademik_page.perkuliahan.matakuliah', compact(
                'menu',
                'program_studi',
                'jenis_matakuliah',
                'jenis_pelaksanaan',
                'matakuliah',
                'kurikulum',
                'konsentrasi'
            ));
        } catch (\Exception $e) {
            Log::error('Error in matakuliah index: ' . $e->getMessage());
            Session::flash('failed_message', $e->getMessage());
            return redirect()->back();
        }
    }

    public function json(Request $request)
    {
        try {
            // Set default values untuk filter
            $kd_prodi = $request->kd_prodi ?? 'all';
            $id_kurikulum = $request->id_kurikulum ?? null;

            $length = $request->length ?? 10;
            $start = $request->start ?? 0;
            $search = $request->search['value'] ?? '';

            $data_ = Matakuliah::get_daftar($kd_prodi, $id_kurikulum, $search, $start, $length);

            $data = [
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => $data_,
                'error' => null
            ];

            if (count($data_) > 0) {
                $data['recordsTotal'] = $data_[0]->jml_record ?? count($data_);
                $data['recordsFiltered'] = $data['recordsTotal'];
            }

            return response()->json($data, 200);
        } catch (\Exception $e) {
            Log::error('Error in matakuliah json: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan saat memuat data'
            ], 500);
        }
    }

    public function json_matakuliah_by_kurikulum(Request $request)
    {
        try {
            $request->validate([
                'id_kurikulum' => 'required|string',
            ]);

            $matakuliah = Matakuliah::get_daftar('all', $request->id_kurikulum);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dimuat',
                'data' => $matakuliah
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors(),
                'data' => []
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in json_matakuliah_by_kurikulum: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat matakuliah',
                'data' => []
            ], 500);
        }
    }

    public function json_kurikulum_by_prodi(Request $request)
    {
        try {
            $request->validate([
                'kd_prodi' => 'required|string',
            ]);

            $kurikulum = Kurikulum::get_daftar_kurikulum($request->kd_prodi);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dimuat',
                'data' => $kurikulum
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors(),
                'data' => []
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in json_kurikulum_by_prodi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat kurikulum',
                'data' => []
            ], 500);
        }
    }

    public function insup(Request $request)
    {
        try {
            // Validasi input dengan pesan yang lebih jelas
            $validated = $request->validate([
                'id' => 'required|string',
                'kode_matakuliah' => 'required|string|max:20',
                'nama_matakuliah' => 'required|string|max:255',
                'id_kurikulum' => 'required|string',
                'jumlah_sks' => 'required|integer|min:1|max:6',
                'id_konsentrasi' => 'required|string',
                'semester' => 'required|integer|min:1|max:8',
                'id_jenis_matakuliah' => 'required|string',
                'id_jenis_pelaksanaan' => 'required|string',
                'id_matakuliah_prasyarat' => 'nullable|array',
                'id_matakuliah_prasyarat.*' => 'string'
            ], [
                'kode_matakuliah.required' => 'Kode matakuliah wajib diisi',
                'kode_matakuliah.max' => 'Kode matakuliah maksimal 20 karakter',
                'nama_matakuliah.required' => 'Nama matakuliah wajib diisi',
                'nama_matakuliah.max' => 'Nama matakuliah maksimal 255 karakter',
                'jumlah_sks.min' => 'Jumlah SKS minimal 1',
                'jumlah_sks.max' => 'Jumlah SKS maksimal 6',
                'semester.min' => 'Semester minimal 1',
                'semester.max' => 'Semester maksimal 8',
            ]);

            // Proses prasyarat - gabungkan dengan delimiter yang sesuai
            $prasyarat = null;
            if (!empty($validated['id_matakuliah_prasyarat'])) {
                $prasyarat = implode(";", array_filter($validated['id_matakuliah_prasyarat']));
            }

            // Panggil method model untuk insert/update
            $result = Matakuliah::insup(
                $validated['id'],
                $validated['kode_matakuliah'],
                $validated['nama_matakuliah'],
                $validated['id_kurikulum'],
                $validated['jumlah_sks'],
                $validated['id_konsentrasi'],
                $validated['semester'],
                $validated['id_jenis_matakuliah'],
                $validated['id_jenis_pelaksanaan'],
                $prasyarat
            );

            if ($result->status == 1) {
                return response()->json([
                    'success' => true,
                    'status' => 200,
                    'message' => $result->keterangan ?? 'Data berhasil disimpan',
                    'data' => $result
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'status' => 400,
                    'message' => $result->keterangan ?? 'Gagal menyimpan data',
                    'data' => $result
                ], 400);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error in matakuliah insup: ' . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));

            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string',
                'status' => 'required|string'
            ], [
                'id.required' => 'ID matakuliah wajib diisi',
                'status.required' => 'Status wajib diisi'
            ]);

            $result = Matakuliah::set_aktif($validated['id'], $validated['status']);

            if ($result->status == 1) {
                Session::flash('success_message', $result->keterangan ?? 'Data berhasil dihapus');
            } else {
                Session::flash('failed_message', $result->keterangan ?? 'Gagal menghapus data');
            }
            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Session::flash('failed_message', 'Data tidak valid: ' . implode(', ', array_flatten($e->errors())));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Error in matakuliah delete: ' . $e->getMessage());
            Session::flash('failed_message', 'Terjadi kesalahan saat menghapus data');
            return redirect()->back();
        }
    }

    /**
     * Method helper untuk mendapatkan konsentrasi berdasarkan program studi
     */
    public function json_konsentrasi_by_prodi(Request $request)
    {
        try {
            $request->validate([
                'kd_prodi' => 'required|string',
            ]);

            $konsentrasi = KonsentrasiJurusan::get_konsentrasi_jurusan(
                '00000000-0000-0000-0000-000000000000',
                $request->kd_prodi
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dimuat',
                'data' => $konsentrasi
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in json_konsentrasi_by_prodi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat konsentrasi',
                'data' => []
            ], 500);
        }
    }
}
