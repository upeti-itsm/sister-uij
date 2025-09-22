<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JenisTagihanController extends Controller
{
    /**
     * Display the main page for managing billing types.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $menu = 'Pengelolaan Jenis Tagihan';
        return view('admin_akademik_page.perkuliahan.jenis_tagihan', compact('menu'));
    }
}
