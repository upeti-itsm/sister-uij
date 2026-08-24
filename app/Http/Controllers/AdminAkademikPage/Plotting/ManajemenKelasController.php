<?php

namespace App\Http\Controllers\AdminAkademikPage\Plotting;

use App\Http\Controllers\Controller;
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class ManajemenKelasController extends Controller
{
    /**
     * Display the main page for managing teaching types.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $menu = 'Manajemen Kelas Perkuliahan';
        $kd_fakultas = Session::get('user')->kd_fakultas ?? '0';
        if (in_array(Session::get('peran')['aktif'], [39])) {
            $kd_fakultas = "0";
        }
        $program_studi = ProgramStudi::get_program_studi("0", $kd_fakultas);
        $tahun_akademik = Semester::get_semester();
        return view('admin_akademik_page.plotting.kelas_perkuliahan', compact('menu', 'program_studi', 'tahun_akademik'));
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

            // Jika result adalah object/array dengan property message atau keterangan
            if (is_object($result) || is_array($result)) {
                $result = (object) $result;

                return response()->json([
                    'success' => isset($result->status) ? $result->status == 1 : true,
                    'message' => $result->message ?? $result->keterangan ?? 'Operasi berhasil',
                    'data' => $result
                ]);
            }

            // Jika result hanya return value biasa
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
            'kd_prodi' => 'required',
            'tahun_akademik' => 'required',
        ]);
        return $this->handleDataTablesOperation(function() use ($request) {
            $length = $request->input('length');
            $start = $request->input('start');
            $search = $request->input('search')['value'] ?? '';

            $data_ = KelasPerkuliahan::get_daftar($request->kd_prodi, $request->tahun_akademik, $search, $start, $length);

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
     * Store a new teaching type.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->handleApiOperation(function() use ($request) {
            $request->validate([
                'nama_kelas' => 'required',
                'kode_kelas' => 'required',
                'kd_prodi' => 'required',
                'tahun_akademik' => 'required',
            ]);
            return KelasPerkuliahan::insup($request->nama_kelas, $request->kode_kelas, $request->kd_prodi, $request->tahun_akademik, $request->keterangan);
        });
    }

    /**
     * Update an existing teaching type.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        return $this->handleApiOperation(function() use ($request) {
            $request->validate([
                'id' => 'required|uuid',
                'nama_kelas' => 'required',
                'kode_kelas' => 'required',
                'kd_prodi' => 'required',
                'tahun_akademik' => 'required',
            ]);

            return KelasPerkuliahan::insup($request->nama_kelas, $request->kode_kelas, $request->kd_prodi, $request->tahun_akademik, $request->keterangan, $request->id);
        });
    }

    /**
     * Toggle the active status of a teaching type (soft delete).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        return $this->handleApiOperation(function() use ($request) {
            $request->validate([
                'id' => 'required|uuid',
                'status' => 'required'
            ]);

            return KelasPerkuliahan::set_aktif($request->id, $request->status);
        });
    }
}
