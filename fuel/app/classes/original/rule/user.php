<?php

class Original_Rule_User
{
	
	
	/**
	* ユーザーの存在チェック
	*
	* @param integer $profile_no ユーザーNo
	* @return boolean 
	*/
	public static function _validation_check_user_data($user_no) {
		
		// 確認
		$model_user = new Model_User();
		$result = $model_user->get_user_data($user_no, null);
		
		return ($result) ? true : false;
		
	}
	
	
	/**
	* プロフィールの存在チェック
	*
	* @param integer $profile_no プロフィールNo
	* @return boolean 
	*/
	public static function _validation_check_profile($profile_no) {
		
		// 確認
		$model_user = new Model_User();
		$result = $model_user->get_profile($profile_no);
		
		return ($result) ? true : false;
		
	}
	
	
	/**
	* プロフィールの作者チェック
	*
	* @param integer $profile_no プロフィールNo
	* @return boolean 
	*/
	public static function _validation_check_profile_author($profile_no) {
		
		if (Fuel::$env == 'test')
		{
			$user_no = Input::post('login_user_no');
		}
		else
		{
			$user_no = (Auth::check()) ? Auth::get_user_id() : null;
		}
		
		// 確認
		$model_user = new Model_User();
		$result = $model_user->get_profile($profile_no);
		
		return ($user_no == $result['author_user_no']) ? true : false;
		
	}
	
	
	
	
	
	/**
	* ログインIDの重複チェック
	*
	* @param string $str ログインID
	* @return boolean 
	*/
	public static function _validation_login_username_duplication($str) {
		
		// 確認
		$model_user = new Model_User();
		$result = $model_user->get_login_id($str);
		
		return ($result) ? false : true;
		
	}
	
	
	/**
	* ユーザーIDの重複チェック
	*
	* @param string $str ログインID
	* @return boolean 
	*/
	public static function _validation_user_id_duplication($user_id) {
		
		$user_no = (Auth::check()) ? Auth::get_user_id() : null;
		
		// 確認
		$model_user = new Model_User();
		$result = $model_user->check_user_id_duplication($user_no, $user_id);
		
		return ($result) ? false : true;
		
	}
	
	
	
	
	/**
	* Eメールの重複チェック　ログイン
	*
	* @param string $str Eメール
	* @return boolean 
	*/
	public static function _validation_email_duplication_users_login($str) {
		
		// メールアドレスの暗号化
		$original_common_crypter = new Original\Common\Crypter();
		$encrypted_email = $original_common_crypter->encrypt($str);
		//var_dump($str, $encrypted_email);
		
		// すでに登録済みのメールかチェック
		$model_mail = new Model_Mail();
		$result = $model_mail->check_user_login_email($encrypted_email);
		//var_dump($result, $result_provisional);
		
		return ($result) ? false : true;
		
		//return true;
	}
	
	
	/**
	* Eメールの存在チェック　ログイン　上の結果を反転しただけ
	*
	* @param string $str Eメール
	* @return boolean 
	*/
	public static function _validation_email_existence_users_login($str) {
		
		// メールアドレスの暗号化
		$original_common_crypter = new Original\Common\Crypter();
		$encrypted_email = $original_common_crypter->encrypt($str);
		//var_dump($str, $encrypted_email);
		
		// すでに登録済みのメールかチェック
		$model_mail = new Model_Mail();
		$result = $model_mail->check_user_login_email($encrypted_email);
		//var_dump($result, $result_provisional);
		
		return ($result) ? true : false;
		
		//return true;
	}
	
	
	
	/**
	* Eメールの重複チェック　仮登録
	*
	* @param string $str Eメール
	* @return boolean 
	*/
	public static function _validation_email_duplication_provisional_mail($str) {
		
		// メールアドレスの暗号化
		$original_common_crypter = new Original\Common\Crypter();
		$encrypted_email = $original_common_crypter->encrypt($str);
		//var_dump($str, $encrypted_email);
		
		// すでに登録済みのメールかチェック
		$model_mail = new Model_Mail();
		$result = $model_mail->check_provisional_mail($encrypted_email);
		//var_dump($result, $result_provisional);
		
		return ($result) ? false : true;
		
		//return true;
	}

}