export default {
    state: {
        serviceRequests: [],
        serviceRequestDetails: [],
        arrDetServiceRequest: [],
        idServiceRequest: 0,
        nameServiceRequest: ''
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
        },
        CHANGE_ID_SR( state, id ) {
            state.idServiceRequest = id;
        },
        LOAD_DATA_SERVICE_REQUEST( state, data ) {
            if( data.status ) {
                state.nameServiceRequest = data.serviceRequest.name;
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

        }
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
            axios.post( '/service-request/generate/', {
                id: state.idServiceRequest,
                name: state.nameServiceRequest,
                details: state.arrDetServiceRequest,
            }).then(
                response => {
                    if( response.status ) {
                        commit( 'CLEAR_DETAILS_SR' );
                    }
                }
            )
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
