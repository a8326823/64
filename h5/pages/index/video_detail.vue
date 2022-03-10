<template>
	<view class="header_top">
		<!-- #ifdef H5 -->
		<public-head :headshow="false" :title="i18n.munelist[2]"></public-head>
			<!-- #endif -->
		<view class="video_content">
			<view class="video_item" v-for="(item,index) in videolist" :key="index">
				<video :id="'myVideo'+item.id" :src="$configurl.ossBaseUrl+item.video" :title="item.title"
				        @error="videoErrorCallback" :danmu-btn="false" :show-fullscreen-btn="true" object-fit="fill">
					 <cover-view class="controls-title">{{item.title}}</cover-view>
				</video>
			</view>
		</view>
	</view>
</template>
<script>
	export default {
	    data() {
	        return {
				videolist:'',
	            src: '',
	        }
	    },
		computed:{
			i18n(){
				return this.$t('index');
			}
		},
	    methods: {
			videojson(){
				let that = this;
				this.$http.requestajx('video','get',{}).then(function(res){
					console.log(res);
					 let data = res.result.data;
					 if(res.code === 200){
						 that.videolist = data;
					 }
					// console.log(res)
				});
			},
	        videoErrorCallback: function(e) {
				console.log(JSON.stringify(e));
	            uni.showModal({
	                content: e.target.errMsg,
	                showCancel: false
	            })
				
	        },
			titleview(){
				uni.setNavigationBarTitle({
					title: this.i18n.munelist[2]
				})
			}
	    },
		onLoad() {
			this.videojson();
		},
		onShow() {
			this.videojson();
			this.titleview();
		}
	}
	
</script>

<style lang="less">
	/* #ifdef APP-PLUS */
		.header_top{ padding-top: 0;}
	/* #endif */
	.video_content{ padding: 20px 15px;
		.video_item{ border-radius: 5px;overflow: hidden;
			video{ width: 100%;border-radius: 10px;}
		}
	}
	.controls-title{ color:#FFFFFF;font-size: 15px;width: 100%;padding: 10px;}
	
</style>
