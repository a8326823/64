<template>
	<view>
		<view class="wrap">
			<view class="header">
				<image src="/static/images/profile/qiandai.png" style="width: 34rpx;height: 36rpx;margin-right: 26rpx;"></image>
				<text>二维码支付</text>
			</view>
			<view class="content">
				<text class="title">扫二维码向我付款</text>
				<image :src="img" style="width: 430rpx;height: 644rpx;"></image>
			</view>
		</view>
		<button class="btn" @click="navTo('/pages/profile/upload-image')">已支付，上传账单信息</button>
	</view>
</template>

<script>
	import config from '@/service/config.js'
	export default {
		data() {
			return {
				img:''
			};
		},
		methods:{
			navTo(url){uni.navigateTo({url})},
			async getCodePic() {
				let res = await this.$http.codePic()
				// console.log(res);
				this.img = config.ossBaseUrl + res.result.image
			}
		},
		onLoad() {
			this.getCodePic()
		}
	}
</script>

<style lang="scss">
page {
	background-color: #ffc000;
}
.wrap {
	width: 710rpx;
	height: 965rpx;
	background-color: #ffffff;
	border-radius: 24rpx;
	margin: 0 auto;
	overflow: hidden;
	.header {
		display: flex;
		align-items: center;
		height: 116rpx;
		padding-left: 47rpx;
		background-color: #f8f8f8;
		color: #ffc000;
		font-size: 30rpx;
	}
	.content {
		display: flex;
		flex-direction: column;
		align-items: center;
		.title {
			color: #373737;
			font-size: 28rpx;
			margin: 68rpx 0 28rpx;
		}
	}
}
.btn {
	width: 690rpx;
	margin-top: 100rpx;
	background-color: #fff;
	color: $unified-color;
}
</style>
