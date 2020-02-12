<!DOCTYPE html>
<html lang="en">
@include( 'classimax.inc.head' )

<body class="body-wrapper">
@include( 'classimax.inc.sidebar' )
<style>
    .btn-xs {
        padding: 9px 30px !important;
    }
</style>
<section class="hero-area bg-1 text-center overly">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content-block">
                    <h1>Nuevo Usuario</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mt-30 mb-30">
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-12">
                <form  action="{{ route( 'login.register.post' ) }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">Nombre(s) y Apellidos</label>
                        <input id="name" type="text" name="name" class="form-control" placeholder="Nombre(s) y Apellidos" value="{{ old( 'name' ) }}">
                        @if( $errors->has('name') )
                            <span class="badge badge-danger">{{ $errors->first( 'name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" class="form-control" placeholder="Correo Electronico" value="{{ old( 'email' ) }}">
                        @if( $errors->has('email') )
                            <span class="badge badge-danger">{{ $errors->first( 'email') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email">Contraseña</label>
                        <input id="password" type="password" name="password" class="form-control" placeholder="Contraseña">
                        @if( $errors->has('password') )
                            <span class="badge badge-danger">{{ $errors->first( 'password') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email">Repetir contraseña</label>
                        <input id="passwordConfirmation" type="password" name="passwordConfirmation" class="form-control" placeholder="Contraseña">
                        @if( $errors->has('passwordConfirmation') )
                            <span class="badge badge-danger">{{ $errors->first( 'passwordConfirmation') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="remember-me" class="text-dark">
                            <span>
                                <input type="checkbox" id="ckb1" type="checkbox"  >
                            </span>
                            <span>Acepto los <a href="{{ route('tyc') }}" target="_blank" class="text-info">términos y condiciones</a>.</span>
                        </label>
                        <br>
                        <button type="submit" name="submit" class="btn btn-success btn-xs">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@include( 'classimax.inc.footer' )
@include( 'classimax.inc.plugins' )
</body>

</html>
