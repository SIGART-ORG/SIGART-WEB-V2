import Vue from 'vue';
import Vuex from 'vuex';

import Settings from './modules/settings';
import Products from './modules/products';
import Service from './modules/service';

Vue.use( Vuex );

export default new Vuex.Store({
    modules: {
        Settings,
        Products,
        Service,
    }
});
