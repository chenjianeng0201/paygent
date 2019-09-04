<?php
/**
 * PAYGENT B2B MODULE
 * PaygentB2BModuleException.php
 *
 * Copyright (C) 2007 by PAYGENT Co., Ltd.
 * All rights reserved.
 */
namespace PaygentModule\Exception;

/*
 * 接続モジュール　各種エラー用Exception
 *
 * @version $Revision: 15878 $
 * @author $Author: orimoto $
 */

 class PaygentB2BModuleException {

	/** エラーコード */
	var $errorCode = "";

	/**
	 * コンストラクタ
	 *
	 * @param errorCode String
	 * @param msg String
	 */
	function __construct($errCode, $msg = null) {
		$this->errorCode = $errCode;
	}

	/**
	 * エラーコードを返す
	 *
	 * @return String errorCode
	 */
	function getErrorCode() {
		return $this->errorCode;
	}

	/**
	 * メッセージを返す
	 *
	 * @return String code=message
	 */
    function getLocalizedMessage() {
    }

}
?>
