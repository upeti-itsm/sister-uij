<?php

namespace App\Http\Controllers\DosenPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\KRS;
use App\Models\Akademik\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPUnit\Exception;

class DosenKRSController extends Controller
{
    /**
     * Menampilkan halaman persetujuan KRS
     */
    public function index()
    {
        try {
            $menu = 'Validasi KRS DPS';
            $semester = Semester::get_semester();
            return view('dosen_page.akademik.perwalian.persetujuan_krs_dps', compact('menu', 'semester'));
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memuat halaman KRS '.$e->getMessage());
        }
    }

    /**
     * Get rekap data KRS untuk statistik
     */
    public function getRekapData(Request $request)
    {
        try {
            $user = Session::get('user');
            $nidn = $user->nidn ??  '';
            $tahun_akademik = $request->tahun_akademik ?? '1';

            $rekap = KRS::get_rekap_krs_dps($nidn, $tahun_akademik);

            if (! $rekap) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Data rekap tidak ditemukan',
                    'data' => []
                ]);
            }

            // Convert object to array untuk konsistensi
            $rekapData = [
                'nidn_dps' => $rekap->nidn_dps ??  '',
                'nama_dps' => $rekap->nama_dps ?? '',
                'kd_prodi' => $rekap->kd_prodi ?? '',
                'nama_prodi' => $rekap->nama_prodi ?? '',
                'nama_fakultas' => $rekap->nama_fakultas ?? '',

                // Total Mahasiswa
                'total_mahasiswa_bimbingan' => $rekap->total_mahasiswa_bimbingan ?? 0,
                'total_mahasiswa_sudah_krs' => $rekap->total_mahasiswa_sudah_krs ?? 0,
                'total_mahasiswa_belum_krs' => $rekap->total_mahasiswa_belum_krs ?? 0,
                'persentase_sudah_krs' => $rekap->persentase_sudah_krs ?? 0,
                'persentase_belum_krs' => $rekap->persentase_belum_krs ?? 0,

                // Total KRS per Status
                'total_krs' => $rekap->total_krs ?? 0,
                'draft' => $rekap->draft ?? 0,
                'menunggu_persetujuan' => $rekap->menunggu_persetujuan ?? 0,
                'disetujui' => $rekap->disetujui ?? 0,
                'ditolak' => $rekap->ditolak ?? 0,
                'selesai' => $rekap->selesai ?? 0,

                // Persentase
                'persentase_draft' => $rekap->persentase_draft ?? 0,
                'persentase_menunggu' => $rekap->persentase_menunggu ?? 0,
                'persentase_disetujui' => $rekap->persentase_disetujui ?? 0,
                'persentase_ditolak' => $rekap->persentase_ditolak ?? 0,
                'persentase_selesai' => $rekap->persentase_selesai ?? 0,

                // Progress Verifikasi
                'krs_belum_diverifikasi' => $rekap->krs_belum_diverifikasi ?? 0,
                'krs_sudah_diverifikasi' => $rekap->krs_sudah_diverifikasi ?? 0,
                'persentase_verifikasi' => $rekap->persentase_verifikasi ?? 0,

                // Waktu Proses
                'avg_waktu_verifikasi_hari' => $rekap->avg_waktu_verifikasi_hari ?? 0,
                'avg_waktu_ke_kaprodi_hari' => $rekap->avg_waktu_ke_kaprodi_hari ?? 0,
                'avg_waktu_total_proses_hari' => $rekap->avg_waktu_total_proses_hari ?? 0,

                // Beban Kerja
                'total_mk_diambil' => $rekap->total_mk_diambil ?? 0,
                'total_sks_diambil' => $rekap->total_sks_diambil ?? 0,
                'avg_mk_per_mahasiswa' => $rekap->avg_mk_per_mahasiswa ??  0,
                'avg_sks_per_mahasiswa' => $rekap->avg_sks_per_mahasiswa ?? 0,

                // Alert
                'krs_tertunda_lebih_3_hari' => $rekap->krs_tertunda_lebih_3_hari ?? 0,
                'krs_tertunda_lebih_7_hari' => $rekap->krs_tertunda_lebih_7_hari ?? 0,
                'tingkat_penolakan_persen' => $rekap->tingkat_penolakan_persen ??  0,
            ];

            return response()->json([
                'status' => '1',
                'message' => 'Data rekap berhasil dimuat',
                'data' => [$rekapData]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Gagal memuat data rekap:  ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get daftar KRS mahasiswa bimbingan untuk DataTable
     */
    public function getKRSList(Request $request)
    {
        try {
            $user = Session::get('user');
            $nidn = $user->nidn ?? '';

            // DataTable parameters
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $search = $request->input('search_mahasiswa', '');
            $status_krs = $request->input('status_krs', null);

            // Convert empty string to null
            if ($status_krs === '') {
                $status_krs = null;
            } else {
                $status_krs = (int) $status_krs;
            }

            $tahun_akademik = $request->tahun_akademik ?? '1';

            // Get data dari database
            $data = KRS::get_daftar_krs_dps(
                $nidn,
                $status_krs,
                $start,
                $length,
                $search,
                $tahun_akademik
            );

            // Get total records
            $recordsTotal = 0;
            $recordsFiltered = 0;

            if (count($data) > 0) {
                $recordsTotal = $data[0]->jml_record ??  0;
                $recordsFiltered = $recordsTotal;
            }

            // Format data untuk DataTable
            $formattedData = array_map(function($item) {
                return [
                    'nomor' => $item->nomor ??  0,
                    'id_krs_mahasiswa' => $item->id_krs_mahasiswa ?? '',
                    'nim' => $item->nim ?? '',
                    'nama_mahasiswa' => $item->nama_mahasiswa ?? '',
                    'nama_prodi' => $item->nama_prodi ?? '',
                    'tahun_akademik' => $item->tahun_akademik ?? '',
                    'status_krs' => $item->status_krs ?? 0,
                    'status_text' => $item->status_text ?? '',
                    'tgl_pengisian' => $item->tgl_pengisian ?? null,
                    'tgl_pengisian_formatted' => $item->tgl_pengisian_formatted ??  '-',
                    'tgl_pengajuan_krs' => $item->tgl_pengajuan_krs ?? null,
                    'tgl_pengajuan_formatted' => $item->tgl_pengajuan_formatted ?? '-',
                    'total_mk' => $item->total_mk ?? 0,
                    'total_sks' => $item->total_sks ?? 0,
                    'komentar_dps' => $item->komentar_dps ?? '',
                ];
            }, $data);

            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData
            ]);

        } catch (Exception $e) {
            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Gagal memuat data:  ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail KRS mahasiswa
     */
    public function getKRSDetail(Request $request)
    {
        try {
            $id_krs = $request->input('id_krs');
            if (!$id_krs) {
                return response()->json([
                    'status' => '0',
                    'message' => 'ID KRS tidak valid'
                ], 400);
            }

            // Get detail KRS dari stored procedure yang sudah ada
            $details = KRS::get_detail_krs($id_krs);

            if (empty($details)) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Data KRS tidak ditemukan'
                ], 404);
            }

            // Ambil data pertama untuk info mahasiswa
            $firstRow = $details[0];

            // Format tanggal pengajuan
            $tgl_pengajuan_formatted = '-';
            if (! empty($firstRow->tgl_pengajuan_krs)) {
                $tgl_pengajuan_formatted = date('d-m-Y', strtotime($firstRow->tgl_pengajuan_krs));
            }

            $data = [
                'id_krs_mahasiswa' => $firstRow->id_krs_mahasiswa ??  '',
                'nim' => $firstRow->nim ?? '',
                'nama_mahasiswa' => $firstRow->nama_mahasiswa ??  '',
                'nama_prodi' => $firstRow->nama_prodi ?? '',
                'tahun_akademik' => $firstRow->tahun_akademik ??  '',
                'status_krs' => $firstRow->status_krs ?? 0,
                'status_text' => $this->getStatusText($firstRow->status_krs ?? 0),
                'tgl_pengajuan_formatted' => $tgl_pengajuan_formatted,
                'komentar_dps' => $firstRow->komentar_dps ?? '',
                'total_mk' => count($details),
                'total_sks' => array_sum(array_column($details, 'sks'))
            ];

            return response()->json([
                'status' => '1',
                'message' => 'Data detail KRS berhasil dimuat',
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Gagal memuat detail KRS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail mata kuliah dalam KRS
     */
    public function getKRSMataKuliah(Request $request)
    {
        try {
            $id_krs = $request->input('id_krs');
            if (!$id_krs) {
                return response()->json([
                    'status' => '0',
                    'message' => 'ID KRS tidak valid',
                    'data' => []
                ], 400);
            }

            $details = KRS::get_detail_krs($id_krs);
            if (empty($details)) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Data mata kuliah tidak ditemukan',
                    'data' => []
                ], 404);
            }

            // Array nama hari
            $hari_names = [
                0 => '-',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];

            // Format data mata kuliah
            $formattedData = array_map(function($item) use ($hari_names) {
                return [
                    'id_krs_detail' => $item->id_krs_detail ?? '',
                    'kd_mata_kuliah' => $item->kd_mata_kuliah ??  '',
                    'nama_mata_kuliah' => $item->nama_mata_kuliah ?? '',
                    'nama_kelas' => $item->nama_kelas ?? '',
                    'jenis_kelas' => $item->jenis_kelas ?? '',
                    'sks' => $item->sks ?? 0,
                    'hari' => $item->hari ?? 0,
                    'hari_nama' => $hari_names[$item->hari ??  0] ?? '-',
                    'jam_mulai' => $item->jam_mulai ??  '',
                    'jam_selesai' => $item->jam_selesai ?? '',
                    'ruang' => $item->ruang ?? '',
                    'lokasi' => $item->lokasi ?? '',
                    'nama_dosen' => $item->nama_dosen ?? '',
                ];
            }, $details);

            return response()->json([
                'status' => '1',
                'message' => 'Data mata kuliah berhasil dimuat',
                'data' => $formattedData
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Gagal memuat data mata kuliah:  ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Approve KRS mahasiswa
     */
    public function approveKRS(Request $request)
    {
        try {
            $user = Session::get('user');
            $nidn = $user->nidn ?? '';

            $id_krs = $request->input('id_krs');
            $komentar = $request->input('komentar', '');

            if (!$id_krs) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'ID KRS tidak valid'
                ], 400);
            }

            // Status 2 = ACC DPS / MENUNGGU KAPRODI
            $result = KRS::update_status_krs($id_krs, 2, $komentar, $nidn);

            if ($result && ($result->status == '1' || $result->status == 1)) {
                return response()->json([
                    'status' => '1',
                    'keterangan' => $result->keterangan ?? 'KRS berhasil disetujui'
                ]);
            } else {
                return response()->json([
                    'status' => '0',
                    'keterangan' => $result->keterangan ?? 'Gagal menyetujui KRS'
                ], 400);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject KRS mahasiswa
     */
    public function rejectKRS(Request $request)
    {
        try {
            $user = Session::get('user');
            $nidn = $user->nidn ?? '';

            $id_krs = $request->input('id_krs');
            $alasan = $request->input('alasan', '');

            if (! $id_krs) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'ID KRS tidak valid'
                ], 400);
            }

            if (empty(trim($alasan))) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Alasan penolakan wajib diisi'
                ], 400);
            }

            // Status 3 = DITOLAK DPS
            $result = KRS::update_status_krs($id_krs, 3, $alasan, $nidn);

            if ($result && ($result->status == '1' || $result->status == 1)) {
                return response()->json([
                    'status' => '1',
                    'keterangan' => $result->keterangan ?? 'KRS berhasil ditolak'
                ]);
            } else {
                return response()->json([
                    'status' => '0',
                    'keterangan' => $result->keterangan ?? 'Gagal menolak KRS'
                ], 400);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function untuk mendapatkan text status
     */
    private function getStatusText($status)
    {
        $statusMap = [
            0 => 'Draft',
            1 => 'Menunggu Persetujuan DPS',
            2 => 'Disetujui DPS',
            3 => 'Ditolak',
            4 => 'Selesai'
        ];

        return $statusMap[$status] ??  'Tidak Diketahui';
    }
}
