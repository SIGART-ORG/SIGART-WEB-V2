<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg  navigation">
                    <a class="navbar-brand" href="{{ route('home.index') }}">
                        <img src="{{ asset( '/assets/classimax/images/logo.png' ) }}" alt="">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto main-nav ">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ route('home.index') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.html">¿Quiénes somos?</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.html">Servicios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.html">Blog</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.html">Contáctanos</a>
                            </li>
                            @if (! Auth::guest() )
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route( 'dashboard' ) }}">Dashboard</a>
                                </li>
                            @endif
                        </ul>
                        <ul class="navbar-nav ml-auto mt-10">
                            @if (Auth::guest())
                            <li class="nav-item">
                                <a class="nav-link login-button" href="{{ route('login.form') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link add-button" href="{{ route('login.register') }}">
                                    <i class="fa fa-plus-circle"></i> Regístrate
                                </a>
                            </li>
                            @else
                            <li class="nav-item dropdown dropdown-slide">
                                <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}</span>
                                </a>
                                <!-- Dropdown list -->
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route( 'profile' ) }}"><i class="fa fa-user-circle"></i>  Mi Perfil</a>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-in"></i>  Salir
                                    </a>
                                </div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            @endif
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>
