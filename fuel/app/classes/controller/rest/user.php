<?php

class Controller_Rest_User extends Controller_Rest_Base
{

	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}



	/**
	* プロフィール読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_profile()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['user_no'] = 1;
		}


		$arr = array();
		//return array('aaa' => 'aaa');

		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		// ------------------------------
		//    バリデーションルール設定
		// ------------------------------

		$val = Validation::forge();
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
		$val->add_field('user_no', 'User No', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run())
		{

			// --------------------------------------------------
			//   バリデーション後の値取得
			// --------------------------------------------------

			$validated_page = $val->validated('page');
			$validated_user_no = $val->validated('user_no');

			//var_dump($validated_user_no, $validated_profile_no);


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

			$model_user = new Model_User();
			$model_user->agent_type = $this->agent_type;
			$model_user->user_no = $this->user_no;
			$model_user->language = $this->language;
			$model_user->uri_base = $this->uri_base;
			$model_user->uri_current = $this->uri_current;


			// --------------------------------------------------
			//    プロフィール作成
			// --------------------------------------------------

			// Limit取得
			$limit_profile = ($this->agent_type != 'smartphone') ? Config::get('limit_profile') : Config::get('limit_profile_sp');

			// プロフィールデータ取得
			$db_profiles_arr = $model_user->get_profile_list($validated_user_no, $validated_page, $limit_profile);

			// 総数取得
			$total_profile = $model_user->get_profile_list_total($validated_user_no);



			if (count($db_profiles_arr) > 0)
			{

				// ----- ゲームデータ処理 -----

				$game_no_arr = array();

				foreach ($db_profiles_arr as $key => $value) {

					if ($value['game_list'])
					{
						$game_list_arr = explode(',', $value['game_list']);
						array_shift($game_list_arr);
						array_pop($game_list_arr);
						$game_no_arr = array_merge($game_no_arr, $game_list_arr);
					}

				}

				if (count($game_no_arr) > 0)
				{
					// 重複番号削除
					$game_no_arr = array_unique($game_no_arr);

					// ゲーム名取得
					$game_names_arr = $model_game->get_game_name($this->language, $game_no_arr);
				}
				else
				{
					$game_names_arr = null;
				}


				// ----- コード作成 -----

				$code_profiles = null;

				foreach ($db_profiles_arr as $key => $value) {

					$view_content_profiles = View::forge('parts/profile_view');
					$view_content_profiles->set_safe('app_mode', $this->app_mode);
					$view_content_profiles->set('uri_base', $this->uri_base);
					$view_content_profiles->set('login_user_no', $this->user_no);
					$view_content_profiles->set('profile_arr', $value);
					$view_content_profiles->set('online_limit', Config::get('online_limit'));
					$view_content_profiles->set('game_names_arr', $game_names_arr);
					$view_content_profiles->set_safe('link_force_off', true);
					$view_content_profiles->set_safe('appoint', false);
					$code_profiles .= $view_content_profiles->render() . "\n\n";

				}

				$arr['profile'] = $code_profiles;


				// --------------------------------------------------
				//    ページャー
				// --------------------------------------------------

				// ページャーの数字表示回数取得
				$pagination_times = ($this->agent_type != 'smartphone') ? Config::get('pagination_times') : Config::get('pagination_times_sp');

				$view_content_pagination = View::forge('parts/pagination_view');
				$view_content_pagination->set('page', $validated_page);
				$view_content_pagination->set('total', $total_profile);
				$view_content_pagination->set('limit', $limit_profile);
				$view_content_pagination->set('times', $pagination_times);
				$view_content_pagination->set('function_name', 'GAMEUSERS.player.readProfile');
				$view_content_pagination->set('argument_arr', array($validated_user_no));

				$arr['pagination'] = $view_content_pagination->render();

			}

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
	* プロフィール編集フォーム表示
	*
	* @return string HTMLコード
	*/
	public function post_show_edit_profile_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$this->user_no = null;
			//$_POST['user_no'] = 1;
			$_POST['profile_no'] = 8;
		}


		$arr = array();
		//$arr['test'] = Fuel::$env;
		//$arr['user_no'] = $this->user_no;
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
			$val->add_field('user_no', 'User No', 'match_value[' . $this->user_no . ']|match_pattern["^[1-9]\d*$"]');
			$val->add_field('profile_no', 'Profile No', 'match_pattern["^[1-9]\d*$"]');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_user_no = $val->validated('user_no');
				$validated_profile_no = $val->validated('profile_no');
				//var_dump($validated_user_no, $validated_profile_no);


				// --------------------------------------------------
				//   不正な送信　両方に値が入っている場合
				// --------------------------------------------------

				if ($validated_user_no and $validated_profile_no)
				{
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   インスタンス作成
				// --------------------------------------------------

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


				// --------------------------------------------------
				//   ユーザープロフィールの場合
				// --------------------------------------------------

				if ($validated_user_no)
				{
					$result = $model_user->get_user_data($validated_user_no, null);
				}

				// --------------------------------------------------
				//   追加プロフィールの場合
				// --------------------------------------------------

				else if ($validated_profile_no)
				{

					$result = $model_user->get_profile($validated_profile_no);

					// 自分のプロフィールじゃない場合は処理打ち切り
					if ($result['author_user_no'] != $this->user_no) throw new Exception('Error');;


					// ----- ゲームデータ処理 -----

					if (isset($result['game_list']))
					{
						// 配列処理
						$game_no_arr = explode(',', $result['game_list']);
						array_shift($game_no_arr);
						array_pop($game_no_arr);

						// 重複番号削除
						$game_no_arr = array_unique($game_no_arr);

						// ゲーム名取得
						$game_names_arr = $model_game->get_game_name($this->language, $game_no_arr);
					}

				}
				//var_dump($result);

				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				if (isset($result))
				{
					$view = View::forge('parts/edit_profile_form_view', $result);
					$view->set_safe('app_mode', $this->app_mode);
					$view->set('language', $this->language);
					$view->set('uri_base', $this->uri_base);
					if (isset($game_names_arr)) $view->set('game_names_arr', $game_names_arr);
					$arr['code'] = $view->render();
				}
				else
				{
					// 新規プロフィール追加
					$view = View::forge('parts/edit_profile_form_view');
					$view->set_safe('app_mode', $this->app_mode);
					$view->set('uri_base', $this->uri_base);
					$arr['code'] = $view->render();
				}

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
	* プロフィール保存
	*
	* @return string HTMLコード
	*/
	public function post_save_profile()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{

			// $_POST['profile_title'] = 'テスト6';
			// $_POST['handle_name'] = 'テスト';
			// $_POST['explanation'] = 'テスト';
			// $_POST['status'] = 'テスト';
			// $_POST['thumbnail'] = null;
			// $_POST['thumbnail_delete'] = null;
			// $_POST['open_profile'] = 1;
			// $_POST['game_list'] = '6,7';
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

			if (Input::post('user_no'))
			{
				// プレイヤープロフィール
				$val->add_field('user_no', 'User No', 'required|match_value[' . $this->user_no . ']|match_pattern["^[1-9]\d*$"]');
				$val->add_field('profile_title', 'プロフィールタイトル', 'required|min_length[1]|max_length[20]');
				$val->add_field('handle_name', 'ハンドルネーム', 'required|min_length[1]|max_length[30]');
				$val->add_field('explanation', 'コメント', 'required|min_length[1]|max_length[3000]');
				$val->add_field('status', 'ステータス', 'required|min_length[1]|max_length[20]');
			}
			else if (Input::post('profile_no'))
			{
				// 追加プロフィール
				$val->add_callable('Original_Rule_User');
				$val->add_callable('Original_Rule_Common');
				$val->add_field('profile_no', 'Profile No', 'required|match_pattern["^[1-9]\d*$"]|check_profile_author');
				$val->add_field('profile_title', 'プロフィールタイトル', 'required|min_length[1]|max_length[20]');
				$val->add_field('handle_name', 'ハンドルネーム', 'required|min_length[1]|max_length[30]');
				$val->add_field('explanation', 'コメント', 'required|min_length[1]|max_length[3000]');
				$val->add_field('status', 'ステータス', 'required|min_length[1]|max_length[20]');
				$val->add_field('game_list', 'Game List', 'check_game_existence');
			}
			else
			{
				// プロフィール新規挿入
				$val->add_callable('Original_Rule_Common');
				$val->add_field('profile_title', 'プロフィールタイトル', 'required|min_length[1]|max_length[20]');
				$val->add_field('handle_name', 'ハンドルネーム', 'required|min_length[1]|max_length[30]');
				$val->add_field('explanation', 'コメント', 'required|min_length[1]|max_length[3000]');
				$val->add_field('status', 'ステータス', 'required|min_length[1]|max_length[20]');
				$val->add_field('game_list', 'Game List', 'check_game_existence');
			}


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_user_no = $val->validated('user_no');
				$validated_profile_no = $val->validated('profile_no');
				$validated_profile_title = $val->validated('profile_title');
				$validated_handle_name = $val->validated('handle_name');
				$validated_explanation = $val->validated('explanation');
				$validated_status = $val->validated('status');
				$validated_thumbnail_delete = (Input::post('thumbnail_delete')) ? 1: null;
				$validated_open_profile = (Input::post('open_profile')) ? 1: null;

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

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();


				// --------------------------------------------------
				//   画像検証
				// --------------------------------------------------

				$image_name_arr = array('thumbnail');
				$result_check_upload_image_arr = $original_func_common->check_upload_image($image_name_arr, Config::get('limit_bbs_comment_image'));

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
				//echo "file = ";
				//var_dump($_FILES);
				//$arr['file'] = $_FILES;

				// --------------------------------------------------
				//   新規で追加プロフィールを挿入する場合
				// --------------------------------------------------

				if (! $validated_user_no and ! $validated_profile_no)
				{

					$validated_profile_no = $model_user->insert_profile($datetime_now, $validated_profile_title, $validated_handle_name, $validated_explanation, $validated_status, null, $validated_open_profile, $validated_game_list);

					$validated_thumbnail = null;

					// 重複で書き込みに失敗した場合はエラー
					if ($validated_profile_no == false)
					{
						$arr['alert_color'] = 'warning';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = '同じ内容のプロフィールがすでに登録されています。';
						throw new Exception('Error');
					}

				}
				else if ($validated_user_no)
				{
					// サムネイルの存在確認
					$db_user_data_arr = $model_user->get_user_data($validated_user_no, null);
					$validated_thumbnail = ($db_user_data_arr['thumbnail']) ? 1 : null;
				}
				else if ($validated_profile_no)
				{
					// サムネイルの存在確認
					$db_profile_arr = $model_user->get_profile($validated_profile_no);
					$validated_thumbnail = ($db_profile_arr['thumbnail']) ? 1 : null;
				}



				// --------------------------------------------------
				//    アップロードされた画像があるかの確認
				// --------------------------------------------------

				$uploaded_image_existence = false;

				if (isset($_FILES))
				{
					foreach ($_FILES as $key => $value)
					{
						if ($value['size'] > 0) $uploaded_image_existence = true;
					}
				}


				// --------------------------------------------------
				//   画像設定
				// --------------------------------------------------

				// ----- 保存先パス設定 -----

				if ($validated_user_no)
				{
					$path = DOCROOT . 'assets/img/user/' . $this->user_no . '/';
				}
				else
				{
					$path = DOCROOT . 'assets/img/profile/' . $validated_profile_no . '/';
				}


				// --------------------------------------------------
				//   画像削除
				// --------------------------------------------------

				if ($validated_thumbnail_delete)
				{
					$original_common_image = new Original\Common\Image();
					$original_common_image->delete($path . 'thumbnail.jpg');
					$original_common_image->delete($path . 'thumbnail_original.jpg');
					$validated_thumbnail = null;
				}

				//var_dump($uploaded_image_existence);

				// --------------------------------------------------
				//    画像保存
				// --------------------------------------------------

				if ($uploaded_image_existence)
				{

					// --------------------------------------------------
					//   画像保存
					// --------------------------------------------------

					$image_name_arr = array('thumbnail');
					$result_upload_image_arr = $original_func_common->zebra_image_save2($path, $image_name_arr, false, 1);

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

						$validated_thumbnail = ($result_upload_image_arr['size_arr']) ? 1 : null;

						//var_dump($result_upload_image_arr['size_arr']);
					}

				}



				// --------------------------------------------------
				//   ◆◆◆　データベースに保存　◆◆◆
				// --------------------------------------------------

				if ($validated_user_no)
				{

					// ------------------------------
					//    メインプロフィール保存
					// ------------------------------

					$result = $model_user->update_profile_user_data($this->user_no, $datetime_now, $validated_profile_title, $validated_handle_name, $validated_explanation, $validated_status, $validated_thumbnail);


					// ------------------------------
					//    プロフィールコード作成
					// ------------------------------

					$db_user_data = $model_user->get_user_data($this->user_no, null);

					$view_content_main_profile = View::forge('parts/profile_view');
					$view_content_main_profile->set_safe('app_mode', $this->app_mode);
					$view_content_main_profile->set('login_user_no', $this->user_no);
					$view_content_main_profile->set('language', $this->language);
					$view_content_main_profile->set('online_limit', Config::get('online_limit'));
					$view_content_main_profile->set('uri_base', $this->uri_base);
					$view_content_main_profile->set_safe('link', 0);
					$view_content_main_profile->set_safe('appoint', false);
					$view_content_main_profile->set('profile_arr', $db_user_data);

					$arr['code'] = $view_content_main_profile->render();


					// ------------------------------
					//    User ID取得
					// ------------------------------

					//$result = $model_user->get_user_data($validated_user_no, null);
					//$arr['user_id'] = $result['user_id'];

				}
				else
				{

					// ------------------------------
					//    追加プロフィール保存
					// ------------------------------

					$result = $model_user->update_profile_profile($validated_profile_no, $datetime_now, $validated_profile_title, $validated_handle_name, $validated_explanation, $validated_status, $validated_thumbnail, $validated_open_profile, $validated_game_list);


					// ------------------------------
					//    プロフィールコード作成
					// ------------------------------


					// ----- ゲームデータ処理 -----

					$db_profiles_arr = $model_user->get_profile($validated_profile_no);

					if ($db_profiles_arr['game_list'])
					{
						$game_no_arr = explode(',', $db_profiles_arr['game_list']);
						array_shift($game_no_arr);
						array_pop($game_no_arr);

						// ゲーム名取得
						$game_names_arr = $model_game->get_game_name($this->language, $game_no_arr);
					}
					else
					{
						$game_names_arr = null;
					}


					// ----- コード作成 -----

					$view_content_profiles = View::forge('parts/profile_view', $db_profiles_arr);
					$view_content_profiles->set_safe('app_mode', $this->app_mode);
					$view_content_profiles->set('login_user_no', $this->user_no);
					$view_content_profiles->set('language', $this->language);
					$view_content_profiles->set('online_limit', Config::get('online_limit'));
					$view_content_profiles->set('uri_base', $this->uri_base);
					$view_content_profiles->set_safe('link', 0);
					$view_content_profiles->set_safe('appoint', false);
					$view_content_profiles->set('profile_arr', $db_profiles_arr);
					$view_content_profiles->set('game_names_arr', $game_names_arr);
					$arr['code'] = $view_content_profiles->render();


					// ------------------------------
					//    User ID取得
					// ------------------------------

					//$result = $model_user->get_profile($validated_profile_no);
					//$arr['user_id'] = $result['user_id'];

				}


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '保存されました。';


				if (isset($test))
				{

					if (isset($result_upload_image_arr))
					{
						echo '$result_upload_image_arr';
						var_dump($result_upload_image_arr);
					}


					echo '$validated_thumbnail';
					var_dump($validated_thumbnail);

				}
				//exit();

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
	* プロフィール削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_profile()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['profile_no'] = 8;
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
			$val->add_callable('Original_Rule_User');
			$val->add_field('profile_no', 'Profile No', 'required|match_pattern["^[1-9]\d*$"]|check_profile_author');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_profile_no = $val->validated('profile_no');



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


				// --------------------------------------------------
				//   画像削除
				// --------------------------------------------------

				$path = DOCROOT . 'assets/img/profile/' . $validated_profile_no . '/';

				$original_common_image = new Original\Common\Image();
				$original_common_image->delete($path . 'thumbnail.jpg');
				$original_common_image->delete($path . 'thumbnail_original.jpg');

				//if (file_exists($path . 'original.jpg')) unlink($path . 'original.jpg');
				//if (file_exists($path . 'thumbnail.jpg')) unlink($path . 'thumbnail.jpg');

				//$arr['test'] = 'aaa';


				// --------------------------------------------------
				//   ◆◆◆　データベースに保存　◆◆◆
				// --------------------------------------------------

				// ------------------------------
				//    追加プロフィール削除
				// ------------------------------

				$result = $model_user->delete_profile($validated_profile_no, $datetime_now);


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '削除されました。';

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
				$arr['alert_message'] = '削除できませんでした。' . $error_message;

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
	* お知らせ読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_notifications()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//if ($this->user_no == 1) $test = true;
		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['user_no'] = 1;
			$_POST['type'] = 'already';
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
				// $arr['alert_color'] = 'warning';
				// $arr['alert_title'] = 'エラー';
				// $arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
				// throw new Exception('Error');
			// }


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
			//   閲覧する権限がありません。
			// --------------------------------------------------

			if ($this->user_no != Input::post('user_no'))
			{
				$arr['alert_color'] = 'danger';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '閲覧する権限がありません。';
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
			$val->add_field('user_no', 'User No', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(unread|already)$/');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page = $val->validated('page');
				$validated_user_no = $val->validated('user_no');
				$validated_type = $val->validated('type');
				$validated_app_mode = (Input::post('app_mode')) ? 1: null;



				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$original_code_common = new Original\Code\Common();
				$original_code_common->app_mode = $this->app_mode;
				$original_code_common->agent_type = $this->agent_type;
				$original_code_common->user_no = $this->user_no;
				$original_code_common->language = $this->language;
				$original_code_common->uri_base = $this->uri_base;
				$original_code_common->uri_current = $this->uri_current;

				// $original_code_player = new Original\Code\Player();
				// $original_code_player->app_mode = $this->app_mode;
				// //if ($validated_app_mode) $original_code_player->app_mode = true;
				// $original_code_player->agent_type = $this->agent_type;
				// $original_code_player->user_no = $this->user_no;
				// $original_code_player->language = $this->language;
				// $original_code_player->uri_base = $this->uri_base;
				// $original_code_player->uri_current = $this->uri_current;


				// --------------------------------------------------
				//  データベースから取得
				// --------------------------------------------------

				$db_users_data_arr = $model_user->get_user_data($validated_user_no, null);


				// --------------------------------------------------
				//  コード作成
				// --------------------------------------------------

				$type = ($validated_type == 'unread') ? true : false;

				$result_arr = $original_code_common->notifications(true, $type, $db_users_data_arr, $validated_page);

				$arr['code'] = $result_arr['code'];
				$arr['unread_id'] = $result_arr['unread_id'];
				//$arr['code'] = $validated_user_no;


				if (isset($test))
				{
					Debug::$js_toggle_open = true;

					echo '$db_users_data_arr';
					Debug::dump($db_users_data_arr);

					//echo '$code_notifications';
					//var_dump($code_notifications);
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
				$arr['alert_message'] = 'エラー ' . $error_message;

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
	* Use Data にある通知設定のデバイス情報を更新する
	*
	* @return string HTMLコード
	*/
	public function post_save_notification_data()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['on_off'] = 'on';
			//$_POST['on_off_browser'] = 'on';
			// $_POST['on_off_app'] = 'on';
			// $_POST['on_off_mail'] = 'off';


			$_POST['endpoint'] = '';
			$_POST['public_key'] = '';
			$_POST['auth_token'] = '';


			// $_POST['type'] = 'iOS';
			// $_POST['id'] = '1929495969';
			// $_POST['name'] = 'iPad2';
			// $_POST['token'] = 'token iPad2';

			// $_POST['type'] = 'Android';
			// $_POST['id'] = 'a';
			// $_POST['name'] = 'Xperia';
			// $_POST['token'] = 'token';

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


			$val->add('on_off', 'On Off')->add_rule('match_pattern', '/^(on|off)$/');
			$val->add('on_off_browser', 'on_off_browser')->add_rule('match_pattern', '/^(on|off)$/');
			$val->add('on_off_app', 'on_off_app')->add_rule('match_pattern', '/^(on|off)$/');
			$val->add('on_off_mail', 'on_off_mail')->add_rule('match_pattern', '/^(on|off)$/');


			$val->add_field('receive_browser', 'Receive Browser', 'min_length[1]|max_length[300]');
			$val->add_field('delete_browser', 'Delete Browser', 'min_length[1]|max_length[500]');

			$val->add_field('endpoint', 'endpoint', 'min_length[1]|max_length[500]');
			$val->add_field('public_key', 'public_key', 'exact_length[88]');
			$val->add_field('auth_token', 'auth_token', 'exact_length[24]');


			$val->add_field('receive_device', 'Receive Device', 'min_length[1]|max_length[100]');
			$val->add_field('delete_device', 'Delete Device', 'min_length[1]|max_length[500]');

			$val->add('type', 'Type')->add_rule('match_pattern', '/^(iOS|Android)$/');
			$val->add_field('id', 'ID', 'min_length[1]|max_length[100]');
			$val->add_field('name', 'Name', 'min_length[1]|max_length[100]');
			$val->add_field('token', 'Token', 'min_length[1]|max_length[500]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_on_off = $val->validated('on_off');
				$validated_on_off_browser = $val->validated('on_off_browser');
				$validated_on_off_app = $val->validated('on_off_app');
				$validated_on_off_mail = $val->validated('on_off_mail');


				$validated_receive_browser = $val->validated('receive_browser');
				$validated_delete_browser = $val->validated('delete_browser');

				$validated_endpoint = $val->validated('endpoint');
				$validated_public_key = $val->validated('public_key');
				$validated_auth_token = $val->validated('auth_token');


				$validated_receive_device = $val->validated('receive_device');
				$validated_delete_device = $val->validated('delete_device');

				$validated_type = $val->validated('type');
				$validated_id = $val->validated('id');
				$validated_name = $val->validated('name');
				$validated_token = $val->validated('token');

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






			// --------------------------------------------------
			//   設定
			// --------------------------------------------------

			$config_receive_browser = 3;
			$config_browser_device_info = 10;


			// --------------------------------------------------
			//   共通処理　インスタンス作成
			// --------------------------------------------------

			// 日時
			$original_common_date = new Original\Common\Date();
			$datetime_now = $original_common_date->sql_format();

			$model_user = new Model_User();
			$model_user->agent_type = $this->agent_type;
			$model_user->user_no = $this->user_no;
			$model_user->language = $this->language;
			$model_user->uri_base = $this->uri_base;
			$model_user->uri_current = $this->uri_current;

			$model_notifications = new Model_Notifications();
			$model_notifications->agent_type = $this->agent_type;
			$model_notifications->user_no = $this->user_no;
			$model_notifications->language = $this->language;
			$model_notifications->uri_base = $this->uri_base;
			$model_notifications->uri_current = $this->uri_current;



			// --------------------------------------------------
			//    通知データ取得
			// --------------------------------------------------

			$db_user_data = $model_user->get_user_data($this->user_no, null);
			$db_notification_data_arr = unserialize($db_user_data['notification_data']);


			// --------------------------------------------------
			//    保存データ作成
			// --------------------------------------------------

			$changed = false;

			$save_notification_data_arr = $db_notification_data_arr;

			$save_browser_info_arr = $db_notification_data_arr['browser_info'] ?? null;
			$save_device_info_arr = $db_notification_data_arr['device_info'] ?? null;



			// ----------------------------------------
			//    通知のオンオフ
			// ----------------------------------------

			// On Off
			if ($validated_on_off)
			{
				$save_notification_data_arr['on_off'] = ($validated_on_off == 'on') ? true : false;
				$changed = true;
			}

			// On Off　ブラウザ
			if ($validated_on_off_browser)
			{
				$save_notification_data_arr['on_off_browser'] = ($validated_on_off_browser == 'on') ? true : false;
				$changed = true;
			}

			// On Off　アプリ
			if ($validated_on_off_app)
			{
				$save_notification_data_arr['on_off_app'] = ($validated_on_off_app == 'on') ? true : false;
				$changed = true;
			}

			// On Off　メール
			if ($validated_on_off_mail)
			{
				$save_notification_data_arr['on_off_mail'] = ($validated_on_off_mail == 'on') ? true : false;
				$changed = true;
			}



			// ----------------------------------------
			//    ブラウザ情報
			// ----------------------------------------

			// ------------------------------
			//   ブラウザ情報を追加・更新する場合
			// ------------------------------

			if ($validated_endpoint and $validated_public_key and $validated_auth_token)
			{

				// ハッシュ作成
				$hash = md5($validated_endpoint);

				// ユーザーエージェント取得
				$user_agent = $_SERVER['HTTP_USER_AGENT'];


				// ------------------------------
				//   新規登録
				// ------------------------------

				if (empty($save_browser_info_arr[$hash]) and count($save_browser_info_arr) < $config_browser_device_info)
				{

					$save_browser_info_arr[$hash]['regi_date'] = $datetime_now;
					$save_browser_info_arr[$hash]['endpoint'] = $validated_endpoint;
					$save_browser_info_arr[$hash]['public_key'] = $validated_public_key;
					$save_browser_info_arr[$hash]['auth_token'] = $validated_auth_token;
					$save_browser_info_arr[$hash]['user_agent'] = $user_agent;


					// ------------------------------
					//   3件以内の新規登録ブラウザは、受信ブラウザとして登録する
					// ------------------------------

					$db_receive_browser_arr = $db_notification_data_arr['receive_browser'] ?? array();

					if (count($db_receive_browser_arr) < $config_receive_browser)
					{
						array_push($db_receive_browser_arr, $hash);
						$validated_receive_browser = implode(',', $db_receive_browser_arr);
					}


					// ------------------------------
					//   1件目の登録の場合、on_off_browserをtrueにする
					// ------------------------------

					if (count($save_browser_info_arr) == 1)
					{
						$save_notification_data_arr['on_off_browser'] = true;
					}

					$changed = true;

				}

				// ------------------------------
				//   編集
				// ------------------------------

				else if
					(
						isset($save_browser_info_arr[$hash]) and
						$save_browser_info_arr[$hash]['public_key'] != $validated_public_key or
						$save_browser_info_arr[$hash]['auth_token'] != $validated_auth_token or
						$save_browser_info_arr[$hash]['user_agent'] != $user_agent
					)
				{
					//\Debug::dump('edit');
					$save_browser_info_arr[$hash]['public_key'] = $validated_public_key;
					$save_browser_info_arr[$hash]['auth_token'] = $validated_auth_token;
					$save_browser_info_arr[$hash]['user_agent'] = $user_agent;

					$changed = true;
				}

				$save_notification_data_arr['browser_info'] = $save_browser_info_arr;

			}

			// ------------------------------
			//   ブラウザ削除
			// ------------------------------

			else if ($validated_delete_browser and $save_browser_info_arr)
			{

				$delete_browser_arr = explode(',', $validated_delete_browser);

				foreach ($delete_browser_arr as $key => $value) {
					unset($save_browser_info_arr[$value]);
				}

				//\Debug::dump($save_browser_info_arr);

				// 削除して登録ブラウザがなくなった場合、Nullを保存する　on_off_browserもfalseに
				if (count($save_browser_info_arr) == 0)
				{
					$save_notification_data_arr['receive_browser'] = null;
					$save_browser_info_arr = null;
					$save_notification_data_arr['on_off_browser'] = false;
				}

				$save_notification_data_arr['browser_info'] = $save_browser_info_arr;

				$changed = true;

			}


			// ------------------------------
			//   Receive Device
			// ------------------------------

			if ($validated_receive_browser)
			{

				$receive_browser_arr = explode(',', $validated_receive_browser);

				$save_receive_browser_arr = [];
				foreach ($receive_browser_arr as $key => $value)
				{
					if (isset($save_browser_info_arr[$value])) array_push($save_receive_browser_arr, $value);
				}

				if (count($save_receive_browser_arr) != 0 and count($save_receive_browser_arr) <= $config_receive_browser)
				{
					$save_notification_data_arr['receive_browser'] = $save_receive_browser_arr;
				}
				else
				{
					$save_notification_data_arr['receive_browser'] = null;
				}

				$changed = true;

				if (isset($test))
				{
					Debug::$js_toggle_open = true;

					echo '<br>$save_receive_browser_arr';
					\Debug::dump($save_receive_browser_arr);

					// echo '<br>$check';
					// \Debug::dump($check);
				}

			}






			// ----------------------------------------
			//   デバイス情報
			// ----------------------------------------

			// ------------------------------
			//   Receive Device
			// ------------------------------

			if ($validated_receive_device and isset($save_device_info_arr[$validated_receive_device]))
			{
				$save_notification_data_arr['receive_device'] = $validated_receive_device;
				$changed = true;
			}


			// ------------------------------
			//   デバイス情報を追加・更新する場合
			// ------------------------------

			if ($validated_type and $validated_id and $validated_name and $validated_token)
			{

				// ------------------------------
				//   新規登録
				// ------------------------------

				if (empty($save_device_info_arr[$validated_id]) and count($save_device_info_arr) <= $config_browser_device_info)
				{
					$save_device_info_arr[$validated_id]['regi_date'] = $datetime_now;
					$save_device_info_arr[$validated_id]['type'] = $validated_type;
					$save_device_info_arr[$validated_id]['name'] = $validated_name;
					$save_device_info_arr[$validated_id]['token'] = $validated_token;

					$arr['new_device'] = true;
					$changed = true;


					// ------------------------------
					//   1件目の登録の場合、on_off_appをtrueにする
					// ------------------------------

					if (count($save_device_info_arr) == 1)
					{
						$save_notification_data_arr['on_off_app'] = true;
					}

				}

				// ------------------------------
				//   編集
				// ------------------------------

				else if ($save_device_info_arr[$validated_id]['type'] != $validated_type or $save_device_info_arr[$validated_id]['name'] != $validated_name or $save_device_info_arr[$validated_id]['token'] != $validated_token)
				{
					//\Debug::dump('edit');
					$save_device_info_arr[$validated_id]['type'] = $validated_type;
					$save_device_info_arr[$validated_id]['name'] = $validated_name;
					$save_device_info_arr[$validated_id]['token'] = $validated_token;

					$changed = true;
				}

				$save_notification_data_arr['device_info'] = $save_device_info_arr;


				// if (isset($test))
				// {
				// 	Debug::$js_toggle_open = true;
				//
				// 	echo '<br>$db_notification_data_device_info_arr';
				// 	\Debug::dump($db_notification_data_device_info_arr);
				//
				// 	echo '<br>$save_device_info_arr';
				// 	\Debug::dump($save_device_info_arr);
				// }

			}

			// ------------------------------
			//   デバイス削除
			// ------------------------------

			else if ($validated_delete_device and $save_device_info_arr)
			{

				$delete_device_arr = explode(',', $validated_delete_device);

				foreach ($delete_device_arr as $key => $value) {
					unset($save_device_info_arr[$value]);
				}

				//\Debug::dump($save_device_info_arr);

				// 削除して登録デバイスがなくなった場合、Nullを保存する　on_off_appもfalseに
				if (count($save_device_info_arr) == 0)
				{
					$save_notification_data_arr['receive_device'] = null;
					$save_device_info_arr = null;
					$save_notification_data_arr['on_off_app'] = false;
				}

				$save_notification_data_arr['device_info'] = $save_device_info_arr;

				$changed = true;

			}



			// --------------------------------------------------
			//    データベースに保存
			// --------------------------------------------------

			if ($changed)
			{
				// シリアライズ
				$save_arr['notification_data'] = serialize($save_notification_data_arr);
				$result_arr = $model_user->update_user_data(USER_NO, $save_arr);

				// 通知できるユーザーか更新
				$model_notifications->update_notification_on_off();

				//$save_unsave = '保存';

				//$arr['saved'] = true;

			}
			else
			{
				//$save_unsave = '未処理';
			}



			// --------------------------------------------------
			//   アラート　成功
			// --------------------------------------------------

			$arr['alert_color'] = 'success';
			$arr['alert_title'] = 'OK';
			$arr['alert_message'] = '保存しました。';
			//$arr['alert_message'] = '保存しました。' . $save_unsave;


			if (isset($test))
			{
				Debug::$js_toggle_open = true;

				// echo '<br>$validated_on_off';
				// \Debug::dump($validated_on_off);
				//
				// echo '<br>$validated_on_off_browser';
				// \Debug::dump($validated_on_off_browser);
				//
				// echo '<br>$validated_on_off_app';
				// \Debug::dump($validated_on_off_app);
				//
				// echo '<br>$validated_on_off_mail';
				// \Debug::dump($validated_on_off_mail);
				//
				// echo '<br>$validated_on_off_mail';
				// \Debug::dump($validated_on_off_mail);


				// echo '<br>$validated_endpoint';
				// \Debug::dump($validated_endpoint);
				//
				// echo '<br>$validated_public_key';
				// \Debug::dump($validated_public_key);
				//
				// echo '<br>$validated_auth_token';
				// \Debug::dump($validated_auth_token);


				// echo '<br>$validated_receive_device';
				// \Debug::dump($validated_receive_device);
				//
				// echo '<br>$validated_delete_device';
				// \Debug::dump($validated_delete_device);
				//
				//
				// echo '<br>$validated_type';
				// \Debug::dump($validated_type);
				//
				// echo '<br>$validated_id';
				// \Debug::dump($validated_id);
				//
				// echo '<br>$validated_name';
				// \Debug::dump($validated_name);
				//
				// echo '<br>$validated_token';
				// \Debug::dump($validated_token);




				if (isset($save_notification_data_arr))
				{
					echo '<br>$save_notification_data_arr';
					\Debug::dump($save_notification_data_arr);
				}

				if (isset($save_arr))
				{
					echo '<br>$save_arr';
					\Debug::dump($save_arr);
				}

				echo '<br>$changed';
				\Debug::dump($changed);


			}
			//exit();



		}
		catch (Exception $e) {

			if (isset($test))
			{
				Debug::$js_toggle_open = true;
				\Debug::dump($e);
			}

			// $arr['alert_color'] = 'warning';
			// $arr['alert_title'] = 'エラー';
			// $arr['alert_message'] = '保存できませんでした。' . $e->getMessage();
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
	* お知らせID保存予約
	*
	* @return string HTMLコード
	*/
	public function post_save_notifications_id_reservation()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['id'] = 'a,b';
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
			$val->add_field('id', 'ID', 'required|valid_string[alpha,lowercase,numeric,commas]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_id = $val->validated('id');
				$id_arr = explode(',', $validated_id);


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$model_notifications = new Model_Notifications();
				$model_notifications->agent_type = $this->agent_type;
				$model_notifications->user_no = $this->user_no;
				$model_notifications->language = $this->language;
				$model_notifications->uri_base = $this->uri_base;
				$model_notifications->uri_current = $this->uri_current;


				// --------------------------------------------------
				//  データベースに保存
				// --------------------------------------------------

				$result_arr = $model_notifications->save_notifications_id_reservation($id_arr);


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					// echo '<br>$validated_id';
					// var_dump($validated_id);
//
					// echo '<br>$id_arr';
					// var_dump($id_arr);

					echo '<br>$result_arr';
					var_dump($result_arr);
				}


			}/*
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
				$arr['alert_message'] = '削除できませんでした。' . $error_message;

				if (isset($test)) echo $error_message;

			}
			*/
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
	* 参加コミュニティ読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_participation_community()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['user_no'] = 1;
			$_POST['type'] = 'close';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   ログインする必要があります。
			// --------------------------------------------------

			if (Input::post('type') == 'close' and ! $this->user_no)
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
			$val->add_callable('Original_Rule_User');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add_field('user_no', 'User No', 'required|match_pattern["^[1-9]\d*$"]|check_user_data');
			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(open|close)$/');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page = $val->validated('page');
				$validated_user_no = $val->validated('user_no');
				$validated_type = $val->validated('type');


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

				$original_code_common = new Original\Code\Common();
				$original_code_common->app_mode = $this->app_mode;
				$original_code_common->agent_type = $this->agent_type;
				$original_code_common->user_no = $this->user_no;
				$original_code_common->language = $this->language;
				$original_code_common->uri_base = $this->uri_base;
				$original_code_common->uri_current = $this->uri_current;


				// --------------------------------------------------
				//   データ取得
				// --------------------------------------------------

				$db_users_data_arr = $model_user->get_user_data($validated_user_no, null);


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				if ($validated_type == 'open' and isset($db_users_data_arr['participation_community']))
				{
					$code = $original_code_common->search_community_list(array('user_no' => $validated_user_no, 'game_list' => $db_users_data_arr['participation_community']), $validated_page);
				}
				else if ($validated_type == 'close' and isset($db_users_data_arr['participation_community_secret']))
				{
					$code = $original_code_common->search_community_list(array('user_no' => $validated_user_no, 'game_list' => $db_users_data_arr['participation_community_secret']), $validated_page);
				}

				if (isset($code)) $arr['code'] = $code;


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_page';
					var_dump($validated_page);

					echo '<br>$validated_user_no';
					var_dump($validated_user_no);

					echo '<br>$validated_type';
					var_dump($validated_type);

					echo '<br>$db_users_data_arr';
					var_dump($db_users_data_arr);

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
				$arr['alert_message'] = '削除できませんでした。' . $error_message;

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
	* ページ設定保存
	*
	* @return string HTMLコード
	*/
	public function post_save_config_page()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page_title'] = 'AAA';
			$_POST['user_id'] = 'bbb';
			//$_POST['top_image_1_delete'] = 1;
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
			$val->add_callable('Original_Rule_User');
			$val->add_field('page_title', 'ページタイトル', 'required|min_length[1]|max_length[50]');
			$val->add_field('user_id', 'プレイヤーID', 'required|min_length[3]|max_length[50]|valid_string[alpha,lowercase,numeric,dashes]|user_id_duplication');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page_title = $val->validated('page_title');
				$validated_user_id = $val->validated('user_id');
				$validated_top_image_1_delete = (Input::post('top_image_1_delete')) ? 1: null;
				$validated_top_image_2_delete = (Input::post('top_image_2_delete')) ? 1: null;
				$validated_top_image_3_delete = (Input::post('top_image_3_delete')) ? 1: null;


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

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;


				// --------------------------------------------------
				//   データベースに保存する画像情報
				// --------------------------------------------------

				$db_user_data_arr = $model_user->get_user_data($this->user_no, null);
				$top_image_arr = ($db_user_data_arr['top_image']) ? unserialize($db_user_data_arr['top_image']) : null;

				//$top_image_arr = array();
				// $top_image_arr = array('top_image_1' => array('width' => 800, 'height' => 500), 2 => array('width' => 640, 'height' => 427), 3 => array('width' => 800, 'height' => 480));
				//$top_image_arr = array('top_image_1' => array('width' => 600, 'height' => 299));


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();

				$save_arr['renewal_date'] = $datetime_now;
				$save_arr['page_title'] = $validated_page_title;
				$save_arr['user_id'] = $validated_user_id;


				// --------------------------------------------------
				//    アップロードされた画像があるかの確認
				// --------------------------------------------------

				$uploaded_image_existence = false;

				if (isset($_FILES))
				{
					foreach ($_FILES as $key => $value)
					{
						if ($value['size'] > 0) $uploaded_image_existence = true;
					}
				}


				// --------------------------------------------------
				//   画像設定
				// --------------------------------------------------

				// 保存先パス設定
				$path = DOCROOT . 'assets/img/user/' . $this->user_no . '/';


				// --------------------------------------------------
				//   画像削除
				// --------------------------------------------------

				$top_image_delete_arr = array();

				if ($validated_top_image_1_delete)
				{
					array_push($top_image_delete_arr, 1);
					unset($top_image_arr['top_image_1']);
				}

				if ($validated_top_image_2_delete)
				{
					array_push($top_image_delete_arr, 2);
					unset($top_image_arr['top_image_2']);
				}

				if ($validated_top_image_3_delete)
				{
					array_push($top_image_delete_arr, 3);
					unset($top_image_arr['top_image_3']);
				}

				if (count($top_image_delete_arr) > 0) $original_func_common->image_delete($path, 'top_image_', $top_image_delete_arr);


				// --------------------------------------------------
				//    画像保存
				// --------------------------------------------------

				if ($uploaded_image_existence)
				{

					// --------------------------------------------------
					//   画像保存
					// --------------------------------------------------

					$image_name_arr = array('top_image_1', 'top_image_2', 'top_image_3');
					$result_upload_image_arr = $original_func_common->zebra_image_save2($path, $image_name_arr, false, Config::get('limit_player_top_image'));

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

						if (isset($result_upload_image_arr['size_arr']['top_image_1'])) $top_image_arr['top_image_1'] = $result_upload_image_arr['size_arr']['top_image_1'];
						if (isset($result_upload_image_arr['size_arr']['top_image_2'])) $top_image_arr['top_image_2'] = $result_upload_image_arr['size_arr']['top_image_2'];
						if (isset($result_upload_image_arr['size_arr']['top_image_3'])) $top_image_arr['top_image_3'] = $result_upload_image_arr['size_arr']['top_image_3'];

						//$save_arr['top_image'] = serialize($top_image_arr);
						//var_dump($result_upload_image_arr['size_arr']);
					}

				}


				// --------------------------------------------------
				//   並び替え
				// --------------------------------------------------

				$save_top_image_arr = null;

				if (isset($top_image_arr['top_image_1'])) $save_top_image_arr['top_image_1'] = $top_image_arr['top_image_1'];
				if (isset($top_image_arr['top_image_2'])) $save_top_image_arr['top_image_2'] = $top_image_arr['top_image_2'];
				if (isset($top_image_arr['top_image_3'])) $save_top_image_arr['top_image_3'] = $top_image_arr['top_image_3'];

				$save_arr['top_image'] = (isset($save_top_image_arr)) ? serialize($save_top_image_arr) : null;


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_page_title';
					var_dump($validated_page_title);

					echo '<br>$validated_user_id';
					var_dump($validated_user_id);

					echo '<br>$top_image_delete_arr';
					var_dump($top_image_delete_arr);

					echo '<br>$top_image_arr';
					var_dump($top_image_arr);

					echo '<br>$save_arr';
					var_dump($save_arr);

				}

				//exit();

				// --------------------------------------------------
				//   データベースに保存
				// --------------------------------------------------

				$result = $model_user->update_user_data($this->user_no, $save_arr);


				// --------------------------------------------------
				//   Users Data / User ID キャッシュ削除　ヘッダーのPlayerリンク用
				// --------------------------------------------------

				Cache::delete('users_data.user_id.' . $this->user_no);


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

				//$arr['test'] = $error_message;
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
	* 通知設定保存
	*
	* @return string HTMLコード
	*/
	/*
	public function post_save_config_notification()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		$test = true;

		if (isset($test))
		{
			$_POST['page_title'] = 'aaa';
			$_POST['user_id'] = 'bbb';
			//$_POST['top_image_1_delete'] = 1;
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
			$val->add_callable('Original_Rule_User');
			$val->add_field('page_title', 'ページタイトル', 'required|min_length[1]|max_length[50]');
			$val->add_field('user_id', 'プレイヤーID', 'required|min_length[3]|max_length[50]|valid_string[alpha,lowercase,numeric,dashes]|user_id_duplication');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page_title = $val->validated('page_title');
				$validated_user_id = $val->validated('user_id');
				$validated_top_image_1_delete = (Input::post('top_image_1_delete')) ? 1: null;
				$validated_top_image_2_delete = (Input::post('top_image_2_delete')) ? 1: null;
				$validated_top_image_3_delete = (Input::post('top_image_3_delete')) ? 1: null;


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

				$original_func_common = new Original\Func\Common();
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;


				// --------------------------------------------------
				//   データベースに保存する画像情報
				// --------------------------------------------------

				$db_user_data_arr = $model_user->get_user_data($this->user_no, null);
				$top_image_arr = ($db_user_data_arr['top_image']) ? unserialize($db_user_data_arr['top_image']) : null;

				//$top_image_arr = array();
				// $top_image_arr = array('top_image_1' => array('width' => 800, 'height' => 500), 2 => array('width' => 640, 'height' => 427), 3 => array('width' => 800, 'height' => 480));
				//$top_image_arr = array('top_image_1' => array('width' => 600, 'height' => 299));


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();

				$save_arr['renewal_date'] = $datetime_now;
				$save_arr['page_title'] = $validated_page_title;
				$save_arr['user_id'] = $validated_user_id;


				// --------------------------------------------------
				//    アップロードされた画像があるかの確認
				// --------------------------------------------------

				$uploaded_image_existence = false;

				if (isset($_FILES))
				{
					foreach ($_FILES as $key => $value)
					{
						if ($value['size'] > 0) $uploaded_image_existence = true;
					}
				}


				// --------------------------------------------------
				//   画像設定
				// --------------------------------------------------

				// 保存先パス設定
				$path = DOCROOT . 'assets/img/user/' . $this->user_no . '/';


				// --------------------------------------------------
				//   画像削除
				// --------------------------------------------------

				$top_image_delete_arr = array();

				if ($validated_top_image_1_delete)
				{
					array_push($top_image_delete_arr, 1);
					unset($top_image_arr['top_image_1']);
				}

				if ($validated_top_image_2_delete)
				{
					array_push($top_image_delete_arr, 2);
					unset($top_image_arr['top_image_2']);
				}

				if ($validated_top_image_3_delete)
				{
					array_push($top_image_delete_arr, 3);
					unset($top_image_arr['top_image_3']);
				}

				if (count($top_image_delete_arr) > 0) $original_func_common->image_delete($path, 'top_image_', $top_image_delete_arr);


				// --------------------------------------------------
				//    画像保存
				// --------------------------------------------------

				if ($uploaded_image_existence)
				{

					// --------------------------------------------------
					//   画像保存
					// --------------------------------------------------

					$image_name_arr = array('top_image_1', 'top_image_2', 'top_image_3');
					$result_upload_image_arr = $original_func_common->zebra_image_save2($path, $image_name_arr, false, Config::get('limit_player_top_image'));

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

						if (isset($result_upload_image_arr['size_arr']['top_image_1'])) $top_image_arr['top_image_1'] = $result_upload_image_arr['size_arr']['top_image_1'];
						if (isset($result_upload_image_arr['size_arr']['top_image_2'])) $top_image_arr['top_image_2'] = $result_upload_image_arr['size_arr']['top_image_2'];
						if (isset($result_upload_image_arr['size_arr']['top_image_3'])) $top_image_arr['top_image_3'] = $result_upload_image_arr['size_arr']['top_image_3'];

						//$save_arr['top_image'] = serialize($top_image_arr);
						//var_dump($result_upload_image_arr['size_arr']);
					}

				}


				// --------------------------------------------------
				//   並び替え
				// --------------------------------------------------

				$save_top_image_arr = null;

				if (isset($top_image_arr['top_image_1'])) $save_top_image_arr['top_image_1'] = $top_image_arr['top_image_1'];
				if (isset($top_image_arr['top_image_2'])) $save_top_image_arr['top_image_2'] = $top_image_arr['top_image_2'];
				if (isset($top_image_arr['top_image_3'])) $save_top_image_arr['top_image_3'] = $top_image_arr['top_image_3'];

				$save_arr['top_image'] = (isset($save_top_image_arr)) ? serialize($save_top_image_arr) : null;


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_page_title';
					var_dump($validated_page_title);

					echo '<br>$validated_user_id';
					var_dump($validated_user_id);

					echo '<br>$top_image_delete_arr';
					var_dump($top_image_delete_arr);

					echo '<br>$top_image_arr';
					var_dump($top_image_arr);

					echo '<br>$save_arr';
					var_dump($save_arr);

				}

				//exit();

				// --------------------------------------------------
				//   データベースに保存
				// --------------------------------------------------

				$result = $model_user->update_user_data($this->user_no, $save_arr);


				// --------------------------------------------------
				//   Users Data / User ID キャッシュ削除　ヘッダーのPlayerリンク用
				// --------------------------------------------------

				Cache::delete('users_data.user_id.' . $this->user_no);


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

				//$arr['test'] = $error_message;
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
	*/



	/**
	* Eメール仮登録
	*
	* @return string HTMLコード
	*/
	public function post_save_email()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['email'] = 'rodinia@hotmail.co.jp';
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
			$val->add_callable('Original_Rule_User');
			$val->add_field('email', 'Eメール', 'required|valid_email|email_duplication_users_login|email_duplication_provisional_mail');

			if ($val->run())
			{
				$validated_email = $val->validated('email');


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_email';
					var_dump($validated_email);

				}
				//exit();


				// --------------------------------------------------
				//   仮登録
				// --------------------------------------------------

				$model_mail = new Model_Mail();
				$return_hash = $model_mail->save_provisional_mail($this->user_no, $validated_email);


				// --------------------------------------------------
				//   メール送信
				// --------------------------------------------------

				if (Fuel::$env != 'test')
				{
					$body = '' . "\n";
					$body = 'こちらのURLにアクセスするとメール登録が完了します。' . "\n";
					$body .= Uri::base() . 'config/mail/' . $return_hash . "\n\n";
					$body .= 'Game Users' . "\n";
					$body .= Uri::base();

					$original_common_mail = new Original\Common\Mail();
					$result_to = $original_common_mail->to('mail@gameusers.org', 'Game Users', $validated_email, '仮登録', 'Game Users 仮登録メール', $body);
				}


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '仮登録メールが送信されました。';

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
				$arr['alert_message'] = $error_message;

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
	* Eメール削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_email()
	{

		//$_POST['email'] = 'rodinia@hotmail.co.jp';


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
			//   メールアドレス削除
			// --------------------------------------------------

			$model_mail = new Model_Mail();
			$model_mail->delete_mail($this->user_no);

			// 通知できるユーザーか更新
			$model_notifications = new Model_Notifications();
			$model_notifications->agent_type = $this->agent_type;
			$model_notifications->user_no = $this->user_no;
			$model_notifications->language = $this->language;
			$model_notifications->uri_base = $this->uri_base;
			$model_notifications->uri_current = $this->uri_current;
			$model_notifications->update_notification_on_off();


			// --------------------------------------------------
			//   アラート　成功
			// --------------------------------------------------

			$arr['alert_color'] = 'success';
			$arr['alert_title'] = 'OK';
			$arr['alert_message'] = 'メールアドレスを削除しました。';

		}
		catch (Exception $e) {}


		//var_dump($arr);

		return $this->response($arr);

	}




	/**
	* ログインID保存
	*
	* @return string HTMLコード
	*/
	public function post_save_login_username()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['login_username'] = '123456';
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
			$val->add_callable('Original_Rule_User');
			$val->add_field('login_username', 'ログインID', 'required|min_length[3]|max_length[25]|valid_string[alpha,numeric,dashes]|login_username_duplication');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_login_username = $val->validated('login_username');


				// --------------------------------------------------
				//   共通処理　インスタンス作成
				// --------------------------------------------------

				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$original_common_text = new Original\Common\Text();


				// --------------------------------------------------
				//   データ取得
				// --------------------------------------------------

				$db_user_login_arr = $model_user->get_user_login($this->user_no);

				$auth = Auth::instance();
				$login_username_hash = $auth->hash_password((string) $validated_login_username);


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_login_username';
					var_dump($validated_login_username);

					echo '<br>$login_username_hash';
					var_dump($login_username_hash);

					echo '<br>db_password';
					var_dump($db_user_login_arr['password']);

				}
				//exit();

				if ($db_user_login_arr['password'] == $login_username_hash)
				{
					$arr['alert_color'] = 'warning';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'ログインIDとログインパスワードに同じ文字列を使用することはできません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   ID保存＆再ログイン
				// --------------------------------------------------

				$auth = Auth::instance();
				$auth->update_user($this->user_no, array('login_username' => $validated_login_username));


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
				$arr['alert_message'] = $error_message;

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
	* ログインパスワード保存
	*
	* @return string HTMLコード
	*/
	public function post_save_login_password()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['login_password'] = 'abcdef';
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
			$val->add_field('login_password', 'パスワード', 'required|min_length[6]|max_length[32]|valid_string[alpha,numeric,dashes]');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_login_password = $val->validated('login_password');


				// --------------------------------------------------
				//   共通処理　インスタンス作成
				// --------------------------------------------------

				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;

				$original_common_text = new Original\Common\Text();


				// --------------------------------------------------
				//   データ取得
				// --------------------------------------------------

				$db_user_login_arr = $model_user->get_user_login($this->user_no);

				if ($db_user_login_arr['username'] == $validated_login_password)
				{
					$arr['alert_color'] = 'warning';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'ログインIDとログインパスワードに同じ文字列を使用することはできません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   パスワードの強度判定
				// --------------------------------------------------

				$password_strength = $original_common_text->check_password_strength($validated_login_password);

				if ($password_strength < 2)
				{
					$arr['alert_color'] = 'warning';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'パスワードの強度が足りません。';
					throw new Exception('Error');
				}


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_login_password';
					var_dump($validated_login_password);



				}
				//exit();


				// --------------------------------------------------
				//   パスワード保存
				// --------------------------------------------------

				$auth = Auth::instance();
				$auth->update_user($this->user_no, array('login_password' => $validated_login_password));


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
				$arr['alert_message'] = $error_message;

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
	* アカウント削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_player_account()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['login_password'] = 'abcdef';
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


			if ($val->run())
			{

				// --------------------------------------------------
				//   共通処理　インスタンス作成
				// --------------------------------------------------

				$model_user = new Model_User();
				$model_user->agent_type = $this->agent_type;
				$model_user->user_no = $this->user_no;
				$model_user->language = $this->language;
				$model_user->uri_base = $this->uri_base;
				$model_user->uri_current = $this->uri_current;


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;
					/*
					echo '<br>$db_user_data_arr';
					var_dump($db_user_data_arr);
					*/

				}
				//exit();


				// --------------------------------------------------
				//   データベース更新
				// --------------------------------------------------

				$result = $model_user->delete_player_account($this->user_no, null);


				// --------------------------------------------------
				//   ログアウト
				// --------------------------------------------------

				$auth = Auth::instance();
				$auth->logout();


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '削除しました。';

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
				$arr['alert_message'] = '削除できませんでした。';

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
	* 広告編集フォームを読み込む
	*
	* @return string HTMLコード
	*/
	public function post_read_form_advertisement()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 3;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   ログインする必要があります。
			// --------------------------------------------------

			if (Input::post('type') == 'close' and ! $this->user_no)
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
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page = $val->validated('page');


				// --------------------------------------------------
				//   インスタンス作成
				// --------------------------------------------------

				$original_code_player = new Original\Code\Player();


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$temp_arr = array(
					'page' => $validated_page
				);

				$arr['code'] = $original_code_player->form_advertisement_list($temp_arr);



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_page';
					var_dump($validated_page);

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
				$arr['alert_message'] = '削除できませんでした。' . $error_message;

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
	* AmazonトラッキングID保存
	*
	* @return string HTMLコード
	*/
	public function post_save_amazon_tracking_id()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['tracking_id'] = 'tracking_id-22';
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

			if ( ! USER_NO)
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
			$val->add_field('tracking_id', 'トラッキングID', 'min_length[3]|max_length[30]|valid_string[alpha,numeric,dashes]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_tracking_id = ($val->validated('tracking_id')) ? $val->validated('tracking_id') : null;


				// --------------------------------------------------
				//   インスタンス作成
				// --------------------------------------------------

				$model_user = new Model_User();


				// --------------------------------------------------
				//   データ取得・データ処理
				// --------------------------------------------------

				$db_user_data_arr = $model_user->get_user_data(USER_NO);


				if (isset($db_user_data_arr['user_advertisement']))
				{
					$user_advertisement_arr = unserialize($db_user_data_arr['user_advertisement']);
					$user_advertisement_arr['amazon_tracking_id'] = $validated_tracking_id;
				}
				else
				{
					$user_advertisement_arr = array(
						'amazon_tracking_id' => $validated_tracking_id
					);
				}

				$user_advertisement = serialize($user_advertisement_arr);



				// --------------------------------------------------
				//   保存用配列作成
				// --------------------------------------------------

				$save_arr = array(
					'user_advertisement' => $user_advertisement
				);



				// --------------------------------------------------
				//   データベースに保存
				// --------------------------------------------------

				$result_arr = $model_user->update_user_data(USER_NO, $save_arr);




				if (isset($test))
				{
					Debug::$js_toggle_open = true;

					echo '<br>$db_user_data_arr';
					\Debug::dump($db_user_data_arr);

					echo '<br>$user_advertisement_arr';
					\Debug::dump($user_advertisement_arr);

					echo '<br>$user_advertisement';
					\Debug::dump($user_advertisement);

					echo '<br>$save_arr';
					\Debug::dump($save_arr);

				}
				//exit();


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '登録されました。';

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
				$arr['alert_message'] = $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
			if (isset($test)) echo $e->getMessage();
		}


		if (isset($test))
		{
			echo '<br>$arr';
			\Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}




	/**
	* 広告保存
	*
	* @return string HTMLコード
	*/
	public function post_save_advertisement()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['advertisement_no'] = 7;
			$_POST['name'] = 'adsense_link';
			$_POST['code'] = '【ユーザー広告　A1】';
			$_POST['code_sp'] = '【ユーザー広告　A SP2】';
			$_POST['comment'] = 'comment';
			//$_POST['ad_default'] = 'wiki_1';
			//$_POST['hide_myself'] = 1;
			//$_POST['approval'] = 1;
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
			$val->add_callable('Original_Rule_Advertisement');
			$val->add_field('advertisement_no', '広告No', 'check_advertisement_no');
			$val->add_field('name', '広告名', 'required|min_length[1]|max_length[20]|valid_string[alpha,numeric,dashes]');
			$val->add_field('code', '広告コード', 'required|min_length[1]|max_length[1000]');
			$val->add_field('code_sp', '広告コード / スマートフォン用', 'min_length[1]|max_length[1000]');
			$val->add_field('comment', '広告についての説明', 'min_length[1]|max_length[1000]');
			if (Input::post('approval')) $val->add('approval', 'approval')->add_rule('match_pattern', '/^(1|2)$/');
			//$val->add('ad_default', 'デフォルト広告に設定')->add_rule('match_pattern', '/^(wiki_1|wiki_2)$/');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_advertisement_no = ($val->validated('advertisement_no')) ? $val->validated('advertisement_no') : null;
				$validated_name = $val->validated('name');
				$validated_code = html_entity_decode($val->validated('code'), ENT_QUOTES);
				$validated_code_sp = ($val->validated('code_sp')) ? html_entity_decode($val->validated('code_sp'), ENT_QUOTES) : null;
				$validated_comment = ($val->validated('comment')) ? $val->validated('comment') : null;
				$validated_ad_default = ($val->validated('ad_default')) ? $val->validated('ad_default') : null;
				$validated_hide_myself = (Input::post('hide_myself')) ? 1 : null;
				$validated_approval = (Input::post('approval')) ? $val->validated('approval') : null;


				// --------------------------------------------------
				//   インスタンス作成
				// --------------------------------------------------

				$model_advertisement = new Model_Advertisement();

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();


				// --------------------------------------------------
				//   広告の保存できる最大数　これを越えて保存はできない
				// --------------------------------------------------

				$limit_advertisement = \Config::get('limit_advertisement');


				// --------------------------------------------------
				//   保存用配列作成
				// --------------------------------------------------

				$save_arr = array(
					'renewal_date' => $datetime_now,
					'name' => $validated_name,
					'code' => $validated_code,
					'code_sp' => $validated_code_sp,
					'comment' => $validated_comment,
					'ad_default' => $validated_ad_default,
					'hide_myself' => $validated_hide_myself
				);

				// Debug::$js_toggle_open = true;
				// echo '<br>$save_arr';
				// \Debug::dump($save_arr);
				// exit();


				// --------------------------------------------------
				//   運営の場合
				// --------------------------------------------------

				if (Auth::member(100))
				{
					//echo 'aaa';

					// 更新しない要素を削除する
					unset($save_arr['renewal_date']);
					unset($save_arr['name']);
					unset($save_arr['code']);
					unset($save_arr['code_sp']);
					unset($save_arr['ad_default']);
					unset($save_arr['hide_myself']);

					// 追加
					$save_arr['advertisement_no'] = $validated_advertisement_no;
					$save_arr['approval'] = $validated_approval;


					if ($validated_approval == 1)
					{
						$arr['label_approval_code'] = '<span class="label label-warning">承認済み</span>';
					}
					else if ($validated_approval == 2)
					{
						$arr['label_approval_code'] = '<span class="label label-default">掲載不可</span>';
					}
					else
					{
						$arr['label_approval_code'] = '<span class="label label-default">未承認</span>';
					}

					$result_arr = $model_advertisement->update_advertisement($save_arr);

				}

				// --------------------------------------------------
				//   更新する場合
				// --------------------------------------------------

				else if ($validated_advertisement_no)
				{

					// ----------------------------------------
					//   データ取得
					// ----------------------------------------

					$tmp_arr = array(
						'advertisement_no' => $validated_advertisement_no,
						'user_no' => USER_NO,
						'limit' => $limit_advertisement,
						'page' => 1
					);

					$result_arr = $model_advertisement->get_advertisement($tmp_arr);
					$db_advertisement_arr = $result_arr['data_arr'];


					// ----------------------------------------
					//   他人の広告を更新しようとした場合は処理終了
					// ----------------------------------------

					if (empty($db_advertisement_arr[0]['advertisement_no'])) exit();


					// ----------------------------------------
					//   他人の広告を更新しようとした場合は処理終了
					// ----------------------------------------

					//if ($db_advertisement_arr[0]['user_no'] != USER_NO) exit();


					// ----------------------------------------
					//   広告名重複チェック
					// ----------------------------------------

					$temp_arr = array(
						'not_advertisement_no' => $validated_advertisement_no,
						'user_no' => USER_NO,
						'name' => $validated_name
					);

					$check_duplication_name_arr = $model_advertisement->get_advertisement_current($temp_arr);

					if (isset($check_duplication_name_arr['advertisement_no']))
					{
						$arr['alert_color'] = 'warning';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = '同じ広告名がすでにあります。';
						throw new Exception('Error');
					}

					// echo 'aaaaaaaaaa';
					// \Debug::dump($check_duplication_name_arr);
					// exit();

					$save_arr['advertisement_no'] = $validated_advertisement_no;

					// コードが変更されている場合は、非承認に戻す
					if ($validated_code !== $db_advertisement_arr[0]['code'] or $validated_code_sp !== $db_advertisement_arr[0]['code_sp'])
					{
						$save_arr['approval'] = null;

						$arr['label_approval_code'] = '<span class="label label-default">未承認</span>';
					}

					// ad_default置き換え
					//if ($validated_ad_default) $result_arr = $model_advertisement->change_ad_default(array('ad_default' => $validated_ad_default));

					$result_arr = $model_advertisement->update_advertisement($save_arr);

				}

				// --------------------------------------------------
				//   新規に挿入する場合
				// --------------------------------------------------

				else
				{

					// --------------------------------------------------
					//   総数取得
					// --------------------------------------------------

					$tmp_arr = array(
						'user_no' => USER_NO,
						'limit' => 1,
						'page' => 1,
						'get_total' => true
					);

					$result_arr = $model_advertisement->get_advertisement($tmp_arr);
					$db_advertisement_total = $result_arr['total'];


					// --------------------------------------------------
					//   規定数以上の広告は保存できない
					// --------------------------------------------------

					if ($db_advertisement_total >= $limit_advertisement) exit();



					$save_arr['regi_date'] = $datetime_now;
					$save_arr['user_no'] = USER_NO;

					// ad_default置き換え
					//if ($validated_ad_default) $result_arr = $model_advertisement->change_ad_default(array('ad_default' => $validated_ad_default));

					$result_arr = $model_advertisement->insert_advertisement($save_arr);

					//$arr['alert_message'] = '保存されました。' . $datetime_now . $validated_name . $validated_code . $validated_code_sp . $validated_comment . $validated_ad_default . $validated_hide_myself . USER_NO;
					//$arr['alert_message'] = $result_arr['error'];

				}





				if (isset($test))
				{
					Debug::$js_toggle_open = true;

					echo '<br>$validated_advertisement_no';
					\Debug::dump($validated_advertisement_no);

					echo '<br>$validated_code';
					\Debug::dump($validated_code);

					echo '<br>$validated_code_sp';
					\Debug::dump($validated_code_sp);

					echo '<br>$validated_comment';
					\Debug::dump($validated_comment);

					echo '<br>$validated_hide_myself';
					\Debug::dump($validated_hide_myself);

					if (isset($db_advertisement_arr))
					{
						echo '<br>$db_advertisement_arr';
						\Debug::dump($db_advertisement_arr);
					}

					// echo '<br>$temp_arr2';
					// \Debug::dump($temp_arr2);

					if (isset($db_advertisement_total))
					{
						echo '<br>$db_advertisement_total';
						\Debug::dump($db_advertisement_total);
					}

					echo '<br>$save_arr';
					\Debug::dump($save_arr);

					echo '<br>$result_arr';
					\Debug::dump($result_arr);

				}
				//exit();


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
				$arr['alert_message'] = $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
			if (isset($test)) echo $e->getMessage();
		}


		if (isset($test))
		{
			echo '<br>$arr';
			\Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}



}
