import Vue from 'vue';
import App from './App.vue';
import store from './store/index';
import router from './router';

require ('./assets/js/jquery.min.js');
require ('./assets/js/popper.min.js');
// require ('./assets/js/modernizr.min.js');
require ('./assets/js/bootstrap.min.js');
// require ('./assets/js/gmaps.min.js');
// require ('./assets/js/owl.carousel.min.js');
// require ('./assets/js/scrollup.min.js');
// require ('./assets/js/price-range.js');
// require ('./assets/js/jquery.countdown.js');
// require ('./assets/js/custom.js');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store,
    router,
    render:h => h(App)
});
