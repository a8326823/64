<template>
	<view class="picker_show" v-show="picker_show" @touchmove.stop.prevent catchtouchmove="true">
		<view class="picker_mask" :class="{active:maskshow}" @touchmove.stop.prevent
         catchtouchmove="true"></view>
		<view class="picker_list" :class="{active:maskshow}">
			<view class="picker_button">
				<text @click="picker_close">{{i18n[0]}}</text>
				<text @click="bindconfirm">{{i18n[1]}}</text>
			</view>
			<picker-view v-if="visible" :indicator-style="indicatorStyle" :value="value" @change="bindChange">
				<picker-view-column>
					<view class="item" v-for="(item,index) in pickerlist" :key="index">{{item}}</view>
				</picker-view-column>
			</picker-view>
		</view>
	</view>
</template>

<script>
	export default{
		props:{
			pickerlist:{
				type:Array,
				default:[]
			}
		},
		data(){
			return{
				value: [0],
				visible: false,
				indicatorStyle: `height: ${Math.round(uni.getSystemInfoSync().screenWidth/(750/100))}px;`,
				picker_show:false,
				maskshow:false,
				valuenum:0,
				changeval:''
			}
		},
		computed:{
			i18n(){
				return this.$t('btncomfirm');
			}
		},
		mounted() {
			// this.changeval = this.pickerlist[this.value[0]];
			this.valuenum = 0;
			// console.log(this.changeval)
		},
		methods:{
			bindChange: function (e) {
				// console.log(e);
				const val = e.detail.value;
				this.value = val;
				this.valuenum = val;
				this.changeval = this.pickerlist[val];
			},
			picker_open(){
				this.picker_show = true;
				this.visible = true;
				setTimeout(()=>{
					this.maskshow = true;
				},300);
				
			},
			picker_close(){
				this.maskshow = false;
				this.visible = false;
				setTimeout(()=>{
					this.picker_show = false;
				},300);
			},
			bindconfirm(){
				this.changeval = this.pickerlist[this.valuenum];
				this.$emit('btnconfirm',this.changeval,this.valuenum);
				this.picker_close();
			}
		}
	}
	
</script>

<style lang="less">
	picker-view {
	    width: 100%;
	    height: 600rpx;
	}
	
	.item {
	    line-height: 100rpx;
	    text-align: center;
	}
	.picker_show{
		/* #ifndef APP-NVUE */
		display: block;
		/* #endif */
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		overflow: hidden;
		z-index: 999;
	}
	.picker_list{
		/* #ifndef APP-NVUE */
		display: block;
		/* #endif */
		position: absolute;
		right: 0;left: 0;bottom: -100%;
		background-color: #ffffff;
		transition: transform 0.3s ease;
		&.active{
			transform: translateY(0px);
			bottom: 0;
		}
		.picker_button{ display: flex;justify-content: space-between;border-bottom: 1px solid #ececec;
			text{ font-size: 16px;color: #333333;padding: 15px;}
		}
	}
	
	.picker_bottom {
		bottom: 0;
		/* #ifdef APP-NVUE */
		transform: translateY()(220px);
		/* #endif */
		/* #ifndef APP-NVUE */
		transform: translateY(100%);
		/* #endif */
	}
	.picker_mask{
		/* #ifndef APP-NVUE */
		display: block;
		/* #endif */
		opacity: 0;
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		right: 0;
		background-color: rgba(0, 0, 0, 0.4);
		transition: opacity 0.3s;
		&.active{
			/* #ifndef APP-NVUE */
			display: block;
			/* #endif */
			opacity: 1;
		}
	}
</style>
