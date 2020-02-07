<template>
    <div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
        <!-- Recently Favorited -->
        <div class="widget dashboard-container my-adslist">
            <h3 class="widget-header">Mis Servicios</h3>

            <table class="table table-responsive product-dashboard-table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Servicio</th>
                    <th class="text-center">Solicitud</th>
                    <th class="text-center">Ejecución</th>
                    <th class="text-center">Sub-Total</th>
                    <th class="text-center">IGV( 18% ).</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Pagos</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="( item, idx ) in salesQuotations">
                    <td>{{ idx + 1 }}</td>
                    <td class="product-details">
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
                    </td>
                    <td class="product-category">
                        <mark>{{ item.start }}</mark> a <mark>{{ item.end }}</mark>
                    </td>
                    <td>
                        <a v-if="item.attachment" class="edit" :href="item.attachment" target="_blank">
                            <i class="fa fa-clipboard"></i>&nbsp;Adjunto
                        </a>
                        <span v-else class="badge badge-danger"><i class="fa fa-ban"></i>&nbsp;Sin Adjunto</span>
                    </td>
                    <td>S/. {{ item.subTotal }}</td>
                    <td>S/. {{ item.discount }}<br/><small>({{ item.discountPorc }}% Total)</small></td>
                    <td>S/. {{ item.total }}</td>
                    <td class="action" data-title="Action">
                        <ul class="list-inline justify-content-center">
                            <li class="list-inline-item">
                                <a data-toggle="tooltip" data-placement="top" title="Ver Información" class="view btn-custom" href="#">
                                    <i class="fa fa-info"></i>&nbsp;Detalle
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="edit btn-custom" href="#" @click="actionButton( item, 'aproval' )">
                                    <i class="fa fa-check"></i>&nbsp;Aprobar
                                </a>
                            </li>
                            <li class="list-inline-item">
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
    </div>
</template>

<script>
    export default {
        name: "service"
    }
</script>

<style scoped>

</style>
