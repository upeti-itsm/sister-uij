<?PHP

use Illuminate\Contracts\Session\Session;

function getUserIP()
{
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    \Illuminate\Support\Facades\Session::put('ip', $ip);
    return $ip;
}

$user_ip = getUserIP();
?>
    <!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Tracer Study; STIE-Mandala Jember">
    <meta name="author" content="Bdtask">
    <title>Tracer Study | Auth</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('image/logo-mandala.png')}}">
    <!--Global Styles(used by all pages)-->
    <link href="{{asset('adminpage/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/fontawesome/css/all.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/typicons/src/typicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/themify-icons/themify-icons.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css')}}" rel="stylesheet">
    <!--Third party Styles(used by this page)-->
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <!--Start Your Custom Style Now-->
    <link href="{{asset('adminpage/assets/dist/css/style.css')}}" rel="stylesheet">
</head>
<body class="bg-white" style="background-image: url('{{asset('/tracerstudy/bg_login.png')}}');background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    position: absolute;
    top:0px;
    left:0px;
    width: 100%;
    height: 100%;">
<div class="d-flex align-items-center justify-content-center text-center h-100vh">
    <div class="form-wrapper m-auto">
        <div class="form-container my-4">
            <div class="register-logo text-center mb-4">
                <img src="{{asset('image/logo-mandala.png')}}" class="img-fluid img-circle" style="max-width: 20%"
                     alt="">
            </div>
            <div class="panel">
                <div class="panel-header text-center mb-3">
                    <h3 class="fs-24">TRACER STUDY</h3>
                    <p class="text-muted text-center mb-0">Sekolah Tinggi Ilmu Ekonomi Mandala</p>
                </div>
                <form class="register-form">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Masukkan NIM" required>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-control select2" name="tanggal_lahir">
                                    <option value="">Tanggal Lahir</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-2">
                                <select name="bulan_lahir" class="form-control select2">
                                    <option value="">Bulan Lahir</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-2">
                                <select class="form-control select2" name="tahun_lahir">
                                    <option value="">Tahun Lahir</option>
                                    <option value="2001">2001</option>
                                    <option value="2000">2000</option>
                                    <option value="1999">1999</option>
                                    <option value="1998">1998</option>
                                    <option value="1997">1997</option>
                                    <option value="1996">1996</option>
                                    <option value="1995">1995</option>
                                    <option value="1994">1994</option>
                                    <option value="1993">1993</option>
                                    <option value="1992">1992</option>
                                    <option value="1991">1991</option>
                                    <option value="1990">1990</option>
                                    <option value="1989">1989</option>
                                    <option value="1988">1988</option>
                                    <option value="1987">1987</option>
                                    <option value="1986">1986</option>
                                    <option value="1985">1985</option>
                                    <option value="1984">1984</option>
                                    <option value="1983">1983</option>
                                    <option value="1982">1982</option>
                                    <option value="1981">1981</option>
                                    <option value="1980">1980</option>
                                    <option value="1979">1979</option>
                                    <option value="1978">1978</option>
                                    <option value="1977">1977</option>
                                    <option value="1976">1976</option>
                                    <option value="1975">1975</option>
                                    <option value="1974">1974</option>
                                    <option value="1973">1973</option>
                                    <option value="1972">1972</option>
                                    <option value="1971">1971</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <a href="{{route('tracer_study.login.login')}}" title="Refresh Captcha" class="btn btn-danger-soft btn-block form-control"><span>{{\Illuminate\Support\Facades\Session::get('first').' + '.\Illuminate\Support\Facades\Session::get('second').' x '.\Illuminate\Support\Facades\Session::get('third').' = '}}</a>
                            </div>
                            <div class="col-md-7">
                                <input type="number" class="form-control" name="result" placeholder="Masukkan Hasil" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-sign-in-alt mr-2"></i>Masuk</button>
                </form>
                <div class="panel-footer text-left mt-2">
                    Lupa NIM anda ? <a target="_blank" href="http://upeti.stie-mandala.ac.id/nim-checker" class="font-weight-500">Cek NIM disini</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.End of form wrapper -->
<!--Global script(used by all pages)-->
<script src="{{asset('adminpage/assets/plugins/jQuery/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $(".select2").select2();
        @if(\Illuminate\Support\Facades\Session::get('failed_message'))
        $.alert({
            title: 'Informasi',
            type: 'red',
            content: '{{\Illuminate\Support\Facades\Session::get('failed_message')}}',
            buttons: {
                OK: {
                    text: 'OK',
                    keys: ['enter']
                }
            }
        });
        @endif
    });
</script>
</body>
</html>
