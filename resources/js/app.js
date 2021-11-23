import Vue from 'vue';
require('./bootstrap');

Vue.component('product-add', require('./components/products/ProductAdd').default);
Vue.component('product-edit', require('./components/products/ProductEdit').default);
Vue.component('stock-manage', require('./components/stocks/StockManage').default);
Vue.component('return-product', require('./components/return_products/ReturnProduct').default);

import store from './store'

const app = new Vue({
    el: '#app',
    store
});
