<template>
	<view style="height: 100vh;display: flex;flex-direction: column;" class="header_top">
		<loading-plus v-if="beforeReady"></loading-plus>
		<public-head headshow :title="i18n.title"></public-head>
		<fh-tab-control :tabItem="i18n.titlelist" 
			:isFixed="false" 
			:tabIndex="tabIndex"
			 :sliderWidth="60" 
			 :sliderHeight="8"
			 :sliderRadius="10"
			 :bottom="0" @itemClick="itemClick"></fh-tab-control>
		
		
		<view class="bgd" style="flex: 1;background-color: #f5f7fd;display: flex;align-items: center;justify-content: center;" v-if="isShow">
			<uni-load-more status="loading"></uni-load-more>
		</view>
		<swiper :current="swiperIndex"  :duration="150" style="flex: 1;" @change="onChangeTab" v-if="!isShow">
			<swiper-item style="display: flex;" v-for="(ite,index) in 2" :key="index">
				<scroll-view style="flex: 1;" scroll-y="true" @scrolltolower="loadmore()">
					<view class="notice-list" v-if="pageData.data.length !== 0">
						<block v-for="(item, index) in pageData.data" :key="index">
							<view class="list_item" @click="navTo('/pages/financial/order_detail?orderid='+item.order_no)">
								<view class="item_title">
									<view class="">{{i18n.munetitle}}：{{item.order_no}}</view>
									<view class=""><image src="/static/images/vip/o1.png" style="width: 15px;height: 15px;"></image> </view>
								</view>
								<view class="item_ul">
									<view class="item_li">
										<view class="item_name">{{i18n.munelist[0]}}：</view>
										<view class="item_txt">{{item.mode}}</view>
									</view>
									<view class="item_li">
										<view class="item_name">{{i18n.munelist[1]}}：</view>
										<view class="item_txt">{{item.income_mode}}</view>
									</view>
									<view class="item_li">
										<view class="item_name">{{i18n.munelist[2]}}：</view>
										<view class="item_txt">{{item.daily_interest_rate}}</view>
									</view>
									<view class="item_li">
										<view class="item_name">{{i18n.munelist[3]}}：</view>
										<view class="item_txt">{{item.amount}}</view>
									</view>
									<view class="item_li">
										<view class="item_name">{{i18n.munelist[4]}}：</view>
										<view class="item_txt">{{item.unfreeze_time}}</view>
									</view>
									<view class="item_li">
										<view class="item_name">{{i18n.munelist[5]}}：</view>
										<view class="item_txt">{{item.profit}}</view>
									</view>
								</view>
							</view>
						</block>
						
						<uni-load-more :status="status" v-if="pageData.data.length > 10"></uni-load-more>
						
					</view>
					<view style="text-align: center;" v-else>
						<image src="/static/images/index/pic_zanwu.png" style="width: 450rpx;height: 230rpx;margin-top: 400rpx;"></image>
						<view style="color: #615f60;font-size: 28rpx;margin-top: 50rpx;">{{i18n.listinfo}}</view>
					</view>
				</scroll-view>
			</swiper-item>
		</swiper>
	</view>
</template>

<script>
import loading from '@/utils/mixin/loading.js';
export default {
	mixins: [loading],
	data() {
		return {
			titlelist:['收益中','已结算'],
			type: 1,
			tabIndex: 0,
			swiperIndex:0,
			page: 1,
			pageData: {
				data: []
			},
			status: 'more',
			isShow:false
		};
	},
	computed:{
		i18n(){
			return this.$t('orderlist');
		}
	},
	methods: {
		navTo(url) {
			uni.navigateTo({ url });
		},
		getData() {
			const that = this;
			this.$http.requestajx('mf/order','get',{page:this.page}).then((res) => {
				// console.log(res.result.data);
				if (this.page === 1) {
					this.pageData = res.result;
					this.pageData.data = res.result.data.filter(function(item,index,arr){
						return item.is_settle === that.tabIndex;
					});
				} else {
					this.pageData.data.push(...res.result.data);
				}
			});
			/* let res = await this.$http.userNotify({
				type: this.type,
				page: this.page
			}); */
			
		},
		itemClick(index) {
			this.isShow = true
			setTimeout(()=>{
				this.isShow = false
			},1000)
			this.tabIndex = index
			this.swiperIndex = index
			this.page = 1;
			this.type = index + 1;
			this.getData();
		},
		onChangeTab(e) {
			this.itemClick(e.detail.current)
		},
		loadmore() {
			if (this.pageData.last_page === 1) return;
			setTimeout(() => {
				this.status = 'loading';
				if (this.page >= this.pageData.last_page) {
					return (this.status = 'noMore');
				}
				this.page++;
				this.getData();
			},500)
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
	background-color: #f5f7fd;
	overflow: hidden;
}
.notice-list {padding: 0 15px 15px;
	.list_item{ margin-top: 10px;border-radius: 10px;background-color: #ffffff;
		.item_title{ padding: 12px 15px;border-bottom: 1px solid #f0f1f6;font-size: 24rpx;color: #000000;display: flex;justify-content: space-between;align-items: center;}
		.item_ul{ padding: 15px;
			.item_li{ margin-bottom: 12px;font-size: 28rpx;display: flex;justify-content: space-between;
				.item_name{ color:#666666;}
				.item_text{ color:#000000;}
			}
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
