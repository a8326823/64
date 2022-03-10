export default {
	data(){
		return {
			beforeReady:true,
		}
	},
	mounted() {
		const that = this;
		this.$nextTick(()=>{
			setTimeout(()=>{
				that.beforeReady = false
			},500)
		})
	}
}