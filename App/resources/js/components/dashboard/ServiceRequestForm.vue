<template>
    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
        <div class="widget dashboard-container my-adslist">
            <h3 class="widget-header">Registrar nueva solicitud de servicio</h3>
            <div class="row">
                <div class="col-md-12">
                    <button v-if="idServiceRequest === 0" :disabled="details.length === 0" class="btn pull-right btn btn-transparent" @click.prevent="generateServiceRequest">
                        <i class="fa fa-plus-circle"></i> Generar Solicitud
                    </button>
                    <button v-else :disabled="details.length === 0" class="btn pull-right btn btn-transparent" @click.prevent="generateServiceRequest">
                        <i class="fa fa-plus-circle"></i> Editar Solicitud
                    </button>
                </div>
            </div>
            <ValidationObserver ref="registerServivceRequest" v-slot="{ invalid }">
                <form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nombre <span class="text-danger">(*)</span></label>
                                <ValidationProvider name="nombre" rules="required" v-slot="{ errors }">
                                    <input type="text" v-model="name" class="form-control">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Adjuntar <span class="text-danger">(*)</span></label>
                                <ValidationProvider name="adjunto" rules="mimes:image/*,application/pdf|size:2048" v-slot="{ errors, validate }">
                                    <input type="file" @change="handleFileChange( $event ) || validate( $event )" class="form-control-file">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </ValidationProvider>
                                <small>Archivos permitidos: JPG, JPEG, PNG, PDF</small>
                            </div>
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
                        <div class="col-md-2 align-bottom">
                            <div class="form-group">
                                <label>Acción</label>
                                <button class="btn btn-success text-white" :disabled="invalid" @click.prevent="addItem( row )">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </ValidationObserver>
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
</template>

<script>
    import { mapMutations } from 'vuex';

    const typePermits     = [
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
                get: function() {
                    return this.$store.state.Service.nameServiceRequest;
                },
                set: function (newValue) {
                    this.$store.state.Service.nameServiceRequest = newValue;
                }
            },
            attachment() {
                return this.$store.state.Service.attachment;
            },
            idServiceRequest() {
                return this.$store.state.Service.idServiceRequest;
            }
        },

        methods: {
            ...mapMutations(['ADD_DET_SERVICE_REQUEST', 'DELETE_DETAILS', 'CHANGE_CURRENT', 'LOAD_SETTINGS']),
            addItem( item ) {
                this.ADD_DET_SERVICE_REQUEST( item );
                this.item = '';
                this.count = 1;
            },
            generateServiceRequest() {
                this.$store.dispatch( 'generateServiceRequest' );
                this.CHANGE_CURRENT( 'mis-solicitudes' );
                this.$store.dispatch( 'loadSettings' );
            },
            loadServiceRequest() {
                this.$store.dispatch({
                    type: 'getDetailServiceRequest',
                    data: {
                        id: this.idServiceRequest
                    }
                });
            },
            handleFileChange( e ) {

                let fileName = e.target.files[0];
                if( typePermits.includes( fileName.type ) ){
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
            if( this.idServiceRequest > 0 ) {
                this.loadServiceRequest();
            }
        }
    }
</script>

<style scoped>

</style>
