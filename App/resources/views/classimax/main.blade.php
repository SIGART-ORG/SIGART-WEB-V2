<!DOCTYPE html>
<html lang="en">
    @include( 'classimax.inc.head' )

    <body class="body-wrapper">
        @include( 'classimax.inc.sidebar' )

        @yield( 'content' )

        @include( 'classimax.inc.footer' )
        @include( 'classimax.inc.plugins' )
    </body>

</html>


