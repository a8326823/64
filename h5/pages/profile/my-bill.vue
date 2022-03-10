<template>
	<view class="bill header_top" >
		<fh-tab-control :tabItem="i18n.tabItem" :tabIndex="tabIndex" :sliderWidth="42" :bottom="0" :isBorder="true" @itemClick="itemClick"></fh-tab-control>
		
		<loading-plus v-if="isShow"></loading-plus>
		<public-head :title="i18n.title"></public-head>
		<view class="bill-list">
			<view class="record-item" v-for="(item, index) in pageData.data" :key="index">
				<view class="item-left">
					<view class="type">{{ item.type }}</view>
					<text class="date">{{ item.updated_at }}</text>
				</view>
				<view class="item-right" v-if="item.balance > 0">+{{ item.balance | formatprice }}</view>
				<view class="item-right-active" v-else>{{ item.balance | formatprice }}</view>
			</view>
		</view>
		<uni-load-more :status="status" v-if="pageData.data.length>=10"></uni-load-more>
		<view class="empty" v-if="pageData.data.length == 0">{{i18n.info}}</view>
	</view>
</template>
<script>
import loadMore from '@/utils/mixin/loadMore.js';
export default {
	mixins: [loadMore],
	data() {
		return {
			tabItem: ['全部', '任务收益', '下级收益'],
			type:1,
			tabIndex: 0,
			isShow:true
		};
	},
	filters:{
		formatprice(val) {
			return val.toFixed(2)
		}
	},
	computed:{
		i18n(){
			return this.$t('mybill');
		}
	},
	methods: {
		async getData() {
			let res = await this.$http.userBill({
				type:this.type,
				page:this.page
			});
			if(this.page === 1) {
				this.pageData = res.result
				// console.log(this.pageData);
			} else {
				this.pageData.data.push(...res.result.data);
			}
		},
		itemClick(index) {
			this.isShow = true
			setTimeout(()=>{
				this.isShow = false
			},1000)
			this.tabIndex = index
			this.page = 1;
			this.type = index + 1
			this.getData();
		}
	},
	onShow() {
		this.getData();
		setTimeout(()=>{
			this.isShow = false
		},1000)
	}
};
</script>

<style lang="less">
page {
	padding-top: 81rpx;
}
.empty {
	text-align: center;
	margin-top: 450rpx;
	color: rgb(119, 119, 119);
	font-size: 30rpx;
}
.bill-list {
	padding: 0 30rpx;
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
			width: 430rpx;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
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
		color: #f9103c;
		font-size: 40rpx;
	}
	.item-right-active {
		color: #2a2a2a;
		font-size: 40rpx;
	}
}
.record-item:last-child {
	border: none;
}
</style>
