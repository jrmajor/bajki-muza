window.Vue = require('vue');

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('artist-form', require('./components/ArtistForm.vue').default);
Vue.component('tale-form', require('./components/TaleForm.vue').default);

const app = new Vue({
    el: '#app'
});