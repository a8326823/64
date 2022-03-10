<template>
	<view class="header_top">
		<public-head :title="i18n.title"></public-head>
		<fh-tab-control :tabItem="i18n.tabitem" :tabIndex="tabIndex" style="border-bottom: 10rpx solid #f5f7fd;" @itemClick="itemClick"></fh-tab-control>
		<view class="list">
			<view class="list-item" v-for="(item, index) in pageData.data" :key="index">
				<view class="item-left">
					<text class="type">{{ item.type }}</text>
					<text class="date">{{ item.created_at }}</text>
				</view>
				<view class="item-right" v-if="item.credit > 0">+{{ item.credit }}</view>
				<view class="item-right-active" v-else>{{ item.credit }}</view>
			</view>
		</view>
		<uni-load-more :status="status" v-if="pageData.data.length > 10"></uni-load-more>
		<view class="empty" v-if="pageData.data.length == 0">{{i18n.info}}</view>
	</view>
</template>

<script>
import loadMore from '@/utils/mixin/loadMore.js';
export default {
	mixins: [loadMore],
	data() {
		return {
			tabItem: ['已加信用', '已扣信用'],
			type: 1,
			tabIndex:0
		};
	},
	computed:{
		i18n(){
			return this.$t('creditlist');
		}
	},
	methods: {
		async getData() {
			let res = await this.$http.credit({
				type: this.type,
				page: this.page
			});
			if (this.page === 1) {
				this.pageData = res.result;
			} else {
				this.pageData.data.push(...res.result.data);
			}
			// console.log(this.pageData);
		},
		itemClick(index) {
			this.tabIndex = index
			this.page = 1;
			this.type = index + 1;
			this.getData();
		}
	},
	onShow() {
		this.getData();
	}
};
</script>

<style lang="less">
.list {
	margin-top: 91rpx;
	padding-top: 20rpx;
	.list-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
		width: 100%;
		height: 120rpx;
		padding: 0 37rpx;
		border-bottom: solid 2px #f7f9fc;
		.item-left {
			display: flex;
			flex-direction: column;
			color: #000;
			font-size: 24rpx;
			.date {
				margin-top: 24rpx;
			}
		}
		.item-right {
			color: #f9103c;
			font-size: 40rpx;
		}
		.item-right-active {
			color: #2a2a2a;
			font-size: 40rpx;
		}
	}
}
.empty {
	text-align: center;
	margin-top: 450rpx;
	color: rgb(119, 119, 119);
	font-size: 30rpx;
}
</style>
