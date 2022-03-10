import config from './config.js'
import showToast from '@/utils/utils.js';
export default {
	config: {
		baseUrl: config.apiBaseUrl,
		data: {},
		method: "POST",
		dataType: "json",
		success() {},
		fail() {},
		complete() {}
	},
	interceptor: {
		request: null,
		response: null
	},
	uploadFile(api, filePath, data, fileType) {
		return new Promise((resolve, reject) => {
			uni.uploadFile({
				url: this.config.baseUrl + api,
				filePath: filePath,
				name: 'file',
				fileType:fileType,
				header: { 
					// #ifdef APP-PLUS
					'Content-type': 'multipart/form-data', 
					// #endif
					'Authorization': uni.getStorageSync('userToken') || '',
					'Locale': uni.getStorageSync('locale_key') || 0
				},
				formData: data,
				success: (response) => {
					if (response.statusCode !== 200) {
						return showToast(uni.getStorageSync('ajaxmess').networktxt)
					}
					if (response.statusCode === 200) {
						let result = JSON.parse(response.data)
						// 请求失败的提示或返回
						if (result.code === 400 || result.code === 500) {
							
							return showToast(result.message || uni.getStorageSync('ajaxmess').networktxt)
						}
						// 未登陆的拦截
						if (result.code === 401) {
							showToast(result.message !== " " ? result.message : uni.getStorageSync('ajaxmess').networklogin)
							uni.removeStorageSync('userToken')
							return uni.redirectTo({
								url: '/pages/login/pwd-login',
								animationType: 'slide-in-bottom'
							})
						}
						// 交换token
						if (result.token) {
							uni.setStorageSync('userToken', result.token)
							delete result.token
						}
						resolve(result);
					} else {
						reject(response);
					}
				},
			});
			
		});
	},
	request(options) {
		if (!options) {
			options = {}
		}
		
		options.baseUrl = options.baseUrl || this.config.baseUrl
		options.dataType = options.dataType || this.config.dataType
		options.url = options.baseUrl + options.url
		options.data = options.data || {}
		options.method = options.method || this.config.method
		options.header = {
			'Content-Type': 'application/x-www-form-urlencoded',
			'Authorization': uni.getStorageSync('userToken') || '',
			'Locale': uni.getStorageSync('locale_key') || 0
		}
		return new Promise((resolve, reject) => {
			let _config = null;
			// 请求完成
			options.complete = (response) => {
				if (this.interceptor.response) {
					let newResponse = this.interceptor.response(response)
					if (newResponse) {
						response = newResponse
					}
				}
				if (response.statusCode !== 200) {
					
					return showToast(uni.getStorageSync('ajaxmess').networktxt)
				}
				
				if (response.statusCode === 200) {
					let result = response.data
					
					// 请求失败的提示或返回
					if (result.code === 403 || result.code === 500 || result.code === 400) {
						
						return showToast(result.message !== " " ? result.message : uni.getStorageSync('ajaxmess').networktxt)
					}
					// 未登陆的拦截
					if (result.code === 401) {
						showToast(result.message !== " " ? result.message : uni.getStorageSync('ajaxmess').networklogin)
						uni.removeStorageSync('userToken')
						return uni.redirectTo({
							url: '/pages/login/pwd-login',
							animationType: 'slide-in-bottom'
						})
					}
					// 交换token
					if (result.token) {
						uni.setStorageSync('userToken', result.token)
						delete result.token
					}
					resolve(result);
					
				} else {
					reject(response);
				}
			}
			// 请求异常
			options.fail = (response) => {
				console.log(response)
				showToast(uni.getStorageSync('ajaxmess').networkerror)
			}

			// 加载配置
			_config = Object.assign({}, this.config, options)
			if (this.interceptor.request) {
				this.interceptor.request(_config)
			}
			uni.request(_config);
		}).catch((e)=>{
			console.log(e)
		});
	},
}
