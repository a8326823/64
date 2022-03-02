<?php

declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\UserBillDAO;
use App\Service\DAO\UserNotifyContentDAO;
use App\Service\DAO\UserNotifyDAO;
use App\Service\DAO\UserWithdrawalDAO;
use App\Service\Dao\CountryDAO;
use Hyperf\DbConnection\Db;



/**
 * 用户提现服务
 *
 * @author 李想
 * @package App\Service
 */
class UserWithdrawalService extends Base
{
    /**
     * 提现审核
     *
     * @param array $params
     */
    public function audit(array $params)
    {
        // 查找用户提现记录
        $user_withdrawal = $this->container->get(UserWithdrawalDAO::class)->findById((int)$params['id']);
        if (!$user_withdrawal) {
            $this->error('logic.USER_WITHDRAWAL_NOT_FOUND');
        }

        // 判断提现状态
        if ($user_withdrawal->status !== 0) {
            $this->error('logic.WITHDRAWAL_IS_NOT_TO_BE_REVIEWED');
        }

        // 查找提现用户
        $user = $this->container->get(MemberDAO::class)->findUserById($user_withdrawal->user_id);
        if (!$user) {
            $this->error('logic.WITHDRAWAL_USER_NOT_FOUND');
        }
        
        //代付审批进行中状态修改
        if($params['df_status']!=6){
            $user_withdrawal->status = 3;
            $user_withdrawal->save();
            $user_withdrawal = $this->container->get(UserWithdrawalDAO::class)->findById((int)$params['id']);
            if($user_withdrawal->status!=3){
                 $this->error('logic.WITHDRAWAL_USER_STATUS_NOT_FOUND');
            }
        }
        

        $user_withdrawal->admin_id = JwtInstance::instance()->build()->getId();
        $user_withdrawal->remark   = trim($params['remark'] ?? '');
        
        $userInfo =  $this->container->get(MemberDAO::class)->findUserInfoById($user_withdrawal->user_id);
       
        $params['df_status'] = isset($params['df_status'])?$params['df_status']:0;
        if($params['df_status']>0){
            $count_id = 9;  //第三方平台是INR代付
            $country = $this->container->get(MemberDAO::class)->findCountyById((int)$count_id);
            $yuan = $user_withdrawal->amount*$country->exchange_rate;
            //提醒手续费
            $yuan = $yuan*(1-$user_withdrawal->service_charge);
            $data['country_id'] = $count_id;
            $data['amount'] =$user_withdrawal->amount;
            $data['yuan'] = $yuan;
            $data['name']=$user_withdrawal->name;
            $data['bank_account']=$user_withdrawal->account;
            $data['bank_name']=$user_withdrawal->bank_name;
            $data['bank_code']=$userInfo->bank_code;
            $data['open_province'] ='';
            $data['open_city'] ='';
            $data['user_mobile'] =$userInfo->phone;
            $data['user_email'] =$user->email?$user->email:''; //第一个支付的代付邮箱必填
            $data['address'] = $user_withdrawal->bank_address;
            $data['user_id'] = $user->id;
            $data['user_withdrawal_id'] = $user_withdrawal->id;
            $data['service_charge'] = $user_withdrawal->service_charge;
            $data['user_id']    = $user->id;

            $this->container->get(DefrayService::class)->defray($data,$params['df_status']);
        }
        
        
        // 操作
        Db::beginTransaction();
        try {
            switch ((int)$params['status']) {
                case 2: // 拒绝
                    $user_withdrawal->status = 2;
                    $user_withdrawal->save();

                    // 记录账单
                    $this->container->get(UserBillDAO::class)->create([
                        'user_id'        => $user->id,
                        'type'           => 5,
                        'balance'        => $user_withdrawal->amount,
                        'before_balance' => $user->balance,
                        'after_balance'  => $user->balance + $user_withdrawal->amount
                    ]);

                    // 返还提现金额
                    $user->increment('balance', $user_withdrawal->amount);

                    // 添加用户通知
                    $this->container->get(UserNotifyDAO::class)->create([
                        'type'    => 1,
                        'user_id' => $user->id,
                        'title'   => 'system_notification',
                        'content' => 'Withdrawal failed'
                    ]);

                    break;
                case 1: // 通过
                    $user_withdrawal->status = 1;
                    $user_withdrawal->save();

                    // 添加用户通知
                    $this->container->get(UserNotifyDAO::class)->create([
                        'type' => 1,
                        'user_id' => $user->id,
                        'title' => 'system_notification',
                        'content' => 'Withdrawal passed'
                    ]);
                    break;
                default:
                    throw new \Exception(__('logic.WITHDRAWAL_RESULT_ERROR'));
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->logger('userWithdrawal')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }
}