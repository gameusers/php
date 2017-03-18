<?php

class Controller_Rest_Common extends Controller_Rest_Base
{
	
	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}
	
	
	
	/**
	* ゲーム名検索
	*
	* @return string HTMLコード
	*/
	public function get_search_game_name()
	{
		/*
		//$this->user_no = 1;
		//$_POST['user_no'] = 1;
		$_POST['keyword'] = 'Dark';
		*/
		//var_dump($_POST['page']);
		//var_dump($this->user_no);
		
		$language = 'ja';
		
		
		$arr = array();
		
		
		// バリデーション
		$val = Validation::forge();
		$val->add_field('keyword', 'Keyword', 'required|min_length[1]|max_length[100]');
		
		if ($val->run(array('keyword' => Input::get('keyword'))))
		{
			$validated_keyword = $val->validated('keyword');
			//var_dump($validated_user_no, $validated_profile_no);
			
			$model_game = new Model_Game();
			$arr = $model_game->search_game_name($language, $validated_keyword, 10);
			
			/*
			var_dump($result_arr);
			
			if ($result_arr)
			{
				foreach ($result_arr as $key => $value) {
					
				}
			}
			*/
			
			/*
			$view = View::forge('parts/autocomplete_game_data_view');
			$view->set('game_names_arr', $result);
			$view->set('language', $this->language);
			$arr['view'] = $view->render();
			*/
			//var_dump($arr);
			
		}/*
		else
		{
			
			$error_message = '';
			if (count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) {
					$error_message .= $value;
				}
			}
			echo $error_message;
			//$arr['alert_color'] = 'warning';
			//$arr['alert_title'] = 'エラー';
			//$arr['alert_message'] = '保存できませんでした。';
			 
		}
		*/
		
		return $this->response(\Security::htmlentities($arr));
		
	}
	
	
	
	/**
	* ユーザーコミュニティ検索
	*
	* @return string HTMLコード
	*/
	public function post_search_community_list()
	{
		//return $this->response(array('success' => true));
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		//$_POST['page'] = 1;
		if (isset($test))
		{
			$_POST['page'] = 1;
		}
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   フォームが正しい経緯で送信されていません。
			// --------------------------------------------------
			
			$cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
			$post_csrf_token = Input::post('fuel_csrf_token');
			
			if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			{
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
				throw new Exception('Error');
			}
			
			
			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------
			
			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------
			
			$val = Validation::forge();
			
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			
			if ($val->run())
			{
				
				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------
				
				$validated_page = $val->validated('page');
				$validated_app_mode = (Input::post('app_mode')) ? 1: null;
				
				
				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------
				
				// インスタンス作成
				$original_code_common = new Original\Code\Common();
				$original_code_common->app_mode = $this->app_mode;
				//if ($validated_app_mode) $original_code_common->app_mode = true;
				$original_code_common->agent_type = $this->agent_type;
				$original_code_common->user_no = $this->user_no;
				$original_code_common->language = $this->language;
				$original_code_common->uri_base = $this->uri_base;
				$original_code_common->uri_current = $this->uri_current;
				
				
				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------
				
				$arr['code'] = $original_code_common->search_community_list(null, $validated_page);
				
				
				if (isset($test))
				{
					//Debug::$js_toggle_open = true;
					
					echo '$validated_page';
					var_dump($validated_page);
					/*
					echo '$validated_community_description';
					var_dump($validated_community_description);
					
					echo '$validated_community_description_mini';
					var_dump($validated_community_description_mini);
					
					echo '$validated_community_id';
					var_dump($validated_community_id);
					
					echo '$validated_game_list';
					var_dump($validated_game_list);
					
					echo '$save_arr';
					var_dump($save_arr);
					*/
					echo $arr['code'];
					
					
				}
				//exit();
				
				
				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------
				/*
				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = 'コミュニティを作成しました。';
				*/
			}
			else
			{
				
				// --------------------------------------------------
				//   アラート　エラー
				// --------------------------------------------------
				/*
				$error_message = '';
				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$error_message .= $value;
					}
				}
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '作成できませんでした。' . $error_message;
				
				//$arr['test'] = 'エラー ' . $error_message;
				if (isset($test)) echo $error_message;
				*/
			}
			
		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			if (isset($test)) echo $e->getMessage();
		}
		
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	/**
	* Goodボタン
	*
	* @return string HTMLコード
	*/
	public function post_plus_good()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			//$_POST['type'] = 'user';
			//$_POST['no'] = 3;
			
			// $_POST['type'] = 'recruitment';
			// $_POST['id'] = 'xtklbe1caeoxiw23';
			
			//$_POST['type'] = 'recruitment_reply';
			//$_POST['id'] = '9mh5pnluy8f6r98n';
			
			//$_POST['type'] = 'bbs_reply_gc';
			//$_POST['no'] = 31;
			
			$_POST['type'] = 'bbs_reply_uc';
			$_POST['no'] = 38;
		}
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   フォームが正しい経緯で送信されていません。
			// --------------------------------------------------
			
			$cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
			$post_csrf_token = Input::post('fuel_csrf_token');
			
			if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			{
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。ページを再読み込みしてください。';
				throw new Exception('Error');
			}
			
			
			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------
			
			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------
			
			$val = Validation::forge();
			
			if (Input::post('type') == 'user')
			{
				$val->add_callable('Original_Rule_User');
				$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]|check_user_data');
				
			}
			else if (Input::post('type') == 'profile')
			{
				$val->add_callable('Original_Rule_User');
				$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]|check_profile');
			}
			else if (Input::post('type') == 'recruitment')
			{
				$val->add_callable('Original_Rule_Gc');
				$val->add_field('id', 'id', 'required|check_recruitment_id');
			}
			else if (Input::post('type') == 'recruitment_reply')
			{
				$val->add_callable('Original_Rule_Gc');
				$val->add_field('id', 'id', 'required|check_recruitment_reply_id');
			}
			else if (Input::post('type') == 'bbs_thread_gc')
			{
				$val->add_callable('Original\Validation\Common');
				$val->add_field('no', 'No', 'required|check_bbs_thread_no_gc');
			}
			else if (Input::post('type') == 'bbs_comment_gc')
			{
				$val->add_callable('Original\Validation\Common');
				$val->add_field('no', 'No', 'required|check_bbs_comment_no_gc');
			}
			else if (Input::post('type') == 'bbs_reply_gc')
			{
				$val->add_callable('Original\Validation\Common');
				$val->add_field('no', 'No', 'required|check_bbs_reply_no_gc');
			}
			/*else if (Input::post('type') == 'announcement')
			{
				$val->add_callable('Original_Rule_Co');
				$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]|check_announcement_no');
			}*/
			else if (Input::post('type') == 'bbs_thread_uc')
			{
				$val->add_callable('Original_Rule_Co');
				$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]|check_bbs_thread_no');
			}
			else if (Input::post('type') == 'bbs_comment_uc')
			{
				$val->add_callable('Original_Rule_Co');
				$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]|check_bbs_comment_no');
			}
			else if (Input::post('type') == 'bbs_reply_uc')
			{
				$val->add_callable('Original_Rule_Co');
				$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]|check_bbs_reply_no');
			}
			else
			{
				throw new Exception('Error');
			}
			
			$val->add_field('host', 'Host', 'required');
			$val->add_field('user_agent', 'User Agent', 'required');
			
			//exit();
			
			if ($val->run(array('host' => $this->host, 'user_agent' => $this->user_agent)))
			{
				
				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------
				
				$validated_type = Input::post('type');
				$validated_no = ($val->validated('no')) ? $val->validated('no') : null;
				$validated_id = ($val->validated('id')) ? $val->validated('id') : null;
				
				
				
				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------
				
				// インスタンス作成
				$model_good = new Model_Good();
				$model_good->user_no = $this->user_no;
				$model_good->host = $this->host;
				$model_good->user_agent = $this->user_agent;
				
				
				// --------------------------------------------------
				//   Good Log チェック
				// --------------------------------------------------
				
				if ($this->user_no)
				{
					$good_log_arr = $model_good->get_good_log(array('type' => $validated_type, 'no' => $validated_no, 'id' => $validated_id, 'user_no' => $this->user_no));
				}
				else
				{
					$good_log_arr = $model_good->get_good_log(array('type' => $validated_type, 'no' => $validated_no, 'id' => $validated_id, 'host' => $this->host));
				}
				//echo '<br>$good_log_arr';
				//var_dump($good_log_arr);
				
				if ($good_log_arr)
				{
					$arr['alert_color'] = 'warning';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '過去にこのGoodボタンを押しています。';
					throw new Exception('Error');
				}
				
				
				
				// --------------------------------------------------
				//   Good 追加
				// --------------------------------------------------
				
				if ($validated_type == 'user')
				{
					$result_arr = $model_good->plus_good_user_data($validated_no);
				}
				else if ($validated_type == 'profile')
				{
					$result_arr = $model_good->plus_good_profile($validated_no);
				}
				else if ($validated_type == 'recruitment')
				{
					$result_arr = $model_good->plus_good_recruitment($validated_id);
				}
				else if ($validated_type == 'recruitment_reply')
				{
					$result_arr = $model_good->plus_good_recruitment_reply($validated_id);
				}
				else if ($validated_type == 'bbs_thread_gc')
				{
					$result_arr = $model_good->plus_good_bbs_thread_gc($validated_no);
				}
				else if ($validated_type == 'bbs_comment_gc')
				{
					$result_arr = $model_good->plus_good_bbs_comment_gc($validated_no);
				}
				else if ($validated_type == 'bbs_reply_gc')
				{
					$result_arr = $model_good->plus_good_bbs_reply_gc($validated_no);
				}
				/*else if ($validated_type == 'announcement')
				{
					$result_arr = $model_good->plus_good_announcement($validated_no);
				}*/
				else if ($validated_type == 'bbs_thread_uc')
				{
					$result_arr = $model_good->plus_good_bbs_thread_uc($validated_no);
				}
				else if ($validated_type == 'bbs_comment_uc')
				{
					$result_arr = $model_good->plus_good_bbs_comment_uc($validated_no);
				}
				else if ($validated_type == 'bbs_reply_uc')
				{
					$result_arr = $model_good->plus_good_bbs_reply_uc($validated_no);
				}
				
				
				
				// --------------------------------------------------
				//   Good ログ 削除　1/100の確率で古いデータ削除
				// --------------------------------------------------
				
				$random_number = mt_rand(1, 100);
				if ($random_number == 1) $result_delete = $model_good->delete_good_log();
				
				
				
				// --------------------------------------------------
				//   レベル反映用
				// --------------------------------------------------
				
				if ($result_arr['result'] === true)
				{
					$arr['good'] = $result_arr['good'];
					$arr['level_id_user'] = $result_arr['level_id_user'];
					$arr['level_user'] = $result_arr['level_user'];
					$arr['level_id_profile'] = $result_arr['level_id_profile'];
					$arr['level_profile'] = $result_arr['level_profile'];
				}
				else if ($result_arr['result'] == 'error1')
				{
					$arr['alert_color'] = 'warning';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '自分で自分を評価することはできません。';
					throw new Exception('Error');
				}
				else
				{
					$arr['alert_color'] = 'warning';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'エラー';
					throw new Exception('Error');
				}
				
				
				if (isset($test))
				{
					echo '<br>$validated_type';
					var_dump($validated_type);
					
					echo '<br>$validated_no';
					var_dump($validated_no);
					
					echo '<br>$validated_id';
					var_dump($validated_id);
					
					echo '<br>$good_log_arr';
					var_dump($good_log_arr);
					
					echo '<br>$result_arr';
					var_dump($result_arr);
				}
				
				//exit();
				
			}
			else
			{
				
				// --------------------------------------------------
				//   アラート　エラー
				// --------------------------------------------------
				/*
				$error_message = '';
				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$error_message .= $value;
					}
				}
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '保存できませんでした。' . $error_message;r_message;
				$arr['test'] = 'エラー ' . $error_message;
				
				if (isset($test)) echo $error_message;
				*/
			}
			
		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			if (isset($test)) echo $e->getMessage();
		}
		
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	/**
	* お知らせ未読件数
	* Original\Code\Player / notifications
	* Model_Notifications / read_notifications
	* 上記ふたつを組み合わせたもの、上記にコードの変更があった場合はこちらも変更すること
	*
	* @return string HTMLコード
	*/
	public function post_read_notifications_unread_total()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			$_POST['page'] = 1;
			$this->user_no = 1;
		}
		
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   ログインする必要があります。
			// --------------------------------------------------
			
			if ( ! $this->user_no)
			{
				$arr['alert_color'] = 'danger';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = 'ログインする必要があります。';
				throw new Exception('Error');
			}
			
			
			// --------------------------------------------------
			//   共通処理
			// --------------------------------------------------
			
			// インスタンス作成
			$model_user = new \Model_User();
			$model_user->agent_type = $this->agent_type;
			$model_user->user_no = $this->user_no;
			$model_user->language = $this->language;
			$model_user->uri_base = $this->uri_base;
			$model_user->uri_current = $this->uri_current;
			
			$model_notifications = new \Model_Notifications();
			$model_notifications->agent_type = $this->agent_type;
			$model_notifications->user_no = $this->user_no;
			$model_notifications->language = $this->language;
			$model_notifications->uri_base = $this->uri_base;
			$model_notifications->uri_current = $this->uri_current;
			
			$model_gc = new \Model_Gc();
			$model_gc->agent_type = $this->agent_type;
			$model_gc->user_no = $this->user_no;
			$model_gc->language = $this->language;
			$model_gc->uri_base = $this->uri_base;
			$model_gc->uri_current = $this->uri_current;
			
			$original_func_common = new \Original\Func\Common();
			$original_func_common->app_mode = $this->app_mode;
			$original_func_common->agent_type = $this->agent_type;
			$original_func_common->user_no = $this->user_no;
			$original_func_common->language = $this->language;
			$original_func_common->uri_base = $this->uri_base;
			$original_func_common->uri_current = $this->uri_current;
			
			
			
			// --------------------------------------------------
			//    データ取得
			// --------------------------------------------------
			
			$db_users_data_arr = $model_user->get_user_data($this->user_no, null);
			
			
			// --------------------------------------------------
			//    既読IDの処理
			//    例）$already_read_id_arr = array('0zj0pnd2vlw2eex', '2rwgyd1sbzyu5ub')
			// --------------------------------------------------
			
			if ($db_users_data_arr['notifications_already_read_id'])
			{
				// アンシリアライズ
				$pre_already_read_id_arr = unserialize($db_users_data_arr['notifications_already_read_id']);
				
				// 日付を削除した配列を作る
				$already_read_id_arr = array();
				foreach ($pre_already_read_id_arr as $key => $value) {
					array_push($already_read_id_arr, $value['id']);
				}
			}
			else
			{
				$already_read_id_arr = null;
			}
			
			
			
			// --------------------------------------------------
			//    参加しているユーザーコミュニティのNoを配列化する処理
			//    例）$participation_community_no_arr = array(1,2,3,4,5)
			// --------------------------------------------------
			
			// 公開
			if ($db_users_data_arr['participation_community'])
			{
				$participation_community_arr = $original_func_common->return_db_array('db_php', $db_users_data_arr['participation_community']);
			}
			else
			{
				$participation_community_arr = array();
			}
			
			// 非公開
			if ($db_users_data_arr['participation_community_secret'])
			{
				$participation_community_secret_arr = $original_func_common->return_db_array('db_php', $db_users_data_arr['participation_community_secret']);
			}
			else
			{
				$participation_community_secret_arr = array();
			}
			
			// 公開と非公開を合成
			$participation_community_no_arr = array_merge($participation_community_arr, $participation_community_secret_arr);
			
			
			
			// --------------------------------------------------
			//    新規募集があったときに通知を受ける設定にしているゲームNoを取得
			//    例）$notification_recruitment = array(1,2,3,4,5)
			// --------------------------------------------------
			
			$db_user_game_community = $model_gc->get_user_game_community();
			$notification_recruitment_arr = (isset($db_user_game_community['notification_recruitment'])) ? $original_func_common->return_db_array('db_php', $db_user_game_community['notification_recruitment']) : null;
			
			
			// --------------------------------------------------
			//    データ読み込み
			// --------------------------------------------------
			
			//$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_notification') : \Config::get('limit_notification_sp');
			//$result_arr = $model_notifications->read_notifications($unread, $already_read_id_arr, $participation_community_no_arr, $page, $limit);
			
			
			// ○○前の日時を取得
			$original_common_date = new Original\Common\Date();
			$pre_datetime = $original_common_date->sql_format(Config::get('limit_notification_time'));
			
			
			$query = DB::select(DB::expr('COUNT(*) as total'))->from('notifications');
			$query->join('users_data', 'LEFT');
			$query->on('notifications.user_no', '=', 'users_data.user_no');
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
			
			$query->and_where('notifications.target_user_no', '=', $this->user_no);
			if (count($participation_community_no_arr) > 0) $query->or_where('notifications.community_no', 'in', $participation_community_no_arr);
			
			if (isset($notification_recruitment_arr))
			{
				$query->or_where_open();
				$query->and_where('notifications.type1', '=', 'gc');
				$query->and_where('notifications.type2', '=', 'recruitment');
				$query->and_where('notifications.game_no', 'in', $notification_recruitment_arr);
				$query->or_where_close();
			}
			
			$query->and_where_close();
			
			
			// $query->and_where_open();
			// $query->and_where('notifications.target_user_no', '=', $this->user_no);
			// if (count($participation_community_no_arr) > 0)
			// {
				// $query->or_where('notifications.community_no', 'in', $participation_community_no_arr);
			// }
			// $query->and_where_close();
			
			if ($already_read_id_arr)
			{
				$query->and_where('notifications.id', 'not in', $already_read_id_arr);
			}
			
			$result_arr = $query->execute()->as_array();
			
			$arr['total'] = $result_arr[0]['total'];
			
			
			if (isset($test))
			{
				Debug::$js_toggle_open = true;
				
				echo '<br>$already_read_id_arr';
				Debug::dump($already_read_id_arr);
				
				echo '<br>$notification_recruitment_arr';
				Debug::dump($notification_recruitment_arr);
				
			}
			
			
			
		}
		catch (Exception $e) {
			if (isset($test)) echo $e->getMessage();
		}
		
		
		if (isset($test))
		{
			Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	
	/**
	* お知らせ　すべての未読を既読に変える
	*
	* @return string HTMLコード
	*/
	public function post_change_all_unread_to_already()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			//$_POST['page'] = 1;
			//$this->user_no = 1;
		}
		
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   ログインする必要があります。
			// --------------------------------------------------
			
			if ( ! $this->user_no)
			{
				$arr['alert_color'] = 'danger';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = 'ログインする必要があります。';
				throw new Exception('Error');
			}
			
			
			// --------------------------------------------------
			//   共通処理
			// --------------------------------------------------
			
			// インスタンス作成
			$original_func_common = new \Original\Func\Common();
			$original_func_common->app_mode = $this->app_mode;
			$original_func_common->agent_type = $this->agent_type;
			$original_func_common->user_no = $this->user_no;
			$original_func_common->language = $this->language;
			$original_func_common->uri_base = $this->uri_base;
			$original_func_common->uri_current = $this->uri_current;
			
			
			
			// --------------------------------------------------
			//    データベース更新
			// --------------------------------------------------
			
			$result_arr = $original_func_common->change_notifications_already(array());
			
			
			$arr['success'] = true;
			
			
			if (isset($test))
			{
				Debug::$js_toggle_open = true;
				
				echo '<br>$result_arr';
				\Debug::dump($result_arr);
				
			}
			
			
			
		}
		catch (Exception $e) {
			if (isset($test)) echo $e->getMessage();
			$arr['success'] = false;
		}
		
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	
	/**
	* 通報送信
	*
	* @return string HTMLコード
	*/
	public function post_send_report()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			$_POST['report_page_information'] = 'https://192.168.10.2/gameusers/public/pl/az1979';
			$_POST['report_comment'] = 'コメント';
		}
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   フォームが正しい経緯で送信されていません。
			// --------------------------------------------------
			
			$cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
			$post_csrf_token = Input::post('fuel_csrf_token');
			
			if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			{
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
				throw new Exception('Error');
			}
			
			
			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------
			
			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------
			
			$val = Validation::forge();
			
			$val->add_field('report_page_information', 'ページ情報', 'required|min_length[1]|max_length[500]');
			$val->add_field('report_comment', '通報内容', 'required|min_length[1]|max_length[3000]');
			
			if ($val->run())
			{
				
				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------
				
				$validated_report_page_information = $val->validated('report_page_information');
				$validated_report_comment = $val->validated('report_comment');
				
				
				$validated_report_comment .= "\n\n" . $validated_report_page_information;
				$validated_report_comment .= "\n\n" . '--------------------' . "\n\n" . $this->host . "\n" . $this->user_agent . "\n\n" . '--------------------';
				
				
				
				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------
				
				// インスタンス作成
				$original_common_mail = new Original\Common\Mail();
				
				
				
				// --------------------------------------------------
				//    メール送信
				//    mail@gameusers.orgに送信するとなぜだか届かないので、sakura.ne.jp宛にする
				// --------------------------------------------------
				
				$result = $original_common_mail->to('mail@gameusers.org', 'User', Config::get('inquiry_mail_address'), 'Game Users', '通報', $validated_report_comment);
				
				
				
				
				if (isset($test))
				{
					//Debug::$js_toggle_open = true;
					
					echo '$validated_report_page_information';
					var_dump($validated_report_page_information);
					
					echo '$validated_report_comment';
					var_dump($validated_report_comment);
					
				}
				//exit();
				
				
				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------
				
				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = 'ご協力ありがとうざいました。';
				
			}
			else
			{
				
				// --------------------------------------------------
				//   アラート　エラー
				// --------------------------------------------------
				
				$error_message = '';
				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$error_message .= $value;
					}
				}
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '送信できませんでした。' . $error_message;
				
				//$arr['test'] = 'エラー ' . $error_message;
				if (isset($test)) echo $error_message;
				
			}
			
		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			if (isset($test)) echo $e->getMessage();
		}
		
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
}