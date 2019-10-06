export default {

    state: {
        products: []
    },

    mutations: {
        LOAD_PRODUCTS( state, data ) {
            state.products = data.products;
        }
    },

    actions: {
        loadProducts( context ) {
            axios.get( '/products/' )
                .then(
                    response => {
                        context.commit( 'LOAD_PRODUCTS', response.data );
                    }
                );
        }
    }
}
