<?php
declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Kernel\Payment;

use App\Exception\LogicException;

use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;

/**
 * GagaPay
 *
 * @author  刘兴永(aile8880@qq.com)
 * @package App\Kernel\Payment
 */
class GagaPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'http://p.winpay.today:99/';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ClientFactory
     */
    private $guzzle;

    /**
     * GagaPay constructor.
     *
     * @param ContainerInterface $container
     * @param ClientFactory      $guzzle
     */
    public function __construct(ContainerInterface $container, ClientFactory $guzzle)
    {
        $this->container = $container;
        $this->guzzle    = $guzzle;
    }

    /**
     * 统一下单接口
     *
     * @param string $pay_no
     * @param float  $amount
     * @param array  $extra
     *
     * @return mixed
     */
    public function pay(string $pay_no, float $amount, array $extra = [])
    {
        $params  = [
            'merchant'   => env('GAGA_PAY_MERCHANT', ''),
            'outTradeNo' => $pay_no,
            'type'       => $extra['type'],
            'money'      => $amount,
            'time'       => time(),
            'notifyUrl'  => env('HOST') . 'notify/gaga_pay',
            // 'channelName' => 'CG_EASEBUZZ'
        ];
        $request = $this->guzzle->create()->post(self::BASE_URL . 'api/orderApi/new', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'
            ],
            'form_params' => array_merge($params, [
                'sign' => $this->getSign($params, env('GAGA_PAY_KEY', ''))
            ])
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 'success') {
            throw new LogicException($data['success']);
        }
        return $data;
    }

    /**
     * 代付接口
     *
     * @param string $pay_no
     * @param string $pay_mode
     * @param float  $amount
     * @param string $account_name
     * @param string $bank_account
     * @param string $bank_code
     * @param string $vpa
     * @param string $phone
     * @param string $email
     * @param string $address
     *
     * @return array
     */
    public function payOut(
        string $pay_no,
        string $pay_mode,
        float $amount,
        string $account_name,
        string $bank_account,
        string $bank_code,
        string $vpa,
        string $phone,
        string $email,
        string $address
    )
    {
        $accountNo = ''; //收款账号
        $accountCode = ''; //收款编号
        $bankName = ''; //收款账号开户行名称
        $accountName = ''; //收款人名称
        $accountEmail = ''; //收款人邮箱
        $accountPhone = ''; //收款人手机
        $transferDesc = ''; //转账备注
        $reqTime = time();
        $params  = [
            'mchNo'   => env('PAY_MERCHANT', ''),
            'appId'     => env('APPID'),
            'mchOrderNo'=> $pay_no,
            'ifCode'    => 'paypay',
            'wayCode'   => env('PAY_WAY'),
            'amount'    => $amount,
            'entryType' => 'IMPS',
            'currency'  => env('PAY_CURRENCY'),
            'accountNo' => $accountNo,

            'accountCode' => $accountCode, //收款编号
            'bankName' => $bankName, //收款账号开户行名称
            'accountName' => $accountName, //收款人名称
            'accountEmail' => $accountEmail, //收款人邮箱
            'accountPhone' => $accountPhone, //收款人手机
            'transferDesc'=> $transferDesc,
            'notifyUrl'=>env('HOST') . 'notify/payout',
            'reqTime'=>$reqTime, //请求时间
            'version'=>'1.0',
            'signType'=>'MD5',

        ];
        
        
        $params  = [
            'merchant'    => env('GAGA_PAY_MERCHANT', ''),
            'outTradeNo'  => $pay_no,
            'payMode'     => $pay_mode,
            'amount'      => $amount,
            'accountName' => $account_name,
            'bankAccount' => $bank_account,
            'ifsc'        => $bank_code,
            'vpa'         => $vpa,
            'phone'       => $phone,
            'email'       => $email,
            'address'     => $address,
            'notifyUrl'   => env('HOST') . 'notify/gaga_payout'
        ];
        $request = $this->guzzle->create()->post(self::BASE_URL . 'api/payout/pay', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'
            ],
            'form_params' => array_merge($params, [
                'sign' => $this->getSign($params, env('GAGA_PAY_KEY', ''))
            ])
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 'success') {
            throw new LogicException($data['message']);
        }
        return $data;
    }

    /**
     * 验证签名
     *
     * @param array  $data
     * @param string $sign
     */
    public function verifySign(array $data, string $sign)
    {
        // TODO: Implement verifySign() method.
    }

    /**
     * 获取签名
     *
     * @param array  $data
     * @param string $key
     *
     * @return string
     */
    private function getSign(array $data, string $key)
    {
        ksort($data);
        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}