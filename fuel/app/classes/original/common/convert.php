<?php

namespace Original\Common;

class Convert
{

	/**
	* 文字列中のhttpにリンクをつけて返す
	*
	* @param string $str リンクをつける元データ
	* @return string 
	*/
	public function auto_linker($str) {
		
		$pat_sub = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/'); // 正規表現向けのエスケープ処理
        $pat  = '/((http|https):\/\/[0-9a-z' . $pat_sub . ']+)/i'; // 正規表現パターン
        $rep  = '<a href="\\1" id="external_link">\\1</a>'; // \\1が正規表現にマッチした文字列に置き換わります
 
        $str = preg_replace ($pat, $rep, $str); // 実処理
        
        return $str;
		
	}

}