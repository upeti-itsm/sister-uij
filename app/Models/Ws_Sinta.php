<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Ws_Sinta extends Model
{
    use HasFactory;

    private static $url = 'https://apisinta.kemdikbud.go.id/';
    private static $username = 'ITSMANDALA';
    private static $password = '9012841840924197135019';
    private static $uniq_id = '496847296';
    private static $env = 'prod';

    public static function GetToken()
    {
        $response = Http::post(self::$url . 'consumer/login', [
            'username' => self::$username,
            'password' => self::$password
        ]);
        Session::put('sinta_token', $response->json()["token"]);
    }

    public static function GetAuthors($items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/authors?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/authors?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['authors'];
    }

    public static function GetAuthorsDocGaruda($sinta_id, $items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/garuda/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/garuda/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['documents'];
    }

    public static function GetAuthorsDocGoogle($sinta_id, $items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/google/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/google/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['documents'];
    }

    public static function GetAuthorsDocScopus($sinta_id, $items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/scopus/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/scopus/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['documents'];
    }

    public static function GetAuthorsDocWos($sinta_id, $items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/wos/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/wos/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['documents'];
    }

    public static function GetAuthorsBooks($sinta_id, $items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/book/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/book/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['documents'];
    }

    public static function GetAuthorsIprs($sinta_id, $items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/ipr/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/ipr/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['documents'];
    }

    public static function GetAuthorsResearch($sinta_id, $items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/research/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/research/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['documents'];
    }

    public static function GetAuthorsService($sinta_id, $items = 100, $page = 1)
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/service/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        if ($response['message'] == 'Signature verification failed' || $response['message'] == 'Missing authorization header') {
            self::GetToken();
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . Session::get('sinta_token')])->post(self::$url . 'v3/' . self::$env . '/' . self::$uniq_id . '/author/service/id/' . $sinta_id . '?items=' . $items . 'page=' . $page, []);
        }
        return $response->json()['results']['documents'];
    }
}
