<template>
    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-3 offset-lg-0">
            <div class="sidebar">
                <!-- User Widget -->
                <div class="widget user-dashboard-profile">
                    <!-- User Image -->
                    <div class="profile-thumb">
                        <img :alt="user.name" :src="user.avatar" class="rounded-circle">
                    </div>
                    <!-- User Name -->
                    <h5 class="text-center">{{ user.name }}</h5>
                    <p>Unido {{ user.joined }}</p>
                    <a :href="'#' + urlProfile" class="btn btn-main-sm" v-if="urlProfile === 'datos-del-cliente'">Completar
                        Información</a>
                    <a :href="'#' + urlProfile" class="btn btn-main-sm" v-else>Editar Perfil</a>
                </div>
                <!-- Dashboard Links -->
                <div class="widget user-dashboard-menu">
                    <ul>
                        <li :class="url.id === current ||
                            ( current === 'new-service-request' && url.id === 'mis-solicitudes' ) ||
                            ( current === 'service-detail' && url.id === 'mis-servicios' )
                            ? 'active': '' " :key="url.id"
                            class="custom-sidebar-item"
                            v-for="url in urls"
                        >
                            <a :href="'#' + url.id" @click.prevent="CHANGE_CURRENT( url.id )">
                                <i :class="url.icon" class="fa"></i> {{ url.name }} <span v-show="url.count > 0">{{ url.count }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <comp-customer-data v-if="current === 'datos-del-cliente'"></comp-customer-data>
        <servicerequest v-if="current === 'mis-solicitudes'"></servicerequest>
        <servicerequestform v-if="current === 'new-service-request'"></servicerequestform>
        <sale-quotation-list v-if="current === 'cotizaciones-por-aprobar'"></sale-quotation-list>
        <sale-quotation-list-approved v-if="current === 'mis-cotizaciones'"></sale-quotation-list-approved>
        <service v-if="current === 'mis-servicios'"></service>
        <service-detail v-if="current === 'service-detail'"></service-detail>
    </div>
</template>

<script>
    import {mapMutations} from 'vuex'
    import ServiceDetail from "../service/service-detail";

    export default {
        name: "dashboard",
        components: {ServiceDetail},
        created() {
            this.$store.dispatch('loadSettings');
            this.$store.dispatch('loadProducts');
        },
        computed: {
            user() {
                return this.$store.state.Settings.userData;
            },
            urls() {
                return this.$store.state.Settings.urlSettings;
            },
            current() {
                return this.$store.state.Settings.current;
            },
            urlProfile() {
                return this.$store.state.Settings.urlProfile;
            }
        },
        methods: {
            ...mapMutations(['CHANGE_CURRENT'])
        }
    }
</script>

<style scoped>
    .custom-sidebar-item a {
        position: relative;
    }

    .custom-sidebar-item span {
        position: absolute;
        right: 5px;
    }
</style>
