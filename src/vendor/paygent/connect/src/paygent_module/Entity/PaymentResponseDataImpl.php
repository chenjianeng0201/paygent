<?php
/**
 * PAYGENT B2B MODULE
 * PaymentResponseDataImpl.php
 *
 * Copyright (C) 2007 by PAYGENT Co., Ltd.
 * All rights reserved.
 */

namespace PaygentModule\Entity;

use PaygentModule\Util\StringUtil;
use PaygentModule\Entity\ResponseData;


/**
 * 決済系応答電文処理クラス
 *
 * @version $Revision: 15878 $
 * @author $Author: orimoto $
 */

class PaymentResponseDataImpl extends ResponseData {

	/** 処理結果 文字列*/
	var $resultStatus;

	/** レスポンスコード 文字列*/
	var $responseCode;

	/** レスポンス詳細 */
	var $responseDetail;

	/** データ array*/
	var $data;

	/** 現在のIndex */
	var $currentIndex;

	private $app;

	public function __construct($app)
	{
		$this->app = $app;
		$this->data = array();
		$this->currentIndex = 0;
	}

	/**
	 * body を分解
	 *
	 * @param レスポンスボディ
	 * @return boolean TRUE: 成功、他：エラーコード
	 */
	function parse($body) {

		$line = "";
		// 保持データを初期化
		$this->data = array();
		$map = array();

		// 現在位置を初期化
		$this->currentIndex = 0;

		// リザルト情報の初期化
		$this->resultStatus = "";
		$this->responseCode = "";
		$this->responseDetail = "";

		// "_html" キー存在フラグ
		$htmlKeyFlg = false;

		// "_htmk" キー値
		$htmlKey = "";

		// "_html" キー出現以後のデータ保持
		$htmlValue = "";


		$PaymentResponseDataImpl__LINE_SEPARATOR = $this->app['const']['PaymentResponseDataImpl__LINE_SEPARATOR'];
		$PaymentResponseDataImpl__PROPERTIES_REGEX = $this->app['const']['PaymentResponseDataImpl__PROPERTIES_REGEX'];
		$PaymentResponseDataImpl__PROPERTIES_REGEX_COUNT = $this->app['const']['PaymentResponseDataImpl__PROPERTIES_REGEX_COUNT'];
		$ResponseData__HTML_ITEM = $this->app['const']['ResponseData__HTML_ITEM'];
		$ResponseData__RESULT = $this->app['const']['ResponseData__RESULT'];
		$ResponseData__RESPONSE_CODE = $this->app['const']['ResponseData__RESPONSE_CODE'];
		$ResponseData__RESPONSE_DETAIL = $this->app['const']['ResponseData__RESPONSE_DETAIL'];

		$lines = explode($PaymentResponseDataImpl__LINE_SEPARATOR, $body);

		foreach($lines as $i => $line) {
			$lineItem = StringUtil::split($line, $PaymentResponseDataImpl__PROPERTIES_REGEX,
				$PaymentResponseDataImpl__PROPERTIES_REGEX_COUNT);

			// 読込終了
			$tmpLen = strlen($lineItem[0]) - strlen($ResponseData__HTML_ITEM);
			if ($tmpLen >= 0
				&&  strpos($lineItem[0], $ResponseData__HTML_ITEM, $tmpLen)
				=== $tmpLen) {
				// Key が "_html" の場合
				$htmlKey = $lineItem[0];
				$htmlKeyFlg = true;
			}
			if ($htmlKeyFlg) {
				if (!(strlen($lineItem[0]) - strlen($ResponseData__HTML_ITEM) >= 0
					&& strpos($lineItem[0], $ResponseData__HTML_ITEM,
						strlen($lineItem[0]) - strlen($ResponseData__HTML_ITEM))
					=== strlen($lineItem[0]) - strlen($ResponseData__HTML_ITEM))) {
					// "_html" Key が読み取られた場合
					$htmlValue .= $line;
					$htmlValue .= $PaymentResponseDataImpl__LINE_SEPARATOR;
				}
			} else {
				if (1 < count($lineItem)) {
					if ($lineItem[0] == $ResponseData__RESULT) {
						// 処理結果を設定
						$this->resultStatus = $lineItem[1];
					} else if ($lineItem[0] == $ResponseData__RESPONSE_CODE) {
						// レスポンスコードを設定
						$this->responseCode = $lineItem[1];
					} else if ($lineItem[0] == $ResponseData__RESPONSE_DETAIL) {
						// レスポンス詳細を設定
						$this->responseDetail = $lineItem[1];
					} else {
						// Mapに設定
						$map[$lineItem[0]] = $lineItem[1];
					}
				}
			}
		}

		if ($htmlKeyFlg) {
			// "_html" Key が出現した場合、設定
			if (strlen($PaymentResponseDataImpl__LINE_SEPARATOR) <= strlen($htmlValue)) {
				if (strpos($htmlValue, $PaymentResponseDataImpl__LINE_SEPARATOR,
						strlen($htmlValue) - strlen($PaymentResponseDataImpl__LINE_SEPARATOR))
					=== strlen($htmlValue) - strlen($PaymentResponseDataImpl__LINE_SEPARATOR)) {
					$htmlValue = substr($htmlValue, 0,
						strlen($htmlValue) - strlen($PaymentResponseDataImpl__LINE_SEPARATOR));
				}
			}
			$map[$htmlKey] = $htmlValue;
		}

		if (0 < count($map)) {
			// Map が設定されている場合
			$this->data[] = $map;
		}

		if (StringUtil::isEmpty($this->resultStatus)) {
			$PaygentB2BModuleConnectException__KS_CONNECT_ERROR = $this->app['const']['PaygentB2BModuleConnectException__KS_CONNECT_ERROR'];
			// 処理結果が 空文字 もしくは null の場合
			trigger_error($PaygentB2BModuleConnectException__KS_CONNECT_ERROR
			. ": resultStatus is Nothing.", E_USER_WARNING);
			return $PaygentB2BModuleConnectException__KS_CONNECT_ERROR;
		}

		return true;
	}

	/**
	 * data を分解 リザルト情報のみ、変数に反映
	 *
	 * @param data
	 * @return boolean TRUE: 成功、FALSE：失敗
	 */
	function parseResultOnly($body) {

		$line = "";

		// 保持データを初期化
		$this->data = array();

		// 現在位置を初期化
		$this->currentIndex = 0;

		// リザルト情報の初期化
		$this->resultStatus = "";
		$this->responseCode = "";
		$this->responseDetail = "";

		$PaymentResponseDataImpl__LINE_SEPARATOR = $this->app['const']['PaymentResponseDataImpl__LINE_SEPARATOR'];
		$PaymentResponseDataImpl__PROPERTIES_REGEX = $this->app['const']['PaymentResponseDataImpl__PROPERTIES_REGEX'];
		$ResponseData__HTML_ITEM = $this->app['const']['ResponseData__HTML_ITEM'];
		$ResponseData__RESULT = $this->app['const']['ResponseData__RESULT'];
		$ResponseData__RESPONSE_CODE = $this->app['const']['ResponseData__RESPONSE_CODE'];
		$ResponseData__RESPONSE_DETAIL = $this->app['const']['ResponseData__RESPONSE_DETAIL'];

		$lines = explode($PaymentResponseDataImpl__LINE_SEPARATOR, $body);
		foreach($lines as $i => $line) {
			$lineItem = StringUtil::split($line, $PaymentResponseDataImpl__PROPERTIES_REGEX);
			// 読込終了
			if (strpos($lineItem[0], $ResponseData__HTML_ITEM)
				=== strlen($lineItem[0]) - strlen($ResponseData__HTML_ITEM)) {
				// Key が "_html" の場合
				break;
			}

			if (1 < count($lineItem)) {
				// 1行ずつ読込(項目数が2以上の場合)
				if ($lineItem[0] == $ResponseData__RESULT) {
					// 処理結果を設定
					$this->resultStatus = $lineItem[1];
				} else if ($lineItem[0] == $ResponseData__RESPONSE_CODE) {
					// レスポンスコードを設定
					$this->responseCode = $lineItem[1];
				} else if ($lineItem[0] == $ResponseData__RESPONSE_DETAIL) {
					// レスポンス詳細を設定
					$this->responseDetail = $lineItem[1];
				}
			}
		}

		if (StringUtil::isEmpty($this->resultStatus)) {
			$PaygentB2BModuleConnectException__KS_CONNECT_ERROR = $this->app['const']['PaygentB2BModuleConnectException__KS_CONNECT_ERROR'];
			// 処理結果が 空文字 もしくは null の場合
			trigger_error($PaygentB2BModuleConnectException__KS_CONNECT_ERROR
				. ": resultStatus is Nothing.", E_USER_WARNING);
			return $PaygentB2BModuleConnectException__KS_CONNECT_ERROR;
		}
		return true;
	}

	/**
	 * 次のデータを取得
	 *
	 * @return Map データがない場合、NULLを戻す
	 */
	function resNext() {
		$map = null;

		if ($this->hasResNext()) {

			$map =$this->data[$this->currentIndex];

			$this->currentIndex++;
		}

		return $map;
	}

	/**
	 * 次のデータが存在するか判定
	 *
	 * @return boolean true=存在する false=存在しない
	 */
	function hasResNext() {
		$rb = false;

		if ($this->currentIndex < count($this->data)) {
			$rb = true;
		}

		return $rb;
	}

	/**
	 * resultStatus を取得
	 *
	 * @return String
	 */
	function getResultStatus() {
		return $this->resultStatus;
	}

	/**
	 * responseCode を取得
	 *
	 * @return String
	 */
	function getResponseCode() {
		return $this->responseCode;
	}

	/**
	 * responseDetail を取得
	 *
	 * @return String
	 */
	function getResponseDetail() {
		return $this->responseDetail;
	}

}

?>