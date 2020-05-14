export default {
    state: {
        email: '',
        password: ''
    },
    mutations: {
        RESET_LOGIN( state ) {
            state.email = '';
            state.password = '';
        }
    },
    actions: {
        login({commit, state}) {
            return new Promise( (resolve, reject) => {
                let url = '/login';
                axios.post( url, {
                    email: state.email,
                    password: state.password
                }).then( response => {
                    let result = response.data;
                    if( result.status ) {
                        commit( 'RESET_LOGIN' );
                    }
                    resolve( result );
                }).catch( errors => {
                    reject( errors );
                })
            });
        }
    }
}
