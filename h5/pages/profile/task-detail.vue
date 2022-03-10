<template>
	<view class="task-list">
		<view class="task-item1">
			<view class="item1-title">任务名称</view>
			<view class="item1-con">{{options.title}}</view>
		</view>
		<view class="task-item2">
			<view class="item2-title">任务金额</view>
			<view class="item2-con">{{options.amount}}</view>
		</view>
		<view class="task-item2">
			<view class="item2-title">任务状态</view>
			<view class="item2-con" style="color: #f3164a;" v-if="options.status == 1">待审核</view>
			<view class="item2-con" style="color: #f3164a;" v-if="options.status == 2">已完成</view>
			<view class="item2-con" style="color: #f3164a;" v-if="options.status == 3">已拒绝</view>
			<view class="item2-con" style="color: #f3164a;" v-if="options.status == 4">取消</view>
		</view>
		<view class="task-item2">
			<view class="item2-title">任务领取时间</view>
			<view class="item2-con">{{options.time}}</view>
		</view>
		<view class="task-item2" v-if="options.status == 3">
			<view class="item2-title">拒绝原因</view>
			<view class="item2-con">{{options.remark}}</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				options:''
			};
		},
		onLoad(options) {
			// console.log(options);
			this.options = options
		}
	}
</script>

<style lang="less">
	page {
		background-color: #f7f5fd;
	}
.task-list {
	width: 100%;
	padding: 0 30rpx;
	background-color: #fff;
	overflow: hidden;
	margin-top: 16rpx;
	.task-item1 {
		width: 100%;
		height: 198rpx;
		border-bottom: 1px solid #f7f5fd;
		.item1-title {
			color: #333;
			font-size: 30rpx;
			margin-top: 34rpx;
		}
		.item1-con {
			overflow : hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			color: #b3b3b3;
			font-size: 28rpx;
			margin-top: 34rpx;
		}
	}
	.task-item2 {
		display: flex;
		height: 100rpx;
		align-items: center;
		border-bottom: 1px solid #f7f5fd;
		.item2-title {
			color: #333;
			font-size: 30rpx;
			margin-right: 34rpx;
		}
		.item2-con {
			font-size: 28rpx;
			color: #b3b3b3;
		}
	}
}
</style>
