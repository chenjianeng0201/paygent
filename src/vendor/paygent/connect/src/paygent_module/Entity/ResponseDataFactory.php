<?php
/**
 * PAYGENT B2B MODULE
 * ResponseDataFactory.php
 *
 * Copyright (C) 2007 by PAYGENT Co., Ltd.
 * All rights reserved.
 */

namespace PaygentModule\Entity;

use PaygentModule\System\PaygentB2BModuleResources;
use PaygentModule\Entity\ReferenceResponseDataImpl;
use PaygentModule\Entity\FilePaymentResponseDataImpl;

/**
 * 応答電文処理用オブジェクト作成クラス
 *
 * @version $Revision: 15878 $
 * @author $Author: orimoto $
 */
class ResponseDataFactory {
	private $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * ResponseData を作成
	 *
	 * @param kind
	 * @return ResponseData
	 */
	public function create($kind) {
		$resData = null;
		$masterFile = null;

		$masterFile = PaygentB2BModuleResources::getInstance($this->app);

		// Create ResponseData
		if ($this->app['const']['PaygentB2BModule__TELEGRAM_KIND_FILE_PAYMENT_RES'] == $kind) {
			// ファイル決済結果照会の場合
			$resData = new FilePaymentResponseDataImpl($this->app);
		} elseif ($masterFile->isTelegramKindRef($kind)) {
			// 照会の場合
			$resData = new ReferenceResponseDataImpl($this->app);
		} else {
			// 照会以外の場合
			$resData = new PaymentResponseDataImpl($this->app);
		}

		return $resData;
	}

}

?>