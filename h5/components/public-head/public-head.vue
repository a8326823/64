<template>
	<view class="head_pos" :class="{active:headshow}" :style="{'background-color':backcolor,'color':color_icon}">
		<view class="status_bar"></view>
		<view class="head_content">
			<view class="head_title">{{title}}</view>
			<view class="head_flex">
				<view class="head_left">
					<navigator open-type="navigateBack" v-show="backicon">
						<text class="alicon back_icon"></text>
					</navigator>
				</view>
				<view class="head_btn">
				   <text @click="btn_right" v-if="btntxt">{{btntxt}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default{
		props:{
			headshow:{
				type:Boolean,
				default:false
			},
			backcolor:{
				type:String,
				default:'#FFFFFF'
			},
			color_icon:{
				type:String,
				default:'#333'
			},
			title:{
				type:String
			},
			btntxt:{
				type:String,
				default:''
			},
			backicon:{
				type:Boolean,
				default:true
			}
		},
		methods:{
			btn_right(){
				this.$emit('initbtn');
			}
		}
	}
	
</script>

<style lang="less">
	@font-face {
	    font-family: headicon;
	    src: url('~@/static/font/iconfont1.ttf');
	}
	.alicon{ 
		 font-family: "headicon" !important;
	    font-size: 27px;
	    font-style: normal;
	}
	.back_icon::before {
	  content: "\f0292";
	}
	.icon-zy::before {
	  content: "\e620";
	}
	
	.head_pos{ position: fixed;top: 0;left: 0;width: 100%;z-index: 999;
		&.active{ border-bottom: 2px solid #f7f9fc;}
	}
	.head_content{ position: relative;
		 .head_flex{ position: absolute;left: 0;top: 0;height: 45px;width: 100%;display: flex;justify-content: space-between;align-items: center;padding: 0 10px;
			.icon-zy{ font-size: 16px;margin-right: 5px;}
		 }
		 .head_title{ text-align: center;font-size: 36rpx;height: 45px;line-height: 45px;}
	}
	
</style>
