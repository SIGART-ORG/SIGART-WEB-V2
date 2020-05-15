export default {
    state: {
        email: '',
        typeDocument: 1,
        document: '',
        password: '',
        confirm: '',
        token: ''
    },
    mutations: {
        RESET_FORM_FIRST_STEP( state ) {
            state.email = '';
            state.typeDocument = 1;
            state.document = '';
        },
        RESET_FORM_SECOND_STEP( state ) {
            state.token = '';
            state.password = '';
            state.confirm = '';
        },
        SET_TOKEN( state, token ) {
            state.token = token;
        }
    },
    actions: {
        registerFirstStep({commit, state}) {
            return new Promise((resolve, reject) => {
                let url = '/register/first-step';
                axios.post(url, {
                    typeDocument: state.typeDocument,
                    document: state.document,
                    email: state.email
                }).then(response => {
                    let result = response.data;
                    commit( 'RESET_FORM_FIRST_STEP' );
                    resolve( result );
                }).catch(errors => {
                   reject( errors );
                });
            });
        },
        registerSecondStep({commit, state}) {
            return new Promise((resolve, reject) => {
                let url = '/register/complete';
                axios.post(url, {
                    password: state.password,
                    confirm: state.confirm,
                    token: state.token
                }).then( response => {
                    let result = response.data;
                    commit('RESET_FORM_SECOND_STEP');
                    resolve( result );
                });
            });
        }
    }
}
