import Swal from "sweetalert2";
<template>
    <div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
        <div class="widget dashboard-container my-adslist">
            <h3 class="widget-header">Registrar nueva solicitud de servicio</h3>
            <div class="row">
                <div class="col-md-12">
                    <button v-if="idServiceRequest === 0" :disabled="details.length === 0"
                            class="btn pull-right btn btn-transparent" @click.prevent="generateServiceRequest">
                        <i class="fa fa-plus-circle"></i> Generar Solicitud
                    </button>
                    <button v-else :disabled="details.length === 0" class="btn pull-right btn btn-transparent"
                            @click.prevent="generateServiceRequest">
                        <i class="fa fa-plus-circle"></i> Editar Solicitud
                    </button>
                </div>
            </div>
            <ValidationObserver ref="registerServivceRequest" v-slot="{ invalid }">
                <form>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nombre <span class="text-danger">(*)</span></label>
                                <ValidationProvider name="nombre" rules="required" v-slot="{ errors }">
                                    <input type="text" v-model="name" class="form-control">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha de entrega</label>
                                <datetime
                                    v-model="dateDelivery"
                                    title="Fecha de entrega"
                                    :use12-hour="true"
                                ></datetime>
                                <small class="form-text text-muted">Fecha de entrega.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Adjuntar <span class="text-danger">(*)</span></label>
                                <ValidationProvider name="adjunto" rules="mimes:image/*,application/pdf|size:2048"
                                                    v-slot="{ errors, validate }">
                                    <input type="file" @change="handleFileChange( $event ) || validate( $event )"
                                           class="form-control-file">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </ValidationProvider>
                                <small class="form-text text-muted">Archivos permitidos: JPG, JPEG, PNG, PDF</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Dirección: <span class="text-danger">(*)</span></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" v-model="address">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ValidationProvider name="departamento" rules="required" v-slot="{ errors }">
                                <select class="form-control" name="departamento" v-model="ubigeo.departament"
                                        @change="loadProvince">
                                    <option value="" disabled selected hidden>Departamento</option>
                                    <option v-for="dep in arrDepartaments" v-bind:value="dep.id"
                                            v-text="dep.name"></option>
                                </select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="col-md-4">
                            <ValidationProvider name="provincia" rules="required" v-slot="{ errors }">
                                <select class="form-control" name="provincia" v-model="ubigeo.province"
                                        @change="loadDistrict">
                                    <option value="" disabled selected>Provincia</option>
                                    <option v-for="prov in arrProvinces" v-bind:value="prov.id"
                                            v-text="prov.name"></option>
                                </select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="col-md-4">
                            <ValidationProvider name="distrito" rules="required" v-slot="{ errors }">
                                <select class="form-control" name="distrito" v-model="ubigeo.district">
                                    <option value="" disabled selected hidden>Distrito</option>
                                    <option v-for="dist in arrDistricts" v-bind:value="dist.id"
                                            v-text="dist.name"></option>
                                </select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                    <div v-if="attachment" class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Archivo adjunto</label>
                                <span class="badge badge-primary">
                                    <a class="text-white" :href="attachment" target="_blank">
                                        <i class="fa fa-link"></i> {{ attachment }}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Item</label>
                                <ValidationProvider name="item" rules="required" v-slot="{ errors }">
                                    <input type="text" class="form-control" v-model="item">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cantidad</label>
                                <ValidationProvider name="cantidad" rules="required|min_value:1" v-slot="{ errors }">
                                    <input type="text" class="form-control" v-model="count">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Acción</label>
                                <button class="btn btn-success text-white" :disabled="invalid"
                                        @click.prevent="addItem( row )">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </ValidationObserver>
            <div class="row">
                <div class="col-12">
                    <table class="table table-responsive product-dashboard-table">
                        <thead>
                        <tr>
                            <th style="width: 10%">Item</th>
                            <th style="width: 50%" class="text-center">Detalle</th>
                            <th style="width: 20%" class="text-center">Cantidad</th>
                            <th style="width: 20%" class="text-center">Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="( detail, idx ) in details">
                            <td style="padding: 10px 0">{{ idx + 1 }}</td>
                            <td style="padding: 10px 0" class="product-details">
                                <h3 class="title">{{ detail.item }}</h3>
                            </td>
                            <td style="padding: 10px 0" class="product-category">
                                <span class="categories">{{ detail.count }}</span>
                            </td>
                            <td style="padding: 10px 0" class="action" data-title="Action">
                                <div class="">
                                    <ul class="list-inline justify-content-center">
                                        <li class="list-inline-item">
                                            <a class="delete" @click.prevent="DELETE_DETAILS( idx )">
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
    </div>
</template>

<script>
    import {mapMutations} from 'vuex';
    import {Datetime} from 'vue-datetime';
    import {Settings} from 'luxon'
    import Swal from 'sweetalert2';
    import 'sweetalert2/src/sweetalert2.scss';
    import 'vue-datetime/dist/vue-datetime.css';

    Settings.defaultLocale = 'es';

    const typePermits = [
        'image/gif',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'application/pdf'
    ];

    export default {
        name: "ServiceRequestForm",
        data() {
            return {
                item: '',
                count: 1,
            }
        },
        components: {
            datetime: Datetime
        },
        created() {
            this.$store.dispatch('loadDepartaments');
        },
        computed: {
            row() {
                let newItem = {
                    'item': this.item,
                    'count': this.count,
                    'id': 0,
                };
                return newItem;
            },
            details() {
                return this.$store.state.Service.arrDetServiceRequest;
            },
            name: {
                get: function () {
                    return this.$store.state.Service.nameServiceRequest;
                },
                set: function (newValue) {
                    this.$store.state.Service.nameServiceRequest = newValue;
                }
            },
            dateDelivery: {
                get: function () {
                    return this.$store.state.Service.formDate;
                },
                set: function (newval) {
                    this.$store.state.Service.formDate = newval;
                }
            },
            ubigeo: {
                get: function () {
                    return this.$store.state.Service.ubigeo;
                },
                set: function (newVal) {
                    this.$store.state.Service.ubigeo = newVal;
                }
            },
            address: {
                get: function () {
                    return this.$store.state.Service.address;
                },
                set: function (newVal) {
                    this.$store.state.Service.address = newVal;
                }
            },
            attachment() {
                return this.$store.state.Service.attachment;
            },
            idServiceRequest() {
                return this.$store.state.Service.idServiceRequest;
            },
            arrDepartaments() {
                return this.$store.state.StaticData.arrDepartaments;
            },
            arrProvinces() {
                return this.$store.state.StaticData.arrProvinces;
            },
            arrDistricts() {
                return this.$store.state.StaticData.arrDistricts;
            },
        },

        methods: {
            ...mapMutations(['ADD_DET_SERVICE_REQUEST', 'DELETE_DETAILS', 'CHANGE_CURRENT', 'LOAD_SETTINGS', 'CHANGE_TYPE_SEND']),
            loadProvince() {
                this.$store.dispatch({
                    type: 'loadProvinces',
                    data: {
                        departament: this.ubigeo.departament
                    },
                });
            },
            loadDistrict() {
                this.$store.dispatch({
                    type: 'loadDistrict',
                    data: {
                        departament: this.ubigeo.departament,
                        province: this.ubigeo.province,
                    },
                });
            },
            addItem(item) {
                this.ADD_DET_SERVICE_REQUEST(item);
                this.item = '';
                this.count = 1;
            },
            generateServiceRequest() {
                let me = this;
                Swal.fire({
                    title: '<strong>Guardar <u>Solicitud de Servicio</u></strong>',
                    type: 'question',
                    html: 'Desea guardar y enviar su solicitud para que pueda ser cotizada?<br><small class="form-text text-muted">* Recuerda que una vez enviada la solicitud no puede ser editada hasta que se responda dicha solicitud.</small>',
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-send"></i> Guardar y enviar',
                    cancelButtonText: '<i class="fa fa-save"></i> Guardar',
                    confirmButtonColor: '#5672F9',
                    cancelButtonColor: '#FF5252',
                }).then((result) => {
                    let type = 'save';
                    if( result.value ) {
                        type = 'send';
                    }
                    me.saveServicerequest( type );
                })
            },
            saveServicerequest(type) {
                this.CHANGE_TYPE_SEND( type );
                this.$store.dispatch('generateServiceRequest').then(response => {
                    let result = response.data;
                    if ( result.status ) {
                        this.CHANGE_CURRENT('mis-solicitudes');
                        this.$store.dispatch('loadSettings');
                    }
                }).catch(errors => {
                    console.log(errors);
                });
            },
            loadServiceRequest() {
                this.$store.dispatch({
                    type: 'getDetailServiceRequest',
                    data: {
                        id: this.idServiceRequest
                    }
                });
            },
            handleFileChange(e) {

                let fileName = e.target.files[0];
                if (typePermits.includes(fileName.type)) {
                    this.$store.commit(
                        'SET_FILE',
                        {
                            value: fileName
                        }
                    )
                }
            }
        },
        mounted() {
            if (this.idServiceRequest > 0) {
                this.loadServiceRequest();
            }
            let me = this;
            setTimeout(function () {
                me.loadProvince();
                me.loadDistrict();
            }, 1000);
        }
    }
</script>

<style scoped>

</style>
