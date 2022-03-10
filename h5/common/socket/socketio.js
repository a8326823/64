import IO from "./uni-socket.io.js"
import store from '../../store/index.js'
import config from '../../service/config.js'

export default {
    socketIO: null,
    socketinit() {
       this.socketIO = IO('https://kefu.happyshare.me/', {
			reconnection: true,//当连接终止后，是否允许Socket.io自动进行重连
			reconnectionAttempts: Infinity,//设置一个重连的最大尝试次数，超过这个值后Socket.io会使用所有允许的其他连接方式尝试重连，直到最终失败。
			reconnectionDelay: 3000,//多长时间后建立一个新的连接
			reconnectionDelayMax: 5000,//重新连接的最大等待时间（5000）
			randomizationFactor: 0.5,//它可以用来控制ReconnectionDelay范围。它的默认值是0.5，可以在0…1的值之间进行设置。
			timeout: 30000,//设置创建连接所接收的超时时间,发出“connect_error”和“connect_timeout”事件之前的连接超时
			autoConnect: true,//默认情况下，websocket在创建实例的时候就会自动发起连接了，所以切记不要在组件中重复发起连接。如果你想自己控制发起连接的时机可以将options.autoConnect设置为false。
			transports: ["websocket"],//默认支持的链接方式（顺序敏感）
			query: {token:uni.getStorageSync('userToken')}
		});
		this.socketIO.on('connect', data => {
			console.log('WebSocket 连接成功')
		});
		this.socketIO.on('message', (data) => {//收到新消息
			store.commit('addsocket',data);
			if(!store.getters.onlinebool){
				if(data.from.objectId > 0){
					store.state.socketonline++;
					uni.setStorageSync('socketonline', store.state.socketonline);
				}else{
					store.state.allonline++;
					uni.setStorageSync('allonline', store.state.allonline);
				}
			}
			// console.info('收到新消息', data);
		});
    },
    socketload() {// 数据开始加载
		this.socketinit();
    },
	socketcontent(sendid){// 打印用户ID
		let that = this;
		this.socketIO.emit('uid', {}, function(res){
			store.commit('setsocketuid',res);
			if(Number(sendid) > 0){ //上级信息  
				that.getsocket({
					page: 1,
					side_id:sendid
				});
			}else{//后台信息
				that.getsocket({
					page: 1,
					side_id:0
				});
			}
			
		});
	},
	sendsocketSystem(data){// 给系统客服发消息
		this.socketIO.emit('sendToSystem', data, function (answer) {
			// console.log('图片发送..'+answer)
		})
	},
	sendsocket(data){// 给上级发消息
		this.socketIO.emit('sendToParent', data, function (answer) {
			// console.log('文字发送。。。。'+answer)
		})
	},
	getsocket(data){
		return new Promise((resolve, reject) => {
			// console.log(data);
			this.socketIO.emit('getMessage', data, (res) => {//获取历史聊天记录
				// console.log(res);
				store.commit('setsockettotal',res.total);//获取页面总数
				if(data.page < 2){
					store.commit('setsocketio',{'page':data.page,'data':res.data});//获取100条数据
					let reslist = res.data.reverse().filter((item,index,arr) =>{
						return index < 20
					}).reverse();
					// console.log(reslist)
					store.commit('loadsocketlist',reslist);
					resolve();
				}else{
					store.commit('setsocketio',{'page':data.page,'data':res.data});//获取100条数据
					let datalist = [];
					store.getters.socketjson.map(function(item,index,arr){
						datalist.push(item);
					});
					res.data.map(function(item,index,arr){
						 datalist.push(item);
					});
					resolve(datalist);
				}
			});
		})
	},
	removesocket(sendid){//删除当前未查看信息
		if(sendid > 0){
			store.commit('onlinesocket',0);
			uni.removeStorageSync('socketonline');
		}else{
			store.commit('allonlinesocket',0);
			uni.removeStorageSync('allonline');
		}
	},
	close_socket(){
		this.socketIO.disconnect();
	}
  

}
