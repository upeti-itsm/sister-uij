<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePengajuanSuratTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS akademik');

        DB::statement('CREATE TABLE IF NOT EXISTS akademik.pengajuan_surat (
            id_pengajuan uuid PRIMARY KEY,
            nomor_pengajuan varchar(50) NOT NULL,
            nim varchar(20) NOT NULL,
            nama_mahasiswa varchar(150),
            nama_prodi varchar(150),
            kd_fakultas varchar(50),
            id_fakultas varchar(50),
            jenis_surat varchar(10) NOT NULL,
            keperluan text NOT NULL,
            tahun_akademik varchar(50),
            semester varchar(20),
            status varchar(30) NOT NULL,
            catatan text,
            id_dosen_pembimbing varchar(50),
            nama_dosen_pembimbing varchar(150),
            nidn_dosen_pembimbing varchar(50),
            tgl_pengajuan timestamp without time zone DEFAULT now(),
            tgl_disetujui_dosen timestamp without time zone,
            id_dekan varchar(50),
            nama_dekan varchar(150),
            nidn_dekan varchar(50),
            tgl_disetujui_dekan timestamp without time zone,
            tgl_ditolak timestamp without time zone,
            created_at timestamp without time zone DEFAULT now(),
            updated_at timestamp without time zone DEFAULT now()
        )');

        DB::statement('CREATE INDEX IF NOT EXISTS idx_pengajuan_surat_nim ON akademik.pengajuan_surat (nim)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_pengajuan_surat_nidn ON akademik.pengajuan_surat (nidn_dosen_pembimbing)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_pengajuan_surat_fakultas ON akademik.pengajuan_surat (id_fakultas, kd_fakultas)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_pengajuan_surat_status ON akademik.pengajuan_surat (status)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TABLE IF EXISTS akademik.pengajuan_surat');
    }
}
