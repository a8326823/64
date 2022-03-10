<template>
	<view class="header_top pageback">
		<public-head headshow title="我的设备"></public-head>
		<view class="order_content">
			<view class="order_list">
				<view class="list_item" v-for="(item,index) in muneList" :key="index">
					<view class="item_title">
						<view class="">订单号：{{item.financial_product_id}}</view>
						<view class=""><image src="/static/images/vip/o1.png"></image> </view>
					</view>
					<view class="item_ul">
						<view class="item_li">
							<view class="item_name">产品名称：</view>
							<view class="item_txt">{{item.financial_products.name}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">转换次数：</view>
							<view class="item_txt">{{item.financial_products.withdrawal_times}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">到期时间：</view>
							<view class="item_txt">{{item.financial_products.created_at}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">当前收益：</view>
							<view class="item_txt">{{item.financial_products.total_issue}}</view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default{
		data(){
			return{
				muneList:[]
			}
		},
		computed:{
			i18n(){
				return this.$t('orderdetail');
			}
		},
		methods:{
			getload(){
				this.$http.requestajx('user_financial_products','get',{}).then((res) => {
					// console.log(res)
					let data = res.result.data;
					this.muneList = data;
				});
			}
		},
		onLoad(option) {
			this.getload();
		}
	}
</script>

<style lang="scss">
	.pageback{ background-color: #F7F9FC;min-height: 100vh;}
	.order_content{ padding-bottom: 20px;}
	.order_list{ padding: 0 15px;position: relative;}
	.order_mune{ display: flex;background-color: #FFFFFF;margin-top: 1px;
		.mune_item{ flex: 1;text-align: center;padding: 17px 0;
			text{position: relative;font-size: 26rpx;color: #333333;
				&.active::after{ content: '';position: absolute;width: 25px;height: 3px;bottom: -17px;left: calc(50% - 13px);
	background-image: linear-gradient(#1b2664, #1b2664),linear-gradient(#fbb246, #fbb246);background-blend-mode: normal, normal;
	box-shadow: 0px 6px 12px 0px rgba(191, 201, 255, 0.59);border-radius: 3px;}
			}
		}
	}
	.list_item{ margin-top: 10px;border-radius: 10px;background-color: #ffffff;
		.item_title{ padding: 12px 15px;border-bottom: 1px solid #f0f1f6;font-size: 24rpx;color: #000000;display: flex;justify-content: space-between;align-items: center;
			uni-image{ width: 15px;height: 15px;}
		}
		.item_ul{ padding: 15px;
			.item_li{ font-size: 28rpx;display: flex;justify-content: space-between;
				.item_name{ color:#666666;}
				.item_text{ color:#000000;}
				&+.item_li{ margin-top: 12px;}
				.color_red{ color:#FA4A80;}
			}
		}
	}
	.order_btn{ position: absolute;bottom: 40px;left: 0;width: 100%;padding: 0 20px;
		button{background-color: $unified-color;font-size: 32rpx;color: #FFFFFF;padding: 5px 0;}
	}
</style>
