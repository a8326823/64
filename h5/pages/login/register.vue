<template>
	<view class="header_top">
		<!-- <view class="input">
			<image src="/static/images/login_Img/icon_avatar.png" style="width: 24rpx;height: 28rpx;"></image>
			<view class="line"></view>
			<input type="text" :placeholder="i18n.listtext[0]" v-model="account" placeholder-style="font-size:30rpx;color:#a3a3a3" />
		</view> -->
		<public-head :title="i18n.title"></public-head>
		<view style="padding-top: 80px;">
			<view class="reg_select">
				<text @click="grad_picker(0,'country','name')">{{i18n.selettxt}}：{{countrytxt}} </text>
			</view>
			<view class="input" style="margin: 35rpx auto 0;">
				<image src="/static/images/login_Img/icon_iphone.png"></image>
				<view class="line"></view>
				<input type="number" :placeholder="i18n.listtext[3]" v-model="phone" placeholder-style="font-size:30rpx;color:#a3a3a3" />
				<text class="register_iden">+{{reg_iden}}</text>
			</view>
			<view class="input" style="margin: 35rpx auto 0;">
				<image src="/static/images/login_Img/icon_mima.png" style="width: 24rpx;height: 32rpx;"></image>
				<view class="line"></view>
				<input type="password" :placeholder="i18n.listtext[1]" v-model="password" style="font-size: 22rpx;" placeholder-style="font-size:30rpx;color:#a3a3a3" />
			</view>
			<view class="input" style="margin: 35rpx auto 0;">
				<image src="/static/images/login_Img/icon_mima.png" style="width: 24rpx;height: 32rpx;"></image>
				<view class="line"></view>
				<input type="password" :placeholder="i18n.listtext[2]" v-model="password_confirmation" style="font-size: 22rpx;" placeholder-style="font-size:30rpx;color:#a3a3a3" />
			</view>
		</view>
		<!-- <view class="input" style="margin: 35rpx auto 0;">
			<image src="/static/images/login_Img/icon_shuru.png" style="width: 29rpx;height: 36rpx;"></image>
			<view class="line"></view>
			<input type="text" :placeholder="i18n.listtext[4]" v-model="code" placeholder-style="font-size:30rpx;color:#a3a3a3" style="width: 150rpx;" />
			<view  :class="isClick?'getCode':'getCoder'" @click="getVerCode">
				<block v-if="isClick">
					{{i18n.telyan}}
				</block>
				<block v-else>
					{{verCodeText}}
				</block>
			</view>
		</view> -->
		<view class="input" style="margin: 35rpx auto 0;">
			<image src="/static/images/login_Img/icon_shuru.png" style="width: 29rpx;height: 36rpx;"></image>
			<view class="line"></view>
			<input type="number" :placeholder="i18n.listtext[5]" :disabled="codeshow" v-model="invitation_code" placeholder-style="font-size:30rpx;color:#a3a3a3" />
		</view>
		<view class="login_btn" @click="confirm">{{i18n.btn}}</view>
		<picker-show :pickerlist="pickerlist" @btnconfirm="bindconfirm" ref="showpicker"></picker-show>
	</view>
</template>

<script>
export default {
	data() {
		return {
			codeshow:false,
			pickerlist:['86','567','234234','234'],
			countrytxt:'',
			countryid:'',
			reg_iden:'86',
			pickerarr:['',''],
			selecttask:0,
			account:'',
			password:'',
			password_confirmation:'',
			phone:'',
			code:'',
			invitation_code:'',
			verCodeText:'',
			isClick:true,
			isClickNum:0
		};
	},
	computed:{
		i18n(){
			return this.$t('register');
		},
		toastinfo(){
			return this.$t('toastinfo');
		},
		pickerlist1(){
			return this.pickerlist;
		}
	},
	onLoad() {
		// #ifdef H5
		this.invitation_code = this.geturl('parentId');
		// #endif
		let localkey = uni.getStorageSync('locale_key');
		if(localkey === ""){ 
			this.munebtn(0);
		}else{
			this.munebtn(localkey);
		}
	},
	methods:{
		navTo(url){
			uni.navigateTo({url})
		},
		munebtn(e){
			var that = this;
			uni.setStorage({
			    key: 'locale_key',
			    data: e,
			    success: function () {
					var edition = ['zh','en','ru','ph','kr','my','pt','tr','es','in','idsa','vn','th','sa'];
					that.$i18n.locale = edition[parseInt(e)];
					that.pickerjson(0,'country','name');
			    }
			});
		},
		geturl(paraName){
				var url = document.location.toString();
		　　　　var arrObj = url.split("?");
		　　　　if (arrObj.length > 1) {
		　　　　　　var arrPara = arrObj[1].split("&");
		　　　　　　var arr;
		　　　　　　for (var i = 0; i < arrPara.length; i++) {
		　　　　　　　　arr = arrPara[i].split("=");
		　　　　　　　　if (arr != null && arr[0] == paraName) {
							   this.codeshow = true;
		　　　　　　　　　　return arr[1];
		　　　　　　　　}
		　　　　　　}
		　　　　　　return "";
		　　　　}
		　　　　else {
		　　　　　　return "";
		　　　　}
		},
		bindconfirm(e,code_id){
			// console.log(this.pickerarr[0].result[code_id].phone_code)
			
			this.countrytxt = e;
			this.countryid = code_id;
			this.reg_iden = this.pickerarr[0].result[code_id].phone_code;
			/* if(this.selecttask < 1){
				this.countrytxt = e;
				this.countryid = code_id;
			}else{
				this.reg_iden = e;
			} */
		},
		grad_picker(e,get_url,itemname){
			this.selecttask = e;
			this.$http.requestajx(get_url,'get',{}).then((res) => {
				let data = res.result;
				this.pickerarr.splice(e,1,res);
				this.pickerlist = data.map((item)=>{
					return item[itemname];
				});
				console.log(this.pickerarr)
			});
			this.$refs.showpicker.picker_open();
		},
		pickerjson(e,get_url,itemname){
			this.$http.requestajx(get_url,'get',{}).then((res) => {
				let data = res.result;
				this.pickerarr.splice(e,1,res);
				if(e < 1){
					this.countrytxt = data[9].name;
					this.countryid = data[9].id;
					this.reg_iden = data[9].phone_code;
				}else{
					this.reg_iden = data[9].code;
				}
				this.pickerlist = data.map((item)=>{
					return item[itemname];
				});
			});
			
		},
		async confirm() {
			let res = await this.$http.authRegister({
				country_id:this.countryid,
				country_code:this.reg_iden,
				password:this.password,
				password_confirmation:this.password_confirmation,
				phone:this.phone,
				invitation_code:this.invitation_code
			});
			uni.setStorageSync('userToken',res.result.token)
			this.totat(this.toastinfo[3])
			setTimeout(()=>{
				uni.hideToast()
				uni.switchTab({
					url:"/pages/tabbar/index"
				})
			},1500)
		},
		async getVerCode () {
			if(!this.phone) {
				return this.totat(this.toastinfo[0]);
			}
			this.isClick = false
			this.isClickNum ++
			if(this.isClickNum > 1) return
			// console.log(this.phone);
			let intervalTime = 60;
			let res = await this.$http.getCode(this.phone,{type:3,add_num:this.reg_iden})
			if(res.code === 4000) {
				this.isClick = true 
				this.totat(`${res.message}`)
				this.isClickNum = 0
				return 
			}
			this.totat(this.toastinfo[1])
			let timer = setInterval(()=>{
				this.verCodeText = `${intervalTime}s`
				intervalTime --
				if(intervalTime < 0) {
					this.verCodeText = this.i18n.telyan;
					this.isClick = true
					this.isClickNum = 0
					clearInterval(timer)
				}
			},1000)
		}
	}
}
</script>

<style lang="less">
	.register_iden{font-size: 15px;color: rgb(163, 163, 163);position: absolute;right: 22px;
		&::after{ content: "";position: absolute;top: 6px;right: -10px;border-width: 4px;
			    border-style: solid;border-color: #a3a3a3 transparent transparent transparent;}
	}
	.reg_select{ font-size: 15px;color: rgb(163, 163, 163);text-align: center;margin-bottom: 10px;
		text{position: relative;
			&:after{content: "";position: absolute;top: 10px;right: -12px;border-width: 4px;
			    border-style: solid;border-color: #a3a3a3 transparent transparent transparent;}
		}
		
	}
.input {
	display: flex;
	align-items: center;
	background-color: #fafafa;
	border-radius: 40px;
	width: 540rpx;
	height: 80rpx;
	margin: 200rpx auto 0;
	position: relative;
	image {
		width: 24rpx;
		height: 34rpx;
		margin-left: 26rpx;
		margin-right: 22rpx;
	}
	.line {
		width: 2rpx;
		height: 28rpx;
		background-color: #a9a9a9;
		margin-right: 33rpx;
	}
	uni-input {
		display: flex;
		align-items: center;
	}
	.getCode {
		width: 170rpx;
		height: 62rpx;
		background-color: #080d5d;
		border-radius: 31rpx;
		color: #fff;
		font-size: 26rpx;
		line-height: 62rpx;
		text-align: center;
		margin-left: 107rpx;
	}
	.getCoder {
		width: 170rpx;
		height: 62rpx;
		background-color: #ccc;
		border-radius: 31rpx;
		color: #fff;
		font-size: 26rpx;
		line-height: 62rpx;
		text-align: center;
		margin-left: 107rpx;
	}
}
.login_btn {
	display: flex;
	align-items: center;
	justify-content: center;
	background-color: #080d5d;
	border-radius: 40px;
	width: 540rpx;
	height: 80rpx;
	margin: 80rpx auto 43rpx;
	color: #fff;
	font-size: 30rpx;
}
</style>
