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
                    <th class="text-center">Sub Total<br>(S/)</th>
                    <th class="text-center">IGV<br>(18%)</th>
                    <th class="text-center">Total<br>(S/)</th>
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
                            <div class="red border-right" :class="item.project.trafficLight > 0 ? 'active' : ''"></div>
                            <div class="red border-right" :class="item.project.trafficLight >= 2 ? 'active' : ''"></div>
                            <div class="yellow border-right" :class="item.project.trafficLight >= 3 ? 'active' : ''"></div>
                            <div class="yellow border-right" :class="item.project.trafficLight >= 4 ? 'active' : ''"></div>
                            <div class="green border-right" :class="item.project.trafficLight >= 5 ? 'active' : ''"></div>
                            <div class="green" :class="item.project.trafficLight === 6 ? 'active' : ''"></div>
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
                        <span v-if="item.orderPay === 1 || item.orderPay === 3" class="badge badge-warning">Pendiento de pago</span>
                        <div class="mw-100 d-flex justify-content-around">
                            <a href="javascript:;" class="text-info w-100">{{ item.voucherFiles }} voucher(s)</a>
                        </div>
                        <div class="mw-100 d-flex justify-content-around">
                            <button type="button" class="btn btn-xs btn-outline-info" @click.prevent="modalUploadVoucher( item.id, item.orderPay )">
                                <i class="fa fa-upload"></i> Subir voucher
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <b-modal ref="upload" hide-footer :title="modalTitle" size="md" @ok="uploadVoucher" @hide="closeModal" @cancel="closeModal">
            <ValidationObserver ref="uploadVoucher" v-slot="{ invalid }">
                <form>
                    <div class="form-group">
                        <label>Voucher</label>
                        <ValidationProvider name="voucher" rules="required|image" v-slot="{ errors, validate }">
                            <input type="file" class="form-control" @change="validateImage( $event ) || validate( $event )">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="form-group">
                        <b-button class="mt-2" variant="outline-info" :disabled="!!( invalid || blockButton )" block @click="uploadVoucher( $event )">Subir voucher</b-button>
                        <b-button class="mt-3" variant="outline-danger" :disabled="!!( invalid || blockButton )" block @click="closeModal">Cancelar</b-button>
                    </div>
                </form>
            </ValidationObserver>
        </b-modal>
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
        data() {
            return {
                modalTitle: '',
                voucher: {},
                blockButton: true,
                idServiceTemp: 0,
                orderPayTemp: 0,
            }
        },
        computed: {
            services() {
                return this.$store.state.Service.services;
            }
        },
        methods: {
            ...mapMutations(['CHANGE_CURRENT', 'SET_SERVICE_ID', 'SET_FILE_VOUCHER']),
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
                this.SET_SERVICE_ID( id );
            },
            modalUploadVoucher( id, type ) {
                this.modalTitle = 'Subir voucher de pago';
                this.idServiceTemp = id;
                this.orderPayTemp = type;
                this.$refs['upload'].show();
            },
            uploadVoucher() {
                let idServiceTemp = this.idServiceTemp;
                let orderPayTemp = this.orderPayTemp;
                this.$store.commit(
                    'SET_FILE_VOUCHER',
                    { value: this.imageInput }
                );
                this.$store.dispatch({
                    type: 'uploadVoucher',
                    data: {
                        idService: idServiceTemp,
                        orderPayTemp: orderPayTemp
                    }
                }).then( response => {
                    let result = response.data;
                    this.closeModal();
                    if( result.status ) {
                        this.$store.dispatch( 'loadServices' );
                    }
                });
            },
            validateImage( event ) {
                let imageTemp = event.target.files[0];

                if ( /.(gif|jpeg|jpg|png)$/i.test( imageTemp.type ) ) {
                    this.imageInput = imageTemp;
                    this.blockButton = false;
                }
            },
            closeModal() {
                this.imageInput = {};
                this.blockButton = true;
                this.modalTitle = '';
                this.idServiceTemp = 0;
                this.orderPayTemp = 0;
                this.$refs['upload'].hide();
            }
        }
    }
</script>

<style scoped>

</style>
