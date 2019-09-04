<?php
/**
 * PAYGENT B2B MODULE
 * ResponseData.php
 *
 * Copyright (C) 2007 by PAYGENT Co., Ltd.
 * All rights reserved.
 */
namespace PaygentModule\Entity;

/**
 * 応答電文処理用インターフェース
 *
 * @version $Revision: 15878 $
 * @author $Author: orimoto $
 */

class ResponseData {

	/**
	 * 受信電文を分解し、メモリ上に保持
	 *
	 * @param data 受信電文
	 * @return boolean TRUE: 成功、FALSE：失敗
	 */
	function parse($data){}

	/**
	 * 受信電文を分解、処理結果、レスポンスコード、レスポンス詳細のみ保持
	 *
	 * @param data 受信電文
	 * @return boolean TRUE: 成功、FALSE：失敗
	 */
	function parseResultOnly($data){}

	/**
	 * 処理結果を取得
	 *
	 * @return String 処理結果
	 */
	function getResultStatus(){}

	/**
	 * レスポンスコードを取得
	 *
	 * @return String レスポンスコード
	 */
	function getResponseCode(){}

	/**
	 * レスポンス詳細を取得
	 *
	 * @return String レスポンス詳細
	 */
	function getResponseDetail(){}

	/**
	 * 受信電文より、1レコード分取得
	 *
	 * @return Map 1レコード分の情報;ない場合、NULLを返す
	 */
	function resNext(){}

	/**
	 * 次のレコードが存在するか判定
	 *
	 * @return boolean 判定結果
	 */
	function hasResNext(){}

}

?>