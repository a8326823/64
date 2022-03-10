<template>
	<view class="header_top">
		
		<loading-plus v-if="isShow"></loading-plus>
		<public-head headshow :title="i18n.title"></public-head>
		<!-- <fh-tab-control :tabItem="tabItem" @itemClick="itemClick" :tabIndex="tabIndex1" :isBorder="true" :sliderWidth="42" :bottom="0"></fh-tab-control> -->
		<view class="member_content">
			<!-- <checkbox-group @change="checkboxChange">
				<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in pageData.data" :key="index">
					<view>
						<checkbox :value="item.title" :checked="item.checked" />
					</view>
					<view>{{item.name}}</view>
				</label>
			</checkbox-group> -->
			<view class="c-item" v-for="(item, index) in pageData.data" :key="index" :class="{active:item.checked}">
				<view class="item-l" @click="checkboxChange(index)">
					<view class="l-img"><image :src="item.category.icon" mode=""></image></view>
				</view>
				<view class="item-c">
					<view class="c-title">{{ item.title }}</view>
					<view class="c-tag">
						<image src="/static/images/index/icon_huangguan.png" mode=""></image>
						<text>{{ item.level_info.name }}</text>
					</view>
					<view class="c-number">{{i18n.numtxt}}： {{ item.remaining_quantity }}</view>
				</view>
				<view class="item-r" @click="toDetail(item.id)">
					<text>{{ item.amount | formatPrice }}</text>
					<image src="/static/images/index/icon_big_xiala.png" mode=""></image>
				</view>
			</view>
			<uni-load-more :status="status" v-if="pageData.data.length > 10"></uni-load-more>
			<view style="text-align: center;" v-if="pageData.data.length === 0">
				<image src="/static/images/index/pic_zanwu.png" style="width: 450rpx;height: 230rpx;margin-top: 400rpx;"></image>
				<view style="color: #615f60;font-size: 28rpx;margin-top: 50rpx;">{{i18n.listinfo}}</view>
			</view>
		</view>
		<view class="memberr_robot">
			<image src="/static/images/index/robot.png" mode="widthFix" @click="submit_robot"></image>
		</view>
		<view class="memberload" v-if="memberload">
			<image src="/static/images/index/task-flow-completed.gif" mode="widthFix"></image>
		</view>
	</view>
</template>

<script>
import config from '@/service/config.js'
import loadMore from '@/utils/mixin/loadMore.js'
export default {
	mixins:[loadMore],
	data() {
		return {
			list: [],
			level: '',
			tabIndex: '',
			isShow:true,
			memberload:false
		};
	},
	methods: {
		async getTaskCategory() {//任务分类
			let res = await this.$http.taskCategory();
			// console.log(res);
			this.list = res.result;
		},
		async itemClick(index) {
			this.isShow = true
			this.tabIndex = index;
			// console.log(this.tabIndex);
			this.page = 1;
			this.getData();
		},
		async getData() {
			let res = await this.$http.task({
				category_id: this.tabIndex,
				level: this.level,
				page: this.page
			});
			res.result.data.forEach((item)=>{
				item.category.icon = config.ossBaseUrl+item.category.icon
			})
			// console.log(res, 1111111);
			if (this.page === 1) {
				this.pageData = res.result;
			} else {
				this.pageData.data.push(...res.result.data);
			}
			this.isShow = false;
		},
		toDetail(id) {
			uni.navigateTo({
				url:"/pages/index/task-detail?id="+id
			})
		},
		checkboxChange(index){
			var items = this.pageData.data,
				values = index;
			for (var i = 0, lenI = items.length; i < lenI; ++i) {
				const item = items[i]
				if(values===i){
					if(item.checked){
						this.$set(item,'checked',false)
					}else{
						this.$set(item,'checked',true);
					}
				}
			}
		},
		submit_robot(){
			const that = this;
			let robotarr = this.pageData.data.filter(function(item,index,arr){
				return item.checked;
			});
			if(robotarr.length < 1){
				this.totat('请点击头像选择任务');
				return false;
			}
			let subid = robotarr.map(function(item,index,arr){
				return item.id;
			})
			// console.log(robotarr)
			uni.showModal({
				title:'',
				content: '使用机器人完成任务，需要扣除相关费用',
				confirmText:'确认',
				cancelText:'取消',
				success(res) {
					if(res.confirm) {
						that.$http.requestajx('task/receive','post',{type:1,ids:JSON.stringify(subid)}).then((res) => {
							// console.log(res);
							that.submittask(robotarr);
						});
					}
				}
			})
			
		},
		async submittask(robotarr){
			const that = this;
			this.memberload = true;
			/* let taskid = robotarr.map(async function(item,index,arr){
				let taskdata = await that.$http.taskReceive({
					id : item.id
				});
				let res = await that.$http.taskSubmit({
					id:taskdata.result.id,
					image:that.pageData.data[0].category.icon
				});
			}); */
			this.itemClick(this.levelindex);
			setTimeout(function(){
				that.memberload = false;
				that.totat('成功开启');
			},1500)
			
		}
	},
	computed: {
		tabItem() {
			let arr = [];
			this.list.forEach(item => {
				arr.push(item.name);
			});
			return arr;
		},
		tabId() {
			let arr = [];
			this.list.forEach(item => {
				arr.push(item.id);
			});
			return arr;
		},
		i18n(){
			return this.$t('memberhall');
		},
		i18nbtn(){
			return this.$t('btncomfirm');
		}
	},
	onShow() {
		// this.getTaskCategory();
		this.itemClick(this.levelindex);
		
	},
	onLoad(options) {
		// console.log(options)
		this.level = options.level;
		this.levelindex = options.levelindex;
		this.itemClick(this.levelindex);
	}
};
</script>

<style lang="scss">
.member_content{ padding: 20px;}
.memberload{ position: fixed;top: 0;left: 0;right: 0;bottom: 0;z-index: 99999;background-color: rgba($color: #000000, $alpha: 0.5);
		display: flex;align-items: center;text-align: center;padding: 0 60px;
			image{ width: 100%;border-radius: 10px;}
	}
.memberr_robot{ position: fixed;bottom: 100px;right: 10px;
	image{ width: 40px;}
}
.c-item {
	background-color: #ffffff;
	box-shadow: 0px 6rpx 32rpx 0px rgba(0, 35, 68, 0.14);
	border-radius: 20rpx;
	margin-bottom: 32rpx;
	display: flex;padding: 10px 0;
	&.active{ background: url(/static/images/vip/select.png) no-repeat;background-position: right bottom;  }
	.item-l {
		display: flex;
		justify-content: center;
		align-items: center;
		flex: 2;
		.l-img {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 105rpx;
			height: 105rpx;
			border-radius: 50%;
			background-color: #ebebf3;
			image {
				width: 50rpx;
				height: 50rpx;
			}
		}
	}
	.item-c {
		padding-top: 10rpx;
		flex: 4;
		.c-title {
			color: #1a2564;
			line-height: 36rpx;
			font-size: 26rpx;

			text-overflow: -o-ellipsis-lastline;
			overflow: hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
		}
		.c-tag {
			display: flex;
			align-items: flex-end;
			margin: 10rpx 0;
			image {
				width: 19rpx;
				height: 19rpx;
				margin-right: 12rpx;
			}
			text {
				color: #ffbd55;
				font-size: 18rpx;
			}
		}
		.c-number {
			color: #1a2564;
			font-size: 18rpx;
			margin-top: 20rpx;
		}
	}
	.item-r {
		flex: 2;
		display: flex;
		align-items: center;
		justify-content: center;
		text {
			color: #fb5081;
			font-size: 24rpx;
		}
		image {
			width: 8rpx;
			height: 16rpx;
			margin-left: 20rpx;
		}
	}
}
</style>
