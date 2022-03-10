<template>
	<view class="header_top">
		<public-head :title="i18n.title"></public-head>
		<view class="input-list">
			<view class="list-item">
				<text class="name">{{i18n.name}}</text>
				<view class="value">{{result.name}}</view>
			</view>
			<view class="list-item">
				<text class="name">{{i18n.pricle}}</text>
				<text class="value">{{result.amount | formatPrice}}</text>
			</view>
			<view class="list-item">
				<text class="name">{{i18n.statetask}}</text>
				<text class="value">{{i18n.stateinfo}}</text>
			</view>
		</view>
		<view class="upload">
			<view class="upload-title">{{i18n.uploadimg}}</view>
			<view class="pic">
				<view class="pic1" @click="uploadImg">
					<image src="/static/images/profile/icon_camera.png" ></image>
					<text>{{i18n.uploadimg1}}</text>
				</view>
				<view class="pic2" v-show="imageshow" :style="{'background-image':'url('+img2+')'}">
					<image src="/static/images/profile/icon_guanbi.png"  class="del" @click="delImg"></image>
				</view>
			</view>
		</view>
		<button class="btn" @click="submitTask">{{i18n.subbtn}}</button>
	</view>
</template>

<script>
	import config from '@/service/config.js'
	export default {
		data() {
			return {
				result:'',
				imageshow:false,
				img1:'',
				img2:''
			};
		},
		computed:{
			i18n(){
				return this.$t('submittask');
			}
		},
		methods:{
			async submitTask() {
				let res = await this.$http.taskSubmit({
					id:this.result.id,
					image:this.img1
				})
				this.totat(this.i18n.modelsucc);
				setTimeout(()=>{
					uni.navigateBack({
						delta:2
					})
				},1500)
			},
			uploadImg() {
				let that = this;
				uni.chooseImage({
					count: 1,
					sizeType: ['compressed'], 
					sourceType: ['album'],
				    success: (res) => {
				        const tempFilePaths = res.tempFilePaths,
								filesize = res.tempFiles[0].size / 1024 / 1024;
								if(filesize > 10){
									that.totat(that.i18n.modelinfo);
									return false;
								}
						 uni.showLoading({mask:true});
						that.$http.uploadajax('upload',tempFilePaths[0],{},'image').then((res) => {
							// console.log(res);
							that.img1 = res.result.uri;
							that.img2 = res.result.full_uri;
							that.imageshow = true;
							uni.hideLoading(); 
							
						});
				    }
				});
				   
				   
			},
			delImg() {
				this.img2 = ""
				this.img1 = ""
				this.imageshow = false;
			}
		},
		onLoad(options) {
			this.result = options
		}
	}
</script>

<style lang="less">
.input-list {
	width: 100%;
	padding: 0 30rpx;
	border-top: 10rpx solid #f5f7fd;
	.list-item {
		display: flex;
		align-items: center;
		width: 100%;
		height: 100rpx;
		border-bottom: 2rpx solid #e6e6e6;
		.name {
			color: #333333;
			font-size: 30rpx;
		}
		.value {
			width: 400rpx;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			color: #b3b3b3;
			font-size: 28rpx;
			margin-left: 36rpx;
		}
		&:last-child {
			border-bottom-color: #FFFFFF;
		}
	}
}
.upload {
	border-top: 10rpx solid #f5f7fd;
	padding: 30rpx;
	color:#333;
	font-size: 30rpx;
	.pic {
		width: 600rpx;position: relative;
		height: 338rpx;
		background-color: #ffffff;
		border: dashed 1px #cccccc;
		margin: 30rpx auto 0;
		.pic1 {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			width: 600rpx;
			height: 338rpx;
			image {
				width: 86rpx;
				height: 70rpx;
			}
			text {
				color: #debc7d;
				font-size: 30rpx;
				margin-top: 32rpx;
			}
		}
		.pic2 {
			position: absolute;left: 0;top: 0;z-index: 99;
			width: 100%;
			height: inherit;
			background-size: 100% 100%;
			image{
				width: 596rpx;
				height: 334rpx;
			}
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
		}
	}
}
.btn {
	margin-top: 300rpx;
}
</style>
