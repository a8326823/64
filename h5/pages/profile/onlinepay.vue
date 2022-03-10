<template>
<view class="header_top re_back">
		<public-head :title="i18n.title"></public-head>
		<view class="re_content">
			<!-- <view class="re_head">
				<text>USDT</text>
				<button size="mini" class="re_btn" @click="link_to('/pages/wallet/voucher?payload=' + JSON.stringify(payload))">{{i18n.voucher}}</button>
			</view> -->
			<view class="re_type">
				<!-- <view class="title">{{i18n.infotitle}}：</view> -->
				<button>TRC20</button>
			</view>
			<view class="qrcode_content">
				<view class="re_qrcode">
					<tki-qrcode
						class="code_img"
						cid="qrcode1"
						ref="qrcode"
						:val="val"
						:size="size"
						:unit="unit"
						:background="background"
						:pdground="pdground"
						:icon="icon"
						:iconSize="iconsize"
						:lv="lv"
						:onval="onval"
						:loadMake="loadMake"
						:usingComponents="true"
						@result="qrR"
					/>
				</view>
				<view class="title">{{i18n.address}}</view>
				<view class="address">{{val}}</view>
				<view class="copy_btn">
					<button @click="onCopyResult">{{i18n.copy}}</button>
				</view>
			</view>
			<view class="info">
				<rich-text :nodes="artiveinfo"></rich-text>
			</view>
		</view>
		<view class="profile_content">
			<view class="que_form">
				<!-- <view class="title">{{i18n.fromaddress}}</view>
				<view class="form_item">
					<input type="text" v-model="form.address" :placeholder="i18n.placeAddress" placeholder-class="place" />
				</view> -->
				<view class="title">{{i18nupvou.fromprice}} <text class="exchangeRate">≈{{exchangeRate}} USDT</text></view>
				<view class="form_item">
					<input type="number" v-model="form.money" :placeholder="i18nupvou.placeprice" placeholder-class="place" />
				</view>
				<view class="title">{{i18nupvou.fromimg}}</view>
				<u-upload :fileList="fileList1" @afterRead="afterRead" @delete="deletePic" name="1" multiple :maxCount="1"></u-upload>
				<!-- <u-upload ref="uUpload" :action="$configurl.apiBaseUrl+action" :auto-upload="true" max-count="3" :custom-btn="true" @on-change="upload_data"></u-upload> -->
			</view>
		</view>
		<div class="submit_btn">
		  <u-button  :disabled="subbtn_disabled" :ripple="true"  class="btn" @click="onSubmit">
			  <text style="color: #FFFFFF;">{{i18nupvou.sub}}</text>
		  </u-button>
		</div>
	</view>
</template>

<script>
	export default{
		data(){
			return{
				payload:'',
				invishow:false,
				val: '123123', // 要生成的二维码值
				size: 150, // 二维码大小
				unit: 'rpx', // 单位
				background: '#fff', // 背景色
				pdground: '#000', // 角标色
				icon: '', // 二维码图标
				iconsize: 40, // 二维码图标大小
				lv: 3, // 二维码容错级别 ， 一般不用设置，默认就行
				onval: true, // val值变化时自动重新生成二维码
				loadMake: false, // 组件加载完成后自动生成二维码
				src: '' ,// 二维码生成后的图片地址或base64
				artiveinfo: '',
				
				fileList1:[],
				form:{
					address:'',
					money:''
				}
			}
		},
		computed:{
			i18n(){
				return this.$t('payrecharge')  
			},
			i18nupvou(){
				return this.$t('upvoucher')  
			},
			exchangeRate(){
				return this.form.money * this.payload.usdtExchangeRate;
			},
			subbtn_disabled(){
			  return !this.fileList1.length || !this.form.money;
			}
		},
		onLoad() {
			let that = this;
			this.$http.requestajx('user_recharge/usdt_recharge_info','get', {}).then((res) => {
				that.val = res.result.usdtAddress;
				that.payload = res.result;
				that.creatQrcode();
			}).catch((res) => {
				that.$totast(res.message)
			});
			// this.$http.requestajx('v1/article/get','get', {type: 13}).then((res) => {
			// 	that.artiveinfo = res.result.article;
			// }).catch((res) => {
			// 	that.$totast(res.message)
			// });
		},
		methods:{
			link_to(url){
				uni.navigateTo({
					url: url
				})
			},
			creatQrcode() {
				setTimeout(() => {
					this.$refs.qrcode._makeCode();
				}, 500);
			},
			qrR(res) {
				// console.log(res)
				this.src = res;
			},
			onCopyResult(){
				let that = this;
				// #ifdef H5
				this.$copyText(this.val).then( res => {
					that.totat(that.i18n.succ);
				});
				// #endif
				 // #ifndef H5
					uni.setClipboardData({
						data: this.val,
						success: () => {
							that.totat(that.i18n.succ);
						}
					})
				// #endif
			},
			// 删除图片
			deletePic(event) {
				this[`fileList${event.name}`].splice(event.index, 1)
			},
			// 新增图片
			async afterRead(event) {
				// 当设置 mutiple 为 true 时, file 为数组格式，否则为对象格式
				let lists = [].concat(event.file)
				let fileListLen = this[`fileList${event.name}`].length
				lists.map((item) => {
					this[`fileList${event.name}`].push({
						...item,
						status: 'uploading',
						message: 'loading'
					})
				})
				for (let i = 0; i < lists.length; i++) {
					this.$http.uploadajax('upload',lists[i].url,{},'image').then((res) => {
						// const result = await this.uploadFilePromise(lists[i].url)
						let result = res.result.uri;
						let item = this[`fileList${event.name}`][fileListLen]
						this[`fileList${event.name}`].splice(fileListLen, 1, Object.assign(item, {
							status: 'success',
							message: '',
							url: result
						}))
						fileListLen++
					});
					
				}
			},
			onSubmit(){
				let that = this;
				const form = this.form;
				this.$http.requestajx('user_recharge/usdtRecharge','post',{
					address:form.address,
					credential: this.fileList1[0].url,
					amount:form.money
				}).then((res) => {
					that.totat(res.message)
					setTimeout(function(){
						uni.navigateBack()
					},300)
				}).catch(({ res }) => {
					that.totat(res.message)
				});
				
			}
		}
	}
</script>

<style lang="scss">
	$huo-gray: #939393;
	.re_back{ min-height: 100vh;background-color: #f6f5fa;}
	.re_content{ padding: 15px;
		.re_head{ font-size: 32rpx;color: $huo-gray;padding: 12px 15px;margin-bottom: 15px;border-radius: 6px;background-color: #FFFFFF;
			display: flex;align-items: center;justify-content: space-between;
			.re_btn{ margin: 0;color: #fff;background-color: #2979ff;}
		}
		.re_type{ 
			.title{ font-size: 28rpx;margin-bottom: 10px;color: #333;} 
			button{ background-color: #2979ff;color: #FFFFFF;font-size: 28rpx;height: 30px;line-height: 30px;
				border-radius: 5px;display: inline-block;font-weight: normal;}
		}
	}
	.qrcode_content{ border-radius: 6px;text-align: center;padding: 30px 0;font-size: 28rpx;margin-top: 10px;background-color: #FFFFFF;
		.re_qrcode{ border: 2px solid #fff;display: inline-block;}
		.title{ color: $huo-gray;margin-top: 20px;}
		.address{ color:$huo-gray;margin-top: 10px;font-weight: normal;}
		uni-button{ background-color: #2979ff;color: #fff;display: inline-block;font-size: 28rpx;margin-top: 20px;width: 500rpx;}
	}
	.info{ margin-top: 12px;font-size: 24rpx;line-height: 20px;font-size: #ddd;}
	
	.profile_content{ padding: 0 15px;}
	.title{ font-size: 28rpx;color: #999;padding: 10px 0;}
	.form_item{ background-color: #fff;border-radius: 5px;width: 100%;padding: 10px;font-size: #303133;
		textarea{ text-align: left;height: 100px;font-size: 28rpx;color: #0c0c0c;font-family: sans-serif;}
		text{ font-size: 28rpx;color: #bbb;}
	}
	.place{ font-size: 14px;}
	.upload{ width: 110px;height: 110px;display: flex;justify-content: center;align-items: center;border-radius: 10rpx;background-color: #FFFFFF;
		position: relative;
		&::after,&::before{ width: 1px;height: 25px;content: " ";position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);background-color: #ccc;}
		&::before{ width: 25px;height: 1px;}
	}
	.exchangeRate{ font-size: 10px;color: #ff0000;margin-left: 10px;}
	.submit_btn{ width: 100%;padding: 15px;box-shadow: 0 -10rpx 40rpx 0 rgba(0,0,0,0.04);background-color: #fff;box-sizing: border-box;margin-top: 70px;
	    .btn{font-size: 32rpx;background-color: #080d5d;border-radius: 30px;}
	}
</style>