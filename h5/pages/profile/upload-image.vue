<template>
	<view>
		<input type="text" placeholder="请输入订单账号" v-model="trade_no"/>
		<view class="tip-text">上传截图(付款成功后，别忘了截图上传哦)</view>
		<view class="upload-img">
			<image :src="pic" class="add" @click="changeAvatar" ></image>
		</view>
		<button  class="btn" @click="upload">上传</button>
	</view>
</template>

<script>
	import config from '@/service/config.js'
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				img:'',
				trade_no:''
			};
		},
		methods:{
			async changeAvatar() {
				uni.chooseImage({
					success:async chooseImageRes => {
						const tempFilePaths = chooseImageRes.tempFilePaths;
						let result = await this.$http.uploadFile(tempFilePaths[0],{})
						this.img = result.result.uri
					}
				})
			},
			async upload() {
				let res = await this.$http.manual({
					level:this.level,
					trade_no:this.trade_no,
					image:this.img
				})
				this.totat("上传成功！")
				setTimeout(()=>{
					uni.switchTab({
						url:'/pages/tabbar/profile'
					})
				},1500)
			}
		},
		computed:{
			pic() {
				return this.img === '' ? '/static/images/profile/btn_add.png': config.ossBaseUrl+ this.img
			},
			...mapState(['level'])
		}
	}
</script>

<style lang="less">
input {
	width: 690rpx;
	height: 88rpx;
	background-color: #ffffff;
	border-radius: 44rpx;
	border: solid 1px #cccccc;
	margin: 122rpx auto 0;
	padding-left: 50rpx;
	color: #bdbdbd;
	font-size: 26rpx;
	box-sizing: border-box;
}
.tip-text {
	color: #616161;
	font-size: 26rpx;
	margin: 122rpx 0 30rpx 30rpx;
}
.upload-img {
	display: flex;
	align-items: center;
	width: 690rpx;
	height: 180rpx;
	margin-left: 30rpx;
	overflow: hidden;
	.add {
		width: 150rpx;
		height: 150rpx;
		border-radius: 10rpx;
		border: solid 2rpx #dbdbdb;
	}
	.img {
		width: 150rpx;
		height: 150rpx;
		position: relative;
		border-radius: 10rpx;
		margin-left: 18rpx;
		.del {
			position: absolute;
			top: -14rpx;
			right: -14rpx;
			width: 30rpx;
			height: 30rpx;
			z-index: 10;
			background-color: #fff;
			border-radius: 50%;
		}
		.img-item {
			width: 100%;
			height: 100%;
			border-radius: 10rpx;
		}
	}
}
.btn {
	margin-top: 400rpx;
}
</style>
