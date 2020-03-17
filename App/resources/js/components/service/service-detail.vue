<template>
    <div id="service" class="col-md-10 offset-md-1 col-lg-9 offset-lg-0 service-detail">
        <div class="widget dashboard-container my-adslist">
            <h1 class="widget-header title">Detalle de servicio - <strong>SERV001-550</strong></h1>
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
            <h2>Tareas</h2>
            <h3>Por Iniciar <strong>({{ taskToStart.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="taskToStart.total > 0" v-for="( e, i ) in taskToStart.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>{{ e.name }}</td>
                    <td>{{ e.description }}</td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
            <h3>En Proceso <strong>({{ taskInProcess.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Inicio</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="taskInProcess.total > 0" v-for="( e, i ) in taskInProcess.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>{{ e.name }}</td>
                    <td>{{ e.description }}</td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                    <td>{{ e.start }}</td>
                </tr>
                </tbody>
            </table>
            <h3>Terminado <strong>({{ taskFinished.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Validar</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="taskFinished.total > 0" v-for="( e, i ) in taskFinished.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>{{ e.name }}</td>
                    <td>{{ e.description }}</td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                    <td>{{ e.start }}</td>
                    <td>{{ e.end }}</td>
                    <td class="action">
                        <ul class="list-inline justify-content-around">
                            <li class="list-inline-item">
                                <a class="view">
                                    <i class="fa fa-check"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="delete" @click.prevent="showModal( e.id )">
                                    <i class="fa fa-close"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
            <h3>Tareas en observación <strong>({{ taskObserved.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Observaciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="taskObserved.total > 0" v-for="( e, i ) in taskObserved.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>{{ e.name }}</td>
                    <td>{{ e.description }}</td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                    <td>
                        <a class="text-primary" href="javascript:;" @click.prevent="showModal( e.id )">
                            <i class="fa fa-plus"></i> Observación
                        </a>
                        <a v-if="e.observeds.sent > 0" class="text-warning" href="javascript:;">
                            <i class="fa fa-exclamation-triangle"></i>&nbsp;{{ e.observeds.sent }}
                        </a>
                        <a v-if="e.observeds.solved > 0" class="text-success" href="javascript:;">
                            <i class="fa fa-check"></i>&nbsp;{{ e.observeds.solved }}
                        </a>
                        <a v-if="e.observeds.denied > 0" class="text-success" href="javascript:;">
                            <i class="fa fa-close"></i>&nbsp;{{ e.observeds.denied }}
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <h3>Tareas Finalizadas <strong>({{ taskFinalized.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Aprobado</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="taskFinalized.total > 0" v-for="( e, i ) in taskFinalized.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>{{ e.name }}</td>
                    <td>{{ e.description }}</td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                    <td>{{ e.start }}</td>
                    <td>{{ e.end }}</td>
                    <td>{{ e.validate }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <b-modal ref="my-modal" hide-footer title="Registrar observación">
            <ValidationObserver ref="registerObervation" v-slot="{ invalid }">
                <form>
                    <div class="form-group">
                        <label for="observation" class="sr-only">Observación</label>
                        <ValidationProvider name="descripción" rules="required" v-slot="{ errors }">
                            <textarea v-model="formObservation.observation" class="form-control" id="observation" aria-describedby="observationHelp" placeholder="Observación"></textarea>
                            <small id="observationHelp" class="form-text text-muted text-danger">{{ errors[0] }}</small>
                        </ValidationProvider>
                    </div>
                    <b-button class="mt-3" variant="outline-danger" block :disabled="invalid" @click.prevent="sendObservation">Enviar Observación</b-button>
                    <b-button class="mt-2" variant="outline-secondary" block @click="closeModal">Cancelar</b-button>
                </form>
            </ValidationObserver>
        </b-modal>
    </div>
</template>

<script>
    import {mapMutations} from 'vuex';
    import Swal from 'sweetalert2';
    import 'sweetalert2/src/sweetalert2.scss';
    import 'vue-datetime/dist/vue-datetime.css';
    export default {
        name: "service-detail",
        created() {
            this.$store.dispatch( 'loadServiceDetail' );
        },
        computed: {
            taskToStart: {
                get:function() {
                    return this.$store.state.Service.tasks.toStart;
                }
            },
            taskInProcess: {
                get:function() {
                    return this.$store.state.Service.tasks.inProcess;
                }
            },
            taskFinished: {
                get:function() {
                    return this.$store.state.Service.tasks.finished;
                }
            },
            taskObserved: {
                get:function() {
                    return this.$store.state.Service.tasks.observed;
                }
            },
            taskFinalized: {
                get:function() {
                    return this.$store.state.Service.tasks.finalized;
                }
            },
            formObservation: {
                get: function () {
                    return this.$store.state.Task.formObservation;
                },
                set: function( newValue ) {
                    this.$store.state.Task.formObservation = newValue;
                }
            }
        },
        methods: {
            ...mapMutations(['CLEAR_FORM_OBSERVATION', 'CHANGE_TASK_ID']),
            showModal( id ) {
                this.CHANGE_TASK_ID( id );
                this.$refs['my-modal'].show();
            },
            sendObservation() {
                this.$store.dispatch( 'sendObservation' ).then( response => {
                    let result = response.data;
                    if( result.status ) {
                        this.$store.dispatch( 'loadServiceDetail' );
                        this.closeModal();
                        Swal.fire({
                            type: 'success',
                            title: 'Se registro correctamente la observación.',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                }).catch( errors => {
                    console.log( errors );
                });
            },
            closeModal() {
                this.$refs['my-modal'].hide();
                this.CLEAR_FORM_OBSERVATION();
            }
        }
    }
</script>

<style scoped>

</style>
