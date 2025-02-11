<?php

namespace App\Models\SIAKAD_MODEL\staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StafDosen extends Model
{
    use HasFactory;

    public static function staff_tfdaftardosen()
    {
        return DB::connection('siakad')->select("SELECT * FROM staf.tfdaftardosen()");
    }

    public static function akademik_tfdaftardosen($id_staff = '00000000-0000-0000-0000-000000000000')
    {
        if ($id_staff == '00000000-0000-0000-0000-000000000000')
            return DB::connection('siakad')->select("SELECT * FROM akademik.tfdaftardosen()");
        else
            return DB::connection('siakad')->selectOne("SELECT * FROM akademik.tfdaftardosen() where idstafdosen = ?", [
                $id_staff
            ]);
    }

    public static function daftar_dosen($search, $offset, $limit)
    {
        if ($limit == -1)
        return DB::connection('siakad')->select("WITH data_dosen AS (SELECT t1.* FROM akademik.tfdaftardosen() t1) SELECT t1.*, COUNT(*) OVER()::integer as jml_record FROM data_dosen t1 WHERE LOWER(t1.namanongelar) ~* LOWER(?)", [
            $search
        ]);
        else
        return DB::connection('siakad')->select("WITH data_dosen AS (SELECT t1.* FROM akademik.tfdaftardosen() t1) SELECT t1.*, COUNT(*) OVER()::integer as jml_record FROM data_dosen t1 WHERE LOWER(t1.namanongelar) ~* LOWER(?) LIMIT ? OFFSET ?", [
            $search, $limit, $offset
        ]);
    }
}
