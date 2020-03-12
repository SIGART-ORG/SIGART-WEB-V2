export default {
    state: {
        userData: [],
        urlSettings: [],
        current: '',
        urlProfile: '',
        minDayWork: ''
    },

    mutations: {
        LOAD_SETTINGS( state, settings ) {
            state.userData = settings.user;
            state.urlSettings = settings.urls;
            state.current = settings.current;
            state.urlProfile = settings.urlProfile;
            state.minDayWork = settings.minday;
        },
        CHANGE_CURRENT( state, newCurrent ) {
            state.current = newCurrent;
        }
    },

    actions: {
        loadSettings( context ) {
            axios.get( '/dashboard/settings/' )
                .then( response => {
                    context.commit( 'LOAD_SETTINGS', response.data );
                });
        },
        changeCurrent( context, newCurrent ) {
            context.commit( 'CHANGE_CURRENT', newCurrent );
        }
    }
}
