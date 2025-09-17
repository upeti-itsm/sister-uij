<?php

namespace App\Http\Controllers\AdminAkademikPage\Plotting;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JenisPengajaran;
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Akademik\Kurikulum;
use App\Models\Akademik\Matakuliah;
use App\Models\Akademik\PlottingPerkuliahan;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PlottingPerkuliahanController extends Controller
{
    /**
     * Display the main page for plotting lectures.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $menu = 'Plotting Perkuliahan';

        // Filter data
        $program_studi = ProgramStudi::get_program_studi();
        $tahun_akademik = Semester::get_semester();

        // Form data - Initialize with first available data
        $kurikulum = [];
        $matakuliah = [];
        $kelas = [];

        if (!empty($program_studi)) {
            $kurikulum = Kurikulum::get_daftar_kurikulum($program_studi[0]->kd_program_studi);

            if (!empty($kurikulum)) {
                $matakuliah = Matakuliah::get_daftar('all', $kurikulum[0]->id_kurikulum);
            }

            if (!empty($tahun_akademik)) {
                $tahunAkademikFormatted = $tahun_akademik[0]->tahun_akademik . $tahun_akademik[0]->id_semester_uij;
                $kelas = KelasPerkuliahan::get_daftar($program_studi[0]->kd_program_studi, $tahunAkademikFormatted);
            }
        }

        $dosen = PlottingPerkuliahan::get_dosen();
        $jenis_pengajaran = JenisPengajaran::get_daftar();

        return view('admin_akademik_page.plotting.plotting_perkuliahan', compact(
            'menu',
            'program_studi',
            'tahun_akademik',
            'matakuliah',
            'kurikulum',
            'dosen',
            'jenis_pengajaran',
            'kelas'
        ));
    }

    /**
     * Handle API operations with try-catch wrapper.
     *
     * @param callable $operation
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiOperation(callable $operation)
    {
        try {
            $result = $operation();

            // Handle response from model functions
            if (is_object($result) || is_array($result)) {
                $result = (object)$result;

                return response()->json([
                    'success' => isset($result->status) ? $result->status == 1 : true,
                    'message' => $result->message ?? $result->keterangan ?? 'Operasi berhasil',
                    'data' => $result
                ]);
            }

            // Handle simple return values
            return response()->json([
                'success' => true,
                'message' => 'Operasi berhasil',
                'data' => $result
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Handle DataTables operations with try-catch wrapper.
     *
     * @param callable $operation
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleDataTablesOperation(callable $operation, Request $request)
    {
        try {
            return response()->json($operation());
        } catch (\Exception $e) {
            return response()->json([
                'draw' => $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return JSON data for DataTables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(Request $request)
    {
        $request->validate([
            'kd_prodi' => 'required|string',
            'tahun_akademik' => 'required|string',
        ]);

        return $this->handleDataTablesOperation(function () use ($request) {
            $length = $request->input('length');
            $start = $request->input('start');
            $search = $request->input('search')['value'] ?? '';
            $kd_prodi = $request->input('kd_prodi');
            $tahun_akademik = $request->input('tahun_akademik');

            $data_ = PlottingPerkuliahan::get_daftar($kd_prodi, $tahun_akademik, $search, $start, $length);

            $response = [
                'draw' => $request->input('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => $data_,
                'error' => null
            ];

            if (count($data_) > 0) {
                $response['recordsTotal'] = $data_[0]->jml_record;
                $response['recordsFiltered'] = $response['recordsTotal'];
            }

            return $response;
        }, $request);
    }

    /**
     * Store a new plotting.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->handleApiOperation(function () use ($request) {
            $validated = $request->validate([
                'id_matakuliah'   => 'required|uuid',
                'id_karyawan'     => 'required',
                'tahun_akademik'  => 'required|string|max:20',
                'jenis_pengajaran'=> 'required',
                'id_kelas'        => 'required|uuid',
            ]);

            // Normalisasi id_karyawan -> selalu string
            $idKaryawan = is_array($validated['id_karyawan'])
                ? implode(',', $validated['id_karyawan']) // misal gabung dengan koma
                : (string) $validated['id_karyawan'];

            return PlottingPerkuliahan::insup(
                $validated['id_matakuliah'],
                $idKaryawan,
                $validated['tahun_akademik'],
                $validated['jenis_pengajaran'],
                $validated['id_kelas']
            );
        });
    }

    /**
     * Update an existing plotting.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        return $this->handleApiOperation(function () use ($request) {
            $validated = $request->validate([
                'id'              => 'required|uuid',
                'id_matakuliah'   => 'required|uuid',
                'id_karyawan'     => 'required',
                'tahun_akademik'  => 'required|string|max:20',
                'jenis_pengajaran'=> 'required',
                'id_kelas'        => 'required|uuid',
            ]);

            // Normalisasi id_karyawan -> selalu string
            $idKaryawan = is_array($validated['id_karyawan'])
                ? implode(',', $validated['id_karyawan']) // bisa juga ambil index 0 kalau hanya satu
                : (string) $validated['id_karyawan'];

            return PlottingPerkuliahan::insup(
                $validated['id_matakuliah'],
                $idKaryawan,
                $validated['tahun_akademik'],
                $validated['jenis_pengajaran'],
                $validated['id_kelas'],
                $validated['id']
            );
        });
    }

    /**
     * Store plotting data - Enhanced untuk support import
     */
    public function import(Request $request)
    {
        try {
            // Validation rules
            $request->validate([
                'kd_matakuliah' => 'required',
                'nidn' => 'required',
                'tahun_akademik' => 'required',
                'jenis_pengajaran' => 'required',
                'kd_kelas' => 'required'
            ], [
                'kd_matakuliah.required' => 'Mata kuliah harus dipilih',
                'nidn.required' => 'Dosen pengampu harus dipilih',
                'tahun_akademik.required' => 'Tahun akademik harus dipilih',
                'jenis_pengajaran.required' => 'Jenis pengajaran harus dipilih',
                'kd_kelas.required' => 'Kelas harus dipilih'
            ]);

            // Panggil function import database
            $result = PlottingPerkuliahan::import_ploting(
                $request->kd_matakuliah,    // kd_matkul
                $request->nidn,         // nidn
                $request->tahun_akademik,   // tahun_akademik
                $request->jenis_pengajaran, // jenis_pengajaran
                $request->kd_kelas          // kd_kelas
            );

            // Cek hasil dari database function
            if ($result && isset($result->status)) {
                if ($result->status === 1) {
                    return response()->json([
                        'success' => true,
                        'message' => $result->keterangan ?? 'Data plotting berhasil disimpan'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => $result->keterangan ?? 'Gagal menyimpan data plotting'
                    ]);
                }
            }

            // Jika response tidak sesuai format, anggap berhasil jika tidak ada error
            return response()->json([
                'success' => true,
                'message' => 'Data plotting berhasil disimpan'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of a plotting.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        return $this->handleApiOperation(function () use ($request) {
            $validated = $request->validate([
                'id' => 'required|uuid',
                'status' => 'required'
            ]);

            return PlottingPerkuliahan::set_aktif($validated['id'], $validated['status']);
        });
    }

    /**
     * Get kurikulum by program studi (cascade).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKurikulumByProdi(Request $request)
    {
        try {
            $validated = $request->validate([
                'kd_prodi' => 'required|string'
            ]);

            $result = Kurikulum::get_daftar_kurikulum($validated['kd_prodi']);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get matakuliah by kurikulum (cascade).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMatakuliahByKurikulum(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_kurikulum' => 'required|uuid'
            ]);

            $result = Matakuliah::get_daftar('all', $validated['id_kurikulum']);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get kelas by program studi and tahun akademik (cascade).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKelasByProdiTahun(Request $request)
    {
        try {
            $validated = $request->validate([
                'kd_prodi' => 'required|string',
                'tahun_akademik' => 'required|string'
            ]);

            $result = KelasPerkuliahan::get_daftar($validated['kd_prodi'], $validated['tahun_akademik']);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
