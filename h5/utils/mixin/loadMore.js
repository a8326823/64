export default {
	data(){
		return{
			page: 1,
			pageData: {
				data: []
			},
			status: 'more',
		}
	},
	onReachBottom() {
		if (this.pageData.last_page === 1) return;
		setTimeout(() => {
			this.status = 'loading';
			if (this.page >= this.pageData.last_page) {
				return (this.status = 'noMore');
			}
			this.page++;
			this.getData();
		}, 500);
	},
}