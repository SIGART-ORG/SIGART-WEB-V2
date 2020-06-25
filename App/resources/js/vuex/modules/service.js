export default {
    state: {
        force: false,
        services: [],
        serviceDetail: {
            id: 0,
            document: '',
            start: '',
            end: '',
            total: 0,
            trafficLight: 0,
            percent: 0,
        },
        stages: {
            toStart: {
                total: 0,
                records: [],
            },
            inProcess: {
                total: 0,
                records: [],
            },
            finished: {
                total: 0,
                records: [],
            },
            observed: {
                total: 0,
                records: [],
            },
            finalized: {
                total: 0,
                records: [],
            }
        },
        isValidateCustomer: false,
        serviceRequests: [],
        serviceRequestDetails: [],
        arrDetServiceRequest: [],
        idServiceRequest: 0,
        idService: 0,
        nameServiceRequest: '',
        formDate: '',
        dateRegFormat: '',
        dateSendFormat: '',
        isSend: 0,
        status: 0,
        ubigeo: {
            district: 0,
            province: 0,
            departament: 0
        },
        address: '',
        attachmentServiceRequest: null,
        attachment: null,
        typeSave: 'save',
        voucherTemp: {},
        vouchers: []
    },

    getters:{
        attachmentServiceRequest: state =>  {
            return state.attachmentServiceRequest
        },

    },

    mutations: {
        LOAD_SERVICES( state, data ) {
            state.services = data.services;
        },
        LOAD_SERVICE_REQUEST( state, data ) {
            state.serviceRequests = data.serviceRequest.data;
        },
        ADD_DET_SERVICE_REQUEST( state, arrData ) {
            state.arrDetServiceRequest.push( arrData );
        },
        DELETE_DETAILS( state, index ) {
            state.arrDetServiceRequest.splice( index, 1 ) ;
        },
        CLEAR_DETAILS_SR( state ) {
            state.idServiceRequest = 0;
            state.arrDetServiceRequest = [];
            state.nameServiceRequest = '';
            state.formDate = '';
            state.ubigeo.district = 0;
            state.ubigeo.province = 0;
            state.ubigeo.departament = 0;
            state.address = '';
            state.dateRegFormat = '';
            state.dateSendFormat = '';
            state.isSend = 0;
            state.status = 0;
        },
        CHANGE_ID_SR( state, id ) {
            state.idServiceRequest = id;
        },
        CHANGE_TYPE_SEND( state, type ) {
            state.typeSave = type;
        },
        LOAD_DATA_SERVICE_REQUEST( state, data ) {
            if( data.status ) {
                state.nameServiceRequest = data.serviceRequest.name;
                state.attachment = data.serviceRequest.attachment;
                state.formDate = data.serviceRequest.dateDelivery;
                state.ubigeo = data.serviceRequest.ubigeo;
                state.address = data.serviceRequest.address;
                state.dateRegFormat = data.serviceRequest.dateRegFormat;
                state.dateSendFormat = data.serviceRequest.dateSendFormat;
                state.isSend = data.serviceRequest.isSend;
                state.status = data.serviceRequest.status;
                state.arrDetServiceRequest = [];
                let detail = data.serviceRequest.detail;
                detail.map( function( e ) {
                    state.arrDetServiceRequest.push({
                        item: e.description,
                        count: e.quantity,
                        id: e.id
                    });
                });
            }
        },
        SEND_SR( state, id ) {

        },
        SET_FILE( state, payload ) {
            state.attachmentServiceRequest = payload.value;
        },
        SET_SERVICE_ID( state, id ) {
            state.idService = id;
        },
        LOAD_DATA_SERVICE_DETAIL( state, data ) {
            state.serviceDetail.id = data.id;
            state.serviceDetail.document = data.document;
            state.serviceDetail.start = data.start;
            state.serviceDetail.end = data.end;
            state.serviceDetail.total = data.total;
            state.serviceDetail.trafficLight = data.project.trafficLight;
            state.serviceDetail.percent = data.project.percent;
            state.stages = data.project.stages;
            state.isValidateCustomer = data.project.isValidateCustomer;
        },
        SET_FILE_VOUCHER( state, payload ) {
            state.voucherTemp = payload.value;
        },
        LOAD_VOUCHERS( state, data ) {
            state.vouchers = data;
        },
    },

    actions: {
        loadServices( context ) {
            axios.get( '/service/' )
                .then( response => {
                    context.commit( 'LOAD_SERVICES', response.data );
                });
        },
        toActionSO( { commit }, params ) {
            return new Promise( ( resolve, reject ) => {
                let data = params.data;
                let url = '/service/service-order-action/';

                axios.post( url, {
                    id: data.id,
                    referenceterm: data.referenceterm,
                    action: data.action
                }).then( response => {
                    resolve( response.data );
                }).catch( errors => {
                    reject( errors );
                })
            })
        },
        loadServiceRequest( context ) {
            axios.get( '/service-request/' )
                .then( response => {
                    context.commit( 'LOAD_SERVICE_REQUEST', response.data );
                });
        },
        addDetServiceRequest( context, arrData ) {
            context.commit( 'ADD_DET_SERVICE_REQUEST', arrData );
        },
        deleteDetails( context, idx ) {
            context.commit( 'DELETE_DETAILS', idx );
        },
        generateServiceRequest( { commit, state } ) {

            return new Promise( ( resolve, reject ) => {
                let formData = new FormData();
                formData.append('id', state.idServiceRequest );
                formData.append('name', state.nameServiceRequest );
                formData.append('dateDelivery', state.formDate );
                formData.append('address', state.address );
                formData.append('ubigeo', JSON.stringify( state.ubigeo ) );
                formData.append('attachment', state.attachmentServiceRequest );
                formData.append('type', state.typeSave );
                state.arrDetServiceRequest.forEach( ( detail, i ) =>
                    formData.append( `details[${i}]`, JSON.stringify( detail ) )
                );

                axios.post( '/service-request/generate/', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(
                    response => {
                        if( response.status ) {
                            commit( 'CLEAR_DETAILS_SR' );
                            resolve( response );
                        }
                        else {
                            reject( response );
                        }
                    }
                ).catch( errors => {
                    reject( errors );
                });
            });
        },
        getDetailServiceRequest( { commit, state } ) {
            let idSR = state.idServiceRequest;
            let url = '/service-request/' + idSR + '/edit/';

            if( idSR > 0 ) {
                axios.get(url, {
                    params: {
                        force: state.force
                    }
                }).then(response => {
                    commit('LOAD_DATA_SERVICE_REQUEST', response.data);
                });
            }
        },
        sendSR( { commit }, parameters) {
            return new Promise( ( resolve, reject ) => {
                let data = parameters.data;
                let url = '/service-request/' + data.id + '/send/';
                axios.get( url )
                    .then( response => {
                        resolve( response.data );
                    })
                    .catch( errors => {
                        reject( errors );
                    })
            });
        },
        deleteSR( { commit }, parameters ) {
            return new Promise( ( resolve, reject ) => {
                let data = parameters.data;
                let url = '/service-request/' + data.id + '/delete/';
                axios.get( url ).then( response => {
                    resolve( response.data );
                }).catch( errors => {
                    reject( errors );
                })
            });
        },
        loadServiceDetail({ commit, state }) {
            let url = '/service/' + state.idService + '/detail/all/';
            axios.get( url ).then( response => {
                let result = response.data;
                if( result.status ) {
                    commit( 'LOAD_DATA_SERVICE_DETAIL', result.service );
                } else {
                    console.log( result.msg );
                }
            }).catch( errors => {
                console.log( errors );
            })
        },
        uploadVoucher({ commit, state }, parameters) {
            return new Promise( ( resolve, reject ) => {
                let params = parameters.data;
                let url = '/service/' + params.idService + '/upload-voucher/';
                let formData = new FormData();
                formData.append('voucherFile', state.voucherTemp );
                formData.append('orderPayTemp', params.orderPayTemp );
                formData.append('numOper', params.numOper );
                formData.append('mount', params.mount );
                axios.post( url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(
                    response => {
                        if( response.status ) {
                            commit( 'SET_FILE_VOUCHER', {} );
                            resolve( response );
                        }
                        else {
                            reject( response );
                        }
                    }
                ).catch( errors => {
                    reject( errors );
                });
            });
        },
        loadVouchers( { commit }, parameters ) {
            let params = parameters.data;
            let url = '/service/' + params.idService + '/vouchers/';
            axios.get( url ).then( response => {
                let result = response.data;
                if( result.status ) {
                    commit( 'LOAD_VOUCHERS', result.attachments );
                }
            });
        },
    }
}
