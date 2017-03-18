<?php

class Controller_Rest_Gc extends Controller_Rest_Base
{

	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}





	/**
	* 募集　通知を受ける設定にする
	*
	* @return string HTMLコード
	*/
	public function post_save_gc_notification()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['game_no'] = 1;
			$_POST['type'] = 'recruitment';
			$_POST['on'] = 1;
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
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Common');

			$val->add_field('game_no', 'Game No', 'required|check_game_no');
			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(recruitment)$/');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_game_no = $val->validated('game_no');
				$validated_type = $val->validated('type');
				$validated_on = (Input::post('on')) ? 1 : null;


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$model_gc = new Model_Gc();
				$model_gc->agent_type = $this->agent_type;
				$model_gc->user_no = $this->user_no;
				$model_gc->language = $this->language;
				$model_gc->uri_base = $this->uri_base;
				$model_gc->uri_current = $this->uri_current;

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_users_game_community_arr = $model_gc->get_user_game_community();
				$db_notification_recruitment = $db_users_game_community_arr['notification_recruitment'];

				if ($db_notification_recruitment)
				{
					$notification_recruitment_arr = $original_func_common->return_db_array('db_php', $db_notification_recruitment);
				}
				else
				{
					$notification_recruitment_arr = array();
				}


				// --------------------------------------------------
				//    配列に追加・削除
				// --------------------------------------------------

				//$validated_on = null;
				//$notification_recruitment_arr = array();

				// 通知を受ける（追加する）場合
				if ($validated_on)
				{
					if ( ! in_array($validated_game_no, $notification_recruitment_arr)) array_push($notification_recruitment_arr, $validated_game_no);
				}
				// 通知を受けない（削除する）場合
				else
				{
					$key = array_search($validated_game_no, $notification_recruitment_arr);
					if ($key !== false) array_splice($notification_recruitment_arr, $key, 1);

					//var_dump($key);
				}


				// --------------------------------------------------
				//    番号並び替え
				// --------------------------------------------------

				sort($notification_recruitment_arr);


				// --------------------------------------------------
				//    保存用データ作成
				// --------------------------------------------------

				if (count($notification_recruitment_arr) > 0)
				{
					$save_arr['notification_recruitment'] = $original_func_common->return_db_array('php_db', $notification_recruitment_arr);
				}
				else
				{
					$save_arr['notification_recruitment'] = null;
				}


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result_arr = $model_gc->update_users_game_community($save_arr);


				// --------------------------------------------------
				//   未読通知を既読通知にする　新規に参加するとこれまでの通知が大量に表示されるからそれを防止する
				// --------------------------------------------------

				$change_notifications_already_arr = array('game_no' => $validated_game_no);
				$result_arr = $original_func_common->change_notifications_already($change_notifications_already_arr);



				if (isset($test))
				{

					echo '$validated_game_no';
					\Debug::dump($validated_game_no);

					echo '$validated_type';
					\Debug::dump($validated_type);

					echo '$validated_on';
					\Debug::dump($validated_on);


					// echo '$db_user_game_community';
					// var_dump($db_user_game_community);

					echo '$notification_recruitment_arr';
					\Debug::dump($notification_recruitment_arr);

					echo '$save_arr';
					\Debug::dump($save_arr);

					echo '$result_arr';
					\Debug::dump($result_arr);

				}

				//exit();


			}
			else
			{
				/*
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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;
				*/
			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//echo $e->getMessage();
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
	* 募集読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_recruitment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		// $test = true;

		if (isset($test))
		{
			$_POST['game_no'] = 1;
			$_POST['recruitment_id'] = '7246rqe2d17s4t8w';
			//$_POST['page'] = 1;

			// 検索
			//$_POST['search_recruitment_type'] = "1";
			// $_POST['search_recruitment_hardware_id_no'] = "1,2,3";
			// $_POST['search_recruitment_id_null'] = 1;
			//$_POST['search_recruitment_keyword'] = 'あ';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Basic');
			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('game_no', 'Game No', 'required|check_game_no');

			if (Input::post('recruitment_id'))
			{
				$val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id');
			}
			else
			{
				$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			}

			// 検索
			if (Input::post('search_recruitment_type')) $val->add_field('search_recruitment_type', 'search_recruitment_type', 'check_csv_int');
			if (Input::post('search_recruitment_hardware_id_no')) $val->add_field('search_recruitment_hardware_id_no', 'search_recruitment_hardware_id_no', 'check_csv_int');
			if (Input::post('search_recruitment_keyword')) $val->add_field('search_recruitment_keyword', 'キーワード', 'min_length[1]|max_length[50]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_game_no = $val->validated('game_no');
				$validated_recruitment_id = ($val->validated('recruitment_id')) ? $val->validated('recruitment_id') : null;
				$validated_page = ($val->validated('page')) ? (int) $val->validated('page') : null;

				$validated_search_recruitment_type = $val->validated('search_recruitment_type');
				$validated_search_recruitment_hardware_id_no = $val->validated('search_recruitment_hardware_id_no');
				$validated_search_recruitment_id_null = (Input::post('search_recruitment_id_null')) ? true : null;
				$validated_search_recruitment_keyword = $val->validated('search_recruitment_keyword');

				//var_dump($validated_search_recruitment_keyword);


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$model_game = new Model_Game();
				$model_game->agent_type = $this->agent_type;
				$model_game->user_no = $this->user_no;
				$model_game->language = $this->language;
				$model_game->uri_base = $this->uri_base;
				$model_game->uri_current = $this->uri_current;

				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_user_data_arr = $model_user->get_login_user_data($validated_game_no);
				$db_users_game_community_arr = $login_user_data_arr[0];
				$login_profile_data_arr = $login_user_data_arr[1];


				// --------------------------------------------------
				//    ハードウェア
				// --------------------------------------------------

				$language = 'ja';
				$db_hardware_arr = $model_game->get_hardware_sort($language);


				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				//$validated_search_recruitment_type = $val->validated('search_recruitment_type');
				$search_recruitment_type = ($validated_search_recruitment_type) ? explode(',', $validated_search_recruitment_type) : null;

				//$validated_search_recruitment_hardware_id_no = $val->validated('search_recruitment_hardware_id_no');
				$search_recruitment_hardware_id_no = ($validated_search_recruitment_hardware_id_no) ? explode(',', $validated_search_recruitment_hardware_id_no) : null;

				//$validated_search_recruitment_id_null = (Input::post('search_recruitment_id_null')) ? true : null;
				//$validated_search_recruitment_keyword = $val->validated('search_recruitment_keyword');


				$more_button = ($validated_recruitment_id) ? true : false;


				// --------------------------------------------------
				//    募集
				// --------------------------------------------------

				$code_recruitment_arr = array(
					//'game_id' => $validated_game_id,
					'game_no' => $validated_game_no,
					'recruitment_id' => $validated_recruitment_id,
					'db_users_game_community_arr' => $db_users_game_community_arr,
					'login_profile_data_arr' => $login_profile_data_arr,
					'db_hardware_arr' => $db_hardware_arr,
					'datetime_now' => $datetime_now,
					'more_button' => $more_button,
					'search_type' => $search_recruitment_type,
					'search_hardware_id_no' => $search_recruitment_hardware_id_no,
					'search_id_null' => $validated_search_recruitment_id_null,
					'search_keyword' => $validated_search_recruitment_keyword,
					'page' => $validated_page
				);

				$code_recruitment = $original_code_gc->recruitment($code_recruitment_arr);


				//$code = $original_code_gc->recruitment($validated_game_no, $validated_recruitment_id, $db_users_game_community_arr, $login_profile_data_arr, $db_hardware_arr, $datetime_now, null, $search_recruitment_type, $search_recruitment_hardware_id_no, $validated_search_recruitment_id_null, $validated_search_recruitment_keyword, $validated_page);


				$arr = $code_recruitment;


				if (isset($test))
				{
					echo '<br>$validated_search_recruitment_type';
					var_dump($validated_search_recruitment_type);

					echo '<br>$validated_search_recruitment_hardware_id_no';
					var_dump($validated_search_recruitment_hardware_id_no);

					echo '<br>$validated_search_recruitment_id_null';
					var_dump($validated_search_recruitment_id_null);

					echo '<br>$validated_search_recruitment_keyword';
					var_dump($validated_search_recruitment_keyword);
				}

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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
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
	* 募集　返信　読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_recruitment_reply()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 2;
			$_POST['game_no'] = 1;
			//$_POST['recruitment_id'] = 'r0pg5binnda4xj59';
			$_POST['recruitment_id'] = 'rb8s8qmswlgixifb';
		}



		$arr = array();

		try
		{

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add_field('game_no', 'Game No', 'required|check_game_no');
			$val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page = $val->validated('page');
				$validated_game_no = $val->validated('game_no');
				$validated_recruitment_id = $val->validated('recruitment_id');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$model_game = new Model_Game();
				$model_game->agent_type = $this->agent_type;
				$model_game->user_no = $this->user_no;
				$model_game->language = $this->language;
				$model_game->uri_base = $this->uri_base;
				$model_game->uri_current = $this->uri_current;

				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_user_data_arr = $model_user->get_login_user_data($validated_game_no);
				$db_users_game_community_arr = $login_user_data_arr[0];
				$login_profile_data_arr = $login_user_data_arr[1];


				// --------------------------------------------------
				//    ハードウェア
				// --------------------------------------------------

				$language = 'ja';
				$db_hardware_arr = $model_game->get_hardware_sort($language);


				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				$code = $original_code_gc->recruitment_reply($validated_game_no, $validated_recruitment_id, $db_users_game_community_arr, $login_profile_data_arr, $db_hardware_arr, $datetime_now, $validated_page);


				$arr['code'] = $code;


				// if (isset($test))
				// {
					// echo "<br>db_community_arr";
					// var_dump($db_community_arr);
				// }

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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;
				*/
			}

		}
		catch (Exception $e) {
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
	* 募集・返信　投稿・編集フォーム表示
	*
	*/
	public function post_show_recruitment_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;


		if (isset($test))
		{
			// $_POST['game_no'] = 1;
			// $_POST['form_type'] = 'recruitment_edit';
			// $_POST['recruitment_id'] = 'kbz80ml1uxwhn471';
			// $_POST['recruitment_reply_id'] = null;

			$_POST['game_no'] = 1;
			$_POST['form_type'] = 'reply_new';
			$_POST['recruitment_id'] = 'pk17yxnt0v5s32pr';
			$_POST['recruitment_reply_id'] = 'uosjgmbvnqcp96hb';

			// $_POST['game_no'] = 1;
			// $_POST['form_type'] = 'reply_edit';
			// $_POST['recruitment_id'] = '77ep1gailu6bobzc';
			// $_POST['recruitment_reply_id'] = 'br5s6jtiizupwsd2';
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

			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_Common');


			// ------------------------------
			//    共通
			// ------------------------------

			$val->add_field('game_no', 'Game No', 'required|check_game_no');
			$val->add('form_type', 'Form Type')->add_rule('required')->add_rule('match_pattern', '/^(recruitment_new|recruitment_edit|reply_new|reply_edit)$/');

			if (Input::post('form_type') == 'recruitment_edit')
			{
				$val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id_authority_edit');
			}
			else
			{
				$val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id');
			}

			if (Input::post('form_type') == 'reply_edit')
			{
				$val->add_field('recruitment_reply_id', 'recruitment_reply_id', 'required|check_recruitment_reply_id_authority_edit');
			}
			else
			{
				if (Input::post('recruitment_reply_id')) $val->add_field('recruitment_reply_id', 'recruitment_reply_id', 'required|check_recruitment_reply_id');
			}


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_game_no = $val->validated('game_no');
				$validated_form_type = $val->validated('form_type');
				$validated_recruitment_id = $val->validated('recruitment_id');
				$validated_recruitment_reply_id = (Input::post('recruitment_reply_id')) ? $val->validated('recruitment_reply_id') : null;

				// $arr['code'] = 'aaa';
				// return $this->response($arr['code']);

				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_game = new Model_Game();
				$model_game->agent_type = $this->agent_type;
				$model_game->user_no = $this->user_no;
				$model_game->language = $this->language;
				$model_game->uri_base = $this->uri_base;
				$model_game->uri_current = $this->uri_current;

				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				//$original_code_gc->host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				//$original_code_gc->user_agent = $_SERVER['HTTP_USER_AGENT'];
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_user_data_arr = $model_user->get_login_user_data($validated_game_no);
				$db_users_game_community_arr = $login_user_data_arr[0];
				$login_profile_data_arr = $login_user_data_arr[1];

				//var_dump($db_users_game_community_arr, $login_profile_data_arr);



				// --------------------------------------------------
				//    ハードウェア
				// --------------------------------------------------

				$language = 'ja';
				$db_hardware_arr = $model_game->get_hardware_sort($language);
				//var_dump($db_hardware_arr);


				// --------------------------------------------------
				//    募集フォーム
				// --------------------------------------------------

				$code = $original_code_gc->form_recruitment($validated_game_no, $validated_form_type, $validated_recruitment_id, $validated_recruitment_reply_id, $login_profile_data_arr, $db_hardware_arr, $datetime_now);


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$arr['code'] = $code;

				//$arr['code'] = null;


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;



					// echo '<br>$validated_community_no';
					// var_dump($validated_community_no);
//
					// echo '<br>$validated_announcement_no';
					// var_dump($validated_announcement_no);
//
					// if (isset($db_announcement_arr))
					// {
						// echo '<br>$db_announcement_arr';
						// var_dump($db_announcement_arr);
					// }

					echo $arr['code'];

				}


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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

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
	* 募集読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_form_recruitment_select_game_id()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['game_no'] = 1;

			//$_POST['form_type'] = 'recruitment_new';

			$_POST['form_type'] = 'recruitment_edit';
			//$_POST['recruitment_id'] = 'kbz80ml1uxwhn471';
			//$_POST['recruitment_id'] = 'ujd76we4llnrb635';
			$_POST['recruitment_id'] = 'fxlajm0iog1zid7g';

			//$_POST['form_type'] = 'reply_new';

			//$_POST['form_type'] = 'reply_edit';
			//$_POST['recruitment_reply_id'] = 'r252yzkedn1mjc6y';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add_field('game_no', 'Game No', 'required|check_game_no');
			$val->add('form_type', 'Form Type')->add_rule('required')->add_rule('match_pattern', '/^(recruitment_new|recruitment_edit|reply_new|reply_edit)$/');

			if (Input::post('recruitment_id')) $val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id');
			if (Input::post('recruitment_reply_id')) $val->add_field('recruitment_reply_id', 'recruitment_reply_id', 'required|check_recruitment_reply_id');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page = $val->validated('page');
				$validated_game_no = $val->validated('game_no');
				$validated_form_type = $val->validated('form_type');
				$validated_recruitment_id = ($val->validated('recruitment_id')) ? $val->validated('recruitment_id') : null;
				$validated_recruitment_reply_id = ($val->validated('recruitment_reply_id')) ? $val->validated('recruitment_reply_id') : null;



				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    ID選択コード作成
				// --------------------------------------------------

				$temp_arr = array(
					'game_no' => $validated_game_no,
					'form_type' => $validated_form_type,
					'recruitment_id' => $validated_recruitment_id,
					'recruitment_reply_id' => $validated_recruitment_reply_id,
					'page' => $validated_page
				);

				$result_arr = $original_code_gc->form_recruitment_select_id($temp_arr);
				$arr['code'] = $result_arr['code'];

				unset($temp_arr, $result_arr);


				//$arr['code'] = $code_recruitment;




				if (isset($test))
				{
					/*
					echo '<br>$validated_page';
					var_dump($validated_page);

					echo '<br>$validated_game_no';
					var_dump($validated_game_no);

					echo '<br>$validated_form_type';
					var_dump($validated_form_type);

					echo '<br>$validated_recruitment_id';
					var_dump($validated_recruitment_id);

					echo '<br>$validated_recruitment_reply_id';
					var_dump($validated_recruitment_reply_id);

					echo $arr['code'];
					*/
				}

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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;
				*/
			}

		}
		catch (Exception $e) {
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
	* 募集・返信　作成・更新
	*
	* @return string HTMLコード
	*/
	public function post_save_recruitment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			/*
			$_POST['form_type'] = 'recruitment_new';

			//$_POST['form_type'] = 'recruitment_edit';
			//$_POST['recruitment_id'] = 'ujd76we4llnrb635';

			$_POST['game_no'] = 1;
			$_POST['type'] = 1;
			$_POST['handle_name'] = 'ハンドルネーム';
			$_POST['etc_title'] = 'タイトル';
			$_POST['comment'] = '通知テスト2';
			//$_POST['anonymity'] = true;

			//$_POST['movie_url'] = 'http://youtu.be/hLpfLOkcX4M';
			//$_POST['image_1_delete'] = 1;

			//$_POST['id_select'] = '1_test/-*-/3_gonz_123/-*-/1_bbb';

			//$_POST['id_select_hardware_no_1'] = 1;
			//$_POST['id_select_1'] = 'game';
			//$_POST['id_select_hardware_no_2'] = 2;
			//$_POST['id_select_2'] = 'XboxID';
			//$_POST['id_select_hardware_no_3'] = 3;
			//$_POST['id_select_3'] = 'No_3';

			//$_POST['id_input_hardware_no_1'] = '';
			//$_POST['id_input_1'] = 'naked_id';

			//$_POST['info_title_1'] = '情報タイトル1';
			//$_POST['info_1'] = '情報1';
			//$_POST['info_title_2'] = '情報タイトル2';
			//$_POST['info_2'] = '情報2';
			//$_POST['info_title_5'] = '情報タイトル5';
			//$_POST['info_5'] = '情報5';

			$_POST['open_type'] = 1;

			//$_POST['limit_days'] = 1;
			//$_POST['limit_hours'] = '';
			//$_POST['limit_minutes'] = '';

			//$_POST['close'] = 1;

			//$_POST['twitter'] = true;
			*/


			/*
			$_POST['form_type'] = 'reply_edit';

			$_POST['recruitment_id'] = 'kbz80ml1uxwhn471';
			$_POST['recruitment_reply_id'] = '17qwioapt94hlcyd';

			$_POST['game_no'] = 1;
			$_POST['handle_name'] = 'ハンドルネーム';
			$_POST['comment'] = 'パーフェクト2';
			//$_POST['anonymity'] = true;

			$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
			//$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
			//$_POST['image_1_delete'] = 1;

			//$_POST['id_select_hardware_no_1'] = 4;
			//$_POST['id_select_1'] = 'bloodborne1979';
			//$_POST['id_select_hardware_no_2'] = 5;
			//$_POST['id_select_2'] = 'dorayaki51';
			//$_POST['id_select_hardware_no_3'] = 5;
			//$_POST['id_select_3'] = 'No_3';

			//$_POST['id_input_hardware_no_1'] = '8';
			//$_POST['id_input_1'] = 'haguremetaru_id';

			//$_POST['info_title_1'] = '返信情報タイトル1';
			//$_POST['info_1'] = '返信情報1';
			//$_POST['info_title_2'] = '返信情報タイトル2';
			//$_POST['info_2'] = '返信情報2';
			//$_POST['info_title_5'] = '情報タイトル5';
			//$_POST['info_5'] = '情報5';

			$_POST['open_type'] = 1;

			// $_POST['limit_days'] = 1;
			// $_POST['limit_hours'] = '';
			// $_POST['limit_minutes'] = '';

			$_POST['twitter'] = true;
			*/



			$_POST['form_type'] = 'reply_new';

			$_POST['recruitment_id'] = 'rutdebi6xfuxbaz9';
			//$_POST['recruitment_reply_id'] = 'eswfz96293ym9wse';

			$_POST['game_no'] = 2;
			$_POST['handle_name'] = 'Twitterメッセージテスト';
			$_POST['comment'] = 'Twitterメッセージテスト3';
			//$_POST['anonymity'] = true;

			//$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
			//$_POST['image_1_delete'] = 1;

			//$_POST['id_select_hardware_no_1'] = 8;
			//$_POST['id_select_1'] = 'alpha';
			//$_POST['id_select_hardware_no_2'] = 5;
			//$_POST['id_select_2'] = 'dorayaki51';
			//$_POST['id_select_hardware_no_3'] = 5;
			//$_POST['id_select_3'] = 'No_3';

			//$_POST['id_input_hardware_no_1'] = '8';
			//$_POST['id_input_1'] = 'haguremetaru_id';

			//$_POST['info_title_1'] = '返信情報タイトル1';
			//$_POST['info_1'] = '返信情報1';
			//$_POST['info_title_2'] = '返信情報タイトル2';
			//$_POST['info_2'] = '返信情報2';
			//$_POST['info_title_5'] = '情報タイトル5';
			//$_POST['info_5'] = '情報5';

			$_POST['open_type'] = 1;

			// $_POST['limit_days'] = 1;
			// $_POST['limit_hours'] = '';
			// $_POST['limit_minutes'] = '';

			//$_POST['specific_recruitment_reply_id'] = 'j7qqz7jb3xkqby4k';

			//$_POST['twitter'] = true;

		}


		// --------------------------------------------------
		//    管理者の場合は、ハンドルネームを入力できるようにする
		// --------------------------------------------------

		if (\Auth::member(100)) $this->user_no = null;



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
				$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。' . $cookie_csrf_token;
				throw new Exception('Error');
			}


			// --------------------------------------------------
			//   掲載できるIDの数は合計で3つまでです。
			// --------------------------------------------------

			$id_total_arr = array();
			$run_arr = array();

			if (Input::post('id_select'))
			{
				$id_select_arr = explode('/-*-/', Input::post('id_select'));

				foreach ($id_select_arr as $key => $value)
				{
					$select_exploded_arr = explode('_', $value);

					// ハードウェアNo
					$select_hardware_no = $select_exploded_arr[0];

					// IDにコンマが入ってる場合は結合する
					$select_id_arr = array_slice($select_exploded_arr, 1);
					$select_id = implode('_', $select_id_arr);

					// 可変変数使用
					$variable_name = "id_select_hardware_no_" . ($key + 1);
					${$variable_name} = $select_hardware_no;

					$run_arr[$variable_name] = ${$variable_name};
					//array_push($run_arr, array())

					$variable_name = "id_select_" . ($key + 1);
					${$variable_name} = $select_id;

					$run_arr[$variable_name] = ${$variable_name};

					// IDの数計算用
					array_push($id_total_arr, $select_id);



					//${$variable_name}


					// echo '$select_exploded_arr';
					// var_dump($select_exploded_arr);
//
					// echo '$select_id';
					// var_dump($select_id);
//
					// echo '$key';
					// var_dump($key);
//
					// echo '$value';
					// var_dump($value);

				}

				//var_dump($id_total_arr, $id_select_arr);
			}

			// $run_arr = array(
				// 'community_id' => $community_id
			// );


			// if (isset($id_select_1)) var_dump($id_select_hardware_no_1, $id_select_1);
			// if (isset($id_select_2)) var_dump($id_select_hardware_no_2, $id_select_2);
			// if (isset($id_select_3)) var_dump($id_select_hardware_no_3, $id_select_3);


			if (Input::post('id_input_1')) array_push($id_total_arr, Input::post('id_input_1'));
			if (Input::post('id_input_2')) array_push($id_total_arr, Input::post('id_input_2'));
			if (Input::post('id_input_3')) array_push($id_total_arr, Input::post('id_input_3'));

			if (count($id_total_arr) > 3)
			{
				$arr['alert_color'] = 'danger';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '掲載できるIDの数は合計で3つまでです。';
				throw new Exception('Error');
			}

			//var_dump($id_total_arr, $run_arr);



			// --------------------------------------------------
			//   $form_type
			// --------------------------------------------------

			$form_type = Input::post('form_type');


			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_Common');


			// ------------------------------
			//    共通
			// ------------------------------

			$val->add_field('game_no', 'Game No', 'required|check_game_no');
			if ( ! $this->user_no) $val->add_field('handle_name', 'ハンドルネーム', 'required|min_length[1]|max_length[50]');
			$val->add_field('comment', 'コメント', 'required|min_length[1]|max_length[1000]');
			$val->add_field('movie_url', 'Movie URL', 'valid_url|check_movie_url');

			for ($i=1; $i <= 3; $i++) {
				$val->add_field('id_select_hardware_no_' . $i, 'id_select_hardware_no_' . $i, 'check_hardware_no');
				$val->add_field('id_select_' . $i, 'id_select_' . $i, 'min_length[1]|max_length[100]');
			}

			for ($i=1; $i <= 3; $i++) {
				$val->add_field('id_input_hardware_no_' . $i, 'id_input_hardware_no_' . $i, 'check_hardware_no');
				$val->add_field('id_input_' . $i, 'id_input_' . $i, 'min_length[1]|max_length[100]');
			}

			for ($i=1; $i <= 5; $i++) {
				$val->add_field('info_title_' . $i, 'info_title_' . $i, 'min_length[1]|max_length[50]');
				$val->add_field('info_' . $i, 'info_' . $i, 'min_length[1]|max_length[100]');
			}

			$val->add_field('open_type', 'Open Type', 'required|valid_string[numeric]|numeric_between[1,3]');


			// ------------------------------
			//    募集
			// ------------------------------

			$go_update = false;

			if ($form_type == 'recruitment_new' or $form_type == 'recruitment_edit')
			{
				if (Input::post('recruitment_id'))
				{
					$val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id');
					$go_update = true;
				}

				$val->add_field('type', 'Type', 'required|valid_string[numeric]|numeric_between[1,5]');
				//if (Input::post('type') == 5) $val->add_field('etc_title', 'その他の募集タイトル', 'required|min_length[1]|max_length[50]');
				$val->add_field('etc_title', '募集タイトル', 'required|min_length[1]|max_length[50]');

				$val->add_field('limit_days', '募集期間 日時', 'valid_string[numeric]|numeric_between[1,365]');
				$val->add_field('limit_hours', '募集期間 時間', 'valid_string[numeric]|numeric_between[1,24]');
				$val->add_field('limit_minutes', '募集期間 分', 'valid_string[numeric]|numeric_between[1,59]');
			}


			// ------------------------------
			//    返信
			// ------------------------------

			else if ($form_type == 'reply_new' or $form_type == 'reply_edit')
			{
				$val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id');

				if (Input::post('recruitment_reply_id')) $val->add_field('recruitment_reply_id', 'recruitment_reply_id', 'required|check_recruitment_reply_id');
				if ($form_type == 'reply_edit') $go_update = true;
				if (Input::post('specific_recruitment_reply_id')) $val->add_field('specific_recruitment_reply_id', 'specific_recruitment_reply_id', 'required|check_recruitment_reply_id');
			}
			else
			{
				throw new Exception('Error');
			}


			if ($val->run($run_arr))
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_game_no = $val->validated('game_no');
				$validated_recruitment_id = $val->validated('recruitment_id');
				$validated_recruitment_reply_id = $val->validated('recruitment_reply_id');
				$validated_type = $val->validated('type');
				$validated_handle_name = ($val->validated('handle_name')) ? $val->validated('handle_name') : null;
				$validated_etc_title = ($val->validated('etc_title')) ? $val->validated('etc_title') : null;
				$validated_comment = $val->validated('comment');
				$validated_anonymity = (Input::post('anonymity')) ? 1 : null;

				$validated_image_1_delete = (Input::post('image_1_delete')) ? 1 : null;
				$validated_movie_url = $val->validated('movie_url');

				for ($i=1; $i <= 3; $i++) {
					$variable_name = "validated_id_select_hardware_no_" . $i;
					${$variable_name} = $val->validated('id_select_hardware_no_' . $i);

					$variable_name = "validated_id_select_" . $i;
					${$variable_name} = $val->validated('id_select_' . $i);
				}

				for ($i=1; $i <= 3; $i++) {
					$variable_name = "validated_id_input_hardware_no_" . $i;
					${$variable_name} = $val->validated('id_input_hardware_no_' . $i);

					$variable_name = "validated_id_input_" . $i;
					${$variable_name} = $val->validated('id_input_' . $i);
				}

				for ($i=1; $i <= 5; $i++) {
					$variable_name = "validated_info_title_" . $i;
					${$variable_name} = $val->validated('info_title_' . $i);

					$variable_name = "validated_info_" . $i;
					${$variable_name} = $val->validated('info_' . $i);
				}

				$validated_open_type = $val->validated('open_type');

				$validated_limit_days = $val->validated('limit_days');
				$validated_limit_hours = $val->validated('limit_hours');
				$validated_limit_minutes = $val->validated('limit_minutes');

				$validated_twitter = (Input::post('twitter')) ? 1: null;

				$validated_specific_recruitment_reply_id = ($val->validated('specific_recruitment_reply_id')) ? $val->validated('specific_recruitment_reply_id') : null;

				$validated_close = (Input::post('close')) ? 1: null;
				//echo 'aaa';
				//exit();


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_gc = new Model_Gc();
				$model_gc->agent_type = $this->agent_type;
				$model_gc->user_no = $this->user_no;
				$model_gc->language = $this->language;
				$model_gc->uri_base = $this->uri_base;
				$model_gc->uri_current = $this->uri_current;

				$model_game = new Model_Game();
				$model_game->agent_type = $this->agent_type;
				$model_game->user_no = $this->user_no;
				$model_game->language = $this->language;
				$model_game->uri_base = $this->uri_base;
				$model_game->uri_current = $this->uri_current;

				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$model_present = new Model_Present();
				$model_present->agent_type = $this->agent_type;
				$model_present->user_no = $this->user_no;
				$model_present->language = $this->language;
				$model_present->uri_base = $this->uri_base;
				$model_present->uri_current = $this->uri_current;

				$model_notifications = new Model_Notifications();
				$model_notifications->agent_type = $this->agent_type;
				$model_notifications->user_no = $this->user_no;
				$model_notifications->language = $this->language;
				$model_notifications->uri_base = $this->uri_base;
				$model_notifications->uri_current = $this->uri_current;

				$model_common = new Model_Common();
				$model_common->user_no = $this->user_no;

				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;

				$original_func_gc = new Original\Func\Gc();
				$original_func_gc->app_mode = $this->app_mode;
				$original_func_gc->agent_type = $this->agent_type;
				$original_func_gc->user_no = $this->user_no;
				$original_func_gc->language = $this->language;
				$original_func_gc->uri_base = $this->uri_base;
				$original_func_gc->uri_current = $this->uri_current;

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;

				$original_common_text = new Original\Common\Text();



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_user_data_arr = $model_user->get_login_user_data($validated_game_no);
				$db_users_game_community_arr = $login_user_data_arr[0];
				$login_profile_data_arr = $login_user_data_arr[1];



				// --------------------------------------------------
				//    編集する場合　データ取得　画像・動画配列作成
				// --------------------------------------------------

				$image_arr = null;
				$movie_arr = null;

				if ($validated_recruitment_id)
				{
					$db_recruitment_arr = $model_gc->get_recruitment_appoint($validated_recruitment_id);
				}

				if ($validated_recruitment_reply_id)
				{
					$db_recruitment_reply_arr = $model_gc->get_recruitment_reply_appoint($validated_recruitment_reply_id);
				}

				if ($form_type == 'recruitment_new' or $form_type == 'recruitment_edit')
				{
					$image_arr = (isset($db_recruitment_arr['image'])) ? $db_recruitment_arr['image'] : null;
					$movie_arr = (isset($db_recruitment_arr['movie'])) ? $db_recruitment_arr['movie'] : null;
				}
				else
				{
					$image_arr = (isset($db_recruitment_reply_arr['image'])) ? $db_recruitment_reply_arr['image'] : null;
					$movie_arr = (isset($db_recruitment_reply_arr['movie'])) ? $db_recruitment_reply_arr['movie'] : null;
				}
				// echo '$movie_arr';
				// var_dump($movie_arr);
				// exit();

				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				$datetime_past = new DateTime('-30 minutes');

				if ($form_type == 'recruitment_edit')
				{
					$author_recruitment_arr = (Session::get('author_recruitment_arr')) ? Session::get('author_recruitment_arr') : array();
					$datetime_renewal = new DateTime($db_recruitment_arr['renewal_date']);

					if (isset($this->user_no) and $db_recruitment_arr['user_no'] == $this->user_no)
					{
						$authority_edit = true;
					}
					else if ($datetime_renewal > $datetime_past and in_array($validated_recruitment_id, $author_recruitment_arr))
					{
						$authority_edit = true;
					}
				}
				else if ($form_type == 'reply_edit')
				{
					$author_reply_arr = (Session::get('author_reply_arr')) ? Session::get('author_reply_arr') : array();
					$datetime_renewal = new DateTime($db_recruitment_reply_arr['renewal_date']);

					if (isset($this->user_no) and $db_recruitment_reply_arr['user_no'] == $this->user_no)
					{
						$authority_edit = true;
					}
					else if ($datetime_renewal > $datetime_past and in_array($validated_recruitment_reply_id, $author_reply_arr))
					{
						$authority_edit = true;
					}
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ($form_type == 'recruitment_edit' or $form_type == 'reply_edit')
				{
					if ( ! $authority_edit)
					{
						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = '権限がありません。';
						throw new Exception('Error');
					}
				}



				// --------------------------------------------------
				//   削除済みユーザー
				// --------------------------------------------------

				if ($this->user_no and ! $login_profile_data_arr)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '削除済みユーザーでは書き込みできません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   画像検証
				// --------------------------------------------------

				$image_name_arr = array('image_1');
				$result_check_upload_image_arr = $original_func_common->check_upload_image($image_name_arr, Config::get('limit_recruitment_image'));

				if ($result_check_upload_image_arr[1])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'アップロードされた画像に問題があります。';
					throw new Exception('Error');
				}
				else
				{
					$uploaded_image_existence = $result_check_upload_image_arr[0];
				}




				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();


				// ------------------------------
				//    募集のみ
				// ------------------------------

				if ($form_type == 'recruitment_new' or $form_type == 'recruitment_edit')
				{
					$save_arr['type'] = (int) $validated_type;
					$save_arr['etc_title'] = $validated_etc_title;

					//$validated_limit_days = '';
					//$validated_limit_hours = '1';
					//$validated_limit_minutes = '10';
					// 募集期間
					if ($validated_close)
					{
						$save_arr['limit_date'] = $original_common_date->sql_format('-1 minute');
					}
					else if (! $validated_limit_days and ! $validated_limit_hours and ! $validated_limit_minutes)
					{
						$save_arr['limit_date'] = null;
					}
					else
					{
						$modify_string = '';
						if ($validated_limit_days) $modify_string .= '+' . $validated_limit_days . ' days';
						if ($validated_limit_hours) $modify_string .= ' +' . $validated_limit_hours . ' hours';
						if ($validated_limit_minutes) $modify_string .= ' +' . $validated_limit_minutes . ' minutes';
						if ($modify_string) $save_arr['limit_date'] = $original_common_date->sql_format($modify_string);
					}

				}

				// ------------------------------
				//    返信のみ
				// ------------------------------

				else
				{
					$save_arr['recruitment_id'] = $validated_recruitment_id;
				}


				// ------------------------------
				//    新規の時のみ
				// ------------------------------

				if ( ! $go_update)
				{
					if ($form_type == 'recruitment_new')
					{
						$save_arr['recruitment_id'] = $original_common_text->random_text_lowercase(16);
					}
					else if ($form_type == 'reply_new')
					{
						$save_arr['recruitment_reply_id'] = $original_common_text->random_text_lowercase(16);
					}

					$save_arr['regi_date'] = $datetime_now;
					$save_arr['language'] = 'ja';
					$save_arr['game_no'] = (int) $validated_game_no;

					if (isset($login_profile_data_arr['profile_no']))
					{
						$save_arr['user_no'] = (int) $login_profile_data_arr['author_user_no'];
						$save_arr['profile_no'] = (int) $login_profile_data_arr['profile_no'];
					}
					else if (isset($login_profile_data_arr['user_no']))
					{
						$save_arr['user_no'] = (int) $login_profile_data_arr['user_no'];
					}
				}


				$save_arr['renewal_date'] = $datetime_now;
				$save_arr['sort_date'] = $datetime_now;
				if ($validated_handle_name) $save_arr['handle_name'] = $validated_handle_name;
				$save_arr['anonymity'] = $validated_anonymity;
				$save_arr['comment'] = $validated_comment;

				// 動画
				if ($validated_movie_url)
				{
					//var_dump($movie_arr);
					//if ($movie_arr) $movie_arr = unserialize($movie_arr);
					$movie_arr = null;
					//var_dump($movie_arr);
					$movie_arr = $original_func_common->return_movie(array($validated_movie_url), $movie_arr, Config::get('limit_recruitment_movie'));
					//var_dump($movie_arr);

					$save_arr['movie'] = ($movie_arr) ? serialize($movie_arr) : null;
					//$save_arr['movie'] = serialize($movie_arr);
					//var_dump($movie_arr, $validated_movie_url, $save_arr);
					//exit();
				}
				else
				{
					$save_arr['movie'] = null;
				}


				// ID
				$id_arr = array();
				$id_check_duplication_arr = array();

				for ($i=1; $i <= 3; $i++) {

					// select
					$variable_name_hardware_no = "validated_id_select_hardware_no_" . $i;
					$variable_name_id = "validated_id_select_" . $i;

					// 重複防止用
					$check_duplication_str = ${$variable_name_hardware_no} . ${$variable_name_id};

					if ($check_duplication_str and ! in_array($check_duplication_str, $id_check_duplication_arr))
					{

						if (${$variable_name_hardware_no} and ${$variable_name_id})
						{
							//if (isset($id_arr['']))
							array_push($id_arr, array('hardware_no' => ${$variable_name_hardware_no}, 'id' => ${$variable_name_id}));
							//var_dump(${$variable_name_hardware_no}, ${$variable_name_id});
						}
						else if (${$variable_name_id})
						{
							array_push($id_arr, array('hardware_no' => null, 'id' => ${$variable_name_id}));
						}

						array_push($id_check_duplication_arr, $check_duplication_str);

					}

					//var_dump($check_duplication_str, $id_check_duplication_arr);


					// input
					$variable_name_hardware_no = "validated_id_input_hardware_no_" . $i;
					$variable_name_id = "validated_id_input_" . $i;

					// 重複防止用
					$check_duplication_str = ${$variable_name_hardware_no} . ${$variable_name_id};

					if ($check_duplication_str and ! in_array($check_duplication_str, $id_check_duplication_arr))
					{

						if (${$variable_name_hardware_no} and ${$variable_name_id})
						{
							array_push($id_arr, array('hardware_no' => ${$variable_name_hardware_no}, 'id' => ${$variable_name_id}));
							//var_dump(${$variable_name_hardware_no}, ${$variable_name_id});
						}
						else if (${$variable_name_id})
						{
							array_push($id_arr, array('hardware_no' => null, 'id' => ${$variable_name_id}));
						}

						array_push($id_check_duplication_arr, $check_duplication_str);

					}

					//var_dump($check_duplication_str, $id_check_duplication_arr);

				}

				for ($i=0; $i < 3; $i++)
				{
					$save_arr['id_hardware_no_' . ($i + 1)] = (isset($id_arr[$i]['hardware_no'])) ? $id_arr[$i]['hardware_no'] : null;
					$save_arr['id_' . ($i + 1)] = (isset($id_arr[$i]['id'])) ? $id_arr[$i]['id'] : null;
				}

				// foreach ($id_arr as $key => $value) {
					// $save_arr['id_hardware_no_' . ($key + 1)] = $value['hardware_no'];
					// $save_arr['id_' . ($key + 1)] = $value['id'];
				// }

				//var_dump($id_arr, $save_arr);


				// 情報
				$info_arr = array();

				for ($i=1; $i <= 5; $i++) {

					$variable_name_title = "validated_info_title_" . $i;
					$variable_name_info = "validated_info_" . $i;

					if (${$variable_name_title} and ${$variable_name_info})
					{
						array_push($info_arr, array('title' => ${$variable_name_title}, 'info' => ${$variable_name_info}));
						//var_dump(${$variable_name_title}, ${$variable_name_info});
					}

				}

				foreach ($info_arr as $key => $value) {
					$save_arr['info_title_' . ($key + 1)] = $value['title'];
					$save_arr['info_' . ($key + 1)] = $value['info'];
				}


				$save_arr['open_type'] = $validated_open_type;

				$save_arr['host'] = $this->host;
				$save_arr['user_agent'] = $this->user_agent;




				// --------------------------------------------------
				//    データベース挿入　新規登録　二重書き込み防止機能あり
				// --------------------------------------------------

				if ( ! $go_update)
				{
					$language = 'ja';


					// ------------------------------
					//    返信
					// ------------------------------

					if (isset($save_arr['recruitment_id'], $save_arr['recruitment_reply_id']))
					{

						$result_insert = $model_gc->insert_recruitment_reply($language, $save_arr);


						// 編集権限用　セッション
						if ( ! $this->user_no)
						{
							$author_reply_arr = Session::get('author_reply_arr');

							if ($author_reply_arr)
							{
								array_push($author_reply_arr, $save_arr['recruitment_reply_id']);
								Session::set('author_reply_arr', $author_reply_arr);
							}
							else
							{
								Session::set('author_reply_arr', array($save_arr['recruitment_reply_id']));
							}
						}


						// 書き込んだユーザー
						if (isset($this->user_no) and $this->user_no != $db_recruitment_arr['user_no'])
						{

							$write_users_arr = ($db_recruitment_arr['write_users']) ? unserialize($db_recruitment_arr['write_users']) : array();

							if ( ! in_array($this->user_no, $write_users_arr))
							{
								array_push($write_users_arr, $this->user_no);
							}

							$write_users_arr = array_values($write_users_arr);

							$save_write_users_arr['write_users'] = (count($write_users_arr) > 0) ? serialize($write_users_arr) : null;

							$result_arr = $model_gc->update_recruitment_only($validated_recruitment_id, $save_write_users_arr);

							//var_dump($save_write_users_arr);

						}


						// --------------------------------------------------
						//   プレゼント抽選エントリーポイント　＋
						// --------------------------------------------------

						// $present_entry_arr = array(
						// 	'regi_date' => $save_arr['regi_date'],
						// 	'user_no' => $this->user_no,
						// 	'type_1' => 'recruitment_reply',
						// 	'type_2' => 'plus',
						// 	'point' => Config::get('present_point_recruitment_reply')
						// );
						//
						// if (isset($save_arr['profile_no'])) $present_entry_arr['profile_no'] = $save_arr['profile_no'];
						//
						// $model_present->plus_minus_point($present_entry_arr);




						// --------------------------------------------------
						//   募集者以外が返信した場合、募集者に対して通知する
						// --------------------------------------------------

						$permission = true;

						// 募集の著者はログインユーザーでなければならない　野良の人には通知できないため
						if ($db_recruitment_arr['user_no'] === null) $permission = false;

						// 募集の著者自身の返信の場合、通知しない
						if ($db_recruitment_arr['user_no'] == $this->user_no) $permission = false;


						if ($permission)
						{

							$recruitument_type = $original_func_gc->get_recruitment_type($language, $db_recruitment_arr['type'], $db_recruitment_arr['etc_title']);

							$save_notifications_arr = array(
								'regi_date' => $datetime_now,
								'target_user_no' => $db_recruitment_arr['user_no'],
								'game_no' => $validated_game_no,
								'type1' => 'gc',
								'type2' => 'recruitment_reply',
								'title' => $recruitument_type,
								'anonymity' => $validated_anonymity,
								'name' => $validated_handle_name,
								'comment' => $validated_comment,
								'recruitment_id' => $validated_recruitment_id
							);

							if (isset($login_profile_data_arr['profile_no'])) $save_notifications_arr['profile_no'] = $login_profile_data_arr['profile_no'];

							$model_notifications->save_notifications($save_notifications_arr);

							//var_dump($db_recruitment_arr);

							//echo '募集者への通知';
							//var_dump($save_notifications_arr);

						}


						// --------------------------------------------------
						//   特定の返信者に向けて返信した場合、その特定の返信者に対して通知する
						//   募集者には返信しない（上の通知と二重になるため）
						// --------------------------------------------------

						//var_dump($validated_notification_recruitment_reply_id);

						// 返信を取得
						if ($validated_specific_recruitment_reply_id)
						{
							$notification_db_recruitment_reply_arr = $model_gc->get_recruitment_reply_appoint_double_check($save_arr['recruitment_id'], $validated_specific_recruitment_reply_id);
							//var_dump($notification_db_recruitment_reply_arr);
						}


						//$notification_db_recruitment_reply_arr['user_no'] = 2;


						$permission = true;

						// 返信が存在している必要がある
						if (isset($notification_db_recruitment_reply_arr))
						{

							// 返信の著者はログインユーザーでなければならない　野良の人には通知できないため
							if ($notification_db_recruitment_reply_arr['user_no'] === null) $permission = false;

							// 返信の著者自身の返信の場合、通知しない
							if ($notification_db_recruitment_reply_arr['user_no'] == $this->user_no) $permission = false;

							// 募集の著者には通知しない　上の通知と同じ内容で二重になってしまうため
							if ($notification_db_recruitment_reply_arr['user_no'] == $db_recruitment_arr['user_no']) $permission = false;

						}
						else
						{
							$permission = false;
						}
						//var_dump($permission);
						//exit();

						if ($permission)
						{

							$recruitument_type = $original_func_gc->get_recruitment_type($language, $db_recruitment_arr['type'], $db_recruitment_arr['etc_title']);

							$save_notifications_arr = array(
								'regi_date' => $datetime_now,
								'target_user_no' => $notification_db_recruitment_reply_arr['user_no'],
								'game_no' => $validated_game_no,
								'type1' => 'gc',
								'type2' => 'recruitment_reply',
								'title' => $recruitument_type,
								'anonymity' => $validated_anonymity,
								'name' => $validated_handle_name,
								'comment' => $validated_comment,
								'recruitment_id' => $validated_recruitment_id
							);

							if (isset($login_profile_data_arr['profile_no'])) $save_notifications_arr['profile_no'] = $login_profile_data_arr['profile_no'];

							$model_notifications->save_notifications($save_notifications_arr);

							//echo '特定の返信者への通知';
							//var_dump($save_notifications_arr);

						}

						//exit();

					}

					// ------------------------------
					//    募集
					// ------------------------------

					else
					{

						$result_insert = $model_gc->insert_recruitment($language, $save_arr);

						// 編集権限用　セッション
						if ( ! $this->user_no)
						{
							$author_recruitment_arr = Session::get('author_recruitment_arr');

							if ($author_recruitment_arr)
							{
								array_push($author_recruitment_arr, $save_arr['recruitment_id']);
								Session::set('author_recruitment_arr', $author_recruitment_arr);
							}
							else
							{
								Session::set('author_recruitment_arr', array($save_arr['recruitment_id']));
							}
						}



						// --------------------------------------------------
						//   プレゼント抽選エントリーポイント　＋
						// --------------------------------------------------

						// $present_entry_arr = array(
						// 	'regi_date' => $save_arr['regi_date'],
						// 	'user_no' => $this->user_no,
						// 	'type_1' => 'recruitment',
						// 	'type_2' => 'plus',
						// 	'point' => Config::get('present_point_recruitment')
						// );
						//
						// if (isset($save_arr['profile_no'])) $present_entry_arr['profile_no'] = $save_arr['profile_no'];
						//
						// $model_present->plus_minus_point($present_entry_arr);



						// --------------------------------------------------
						//   お知らせ保存
						// --------------------------------------------------

						$recruitument_type = $original_func_gc->get_recruitment_type($language, $validated_type, $validated_etc_title);

						$save_notifications_arr = array(
							'regi_date' => $datetime_now,
							'target_user_no' => null,
							'game_no' => $validated_game_no,
							'type1' => 'gc',
							'type2' => 'recruitment',
							'title' => $recruitument_type,
							'anonymity' => $validated_anonymity,
							'name' => $validated_handle_name,
							'comment' => $validated_comment,
							'recruitment_id' => $save_arr['recruitment_id']
						);

						if (isset($login_profile_data_arr['profile_no'])) $save_notifications_arr['profile_no'] = $login_profile_data_arr['profile_no'];
						//var_dump($save_notifications_arr);
						$model_notifications->save_notifications($save_notifications_arr);



						// --------------------------------------------------
						//    SNS　データベース保存
						// --------------------------------------------------

						if ( ! $validated_twitter)
						{
							$original_common_text = new \Original\Common\Text();
							$send_sns_id = $original_common_text->random_text_lowercase(16);
							$approval = (\Auth::member(100)) ? 1 : null;

							$save_send_sns_arr[0] = array(
								'send_sns_id' => $send_sns_id,
								'regi_date' => $datetime_now,
								'approval' => $approval,
								'type' => 'gc_recruitment',
								'game_no' => $validated_game_no,
								'recruitment_id' => $save_arr['recruitment_id']
							);

							$model_sns = new \Model_Sns();
							$model_sns->insert_send_sns($save_send_sns_arr);
						}

					}


					//$result_insert = true;

					if (isset($test))
					{
						echo '$result_insert';
						var_dump($result_insert);
					}




					// アップロードされた画像がある場合、更新へ
					if ($result_insert and $uploaded_image_existence)
					{
						$go_update = true;

						// 新規挿入した後、募集IDを設定
						$validated_recruitment_id = $save_arr['recruitment_id'];
					}
					else if ( ! $result_insert)
					{
						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = '二重書き込みです。';
						throw new Exception('Error');
					}

					//$code_output = true;



//
					// $original_func_common->save_notifications(array(
						// 'regi_date' => $datetime_now,
						// 'target_user_no' => null,
						// 'community_no' => $db_community_arr['community_no'],
						// 'game_no' => $game_list_arr[0],
						// 'type1' => 'uc',
						// 'type2' => 'bbs_thread',
						// 'title' => $validated_title,
						// 'anonymity' => $validated_anonymity,
						// 'name' => $handle_name,
						// 'comment' => $validated_comment,
						// 'bbs_thread_no' => $notifications_bbs_thread_no
					// ));

				}




				if (isset($test))
				{
					/*
					echo "バリデーション";

					echo '$validated_game_no';
					var_dump($validated_game_no);

					echo '$validated_recruitment_id';
					var_dump($validated_recruitment_id);

					echo '$validated_recruitment_reply_id';
					var_dump($validated_recruitment_reply_id);

					echo '$validated_type';
					var_dump($validated_type);

					echo '$validated_handle_name';
					var_dump($validated_handle_name);

					echo '$validated_comment';
					var_dump($validated_comment);

					echo '$validated_anonymity';
					var_dump($validated_anonymity);

					echo '$validated_movie_url';
					var_dump($validated_movie_url);

					echo '$validated_image_1_delete';
					var_dump($validated_image_1_delete);


					echo '$validated_id_select_hardware_no_1';
					var_dump($validated_id_select_hardware_no_1);

					echo '$validated_id_select_1';
					var_dump($validated_id_select_1);

					echo '$validated_id_select_hardware_no_2';
					var_dump($validated_id_select_hardware_no_2);

					echo '$validated_id_select_2';
					var_dump($validated_id_select_2);

					echo '$validated_id_select_hardware_no_3';
					var_dump($validated_id_select_hardware_no_3);

					echo '$validated_id_select_3';
					var_dump($validated_id_select_3);


					echo '$validated_info_title_1';
					var_dump($validated_info_title_1);

					echo '$validated_info_1';
					var_dump($validated_info_1);

					echo '$validated_info_title_2';
					var_dump($validated_info_title_2);

					echo '$validated_info_2';
					var_dump($validated_info_2);

					echo '$validated_info_title_3';
					var_dump($validated_info_title_3);

					echo '$validated_info_3';
					var_dump($validated_info_3);

					echo '$validated_info_title_4';
					var_dump($validated_info_title_4);

					echo '$validated_info_4';
					var_dump($validated_info_4);

					echo '$validated_info_title_5';
					var_dump($validated_info_title_5);

					echo '$validated_info_5';
					var_dump($validated_info_5);


					echo '$validated_limit_days';
					var_dump($validated_limit_days);

					echo '$validated_limit_hours';
					var_dump($validated_limit_hours);

					echo '$validated_limit_minutes';
					var_dump($validated_limit_minutes);


					echo '$validated_twitter';
					var_dump($validated_twitter);


					echo '$db_users_game_community_arr';
					var_dump($db_users_game_community_arr);

					echo '$login_profile_data_arr';
					var_dump($login_profile_data_arr);


					echo '$save_arr';
					var_dump($save_arr);
					 */
				}

				//exit();



				// --------------------------------------------------
				//    データベース更新　新規挿入後、画像を追加、または編集する場合
				// --------------------------------------------------

				if ($go_update)
				{
					//var_dump($save_arr);
					// --------------------------------------------------
					//   画像設定
					// --------------------------------------------------

					// 保存先パス設定
					if ($form_type == 'recruitment_new' or $form_type == 'recruitment_edit')
					{
						$path = DOCROOT . 'assets/img/recruitment/recruitment/' . $validated_recruitment_id . '/';
					}
					else
					{
						if ( ! $validated_recruitment_reply_id) $validated_recruitment_reply_id = $save_arr['recruitment_reply_id'];
						$path = DOCROOT . 'assets/img/recruitment/reply/' . $validated_recruitment_reply_id . '/';
					}

					//exit();

					// --------------------------------------------------
					//   画像削除
					// --------------------------------------------------

					if ($validated_image_1_delete)
					{
						$original_func_common->image_delete($path, 'image_', array(1));
						$save_arr['image'] = null;
					}


					// --------------------------------------------------
					//   画像保存
					// --------------------------------------------------

					if ($uploaded_image_existence)
					{

						$image_name_arr = array('image_1');
						$result_upload_image_arr = $original_func_common->zebra_image_save2($path, $image_name_arr, true, Config::get('limit_recruitment_image'));

						if (isset($test))
						{
							echo "count_FILES";
							var_dump(count($_FILES));
							echo "<br>result_upload_image_arr";
							var_dump($result_upload_image_arr);
						}

						if ($result_upload_image_arr['error'])
						{
							$arr['alert_color'] = 'danger';
							$arr['alert_title'] = 'エラー';
							$arr['alert_message'] = 'アップロードされた画像に問題があります。';
							throw new Exception('Error');
						}
						else if ($result_upload_image_arr['size_arr'])
						{
							$save_arr['image'] = serialize($result_upload_image_arr['size_arr']);
						}

					}

					//var_dump($validated_recruitment_reply_id, $validated_game_no, $save_arr);
					//exit();

					// --------------------------------------------------
					//    データベース更新
					// --------------------------------------------------

					// 返信
					if (isset($save_arr['recruitment_id']) and $validated_recruitment_reply_id)
					{
						$result_update = $model_gc->update_recruitment_reply($validated_recruitment_reply_id, $validated_game_no, $save_arr);
					}
					// 募集
					else
					{
						$result_update = $model_gc->update_recruitment($validated_recruitment_id, $validated_game_no, $save_arr);
					}


					if (isset($test))
					{
						echo '$result_update';
						var_dump($result_update);
					}

					//$code_output = true;

				}


				// --------------------------------------------------
				//   言語固定　多言語化の際に変更
				// --------------------------------------------------

				$language = 'ja';



				// --------------------------------------------------
				//   Twitter
				// --------------------------------------------------

				if ($validated_twitter)
				{

					// --------------------------------------------------
					//    コンシューマーキー取得
					// --------------------------------------------------

					$consumer_key = Config::get('twitter_consumer_key');
					$consumer_secret = Config::get('twitter_consumer_secret');


					// --------------------------------------------------
					//    アクセストークン取得
					// --------------------------------------------------

					$result_twitter_arr = $model_user->get_twitter_access_token($this->user_no);
					//$result_twitter_arr = $model_user->get_twitter_access_token(null);
					$access_token = $result_twitter_arr['access_token'];
					$access_token_secret = $result_twitter_arr['access_token_secret'];

					//$validated_recruitment_id = 'baeibjaoebajo23';



					// --------------------------------------------------
					//    メッセージ作成
					// --------------------------------------------------

					$twitter_recruitment_id = (isset($save_arr['recruitment_id'])) ? $save_arr['recruitment_id'] : $validated_recruitment_id;
					if ( ! $validated_type) $validated_type = $db_recruitment_arr['type'];


					$twitter_message_arr = array(
						'message_type' => 'gc_recruitment',
						'form_type' => $form_type,
						'game_no' => $validated_game_no,
						'language' => $language,
						'recruitment_id' => $twitter_recruitment_id,
						'recruitument_type' => $validated_type,
						'comment' => $validated_comment
					);

					$twitter_message = $original_func_common->create_twitter_message($twitter_message_arr);




					// if (isset($validated_recruitment_id))
					// {
						// echo '$validated_recruitment_id';
						// var_dump($validated_recruitment_id);
					// }
//
					// if (isset($save_arr['recruitment_id']))
					// {
						// echo '$save_arr_recruitment_id';
						// var_dump($save_arr['recruitment_id']);
					// }


					// if (USER_NO == 1)
					// {
						// echo '$twitter_message';
						// \Debug::dump($twitter_message);
					// }
//
					// exit();
					//$twitter_message = '募集テスト9';



					//var_dump($consumer_key, $consumer_secret, $access_token, $access_token_secret, $twitter_message);
					//exit();

					// --------------------------------------------------
					//    Tweetする
					// --------------------------------------------------

					if (isset($consumer_key, $consumer_secret, $access_token, $access_token_secret, $twitter_message))
					{
						$original_common_twitter = new Original\Common\Twitter();
						$result_tweet = $original_common_twitter->post_message($consumer_key, $consumer_secret, $access_token, $access_token_secret, $twitter_message);
						//var_dump($result_tweet);
						$arr['tweeted'] = 1;
					}

					// --------------------------------------------------
					//    Tweetする　Twitterの情報が入力されていない場合は、oauthに移行
					// --------------------------------------------------

					else
					{
						// セッション設定
						$db_game_data_arr = $model_game->get_game_data($validated_game_no, null);
						$db_game_data_arr['id'];
						//var_dump($db_game_data_arr['id']);

						Session::set('twitter_message', $twitter_message);
						Session::set('redirect_type', 'gc');
						Session::set('redirect_id', $db_game_data_arr['id']);

						$arr['go_twitter'] = 1;
						//var_dump(Session::get());
					}
					//

				}

				//var_dump($this->agent_type);


				// --------------------------------------------------
				//   利用規約　同意した最新バージョンをセッションに保存
				// --------------------------------------------------

				Session::set('user_terms_approval_version', Config::get('user_terms_version'));


				// --------------------------------------------------
				//   最新の利用規約に同意しているかをチェック、してない場合は保存　ログインユーザーのみ
				// --------------------------------------------------

				$model_common->check_and_update_user_terms();


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_user_data_arr = $model_user->get_login_user_data($validated_game_no);
				$db_users_game_community_arr = $login_user_data_arr[0];
				$login_profile_data_arr = $login_user_data_arr[1];


				// --------------------------------------------------
				//    ハードウェア
				// --------------------------------------------------

				$db_hardware_arr = $model_game->get_hardware_sort($language);



				// --------------------------------------------------
				//    募集
				// --------------------------------------------------

				$code_recruitment_arr = array(
					//'game_id' => $validated_game_id,
					'game_no' => $validated_game_no,
					'recruitment_id' => null,
					'db_users_game_community_arr' => $db_users_game_community_arr,
					'login_profile_data_arr' => $login_profile_data_arr,
					'db_hardware_arr' => $db_hardware_arr,
					'datetime_now' => $datetime_now,
					'more_button' => null,
					'search_type' => null,
					'search_hardware_id_no' => null,
					'search_id_null' => null,
					'search_keyword' => null,
					'page' => 1
				);

				$code_recruitment = $original_code_gc->recruitment($code_recruitment_arr);

				$arr = $code_recruitment;

				//$arr['code'] = $original_code_gc->recruitment($validated_game_no, null, $db_users_game_community_arr, $login_profile_data_arr, $db_hardware_arr, $datetime_now, null, null, null, null, null, 1);


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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//echo $e->getMessage();
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
	* 募集　承認ユーザー
	*
	* @return string HTMLコード
	*/
	public function post_approval_recruitment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['recruitment_id'] = 'rb8s8qmswlgixifb';
			$_POST['user_no'] = 2;
			$_POST['profile_no'] = null;
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
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_User');

			$val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id');

			if (Input::post('user_no'))
			{
				$val->add_field('user_no', 'User No', 'required|check_user_data');
			}
			else if (Input::post('profile_no'))
			{
				$val->add_field('profile_no', 'Profile No', 'required|check_profile');
			}
			else
			{
				throw new Exception('Error');
			}

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_recruitment_id = $val->validated('recruitment_id');
				$validated_user_no = $val->validated('user_no');
				$validated_profile_no = $val->validated('profile_no');



				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_gc = new Model_Gc();
				$model_gc->agent_type = $this->agent_type;
				$model_gc->user_no = $this->user_no;
				$model_gc->language = $this->language;
				$model_gc->uri_base = $this->uri_base;
				$model_gc->uri_current = $this->uri_current;

				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;
				/*
				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;

				$original_common_text = new Original\Common\Text();
				*/


				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				// ------------------------------
				//    募集
				// ------------------------------

				$db_recruitment_arr = $model_gc->get_recruitment_appoint($validated_recruitment_id);


				if ($this->user_no != $db_recruitment_arr['user_no'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// ------------------------------
				//    ユーザーNo
				// ------------------------------

				if ($validated_profile_no)
				{
					$db_profile_arr = $model_user->get_profile($validated_profile_no);
					$save_user_no = $db_profile_arr['author_user_no'];
				}
				else
				{
					$save_user_no = $validated_user_no;
				}

				if ($save_user_no == $db_recruitment_arr['user_no'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '自分は承認できません。';
					throw new Exception('Error');
				}




				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();
				//$save_user_no = 1;

				$approval_users_arr = ($db_recruitment_arr['approval_users']) ? unserialize($db_recruitment_arr['approval_users']) : array();
				//$approval_users_arr = array();

				if (in_array($save_user_no, $approval_users_arr))
				{
					foreach ($approval_users_arr as $key => &$value) {
						if ($save_user_no == $value) unset($approval_users_arr[$key]);
					}
					unset($value);

					$registration = false;
				}
				else
				{
					array_push($approval_users_arr, $save_user_no);

					$registration = true;
				}

				$approval_users_arr = array_values($approval_users_arr);

				$save_arr['approval_users'] = (count($approval_users_arr) > 0) ? serialize($approval_users_arr) : null;



				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result_arr = $model_gc->update_recruitment_only($validated_recruitment_id, $save_arr);



				$arr['label'] = ($registration) ? '<span class="glyphicon glyphicon-exclamation-sign padding_right_5" aria-hidden="true"></span> ID・情報を公開中' : 'ID・情報を公開する';



				if (isset($test))
				{

					//echo "バリデーション";

					echo '$db_recruitment_arr';
					var_dump($db_recruitment_arr);

					echo '$approval_users_arr';
					var_dump($approval_users_arr);

					echo '$save_user_no';
					var_dump($save_user_no);



					echo '$save_arr';
					var_dump($save_arr);
				}

				//exit();



				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				//$arr['alert_color'] = 'success';
				//$arr['alert_title'] = 'OK';
				//$arr['alert_message'] = '承認しました。';


			}
			else
			{
				/*
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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;
				*/
			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//echo $e->getMessage();
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
	* 募集　承認ユーザー
	*
	* @return string HTMLコード
	*/
	public function post_save_ng_user_id()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['type'] = 'save_recruitment';
			$_POST['type'] = 'delete_recruitment';
			$_POST['id'] = 'q2nk1z1kd5lbe551';
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
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_User');

			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(save_recruitment|delete_recruitment|save_reply|delete_reply)$/');


			if (Input::post('type') == 'save_recruitment' or Input::post('type') == 'delete_recruitment')
			{
				$val->add_field('id', 'id', 'required|check_recruitment_id');
			}
			else if (Input::post('type') == 'save_reply' or Input::post('type') == 'delete_reply')
			{
				$val->add_field('id', 'id', 'required|check_recruitment_reply_id');
			}
			else
			{
				throw new Exception('Error');
			}

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_type = $val->validated('type');
				$validated_id = $val->validated('id');



				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_gc = new Model_Gc();
				$model_gc->agent_type = $this->agent_type;
				$model_gc->user_no = $this->user_no;
				$model_gc->language = $this->language;
				$model_gc->uri_base = $this->uri_base;
				$model_gc->uri_current = $this->uri_current;

				$model_game = new Model_Game();
				$model_game->agent_type = $this->agent_type;
				$model_game->user_no = $this->user_no;
				$model_game->language = $this->language;
				$model_game->uri_base = $this->uri_base;
				$model_game->uri_current = $this->uri_current;

				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				// ------------------------------
				//    募集・返信
				// ------------------------------

				if ($validated_type == 'save_recruitment' or $validated_type == 'delete_recruitment')
				{
					$db_data_arr = $model_gc->get_recruitment_appoint($validated_id);
				}
				else if ($validated_type == 'save_reply' or $validated_type == 'delete_reply')
				{
					$db_data_arr = $model_gc->get_recruitment_reply_appoint($validated_id);
				}


				// ------------------------------
				//    保存用データ処理
				// ------------------------------

				// $db_data_arr['profile_no'] = null;
				// $db_data_arr['user_no'] = null;
				// $db_data_arr['id_hardware_no_1'] = 3;
				// $db_data_arr['id_2'] = 'marathon';

				if ($db_data_arr['profile_no'])
				{
					$save_ng_user_arr = array('profile_' . $db_data_arr['profile_no'] => array('regi_date' => $datetime_now, 'game_no' => $db_data_arr['game_no'], 'user_no' => $db_data_arr['user_no'], 'profile_no' => $db_data_arr['profile_no']));
				}
				else if ($db_data_arr['user_no'])
				{
					$save_ng_user_arr = array('user_' . $db_data_arr['user_no'] => array('regi_date' => $datetime_now, 'game_no' => $db_data_arr['game_no'], 'user_no' => $db_data_arr['user_no'], 'profile_no' => $db_data_arr['profile_no']));
				}
				else if ($db_data_arr['id_1'])
				{

					//$save_key_1 = ($db_data_arr['id_hardware_no_1'] and $db_data_arr['id_1']) ? 'h' . $db_data_arr['id_hardware_no_1'] . '_' . $db_data_arr['id_1'] : 'g' . $db_data_arr['game_no'] . '_' . $db_data_arr['id_1'];
					//$save_key_2 = ($db_data_arr['id_hardware_no_2'] and $db_data_arr['id_2']) ? 'h' . $db_data_arr['id_hardware_no_2'] . '_' . $db_data_arr['id_2'] : 'g' . $db_data_arr['game_no'] . '_' . $db_data_arr['id_2'];
					//$save_key_3 = ($db_data_arr['id_hardware_no_3'] and $db_data_arr['id_3']) ? 'h' . $db_data_arr['id_hardware_no_3'] . '_' . $db_data_arr['id_3'] : 'g' . $db_data_arr['game_no'] . '_' . $db_data_arr['id_3'];

					if ($db_data_arr['id_hardware_no_1'] and $db_data_arr['id_1'])
					{
						$save_key_1 = 'h' . $db_data_arr['id_hardware_no_1'] . '_' . $db_data_arr['id_1'];
					}
					else
					{
						$save_key_1 = ($db_data_arr['id_1']) ?  'g' . $db_data_arr['game_no'] . '_' . $db_data_arr['id_1'] : null;
					}

					if ($db_data_arr['id_hardware_no_2'] and $db_data_arr['id_2'])
					{
						$save_key_2 = 'h' . $db_data_arr['id_hardware_no_2'] . '_' . $db_data_arr['id_2'];
					}
					else
					{
						$save_key_2 = ($db_data_arr['id_2']) ?  'g' . $db_data_arr['game_no'] . '_' . $db_data_arr['id_2'] : null;
					}

					if ($db_data_arr['id_hardware_no_3'] and $db_data_arr['id_3'])
					{
						$save_key_3 = 'h' . $db_data_arr['id_hardware_no_3'] . '_' . $db_data_arr['id_3'];
					}
					else
					{
						$save_key_3 = ($db_data_arr['id_3']) ?  'g' . $db_data_arr['game_no'] . '_' . $db_data_arr['id_3'] : null;
					}


					if ($save_key_1) $save_ng_ip_arr[$save_key_1] = array('regi_date' => $datetime_now, 'game_no' => $db_data_arr['game_no'], 'hardware_no' => $db_data_arr['id_hardware_no_1'], 'id' => $db_data_arr['id_1']);
					if ($save_key_2) $save_ng_ip_arr[$save_key_2] = array('regi_date' => $datetime_now, 'game_no' => $db_data_arr['game_no'], 'hardware_no' => $db_data_arr['id_hardware_no_2'], 'id' => $db_data_arr['id_2']);
					if ($save_key_3) $save_ng_ip_arr[$save_key_3] = array('regi_date' => $datetime_now, 'game_no' => $db_data_arr['game_no'], 'hardware_no' => $db_data_arr['id_hardware_no_3'], 'id' => $db_data_arr['id_3']);

				}
				else
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'NGにできません。';
					throw new Exception('Error');
				}


				// ------------------------------
				//    ユーザーデータ
				// ------------------------------

				$db_user_game_community = $model_gc->get_user_game_community();

				$ng_user_arr = ($db_user_game_community['ng_user']) ? unserialize($db_user_game_community['ng_user']) : array();
				$ng_id_arr = ($db_user_game_community['ng_id']) ? unserialize($db_user_game_community['ng_id']) : array();



				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				// ------------------------------
				//    保存
				// ------------------------------

				if ($validated_type == 'save_recruitment' or $validated_type == 'save_reply')
				{
					if (isset($save_ng_user_arr))
					{
						foreach ($save_ng_user_arr as $key => $value) {
							if ( ! array_key_exists($key, $ng_user_arr))
							{
								$ng_user_arr[$key] = $value;
							}
						}
					}

					if (isset($save_ng_ip_arr))
					{
						foreach ($save_ng_ip_arr as $key => $value) {
							if ( ! array_key_exists($key, $ng_id_arr))
							{
								$ng_id_arr[$key] = $value;
							}
						}
					}
				}

				// ------------------------------
				//    削除
				// ------------------------------

				else
				{
					if (isset($save_ng_user_arr))
					{
						foreach ($save_ng_user_arr as $key => $value) {
							if (array_key_exists($key, $ng_user_arr))
							{
								unset($ng_user_arr[$key]);
							}
						}
						//$ng_user_arr = array_values($ng_user_arr);
					}

					if (isset($save_ng_ip_arr))
					{
						foreach ($save_ng_ip_arr as $key => $value) {
							if (array_key_exists($key, $ng_id_arr))
							{
								unset($ng_id_arr[$key]);
							}
						}
						//$ng_id_arr = array_values($ng_id_arr);
					}
				}


				$save_arr['ng_user'] = (count($ng_user_arr) > 0) ? serialize($ng_user_arr) : null;
				$save_arr['ng_id'] = (count($ng_id_arr) > 0) ? serialize($ng_id_arr) : null;



				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result_arr = $model_gc->update_users_game_community($save_arr);





				if (isset($test))
				{

					// echo '$validated_type';
					// var_dump($validated_type);
//
					// echo '$validated_id';
					// var_dump($validated_id);
//
					echo '$db_data_arr';
					var_dump($db_data_arr);

					//echo '$save_key_';
					//var_dump($save_key_1, $save_key_2, $save_key_3);

					if (isset($save_ng_user_arr))
					{
						echo '$save_ng_user_arr';
						var_dump($save_ng_user_arr);
					}

					if (isset($save_ng_ip_arr))
					{
						echo '$save_ng_ip_arr';
						var_dump($save_ng_ip_arr);
					}

					echo '$db_user_game_community';
					var_dump($db_user_game_community);

					echo '$ng_user_arr';
					var_dump($ng_user_arr);

					echo '$ng_id_arr';
					var_dump($ng_id_arr);


					echo '$save_arr';
					var_dump($save_arr);
				}

				//exit();





				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_user_data_arr = $model_user->get_login_user_data($db_data_arr['game_no']);
				$db_users_game_community_arr = $login_user_data_arr[0];
				$login_profile_data_arr = $login_user_data_arr[1];


				// --------------------------------------------------
				//    ハードウェア
				// --------------------------------------------------

				$language = 'ja';
				$db_hardware_arr = $model_game->get_hardware_sort($language);


				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				// --------------------------------------------------
				//    募集
				// --------------------------------------------------
				//var_dump($read_id);
				$code_recruitment_arr = array(
					'game_id' => $game_id,
					'game_no' => $db_data_arr['game_no'],
					'recruitment_id' => $db_data_arr['recruitment_id'],
					'db_users_game_community_arr' => $db_users_game_community_arr,
					'login_profile_data_arr' => $login_profile_data_arr,
					'db_hardware_arr' => $db_hardware_arr,
					'datetime_now' => $datetime_now,
					'more_button' => $more_button,
					'search_type' => null,
					'search_hardware_id_no' => null,
					'search_id_null' => null,
					'search_keyword' => null,
					'page' => 1
				);

				$code_recruitment = $original_code_gc->recruitment($code_recruitment_arr);


				//$code = $original_code_gc->recruitment($db_data_arr['game_no'], $db_data_arr['recruitment_id'], $db_users_game_community_arr, $login_profile_data_arr, $db_hardware_arr, $datetime_now, null, null, null, null, null, null);


				$arr['recruitment_id'] = $db_data_arr['recruitment_id'];
				$arr['code'] = $code_recruitment;



				//$arr['label'] = ($registration) ? '<span class="glyphicon glyphicon-exclamation-sign padding_right_5" aria-hidden="true"></span> ID・情報を公開中' : 'ID・情報を公開する';



				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				if ($validated_type == 'save_recruitment' or $validated_type == 'save_reply')
				{
					$alert_message = 'NGにしました。';
				}
				else
				{
					$alert_message = 'NGを解除しました。';
				}

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = $alert_message;


			}
			else
			{
				/*
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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;
				*/
			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//echo $e->getMessage();
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
	* 募集・返信　削除
	*
	*/
	public function post_delete_recruitment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['recruitment_id'] = 'r0pg5binnda4xj59';
			//$_POST['recruitment_reply_id'] = null;

			$_POST['recruitment_id'] = 'vp5tdxsoi6i4j5xx';
			$_POST['recruitment_reply_id'] = 'pj43qr8yjbs03h4y';
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

			$val->add_callable('Original_Rule_Gc');


			// ------------------------------
			//    共通
			// ------------------------------

			$val->add_field('recruitment_id', 'recruitment_id', 'required|check_recruitment_id');
			if (Input::post('recruitment_reply_id')) $val->add_field('recruitment_reply_id', 'recruitment_reply_id', 'required|check_recruitment_reply_id');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_recruitment_id = $val->validated('recruitment_id');
				$validated_recruitment_reply_id = (Input::post('recruitment_reply_id')) ? $val->validated('recruitment_reply_id') : null;



				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_gc = new Model_Gc();
				$model_gc->agent_type = $this->agent_type;
				$model_gc->user_no = $this->user_no;
				$model_gc->language = $this->language;
				$model_gc->uri_base = $this->uri_base;
				$model_gc->uri_current = $this->uri_current;

				$model_present = new Model_Present();
				$model_present->agent_type = $this->agent_type;
				$model_present->user_no = $this->user_no;
				$model_present->language = $this->language;
				$model_present->uri_base = $this->uri_base;
				$model_present->uri_current = $this->uri_current;

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    編集権限チェック
				// --------------------------------------------------

				$authority = false;
				$datetime_past = new DateTime('-30 minutes');

				if ($validated_recruitment_id)
				{
					$db_recruitment_arr = $model_gc->get_recruitment_appoint($validated_recruitment_id);
					$game_no = $db_recruitment_arr['game_no'];

					$image = $db_recruitment_arr['image'];

					// ログインしていない場合
					$datetime_renewal = new DateTime($db_recruitment_arr['renewal_date']);
					$author_recruitment_arr = (Session::get('author_recruitment_arr')) ? Session::get('author_recruitment_arr') : array();
					if ($datetime_renewal > $datetime_past and in_array($validated_recruitment_id, $author_recruitment_arr)) $authority = true;
				}

				if ($validated_recruitment_reply_id)
				{
					$db_recruitment_reply_arr = $model_gc->get_recruitment_reply_appoint($validated_recruitment_reply_id);
					$game_no = $db_recruitment_reply_arr['game_no'];

					$image = $db_recruitment_reply_arr['image'];

					// ログインしていない場合
					$datetime_renewal = new DateTime($db_recruitment_reply_arr['renewal_date']);
					$author_reply_arr = (Session::get('author_reply_arr')) ? Session::get('author_reply_arr') : array();
					if ($datetime_renewal > $datetime_past and in_array($validated_recruitment_reply_id, $author_reply_arr)) $authority = true;
				}


				// ログインしている場合　募集
				if (isset($db_recruitment_arr['user_no']))
				{
					if (isset($this->user_no) and $db_recruitment_arr['user_no'] == $this->user_no) $authority = true;
				}

				// ログインしている場合　返信
				if (isset($db_recruitment_reply_arr['user_no']))
				{
					if (isset($this->user_no) and $db_recruitment_reply_arr['user_no'] == $this->user_no) $authority = true;
				}



				// --------------------------------------------------
				//   削除する権限がありません。
				// --------------------------------------------------

				if ( ! $authority and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '削除する権限がありません。';
					throw new Exception('Error');
				}



				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$language = 'ja';

				if ($validated_recruitment_reply_id)
				{
					$result_arr = $model_gc->delete_recruitment_reply($language, $validated_recruitment_id, $validated_recruitment_reply_id);


					// --------------------------------------------------
					//   プレゼント抽選エントリーポイント　－
					// --------------------------------------------------

					// $present_entry_arr = array(
					// 	'regi_date' => $db_recruitment_reply_arr['regi_date'],
					// 	'user_no' => $db_recruitment_reply_arr['user_no'],
					// 	'type_1' => 'recruitment_reply',
					// 	'type_2' => 'minus',
					// 	'point' => Config::get('present_point_recruitment_reply')
					// );
					//
					// if (isset($db_recruitment_reply_arr['profile_no'])) $present_entry_arr['profile_no'] = $db_recruitment_reply_arr['profile_no'];
					//
					// $model_present->plus_minus_point($present_entry_arr);

				}
				else
				{
					$result_arr = $model_gc->delete_recruitment($language, $game_no, $validated_recruitment_id);


					// --------------------------------------------------
					//   プレゼント抽選エントリーポイント　－
					// --------------------------------------------------

					// $present_entry_arr = array(
					// 	'regi_date' => $db_recruitment_arr['regi_date'],
					// 	'user_no' => $db_recruitment_arr['user_no'],
					// 	'type_1' => 'recruitment',
					// 	'type_2' => 'minus',
					// 	'point' => Config::get('present_point_recruitment')
					// );
					//
					// if (isset($db_recruitment_arr['profile_no'])) $present_entry_arr['profile_no'] = $db_recruitment_arr['profile_no'];
					//
					// $model_present->plus_minus_point($present_entry_arr);

				}



				// --------------------------------------------------
				//   画像削除
				// --------------------------------------------------



				if ($image)
				{

					// 保存先パス設定
					if ($validated_recruitment_reply_id)
					{
						$path = DOCROOT . 'assets/img/recruitment/reply/' . $validated_recruitment_reply_id . '/';
					}
					else
					{
						$path = DOCROOT . 'assets/img/recruitment/recruitment/' . $validated_recruitment_id . '/';
					}

					// 画像削除
					$original_func_common->image_delete($path, 'image_', array(1));

					// ディレクトリ削除
					if (is_dir($path)) rmdir($path);
				}

				// \Debug::dump($delete_id);


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '削除しました。';



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_recruitment_id';
					var_dump($validated_recruitment_id);

					echo '<br>$validated_recruitment_reply_id';
					var_dump($validated_recruitment_reply_id);

					if (isset($db_announcement_arr))
					{
						echo '<br>$db_announcement_arr';
						var_dump($db_announcement_arr);
					}

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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

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
	* プロフィール選択フォーム読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_gc_select_profile_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['game_no'] = 1;
			$_POST['all'] = 1;
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
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Basic');
			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('game_no', 'Game No', 'required|check_game_no');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_game_no = $val->validated('game_no');
				$validated_all = (Input::post('all')) ? true : null;
				$validated_page = $val->validated('page');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				//$original_common_date = new Original\Common\Date();
				//$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				/*
				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$model_game = new Model_Game();
				$model_game->agent_type = $this->agent_type;
				$model_game->user_no = $this->user_no;
				$model_game->language = $this->language;
				$model_game->uri_base = $this->uri_base;
				$model_game->uri_current = $this->uri_current;
				*/
				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;



				if (isset($test))
				{
					echo '<br>$validated_game_no';
					var_dump($validated_game_no);

					echo '<br>$validated_all';
					var_dump($validated_all);

					echo '<br>$validated_page';
					var_dump($validated_page);

					//echo '<br>$validated_search_recruitment_keyword';
					//var_dump($validated_search_recruitment_keyword);
				}



				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				$arr['code'] = $original_code_gc->gc_select_profile_form($validated_game_no, $validated_all, $validated_page);


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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
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
	* プロフィール選択保存
	*
	* @return string HTMLコード
	*/
	public function post_save_gc_select_profile()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['game_no'] = 1;
			$_POST['user'] = 1;
			//$_POST['profile_no'] = 1;
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
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Common');
			$val->add_callable('Original_Rule_User');

			$val->add_field('game_no', 'Game No', 'required|check_game_no');
			if (Input::post('profile_no')) $val->add_field('profile_no', 'profile_no', 'required|check_profile_author');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_game_no = $val->validated('game_no');
				$validated_user = (Input::post('user')) ? true : null;
				$validated_profile_no = $val->validated('profile_no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				//$original_common_date = new Original\Common\Date();
				//$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				/*
				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$model_game = new Model_Game();
				$model_game->agent_type = $this->agent_type;
				$model_game->user_no = $this->user_no;
				$model_game->language = $this->language;
				$model_game->uri_base = $this->uri_base;
				$model_game->uri_current = $this->uri_current;
				*/
				$model_gc = new \Model_Gc();
				$model_gc->agent_type = $this->agent_type;
				$model_gc->user_no = $this->user_no;
				$model_gc->language = $this->language;
				$model_gc->uri_base = $this->uri_base;
				$model_gc->uri_current = $this->uri_current;

				// $original_code_gc = new Original\Code\Gc();
				// $original_code_gc->app_mode = $this->app_mode;
				// $original_code_gc->agent_type = $this->agent_type;
				// $original_code_gc->host = $this->host;
				// $original_code_gc->user_agent = $this->user_agent;
				// $original_code_gc->user_no = $this->user_no;
				// $original_code_gc->language = $this->language;
				// $original_code_gc->uri_base = $this->uri_base;
				// $original_code_gc->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    設定　データ取得
				// --------------------------------------------------

				$db_user_game_community = $model_gc->get_user_game_community();
				$config_arr = (isset($db_user_game_community['config'])) ? unserialize($db_user_game_community['config']) : null;

				//$config_arr = array(1 => array('profile_no' => 1), 2 => array('profile_no' => 2), 3 => array('profile_no' => 3));
				//$validated_game_no = 3;

				// メインプロフィール　
				if ($validated_user)
				{
					// 削除　そのゲームNoの配列がない場合、メインを選んでいることになる
					// if (isset($config_arr[$validated_game_no]))
					// {
						// unset($config_arr[$validated_game_no]);
					// }
					$config_arr[$validated_game_no] = array('user_no' => $this->user_no);
				}
				// 追加プロフィール
				else
				{
					$config_arr[$validated_game_no] = array('profile_no' => $validated_profile_no);
				}

				$save_arr['config'] = serialize($config_arr);


				$result_arr = $model_gc->update_users_game_community($save_arr);




				if (isset($test))
				{
					echo '<br>$validated_game_no';
					var_dump($validated_game_no);

					echo '<br>$validated_user';
					var_dump($validated_user);

					echo '<br>$validated_profile_no';
					var_dump($validated_profile_no);

					echo '<br>$config_arr';
					var_dump($config_arr);

					echo '<br>$save_arr';
					var_dump($save_arr);
				}

				//exit();

				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '保存しました。';


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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
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
	* ID登録・編集フォーム読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_edit_game_id_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['game_no'] = 1;
			$_POST['all'] = 1;
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
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Basic');
			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('game_no', 'Game No', 'required|check_game_no');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_game_no = $val->validated('game_no');
				$validated_all = (Input::post('all')) ? true : null;
				$validated_page = $val->validated('page');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->host = $this->host;
				$original_code_gc->user_agent = $this->user_agent;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;



				if (isset($test))
				{
					echo '<br>$validated_game_no';
					var_dump($validated_game_no);

					echo '<br>$validated_all';
					var_dump($validated_all);

					echo '<br>$validated_page';
					var_dump($validated_page);

				}
				//exit();


				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				$arr['code'] = $original_code_gc->edit_game_id_form($validated_game_no, $validated_all, $validated_page);


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
				$arr['alert_message'] = '読み込めませんでした。' . $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
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
	* ゲームID保存
	*
	* @return string HTMLコード
	*/
	public function post_save_game_id()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			// $_POST['1_game_id_no'] = 1;
			// $_POST['1_sort_no'] = 1;
			// $_POST['1_hardware_no'] = 1;
			// $_POST['1_game_no'] = 1;
			// $_POST['1_delete'] = 'false';
			// $_POST['1_id'] = 'AZ-1979';

			$_POST['form_1'] = '1,5,1,1,false,AZ-1979,1,2,3';
			$_POST['form_2'] = '2,,,1,true,snowboard';
			$_POST['form_3'] = ',2,3,,false,gonzo100';

		}



		for ($i=1; $i <= 10; $i++) {

			if (Input::post('form_' . $i))
			{
				$form_arr = explode(',', Input::post('form_' . $i));

				$_POST['game_id_no_' . $i] = $form_arr[0];
				$_POST['sort_no_' . $i] = $form_arr[1];
				$_POST['hardware_no_' . $i] = $form_arr[2];
				$_POST['game_no_' . $i] = $form_arr[3];
				$_POST['delete_' . $i] = $form_arr[4];

				// IDにコンマが入ってる場合は結合する
				$id_arr = array_slice($form_arr, 5);
				$id = implode(',', $id_arr);
				//var_dump($id);

				$_POST['id_' . $i] = $id;

			}

		}


		//var_dump($_POST);
		//exit();


		$arr = array();

		//$_POST[1] = 1;

		//$arr['test'] = Input::post('form_1');

		//return $this->response($arr);


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
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Basic');
			$val->add_callable('Original_Rule_Gc');
			$val->add_callable('Original_Rule_Common');

			// $_POST['game_id_no_' . $i] = $form_arr[0];
				// $_POST['sort_no_' . $i] = $form_arr[1];
				// $_POST['hardware_no_' . $i] = $form_arr[2];
				// $_POST['game_no_' . $i] = $form_arr[3];
				// $_POST['delete_' . $i] = $form_arr[4];
				// $_POST['id_' . $i] = $form_arr[5];

			for ($i=1; $i <= 10; $i++) {

				if (Input::post('form_' . $i))
				{
					if (Input::post('game_id_no_' . $i)) $val->add_field('game_id_no_' . $i, 'game_id_no_' . $i, 'check_game_id');
					$val->add_field('sort_no_' . $i, 'sort_no_' . $i, 'match_pattern["^[1-9]\d*$"]');
					$val->add_field('hardware_no_' . $i, 'hardware_no_' . $i, 'check_hardware_no');
					if (Input::post('game_no_' . $i)) $val->add_field('game_no_' . $i, 'game_no_' . $i, 'check_game_no');
					$val->add('delete_' . $i, 'delete_' . $i)->add_rule('required')->add_rule('match_pattern', '/^(true|false)$/');
					$val->add_field('id_' . $i, 'id_' . $i, 'required|min_length[1]|max_length[50]');
				}

			}




			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$save_arr = array();

				for ($i=1; $i <= 10; $i++) {

					if (Input::post('form_' . $i))
					{
						$save_arr[$i]['game_id_no'] = ($val->validated('game_id_no_' . $i)) ? (int) $val->validated('game_id_no_' . $i) : null;
						$save_arr[$i]['sort_no'] = ($val->validated('sort_no_' . $i)) ? (int) $val->validated('sort_no_' . $i) : null;
						$save_arr[$i]['hardware_no'] = ($val->validated('hardware_no_' . $i)) ? (int) $val->validated('hardware_no_' . $i) : null;
						$save_arr[$i]['game_no'] = ($val->validated('game_no_' . $i) and ! $val->validated('hardware_no_' . $i)) ? (int) $val->validated('game_no_' . $i) : null;
						$save_arr[$i]['delete'] = ($val->validated('delete_' . $i) == 'true') ? true : false;
						$save_arr[$i]['id'] = $val->validated('id_' . $i);
						$save_arr[$i]['user_no'] = $this->user_no;
					}

				}




				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$model_game = new Model_Game();
				$model_game->agent_type = $this->agent_type;
				$model_game->user_no = $this->user_no;
				$model_game->language = $this->language;
				$model_game->uri_base = $this->uri_base;
				$model_game->uri_current = $this->uri_current;



				if (isset($test))
				{
					//echo '<br>$save_arr';
					//var_dump($save_arr);
				}
				//exit();


				// --------------------------------------------------
				//    データベース保存
				// --------------------------------------------------

				$result_arr = $model_game->insert_update_game_id($save_arr);


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '保存しました。';


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
				$arr['alert_message'] = '保存できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
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
