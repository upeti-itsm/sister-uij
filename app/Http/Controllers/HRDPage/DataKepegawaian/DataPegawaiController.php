<?php

namespace App\Http\Controllers\HRDPage\DataKepegawaian;

use App\Http\Controllers\Controller;
use App\Mail\AccountSender;
use App\Models\Akademik\ProgramStudi;
use App\Models\Organisasi\Golongan;
use App\Models\Organisasi\JabatanFungsional;
use App\Models\Organisasi\JabatanStruktural;
use App\Models\Organisasi\Karyawan;
use App\Models\Organisasi\UnitKerja;
use App\Models\Referensi\Agama;
use App\Models\Referensi\Pendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PHPUnit\Exception;
use function PHPUnit\Framework\isNull;

class DataPegawaiController extends Controller
{
    public function index()
    {
        $menu = "List Data Pegawai - HRD";
        return view('hrd_page.data_kepegawaian.list_data_pegawai', compact('menu'));
    }

    public function json_get_daftar_pegawai(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Karyawan::get_daftar_pegawai($search, $start, $length, $request->jenis_pegawai ? implode(',', $request->jenis_pegawai) : null);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function create($jenis_karyawan)
    {
        $menu = "Create Data Pegawai - HRD";
        $unit_kerja = UnitKerja::get_daftar_unit_kerja();
        $pendidikan = Pendidikan::list_pendidikan_terakhir();
        $golongan = Golongan::get_daftar_golongan();
        $prodi = ProgramStudi::get_program_studi();
        $jabatan_struktural = JabatanStruktural::get_daftar_jabatan_struktural();
        $jabatan_fungsional = JabatanFungsional::get_daftar_jabatan_fungsional();
        $agama = Agama::list_agama();
        return view('hrd_page.data_kepegawaian.create_data_pegawai', compact('menu', 'jenis_karyawan', 'unit_kerja', 'pendidikan', 'golongan', 'jabatan_struktural', 'jabatan_fungsional', 'agama', 'prodi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_photo_profile' => 'required|max:10000|mimes:jpg,jpeg,png',
            'no_ktp' => 'required|max:16|min:16',
            'jenis_kelamin' => 'required',
            'jenis_bank' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'no_hp' => 'required|min:10|max:13',
            'email' => 'required|email:rfc,dns',
            'jenis_karyawan' => 'required',
            'id_agama' => 'required',
            'alamat' => 'required',
            'unit_kerja' => 'required',
            'status_menikah' => 'required',
            'file_kk' => 'required|max:10000|mimes:pdf',
            'pendidikan_terakhir' => 'required',
            'ip_absensi' => 'required',
            'file_ijazah' => 'required|max:10000|mimes:pdf',
            'file_sk_golongan' => 'required|max:10000|mimes:pdf',
            'tgl_aktif' => 'required|date_format:Y-m-d',
            'tgl_lulus' => 'required|date_format:Y-m-d',
            'tmt_golongan' => 'required|date_format:Y-m-d',
        ], [
            'file_photo_profile.required' => 'Pastikan anda sudah memilih foto profil',
            'file_photo_profile.max' => 'Pastikan ukuran foto profile tidak lebih dari 10Mb',
            'file_photo_profile.mimes' => 'Pastikan ekstensi file adalah jpg/jpeg/png',
            'no_ktp.required' => 'Pastikan anda sudah mengisi Nomor KTP',
            'no_ktp.max' => 'Pastikan Nomor KTP tidak lebih dari 16 karakter',
            'no_ktp.min' => 'Pastikan Nomor KTP tidak kurang dari 16 karakter',
            'jenis_kelamin.required' => 'Pastikan anda sudah memilih jenis kelamin',
            'jenis_bank.required' => 'Pastikan anda sudah memilih jenis bank',
            'nama.required' => 'Pastikan nama anda sudah terisi',
            'tempat_lahir.required' => 'Pastikan tempat lahir sudah terisi',
            'tgl_lahir.required' => 'Pastikan tanggal lahir sudah terisi',
            'tgl_lahir.date_format' => 'Pastikan format tanggal lahir benar',
            'no_hp.required' => 'Pastikan nomor HP terisi',
            'no_hp.min' => 'Pastikan Nomor HP minimal 10 karakter',
            'no_hp.max' => 'Pastikan Nomor HP maksimal 13 karakter',
            'email.required' => 'Pastikan Email sudah terisi',
            'email.email' => 'Pastikan Email yang anda isi valid',
            'jenis_karyawan.required' => 'Pastikan jenis dosen sudah terpilih',
            'alamat.required' => 'Pastikan alamat sudah terisi',
            'unit_kerja.required' => 'Pastikan unit kerja sudah terpilih',
            'status_menikah.required' => 'Pastikan status pernikahan sudah terpilih',
            'file_kk.required' => 'Pastikan file KK sudah di upload',
            'file_kk.max' => 'Pastikan ukuran file KK tidak lebih dari 10Mb',
            'file_sk_golongan.mimes' => 'Pastikan file Golongan dalam format pdf',
            'file_sk_golongan.required' => 'Pastikan file Golongan sudah di upload',
            'file_sk_golongan.max' => 'Pastikan ukuran file Golongan tidak lebih dari 10Mb',
            'file_kk.mimes' => 'Pastikan file KK dalam format pdf',
            'pendidikan_terakhir.required' => 'Pastikan pendidikan terakhir sudah terisi',
            'ip_absensi.required' => 'Pastikan IP Absensi sudah terisi',
            'file_ijazah.required' => 'Pastikan file ijazah sudah terupload',
            'file_ijazah.max' => 'Pastikan ukuran file tidak lebih dari 10Mb',
            'file_ijazah.mimes' => 'Pastikan file Ijazah dalam format pdf',
            'tgl_aktif.required' => 'Pastikan tanggal aktif terisi',
            'tgl_aktif.date_format' => 'Pastikan format tanggal aktif benar',
        ]);

        $t = time();

        // Photo Profile
        $file_photo = $request->file('file_photo_profile');
        $file_name_photo = 'photo_profile_' . date("Y_m_d_h_m_s", $t) . '.' . $file_photo->getClientOriginalExtension();

        // KK
        $file_kk = $request->file('file_kk');
        $file_name_kk = 'file_kk_' . date("Y_m_d_h_m_s", $t) . '.' . $file_kk->getClientOriginalExtension();

        // Ijazah
        $file_ijazah = $request->file('file_ijazah');
        $file_name_ijazah = 'file_ijazah_' . date("Y_m_d_h_m_s", $t) . '.' . $file_ijazah->getClientOriginalExtension();

        // SK Golongan
        $file_golongan = $request->file('file_sk_golongan');
        $file_name_golongan = null;
        if (!is_null($file_golongan))
            $file_name_golongan = 'file_sk_golongan_' . date("Y_m_d_h_m_s", $t) . '.' . $file_golongan->getClientOriginalExtension();

        // SK Struktural
        $file_struktural = $request->file('file_sk_jabatan_struktural');
        $file_name_struktural = null;
        if (!is_null($file_struktural))
            $file_name_struktural = 'file_sk_jabatan_struktural_' . date("Y_m_d_h_m_s", $t) . '.' . $file_struktural->getClientOriginalExtension();

        // SK Fungsional
        $file_fungsional = $request->file('file_sk_jabatan_fungsional');
        $file_name_fungsional = null;
        if (!is_null($file_fungsional))
            $file_name_fungsional = 'file_sk_jabatan_fungsional_' . date("Y_m_d_h_m_s", $t) . '.' . $file_fungsional->getClientOriginalExtension();

        // Ssertifikat Sertifikasi
        $file_sertifikat = $request->file('file_sertifikat');
        $file_name_sertifikat = null;
        if (!is_null($file_sertifikat))
            $file_name_sertifikat = 'file_sertifikat_' . date("Y_m_d_h_m_s", $t) . '.' . $file_sertifikat->getClientOriginalExtension();

        // Proses insert
        $karyawan = Karyawan::insert_data_pegawai($request->nama, $request->gelar_depan, $request->gelar_belakang, $request->nip, $request->nidn, $request->jenis_kelamin, $request->tempat_lahir, $request->tgl_lahir, $request->alamat, $request->no_hp, $request->email, $request->id_agama, $request->no_rekening, $request->tgl_aktif, $request->unit_kerja, $request->no_ktp, $request->nik, $file_name_photo, $request->jenis_karyawan, $request->status_menikah, $request->pendidikan_terakhir, $file_name_ijazah, $request->tgl_lulus, $request->golongan, $file_name_golongan, $request->tmt_golongan, $request->jabatan_struktural, $request->tmt_jabatan_struktural, $file_name_struktural, $request->jabatan_fungsional, $file_name_fungsional, $request->tmt_jabatan_fungsional, $file_name_kk, 'BNI', $request->status_sertifikasi == 0 ? null : implode(',', $request->status_sertifikasi), $request->id_sinta, $file_name_sertifikat, $request->home_base, $request->jenis_bank, $request->ip_absensi);

        if ($karyawan->status == 1) {
            // Jika sukses maka upload file
            // Photo Profile
            $destinationPath = 'files/profil_karyawan/' . $karyawan->id_personal;
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
            $file_photo->move($destinationPath, $file_name_photo);

            // File KK
            $destinationPath = 'files/berkas_kepegawaian/' . $karyawan->id_personal;
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
            $file_kk->move($destinationPath, $file_name_kk);

            // File Ijazah
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
            $file_ijazah->move($destinationPath, $file_name_ijazah);

            // File Golongan
            if (!is_null($file_golongan)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_golongan->move($destinationPath, $file_name_golongan);
            }

            // File Struktural
            if (!is_null($file_struktural)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_struktural->move($destinationPath, $file_name_struktural);
            }

            // File Fungsional
            if (!is_null($file_fungsional)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_fungsional->move($destinationPath, $file_name_fungsional);
            }

            // File Sertifikasi
            if (!is_null($file_sertifikat)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_sertifikat->move($destinationPath, $file_name_sertifikat);
            }

            $message = "";
            try {
                Mail::to($request->email)->send(new AccountSender($karyawan));
                $message = $karyawan->keterangan;
            } catch (Exception $exception) {
                $message = "Data berhasil disimpan, namun akun gagal dikirim ke email pengguna";
            }
            if ($request->status_menikah != 1) {
                Session::flash('success_message_on_data_anak', $message);
                return redirect(route('hrd.data_kepegawaian.data_anak_karyawan.index', ['id' => $karyawan->id_personal, 'is_insert' => true]));
            } else {
                Session::flash('success_message', $message);
                return redirect(route('hrd.data_kepegawaian.list_data_pegawai.index'));
            }
        } else {
            Session::flash('failed_message', $karyawan->keterangan);
            return redirect()->back()->withInput();
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ], [
            'id.required' => 'ID Tidak Di Isi'
        ]);
        $karyawan = Karyawan::delete_pegawai($request->id);
        $status_code = 500;
        if ($karyawan->status == 1)
            $status_code = 200;
        return response()->json($karyawan, $status_code);
    }

    public function detail($id)
    {
        $menu = "Detail Data Pegawai - HRD";
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($id);
        return view('hrd_page.data_kepegawaian.detail_data_pegawai', compact('menu', 'karyawan'));
    }

    public function edit($id)
    {
        $menu = "Edit Data Pegawai - HRD";
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($id);
        $unit_kerja = UnitKerja::get_daftar_unit_kerja();
        $pendidikan = Pendidikan::list_pendidikan_terakhir();
        $golongan = Golongan::get_daftar_golongan();
        $prodi = ProgramStudi::get_program_studi();
        $jabatan_struktural = JabatanStruktural::get_daftar_jabatan_struktural();
        $jabatan_fungsional = JabatanFungsional::get_daftar_jabatan_fungsional();
        $agama = Agama::list_agama();
        return view('hrd_page.data_kepegawaian.edit_data_pegawai', compact('menu', 'karyawan', 'unit_kerja', 'pendidikan', 'golongan', 'jabatan_struktural', 'jabatan_fungsional', 'agama', 'prodi'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'no_ktp' => 'required|max:16|min:16',
            'jenis_kelamin' => 'required',
            'jenis_bank' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'no_hp' => 'required|min:10|max:13',
            'email' => 'required|email:rfc,dns',
            'jenis_karyawan' => 'required',
            'id_agama' => 'required',
            'alamat' => 'required',
            'unit_kerja' => 'required',
            'status_menikah' => 'required',
            'ip_absensi' => 'required',
            'tgl_aktif' => 'required|date_format:Y-m-d',
        ], [
            'id.required' => 'Pastikan ID Terisi',
            'no_ktp.required' => 'Pastikan anda sudah mengisi Nomor KTP',
            'no_ktp.max' => 'Pastikan Nomor KTP tidak lebih dari 16 karakter',
            'no_ktp.min' => 'Pastikan Nomor KTP tidak kurang dari 16 karakter',
            'jenis_kelamin.required' => 'Pastikan anda sudah memilih jenis kelamin',
            'jenis_bank.required' => 'Pastikan anda sudah memilih jenis bank',
            'nama.required' => 'Pastikan nama anda sudah terisi',
            'tempat_lahir.required' => 'Pastikan tempat lahir sudah terisi',
            'tgl_lahir.required' => 'Pastikan tanggal lahir sudah terisi',
            'tgl_lahir.date_format' => 'Pastikan format tanggal lahir benar',
            'no_hp.required' => 'Pastikan nomor HP terisi',
            'no_hp.min' => 'Pastikan Nomor HP minimal 10 karakter',
            'no_hp.max' => 'Pastikan Nomor HP maksimal 13 karakter',
            'email.required' => 'Pastikan Email sudah terisi',
            'email.email' => 'Pastikan Email yang anda isi valid',
            'jenis_karyawan.required' => 'Pastikan jenis dosen sudah terpilih',
            'alamat.required' => 'Pastikan alamat sudah terisi',
            'unit_kerja.required' => 'Pastikan unit kerja sudah terpilih',
            'status_menikah.required' => 'Pastikan status pernikahan sudah terpilih',
            'ip_absensi.required' => 'Pastikan IP Absensi Sudah Terisi',
            'tgl_aktif.required' => 'Pastikan tanggal aktif terisi',
            'tgl_aktif.date_format' => 'Pastikan format tanggal aktif benar',
        ]);

        // ambil data lama
        $karyawan_old = Karyawan::get_detail_karyawan_by_id_personal($request->id);

        // KK
        if ($request->status_menikah != $karyawan_old->id_status_pernikahan)
            $request->validate([
                'file_kk' => 'required|max:10000|mimes:pdf'
            ], [
                'file_kk.required' => 'Pastikan file KK sudah di upload',
                'file_kk.max' => 'Pastikan ukuran file KK tidak lebih dari 10Mb',
                'file_kk.mimes' => 'Pastikan file KK dalam format pdf',
            ]);
        $file_name_kk = null;
        if (!is_null($request->file_kk)) {
            $t = time();
            // KK
            $file_kk = $request->file('file_kk');
            $file_name_kk = 'file_kk_' . date("Y_m_d_h_m_s", $t) . '.' . $file_kk->getClientOriginalExtension();
        }

        // Golongan
        if ($request->golongan != $karyawan_old->id_golongan)
            $request->validate([
                'file_sk_golongan' => 'required|max:10000|mimes:pdf'
            ], [
                'file_sk_golongan.required' => 'Pastikan file SK Golongan sudah di upload',
                'file_sk_golongan.max' => 'Pastikan ukuran file SK Golongan tidak lebih dari 10Mb',
                'file_sk_golongan.mimes' => 'Pastikan file SK Golongan dalam format pdf',
            ]);
        $file_name_golongan = null;
        if (!is_null($request->file_sk_golongan)) {
            $t = time();
            // Jafung
            $file_golongan = $request->file('file_sk_golongan');
            $file_name_golongan = 'file_sk_golongan_' . date("Y_m_d_h_m_s", $t) . '.' . $file_golongan->getClientOriginalExtension();
        }

        // Jafung
        if ($request->jafung != $karyawan_old->id_jabatan_fungsional)
            $request->validate([
                'file_sk_jafung' => 'required|max:10000|mimes:pdf'
            ], [
                'file_sk_jafung.required' => 'Pastikan file SK Jabatan Fungsional sudah di upload',
                'file_sk_jafung.max' => 'Pastikan ukuran file SK Jabatan Fungsional tidak lebih dari 10Mb',
                'file_sk_jafung.mimes' => 'Pastikan file SK Jabatan Fungsional dalam format pdf',
            ]);
        $file_name_jafung = null;
        if (!is_null($request->file_sk_jafung)) {
            $t = time();
            // Jafung
            $file_jafung = $request->file('file_sk_jafung');
            $file_name_jafung = 'file_sk_jabatan_fungsional_' . date("Y_m_d_h_m_s", $t) . '.' . $file_jafung->getClientOriginalExtension();
        }

        // Jastruk
        $file_name_jastruk = null;
        if (!is_null($request->file_sk_jastruk)) {
            $t = time();
            // Jastruk
            $file_jastruk = $request->file('file_sk_jastruk');
            $file_name_jastruk = 'file_sk_jabatan_struktural_' . date("Y_m_d_h_m_s", $t) . '.' . $file_jastruk->getClientOriginalExtension();
        }

        // Ijazah
        if ($request->pendidikan != $karyawan_old->kd_pendidikan)
            $request->validate([
                'file_ijazah' => 'required|max:10000|mimes:pdf'
            ], [
                'file_ijazah.required' => 'Pastikan file Ijazah sudah di upload',
                'file_ijazah.max' => 'Pastikan ukuran file Ijazah tidak lebih dari 10Mb',
                'file_ijazah.mimes' => 'Pastikan file Ijazah dalam format pdf',
            ]);
        $file_name_ijazah = null;
        if (!is_null($request->file_ijazah)) {
            $t = time();
            // Ijazah
            $file_ijazah = $request->file('file_ijazah');
            $file_name_ijazah = 'file_ijazah_' . date("Y_m_d_h_m_s", $t) . '.' . $file_ijazah->getClientOriginalExtension();
        }

        $file_name_sertifikat = null;
        if (!is_null($request->file_sertifikat)) {
            $t = time();
            // Sertifikat Sertifikasi
            $file_sertifikat = $request->file('file_sertifikat');
            $file_name_sertifikat = 'file_sertifikat_' . date("Y_m_d_h_m_s", $t) . '.' . $file_sertifikat->getClientOriginalExtension();
        }

        $karyawan = Karyawan::update_data_pegawai($request->id, $request->nama, $request->gelar_depan, $request->gelar_belakang, $request->nip, $request->nidn, $request->jenis_kelamin, $request->tempat_lahir, $request->tgl_lahir, $request->alamat, $request->no_hp, $request->email, $request->id_agama, $request->no_rekening, $request->tgl_aktif, $request->unit_kerja, $request->no_ktp, $request->nik, $request->jenis_karyawan, $request->status_menikah, $file_name_kk, $request->golongan, $file_name_golongan, $request->tmt_golongan, $request->jafung, $file_name_jafung, $request->tmt_jafung, $request->jastruk, $file_name_jastruk, $request->tmt_jastruk, $request->pendidikan, $file_name_ijazah, $request->tgl_lulus, 'BNI', $request->status_sertifikasi == 0 ? null : implode(',', $request->status_sertifikasi), $request->id_sinta, $file_name_sertifikat, $request->home_base, $request->jenis_bank, $request->ip_absensi);
        if ($karyawan->status == 1) {
            if (!is_null($request->file_kk)) {
                // File KK
                $destinationPath = 'files/berkas_kepegawaian/' . $request->id;
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_kk->move($destinationPath, $file_name_kk);

                if (File::exists(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_kartu_keluarga))) {
                    File::delete(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_kartu_keluarga));
                }
            }

            if (!is_null($request->file_sk_golongan)) {
                // File Golongan
                $destinationPath = 'files/berkas_kepegawaian/' . $request->id;
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_golongan->move($destinationPath, $file_name_golongan);

                if (File::exists(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_golongan))) {
                    File::delete(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_golongan));
                }
            }

            if (!is_null($request->file_sk_jafung)) {
                // File Jafung
                $destinationPath = 'files/berkas_kepegawaian/' . $request->id;
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_jafung->move($destinationPath, $file_name_jafung);

                if (File::exists(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_riwayat_jabatan_fungsional))) {
                    File::delete(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_riwayat_jabatan_fungsional));
                }
            }

            if (!is_null($request->file_sk_jastruk)) {
                // File Jastruk
                $destinationPath = 'files/berkas_kepegawaian/' . $request->id;
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_jastruk->move($destinationPath, $file_name_jastruk);

                if (File::exists(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_riwayat_jabatan_struktural))) {
                    File::delete(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_riwayat_jabatan_struktural));
                }
            }

            if (!is_null($request->file_ijazah)) {
                // File Ijazah
                $destinationPath = 'files/berkas_kepegawaian/' . $request->id;
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_ijazah->move($destinationPath, $file_name_ijazah);

                if (File::exists(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_pendidikan))) {
                    File::delete(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_pendidikan));
                }
            }

            if (!is_null($request->file_sertifikat)) {
                // File Sertifikat Sertifikasi
                $destinationPath = 'files/berkas_kepegawaian/' . $request->id;
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_sertifikat->move($destinationPath, $file_name_ijazah);

                if (File::exists(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_sertifikat))) {
                    File::delete(public_path('files/berkas_kepegawaian/' . $request->id . '/' . $karyawan_old->path_dokumen_pendukung_sertifikat));
                }
            }
            Session::flash('success_message', $karyawan->keterangan);
            return redirect()->back();
        } else {
            Session::flash('failed_message', $karyawan->keterangan);
            return redirect()->back()->withInput();
        }
    }

    public function update_path_photo(Request $request)
    {
        $request->validate([
            'file' => 'required|max:10000|mimes:jpg,jpeg,png',
            'id' => 'required',
        ], [
            'file.required' => 'Pastikan File Photo sudah terisi',
            'file.max' => 'Pastikan ukuran file tidak lebih dari 10MB',
            'file.mimes' => 'Pastikan ekstensi file JPG/JPEG/PNG',
            'id.required' => 'Pastikan ID sudah terisi',
        ]);
        $t = time();
        $file = $request->file('file');
        $file_name = 'photo_profile_' . date("Y_m_d_h_m_s", $t) . '.' . $file->getClientOriginalExtension();
        if (!empty($request->old_path)) {
            File::delete('files/profil_karyawan/' . $request->id . '/' . $request->old_path);
        }
        $photo_profile = Karyawan::update_path_photo($request->id, $file_name);
        if ($photo_profile->status == 1) {
            $karyawan = Karyawan::get_detail_karyawan_by_id_personal(Session::get('user')->id_personal);
            Session::forget('karyawan');
            Session::put('karyawan', $karyawan);
            // Proses Upload File
            $destinationPath = 'files/profil_karyawan/' . $request->id;
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
            $file->move($destinationPath, $file_name);
        }
        return response()->json($photo_profile, 200);
    }
}
