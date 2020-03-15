<template>
    <div id="service" class="service col-md-10 offset-md-1 col-lg-9 offset-lg-0">
        <!-- Recently Favorited -->
        <div class="widget dashboard-container my-adslist">
            <h3 class="widget-header">Mis Servicios</h3>

            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Servicio</th>
                    <th class="text-center">Solicitud</th>
                    <th class="text-center">Duración</th>
                    <th class="text-center">Sub Total<br>( S/ )</th>
                    <th class="text-center">IGV<br>( 18% )</th>
                    <th class="text-center">Total<br>( S/ )</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="( item, idx ) in services">
                    <td>{{ idx + 1 }}</td>
                    <td>
                        <span class="categories">{{ item.document }}</span>
                    </td>
                    <td>
                        <strong>{{ item.servicerequest.name }}</strong>
                        <br/>
                        <small>{{ item.servicerequest.document }}</small>
                        <br/>
                        <small>{{ item.servicerequest.send }}</small>
                    </td>
                    <td>
                        <strong v-text="item.referenceTerm.ejecution"></strong> <small>día(s) hábiles( Lunes a Sabado ).</small>
                    </td>
                    <td>{{ item.subTotal }}</td>
                    <td>{{ item.igv }}</td>
                    <td>{{ item.total }}</td>
                    <td>
                        <span v-if="item.status === 1" class="badge badge-info">Por Aprobar</span>
                        <span v-if="item.status === 3" class="badge badge-primary">Por Iniciar</span>
                        <span v-if="item.status === 4" class="badge badge-success">En Proceso</span>
                        <div class="content-traffic-light">
                            <div class="red active border-right"></div>
                            <div class="red active border-right"></div>
                            <div class="yellow active border-right"></div>
                            <div class="yellow active border-right"></div>
                            <div class="green active border-right"></div>
                            <div class="green"></div>
                        </div>
                    </td>
                    <td class="action" data-title="Action">
                        <ul class="list-inline justify-content-center">
                            <li class="list-inline-item" v-if="item.referenceTerm.approved === 0">
                                <a class="edit btn-custom" href="#" @click="approvedSO( item, 'aproval' )">
                                    <i class="fa fa-check"></i>&nbsp;Aprobar
                                </a>
                            </li>
                            <li class="list-inline-item" v-if="item.referenceTerm.approved === 0">
                                <a class="delete btn-custom" href="#" @click="approvedSO( item, 'cancel' )">
                                    <i class="fa fa-close"></i>&nbsp;Rechazar
                                </a>
                            </li>
                            <li class="list-inline-item" v-if="item.referenceTerm.approved === 1">
                                <a class="view btn-custom" href="#service" @click="viewService( item.id )">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </li>
                        </ul>
                        <br v-if="item.referenceTerm.approved === 0">
                        <span v-if="item.orderPay === 1" class="badge badge-warning">Pendiento de pago</span>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    import Swal from 'sweetalert2';
    import 'sweetalert2/src/sweetalert2.scss';
    import {mapMutations} from "vuex";
    export default {
        name: "service",
        created() {
            this.$store.dispatch( 'loadServices' );
        },
        computed: {
            services() {
                return this.$store.state.Service.services;
            }
        },
        methods: {
            ...mapMutations(['CHANGE_CURRENT']),
            approvedSO( data, action ) {
                let actionTitle = 'Aprobar Orden de Servicio';
                let text = '¿Estas seguro de <strong>APROBAR</strong> la orden de servicio <u>' + data.document + '</u>';
                let buttonConfirm = '<i class="fa fa-check"></i> Aprobar';
                let confirmBtn = '#59D659';

                if( action === 'cancel' ) {
                    actionTitle = 'Desaprobar Orden de servicio';
                    text = '¿Estas seguro de <strong>RECHAZAR</strong> la orden de servicio <u>' + data.document + '</u>';
                    buttonConfirm = '<i class="fa fa-close"></i> Rechazar';
                    confirmBtn = '#FF5252';
                }

                Swal.fire({
                    title: actionTitle,
                    type: 'question',
                    html: text,
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: buttonConfirm,
                    cancelButtonText: '<i class="fa fa-reply"></i> Cancelar',
                    confirmButtonColor: confirmBtn,
                    cancelButtonColor: '#929394',
                }).then( response => {
                    if( response.value ) {
                        this.$store.dispatch({
                            type: 'toActionSO',
                            data: {
                                id: data.id,
                                referenceterm: data.referenceTerm.id,
                                action: action
                            }
                        }).then( response => {
                            if( response.status ) {
                                Swal.fire(
                                    actionTitle + '!',
                                    'Se realizó correctamente la operación.',
                                    'success'
                                );
                                this.$store.dispatch( 'loadServices' );
                                this.$store.dispatch( 'loadSettings' );
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.msg,
                                    'error'
                                );
                            }
                        }).catch( errors => {
                            Swal.fire(
                                'Error!',
                                'Ocurrio un error al ejecutar esta acción.',
                                'error'
                            );
                            console.log( errors );
                        })
                    }
                })
            },
            viewService( id ) {
                this.CHANGE_CURRENT( 'service-detail' );
            }
        }
    }
</script>

<style scoped>

</style>
