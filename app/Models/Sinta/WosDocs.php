<?php

namespace App\Models\Sinta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WosDocs extends Model
{
    use HasFactory;
    public static function get_docs($id_sinta = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sinta.get_wos_doc_author(?,?,?,?)', [
            $id_sinta, $search, $offset, $limit
        ]);
    }

    public static function sync_docs($id_sinta, $doc_id_sinta, $doi, $title, $first_author, $last_authors, $authors, $publish_date, $journal_name, $citation, $abstract, $publish_type, $publish_year, $page_begin, $page_end, $issn, $eissn, $url)
    {
        return DB::select('SELECT * FROM sinta.sync_wos_doc_author(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
            $id_sinta, $doc_id_sinta, $doi, $title, $first_author, $last_authors, $authors, $publish_date, $journal_name, $citation, $abstract, $publish_type, $publish_year, $page_begin, $page_end, $issn, $eissn, $url
        ])[0];
    }
}
