<?php

class Model_User extends Model_Crud
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
		$query = DB::select('user_no', 'on_off', 'renewal_date', 'access_date', 'page_title', 'profile_title', 'handle_name', 'explanation', 'status', 'top_image', 'thumbnail', 'user_id', 'good', 'participation_community', 'participation_community_secret', 'level', 'notification_data', 'notifications_reservation_id', 'notifications_already_read_id', 'user_terms', 'user_advertisement')->from('users_data');
		//$query = DB::select('*')->from('users_data');
		if ($user_no) $query->where('user_no', '=', $user_no);
		if ($user_id) $query->where('user_id', '=', $user_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}


	/**
	* User IDを取得　キャッシュ　3時間有効
	* @return array
	*/
	public function get_user_id_cach($user_no)
	{
		$query = DB::select('user_id')->from('users_data');
		$query->where('user_no', '=', $user_no);
		$query->where('on_off', '=', 1);
		$query->cached(10800, 'users_data.user_id.' . $user_no, false);
		$arr = $query->execute()->current();

		return $arr;
	}



	/**
	* User Dataを取得　personal_box用
	* @return array
	*/
	public function get_user_data_personal_box($user_no)
	{
		$query = DB::select('user_no', 'on_off', 'renewal_date', 'access_date', 'handle_name', 'status', 'thumbnail', 'user_id', 'good', 'level')->from('users_data');
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
		$query = DB::select('user_no', 'on_off', 'renewal_date', 'access_date', 'handle_name', 'status', 'thumbnail', 'user_id', 'good', 'level', 'notification_on_off', 'notification_data')->from('users_data');
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
	* Twitter IDの重複をチェックする　作ってみたけど使わないことになった　simpleauth.phpのupdate_userに同じ機能が含まれている
	* @return array
	*/
	/*
	public function check_twitter_id_duplication($twitter_id_number)
	{
		// twitterのIDは番号を使用している　例）tester_mailの場合、231338525　これをハッシュ化してデータベースに記録

		$auth = Auth::instance();
		$twitter_id = $auth->hash_password($twitter_id_number);

		$query = DB::select('id')->from('users_login');
		$query->where('twitter_id', '=', $twitter_id);
		//$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();
		//var_dump($arr);

		return ($arr) ? true : false;
	}
	*/


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
		$query->order_by('profile.access_date', 'DESC');
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
	* プロフィール一覧を取得（User NoとGame Noで検索）
	* @return array
	*/
	public function get_profile_list_game_no($user_no, $game_no, $page, $limit)
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

		$query->where_open();
		$query->where('profile.game_list', 'like', '%,' . $game_no . ',%');
		$query->or_where('profile.game_list', '=', null);
		$query->where_close();

		$query->order_by('profile.access_date', 'DESC');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();
		//echo DB::last_query();

		$total = DB::count_last_query();

		return array($arr, $total);
	}





	/**
	* Profileのデータを取得　Profile Noで検索
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
	* Profileのデータを取得　personal_box用　Profile Noの配列で検索
	* @return array
	*/
	public function get_profile_list_in_personal_box($arr, $on = true)
	{
		$query = DB::select('profile.profile_no', 'profile.on_off', 'profile.renewal_date', 'profile.access_date', 'profile.author_user_no', 'profile.handle_name', 'profile.status', 'profile.thumbnail', 'profile.open_profile', 'profile.good', 'profile.level', 'users_data.user_id', 'users_data.notification_on_off', 'users_data.notification_data')->from('profile');
		$query->join('users_data', 'LEFT');
		$query->on('profile.author_user_no', '=', 'users_data.user_no');
		if ($on) $query->where('profile.on_off', '=', 1);
		$query->where('profile.profile_no', 'in', $arr);
		$query->where('users_data.on_off', '=', 1);
		$arr = $query->execute()->as_array('profile_no');

		return $arr;
	}




	/**
	* ログインユーザーのプロフィール取得
	*
	* @param integer $game_no ゲームNo
	* @return string
	*/
	public function get_login_user_data($game_no)
	{

		// --------------------------------------------------
		//    ログインユーザー情報
		// --------------------------------------------------

		if ($this->user_no)
		{

			$query = DB::select('*')->from('users_game_community');
			$query->where('user_no', '=', $this->user_no);
			$users_game_community_arr = $query->execute()->current();

			//$users_game_community_arr['config'] = array(1 => array('profile_no' => 1));

			//$users_game_community_arr['config'] = null;
			$config_arr = unserialize($users_game_community_arr['config']);
			//var_dump($users_game_community_arr, $config_arr);


			// --------------------------------------------------
			//   共通処理　インスタンス作成
			// --------------------------------------------------

			$model_user = new \Model_User();
			$model_user->agent_type = $this->agent_type;
			$model_user->user_no = $this->user_no;
			$model_user->language = $this->language;
			$model_user->uri_base = $this->uri_base;
			$model_user->uri_current = $this->uri_current;


			if (isset($config_arr[$game_no]['profile_no']))
			{
				$login_profile_data_arr = $model_user->get_profile($config_arr[$game_no]['profile_no']);
			}
			else
			{
				$login_profile_data_arr = $model_user->get_user_data_personal_box($this->user_no, null);
			}
			//$login_profile_data_arr = $model_user->get_user_data_personal_box($this->user_no, null);
			// echo '<br><br><br><br>';
			// var_dump($login_profile_data_arr);


			//exit();

		}
		else
		{
			$users_game_community_arr = null;
			$login_profile_data_arr = null;
		}


		return array($users_game_community_arr, $login_profile_data_arr);

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

			// ----- 挿入 -----

			$query = DB::insert('profile');
			$query->set(array('regi_date' => $datetime_now, 'renewal_date' => $datetime_now, 'access_date' => $datetime_now, 'author_user_no' => $this->user_no, 'profile_title' => $profile_title, 'handle_name' => $handle_name, 'explanation' => $explanation, 'status' => $status, 'thumbnail' => $thumbnail, 'open_profile' => $open_profile, 'game_list' => $game_list));
			$arr = $query->execute();

			return $arr[0];

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
		//$query->set(array('on_off' => null, 'renewal_date' => $renewal_date, 'profile_title' => '', 'handle_name' => '', 'explanation' => null, 'status' => null, 'thumbnail' => null, 'open_profile' => null, 'game_list' => null, 'good' => 0, 'level' => 0));
		$query->set(array('on_off' => null, 'renewal_date' => $renewal_date));
		$query->where('profile_no', '=', $profile_no);
		$arr = $query->execute();

		return $arr;
	}




	/**
	* User Data　更新
	* @return array
	*/
	public function update_user_data($user_no, $save_arr)
	{
		$query = DB::update('users_data');
		$query->set($save_arr);
		$query->where('user_no', '=', $user_no);
		$arr = $query->execute();

		// 参加コミュニティが勝手に削除される問題　解決後コメントアウトすること
		/*
		if ($user_no == 1)
		{
			$query = DB::select('participation_community')->from('users_data');
			$query->where('user_no', '=', 1);
			$participation_arr = $query->execute()->current();

			if ($participation_arr['participation_community'] == null)
			{
				$body = '';
				foreach ($save_arr as $key => $value) {
					$body .= $key . ' = ' . $value . "\n";
				}

				Log::error('参加コミュニティ勝手に削除エラー' . $body);

				$original_common_mail = new \Original\Common\Mail();
				$result = $original_common_mail->to('mail@gameusers.org', 'Game Users', 'private-leaf@k.vodafone.ne.jp', 'A', 'エラー報告', $body);
			}
		}
		*/
		return $arr;
	}




	/**
	* Access Dateを更新 User Data
	* @return array
	*/
	public function update_user_data_access_date($datetime, $user_no, $community_no, $member_arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   アクセス日時取得
			// --------------------------------------------------

			$query = DB::select('access_date')->from('users_data');
			$query->where('user_no', '=', $user_no);
			$arr = $query->execute()->current();
			$access_date_past = $arr['access_date'];
			//var_dump($access_date_past);


			// --------------------------------------------------
			//   日付比較
			// --------------------------------------------------

			$datetime_now = new DateTime($datetime);
			$datetime_past = new DateTime($access_date_past);
			$interval = $datetime_now->diff($datetime_past);
			$interval_days = $interval->format('%a');
			//var_dump($interval, $interval_days);


			// --------------------------------------------------
			//   User Data更新
			// --------------------------------------------------

			$query = DB::update('users_data');

			// 前のアクセスから1日以上経っている場合は、レベル+1
			if ($interval_days > 0)
			{
				$query->set(array('access_date' => $datetime, 'level' => DB::expr('level + 1')));
			}
			else
			{
				$query->set(array('access_date' => $datetime));
			}

			$query->where('user_no', '=', $user_no);
			$arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティ更新
			// --------------------------------------------------

			if (isset($community_no, $member_arr))
			{
				$query = DB::update('community');
				$query->set(array('member' => $member_arr));
				$query->where('community_no', '=', $community_no);
				$arr = $query->execute();
			}


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}


	/**
	* Access Dateを更新 Profile
	* @return array
	*/
	public function update_profile_access_date($datetime, $profile_no, $community_no, $member_arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   アクセス日時取得
			// --------------------------------------------------

			$query = DB::select('access_date')->from('profile');
			$query->where('profile_no', '=', $profile_no);
			$arr = $query->execute()->current();
			$access_date_past = $arr['access_date'];
			//var_dump($access_date_past);


			// --------------------------------------------------
			//   日付比較
			// --------------------------------------------------

			$datetime_now = new DateTime($datetime);
			$datetime_past = new DateTime($access_date_past);
			$interval = $datetime_now->diff($datetime_past);
			$interval_days = $interval->format('%a');
			//var_dump($interval, $interval_days);


			// --------------------------------------------------
			//   Profile更新
			// --------------------------------------------------

			$query = DB::update('profile');

			// 前のアクセスから1日以上経っている場合は、レベル+1
			if ($interval_days > 0)
			{
				$query->set(array('access_date' => $datetime, 'level' => DB::expr('level + 1')));
			}
			else
			{
				$query->set(array('access_date' => $datetime));
			}

			$query->where('profile_no', '=', $profile_no);
			$arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティ更新
			// --------------------------------------------------

			if (isset($community_no, $member_arr))
			{
				$query = DB::update('community');
				$query->set(array('member' => $member_arr));
				$query->where('community_no', '=', $community_no);
				$arr = $query->execute();
			}


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}





	// --------------------------------------------------
	//   コミュニティ　メンバー用
	// --------------------------------------------------


	/**
	* User Dataを取得　コミュニティ　メンバー用　配列で検索
	* @return array
	*/
	public function get_user_data_list_in_personal_box_member($arr)
	{
		$query = DB::select('user_no', 'on_off', 'renewal_date', 'access_date', 'handle_name', 'explanation', 'status', 'thumbnail', 'user_id', 'good', 'level')->from('users_data');
		$query->where('user_no', 'in', $arr);
		//$query->where('on_off', '=', 1);
		$arr = $query->execute()->as_array('user_no');

		return $arr;
	}


	/**
	* Profileを取得　コミュニティ　メンバー用　配列で検索
	* @return array
	*/
	public function get_profile_list_in_personal_box_member($arr)
	{
		$query = DB::select('profile.profile_no','profile.on_off', 'profile.renewal_date', 'profile.access_date', 'profile.author_user_no', 'profile.handle_name', 'profile.explanation', 'profile.status', 'profile.thumbnail', 'profile.open_profile', 'profile.good', 'profile.level', 'users_data.user_id')->from('profile');
		$query->join('users_data', 'LEFT');
		$query->on('profile.author_user_no', '=', 'users_data.user_no');
		$query->where('profile.profile_no', 'in', $arr);
		//$query->where('profile.on_off', '=', 1);
		$arr = $query->execute()->as_array('profile_no');

		return $arr;
	}






	/**
	* プレイヤーアカウントを削除
	* @return array
	*/
	public function delete_player_account($user_no)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   users_login　username & password 変更　Eメール・twitter auth削除
			// --------------------------------------------------

			$original_common_text = new \Original\Common\Text();
			$new_username = $original_common_text->random_text(25);
			$new_password = $original_common_text->random_text(32);

			$query = DB::update('users_login');
			$query->set(array('username' => $new_username, 'password' => $new_password, 'email' => null, 'twitter_id' => null, 'twitter_access_token' => null, 'twitter_access_token_secret' => null, 'auth_id1' => null, 'auth_id2' => null, 'auth_id3' => null));
			$query->where('id', '=', $user_no);
			$arr = $query->execute();


			// --------------------------------------------------
			//   users_data　ユーザーを削除
			// --------------------------------------------------

			$query = DB::update('users_data');
			//$query->set(array('on_off' => null));
			//$query->set(array('notification_on_off' => null));
			$query->set(array('on_off' => null, 'notification_data' => 'a:7:{s:6:"on_off";b:1;s:14:"on_off_browser";b:0;s:10:"on_off_app";b:0;s:11:"on_off_mail";b:1;s:12:"browser_info";N;s:14:"receive_device";N;s:11:"device_info";N;}'));
			$query->where('user_no', '=', $user_no);
			$arr = $query->execute();


			// --------------------------------------------------
			//   profile　プロフィールをすべて削除
			// --------------------------------------------------

			$query = DB::update('profile');
			$query->set(array('on_off' => null));
			$query->where('author_user_no', '=', $user_no);
			$arr = $query->execute();


			// --------------------------------------------------
			//   community　管理者になっているコミュニティをすべて削除
			// --------------------------------------------------

			$query = DB::update('community');
			$query->set(array('on_off' => null));
			$query->where('author_user_no', '=', $user_no);
			$arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}




	// --------------------------------------------------
	//   レベルアップ
	// --------------------------------------------------


	/**
	* まとめてレベルアップ　User Data　管理者用
	* @return array
	*/
	public function level_up_users($arr)
	{
		//$query = DB::query('UPDATE users_data SET `level`=`level`+1 WHERE `user_no`IN (1,2)');
		//$arr = $query->execute();

		$query = DB::update('users_data');
		$query->set(array('level' => DB::expr('level + 1')));
		$query->where('user_no', 'in', $arr);
		$arr = $query->execute();

		return $arr;
	}



}
