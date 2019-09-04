<?php
/**
 * PAYGENT B2B MODULE
 * CSVTokenizer.php
 *
 * Copyright (C) 2007 by PAYGENT Co., Ltd.
 * All rights reserved.
 */

/**
 * CSVデータの解析クラス。<BR>
 * １行分の文字列データを、項目リスト（文字列配列）に変換する。<BR>
 * 囲い文字の中に、データとして囲い文字を使用したい場合は、囲い文字2つで、
 * 1つの囲い文字データとみなす。<BR>
 * 囲い文字の中に存在する、区切り文字は、区切り文字としてみない。<BR>
 * 区切り文字の直後の文字が、囲み文字かどうかで囲み文字があるかどうかを判断する。<BR>
 * データ、区切り文字、囲い文字以外の余計な文字
 * （区切り文字の前後のスペース、タブなども）はみとめない。
 * @version $Revision: 15878 $
 * @author $Author: orimoto $
 */
namespace PaygentModule\Util;


class CSVTokenizer {

	/** 項目区切り文字 */
	var $separator = null;
	/** 項目データ囲み文字 */
	var $itemEnvelope = null;

	/** 解析対象データ */
	var $line;
	/** 次の読み出し開始位置 */
	var $currentPos;
	/** 最終読込み位置 */
	var $maxPos;

	private $app;

	/**
	 * コンストラクタ
	 * @param separator 項目区切り文字
	 * @param envelope 項目データ囲み文字
	 */
	public function __construct($app, $separator = ',', $envelope = '"')
	{
		$this->app = $app;
		$this->separator = $separator;
		$this->itemEnvelope = $envelope;
	}

	/**
	 * CSVデータ文字列から項目データ配列を取得する。
	 * @param value 解析対象文字列（1行分のデータ）
	 * @return        データ配列
	 */
	function parseCSVData($value) {
		if (isset($value) == false) {
			return array();
		}
		$this->line = $value;
		$this->maxPos = strlen($this->line);
		$this->currentPos = 0;

		// 項目データを格納する
		$items = array();
		// 囲み文字あり／なしの状態判定フラグ
		$existEnvelope = false;

		while ($this->currentPos <= $this->maxPos) {
			/* データ区切り位置を取得する */
			$endPos = $this->getEndPosition($this->currentPos);

			/* １項目分のデータを読み取る */
			$temp = substr($this->line, $this->currentPos, $endPos - $this->currentPos);
			$work = "";
			// 項目データなしの場合
			if (strlen($temp) == 0) {
				$work = "";
			} else {
				// 囲い文字があるかチェックする
				if ($this->itemEnvelope != null
					&& $temp{0} == $this->itemEnvelope) {
					$existEnvelope = true;
				}

				$isData = false;
				for ($i = 0; $i < strlen($temp);) {
					$chrTmp = $temp{$i};
					if ($existEnvelope == true
						&& $temp{$i} == $this->itemEnvelope) {
						$i++;
						if ($isData == true) {
							if (($i < strlen($temp))
								&& ($this->itemEnvelope != null
									&& $temp{$i}
										== $this->itemEnvelope)) {
								/* 囲み文字が２つ続けて現れたときは、
								 * 文字データとして取得する */
								$work .= $temp{$i++};
							} else {
								$isData = !$isData;
							}
						} else {
							$isData = !$isData;
						}
					} else {
						$work .= $temp{$i++};
					}
				}
			}
			/* １項目分のデータを登録する */
			$items[] = $work;

			/* 次の読取位置の更新 */
			$this->currentPos = $endPos + 1;
		}
		return $items;
	}

	/**
	 *    データ区切り位置を返す。
	 *    @param        start    検索開始位置
	 *    @return        １データの区切り位置を返す
	 */
	function getEndPosition($start) {
		// 文字列／文字列外の状態判定フラグ
		$state = false;
		// 囲み文字あり／なしの状態判定フラグ
		$existEnvelope = false;
		// 読み込んだ文字
		$ch = null;
		// 区切り位置
		$end = 0;

		if ($start >= $this->maxPos) {
			return $start;
		}

		// 囲み文字の有無判定
		if ($this->itemEnvelope != null
			&& $this->line{$start} == $this->itemEnvelope) {
			$existEnvelope = true;
		}

		$end = $start;

		while ($end < $this->maxPos) {
			// １文字読み込む
			$ch = $this->line{$end};
			// 文字の判定
			if ($state == false
				&& $this->separator != null
				&& $ch == $this->separator) {
				// 文字列中の区切り文字でなければ、データ区切り
				break;
			} else if (
				$existEnvelope == true && $ch == $this->itemEnvelope) {
				// 囲み文字が現れたら、文字列／文字列外の状態判定を反転
				if ($state) {
					$state = false;
				} else {
					$state = true;
				}
			}
			// 文字位置のカウントアップ
			$end++;
		}
		return $end;
	}

	/**
	 * 文字列中にカンマが存在する場合は""で囲む。
	 * @param str 変換対象文字列
	 * @return 変換結果文字列
	 */
	function cnvKnmString($str) {
		if (isset($str) == false) {
			return null;
		}
		for ($i = 0; $i < strlen($str); ++$i) {
			if ($str{$i} == $this->app['const']['CSVTokenizer__DEF_SEPARATOR']) {

				return "\"" . $str . "\"";
			}
		}

		return $str;
	}
}

?>
