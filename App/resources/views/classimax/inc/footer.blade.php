<footer class="footer section section-sm">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-7 offset-md-1 offset-lg-0">
                <!-- About -->
                <div class="block about">
                    <!-- footer logo -->
                    <img src="{{ asset( '/images/theme/logo.svg' ) }}" width="193" height="38" alt="{{ env( 'PROJECT_NAME' ) }}">
                    <!-- description -->
                    <p class="alt-color">
                        Ser una empresa altamente competitiva en cuanto a diseño, fabricación y servicios de carpintería y ebanistería. Ofrecer excelente calidad en productos de línea clásica, innovadora y de arte sacro a nivel comercial, residencial e institucional.
                    </p>
                </div>
            </div>
            <!-- Link list -->
            <div class="col-lg-2 offset-lg-1 col-md-3">
                <div class="block">
                    <h4>Páginas del sitio</h4>
                    <ul>
                        <li>
                            <a class="nav-link" href="{{ route('home.index') }}">Inicio</a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('about-us') }}">¿Quiénes somos?</a>
                        </li>
                        <li>
                            <a class="nav-link" href="#">Servicios</a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route( 'contact-us' ) }}">Contáctanos</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Link list -->
            <div class="col-lg-2 col-md-3 offset-md-1 offset-lg-0">
                <div class="block">
                    <h4>Cliente</h4>
                    <ul>
                        @if (! Auth::guest() )
                            <li>
                                <a class="nav-link" href="{{ route( 'dashboard' ) }}">Dashboard</a>
                            </li>
                        @else
                            <li><a href="{{ route('login.form') }}">Ingrese a su cuenta</a></li>
                        @endif
                        <li><a href="{{ route('login.register') }}">Regístrate</a></li>
                    </ul>
                </div>
            </div>
            <!-- Promotion -->
            <div class="col-lg-4 col-md-7">
                <!-- App promotion -->
                <div class="block-2 app-promotion">
                    <a href="">
                        <!-- Icon -->
                        <img src="{{ asset( '/assets/classimax/images/footer/phone-icon.png' ) }}" alt="mobile-icon">
                    </a>
                    <p>Muy pronto podrá descargar la App {{ env( 'PROJECT_NAME' ) }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Container End -->
</footer>
<footer class="footer-bottom">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-12">
                <!-- Copyright -->
                <div class="copyright">
                    <p>Copyright © {{ date( 'Y' ) }}. Todos los derechos reservados</p>
                </div>
            </div>
            <div class="col-sm-6 col-12">
                <!-- Social Icons -->
                <ul class="social-media-icons text-right">
                    <li><a class="fa fa-facebook" href=""></a></li>
                    <li><a class="fa fa-twitter" href=""></a></li>
                    <li><a class="fa fa-pinterest-p" href=""></a></li>
                    <li><a class="fa fa-vimeo" href=""></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Container End -->
    <!-- To Top -->
    <div class="top-to">
        <a id="top" class="" href=""><i class="fa fa-angle-up"></i></a>
    </div>
</footer>
