<template>
	<view class="wrapper">
		<loading-plus v-if="beforeReady"></loading-plus>
		<view class="head_pos">
			<view class="status_bar"></view>
			<view class="head_content">
				<view class="head_item">
					<view class="lang_flag">
						<label @click="langlist">
							<image :src="$configurl.ossBaseUrl+langflag.image" mode="widthFix"></image>{{langflag.lang}}
						</label>
						<view class="lang_list" :class="{'active':langshow}">
							<view class="list_after" v-show="langshow">
								<view class="langul" v-if="munelang.length > 0">
									<view class="lang_flag" v-for="(item,index) in munelang" :key="index" :class="{'active':langflag.code == item.code}">
										<view @click="munebtn(item.code)">
											<image :src="$configurl.ossBaseUrl+item.image" mode="widthFix"></image> {{item.lang}}
										</view> 
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
				<view class="head_item head_kefu">
					<image src="/static/images/tabbar/kefu.png" mode="widthFix" @click="kefu_btn"></image>
				</view>
			</view>
		</view>
		<view class="backwrite">
			<view class="top-bgd"></view>
			<view class="index_banner">
				<view class="banner_content">
					<u-swiper
						:list="bannerlist"
						keyName="image"
						indicator
						indicatorMode="line"
						:autoplay="false"
						circular
					></u-swiper>
				</view>
			</view>
			<view class="bannerBotice">
				<u-notice-bar :text="broadmune" direction="column" bgColor="#7a2b86" color="#fff"></u-notice-bar>
			</view>
			<view class="vip-classify">
				<view class="classify-item" v-for="(item, index) in memberInfo" :key="index">
					<navigator :url="item.navrouter" hover-class="none" class="item_nav">
						<view class="" style="margin-bottom: 5px;text-align: center;">
							<view class="lazyimg">
								<u-image width="45px" height="45px" :src="item.imgsrc" mode="widthFix" :fade="true" duration="450"  error-icon="error-square"></u-image>
							</view>
						</view>
						<view class="item_nav uni-ellipsis">
							<text>{{i18n[item.name]}}</text>
						</view>
					</navigator>
				</view>
			</view>
			<view class="indexTable">
				<uni-row :gutter="30">
					<uni-col :span="12">
						<view class="item">
							<view class="title">余额</view>
							<view class="info">
								<view class="left">$0.11</view>
								<view class="right">
									<image src="/static/images/index/icon_team.png" class="l-img" mode="widthFix"></image>
								</view>
							</view>
						</view>
					</uni-col>
					<uni-col :span="12">
						<view class="item">
							<view class="title">购买金额</view>
							<view class="info">
								<view class="left">{{TodayProfit | formatPrice}}</view>
								<view class="right">
									<image src="/static/images/index/icon_shouyi.png" class="r-img" mode="widthFix"></image>
								</view>
							</view>
						</view>
					</uni-col>
					<uni-col :span="12">
						<view class="item">
							<view class="title">USDT资产</view>
							<view class="info">
								<view class="left">$0.11</view>
								<view class="right">
									<image src="/static/images/index/icon_team.png" class="l-img" mode="widthFix"></image>
								</view>
							</view>
						</view>
					</uni-col>
					<uni-col :span="12">
						<view class="item">
							<view class="title">总收益</view>
							<view class="info">
								<view class="left">{{TodayProfit | formatPrice}}</view>
								<view class="right">
									<image src="/static/images/index/icon_shouyi.png" class="r-img" mode="widthFix"></image>
								</view>
							</view>
						</view>
					</uni-col>
				</uni-row>
			</view>
		</view>
		<view class="myrecord">
			<view class="mainbox" v-for="(item,index) in mainboxlist" :key="index">
				<view class="orderbox">
					<view class="headtitle uni-ellipsis">{{item.name}}</view>
					<view class="flexCenter">
						<uni-row :gutter="10">
							<uni-col :span="7">
								<u-image width="100%" height="85px" :src="item.image" mode="aspectFill" :fade="true" duration="450"  error-icon="error-square"></u-image>
							</uni-col>
							<uni-col :span="17">
								<view class="product">
									<view class="top">{{item.introduce}}</view>
									<view class="center">
										<view class="title">价格: <text class="color_green">{{item.price}}</text></view>
										<view class="title">数量: <text class="color_orgin">{{item.total_issue}}</text></view>
										<view class="title">收益: <text class="num">{{item.hourly_income}}</text></view>
										<view class="title">周期: {{item.effective_time}}</view>
									</view>
								</view>
								<!-- <view class="bottom">{{item.introduce}}</view> -->
							</uni-col>
						</uni-row>
					</view>
					<view class="flexbottom">
						<view class="left">
							<button size="mini" class="subbtn" @click="submitForm(item.id,0)">法币购买</button>
						</view>
						<view class="right">
							<button size="mini" class="subbtn" @click="submitForm(item.id,1)">USDT购买</button>
						</view>
					</view>
				</view>
				<view class="status"></view>
			</view>
			<view class="statusbtn">
				<button @click="addmemberlist()">loading more grouping orders</button>
			</view>
		</view>
		
		<popu-modal v-model="value" :mData="defaultData" :type="type" @cancel="cancel" navMask></popu-modal>
	</view>
</template>

<script>
import loading from '@/utils/mixin/loading.js';

export default {
	mixins: [loading],
	data() {
		return {
			value:false,
			type:'default',
			defaultData:{content:'这是一个模态弹窗',cancelText:'cancel'},
			langflag:'',
			langshow:false,
			munelang:[],
			headbtn:'',
			bannerData: [],
			TodayProfit: 0,
			invitationid:'',
			currennum:0,
			memberInfo:[{imgsrc:'/static/images/index/icon_gaoji.png',navrouter:'/pages/profile/selectmode',name:"munelist5"},
									 {imgsrc:'/static/images/index/index_hy.png',navrouter:'/pages/product/index',name:"munelist6"},
									 {imgsrc:'/static/images/index/icon_zunxiang.png',navrouter:'/pages/profile/selectmode',name:"munelist1"},
									 {imgsrc:'/static/images/index/icon_daili.png',navrouter:'/pages/profile/withdrawal',name:"munelist2"}],
			bannerlist:[],
			broadmune:['恭喜234242432成为钻石会员','恭喜werwerwr成为白金会员','恭喜234242432成为黄金会员','恭喜234242432成为铂金会员'],
			mainboxlist:[]
		};
	},
	computed:{
		i18n () {  
		  return this.$t('tabIndex');  
		},
		i18ngarb(){
			return this.$t('grabindex');
		},
		tabbartxt(){
			return this.$t('tabbartxt');
		}
	},
	methods: {
		navTo(url) {
			uni.navigateTo({ url });
		},
		cancel(){
			this.value = false;
		},
		loadjson(){
			let that = this;
			this.$http.requestajx('index','get',{}).then((res) => {
				// console.log(res);
				let data = res.result;
				that.invitationid = data.id;
				that.TodayProfit = data.today_profit;
				// that.memberInfo = data.level.splice(0, 3);
				that.broadmune = data.user_level_notify;
			}).catch((error) => {
				console.log('错误重启');
			});
			this.$http.requestajx('banner','get',{}).then((res) => {
				// console.log(res)
				that.bannerlist = res.result.map(function(item,index){
					return item.image = that.$configurl.ossBaseUrl + item.image;
				});
			}).catch((error) => {
				console.log('错误重启');
			});
			this.$http.requestajx('financial_products','get',{}).then((res) => {
				that.mainboxlist = res.result.data;
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
		langlist(){
			this.langshow = !this.langshow;
		},
		munebtn(code){
			let that = this;
			this.$http.requestajx('country','get',{}).then((res) => {
				// console.log(res);
				let data = res.result;
				that.munelang = data;
				that.$i18n.locale = code;
				that.tabbartxt.map(function(item,index){
					uni.setTabBarItem({ index: index, text: item});
				});
				uni.setStorageSync('ajaxmess', that.$t('ajaxmess'));
				uni.setStorageSync('locale_key', code);
				that.langflag = data.find((item,index) => {
					return item.code == code
				});
				that.langshow = false;
			}).catch((error) => {
				console.log('错误重启',error);
			});
		},
		btn_href(url){
			// #ifdef H5
				let winhref = window.open('','_blank');
				winhref.location = url;
			// #endif
			// #ifdef APP-PLUS
				plus.runtime.openURL(url);
			// #endif
		},
		async kefu_btn(){
			// #ifdef H5
				let winhref = window.open('','_blank');
			// #endif
				let res = await this.$http.customerUrl();
			// #ifdef APP-PLUS
				plus.runtime.openURL(res.result)
			// #endif
			// #ifdef H5
				winhref.location = res.result;
			// #endif
		}
	},
	onShow() {
		this.loadjson();
		/* this.getTodayProfit();
		this.getUserLevel();
		this.getData(); */
		
	},
	onReady() {
		this.$http.requestajx('index/homePopUp','get',{}).then((res) => {
			this.defaultData.content = res.result;
			// this.tabMask = new TabMask({opacity:0.6})
			this.value = true;
			
		})
	},
	onLoad() {
		/* 设置语言 */
		this.munebtn(uni.getStorageSync('locale_key'));
		
		this.loadjson();
	}
};
</script>

<style lang="scss">
@font-face {
	font-family: youshe;
	src: url('~@/static/font/YouSheBiaoTiHei-2.ttf');
}
.color_green{ color: #0dc253!important;}
.color_orgin{ color: #ffa900!important;}
.head_pos{position: fixed;top: 0;left: 0;width: 100%;z-index: 999;background-color: $unified-color;color: #fff;
	.head_content{ padding-left: 20px;padding-right: 15px;width: 100%;height: 45px;display: flex;align-items: center;justify-content: space-between;}
	input{ height: 30px;border-radius: 30px;background-color: #FFFFFF;padding: 0 15px;text-align: center;color: #333333;box-sizing: border-box;}
	.head_btn{ height: 45px;line-height: 45px;padding-left: 15px;
		.icon-zy{ font-size: 16px;margin-right: 5px;}
	}
	.head_item:nth-of-type(1) {
	   flex: 1;
	}
	.head_kefu{ padding-left: 15px;
		image{ width: 20px;}
	}
}

.lang_flag{text-align: left;position: relative;
	image{ width: 25px;height: 16px;vertical-align: middle;margin-right: 5px;margin-bottom: 3px;}
}
.lang_list{position: absolute;top: 25px;left: 0;z-index: 99;transition: all .3s ease-in;opacity: 0;
		.list_after::after{
			content: "";position: absolute;top: 0;left: 10px;margin-top: -10px;
			    border-width: 5px;border-style: solid;border-color: transparent transparent #2e4f79 transparent;
		}
		.langul{background-color: #2e4f79;display: flex;flex-wrap: wrap;border-radius: 5px;overflow: hidden;}
		.lang_flag{text-align: left;color: #FFFFFF;width: 50%;padding: 10px;
			&.active{background-color: #2d486a;}
		}
		&.active{ opacity: 1;}
	}

.wrapper{ background-color: #f6f6f6;min-height: 100vh;}
.backwrite{ background-color: #FFFFFF;padding-bottom: 15px;}
.top-bgd {
	width: 100%;
	height: calc(var(--status-bar-height) + 120px);
	background: url(/static/images/index/pic_beijing.png) no-repeat;
	background-size: 100% 100%;
	background-position: 0 0;
}
.index_banner{ padding: 0 40rpx;margin-top: -120rpx;height: 280rpx;
	.banner_swiper{ height: 100%;border-radius: 10px;overflow: hidden;}
	.banner_content{ border-radius: 10px;overflow: hidden;width: 100%;height: inherit;
		.banner_image,.swiper-item{ height: 100%;}
		image,img{ display: block;width: 100%;border-radius: 10px;}
	}
}
.bannerBotice{ padding: 10px 0;}
.vip-classify {
	display: flex;
	justify-content: space-between;
	width: 100%;
	height: 140rpx;
	margin: 50rpx 0 55rpx 0;
	padding: 0 1px;
	.classify-item {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: space-between;
		width: 25%;
		height: 140rpx;
		.item_nav{ width: 100%;text-align: center;}
		uni-image {
			width: 90rpx;
			height: 90rpx;
		}
		.lazyimg{ width: 90rpx;display: inline-block;}
		uni-text {
			color: $unified-color;
			font-size: 26rpx;
		}
	}
}
.indexTable{ padding: 0 20px;
	.item{ background-color: $unified-color;border-radius: 40rpx;padding: 44rpx 10px 15px 34rpx;color: #fff;margin-bottom: 10px;
		.iconImg{ width: 39px;}
		.title{ margin-bottom: 10px;}
		.l-img { width: 98rpx;height: 56rpx;}
		.r-img { width: 78rpx;height: 56rpx;}
	}
	.info{ display: flex;align-items: center;justify-content: space-between;}
}

.c-item {
	display: flex;
	width: 680rpx;
	height: 156rpx;
	background-color: #ffffff;
	box-shadow: 0px 6rpx 32rpx 0px rgba(0, 35, 68, 0.14);
	border-radius: 34rpx;
	margin: 0 auto 32rpx;
	.item-l {
		display: flex;
		justify-content: center;
		align-items: center;
		flex: 2;
		.l-img {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 105rpx;
			height: 105rpx;
			border-radius: 50%;
			background-color: #ebebf3;
			image {
				width: 40rpx;
				height: 50rpx;
			}
		}
	}
	.item-c {
		padding-top: 10rpx;
		flex: 4;
		.c-title {
			color: #1a2564;
			line-height: 36rpx;
			font-size: 26rpx;

			text-overflow: -o-ellipsis-lastline;
			overflow: hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
		}
		.c-tag {
			display: flex;
			align-items: flex-end;
			margin: 10rpx 0;
			image {
				width: 19rpx;
				height: 19rpx;
				margin-right: 12rpx;
			}
			text {
				color: #ffbd55;
				font-size: 18rpx;
			}
		}
		.c-number {
			color: #1a2564;
			font-size: 18rpx;
			margin-top: 20rpx;
		}
	}
	.item-r {
		flex: 2;
		display: flex;
		align-items: center;
		justify-content: center;
		text {
			color: #fb5081;
			font-size: 24rpx;
		}
		image {
			width: 8rpx;
			height: 16rpx;
			margin-left: 20rpx;
		}
	}
}
.empty {
	text-align: center;
	margin-top: 450rpx;
	color: rgb(119, 119, 119);
	font-size: 30rpx;
}

.myrecord{ padding: 15px 10px;
	.mainbox{ background-color: #FFFFFF;border-radius: 8px;padding: 12px;position: relative;margin-bottom: 15px;
		.headtitle{ font-size: 32rpx;color: #005652;font-weight: 600;max-width: 70%;}
		.boxicon{ width: 90%;}
		.flexCenter{ margin-top: 10px;font-size: 24rpx;color: #a6c4c3;
			.bottom{ line-height: 40rpx;}
			.center{ display: flex;align-items: center;justify-content: space-between;flex: 0 0 100%;}
			.product{ display: flex;align-content: normal;flex-wrap: wrap;height: 85px;padding-left: 5px;}
		}
		.top{ margin-bottom: 10px;}
		.num{ color: #0dc253;font-weight: 700;font-size: 28rpx;margin-top: 5px;}
		.flexbottom{ display: flex;align-items: center;justify-content: space-between;margin-top: 10px;padding-top: 10px;border-top: 1px solid #eee;
			.right,.left{ background-image: linear-gradient(270deg,#f089e5,#f56285);border-radius: 10px;width: 100px;height: 35px;padding: 2px;
				.subbtn{ color: #f56285;width: 100%;height: 100%;line-height: 31px;text-align: center;display: inline-block;background-color: #FFFFFF;border-radius: 10px;font-size: 30rpx;font-weight: 500;padding: 0;}
				&.active{ filter: grayscale(100%);}
			}
			
		}
		.status{ position: absolute;right: -2px;top: -2px;width: 90px;height: 24px;background: url('/static/images/grab/index/info.png') no-repeat;background-size: 100%;}
	}
	
}
</style>
