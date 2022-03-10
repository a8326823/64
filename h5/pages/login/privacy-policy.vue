<template>
	<view style="padding: 30rpx;">
		<rich-text :nodes="content"></rich-text>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				content:""
			};
		},
		methods: {
			async getUsageAgreement() {
				let res = await this.$http.privacyPolicy()
				this.content = res.result
			}
		},
		onLoad() {
			this.getUsageAgreement()
		}
	}
</script>

<style lang="less">

</style>
