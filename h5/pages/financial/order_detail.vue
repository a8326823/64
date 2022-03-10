<template>
	<view class="header_top">
		<public-head headshow :title="i18n.title"></public-head>
		<view class="order_content">
			<view class="order_list">
				<view class="list_item">
					<view class="item_title">
						<view class="">{{i18n.title}}</view>
					</view>
					<view class="item_ul">
						<view class="item_li">
							<view class="item_name">{{i18n.munetitle}}：</view>
							<view class="item_txt">{{munedetail.order_no}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">{{i18n.munelist[0]}}：</view>
							<view class="item_txt">{{munedetail.mode}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">{{i18n.munelist[1]}}：</view>
							<view class="item_txt">{{munedetail.income_mode}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">{{i18n.munelist[2]}}：</view>
							<view class="item_txt">{{munedetail.daily_interest_rate}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">{{i18n.munelist[3]}}：</view>
							<view class="item_txt">{{munedetail.amount}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">{{i18n.munelist[4]}}：</view>
							<view class="item_txt">{{munedetail.profit}}</view>
						</view>
					</view>
				</view>
				<view class="list_item">
					<view class="item_ul">
						<view class="item_li">
							<view class="item_name">{{i18n.datatime[0]}}：</view>
							<view class="item_txt color_red" v-if="munedetail.unfreeze_time!==null">{{munedetail.unfreeze_time}}</view>
							<view class="item_txt color_red" v-else>{{i18n.muneinfo}}</view>
						</view>
						<view class="item_li">
							<view class="item_name">{{i18n.datatime[1]}}：</view>
							<view class="item_txt">{{munedetail.created_at}}</view>
						</view>
					</view>
				</view>
			</view>
			<view class="order_btn" v-if="issettle===0">
				<button @click="sellsubmit">{{i18n.subtn}}</button>
			</view>
		</view>
	</view>
</template>

<script>
	export default{
		data(){
			return{
				orderid:'',
				munedetail:''
			}
		},
		computed:{
			i18n(){
				return this.$t('orderdetail');
			},
			issettle(){
				return this.munedetail.is_settle;
			}
		},
		methods:{
			loadjson(orderid){
				this.$http.requestajx('mf/order/'+orderid,'get',{}).then((res) => {
					// console.log(res)
					let data = res.result;
					this.munedetail = data;
				});
			},
			sellsubmit(){
				const that = this;
				this.$http.requestajx('mf/sell','post',{order_no:this.munedetail.order_no}).then((res) => {
					// console.log(res)
					if(res.code === 200){
						that.munedetail.is_settle = 0;
						that.totat(that.i18n.modesucc);
					}
				});
			}
		},
		onShow() {
			this.loadjson(this.orderid);
		},
		onLoad(option) {
			this.orderid = option.orderid;
			this.loadjson(option.orderid);
		}
	}
</script>

<style lang="scss">
	page{ background-color: #F7F9FC!important;}
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
		.item_title{ padding: 12px 15px;border-bottom: 1px solid #f0f1f6;font-size: 35rpx;color: #000000;display: flex;justify-content: space-between;}
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
