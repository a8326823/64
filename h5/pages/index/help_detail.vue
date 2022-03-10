<template>
	<view class="help-list animated fadeIn faster header_top">
		<loading-plus v-if="isShow"></loading-plus>
		<public-head :title="i18n.munelist[0]"></public-head>
		<block v-for="(item, index) in dataList" :key="index">
			<tui-collapse :index="index" :current="current" @click="change">
				<template v-slot:title>
					<tui-list-cell>{{ item.title }}</tui-list-cell>
				</template>
				<template v-slot:content>
					<view class="tui-content" v-for="(itemy, indey) in item.content" :key="indey">{{ itemy }}</view>
				</template>
			</tui-collapse>
		</block>
		<view style="text-align: center;margin-bottom: 150rpx;" v-if="dataList.length === 0">
			<image src="/static/images/index/pic_zanwu.png" style="width: 450rpx;height: 230rpx;margin-top: 400rpx;"></image>
			<view style="color: #615f60;font-size: 28rpx;margin-top: 50rpx;">{{i18n.listinfo}}</view>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			dataList: '',
			current: 0,
			isShow:true
		};
	},
	computed:{
		i18n(){
			return this.$t('index');
		}
	},
	methods: {
		change(e) {
			this.current = e.index;
		},
		async helpQuestion() {
			let res = await this.$http.help();
			this.dataList = res.result;
			// console.log(res);
		}
	},
	onShow() {
		this.helpQuestion();
		setTimeout(()=>{
			this.isShow = false
		},1000)
	}
};
</script>

<style lang="less">
page {
	background-color: #f7f9fc;
}
.help-list {
	margin-top: 34rpx;
}
.tui-content {
	padding: 20rpx 30rpx;
	background-color: #fff;
	color: #999999;
	font-size: 24rpx;
	line-height: 33rpx;
}
</style>
