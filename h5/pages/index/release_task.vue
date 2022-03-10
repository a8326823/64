<template>
	<view class="header_top">
		<public-head headshow :title="i18n.title"></public-head>
		<view class="task_form">
			<view class="form_item">
				<view class="form_title">{{i18n.releclass}}</view>
				<view class="form_input">
					<input type="text" :value="taskclass" disabled class="active" :placeholder="i18n.releclasstxt" placeholder-class="place_txt" 
						 name="category_id" @click="grad_picker(0,'task/category','name')" />
				</view>
			</view>
			<view class="form_item">
				<view class="form_title">{{i18n.releclass1}}</view>
				<view class="form_input">
					<input type="text" :value="taskgrad" disabled class="active" :placeholder="i18n.releclass1_txt" placeholder-class="place_txt"
					   name="level" @click="grad_picker(1,'user_level','name')" />
				</view>
			</view>
			<form @submit="tasksubmit">
				<view class="form_item">
					<view class="form_title">{{i18n.releclass2}}</view>
					<view class="form_input">
						<input type="text" name="title" :placeholder="i18n.releclass2_txt" />
					</view>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.releclass3}}</view>
					<view class="form_input">
						<input type="text" name="description" :placeholder="i18n.releclass3_txt" />
					</view>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.releclass4}}</view>
					<view class="form_input">
						<input type="text" name="url" :placeholder="i18n.releclass4_txt" />
					</view>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.releclass5}}</view>
					<view class="form_input">
						<input type="number" v-model="priceamount" :placeholder="i18n.releclass5_txt" />
					</view>
				</view>
				<view class="form_item">
					<view class="form_title">{{i18n.releclass6}}</view>
					<view class="form_input">
						<input type="number" v-model="nummoney" :placeholder="i18n.releclass6_txt" />
					</view>
				</view>
				<view class="form_price">
					{{i18n.releclass_info}}<text>{{pricenum | formatPrice}}</text>，{{i18n.releclass_info1}}，<navigator url="/pages/profile/bank-pay"><text>{{i18n.infobtn}}</text></navigator> 
				</view>
				<view class="form_btn">
					<button form-type="submit">{{i18n.relebtn}}</button>
				</view>
			</form>
			<picker-show :pickerlist="tasklist" @btnconfirm="bindconfirm" ref="showpicker"></picker-show>
		</view>
	</view>
</template>

<script>
	export default{
		data(){
			return{
				tasklist:['qweqwe','qweqwe','ddddd'],
				selecttask:0,
				pickerarr:['',''],
				taskclass:'',
				taskgrad:'',
				taskclass_id:'',
				taskgrad_id:'',
				priceamount:'',
				nummoney:''
				
			}
		},
		computed:{
			i18n(){
				return this.$t('release_task');
			},
			pricenum(){
				return this.priceamount*this.nummoney;
			}
		},
		onLoad() {
			
		},
		methods:{
			tasksubmit(e){
				// console.log('form发生了submit事件，携带数据为：' + JSON.stringify(e.detail.value))
				 let formdata = e.detail.value,that = this;
				 this.$http.requestajx('task/publish','post',{
					amount: this.priceamount,
					category_id: this.taskclass_id,
					description: formdata.description,
					level: this.taskgrad_id,
					num: this.nummoney,
					title: formdata.title,
					url: this.drawurl(formdata.url),
				}).then((result) => {
					// console.log(result)
					// let val = JSON.stringify(result),data = JSON.parse(val);
					if(result.code = 200){
						// that.totat('发布成功');
						uni.switchTab({
							url: '/pages/tabbar/index'
						});
					}
					
				 }).catch((error) => {
					console.log(error);
				 });
			},
			drawurl(string){
				 var reg = /(http:\/\/|https:\/\/)((\w|=|\?|\.|\/|&|-)+)/g;
			    if (reg.test(string)) { // 包含url链接
					return string.match(reg)[0];
				} else {
					return "";
			    }
			},
			bindconfirm(e,listid){
				 if(this.selecttask < 1){
					 this.taskclass = e;
					 this.taskclass_id = this.pickerarr[0][listid].id;
				 }else{
					 this.taskgrad = e;
					 this.taskgrad_id = this.pickerarr[1][listid].level;
				 }
			},
			grad_picker(e,get_url,itemname){
				this.selecttask = e;
				this.$http.requestajx(get_url,'get',{}).then((res) => {
					let data = res.result;
					this.pickerarr.splice(e,1,data);
					// console.log(this.pickerarr)
					this.tasklist = data.map((item)=>{
						return item[itemname];
					});
					this.$refs.showpicker.picker_open();
				});
			}
		}
	}
	
</script>

<style lang="scss">
	.task_form{ padding: 31px 15px 15px;}
	.form_item{
		margin-bottom: 22px;
		.form_title{ font-size: 30rpx;color: #0e1c3c;margin-bottom: 18rpx;}
		input{	height: 35px;border-radius: 3px;border: solid 1px #e8e8e8;color: #c1c1c1;padding: 0 8px;
			&.active{ background-color: #F5F5F5;color: #848080;
				.place_txt{color: #848080;}
			}
		}
		.place_txt{ font-size: 30rpx;color: #c1c1c1;}
	}
	.form_btn{padding: 40px 15px 30px;
		button{ width: 100%;height: 40px;background-color: $unified-color;border-radius: 20px;font-size: 30rpx;color: #FFFFFF;}
	}
	.form_price{line-height: 25px;font-size: 28rpx;
		text{ color:#f62f3d;}
	}
	
	
</style>
