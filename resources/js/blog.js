import Vue from 'vue';

window.axios = require('axios').default;

import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.use(VueLoaders);

Vue.component('comments-frontend', require('./components/comments-frontend.vue').default);

new Vue({
  el: '#app',
});
