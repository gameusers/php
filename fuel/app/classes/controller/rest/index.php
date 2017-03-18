<?php

class Controller_Rest_Index extends Controller_Rest_Base
{

	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}



	/**
	* ゲームコミュニティ　募集一覧　検索
	*
	* @return string HTMLコード
	*/
	public function post_search_game_community_recruitment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['keyword'] = 'AAA';
			//$_POST['keyword'] = 'a';
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

			$val->add_field('keyword', 'Keyword', 'min_length[1]|max_length[100]');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_keyword = $val->validated('keyword');
				$validated_page = $val->validated('page');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$original_code_gc = new Original\Code\Gc();
				$original_code_gc->app_mode = $this->app_mode;
				$original_code_gc->agent_type = $this->agent_type;
				$original_code_gc->user_no = $this->user_no;
				$original_code_gc->language = $this->language;
				$original_code_gc->uri_base = $this->uri_base;
				$original_code_gc->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				if ($validated_keyword)
				{
					$search_arr = array('keyword' => $validated_keyword, 'page' => $validated_page);
				}
				else
				{
					$search_arr = array('page' => $validated_page);
				}

				$arr['code'] = $original_code_gc->search_recruitment_list($search_arr);


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;


					echo '$validated_keyword';
					var_dump($validated_keyword);

					echo '$code';
					echo $arr['code'];


				}
				//exit();




				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------
				/*
				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '保存されました。';
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
				$arr['alert_message'] = '保存できませんでした。';

				//$arr['test'] = 'エラー ' . $error_message;
				if (isset($test)) echo $error_message;
				*/
			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//if (isset($test)) echo $e->getMessage();
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
	* ゲームコミュニティ　ゲーム一覧　検索
	*
	* @return string HTMLコード
	*/
	public function post_search_game_list()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['keyword'] = 'ア';
			//$_POST['keyword'] = 'a';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   フォームが正しい経緯で送信されていません。
			// --------------------------------------------------

			// $cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
			// $post_csrf_token = Input::post('fuel_csrf_token');
			//
			// if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			// {
			// 	$arr['alert_color'] = 'warning';
			// 	$arr['alert_title'] = 'エラー';
			// 	$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
			// 	throw new Exception('Error');
			// }



			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_field('keyword', 'Keyword', 'min_length[1]|max_length[1]');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_keyword = $val->validated('keyword');
				$validated_page = $val->validated('page');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$original_code_index = new Original\Code\Index();
				$original_code_index->app_mode = $this->app_mode;
				$original_code_index->agent_type = $this->agent_type;
				$original_code_index->user_no = $this->user_no;
				$original_code_index->language = $this->language;
				$original_code_index->uri_base = $this->uri_base;
				$original_code_index->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				if ($validated_keyword)
				{
					$search_arr = array('keyword' => $validated_keyword, 'page' => $validated_page);
				}
				else
				{
					$search_arr = array('page' => $validated_page);
				}

				$arr['code'] = $original_code_index->search_game_list($search_arr);




				if (isset($test))
				{
					//Debug::$js_toggle_open = true;


					echo '$validated_keyword';
					var_dump($validated_keyword);

					//echo '$code';
					//var_dump($code);


				}
				//exit();


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------
				/*
				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '保存されました。';
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
				$arr['alert_message'] = '保存できませんでした。';

				//$arr['test'] = 'エラー ' . $error_message;
				if (isset($test)) echo $error_message;
				*/
			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//if (isset($test)) echo $e->getMessage();
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
	* ユーザーコミュニティ作成
	*
	* @return string HTMLコード
	*/
	public function post_create_user_community()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_name'] = '新コミュニティ';
			$_POST['community_description'] = '新コミュニティ説明文';
			$_POST['community_description_mini'] = '新コミュニティミニ';
			$_POST['community_id'] = 'test';
			$_POST['game_list'] = '1,2,3';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   フォームが正しい経緯で送信されていません。
			// --------------------------------------------------

			$cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
			$post_csrf_token = Input::post('fuel_csrf_token');

			//if ((Fuel::$env != 'development' and Fuel::$env != 'test') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			{
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
				//$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。' . 'cookie_csrf_token=' . $cookie_csrf_token . '<br>post_csrf_token=' . $post_csrf_token;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('community_name', 'コミュニティの名前', 'required|min_length[1]|max_length[50]');
			$val->add_field('community_description', 'コミュニティの説明文', 'required|min_length[1]|max_length[3000]');
			$val->add_field('community_description_mini', 'コミュニティの説明文（一覧用）', 'required|min_length[1]|max_length[100]');
			$val->add_field('community_id', 'コミュニティID', 'required|min_length[3]|max_length[50]|valid_string[alpha,lowercase,numeric,dashes]|community_id_duplication');
			$val->add_field('game_list', '関連ゲーム', 'required|check_game_existence');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_name = $val->validated('community_name');
				$validated_community_description = $val->validated('community_description');
				$validated_community_description_mini = $val->validated('community_description_mini');
				$validated_community_id = $val->validated('community_id');

				if (Input::post('game_list'))
				{
					$validated_game_list = explode(',', $val->validated('game_list'));
					array_unshift($validated_game_list, '/');
					array_push($validated_game_list, '/');
					$validated_game_list = implode(',', $validated_game_list);
				}
				else
				{
					$validated_game_list = null;
				}


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

				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_code_index = new Original\Code\Index();
				$original_code_index->app_mode = $this->app_mode;
				$original_code_index->agent_type = $this->agent_type;
				$original_code_index->user_no = $this->user_no;
				$original_code_index->language = $this->language;
				$original_code_index->uri_base = $this->uri_base;
				$original_code_index->uri_current = $this->uri_current;

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();

				$save_arr['regi_date'] = $datetime_now;
				$save_arr['renewal_date'] = $datetime_now;
				$save_arr['sort_date'] = $datetime_now;
				$save_arr['language'] = 'ja';
				$save_arr['community_id'] = $validated_community_id;
				$save_arr['author_user_no'] = $this->user_no;
				$save_arr['name'] = $validated_community_name;
				$save_arr['description'] = $validated_community_description;
				$save_arr['description_mini'] = $validated_community_description_mini;
				$save_arr['game_list'] = $validated_game_list;

				$member_arr = array($this->user_no => array('profile_no' => null, 'administrator' => true, 'moderator' => false, 'access_date' => $datetime_now, 'mail_all' => true));
				$save_arr['member'] = serialize($member_arr);

				$mail_arr = array('count' => 0, 'log' => array(), 'mail' => array(0 => array('subject' => null, 'body' => null), 1 => array('subject' => null, 'body' => null), 2 => array('subject' => null, 'body' => null), 3 => array('subject' => null, 'body' => null), 4 => array('subject' => null, 'body' => null), 5 => array('subject' => null, 'body' => null), 6 => array('subject' => null, 'body' => null), 7 => array('subject' => null, 'body' => null), 8 => array('subject' => null, 'body' => null), 9 => array('subject' => null, 'body' => null)));
				$save_arr['mail'] = serialize($mail_arr);

				$config_arr = array('participation_type' => 1, 'online_limit' => 24, 'anonymity' => false, 'read_announcement' => array(1, 2, 3), 'read_bbs' => array(1, 2, 3), 'read_member' => array(1, 2, 3), 'read_additional_info' => array(1, 2, 3), 'operate_announcement' => array(3), 'operate_bbs_thread' => array(1, 2, 3), 'operate_bbs_comment' => array(1, 2, 3), 'operate_bbs_delete' => array(3), 'operate_member' => array(3), 'operate_send_all_mail' => array(3), 'operate_config_community' => array(3));
				$save_arr['config'] = serialize($config_arr);

				//var_dump($member_arr, $mail_arr, $config_arr);


				// --------------------------------------------------
				//    データベース挿入
				// --------------------------------------------------

				$result = $model_co->insert_community($save_arr);

				//$result[0] = 4;
				// --------------------------------------------------
				//    参加コミュニティ
				// --------------------------------------------------

				$db_user_data_arr = $model_user->get_user_data($this->user_no, null);
				$participation_community_arr = (isset($db_user_data_arr['participation_community'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community']) : array();
				array_push($participation_community_arr, $result[0]);
				$save_user_data_arr['participation_community'] = $original_func_common->return_db_array('php_db', $participation_community_arr);


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_user->update_user_data($this->user_no, $save_user_data_arr);




				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_community_name';
					var_dump($validated_community_name);

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

					echo '$participation_community_arr';
					var_dump($participation_community_arr);

					echo '$save_user_data_arr';
					var_dump($save_user_data_arr);

				}
				//exit();


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = 'コミュニティを作成しました。';

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
				$arr['alert_message'] = '作成できませんでした。' . $error_message;

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




	/**
	* ゲーム登録用　ゲーム読み込み　検索
	*
	* @return string HTMLコード
	*/
	// public function post_search_game_data()
	// {
	//
	// 	// --------------------------------------------------
	// 	//   テスト変数
	// 	// --------------------------------------------------
	//
	// 	//$test = true;
	//
	// 	if (isset($test))
	// 	{
	// 		//$_POST['game_no'] = 1;
	// 		$_POST['page'] = 1;
	// 		$_POST['keyword'] = 'ゼルダの伝説';
	// 	}
	//
	//
	// 	$arr = array();
	//
	// 	try
	// 	{
	//
	// 		// --------------------------------------------------
	// 		//   フォームが正しい経緯で送信されていません。
	// 		// --------------------------------------------------
	//
	// 		$cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
	// 		$post_csrf_token = Input::post('fuel_csrf_token');
	//
	// 		if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
	// 		{
	// 			$arr['alert_color'] = 'warning';
	// 			$arr['alert_title'] = 'エラー';
	// 			$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
	// 			throw new Exception('Error');
	// 		}
	//
	//
	// 		// --------------------------------------------------
	// 		//   バリデーション
	// 		// --------------------------------------------------
	//
	// 		// ------------------------------
	// 		//    バリデーションルール設定
	// 		// ------------------------------
	//
	// 		$val = Validation::forge();
	//
	// 		$val->add_field('keyword', 'Keyword', 'min_length[1]|max_length[100]');
	// 		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
	//
	// 		if ($val->run())
	// 		{
	//
	// 			// --------------------------------------------------
	// 			//   バリデーション後の値取得
	// 			// --------------------------------------------------
	//
	// 			$validated_keyword = $val->validated('keyword');
	// 			$validated_page = $val->validated('page');
	//
	//
	// 			// --------------------------------------------------
	// 			//   共通処理
	// 			// --------------------------------------------------
	//
	// 			// 日時
	// 			$original_common_date = new Original\Common\Date();
	// 			$datetime_now = $original_common_date->sql_format();
	//
	// 			// インスタンス作成
	// 			$original_code_index = new Original\Code\Index();
	// 			$original_code_index->app_mode = $this->app_mode;
	// 			$original_code_index->agent_type = $this->agent_type;
	// 			$original_code_index->user_no = $this->user_no;
	// 			$original_code_index->language = $this->language;
	// 			$original_code_index->uri_base = $this->uri_base;
	// 			$original_code_index->uri_current = $this->uri_current;
	//
	//
	// 			// --------------------------------------------------
	// 			//    データ読み込み
	// 			// --------------------------------------------------
	//
	// 			$arr['code'] = $original_code_index->game_data_form($validated_keyword, $validated_page);
	//
	//
	//
	//
	// 			if (isset($test))
	// 			{
	// 				//Debug::$js_toggle_open = true;
	//
	//
	// 				echo '$validated_keyword';
	// 				var_dump($validated_keyword);
	//
	// 				//echo '$code';
	// 				//var_dump($code);
	//
	//
	// 			}
	// 			//exit();
	//
	// 		}
	// 		else
	// 		{
	//
	// 		}
	//
	// 	}
	// 	catch (Exception $e) {
	// 		//$arr['test'] = 'エラー ' . $e->getMessage();
	// 		//if (isset($test)) echo $e->getMessage();
	// 	}
	//
	//
	// 	if (isset($test))
	// 	{
	// 		var_dump($arr);
	// 	}
	// 	else
	// 	{
	// 		return $this->response($arr);
	// 	}
	//
	// }



	/**
	* ゲーム登録用　ゲーム読み込み　個別履歴
	*
	* @return string HTMLコード
	*/
	// public function post_read_game_data()
	// {
	//
	// 	// --------------------------------------------------
	// 	//   テスト変数
	// 	// --------------------------------------------------
	//
	// 	//$test = true;
	//
	// 	if (isset($test))
	// 	{
	// 		$_POST['game_no'] = 1;
	// 		$_POST['history_no'] = 1;
	// 	}
	//
	//
	// 	$arr = array();
	//
	// 	try
	// 	{
	//
	// 		// --------------------------------------------------
	// 		//   フォームが正しい経緯で送信されていません。
	// 		// --------------------------------------------------
	//
	// 		$cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
	// 		$post_csrf_token = Input::post('fuel_csrf_token');
	//
	// 		if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
	// 		{
	// 			$arr['alert_color'] = 'warning';
	// 			$arr['alert_title'] = 'エラー';
	// 			$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
	// 			throw new Exception('Error');
	// 		}
	//
	//
	// 		// --------------------------------------------------
	// 		//   バリデーション
	// 		// --------------------------------------------------
	//
	// 		// ------------------------------
	// 		//    バリデーションルール設定
	// 		// ------------------------------
	//
	// 		$val = Validation::forge();
	//
	// 		$val->add_callable('Original_Rule_Common');
	//
	// 		$val->add_field('game_no', 'Game No', 'required|check_game_no');
	// 		$val->add_field('history_no', 'History No', 'numeric_between[0,' . Config::get('limit_registration_game_data_log') . ']');
	//
	// 		if ($val->run())
	// 		{
	//
	// 			// --------------------------------------------------
	// 			//   バリデーション後の値取得
	// 			// --------------------------------------------------
	//
	// 			$validated_game_no = $val->validated('game_no');
	// 			$validated_history_no = $val->validated('history_no');
	//
	//
	// 			// --------------------------------------------------
	// 			//   共通処理
	// 			// --------------------------------------------------
	//
	// 			// 日時
	// 			$original_common_date = new Original\Common\Date();
	// 			$datetime_now = $original_common_date->sql_format();
	//
	// 			// インスタンス作成
	// 			$original_code_index = new Original\Code\Index();
	// 			$original_code_index->app_mode = $this->app_mode;
	// 			$original_code_index->agent_type = $this->agent_type;
	// 			$original_code_index->user_no = $this->user_no;
	// 			$original_code_index->language = $this->language;
	// 			$original_code_index->uri_base = $this->uri_base;
	// 			$original_code_index->uri_current = $this->uri_current;
	//
	//
	// 			// --------------------------------------------------
	// 			//    データ読み込み
	// 			// --------------------------------------------------
	//
	// 			$arr['code'] = $original_code_index->game_data_form(null, null, $validated_game_no, $validated_history_no);
	//
	//
	//
	// 			if (isset($test))
	// 			{
	// 				//Debug::$js_toggle_open = true;
	//
	//
	// 				echo '$validated_game_no';
	// 				var_dump($validated_game_no);
	//
	// 				echo '$validated_history_no';
	// 				var_dump($validated_history_no);
	//
	// 				echo $arr['code'];
	//
	//
	// 			}
	// 			//exit();
	//
	// 		}
	// 		else
	// 		{
	//
	// 		}
	//
	// 	}
	// 	catch (Exception $e) {
	// 		//$arr['test'] = 'エラー ' . $e->getMessage();
	// 		//if (isset($test)) echo $e->getMessage();
	// 	}
	//
	//
	// 	if (isset($test))
	// 	{
	// 		var_dump($arr);
	// 	}
	// 	else
	// 	{
	// 		return $this->response($arr);
	// 	}
	//
	// }



	/**
	* ゲーム登録　保存
	*
	* @return string HTMLコード
	*/
	public function post_save_game_data()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		// $test = true;

		if (isset($test))
		{
			$_POST['game_no'] = 604;
			$_POST['name'] = 'ドラゴンクエスト3';
			$_POST['subtitle'] = '～そして伝説へ～';

			/*
			$_POST['similarity_0'] = 'デスティニー';
			$_POST['similarity_1'] = 'マリオ';
			$_POST['similarity_2'] = 'ABC';
			$_POST['similarity_3'] = '4番目';
			$_POST['similarity_19'] = 'デッテニー';
			*/

			$_POST['id'] = 'dq3';
			$_POST['kana'] = 'ドラゴンクエスト3';
			$_POST['twitter_hashtag_ja'] = 'ドラゴンクエスト3';

			// $_POST['hardware_1'] = 1;
			// $_POST['hardware_2'] = 2;
			// $_POST['hardware_3'] = 3;
			//
			// $_POST['genre_1'] = 1;
			// $_POST['genre_2'] = 2;
			// $_POST['genre_3'] = 1;
			//
			// $_POST['release_date_1'] = '2015-01-01';
			// $_POST['release_date_2'] = '2015-01-02';
			// $_POST['release_date_3'] = '2015-01-03';
			//
			// $_POST['players_max'] = 8;
			//
			// $_POST['developer'] = '1,2,3';
			//
			// $_POST['link_type_1'] = 'Official';
			// $_POST['link_name_1'] = null;
			// $_POST['link_url_1'] = 'https://192.168.10.2/gameusers/public/';
			// $_POST['link_country_1'] = 'Japan';
			//
			// $_POST['link_type_2'] = 'Twitter';
			// $_POST['link_name_2'] = null;
			// $_POST['link_url_2'] = 'https://twitter.com/Azumi1979';
			// $_POST['link_country_2'] = 'Japan';
			//
			// $_POST['link_type_4'] = 'YouTube';
			// $_POST['link_name_4'] = null;
			// $_POST['link_url_4'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
			// $_POST['link_country_4'] = 'Japan';



			// $_POST['game_no'] = 3;
			// $_POST['name'] = 'Grand Theft Auto V';
			// $_POST['id'] = 'gta5';
			// $_POST['kana'] = 'グランドセフトオート5';
			// $_POST['twitter_hashtag_ja'] = 'GTAV';

			// $_POST['delete_image_ids'] = 'o7cven72cfhnzqmj';

			//$_POST['thumbnail_delete'] = 1;
			//$_POST['ogp_image_delete'] = 1;

			//$_POST['first_bbs_thread'] = 1;

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
			//    管理者チェック
			// --------------------------------------------------

			$administrator = (Auth::member(100)) ? true : false;


			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Common');

			if (Input::post('game_no')) $val->add_field('game_no', 'Game No', 'required|check_game_no');
			$val->add_field('name', 'Name', 'required|min_length[1]|max_length[100]');
			$val->add_field('subtitle', 'Subtitle', 'min_length[1]|max_length[100]');


			if ($administrator)
			{

				for ($i=0; $i < 20; $i++)
				{
					$val->add_field('similarity_' . $i, 'Similarity ' . $i, 'min_length[1]|max_length[100]');
				}

				$val->add_field('id', 'ID', 'required|valid_string[alpha,lowercase,numeric,dashes]');
				$val->add_field('kana', 'カナ', 'required|min_length[1]|max_length[255]');
				$val->add_field('twitter_hashtag_ja', 'twitter_hashtag_ja', 'min_length[1]|max_length[255]');

				$val->add_field('delete_image_ids', 'Delete Image IDs', 'valid_string[alpha,lowercase,numeric,dashes,commas]');


				for ($i=1; $i <= 5; $i++)
				{
					$val->add_field('hardware_' . $i, 'hardware_' . $i, 'match_pattern["^[1-9]\d*$"]');
					$val->add_field('genre_' . $i, 'genre_' . $i, 'match_pattern["^[1-9]\d*$"]');
					$val->add_field('release_date_' . $i, 'release_date_' . $i, 'min_length[1]|max_length[255]');
				}

				$val->add_field('players_max', 'players_max', 'match_pattern["^[1-9]\d*$"]');
				$val->add_field('developer', 'developer', 'min_length[1]|max_length[255]');

				// $val->add_field('blurb', 'blurb', 'min_length[1]|max_length[500]');
				// $val->add_field('android_link', 'android_link', 'valid_url');
				// $val->add_field('android_image', 'android_image', 'valid_url');
				// $val->add_field('ios_link', 'ios_link', 'valid_url');
				// $val->add_field('ios_image', 'ios_image', 'valid_url');


				for ($i=1; $i <= 20; $i++)
				{
					$val->add_field('link_type_' . $i, 'link_type_' . $i, 'min_length[1]|max_length[20]');
					$val->add_field('link_name_' . $i, 'link_name_' . $i, 'min_length[1]|max_length[20]');
					$val->add_field('link_url_' . $i, 'link_type_' . $i, 'valid_url');
					$val->add_field('link_country_' . $i, 'link_country_' . $i, 'min_length[1]|max_length[20]');
				}

			}


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_game_no = $val->validated('game_no');
				$validated_name = $val->validated('name');
				$validated_subtitle = $val->validated('subtitle');

				$validated_similarity_arr = array();

				for ($i=0; $i < 20; $i++)
				{
					($val->validated('similarity_' . $i)) ? array_push($validated_similarity_arr, $val->validated('similarity_' . $i)) : array_push($validated_similarity_arr, null);
				}

				$validated_id = ($val->validated('id')) ? $val->validated('id') : null;
				$validated_kana = ($val->validated('kana')) ? $val->validated('kana') : null;
				$validated_twitter_hashtag_ja = ($val->validated('twitter_hashtag_ja')) ? $val->validated('twitter_hashtag_ja') : null;

				$validated_delete_image_ids = ($val->validated('delete_image_ids')) ? $val->validated('delete_image_ids') : null;

				// $validated_on_off_advertisement = (Input::post('on_off_advertisement')) ? 1 : null;
				// $validated_blurb = ($val->validated('blurb')) ? $val->validated('blurb') : null;
				// $validated_android_link = ($val->validated('android_link')) ? $val->validated('android_link') : null;
				// $validated_android_image = ($val->validated('android_image')) ? $val->validated('android_image') : null;
				// $validated_ios_link = ($val->validated('ios_link')) ? $val->validated('ios_link') : null;
				// $validated_ios_image = ($val->validated('ios_image')) ? $val->validated('ios_image') : null;

				$validated_thumbnail_delete = (Input::post('thumbnail_delete')) ? 1 : null;
				//$validated_ogp_image_delete = (Input::post('ogp_image_delete')) ? 1 : null;


				$validated_hardware_arr = [];
				$validated_genre_arr = [];

				for ($i=1; $i <= 5; $i++)
				{
					if ($val->validated('hardware_' . $i)) array_push($validated_hardware_arr, $val->validated('hardware_' . $i));
					if ($val->validated('genre_' . $i)) array_push($validated_genre_arr, $val->validated('genre_' . $i));

					if ($val->validated('release_date_' . $i))
					{
						$variable_name = 'validated_release_date_' . $i;
						$datetime = new \DateTime($val->validated('release_date_' . $i));
						${$variable_name} = $datetime->format("Y-m-d H:i:s");
					}
				}

				$validated_players_max = $val->validated('players_max') ?? null;

				if ($val->validated('developer'))
				{
					$validated_developer_arr = explode(',', $val->validated('developer'));
				}
				else
				{
					$validated_developer_arr = [];
				}


				$validated_link_arr = [];

				for ($i=1; $i <= 20; $i++)
				{
					if ($val->validated('link_type_' . $i))
					{
						$temp_arr['type'] = $val->validated('link_type_' . $i) ?? null;
						$temp_arr['name'] = $val->validated('link_name_' . $i) ?? null;
						$temp_arr['url'] = $val->validated('link_url_' . $i) ?? null;
						$temp_arr['country'] = $val->validated('link_country_' . $i) ?? null;
						array_push($validated_link_arr, $temp_arr);
					}

					// $val->add_field('link_type_' . $i, 'link_type_' . $i, 'min_length[1]|max_length[20]');
					// $val->add_field('link_name_' . $i, 'link_name_' . $i, 'min_length[1]|max_length[20]');
					// $val->add_field('link_url_' . $i, 'link_type_' . $i, 'valid_url');
					// $val->add_field('link_country_' . $i, 'link_country_' . $i, 'min_length[1]|max_length[20]');
				}


				$validated_first_bbs_thread = (Input::post('first_bbs_thread')) ? 1 : null;


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

				$model_present = new Model_Present();
				$model_present->agent_type = $this->agent_type;
				$model_present->user_no = $this->user_no;
				$model_present->language = $this->language;
				$model_present->uri_base = $this->uri_base;
				$model_present->uri_current = $this->uri_current;

				$original_code_index = new Original\Code\Index();
				$original_code_index->app_mode = $this->app_mode;
				$original_code_index->agent_type = $this->agent_type;
				$original_code_index->user_no = $this->user_no;
				$original_code_index->language = $this->language;
				$original_code_index->uri_base = $this->uri_base;
				$original_code_index->uri_current = $this->uri_current;

				$original_common_text = new Original\Common\Text();

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;




				// --------------------------------------------------
				//    変数設定
				// --------------------------------------------------

				$language = 'ja';



				// --------------------------------------------------
				//   Game Users運営の場合
				// --------------------------------------------------

				if ($administrator)
				{

					// --------------------------------------------------
					//   画像検証
					// --------------------------------------------------

					$uploaded_image_existence = false;

					if (count($_FILES) > 0)
					{
						foreach ($_FILES as $key => $value)
						{
							if ($value['size'] > 0)
							{
								$uploaded_image_existence = true;
							}
						}
					}

					//\Debug::dump($_FILES, $uploaded_image_existence);

					if ($validated_thumbnail_delete) $save_game_data_arr['thumbnail'] = null;
					if ($uploaded_image_existence) $save_game_data_arr['thumbnail'] = 1;



					$save_game_data_arr['approval'] = 1;
					$save_game_data_arr['renewal_date'] = $datetime_now;
					$save_game_data_arr['name_' . $language] = $validated_name;
					$save_game_data_arr['subtitle'] = $validated_subtitle;

					// 検索ワード
					$validated_similarity_arr = array_filter($validated_similarity_arr, 'strlen');
					$similarity = (count($validated_similarity_arr) > 0) ? '/-*-/' . implode('/-*-/', $validated_similarity_arr) . '/-*-/' : null;
					$save_game_data_arr['similarity_' . $language] = $similarity;

					$config_arr = array($language => array(true, count($validated_similarity_arr)));
					$save_game_data_arr['config'] = serialize($config_arr);



					// --------------------------------------------------
					//   ID＆カナ＆Twitterハッシュタグ
					// --------------------------------------------------

					$save_game_data_arr['id'] = $validated_id;
					$save_game_data_arr['kana'] = $validated_kana;
					$save_game_data_arr['twitter_hashtag_ja'] = $validated_twitter_hashtag_ja;


					// --------------------------------------------------
					//    user_noを0にする　historyを空にする
					// --------------------------------------------------

					$save_game_data_arr['user_no'] = 0;
					$save_game_data_arr['history'] = serialize(array());
					// $save_game_data_arr['on_off_advertisement'] = null;
					// $save_game_data_arr['advertisement'] = null;



					// --------------------------------------------------
					//    追加情報
					// --------------------------------------------------

					if (count($validated_hardware_arr) > 0)
					{
						$save_game_data_arr['hardware'] = $original_func_common->return_db_array('php_db', $validated_hardware_arr);
					}

					if (count($validated_genre_arr) > 0)
					{
						$save_game_data_arr['genre'] = $original_func_common->return_db_array('php_db', $validated_genre_arr);
					}

					if (isset($validated_release_date_1)) $save_game_data_arr['release_date_1'] = $validated_release_date_1;
					if (isset($validated_release_date_2)) $save_game_data_arr['release_date_2'] = $validated_release_date_2;
					if (isset($validated_release_date_3)) $save_game_data_arr['release_date_3'] = $validated_release_date_3;
					if (isset($validated_release_date_4)) $save_game_data_arr['release_date_4'] = $validated_release_date_4;
					if (isset($validated_release_date_5)) $save_game_data_arr['release_date_5'] = $validated_release_date_5;

					$save_game_data_arr['players_max'] = $validated_players_max;

					if (count($validated_developer_arr) > 0)
					{
						$save_game_data_arr['developer'] = $original_func_common->return_db_array('php_db', $validated_developer_arr);
					}

					// \Debug::dump($save_game_data_arr);
					// exit();


					// --------------------------------------------------
					//    リンク
					// --------------------------------------------------

					$save_data_link_arr = [];
					$db_data_link_arr = $model_game->select_data_link_for_update(array('game_no' => $validated_game_no));

					foreach ($validated_link_arr as $key => $value)
					{
						if (isset($db_data_link_arr[$key]))
						{
							$save_data_link_arr['update'][$key] = $value;
							$save_data_link_arr['update'][$key]['link_no'] = $db_data_link_arr[$key]['link_no'];
							$save_data_link_arr['update'][$key]['game_no'] = $validated_game_no;
							//echo 'aaa';
						}
						else
						{
							$save_data_link_arr['insert'][$key] = $value;
							$save_data_link_arr['insert'][$key]['game_no'] = $validated_game_no;
						}
					}



					//\Debug::$js_toggle_open = true;
					//\Debug::dump($validated_link_arr, $db_data_link_arr, $save_data_link_arr);
					//exit();



					// --------------------------------------------------
					//    データベース更新　game_data
					// --------------------------------------------------

					if ($validated_game_no)
					{
						$result_arr = $model_game->update_game_data($validated_game_no, $save_game_data_arr);
						$game_no = $validated_game_no;
					}
					else
					{
						$result_arr = $model_game->insert_game_data($save_game_data_arr, $datetime_now);
						$game_no = $result_arr[0];
					}


					// --------------------------------------------------
					//    データベース更新　data_link
					// --------------------------------------------------

					if (count($save_data_link_arr) > 0)
					{
						$result = $model_game->insert_update_data_link($save_data_link_arr);
					}


					// --------------------------------------------------
					//   サムネイル　保存先パス設定
					// --------------------------------------------------

					$path = DOCROOT . 'assets/img/game/' . $game_no . '/';


					// --------------------------------------------------
					//   サムネイル削除
					// --------------------------------------------------

					if ($validated_thumbnail_delete)
					{
						$original_common_image = new Original\Common\Image();

						$path_1 = $path . 'thumbnail_original.jpg';
						$path_2 = $path . 'thumbnail.jpg';
						$original_common_image->delete($path_1);
						$original_common_image->delete($path_2);
					}


					// --------------------------------------------------
					//   ヒーローイメージ削除
					// --------------------------------------------------

					if ($validated_delete_image_ids)
					{
						$temp_arr = array(
							'delete_image_ids' => $validated_delete_image_ids
						);

						$result_arr = $original_func_common->delete_images($temp_arr);
					}



					// --------------------------------------------------
					//   画像保存
					// --------------------------------------------------

					// echo '画像保存';
					// \Debug::dump(\Upload::is_valid());
					//$upload_field = null;

					// 管理者の場合、最大アップロードサイズを10MBまでにする
					if (\Auth::member(100) and $uploaded_image_existence)
					{
						$config = array(
							'auto_process' => false,
							'max_size' => 10485760
						);
						\Upload::process($config);
					}

					if ($uploaded_image_existence and \Upload::is_valid())
					{

						$upload_files_arr = \Upload::get_files();
						$upload_field = $upload_files_arr[0]['field'] ?? null;
						//\Debug::dump($upload_field);


						// --------------------------------------------------
						//   サムネイル保存　
						//   画質の劣化を防ぐため、そのまま保存されるようになっている
						//   ヒーローイメージと同時にアップロードはできない
						// --------------------------------------------------

						if ($upload_field === 'thumbnail')
						{
							//echo 'アップロード画像あり';
							$config = array(
								'auto_process' => false,
								'auto_rename' => false,
								'randomize' => false,
								'overwrite' => true,
								'ext_whitelist' => array('jpg'),
								'new_name' => 'thumbnail',
								'path' => $path
							);

							\Upload::process($config);
							\Upload::save();
							//echo '画像セーブ';

						}


						// --------------------------------------------------
						//   ヒーローイメージ保存　
						//   サムネイルと同時にアップロードはできない
						// --------------------------------------------------

						if (strpos($upload_field, 'image_') !== false)
						{
							//echo 'aaa';
							$temp_arr = array(
								'path' => DOCROOT . 'assets/img/u/',
								'delete_original_image' => true,
								'limit' => 3,
								'game_no' => $game_no,
								'max_width' => 1280,
								'max_height' => 1280,
								'max_width_s' => 640,
								'max_height_s' => 640,
								'quality' => 50
							);

							$hero_image_size_arr = $original_func_common->zebra_image_save3($temp_arr);

						}

					}



					// --------------------------------------------------
					//   交流スレッド作成
					// --------------------------------------------------

					if ($validated_first_bbs_thread)
					{

						$model_bbs = new \Model_Bbs();
						$gc_bbs_thread_total = $model_bbs->get_bbs_thread_total_gc($game_no);

						//\Debug::dump($gc_bbs_thread_total);

						// すでになんらかのスレッドが立てられている場合は立てない
						if ($gc_bbs_thread_total == 0)
						{

							$original_common_date = new \Original\Common\Date();
							$bbs_datetime_now = $original_common_date->sql_format();

							$save_bbs_thread_arr = array(
								'game_no' => $game_no,
								'regi_date' => $bbs_datetime_now,
								'renewal_date' => $bbs_datetime_now,
								'sort_date' => $bbs_datetime_now,
								'user_no' => null,
								'profile_no' => null,
								'anonymity' => null,
								'handle_name' => null,
								'title' => $validated_name . 'について語ろう！',
								'comment' => '雑談でもなんでもOK！' . "\n" . 'みんなで語りましょう！！',
								'image' => null,
								'movie' => null,
								'host' => HOST,
								'user_agent' => USER_AGENT
							);

							$save_arr = array('bbs_thread_arr' => $save_bbs_thread_arr);

							$result_arr = $model_bbs->insert_bbs_thread_gc($save_arr);

						}

					}



					// --------------------------------------------------
					//    コード作成
					// --------------------------------------------------

					$temp_arr = array(
						'page' => 1,
						'game_no' => $game_no
					);

					$arr = $original_code_index->register_game($temp_arr);

				}


				// --------------------------------------------------
				//   一般ユーザー　更新の場合
				// --------------------------------------------------

				else if ($validated_game_no)
				{

					// --------------------------------------------------
					//    データベースから取得
					// --------------------------------------------------

					$db_game_data_arr = $model_game->get_game_data($validated_game_no);
					$db_config_arr = unserialize($db_game_data_arr['config']);


					// --------------------------------------------------
					//    名前
					// --------------------------------------------------

					// 運営が確認済みの場合は処理停止
					if (isset($db_config_arr[$language][0])) exit();


					$save_game_data_arr['name_' . $language] = $validated_name;
					$save_game_data_arr['subtitle'] = $validated_subtitle;
					$save_game_data_arr['approval'] = 0;
					$save_game_data_arr['renewal_date'] = $datetime_now;
					$save_game_data_arr['user_no'] = $this->user_no;


					// --------------------------------------------------
					//    データベース更新
					// --------------------------------------------------

					$result = $model_game->update_game_data($validated_game_no, $save_game_data_arr);


					// --------------------------------------------------
					//    コード作成
					// --------------------------------------------------

					$temp_arr = array(
						'page' => 1,
						'game_no' => $validated_game_no
					);

					$arr = $original_code_index->register_game($temp_arr);

				}


				// --------------------------------------------------
				//   一般ユーザー　新規登録の場合
				// --------------------------------------------------

				else
				{

					// --------------------------------------------------
					//   ID作成
					// --------------------------------------------------

					$id = $original_common_text->random_text_lowercase(7);


					// --------------------------------------------------
					//   保存
					// --------------------------------------------------

					$save_game_data_arr['renewal_date'] = $datetime_now;
					$save_game_data_arr['id'] = $id;
					$save_game_data_arr['name_' . $language] = $validated_name;
					$save_game_data_arr['subtitle'] = $validated_subtitle;
					$save_game_data_arr['user_no'] = $this->user_no;
					$save_game_data_arr['history'] = serialize(array());
					$save_game_data_arr['config'] = serialize(array());


					// --------------------------------------------------
					//    データベース更新
					// --------------------------------------------------

					$result_arr = $model_game->insert_game_data($save_game_data_arr, $datetime_now);
					$game_no = $result_arr[0];



					// --------------------------------------------------
					//   プレゼント抽選エントリーポイント　＋
					// --------------------------------------------------
					/*
					$present_entry_arr = array(
						'regi_date' => $datetime_now,
						'user_no' => $this->user_no,
						'type_1' => 'register_game',
						'type_2' => 'plus',
						'point' => Config::get('present_point_register_game')
					);

					$model_present->plus_minus_point($present_entry_arr);
					*/


					// --------------------------------------------------
					//   交流スレッド作成
					// --------------------------------------------------

					$model_bbs = new \Model_Bbs();
					$gc_bbs_thread_total = $model_bbs->get_bbs_thread_total_gc($game_no);

					//\Debug::dump($gc_bbs_thread_total);

					// すでになんらかのスレッドが立てられている場合は立てない
					if ($gc_bbs_thread_total == 0)
					{

						$original_common_date = new \Original\Common\Date();
						$bbs_datetime_now = $original_common_date->sql_format();

						$save_bbs_thread_arr = array(
							'game_no' => $game_no,
							'regi_date' => $bbs_datetime_now,
							'renewal_date' => $bbs_datetime_now,
							'sort_date' => $bbs_datetime_now,
							'user_no' => null,
							'profile_no' => null,
							'anonymity' => null,
							'handle_name' => null,
							'title' => $validated_name . 'について語ろう！',
							'comment' => '雑談でもなんでもOK！' . "\n" . 'みんなで語りましょう！！',
							'image' => null,
							'movie' => null,
							'host' => HOST,
							'user_agent' => USER_AGENT
						);

						$save_arr = array('bbs_thread_arr' => $save_bbs_thread_arr);

						$result_arr = $model_bbs->insert_bbs_thread_gc($save_arr);

					}


					// --------------------------------------------------
					//    コード作成
					// --------------------------------------------------

					$temp_arr = array(
						'page' => 1,
						'game_no' => $game_no
					);

					$arr = $original_code_index->register_game($temp_arr);

				}






				if (isset($test))
				{
					//Debug::$js_toggle_open = true;
					// echo "<br><br><br><br>";
					//
					// echo '$validated_game_no';
					// \Debug::dump($validated_game_no);
					//
					// echo '$validated_similarity_arr';
					// \Debug::dump($validated_similarity_arr);
					//
					// //echo '$similarity';
					// //var_dump($similarity);
					//
					// if (isset($db_game_data_arr))
					// {
					// 	echo '$db_game_data_arr';
					// 	\Debug::dump($db_game_data_arr);
					// }
					//
					// if (isset($db_history_arr))
					// {
					// 	echo '$db_history_arr';
					// 	\Debug::dump($db_history_arr);
					// }
					//
					// if (isset($db_config_arr))
					// {
					// 	echo '$db_config_arr';
					// 	\Debug::dump($db_config_arr);
					// }
					//
					//
					// echo '$save_game_data_arr';
					// \Debug::dump($save_game_data_arr);
					//
					//
					// if (isset($hero_image_size_arr))
					// {
					// 	echo '$hero_image_size_arr';
					// 	\Debug::dump($hero_image_size_arr);
					// }



					/*
					echo '$validated_similarity_0';
					var_dump($validated_similarity_0);

					echo '$validated_similarity_1';
					var_dump($validated_similarity_1);

					echo '$validated_similarity_19';
					var_dump($validated_similarity_19);
					*/
				}
				//exit();




				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				//$result = $model_co->update_community($validated_community_no, $save_community_arr);


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '保存されました。';

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

				//$arr['test'] = 'エラー ' . $error_message;
				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
			//$arr['code'] = 'エラー ' . $e->getMessage();
			if (isset($test)) echo $e->getMessage();
			// echo $e->getMessage();
			// $arr['alert_color'] = 'warning';
			// $arr['alert_title'] = 'エラー';
			// $arr['alert_message'] = $e->getMessage();
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
	* ゲーム名検索
	*
	* @return string HTMLコード
	*/
	public function get_search_developer()
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_GET['keyword'] = '任天堂';
		}


		$arr = array();


		// バリデーション
		$val = Validation::forge();
		$val->add_field('keyword', 'Keyword', 'required|min_length[1]|max_length[100]');

		if ($val->run(array('keyword' => Input::get('keyword'))))
		{
			$validated_keyword = $val->validated('keyword');

			$model_game = new Model_Game();
			$arr = $model_game->select_search_data_developer(array('keyword' => $validated_keyword));
		}


		return $this->response(\Security::htmlentities($arr));

	}



}
