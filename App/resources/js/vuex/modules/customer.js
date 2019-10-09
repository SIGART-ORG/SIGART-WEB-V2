export default {

    namespaced: true,

    state: {

    },

    mutations: {

    },

    actions: {
        loadCustomer( context ) {
            return new Promise( ( resolve, reject ) => {
                axios.get( '/customer/show/' )
                    .then( response => {
                        if( response.data.status ) {
                            resolve( response );
                        }
                    })
                    .catch( error => {
                        reject( error );
                    });
            })
        },

        updateCustomer( { commit }, parameters ) {
            let customer = parameters.data;

            return new Promise( ( resolve, reject) => {
                axios.post( '/customer/update', {
                    customer
                }).then( response => {
                    resolve( response.data );
                }).catch( errors => {
                    reject( errors );
                })
            });

        }
    }
}
