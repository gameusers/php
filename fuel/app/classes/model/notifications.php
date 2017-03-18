<?php

class Model_Notifications extends Model_Crud
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
	* 読み込み
	* @param string $arr['language'] 言語
	* @param boolean $arr['unread'] 未読通知を読み込む場合 true / 既読通知を読み込む場合 false
	* @param array $arr['already_read_id_arr'] 既読IDの配列
	* @param array $arr['participation_community_no_arr'] 参加ユーザーコミュニティNoの配列
	* @param integer $arr['page'] ページ
	* @param integer $arr['limit'] リミット
	* @return array
	*/
	public function read_notifications($arr)
	{

		$offset = $arr['limit'] * ($arr['page'] - 1);

		// ○○前の日時を取得　一定期間以上過ぎた古い通知は読み込まない
		$original_common_date = new Original\Common\Date();
		$pre_datetime = $original_common_date->sql_format(Config::get('limit_notification_time'));


		$query = DB::select(
			'notifications.*',
			array('users_data.handle_name', 'user_handle_name'),
			array('users_data.thumbnail', 'user_thumbnail'),
			array('profile.handle_name', 'profile_handle_name'),
			array('profile.thumbnail', 'profile_thumbnail'),
			//array('profile.open_profile', 'profile_open_profile'),
			array('community.name', 'community_name'),
			'community.community_id',
			'community.member',
			array('game_data.name_' . $arr['language'], 'game_name'),
			array('game_data.id', 'game_id')
		)->from('notifications');

		$query->join('users_data', 'LEFT');
		$query->on('notifications.user_no', '=', 'users_data.user_no');
		$query->join('profile', 'LEFT');
		$query->on('notifications.profile_no', '=', 'profile.profile_no');
		$query->join('community', 'LEFT');
		$query->on('notifications.community_no', '=', 'community.community_no');
		$query->join('game_data', 'LEFT');
		$query->on('notifications.game_no', '=', 'game_data.game_no');


		// 一定期間以上過ぎた古い通知は読み込まない
		$query->where('notifications.regi_date', '>', $pre_datetime);


		// User No　自分のNo以外（他人のアクションを通知）　または　null（ログインしていないユーザーのアクションを通知）
		$query->and_where_open();
		$query->and_where('notifications.user_no', '!=', $this->user_no);
		$query->or_where('notifications.user_no', '=', null);
		$query->and_where_close();


		// 自分への通知　参加してるコミュニティの通知＆通知を受けとる設定にしてるゲームコミュニティ
		$query->and_where_open();

		if (isset($arr['user_no'])) $query->and_where('notifications.target_user_no', '=', $arr['user_no']);

		if (count($arr['participation_community_no_arr']) > 0)
		{
			$query->or_where('notifications.community_no', 'in', $arr['participation_community_no_arr']);
		}

		if (isset($arr['notification_recruitment_arr']))
		{
			$query->or_where_open();
			$query->and_where('notifications.type1', '=', 'gc');
			$query->and_where('notifications.type2', '=', 'recruitment');
			$query->and_where('notifications.game_no', 'in', $arr['notification_recruitment_arr']);
			$query->or_where_close();
		}

		$query->and_where_close();


		// 既読・未読の切り替え
		if ($arr['already_read_id_arr'])
		{
			// 未読
			if ($arr['unread'])
			{
				$query->and_where('notifications.id', 'not in', $arr['already_read_id_arr']);
			}
			// 既読
			else
			{
				$query->and_where('notifications.id', 'in', $arr['already_read_id_arr']);
			}
		}

		$query->order_by('regi_date','desc');
		$query->limit($arr['limit']);
		$query->offset($offset);

		$result_arr['data'] = $query->execute()->as_array();
		//echo "<br><br><br><br>" . DB::last_query() . "<br>";
		//var_dump($this->user_no);
		$result_arr['total'] = DB::count_last_query();


		return $result_arr;

	}





	/**
	* 読み込み　通知・メール送信用
	* @return array
	*/
	public function read_notifications_for_send()
	{

		$language = 'ja';

		$query = DB::select(
			'notifications.*',
			array('users_data.handle_name', 'user_handle_name'),
			array('users_data.thumbnail', 'user_thumbnail'),
			array('profile.handle_name', 'profile_handle_name'),
			array('profile.thumbnail', 'profile_thumbnail'),
			array('community.name', 'community_name'),
			'community.community_id',
			'community.member',
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.id', 'game_id')
		)->from('notifications');

		$query->join('users_data', 'LEFT');
		$query->on('notifications.user_no', '=', 'users_data.user_no');
		$query->join('profile', 'LEFT');
		$query->on('notifications.profile_no', '=', 'profile.profile_no');
		$query->join('community', 'LEFT');
		$query->on('notifications.community_no', '=', 'community.community_no');
		$query->join('game_data', 'LEFT');
		$query->on('notifications.game_no', '=', 'game_data.game_no');

		$query->where_open();
		$query->where('notifications.send_status', '=', 'on');
		$query->or_where('notifications.send_status', '=', 'sending');
		$query->where_close();

		//if ($type === 'individual') $query->where('notifications.target_user_no', '!=', null);

		$query->order_by('notifications.regi_date','asc');
		//$query->order_by(DB::expr('RAND()'));

		// テスト用
		//$query->where('notifications.id', '=', 'eg8eer6y7mjkgag');
		//$query->where('notifications.id', '=', 'rxkx0wr80aova8m');
		//$query->where('notifications.id', '=', '6yqnif3bur682v6');

		//$limit = ($type === 'individual') ? 100 : 1;
		$query->limit(1);
		$query->offset(0);
		$data_arr = $query->execute()->current();


		//echo DB::last_query();


		return $data_arr;
	}



	/**
	* 通知の送信で、特定ユーザーの情報を取得（個別に通知を送信するときに利用）
	*
	* @param array $arr 検索条件
	* @return string
	*/
	public function get_notifications_target_users_list($arr)
	{
		$language = 'ja';

		$offset = $arr['limit'] * ($arr['page'] - 1);

		$query = DB::select(
			'notifications.*',
			//'users_data.user_no',
			array('users_data.on_off', 'users_data_on_off'),
			array('users_data.notification_on_off', 'users_data_notification_on_off'),
			//'users_data.on_off',
			//'users_data.notification_on_off',
			'users_data.notification_data',
			'users_login.email',
			'users_game_community.ng_user',
			'users_game_community.config',
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.id',  'game_id')
		)->from('notifications');

		$query->join('users_data', 'LEFT');
		$query->on('notifications.target_user_no', '=', 'users_data.user_no');
		$query->join('users_login', 'LEFT');
		$query->on('notifications.target_user_no', '=', 'users_login.id');
		$query->join('users_game_community', 'LEFT');
		$query->on('notifications.target_user_no', '=', 'users_game_community.user_no');
		$query->join('game_data', 'LEFT');
		$query->on('notifications.game_no', '=', 'game_data.game_no');

		$query->where_open();
		$query->where('notifications.send_status', '=', 'on');
		$query->or_where('notifications.send_status', '=', 'sending');
		$query->where_close();
		$query->order_by('notifications.regi_date','asc');

		$query->where('notifications.target_user_no', '!=', null);
		// $query->where('users_data.on_off', '=', 1);
		// $query->where('users_data.notification_on_off', '=', 1);

		$query->limit($arr['limit']);
		$query->offset($offset);

		$result_arr = $query->execute()->as_array();

		$total = DB::count_last_query();

		//echo DB::last_query();

		return array($result_arr, $total);
	}







	/**
	* 挿入
	* @param array $save_arr
	* @return array
	*/
	public function insert_notifications($save_arr)
	{
		$query = DB::insert('notifications');
		$query->set($save_arr);
		$arr = $query->execute();

		return $arr;
	}



	/**
	* 古いデータを削除する
	* @return array
	*/
	public function delete_notifications()
	{

		// --------------------------------------------------
		//   ○○前の日時を取得
		// --------------------------------------------------

		$original_common_date = new Original\Common\Date();
		$pre_datetime = $original_common_date->sql_format(Config::get('limit_notification_time'));


		// --------------------------------------------------
		//   古いデータをまとめて削除
		// --------------------------------------------------

		$query = DB::delete('notifications');
		$query->where('regi_date', '<', $pre_datetime);
		$result_arr = $query->execute();


		return $result_arr;
	}



	/**
	* 通知を挿入・更新
	* @param array $save_arr
	* @return array
	*/
	public function save_notifications($arr)
	{

		$save_type = 'insert';


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$original_common_text = new Original\Common\Text();
		$original_common_date = new Original\Common\Date();



		// --------------------------------------------------
		//   保存用配列作成
		// --------------------------------------------------

		$save_arr = array();


		// --------------------------------------------------
		//   ID
		// --------------------------------------------------

		$save_arr['id'] = $original_common_text->random_text_lowercase(15);


		// --------------------------------------------------
		//   日時
		// --------------------------------------------------

		$save_arr['regi_date'] = (isset($arr['regi_date'])) ? $arr['regi_date'] : $original_common_date->sql_format();


		// --------------------------------------------------
		//   ユーザーNo
		// --------------------------------------------------

		$save_arr['user_no'] = (isset($arr['user_no'])) ? $arr['user_no'] : $this->user_no;


		// --------------------------------------------------
		//   プロフィール No
		// --------------------------------------------------

		$save_arr['profile_no'] = (isset($arr['profile_no'])) ? $arr['profile_no'] : null;


		// --------------------------------------------------
		//   Target ユーザーNo
		// --------------------------------------------------

		$save_arr['target_user_no'] = $arr['target_user_no'];


		// --------------------------------------------------
		//   Community No
		// --------------------------------------------------

		if (isset($arr['community_no'])) $save_arr['community_no'] = $arr['community_no'];


		// --------------------------------------------------
		//   ゲームNo
		// --------------------------------------------------

		$save_arr['game_no'] = $arr['game_no'];


		// --------------------------------------------------
		//   Type1
		// --------------------------------------------------

		$save_arr['type1'] = $arr['type1'];


		// --------------------------------------------------
		//   Type2
		// --------------------------------------------------

		$save_arr['type2'] = $arr['type2'];


		// --------------------------------------------------
		//   Argument
		// --------------------------------------------------

		$argument_arr = array();

		// ゲームコミュニティ
		if (isset($arr['recruitment_id'])) $argument_arr['recruitment_id'] = $arr['recruitment_id'];

		// ユーザーコミュニティ
		if (isset($arr['bbs_id'])) $argument_arr['bbs_id'] = $arr['bbs_id'];
		if (isset($arr['bbs_thread_no'])) $argument_arr['bbs_thread_no'] = $arr['bbs_thread_no'];
		if (isset($arr['bbs_comment_no'])) $argument_arr['bbs_comment_no'] = $arr['bbs_comment_no'];
		if (isset($arr['bbs_reply_no'])) $argument_arr['bbs_reply_no'] = $arr['bbs_reply_no'];

		if (count($argument_arr) > 0) $save_arr['argument'] = serialize($argument_arr);


		// --------------------------------------------------
		//   タイトル
		// --------------------------------------------------

		$save_arr['title'] = $arr['title'];


		// --------------------------------------------------
		//   匿名
		// --------------------------------------------------

		$save_arr['anonymity'] = (isset($arr['anonymity'])) ? 1 : null;


		// --------------------------------------------------
		//   名前
		// --------------------------------------------------

		$save_arr['name'] = (isset($arr['name'])) ? $arr['name'] : null;


		// --------------------------------------------------
		//   コメント
		// --------------------------------------------------

		$save_arr['comment'] = $arr['comment'];





		// --------------------------------------------------
		//   通知が大量に増えないように1つの通知を上書き更新することで対応
		//   通知送信前、送信中は更新しない
		// --------------------------------------------------

		// --------------------------------------------------
		//   ゲームコミュニティ　募集
		// --------------------------------------------------

		if ($arr['type1'] == 'gc' and $arr['type2'] == 'recruitment')
		{

			$query = DB::select('*')->from('notifications');
			$query->where('type1', '=', 'gc');
			$query->where('type2', '=', 'recruitment');
			$query->where('game_no', '=', $arr['game_no']);
			$query->limit(1);
			$query->offset(0);
			$db_notifications_arr = $query->execute()->current();

			if (isset($db_notifications_arr))
			{
				// 通知送信前（on）、送信中は更新しない（sending）
				$save_type = ($db_notifications_arr['send_status'] == 'off') ? 'update' : 'not_save';

				// 一定期間以上過ぎていない新しい通知は更新しない
				$datetime_regi = new DateTime($db_notifications_arr['regi_date']);
				$datetime_past = new DateTime();
				$datetime_past->modify('-1 minute');

				if ($datetime_regi > $datetime_past) $save_type = 'not_save';
			}

		}

		// --------------------------------------------------
		//   ユーザーコミュニティ　告知
		// --------------------------------------------------

		else if ($arr['type1'] == 'uc' and $arr['type2'] == 'announcement')
		{

			$query = DB::select('*')->from('notifications');
			$query->where('type1', '=', 'uc');
			$query->where('type2', '=', 'announcement');
			$query->where('community_no', '=', $arr['community_no']);
			$query->limit(1);
			$query->offset(0);
			$db_notifications_arr = $query->execute()->current();

			if (isset($db_notifications_arr))
			{
				// 通知送信前（on）、送信中は更新しない（sending）
				$save_type = ($db_notifications_arr['send_status'] == 'off') ? 'update' : 'not_save';

				// 一定期間以上過ぎていない新しい通知は更新しない
				$datetime_regi = new DateTime($db_notifications_arr['regi_date']);
				$datetime_past = new DateTime();
				$datetime_past->modify('-10 minutes');

				if ($datetime_regi > $datetime_past) $save_type = 'not_save';

				//var_dump($datetime_regi, $datetime_past);
			}

		}


		//$save_type = 'insert';
		//$save_type = 'update';
		//$save_type = 'not_save';


		// --------------------------------------------------
		//   新規挿入
		// --------------------------------------------------

		if ($save_type == 'insert')
		{
			$query = DB::insert('notifications');
			$query->set($save_arr);
			$result_arr = $query->execute();
		}

		// --------------------------------------------------
		//   上書き更新
		// --------------------------------------------------

		else if ($save_type == 'update')
		{
			//unset($save_arr['id']);

			$save_arr['send_start_date'] = null;
			$save_arr['send_stop_date'] = null;
			$save_arr['send_status'] = 'on';
			$save_arr['send_latest_users'] = null;
			$save_arr['send_page'] = 1;
			$save_arr['send_start_date'] = null;

			$query = DB::update('notifications');
			$query->set($save_arr);
			$query->where('id', '=', $db_notifications_arr['id']);
			$result_arr = $query->execute();
		}



		//$test = true;

		if (isset($test))
		{
			Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

			if (isset($db_notifications_arr))
			{
				echo '$db_notifications_arr';
				Debug::dump($db_notifications_arr);
			}

			echo '$save_type';
			Debug::dump($save_type);

			echo '$save_arr';
			Debug::dump($save_arr);

			if (isset($result_arr))
			{
				echo '$result_arr';
				Debug::dump($result_arr);
			}

		}



		// --------------------------------------------------
		//   お知らせ 削除　1/100の確率で古いデータ削除
		// --------------------------------------------------

		$random_number = mt_rand(1, 100);
		if ($random_number == 1) $result_delete = $model_notifications->delete_notifications();


		return (isset($result_arr)) ? true : false;

	}



	/**
	* 未読のIDを既読にするため、保存予約
	* @param array $id_arr テーブルnotificationsのID配列
	* @return array
	*/
	public function save_notifications_id_reservation($id_arr)
	{


		// --------------------------------------------------
		//   予約されているIDを取得
		// --------------------------------------------------

		$model_user = new Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;

		$db_user_data_arr = $model_user->get_user_data($this->user_no);
		$existing_id_serialized = $db_user_data_arr['notifications_reservation_id'];


		// テスト用の値　既存ID
		//$existing_id_serialized = serialize(array(array('id' => 'bwoidtkr9flcqi2', 'regi_date' => '2000-02-24 21:58:05')));


		// --------------------------------------------------
		//   既読のIDがすでに存在する場合
		// --------------------------------------------------

		if (isset($existing_id_serialized))
		{
			$existing_id_arr = unserialize($existing_id_serialized);

			// すでにある未読分と統合
			$merged_arr = array_merge($existing_id_arr, $id_arr);

			$notifications_already_read_id_arr = array_unique($merged_arr);
		}

		// --------------------------------------------------
		//   初めて既読IDを登録する場合
		// --------------------------------------------------

		else
		{
			$notifications_already_read_id_arr = $id_arr;
		}


		// --------------------------------------------------
		//   保存用配列作成
		// --------------------------------------------------

		$save_arr['notifications_reservation_id'] = (count($notifications_already_read_id_arr) > 0) ? serialize($notifications_already_read_id_arr) : null;



		// --------------------------------------------------
		//   データベースに保存
		// --------------------------------------------------

		$result_arr = $model_user->update_user_data($this->user_no, $save_arr);





		//$test = true;

		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			echo '<br>$id_arr';
			var_dump($id_arr);

			// echo '<br>$db_user_data_arr';
			// var_dump($db_user_data_arr);

			if (isset($merged_arr))
			{
				echo '<br>$merged_arr';
				var_dump($merged_arr);
			}

			echo '<br>$notifications_already_read_id_arr';
			var_dump($notifications_already_read_id_arr);

			echo '<br>$save_arr';
			var_dump($save_arr);

		}


		//return $result_arr;

	}



	/**
	* 未読のIDを既読にするため、保存
	* @return array
	*/
	public function save_notifications_id()
	{

		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_user = new Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_user_data_arr = $model_user->get_user_data($this->user_no);


		// --------------------------------------------------
		//   未読保存予約がされているIDがある場合のみ処理
		// --------------------------------------------------

		$reservation_id_serialized = $db_user_data_arr['notifications_reservation_id'];

		if (empty($reservation_id_serialized))
		{
			return;
		}
		else
		{
			$id_arr = unserialize($reservation_id_serialized);
		}


		// --------------------------------------------------
		//   既読ID
		// --------------------------------------------------

		$existing_id_serialized = $db_user_data_arr['notifications_already_read_id'];


		// --------------------------------------------------
		//   存在するIDのみを取得
		// --------------------------------------------------

		$query = DB::select('id', 'regi_date')->from('notifications');
		$query->and_where('id', 'in', $id_arr);
		$checked_id_arr = $query->execute()->as_array();


		// --------------------------------------------------
		//   存在するIDのみを取得
		// --------------------------------------------------

		$query = DB::select('id', 'regi_date')->from('notifications');
		$query->and_where('id', 'in', $id_arr);
		$checked_id_arr = $query->execute()->as_array();





		// テスト用の値　既存ID
		//$existing_id_serialized = serialize(array(array('id' => 'bwoidtkr9flcqi2', 'regi_date' => '2000-02-24 21:58:05')));


		// --------------------------------------------------
		//   既読のIDがすでに存在する場合
		// --------------------------------------------------

		if (isset($existing_id_serialized))
		{
			$existing_id_arr = unserialize($existing_id_serialized);

			// 既読と未読を合成
			$merged_arr = array_merge($existing_id_arr, $checked_id_arr);


			// --------------------------------------------------
			//   IDが重複してしまった場合＆古いIDが含まれている場合は、該当IDを削除
			// --------------------------------------------------

			$notifications_already_read_id_arr = $arr_tmp = array();


			// 古いIDを削除するためにDateTimeオブジェクトを作成
			$pre_datetime = new \DateTime();
			$pre_datetime->modify(Config::get('limit_notification_time'));

			//var_dump($pre_datetime);

			// 多次元配列重複削除
			foreach ($merged_arr as $key => $value) {

				$regi_datetime = new \DateTime($value['regi_date']);
				//var_dump($regi_datetime);

				if ( ! in_array($value['id'], $arr_tmp) and $pre_datetime < $regi_datetime)
				{
					array_push($arr_tmp, $value['id']);
					array_push($notifications_already_read_id_arr, $value);
				}
			}

		}

		// --------------------------------------------------
		//   初めて既読IDを登録する場合
		// --------------------------------------------------

		else
		{
			$notifications_already_read_id_arr = $checked_id_arr;
		}


		// --------------------------------------------------
		//   保存用配列作成
		// --------------------------------------------------

		$save_arr['notifications_reservation_id'] = null;
		$save_arr['notifications_already_read_id'] = (count($notifications_already_read_id_arr) > 0) ? serialize($notifications_already_read_id_arr) : null;



		// --------------------------------------------------
		//   データベースに保存
		// --------------------------------------------------

		$result_arr = $model_user->update_user_data($this->user_no, $save_arr);
		//$result_arr = null;




		//$test = true;

		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			echo '<br>$id_arr';
			var_dump($id_arr);

			echo '<br>$checked_id_arr';
			var_dump($checked_id_arr);

			// echo '<br>$db_user_data_arr';
			// var_dump($db_user_data_arr);

			if (isset($merged_arr))
			{
				echo '<br>$merged_arr';
				var_dump($merged_arr);
			}

			if (isset($arr_tmp))
			{
				echo '<br>$arr_tmp';
				var_dump($arr_tmp);
			}

			echo '<br>$notifications_already_read_id_arr';
			var_dump($notifications_already_read_id_arr);

			echo '<br>$save_arr';
			var_dump($save_arr);

		}


		return $result_arr;

	}




	/**
	* 通知・メールの情報を保存
	* @return array
	*/
	public function save_notifications_data()
	{

		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_user = new Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_user_data_arr = $model_user->get_user_data($this->user_no);


		// --------------------------------------------------
		//   未読保存予約がされているIDがある場合のみ処理
		// --------------------------------------------------

		$reservation_id_serialized = $db_user_data_arr['notifications_reservation_id'];

		if (empty($reservation_id_serialized))
		{
			return;
		}
		else
		{
			$id_arr = unserialize($reservation_id_serialized);
		}


		// --------------------------------------------------
		//   既読ID
		// --------------------------------------------------

		$existing_id_serialized = $db_user_data_arr['notifications_already_read_id'];


		// --------------------------------------------------
		//   存在するIDのみを取得
		// --------------------------------------------------

		$query = DB::select('id', 'regi_date')->from('notifications');
		$query->and_where('id', 'in', $id_arr);
		$checked_id_arr = $query->execute()->as_array();


		// --------------------------------------------------
		//   存在するIDのみを取得
		// --------------------------------------------------

		$query = DB::select('id', 'regi_date')->from('notifications');
		$query->and_where('id', 'in', $id_arr);
		$checked_id_arr = $query->execute()->as_array();





		// テスト用の値　既存ID
		//$existing_id_serialized = serialize(array(array('id' => 'bwoidtkr9flcqi2', 'regi_date' => '2000-02-24 21:58:05')));


		// --------------------------------------------------
		//   既読のIDがすでに存在する場合
		// --------------------------------------------------

		if (isset($existing_id_serialized))
		{
			$existing_id_arr = unserialize($existing_id_serialized);

			// 既読と未読を合成
			$merged_arr = array_merge($existing_id_arr, $checked_id_arr);


			// --------------------------------------------------
			//   IDが重複してしまった場合＆古いIDが含まれている場合は、該当IDを削除
			// --------------------------------------------------

			$notifications_already_read_id_arr = $arr_tmp = array();


			// 古いIDを削除するためにDateTimeオブジェクトを作成
			$pre_datetime = new \DateTime();
			$pre_datetime->modify(Config::get('limit_notification_time'));

			//var_dump($pre_datetime);

			// 多次元配列重複削除
			foreach ($merged_arr as $key => $value) {

				$regi_datetime = new \DateTime($value['regi_date']);
				//var_dump($regi_datetime);

				if ( ! in_array($value['id'], $arr_tmp) and $pre_datetime < $regi_datetime)
				{
					array_push($arr_tmp, $value['id']);
					array_push($notifications_already_read_id_arr, $value);
				}
			}

		}

		// --------------------------------------------------
		//   初めて既読IDを登録する場合
		// --------------------------------------------------

		else
		{
			$notifications_already_read_id_arr = $checked_id_arr;
		}


		// --------------------------------------------------
		//   保存用配列作成
		// --------------------------------------------------

		$save_arr['notifications_reservation_id'] = null;
		$save_arr['notifications_already_read_id'] = (count($notifications_already_read_id_arr) > 0) ? serialize($notifications_already_read_id_arr) : null;



		// --------------------------------------------------
		//   データベースに保存
		// --------------------------------------------------

		$result_arr = $model_user->update_user_data($this->user_no, $save_arr);





		//$test = true;

		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			echo '<br>$id_arr';
			var_dump($id_arr);

			echo '<br>$checked_id_arr';
			var_dump($checked_id_arr);

			// echo '<br>$db_user_data_arr';
			// var_dump($db_user_data_arr);

			if (isset($merged_arr))
			{
				echo '<br>$merged_arr';
				var_dump($merged_arr);
			}

			if (isset($arr_tmp))
			{
				echo '<br>$arr_tmp';
				var_dump($arr_tmp);
			}

			echo '<br>$notifications_already_read_id_arr';
			var_dump($notifications_already_read_id_arr);

			echo '<br>$save_arr';
			var_dump($save_arr);

		}


		return $result_arr;

	}



	/**
	* 更新
	* @return array
	*/
	public function update_notification($id, $id_arr, $save_arr)
	{

		$query = DB::update('notifications');
		$query->set($save_arr);

		if (isset($id)) $query->where('id', '=', $id);
		if (isset($id_arr)) $query->where('id', 'in', $id_arr);

		$arr = $query->execute();

		return $arr;

	}



	/**
	* 通知できるユーザーかどうかをチェックして更新
	* @param integer $user_no
	* @return array
	*/
	public function update_notification_on_off($user_no = null)
	{

		// --------------------------------------------------
		//   User No設定 / ない場合は処理停止
		// --------------------------------------------------

		if ( ! $user_no) $user_no = $this->user_no;
		if ( ! $user_no) exit();


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$query = DB::select('users_data.notification_data', 'users_login.email')->from('users_data');
		$query->join('users_login', 'LEFT');
		$query->on('users_data.user_no', '=', 'users_login.id');
		$query->and_where('users_data.on_off', '=', 1);
		$query->and_where('users_data.user_no', '=', $user_no);
		$arr = $query->execute()->current();


		$notification_data_arr = unserialize($arr['notification_data']);
		$notification_on_off = null;


		// --------------------------------------------------
		//   まず通知を受け取る設定にしていることが前提条件
		// --------------------------------------------------

		if ($notification_data_arr['on_off'])
		{

			// --------------------------------------------------
			//   ブラウザで通知を受ける場合
			//   on_off_browserがtrue、receive_browserが存在しているとOK
			// --------------------------------------------------

			if ($notification_data_arr['on_off_browser'] and $notification_data_arr['receive_browser'])
			{
				$notification_on_off = 1;
			}

			// --------------------------------------------------
			//   アプリで通知を受ける場合
			//   on_off_appがtrue、receive_deviceが存在しているとOK
			// --------------------------------------------------

			if ($notification_data_arr['on_off_app'] and $notification_data_arr['receive_device'])
			{
				$notification_on_off = 1;
			}

			// --------------------------------------------------
			//   メールで通知を受ける場合
			//   on_off_mailがtrue、Eメールが登録されているとOK
			// --------------------------------------------------

			if ($notification_data_arr['on_off_mail'] and $arr['email'])
			{
				$notification_on_off = 1;
			}

		}


		// \Debug::dump($notification_data_arr, $notification_on_off);
		// exit();

		// --------------------------------------------------
		//   データベース更新
		// --------------------------------------------------

		$query = DB::update('users_data');
		$query->set(array('notification_on_off' => $notification_on_off));
		$query->where('user_no', '=', $user_no);
		$arr = $query->execute();

		//var_dump($arr);

		return $arr;

	}




}
