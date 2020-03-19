export default {
    state: {
        idtask: 0,
        formObservation: {
            id: 0,
            observation: ''
        },
        observations: []
    },
    mutations: {
        CLEAR_FORM_OBSERVATION( state ) {
            state.idtask = 0;
            state.formObservation.id = 0;
            state.formObservation.observation = '';
        },
        CHANGE_TASK_ID( state, newId ) {
            state.idtask = newId;
        },
        LOAD_OBSERVATIONS( state, data ) {
            state.observations = data;
        },
        CLEAR_OBSERVATIONS( state ) {
            state.idtask = 0;
            state.observations = [];
        }
    },
    actions: {
        sendObservation( { commit, state } ) {
            return new Promise( ( resolve, reject ) => {
                let url = '/service/task/' + state.idtask + '/observation/';
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
                let url = '/service/task/' + state.idtask + '/observations/';
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
        approved({ commit, state }) {
            return new Promise( ( resolve, reject ) => {
                let url = '/service/task/' + state.idtask + '/approved/';
                axios.post( url ).then(
                    response => {
                        if( response.status ) {
                            let result = response.data;
                            commit( 'CHANGE_TASK_ID', 0 );
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
        }
    }
}
