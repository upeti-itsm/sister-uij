<?php

namespace App\Models\Sinta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Authors extends Model
{
    use HasFactory;

    public static function get_authors($kd_prodi = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sinta.get_daftar_author(?,?,?,?)', [
            $kd_prodi, $search, $offset, $limit
        ]);
    }

    public static function sync_author($id_sinta, $sinta_score_v2_overall, $sinta_score_v2_3year, $sinta_score_v3_overall, $sinta_score_v3_3year, $affiliation_score_v3_overall, $affiliation_score_v3_3year, $total_document_scopus, $total_citation_scopus, $total_cited_doc_scopus, $h_index_scopus, $i10_index_scopus, $g_index_scopus, $g_index_3year_scopus, $total_document_wos, $total_citation_wos, $total_cited_doc_wos, $h_index_wos, $total_document_garuda, $total_citation_garuda, $total_cited_doc_garuda, $total_document_google, $total_citation_google, $total_cited_doc_google, $h_index_google, $i10_index_google, $g_index_google)
    {
        return DB::select('SELECT * FROM sinta.sync_author_sinta(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
            $id_sinta, $sinta_score_v2_overall, $sinta_score_v2_3year, $sinta_score_v3_overall, $sinta_score_v3_3year, $affiliation_score_v3_overall, $affiliation_score_v3_3year, $total_document_scopus, $total_citation_scopus, $total_cited_doc_scopus, $h_index_scopus, $i10_index_scopus, $g_index_scopus, $g_index_3year_scopus, $total_document_wos, $total_citation_wos, $total_cited_doc_wos, $h_index_wos, $total_document_garuda, $total_citation_garuda, $total_cited_doc_garuda, $total_document_google, $total_citation_google, $total_cited_doc_google, $h_index_google, $i10_index_google, $g_index_google
        ])[0];
    }
}
