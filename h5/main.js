import Vue from 'vue'
import App from './App'
import uView from '@/uni_modules/uview-ui'
import store from './store/index.js'
import http from './service/methods.js'
import './utils/mixin/toast.js'
import configurl from './service/config.js'
import VueI18n from 'vue-i18n' 
import langjs from 'common/language/language.js'
import VueClipboard from 'vue-clipboard2';



Vue.config.productionTip = false

App.mpType = 'app'
Vue.use(uView);
Vue.use(VueI18n);
Vue.use(VueClipboard);
// http请求
Vue.prototype.$http = http;

//vuex
Vue.prototype.$store = store;
Vue.prototype.$configurl = configurl;

// 引入全局加载动画
import loadingPlus from "@/utils/mixin/loading-plus.vue"
Vue.component('loading-plus', loadingPlus)


// 自定义指令（价格）
Vue.directive('formatPrice',{
	inserted(el) {
		el.innerHTML = "$" + Number(el.innerHTML).toFixed(2)
	}
})



// 全局过滤器(价格)
Vue.filter('formatPrice', (val) => {
	return "$" + Number(val).toFixed(2);
})

const i18n = new VueI18n({
  locale: 'en', 
  messages: langjs
})
Vue.prototype._i18n = i18n;
const app = new Vue({
	i18n,
	...App
})

app.$mount()
