export default {
    state: {
        idStage: 0,
        formObservation: {
            id: 0,
            observation: ''
        },
        observations: []
    },
    mutations: {
        CLEAR_FORM_OBSERVATION( state ) {
            state.idStage = 0;
            state.formObservation.id = 0;
            state.formObservation.observation = '';
        },
        LOAD_OBSERVATIONS( state, data ) {
            state.observations = data;
        },
        CHANGE_STAGE_ID( state, newId ) {
            state.idStage = newId;
        },
        CLEAR_OBSERVATIONS( state ) {
            state.idStage = 0;
            state.observations = [];
        }
    },
    actions: {
        sendObservation( { commit, state } ) {
            return new Promise( ( resolve, reject ) => {
                let url = '/service/stage/' + state.idStage + '/observation/';
                let formData = {
                    observation: state.formObservation.observation
                };
                axios.post( url, formData, ).then(
                    response => {
                        if( response.status ) {
                            commit( 'CLEAR_FORM_OBSERVATION' );
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
        observations({ commit, state }) {
            return new Promise( ( resolve, reject ) => {
                let url = '/service/stage/' + state.idStage + '/observations/';
                axios.get( url ).then(
                    response => {
                        if( response.status ) {
                            let result = response.data;
                            commit( 'LOAD_OBSERVATIONS', result.observations );
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
    }
}
