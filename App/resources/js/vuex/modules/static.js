export default {
    state: {
        arrDepartaments: [],
        arrProvinces: [],
        arrDistricts: [],
        arrTypeDocuments: [],
        arrTypePerson: []
    },

    mutations: {
        LOAD_DEPARTAMENTS( state, data ) {
            state.arrDepartaments = data;
        },
        LOAD_PROVINCES( state, data ) {
            state.arrProvinces = data;
        },
        LOAD_DISTRICTS( state, data ) {
            state.arrDistricts = data;
        },
        LOAD_TYPE_DOCUMENT( state, data ) {
            state.arrTypeDocuments = data;
        },
        LOAD_TYPE_PERSON( state, data ) {
            state.arrTypePerson = data;
        }
    },

    actions: {
        loadDepartaments( context ) {
            axios.get( '/departaments/' )
                .then( response => {
                    context.commit( 'LOAD_DEPARTAMENTS', response.data );
                });
        },
        loadProvinces( { commit }, parameters ) {
            axios.get( '/provinces/' + parameters.data.departament )
                .then( response => {
                    commit( 'LOAD_PROVINCES', response.data )
                });
        },
        loadDistrict( { commit }, parameters ) {
            axios.get( '/districts/' + parameters.data.departament + '/' + parameters.data.province )
                .then( response => {
                    commit( 'LOAD_DISTRICTS', response.data );
                });
        },
        loadTypeDocument( context ) {
            axios.get( '/type-document/' )
                .then( response => {
                    context.commit( 'LOAD_TYPE_DOCUMENT', response.data );
                })
        },
        loadTypePerson( context ) {
            axios.get( '/type-person/' )
                .then( response => {
                    context.commit( 'LOAD_TYPE_PERSON', response.data );
                })
        }
    }
}
