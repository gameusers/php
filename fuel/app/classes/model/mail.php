<?php

class Model_Mail extends Model_Crud
{
	
	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------
	
	// PC・スマホ・タブレット
	public $agent_type = null;
	
	// ホスト
	public $host = null;
	
	// ユーザーエージェント
	public $user_agent = null;
	
	// ユーザーNo
	public $user_no = null;
	
	// 言語
	public $language = null;
	
	// URI
	public $uri_base = null;
	public $uri_current = null;
	
	
	
	
	/**
	* メールアドレス存在確認
	* @param string $encrypted_email 暗号化されたEメール
	* @return array
	*/
	public function check_user_login_email($encrypted_email)
	{
		$query = DB::select('id')->from('users_login');
		$query->where('email', '=', $encrypted_email);
		$arr = $query->execute()->current();
		
		return $arr;
	}
	
	
	/**
	* 仮登録メール存在確認用
	* @param string $encrypted_email 暗号化されたEメール
	* @return array
	*/
	public function check_provisional_mail($encrypted_email)
	{
		
		if (Fuel::$env == 'test')
		{
			$user_no = Input::post('login_user_no');
		}
		else
		{
			$user_no = (Auth::check()) ? Auth::get_user_id() : null;
		}
		
		// 1日前の日時を取得
		$original_common_date = new Original\Common\Date();
		$pre_datetime = $original_common_date->sql_format('-1 day');
		
		$query = DB::select('hash')->from('provisional_mail');
		$query->where('regi_date', '>', $pre_datetime);
		$query->where('user_no', '=', $user_no);
		$arr = $query->execute()->current();
		
		return $arr;
	}
	
	
	/**
	* メールアドレス仮登録
	* @param integer $user_no ユーザーNo
	* @param string $email Eメールアドレス
	* @return array
	*/
	public function save_provisional_mail($user_no, $email)
	{
		
		// --------------------------------------------------
		//   メールアドレスの暗号化
		// --------------------------------------------------
		
		$original_common_crypter = new Original\Common\Crypter();
		$encrypted_email = $original_common_crypter->encrypt($email);
		
		
		// --------------------------------------------------
		//   日時を取得
		// --------------------------------------------------
		
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		
		// --------------------------------------------------
		//   1日前の日時を取得
		// --------------------------------------------------
		
		$original_common_date = new Original\Common\Date();
		$pre_datetime = $original_common_date->sql_format('-1 day');
		
		
		// --------------------------------------------------
		//   90日前の日時を取得
		// --------------------------------------------------
		
		$original_common_date = new Original\Common\Date();
		$delete_pre_datetime = $original_common_date->sql_format('-90 day');
		
		
		// --------------------------------------------------
		//   ハッシュ作成
		// --------------------------------------------------
		
		$hash = md5(uniqid(rand(), true));
		
		
		// --------------------------------------------------
		//   ホスト ＆ ユーザーエージェント取得
		// --------------------------------------------------
		
		$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		
		// --------------------------------------------------
		//   古い未登録データをまとめて削除
		// --------------------------------------------------
		
		$query = DB::delete('provisional_mail');
		$query->where('regi_date', '<', $delete_pre_datetime);
		$query->execute();
		
		
		// --------------------------------------------------
		//   仮登録
		// --------------------------------------------------
		
		$query = DB::insert('provisional_mail');
		$query->set(array('regi_date' => $datetime_now, 'user_no' => $user_no, 'email' => $encrypted_email, 'hash' => $hash, 'host' => $host, 'user_agent' => $user_agent));
		$query->execute();
		
		return $hash;
		
	}
	
	
	/**
	* メールアドレス仮登録メールハッシュ存在確認　本登録前
	* @param integer user_no ユーザーNo
	* @return array
	*/
	public function check_provisional_hash($hash)
	{
		// 1日前の日時を取得
		$original_common_date = new Original\Common\Date();
		$pre_datetime = $original_common_date->sql_format('-1 day');
		
		$query = DB::select('user_no')->from('provisional_mail');
		$query->where('regi_date', '>', $pre_datetime);
		$query->where('hash', '=', $hash);
		$arr = $query->execute()->current();
		
		return $arr['user_no'];
	}
	
	
	/**
	* メールアドレス本登録
	* @param string $hash ハッシュ
	* @return array
	*/
	public function save_mail($hash)
	{
		
		// --------------------------------------------------
		//   ユーザーNoとEメールを取得
		// --------------------------------------------------
		
		$query = DB::select('user_no', 'email')->from('provisional_mail');
		$query->where('hash', '=', $hash);
		$result_arr = $query->execute()->current();
		
		
		// --------------------------------------------------
		//   本登録　Eメール保存
		// --------------------------------------------------
		
		$query = DB::update('users_login');
		$query->set(array('email' => $result_arr['email']));
		$query->where('id', '=', $result_arr['user_no']);
		$query->execute();
		
		
		// --------------------------------------------------
		//   仮登録情報削除
		// --------------------------------------------------
		
		$query = DB::delete('provisional_mail');
		$query->where('hash', '=', $hash);
		$query->execute();
		
	}
	
	
	/**
	* メールアドレス削除
	* @param integer $user_no ユーザーNo
	* @return array
	*/
	public function delete_mail($user_no)
	{
		
		// --------------------------------------------------
		//   Eメール削除
		// --------------------------------------------------
		
		$query = DB::update('users_login');
		$query->set(array('email' => null));
		$query->where('id', '=', $user_no);
		$query->execute();
		
	}
	
	
	/**
	* メールアドレス削除　メールアドレスで検索
	* @param integer $email メールアドレス
	* @return array
	*/
	public function delete_mail_search_address($email)
	{
		
		// --------------------------------------------------
		//   メールアドレスの暗号化
		// --------------------------------------------------
		
		$original_common_crypter = new Original\Common\Crypter();
		$encrypted_email = $original_common_crypter->encrypt($email);
		
		
		// --------------------------------------------------
		//   Eメール削除
		// --------------------------------------------------
		
		$query = DB::update('users_login');
		$query->set(array('email' => null));
		$query->where('email', '=', $encrypted_email);
		$query->execute();
		
	}
	
}