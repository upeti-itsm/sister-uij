<?php

namespace App\Models\Sinta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Books extends Model
{
    use HasFactory;

    public static function get_docs($id_sinta = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sinta.get_book_doc_author(?,?,?,?)', [
            $id_sinta, $search, $offset, $limit
        ]);
    }

    public static function syn_docs($id_sinta, $doc_id_sinta, $category, $isbn, $title, $authors, $place, $publisher, $year)
    {
        return DB::select('SELECT * FROM sinta.sync_book_doc_author(?,?,?,?,?,?,?,?,?)', [
            $id_sinta, $doc_id_sinta, $category, $isbn, $title, $authors, $place, $publisher, $year
        ])[0];
    }
}
