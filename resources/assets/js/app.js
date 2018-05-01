
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./directives');

window.Vue = require('vue');
import { Form, Errors } from './utils';

window.Form = Form;
window.Errors = Errors;

import Vuex from 'vuex';
import VCalendar from 'v-calendar';
import 'v-calendar/lib/v-calendar.min.css';
import VueRouter from 'vue-router';

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VCalendar, {
    formats: {
        title: 'MMMM YYYY',
        weekdays: 'W',
        navMonths: 'MMM',
        input: ['DD/MM/YYYY', 'L', 'YYYY-MM-DD', 'YYYY/MM/DD'],
        dayPopover: 'L',
        data: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD'],
    }
});

/**
 * Add components here
 */
import store from './store';
require('./shared');
require('./callings');
require('./organizations');
require('./auth');
require('./forms');
require('./elements');

const app = new Vue({
    el: '#app',
    store,
});
