<template>
	<view class="header_top">
		<public-head :title="i18n.title" backcolor="#27326e" color_icon="#fff"></public-head>
		<view class="team_content">
			<view class="team_info">
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
				<block v-for="(item,index) in i18n.tabitem" :key="index">
					<view class="list_item" :class="{active:tabIndex==index}" >
						<text @click="itemClick(index)">{{item}}({{teamlist[index]}}{{i18n.people}})</text>
					</view>
				</block>
			</view>
			<view class="teamMune">
				<uni-row class="table_body" v-for="(item,index) in teamdata" :key="index">
					<uni-col :span="8">{{item.user_id}}</uni-col>
					<uni-col :span="8" class="color_red right">返利收益</uni-col>
					<uni-col :span="8" class="right">${{item.parent_id}}</uni-col>
				</uni-row>
			</view>
		</view>
	</view>
</template>

<script>
export default{
	data(){
		return{
			porfileinfo:'',
			resultdata:'',
			tabIndex: 0,
			munelist:[],
			teamlist:[],
			teamdata:[]
		}
	},
	computed:{
		i18n(){
			return this.$t('myteam');
		}
	},
	onLoad() {
		this.teamjson(this.tabIndex+1);
		this.getAccountInfo();
	},
	methods:{
		teamjson(level){
			let that = this;
			this.$http.requestajx('team','get',{time:[this.startdate,this.endbdate],level:level}).then((res) => {
				// console.log(res)
				let data = res.result;
				that.resultdata = data;
				that.teamdata = data.team;
				that.munelist = [data.withdrawal_sum,data.user_count,data.recharge_level_count];
				that.teamlist = [data.level_one_count,data.level_two_count,data.level_three_count];
				
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
	}
}
</script>

<style lang="scss">
.team_info{ background-image: linear-gradient(0deg,#1b2664 0,#5b6498);
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
.team_list{ display: flex;height: 40px;align-items: center;border-bottom: 5px solid #f5f7fd;
	.list_item{flex: 1;text-align: center;font-size: 28rpx;color: #8e8e8e;position: relative;
		&.active{ color:#000;
			&::after{ content: '';width: 26px;height: 3px;background-color: #1b2664;border-radius: 3px;position: absolute;bottom: -8px;left: calc(50% - 13px);}
		}
	}
}
.teamMune{ padding: 10px;}
.table_body{ padding: 10px;font-size: 24rpx;color: #333;
	.color_red{ color: #d8415d;}
	.color_blue{ color: #333;}
	.right{ text-align: right;}
}
</style>
