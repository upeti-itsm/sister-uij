<?php

namespace App\Models\Sinta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IPRs extends Model
{
    use HasFactory;
    public static function get_docs($id_sinta = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sinta.get_ipr_doc_author(?,?,?,?)', [
            $id_sinta, $search, $offset, $limit
        ]);
    }

    public static function syn_docs($id_sinta, $doc_id_sinta, $category, $request_year, $request_number, $title, $inventor, $patent_holder, $publication_date, $publication_number, $filing_date, $reception_date, $registration_date,  $registration_number)
    {
        return DB::select('SELECT * FROM sinta.sync_ipr_doc_author(?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
            $id_sinta, $doc_id_sinta, $category, $request_year, $request_number, $title, $inventor, $patent_holder, $publication_date, $publication_number, $filing_date, $reception_date, $registration_date,  $registration_number
        ])[0];
    }
}
