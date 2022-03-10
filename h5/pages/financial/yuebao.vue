<template>
	<view class="header_top">
		<public-head headshow :backicon="false" backcolor="#7a2b86" color_icon="#fff" :title="i18n.title" :btntxt="i18n.titlebtn" v-on:initbtn="btn_on"></public-head>
		<view class="yue_content">
			<view class="yue_head">
				<view class="head_item" v-for="(item,index) in i18n.munelist" :key="index">
					<view class="title">{{item}}</view>
					<text class="color_red">{{munedetail[index]}}</text>
				</view>
			</view>
			<view class="yue_form">
				<view class="form_title">{{i18n.formtitle}}</view>
				<view class="form_input"><text>$</text><input type="number" v-model="moneytxt" :placeholder="i18n.formplace" placeholder-class="place" /> </view>
				<view class="form_info">{{i18n.forminfo}}：<text class="color_red" v-if="listjson.length > 0">{{propor | formatPrice}}</text></view>
			</view>
			<view class="yue_list">
				<view class="list_title">{{i18n.listtitle}}</view>
				<view class="list_table">
					<view class="list_item" v-for="(item,index) in listjson" :key="index">
						<view class="list_li" :class="{active:index===muneindex}" hover-class="heartBeat" @click="muneclick(index)">
							<view class="title">{{item.title}}</view>
							<view class="text">{{i18n.listtxt}}：{{item.min_amount}} </view>
							<view class="text1">{{item.mode}}:{{item.period}}{{i18n.listday}} {{item.daily_interest_rate}}</view>
						</view>
					</view>
				</view>
			</view>
			<view class="yue_btn">
				<button @click="buysubmit">{{i18n.buytitle}}</button>
			</view>
		</view>
	</view>
</template>

<script>
	export default{
		data(){
			return{
				moneytxt:null,
				muneindex:0,
				munedetail:[],
				munelist:[{title:'总资产',money:'200'},{title:'余额宝',money:'200'},{title:'持有收益',money:'200'},{title:'总收益',money:'200'}],
				listjson:[]
			}
		},
		computed:{
			i18n(){
				return this.$t('orderyuebao');
			},
			propor(){
				let list = this.listjson[this.muneindex];
				let num = list.daily_interest_rate.replace("%","")/100*list.period;
				return Number(num*this.moneytxt);
			}
		},
		methods:{
			btn_on(){
				uni.navigateTo({
					url:'/pages/financial/order'
				})
			},
			muneclick(e){
				this.muneindex = e;
			},
			loadincome(){
				this.$http.requestajx('mf/product','get',{page:1}).then((res) => {
					this.listjson = res.result.data;
					// console.log(res)
				});
				this.$http.requestajx('mf/income','get').then((res) => {
					let data = res.result;
					this.munedetail = [data.balance,data.yesterdayIncome,data.totalIncome];
					// console.log(res)
				});
			},
			buysubmit(){
				this.$http.requestajx('mf/buy','post',{
					amount:this.moneytxt,
					id:this.listjson[this.muneindex].id,
				}).then((res) => {
					// console.log(res)
					uni.navigateTo({
						url:'/pages/financial/order'
					})
				});
			}
		},
		onShow() {
			this.loadincome();
		},
		onLoad(option) {
			this.moneytxt = option.price || null;
			this.loadincome();
		}
	}
	
</script>

<style lang="scss">
	.color_red{ color:#f9103c;}
	.yue_content{ padding: 0 15px 100px;}
	.yue_head{ display: flex;text-align: center;padding: 20px 0;border-bottom: 1px solid #e6e6e6;
		.head_item{ flex: 1;
			.title{ font-size: 26rpx;color: #333333;margin-bottom: 15px;}
			text{ font-size: 24rpx;}
		}
	}
	.yue_form{ padding: 20px 0;
		.form_title{ font-size: 22rpx;color: #333333;}
		.form_input{ border-bottom: 1px solid #e6e6e6;display: flex;margin-top: 20px;padding-bottom: 13px;align-items: flex-end;
			text{ font-size: 48rpx;color: #333333;}
			input{ font-size: 28rpx;padding-left: 10px;width: 90%;}
			.place{ font-size: 30rpx;color: #dde0e6;}
		}
		.form_info{ font-size: 24rpx;color: #333333;padding-top: 20px;padding-bottom: 17px;border-bottom: 1px solid #e6e6e6;}
	}
	.yue_list{ padding-top: 19px;
		.list_title{ font-size: 22rpx;color: #333333;margin-bottom: 17px;}
		.list_table{ display: flex;flex-wrap: wrap;}
		.list_item{ padding: 6px;flex-basis: 50%;
			.list_li{ border-radius: 10px;padding: 10px 0;text-align: center;border: solid 1px rgba($color: #000000, $alpha: 0.3);color: #333333;
				&.active{background-image: linear-gradient(133deg, #9551a0 0%, #7a2b86 100%);color: #FFFFFF;}
			}
			.title{ font-size: 24rpx;}
			.text{ font-size: 22rpx;margin-top: 10px;}
			.text1{opacity: 0.7;margin-top: 10px;font-size: 22rpx;}
		}
	}
	.yue_btn{ padding-top: 80px;
		button{background-color: $unified-color;font-size: 32rpx;color: #FFFFFF;padding: 5px 0;border-radius: 40px;}
	}
</style>
