<template>
	<view class="header_top">
		<public-head :title="i18n.title"></public-head>
		<view class="modify_content">
			<view class="tip-text">{{i18n.forminfo}}</view>
			<view class="wrap"><input type="text" v-model="name" :placeholder="i18n.inputplace" placeholder-style="color: #bfbfc5;font-size:28rpx;" /></view>
			<view class="modify_btn">
				<button @click="formsubmit">{{i18n.formbtn}}</button>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			name: ''
		};
	},
	computed:{
		i18n(){
			return this.$t('modify_name');
		}
	},
	methods:{
		async formsubmit(){
			await this.$http.accountNickname({
				nickname: this.name
			});
			this.totat(this.i18n.totainfo);
			setTimeout(() => {
				uni.hideToast();
				uni.navigateBack();
			}, 1500);
		}
	}
};
</script>

<style lang="scss">
page {
	background-color: #f7f5fd;
}
.modify_content{ padding-top: 30px;}
.modify_btn{ position: absolute;left: 0;right: 0;bottom: 125px;padding: 0 30px;
	button{ width: 100%;height: 45px;line-height: 45px;background-color: $unified-color;border-radius: 22px; color: #fff;font-size: 15px;}
}
.wrap {
	display: flex;
	align-items: center;
	width: 750rpx;
	height: 112rpx;
	padding-left: 36rpx;
	background-color: #ffffff;
}
.tip-text {
	color: #afafaf;
	font-size: 28rpx;
	margin: 18rpx 0 16rpx 34rpx;
}
</style>
