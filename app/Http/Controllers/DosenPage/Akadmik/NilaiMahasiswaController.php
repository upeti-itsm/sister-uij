<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Http\Controllers\Controller;
use App\Models\SIAKAD_MODEL\JadwalDosen;
use Illuminate\Http\Request;

class NilaiMahasiswaController extends Controller
{
    public function index($id)
    {
        $menu = 'Dosen - Akademik - Melihat Daftar Matakuliah';
        $mahasiswa = JadwalDosen::get_list_mahasiswa_by_jadwal($id);
//        dd($mahasiswa);
        return view('dosen_page.akademik.nilai_mahasiswa', compact('menu', 'mahasiswa'));
    }

    public function store_nilai(Request $request)
    {
        try {
            // Check if this is a batch save request
            if ($request->has('save_all') && $request->save_all) {
                return $this->handleBatchSaveWithFunction($request);
            }

            // Handle individual save (backward compatibility)
            return $this->handleIndividualSave($request);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function handleIndividualSave(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required',
            'nim' => 'required|string',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        // Convert individual save to batch format for consistency
        $nilaiData = [
            $request->nim => [
                $request->kriteria_id => $request->nilai
            ]
        ];

        return $this->processBatchWithFunction($nilaiData, true);
    }

    private function handleBatchSaveWithFunction(Request $request)
    {
        $request->validate([
            'nilai_data' => 'required|array',
            'nilai_data.*' => 'array',
            'nilai_data.*.*' => 'numeric|min:0|max:100',
        ]);

        return $this->processBatchWithFunction($request->nilai_data, false);
    }

    private function processBatchWithFunction($nilaiData, $isIndividual = false)
    {
        try {
            // Convert JavaScript format to database function format
            $functionParams = $this->convertToFunctionParams($nilaiData);

            if (empty($functionParams['kriteria_list']) || empty($functionParams['nim_list']) || empty($functionParams['nilai_list'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data nilai tidak valid atau kosong'
                ], 400);
            }

            $result = JadwalDosen::insup_nilai(
                $functionParams['kriteria_list'],
                $functionParams['nim_list'],
                $functionParams['nilai_list']
            );

            // Process result - selectOne returns object or null, not array
            if ($result) {
                return response()->json([
                    'success' => $result->status == 1,
                    'message' => $result->keterangan,
                    'data' => [
                        'function_result' => $result,
                        'total_mahasiswa' => count($nilaiData)
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada respon dari database'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    private function convertToFunctionParams($nilaiData)
    {
        // Extract all unique kriteria IDs
        $allKriteriaIds = [];
        foreach ($nilaiData as $nim => $kriteriaValues) {
            $allKriteriaIds = array_merge($allKriteriaIds, array_keys($kriteriaValues));
        }
        $uniqueKriteriaIds = array_unique($allKriteriaIds);

        // Pastikan urutan konsisten
        sort($uniqueKriteriaIds);

        // Create kriteria list string
        $kriteriaList = implode(',', $uniqueKriteriaIds);

        // Extract all NIMs
        $nims = array_keys($nilaiData);
        sort($nims); // Pastikan urutan konsisten
        $nimList = implode(',', $nims);

        // Create nilai matrix
        $nilaiMatrix = [];
        foreach ($nims as $nim) {
            $kriteriaValues = $nilaiData[$nim];
            $nilaiRow = [];

            // Ensure values are in the same order as kriteria list
            foreach ($uniqueKriteriaIds as $kriteriaId) {
                $nilaiRow[] = strval($kriteriaValues[$kriteriaId] ?? '0');
            }

            $nilaiMatrix[] = implode(',', $nilaiRow);
        }

        $nilaiList = implode(';', $nilaiMatrix);

        return [
            'kriteria_list' => $kriteriaList,
            'nim_list' => $nimList,
            'nilai_list' => $nilaiList
        ];
    }
}
