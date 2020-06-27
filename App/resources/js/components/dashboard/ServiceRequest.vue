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
                    <td>
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
                                    <a data-toggle="tooltip" data-placement="top" title="Ver detalle" class="view" href="javascript:void(0);" @click.prevent="openDetail( sr.id )">
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
        <b-modal ref="viewDetails" hide-footer :title="modalTitle" size="xl" @hide="closeModalObservation">
            <div class="row">
                <div class="col-md-12">
                    <h5>Datos Generales</h5>
                    <table class="table table-responsive product-dashboard-table service-detail__table">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Plazo de entrega</th>
                            <th>Documentos Adjuntos</th>
                            <th>Fecha de registro</th>
                            <th>Fecha de envio</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ serviceRequestView.name }}</td>
                            <td>{{ serviceRequestView.formDate }}</td>
                            <td>
                                <a v-if="serviceRequestView.attachment" class="btn btn-link" target="_blank" :href="serviceRequestView.attachment">
                                    <i class="fa fa-link"></i> Adjunto
                                </a>
                            </td>
                            <td>{{ serviceRequestView.dateRegFormat }}</td>
                            <td>{{ serviceRequestView.dateSendFormat }}</td>
                            <td>
                                <span v-if="serviceRequestView.isSend === 0" class="badge badge-secondary">No enviado</span>
                                <span v-if="serviceRequestView.isSend === 1 && serviceRequestView.status === 1" class="badge badge-info">Enviado</span>
                                <span v-if="serviceRequestView.isSend === 1 && serviceRequestView.status === 3" class="badge badge-warning">Cotizando</span>
                                <span v-if="serviceRequestView.isSend === 2" class="badge badge-success">Cotizado</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5>Ubicación</h5>
                    <div class="w-100 mw-100 pl-md-4 text-resalt">
                        {{ serviceRequestView.address }} - {{ serviceRequestView.ubigeo.district_name }} -
                        {{ serviceRequestView.ubigeo.province_name }} - {{ serviceRequestView.ubigeo.departament_name }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5>Detalle de solicitud</h5>
                    <table class="table table-responsive product-dashboard-table service-detail__table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Detalle</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="( viewDet, idx ) in serviceRequestView.details">
                            <td class="item">{{ idx + 1}}</td>
                            <td>{{ viewDet.item }}</td>
                            <td>{{ viewDet.count }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </b-modal>
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
        data() {
            return {
                modalTitle: ''
            }
        },
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
            },
            serviceRequestView: {
                get: function() {
                    let state = this.$store.state.Service;
                    return {
                        name: state.nameServiceRequest,
                        attachment: state.attachment,
                        formDate: state.formDate,
                        ubigeo: state.ubigeo,
                        address: state.address,
                        details: state.arrDetServiceRequest,
                        dateRegFormat: state.dateRegFormat,
                        dateSendFormat: state.dateSendFormat,
                        isSend: state.isSend,
                        status: state.status,
                    }
                }
            },
            idSR: {
                get: function() {
                    return this.$store.state.Service.idServiceRequest;
                },
                set: function( id ) {
                    this.$store.state.Service.idServiceRequest = id;
                }
            },
            force: {
                get: function() {
                    return this.$store.state.Service.force;
                },
                set: function( force ) {
                    this.$store.state.Service.force = force;
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
                                socket.emit(
                                    'create-notification-client',
                                    'sendServiceRequest',
                                    this.userData.id,
                                    'Nueva solicitud de cotización servicio enviada - ' + response.document );
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
            },
            openDetail( id ) {
                let me = this;
                this.idSR = id;
                this.force = true;
                this.$store.dispatch( 'getDetailServiceRequest' );
                setTimeout( function() {
                    me.modalTitle = me.serviceRequestView.name + ' - Detalle';
                }, 100 );
                this.$refs['viewDetails'].show();
            },
            closeModalObservation() {
                this.idSR = 0;
                this.modalTitle = '';
                this.$refs['viewDetails'].hide();
            }
        },
        mounted() {
            this.CLEAR_DETAILS_SR();
        }
    }
</script>

<style scoped>

</style>
