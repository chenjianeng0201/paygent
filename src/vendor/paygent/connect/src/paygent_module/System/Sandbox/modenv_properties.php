<?php
### Client Certificate File        ###
### �N���C�A���g�ؖ����t�@�C���p�X ###
paygentB2Bmodule.client_file_path=/app/Handlers/paygent/config/Sandbox/test-20180516_client_cert.pem

### Trusted Server Certificate ###
### �F�؍ς݂�CA�t�@�C���p�X   ###
paygentB2Bmodule.ca_file_path=/app/Handlers/paygent/config/Sandbox/curl-ca-bundle.crt


### Proxy Server Settings ( Edit them when connections are proxied) ###
### �v���L�V�T�[�o�[�ݒ�i�v���L�V�T�[�o�[���g�p����ꍇ�̂ݐݒ�j  ###
paygentB2Bmodule.proxy_server_name=
paygentB2Bmodule.proxy_server_ip=
paygentB2Bmodule.proxy_server_port=0

### Default ID/Password (Used when these values are not specified within programs) ###
### �ڑ�ID�A�ڑ��p�X���[�h���ݒ肳��Ȃ��ꍇ�Ɏg�p�����f�t�H���g�l�i�󔒉j     ###
paygentB2Bmodule.merchant_id=
paygentB2Bmodule.default_id=
paygentB2Bmodule.default_password=

### Timeout value in second ###
### �^�C���A�E�g�l�i�b�j     ###
paygentB2Bmodule.timeout_value=35

### Program Log File     ###
### ���O�t�@�C���o�̓p�X ###
paygentB2Bmodule.log_output_path=

###�f�o�b�O�I�v�V����###
# 1:���N�G�X�g/���X�|���X�����O�o��
# 0:�G���[���̂ݏo��
# ���{�ԉғ����͕K��0��ݒ肵�Ă�������
paygentB2Bmodule.debug_flg=0

#!!!  DO NOT EDIT BELOW THIS LINE   !!!
#!!! �ȉ��̒l�͕ҏW���Ȃ��ł������� !!!

###�ő�Ɖ���i2000�����y�C�W�F���g�V�X�e���̍ő�l�Ȃ̂ł���ȏ�̒l�͖����j###
paygentB2Bmodule.select_max_cnt=2000

###CSV�o�͑Ώ�###
paygentB2Bmodule.telegram_kind.ref=027,090
###ATM����URL###
paygentB2Bmodule.url.01=https://sandbox.paygent.co.jp/n/atm/request
###�N���W�b�g�J�[�h����URL1###
paygentB2Bmodule.url.02=https://sandbox.paygent.co.jp/n/card/request
###�N���W�b�g�J�[�h����URL2###
paygentB2Bmodule.url.11=https://sandbox.paygent.co.jp/n/card/request
###�N���W�b�g�J�[�h����(���ʉ�)URL###
paygentB2Bmodule.url.18=https://sandbox.paygent.co.jp/n/card/request
###�N���W�b�g�J�[�h����(�[���ǎ�)URL###
paygentB2Bmodule.url.19=https://sandbox.paygent.co.jp/n/card/request
###�N���W�b�g�J�[�h����URL(�p���ۋ��p)###
paygentB2Bmodule.url.28=https://sandbox.paygent.co.jp/n/card/request
###�N���W�b�g�J�[�h����URL(�p���ۋ��Ɖ�p)###
paygentB2Bmodule.url.096=https://sandbox.paygent.co.jp/n/card/request
###�R���r�j�ԍ���������URL###
paygentB2Bmodule.url.03=https://sandbox.paygent.co.jp/n/conveni/request
###�R���r�j���[��������URL###
paygentB2Bmodule.url.04=https://sandbox.paygent.co.jp/n/conveni/request_print
###��s�l�b�g����URL###
paygentB2Bmodule.url.05=https://sandbox.paygent.co.jp/n/bank/request
###��s�l�b�g����ASPURL###
paygentB2Bmodule.url.06=https://sandbox.paygent.co.jp/n/bank/requestasp
###���z��������URL###
paygentB2Bmodule.url.07=https://sandbox.paygent.co.jp/n/virtualaccount/request
###���Ϗ��Ɖ�URL###
paygentB2Bmodule.url.09=https://sandbox.paygent.co.jp/n/ref/request
###���Ϗ�񍷕��Ɖ�URL###
paygentB2Bmodule.url.091=https://sandbox.paygent.co.jp/n/ref/paynotice
###�L�����A�p���ۋ������Ɖ�URL###
paygentB2Bmodule.url.093=https://sandbox.paygent.co.jp/n/ref/runnotice
###���Ϗ��Ɖ�URL###
paygentB2Bmodule.url.094=https://sandbox.paygent.co.jp/n/ref/paymentref
###�g�уL�����A����URL###
paygentB2Bmodule.url.10=https://sandbox.paygent.co.jp/n/c/request
###�g�уL�����A����URL�i�p���ۋ��p�j###
paygentB2Bmodule.url.12=https://sandbox.paygent.co.jp/n/c/request
###�t�@�C������URL###
paygentB2Bmodule.url.20=https://sandbox.paygent.co.jp/n/o/requestdata
###�d�q�}�l�[����URL###
paygentB2Bmodule.url.15=https://sandbox.paygent.co.jp/n/emoney/request
###PayPal����URL###
paygentB2Bmodule.url.13=https://sandbox.paygent.co.jp/n/paypal/request
###�J�[�h�ԍ��Ɖ�URL###
paygentB2Bmodule.url.095=https://sandbox.paygent.co.jp/n/ref/cardnoref
###�㕥������URL###
paygentB2Bmodule.url.22=https://sandbox.paygent.co.jp/n/later/request
###�����U�֌���URL###
paygentB2Bmodule.url.26=https://sandbox.paygent.co.jp/n/accounttransfer/request
###�l�b�g�����U�֎�tURL###
paygentB2Bmodule.url.263=https://sandbox.paygent.co.jp/n/accounttransfer/receipt
###�l�b�g�����U�֎�tURL###
paygentB2Bmodule.url.264=https://sandbox.paygent.co.jp/n/accounttransfer/receipt
###�y�V�y�CURL###
paygentB2Bmodule.url.27=https://sandbox.paygent.co.jp/n/rakutenid/request
###JCBPREMO����URL###
paygentB2Bmodule.url.29=https://sandbox.paygent.co.jp/n/jcbpremo/request
###����l�b�g����URL###
paygentB2Bmodule.url.30=https://sandbox.paygent.co.jp/n/upop/request
###Alipay���ی���URL###
paygentB2Bmodule.url.31=https://sandbox.paygent.co.jp/n/alipay/request
###ApplePayURL###
paygentB2Bmodule.url.32=https://sandbox.paygent.co.jp/n/applepay/request
###PaidyURL###
paygentB2Bmodule.url.34=https://sandbox.paygent.co.jp/n/paidy/request
###GooglePayURL###
paygentB2Bmodule.url.35=https://sandbox.paygent.co.jp/n/googlepay/request
?>
