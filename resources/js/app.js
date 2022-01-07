
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

Vue.component('dashboard-widget', require('./components/dashboard-widget.vue').default);

new Vue({
   el: '#app'
})
