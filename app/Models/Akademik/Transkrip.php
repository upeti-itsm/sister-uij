<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transkrip extends Model
{
    use HasFactory;

    protected $table = 'akademik.pengajuan_transkrip';
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengajuan',
        'nim',
        'tahun_akademik',
        'keperluan',
        'catatan_mahasiswa',
        'status',
        'tanggal_pengajuan',
        'tanggal_acc_kaprodi',
        'tanggal_acc_dekan',
        'id_kaprodi',
        'id_dekan',
        'nama_kaprodi',
        'nama_dekan',
        'nidn_kaprodi',
        'nidn_dekan',
        'catatan_kaprodi',
        'catatan_dekan',
        'qr_code_kaprodi',
        'qr_code_dekan',
        'nomor_transkrip'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_acc_kaprodi' => 'datetime',
        'tanggal_acc_dekan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED_KAPRODI = 'approved_kaprodi';
    const STATUS_APPROVED_DEKAN = 'approved_dekan';
    const STATUS_REJECTED_KAPRODI = 'rejected_kaprodi';
    const STATUS_REJECTED_DEKAN = 'rejected_dekan';

    /**
     * Generate ID Pengajuan
     * Format: TRS-YYYYMMDD-XXXX
     *
     * @return string
     */
    public static function generateIdPengajuan()
    {
        $prefix = 'TRS';
        $date = date('Ymd');

        $lastPengajuan = self::where('id_pengajuan', 'LIKE', "$prefix-$date-%")
            ->orderBy('id_pengajuan', 'DESC')
            ->first();

        if ($lastPengajuan) {
            $lastNumber = intval(substr($lastPengajuan->id_pengajuan, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "$prefix-$date-$newNumber";
    }

    /**
     * Buat pengajuan transkrip baru
     *
     * @param string $nim NIM mahasiswa
     * @param string $keperluan Keperluan transkrip
     * @param string $catatan Catatan tambahan dari mahasiswa
     * @return object|null
     */
    public static function buatPengajuan($nim, $keperluan, $catatan = '')
    {
        try {
            DB::beginTransaction();

            // Cek apakah ada pengajuan yang masih pending
            $existingPending = self::where('nim', $nim)
                ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED_KAPRODI])
                ->first();

            if ($existingPending) {
                throw new \Exception('Anda masih memiliki pengajuan transkrip yang belum selesai diproses');
            }

            $idPengajuan = self::generateIdPengajuan();

            $pengajuan = self::create([
                'id_pengajuan' => $idPengajuan,
                'nim' => $nim,
                'tahun_akademik' => date('Y'),
                'keperluan' => $keperluan,
                'catatan_mahasiswa' => $catatan,
                'status' => self::STATUS_PENDING,
                'tanggal_pengajuan' => now()
            ]);

            DB::commit();

            return $pengajuan;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get daftar pengajuan transkrip mahasiswa
     *
     * @param string $nim NIM mahasiswa
     * @param string $status Filter status (optional)
     * @param int $offset Offset pagination
     * @param int $limit Limit pagination
     * @return array
     */
    public static function getDaftarPengajuan($nim, $status = '', $offset = 0, $limit = 25)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_pengajuan_transkrip_mahasiswa(?,?,?,?)", [
            $nim,
            $status,
            $offset,
            $limit
        ]);
    }

    /**
     * Get detail pengajuan transkrip
     *
     * @param string $idPengajuan ID pengajuan
     * @return object|null
     */
    public static function getDetailPengajuan($idPengajuan)
    {
        return DB::selectOne('SELECT * FROM akademik.get_detail_pengajuan_transkrip(?)', [
            $idPengajuan
        ]);
    }

    /**
     * Get data transkrip lengkap untuk PDF
     * Includes nilai, IPK, dll
     *
     * @param string $nim NIM mahasiswa
     * @param string $idPengajuan ID pengajuan
     * @return object|null
     */
    public static function getDataTranskrip($nim, $idPengajuan = '')
    {
        return DB::selectOne('SELECT * FROM akademik.get_data_transkrip_mahasiswa(?,?)', [
            $nim,
            $idPengajuan
        ]);
    }

    /**
     * Get daftar nilai untuk transkrip
     *
     * @param string $nim NIM mahasiswa
     * @return array
     */
    public static function getDaftarNilaiTranskrip($nim)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_nilai_transkrip(?)', [
            $nim
        ]);
    }

    /**
     * Cancel pengajuan transkrip (hanya bisa jika status masih pending)
     *
     * @param string $idPengajuan ID pengajuan
     * @param string $nim NIM mahasiswa (untuk validasi)
     * @return bool
     */
    public static function cancelPengajuan($idPengajuan, $nim)
    {
        try {
            DB::beginTransaction();

            $pengajuan = self::where('id_pengajuan', $idPengajuan)
                ->where('nim', $nim)
                ->where('status', self::STATUS_PENDING)
                ->first();

            if (!$pengajuan) {
                throw new \Exception('Pengajuan tidak ditemukan atau tidak dapat dibatalkan');
            }

            $pengajuan->delete();

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check apakah mahasiswa memiliki pengajuan aktif
     *
     * @param string $nim NIM mahasiswa
     * @return object|null
     */
    public static function cekPengajuanAktif($nim)
    {
        return self::where('nim', $nim)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED_KAPRODI])
            ->first();
    }

    /**
     * Get statistik pengajuan mahasiswa
     *
     * @param string $nim NIM mahasiswa
     * @return object
     */
    public static function getStatistikPengajuan($nim)
    {
        $stats = DB::selectOne("
            SELECT
                COUNT(*) as total_pengajuan,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved_kaprodi' THEN 1 ELSE 0 END) as approved_kaprodi,
                SUM(CASE WHEN status = 'approved_dekan' THEN 1 ELSE 0 END) as approved_dekan,
                SUM(CASE WHEN status LIKE 'rejected%' THEN 1 ELSE 0 END) as rejected
            FROM akademik.pengajuan_transkrip
            WHERE nim = ?
        ", [$nim]);

        return $stats ?? (object)[
            'total_pengajuan' => 0,
            'pending' => 0,
            'approved_kaprodi' => 0,
            'approved_dekan' => 0,
            'rejected' => 0
        ];
    }

    /**
     * Get status label dengan badge class
     *
     * @param string $status
     * @return array
     */
    public static function getStatusLabel($status)
    {
        $labels = [
            self::STATUS_PENDING => [
                'text' => 'Menunggu Persetujuan Kaprodi',
                'class' => 'warning',
                'icon' => 'clock'
            ],
            self::STATUS_APPROVED_KAPRODI => [
                'text' => 'Disetujui Kaprodi - Menunggu Dekan',
                'class' => 'info',
                'icon' => 'hourglass-half'
            ],
            self::STATUS_APPROVED_DEKAN => [
                'text' => 'Disetujui - Siap Download',
                'class' => 'success',
                'icon' => 'check-circle'
            ],
            self::STATUS_REJECTED_KAPRODI => [
                'text' => 'Ditolak Kaprodi',
                'class' => 'danger',
                'icon' => 'times-circle'
            ],
            self::STATUS_REJECTED_DEKAN => [
                'text' => 'Ditolak Dekan',
                'class' => 'danger',
                'icon' => 'times-circle'
            ]
        ];

        return $labels[$status] ?? [
            'text' => 'Status Tidak Diketahui',
            'class' => 'secondary',
            'icon' => 'question-circle'
        ];
    }

    /**
     * Get informasi mahasiswa untuk transkrip
     *
     * @param string $nim NIM mahasiswa
     * @return object|null
     */
    public static function getInfoMahasiswa($nim)
    {
        return DB::selectOne('SELECT * FROM akademik.get_info_mahasiswa_transkrip(?)', [
            $nim
        ]);
    }

    /**
     * Validasi kelayakan mahasiswa untuk mengajukan transkrip
     * (cek IPK, SKS minimal, status mahasiswa, dll)
     *
     * @param string $nim NIM mahasiswa
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validateKelayakanPengajuan($nim)
    {
        try {
            $info = DB::selectOne('SELECT * FROM akademik.validate_kelayakan_transkrip(?)', [$nim]);

            if (!$info) {
                return [
                    'valid' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ];
            }

            return [
                'valid' => $info->is_valid ?? false,
                'message' => $info->message ?? 'Validasi gagal',
                'data' => $info
            ];

        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Terjadi kesalahan saat validasi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        if (!empty($status)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope untuk pengajuan terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_pengajuan', 'DESC');
    }

    /**
     * Accessor untuk status label
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatusLabel($this->status);
    }

    /**
     * Accessor untuk cek apakah sudah bisa download
     */
    public function getCanDownloadAttribute()
    {
        return $this->status === self::STATUS_APPROVED_DEKAN;
    }

    /**
     * Accessor untuk cek apakah bisa di-cancel
     */
    public function getCanCancelAttribute()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Accessor untuk format tanggal pengajuan
     */
    public function getTanggalPengajuanFormattedAttribute()
    {
        return $this->tanggal_pengajuan ?
            $this->tanggal_pengajuan->format('d/m/Y H:i') : '-';
    }
}
