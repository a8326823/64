import Vue from 'vue'
Vue.mixin({
	methods: {
		totat(title, icon = "none", duration = 1500,position) {
			return uni.showToast({
				title,
				icon,
				duration,
				position
			})
		}
	}
})
