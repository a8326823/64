<template>
	<view class="header_top pageback">
		<public-head title="充值记录"></public-head>
		<view class="record_content">
			<view class="recordlist">
				<mescroll-body ref="mescrollRef" :i18n="en" @init="mescrollInit" @down="downCallback" @up="upCallback" :up="upOption">
					<view class="item" v-for="(item,index) in dataList" :key="index">
						<view class="left">
							{{item.price}}{{item.sysmbol}}
						</view>
						<view class="right">
							<text :class="item.type == 1 ? 'color_red' : 'color_green'">{{i18n.paydata[item.type]}}</text> 
							<view class="date">
								{{item.time}}
							</view>
						</view>
					</view>
				</mescroll-body>
			</view>
		</view>
	</view>
</template>
<script>
import MescrollMixin from "@/uni_modules/mescroll-uni/components/mescroll-uni/mescroll-mixins.js";
export default {
	mixins: [MescrollMixin],
	data() {
		return {
			upOption: {
				page: {
					size: 10
				},
				noMoreSize: 5, 
				toTop:{
					src:null
				},
				empty:{
					icon:"/static/images/empty/mescroll-empty1.png"
				}
			},
			dataList:[{price:"100",sysmbol:"$",type:0,time:"2012/12/12 03/12"},{price:"100",sysmbol:"U",type:1,time:"2012/12/12 03/12"},
					  {price:"100",sysmbol:"$",type:2,time:"2012/12/12 03/12"},{price:"100",sysmbol:"U",type:0,time:"2012/12/12 03/12"}]
		};
	},
	computed:{
		i18n(){
			return this.$t('payrecord');
		}
	},
	methods: {
		loadjson(page){
			let pageNum = page.num; // 页码, 默认从1开始
			let pageSize = page.size; // 页长, 默认每页10条
			let limit_begin = (pageNum - 1)*pageSize; //条数
			let that = this;
			this.$http.requestajx('user_withdrawal','get',{
				page: pageNum
			}).then(function(res){
				// 接口返回的当前页数据列表 (数组)
				let curPageData = res.result.data;
				// 接口返回的当前页数据长度 (如列表有26个数据,当前页返回8个,则curPageLen=8)
				let curPageLen = curPageData.length; 
				// 接口返回的总页数 (如列表有26个数据,每页10条,共3页; 则totalPage=3)
				let totalPage = res.result.total; 
				
				//设置列表数据
				if(page.num == 1) that.dataList = []; //如果是第一页需手动置空列表
				that.dataList = that.dataList.concat(curPageData); //追加新数据
				// that.mescroll.endByPage(curPageLen, totalPage); 
				that.mescroll.endSuccess(curPageLen, true); 
			})
		},
		downCallback(){
			this.mescroll.resetUpScroll(); 
		},
		upCallback(page) {/*上拉加载的回调*/
			this.loadjson(page)
		}
	},
	onShow() {
		
	}
};
</script>

<style lang="scss">
.color_red{ color: $uni-color-red;}
.color_green{ color: $uni-color-green;}
// .pageback{ min-height: 100vh;}
.record_content{ padding: 0 15px;}
.recordlist{
	.item{ display: flex;align-items: center;justify-content: space-between;height: 65px;border-bottom: 1px solid #eaeaea;font-size: 14px;color: #333333;
		uni-text{ margin-left: 5px;}
		.left{ color: $uni-color-purple;}
		.right{ font-size: 13px;text-align: right;}
		.date{ margin-top: 9px;color: #bebebe;}
	}
}
</style>
