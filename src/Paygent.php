<?php

namespace Chenjianeng0201\Paygent;

require __DIR__.'/vendor/autoload.php';

use Chenjianeng0201\Paygent\Exceptions\InvalidArgumentException;
use PaygentModule\System\PaygentB2BModule;

date_default_timezone_set('Asia/Tokyo');

class Paygent
{
    protected $p;

    /*
     *  初始化
     * @param string $env 环境 [local、production]
     * @param string $merchant_id merchant_id
     * @param string $connect_id 连接 id
     * @param string $connect_password 连接密码
     * @param string $pem 证书路径
     * @param string $crt 私钥路径
     * @param string $telegram_version 版本
     */
    public function __construct($env, $merchant_id, $connect_id, $connect_password, $pem, $crt, $telegram_version = '1.0')
    {
        if (!in_array(strtolower($env), ['local', 'production'])) {
            throw new InvalidArgumentException('Invalid response env: '.$env);
        }

        // env => [local、production], pem => 证书路径, crt => 私钥路径
        $this->p = new PaygentB2BModule($env, $pem, $crt);
        $this->p->init();

        // merchant_id
        $this->p->reqPut('merchant_id', $merchant_id);
        // 连接 id
        $this->p->reqPut('connect_id', $connect_id);
        // 连接密码
        $this->p->reqPut('connect_password', $connect_password);
        // 版本号
        $this->p->reqPut('telegram_version', $telegram_version);
    }

    /*
     * 信用卡支付
     * @param array $params 支付数据
     * @param int split_count 分期数
     * @param string card_token token
     * @param string trading_id 订单号
     * @param string payment_amount 金额
     * @return array
     */
    public function paySend($split_count, $card_token, $trading_id, $payment_amount)
    {
        $this->p->reqPut('3dsecure_ryaku', 1);

        $payment_class = '1' === $split_count ? 10 : 61;
        $this->p->reqPut('split_count', $split_count);
        $this->p->reqPut('payment_class', $payment_class);
        $this->p->reqPut('card_token', $card_token);
        $this->p->reqPut('trading_id', $trading_id);
        $this->p->reqPut('payment_amount', $payment_amount);

        // 支付类型
        $this->p->reqPut('telegram_kind', '020');
        // 发送
        $result = $this->p->post();
        // 1 请求失败，0 请求成功
        if (true !== $result) {
            return ['code' => 1, 'result' => $result];
        } else {
            // 请求成功后 直接确认支付
            if ($this->p->hasResNext()) {
                $res = $this->p->resNext();
                $this->p->reqPut('payment_id', $res['payment_id']);
                // 信用卡确认支付
                $this->p->reqPut('telegram_kind', '022');
            }
            // 发送
            $result = $this->p->post();

            if (true !== $result) {
                return ['code' => 1, 'result' => $result];
            }

            $response = [
                'code' => 0,
                'status' => $this->p->getResultStatus(),
                'pay_code' => $this->p->getResponseCode(), // 0 成功，1 失败，其他为具体报错码
                'payment_id' => $res['payment_id'],
                'detail' => $this->iconv_parse($this->p->getResponseDetail()),
            ];

            return $response;
        }
    }

    /*
     * 后支付请求
     * @param string $trading_id 订单号
     * @param string $payment_amount 订单总金额
     * @param string $shop_order_date 日期 YmdHis
     * @param string $customer_name_kanji 用户名（日文）
     * @param string $customer_name_kana 用户假名（日文）
     * @param string $customer_email 邮箱
     * @param string $customer_zip_code zip_code 2740065
     * @param string $customer_address 地址
     * @param string $customer_tel 电话 090-4500-9650
     * @param array $goods_list
     * @param array $goods_list[goods[0]] 订单商品名
     * @param array $goods_list[goods_price[0]] 单价
     * @param array $goods_list[goods_amount[0]] 数量
     */
    public function afterPaySend($trading_id, $payment_amount, $shop_order_date, $customer_name_kanji, $customer_name_kana,
                                 $customer_email, $customer_zip_code, $customer_address, $customer_tel, $goods_list)
    {
        // 支付类型
        $this->p->reqPut('telegram_kind', '220');

        $this->p->reqPut('trading_id', $trading_id);
        $this->p->reqPut('payment_amount', $payment_amount);
        $this->p->reqPut('shop_order_date', $shop_order_date);
        $this->p->reqPut('customer_name_kanji', $this->iconv_parse2(preg_replace('/\\s+/', '', $this->makeSemiangle($customer_name_kanji))));
        $this->p->reqPut('customer_name_kana', $this->iconv_parse2(preg_replace('/\\s+/', '', $this->makeSemiangle($customer_name_kana))));
        $this->p->reqPut('customer_email', $this->makeSemiangle($customer_email));
        $this->p->reqPut('customer_zip_code', $this->makeSemiangle($customer_zip_code));
        $this->p->reqPut('customer_address', $this->iconv_parse2($this->makeSemiangle($customer_address)));
        $this->p->reqPut('customer_tel', $this->makeSemiangle($customer_tel));

        foreach ($goods_list as $key => $value) {
            $this->p->reqPut('goods['.$key.']', $this->iconv_parse2($this->makeSemiangle($value['goods'])));
            $this->p->reqPut('goods_price['.$key.']', $value['goods_price']);
            $this->p->reqPut('goods_amount['.$key.']', $value['goods_amount']);
        }

        // 请求
        $result = $this->p->post();

        if (true !== $result) {
            return ['code' => 1, 'result' => $result];
        } else {
            // 请求成功
            if (!$this->p->hasResNext()) {
                return ['code' => 1, 'result' => $result];
            }
            $res = $this->p->resNext();

            $response = [
                'code' => 0,
                'status' => $this->p->getResultStatus(),
                'pay_code' => $this->p->getResponseCode(), // 0 成功，1 失败，其他为具体报错码
                'payment_id' => $res['payment_id'],
                'detail' => $this->iconv_parse($this->p->getResponseDetail()),
            ];

            return $response;
        }
    }

    /*
     * 后支付取消
     * @param string $trading_id 订单号
     * @param string $payment_id 交易 id
     * @return array
     */
    public function afterPayCancel($trading_id = null, $payment_id = null)
    {
        // 支付类型
        $this->p->reqPut('telegram_kind', '221');
        // 都传的情况下使用订单号
        isset($trading_id) && null != $trading_id ? $this->p->reqPut('trading_id', $trading_id) : $this->p->reqPut('payment_id', $payment_id);
        $result = $this->p->post();

        if (true !== $result) {
            return ['code' => 1, 'result' => $result];
        } else {
            if (!$this->p->hasResNext()) {
                return ['code' => 1, 'result' => $result];
            }

            $response = [
                'code' => 0,
                'status' => $this->p->getResultStatus(),
                'pay_code' => $this->p->getResponseCode(),
                'detail' => $this->iconv_parse($this->p->getResponseDetail()),
            ];

            return $response;
        }
    }

    /*
     * 后支付确认
     * @param string $delivery_company_code 快递公司
     * @param string $delivery_slip_no 快递单号
     * @param string $trading_id 订单号
     * @param string $payment_id 交易 id
     * @return array
     */
    public function afterPayConfirm($delivery_company_code, $delivery_slip_no, $trading_id = null, $payment_id = null)
    {
        // 支付类型
        $this->p->reqPut('telegram_kind', 222);
        $this->p->reqPut('delivery_company_code', intval($delivery_company_code));
        $this->p->reqPut('delivery_slip_no', $delivery_slip_no);
        // 都传的情况下使用订单号
        isset($trading_id) && null != $trading_id ? $this->p->reqPut('trading_id', $trading_id) : $this->p->reqPut('payment_id', $payment_id);

        $result = $this->p->post();

        if (true !== $result) {
            return ['code' => 1, 'result' => $result];
        } else {
            if (!$this->p->hasResNext()) {
                return ['code' => 1, 'result' => $result];
            }
            $response = [
                'code' => 0,
                'status' => $this->p->getResultStatus(),
                'pay_code' => $this->p->getResponseCode(),
                'detail' => $this->iconv_parse($this->p->getResponseDetail()),
            ];

            return $response;
        }
    }

    /*
     * 全角转半角
     */
    public function makeSemiangle($str)
    {
        $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
            '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
            'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
            'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
            'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
            'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
            'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
            '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
            '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
            '》' => '>',
            '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
            '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
            '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
            '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
            '　' => ' ', '『' => '', '』' => '', '･' => '', );

        return strtr($str, $arr);
    }

    /*
     * 变码格式转化 SHITF_JIS->UTF-8
     * $param string $str
     * return $str
     */
    public function iconv_parse($str)
    {
        return iconv('Shift_JIS', 'UTF-8', $str);
    }

    /*
    * 变码格式转化 UTF-8->SHITF_JIS
    * $param string $str
    * return $str
    */
    public function iconv_parse2($str)
    {
        return iconv('UTF-8', 'Shift_JIS', $str);
    }
}
