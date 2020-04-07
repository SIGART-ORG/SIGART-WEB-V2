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
            <h2>Etapas</h2>
            <h3>Por Iniciar <strong>({{ stageToStart.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="stageToStart.total > 0" v-for="( e, i ) in stageToStart.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>{{ e.description }}</td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
            <h3>En Proceso <strong>({{ stageInProcess.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Inicio</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="stageInProcess.total > 0" v-for="( e, i ) in stageInProcess.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>
                        {{ e.description }}
                        <div class="content-obsevarvations" v-if="e.observeds.forApproved > 0">
                            <a class="text-warning" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-info"></i>&nbsp;Tiene {{ e.observeds.denied }} respuesta(s) por validar.
                            </a>
                        </div>
                        <div class="content-obsevarvations">
                            <a v-if="e.observeds.sent > 0" class="text-warning" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;{{ e.observeds.sent }}
                            </a>
                            <a v-if="e.observeds.solved > 0" class="text-success" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-check"></i>&nbsp;{{ e.observeds.solved }}
                            </a>
                            <a v-if="e.observeds.denied > 0" class="text-danger" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-close"></i>&nbsp;{{ e.observeds.denied }}
                            </a>
                        </div>
                    </td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                    <td>{{ e.start }}</td>
                </tr>
                </tbody>
            </table>
            <h3>Terminado <strong>({{ stageFinished.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Registro</th>
                    <th>Validar</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="stageFinished.total > 0" v-for="( e, i ) in stageFinished.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>
                        {{ e.description }}
                        <div class="content-obsevarvations" v-if="e.observeds.forApproved > 0">
                            <a class="text-warning" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-info"></i>&nbsp;Tiene {{ e.observeds.denied }} respuesta(s) por validar.
                            </a>
                        </div>
                        <div class="content-obsevarvations">
                            <a v-if="e.observeds.sent > 0" class="text-warning" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;{{ e.observeds.sent }}
                            </a>
                            <a v-if="e.observeds.solved > 0" class="text-success" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-check"></i>&nbsp;{{ e.observeds.solved }}
                            </a>
                            <a v-if="e.observeds.denied > 0" class="text-danger" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-close"></i>&nbsp;{{ e.observeds.denied }}
                            </a>
                        </div>
                    </td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                    <td>{{ e.start }}</td>
                    <td class="action">
                        <ul class="list-inline justify-content-around" v-if="isValidateCustomer">
                            <li class="list-inline-item">
                                <a class="view" @click="taskApproved( e.id, e.name )">
                                    <i class="fa fa-check"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="delete" @click.prevent="showModal( e.id, e.observeds.total )">
                                    <i class="fa fa-close"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
            <h3>Tareas en observación <strong>({{ stageObserved.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Observaciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="stageObserved.total > 0" v-for="( e, i ) in stageObserved.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>
                        {{ e.description }}
                        <div class="content-obsevarvations" v-if="e.observeds.forApproved > 0">
                            <a class="text-warning" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-info"></i>&nbsp;Tiene {{ e.observeds.denied }} respuesta(s) por validar.
                            </a>
                        </div>
                    </td>
                    <td>
                        <ul v-if="e.users.total > 0">
                            <li v-for="u in e.users.records" :key="u.id">{{ u.name }}</li>
                        </ul>
                    </td>
                    <td>
                        <div class="content-obsevarvations">
                            <a class="text-primary" href="javascript:;" @click.prevent="showModal( e.id, e.observeds.total )">
                                <i class="fa fa-plus"></i> Observación
                            </a>
                            <a v-if="e.observeds.sent > 0" class="text-warning" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;{{ e.observeds.sent }}
                            </a>
                            <a v-if="e.observeds.solved > 0" class="text-success" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-check"></i>&nbsp;{{ e.observeds.solved }}
                            </a>
                            <a v-if="e.observeds.denied > 0" class="text-danger" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-close"></i>&nbsp;{{ e.observeds.denied }}
                            </a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <h3>Tareas Finalizadas <strong>({{ stageFinalized.total }})</strong></h3>
            <table class="table table-responsive product-dashboard-table service-detail__table">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Descripción</th>
                    <th>Trabajadores</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Aprobado</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="stageFinalized.total > 0" v-for="( e, i ) in stageFinalized.records" :key="e.id">
                    <td class="item">{{ i + 1 }}</td>
                    <td>
                        {{ e.description }}
                        <div class="content-obsevarvations">
                            <a v-if="e.observeds.sent > 0" class="text-warning" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;{{ e.observeds.sent }}
                            </a>
                            <a v-if="e.observeds.solved > 0" class="text-success" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-check"></i>&nbsp;{{ e.observeds.solved }}
                            </a>
                            <a v-if="e.observeds.denied > 0" class="text-danger" href="javascript:;" @click.prevent="openObervations( e.id, e.name )">
                                <i class="fa fa-close"></i>&nbsp;{{ e.observeds.denied }}
                            </a>
                        </div>
                    </td>
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
                    <div v-if="maxObs < 3" class="form-group information__warning">
                        <i class="fa fa-exclamation-triangle"></i>Se le informa que por Etapa solo podrá ingresar un <strong class="text-danger">máximo
                        de 3 observaciones</strong>, al finalizar el servicio tendrá un <strong class="text-danger">plazo máximo de 4 días</strong> para aprobar esta
                        etapa de lo contrario se aprobará automáticamente.<br/>
                        Observaciones enviadas: <strong>{{ maxObs }}</strong>
                    </div>
                    <div v-else class="form-group information__warning">
                        <i class="fa fa-close"></i>La cantidad de <strong class="text-danger">observaciones ingresadas a sobrepasado el limite
                        permitido</strong>, si no se encuentra conforme con la última respuesta a su observación, se le sugiere
                        comunicarse con un representante de la empresa mediante cualquiera de los medios brindados,
                        al finalizar el servicio tendrá un <strong class="text-danger">plazo máximo de 4 días</strong> para aprobar
                        esta etapa de lo contrario se aprobará automáticamente, asumiendo que todo está conforme.
                    </div>
                    <div class="form-group" v-if="maxObs < 3">
                        <label for="observation" class="sr-only">Observación</label>
                        <ValidationProvider name="descripción" rules="required" v-slot="{ errors }">
                            <textarea v-model="formObservation.observation" class="form-control" id="observation" aria-describedby="observationHelp" placeholder="Observación"></textarea>
                            <small id="observationHelp" class="form-text text-danger">{{ errors[0] }}</small>
                        </ValidationProvider>
                        <small v-if="msgError !== ''" class="form-text text-danger">{{ msgError }}</small>
                    </div>
                    <b-button v-if="maxObs < 3" class="mt-3" variant="outline-danger" block :disabled="invalid" @click.prevent="sendObservation">Enviar Observación</b-button>
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
                    <td>
                        {{ o.description }}
                        <div class="reply__content" v-if="o.status === 4 && !o.isValidate">
                            <div class="title">Validación de recepción de respuesta</div>
                            <div class="excerpt mobile">
                                <i class="fa fa-info"></i>
                            </div>
                            <div class="excerpt desktop">
                                <i class="fa fa-info"></i>Se le informa que respuesta a su observación fue enviada la fecha
                                <code v-text="o.replyDate"></code>.<br/>
                                Ud. <strong class="text-danger">tendrá un máximo de 4 días</strong>
                                para validar la recepción de la misma, de lo contrario se validará automáticamente. Si no se encuentra conforme con la última respuesta a su observación, se le sugiere
                                comunicarse con un representante de la empresa mediante cualquiera de los medios brindados
                            </div>
                            <div class="buttons">
                                <button @click="taskApprovedReply( o )">
                                    <i class="fa fa-check"></i> Validar recepción de respuesta
                                </button>
                            </div>
                        </div>
                        <div v-if="o.reply" class="bd-callout" :class="o.status === 3 ? 'bd-callout-primary' : 'bd-callout-danger'">
                            <h6>Rpta:</h6>
                            <p v-if="typeof o.replyLong === 'undefined' || o.replyLong === false">
                                <code v-text="o.replyDate"></code> - {{ o.reply.slice( 0, 200 ) }}
                                <a v-if="o.reply.length > 200" href="javascript:;" @click="expandText( i )">Ver más</a>
                            </p>
                            <p v-if="o.replyLong === true" >
                                <code v-text="o.replyDate"></code> - {{ o.reply }}
                                <a href="javascript:;" @click="contractText( i )">Ver menos</a>
                            </p>
                        </div>
                    </td>
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
    import Observed from "../../vuex/modules/observed";
    export default {
        name: "service-detail",
        created() {
            this.$store.dispatch( 'loadServiceDetail' );
        },
        data() {
            return {
                modalTitle: '',
                msgError: '',
                maxObs: 0,
            }
        },
        computed: {
            serviceDetail: {
                get: function() {
                    return this.$store.state.Service.serviceDetail;
                }
            },
            stageToStart: {
                get:function() {
                    return this.$store.state.Service.stages.toStart;
                }
            },
            stageInProcess: {
                get:function() {
                    return this.$store.state.Service.stages.inProcess;
                }
            },
            stageFinished: {
                get:function() {
                    return this.$store.state.Service.stages.finished;
                }
            },
            stageObserved: {
                get:function() {
                    return this.$store.state.Service.stages.observed;
                }
            },
            stageFinalized: {
                get:function() {
                    return this.$store.state.Service.stages.finalized;
                }
            },
            isValidateCustomer: {
                get: function() {
                    return this.$store.state.Service.isValidateCustomer;
                }
            },
            formObservation: {
                get: function () {
                    return this.$store.state.Observed.formObservation;
                },
                set: function( newValue ) {
                    this.$store.state.Observed.formObservation = newValue;
                }
            },
            observations: {
                get: function() {
                    return this.$store.state.Observed.observations;
                }
            },
        },
        methods: {
            ...mapMutations(['CLEAR_FORM_OBSERVATION', 'CHANGE_STAGE_ID', 'CLEAR_OBSERVATIONS', 'CHANGE_OBS_ID']),
            showModal( id, numObs ) {
                this.CHANGE_STAGE_ID( id );
                this.maxObs = numObs;
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
                    } else {
                        this.msgError = result.msg;
                    }
                }).catch( errors => {
                    console.log( errors );
                });
            },
            closeModal() {
                this.$refs['my-modal'].hide();
                this.CLEAR_FORM_OBSERVATION();
            },
            openObervations( stage, name ) {
                this.modalTitle = name + ' - Observaciones';
                this.CHANGE_STAGE_ID( stage );
                this.$store.dispatch( 'observations' ).catch( errors => {
                    console.log( errors );
                });
                this.$refs['observations'].show();
            },
            closeModalObservation() {
                this.modalTitle = '';
                this.msgError = '';
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
            sendApproved( stage, name ) {
                this.CHANGE_STAGE_ID( stage );
                this.$store.dispatch( 'approved' ).then( response => {
                    if( response.status ) {
                        this.$store.dispatch( 'loadServiceDetail' );
                        this.CHANGE_STAGE_ID( 0 );
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
            },
            expandText( index ) {
                this.observations[index].replyLong = true;
            },
            contractText( index ) {
                this.observations[index].replyLong = false;
            },
            taskApprovedReply( data ){
                let me = this;
                Swal.fire({
                    title: '<strong>Validar respuesta</strong>',
                    type: 'question',
                    html: '¿Esta a punto de validar la respuesta a tu observación <u>' + data.name + '</u>?',
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check-circle-o"></i> Aprobar',
                    cancelButtonText: '<i class="fa fa-close"></i> Cancelar',
                    confirmButtonColor: '#5672F9',
                    cancelButtonColor: '#FF5252',
                }).then((result) => {
                    if( result.value ) {
                        me.CHANGE_OBS_ID( data.id );
                        me.$store.dispatch( 'approvedReply' ).then( response => {
                            let resultReply = response.data;
                            if( resultReply.status ) {
                                me.$store.dispatch( 'observations' );
                                me.$store.dispatch( 'loadServiceDetail' );
                            }
                        });
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
