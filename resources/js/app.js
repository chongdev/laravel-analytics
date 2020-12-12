require('./bootstrap');

import Vue from 'vue'
import axios from 'axios'
Vue.prototype.$axios = axios

const DashboardRealtime = () => import('./components/DashboardRealtime/index.vue')

new Vue({
    el: '#app',

    components: {
        DashboardRealtime,
    }
});

