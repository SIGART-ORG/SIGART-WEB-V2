<!DOCTYPE html>
<html lang="en">
@include( 'classimax.inc.head' )

<body class="body-wrapper">
@include( 'classimax.inc.sidebar' )

<section class="hero-area bg-1 text-center overly">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content-block">
                    <h1>Bienvenido de nuevo</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mt-30 mb-30">
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-12">
                <form>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" class="form-control" placeholder="Correo Electronico">
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
