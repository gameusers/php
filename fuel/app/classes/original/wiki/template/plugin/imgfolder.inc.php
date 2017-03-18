<?php
/* ***********************************************************

 ■イメージフォルダ画像引用プラグイン imgfolder.inc.php

   機能 :特定の画像フォルダにあるファイルの画像を、大きさを指定して引用表示する
　　（width,height属性自動負荷)

   Author: 2007.01.26 吉野智紀(jack_sonic) 
   Site: http://www.yoshino-tech.com/

 [Usage]:----------------------------------------
 書式 [タイトル(alt属性)]は必須ではありません。
 
  &imgfolder(画像ファイル名, [タイトル]){};  //** 原寸表示 
  &imgfolder(画像ファイル名, 80%, [タイトル] ){};  //** 大きさをパーセントで指定して表示、（クリックすると原寸表示）
  &imgfolder(画像ファイル名, 幅, 高さ, [タイトル] ){};  //** 幅と高さを数値で指定して表示

[ セキュリティ上のこと]
-指定できるファイルは必ず、画像用フォルダのDEF_IMG_FOLDER以下に制約されます。外部パスは指定できません。
-パーセント、幅や高さに異常値が入らないように制約されます。
-このプラグインで作られたimgタグには CSSのクラスimgfolder が付きます。

 インライン用の関数です。
 ------------------------------------------------
*/
//[設定] 引用元画像フォルダの設定
//
//define("DEF_IMG_FOLDER" , IMAGE_DIR ); 								//A: WikiのデフォルトのIMAGE_DIRをルートとする場合
//define("DEF_IMG_FOLDER" , 'C:/xampp/htdocs/gameusers/public/assets/img/wiki/' ); //B: 自分でルートを指定する場合
//define("DEF_IMG_FOLDER" , 'https://192.168.10.2/gameusers/public/assets/img/wiki/' ); //B: 自分でルートを指定する場合

if (empty($_SERVER["HTTPS"]))
{
	$imgfolder_url = 'http://' . $_SERVER["HTTP_HOST"];
}
else
{
	$imgfolder_url = 'https://' . $_SERVER["HTTP_HOST"];
}

if(strpos($imgfolder_url, '192.168.10.2') === false)
{
	define("IMGFOLDER_LOCAL" , false);
	$imgfolder_id_arr = explode('/', $_SERVER["REQUEST_URI"]);
	$imgfolder_url = $imgfolder_url . '/assets/img/wiki/' . $imgfolder_id_arr[2] . '/';
}
else
{
	define("IMGFOLDER_LOCAL" , true);
	$imgfolder_id_arr = explode('/', $_SERVER["REQUEST_URI"]);
	$imgfolder_url = $imgfolder_url . '/gameusers/public/assets/img/wiki/' . $imgfolder_id_arr[4] . '/';
	
	//echo $_SERVER["REQUEST_URI"];
	//var_dump($imgfolder_id_arr);
	//echo IMGFOLDER_ID;
}

define("DEF_IMG_FOLDER" , $imgfolder_url); 
define("RATE_MAX", 600); // 拡大で何パーセントまで許可するか
define("I_HEIGHT_MAX",2000); // 高さ最大値制約
define("I_WIDTH_MAX", 2000);// 幅最大値制約
//////////////////////////////////////////////////////////////////////
//
//                インライン呼び出し関数
//
//
function plugin_imgfolder_inline()
{
	$argc = func_num_args();	// 引数の数
	$args = func_get_args();	// 引数の中身

	// インラインの場合 引数の数が+1になるため1個減らす
   	array_pop($args);	
	$argc -=1; 


	// 2番目の引数が ([0-9}+?)% -> %指定
	// 2番目と3番目の引数が [0-9] -> 幅＋高さ指定
	// 引数の個数分け
	if ($argc < 1) {
		return FALSE;
	}else if (  ($argc >= 1 || $argc <= 2) && (! preg_match("/^([0-9]+)(\%)?$/i",$args[1],$matches )) ) {// 引数1個～2個 かつ2個目が数値or%でない
		// @ 第一引数の受け取り
		$src = $args[0];
		// タグを除去
		$src = strip_tags($src);
		// パスを結合
		$urlImg= DEF_IMG_FOLDER . htmlspecialchars($src, ENT_QUOTES);
		
		// ファイル存在チェック
		// if( ! IMGFOLDER_LOCAL) {
			// if( !file_exists($urlImg) ) {
				// return FALSE;
			// }
		// }
		//echo $urlImg;
		
		// 画像のサイズを取得、属性の自動追加
		$size = @getimagesize($urlImg);
		// 
		if (is_array($size)) {
			$width  = $size[0];
			$height = $size[1];
			$info   = $size[3];
		}
		// alt指定付きならば
		if ( $args[1] != ''  ){
			$alt = strip_tags($args[1]);
		}else{
			$alt = $src;
		}
		// imgタグ生成
		return "<img class=\"imgfolder\" src=\"$urlImg\" width=\"$width\" height=\"$height\" alt=\"$alt\" />";

	}else if( $argc >= 2 && preg_match("/^([0-9]+)\%$/i",$args[1],$matches ) ){ // 引数2個以上かつ2番目[0-9]+?%
		// => 拡大縮小 % 指定
		// 数値チェック
		$src = $args[0];
		// タグを除去
		$src = strip_tags($src);
		$rate = $matches[1];
		// 10%-RATE_MAX%まで許可
		if( ! ($rate > 10 && $rate < RATE_MAX) ){
			return FALSE;
		}
		$urlImg =  DEF_IMG_FOLDER . htmlspecialchars($src, ENT_QUOTES);
		
		// ファイル存在チェック
		// if( ! IMGFOLDER_LOCAL) {
			// if( !file_exists($urlImg) ) {
				// return FALSE;
			// }
		// }
		
		// サイズ情報の生成
		$size = @getimagesize($urlImg);
		if (is_array($size)) {
			$width  = $size[0];
			$height = $size[1];
			$info   = $size[3];
		}
		$width =round( $width * ($rate / 100));
		$height =round( $height * ($rate / 100));
		// alt指定付きならば
		if ( $args[2] != ''  ){
			$alt = strip_tags($args[2]);
		}else{
			$alt = $src;
		}
		// imgタグ生成
		return "<a href=\"".DEF_IMG_FOLDER . $src . "\" target=\"_blank\"><img class=\"imgfolder\" src=\"$urlImg\" width=\"$width\" height=\"$height\" alt=\"$alt\" /></a>";
		
	}else if ($argc >= 3 && preg_match("/^([0-9]+)$/i",$args[1],$matches ) && preg_match("/^([0-9]+)$/i",$args[2],$matches )){ 
		// 引数3個以上かつ [0-9]+? , [0-9]+? 
		// =>幅 高さ指定モード
		// 幅
		$width = $args[1];
		// 1～2000まで許可
		if( ! ($width > 1 && $width <= I_WIDTH_MAX) ){
			return FALSE;
		}
		// 高さ
		// 数値チェック
		// 幅
		$height = $args[2];
		// 1～2000まで許可
		if( ! ($height > 1 && $height <= I_HEIGHT_MAX) ){
			return FALSE;
		}
		$src = $args[0];
		// タグを除去
		$src = strip_tags($src);
		$urlImg = DEF_IMG_FOLDER . htmlspecialchars($src, ENT_QUOTES);
		
		// ファイル存在チェック
		// if( ! IMGFOLDER_LOCAL) {
			// if( !file_exists($urlImg) ) {
				// return FALSE;
			// }
		// }
		
		// alt指定付きならば
		if ( $args[3] != ''  ){
			$alt = strip_tags($args[3]);
		}else{
			$alt = $src;
		}
		// imgタグ生成
		return "<img class=\"imgfolder\" src=\"$urlImg\" width=\"$width\" height=\"$height\" alt=\"$alt\" />";
	}
}
function plugin_imgfolder_convert()
{
	$argc = func_num_args();	// 引数の数
	$args = func_get_args();	// 引数の中身

	// インラインの場合 引数の数が+1になるため1個減らす
   	array_pop($args);	
	$argc -=1; 


	// 2番目の引数が ([0-9}+?)% -> %指定
	// 2番目と3番目の引数が [0-9] -> 幅＋高さ指定
	// 引数の個数分け
	if ($argc < 1) {
		return FALSE;
	}else if (  ($argc >= 1 || $argc <= 2) && (! preg_match("/^([0-9]+)(\%)?$/i",$args[1],$matches )) ) {// 引数1個～2個 かつ2個目が数値or%でない
		// @ 第一引数の受け取り
		$src = $args[0];
		// タグを除去
		$src = strip_tags($src);
		// パスを結合
		$urlImg= DEF_IMG_FOLDER . htmlspecialchars($src, ENT_QUOTES);
		
		// ファイル存在チェック
		// if( ! IMGFOLDER_LOCAL) {
			// if( !file_exists($urlImg) ) {
				// return FALSE;
			// }
		// }
		
		// 画像のサイズを取得、属性の自動追加
		$size = @getimagesize($urlImg);
		// 
		if (is_array($size)) {
			$width  = $size[0];
			$height = $size[1];
			$info   = $size[3];
		}
		// alt指定付きならば
		if ( $args[1] != ''  ){
			$alt = strip_tags($args[1]);
		}else{
			$alt = $src;
		}
		// imgタグ生成
		return "<img class=\"imgfolder\" src=\"$urlImg\" width=\"$width\" height=\"$height\" alt=\"$alt\" />";

	}else if( $argc >= 2 && preg_match("/^([0-9]+)\%$/i",$args[1],$matches ) ){ // 引数2個以上かつ2番目[0-9]+?%
		// => 拡大縮小 % 指定
		// 数値チェック
		$src = $args[0];
		// タグを除去
		$src = strip_tags($src);
		$rate = $matches[1];
		// 10%-RATE_MAX%まで許可
		if( ! ($rate > 10 && $rate < RATE_MAX) ){
			return FALSE;
		}
		$urlImg =  DEF_IMG_FOLDER . htmlspecialchars($src, ENT_QUOTES);
		
		// ファイル存在チェック
		// if( ! IMGFOLDER_LOCAL) {
			// if( !file_exists($urlImg) ) {
				// return FALSE;
			// }
		// }
		
		// サイズ情報の生成
		$size = @getimagesize($urlImg);
		if (is_array($size)) {
			$width  = $size[0];
			$height = $size[1];
			$info   = $size[3];
		}
		$width =round( $width * ($rate / 100));
		$height =round( $height * ($rate / 100));
		// alt指定付きならば
		if ( $args[2] != ''  ){
			$alt = strip_tags($args[2]);
		}else{
			$alt = $src;
		}
		// imgタグ生成
		return "<a href=\"".DEF_IMG_FOLDER . $src . "\" target=\"_blank\"><img class=\"imgfolder\" src=\"$urlImg\" width=\"$width\" height=\"$height\" alt=\"$alt\" /></a>";
		
	}else if ($argc >= 3 && preg_match("/^([0-9]+)$/i",$args[1],$matches ) && preg_match("/^([0-9]+)$/i",$args[2],$matches )){ 
		// 引数3個以上かつ [0-9]+? , [0-9]+? 
		// =>幅 高さ指定モード
		// 幅
		$width = $args[1];
		// 1～2000まで許可
		if( ! ($width > 1 && $width <= I_WIDTH_MAX) ){
			return FALSE;
		}
		// 高さ
		// 数値チェック
		// 幅
		$height = $args[2];
		// 1～2000まで許可
		if( ! ($height > 1 && $height <= I_HEIGHT_MAX) ){
			return FALSE;
		}
		$src = $args[0];
		// タグを除去
		$src = strip_tags($src);
		$urlImg = DEF_IMG_FOLDER . htmlspecialchars($src, ENT_QUOTES);
		
		// ファイル存在チェック
		// if( ! IMGFOLDER_LOCAL) {
			// if( !file_exists($urlImg) ) {
				// return FALSE;
			// }
		// }
		
		// alt指定付きならば
		if ( $args[3] != ''  ){
			$alt = strip_tags($args[3]);
		}else{
			$alt = $src;
		}
		// imgタグ生成
		return "<p><img class=\"imgfolder\" src=\"$urlImg\" width=\"$width\" height=\"$height\" alt=\"$alt\" /></p>";
	}
}
?>
