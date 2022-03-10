<template>
	<view> 
		<public-head :title="pagetitle"></public-head>
		<view class="socket_centent">
			<scroll-view scroll-with-animation scroll-y="true"  @touchmove="hideKey"  @scrolltoupper="scroll_socket" :scroll-into-view="scrollToView"
			style="width: 750rpx;" :style="{'height':srcollHeight}" :scroll-top="gotop" >
				<view class="socket_list" id="okk" scroll-with-animation>
					<view class="scrolltop"></view>
					<view class="loading" v-show="isHistoryLoading">
						<view class="spinner">
							<view class="rect1"></view>
							<view class="rect2"></view>
							<view class="rect3"></view>
							<view class="rect4"></view>
							<view class="rect5"></view>
						</view>
					</view>
					<view class="socket_item" v-for="(item,index) in socketjsonlist" :key="index" :id="'msg'+item.messageId">
						<view class="listdate" v-if="index > 0 && item.timestamp - socketjsonlist[index-1].timestamp > 60 * 3">
							<text>{{item.sendTime}}</text>
						</view>
						<view class="message_centent">
							<block v-if="item.from.objectId !== socketuid">
								<view class="message_item">
									<view class="message_image">
										<image src="/static/images/index/icon_daili.png"></image>
									</view>
									<view class="message_text">
										<block v-if="item.type > 1">
											<view class="message_img" v-if="index==socketjsonlist.length-1 ? imgshow : true">
												<image :src="item.content" mode="widthFix" @click="previewimg(item.content)"></image>
											</view>
											<view class="massage_load" v-else>
												<uni-icons :size="17" class="uni-icon-wrapper" color="#f06c7a" type="spinner-cycle" />
											</view>
										</block>
										<text v-else>{{item.content}}</text>
									</view>
								</view>
							</block>
							<block v-else>
								<view class="message_item active">
									<view class="message_text">
										<block v-if="item.type > 1">
											<view class="message_img" v-if="index==socketjsonlist.length-1 ? imgshow : true">
												<image :src="item.content" mode="widthFix" @click="previewimg(item.content)"></image>
											</view>
											<view class="massage_load" v-else>
												<uni-icons :size="17" class="uni-icon-wrapper" color="#f06c7a" type="spinner-cycle" />
											</view>
										</block>
										<text v-else>{{item.content}}</text>
									</view>
									<view class="message_image">
										<image src="/static/images/index/icon_daili.png"></image>
									</view>
								</view>
							</block>
						</view>
					</view>
					<view class="scrollbottom"></view>
				</view>
			</scroll-view>
			<view class="message_bottom">
				<view class="message_input">
					<input type="text" v-model="messagetxt"  />
					<text class="btnimg" @click="uploadimg" v-show="!messagebtn"></text>
					<button @click="sendmess" v-show="messagebtn">{{i18n.sendbtn}}</button>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import io from '@/common/socket/uni-socket.io.js';
	import {mapGetters} from 'vuex';
	var l,wh,mgUpHeight;
	export default{
		data(){
			return{
				pagetitle:'',
				pageid:null,//当前页面id
				gotop:0,
				anData:{},
				srcollHeight:'',
				isHistoryLoading:false,
				scrollToView:'',
				imgshow:true,
				messagetxt:'',
				socketindex:1,//当前分页页数
				sockettotal:1,//当前页数
			}
		},
		computed:{
			...mapGetters(['socketuid','socketjson','socketjsonlist','onlinebool']),
			messagebtn(){
				return this.messagetxt;
			},
			jsonlist(){
				return JSON.parse(JSON.stringify(this.socketjsonlist));
			},
			totalnum(){//总页数
				return this.$store.state.sockettotal;
			},
			i18n() {
			  return this.$t('servicio');  
			}
		},
		methods:{
			msgMove(x,t){
				var animation = uni.createAnimation({
				        duration: t,
				          timingFunction: 'linear',
				      })
				      this.animation = animation
				      animation.height(x).step()
				      this.anData = animation.export()
			},
			msgtop(){
				const query = uni.createSelectorQuery()
				// 延时100ms保证是最新的高度
				setTimeout(()=>{
					// 获取消息体高度
					query.select('#okk').boundingClientRect(data => {
					   // 如果超过scorll高度就滚动scorll
					   if(data.height-wh>0){
						   this.gotop=data.height-wh
						   
					   }
					   // 保证键盘第一次拉起时消息体能保持可见
					   var moveY=wh-data.height
					   // 超出页面则缩回空盒子
					   if(moveY-mgUpHeight<0){
						   // 小于0则视为0
						   if(moveY<0){
							   this.msgMove(0,200)
						   }else{
							   // 否则缩回盒子对应的高度
							  this.msgMove(moveY,200) 
						   }					   
					   }
						
					}).exec();
				},100)
			},
			msgscroll(){
				uni.onKeyboardHeightChange(res => {
					const query = uni.createSelectorQuery()
					query.select('#okk').boundingClientRect(data => {
						// 若消息体没有超过2倍的键盘则向下移动差值,防止遮住消息体
						var up=res.height*2-data.height-l*110
						console.log(up)
					  if(up>0){
						  // 动态改变空盒子高度
						 this.msgMove(up,300)
						 // 记录改变的值,若不收回键盘且发送了消息用来防止消息过多被遮盖
						 mgUpHeight=up
					  }
					  // 收回
					  if(res.height==0){
						   this.msgMove(0,0)	
					  }
					}).exec();
				 })
				var query=uni.getSystemInfoSync();
					l=query.screenWidth/750;
					wh=query.windowHeight;								
				this.srcollHeight=query.windowHeight+"px"
			},
			hideKey(){
				uni.hideKeyboard()
			},
			scroll_socket(){
				console.log(111)
				if(this.isHistoryLoading){
					return ; 
				}
				this.isHistoryLoading = true;//参数作为进入请求标识，防止重复请求
				//this.socketjsonlist[0].messageId
				let num = 20,Viewid = this.socketjson[num*this.socketindex - num].messageId,that = this;
				// console.log(num*this.socketindex,this.socketjson.length)
				if(num*this.socketindex >= this.socketjson.length){
					if(this.sockettotal >= this.totalnum){
						this.totat('暂无数据');
					}else{
						console.log('分页加载')
						this.sockettotal++;
						this.$socketinit.getsocket({
							page: this.sockettotal
						}).then(function(data){
							console.log(data.length)
							that.pagetotal(num,Viewid,data);
						});
					}
				}else{
					console.log('数据加载')
					this.pagetotal(num,Viewid,this.socketjson);
				}
				console.log('关闭动画')
				setTimeout(()=>{
					that.isHistoryLoading = false;
				},1000)
				
			},
			pagetotal(num,Viewid,arrlist){
				let list = arrlist.filter((item,index,arr) =>{
					return index < num*(this.socketindex);
				}).reverse();
				// console.log(list)
				this.$store.commit('loadsocketlist',list);
				this.socketindex++
				this.$nextTick(function() {
					console.log('msg'+Viewid)
					this.scrollToView = 'msg'+Viewid;//跳转上次的第一行信息位置
				});
			},
			uploadimg(){
				let that = this;
				uni.chooseImage({
					count: 1,
					sizeType: ['compressed'], 
					sourceType: ['album'],
				    success: (res) => {
						console.log(res)
				        const tempFilePaths = res.tempFilePaths,
								filesize = res.tempFiles[0].size / 1024 / 1024;
								if(filesize > 10){
									that.totat(that.i18n.modelinfo);
									return false;
								}
								that.imgshow = false;
								that.$http.uploadajax('upload',tempFilePaths[0],{},'image').then((res) => {
									console.log(res);
									// let imgsrc = res.result.full_uri;
									let upimg = res.result.uri;
									if(this.pageid > 0){//发送上级信息
										this.$socketinit.sendsocket({content:upimg,type:2});
									}else{// 给系统客服发消息
										this.$socketinit.sendsocketSystem({content:upimg,type:2});
									}
									that.$nextTick(function(){
										that.imgshow = true;
										that.msgtop();
									});
								});
				    }
				});
			},
			sendmess(){
				if (this.messagetxt == '') {
					this.totat('不能发送空白消息');
				} else {
					if(this.pageid > 0){//发送上级信息
						this.$socketinit.sendsocket({content:this.messagetxt,type:1});
					}else{// 给系统客服发消息
						this.$socketinit.sendsocketSystem({content:this.messagetxt,type:1});
					}
					this.messagetxt = '';
				}
			},
			previewimg(urls){
				uni.previewImage({
					urls: [urls],
					current:1
				});
			}
		},
		watch:{
			jsonlist:{
				handler(newVal,oldVal){
					if((newVal.length - oldVal.length)===1){
						this.msgtop();
					}
				}
			}
		},
		onLoad(option){
			this.pageid = option.sendid;
			this.$store.commit('onlinecheck',true);
			if(option.sendid > 0){
				this.$http.requestajx('account/info','get',{}).then((res) => {
					this.$socketinit.socketcontent(res.result.parent_id);//上级信息
					this.pagetitle = this.i18n.titlechild;
				});
			}else{
				this.pagetitle = this.i18n.title;
				this.$socketinit.socketcontent(option.sendid);//后台信息
			}
			this.msgscroll();
			this.msgtop();
		},
		onReady() {
			
		},
		beforeDestroy() {
			this.$store.commit('onlinecheck',false);
			this.$socketinit.removesocket(this.pageid);
			// console.log('退出窗口');
			// this.$socketinit.close_socket();
			// console.log('关闭websocket')
		}
	}
</script>

<style lang="scss">
	.socket_centent{ background-color: #f7f9fc;}
	.socket_list{ padding: 30px 15px 15px;}
	.listdate{ text-align: center;display: block;padding-bottom: 5px;padding-top: 25px;
		text{ background-color: rgba($color: #000000, $alpha: 0.15);border-radius: 8px;padding: 2px 9px;font-size: 12px;color: #fff;}
	}
	.message_item{ display: flex;align-items: flex-start;padding-top: 15px;
		.message_image{ border-radius: 50%;overflow: hidden;border: solid 1px #eeeeee;width: 40px;height: 40px;flex-shrink: 0;
			image{ width: inherit;height: inherit;}
		}
		.message_text{ padding: 8px;border-radius: 8px;position: relative;margin-left: 10px;background-color: #FFFFFF;
			text{ font-size: 15px;color: #000000;line-height: 20px;}
			&::after{ content: '';position: absolute;left: -10px;top: 12px; border-width: 5px;border-style: solid;
			border-color: transparent #FFFFFF transparent transparent;}
			.message_img{ width: 100px;
				image{ width: 100%;}
			}
			@keyframes loadturn{
			  0%{-webkit-transform:rotate(0deg);}
			  25%{-webkit-transform:rotate(90deg);}
			  50%{-webkit-transform:rotate(180deg);}
			  75%{-webkit-transform:rotate(270deg);}
			  100%{-webkit-transform:rotate(360deg);}
			}
			.massage_load{ animation:loadturn 4s linear infinite;}
		}
		&.active{justify-content: flex-end;
			.message_text{ margin-right: 10px;background-color: rgba($color: #3d9bf2, $alpha: 0.4);
				&::after{right: -10px;left: auto;border-color: transparent transparent transparent rgba(61, 155, 242, 0.4);}
			}
			
		}
	}
	.message_bottom{ position: fixed;bottom: 0;left: 0;width: 100%;background-color: #FFFFFF;padding: 10px;
		.message_input{ display: flex;justify-content: space-between;align-items: center;
			.btnimg{ background: url('/static/images/lang/socket_img.png') no-repeat;background-size: 100% 100%;width: 32px;height: 24px;margin-left: 18px;}
			input{background-color: #f7f9fc;border-radius: 8px;height: 35px;padding: 0 10px;font-size: 14px;flex: auto;}
			button{ width: 40px;height: 30px;line-height: 30px;font-size: 14px;margin: 0;padding: 0 5px;margin-left: 10px;}
		}
	}
	.scrolltop{ height: calc(var(--status-bar-height) + 45px);}
	.scrollbottom{ height: 55px;}
	.loading{
		//loading动画
		display: flex;
		justify-content: center;
		@keyframes stretchdelay {
			0%, 40%, 100% {
				transform: scaleY(0.6);
			}
			20% {
				transform: scaleY(1.0);
			}
		}
		.spinner {
			margin: 20upx 0;
			width: 60upx;
			height: 100upx;
			display: flex;
			align-items: center;
			justify-content: space-between;
			view {
				background-color: #f06c7a;
				height: 50upx;
				width: 6upx;
				border-radius: 6upx;
				animation: stretchdelay 1.2s infinite ease-in-out;
			}
			.rect2 {
			  animation-delay: -1.1s;
			}
			.rect3 {
			  animation-delay: -1.0s;
			}
			.rect4 {
			  animation-delay: -0.9s;
			}
			.rect5 {
			  animation-delay: -0.8s;
			}
		}
	}
</style>
