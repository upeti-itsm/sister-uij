<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Ws_Feeder extends Model
{
    use HasFactory;
    private static $url = '10.2.2.102:3003/ws/live2.php';
    public static function GetToken()
    {
//        $response = Http::post('10.2.2.102:3004/ws/live2.php', [
//            'act' => 'GetToken',
//            'username' => '073030',
//            'password' => 'm4nd4l4073030'
//        ]);
        $response = Http::post(self::$url, [
            'act' => 'GetToken',
            'username' => 'suwignyo@itsm.ac.id',
            'password' => 'M4nd4l4072042!'
        ]);
        Session::put('token', $response->json()["data"]["token"]);
    }

    public static function getData($method, $filter = "", $order = "", $limit = 1000, $offset = 0)
    {
        $response = Http::post(self::$url, [
            'act' => $method,
            'token' => Session::get("token"),
            'filter' => $filter,
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset
        ]);
        if ($response->json()["error_code"] == "100") {
            Ws_Feeder::GetToken();
            return Http::post(self::$url, [
                'act' => $method,
                'token' => Session::get("token"),
                'filter' => $filter,
                'order' => $order,
                'limit' => $limit,
                'offset' => $offset
            ])->json()["data"];
        } else
            return $response->json()["data"];
    }

    public static function deleteData($method, $key)
    {
        $response = Http::post(self::$url, [
            'act' => $method,
            'token' => Session::get("token"),
            'key' => json_decode($key)
        ]);
        if ($response->json()["error_code"] == "100") {
            Ws_Feeder::GetToken();
            return Http::post(self::$url, [
                'act' => $method,
                'token' => Session::get("token"),
                'key' => json_decode($key)
            ])->json();
        } else
            return $response->json();
    }

    public static function insertData($method, $key)
    {
        $response = Http::post(self::$url, [
            'act' => $method,
            'token' => Session::get("token"),
            'record' => json_decode($key)
        ]);
        if ($response->json()["error_code"] == "100") {
            Ws_Feeder::GetToken();
            return Http::post(self::$url, [
                'act' => $method,
                'token' => Session::get("token"),
                'record' => json_decode($key)
            ])->json();
        } else
            return $response->json();
    }

    public static function updateData($method, $key, $data)
    {
        $response = Http::post(self::$url, [
            'act' => $method,
            'token' => Session::get("token"),
            'key' => json_decode($key),
            'record' => json_decode($data)
        ]);
        if ($response->json()["error_code"] == "100") {
            Ws_Feeder::GetToken();
            return Http::post(self::$url, [
                'act' => $method,
                'token' => Session::get("token"),
                'key' => json_decode($key),
                'record' => json_decode($data)
            ])->json();
        } else
            return $response->json();
    }
}
