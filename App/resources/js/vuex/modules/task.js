export default {
    state: {
        idtask: 0,
        formObservation: {
            id: 0,
            observation: ''
        }
    },
    mutations: {
        CLEAR_FORM_OBSERVATION( state ) {
            state.idtask = 0;
            state.formObservation.id = 0;
            state.formObservation.observation = '';
        },
        CHANGE_TASK_ID( state, newId ) {
            state.idtask = newId;
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
        }
    }
}
