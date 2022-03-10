<template>
	<view class="header_top">
		<loading-plus v-if="isLoad"></loading-plus>
		<public-head :title="i18n.title" v-on:initbtn="btnsign" :btntxt="i18n.titlebtn"></public-head>
		<view class="progress">
			<view class="progress-status" @click="navTo('/pages/profile/credit-list')">
				<view class="p-content">
					<view style="font-size:24rpx;">{{i18n.info}}</view>
					<view style="font-size:86rpx;font-weight: bold;margin: 10rpx 0 30rpx 0;">{{ info.credit }}</view>
					<view style="font-size:24rpx;" class="status">{{i18n.infotext}}</view>
				</view>
			</view>
			<view class="line">
				<view :class="['line-item-2', index === currentIndex ? 'active' : '']" v-for="(itme, index) in 5" :key="index"></view>
			</view>
			<view class="number">
				<text>0</text>
				<text>300</text>
				<text>500</text>
				<text>700</text>
				<text>1000</text>
				<text>1500</text>
			</view>
		</view>
		<view class="rule">
			<rich-text :nodes="content"></rich-text>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			info:'',
			percent: 0,
			currentIndex:0,
			content:'',
			isLoad:true
		};
	},
	methods: {
		navTo(url) {
			uni.navigateTo({ url });
		},
		async getCreditRule() {
			let res = await this.$http.creditRule()
			// console.log(res);
			this.content = res.result
			this.getAccountInfo();
			let credit = this.info.credit
			if(credit<=300){
				this.currentIndex = 0
			} else if(credit<=500&&credit>300) {
				this.currentIndex = 1
			} else if(credit>500 && credit<= 700) {
				this.currentIndex = 2
			} else if(credit>700 && credit<= 1000) {
				this.currentIndex = 3
			} else if(credit>1000 && credit<= 1500) {
				this.currentIndex = 4
			}
		},
		async getAccountInfo() {
			let res = await this.$http.accountInfo();
			this.info = res.result;
		},
		async btnsign(){
			await this.$http.signIn();
			this.totat(this.i18n.totatinfo);
			this.getCreditRule();
		}
	},
	computed:{
		i18n(){
			return this.$t('mycredit');
		}
	},
	onLoad() {
		this.getCreditRule();
	},
	onShow() {
		setTimeout(()=>{
			this.isLoad = false
		},1000)
	}
};
</script>

<style lang="less">
page {
	border-top: 4rpx solid #f5f7fd;
}
.progress {
	width: 100%;
	height: 660rpx;
	border-bottom: 12rpx solid #f5f7fd;
	.progress-status {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 460rpx;
		height: 460rpx;
		border-radius: 50%;
		background: url('/static/images/profile/pic_xinyong.png') no-repeat;
		background-size: 100% 100%;
		margin: 70rpx auto 80rpx;
	}
	.p-content {
		width: 320rpx;
		height: 320rpx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		box-shadow: 0px 5rpx 30rpx 0px rgba(228, 193, 129, 0.5);
		border-radius: 50%;
		color: #c4a365;
		.status {
			position: relative;
			width: 100%;
			text-align: center;
			&::after {
				content: '';
				position: absolute;
				right: 40rpx;
				top: 0;
				bottom: 0;
				margin: auto;
				width: 40rpx;
				height: 4rpx;
				background-color: #c4a365;
				opacity: 0.5;
			}
			&::before {
				content: '';
				position: absolute;
				left: 40rpx;
				top: 0;
				bottom: 0;
				margin: auto;
				width: 40rpx;
				height: 4rpx;
				background-color: #c4a365;
				opacity: 0.5;
			}
		}
	}
	.number {
		display: flex;
		justify-content: space-between;
		width: 610rpx;
		margin: 0 auto;
		color: #787777;
		font-size: 28rpx;
	}
	.line {
		display: flex;
		justify-content: space-between;
		align-items: center;
		width: 610rpx;
		height: 50rpx;
		margin: 0 auto 0;
		.line-item-2 {
			width: 108rpx;
			height: 30rpx;
			background-color: #e9e9e9;
			opacity: 0.7;
		}
		.line-item-2:nth-child(1) {
			border-radius: 15rpx 0px 0px 15rpx;
		}
		.line-item-2:last-child {
			border-radius: 0px 15rpx 15rpx 0px;
		}
		.active {
			height: 38rpx;
			background-image: linear-gradient(#debc7d, #debc7d), linear-gradient(#ffd401, #ffd401);
		}
	}
}
.rule {
	margin-top: 50rpx;
	margin-left: 30rpx;
}
</style>
