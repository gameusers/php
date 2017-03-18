<?php

namespace Original\Common;

class Mail
{
	
	/**
	 * クラスの初期化
	 */
	public static function _init()
	{
		//パッケージの読み込み
		\Package::load('email');
	}
	
	
	/**
	* TOでひとりにメール送信
	*
	* @param string $from 自分のメールアドレス
	* @param string $from_name 自分の名前
	* @param string $to 相手のメールアドレス
	* @param string $to_name 相手の名前
	* @param string $subject タイトル
	* @param string $body 本文
	* @return string 成功した場合はtrue、失敗した場合はfalse
	*/
	public function to($from, $from_name, $to, $to_name, $subject, $body) {
		
		// $email = Email::forge('jis');
		// $email->from($from, mb_encode_mimeheader($from_name, 'jis'));
		// $email->to($to, mb_encode_mimeheader($to_name, 'jis'));
		// $email->subject(mb_encode_mimeheader($subject, 'jis'));
		// $email->body(mb_convert_encoding($body, 'jis'));
		
		$email = \Email::forge();
		$email->from($from, $from_name);
		$email->to($to, $to_name);
		$email->subject($subject);
		$email->body($body);
		
		//送信の試行
		try
		{
			$result = true;
			$email->send();
		}
		catch (\EmailValidationFailedException $e)
		{
			//送信先が正しいEmailアドレスでない場合
			$result = false;
		}
		catch (\EmailSendingFailedException $e)
		{
			//送信に失敗した場合
			$result = false;
		}
		
		return $result;
		
	}
	
	
	/**
	* BCCを利用して複数人にメール送信
	*
	* @param string $from 自分のメールアドレス
	* @param string $from_name 自分の名前
	* @param array $bcc 相手のメールアドレスを配列で
	* @param string $subject タイトル
	* @param string $body 本文
	* @return string 成功した場合はtrue、失敗した場合はfalse
	*/
	public function bcc($from, $from_name, $bcc_arr, $subject, $body) {
		
		// $email = Email::forge('jis');
		// $email->from($from, mb_encode_mimeheader($from_name, 'jis'));
		// $bcc_arr = array('private-leaf@k.vodafone.ne.jp', 'info@reaf.net');
		// $email->to($bcc_arr);
		// $email->subject(mb_encode_mimeheader($subject, 'jis'));
		// $email->body(mb_convert_encoding($body, 'jis'));
		
		$email = \Email::forge();
		$email->from($from, $from_name);
		$email->bcc($bcc_arr);
		$email->subject($subject);
		$email->body($body);
		
		//送信の試行
		try
		{
			$result = true;
			$email->send();
		}
		catch (\EmailValidationFailedException $e)
		{
			//送信先が正しいEmailアドレスでない場合
			$result = false;
		}
		catch (\EmailSendingFailedException $e)
		{
			//送信に失敗した場合
			$result = false;
		}
		
		return $result;
		
	}

}