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
        loadSalesQuotations( { commit, rootState  } ) {
            let current = rootState.Settings.current;
            let url = '/sale-quotation/';
            if( current !== 'cotizaciones-por-aprobar' ) {
                url = '/sale-quotation/approved/';
            }
            axios.get( url  ).then( response => {
                let result = response.data;
                if( result.status ) {
                    commit( 'LOAD_SALES_QUOTATIONS', result );
                }
            }).catch( errors => {
                console.log( errors );
            })
        },
        toAction( { commit }, params ) {
            return new Promise( ( resolve, reject ) => {
                 let data = params.data;
                 let url = '/sale-quotation/action/';

                 axios.post( url, {
                     id: data.id,
                     action: data.action
                 }).then( response => {
                     resolve( response.data );
                 }).catch( errors => {
                     reject( errors );
                 })
            })
        }
    }
}
