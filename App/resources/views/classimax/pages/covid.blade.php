@extends( 'classimax.main' )
@section( 'content' )
<section class="message-container">
    <div class="content">
        <div class="article">
            <div class="breadcrumb">
                <div class="link__arrow">
                    <a href="{{ route( 'home.index' ) }}">Inicio</a>
                </div>
                <div class="link__arrow">Servicios en estado de emergencia</div>
            </div>
            <div class="paper paper__large">
                <div class="content content__narrow">
                    <div class="intercom-force-break">
                        <div class="article__meta" dir="ltr">
                            <h1 class="t__h1">Servicios en estado de emergencia</h1>
                            <div class="article__desc">

                            </div>
                            <div class="avatar">
                                <div class="avatar__photo o__ltr">
                                    <img src="{{ asset( '/images/theme/avatar.svg' ) }}" alt="Monsefú" class="avatar__image">

                                </div>
                                <div class="avatar__info">
                                    <div>
                                        Redactado por <span class="c__darker"> Monsefú</span>
                                        <br> Actualizado hace más de una semana
                                    </div>
                                </div>
                            </div>

                        </div>
                        <article dir="ltr"><p class="intercom-align-left">Para nuestra amig@s de D'Pintart,<br><br>Nuestra mayor prioridad es la salud y el bienestar de nuestros colaboradores y clientes. Con la creciente preocupación por la propagación del virus covid-19 y según las indicaciones del estado de emergencia en el país, a partir del lunes 16 de marzo, estamos
                                cerrando temporalmente nuestro centro de labores. Supervisaremos activamente la situación
                                en los próximos días, planeando reabrir nuestro centro de labores una vez terminado el
                                aislamiento social.<br><br>Queremos agradecer y reconocer a nuestros excepcionales colaboradores por su dedicación
                                durante estos tiempos inciertos. &nbsp;Continuaremos apoyando completamente a nuestros asociados,
                                incluso compensándolos por sus turnos programados durante este cierre.</p><p class="intercom-align-left">Mientras nuestra oficina esté cerrada, le invitamos a visitar nuestra web,
                                <a href="http://www.d-pintart.com" rel="nofollow noopener noreferrer" target="_blank">http://www.d-pintart.com</a>
                                y mantenerse conectado con nosotros a través de Facebook <a href="https://www.facebook.com/dpintart" rel="nofollow noopener noreferrer" target="_blank">https://www.facebook.com/dpintart</a><br><br>Los servicios que hayas realizado y los que desees realizar, serán reprogramadas para su
                                realización una vez terminado el aislamiento social, de acuerdo a las disposiciones del estado.</p><p class="intercom-align-left">Si tiene alguna pregunta o necesitas ayuda, nuestros equipos de servicio al cliente
                                estarán disponibles para ayudarlo dentro del chat en <a href="http://www.d-pintart.com" rel="nofollow noopener noreferrer" target="_blank">d-pintart.com.</a>
                                <br><br>En {{ env( 'PROJECT_NAME' ) }}, creemos que la familia, los amigos y la comunidad son lo
                                primero, y las formas en que nos cuidemos el uno al otro hoy, &nbsp;demostrarán nuestra
                                verdadera fortaleza y esencia. Esperamos reabrir nuestra oficina una vez terminado el
                                aislamiento social y le agradecemos su continuo entendimiento y apoyo. &nbsp;
                                Gracias por ser un fan de {{ env( 'PROJECT_NAME' ) }}.
                            </p><p class="intercom-align-left">Los queremos sanos a todos!<br><br>La familia {{ env( 'PROJECT_NAME' ) }}&nbsp;
                            </p></article>
                    </div>
                </div>
                <div class="intercom-reaction-picker" dir="ltr">
                    <div class="intercom-reaction-prompt">¿Encontró su respuesta?</div>

                    <button class="intercom-reaction" data-reaction-text="disappointed" tabindex="0" aria-label="Disappointed Reaction">
                        <span data-emoji="disappointed" title="Disappointed">😞</span>
                    </button>
                    <button class="intercom-reaction" data-reaction-text="neutral_face" tabindex="0" aria-label="Neutral face Reaction">
                        <span data-emoji="neutral_face" title="Neutral face">😐</span>
                    </button>
                    <button class="intercom-reaction" data-reaction-text="smiley" tabindex="0" aria-label="Smiley Reaction">
                        <span data-emoji="smiley" title="Smiley">😃</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
