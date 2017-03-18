<?php

class Model_Main extends Model_Crud
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
	
	
	
	
	// --------------------------------------------------
	//   ユーザー　データ取得、チェック
	// --------------------------------------------------
	
	
	/**
	* ログインユーザーのTwitterアクセストークンを取得
	* @param integer $user_no ユーザーNo
	* @return array アクセストークン、アクセストークンシークレット
	*/
	public function get_twitter_access_token($user_no)
	{
		$query = DB::select('twitter_access_token', 'twitter_access_token_secret')->from('users_login');
		$query->where('id', '=', $user_no);
		$return_arr = $query->execute()->current();
		
		$original_common_crypter = new Original\Common\Crypter();
		
		if (isset($return_arr['twitter_access_token'], $return_arr['twitter_access_token_secret']))
		{
			$twitter_access_token = $original_common_crypter->decrypt($return_arr['twitter_access_token']);
			$twitter_access_token_secret = $original_common_crypter->decrypt($return_arr['twitter_access_token_secret']);
		}
		else
		{
			$twitter_access_token = null;
			$twitter_access_token_secret = null;
		}
		
		return array('access_token' => $twitter_access_token, 'access_token_secret' => $twitter_access_token_secret);
	}
	
	
	
	
	/**
	* User Dataを取得
	* @return array
	*/
	public function get_user_data($user_no, $user_id = null)
	{
		$query = DB::select('user_no', 'renewal_date', 'access_date', 'page_title', 'profile_title', 'handle_name', 'explanation', 'status', 'top_image', 'thumbnail', 'user_id', 'good', 'participation_community', 'level')->from('users_data');
		//$query = DB::select('*')->from('users_data');
		if ($user_no) $query->where('user_no', '=', $user_no);
		if ($user_id) $query->where('user_id', '=', $user_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();
		
		return $arr;
	}
	
	
	/**
	* User Dataを取得　personal_box用
	* @return array
	*/
	public function get_user_data_personal_box($user_no)
	{
		$query = DB::select('user_no', 'renewal_date', 'access_date', 'handle_name', 'status', 'thumbnail', 'user_id', 'good', 'level')->from('users_data');
		$query->where('user_no', '=', $user_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();
		
		return $arr;
	}
	
	
	/**
	* User Dataを取得　personal_box用　配列で検索
	* @return array
	*/
	public function get_user_data_list_in_personal_box($arr)
	{
		$query = DB::select('user_no', 'renewal_date', 'access_date', 'handle_name', 'status', 'thumbnail', 'user_id', 'good', 'level')->from('users_data');
		$query->where('user_no', 'in', $arr);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->as_array('user_no');
		
		return $arr;
	}
	
	
	
	/**
	* User IDの重複をチェックする
	* @return array
	*/
	public function check_user_id_duplication($user_no, $user_id)
	{
		$query = DB::select('user_no')->from('users_data');
		if ($user_no) $query->where('user_no', '!=', $user_no);
		if ($user_id) $query->where('user_id', '=', $user_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();
		
		return $arr;
	}
	
	
	
	/**
	* User Loginを取得
	* @return array
	*/
	public function get_user_login($user_no)
	{
		$query = DB::select('username', 'password', 'email')->from('users_login');
		$query->where('id', '=', $user_no);
		$arr = $query->execute()->current();
		
		return $arr;
	}
	
	
	/**
	* ログインIDを取得（ログインユーザーネームで検索、_validation_login_username_duplicationで使用）
	* @return array
	*/
	public function get_login_id($username)
	{
		$query = DB::select('id')->from('users_login');
		$query->where('username', '=', $username);
		$arr = $query->execute()->current();
		
		return $arr;
	}
	
	
	/**
	* プロフィール一覧を取得（User Noで検索）
	* @return array
	*/
	public function get_profile_list($user_no, $page, $limit)
	{
		$offset = $limit * ($page - 1);
		
		$query = DB::select('profile.*', 'users_data.user_id')->from('profile');
		$query->join('users_data', 'LEFT');
		$query->on('profile.author_user_no', '=', 'users_data.user_no');
		
		// 作者じゃない場合、公開プロフィールのみ表示
		if ($this->user_no != $user_no)
		{
			$query->where('profile.open_profile', '=', 1);
		}
		//var_dump($this->user_no);
		
		$query->where('profile.on_off', '=', 1);
		$query->where('profile.author_user_no', '=', $user_no);
		$query->where('users_data.on_off', '=', 1);
		$query->order_by('profile.renewal_date', 'DESC');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();
		
		return $arr;
	}
	
	/**
	* プロフィール一覧の総数を取得（User Noで検索）
	* @return array
	*/
	public function get_profile_list_total($user_no)
	{
		$query = DB::select(DB::expr('COUNT(*) as total'))->from('profile');
		$query->join('users_data', 'LEFT');
		$query->on('profile.author_user_no', '=', 'users_data.user_no');
		
		// 作者じゃない場合、公開プロフィールのみ
		if ($this->user_no != $user_no)
		{
			$query->where('profile.open_profile', '=', 1);
		}
		
		$query->where('profile.on_off', '=', 1);
		$query->where('profile.author_user_no', '=', $user_no);
		$query->where('users_data.on_off', '=', 1);
		$arr = $query->execute()->current();
		
		return $arr['total'];
	}
	
	
	/**
	* Profileのデータをすべて取得　Profile Noで検索
	* @return array
	*/
	public function get_profile($profile_no)
	{
		$query = DB::select('profile.*', 'users_data.user_id')->from('profile');
		$query->join('users_data', 'LEFT');
		$query->on('profile.author_user_no', '=', 'users_data.user_no');
		$query->where('profile.on_off', '=', 1);
		$query->where('profile.profile_no', '=', $profile_no);
		$query->where('users_data.on_off', '=', 1);
		$arr = $query->execute()->current();
		
		return $arr;
	}
	
	
	/**
	* Profileのデータをすべて取得　Profile Noの配列で検索
	* @return array
	*/
	public function get_profile_list_in_personal_box($arr)
	{
		$query = DB::select('profile.profile_no','profile.renewal_date', 'profile.access_date', 'profile.author_user_no', 'profile.handle_name', 'profile.status', 'profile.thumbnail', 'profile.open_profile', 'profile.good', 'profile.level',  'users_data.user_id')->from('profile');
		$query->join('users_data', 'LEFT');
		$query->on('profile.author_user_no', '=', 'users_data.user_no');
		$query->where('profile.on_off', '=', 1);
		$query->where('profile.profile_no', 'in', $arr);
		$query->where('users_data.on_off', '=', 1);
		$arr = $query->execute()->as_array('profile_no');
		
		return $arr;
	}

	
	
	/**
	* ゲームデータを取得（Game Noで検索、配列）
	* @return array
	*/
	public function get_game_name($language, $game_no_arr)
	{
		$query = DB::select('game_no', array('name_' . $language, 'name'))->from('game_data');
		$query->where('game_no', 'in', $game_no_arr);
		$arr = $query->execute()->as_array('game_no');
		
		return $arr;
	}
	
	
	/**
	* ゲームデータを取得（キーワードで検索）
	* @return array
	*/
	public function search_game_name($language, $keyword)
	{
		$query = DB::select('game_no', array('name_' . $language, 'name'))->from('game_data');
		$query->where_open();
		$query->where('name_' . $language, 'like', '%' . $keyword . '%');
		$query->or_where('similarity_' . $language, 'like', '%' . $keyword . '%');
		$query->where_close();
		$query->limit(10);
		$query->offset(0);
		$arr = $query->execute()->as_array();
		
		return $arr;
	}
	
	
	
	
	
	// --------------------------------------------------
	//   ユーザー　データ更新
	// --------------------------------------------------
	
	
	/**
	* プロフィールを更新 User Data
	* @return array
	*/
	public function update_profile_user_data($user_no, $renewal_date, $profile_title, $handle_name, $explanation, $status, $thumbnail)
	{
		$query = DB::update('users_data');
		$query->set(array('renewal_date' => $renewal_date, 'profile_title' => $profile_title, 'handle_name' => $handle_name, 'explanation' => $explanation, 'status' => $status, 'thumbnail' => $thumbnail));
		$query->where('user_no', '=', $user_no);
		$arr = $query->execute();
		
		return $arr;
	}
	
	/**
	* プロフィールを更新 Profile
	* @return array
	*/
	public function update_profile_profile($profile_no, $renewal_date, $profile_title, $handle_name, $explanation, $status, $thumbnail, $open_profile, $game_list)
	{
		$query = DB::update('profile');
		$query->set(array('renewal_date' => $renewal_date, 'profile_title' => $profile_title, 'handle_name' => $handle_name, 'explanation' => $explanation, 'status' => $status, 'thumbnail' => $thumbnail, 'open_profile' => $open_profile, 'game_list' => $game_list));
		$query->where('profile_no', '=', $profile_no);
		$arr = $query->execute();
		
		return $arr;
	}
	
	/**
	* プロフィールを挿入 Profile
	* @return array
	*/
	public function insert_profile($datetime_now, $profile_title, $handle_name, $explanation, $status, $thumbnail, $open_profile, $game_list)
	{
		
		// 30分前の日時を取得
		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");
		
		
		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------
		
		$query = DB::select('profile_no')->from('profile');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('author_user_no', '=', $this->user_no);
		$query->where('profile_title', '=', $profile_title);
		$query->where('handle_name', '=', $handle_name);
		$query->where('explanation', '=', $explanation);
		$query->where('status', '=', $status);
		$query->where('thumbnail', '=', $thumbnail);
		$query->where('open_profile', '=', $open_profile);
		$query->where('game_list', '=', $game_list);
		$arr = $query->execute()->current();
		
		if (empty($arr))
		{
			
			// --------------------------------------------------
			//   Offになっているプロフィールを検索
			// --------------------------------------------------
			
			$query = DB::select('profile_no')->from('profile');
			$query->where('on_off', '=', 0);
			$query->where('author_user_no', '=', $this->user_no);
			$off_profile_arr = $query->execute()->current();
			$off_profile_no = $off_profile_arr['profile_no'];
			
			if ($off_profile_no)
			{
				
				// ----- Offになっているプロフィールがある場合、更新 -----
				
				$query = DB::update('profile');
				$query->set(array('on_off' => 1, 'regi_date' => $datetime_now, 'renewal_date' => $datetime_now, 'access_date' => $datetime_now, 'profile_title' => $profile_title, 'handle_name' => $handle_name, 'explanation' => $explanation, 'status' => $status, 'thumbnail' => $thumbnail, 'open_profile' => $open_profile, 'game_list' => $game_list));
				$query->where('profile_no', '=', $off_profile_no);
				$arr = $query->execute();
				
				return $off_profile_no;
				
			}
			else
			{
				
				// ----- 挿入 -----
				
				$query = DB::insert('profile');
				$query->set(array('regi_date' => $datetime_now, 'renewal_date' => $datetime_now, 'access_date' => $datetime_now, 'author_user_no' => $this->user_no, 'profile_title' => $profile_title, 'handle_name' => $handle_name, 'explanation' => $explanation, 'status' => $status, 'thumbnail' => $thumbnail, 'open_profile' => $open_profile, 'game_list' => $game_list));
				$arr = $query->execute();
				
				return $arr[0];
				
			}
		}
		else
		{
			return false;
		}
		
	}
	
	
	/**
	* プロフィールを削除 Profile
	* @return array
	*/
	public function delete_profile($profile_no, $renewal_date)
	{
		$query = DB::update('profile');
		$query->set(array('on_off' => 0, 'renewal_date' => $renewal_date, 'profile_title' => '', 'handle_name' => '', 'explanation' => null, 'status' => null, 'thumbnail' => null, 'open_profile' => null, 'game_list' => null, 'good' => 0, 'level' => 0));
		$query->where('profile_no', '=', $profile_no);
		$arr = $query->execute();
		
		return $arr;
	}
	
	
	
	/**
	* Userページ　ページ設定保存
	* @return array
	*/
	public function update_user_data_config_page($renewal_date, $page_title, $top_image, $user_id)
	{
		$query = DB::update('users_data');
		$query->set(array('renewal_date' => $renewal_date, 'page_title' => $page_title, 'top_image' => $top_image, 'user_id' => $user_id));
		$query->where('user_no', '=', $this->user_no);
		$arr = $query->execute();
		
		return $arr;
	}
	
	
	
	
	
	
	// --------------------------------------------------
	//   CoD Ghosts
	// --------------------------------------------------
	
	
	/**
	* User Dataを更新 CoD Ghostsで使用
	* @return array
	*/
	public function update_user_data($user_no, $handle_name, $explanation)
	{
		$query = DB::update('users_data');
		$query->set(array('handle_name' => $handle_name, 'explanation' => $explanation));
		$query->where('user_no', '=', $user_no);
		$arr = $query->execute();
		
		return $arr;
	}
	
}