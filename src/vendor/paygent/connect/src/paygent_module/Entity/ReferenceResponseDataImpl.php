<?php
/**
 * PAYGENT B2B MODULE
 * ReferenceResponseDataImpl.php
 *
 * Copyright (C) 2007 by PAYGENT Co., Ltd.
 * All rights reserved.
 */

namespace PaygentModule\Entity;

use PaygentModule\Util\CSVWriter;
use PaygentModule\Util\CSVTokenizer;
use PaygentModule\Util\StringUtil;
use PaygentModule\Entity\ResponseData;

/**
 * 照会系応答電文処理クラス
 *
 * @version $Revision: 15878 $
 * @author $Author: orimoto $
 */

class ReferenceResponseDataImpl extends ResponseData {
	/** 処理結果 */
	var $resultStatus;

	/** レスポンスコード */
	var $responseCode;

	/** レスポンス詳細 */
	var $responseDetail;

	/** データヘッダー */
	var $dataHeader;

	/** データ */
	var $data;

	/** 現在のIndex */
	var $currentIndex;


	private $app;

	public function __construct($app)
	{
		$this->app = $app;
		$this->dataHeader = array();
		$this->data = array();
		$this->currentIndex = 0;
	}

	/**
	 * data を分解
	 *
	 * @param data
	 * @return mixed TRUE:成功、他：エラーコード
	 */
	function parse($body) {

		$csvTknzr = new CSVTokenizer($this->app, $this->app['const']['CSVTokenizer__DEF_SEPARATOR'],
			$this->app['const']['CSVTokenizer__DEF_ITEM_ENVELOPE']);

		// 保持データを初期化
		$this->data = array();

		// 現在位置を初期化
		$this->currentIndex = 0;

		// リザルト情報の初期化
		$this->resultStatus = "";
		$this->responseCode = "";
		$this->responseDetail = "";

		$lines = explode($this->app['const']['ReferenceResponseDataImpl__LINE_SEPARATOR'], $body);
		foreach($lines as $i => $line) {
			$lineItem = $csvTknzr->parseCSVData($line);

			if (0 < count($lineItem)) {
				if ($lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_RECORD_DIVISION']]
						== $this->app['const']['ReferenceResponseDataImpl__LINENO_HEADER']) {
					// ヘッダー部の行の場合
					if ($this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESULT'] < count($lineItem)) {
						// 処理結果を設定
						$this->resultStatus = $lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESULT']];
					}
					if ($this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE'] < count($lineItem)) {
						// レスポンスコードを設定
						$this->responseCode = $lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE']];
					}
					if ($this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL'] < count($lineItem)) {
						// レスポンス詳細を設定
						$this->responseDetail = $lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL']];
					}
				} else if ($lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_RECORD_DIVISION']]
						== $this->app['const']['ReferenceResponseDataImpl__LINENO_DATA_HEADER']) {
					// データヘッダー部の行の場合
					$this->dataHeader = array();

					for ($i = 1; $i < count($lineItem); $i++) {
						// データヘッダーを設定（レコード区分は除く）
						$this->dataHeader[] = $lineItem[$i];
					}
				} else if ($lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_RECORD_DIVISION']]
						== $this->app['const']['ReferenceResponseDataImpl__LINENO_DATA']) {
					// データ部の行の場合
					// データヘッダー部が既に展開済みである事を想定
					$map = array();

					if (count($this->dataHeader) == (count($lineItem) - 1)) {
						// データヘッダー数と、データ項目数（レコード区分除く）は一致
						for ($i = 1; $i < count($lineItem); $i++) {
							// 対応するデータヘッダーを Key に、Mapへ設定
							$map[$this->dataHeader[$i - 1]] = $lineItem[$i];
						}
					} else {
						// データヘッダー数と、データ項目数が一致しない場合
						$sb = $this->app['const']['PaygentB2BModuleException__OTHER_ERROR'] . ": ";
						$sb .= "Not Mutch DataHeaderCount=";
						$sb .= "" . count($this->dataHeader);
						$sb .= " DataItemCount:";
						$sb .= "" . (count($lineItem) - 1);
						trigger_error($sb, E_USER_WARNING);
						return $this->app['const']['PaygentB2BModuleException__OTHER_ERROR'];
					}

					if (0 < count($map)) {
						// Map が設定されている場合
						$this->data[] = $map;
					}
				} else if ($lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_RECORD_DIVISION']]
						== $this->app['const']['ReferenceResponseDataImpl__LINENO_TRAILER']) {
					// トレーラー部の行の場合
					if ($this->app['const']['ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT'] < count($lineItem)) {
						// データサイズ
					}
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
	 * data を分解 リザルト情報のみ、変数に設定
	 *
	 * @param body
	 * @return mixed TRUE:成功、他：エラーコード
	 */
	function parseResultOnly($body) {

		$csvTknzr = new CSVTokenizer($this->app, $this->app['const']['CSVTokenizer__DEF_SEPARATOR'],
			$this->app['const']['CSVTokenizer__DEF_ITEM_ENVELOPE']);
		$line = "";

		// 保持データを初期化
		$this->data = array();

		// 現在位置を初期化
		$this->currentIndex = 0;

		// リザルト情報の初期化
		$this->resultStatus = "";
		$this->responseCode = "";
		$this->responseDetail = "";

		$lines = explode($this->app['const']['ReferenceResponseDataImpl__LINE_SEPARATOR'], $body);
		foreach($lines as $i => $line) {
			$lineItem = $csvTknzr->parseCSVData($line);

			if (0 < count($lineItem)) {
				if ($lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_RECORD_DIVISION']]
						== $this->app['const']['ReferenceResponseDataImpl__LINENO_HEADER']) {
					// ヘッダー部の行の場合
					if ($this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESULT'] < count($lineItem)) {
						// 処理結果を設定
						$this->resultStatus = $lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESULT']];
					}
					if ($this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE'] < count($lineItem)) {
						// レスポンスコードを設定
						$this->responseCode = $lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE']];
					}
					if ($this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL'] < count($lineItem)) {
						// レスポンス詳細を設定
						$this->responseDetail = $lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL']];
					}
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
	 * 次のデータを取得
	 *
	 * @return Map
	 */
	function resNext() {
		$map = null;

		if ($this->hasResNext()) {

			$map = $this->data[$this->currentIndex];

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

	/**
	 * データ件数を取得
	 *
	 * @param data InputStream
	 * @return int -1:エラー
	 */
	function getDataCount($body) {
		$ri = 0;
		$strCnt = null;

		$csvTknzr = new CSVTokenizer($this->app, $this->app['const']['CSVTokenizer__DEF_SEPARATOR'],
			$this->app['const']['CSVTokenizer__DEF_ITEM_ENVELOPE']);
		$line = "";

		$lines = explode($this->app['const']['ReferenceResponseDataImpl__LINE_SEPARATOR'], $body);
		foreach($lines as $i => $line) {
			$lineItem = $csvTknzr->parseCSVData($line);

			if (0 < count($lineItem)) {
				if ($lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_RECORD_DIVISION']]
						== $this->app['const']['ReferenceResponseDataImpl__LINENO_TRAILER']) {
					// トレーラー部の行の場合
					if ($this->app['const']['ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT'] < count($lineItem)) {
						// データ件数を取得 whileから抜ける
						if (StringUtil::isNumeric($lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT']])) {
							$strCnt = $lineItem[$this->app['const']['ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT']];
						}
						break;
					}
				}
			}
		}

		if ($strCnt != null && StringUtil::isNumeric($strCnt)) {
			$ri = intval($strCnt);
		} else {
			return $this->app['const']['PaygentB2BModuleException__OTHER_ERROR'];		//エラー
		}

		return $ri;
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
			// ファイルオーブンエラー
			trigger_error($this->app['const']['PaygentB2BModuleException__CSV_OUTPUT_ERROR']
				. ": Failed to open CSV file.", E_USER_WARNING);
			return $this->app['const']['PaygentB2BModuleException__CSV_OUTPUT_ERROR'];
		}

		$lines = explode($this->app['const']['ReferenceResponseDataImpl__LINE_SEPARATOR'], $body);
		foreach($lines as $i => $line) {
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