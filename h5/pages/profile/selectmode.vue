<template>
	<view class="header_top">
		<public-head :title="i18n.selectmode" btntxt="记录" v-on:initbtn="navTo"></public-head>
		<view class="pay_content">
			<view class="form_mune" :class="'active'+muneindex">
			  <view class="mune_item"
				:class="{active:muneindex==index}"
				@click="muneclick(index)" v-for="(item,index) in munelist" :key="index">{{index == 0 ? i18n[item.name] : "USDT"}}</view>
			</view>
			<view class="usdtAddress" v-show="muneindex == 1">
				TRC20: <text>{{usdtAddress}}</text> <image src="/static/images/wallet/copy.png" @click="onCopyResult"></image>
			</view>
		</view>
		<view class="selectPrice">
			<view class="headtitle">选择充值金额</view>
			<uni-row :gutter="20">
				<uni-col :span="6" v-for="(item,index) in selectPrice" :key="index">
					<view class="item" :class="{'active': selectIndex == index}" @click="selectIndex = index">${{item}}</view>
				</uni-col>
			</uni-row>
		</view>
		<view class="payForm">
			<form @submit="formSubmit">
				<view class="bank_form">
					<view class="form_item">
						<view class="form_title">{{i18npay.pyaform2}}</view>
						<view class="form_input">
							<input type="number" v-model="amount" name="amount" :placeholder="i18npay.pyaform2_txt" />
						</view>
					</view>
					<view class="form_item">
						{{i18n.balance}}：<text class="color_red">{{changeprice.balance | formatPrice}}</text>
					</view>
				</view>
				
				<button class="btn" form-type="submit">{{i18n.formbtn}}</button>
			</form>
		</view>
	</view>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
	data() {
		return {
			isclick:false,
			muneindex:0,
			munelist:[{title:"银行卡",name:"paybank",type: 1,image:"/static/images/wallet/transfer.png"},
					  {title:"USDT",name:"account",type: 2,image:"/static/images/wallet/usdt.png"}],
			selectIndex:0,
			selectPrice:[10,100,200,500,1000,5000,10000,50000],
			usdtAddress:"sfsdfdsf55sdfsd",
			amount:null
		};
	},
	computed:{
		...mapGetters(['changeprice']),
		i18n(){
			return this.$t('payselect');
		},
		i18npay(){
			return this.$t('bankpay');
		}
	},
	methods: {
		navTo(){
			uni.navigateTo({
			    url: '/pages/profile/pay-record'
			});
		},
		muneclick(index){
			this.muneindex = index;
			// this.navTo();
		},
		onCopyResult(){
			let that = this;
			// #ifdef H5
			this.$copyText(this.usdtAddress).then( res => {
				that.totat(that.$t("payrecharge.succ"));
			});
			// #endif
			 // #ifndef H5
				uni.setClipboardData({
					data: this.usdtAddress,
					success: () => {
						that.totat(that.$t("payrecharge.succ"));
					}
				})
			// #endif
		},
		formSubmit(e){
			if(this.isclick){
				this.isclick = false;
			}else{
				return this.isclick;
			}
			let formdata = e.detail.value,that = this;
			 this.$http.requestajx('user_recharge/onlineRecharge','post',{
				country_id:this.countryjson.id,
				amount:Number(formdata.amount)
			}).then((res) => {
				// console.log(JSON.stringify(res))
				// #ifdef APP-PLUS
					plus.runtime.openURL(encodeURI(res.result.pay_url));
				// #endif
				// #ifdef H5
					setTimeout(function(){
						window.open(res.result.pay_url,'_blank');
					},100);
				// #endif
				
			 }).catch((error) => {
				console.log(error);
			 });
			 setTimeout(()=>{
			 	that.isclick = true;
			 },5000);
		}
	},
	onShow() {
		
	}
};
</script>

<style lang="scss">
.pay_content{ padding: 15px 30px;}
.usdtAddress{ font-size: 14px;color: #333;display: flex;align-items: center;
	uni-text{ color: #999999;margin-left: 5px;}
	uni-image{ width: 15px;height: 15px;margin-left: 5px;}
}
.form_mune{ border: 4px solid #F5F5F5;border-radius: 6px;overflow: hidden;display: flex;align-items: center;height: 38px;position: relative;
	background-color: #F5F5F5;margin-bottom: 17px;
	.mune_item{ flex: 1;text-align: center;position: relative;z-index: 2;transition-duration:0.2s;color: #a3a3a3;
	  &.active{ color: #fff;}
	}
	&::before{ content: '';transition-duration:0.3s;position: absolute;left: 0;top: 0;width: 50%;height: 100%;border-radius: 5px;z-index: 1;
	background-color: #D8D8D8;}
	&.active{
	  &::before{ left: 0;}
	}
	&.active1{
	  &::before{ left: 50%;}
	}
	&.active2{
	  &::before{ left: 66%;}
	}
}
.selectPrice{ padding: 0 30px;
	.headtitle{ font-size: 15px;color: #333;margin-bottom: 9px;}
	.item{ border-radius: 5px;height: 45px;line-height: 45px;color: #333;margin-bottom: 10px;text-align: center;border: 1px solid $uni-color-purple;
		&.active{ background-color: $uni-color-purple;color: #FFFFFF;}
	}
}
.bank_form{ padding: 20px 30px 0;}
.form_item{margin-bottom: 22px;
	.form_title{ font-size: 30rpx;color: #333;margin-bottom: 18rpx;}
	input{	height: 35px;border-radius: 3px;border: solid 1px #e8e8e8;color: #c1c1c1;padding: 0 8px;
		&.active{ background-color: #F5F5F5;color: #848080;
			.place_txt{color: #848080;}
		}
	}
	.place_txt{ font-size: 30rpx;color: #c1c1c1;}
	.color_red{ color:#f62f3d;font-size: 28rpx;}
}

</style>
