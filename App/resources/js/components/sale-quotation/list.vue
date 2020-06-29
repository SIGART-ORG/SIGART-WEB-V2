<template>
    <div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
        <!-- Recently Favorited -->
        <div class="widget dashboard-container my-adslist">
            <h3 class="widget-header">Cotizaciones por aprobar</h3>

            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Solicitud</th>
                    <th class="text-center">Cotización</th>
                    <th class="text-center">Ejecución</th>
                    <th class="text-center">Adjunto</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="( item, idx ) in salesQuotations">
                    <td>{{ idx + 1 }}</td>
                    <td>
                        <strong>{{ item.serviceRequest.title }}</strong>
                        <br/>
                        <small>{{ item.serviceRequest.document }}</small>
                        <br/>
                        <small>{{ item.serviceRequest.send }}</small>
                    </td>
                    <td class="product-category">
                        <span class="categories">{{ item.document }}</span>
                        <br/>
                        <small>{{ item.approved }}</small>
                        <br/>
                        <small v-if="item.showButton" class="badge badge-danger">Valido hasta: {{ item.expiration }}</small>
                        <small v-else class="badge badge-danger">Cotización expirada</small>
                    </td>
                    <td class="product-category">
                        <mark>{{ item.execution }} Día(s)</mark>
                    </td>
                    <td>
                        <a v-if="item.attachment" class="edit" :href="item.attachment" target="_blank">
                            <i class="fa fa-clipboard"></i>&nbsp;Adjunto
                        </a>
                        <span v-else class="badge badge-danger"><i class="fa fa-ban"></i>&nbsp;Sin Adjunto</span>
                    </td>
                    <td>S/. {{ item.total }}</td>
                    <td class="action" data-title="Action">
                        <ul class="list-inline justify-content-center">
                            <li class="list-inline-item">
                                <a data-toggle="tooltip" data-placement="top" title="Ver Información" class="view btn-custom"
                                   @click.prevent="openDetail( item.id )"
                                   href="javascript:void(0);">
                                    <i class="fa fa-info"></i>&nbsp;Detalle
                                </a>
                            </li>
                            <li class="list-inline-item" v-if="item.showButton">
                                <a class="edit btn-custom" href="#" @click="actionButton( item, 'aproval' )">
                                    <i class="fa fa-check"></i>&nbsp;Aprobar
                                </a>
                            </li>
                            <li class="list-inline-item" v-if="item.showButton">
                                <a class="delete btn-custom" href="#" @click="actionButton( item, 'cancel' )">
                                    <i class="fa fa-close"></i>&nbsp;Rechazar
                                </a>
                            </li>
                        </ul>
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
                            <th>Cotización</th>
                            <th>Costo</th>
                            <th>Garantía</th>
                            <th>Plazo de ejecución</th>
                            <th>Expiración</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ salesQuotation.document }}</td>
                            <td>S/ {{ salesQuotation.total }}</td>
                            <td>{{ salesQuotation.warranty }} Mes(es)</td>
                            <td>{{ salesQuotation.execution }} Día(s)</td>
                            <td>{{ salesQuotation.expiration }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h6>Detalle</h6>
                    <table class="table table-responsive product-dashboard-table service-detail__table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Mano de obra</th>
                            <th>Materiales</th>
                            <th>Costo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="( i, idx ) in salesQuotation.items">
                            <tr>
                                <td class="item">{{ idx+1 }}</td>
                                <td>{{ i.description }}</td>
                                <td>{{ i.quantity }}</td>
                                <td>{{ i.workforce }}</td>
                                <td>{{ i.total_products }}</td>
                                <td class="text-right">{{ i.sub_total }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="4">
                                    <table class="table table-responsive product-dashboard-table service-detail__table">
                                        <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>P/U</th>
                                            <th>Importe</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="p in i.products" :key="p.id">
                                            <td>{{ p.presentation }}</td>
                                            <td>{{ p.quantity }}</td>
                                            <td>{{ p.priceUnit }}</td>
                                            <td>{{ p.subTotal }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td></td>
                            </tr>
                        </template>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="text-right" colspan="5">Sub-Total</td>
                            <th class="text-right">{{ salesQuotation.subTotal }}</th>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">IGV (18%)</td>
                            <th class="text-right">{{ salesQuotation.igv }}</th>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">Total</td>
                            <th class="text-right">{{ salesQuotation.total }}</th>
                        </tr>
                        </tfoot>
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
    export default {
        name: "list",
        data() {
            return {
                modalTitle: ''
            }
        },
        created() {
            this.$store.dispatch( 'loadSalesQuotations' );
        },
        computed: {
            salesQuotation: {
                get: function () {
                    return this.$store.state.SaleQuotation.salesQuotation;
                },
                set: function( salesQuotation ) {
                    this.$store.state.SaleQuotation.salesQuotation = salesQuotation;
                }
            },
            salesQuotations() {
                return this.$store.state.SaleQuotation.salesQuotations;
            }
        },
        methods: {
            ...mapMutations(['CHANGE_ID']),
            actionButton( data, action ) {

                let title = 'Aprobar&nbsp;<strong>Cotización <u>' + data.document + '</u></strong>';
                let text = '¿Estas seguro de <strong>aprobar</strong> la cotización <strong><u>' + data.document + '</u></strong>?';
                let buttonConfirm = '<i class="fa fa-check"></i> Aprobar';
                let confirmBtn = '#59D659';

                if( action === 'cancel' ) {
                    title = 'Rechazar&nbsp;<strong>Cotización <u>' + data.document + '</u></strong>';
                    text = '¿Estas seguro de <strong>rechazar</strong> la cotización <strong><u>' + data.document + '</u></strong>?';
                    buttonConfirm = '<i class="fa fa-close"></i> Rechazar';
                    confirmBtn = '#FF5252';
                }

                Swal.fire({
                    title: title,
                    type: 'question',
                    html: text,
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: buttonConfirm,
                    cancelButtonText: '<i class="fa fa-reply"></i> Cancelar',
                    confirmButtonColor: confirmBtn,
                    cancelButtonColor: '#929394',
                }).then( response => {
                    if ( response.value ) {
                        this.$store.dispatch({
                            type: 'toAction',
                            data: {
                                id: data.id,
                                action: action
                            }
                        }).then( response => {
                            if( response.status ) {
                                Swal.fire(
                                    title + '!',
                                    'Se realizó correctamente la operación.',
                                    'success'
                                );
                                this.$store.dispatch( 'loadSalesQuotations' );
                                this.$store.dispatch( 'loadSettingsv2' );
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
            openDetail( id ) {
                let me = this;
                this.CHANGE_ID( id );
                this.$store.dispatch( 'loadSalesQuotation' );
                me.modalTitle = 'Información Cotización';
                this.$refs['viewDetails'].show();
            },
            closeModalObservation() {
                this.idSR = 0;
                this.modalTitle = '';
                this.$refs['viewDetails'].hide();
            }
        }
    }
</script>

<style scoped>
    .btn-custom {
        width: unset !important;
        height: unset !important;
        padding: 3px 10px;
        font-size: 11px !important;
        line-height: 1;
        white-space: nowrap;
    }
    .delete.btn-custom:hover {
        color: #ff5252 !important;
        background: #fff !important;
        border-radius: 40px !important;
        border: 2px solid #ff5252 !important;
    }
</style>
