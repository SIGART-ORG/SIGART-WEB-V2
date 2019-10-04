@extends( 'classimax.main' )
@section( 'content' )
    <section class="dashboard section">
        <!-- Container Start -->
        <div class="container" id="app">
            <!-- Row Start -->
            <div class="row">
                <div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
                    <div class="sidebar">
                        <!-- User Widget -->
                        <div class="widget user-dashboard-profile">
                            <!-- User Image -->
                            <div class="profile-thumb">
                                <img src="https://ui-avatars.com/api/?name={{ Str::slug( Auth::user()->name, '+' ) }}&rounded=true&background=0D8ABC&color=fff&size=150" alt="" class="rounded-circle">
                            </div>
                            <!-- User Name -->
                            <h5 class="text-center">{{ Auth::user()->name }}</h5>
                            <p>Unido {{ $joined }}</p>
                            <a href="{{ route( 'profile' ) }}" class="btn btn-main-sm">Editar Perfil</a>
                        </div>
                        <!-- Dashboard Links -->
                        <div class="widget user-dashboard-menu">
                            <ul>
                                <li class="active" ><a href=""><i class="far fa-clipboard"></i> Mis Solicitudes <span>5</span></a></li>
                                <li><a href=""><i class="fa fa-bolt"></i> Cotizaciones por aprobar<span>23</span></a></li>
                                <li><a href=""><i class="fa fa-calculator"></i> Mis Cotizaciones <span>5</span></a></li>
                                <li><a href=""><i class="fa fa-file-archive-o"></i>Mis cotizaciones archivadas <span>12</span></a></li>
                                <li><a href=""><i class="fa fa-cog"></i> Cerrar Sesión</a></li>
                                <li><a href=""><i class="fa fa-power-off"></i>Eliminar cuenta</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
                    <!-- Recently Favorited -->
                    <div class="widget dashboard-container my-adslist">
                        <h3 class="widget-header">Mis solicitudes de servicios</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success pull-right btn btn-transparent">
                                    <i class="fa fa-plus-circle"></i> Solicitud
                                </button>
                            </div>
                        </div>
                        <table class="table table-responsive product-dashboard-table">
                            <thead>
                            <tr>
                                <th>Product Title</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="product-details">
                                    <h3 class="title">Macbook Pro 15inch</h3>
                                    <span class="add-id"><strong>Ad ID:</strong> ng3D5hAMHPajQrM</span>
                                    <span><strong>Posted on: </strong><time>Jun 27, 2017</time> </span>
                                    <span class="status active"><strong>Status</strong>Active</span>
                                    <span class="location"><strong>Location</strong>Dhaka,Bangladesh</span>
                                </td>
                                <td class="product-category"><span class="categories">Laptops</span></td>
                                <td class="action" data-title="Action">
                                    <div class="">
                                        <ul class="list-inline justify-content-center">
                                            <li class="list-inline-item">
                                                <a data-toggle="tooltip" data-placement="top" title="Tooltip on top" class="view" href="">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a class="edit" href="">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a class="delete" href="">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <!-- Row End -->
        </div>
        <!-- Container End -->
    </section>
@endsection
