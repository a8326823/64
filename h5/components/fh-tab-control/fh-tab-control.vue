<template>
	<view class="tab-control" :style="{position:isFixed?'fixed':''}">
		<view class="tab-item" v-for="(item, index) in tabItem" :key="index" @click="changeTab(index)" :style="{'border-bottom':isBorder?'2px solid #f7f9fc':''}">
			<view class="tab-text" :style="{ color: index === tabIndex ? '#000' : '' }">{{ item }}</view>
			<view class="line" v-if="index === tabIndex" 
				:style="{ bottom:bottom+'rpx', background: sliderBgColor, width: sliderWidth + 'rpx', height: sliderHeight + 'rpx',borderRadius:sliderRadius+'rpx'}"></view>
		</view>
	</view>
</template>

<script>
export default {
	props:{
		tabItem:{
			type: Array,
			default: []
		},
		sliderBgColor:{
			type:String,
			default: "#7a2b86"
		},
		sliderWidth: {
			type:Number,
			default: 50
		},
		sliderHeight: {
			type: Number,
			default: 6
		},
		sliderRadius: {
			type: Number,
			default: 4
		},
		bottom: {
			type: Number,
			default: 10
		},
		isBorder: {
			type:Boolean,
			default:false
		},
		tabIndex: {
			type:Number,
			default:0
		},
		isFixed: {
			type:Boolean,
			default:true
		}
	},
	data() {
		return {
			
		};
	},
	methods:{
		changeTab(index){
			if (this.tabIndex === index) return
			// this.tabIndex = index;
			this.$emit("itemClick",index)
		}
	}
}
</script>

<style lang="less">
.tab-control {
	width: 750rpx;
	height: 81rpx;
	background-color: #fff;
	/* #ifndef APP-PLUS */
	top: 92rpx;
	/* #endif */
	/* #ifdef APP-PLUS */
	top: 0;
	/* #endif */
	display: flex;
	line-height: 81rpx;
	z-index: 10;
	box-sizing: content-box;
}
.tab-item {
	position: relative;
	flex: 1;
	text-align: center;
	.tab-text {
		color: #8e8e8e;
		font-size: 24rpx;
	}
}
.line {
	position: absolute;
	left: 0;
	right: 0;
	margin: auto;
	transform: scale(0.6);
}
</style>
