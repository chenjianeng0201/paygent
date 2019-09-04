<?php
/**
 * PAYGENT B2B MODULE
 * StringUtil.php
 *
 * Copyright (C) 2007 by PAYGENT Co., Ltd.
 * All rights reserved.
 * /

/**
 * 接続モジュール　StringUtitily
 *
 * @version $Revision: 15878 $
 * @author $Author: orimoto $
 */
namespace PaygentModule\Util;

class StringUtil{

	/** 共通で変換するカタカナ文字列のマッピング情報を格納しているマップ */
	var $katakanaMap = array();

	var $zenKana = array("ア", "イ", "ウ", "エ", "オ", "カ", "キ", "ク", "ケ", "コ",
			"サ", "シ", "ス", "セ", /*"ソ", */"タ", "チ", "ツ", "テ", "ト", "ナ", "ニ",
			"ヌ", "ネ", "ノ", "ハ", "ヒ", "フ", "ヘ", "ホ", "マ", "ミ", "ム", "メ",
			"モ", "ヤ", "ユ", "ヨ", "ラ", "リ", "ル", "レ", "ロ", "ワ", "ヲ", "ン",
			"ガ", "ギ", "グ", "ゲ", "ゴ", "ザ", "ジ", "ズ", "ゼ", "ゾ", "ダ", "ヂ",
			"ヅ", "デ", "ド", "バ", "ビ", "ブ", "ベ", "ボ", "ヴ", "パ", "ピ", "プ",
			"ペ", "ポ", "ァ", "ィ", "ゥ", "ェ", "ォ", "ャ", "ュ", "ョ", "ッ", "ー" );

	var $hanKana = array("ｱ", "ｲ", "ｳ", "ｴ", "ｵ", "ｶ", "ｷ", "ｸ", "ｹ", "ｺ",
			"ｻ", "ｼ", "ｽ", "ｾ", "ｿ", "ﾀ", "ﾁ", "ﾂ", "ﾃ", "ﾄ", "ﾅ", "ﾆ",
			"ﾇ", "ﾈ", "ﾉ", "ﾊ", "ﾋ", "ﾌ", "ﾍ", "ﾎ", "ﾏ", "ﾐ", "ﾑ", "ﾒ",
			"ﾓ", "ﾔ", "ﾕ", "ﾖ", "ﾗ", "ﾘ", "ﾙ", "ﾚ", "ﾛ", "ﾜ", "ｦ", "ﾝ",
			"ｶﾞ", "ｷﾞ", "ｸﾞ", "ｹﾞ", "ｺﾞ", "ｻﾞ", "ｼﾞ", "ｽﾞ", "ｾﾞ", "ｿﾞ",
			"ﾀﾞ", "ﾁﾞ", "ﾂﾞ", "ﾃﾞ", "ﾄﾞ", "ﾊﾞ", "ﾋﾞ", "ﾌﾞ", "ﾍﾞ", "ﾎﾞ",
			"ｳﾞ", "ﾊﾟ", "ﾋﾟ", "ﾌﾟ", "ﾍﾟ", "ﾎﾟ", "ｧ", "ｨ", "ｩ", "ｪ", "ｫ",
			"ｬ", "ｭ", "ｮ", "ｯ", "ｰ" );

	/**
	 * デフォルトコンストラクタ
	 */
	function __construct() {

		if (count($this->zenKana) == count($this->katakanaMap)) {
			return;
		}

		for ($i = 0; $i < count($this->zenKana); $i++) {
			$this->katakanaMap[$this->zenKana[$i]] = $this->hanKana[$i];
		}
	}

	/**
	 * パラメータが null または空文字かを判断する
	 *
	 * @param str String 判定する文字列
	 * @return <code>null</code>または空文字の場合、<code>true</code>
	 */
	static function isEmpty($str) {
		return (!isset($str) || strlen(trim($str)) <= 0);
	}

	/**
	 * split(分割数制限版)
	 *
	 * @param str String 分割対象文字列
	 * @param delim String 区切り文字
	 * @param limit int 結果の閾値
	 * @return String[] 分割後の文字配列
	 */
	static function split($str, $delim, $limit = -1) {

		$delimLength = strlen($delim);
		$pos = 0;
		$index = 0;
		$list = array();
		if ($delimLength != 0) {

			while (!(($index = strpos($str, $delim, $pos)) === false)) {
				$list[] = substr($str, $pos, $index-$pos);
				$pos = $index + $delimLength;
				if ($pos >= strlen($str)) break;
			}
			if ($pos == strlen($str)) {
				$list[] = "";		// the last is the delimiter.
			} else 	if ($pos < strlen($str)) {
				$list[] = substr($str, $pos);
			}
		} else {
			for ($i = 0; $i < strlen($str); $i++) {
				$c = $str{$i};
				$list[] = "" . $c;
			}
		}

		$rs = &$list;

		if ((0 < $limit) && ($limit < count($rs))) {
			// limit より、分割数が多い場合、分割数を limit に合わせる
			$temp = array();

			$pos = 0;
			for ($i = 0; $i < $limit - 1; $i++) {
				$temp[] = $rs[$i];
				$pos += strlen($rs[$i]) + strlen($delim);
			}

			$temp[$limit - 1] = substr($str, $pos);
			for ($i = $limit; $i < count($rs); $i++) {
				$sb = $temp[$limit - 1];
			}

			$rs = $temp;
		}

		return $rs;
	}

	/**
	 * 数値判定
	 *
	 * @param str String 数値判定対象文字列
	 * @return boolean true=数値 false=数値以外
	 */
	static function isNumeric($str) {
		$rb = is_numeric($str);

		return $rb;
	}

	/**
	 * 数値、桁数判定
	 *
	 * @param str String 数値判定対象文字列
	 * @param len int 判定対象 Length
	 * @return boolean true=桁数内数値 false=数値でない or 桁数違い
	 */
	static function isNumericLength($str, $len) {
		$rb = false;

		if (StringUtil::isNumeric($str)) {
			if (strlen($str) == $len) {
				$rb = true;
			}
		}

		return $rb;
	}

	/**
	 * 全角カタカナ文字を半角カタカナの該当文字に変換する。 指定された文字列がnullの場合はnullを返す。
	 *
	 * @param src String 変換する元の文字列
	 * @return String 変換後の文字列
	 */
	static function convertKatakanaZenToHan($src) {
		if ($src == null ) {
			return null;
		}
		$str = mb_convert_kana($src, "kV", "SJIS");
		return $str;
	}

	/**
	 * 指定された文字列を指定されたマッピング情報に基づき 変換した結果の文字列を返す。 指定された文字列がnullの場合はnullを返す。
	 *
	 * @param src String 変換する元の文字列
	 * @param convertMap
	 *            Map 変換の対象となる文字と変換後のマッピング情報を格納しているマップ
	 * @return String 変換後の文字列
	 */
	static function convert($src, $convertMap) {
		if ($src == null) {
			return null;
		}
		$chars = $this->toChars($src);
		foreach ($chars as $c) {
			if (array_key_exists($c, $convertMap)) {
				$result .= $convertMap[$c];
			} else {
				$result .=$c;
			}
		}

		return $result;
	}

	static function toChars($str) {

		$chars = array();
		for($i=0; $i<mb_strlen($str); $i++) {
			$out = mb_substr($str, $i, 1);
			$chars[] = $out;
			$intx= 0;
		}
		return $chars;
	}
}
	// 初期化
	$StringUtilInit = new StringUtil();
	$StringUtilInit = null;
?>