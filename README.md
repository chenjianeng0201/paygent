# Paygent

## 包含信用卡支付和后支付

## 安装
composer require chenjianeng0201/paygent:dev-master -vvv
## 配置
需要拥有 Paygent 相关证书、私钥、及配置参数

## 使用
```
use Chenjianeng0201\Paygent\Paygent;

$env = 'local';  // 环境 [local、production]
$merchant_id = 'xxxxx'; // merchant_id
$connect_id = 'xxxxxx'; // 连接 id
$connect_password = 'xxxxxx'; // 连接密码
$pem = 'xxxxxxxxxxx'; // 证书路径
$crt = 'xxxxxxx'; //私钥路径
$telegram_version = '1.0'; // 版本，默认 1.0

$p = new Paygent($env, $merchant_id, $connect_id, $connect_password, $pem, $crt, $telegram_version);
```

### 发送信用卡支付请求
```
$split_count = 'xxx'; // 分期数
$card_token = 'xxxxxxxxx'; // token
$trading_id = 'xxxxxxxx'; // 订单号
$payment_amount = 100; // 金额
$result = $p->paySend($split_count, $card_token, $trading_id, $payment_amount);
```


### 发送后支付请求

```
$trading_id = 'xxxxxx'; // 订单号
$payment_amount = 700; // 订单金额
$shop_order_date = date('Ymd', time()); // 日期
$customer_name_kanji = 'xxxxxx'; // 用户名 鈴木 太郎
$customer_name_kana = 'xxxxxxx'; // 用户假名 すずき たろう
$customer_email = 'xxxxxx'; // 邮箱
$customer_zip_code = 'xxxxxx'; // 邮编 2740065
$customer_address = 'xxxxxx'; // 地址 千葉県船橋市高根台7-14-1
$customer_tel = 'xxxxxxx'; // 电话 090-8510-9250

$goods_list = [
            [
                'goods' => '商品1',
                'goods_price' => 300,
                'goods_amount' => 1
            ],
            [
                'goods' => '商品2',
                'goods_price' => 200,
                'goods_amount' => 2
            ],

        ];

$result = $p->afterPaySend($trading_id, $payment_amount, $shop_order_date, $customer_name_kanji, $customer_name_kana,
            $customer_email, $customer_zip_code, $customer_address, $customer_tel, $goods_list);
```

**备注： 需要额外注意返回的 pay_code 为 15007 的情况，此时表示请求审核中**

### 发送后支付取消请求
```
$trading_id = 'xxxxxxx'; // 订单号，与交易 id 可只传一个
$payment_id = 'xxxxxxx'; // 交易 id
$result = $p->afterPayCancel($trading_id, $payment_id);
```


### 发送后支付确认请求
```
$delivery_company_code = 'xxxxxxxxx'; // 快递公司代号， 黑猫为 12，其他公司自行查询
$delivery_slip_no = 'xxxxxxxxxx'; // 快递单号
$trading_id = 'xxxxxxx'; // 订单号，与交易 id 可只传一个
$payment_id = 'xxxxxxx'; // 交易 id
$result = $p->afterPayConfirm($delivery_company_code, $delivery_slip_no, $trading_id, $payment_id);
```


**以下为腾讯云文档 <a href="https://dev.tencent.com/s/d6174133-c098-4426-83a6-307d0ee6608a">跳转</a>**
