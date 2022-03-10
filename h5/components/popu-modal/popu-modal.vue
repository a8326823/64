<template>
	<view class="mask" :style="{backgroundColor:value?`rgba(0,0,0,${opacity})`:'rgba(0,0,0,0)'}" :class="!value?'':'mask-show'">
		<view class="modal_content">
			<block v-if="type=='default'">
				<view class="default-view">
					<!-- <view class="title">{{mData.title}}</view> -->
					<view class="overflow_content">
						<view class="overflw_scroll">
							<view class="content word-break">{{mData.content}}</view>
						</view>
					</view>
					<view class="modalbtn">
						<view class="cancel" @tap="tapCancel" :style="{color:mData.cancelColor?mData.cancelColor:''}">{{i18n[1]}}</view>
						<!-- <view class="confirm" @tap="tapConfirm" :style="{color:mData.confirmColor?mData.confirmColor:''}">{{mData.confirmText?mData.confirmText:'确认'}}</view> -->
					</view>
				</view>
			</block>
			<block v-if="type=='custom'">
				<view class="default-view" @tap.stop>
					<view class="title">{{mData.title}}</view>
					<view class="procontent word-break">
						<view class="progress-box">
							<progress :percent="mData.updateprogres" show-info stroke-width="3" />
						</view>
					</view>
					<view class="modalbtn">
						<view class="cancel" @tap="tapCancel" :style="{color:mData.cancelColor?mData.cancelColor:''}">{{i18n[1]}}</view>
						<!-- <view class="confirm" @tap="tapConfirm" :style="{color:mData.confirmColor?mData.confirmColor:''}">{{mData.confirmText?mData.confirmText:'确认'}}</view> -->
					</view>
				</view>
			</block>
			<block v-if="type=='modalinfo'">
				<view class="default-view" @tap.stop>
					<view class="procontent word-break">
						<view class="modal_info">
							{{mData.content}}
						</view>
					</view>
					<view class="modalbtn">
						<view class="confirm active_close" @tap="tapCancel">{{i18n[0]}}</view>
						<view class="cancel active" @tap="tapConfirm">{{i18n[1]}}</view>
					</view>
				</view>
			</block>
		</view>
		<!-- @tap="tapMask" -->
		<view class="maskback" :style="{backgroundColor:value?`rgba(0,0,0,${opacity})`:'rgba(0,0,0,0)'}" :class="!value?'':'mask-show'" @touchmove.stop.prevent></view>
	</view>
</template>

<script>
	import TabMask from './tabMask.js';
	var that;
	export default{
		props:{
			type:{
				type:String,
				default:'default'
			},
			value:{
				type:Boolean,
				default:false
			},
			maskEnable:{
				type:Boolean,
				default:true
			},
			mData:{
				type:[Object, Array],
				default:()=>{}
			},
			tabbarHeight:{
				type:Number,
				default:null 
			},
			navHeight:{
				type:Number,
				default:null
			},
			opacity:{
				type:Number,
				default:0.4
			},
		},
		data(){
			return{
				tabMask:null
			}
		},
		computed:{
			i18n(){
				return this.$t('btncomfirm');
			}
		},
		mounted() {

			//#ifdef APP-PLUS
			this.tabMask = new TabMask({
				tabbarHeight:this.tabbarHeight,
				navHeight:this.navHeight,
				opacity:this.opacity,
				fn: this.tapMask
			})
			//#endif
			
		},
		methods:{
			tapCancel(){
				this.$emit('cancel')
			},
			tapConfirm(item){
				this.$emit('onConfirm',item)
			},
			tapMask(){
				if(!this.maskEnable) return
				if(this.type == 'multiSelect'){
					this.$emit('onConfirm',this.mData)
				}else{
					this.$emit('cancel',this.mData)
				}

			}
		},
		watch:{
			value:{
				immediate:false,
				handler(newVal,oldVal){ 
					//#ifdef APP-PLUS
					if(newVal) {
						this.tabMask.show()
					}else{
						this.tabMask.hide()
					}
					//#endif
				}
			}
		}
	}
</script>

<style lang="scss">
	.mask{
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		z-index: 9999;
		transition: background 0.3s linear;
		display: flex;
		align-items: center;
		opacity: 0;
		visibility: hidden;
		justify-content: center;
		.maskback{position: absolute;width: 100%;height: 100%;left: 0;top: 0;z-index: 10000;}
		&.mask-show{
			visibility:visible;
			opacity: 1;
		}
		
	}
	.hover{
		background: #f2f2f2;
	}
	.modal_content{position: relative;z-index: 10001;}
	.modal_info{ padding: 10px 0;}
	.procontent{padding: 40rpx 48rpx;
			min-height: 40px;
			font-size: 15px;
			line-height: 1.4;
			color: #999;
			text-align: center;}
	.default-view{
		width: 600rpx;
		font-weight: 400;
		font-size: 18px;
		background-color: #fff;
		border-radius: 6rpx;
		.title{
			height: 100rpx;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		.overflow_content{
			height: 450rpx;padding: 40rpx 48rpx;
			.overflw_scroll{overflow-y: scroll;height: 100%;}
		}
		.content{
			min-height: 40px;
			font-size: 14px;
			line-height: 21px;
			color: #999;
			text-align: center;
		}
		.modalbtn{
			height: 100rpx;
			display: flex;
			flex-direction: row;
			border-top:1px solid #ddd;
			font-size: 15px;
			.cancel{
				display: flex;
				flex: 1;
				justify-content: center;
				align-items: center;
				&.active{ color:#007aff;border-left: 1px solid #ddd;}
			}
			.confirm{
				display: flex;
				flex: 1;
				justify-content: center;
				align-items: center;
				color: rgb(0, 122, 255);
				&.active_close{ color:#ddd;}
			}
		}
	}
	.select-view{
		width: 600rpx;
		background-color: #fff;
		border-radius: 6rpx;
		.select-box{
			height: 100rpx;
			padding: 20rpx;
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			align-items: center;
			border-bottom: 0.5px solid #ddd;
			.select-content{
				color: #aaa;
				font-size: 12px;
			}
		}
		.image{
			display: inline-block;
			vertical-align: middle;
			width: 40rpx;
			height: 40rpx;
			margin-right: 20rpx;
		}
	}
	.select-view .select-box:last-child{
		border: none;
	}
	.notify-view{
		width: 600rpx;
		background-color: #fff;
		border-radius: 6rpx;
		.image{
			width: 600rpx;
			height: 150rpx;
		}
		.title{
			height: 100rpx;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		.content{
			padding: 40rpx 48rpx;
			min-height: 40px;
			font-size: 15px;
			line-height: 1.4;
			color: #999;
			text-align: center;
		}
		.cancel{
			height: 100rpx;
			display: flex;
			flex: 1;
			justify-content: center;
			align-items: center;
			border-top:1px solid #E7E7E7;
		}
	}
	.advert-view{
		overflow: hidden;
		
		display: flex;
		flex-direction: column;
		align-items: center;
		.confirm{
			width: 500rpx;
			height: 700rpx;
			border-radius: 6rpx;
		}
		.cancel{
			margin-top: 150rpx;
			width: 60rpx;
			height: 60rpx;
		}
	}
	.share-view{
		width: 600rpx;
		background-color: #fff;
		border-radius: 6rpx;
		padding: 20rpx;
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		font-size: 18px;
		.share-box{
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			width: 33.33%;
			padding: 40rpx 0;
		}
		.image{
			width: 80rpx;
			height: 80rpx;
			margin-bottom: 20rpx;
		}
	}
	.input-view{
		width: 600rpx;
		font-weight: 400;
		font-size: 18px;
		background-color: #fff;
		border-radius: 6rpx;
		.title{
			height: 100rpx;
			display: flex;
			justify-content: center;
			align-items: center;
			border-bottom: 1px solid #ccc;
		}
		.content{
			padding: 40rpx 48rpx;
			min-height: 40px;
			font-size: 18px;
			
			text-align: left;
		}
		.input-box{
			display: flex;
			margin-bottom: 20rpx;
			.view{
				margin-right: 20rpx;
				min-width: 150rpx;
			}
			.input{
				
				font-size: 18px;
			}
		}
		.btn{
			height: 100rpx;
			display: flex;
			flex-direction: row;
			border-top:1px solid #ccc;
			.cancel{
				display: flex;
				flex: 1;
				justify-content: center;
				align-items: center;
				border-right:1px solid #ccc;
			}
			.confirm{
				display: flex;
				flex: 1;
				justify-content: center;
				align-items: center;
				color: rgb(0, 122, 255);
			}
		}
	}
	.word-break{
		word-wrap: break-word;
		word-break: break-all;
		white-space: pre-wrap;
	}
</style>
