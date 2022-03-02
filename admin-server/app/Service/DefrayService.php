<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Exception\LogicException;
use App\Kernel\Payment\GagaPay;
use App\Kernel\Payment\OnePay;
use App\Kernel\Payment\TwePay;
use App\Kernel\Payment\ThreePay;
use App\Kernel\Payment\FourPay;
use App\Kernel\Payment\FivePay;
use App\Kernel\Payment\SexPay;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\CountryDAO;
use App\Service\DAO\DefrayDAO;
use App\Service\DAO\UserWithdrawalDAO;

use Hyperf\DbConnection\Db;

/**
 * @package App\Service
 */
class DefrayService extends Base
{
    public function defray(array $params,int $type=1)
    {
       
        
        $defray_country = $this->container->get(CountryDAO::class)->firstById((int)$params['country_id']);
        if (!in_array($defray_country->id, [11, 12]) || !$defray_country) {
            // $this->error('logic.COUNTRY_NOT_FOUND');
        }

        // 处理提现金额
        $country = $this->container->get(CountryDAO::class)->firstById(11);
        $biz_amt = (int)$params['amount'] * $country->exchange_rate;
        
        $date     = date('YmdHis');
        $order_no = 'defray' . $date . mt_rand(1000, 9999);
        
        
        
         // 查找用户提现记录
         $user_withdrawal = $this->container->get(UserWithdrawalDAO::class)->findById((int)$params['user_withdrawal_id']);
         
         
         
        
         
         if($type==1){
            // $df_path = 'moneyBag';
            // $data = $this->container->get(OnePay::class)->pay_out($order_no, $params['yuan'], $params);
            // if ($data['status'] == 'error') {
            //     $this->error($data['msg']);
            // }
            $df_path = 'Timepay';
   
        }elseif($type==2){
            
            $df_path = '七星支付';
           if(empty($params['address'])){
               
                $user_withdrawal->status = 0;
                $user_withdrawal->save();
                $this->error('IFSC不能为空');
            }
            
            if(empty($params['bank_account'])){
                $user_withdrawal->status = 0;
                $user_withdrawal->save();
                $this->error('银行账户不能为空');
            }
        }elseif($type==3){
            $df_path = 'sepPay';
        }elseif($type==5){
             $df_path = 'razorPay';
        }elseif($type==6){
             $df_path = 'leapPay';
        }
        
       
        

        Db::beginTransaction();
        try {
            

            $this->container->get(DefrayDAO::class)->create([
                'country_id'    => $defray_country->id,
                'order_no'      => $order_no,
                'admin_id'      => JwtInstance::instance()->build()->getId(),
                'channel'       => $df_path,
                'amount'        => (int)$params['amount'],
                'exchange_rate' => $defray_country->exchange_rate,
                'name'          => $params['name'],
                'bank_account'  => $params['bank_account'],
                'bank_name'     => $params['bank_name'],
                'bank_code'     => $params['bank_code'],
                'open_province' => $params['open_province'] ?? '',
                'open_city'     => $params['open_city'] ?? '',
                'status'        => 0,
                'mer_order_no'  =>isset($data['order_no'])?$data['order_no']:'数据异常',
                'user_id'       => $params['user_id'],
                'user_withdrawal_id' => $params['user_withdrawal_id'],
                'service_charge'=>$params['service_charge']
            ]);

           
            Db::commit();
        }
        catch (LogicException $e) {
            Db::rollBack();
            $this->error($e->getMessage());
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('efuPay')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
        
        if($type==1){
            // $df_path = 'moneyBag';
            // $data = $this->container->get(OnePay::class)->pay_out($order_no, $params['yuan'], $params);
            // if ($data['status'] == 'error') {
            //     $this->error($data['msg']);
            // }
            //Timepay代付
            $data = $this->container->get(FourPay::class)->pay_out($order_no, $params['yuan'], $params);
            //print_r($data); 
            if ($data['code'] == '0') {
                $user_withdrawal->status = 0;
                $user_withdrawal->save();
                     
                $this->container->get(DefrayDAO::class)->delByOrderNo($order_no);
                $this->error($data['msg']);
            }
        }elseif($type==2){
            //七星代付
            $data = $this->container->get(TwePay::class)->pay_out($order_no, $params['yuan'], $params);
            
            if ($data['status'] == 'FAIL') {
                $user_withdrawal->status = 0;
                $user_withdrawal->save();
                     
                $this->container->get(DefrayDAO::class)->delByOrderNo($order_no);
                $this->error($data['err_msg']);
            }
        }elseif($type==3){
                $data = $this->container->get(ThreePay::class)->pay_out($order_no, $params['yuan'], $params);
                $user_withdrawal->status = 0;
                $user_withdrawal->save();
                     
                $this->container->get(DefrayDAO::class)->delByOrderNo($order_no);
                $this->error($data['err_msg']);
        }elseif($type==5){
            //razorpay代付
            $data = $this->container->get(FivePay::class)->pay_out($order_no, $params['yuan'], $params);
            
            if ($data['code'] == '1111') {
                $user_withdrawal->status = 0;
                $user_withdrawal->save();
                     
                $this->container->get(DefrayDAO::class)->delByOrderNo($order_no);
                $this->error($data['message']);
            }
            
        }elseif($type==6){
            //leapPay代付
            $data = $this->container->get(SexPay::class)->pay_out($order_no, $params['yuan'], $params);
            
            if ($data['code'] != '1') {
                $user_withdrawal->status = 0;
                $user_withdrawal->save();
                     
                $this->container->get(DefrayDAO::class)->delByOrderNo($order_no);
                $this->error($data['msg']);
            }
        }
        
        
    }
    
    public function getDfOrder($orders){
        foreach ($orders as $order){
            if(!empty($order->mer_order_no)){
                 $result = $this->container->get(TwePay::class)->getDfOrder($order->order_no,$order->mer_order_no); 
                 $status = isset($result['query_status'])?$result['query_status']:'';
                 
                 if($status=='SUCCESS'){
                     if($result['status']=='SUCCESS'){
                        $params = [
                                'status'=>1
                             ];
                        $this->container->get(DefrayDAO::class)->edit($order->id,$params);
                     }elseif ($result['status']=='FAIL') {
                        $params = [
                                'status'=>2
                             ]; 
                        $this->container->get(DefrayDAO::class)->edit($order->id,$params);
                     }
                 }
                
            }
        }
       
    }
}