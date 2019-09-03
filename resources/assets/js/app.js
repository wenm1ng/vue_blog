
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('index', require('./components/Index.vue'));
Vue.component('foot', require('./components/common/Footer.vue'));
Vue.component('articles', require('./components/Articles.vue'));
Vue.component('article_child', require('./components/Article_child.vue'));
Vue.component('comments', require('./components/Comments.vue'));
Vue.component('whispersa', require('./components/Whispers.vue'));

// import Vue from 'vue';
import ElementUI from 'element-ui'    //引入element－ui
import 'element-ui/lib/theme-default/index.css' //引入element－ui所需的css样式资源文件

Vue.use(ElementUI)    //把引入的ElementUI装入我们的Vue

const app = new Vue({
    el: '#app',
});
