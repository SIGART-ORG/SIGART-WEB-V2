<template>
    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
        <!-- Recently Favorited -->
        <div class="widget dashboard-container my-adslist">
            <h3 class="widget-header">Mis solicitudes de servicios</h3>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-success pull-right btn btn-transparent" @click.prevent="CHANGE_CURRENT( 'new-service-request' )">
                        <i class="fa fa-plus-circle"></i> Solicitud
                    </button>
                </div>
            </div>

            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Solicitud</th>
                    <th class="text-center">Fecha registro</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="( sr, idx ) in serviceRequests">
                    <td>{{ idx + 1 }}</td>
                    <td class="product-details">
                        <h3 class="title">{{ sr.description }}</h3>
                    </td>
                    <td class="product-category"><span class="categories">{{ sr.date_reg }}</span></td>
                    <td class="action" data-title="Action">
                        <div class="">
                            <ul class="list-inline justify-content-center">
                                <li class="list-inline-item">
                                    <a data-toggle="tooltip" data-placement="top" title="Tooltip on top" class="view" href="">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="edit" href="">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="delete" href="">
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
        name: "servicerequest",
        created() {
            this.$store.dispatch( 'loadServiceRequest' );
        },
        computed: {
            serviceRequests() {
                return this.$store.state.Service.serviceRequests;
            }
        },
        methods: {
            ...mapMutations(['CHANGE_CURRENT'])
        }
    }
</script>

<style scoped>

</style>
