<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg  navigation">
                    <a class="navbar-brand" href="{{ route('home.index') }}">
                        <img src="{{ asset( '/assets/classimax/images/logo.png' ) }}" alt="">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
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
                        </ul>
                        <ul class="navbar-nav ml-auto mt-10">
                            <li class="nav-item">
                                <a class="nav-link login-button" href="{{ route('login.form') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link add-button" href="{{ route('login.register') }}">
                                    <i class="fa fa-plus-circle"></i> Regístrate
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>
