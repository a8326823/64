<template>
	<view class="c-item" @click="toDetail">
		<view class="item-l">
			<view class="l-img">
				<image src="/static/images/index/icon_douyin.png" mode=""></image>
			</view>
		</view>
		<view class="item-c">
			<view class="c-title">{{taskList.title}}</view>
			<view class="c-tag">
				<image src="/static/images/index/icon_huangguan.png" mode=""></image>
				<text>{{taskList.level_info.name}}</text>
			</view>
			<view class="c-number">剩余数量： {{taskList.remaining_quantity}}</view>
		</view>
		<view class="item-r">
			<text>{{taskList.amount | formatPrice}}</text>
			<image src="/static/images/index/icon_big_xiala.png" mode=""></image>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {};
	},
	props:{
		taskList: {
			type: Object,
			defau: {}
		}
	},
	methods: {
		toDetail () {
			uni.navigateTo({
				url: '/pages/index/task-detail?id='
			})
		}
	},
	onLoad() {
		console.log(this.taskList);
	}
};
</script>

<style lang="less">
.c-item {
	display: flex;
	width: 680rpx;
	height: 156rpx;
	background-color: #ffffff;
	box-shadow: 0px 6rpx 32rpx 0px rgba(0, 35, 68, 0.14);
	border-radius: 34rpx;
	margin:0 auto 32rpx;
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
</style>
