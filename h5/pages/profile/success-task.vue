<template>
	<view class="task-list header_top">
		<loading-plus v-if="isShow"></loading-plus>
		<public-head :title="i18n.title"></public-head>
		<block			
			@click="
				navTo(
					'/pages/profile/task-detail?title=' +
						item.task.title +
						'&amount=' +
						item.amount +
						'&time=' +
						item.created_at +
						'&status=' +
						item.status +
						'&remark=' +
						item.remark
				)
			"
			v-for="(item, index) in pageData.data"
			:key="index"
		>
			<view v-if="item.task">
				<view class="task-item">
					<view class="item-title">{{ item.task.title }}</view>
					<view class="t-income">{{i18n.profit}} {{ item.amount | formatPrice }}</view>
					<view class="time-status">
						<view class="time">{{i18n.prodate}}{{ item.created_at }}</view>
						<view class="status" v-if="item.status > 0">{{i18n.seleclist[item.status]}}</view>
						<!-- <view class="status" v-if="item.status === 2">已完成</view>
						<view class="status" v-if="item.status === 3">已拒绝</view>
						<view class="status" v-if="item.status === 4">取消</view> -->
					</view>
				</view>
			</view>
		</block>
		<uni-load-more :status="status" v-if="pageData.data.length > 10"></uni-load-more>
		<view class="empty" v-if="pageData.data.length == 0">{{i18n.info}}</view>
	</view>
</template>

<script>
import loadMore from '@/utils/mixin/loadMore.js'
export default {
	mixins:[loadMore],
	data() {
		return {
			type:'',
			isShow:true
		};
	},
	computed:{
		i18n(){
			return this.$t('protasksucc');
		}
	},
	methods: {
		navTo(url) {
			uni.navigateTo({url})
		},
		async getData() {
			let res = await this.$http.userTaskList({
				type: this.type,
				page: this.page
			});
			// console.log(res);
			if (this.page === 1) {
				this.pageData = res.result;
			} else {
				this.pageData.data.push(...res.result.data);
			}
			// console.log(this.pageData);
		},
	},
	onShow() {
		this.getData();
		setTimeout(()=>{
			this.isShow = false
		},1000)
	},
	onLoad(options) {
		this.type = options.type
	}
};
</script>

<style lang="less">
page {
	background-color: #fff;
}
.task-item {
	width: 690rpx;
	height: 234rpx;
	background-color: #ffffff;
	box-shadow: 0px 10rpx 16px 4rpx rgba(189, 189, 189, 0.29);
	border-radius: 6rpx;
	border: solid 1px #f0f0f0;
	margin: 20rpx auto 0;
	padding: 40rpx 30rpx 0;
	.item-title {
		width: 350rpx;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		line-height: 36rpx;
		color: #171717;
		font-size: 30rpx;
	}
	.t-income {
		color: #737373;
		font-size: 24rpx;
		margin-top: 36rpx;
	}
	.time-status {
		margin-top: 12rpx;
		display: flex;
		justify-content: space-between;
		.time {
			color: #737373;
			font-size: 24rpx;
		}
		.status {
			color: #f3164a;
			font-size: 24rpx;
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
