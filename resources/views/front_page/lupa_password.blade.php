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
    <meta name="description" content="Sistem informasi terpadu; Siakad Mandala">
    <meta name="author" content="Bdtask">
    <title>SIPADU | LUPA PASSWORD</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('image/logo-uij.png')}}">
    <!--Global Styles(used by all pages)-->
    <link href="{{asset('adminpage/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/fontawesome/css/all.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/typicons/src/typicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/themify-icons/themify-icons.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css')}}" rel="stylesheet">
    <!--Third party Styles(used by this page)-->
    <!--Start Your Custom Style Now-->
    <link href="{{asset('adminpage/assets/dist/css/style.css')}}" rel="stylesheet">
</head>
<body class="bg-white"
      style="background-image: url('{{asset('image/bg-02.jpg')}}'); background-repeat: no-repeat; background-size: cover">
<div class="d-flex align-items-center justify-content-center text-center h-100vh">
    <div class="form-wrapper m-auto">
        <div class="form-container my-4">
            <div class="register-logo text-center mb-4">
                <img src="{{asset('image/Logos.png')}}" class="img-fluid" style="max-width: 100%"
                     alt="">
            </div>
            <div class="panel">
                <div class="panel-header text-center mb-3">
                    <h3 class="fs-24">Password Resend</h3>
                    <p class="text-muted text-center mb-0">Isi dengan alamat email terdaftar di sister.uij.ac.id</p>
                </div>
                <form class="register-form" action="{{route('login.forget_password')}}" method="post">
                    @csrf
                    @captcha
                    <div class="form-group text-left">
                        <label>Email Terdaftar</label>
                        <input type="text" class="form-control"
                               name="email"
                               placeholder="Masukkan Email Terdaftar"
                               required id="email">
                    </div>
                    <button type="submit" class="btn btn-block btn-success btn-block">Send Account Information</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.End of form wrapper -->
<!--Global script(used by all pages)-->
<script src="{{asset('adminpage/assets/plugins/jQuery/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.js')}}"></script>
<script>
    $(document).ready(function () {
        @if($errors->has('g-recaptcha-response'))
        $.alert({
            title: 'Informasi',
            type: 'red',
            content: '{{$errors->first('g-recaptcha-response')}}',
            buttons: {
                OK: {
                    text: 'OK',
                    keys: ['enter']
                }
            }
        });
        @endif
        @if(\Illuminate\Support\Facades\Session::get('failed_message'))
        $.alert({
            title: 'Informasi',
            type: 'red',
            content: '{{\Illuminate\Support\Facades\Session::get('failed_message')}}'
        });
        @endif
        @if(\Illuminate\Support\Facades\Session::get('success_message'))
        $.alert({
            title: 'Informasi',
            type: 'green',
            content: '{{\Illuminate\Support\Facades\Session::get('success_message')}}'
        });
        @endif
    });
</script>
</body>
</html>
