import Vue from 'vue';
import Vuex from 'vuex';

import Settings from './modules/settings';
import Products from './modules/products';
import Service from './modules/service';
import StaticData from './modules/static';
import Customer from './modules/customer.js'
import SaleQuotation from './modules/sale-quotation';

Vue.use( Vuex );

export default new Vuex.Store({
    modules: {
        Settings,
        Products,
        Service,
        StaticData,
        Customer,
        SaleQuotation
    }
});
