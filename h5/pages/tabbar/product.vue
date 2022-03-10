<template>
	<view class="header_top pageback">
		<public-head :headshow="false" title="购买设备" :backicon="false"></public-head>
		<view class="order_content">
			<!-- <view class="headmune">
				<view class="item" v-for="(item,index) in munelist" :key="index">
					<text :class="{'active':muneindex == index}" @click="clickmune(index)">
						{{item.title}}
					</text>
				</view>
			</view> -->
			<view class="order_top">
				<view class="orderlist">
					<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="upCallback" :up="upOption">
						<view class="item" v-for="(item,index) in dataList" :key="index">
							<view class="popup_back">
								<view class="head">
									<view class="icon">产品编号：{{item.id}}</view>
								</view>
								<view class="item_pad">
									<view class="content">
										<view class="left">
											<u-image :src="item.image" mode="aspectFill" width="100px" height="100px" radius="3"></u-image>
											<!-- <image :src="$configurl.ossBaseUrl + grabcategory.icon" mode="widthFix"></image> -->
										</view>
										<view class="right">
											<view class="title">{{item.introduce}}</view>
											<view class="info">{{item.price}}</view>
										</view>
									</view>
									<view class="populist">
										<view class="itemli">
											<view class="left color_gray">金额</view>
											<view class="right">{{item.price}}</view>
										</view>
										<view class="itemli">
											<view class="left color_gray">数量</view>
											<view class="right">{{item.total_issue}}</view>
										</view>
										<view class="itemli">
											<view class="left color_gray">收益</view>
											<view class="right">{{item.hourly_income}}</view>
										</view>
										<view class="itemli">
											<view class="left">周期</view>
											<view class="right color_red">{{item.effective_time}}</view>
										</view>
									</view>
									<view class="orderbtn" v-if="muneindex == 0">
										<button size="mini" @click="submitForm(item.id,0)">法币购买</button>
										<button size="mini" @click="submitForm(item.id,1)">USDT购买</button>
									</view>
								</view>
							</view>
						</view>
					</mescroll-body>
				</view>
			</view>
		</view>
		
	</view>
</template>

<script>
	import MescrollMixin from "@/uni_modules/mescroll-uni/components/mescroll-uni/mescroll-mixins.js";
	export default{
		mixins: [MescrollMixin],
		data(){
			return{
				muneindex: 0,
				munelist:[{title:"产品列表",type: 2,name:"munelist2"},
						  {title:"进行中",type: 0,name:"munelist1"},
						  {title:"已购买",type: 4,name:"munelist3"}],
				upOption: {
					page: {
						size: 10
					},
					noMoreSize: 5, 
					toTop:{
						src:''
					}
				},
				dataList:[]
			}
		},
		computed:{
			i18n() {
			  return this.$t('product')  
			}  
		},
		onLoad() {
			this.getload();
		},
		methods:{
			clickmune(index){
				this.muneindex = index;
				this.downCallback();
			},
			getload(){
				let that = this;
				this.$http.requestajx('user_financial_products','get',{}).then((res) => {
					// that.datalist = res.result.data;
				}).catch((error) => {
					console.log('错误重启');
				});
				
			},
			submitForm(id,type){
				let that = this;
				this.$http.requestajx(`user_financial_products/buy_financial_products/${id}&${type}`,'get',{}).then((res) => {
					// that.datalist = res.result.data;
				}).catch((error) => {
					console.log('错误重启');
				});
			},
			loadjson(page){
				let pageNum = page.num; // 页码, 默认从1开始
				let pageSize = page.size; // 页长, 默认每页10条
				let limit_begin = (pageNum - 1)*pageSize; //条数
				let that = this;
				this.$http.requestajx('financial_products','get',{
					page: pageNum
				}).then(function(res){
					// 接口返回的当前页数据列表 (数组)
					let curPageData = res.result.data;
					// 接口返回的当前页数据长度 (如列表有26个数据,当前页返回8个,则curPageLen=8)
					let curPageLen = curPageData.length; 
					// 接口返回的总页数 (如列表有26个数据,每页10条,共3页; 则totalPage=3)
					let totalPage = res.result.total; 
					
					//设置列表数据
					if(page.num == 1) that.dataList = []; //如果是第一页需手动置空列表
					that.dataList = that.dataList.concat(curPageData); //追加新数据
					// that.mescroll.endByPage(curPageLen, totalPage); 
					that.mescroll.endSuccess(curPageLen, true); 
				})
			},
			downCallback(){
				this.mescroll.resetUpScroll(); 
			},
			upCallback(page) {/*上拉加载的回调*/
				this.loadjson(page)
			},
			submitGrabOrder(taskid){
				let that = this;
				this.$http.requestajx('task/submitGrabOrder','put',{id: taskid}).then((res) => {
					that.totat(res.result.message);
					// that.getsurplus();
					setTimeout(function(){
						if(res.code == 410){
							that.linkto('/pages/profile/open-member')
						}else if(res.code == 4011){
							uni.showModal({
								title: '',
								content: `${that.i18n.modaltitle1}${that.$options.filters.formatPrice(res.result.balance)}${that.i18n.modaltitle2}`,
								cancelText:that.i18n.cancelText,
								confirmText:that.i18n.confirmText,
								success(res) {
									if (res.confirm) {
										uni.navigateTo({url:'/pages/profile/selectmode'});
									}
								}
							});
						}
						that.downCallback();
					},300)
				}).catch((error) => {
					console.log('错误重启',error);
				});
			}
		}
	}
</script>

<style lang="scss">
.pageback{ min-height: 100vh;width: 100%;}
.headback{ width: 100%;position: absolute;top: 0;left: 0;height: 90px;background: url('/static/images/grab/order/tc-bg.png') no-repeat;background-size: 100%;}
.bakcicon{ position: absolute;top: 0;width: 100%;height: 30vh;background: linear-gradient(1turn,#fff,#bac3d2);}
.headtop{ position: fixed;left: 0px;top: 0px;width: 100%;z-index: 2;overflow: hidden;}
.headmune{ display: flex;align-items: center;width: 100%;border-top: 1px solid #D8D8D8;position: relative;z-index: 1;
	.item{color: #8D8D8D;flex: 1;text-align: center;padding: 15px 0;
		uni-text{ position: relative;
			&.active{ color: #333;
				&::after{ content:"";position: absolute;bottom: -14px;left: calc((100% - 20px)/2);border-bottom: 4px solid #ff6e2b;width: 20px;border-radius: 10px;}
			}
		}
		&+.item{ border-left: 1px solid #DDD;}
	}
}
.order_top{ background-color: #F0F0F0;}
.orderlist{ padding-top: 10px;
	.item{ padding: 5px 10px;position: relative;min-height: 266px;}
	.popup_back{ background-color: #FFFFFF;border-radius: 3px;}
	.head{ color: #9e9e9e;font-size: 28rpx;padding-top: 10px;
		.icon{ background-color: #FD9341;color: #FFFFFF;border-top-right-radius: 20px;border-bottom-right-radius: 20px;display: inline-block;padding: 5px 15px;}
	}
	.item_pad{padding: 5px 15px 20px;}
	.content{ display: flex;padding: 12px 0;border-bottom: 1px solid #D8D8D8;
		uni-image{ width: 100px;}
		.right{ padding-top: 5px;position: relative;margin-left: 10px;flex: auto;
			.title{ font-size: 26rpx;line-height: 32rpx;}
			.info{ position: absolute;bottom: 5px;left: 0;}
		}
	}
	.populist{
		.itemli{ display: flex;align-items: center;justify-content: space-between;margin-top: 15px;
			.color_gray{ color: #969696;}
			.left{ color: #434556;}
			.right{ color: #000000;}
			.color_red{ color: #FF0900!important;}
		}
	}
	.orderbtn{ padding-top: 15px;display: flex;align-items: center;justify-content: space-between;
		uni-button{ background-color: #FD9341;color: #FFFFFF;font-size: 24rpx;}
	}
}
</style>