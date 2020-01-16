/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import ServiceRequest from "./components/dashboard/ServiceRequest";


require('./bootstrap');
import store from "./vuex/store";
import { extend, ValidationObserver, ValidationProvider } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import es from "vee-validate/dist/locale/es.json";

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

for (let rule in rules) {
    extend(rule, {
        ...rules[rule], // add the rule
        message: es.messages[rule] // add its message
    });
}

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import Dashboard from './components/dashboard/dashboard.vue';
import ServiceRequestr from './components/dashboard/ServiceRequest.vue';
import ServiceRequestForm from "./components/dashboard/ServiceRequestForm";
import CompleteCustomerData from "./components/dashboard/complete-customer-data";
import SaleQuotationList from './components/sale-quotation/list';

Vue.component( 'dashboard', Dashboard );
Vue.component( 'comp-customer-data', CompleteCustomerData );
Vue.component( 'servicerequest', ServiceRequestr );
Vue.component( 'servicerequestform', ServiceRequestForm );
Vue.component( 'SaleQuotationList', SaleQuotationList );
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    store,
    el: '#app',
});
