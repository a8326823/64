<template>
	<view class="header_top">
		<public-head :title="i18n.title"></public-head>
		<view class="record-item" v-for="(item, index) in dataList" :key="index">
			<view class="item-left">
				<text class="type" v-if="item.status === 0">{{i18n.drawrecord1}}</text>
				<text class="type" v-else-if="item.status === 1">{{i18n.drawrecord2}}</text>
				<text class="type" v-else>{{i18n.drawrecord3}}</text>
				<text class="date">{{ item.created_at }}</text>
			</view>
			<view class="item-right" v-if="item.status === 1">{{ item.amount | formatPrice }}</view>
			<view class="item-right-other" v-if="item.status === 2">{{ item.remark }}</view>
		</view>
		<uni-load-more :status="status" v-if="pageData.data.length > 10"></uni-load-more>
		<view class="empty" v-if="pageData.data.length == 0">{{i18n.drawinfo}}</view>
	</view>
</template>

<script>
import loadMore from '@/utils/mixin/loadMore.js';
export default {
	mixins: [loadMore],
	data() {
		return {
			dataList:[{status:0,created_at:"2012",remark: 100,amount: 100},{status:1,created_at:"2012",remark: 100,amount: 100},
			{status:2,created_at:"2012",remark: 100,amount: 100},{status:0,created_at:"2012",remark: 100,amount: 100}]
		}
	},
	computed:{
		i18n(){
			 return this.$t('widthdrawal_record');
		}
	},
	methods: {
		// 提现记录
		async getData() {
			let res = await this.$http.withdrawalList({
				page: this.page
			});
			if (this.page === 1) {
				this.pageData = res.result;
			} else {
				this.pageData.data.push(...res.result.data);
			}
			// console.log(this.pageData);
		}
	},
	onShow() {
		this.getData();
	}
};
</script>

<style lang="less">
page {
	border-top: 4rpx solid #f5f7fd;
	padding: 0 30rpx;
	box-sizing: border-box;
}
.empty {
	text-align: center;
	margin-top: 450rpx;
	color: rgb(119, 119, 119);
	font-size: 30rpx;
}
.record-item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	width: 100%;
	height: 134rpx;
	border-bottom: 1px solid #eaeaea;
	.item-left {
		display: flex;
		flex-direction: column;
		justify-content: center;
		.type {
			color: #333333;
			font-size: 28rpx;
		}
		.date {
			color: #bebebe;
			font-size: 22rpx;
			margin-top: 18rpx;
		}
	}
	.item-right {
		color: green;
		font-size: 26rpx;
	}
	.item-right-other {
		color: #f9103c;
		font-size: 26rpx;
	}
}
.record-item:last-child {
	border: none;
}
</style>
