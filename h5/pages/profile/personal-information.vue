<template>
	<view class="header_top">
		<loading-plus v-if="isShow"></loading-plus>
		<public-head :title="i18n.title"></public-head>
		<view class="app_top">
			<view class="personRouter">
				<view class="item" @click="navTo('/pages/profile/modify-name')">
					<view class="left">
						{{i18n.uesrname}}
					</view>
					<view class="right">
						<text>{{info.nickname}}</text> <uni-icons type="right" size="16" color="#999"></uni-icons>
					</view>
				</view>
				<view class="item" @click="pickerlang">
					<view class="left">
						{{i18n.sex}}
					</view>
					<view class="right">
						<text>{{i18n.sexarray[index]}}</text> <uni-icons type="right" size="16" color="#999"></uni-icons>
					</view>
				</view>
				<view class="item" @click="navTo('/pages/profile/account')">
					<view class="left">
						{{i18n.account[0]}}
					</view>
					<view class="right">
						<text>{{info.info ? info.info.account : i18n.account[1]}}</text> <uni-icons type="right" size="16" color="#999"></uni-icons>
					</view>
				</view>
			</view>
		</view>
		<view class="personal_list">
			<view class="list_item" v-for="(item,index) in datamember" :key="index">
				<view class="list_title">
				  <view class="title" v-if="item.user_level">{{item.user_level.name}}</view>
				  <view class="title_num">{{i18npro.munejson[3]}}：<text class="color_red">{{item.receive_count}}</text></view>
				</view>
				<view class="list_txt">{{i18npro.timedate}}：<text class="color_red">{{item.effective_time}}</text></view>
			</view>
		</view>
		<picker-show :pickerlist="array" @btnconfirm="bindconfirm" ref="showpicker"></picker-show>
	</view>
</template>

<script>
export default {
	data() {
		return {
			index:0,
			array:['保密','男','女'],
			info:{
				avatar:'',
				info:{}
			},
			isShow:true,
			datamember:[]
		};
	},
	methods: {
		navTo(url) {
			console.log(url)
			uni.navigateTo({ 
				url:url
			});
		},
		async bindconfirm(val,index){
			let num = Number(index.toString());
			this.index = num;
			let res = await this.$http.accountGender({
				gender: num
			})
		},
		pickerlang(){
			this.array = this.i18n.sexarray;
			this.$refs.showpicker.picker_open();
		},
		async getAccountInfo() {
			let res = await this.$http.accountInfo();
			this.info = res.result;
			this.index = res.result.gender;
			
			this.$http.requestajx('account/member_data','get',{}).then((data) => {
				console.log(data)
				this.datamember = data.result;
			});
		}
	},
	computed:{
		i18n(){
			return this.$t('personal');
		},
		i18npro(){
			return this.$t('pagesprofile');
		},
		i18n_confirm(){
			return this.$t('btncomfirm');
		}
	},
	onShow() {
		
		this.getAccountInfo()
		setTimeout(()=>{
			this.isShow = false
		},500)
	}
};
</script>

<style lang="scss">

.personRouter{
	.item{ border-bottom: 1px solid #F1F1F1;height: 45px;display: flex;align-items: center;justify-content: space-between;padding-left: 15px;padding-right: 10px;
		.right{ color: #999;}
	}
}

.personal_list{border-top: 10px solid #f7f9fc;padding: 10px 15px 0;}
	.list_item{ margin-bottom: 10px;padding: 15px 10px 15px 0;
		.list_title{display: flex;justify-content: space-between;margin-bottom: 10px;
			.title{ font-size: 30rpx;color:#333333;}
			.title_num{font-size: 24rpx;color: #999999;}
		}
		.list_txt{ font-size: 24rpx;color: #999999;}
		.color_red{color:#ff4949;}
		&+.list_item{border-top: 1px solid #f1f1f1;}
	}
	.avatar {
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: 180rpx;
		text {
			margin-left: 44rpx;
			color: $unified-color;
			font-size: 56rpx;
			width: 400rpx;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		image {
			width: 129rpx;
			height: 129rpx;
			border-radius: 50%;
			margin-right: 43rpx;
		}
	}
.border {
	width: 750rpx;
	height: 22rpx;
	background-color: #f7f9fc;
}
</style>
