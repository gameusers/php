<?php

namespace Original\Common;

class Text
{
	
	/**
	* ランダムテキストを生成
	*
	* @param integer $length ランダムテキストの長さ
	* @return array
	*/
	public function random_text($length) {
		
		//使用する文字
		$char = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		$charlen = mb_strlen($char);
		$result = "";
		
		for($i=1;$i<=$length;$i++){
			$index = mt_rand(0, $charlen - 1);
			$result .= mb_substr($char, $index, 1);
		}
		
		return $result;
	}
	
	
	/**
	* ランダムテキストを生成　小文字のみ
	*
	* @param integer $length ランダムテキストの長さ
	* @return array
	*/
	public function random_text_lowercase($length) {
		
		//使用する文字
		$char = '1234567890abcdefghijklmnopqrstuvwxyz';
		
		$charlen = mb_strlen($char);
		$result = "";
		
		for($i=1;$i<=$length;$i++){
			$index = mt_rand(0, $charlen - 1);
			$result .= mb_substr($char, $index, 1);
		}
		
		return $result;
	}
	
	
	/**
	 * パスワードの強度を返す
	 *
	 * @param string $password
	 * @return int 
	 */
	function check_password_strength($password)
	{
		if ( strlen( $password ) == 0 )
		{
			return 1;
		}
		
		// 数字だけの場合 強度低
		if (preg_match("/^[0-9]+$/", $password))
		{
			return 1;
		}
		
		$strength = 0;
		
		/*** get the length of the password ***/
		$length = strlen($password);
		
		/*** check if password is not all lower case ***/
		if(strtolower($password) != $password)
		{
			$strength += 1;
		}
		
		/*** check if password is not all upper case ***/
		if(strtoupper($password) == $password)
		{
			$strength += 1;
		}
		
		/*** check string length is 8 -15 chars ***/
		if($length >= 8 && $length <= 15)
		{
			$strength += 1;
		}
		
		/*** check if lenth is 16 - 35 chars ***/
		if($length >= 16 && $length <=35)
		{
			$strength += 2;
		}
		
		/*** check if length greater than 35 chars ***/
		if($length > 35)
		{
			$strength += 3;
		}
		
		/*** get the numbers in the password ***/
		preg_match_all('/[0-9]/', $password, $numbers);
		$strength += count($numbers[0]);
		
		/*** check for special chars ***/
		preg_match_all('/[|!@#$%&*\/=?,;.:\-_+~^\\\]/', $password, $specialchars);
		$strength += sizeof($specialchars[0]);
		
		/*** get the number of unique chars ***/
		$chars = str_split($password);
		$num_unique_chars = sizeof( array_unique($chars) );
		$strength += $num_unique_chars * 2;
		
		/*** strength is a number 1-10; ***/
		$strength = $strength > 99 ? 99 : $strength;
		$strength = floor($strength / 10 + 1);
		
		// passwordという文字は強度0にする
		if($password === 'password')
		{
			$strength = 0;
		}
		
		return $strength;
	}
	
}