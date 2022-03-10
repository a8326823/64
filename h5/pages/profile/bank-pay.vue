<template>
	<view class="header_top">
		<public-head :title="i18n.title"></public-head>
		<form @submit="formSubmit">
			<view class="Delivery">
				<view class="Delivery-title">
					<image src="/static/images/profile/order1.png" mode=""></image>
					<text>{{i18n.title}}</text>
				</view>
				<view class="d-list">
					<view class="l-left">{{i18n.pyaname1}}:&nbsp;{{bankInfo.bank_name}}</view>
					<view class="l-right" @click="clip(bankInfo.bank_name)">{{i18n.pyacopy}}</view>
				</view>
				<view class="d-list">
					<view class="l-left">{{i18n.pyaname2}}:&nbsp;{{bankInfo.name}}</view>
					<view class="l-right" @click="clip(bankInfo.name)">{{i18n.pyacopy}}</view>
				</view>
				<view class="d-list">
					<view class="l-left">{{i18n.pyaname3}}:&nbsp;{{bankInfo.bank_account}}</view>
					<view class="l-right" @click="clip(bankInfo.bank_account)">{{i18n.pyacopy}}</view>
				</view>
				<view class="bank_form">
					<view class="form_item">
						<view class="form_title">{{i18n.pyaform1}}</view>
						<view class="form_input">
							<input type="text" :value="countrytxt" disabled class="active" :placeholder="i18n.pyaform1_txt" placeholder-class="place_txt"
							   name="level" @click="grad_picker" />
						</view>
					</view>
					<view class="form_item">
						<view class="form_title">{{i18n.pyaform2}}</view>
						<view class="form_input">
							<input type="number" v-model="pyamoney" name="amount" :placeholder="i18n.pyaform2_txt" />
						</view>
					</view>
					<view class="form_item">
						<view class="form_title">{{i18n.pyaform3}}</view>
						<view class="form_input">
							<input type="text" name="bank" :placeholder="i18n.pyaform3_txt" />
						</view>
					</view>
					<view class="form_item">
						<view class="form_title">{{i18n.pyaform4}}</view>
						<view class="form_input">
							<input type="text" name="name" :placeholder="i18n.pyaform4_txt" />
						</view>
					</view>
					<view class="form_item">
						<view class="form_title">{{i18n.pyaform5}}<!-- ：<text class="money">{{exchangerate}}</text> --></view>
						<view class="form_input">
							<input type="number" name="remittance" :placeholder="i18n.pyaform5_txt" />
						</view>
					</view>
					<view class="form_item">
						<view class="form_title">{{i18n.pyaform6}}</view>
						<view class="form_input">
							<input type="text" name="bank_name" :placeholder="i18n.pyaform6_txt" />
						</view>
					</view>
				</view>
			</view>
			<button class="btn" form-type="submit">{{i18n.pyaformbtn}}</button>
		</form>
		<picker-show :pickerlist="tasklist" @btnconfirm="bindconfirm" ref="showpicker"></picker-show>
	</view>
	
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				isclick:true,
				bankInfo:{},
				tasklist:[],
				pickerarr:'',
				pyamoney:'',
				countryjson:'',
				countrytxt:'',
				countrymoney:''
			};
		},
		computed:{
			i18n(){
				return this.$t('bankpay');
			},
			i18n_share(){
				return this.$t('invitation');
			},
			exchangerate(){
				return Number(this.pyamoney)*Number(this.countryjson.exchange_rate);
			}
		},
		methods: {
			loadjosn(){
				const that = this;
				this.$http.requestajx('country/country_bank','get',{country_id:9}).then((res) => {
					console.log(res)
					that.bankInfo = res.result.bank;
					that.countryjson = res.result.country;
					that.countrytxt = res.result.country.name;
				})
				
			},
			getReceivingBank(e) {
				let that = this;
				this.bankInfo = "";
				this.$http.requestajx('country/bank','get',{country_id:e.id}).then(function(res){
					// console.log(res);
					that.bankInfo = res.result;
				})
			},
			bindconfirm(e,listid){
				this.countrytxt = e;
				this.countryjson = this.pickerarr[listid];
				this.getReceivingBank(this.countryjson);
			},
			grad_picker(){
				let that = this;
				this.$http.requestajx('country','get',{}).then((res) => {
					let data = res.result;
					that.pickerarr = data;
					that.tasklist = data.map((item)=>{
						return item.name;
					});
					that.$refs.showpicker.picker_open();
				});
				
			},
			formSubmit(e){ 
				if(this.isclick){
					this.isclick = false;
				}else{
					return this.isclick;
				}
				let formdata = e.detail.value,that = this;
				 this.$http.requestajx('user_recharge/bank_recharge','post',{
					country_id:Number(this.countryjson.id),
					name:formdata.name,
					bank:formdata.bank,
					bank_name:formdata.bank_name,
					remittance:Number(formdata.remittance),
					amount:Number(formdata.amount)
				}).then((data) => {
					// console.log(data);
					/* uni.switchTab({
						url: '/pages/tabbar/index'
					}); */
					if(data.code === 200){
						that.totat('充值成功！');
						setTimeout(()=>{
							uni.navigateBack()
						},1500)
					}else{
						that.totat(data.message);
					}
					
				 }).catch((error) => {
					console.log(error);
				 });
				 setTimeout(()=>{
				 	that.isclick = true;
				 },5000);
			},
			clip(res) {
				let that = this;
				// #ifdef H5
				this.$copyText(res).then( data => {
					that.totat(that.i18n_share.copysucc);
				});
				// #endif
				// #ifndef H5
					uni.setClipboardData({
						data: res,
						success: () => {
							that.totat(that.i18n_share.copysucc);
						}
					})
				// #endif
			}
		},
		onShow() {
			
		},
		onLoad(options) {
			this.price = options.price
			this.vip = options.vip
			this.loadjosn();
		}
	}
</script>

<style lang="less">
	page {
		background-color: #F8F8F8;
	}
	.bank_form{ padding: 10px 20px 0 5px;}
	.form_item{
		margin-bottom: 22px;
		.form_title{ font-size: 30rpx;color: #333;margin-bottom: 18rpx;}
		input{	height: 35px;border-radius: 3px;border: solid 1px #e8e8e8;color: #c1c1c1;padding: 0 8px;
			&.active{ background-color: #F5F5F5;color: #848080;
				.place_txt{color: #848080;}
			}
		}
		.place_txt{ font-size: 30rpx;color: #c1c1c1;}
		.money{ color:#f62f3d;font-size: 28rpx;}
	}
		.Delivery {
			width: 710rpx;
			background: #FFFFFF;
			opacity: 1;
			border-radius: 20rpx;
			margin: 40rpx auto 0;
			overflow: hidden;
			padding: 0 14rpx;
			.Delivery-title {
				display: flex;
				align-items: center;
				margin: 30rpx 0 18rpx 6rpx;
				
				image {
					width: 34rpx;
					height: 34rpx;
				}
				
				text {
					font-size: 34rpx;
					font-weight: 800;
					margin-left: 26rpx;
				}
			}
			.d-list {
				display: flex;
				align-items: center;
				justify-content: space-between;
				height: 88rpx;
				.l-left {
					display: flex;
					align-items: center;
					margin-left: 10rpx;
					color: #333;
					font-size: 30rpx;
					input {
						margin-left: 20rpx;
						width: 380rpx;
					}
				}
				.l-right {
					margin-right: 40rpx;
					color: #999;
					font-size: 30rpx;
				}
			}
		}
		.btn {
			margin-top: 100rpx;margin-bottom: 30px;
		}
</style>
