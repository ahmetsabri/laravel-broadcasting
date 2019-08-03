require('./bootstrap');
import Home from './components/Home.vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
import {routes} from './routes';
import VueChatScroll from 'vue-chat-scroll';
import Vuetify from 'vuetify'
import Clipboard from 'v-clipboard'
import VueExpandableImage from 'vue-expandable-image';
import Photoswipe from 'vue-pswipe';
Vue.use(Photoswipe);
Vue.use(VueExpandableImage);

const moment = require('moment')
require('moment/locale/ar');
Vue.use(require('vue-moment'),{
    moment
});
Vue.use(Clipboard)
Vue.use(Vuetify)
Vue.use(VueChatScroll);
Vue.use(VueRouter);

const router = new VueRouter(
   { 
    routes,
    mode:'history',
    }
)

const app = new Vue({
    el: '#app',
    router,

    components:{
        Home
    }
});
