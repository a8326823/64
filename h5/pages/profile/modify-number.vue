<template>
	<view class="header_top">
		<public-head :title="i18n.title"></public-head>
		<view class="tel-login animated fadeIn faster">
			<view class="input-tel">
				<text class="tel-text" @click="grad_picker">＋{{countrycode}}</text>
				<image src="/static/images/login/down01.png" class="down"></image>
				<text class="line"></text>
				<input type="number" :placeholder="i18n.modifytxt1" placeholder-style="color:#b8b8b8;font-size:30rpx" v-model="phoneText" />
			</view>
			<view class="input-code">
				<input type="text" :placeholder="i18n.modifytxt2" placeholder-style="color:#b8b8b8;font-size:30rpx" v-model="verCode" />
				<text class="code-text" @click="getVerCode">{{ verCodeTextcom }}</text>
			</view>
			<button class="btn" @click="login" :style="{ 'background-color': phoneText && verCode ? '#080d5d' : '#d2d2d2' }">{{i18n.modifybtn}}</button>
		</view>
		
		<picker-show :pickerlist="pickerlist" @btnconfirm="bindconfirm" ref="showpicker"></picker-show>
	</view>
</template>

<script>
export default {
	data() {
		return {
			pickerlist:[],
			countrycode:'86',
			phoneText: '',
			verCode: '',
			verCodeText: '获取验证码'
		};
	},
	computed:{
		i18n(){
			return this.$t('modify_number');
		},
		verCodeTextcom(){
			return this.verCodeText;
		}
	},
	methods: {
		// 获取验证码
		async getVerCode() {
			if(this.phoneText == ""){
				return this.totat(this.i18n.modifytxt1);
			} 
			let intervalTime = 60;
			let res = await this.$http.getCode(this.phoneText,{type:2,add_num:this.countrycode});
			// console.log(res);
			this.totat(this.i18n.codeinfo);
			let timer = setInterval(() => {
				this.verCodeText = `${intervalTime}s`;
				intervalTime--;
				if (intervalTime <= 1) {
					this.verCodeText = this.i18n.modifycode;
					clearInterval(timer);
				}
			}, 1000);
		},
		bindconfirm(e,code_id){
			this.countrycode = e;
			// this.countryid = code_id;
		},
		grad_picker(){
			this.$http.requestajx('country_code','get',{}).then((res) => {
				let data = res.result;
				console.log(data)
				this.pickerlist = data.map((item)=>{
					return item.code;
				});
				this.$refs.showpicker.picker_open();
			});
			
		},
		async login() {
			if(this.phoneText == ""){
				return this.totat(this.i18n.modifytxt1);
			} 
			let res = await this.$http.accountPhone({ code: this.verCode ,phone:this.phoneText,add_num:this.countrycode});
			// console.log(res);
			this.totat(this.i18n.modeinfo);
			setTimeout(() => {
				uni.hideToast();
				uni.navigateBack()
			}, 1500);
		}
	},
	onLoad() {
		this.verCodeText = this.i18n.modifycode;
	}
};
</script>

<style lang="scss">
.tel-login {
	display: flex;
	flex-direction: column;
	align-items: center;
	.logo-img {
		width: 146rpx;
		height: 146rpx;
		margin: 174rpx 0 20rpx 0;
		border-radius: 50%;
	}
	.input-tel {
		display: flex;
		align-items: center;
		width: 635rpx;
		height: 106rpx;
		border-bottom: 1px solid #e8e8e8;
		.tel-text {
			display: flex;
			align-items: center;
			color: #000;
			font-size: 30rpx;
		}
		.down {
			width: 32rpx;
			height: 32rpx;
			margin: 0 32rpx 0 18rpx;
		}
		.line {
			width: 1px;
			height: 40rpx;
			margin-right: 20rpx;
			background-color: #b8b8b8;
		}
		input {
			flex: 1;
			font-size: 30rpx;
		}
	}
	.input-code {
		display: flex;
		align-items: center;
		justify-content: space-between;
		width: 635rpx;
		height: 106rpx;
		border-bottom: 1px solid #e8e8e8;
		input {
			width: 220rpx;
			font-size: 30rpx;
		}
		.code-text {
			color: $unified-color;
			font-size: 26rpx;
		}
	}
	.btn {
		position: absolute;
		left: 0;
		right: 0;
		bottom: 250rpx;
		margin: auto;
	}
	.tip-text1 {
		display: flex;
		margin-top: 55rpx;
		font-size: 20rpx;
		color: #969696;
		.text1-active {
			color: $unified-color;
		}
	}
	.tip-text2 {
		font-size: 20rpx;
		color: $unified-color;
		margin-top: 30rpx;
	}
}
</style>
