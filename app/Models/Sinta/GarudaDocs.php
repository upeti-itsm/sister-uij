<?php

namespace App\Models\Sinta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GarudaDocs extends Model
{
    use HasFactory;

    public static function get_docs($id_sinta = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sinta.get_garuda_doc_author(?,?,?,?)', [
            $id_sinta, $search, $offset, $limit
        ]);
    }

    public static function syn_docs($id_sinta, $doc_id_sinta, $accreditation, $title, $abstract, $publisher_name, $publish_date, $publish_year, $doi, $citation, $source, $source_issue, $source_page, $url)
    {
        return DB::select('SELECT * FROM sinta.sync_garuda_doc_author(?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
            $id_sinta, $doc_id_sinta, $accreditation, $title, $abstract, $publisher_name, $publish_date, $publish_year, $doi, $citation, $source, $source_issue, $source_page, $url
        ])[0];
    }
}
