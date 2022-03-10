export default {
	//mutations的唯一目的就是修改state中的状态
	//mutations的设计原则就是每个函数方法只负责一件事,有利于vue的插件方便调试
	changeInfo(state,payload) {
		state.info = payload;
		
	},
	getLevel(state,payload) {
		state.level = payload
	},
	changeprice(state,price){
		state.balanceprice = price;
	}
}
