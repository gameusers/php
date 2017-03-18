<?php

class Original_Rule_Basic
{
	
	/**
	* ログインしていない場合はURLが入っているとエラー
	*
	* @param string $title 説明文
	* @return boolean 
	*/
	public static function _validation_check_url($str) {
		
		if (Fuel::$env == 'test')
		{
			$user_no = Input::post('login_user_no');
		}
		else
		{
			$user_no = (Auth::check()) ? Auth::get_user_id() : null;
		}
		
		if ( ! $user_no)
		{
			
			$pat_sub = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/'); // 正規表現向けのエスケープ処理
			$pat  = '/((http|https):\/\/[0-9a-z' . $pat_sub . ']+)/i'; // 正規表現パターン
			
			// URLを含んでいる場合はエラー
			if (preg_match($pat, $str))
			{
				return false;
			}
			else
			{
				return true;
			}
			
		}
		
		return true;
		
	}
	
	
	
	/**
	* カンマ区切りのデータ　整数の数値のみ
	*
	* @param string $title 説明文
	* @return boolean 
	*/
	public static function _validation_check_csv_int($str) {
		
		// 配列化
		$arr = explode(',', $str);
		
		$check = true;
		$pattern = '/^[1-9]\d*$/';
		
		foreach ($arr as $key => $value) {
			if ( ! preg_match($pattern, $value)) $check = false;
		}
		
		return $check;
		
	}
	
}