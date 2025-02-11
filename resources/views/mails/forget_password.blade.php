<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="Lupa Password di sipadu.itsm.ac.id">
    <meta name="robots" content="noindex,nofollow">
    <title>Akun SIPADU STIE Mandala</title>
    <link rel="canonical" href="http://sipadu.itsm.ac.id/"/>
</head>

<body style="margin:0px; background: #f8f8f8; ">
<div width="100%"
     style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
    <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
            <tbody>
            <tr>
                <td style="vertical-align: top; padding-bottom:15px;" align="center"><a href="#" target="_blank"><img
                            src="http://sipadu.itsm.ac.id/image/logo-sipadu-uptti.png"
                            style="border:none; height: 100%; max-width: 50%"></a>
                </td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
            <tbody>
            <tr>
                <td style="background:#36bea6; padding:20px; color:#fff; text-align:center;">
                    AKUN SIPADU STIE MANDALA
                </td>
            </tr>
            </tbody>
        </table>
        <div style="padding: 40px; background: #fff;">
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                <tbody>
                <tr>
                    <td><b>Dear {{$data->nama}},</b>
                    </td>
                    <td align="right" width="200"> {{\Carbon\Carbon::now('Asia/Jakarta')->format('d F Y')}}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>SIPADU adalah sistem yang ditujukan kepada Civitas Akademik ITS Mandala untuk menunjang
                            kegiatan akademik/non akademik yang ada di lingkungan ITS Mandala. Berikut username dan
                            password anda untuk mengakses <a href="http://sipadu.itsm.ac.id/sign-in">sipadu.itsm.ac.id</a><br/>
                            <b>Username : </b>{{$data->username}}<br/>
                            <b>Password : </b>{{$data->password}}<br/>
                            <b style="color: red">Username dan Password ini digunakan selama anda menjadi civitas
                                akademik di ITS Mandala, Pastikan anda menyimpan dengan benar</b>
                        </p>
                        <center>
                            <a href="http://sipadu.itsm.ac.id/sign-in"
                               style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #2962FF; border-radius: 60px; text-decoration:none;">Login
                                SIPADU ITSM</a>
                        </center>
                        <b>Terimakasih <br/>(UPT-TI ITSM)</b></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
            <p> Powered by <a href="https://upeti.itsm.ac.id/" style="color: #b2b2b5" target="_blank">UPETI
                    ITS Mandala</a>
                <br>© 2021 <a href="https://itsm.ac.id/" style="color: #b2b2b5" target="_blank">ITS Mandala</a></p>
        </div>
    </div>
</div>
</body>
</html>
