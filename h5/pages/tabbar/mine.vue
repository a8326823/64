<template>
	<view class="profile_back header_top">
		<loading-plus v-if="beforeReady"></loading-plus>
		<view class="head_pos">
			<view class="status_bar"></view>
			<view class="head_content">
				<view class="head_title">{{i18n.title}}</view>
				<view class="head_flex">
					<view class="head_left">
					</view>
					<view class="head_btn">
					   <image src="/static/images/tabbar/kefu.png" mode="widthFix" @click="kefu_btn"></image>
					</view>
				</view>
			</view>
		</view>
		<view class="header">
			<view class="header-info">
				<image src="/static/images/login_Img/logo.png" mode="" class="avatar"></image>
				<view class="info-right">
					<!-- <view class="r-title">{{ info.nickname }}<text>￥：{{info.balance}}</text> </view> -->
					<view class="r-code">
						<view class="code-t1">{{ info.nickname }}</view>
						<view class="code-t2">
							{{info.balance | formatPrice}}
						</view>
					</view>
					<!-- <view class="r-title">
						{{i18n.codetitle}} {{ info.id }}
					</view> -->
					<view class="r-title">
						<image src="/static/images/profile/icon_vip.png" mode="" class="head_image"></image>
						<text>{{ info.level_name }}</text>
					</view>
					
				</view>
				
			</view>
			<!-- <view class="header_date">{{i18n.timedate}}：{{info.effective_time}}</view> -->
		</view>
		<view class="withdraw_content">
			<view class="detailed">
				<view class="detailed-item" v-for="(item,index) in teamlist" :key="index">
					<text class="number">{{item.name}}</text>
					<text class="text">{{item.title}}</text>
				</view>
			</view>
			<view class="withdraw_list">
				<view @click="navTo('/pages/profile/withdrawal')" class="withdraw_item">{{i18n.munebtn}}</view>
				<view @click="navTo('/pages/profile/selectmode')" class="withdraw_item">{{i18n.munerecharge}}</view>
			</view>
		</view>

		<!-- VIP -->
		<view class="bd-vip" @click="navTo('/pages/profile/help-center')">
			转介绍
			<!-- <view class="vip-left">
				<image src="/static/images/profile/btn_vip1.png" mode="widthFix"></image> <text>{{i18n.viptxt}}</text>
				<view class="uni-ellipsis vip-txt">{{i18n.viptitle}}</view>
			</view>
			<view class="vip-right" @click="navTo('/pages/profile/open-member')">{{i18n.vipbtn}}</view> -->
		</view>

		<view class="task-status">
			<view class="task-item" @click="navTo('/pages/team/profit')">
				<image src="/static/images/profile/icon_jinxing.png" />
				<view><text>团队收益</text></view>
			</view>
			<view class="task-item">
				<image src="/static/images/profile/icon_shenhe.png" @click="navTo('/pages/team/rebate')" />
				<view><text>奖励收益</text></view>
			</view>
			<view class="task-item" @click="navTo('/pages/profile/success-task?type='+2)">
				<image src="/static/images/profile/icon_fin.png" />
				<view><text>团队人数</text></view>
			</view>
		</view>
		<view class="list">
			<view class="profile_list">
				<!-- <block v-for="(item,index) in i18n.navlist" :key="index">
					<view class="list_item"  @click="navTo(navlistjson[index].router_url)">
						<view><image :src="navlistjson[index].imgsrc"></image></view>
						<view class="title uni-ellipsis"><text>{{item}}</text> </view>
						<view class="item_icon">
							<uni-icons :size="20" class="uni-icon-wrapper" color="#bbb" type="arrowright" />
						</view>
					</view>
				</block> -->
				<block v-for="(item,index) in navlistjson" :key="index">
					<view class="list_item" @click="navTo(item.router_url)">
						<view><image :src="item.imgsrc"></image></view>
						<view class="title uni-ellipsis"><text>{{item.title}}</text> </view>
						<view class="item_icon">
							<uni-icons :size="20" class="uni-icon-wrapper" color="#bbb" type="arrowright" />
						</view>
					</view>
				</block>
			</view>
		</view>
		<view class="exit-login" @click="exit">{{i18n.navbtn}}</view>
	</view>
</template>

<script>
import config from '@/service/config.js'
import loading from '@/utils/mixin/loading.js';
export default {
	mixins: [loading],
	data() {
		return {
			info: {
				avatar: ''
			},
			navlistjson:[{title:"个人信息",name:"navlist1",imgsrc:'/static/images/profile/icon_@2.png',router_url:'/pages/profile/personal-information'},
					{title:"我的账单",name:"navlist2",imgsrc:'/static/images/profile/icon_01_@2.png',router_url:'/pages/profile/my-bill'},
					{title:"我的团队",name:"navlist3",imgsrc:'/static/images/profile/icon_01_@2.png',router_url:'/pages/profile/my-team'},
					{title:"修改提款密码",name:"navlist4",imgsrc:'/static/images/profile/icon_04_@2.png',router_url:'/pages/profile/change-password'},
					{title:"礼品兑换",name:"navlist5",imgsrc:'/static/images/profile/icon_gift.png',router_url:'/pages/profile/my-bill'},
					{title:"我的产品",name:"navlist6",imgsrc:'/static/images/profile/paycode.png',router_url:'/pages/profile/my-bill'}],
			teamlist: [{title:"团队收益",name:"11"},{title:"任务收益",name:"11"},{title:"团队人数",name:"11"}]
		};
	},
	computed: {
		i18n(){
			return this.$t('pagesprofile');
		},
		i18nyue(){
			return this.$t('orderyuebao');
		},
		i18nbtn(){
			return this.$t('btncomfirm');
		}
	},
	methods: {
		navTo(url) {
			console.log(url)
			if(url === '/pages/profile/withdrawal') {
				// console.log(this.info)
				if(!this.info.info){
					uni.showModal({
						title:'',
						content: this.i18n.navmodal[0],
						confirmText:this.i18n.navmodal[1],
						cancelText:this.i18nbtn[0],
						success(res) {
							if(res.confirm) {
								uni.navigateTo({
									url: '/pages/profile/account'
								})
							}
						}
					})
					return
				}else {
					return uni.navigateTo({ url });
				}
			}
			uni.navigateTo({ url });
		},
		async getAccountInfo() {
			let res = await this.$http.accountInfo();
			// console.log(res);
			this.info = res.result;
			this.$store.commit('changeInfo', res.result);
		},
		async getAccountIndex() {
			let res = await this.$http.accountIndex();
			const data = res.result;
			this.accountIndex = [data.team_profit,data.task_profit,data.team_count];
		},
		exit() {
			uni.showModal({
				title: '',
				content: this.i18n.exitmodal,
				cancelText:this.i18nbtn[0],
				confirmText:this.i18nbtn[1],
				success(res) {
					if (res.confirm) {
						uni.removeStorageSync("userToken")
						uni.reLaunch({
							url:'/pages/login/pwd-login'
						})
					}
				}
			});
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
		this.getAccountInfo();
		// this.getAccountIndex();
	}
};
</script>

<style lang="scss">
.profile_back{ background-color: #f5f7fd;}
.header_top{
	/* #ifdef H5 */
		padding-bottom: 70px;
	/* #endif */
}
.head_pos{ position: fixed;top: 0;left: 0;width: 100%;z-index: 999;background-color: $unified-color;}
	.head_content{ color: #FFFFFF;position: relative;
		 .head_flex{ position: absolute;left: 0;top: 0;height: 45px;width: 100%;display: flex;justify-content: space-between;align-items: center;padding: 0 15px;
			.icon-zy{ font-size: 16px;margin-right: 5px;}
			.head_btn{ 
				image{ width: 20px;}
			}
		 }
		 .head_title{ text-align: center;font-size: 36rpx;height: 45px;line-height: 45px;}
	}

.hover-btn {
	transform: translate(1rpx, 1rpx);
}
.profile_list{ background-color: #FFFFFF;flex-direction: column;display: flex;justify-content: space-between;
	.list_item{ display: flex;justify-content: space-between;flex-direction: row;padding: 12px 15px;align-items: center;
		image{ width: 20px;height: 20px;}
		.title{ flex:1;padding-left: 15px;
			text{ font-size: 30rpx;}
		}
	}
}
.header {
	width: 100%;
	height: 334rpx;
	background: url(/static/images/profile/pic.png) no-repeat;
	background-position: 0 0;
	background-size: 100% 100%;
	.header-info {
		display: flex;
		align-items: center;
		margin-left: 50rpx;
		padding-top: 58rpx;
		.head_image{width: 15px;height: 15px;margin-right: 5px;vertical-align: middle;margin-bottom:3px;}
		.avatar {
			width: 125rpx;
			height: 125rpx;
			border-radius: 50%;
		}
		.info-right {
			display: flex;flex: 1;
			flex-direction: column;
			justify-content: center;
			margin-left: 20rpx;padding-right: 50rpx;
			.r-title {
				font-size: 28rpx;margin-top: 6px;
				color: #fff;
				text{ font-size: 24rpx;padding-left: 3px;}
			}
			.r-code {
				display: flex;
				align-items: center;
				justify-content: space-between;
				font-size: 26rpx;
				color: #fff;
				.code-t2 {
					display: flex;
					align-items: flex-end;
					image {
						width: 30rpx;
						height: 30rpx;
						margin: 0 10rpx 0 40rpx;
					}
				}
			}
		}
	}
	.header_date{ font-size: 30rpx;color: #FFFFFF;text-align: center;padding-top: 15px;}
}
.withdraw_content{ background-color: #ffffff;margin: -54rpx auto 0;border-radius: 20rpx;width: 680rpx;overflow: hidden;}
.withdraw_list{ border-top: 1px solid #f3f3f3;display: flex;
	.withdraw_item{ width: 50%;text-align: center;padding: 10px 0;color: #999;
		&+.withdraw_item{ border-left: 1px solid #f3f3f3;background-color: $unified-color;color: #FFFFFF;}
	}
}
.detailed {
	display: flex;
	align-items: center;
	height: 130rpx;
	.detailed-item {
		position: relative;
		flex: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		color: #999;
		padding: 0 10px;text-align: center;
		.number {
			font-size: 28rpx;
		}
		.text {
			font-size: 22rpx;
			margin-top: 18rpx;
		}
	}
	.detailed-item:nth-child(-n + 3)::after {
		content: '';
		position: absolute;
		right: 0;
		width: 2rpx;
		height: 22rpx;
		background-color: #ddd;
	}
	.detailed-item-active {
		flex: 1.3;
		display: flex;
		flex-direction: column;
		align-items: center;
		.item-btn {
			width: 140rpx;
			height: 58rpx;
			line-height: 58rpx;
			text-align: center;
			font-size: 26rpx;
			color: #fff;
			background-color: $unified-color;
			border-radius: 29rpx;
		}
	}
}

.bd-vip {
	width: 700rpx;
	height: 128rpx;
	border-radius: 15rpx 15rpx 0px 0px;
	opacity: 0.95;
	background: url(../../static/images/profile/pic_vip.png) no-repeat;
	background-size: 100% 100%;
	margin: 18rpx auto 0;
	padding: 0 32rpx;
	display: flex;
	align-items: center;
	color: #FFFFFF;
	font-size: 18px;
	justify-content: center;
	.vip-left {
		max-width: 70%;
		image {
			width: 20px;
			margin-top: 24rpx;
		}
		text{ color:#FFFFFF;font-size: 17px;margin-left: 5px;font-style:oblique;}
		.vip-txt{
			margin-top: 18rpx;
			color: #b0b1b9;
			font-size: 20rpx;
		}
	}
	.vip-right {
		display: flex;
		justify-content: center;
		align-items: center;
		padding: 0 5px;
		height: 58rpx;
		background-color: #ecd8ae;
		border-radius: 15rpx;
		color: #2f3133;
		font-size: 24rpx;
		margin-top: 28rpx;
	}
}

.task-status {
	display: flex;
	align-items: center;
	width: 678rpx;
	height: 150rpx;
	background-color: #ffffff;
	border-radius: 20rpx;
	margin: 10rpx auto 12rpx;
	
	.task-item {
		flex: 1;
		text-align: center;
		image {
			width: 81rpx;
			height: 81rpx;
		}
		text {
			font-size: 26rpx;
			color: #333333;
		}
	}
}

.invitation {
	display: flex;
	align-items: center;
	width: 100%;
	height: 142rpx;
	background-color: #ffffff;
	margin-top: 12rpx;
	image {
		width: 94rpx;
		height: 88rpx;
		margin-left: 43rpx;
	}
	.center {
		flex: 1;
		display: flex;
		flex-direction: column;
		margin-left: 24rpx;
		.title {
			color: #333333;
			font-size: 34rpx;
		}
		.subtitle {
			font-size: 26rpx;
			color: #999;
			margin-top: 14rpx;
		}
	}
	.right {
		color: #47b9eb;
		font-size: 26rpx;
		margin-right: 54rpx;
	}
}

.list {
	margin-top: 14rpx;
}
.exit-login {
	display: flex;
	justify-content: center;
	align-items: center;
	width: 100%;
	height: 92rpx;
	color: #f98166;
	font-size: 30rpx;
	margin-top: 20rpx;
	background-color: #ffffff;
	margin-top: 20rpx;
}
</style>
