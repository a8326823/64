<template>
	<view>
		<!-- <fh-tab-control :tabItem="tabItem" :tabIndex="tabIndex" style="border-bottom: 10rpx solid #f5f7fd;" @itemClick="itemClick"></fh-tab-control> -->
		<loading-plus v-if="isLoading"></loading-plus>
		<public-head :title="i18n.title" :btntxt="i18n.titlebtn" v-on:initbtn="datetimebtn" backcolor="#27326e" color_icon="#fff"></public-head>
		<view class="team_info">
			<view class="team_date" v-show="datetimeshow">
				<view class="team_date_item">
					 <view class="date_input" @click="openDatetimePicker(0)">{{startdate}}</view>
				</view>
				<view class="team_date_item">
					 <view class="date_input" @click="openDatetimePicker(1)">{{endbdate}}</view>
				</view>
			</view>
			<view class="team_tx">
				<view class="team_price">
					<image src="/static/images/login_Img/logo.png" mode="widthFix"></image>
					<view class="team_profile">
						 <view class="title">{{ porfileinfo.nickname }}</view>
						 <text>ID:{{ porfileinfo.id }}</text>  
					</view>
					 
				</view>
				<view class="team_title">
					<view class="team_txt1">{{i18n.recharge}}</view>
					<view class="team_txt2">
						<!-- <image src="/static/images/profile/icon_chongzhi.png" mode=""></image> -->
						<text>{{resultdata.total_recharge_sum}}</text>
					</view>
				</view>
			</view>
			<view class="team_mune">
				<view class="team_item" v-for="(item,index) in munelist" :key="index">
					<view class="team_title">{{item}}</view>
					<text>{{i18n.munelist[index]}}</text>
				</view>
			</view>
		</view>
		<view class="team_list">
			<view class="list_item" :class="{active:tabIndex==index}" v-for="(item,index) in teamlist" :key="index">
				<text @click="itemClick(index)">{{i18n.tabitem[index]}}({{item}}{{i18n.people}})</text>
			</view>
		</view>
		<uni-list style="border-top: 5px solid #f5f7fd;" v-if="teamdata.length > 0">
			<uni-list-item
				:title="String(item.user.id)"
				:thumb="$configurl.ossBaseUrl+item.user.avatar"
				:isBorder="true"
				:rightText="item.created_at"
				:width="66"
				:height="66"
				:showArrow="false"
				v-for="(item, index) in teamdata"
				:key="index"
			>
				<template v-slot:left="">
					<view class="tip">
						<image src="/static/images/profile/icon_01.png" mode="widthFix"></image>
						<text class="uni-ellipsis">{{ item.user.user_level.name }}</text>
					</view>
				</template>
			</uni-list-item>
		</uni-list>
		<view v-else style="margin-top: 400rpx; text-align: center; color: #666; ">{{i18n.numpeople}}0</view>
		<date-time  ref="myPicker"
		 @submit="handleSubmit"
		 :start-year="startyear"
		 :end-year="endyear"></date-time>
		<!-- <picker-show :pickerlist="pickerarray" @btnconfirm="bindconfirm" ref="showpicker"></picker-show> -->
	</view>
</template>

<script>
import dateTime from '@/components/picker_data/simple-datetime-picker'
import config from '@/service/config.js';
export default {
	components:{
		dateTime
	},
	data() {
		return {
			porfileinfo:{
				avatar: ''
			},
			result: {
				team: []
			},
			datetimeshow:false,
			datelist:'',
			startdate:'',
			endbdate:'',
			startyear:2020,
			endyear:2021,
			selectdate:'',
			pickerarray:[],
			teamarray:[],
			teamdata:[],
			countryicon:'',
			total: '',
			tabIndex: 0,
			isLoading: true,
			resultdata:{
				team:[]
			},
			munelist:[],
			teamlist:[]
		};
	},
	computed:{
		i18n(){
			return this.$t('myteam');
		}
	},
	methods: {
		datetimebtn(){
			this.datetimeshow = !this.datetimeshow;
		},
		openDatetimePicker(e) {
		   this.selectdate = e;
		   // this.$refs.myPicker.initYears();
		   this.$refs.myPicker.show();
		},
		closeDatetimePicker() {
		   this.$refs.myPicker.hide();
		},
		handleSubmit(e) {
		   if(this.selectdate > 0){
			    this.endbdate = `${e.year}-${e.month}-${e.day} ${e.hour}:${e.minute}`;
		   }else{
			    this.startdate = `${e.year}-${e.month}-${e.day} ${e.hour}:${e.minute}`;
		   }
			this.teamjson(this.tabIndex + 1);
		},
		shuatime(){
			
			let endtime = Number(new Date(this.endbdate).getTime()),startime = Number(new Date(this.startdate).getTime()),that = this;
			if(endtime > startime){
				let arr = this.resultdata.team.filter(function(element, index, item){
					 let val = item[index].created_at;
					 let num = Number(new Date(val.substring(0,val.length-3)).getTime());
					 return startime < num && num < endtime;
				 });
				 that.teamdata = arr;
				 that.teamlist[this.tabIndex] = arr.length;
			}
		},
		teamjson(level){
			let that = this;
			this.$http.requestajx('team','get',{time:[this.startdate,this.endbdate],level:level}).then((res) => {
				// console.log(res)
				let data = res.result;
				that.resultdata = data;
				that.teamdata = data.team;
				console.log(data)
				that.munelist = [data.withdrawal_sum,data.user_count,data.recharge_level_count];
				that.teamlist = [data.level_one_count,data.level_two_count,data.level_three_count];
				
				that.teamarray = data.team.map(function(item,index){
					return item.created_at; 
				});
				if(that.teamarray.length > 0){
					that.startyear = Number(that.teamarray[0].substring(0,4));
					that.endyear = Number(that.teamarray[that.teamarray.length-1].substring(0,4));
				}
				
			});
		},
		gettime(timestamp) {
			// console.log(timestamp)
			let date = new Date(timestamp),
				Y = date.getFullYear() + '-',
				M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-',
				D = (date.getDate() < 10 ? '0'+date.getDate() : date.getDate()) + ' ',
				h = (date.getHours() < 10 ? '0'+date.getHours() : date.getHours()) + ':',
				m = (date.getMinutes() < 10 ? '0'+date.getMinutes() : date.getMinutes()) + ':',
				s = (date.getSeconds() < 10 ? '0'+date.getSeconds() : date.getSeconds());
			let strDate = Y+M+D+h+m;
			return strDate;
		},
		async getTeamList(level) {
			let res = await this.$http.teamList({ level });
			this.result = res.result;
			this.munelist = [data.withdrawal_sum,data.user_count,data.recharge_level_count];
			this.teamlist = [data.level_one_count,data.level_two_count,data.level_three_count];
			/* console.log(this.munelist)
			console.log(this.result); */
			switch (level) {
				case 1:
					this.total = res.result.level_one_count;
					break;
				case 2:
					this.total = res.result.level_two_count;
					break;
				case 3:
					this.total = res.result.level_three_count;
					break;
			}
			this.result.team.forEach(item => {
				return (item.user.avatar = item.user.avatar === null ? '/static/images/profile/btn_touxiang.png' : config.ossBaseUrl + item.user.avatar);
			});
		},
		async getAccountInfo() {//获取用户头像
			let res = await this.$http.accountInfo();
			this.porfileinfo = res.result;
		},
		itemClick(index) {
			this.tabIndex = index;
			this.teamjson(index + 1);
		}
	},
	onShow() {
		this.teamjson(this.tabIndex+1);
		this.getAccountInfo();
		setTimeout(() => {
			this.isLoading = false;
		}, 1100);
	}
};
</script>

<style lang="less">
/deep/ .uni-list-item__icon-img {
	border-radius: 50%;
}
.team_date{ display: flex;justify-content: space-between;padding: 20px 15px 0;
	.team_date_item{ font-size: 15px;color: #808080;width: 50%;padding: 0 10px;
		.date_input{background-color: #FFFFFF;height: 25px;line-height: 25px;border-radius: 10px;padding: 0 5px;}
	}
} 
.team_info{ background: linear-gradient(top, #1b2664 0%,#5b6498 100%);padding-top: calc(var(--status-bar-height) + 45px);
	.team_tx{ display: flex;justify-content: space-between;padding: 40px 40px 30px 20px;color: #FFFFFF;align-items: center;
		.team_price{ align-items: center;display: inherit;
			image{ width: 43px;height: 43px;border-radius: 50%;}
			text{ font-size: 26rpx;}
			.title{margin-bottom: 5px;font-size: 32rpx;}
			.team_profile{ padding-left: 10px;}
		}
		.team_title{ text-align: center;
			.team_txt1{ font-size: 34rpx;margin-bottom: 15px;}
			.team_txt2{ font-size: 46rpx;}
			image{ width: 17px;height: 17px;margin-right: 5px;}
		}
	}
	
}
.team_mune{ display: flex;align-items: center;color: #FFFFFF;font-size: 24rpx;height: 65px;background-color: rgba(255,255,255,0.05);
	.team_item{ flex: 33.3%;text-align: center;position: relative;padding: 0 10px;
		.team_title{ margin-bottom: 14px;}
		&+.team_item::after{ content: '';position: absolute;left: 0;top: 6px;width: 1px;height: 23px;background-color: #FFFFFF;}
	}
}
.team_list{ display: flex;height: 40px;align-items: center;
	.list_item{flex: 1;text-align: center;font-size: 28rpx;color: #8e8e8e;position: relative;
		&.active{ color:#000;
			&::after{ content: '';width: 26px;height: 3px;background-color: #1b2664;border-radius: 3px;position: absolute;bottom: -8px;left: calc(50% - 13px);}
		}
	}
}
.card {
	position: relative;
	top: 102rpx;
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 426rpx;
	border-bottom: 10rpx solid #f5f7fd;
	box-sizing: content-box;
	.card-item {
		display: flex;
		flex-direction: column;
		align-items: center;
		width: 690rpx;
		height: 360rpx;
		background-image: linear-gradient(151deg, #5b6498 0%, #1b2664 100%);
		border-radius: 20rpx;
		color: #ffffff;
		.list1 {
			font-size: 66rpx;
			margin-top: 110rpx;
		}
		.list2 {
			display: flex;
			align-items: flex-end;
			font-size: 32rpx;
			margin-top: 30rpx;
			image {
				width: 30rpx;
				height: 30rpx;
				margin-right: 14rpx;
			}
		}
		.list3 {
			margin-top: 55rpx;
			font-size: 30rpx;
		}
	}
}
.tip {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 140rpx;
	height: 34rpx;
	background-image: linear-gradient(145deg, #f2d9a5 0%, #cfb385 100%);
	border-radius: 17rpx;
	margin-left: 22rpx;
	padding: 0 5px;
	image {
		width: 12rpx;
		height: 14rpx;
	}
	text {
		font-size: 16rpx;
		color: #393026;
		margin-left: 6rpx;
		
	}
}
</style>
