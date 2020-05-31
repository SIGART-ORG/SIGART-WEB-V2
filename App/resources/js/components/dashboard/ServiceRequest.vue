<template>
    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
        <!-- Recently Favorited -->
        <div class="widget dashboard-container my-adslist">
            <h2 class="sub-title widget-header">Mis solicitudes de servicios</h2>
            <div class="row">
                <div class="col-md-12 mb-10">
                    <button class="btn pull-right custom__btn" @click.prevent="CHANGE_CURRENT( 'new-service-request' )">
                        <i class="fa fa-plus-circle"></i> Solicitud
                    </button>
                </div>
            </div>

            <table class="table table-responsive product-dashboard-table tablle__custom">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Solicitud</th>
                    <th class="text-center">Fecha registro</th>
                    <th class="text-center">Fecha envio</th>
                    <th class="text-center">Adjunto</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="( sr, idx ) in serviceRequests">
                    <td>{{ idx + 1 }}</td>
                    <td class="product-details">
                        <div class="title">{{ sr.description }}</div>
                    </td>
                    <td class="product-category"><span class="categories">{{ sr.dateRegFormat }}</span></td>
                    <td class="product-category"><span class="categories">{{ sr.dateSendFormat }}</span></td>
                    <td class="text-center">
                        <a v-if="sr.attachment" class="edit" :href="sr.attachment" target="_blank">
                            <i class="fa fa-clipboard"></i>&nbsp;Adjunto
                        </a>
                        <span v-else class="badge badge-danger"><i class="fa fa-ban"></i>&nbsp;Sin Adjunto</span>
                    </td>
                    <td class="text-center">
                        <span v-if="sr.is_send === 0" class="badge badge-secondary">No enviado</span>
                        <span v-if="sr.is_send === 1 && sr.status === 1" class="badge badge-info">Enviado</span>
                        <span v-if="sr.is_send === 1 && sr.status === 3" class="badge badge-warning">Cotizando</span>
                        <span v-if="sr.is_send === 2" class="badge badge-success">Cotizado</span>
                    </td>
                    <td class="action" data-title="Action">
                        <div class="">
                            <ul class="list-inline justify-content-center">
                                <li v-if="sr.is_send !== 1" class="list-inline-item">
                                    <a data-toggle="tooltip" data-placement="top" title="Enviar Solicitud" class="edit" href="#" @click.prevent="sendServiceRequest( sr.id )">
                                        <i class="fa fa-send"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-toggle="tooltip" data-placement="top" title="Ver detalle" class="view" href="">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </li>
                                <li v-if="sr.is_send !== 1" class="list-inline-item">
                                    <a class="edit" href="#" @click.prevent="editServiceRequest( sr.id )">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </li>
                                <li v-if="sr.is_send !== 1" class="list-inline-item">
                                    <a class="delete" href="#" @click.prevent="deleteServiceRequest( sr.id )">
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
</template>

<script>
    import { mapMutations } from 'vuex';
    import Swal from 'sweetalert2';
    import 'sweetalert2/src/sweetalert2.scss';
    import io from 'socket.io-client';
    const socket = io( API_URL );
    export default {
        name: "servicerequest",
        created() {
            this.connectApi();
            this.$store.dispatch( 'loadServiceRequest' );
        },
        computed: {
            serviceRequests() {
                return this.$store.state.Service.serviceRequests;
            },
            userData: {
                get:function() {
                    return this.$store.state.Settings.userData;
                }
            }
        },
        methods: {
            ...mapMutations(['CHANGE_CURRENT', 'CHANGE_ID_SR', 'CLEAR_DETAILS_SR']),
            editServiceRequest( id ){
                this.CHANGE_ID_SR( id );
                if ( id > 0 ) {
                    this.CHANGE_CURRENT( 'new-service-request' );
                }
            },
            sendServiceRequest( id ) {
                Swal.fire({
                    title: '<strong>Enviar <u>Solicitud de Servicio</u></strong>',
                    type: 'question',
                    html: '¿Estas seguro de enviar esta solicitud de servicio?',
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-send"></i> Enviar',
                    cancelButtonText: '<i class="fa fa-close"></i> Cancelar',
                    confirmButtonColor: '#5672F9',
                    cancelButtonColor: '#FF5252',
                }).then( ( result ) => {
                    if ( result.value ) {
                        this.$store.dispatch({
                            type: 'sendSR',
                            data: {
                                id
                            }
                        }).then( response => {
                            if( response.status ) {
                                console.log( '2 entra api---------');
                                socket.emit(
                                    'create-notification-client',
                                    'sendServiceRequest',
                                    this.userData.id,
                                    'Nueva solicitud de cotización servicio enviada - ' + response.document );
                                console.log( '2 entra api---------');
                                this.$store.dispatch( 'loadServiceRequest' );
                                Swal.fire(
                                    'Enviado!',
                                    'Se envió correctamente su solicitud de servicio.',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Ocurrio un error al ejecutar esta acción.',
                                    'error'
                                )
                            }
                        }).catch( errors => {
                            Swal.fire(
                                'Error ',
                                'Ocurrio un error al ejecutar esta acción.',
                                'error'
                            );
                            console.log( errors );
                        });

                    }
                }).catch( ( errors ) => {
                    Swal.fire(
                        'Error ',
                        'Ocurrio un error al ejecutar esta acción.',
                        'error'
                    );
                    console.log( errors );
                });
            },
            deleteServiceRequest( id ) {
                Swal.fire({
                    title: '<strong>Eliminar <u>Solicitud de Servicio</u></strong>',
                    type: 'question',
                    html: '¿Estas seguro de eliminar esta solicitud de servicio?',
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-trash"></i> ELiminar',
                    cancelButtonText: '<i class="fa fa-close"></i> Cancelar',
                    confirmButtonColor: '#FF5252',
                    cancelButtonColor: '#929394',
                }).then( ( result ) => {
                    if ( result.value ) {
                        this.$store.dispatch({
                            type: 'deleteSR',
                            data: {
                                id
                            }
                        }).then( response => {
                            if( response.status ) {
                                this.$store.dispatch( 'loadServiceRequest' );
                                this.$store.dispatch( 'loadSettings' );
                                Swal.fire(
                                    'Eliminado!',
                                    'Se eliminó correctamente su solicitud de servicio.',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                )
                            }
                        }).catch( errors => {
                            Swal.fire(
                                'Error ',
                                'Ocurrio un error al ejecutar esta acción.',
                                'error'
                            );
                            console.log( errors );
                        });

                    }
                }).catch( ( errors ) => {
                    Swal.fire(
                        'Error ',
                        'Ocurrio un error al ejecutar esta acción.',
                        'error'
                    );
                    console.log( errors );
                });
            },
            connectApi() {
                socket.emit( 'load-user');
            }
        },
        mounted() {
            this.CLEAR_DETAILS_SR();
        }
    }
</script>

<style scoped>

</style>
