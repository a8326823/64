<template>
	<view class="header_top">
		<public-head :title="i18n.titlebtn"></public-head>
		
	</view>
</template>

<script>
	export default{
		data(){
			return{
				
			}
		},
		computed:{
			i18n(){
				return this.$t('pagesprofile');
			}
		},
		methods:{
			
		}
	}
</script>

<style>
	page{ background: url('/static/images/profile/backbanner.jpg') no-repeat;}
	
</style>
