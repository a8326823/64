<script>
export default {
	onLaunch: function() {
		
		console.log('App Launch');
		/* 是否登录 */
		// this.isToken();
		
		//设置默认语言
		// uni.clearStorage();
		this.defaultLang();
	},
	onShow: function() {
		console.log('App Show');
		/* uni.removeStorage({
		    key: 'locale_key',
		    success: function (res) {
		        console.log('success111');
		    }
		}); */
		
	},
	onHide: function() {
		console.log('App Hide');
	},
	methods:{
		defaultLang(){
			let locale_key = !uni.getStorageSync('locale_key') ? "es-ES" : uni.getStorageSync('locale_key');
			this.setLang(locale_key)
		},
		setLang(code){
			this.$i18n.locale = code;
			this.$t('tabbartxt').map(function(item,index){
				uni.setTabBarItem({ index: index, text: item});
			});
			uni.setStorageSync('ajaxmess', this.$t('ajaxmess'));
			uni.setStorageSync('locale_key', code);
			// uni.setStorageSync("mescroll-i18n", code == 'zh-CN' ? 'zh' : 'en')
		},
		isToken(){
			let token = uni.getStorageSync('userToken') || '';
			if(token){
				uni.switchTab({
					url: '/pages/tabbar/index',
					success:function(){
						// #ifdef APP-PLUS
						plus.navigator.closeSplashscreen();
						// #endif
					}
				});
			}else{
				// #ifdef APP-PLUS
				setTimeout(() => {
					plus.navigator.closeSplashscreen();
				}, 2400);
				// #endif
			}
		}
	}
};
</script>

<style>
	
@import '/static/css/animate.css';
/*每个页面公共css */
/* 设置全局样式 */
@font-face {
	font-family: PingFang;
	src: url('./static/font/PingFang Medium.ttf');
}
/* tabbar文字大小 */
.uni-tabbar__label {
	font-size: 12px !important;
}
/* tabbar边框 */
/* uni-tabbar .uni-tabbar-border {
		background-color: red !important;
	} */
/* #ifdef H5 */
html,body{ width: 100%;min-height: 100%;background-color: #ffffff;}
.uni-app--showtabbar uni-page-wrapper::after{ height: 0!important;}
.pagebody{ padding-bottom: calc(52px + env(safe-area-inset-bottom));}
/* #endif */
uni-page {
	width: 100%;
	height: auto;
	font-family: 'PingFang' !important;
	font-size: 28rpx;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	background: #ffffff;
	line-height: 1;
	box-sizing: border-box;
}
.status_bar {	height: var(--status-bar-height);width: 100%;}
::-webkit-scrollbar {
	display: none;
}
button.button-hover {
	transform: translate(1rpx, 1rpx);
}
uni-button:after {
	border: 0;
}
button::after {
	border: 0;
}
view {
	box-sizing: border-box;
}
.btn {
	width: 662rpx;
	height: 90rpx;
	line-height: 90rpx;
	background-color: #7a2b86;
	border-radius: 45rpx;
	color: #fff;
	font-size: 30rpx;
}
.uni-ellipsis {
	/* #ifndef APP-NVUE */
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	/* #endif */
	/* #ifdef APP-NVUE */
	lines: 1;
	/* #endif */
}
.uni-ellipsis-2 {
	/* #ifndef APP-NVUE */
	overflow: hidden;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	/* #endif */

	/* #ifdef APP-NVUE */
	lines: 2;
	/* #endif */
}
/* 防止图片闪一下 */
image {
	will-change: transform;
	width: 100%;
}
.header_top{padding-top: calc(var(--status-bar-height) + 45px);}
rich-text img{ width: 100%;}

</style>
