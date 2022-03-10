<template>
	<view>
		<view class="input">
			<image src="/static/images/login_Img/icon_mima.png" style="width: 24rpx;height: 32rpx;"></image>
			<view class="line"></view>
			<input type="password" placeholder="输入新密码" style="font-size: 22rpx;" placeholder-style="font-size:30rpx;color:a3a3a3"/>
		</view>
		<view class="input" style="margin: 35rpx auto 100rpx;">
			<image src="../../static/images/login_Img/icon_mima.png" style="width: 24rpx;height: 32rpx;"></image>
			<view class="line"></view>
			<input type="password" placeholder="手机号" placeholder-style="font-size:30rpx;color:a3a3a3"/>
		</view>
		<view class="login_btn">修改密码</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		}
	}
</script>

<style lang="scss">
	.input{
		display: flex;
		align-items: center;
		background-color: #fafafa;
		border-radius: 40px;
		width: 540rpx;
		height: 80rpx;
		margin: 300rpx auto 0;
		image{
			width: 24rpx;
			height: 34rpx;
			margin-left: 26rpx;
			margin-right: 22rpx;
		}
		.line{
			width: 2rpx;
			height: 28rpx;
			background-color: #a9a9a9;
			margin-right: 33rpx;
		}
		uni-input{
			display: flex;
			align-items: center;
		}
		.getCode{
			width: 170rpx;
			height: 62rpx;
			background-color: $unified-color;
			border-radius: 31rpx;
			color: #fff;
			font-size: 26rpx;
			line-height: 62rpx;
			text-align: center;
			margin-left: 107rpx;
		}
	}
	.login_btn{
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: $unified-color;
		border-radius: 40px;
		width: 540rpx;
		height: 80rpx;
		margin: 35rpx auto 43rpx;
		color: #fff;
		font-size: 30rpx;
	}
</style>
