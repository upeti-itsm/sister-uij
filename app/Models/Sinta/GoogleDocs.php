<?php

namespace App\Models\Sinta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoogleDocs extends Model
{
    use HasFactory;

    public static function get_docs($id_sinta = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sinta.get_google_doc_author(?,?,?,?)', [
            $id_sinta, $search, $offset, $limit
        ]);
    }

    public static function sync_docs($id_sinta, $doc_id_sinta, $title, $abstract, $authors, $journal_name, $publish_year, $citation, $url)
    {
        return DB::select('SELECT * FROM sinta.sync_google_doc_author(?,?,?,?,?,?,?,?,?)', [
            $id_sinta, $doc_id_sinta, $title, $abstract, $authors, $journal_name, $publish_year, $citation, $url
        ])[0];
    }
}
