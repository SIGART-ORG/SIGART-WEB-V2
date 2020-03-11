@extends( 'classimax.main' )
@section( 'content' )
    <section class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <h3>Contáctanos</h3>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-us-content p-4">
                        <h5>{{ env( 'PROJECT_NAME' ) }}</h5>
                        <h1 class="pt-3">Contáctanos</h1>
                        <p class="pt-3 pb-5">
                            <strong class="text-uppercase">{{ env( 'PROJECT_NAME' ) }}</strong> está cerca de ti, en donde te encuentres y a la hora que lo necesites.
                            Con gusto nuestros colaboradores especializados atenderán tus dudas, recibirán tus comentarios y te brindarán el servicio que requieres. Ponemos a tu alcance el medio de comunicación más cómodo para ti.
                        </p>
                        <p>También ponemos a su disposición los siguientes canales de atención:</p>
                        <ul>
                            <li>
                                Llámanos: <strong>+51 992 164 579</strong>
                            </li>
                            <li>
                                Visitanos a nuestra oficina: <strong>Calle 58 Urb el Pinar. Comas - Lima - Lima</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <form>
                        <fieldset class="p-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 py-2">
                                        <input type="text" placeholder="Name *" class="form-control" required>
                                    </div>
                                    <div class="col-lg-6 pt-2">
                                        <input type="text" placeholder="Apellidos *" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 py-2">
                                        <input type="email" placeholder="Email *" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 py-2">
                                        <input type="text" placeholder="Telefono *" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 py-2">
                                        <input type="text" placeholder="Empresa" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <textarea name="message" id=""  placeholder="Mensaje *" class="border w-100 p-3 mt-3 mt-lg-4"></textarea>
                            <div class="btn-grounp">
                                <button type="submit" class="btn btn-primary mt-2 float-right">SUBMIT</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
