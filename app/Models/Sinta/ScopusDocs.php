<?php

namespace App\Models\Sinta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ScopusDocs extends Model
{
    use HasFactory;
    public static function get_docs($id_sinta = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sinta.get_scopus_doc_author(?,?,?,?)', [
            $id_sinta, $search, $offset, $limit
        ]);
    }

    public static function sync_docs($id_sinta, $doc_id_sinta, $quartile, $title, $publication_name, $creator, $page, $issn, $volume, $cover_date, $cover_display_date, $doi, $citedby_count, $agregation_type, $url)
    {
        return DB::select('SELECT * FROM sinta.sync_scopus_doc_author(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
            $id_sinta, $doc_id_sinta, $quartile, $title, $publication_name, $creator, $page, $issn, $volume, $cover_date, $cover_display_date, $doi, $citedby_count, $agregation_type, $url
        ])[0];
    }
}
