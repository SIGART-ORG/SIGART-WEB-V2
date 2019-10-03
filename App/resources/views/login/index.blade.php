<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login V13</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset( '/assets/login/images/icons/favicon.ico' ) }}"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/vendor/bootstrap/css/bootstrap.min.css' ) }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css' ) }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css' ) }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/fonts/iconic/css/material-design-iconic-font.min.css' ) }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/vendor/animate/animate.css' ) }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/vendor/css-hamburgers/hamburgers.min.css' ) }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/vendor/animsition/css/animsition.min.css' ) }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/vendor/select2/select2.min.css' ) }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/vendor/daterangepicker/daterangepicker.cs' ) }}s">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/css/util.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset( '/assets/login/css/main.css' ) }}">
    <!--===============================================================================================-->
</head>
<body style="background-color: #999999;">

<div class="limiter">
    <div class="container-login100">
        <div class="login100-more" style="background-image: url('{{ asset( '/assets/login/images/bg-01.jpg' ) }}');"></div>

        <div class="wrap-login100 p-l-50 p-r-50 p-t-72 p-b-50">
            <form class="login100-form validate-form" action="{{ route( 'login.request' ) }}" method="post">
                {{ csrf_field() }}
                <span class="login100-form-title p-b-59">
                    Inicia Sesión
                </span>
                <div class="wrap-input100 validate-input" data-validate = "Se requiere email válido. Ej: ex@abc.xyz">
                    <span class="label-input100">Email</span>
                    <input class="input100" type="text" name="email" placeholder="Email..." value="{{ old( 'email' ) }}">
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100 validate-input" data-validate = "Se requiere contraseña">
                    <span class="label-input100">Contraseña</span>
                    <input class="input100" type="password" name="password" placeholder="*************">
                    <span class="focus-input100"></span>
                </div>
                {{ $errors->first( 'email', '<span class="help-block">:message</span>' ) }}
                <div class="flex-m w-full p-b-33">
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                        <label class="label-checkbox100" for="ckb1">
								<span class="txt1">
									Acepto los
									<a href="{{ route('tyc') }}" target="_blank" class="txt2 hov1">
										términos y condiciones
									</a>
								</span>
                        </label>
                    </div>
                </div>
                {{ $errors->first( 'password', '<span class="help-block">:message</span>' ) }}
                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button type="submit" class="login100-form-btn">
                            Iniciar Sesión
                        </button>
                    </div>
                    <a href="{{ route('login.register') }}" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30">
                        Regístrate
                        <i class="fa fa-long-arrow-right m-l-5"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!--===============================================================================================-->
<script src="{{ asset( '/assets/login/vendor/jquery/jquery-3.2.1.min.js' ) }}"></script>
<!--===============================================================================================-->
<script src="{{ asset( '/assets/login/vendor/animsition/js/animsition.min.js' ) }}"></script>
<!--===============================================================================================-->
<script src="{{ asset( '/assets/login/vendor/bootstrap/js/popper.js' ) }}"></script>
<script src="{{ asset( '/assets/login/vendor/bootstrap/js/bootstrap.min.js' ) }}"></script>
<!--===============================================================================================-->
<script src="{{ asset( '/assets/login/vendor/select2/select2.min.js' ) }}"></script>
<!--===============================================================================================-->
<script src="{{ asset( '/assets/login/vendor/daterangepicker/moment.min.js' ) }}"></script>
<script src="{{ asset( '/assets/login/vendor/daterangepicker/daterangepicker.js' ) }}"></script>
<!--===============================================================================================-->
<script src="{{ asset( '/assets/login/vendor/countdowntime/countdowntime.js' ) }}"></script>
<!--===============================================================================================-->
<script src="{{ asset( '/assets/login/js/main.js' ) }}"></script>

</body>
</html>
