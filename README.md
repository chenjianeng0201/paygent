# Paygent

## 包含信用卡支付和后支付

![StyleCI build status](https://github.styleci.io/repos/206307156/shield) 

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

### 前台 Token 获取参考代码
```
<html>
<head>
    <meta http-equiv="content-type" content="txt/html; charset=utf-8"/>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
    </script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
</head>
<body>
<hr style="margin-top: 0;">
<form action="pay.php" method="post" id="form">
    <div class="form-group">
        <p>请求</p>
    </div>
    <div class="form-group">
        <input type="text" name="trading_id" class="form-control" value="test12345678" placeholder="请输入订单号">
        <p class="t">示例: test12345678</p><br/>
    </div>
    <div class="form-group">
        <input type="text" name="card_number" class="form-control" value="xxxxxxxxxxxxx" placeholder="请输入信用卡号">
        <p class="t">示例: 4023123456780000</p><br/>
    </div>
    <div class="form-group">
        <input type="text" name="expire_year" class="form-control" value="19" placeholder="请输入年份">
        <p class="t">示例: 19</p><br/>
    </div>
    <div class="form-group">
        <input type="text" name="expire_month" class="form-control" value="09" placeholder="请输入月份">
        <p class="t">示例: 09</p><br/>
    </div>
    <div class="form-group">
        <input type="text" name="cvc" class="form-control" value="123" placeholder="请输入安全码">
        <p class="t">示例: 123</p><br/>
    </div>
    <div class="form-group">
        <input type="text" name="name" class="form-control" value="user" placeholder="请输入用户名">
        <p class="t">示例: user</p><br/>
    </div>
    <div class="form-group">
        <input type="text" name="split_count" class="form-control" value="1" placeholder="请输入分期数">
        <p class="t">示例: 1</p><br/>
    </div>
    <input type="hidden" name="card_token" val="">
    <button type="button" class="btn btn-default" onclick="checkSubmit()">提交</button>
</form>
<style>
    form {
        padding-left: 15px;
    }

    p {
        color: red;
    }

    .t {
        margin: 0;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-control {
        width: 40%;
    }
</style>
<!-- 测试环境用 -->
<script type="text/javascript" src="https://sandbox.paygent.co.jp/js/PaygentToken.js" charset="UTF-8"></script>
<!-- 生产环境用 -->
<!-- <script type="text/javascript" src="https://token.paygent.co.jp/js/PaygentToken.js" charset="UTF-8"></script> -->
<script type="text/javascript">

    function checkSubmit() {
        var card_number = $('input[name=card_number]').val()
        var expire_year = $('input[name=expire_year]').val()
        var expire_month = $('input[name=expire_month]').val()
        var cvc = $('input[name=cvc]').val()
        var name = $('input[name=number]').val()

        var paygentToken = new PaygentToken()
        paygentToken.createToken(
            'xxxxx', // merchant_id
            'xxxxxxxxxxxxxxx', // token
            {
                card_number: card_number, // 卡号
                expire_year: expire_year, // 有效年 19
                expire_month: expire_month, // 有效月 09
                cvc: cvc, // 安全码
                name: name, // 用户名
            }, execPurchase
        )
        return false
    }

    function execPurchase(response) {
        var msg = '';
        switch (response.result) {
            case '0000':
                $('input[name=card_token]').val(response.tokenizedCardObject.token);
                $('#form').submit();
                break;
            case '1100':
                msg = 'マーチャントID - 必須エラー';
                break;
            case '1200':
                msg = 'トークン生成公開鍵 - 必須エラー';
                break;
            case '1201':
                msg = 'トークン生成公開鍵 - 不正エラー';
                break;
            case '1300':
                msg = 'カード番号 - 必須チェックエラー';
                break;
            case '1301':
                msg = 'カード番号 - 書式チェックエラー';
                break;
            case '1400':
                msg = '有効期限(年) - 必須チェックエラー';
                break;
            case '1401':
                msg = '有効期限(年) - 書式チェックエラー';
                break;
            case '1500':
                msg = '有効期限(月) - 必須チェックエラー';
                break;
            case '1501':
                msg = '有効期限(月) - 書式チェックエラー';
                break;
            case '1502':
                msg = '有効期限(年月)が不正です。';
                break;
            case '1600':
                msg = 'セキュリティコード - 書式チェックエラー';
                break;
            case '1601':
                msg = 'セキュリティコード - 必須エラー（セキュリティコードトークンの場合）';
                break;
            case '1700':
                msg = 'カード名義 - 書式チェックエラー';
                break;
            case '7000':
                msg = '非対応のブラウザです。';
                break;
            case '7001':
                msg = 'ペイジェントとの通信に失敗しました。';
                break;
            case '8000':
                msg = 'システムメンテナンス中です。';
                break;
            case '9000':
                msg = 'ペイジェント決済システム内部エラー';
                break;
        }
        if (response.result != '0000') {
            alert(msg);
        }
    }

</script>
</body>
</html>
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

## 在 Laravel 中使用
在 Laravel 中使用也是同样的安装方式，配置写在 `config/services.php` 中
```
·
·
·
'paygent' => [
    'env' => env('PAYGENT_ENV', 'local'),
    'merchant_id' => env('PAYGENT_MERCHANT_ID', ''),
    'connect_id' => env('PAYGENT_CONNECT_ID', ''),
    'connect_password' => env('PAYGENT_CONNECT_PASSWORD', ''),
    'token' => env('PAYGENT_TOKEN', ''), // 备注：此 token 为前台页面获取信用卡 token 时使用
    'pem' => app_path() . env('PAYGENT_PEM', ''),
    'crt' => app_path() . env('PAYGENT_CRT', ''),
    'telegram_version' => env('PAYGENT_TELEGRAM_VERSION', '1.0'),
]
```

然后在 `.env` 中配置以上参数
```
PAYGENT_ENV=local
PAYGENT_MERCHANT_ID=
PAYGENT_CONNECT_ID=
PAYGENT_CONNECT_PASSWORD=
PAYGENT_TOKEN=
PAYGENT_PEM=/Config/Paygent/Sandbox/xxxx.pem
PAYGENT_CRT=/Config/Paygent/Sandbox/xxxxx.crt
PAYGENT_TELEGRAM_VERSION=1.0
```

**备注**
`PAYGENT_PEM` 和 `PAYGENT_CRT` 为证书密钥文件的位置

方法参数注入
```
public function test(Paygent $paygent)
{
    $split_count = 'xxx'; // 分期数
    $card_token = 'xxxxxxxxx'; // token
    $trading_id = 'xxxxxxxx'; // 订单号
    $payment_amount = 100; // 金额
    $result = $paygent->paySend($split_count, $card_token, $trading_id, $payment_amount);
}
```

服务器访问
```
public function test()
{
    $split_count = 'xxx'; // 分期数
    $card_token = 'xxxxxxxxx'; // token
    $trading_id = 'xxxxxxxx'; // 订单号
    $payment_amount = 100; // 金额
    $result = app('paygent')->paySend($split_count, $card_token, $trading_id, $payment_amount);
}
```

**以下为腾讯云文档 <a href="https://dev.tencent.com/s/d6174133-c098-4426-83a6-307d0ee6608a">跳转</a>**
