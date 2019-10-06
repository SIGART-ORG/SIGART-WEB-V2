<template>
    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
        <div class="widget dashboard-container my-adslist">
            <h3 class="widget-header">Registrar nueva solicitud de servicio</h3>
            <div class="row">
                <div class="col-md-12">
                    <button :disabled="details.length === 0" class="btn pull-right btn btn-transparent" @click.prevent="generateServiceRequest">
                        <i class="fa fa-plus-circle"></i> Generar Solicitud
                    </button>
                </div>
            </div>
            <ValidationObserver ref="registerServivceRequest" v-slot="{ invalid }">
                <form>
                    <div class="row">
                        <div class="col-md-10">
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
                    </div>
                    <button class="btn btn-success text-white" :disabled="invalid" @click.prevent="addItem( row )">Agregar item</button>
                </form>
            </ValidationObserver>
            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Detalle</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Opciones</th>
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
    import { mapMutations } from 'vuex'
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
                    'count': this.count
                };
                return newItem;
            },
            details() {
                return this.$store.state.Service.arrDetServiceRequest;
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
            }
        }
    }
</script>

<style scoped>

</style>
