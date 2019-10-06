export default {
    state: {
        serviceRequests: [],
        serviceRequestDetails: [],
        arrDetServiceRequest: []
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
        CLEAR_DETAILS( state ) {
            state.arrDetServiceRequest = [];
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
                details: state.arrDetServiceRequest,
            }).then(
                response => {
                    if( response.status ) {
                        commit( 'CLEAR_DETAILS' );
                    }
                }
            )
        }
    }
}
