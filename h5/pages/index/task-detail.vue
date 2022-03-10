<template>
	<view>
		<loading-plus v-if="isLoading"></loading-plus>
		<public-head :headshow="false" title="" backcolor="transparent" color_icon="#fff"></public-head>
		<view class="task_banner">
			<image :src="$configurl.ossBaseUrl+task_detail.category.banner" mode="widthFix"></image>
		</view>
		<view class="content">
			<view class="c-title">{{task_detail.title}}</view>
			<view class="c-content">
				<view class="c-left">
					<view class="list1">{{task_detail.user_complete_count}}{{i18n.detailtxt}}</view>
					<view class="list2">
						<text>{{i18n.detailmony[0]}}{{task_detail.num}}{{i18n.detailmony[1]}}</text>
						<text>48{{i18n.dateinfo}}</text>
					</view>
				</view>
				<view class="c-right">{{task_detail.amount | formatPrice}}</view>
			</view>
			<view class="c-tip" @click="navTo('/pages/index/video_detail')">
				<view class="tip-l">{{i18n.detailinfo}}</view>
				<view class="tip-r">
					<view class="r-text uni-ellipsis-2">{{i18n.detailbtn}}</view>
					<image src="/static/images/index/icon_fanhui.png" mode=""></image>
				</view>
			</view>
			<view class="c-select">
				<view class="select-item uni-ellipsis" v-for="(item,index) in i18n.detaillist" :key="index">
					<image src="/static/images/index/icon_gouxuan.png" mode=""></image>
					<text>{{item}}</text>
				</view>
			</view>
		</view>
		<view class="shop-info">
			<view class="list2-name">{{task_detail.url}}</view>
			<view class="list2-btn">
				<view class="btn2" @click="toNext">{{i18n.detailhref}}</view>
			</view>
		</view>
		<view class="shop_list">
			<view class="list_title">
				<view class="title_head">{{i18ninfo.listhead[0].title}}</view>
				<text>{{i18ninfo.listhead[0].txt}}</text>
			</view>
			<view class="list_mume">
				<view class="mune_item" v-for="(item,index) in i18ninfo.listtxt" :key="index">
					<image src="/static/images/index/taskd1.png"></image>
					<view class="item_title">{{item.title}}</view>
					<view class="item_text">{{item.txt1}}</view>
				</view>
			</view>
		</view>
		<view class="show-img">
		</view>
		<view class="receive-btn" :class="{active:!isclick}" @click="receive">{{i18n.detailsub}}</view>
		<popu-modal v-model="value" :mData="defaultData" :type="type" @cancel="tapConfirm" navMask></popu-modal>
	</view>
</template>

<script>
import config from '@/service/config.js'
export default {
	data() {
		return {
			isclick:true,
			task_id:null,
			value:false,
			type:'default',
			defaultData:{content:'Dear member, the system has started the automatic intelligent task robot service for you, please click to confirm the startup program',cancelText:'cancel'},
			id:'',
			listhead:[{title:'任务步骤',txt:'安卓详细步骤如下所示，轻松完成任务'},{title:'操作流程',txt:'苹果版操作如下'}],
			listtxt:[{title:'如何操作',txt1:'第一步在任务详情领取任务，点击打开按钮',txt2:'点击打开按钮'},
					 {title:'选择任务',txt1:'点击打开之后右下角打开抖音抖音打开后点击赞',txt2:''},
					 {title:'操作任务',txt1:'完成任务（点赞）并截图',txt2:''},
					 {title:'完成任务',txt1:'按照指示提交任务并上传任务完成',txt2:''}],
			munetable:[{title:'点击打开抖音任务按钮，点击右下角',txt1:'领取任务',txt2:'完成点赞任务（点赞）'},
						{title:'按照指示提交任务，提交任务',txt1:'截图留作凭证',txt2:'上传凭证'}],
			table_info:'任务完成',
			task_detail:{
				category:{
					banner:''
				}
			},
			isLoading: true
		};
	},
	computed:{
		banner() {
			return this.task_detail.category.banner === '' ? "" : config.ossBaseUrl+this.task_detail.category.banner
		},
		i18n(){
			return this.$t('memberhall');
		},
		i18ninfo(){
			return this.$t('taskdetail');
		}
	},
	methods: {
		navTo(url) {
			uni.navigateTo({ url });
		},
		async getDetail() {
			let res = await this.$http.taskDetail(this.id);
			this.task_detail = res.result;
		},
		toNext() {
			// #ifdef APP-PLUS
				plus.runtime.openWeb(this.task_detail.url);
			// #endif
			// #ifdef H5
				window.open(this.task_detail.url)
			// #endif
		},
		async tapConfirm(){
			let res = await this.$http.taskSubmit({
				id:this.task_id,
				image:this.$configurl.ossBaseUrl + this.task_detail.category.banner
			});
			this.totat('Robot trigger');
			this.value = false;
			uni.setStorageSync('robotboole', true);
		},
		async receive() {
			if(this.isclick){
				this.isclick = false;
			}else{
				return this.isclick;
			}
			let res = await this.$http.taskReceive({
				id : this.task_detail.id
			})
			this.task_id = res.result.id;
			this.totat(this.i18n.modesucc);
			// this.value = true;//机器人功能
		}
	},
	onShow() {
		this.getDetail()
		setTimeout(()=>{
			this.isLoading = false
		},1000)
	},
	onLoad(options) {
		this.id = options.id
	}
};
</script>

<style lang="scss">
$colororgin:#f39800;
$colorgray:#a2a2a2;
.banner {
	width: 100%;
	height: 418rpx;
	background-repeat: no-repeat;
	background-size: 100% 100%;
	.header {
		position: absolute;
		width: 100%;
		height: 128rpx;
		.back {
			position: absolute;
			left: 0;
			display: flex;
			align-items: center;
			width: 150rpx;
			height: 100%;
			image {
				width: 40rpx;
				height: 40rpx;
				margin-left: 40rpx;
			}
		}
	}
}
.shop_list{
	&+.shop_list{ border-top: 5px solid #F6F7F7;}
}
.list_title{ text-align: center;padding: 20px 0;
	.title_head{ font-size: 30rpx;color: $colororgin;margin-bottom: 10px;}
	text{ font-size:24rpx;color: $colorgray;}
}
.list_mume{ display: flex;justify-content: center;flex-wrap: wrap;
	.mune_item{ flex: 0 0 50%;text-align: center;padding: 0 20px;margin-bottom: 15px;
		image{ width: 60px;height: 60px;}
		.item_title{ color:$colororgin;font-size: 28rpx;margin-top: 10px;margin-bottom: 5px;}
		.item_text{ font-size: 22rpx;color: $colorgray;line-height: 18px;}
	}
}
.table_content{ padding-top: 10px;
	.munetable{ display: flex;justify-content: space-between;align-items: center;font-size: 26rpx;color: $colorgray;
		.table_item{ width: 50%;}
		.munetableleft{	position: relative;padding: 0 30px;text-align: center;line-height: 20px;
			&::after{ content:'';width: 50px;height: 1px;border-top: 1px solid $colorgray;position: absolute;right: -4px;bottom: 17px;}
		}
		.munetableright{ padding-left: 20px;
			.rtitle{ position: relative;
				&::after{ content:'';width: 15px;height: 15px;border-radius: 50%;border: 2px solid $colorgray;position: absolute;left: -25px;top: -3px;}
				&+.rtitle{ margin-top: 35px;
					&::before{ content:'';width: 1px;height: 28px;position: absolute;top: -31px;left: -16px;border-left: 2px dotted $colorgray;}
				}
				&.active{
					.icon{ border: 2px solid $colororgin;width: 15px;height: 15px;border-radius: 50%;display: inline-block;text-align: center;line-height: 13px;
						position: absolute;left: -25px;top: -3px;
						.icon_text{ border-radius: 50%;width: 7px;height: 7px;background-color: $colororgin;display: inline-block;}
					}
					&::after{ display: none;}
				}
			}
		}
		&+.munetable{ margin-top: 35px;
			.rtitle{ 
				&::before{ content:'';width: 1px;height: 28px;position: absolute;top: -31px;left: -16px;border-left: 2px dotted $colorgray;}
			}
		}
		
		
	}
}
.table_info{ text-align: center;color:$colororgin;padding-top: 30px;}
.content {
	width: 100%;
	height: 370rpx;
	border-bottom: 10rpx solid #f5f7fd;
	padding: 30rpx 0 0 40rpx;
	.c-title {
		width: 500rpx;
		font-size: 34rpx;
		color: #000;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.c-content {
		display: flex;
		width: 100%;
		height: 88rpx;
		margin-top: 26rpx;
		.c-left {
			flex: 1;
			display: flex;
			justify-content: center;
			flex-direction: column;
			border-right: 1px solid #d5d7d8;
			color: #898989;
			font-size: 24rpx;
			.list2 {
				margin-top: 24rpx;
			}
		}
		.c-right {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 204rpx;
			height: 100%;
			color: #ffa006;
			font-size: 36rpx;
		}
	}
	.c-tip {
		display: flex;
		width: 672rpx;
		height: 72rpx;
		border-radius: 14rpx;
		overflow: hidden;
		margin-top: 42rpx;
		.tip-l {
			flex: 1;
			background-color: #ebe2d1;
			color: #000201;
			font-size: 24rpx;
			line-height: 72rpx;
			padding-left: 22rpx;
		}
		.tip-r {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 97rpx;
			height: 72rpx;
			background-color: #3d3938;
			.r-text {
				width: 60rpx;
				font-size: 24rpx;
				color: #b59b6f;
			}
			image {
				width: 10rpx;
				height: 18rpx;
			}
		}
	}
	.c-select {
		display: flex;
		margin-top: 30rpx;
		.select-item {
			flex:1;
			image {
				width: 22rpx;
				height: 22rpx;
				margin-right: 6rpx;
			}
			text {
				font-size: 22rpx;
				color: #8f8f8f;
			}
		}
	}
}
.shop-info {
	display: flex;
	align-items: center;
	justify-content: space-between;
	width: 100%;
	height: 216rpx;
	border-bottom: 10rpx solid #f5f7fd;

	.list2-name {
		width: 370rpx;
		height: 70rpx;
		line-height: 70rpx;
		text-align: center;
		background-color: #f5f7fd;
		border-radius: 20rpx;
		margin-left: 40rpx;
		color: #8a8a8a;
		font-size: 22rpx;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.list2-btn {
		display: flex;
		flex-direction: column;
		font-size: 24rpx;
		margin-right: 40rpx;
		.btn2 {
			width: 197rpx;
			height: 62rpx;
			border-radius: 31rpx;
			line-height: 62rpx;
			text-align: center;
			background-color: $unified-color;
			color: #fff;
		}
	}
}
.show-img {
	border-top: 40rpx solid #fff;
	border-bottom: 120rpx solid #fff;
}
.receive-btn {
	position: fixed;
	bottom: 0;
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 96rpx;
	background-color: $unified-color;
	color: #fff;
	font-size: 32rpx;
	&.active{ background-color: #EEEEEE;}
}
</style>
