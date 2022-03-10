<template>
	<view class="uni-padding-wrap">
		<swiper class="swiper" :indicator-active-color="activeColor" :indicator-dots="indicatorDots" :autoplay="autoplay"
		 :interval="interval" :duration="duration" :circular="circular">
			<swiper-item v-for="(item,index) in bannerData" :key="index">
				<view class="swiper-item" style="height: 100%;">
					<image :src="item.image" mode=""></image>
				</view>
			</swiper-item>
		</swiper>
	</view>
</template>

<script>
	export default {
		props: {
			bannerData: Array
		},
		data() {
			return {
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
				circular: true,
				activeColor: "#FFF"
			}
		},
	}
</script>

<style lang="scss">
	swiper {
		height: 360upx;
		box-shadow: 2rpx 10rpx 9rpx 1px rgba(0, 125, 202, 0.1);
		border-radius: 10rpx;
		overflow: hidden;
		margin: 30upx auto 0;
	}

	swiper-item {
		height: 360upx; //这里可以设置比上面高度小（留出大标语位置）或者设置一样大
	}

	image {
		width: 710rpx;
		height: 360rpx;
	}

	.uni-padding-wrap {
		width: 710upx;
		padding: 0;
		margin: 0 auto;
	}
</style>
