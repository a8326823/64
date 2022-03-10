<template>
	<view class="header_top">
		<public-head headshow :title="i18n.title" :btntxt="i18n.titlebtn" v-on:initbtn="btn_on"></public-head>
		<form @submit="formSubmit">
			<view class="bank_form">
				<!-- <view class="form_item">
					<view class="form_title">{{i18n.formtitle1}}</view>
					<view class="form_input">
						<input type="text" :value="countrytxt" disabled class="active" :placeholder="i18n.formtitle1_txt" placeholder-class="place_txt"
						   name="level" />
					</view>
				</view> -->
				<view class="form_mune" :class="'active'+muneindex">
				  <view class="mune_item"
					:class="{active:muneindex==index}"
					@click="muneclick(index)" v-for="(item,index) in munelist" :key="index">{{index == 0 ? i18n[item.name] : "USDT"}}</view>
				</view>
				<view class="formImg">
					<image :src="munelist[muneindex].image"></image>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.formtitle2}}</view>
					<view class="form_input">
						<input type="text" name="trade_pass" :placeholder="i18n.formtitle2_txt" />
					</view>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.formtitle3}} <text class="color_red" v-show="muneindex == 1">≈{{exchangerate | formatPrice}}</text></view>
					<view class="form_input">
						<input type="number" v-model="amount" name="amount" :placeholder="i18n.formtitle3_txt" />
					</view>
				</view>
				<view class="form_item itemflex">
					<view>{{i18n.formprice}}：<text class="color_red">{{changeprice.balance | formatPrice}}</text></view>
					<!-- <view><navigator class="btn_red" :url="'/pages/vip/yuebao?price='+changeprice.balance" hover-class="none">{{i18nyue.title}}</navigator></view> -->
				</view>
				<!-- <view class="form_item">
					{{i18n.priceinfo}}：<text class="color_red">{{exchangerate}}</text>
				</view> -->
				<view class="tip2">
					<image src="/static/images/profile/icon_tishi.png" mode=""></image>
					<text class="t-text1">{{i18n.forminfo1}}{{limit.high_limit}}{{i18n.company}}{{i18n.forminfo2}}{{limit.low_limit}}{{i18n.company}}{{i18n.forminfo3}}{{limit.day_count}}{{i18n.frequency}}</text>
				</view>
				<!-- <view class="tip2">
					<image src="/static/images/profile/icon_tishi.png" mode=""></image>
					<text class="t-text1">{{drawalinfo}}</text>
				</view> -->
			</view>
			<button class="btn" form-type="submit">{{i18n.formbtn}}</button>
		</form>
		<picker-show :pickerlist="tasklist" @btnconfirm="bindconfirm" ref="showpicker"></picker-show>
	</view>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
	data() {
		return {
			isclick:true,
			muneindex:0,
			munelist:[{title:"银行卡",name:"bankpay",type: 1,image:"/static/images/wallet/transfer.png"},
					  {title:"USDT",name:"account",type: 2,image:"/static/images/wallet/usdt.png"}],
			drawalinfo:'',
			tasklist:[],
			pickerarr:'',
			countryjson:'',
			countrytxt:'',
			limit:{},
			amount:''
		};
	},
	computed: {
		...mapGetters(['changeprice']),
		exchangerate(){
			return Number(this.amount)*Number(this.countryjson.exchange_rate) || '';
		},
		i18n(){
			return this.$t('withdrawalpay');
		},
		i18nyue(){
			return this.$t('orderyuebao');
		}
	},
	filters: {
		formatCard(val) {
			return val.slice(-4)
		}
	},
	methods: {
		bindconfirm(e,listid){
			this.countrytxt = e;
			this.countryjson = this.pickerarr[listid];
		},
		grad_picker(){
			const that = this;
			this.$http.requestajx('country','get',{}).then((res) => {
				let data = res.result;
				that.pickerarr = data;
				that.tasklist = data.map((item)=>{
					return item.name;
				});
				that.$refs.showpicker.picker_open();
			});
		},
		muneclick(index){
		  this.muneindex = index;
		},
		getwithdrawallist(){
			const that = this;
			this.$http.requestajx('user_withdrawal/limit','get',{}).then((res) => {
				let data = res.result;
				that.limit = data;
			});
			this.$http.requestajx('country','get',{}).then((res) => {
				let data = res.result;
				that.pickerarr = data;
				that.tasklist = data.map((item)=>{
					return item.name;
				});
				that.countrytxt = data[0].name;
				that.countryjson = data[0];
			});
		},
		formSubmit(e){
			if(this.isclick){
				this.isclick = false;
			}else{
				return this.isclick;
			}
			let formdata = e.detail.value,that = this;
			 this.$http.requestajx('user_withdrawal','post',{
				country_id:this.countryjson.id,
				trade_pass:formdata.trade_pass,
				amount:Number(formdata.amount)
			}).then((res) => {
				// console.log(res);
				let data = res;
				if(data.code === 200){
					that.totat('提现申请成功！');
					setTimeout(()=>{
						uni.navigateBack();
					},1500)
				}
				
			 }).catch((error) => {
				console.log(error);
			 });
			 setTimeout(()=>{
				 that.isclick = true;
			 },5000);
			 
		},
		btn_on() {
			uni.navigateTo({
				url:'/pages/profile/withdraw-record'
			})
		}
	},
	onReady() {
		this.getwithdrawallist()
	},
	onShow() {
		this.getwithdrawallist();
		
	}
};
</script>

<style lang="scss">
.bank_form{ padding: 30px 30px 0;}
.form_item{margin-bottom: 22px;
	.form_title{ font-size: 30rpx;color: #333;margin-bottom: 18rpx;
		uni-text{ margin-left: 5px;}
	}
	uni-input{	height: 35px;border-radius: 3px;border: solid 1px #e8e8e8;color: #c1c1c1;padding: 0 8px;
		&.active{ background-color: #F5F5F5;color: #848080;
			.place_txt{color: #848080;}
		}
	}
	.place_txt{ font-size: 30rpx;color: #c1c1c1;}
	.color_red{ color:#f62f3d;font-size: 28rpx;}
	&.itemflex{ display: flex;justify-content: space-between;}
	.btn_red{color: $unified-color;text-decoration:underline;}
}
.account {
	display: flex;
	align-items: center;
	width: 100%;
	height: 94rpx;
	margin-top: 88rpx;
	.account-left {
		width: 70rpx;
		height: 70rpx;
		margin: 0 26rpx 0 46rpx;
	}
	.account-center {
		height: 94rpx;
		flex: 1;
		display: flex;
		flex-direction: column;
		justify-content: center;
		.c-text1 {
			color: #262626;
			font-size: 32rpx;
		}
		.c-text2 {
			color: #808080;
			font-size: 28rpx;
			margin-top: 26rpx;
		}
	}
	.account-right {
		width: 12rpx;
		height: 22rpx;
		margin-right: 50rpx;
	}
}
.money {
	width: 100%;
	height: 146rpx;
	padding: 0 58rpx;
	margin-top: 76rpx;
	.money-title {
		color: #333333;
		font-size: 28rpx;
		margin-bottom: 26rpx;
	}
	.money-con {
		display: flex;
		height: 88rpx;
		align-items: center;
		border-bottom: 1px solid #ececec;
		.rmb {
			font-size: 66rpx;
			color: #333;
		}
		input {
			font-size: 50rpx;
			color: #333;
		}
	}
}
.tip1 {
	display: flex;
	align-items: center;
	width: 100%;
	height: 36rpx;
	padding-left: 58rpx;
	margin-top: 66rpx;
	.t-text1 {
		color: #333333;
		font-size: 26rpx;
	}
	.t-text2 {
		color: #f9103c;
		font-size: 34rpx;
	}
}
.tip2 {
	display: flex;
	width: 100%;
	height: 36rpx;
	margin-top: 50rpx;
	padding: 0 30rpx 0 58rpx;
	image {
		width: 20rpx;
		height: 20rpx;
		border-radius: 50%;
		margin-right: 12rpx;
	}
	.t-text1 {
		flex: 1;
		color: #b3b3b3;
		font-size: 24rpx;
		line-height: 32rpx;
	}
}
.btn {
	margin-top: 125px;
	&.active{ background-color: #eee;}
}
.formImg{ text-align: center;padding: 13px 0 30px;
	uni-image{ width: 50px;height: 50px;}
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
</style>
