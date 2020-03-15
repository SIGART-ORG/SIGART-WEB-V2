<head>

    <!-- SITE TITTLE -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ?? '' }}{{ env( 'PROJECT_NAME' ) }}</title>
    <meta name="title" content="{{ $title ?? '' }}{{ env( 'PROJECT_NAME' ) }}">
    @if( $metaTags ?? '' )
        @foreach( $metaTags as $idx => $mt )
            <meta name="{{ $idx }}" content="{{ $mt }}">
        @endforeach
    @endif
    @if( $metaTagSocials ?? '' )
        @foreach( $metaTagSocials as $idx => $social )
            @if( $idx === 'facebook' ) <!-- Open Graph / Facebook --> @endif
            @if( $idx === 'twitter' ) <!-- Twitter --> @endif
            @foreach( $social as $idS => $mts )
                    <meta property="{{ $mts['property'] }}" content="{{ $mts['content'] }}">
            @endforeach
        @endforeach
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PLUGINS CSS STYLE -->
    <link href="{{ asset( '/assets/classimax/plugins/jquery-ui/jquery-ui.min.css' ) }}" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="{{ asset( '/assets/classimax/plugins/bootstrap/dist/css/bootstrap.min.css' ) }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset( '/assets/classimax/plugins/font-awesome/css/font-awesome.min.css' ) }}" rel="stylesheet">
    <!-- Owl Carousel -->
    <link href="{{ asset( '/assets/classimax/plugins/slick-carousel/slick/slick.css' ) }}" rel="stylesheet">
    <link href="{{ asset( '/assets/classimax/plugins/slick-carousel/slick/slick-theme.css' ) }}" rel="stylesheet">
    <!-- Fancy Box -->
    <link href="{{ asset( '/assets/classimax/plugins/fancybox/jquery.fancybox.pack.css' ) }}" rel="stylesheet">
    <link href="{{ asset( '/assets/classimax/plugins/jquery-nice-select/css/nice-select.css' ) }}" rel="stylesheet">
    <link href="{{ asset( '/assets/classimax/plugins/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css' ) }}" rel="stylesheet">
    <!-- CUSTOM CSS -->
    <link href="{{ asset( '/assets/classimax/css/style.css' ) }}" rel="stylesheet">
    <link href="{{ asset( '/css/app.css' ) }}" rel="stylesheet">

    <!-- FAVICON -->
    <link href="{{ asset( '/assets/classimax/img/favicon.png' ) }}" rel="shortcut icon">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
