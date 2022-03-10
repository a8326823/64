import http from './network.js'


// 文件上传接口
const uploadFile = (filePath, data = {}, fileType = 'image') => {
	return http.uploadFile('upload', filePath, data, fileType)
}
function uploadajax(url, filePath, data, fileType){
	return http.uploadFile(url, filePath, data, fileType);
}

function requestajx(apiurl,method,data){
	// console.log(data)
	return http.request({
			url: apiurl,
			dataType: 'json',
			method: method,
			data,
		})
} 



// 注册
const authRegister = (data) => {
	return http.request({
		url: 'auth/register',
		dataType: 'json',
		method: 'POST',
		data
	})
}
// 手机号登录
const phoneLogin = (data) => {
	return http.request({
		url: 'auth/phoneLogin',
		dataType: 'json',
		method: 'POST',
		data
	})
}
// 验证码
const getCode = (code, data) => {
	return http.request({
		url: 'sms/sendCode/' + code,
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 登录
const login = (data) => {
	return http.request({
		url: 'auth/login',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 找回密码
const resetPass = (data) => {
	return http.request({
		url: 'auth/resetPass',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 修改密码
const accountPassword = (data) => {
	return http.request({
		url: 'account/password',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 个人信息
const accountInfo = (data) => {
	return http.request({
		url: 'account/info',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 修改昵称
const accountNickname = (data) => {
	return http.request({
		url: 'account/nickname',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 绑定提现账户
const accountBank = (data) => {
	return http.request({
		url: 'account/bank',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 修改性别
const accountGender = (data) => {
	return http.request({
		url: 'account/gender',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 修改图像
const accountAvatar = (data) => {
	return http.request({
		url: 'account/avatar',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 修改手机号
const accountPhone = (data) => {
	return http.request({
		url: 'account/phone',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 收益
const accountIndex = (data) => {
	return http.request({
		url: 'account/index',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 今日收益
const getTodayProfit = (data) => {
	return http.request({
		url: 'user_bill/today_profit',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 用户账单
const userBill = (data) => {
	return http.request({
		url: 'user_bill',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 规则
const creditRule = (data) => {
	return http.request({
		url: 'credit/rule',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 签到
const signIn = (data) => {
	return http.request({
		url: 'credit/sign_in',
		dataType: 'json',
		method: 'POST',
		data
	})
}
// 信用记录
const credit = (data) => {
	return http.request({
		url: 'credit',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 消息记录
const userNotify = (data) => {
	return http.request({
		url: 'user_notify',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 消息详情
const notifyDetail = (data) => {
	return http.request({
		url: 'user_notify/read',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 提现限制
const withdrawalLimit = (data) => {
	return http.request({
		url: 'user_withdrawal/limit',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 提现限制
const userWithdrawal = (data) => {
	return http.request({
		url: 'user_withdrawal',
		dataType: 'json',
		method: 'POST',
		data
	})
}
// 提现记录
const withdrawalList = (data) => {
	return http.request({
		url: 'user_withdrawal',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 提现记录
const PayList = (data) => {
	return http.request({
		url: 'user_recharge',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 获取隐私政策
const privacyPolicy = (data) => {
	return http.request({
		url: 'help/privacy_policy',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 获取用户协议
const usageAgreement = (data) => {
	return http.request({
		url: 'help/usage_agreement',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 团队列表
const teamList = (data) => {
	return http.request({
		url: 'team',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 团队列表
const help = (data) => {
	return http.request({
		url: 'help',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 收款码
const codePic = (data) => {
	return http.request({
		url: 'user_recharge/qr_code',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 等级列表
const userLevel = (data) => {
	return http.request({
		url: 'user_level',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 充值等级列表
const userLevelRecharge = (data) => {
	return http.request({
		url: 'user_level/recharge',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 上传扫码充值记录
const manual = (data) => {
	return http.request({
		url: 'user_recharge/manual',
		dataType: 'json',
		method: 'POST',
		data
	})
}
// 任务分类
const taskCategory = (data) => {
	return http.request({
		url: 'task/category',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 任务列表
const task = (data) => {
	return http.request({
		url: 'task',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 任务列表
const taskDetail = (id) => {
	return http.request({
		url: 'task/detail/'+id,
		dataType: 'json',
		method: 'GET'
	})
}
// 任务列表
const taskReceive = (data) => {
	return http.request({
		url: 'task/receive',
		dataType: 'json',
		method: 'POST',
		data
	})
}
// 用户领取任务列表的状态
const userTaskList = (data) => {
	return http.request({
		url: 'task/userTaskList',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 用户领取任务列表的状态
const bankList = (data) => {
	return http.request({
		url: 'user_withdrawal/bank',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 取消任务
const taskCancel = (data) => {
	return http.request({
		url: 'task/cancel',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 提交任务
const taskSubmit = (data) => {
	return http.request({
		url: 'task/submit',
		dataType: 'json',
		method: 'PUT',
		data
	})
}
// 客服
const customerUrl = (data) => {
	return http.request({
		url: 'help/customer_url',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 客服
const promotionCopy = (data) => {
	return http.request({
		url: 'team/app_download_url',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 获取银行卡信息
const receivingBank = (data) => {
	return http.request({
		url: 'user_recharge/receiving_bank',
		dataType: 'json',
		method: 'GET',
		data
	})
}
// 银行卡充值提交
const bankBecharge = (data) => {
	return http.request({
		url: 'user_recharge/bank_recharge',
		dataType: 'json',
		method: 'POST',
		data
	})
}
export default {
	uploadFile,
	uploadajax,
	requestajx,
	phoneLogin,
	authRegister,
	getCode,
	login,
	resetPass,
	accountPassword,
	accountInfo,
	accountNickname,
	accountBank,
	accountGender,
	accountAvatar,
	accountPhone,
	accountIndex,
	getTodayProfit,
	userBill,
	creditRule,
	signIn,
	credit,
	userNotify,
	notifyDetail,
	withdrawalLimit,
	userWithdrawal,
	withdrawalList,
	PayList,
	privacyPolicy,
	usageAgreement,
	teamList,
	help,
	codePic,
	userLevel,
	manual,
	userLevelRecharge,
	taskCategory,
	task,
	taskDetail,
	taskReceive,
	userTaskList,
	taskCancel,
	taskSubmit,
	bankList,
	customerUrl,
	promotionCopy,
	receivingBank,
	bankBecharge
}
