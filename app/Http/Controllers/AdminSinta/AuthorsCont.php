<?php

namespace App\Http\Controllers\AdminSinta;

use App\Http\Controllers\Controller;
use App\Models\Ws_Sinta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthorsCont extends Controller
{
    public function index()
    {
        Ws_Sinta::GetToken();
        return Ws_Sinta::GetAuthors();
    }
}
