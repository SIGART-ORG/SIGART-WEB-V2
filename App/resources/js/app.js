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

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

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

extend('password', {
    params: ['target'],
    validate(value, { target }) {
        return value === target;
    },
    message: 'La confirmación de contraseña no coincide.'
});

import Dashboard from './components/dashboard/dashboard.vue';
import ServiceRequestr from './components/dashboard/ServiceRequest.vue';
import ServiceRequestForm from "./components/dashboard/ServiceRequestForm";
import CompleteCustomerData from "./components/dashboard/complete-customer-data";
import SaleQuotationList from './components/sale-quotation/list';
import SaleQuotationListApproved from './components/sale-quotation/list-approved';
import Service from './components/service/service';
import ServiceDetail from './components/service/service-detail';
import Login from './components/login/login';
import Register from './components/register/register';
import RegisterSecond from './components/register/register-second';
import ProductsIndex from "./components/products/ProductsIndex";

Vue.component( 'dashboard', Dashboard );
Vue.component( 'comp-customer-data', CompleteCustomerData );
Vue.component( 'servicerequest', ServiceRequestr );
Vue.component( 'servicerequestform', ServiceRequestForm );
Vue.component( 'SaleQuotationList', SaleQuotationList );
Vue.component( 'SaleQuotationListApproved', SaleQuotationListApproved );
Vue.component( 'Service', Service );
Vue.component( 'Login', Login );
Vue.component( 'Register', Register );
Vue.component( 'register-second', RegisterSecond );
Vue.component( 'products-index', ProductsIndex );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
require( './src/helper' );

const app = new Vue({
    store,
    el: '#app',
});
