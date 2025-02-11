<?php

namespace App\Http\Controllers\LPPM;

use App\Http\Controllers\Controller;
use App\Models\Akademik\ProgramStudi;
use App\Models\Sinta\Authors;
use App\Models\Sinta\Books;
use App\Models\Sinta\GarudaDocs;
use App\Models\Sinta\GoogleDocs;
use App\Models\Sinta\IPRs;
use App\Models\Sinta\Research;
use App\Models\Sinta\ScopusDocs;
use App\Models\Sinta\Service;
use App\Models\Sinta\WosDocs;
use App\Models\Ws_Sinta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SintaCont extends Controller
{
    public function list_authors()
    {
        $menu = "Melihat Data Sinta";
        $prodi = ProgramStudi::get_program_studi();
        return view('lppm.sinta.list_author', compact('menu', 'prodi'));
    }

    public function get_author_api(Request $request)
    {
        $data = Ws_Sinta::GetAuthors();
        return response()->json($data);
    }

    public function sync_authors(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
        ]);
        $result = Authors::sync_author($request->id_sinta, $request->sinta_score_v2_overall, $request->sinta_score_v2_3year, $request->sinta_score_v3_overall, $request->sinta_score_v3_3year, $request->affiliation_score_v3_overall, $request->affiliation_score_v3_3year, $request->total_document_scopus, $request->total_citation_scopus, $request->total_cited_doc_scopus, $request->h_index_scopus, $request->i10_index_scopus, $request->g_index_scopus, $request->g_index_3year_scopus, $request->total_document_wos, $request->total_citation_wos, $request->total_cited_doc_wos, $request->h_index_wos, $request->total_document_garuda, $request->total_citation_garuda, $request->total_cited_doc_garuda, $request->total_document_google, $request->total_citation_google, $request->total_cited_doc_google, $request->h_index_google, $request->i10_index_google, $request->g_index_google);
        return response()->json($result);
    }

    public function list_authors_json(Request $request)
    {
        $request->validate([
            'kd_prodi' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Authors::get_authors($request->kd_prodi, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function list_garuda_docs($id_sinta)
    {
        $menu = "Melihat Data Sinta";
        return view('lppm.sinta.list_garuda_docs', compact('menu', 'id_sinta'));
    }

    public function get_doc_garuda_api(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $data = Ws_Sinta::GetAuthorsDocGaruda($request->id_sinta);
        return response()->json($data);
    }

    public function sync_doc_garuda(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
            'doc_id_sinta' => 'required',
            'title' => 'required',
            'abstract' => 'required',
            'url' => 'required',
        ]);
        $result = GarudaDocs::syn_docs($request->id_sinta, $request->doc_id_sinta, $request->accreditation, $request->title, $request->abstract, $request->publisher_name, $request->publish_date, $request->publish_year, $request->doi, $request->citation, $request->source, $request->source_issue, $request->source_page, $request->url);
        return response()->json($result);
    }

    public function list_garuda_docs_json(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = GarudaDocs::get_docs($request->id_sinta, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function list_google_docs($id_sinta)
    {
        $menu = "Melihat Data Sinta";
        return view('lppm.sinta.list_google_docs', compact('menu', 'id_sinta'));
    }

    public function get_doc_google_api(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $data = Ws_Sinta::GetAuthorsDocGoogle($request->id_sinta);
        return response()->json($data);
    }

    public function sync_doc_google(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
            'doc_id_sinta' => 'required',
            'title' => 'required',
            'authors' => 'required',
            'url' => 'required',
        ]);
        $result = GoogleDocs::sync_docs($request->id_sinta, $request->doc_id_sinta, $request->title, $request->abstract, $request->authors, $request->journal_name, $request->publish_year, $request->citation, $request->url);
        return response()->json($result);
    }

    public function list_google_docs_json(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = GoogleDocs::get_docs($request->id_sinta, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function list_scopus_docs($id_sinta)
    {
        $menu = "Melihat Data Sinta";
        return view('lppm.sinta.list_scopus_docs', compact('menu', 'id_sinta'));
    }

    public function get_doc_scopus_api(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $data = Ws_Sinta::GetAuthorsDocScopus($request->id_sinta);
        return response()->json($data);
    }

    public function sync_doc_scopus(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
            'doc_id_sinta' => 'required',
            'title' => 'required',
            'url' => 'required',
        ]);
        $result = ScopusDocs::sync_docs($request->id_sinta, $request->doc_id_sinta, $request->quartile, $request->title, $request->publication_name, $request->creator, $request->page, $request->issn, $request->volume, $request->cover_date, $request->cover_display_date, $request->doi, $request->citedby_count, $request->agregation_type, $request->url);
        return response()->json($result);
    }

    public function list_scopus_docs_json(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = ScopusDocs::get_docs($request->id_sinta, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function list_wos_docs($id_sinta)
    {
        $menu = "Melihat Data Sinta";
        return view('lppm.sinta.list_wos_docs', compact('menu', 'id_sinta'));
    }

    public function get_doc_wos_api(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $data = Ws_Sinta::GetAuthorsDocWos($request->id_sinta);
        return response()->json($data);
    }

    public function sync_doc_wos(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
            'doc_id_sinta' => 'required',
            'title' => 'required',
            'url' => 'required',
        ]);
        $result = WosDocs::sync_docs($request->id_sinta, $request->doc_id_sinta, $request->doi, $request->title, $request->first_author, $request->last_author, $request->authors, $request->publish_date, $request->journal_name, $request->citation, $request->abstract, $request->publish_type, $request->publish_year, $request->page_begin, $request->page_end, $request->issn, $request->eissn, $request->url);
        return response()->json($result);
    }

    public function list_wos_docs_json(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = WosDocs::get_docs($request->id_sinta, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function list_book($id_sinta)
    {
        $menu = "Melihat Data Sinta";
        return view('lppm.sinta.list_books', compact('menu', 'id_sinta'));
    }

    public function get_book_api(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $data = Ws_Sinta::GetAuthorsBooks($request->id_sinta);
        return response()->json($data);
    }

    public function sync_book(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
            'doc_id_sinta' => 'required',
            'title' => 'required',
        ]);
        $result = Books::syn_docs($request->id_sinta, $request->doc_id_sinta, $request->category, $request->isbn, $request->title, $request->authors, $request->place, $request->publisher, $request->year);
        return response()->json($result);
    }

    public function list_book_json(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Books::get_docs($request->id_sinta, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function list_iprs($id_sinta)
    {
        $menu = "Melihat Data Sinta";
        return view('lppm.sinta.list_iprs', compact('menu', 'id_sinta'));
    }

    public function get_iprs_api(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $data = Ws_Sinta::GetAuthorsIprs($request->id_sinta);
        return response()->json($data);
    }

    public function sync_iprs(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
            'doc_id_sinta' => 'required',
            'title' => 'required',
        ]);
        $result = IPRs::syn_docs($request->id_sinta, $request->doc_id_sinta, $request->category, $request->request_year, $request->request_number, $request->title, $request->inventor, $request->patent_holder, $request->publication_date, $request->publication_number, $request->filing_date, $request->reception_date, $request->registration_date, $request->registration_number);
        return response()->json($result);
    }

    public function list_iprs_json(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = IPRs::get_docs($request->id_sinta, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function list_research($id_sinta)
    {
        $menu = "Melihat Data Sinta";
        return view('lppm.sinta.list_research', compact('menu', 'id_sinta'));
    }

    public function get_research_api(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $data = Ws_Sinta::GetAuthorsResearch($request->id_sinta);
        return response()->json($data);
    }

    public function sync_research(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
            'doc_id_sinta' => 'required',
            'title' => 'required',
        ]);
        $result = Research::syn_docs($request->doc_id_sinta, $request->id_sinta, $request->leader, $request->title, $request->first_proposed_year, $request->proposed_year, $request->implementation_year, $request->focus, $request->funds_approved, $request->scheme_name, $request->kategori_sumber_dana, $request->negara_sumber_dana, $request->sumber_dana, $request->sumber_data, $request->kd_program_hibah, $request->program_hibah, $request->member);
        return response()->json($result);
    }

    public function list_research_json(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Research::get_docs($request->id_sinta, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function list_service($id_sinta)
    {
        $menu = "Melihat Data Sinta";
        return view('lppm.sinta.list_service', compact('menu', 'id_sinta'));
    }

    public function get_service_api(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $data = Ws_Sinta::GetAuthorsService($request->id_sinta);
        return response()->json($data);
    }

    public function sync_service(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required',
            'doc_id_sinta' => 'required',
            'title' => 'required',
        ]);
        $result = Service::syn_docs($request->doc_id_sinta, $request->id_sinta, $request->leader, $request->title, $request->first_proposed_year, $request->proposed_year, $request->implementation_year, $request->focus, $request->funds_approved, $request->scheme_name, $request->kategori_sumber_dana, $request->negara_sumber_dana, $request->sumber_dana, $request->sumber_data, $request->kd_program_hibah, $request->program_hibah, $request->member);
        return response()->json($result);
    }

    public function list_service_json(Request $request)
    {
        $request->validate([
            'id_sinta' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Service::get_docs($request->id_sinta, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }
}
