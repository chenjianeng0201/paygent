<?php
/**
 * PAYGENT B2B MODULE
 * FilePaymentResponseDataImpl.php
 *
 * Copyright (C) 2010 by PAYGENT Co., Ltd.
 * All rights reserved.
 */

namespace PaygentModule\Entity;

use PaygentModule\Util\CSVWriter;
use PaygentModule\Util\CSVTokenizer;
use PaygentModule\Util\StringUtil;
use PaygentModule\Entity\ResponseData;

/**
 * ファイル決済系応答電文処理クラス
 *
 * @version $Revision: 15878 $
 * @author $Author: orimoto $
 */

class FilePaymentResponseDataImpl extends ResponseData {

	/** 処理結果 */
	var $resultStatus;

	/** レスポンスコード */
	var $responseCode;

	/** レスポンス詳細 */
	var $responseDetail;
	private $app;

	public function __construct($app)
	{
		$this->app = $app;
	}
	/**
     * ファイル決済の場合は値を含むパースは不可。
     * 常にExceptionをthrowする。
	 *
	 * @param data
	 */
	function parse($body) {
		trigger_error($this->app['const']['PaygentB2BModuleException__FILE_PAYMENT_ERROR']
				. ": parse is not supported.", E_USER_WARNING);
		return $this->app['const']['PaygentB2BModuleException__FILE_PAYMENT_ERROR'];
	}

	/**
     * data を分解 リザルト情報のみ、変数に設定。
	 *
	 * @param body
	 * @return mixed TRUE:成功、他：エラーコード
	 */
	function parseResultOnly($body) {

		$csvTknzr = new CSVTokenizer($this->app,$this->app['const']['CSVTokenizer__DEF_SEPARATOR'],
			$this->app['const']['CSVTokenizer__NO_ITEM_ENVELOPE']);
		$line = "";

		// リザルト情報の初期化
		$this->resultStatus = "";
		$this->responseCode = "";
		$this->responseDetail = "";

		$lines = explode($this->app['const']['FilePaymentResponseDataImpl__LINE_SEPARATOR'], $body);
		foreach($lines as $i => $line) {
			$lineItem = $csvTknzr->parseCSVData($line);

			if (0 < count($lineItem)) {
				if ($lineItem[$this->app['const']['FilePaymentResponseDataImpl__LINE_RECORD_DIVISION']]
						== $this->app['const']['FilePaymentResponseDataImpl__LINENO_HEADER']) {
					// ヘッダー部の行の場合
					if ($this->app['const']['FilePaymentResponseDataImpl__LINE_HEADER_RESULT'] < count($lineItem)) {
						// 処理結果を設定
						$this->resultStatus = $lineItem[$this->app['const']['FilePaymentResponseDataImpl__LINE_HEADER_RESULT']];
					}
					if ($this->app['const']['FilePaymentResponseDataImpl__LINE_HEADER_RESPONSE_CODE'] < count($lineItem)) {
						// レスポンスコードを設定
						$this->responseCode = $lineItem[$this->app['const']['FilePaymentResponseDataImpl__LINE_HEADER_RESPONSE_CODE']];
					}
					if ($this->app['const']['FilePaymentResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL'] < count($lineItem)) {
						// レスポンス詳細を設定
						$this->responseDetail = $lineItem[$this->app['const']['FilePaymentResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL']];
					}

					// ヘッダーのみの解析で終了
					break;
				}
			}
		}

		if (StringUtil::isEmpty($this->resultStatus)) {
			// 処理結果が 空文字 もしくは null の場合
			trigger_error($this->app['const']['PaygentB2BModuleConnectException__KS_CONNECT_ERROR']
				. ": resultStatus is Nothing.", E_USER_WARNING);
			return $this->app['const']['PaygentB2BModuleConnectException__KS_CONNECT_ERROR'];
		}

		return true;

	}

	/**
     * 次のデータを取得。
	 *
	 * @return Map
	 */
	function resNext() {
		return null;
	}

	/**
     * 次のデータが存在するか判定。
     *
	 * @return boolean true=存在する false=存在しない
	 */
	function hasResNext() {
		return false;
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

	/**
	 * CSV を作成
	 *
	 * @param resBody
	 * @param resultCsv String
	 * @return boolean true：成功、他：エラーコード
	 */
	function writeCSV($body, $resultCsv) {
		$rb = false;

		// CSV を 1行ずつ出力
		$csvWriter = new CSVWriter($this->app, $resultCsv);
		if ($csvWriter->open() === false) {
			// ファイルオープンエラー
			trigger_error($this->app['const']['PaygentB2BModuleException__CSV_OUTPUT_ERROR']
				. ": Failed to open CSV file.", E_USER_WARNING);
			return $this->app['const']['PaygentB2BModuleException__CSV_OUTPUT_ERROR'];
		}

		$lines = explode($this->app['const']['FilePaymentResponseDataImpl__LINE_SEPARATOR'], $body);

		foreach($lines as $i => $line) {
			if(StringUtil::isEmpty($line)) {
				continue;
			}
			if (!$csvWriter->writeOneLine($line)) {
				// 書き込めなかった場合
				trigger_error($this->app['const']['PaygentB2BModuleException__CSV_OUTPUT_ERROR']
					. ": Failed to write to CSV file.", E_USER_WARNING);
				return $this->app['const']['PaygentB2BModuleException__CSV_OUTPUT_ERROR'];
			}
		}

		$csvWriter->close();

		$rb = true;

		return $rb;
	}



}

?>