
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');
//window.Vue = require('vue').default;
//
//// mix v6
// require('../assets/vendor/MediaManager/js/manager')




import Vue from 'vue';
window.axios = require('axios').default;

import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.use(VueLoaders);

Vue.component('dashboard-widget', require('./components/dashboard-widget.vue').default);
Vue.component('templates', require('./components/templates.vue').default);
Vue.component('row-box', require('./components/row-box.vue').default);
Vue.component('categories', require('./components/categories.vue').default);
Vue.component('add-edit', require('./components/add-edit.vue').default);
Vue.component('text-field', require('./components/inputs/text.vue').default);
Vue.component('delete', require('./components/delete.vue').default);
Vue.component('rows', require('./components/inputs/rows.vue').default);
Vue.component('row', require('./components/inputs/row.vue').default);
Vue.component('component-field', require('./components/inputs/component.vue').default);

Vue.component('avatar', require('vue-avatar').default);

new Vue({
   el: '#app'
})
