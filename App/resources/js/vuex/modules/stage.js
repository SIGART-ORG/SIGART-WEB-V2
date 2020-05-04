import Observed from "./observed";

export default {
    state: {

    },
    mutations: {

    },
    actions: {
        approved({ rootState }) {
            return new Promise( ( resolve, reject ) => {
                let url = '/service/stage/' + rootState.Observed.idStage + '/approved/';
                axios.post( url ).then(
                    response => {
                        if( response.status ) {
                            let result = response.data;
                            resolve( result );
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
