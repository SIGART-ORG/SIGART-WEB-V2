<!DOCTYPE html>
<html lang="en">
@include( 'classimax.inc.head' )
<body class="body-wrapper">
@include( 'classimax.inc.sidebar' )

<section id="app" class="login-content">
    <register-second paramtoken="{{ $token }}"></register-second>
</section>

@include( 'classimax.inc.footer' )
@include( 'classimax.inc.plugins' )
</body>

</html>
