<template>
	<view class="bgd">
		<uni-load-more status="loading"></uni-load-more>
	</view>
</template>

<script>
</script>

<style lang="scss">
	.bgd {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		font-size: 30rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 10000;
		background-color: #fff;
		color: $unified-color;
	}
</style>
