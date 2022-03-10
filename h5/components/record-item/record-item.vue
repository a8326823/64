<template>
	<view class="list-item">
		<view class="item" @click="itemClick" :style="{'background-image' : 'url(' + ( index %2?img1 :img2) + ')','background-size':'100% 100%','background-repeat':'no-repeat','background-position':'0 0'}">
			<slot name="item"></slot>
		</view>
		<view :class="['item-btn', isClick ? 'active' : '']">
			<slot name="btn"></slot>
		</view>
	</view>
</template>

<script>
export default {
	props: {
		index:{
			type:Number,
			default:0,
		}
	},
	data() {
		return {
			isClick: false,
			img1:'/static/images/profile/pic_red.png',
			img2:'/static/images/profile/pic_blue.png'
		};
	},
	methods: {
		itemClick() {
			this.isClick = !this.isClick;
		}
	}
};
</script>

<style lang="less">
.list-item {
	margin-bottom: 32rpx;
	.item {
		display: flex;
		flex-direction: column;
		padding-left: 70rpx;
		width: 698rpx;
		height: 189rpx;
		background-color: #ffffff;
		box-shadow: 0px 4rpx 24rpx 0px rgba(28, 28, 28, 0.21);
		border-radius: 6rpx;
		margin: 0 auto;
	}
	.item-btn {
		display: flex;
		width: 698rpx;
		height: 0;
		overflow: hidden;
		transition: height 0.3s;
		margin: 0 auto;
	}
	.active {
		height: 120rpx;
		transition: height 0.3s;
	}
}
</style>
