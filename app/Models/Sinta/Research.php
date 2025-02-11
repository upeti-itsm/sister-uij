<?php

namespace App\Models\Sinta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Research extends Model
{
    use HasFactory;
    public static function get_docs($id_sinta = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sinta.get_research_doc_author(?,?,?,?)', [
            $id_sinta, $search, $offset, $limit
        ]);
    }

    public static function syn_docs($doc_id_sinta, $id_sinta, $leader, $title, $first_proposed_year, $proposed_year, $implementation_year, $focus, $funds_approved, $scheme, $kategori_sumber_dana, $negara_sumber_dana, $sumber_dana, $sumber_data,  $kd_program_hibah, $program_hibah, $member)
    {
        return DB::select('SELECT * FROM sinta.sync_research_doc_author(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
            $doc_id_sinta, $id_sinta, $leader, $title, $first_proposed_year, $proposed_year, $implementation_year, $focus, $funds_approved, $scheme, $kategori_sumber_dana, $negara_sumber_dana, $sumber_dana, $sumber_data,  $kd_program_hibah, $program_hibah, $member
        ])[0];
    }
}
