export default {
    state: {
        salesQuotations: [],
        pagination: {
            'total': 0,
            'current_page': 0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0,
        },
        offset: 3,
    },
    mutations: {
        LOAD_SALES_QUOTATIONS( state, data ) {
            state.salesQuotations = data.data;
            state.pagination = data.pagination;
        }
    },
    actions: {
        loadSalesQuotations( context ) {
            axios.get( '/sale-quotation/' ).then( response => {
                let result = response.data;
                if( result.status ) {
                    context.commit( 'LOAD_SALES_QUOTATIONS', result );
                }
            }).catch( errors => {
                console.log( errors );
            })
        }
    }
}
