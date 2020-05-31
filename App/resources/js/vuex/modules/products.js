export default {

    state: {
        products: [],
        page: 1,
        pagination : {
            total: 0,
            current_page: 1,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0
        },
        categories: [],
        brands: [],
        resultSearch: ''
    },

    mutations: {
        LOAD_PRODUCTS( state, data ) {
            state.products = data.products;
        },
        LOAD_PRODUCTS_V2( state, products ) {
            state.products = products;
        },
        LOAD_PAGINATION( state, pagination ) {
            state.pagination = pagination;
        },
        LOAD_CATEGORIES( state, categories ) {
            state.categories = categories;
        },
        LOAD_BRANDS( state, brands ) {
            state.brands = brands;
        },
        CHANGE_PAGE( state, page ) {
            state.page = page;
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
        },
        loadProductsV2({ commit, state }) {
            let url = '/products/list';
            axios.get( url, {
                params: {
                    page: state.page
                }
            }).then( response => {
                let result = response.data;
                if( result.status ) {
                    commit( 'LOAD_PAGINATION', result.pagination );
                    commit( 'LOAD_PRODUCTS_V2', result.presentations );
                    commit( 'LOAD_CATEGORIES', result.categories );
                    commit( 'LOAD_BRANDS', result.brands );
                }
            })
        }
    }
}
