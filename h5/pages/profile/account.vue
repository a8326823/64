<template>
	<view class="account header_top">
		<public-head :title="i18n.title"></public-head>
		<view class="acc_form">
			<form @submit="formSubmit">
				<!-- <view class="form_item active" @click="grad_picker">
					<view class="form_title">选择国家</view>
					<view class="form_input text-right">
						{{countryicon}} <uni-icons :size="17" class="uni-icon-wrapper" color="#c5c5c5" type="arrowright" />
					</view>
				</view> -->
				<view class="form_item">
					<view class="form_title">{{i18n.formname}}</view>
					<view class="form_input">
						<input type="text" name="name" :placeholder="i18n.formname" placeholder-class="place" />
					</view>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.formbank}}</view>
					<view class="form_input">
						<input type="text" name="bank_name" :placeholder="i18n.formbanktxt" placeholder-class="place" />
					</view>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.formacc}}</view>
					<view class="form_input">
						<input type="text" name="account" :placeholder="i18n.formacctxt" placeholder-class="place" />
					</view>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.kaibank}}</view>
					<view class="form_input">
						<input type="text" name="bank_address" :placeholder="i18n.kaibanktxt" placeholder-class="place" />
					</view>
				</view>
				<!-- <view class="form_item active" @click="grad_picker">
					<view class="form_title">{{i18n.bankcode}}</view>
					<view class="form_input">
						<input type="text" name="taskname" :value="taskname" disabled :placeholder="i18n.bankcodetxt" placeholder-class="place" />
					</view>
				</view> -->
				<view class="form_item">
					<view class="form_title">{{i18n.bankword}}</view>
					<view class="form_input">
						<input type="number" maxlength="6" name="trade_pass" :placeholder="i18n.bankwordtxt" placeholder-class="place" />
					</view>
				</view>
				<view class="form_info">{{i18n.bankinfo}}</view>
				<view class="form_btn">
					<button form-type="submit">{{i18n.formbtn}}</button>
				</view>
			</form>
		</view>
		<picker-show :pickerlist="array" @btnconfirm="bindconfirm" ref="showpicker"></picker-show>
		<!-- <button class="btn" @click="confirm"> {{i18n.formbtn}}</button> -->
	</view>
</template>

<script>
	export default {
		data() {
			return {
				isclick:true,
				array:['中国','Englist','Russia','马来西亚'],
				taskname:'',
				countryicon:''
			};
		},
		computed:{
			i18n(){
				return this.$t('account');
			}
		},
		methods: {
			formSubmit(e){
				if(this.isclick){
					this.isclick = false;
				}else{
					return this.isclick;
				}
				 let formdata = e.detail.value,that = this;
				 this.$http.requestajx('account/bank','put',{
					 account: formdata.account,
					 bank_address: formdata.bank_address,
					 bank_name: formdata.bank_name,
					 name: formdata.name,
					 taskname: this.array[0],
					 trade_pass: formdata.trade_pass
				 }).then((res) => {
				 	this.totat(this.i18n.totatinfo);
				 	setTimeout(() => {
				 		uni.hideToast();
				 		uni.navigateBack()
				 	}, 1500);
				 });
				 setTimeout(()=>{
				 	that.isclick = true;
				 },5000);
			},
			bindconfirm(e){
				 this.taskname = e;
			},
			grad_picker(){
				this.$http.requestajx('country/bank_code','get',{country_id:uni.getStorageSync('locale_key')}).then((res) => {
					let data = res.result;
					if(data.length < 1){
						return this.totat(this.i18n.totaterror);
					}
					this.array = data.map(function(item){
						return item.code;
					});
					this.$refs.showpicker.picker_open();
				});
				
			},
			async getBankList() {
				let res = await this.$http.bankList()
				// console.log(res);
				this.array = res.result
			}
		},
		onShow() {
			// this.getBankList()
		}
	}
</script>

<style lang="scss">
	.account{background-color: #f7f9fc;min-height: 100%;}
.acc_form{ padding-top: 12px;}
.form_item{position: relative;height: 45px;display: flex;align-items: center;font-size: 28rpx;background-color: #FFFFFF;margin-bottom: 1px;
	.form_title{ position: absolute;left: 15px;color: #2d2933;max-width: 110px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;}
	.form_input{ width: 100%;padding-left: 120px;padding-right: 15px;color: #333;
		.place{color: #c5c5c5;font-size: 28rpx;}
		&.text-right{ text-align: right;}
		
	}
	&.active{ margin-bottom: 12px;margin-top: 12px;background-color: #F5F5F5;color: #848080;}
}
.form_info{ font-size: 12px;color: #f62f3d;margin-top: 30px;padding: 0 15px;line-height: 17px;}

.form_btn{padding: 0 15px;position: absolute;bottom: 60px;width: 100%;
		button{ width: 100%;height: 40px;background-color: $unified-color;border-radius: 20px;font-size: 30rpx;color: #FFFFFF;}
	}
</style>
