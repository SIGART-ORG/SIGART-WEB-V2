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
    },
    actions: {
        approved({ commit, state }) {
            return new Promise( ( resolve, reject ) => {
                let url = '/service/task/' + state.idtask + '/approved/';
                axios.post( url ).then(
                    response => {
                        if( response.status ) {
                            let result = response.data;
                            //commit( 'CHANGE_TASK_ID', 0 );
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
