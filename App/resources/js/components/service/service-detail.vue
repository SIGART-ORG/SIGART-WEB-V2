<template>
    <div id="service" class="col-md-10 offset-md-1 col-lg-9 offset-lg-0 service-detail">
        <div class="widget dashboard-container my-adslist">
            <h1 class="widget-header title">Detalle de servicio - <strong v-text="serviceDetail.document"></strong></h1>
            <div class="row">
                <div class="col-md-4 text-center">Inicio: <strong v-text="serviceDetail.start"></strong></div>
                <div class="col-md-4 text-center">Fin: <strong v-text="serviceDetail.end"></strong></div>
                <div class="col-md-4 text-center">Total: <strong v-text="serviceDetail.total"></strong></div>
                <div class="col-md-12 text-center mb-10">
                    <div class="content-traffic-light__head">
                        <span>{{ serviceDetail.percent | formatPercent }}</span>
                    </div>
                    <div class="content-traffic-light">
                        <div class="red border-right" :class="serviceDetail.trafficLight > 0 ? 'active' : ''"></div>
                        <div class="red border-right" :class="serviceDetail.trafficLight >= 2 ? 'active' : ''"></div>
                        <div class="yellow border-right" :class="serviceDetail.trafficLight >= 3 ? 'active' : ''"></div>
                        <div class="yellow border-right" :class="serviceDetail.trafficLight >= 4 ? 'active' : ''"></div>
                        <div class="green border-right" :class="serviceDetail.trafficLight >= 5 ? 'active' : ''"></div>
                        <div class="green" :class="serviceDetail.trafficLight === 6 ? 'active' : ''"></div>
                    </div>
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
                                <a class="view" @click="taskApproved( e.id, e.name )">
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
                        <div class="content-obsevarvations">
                            <a class="text-primary" href="javascript:;" @click.prevent="showModal( e.id )">
                                <i class="fa fa-plus"></i> Observación
                            </a>
                            <a v-if="e.observeds.sent > 0" class="text-warning" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;{{ e.observeds.sent }}
                            </a>
                            <a v-if="e.observeds.solved > 0" class="text-success" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-check"></i>&nbsp;{{ e.observeds.solved }}
                            </a>
                            <a v-if="e.observeds.denied > 0" class="text-success" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-close"></i>&nbsp;{{ e.observeds.denied }}
                            </a>
                        </div>
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
        <b-modal ref="my-modal" hide-footer title="Registrar observación" @hide="closeModal">
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
        <b-modal ref="observations" hide-footer :title="modalTitle" size="xl" @hide="closeModalObservation">
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Registro</th>
                    <th>Código</th>
                    <th>Observación</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="observations.length > 0" v-for="( o, i ) in observations" :key="o.id">
                    <td class="item" v-text="i + 1"></td>
                    <td v-text="o.created"></td>
                    <td v-text="o.name"></td>
                    <td>{{ o.description }}</td>
                    <td>
                        <span class="badge" :class="o.badge">{{ o.statusName }}</span>
                    </td>
                </tr>
                </tbody>
            </table>
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
        data() {
            return {
                modalTitle: '',
            }
        },
        computed: {
            serviceDetail: {
                get: function() {
                    return this.$store.state.Service.serviceDetail;
                }
            },
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
            },
            observations: {
                get: function() {
                    return this.$store.state.Task.observations;
                }
            },
        },
        methods: {
            ...mapMutations(['CLEAR_FORM_OBSERVATION', 'CHANGE_TASK_ID', 'CLEAR_OBSERVATIONS']),
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
            },
            openObervations( task, name ) {
                this.modalTitle = name + ' - Observaciones';
                this.CHANGE_TASK_ID( task );
                this.$store.dispatch( 'observations' ).catch( errors => {
                    console.log( errors );
                });
                this.$refs['observations'].show();
            },
            closeModalObservation() {
                this.modalTitle = '';
                this.CLEAR_OBSERVATIONS();
            },
            taskApproved( task, name ) {
                let me = this;
                Swal.fire({
                    title: '<strong>Aprobar tarea<br/><u>' + name + '</u>!</strong>',
                    type: 'question',
                    html: '¿Esta a punto de aprobar la tarea <u>' + name + '</u>?',
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check-circle-o"></i> Aprobar',
                    cancelButtonText: '<i class="fa fa-close"></i> Cancelar',
                    confirmButtonColor: '#5672F9',
                    cancelButtonColor: '#FF5252',
                }).then((result) => {
                    if( result.value ) {
                        this.sendApproved( task, name );
                    }
                })
            },
            sendApproved( task, name ) {
                this.CHANGE_TASK_ID( task );
                this.$store.dispatch( 'approved' ).then( response => {
                    let result = response.data;
                    if( result.status ) {
                        this.$store.dispatch( 'loadServiceDetail' );
                        Swal.fire({
                            type: 'success',
                            showCloseButton: true,
                            title: '<div>Se aprobó correctamente la tarea <strong>' + name + '</strong>.</div>',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                }).catch( errors => {
                    console.log( errors );
                });
            }
        }
    }
</script>

<style scoped>

</style>
