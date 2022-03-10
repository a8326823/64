<template>
	<view class="member_back">
		<public-head headshow :title="i18n.title"></public-head>
		<!-- <fh-tab-control :tabItem="tabItem" @itemClick="itemClick" :tabIndex="tabIndex1" :isBorder="true" :sliderWidth="42" :bottom="0"></fh-tab-control> -->
		<view class="member_scroll">
			<scroll-view scroll-with-animation :scroll-x="true" class="member-list">
				<view class="list_mune">
					<block v-for="(item,index) in memberInfo" :key="index">
						<view class="mune_item" :class="{active:tabIndex===index}" @click="itemClick(index)">{{item.name}}</view>
					</block>
				</view>
			</scroll-view>
		</view>
		<view class="loading_more" v-if="isShow">
			<uni-load-more status="loading"></uni-load-more>
		</view>
		<view class="member_content" v-else>
			<view class="c-item" v-for="(item, index) in pageData.data" :key="index" >
				<view class="item-l">
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
				<view class="item-r">
					<text>{{ item.amount | formatPrice }}</text>
					<!-- <image src="/static/images/index/icon_big_xiala.png" mode=""></image> -->
				</view>
				<view class="item_btn">
					<button @click="submittask(item)">Auto Process</button>
				</view>
			</view>
			<uni-load-more :status="status" v-if="pageData.data.length > 10"></uni-load-more>
			<view style="text-align: center;" v-if="pageData.data.length === 0">
				<image src="/static/images/index/pic_zanwu.png" style="width: 450rpx;height: 230rpx;margin-top: 400rpx;"></image>
				<view style="color: #615f60;font-size: 28rpx;margin-top: 50rpx;">{{i18n.listinfo}}</view>
			</view>
		</view>
		<view class="memberload" v-if="memberload">
			<image src="/static/images/index/task-flow-completed.gif" mode="widthFix"></image>
		</view>
		<!-- <popu-modal v-model="value" :mData="defaultData" type="default" @cancel="tapConfirm" navMask></popu-modal> -->
	</view>
</template>

<script>
export default {
	data() {
		return {
			pageData: {
				data: []
			},
			list: [],
			level: '',
			category_id:'',
			tabIndex: 0,
			memberInfo:[],
			isShow:true,
			value:false,
			defaultData:{content:'Dear member, the system has started the automatic intelligent task robot service for you, please click to confirm the startup program',cancelText:'cancel'},
			memberload:false,
			currtask:''
		};
	},
	methods: {
		async getTaskCategory() {
			let userdata = await this.$http.userLevel(); //会员列表
			this.memberInfo = userdata.result;
			
			let res = await this.$http.taskCategory();//任务分类列表
			this.list = res.result;
			this.itemClick(this.tabIndex);
		},
		async itemClick(index,is_recommended) {
			this.isShow = true;
			this.tabIndex = index;
			this.level = this.memberInfo[index].level;
			this.page = 1;
			if(this.memberInfo[index].is_recommended < 1){
				this.pageData.data = [];
				this.isShow = false;
				return this.totat(this.i18n.leveninfo);
			}
			this.getData();
		},
		async getData() {
			let res = await this.$http.task({ //获取任务列表
				category_id: this.category_id,
				level: this.level,
				page: this.page
			});
			// console.log(res)
			res.result.data.forEach((item)=>{
				item.category.icon = this.$configurl.ossBaseUrl+item.category.icon;
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
		async tapConfirm(){
			const that = this;
			this.value = false;
			this.memberload = true;
			let res = await this.$http.taskSubmit({//弹窗
				id:this.currtask.id,
				image:this.pageData.data[0].category.icon
			});
			this.itemClick(this.tabIndex);
			setTimeout(function(){
				that.memberload = false;
				that.totat('Robot trigger');
			},1500)
			
			uni.setStorageSync('robotboole', true);
		},
		async submittask(item){
			const that = this;
			let taskdata = await this.$http.taskReceive({
				id : item.id
			})
			this.memberload = true;
			let res = await this.$http.taskSubmit({
				id:taskdata.result.id,
				image:this.pageData.data[0].category.icon
			});
			this.itemClick(this.tabIndex);
			setTimeout(function(){
				that.memberload = false;
				that.totat('Task submitted');
			},1500)
			/* this.$http.requestajx('setting','get',{}).then(async (res) => {
				if(res.result.enableRobotAutoSubmit){
					that.defaultData.content = res.result.robotPopupContent;
					let taskdata = await this.$http.taskReceive({
						id : item.id
					})
					that.currtask = taskdata.result;
					that.value = true;
				}else{
					that.toDetail(item.id);
				}
				
			}) */
			
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
		}
	},
	onShow() {
		
	},
	onLoad(options) {
		this.category_id = options.level;
		this.getTaskCategory();
		setTimeout(()=>{
			this.isShow = false
		},1500)
	}
};
</script>

<style lang="scss">
.member_back{ height: 100vh;display: flex;flex-direction: column;padding-top: calc(var(--status-bar-height) + 95px);}
.member_scroll{ position: fixed;top: calc(var(--status-bar-height) + 45px);left: 0;right: 0;z-index: 99;}
.member_content{ padding-top: 20px;padding-bottom: 20px;}
.memberload{ position: fixed;top: 0;left: 0;right: 0;bottom: 0;z-index: 99999;background-color: rgba($color: #000000, $alpha: 0.5);
		display: flex;align-items: center;text-align: center;padding: 0 60px;
			image{ width: 100%;border-radius: 10px;}
	}
.member-list {min-width: 100%;height: 100rpx;white-space: nowrap;background-color:$unified-color;
	.list_mune{ display: flex;height: inherit;align-items: center;}
	.mune_item{ display: inline-block;width: 250rpx;padding: 0 15px; color: #FFFFFF;font-size: 13px;position: relative;
		&.active{ color: #FFFFFF;
			&::after{ content:'';position: absolute;bottom: -10px;width: 16px;height: 2px;background-color: #FFFFFF;left: calc(50% - 8px);}
		}
	}
}
.loading_more{ flex: 1;background-color: #FFFFFF;display: flex;align-items: center;justify-content: center;}
.c-item {
	display: flex;
	flex-wrap: wrap;
	width: 680rpx;
	background-color: #ffffff;
	box-shadow: 0px 6rpx 32rpx 0px rgba(0, 35, 68, 0.14);
	border-radius: 34rpx;
	margin: 0 auto 32rpx;
	padding: 10px 0;
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
		display: flex;
		align-items: center;
		justify-content: center;
		padding-right: 10px;
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
	.item_btn{ padding-top: 10px;width: 100%;display: flex;justify-content: flex-end;padding-right: 15px;
		button{font-size: 10px;background-color: $unified-color;color: #FFFFFF;padding: 0 5px;width: 120px;margin: 0;}
	}
}
</style>
