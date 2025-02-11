<?php

namespace App\Http\Controllers\TracerStudy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        try {
            Session::put('first', random_int(0, 10));
            Session::put('second', random_int(0, 10));
            Session::put('third', random_int(0, 10));
        } catch (\Exception $e) {
            return view('errors.500');
        }
        return view('tracer_study.login');
    }
}
