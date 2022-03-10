<template>
	<view class="header_top pageback">
		<public-head :title="i18n.title"></public-head>
		<view class="word_content">
			<form @submit="formSubmit">
				<view class="input-list">
					<input type="text" password name="old_password" :placeholder="i18n.pass_word[0]" placeholder-style="color:#c5c5c5;font-size:28rpx"/>
				</view>
				<view class="input-list">
					<input type="text" password name="password" :placeholder="i18n.pass_word[1]" placeholder-style="color:#c5c5c5;font-size:28rpx"/>
				</view>
				<view class="input-list">
					<input type="text" password name="password_confirmation" :placeholder="i18n.pass_word[2]" placeholder-style="color:#c5c5c5;font-size:28rpx"/>
				</view>
				<!-- <view class="input-list">
					<input type="text" password name="old_trade_pass" :placeholder="i18n.mongyinfo" placeholder-style="color:#c5c5c5;font-size:28rpx"/>
				</view>
				<view class="input-list">
					<input type="text" password name="trade_pass" :placeholder="i18n.formmongy" placeholder-style="color:#c5c5c5;font-size:28rpx"/>
				</view> -->
				<view class="form_info">*{{i18n.forminfo}}</view>
				<view class="subForm">
					<button class="btn" form-type="submit">{{i18n.word_btn}}</button>
				</view>
			</form>
		</view>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				old_password:'',
				password:'',
				password_confirmation:''
			};
		},
		computed:{
			i18n(){
				return this.$t('changeword');
			}
		},
		methods: {
			formSubmit: function(e) {
				// console.log('form发生了submit事件，携带数据为：' + JSON.stringify(e.detail.value))
				var formdata = e.detail.value,that=this;
				this.$http.requestajx('account/password','put',{
					old_password:formdata.old_password,
					password:formdata.password,
					password_confirmation:formdata.password_confirmation,
					old_trade_pass:formdata.old_trade_pass,
					trade_pass:formdata.trade_pass
				}).then((res) => {
					console.log(res);
					if(res.code===200){
						that.totat(that.i18n.totatinfo);
						// uni.navigateBack()
					}else{
						// that.totat('输入有误');
					}
				}).catch((error) => {
					console.log('错误重启');
				});
			},
			async comfirm() {
				await this.$http.accountPassword({
					old_password:this.old_password,
					password:this.password,
					password_confirmation:this.password_confirmation
				})
				this.totat(this.i18n.totatinfo);
				setTimeout(() => {
					uni.hideToast();
					uni.navigateBack()
				}, 1500);
			}
		}
	}
</script>

<style lang="less">
.pageback{ background-color: #f5f7fd;min-height: 100vh;}
.word_content{ padding-top: 10px;}
.form_info{ font-size: 12px;color: #f62f3d;margin-top: 14px;padding: 0 15px;line-height: 17px;}
.input-list{
	display: flex;
	align-items: center;
	width: 100%;
	height: 90rpx;
	background-color: #ffffff;
	padding-left: 42rpx;
	margin-bottom: 2rpx;
	input {
		font-size: 22rpx;
		width:90%;
	}
}
.change_href{ text-align: center;padding-top: 60px;}
.btn {
	position: absolute;
	left: 0;
	right: 0;
	bottom: 250rpx;
	margin: auto;
}
</style>
