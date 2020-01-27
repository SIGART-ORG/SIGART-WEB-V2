export default {
    state: {
        serviceRequests: [],
        serviceRequestDetails: [],
        arrDetServiceRequest: [],
        idServiceRequest: 0,
        nameServiceRequest: '',
        formDate: '',
        ubigeo: {
            district: 0,
            province: 0,
            departament: 0
        },
        address: '',
        attachmentServiceRequest: null,
        attachment: null,
        typeSave: 'save'
    },

    getters:{
        attachmentServiceRequest: state =>  {
            return state.attachmentServiceRequest
        },

    },

    mutations: {
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
    },

    actions: {
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
        getDetailServiceRequest( { commit }, parameters ) {
            let id = parameters.data.id;
            let url = '/service-request/' + id + '/edit/';
            axios.get( url ).then( response => {
                commit( 'LOAD_DATA_SERVICE_REQUEST', response.data );
            });
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
        }
    }
}
