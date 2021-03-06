import 'fullcalendar/dist/fullcalendar.css';

//import 'bootstrap/dist/css/bootstrap.min.css';

import Vue from 'vue';

import 'babel-polyfill';

import axios from 'axios';

import Form from './utils/form';

import * as uiv from 'uiv';

import FullCalendar from 'vue-full-calendar';

import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(FullCalendar);

Vue.use(uiv);

Vue.use(VueSweetalert2);

window.Vue = Vue;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Form = Form;