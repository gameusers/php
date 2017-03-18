<?php

class Controller_Rest_Co extends Controller_Rest_Base
{

	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}



	/**
	* 告知編集フォーム表示
	*
	*/
	public function post_show_announcement_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
			$_POST['announcement_no'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');
			if (Input::post('announcement_no')) $val->add_field('announcement_no', 'Announcement No', 'required|match_pattern["^[1-9]\d*$"]|check_announcement_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_announcement_no = $val->validated('announcement_no');
				$validated_create_announcement_box = (Input::post('create_announcement_box')) ? 1: null;
				//var_dump($validated_announcement_no);



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

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   閲覧権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_announcement'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '編集権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    告知情報取得
				// --------------------------------------------------

				if ($validated_announcement_no)
				{
					$db_announcement_arr = $model_co->get_announcement($validated_announcement_no);

					$db_announcement_arr['image'] = (isset($db_announcement_arr['image'])) ? unserialize($db_announcement_arr['image']) : null;
					$db_announcement_arr['movie'] = (isset($db_announcement_arr['movie'])) ? unserialize($db_announcement_arr['movie']) : null;

					$data_arr['renewal_date'] = $db_announcement_arr['renewal_date'];
					$data_arr['handle_name'] = null;
					$data_arr['anonymity'] = null;
					$data_arr['title'] = $db_announcement_arr['title'];
					$data_arr['comment'] = $db_announcement_arr['comment'];
					$data_arr['image'] = $db_announcement_arr['image'];
					$data_arr['movie'] = $db_announcement_arr['movie'];
				}
				else
				{
					$data_arr = null;
				}


				// --------------------------------------------------
				//    ユーザー情報取得
				// --------------------------------------------------

				if ($member_arr[$this->user_no]['profile_no'])
				{
					$profile_arr = $model_user->get_profile($member_arr[$this->user_no]['profile_no']);
				}
				else
				{
					$profile_arr = $model_user->get_user_data_personal_box($this->user_no, null);
				}


				// --------------------------------------------------
				//    画像URL設定
				// --------------------------------------------------

				$image_url_base = ($validated_announcement_no) ? $this->uri_base . 'assets/img/announcement/' . $validated_announcement_no . '/' : null;


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = View::forge('parts/form_common_ver2_view');
				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set('login_user_no', $this->user_no);
				$view->set('datetime_now', $datetime_now);
				$view->set('profile_arr', $profile_arr);
				$view->set_safe('online_limit', $config_arr['online_limit']);
				$view->set_safe('anonymity', false);
				$view->set('func_name', 'GAMEUSERS.uc.saveAnnouncement');

				$func_argument_arr = ($validated_announcement_no) ? array($validated_community_no, $validated_announcement_no) : array($validated_community_no, 'null');
				$view->set('func_argument_arr', $func_argument_arr);
				$view->set('func_name_return', 'GAMEUSERS.uc.removeAnnouncementForm');
				$view->set('func_argument_return_arr', null);

				if ($validated_announcement_no)
				{
					$view->set('func_name_delete', 'GAMEUSERS.uc.deleteAnnouncement');
					$view->set('func_argument_delete_arr', array($validated_announcement_no));
				}

				$view->set('data_arr', $data_arr);
				$view->set('image_url_base', $image_url_base);


				if ($validated_create_announcement_box)
				{
					$code = '<div id="announcement_box" data-page="1">';
					$code .= '  <h2 id="heading_black">告知</h2>';
					$code .= '  <div class="panel panel-default margin_bottom_20">';
					$code .= '    <div class="panel-body">';

					$code .= $view->render();

					$code .= '    </div>';
					$code .= '  </div>';
					$code .= '</div>';
				}
				else
				{
					//$code = '<div id="announcement_form_box" style="padding: 10px;">';
					$code = $view->render();
					//$code .= '</div>';
				}

				$arr['code'] = $code;


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;



					echo '<br>$validated_community_no';
					var_dump($validated_community_no);

					echo '<br>$validated_announcement_no';
					var_dump($validated_announcement_no);

					if (isset($db_announcement_arr))
					{
						echo '<br>$db_announcement_arr';
						var_dump($db_announcement_arr);
					}

					echo '<br>$member_arr';
					var_dump($member_arr);

					echo '<br>$config_arr';
					var_dump($config_arr);

					echo $arr['code'];

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
	* 告知編集
	*
	* @return string HTMLコード
	*/
	public function post_save_announcement()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		// $test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
			$_POST['announcement_no'] = 1;
			$_POST['title'] = 'AAA';
			$_POST['comment'] = 'BBB';

			$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('community_no', 'Community No', 'required|check_community_no');
			if (Input::post('announcement_no')) $val->add_field('announcement_no', 'Announcement No', 'required|match_pattern["^[1-9]\d*$"]|check_announcement_no');
			$val->add_field('title', 'タイトル', 'required|min_length[1]|max_length[50]');
			$val->add_field('comment', 'コメント', 'required|min_length[1]|max_length[2000]');
			$val->add_field('movie_url', 'Movie URL', 'valid_url|check_movie_url');
			//$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_announcement_no = $val->validated('announcement_no');
				$validated_title = $val->validated('title');
				$validated_comment = $val->validated('comment');
				//$validated_page = $val->validated('page');
				$validated_movie_url = $val->validated('movie_url');
				$validated_image_1_delete = (Input::post('image_1_delete')) ? 1: null;


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$model_notifications = new Model_Notifications();
				$model_notifications->agent_type = $this->agent_type;
				$model_notifications->user_no = $this->user_no;
				$model_notifications->language = $this->language;
				$model_notifications->uri_base = $this->uri_base;
				$model_notifications->uri_current = $this->uri_current;

				$model_common = new Model_Common();
				$model_common->user_no = $this->user_no;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);

				if ($validated_announcement_no) $db_announcement_arr = $model_co->get_announcement($validated_announcement_no);

				$image_arr = (isset($db_announcement_arr['image'])) ? $db_announcement_arr['image'] : null;
				$movie_arr = (isset($db_announcement_arr['movie'])) ? $db_announcement_arr['movie'] : null;


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   編集権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_announcement'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '編集権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


				// --------------------------------------------------
				//   削除済みユーザー
				// --------------------------------------------------

				if ($login_profile_data_arr === false)
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


				// --------------------------------------------------
				//   動画
				// --------------------------------------------------

				if ($validated_movie_url)
				{
					if ($movie_arr) $movie_arr = unserialize($movie_arr);

					$movie_arr = $original_func_common->return_movie(array($validated_movie_url), $movie_arr, Config::get('limit_announcement_movie'));
					$movie_arr = serialize($movie_arr);
				}
				else
				{
					$movie_arr = null;
				}


				// --------------------------------------------------
				//   保存用配列作成
				// --------------------------------------------------

				$save_arr = array(
					'regi_date' => $datetime_now,
					'renewal_date' => $datetime_now,
					'community_no' => $validated_community_no,
					'user_no' => $this->user_no,
					'profile_no' => $member_arr[$this->user_no]['profile_no'],
					'title' => $validated_title,
					'comment' => $validated_comment,
					'image' => $image_arr,
					'movie' => $movie_arr
				);


				// --------------------------------------------------
				//   新規作成の場合
				// --------------------------------------------------

				if (empty($validated_announcement_no))
				{

					// --------------------------------------------------
					//   データベースに挿入
					// --------------------------------------------------

					$result_arr = $model_co->insert_announcement($save_arr);


					// --------------------------------------------------
					//   過去に作成した告知と内容が重複しています。
					// --------------------------------------------------

					if (isset($result_arr['error']))
					{
						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = '過去に作成した告知と内容が重複しています。';
						throw new Exception('Error');
					}

					$validated_announcement_no = $result_arr[0];

				}



				// --------------------------------------------------
				//   画像設定
				// --------------------------------------------------

				// 保存先パス設定
				$path = DOCROOT . 'assets/img/announcement/' . $validated_announcement_no . '/';


				// --------------------------------------------------
				//   画像削除
				// --------------------------------------------------

				if ($validated_image_1_delete)
				{
					$original_func_common->image_delete($path, 'image_', array(1));
					$save_arr['image'] = null;
				}


				// --------------------------------------------------
				//    画像保存
				// --------------------------------------------------

				if ($validated_announcement_no and $uploaded_image_existence)
				{

					// --------------------------------------------------
					//   画像保存
					// --------------------------------------------------

					$image_name_arr = array('image_1');
					$result_upload_image_arr = $original_func_common->zebra_image_save2($path, $image_name_arr, true, Config::get('limit_announcement_image'));

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


				// --------------------------------------------------
				//   更新する場合
				// --------------------------------------------------

				if ($validated_announcement_no)
				{

					unset($save_arr['regi_date'], $save_arr['community_no']);


					// --------------------------------------------------
					//   データベース更新
					// --------------------------------------------------

					$result_arr = $model_co->update_announcement($validated_announcement_no, $save_arr);

				}


				// if (isset($test))
				// {
				// 	//Debug::$js_toggle_open = true;
				//
				// 	echo '<br>$validated_community_no';
				// 	var_dump($validated_community_no);
				//
				// 	if (isset($validated_announcement_no))
				// 	{
				// 		echo '<br>$validated_announcement_no';
				// 		var_dump($validated_announcement_no);
				// 	}
				//
				// 	echo '<br>$validated_title';
				// 	var_dump($validated_title);
				//
				// 	echo '<br>$validated_comment';
				// 	var_dump($validated_comment);
				//
				// 	echo '<br>$validated_movie_url';
				// 	var_dump($validated_movie_url);
				//
				// 	echo '<br>$validated_page';
				// 	var_dump($validated_page);
				//
				// 	if (isset($db_announcement_arr))
				// 	{
				// 		echo '<br>$db_announcement_arr';
				// 		var_dump($db_announcement_arr);
				// 	}
				//
				// 	echo '<br>$login_profile_data_arr';
				// 	var_dump($login_profile_data_arr);
				//
				// 	echo '<br>$_FILES';
				// 	var_dump($_FILES);
				//
				// 	echo '<br>uploaded_image_existence';
				// 	var_dump($uploaded_image_existence);
				//
				// 	echo '<br>$image_arr';
				// 	var_dump(unserialize($image_arr));
				//
				// 	echo '<br>$movie_arr';
				// 	var_dump(unserialize($movie_arr));
				//
				//
				// 	echo '<br>$save_arr';
				// 	var_dump($save_arr);
				//
				// }
				//exit();



				// --------------------------------------------------
				//   お知らせ保存
				// --------------------------------------------------

				$game_list_arr = $original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

				$save_notifications_arr = array(
					'regi_date' => $datetime_now,
					'target_user_no' => null,
					'community_no' => $validated_community_no,
					'game_no' => $game_list_arr[0],
					'type1' => 'uc',
					'type2' => 'announcement',
					'title' => $validated_title,
					'name' => null,
					'comment' => $validated_comment
				);

				if (isset($login_profile_data_arr['profile_no'])) $save_notifications_arr['profile_no'] = $login_profile_data_arr['profile_no'];

				$model_notifications->save_notifications($save_notifications_arr);


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

				$arr = $original_code_co->announcement(['community_no' => $validated_community_no, 'page' => 1]);
				//\Debug::dump($arr);

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
	* 告知削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_announcement()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$_POST['announcement_no'] = 1;


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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('announcement_no', 'Announcement No', 'required|match_pattern["^[1-9]\d*$"]|check_announcement_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_announcement_no = $val->validated('announcement_no');
				//var_dump($validated_announcement_no);
				//exit();


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				$db_announcement_arr = $model_co->get_announcement($validated_announcement_no);
				$db_community_arr = $model_co->get_community($db_announcement_arr['community_no'], null);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   編集権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_announcement'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '編集権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   データベースに保存
				// --------------------------------------------------

				$result = $model_co->delete_announcement($validated_announcement_no, $datetime_now);


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view_content_announcement = $original_code_co->announcement($db_community_arr, $authority_arr, 1);
				$arr['code'] = ($view_content_announcement) ? $view_content_announcement->render() : null;

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

				echo $error_message;
				$arr['test'] = 'エラー ' . $error_message;
				*/
			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//echo $e->getMessage();
		}


		//var_dump($arr);

		return $this->response($arr);

	}









	/**
	* BBSスレッド一覧読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_thread_list()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
			$_POST['page'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_page = $val->validated('page');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->host = $this->host;
				$original_code_co->user_agent = $this->user_agent;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   閲覧権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['read_bbs'] and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '閲覧権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$arr['code'] = $original_code_co->bbs_thread_list($db_community_arr, $authority_arr, $validated_page);

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
	* BBS読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['community_no'] = 1;
			$_POST['bbs_thread_no'] = 1;
			//$_POST['page'] = 1;
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

			$val->add_callable('Original_Rule_Co');

			if (Input::post('community_no'))
			{
				$val->add_field('community_no', 'Community No', 'required|check_community_no');
				$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			}
			else if (Input::post('bbs_thread_no'))
			{
				$val->add_field('bbs_thread_no', 'BBS Thread No', 'required|check_bbs_thread_no');
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

				$validated_community_no = $val->validated('community_no');
				$validated_bbs_thread_no = $val->validated('bbs_thread_no');
				$validated_page = $val->validated('page');
				//var_dump($validated_announcement_no, $validated_subject, $validated_comment);


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->host = $this->host;
				$original_code_co->user_agent = $this->user_agent;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				if ($validated_community_no)
				{
					$db_community_arr = $model_co->get_community($validated_community_no, null);
				}
				else
				{
					$db_bbs_thread_arr = $model_co->get_bbs_thread($validated_bbs_thread_no);
					$db_community_arr = $model_co->get_community($db_bbs_thread_arr['community_no'], null);
				}



				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   閲覧権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['read_bbs'] and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '閲覧権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				if ($validated_community_no)
				{
					$view = $original_code_co->bbs($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, $validated_page);
				}
				else
				{
					$view = $original_code_co->bbs_appoint_thread_no($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, $validated_bbs_thread_no);
				}

				$arr['code'] = $view->render();


				if (isset($test))
				{
					echo "<br>db_community_arr";
					var_dump($db_community_arr);
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
	* BBSコメント　読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_comment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['bbs_thread_no'] = 1;
			$_POST['page'] = 2;
		}


		$arr = array();

		try
		{
			/*
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
			*/

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Co');

			$val->add_field('bbs_thread_no', 'BBS Thread No', 'required|check_bbs_thread_no');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_thread_no = $val->validated('bbs_thread_no');
				$validated_page = $val->validated('page');

				if (isset($test))
				{
					echo "バリデーション後";
					var_dump($validated_bbs_thread_no, $validated_page);
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

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_bbs_thread_arr = $model_co->get_bbs_thread($validated_bbs_thread_no);
				$db_community_arr = $model_co->get_community($db_bbs_thread_arr['community_no'], null);
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['read_bbs'] and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   コメントがありません。
				// --------------------------------------------------

				if ($db_bbs_thread_arr['comment_total'] == 0)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'コメントがありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				//$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);




				// --------------------------------------------------
				//    コメント
				//
				//    画像・動画のUnserializeを行い、返信を取得
				//    プロフィールを取得するためのNoを集める
				// --------------------------------------------------

				// リミット取得
				$limit_bbs_comment = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_comment') : \Config::get('limit_bbs_comment_sp');
				$limit_bbs_reply = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_reply') : \Config::get('limit_bbs_reply_sp');

				//$validated_page = 1;

				// コメント取得
				$db_bbs_comment_arr = $model_co->get_bbs_comment_list($validated_bbs_thread_no, $validated_page, $limit_bbs_comment);

				// デフォルトの値
				$bbs_user_no_arr = array();
				$bbs_profile_no_arr = array();
				$db_bbs_reply_arr = null;


				foreach ($db_bbs_comment_arr as $key_comment => &$value_comment)
				{

					// ------------------------------
					//    画像・動画のUnserialize
					// ------------------------------

					if (isset($value_comment['image'])) $value_comment['image'] = unserialize($value_comment['image']);
					if (isset($value_comment['movie'])) $value_comment['movie'] = unserialize($value_comment['movie']);


					// ------------------------------
					//    ユーザーNo、プロフィールNo追加
					// ------------------------------

					if ($value_comment['profile_no'])
					{
						array_push($bbs_profile_no_arr, $value_comment['profile_no']);
					}
					else if ($value_comment['user_no'])
					{
						array_push($bbs_user_no_arr, $value_comment['user_no']);
					}


					// --------------------------------------------------
					//    返信
					// --------------------------------------------------

					if ($value_comment['reply_total'] > 0)
					{

						// 返信取得
						$db_bbs_reply_arr[$value_comment['bbs_comment_no']] = $model_co->get_bbs_reply_list($value_comment['bbs_comment_no'], 1, $limit_bbs_reply);

						foreach ($db_bbs_reply_arr[$value_comment['bbs_comment_no']] as $key_reply => &$value_reply)
						{

							// ------------------------------
							//    画像・動画のUnserialize
							// ------------------------------

							if (isset($value_reply['image'])) $value_reply['image'] = unserialize($value_reply['image']);
							if (isset($value_reply['movie'])) $value_reply['movie'] = unserialize($value_reply['movie']);


							// ------------------------------
							//    ユーザーNo、プロフィールNo追加
							// ------------------------------

							if ($value_reply['profile_no'])
							{
								array_push($bbs_profile_no_arr, $value_reply['profile_no']);
							}
							else if ($value_reply['user_no'])
							{
								array_push($bbs_user_no_arr, $value_reply['user_no']);
							}

						}

					}

				}

				unset($value_comment, $value_reply);



				// ------------------------------
				//    Personal Box用プロフィール取得
				// ------------------------------

				$bbs_user_data_arr = array();
				$bbs_profile_arr = array();

				// 重複No削除
				$bbs_user_no_arr = array_unique($bbs_user_no_arr);
				$bbs_profile_no_arr = array_unique($bbs_profile_no_arr);

				if (count($bbs_user_no_arr) > 0)
				{
					$bbs_user_data_arr = $model_user->get_user_data_list_in_personal_box($bbs_user_no_arr);
				}
				if (count($bbs_profile_no_arr) > 0)
				{
					$bbs_profile_arr = $model_user->get_profile_list_in_personal_box($bbs_profile_no_arr, false);
				}



				if (isset($test))
				{
					echo "<br>db_bbs_thread_arr";
					var_dump($db_bbs_thread_arr);

					echo "<br>db_community_arr";
					var_dump($db_community_arr);

					echo "<br>authority_arr";
					var_dump($authority_arr);

					// if (isset($login_profile_data_arr))
					// {
						// echo "<br>login_profile_data_arr";
						// var_dump($login_profile_data_arr);
					// }

					echo "<br>limit_bbs_comment";
					var_dump($limit_bbs_comment);

					echo "<br>db_bbs_comment_arr";
					var_dump($db_bbs_comment_arr);

					echo "<br>db_bbs_reply_arr";
					var_dump($db_bbs_reply_arr);

					echo "<br>bbs_user_no_arr";
					var_dump($bbs_user_no_arr);

					echo "<br>bbs_profile_no_arr";
					var_dump($bbs_profile_no_arr);

					echo "<br>bbs_user_data_arr";
					var_dump($bbs_user_data_arr);

					echo "<br>bbs_profile_arr";
					var_dump($bbs_profile_arr);
				}





				//echo "view";
				//echo $view->render();

				//exit();



				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = View::forge('parts/bbs_comment_view');

				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set_safe('login_user_no', $this->user_no);
				$view->set_safe('agent_type', $this->agent_type);
				$view->set_safe('host', $this->host);
				$view->set_safe('user_agent', $this->user_agent);

				$view->set_safe('datetime_now', $datetime_now);

				$view->set_safe('authority_arr', $authority_arr);
				$view->set_safe('online_limit', $config_arr['online_limit']);

				$view->set('comment_arr', $db_bbs_comment_arr);
				$view->set('reply_arr', $db_bbs_reply_arr);
				$view->set('user_data_arr', $bbs_user_data_arr);
				$view->set('profile_arr', $bbs_profile_arr);

				$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');
				$view->set('pagination_comment_page', $validated_page);
				$view->set('pagination_comment_total', $db_bbs_thread_arr['comment_total']);
				$view->set('pagination_comment_limit', $limit_bbs_comment);
				$view->set('pagination_times', $pagination_times);

				$view->set('pagination_reply_limit', $limit_bbs_reply);

				$arr['code'] = $view->render();


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
	* BBS返信　読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_reply()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['bbs_comment_no'] = 36;
			$_POST['page'] = 1;
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

			$val->add_callable('Original_Rule_Co');

			$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|check_bbs_comment_no');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_comment_no = $val->validated('bbs_comment_no');
				$validated_page = $val->validated('page');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_user = new \Model_User();
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

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_bbs_comment_arr = $model_co->get_bbs_comment($validated_bbs_comment_no);
				$db_community_arr = $model_co->get_community($db_bbs_comment_arr['community_no'], null);
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['read_bbs'] and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   返信がありません。
				// --------------------------------------------------

				if ($db_bbs_comment_arr['reply_total'] == 0)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '返信がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				//$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);




				// --------------------------------------------------
				//    返信
				//
				//    画像・動画のUnserializeを行い、返信を取得
				//    プロフィールを取得するためのNoを集める
				// --------------------------------------------------

				// リミット取得
				$limit_bbs_reply = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_reply') : \Config::get('limit_bbs_reply_sp');

				// 返信取得
				$db_bbs_reply_arr = $model_co->get_bbs_reply_list($validated_bbs_comment_no, $validated_page, $limit_bbs_reply);

				// デフォルトの値
				$bbs_user_no_arr = array();
				$bbs_profile_no_arr = array();
				//$db_bbs_reply_arr = null;


				foreach ($db_bbs_reply_arr as $key => &$value)
				{

					// ------------------------------
					//    画像・動画のUnserialize
					// ------------------------------

					if (isset($value['image'])) $value['image'] = unserialize($value['image']);
					if (isset($value['movie'])) $value['movie'] = unserialize($value['movie']);


					// ------------------------------
					//    ユーザーNo、プロフィールNo追加
					// ------------------------------

					if ($value['profile_no'])
					{
						array_push($bbs_profile_no_arr, $value['profile_no']);
					}
					else if ($value['user_no'])
					{
						array_push($bbs_user_no_arr, $value['user_no']);
					}

				}

				unset($value);



				// ------------------------------
				//    Personal Box用プロフィール取得
				// ------------------------------

				$bbs_user_data_arr = array();
				$bbs_profile_arr = array();

				// 重複No削除
				$bbs_user_no_arr = array_unique($bbs_user_no_arr);
				$bbs_profile_no_arr = array_unique($bbs_profile_no_arr);

				if (count($bbs_user_no_arr) > 0)
				{
					$bbs_user_data_arr = $model_user->get_user_data_list_in_personal_box($bbs_user_no_arr);
				}
				if (count($bbs_profile_no_arr) > 0)
				{
					$bbs_profile_arr = $model_user->get_profile_list_in_personal_box($bbs_profile_no_arr, false);
				}




				// if (isset($test))
				// {
					// echo "バリデーション後";
					// Debug::dump($validated_bbs_comment_no, $validated_page);
				// }
				if (isset($test))
				{
					Debug::$js_toggle_open = true;

					echo "<br>db_community_arr";
					Debug::dump($db_community_arr);

					// echo "<br>authority_arr";
					// Debug::dump($authority_arr);

					//echo "<br>login_profile_data_arr";
					//Debug::dump($login_profile_data_arr);

					echo "<br>db_bbs_comment_arr";
					Debug::dump($db_bbs_comment_arr);

					echo "<br>db_bbs_reply_arr";
					Debug::dump($db_bbs_reply_arr);

					// echo "<br>bbs_user_no_arr";
					// Debug::dump($bbs_user_no_arr);
//
					// echo "<br>bbs_profile_no_arr";
					// Debug::dump($bbs_profile_no_arr);

					echo "<br>bbs_user_data_arr";
					Debug::dump($bbs_user_data_arr);

					echo "<br>bbs_profile_arr";
					Debug::dump($bbs_profile_arr);


					echo "<br>db_bbs_comment_arr[reply_total]";
					Debug::dump($db_bbs_comment_arr['reply_total']);

					echo "<br>limit_bbs_reply";
					Debug::dump($limit_bbs_reply);

				}

				//exit();



				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = View::forge('parts/bbs_reply_view');
				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set('datetime_now', $datetime_now);
				$view->set('login_user_no', $this->user_no);
				$view->set('agent_type', $this->agent_type);
				$view->set('host', $this->host);
				$view->set('user_agent', $this->user_agent);
				$view->set('authority_arr', $authority_arr);
				$view->set('online_limit', $config_arr['online_limit']);

				$view->set('reply_arr', $db_bbs_reply_arr);
				$view->set('user_data_arr', $bbs_user_data_arr);
				$view->set('profile_arr', $bbs_profile_arr);



				// $view->set('pagination_page', $validated_page);
				// $view->set('pagination_total', $db_bbs_comment_arr['reply_total']);
				// $view->set('pagination_limit', $limit_bbs_reply);



				$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

				$view->set('pagination_page', $validated_page);
				$view->set('pagination_total', $db_bbs_comment_arr['reply_total']);
				$view->set('pagination_limit', $limit_bbs_reply);
				$view->set('pagination_times', $pagination_times);

				$code = $view->render();


				//$view->set('pagination_reply_limit', $limit_bbs_reply);
				//echo $view->render();

				// --------------------------------------------------
				//   ページャー
				// --------------------------------------------------

				// if ($db_bbs_comment_arr['reply_total'] > $limit_bbs_reply)
				// {
//
					// //echo '    <li class="list-group-item padding_bottom_15">' . "\n\n";
					// //echo '      <div class="bbs_post_comment_title">コメントをもっと見る</div>' . "\n\n";
//
					//
					// $view_pagination = View::forge('parts/pagination_view');
					// $view_pagination->set_safe('page', $validated_page);
					// $view_pagination->set_safe('total', $db_bbs_comment_arr['reply_total']);
					// $view_pagination->set_safe('limit', $limit_bbs_reply);
					// $view_pagination->set_safe('times', $pagination_times);
					// $view_pagination->set_safe('function_name', 'readBbsReply');
					// $view_pagination->set_safe('argument_arr', array($db_bbs_comment_arr['bbs_comment_no']));
					// //echo $view_pagination->render() . "\n\n";
//
					// //echo '    </li>' . "\n\n";
//
					// $code .= $view_pagination->render();
//
				// }



				$arr['code'] = $code;


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

				if ($test) echo $error_message;
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
	* BBS返信　読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_reply_more()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['bbs_comment_no'] = 36;
			$_POST['page'] = 1;
		}


		$arr = array();

		try
		{
			/*
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
			*/

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Co');

			$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|check_bbs_comment_no');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_comment_no = $val->validated('bbs_comment_no');
				$validated_page = $val->validated('page');

				if (isset($test))
				{
					echo "バリデーション後";
					var_dump($validated_bbs_comment_no, $validated_page);
				}


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_user = new \Model_User();
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

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_bbs_comment_arr = $model_co->get_bbs_comment($validated_bbs_comment_no);
				$db_community_arr = $model_co->get_community($db_bbs_comment_arr['community_no'], null);
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['read_bbs'] and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   返信がありません。
				// --------------------------------------------------

				if ($db_bbs_comment_arr['reply_total'] == 0)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '返信がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				//$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);




				// --------------------------------------------------
				//    返信
				//
				//    画像・動画のUnserializeを行い、返信を取得
				//    プロフィールを取得するためのNoを集める
				// --------------------------------------------------

				// リミット取得
				$limit_bbs_reply = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_reply') : \Config::get('limit_bbs_reply_sp');

				// 返信取得
				$db_bbs_reply_arr = $model_co->get_bbs_reply_list($validated_bbs_comment_no, $validated_page, $limit_bbs_reply);

				// デフォルトの値
				$bbs_user_no_arr = array();
				$bbs_profile_no_arr = array();
				//$db_bbs_reply_arr = null;


				foreach ($db_bbs_reply_arr as $key => &$value)
				{

					// ------------------------------
					//    画像・動画のUnserialize
					// ------------------------------

					if (isset($value['image'])) $value['image'] = unserialize($value['image']);
					if (isset($value['movie'])) $value['movie'] = unserialize($value['movie']);


					// ------------------------------
					//    ユーザーNo、プロフィールNo追加
					// ------------------------------

					if ($value['profile_no'])
					{
						array_push($bbs_profile_no_arr, $value['profile_no']);
					}
					else if ($value['user_no'])
					{
						array_push($bbs_user_no_arr, $value['user_no']);
					}

				}

				unset($value);



				// ------------------------------
				//    Personal Box用プロフィール取得
				// ------------------------------

				$bbs_user_data_arr = array();
				$bbs_profile_arr = array();

				// 重複No削除
				$bbs_user_no_arr = array_unique($bbs_user_no_arr);
				$bbs_profile_no_arr = array_unique($bbs_profile_no_arr);

				if (count($bbs_user_no_arr) > 0)
				{
					$bbs_user_data_arr = $model_user->get_user_data_list_in_personal_box($bbs_user_no_arr);
				}
				if (count($bbs_profile_no_arr) > 0)
				{
					$bbs_profile_arr = $model_user->get_profile_list_in_personal_box($bbs_profile_no_arr, false);
				}



				if (isset($test))
				{
					echo "<br>db_community_arr";
					var_dump($db_community_arr);

					echo "<br>authority_arr";
					var_dump($authority_arr);

					//echo "<br>login_profile_data_arr";
					//var_dump($login_profile_data_arr);

					echo "<br>db_bbs_comment_arr";
					var_dump($db_bbs_comment_arr);

					echo "<br>db_bbs_reply_arr";
					var_dump($db_bbs_reply_arr);

					echo "<br>bbs_user_no_arr";
					var_dump($bbs_user_no_arr);

					echo "<br>bbs_profile_no_arr";
					var_dump($bbs_profile_no_arr);

					echo "<br>bbs_user_data_arr";
					var_dump($bbs_user_data_arr);

					echo "<br>bbs_profile_arr";
					var_dump($bbs_profile_arr);
				}

				//exit();



				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = View::forge('parts/bbs_reply_view');
				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set('datetime_now', $datetime_now);
				$view->set('login_user_no', $this->user_no);
				$view->set('agent_type', $this->agent_type);
				$view->set('host', $this->host);
				$view->set('user_agent', $this->user_agent);
				$view->set('authority_arr', $authority_arr);
				$view->set('online_limit', $config_arr['online_limit']);

				$view->set('reply_arr', $db_bbs_reply_arr);
				$view->set('user_data_arr', $bbs_user_data_arr);
				$view->set('profile_arr', $bbs_profile_arr);

				$view->set('pagination_page', $validated_page);
				$view->set('pagination_total', $db_bbs_comment_arr['reply_total']);
				$view->set('pagination_limit', $limit_bbs_reply);

				//echo $view->render();

				$arr['code'] = $view->render();


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

				if ($test) echo $error_message;
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
	* BBSスレッド編集フォーム表示
	*
	*/
	public function post_show_edit_bbs_thread_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['bbs_thread_no'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('bbs_thread_no', 'BBS Thread No', 'required|match_pattern["^[1-9]\d*$"]|check_bbs_thread_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_thread_no = $val->validated('bbs_thread_no');




				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				$db_bbs_thread_arr = $model_co->get_bbs_thread($validated_bbs_thread_no);
				$db_community_arr = $model_co->get_community($db_bbs_thread_arr['community_no'], null);

				$db_bbs_thread_arr['image'] = (isset($db_bbs_thread_arr['image'])) ? unserialize($db_bbs_thread_arr['image']) : null;
				$db_bbs_thread_arr['movie'] = (isset($db_bbs_thread_arr['movie'])) ? unserialize($db_bbs_thread_arr['movie']) : null;

				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				// 日時
				$datetime_past = $original_common_date->sql_format('-30 minutes');

				if (isset($db_bbs_thread_arr['user_no'], $this->user_no) and $db_bbs_thread_arr['user_no'] == $this->user_no)
				{
					$authority_edit = true;
				}
				else if ($db_bbs_thread_arr['renewal_date'] > $datetime_past and $db_bbs_thread_arr['host'] == $this->host and $db_bbs_thread_arr['user_agent'] == $this->user_agent)
				{
					$authority_edit = true;
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_edit)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ユーザー情報
				// --------------------------------------------------

				if ($db_bbs_thread_arr['profile_no'])
				{
					$profile_arr = $model_user->get_profile($db_bbs_thread_arr['profile_no']);
				}
				else if ($db_bbs_thread_arr['user_no'])
				{
					$profile_arr = $model_user->get_user_data_personal_box($db_bbs_thread_arr['user_no'], null);
				}
				else
				{
					$profile_arr = null;
				}


				// --------------------------------------------------
				//    画像URL設定
				// --------------------------------------------------

				if (isset($db_bbs_thread_arr['bbs_thread_no'], $db_bbs_thread_arr['bbs_comment_no']))
				{
					$image_url_base = $this->uri_base . 'assets/img/bbs/comment/' . $db_bbs_thread_arr['bbs_comment_no'] . '/';
				}
				else
				{
					$image_url_base = $this->uri_base . 'assets/img/bbs/thread/' . $db_bbs_thread_arr['bbs_thread_no'] . '/';
				}


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = View::forge('parts/form_common_view');
				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set('login_user_no', $this->user_no);
				$view->set('datetime_now', $datetime_now);
				$view->set('profile_arr', $profile_arr);
				$view->set_safe('online_limit', $config_arr['online_limit']);
				$view->set_safe('anonymity', $config_arr['anonymity']);
				$view->set('func_name', 'saveBbsThread');
				$view->set('func_argument_arr', array('null', $validated_bbs_thread_no));
				$view->set('func_name_return', 'removeEditBbsThreadForm');
				$view->set('func_argument_return_arr', array($validated_bbs_thread_no));
				$view->set('func_name_delete', 'deleteBbsThread');
				$view->set('func_argument_delete_arr', array($validated_bbs_thread_no));
				$view->set('data_arr', $db_bbs_thread_arr);
				$view->set('image_url_base', $image_url_base);

				$arr['code'] = $view->render();


				if (isset($test))
				{
					echo "validated_bbs_thread_no";
					var_dump($validated_bbs_thread_no);

					echo "<br>authority_edit";
					var_dump($authority_edit);

					echo "<br>profile_arr";
					var_dump($profile_arr);

					echo "<br>db_bbs_thread_arr";
					var_dump($db_bbs_thread_arr);

					echo $view->render();
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
				*/
				//echo $error_message;
				//$arr['test'] = 'エラー ' . $error_message;
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
	* BBSスレッド　作成・更新
	*
	* @return string HTMLコード
	*/
	public function post_save_bbs_thread()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['community_no'] = 1;
			$_POST['bbs_thread_no'] = 6;
			//$_POST['handle_name'] = 'ハンドルネーム';
			$_POST['title'] = 'GTA5は面白かった？2';
			$_POST['comment'] = '北米ではついに現地時間の3月11日にローンチを迎え海外メディアからも非常に高い評価を獲得した『DARK SOULS II』ですが、
海外メディアIGNが同作の死亡カウントを報告し、
すでに全世界のプレイヤーが200万回以上も死亡し、
ドラングレイグの地で阿鼻叫喚の様相を見せていることが明らかとなりました。2';
			//$_POST['anonymity'] = true;
			//$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
			//$_POST['image_1_delete'] = 1;
			//$_POST['movie_1_delete'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');

			if (Input::post('community_no'))
			{
				$val->add_field('community_no', 'Community No', 'required|check_community_no');
			}
			else if (Input::post('bbs_thread_no'))
			{
				$val->add_field('bbs_thread_no', 'BBS Thread No', 'required|check_bbs_thread_no');
			}
			else
			{
				throw new Exception('Error');
			}

			if ( ! $this->user_no) $val->add_field('handle_name', 'ハンドルネーム', 'required|min_length[1]|max_length[50]');
			$val->add_field('title', 'タイトル', 'required|min_length[1]|max_length[50]');
			$val->add_field('comment', 'コメント', 'required|min_length[1]|max_length[3000]');
			$val->add_field('movie_url', 'Movie URL', 'valid_url|check_movie_url');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_bbs_thread_no = $val->validated('bbs_thread_no');
				$validated_handle_name = $val->validated('handle_name');
				$validated_title = $val->validated('title');
				$validated_comment = $val->validated('comment');
				$validated_anonymity = (Input::post('anonymity')) ? 1: null;
				$validated_movie_url = $val->validated('movie_url');
				$validated_image_1_delete = (Input::post('image_1_delete')) ? 1: null;
				//$validated_movie_1_delete = (Input::post('movie_1_delete')) ? 1: null;

				if (isset($test))
				{
					echo "バリデーション後";
					var_dump($validated_community_no, $validated_bbs_thread_no, $validated_handle_name, $validated_title, $validated_comment, $validated_anonymity, $validated_movie_url, $validated_image_1_delete);
				}

				//exit();

				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$model_notifications = new Model_Notifications();
				$model_notifications->agent_type = $this->agent_type;
				$model_notifications->user_no = $this->user_no;
				$model_notifications->language = $this->language;
				$model_notifications->uri_base = $this->uri_base;
				$model_notifications->uri_current = $this->uri_current;

				$model_common = new Model_Common();
				$model_common->user_no = $this->user_no;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->host = $this->host;
				$original_code_co->user_agent = $this->user_agent;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;

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

				if ($validated_community_no)
				{
					$db_community_arr = $model_co->get_community($validated_community_no, null);
				}
				else if ($validated_bbs_thread_no)
				{
					$db_bbs_thread_arr = $model_co->get_bbs_thread($validated_bbs_thread_no);
					$db_community_arr = $model_co->get_community($db_bbs_thread_arr['community_no'], null);
				}

				$image_arr = (isset($db_bbs_thread_arr['image'])) ? $db_bbs_thread_arr['image'] : null;
				$movie_arr = (isset($db_bbs_thread_arr['movie'])) ? $db_bbs_thread_arr['movie'] : null;



				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				if (isset($db_bbs_thread_arr))
				{
					// 日時
					$datetime_past = $original_common_date->sql_format('-30 minutes');
					//var_dump($datetime_past);

					if (isset($db_bbs_thread_arr['user_no'], $this->user_no) and $db_bbs_thread_arr['user_no'] == $this->user_no)
					{
						$authority_edit = true;
					}
					else if ($db_bbs_thread_arr['renewal_date'] > $datetime_past and $db_bbs_thread_arr['host'] == $this->host and $db_bbs_thread_arr['user_agent'] == $this->user_agent)
					{
						$authority_edit = true;
					}
				}

				//echo "<br>authority_edit";
				//var_dump($authority_edit);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_bbs_thread'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);
				//echo "<br>login_profile_data_arr";
				//var_dump($login_profile_data_arr);


				// --------------------------------------------------
				//   削除済みユーザー
				// --------------------------------------------------

				if ($login_profile_data_arr === false)
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


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$user_no = null;
				$profile_no = null;
				$handle_name = null;
				$movie = null;

				if (isset($login_profile_data_arr['profile_no']))
				{
					$user_no = $login_profile_data_arr['author_user_no'];
					$profile_no = $login_profile_data_arr['profile_no'];
				}
				else if (isset($login_profile_data_arr['user_no']))
				{
					$user_no = $login_profile_data_arr['user_no'];
				}
				else
				{
					$handle_name = $validated_handle_name;
				}


				// ------------------------------
				//    動画
				// ------------------------------
				/*
				if ($validated_movie_delete_1)
				{
					$movie_arr = null;
				}
				*/
				if ($validated_movie_url)
				{
					if ($movie_arr) $movie_arr = unserialize($movie_arr);

					$movie_arr = $original_func_common->return_movie(array($validated_movie_url), $movie_arr, Config::get('limit_bbs_thread_movie'));
					$movie_arr = serialize($movie_arr);
				}
				else
				{
					$movie_arr = null;
				}

				if (isset($test))
				{
					echo "<br>validated_movie_url";
					var_dump(array($validated_movie_url));
					echo "<br>movie";
					var_dump($movie_arr);
				}


				$save_arr = array(
					'regi_date' => $datetime_now,
					'renewal_date' => $datetime_now,
					'sort_date' => $datetime_now,
					'community_no' => $db_community_arr['community_no'],
					'user_no' => $user_no,
					'profile_no' => $profile_no,
					'anonymity' => $validated_anonymity,
					'handle_name' => $handle_name,
					'title' => $validated_title,
					'comment' => $validated_comment,
					'image' => $image_arr,
					'movie' => $movie_arr,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				);

				if (isset($test))
				{
					echo "<br>挿入用　save_arr";
					var_dump($save_arr);
				}


				// --------------------------------------------------
				//    アップロードされた画像があるかの確認
				// --------------------------------------------------

				// $uploaded_image_existence = false;
//
				// if (isset($_FILES))
				// {
					// foreach ($_FILES as $key => $value)
					// {
						// if ($value['size'] > 0) $uploaded_image_existence = true;
					// }
				// }

				//echo "<br>FILES";
				//var_dump($_FILES);
				//echo "<br>uploaded_image_existence";
				//var_dump($uploaded_image_existence);


				$code_output = false;


				// --------------------------------------------------
				//    データベース挿入　二重書き込み防止機能あり
				// --------------------------------------------------

				if ($validated_community_no)
				{

					$validated_bbs_thread_no = $model_co->insert_bbs_thread($save_arr);
					$notifications_bbs_thread_no = $validated_bbs_thread_no;

					if (isset($test))
					{
						echo "<br>validated_bbs_thread_no";
						var_dump($validated_bbs_thread_no);
					}


					if ($validated_bbs_thread_no and $uploaded_image_existence)
					{
						$authority_edit = true;
					}
					else if ($validated_bbs_thread_no and $uploaded_image_existence === false)
					{
						// アップロードされた画像がない場合、更新しない
						$validated_bbs_thread_no = false;
					}
					else if ($validated_bbs_thread_no === false)
					{
						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = '二重書き込みです。';
						throw new Exception('Error');
					}

					$code_output = true;


					// --------------------------------------------------
					//   お知らせ保存
					// --------------------------------------------------

					$game_list_arr = $original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

					$save_notifications_arr = array(
						'regi_date' => $datetime_now,
						'target_user_no' => null,
						'community_no' => $db_community_arr['community_no'],
						'game_no' => $game_list_arr[0],
						'type1' => 'uc',
						'type2' => 'bbs_thread',
						'title' => $validated_title,
						'anonymity' => $validated_anonymity,
						'name' => $handle_name,
						'comment' => $validated_comment,
						'bbs_thread_no' => $notifications_bbs_thread_no
					);

					if (isset($login_profile_data_arr['profile_no'])) $save_notifications_arr['profile_no'] = $login_profile_data_arr['profile_no'];

					$model_notifications->save_notifications($save_notifications_arr);

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



				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				if ($validated_bbs_thread_no and $authority_edit)
				{

					// --------------------------------------------------
					//   画像設定
					// --------------------------------------------------

					// 保存先パス設定
					$path = DOCROOT . 'assets/img/bbs/thread/' . $validated_bbs_thread_no . '/';

					// 名前設定
					//$image_name = '';


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
						$result_upload_image_arr = $original_func_common->zebra_image_save2($path, $image_name_arr, true, Config::get('limit_bbs_thread_image'));

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

						/*
						if ($upload_image_result == 'upload_image_error')
						{
							$arr['alert_color'] = 'danger';
							$arr['alert_title'] = 'エラー';
							$arr['alert_message'] = 'アップロードされた画像に問題があります。';
							throw new Exception('Error');
						}
						else if ($upload_image_result)
						{
							$image_arr = $upload_image_result;
							$save_arr['image'] = serialize($image_arr);
						}
						else
						{
							$save_arr['image'] = null;
						}
						*/
					}


					// --------------------------------------------------
					//    データベース更新
					// --------------------------------------------------

					unset($save_arr['regi_date'], $save_arr['user_no'], $save_arr['profile_no']);
					$result = $model_co->update_bbs_thread($validated_bbs_thread_no, $save_arr);

					if (isset($test))
					{
						echo "<br>更新用 save_arr";
						var_dump($save_arr);
					}

					$code_output = true;

				}



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

				if ($code_output)
				{
					$view = $original_code_co->bbs($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, 1);
					$arr['code'] = $view->render();
				}
				else
				{
					$arr['code'] = null;
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
	* BBSスレッド　削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_bbs_thread()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		$test = false;

		if ($test)
		{
			$_POST['bbs_thread_no'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('bbs_thread_no', 'BBS Thread No', 'required|check_bbs_thread_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_thread_no = $val->validated('bbs_thread_no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;

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

				$db_bbs_thread_arr = $model_co->get_bbs_thread($validated_bbs_thread_no);
				$db_community_arr = $model_co->get_community($db_bbs_thread_arr['community_no'], null);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				// 日時
				$datetime_past = $original_common_date->sql_format('-30 minutes');

				if (isset($db_bbs_thread_arr['user_no'], $this->user_no) and $db_bbs_thread_arr['user_no'] == $this->user_no)
				{
					$authority_edit = true;
				}
				else if ($db_bbs_thread_arr['renewal_date'] > $datetime_past and $db_bbs_thread_arr['host'] == $this->host and $db_bbs_thread_arr['user_agent'] == $this->user_agent)
				{
					$authority_edit = true;
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_edit and ! $authority_arr['operate_bbs_delete'] and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array(
					'on_off' => null,
					'renewal_date' => $datetime_now,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				);


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->delete_bbs_thread($db_community_arr['community_no'], $validated_bbs_thread_no, $save_arr);


				if ($test)
				{
					echo "バリデーション後";
					var_dump($validated_bbs_thread_no);

					echo "<br>authority_edit";
					var_dump($authority_edit);

					echo '$authority_arr';
					Debug::dump($authority_arr);

					echo "<br>login_profile_data_arr";
					var_dump($login_profile_data_arr);

					echo "<br>更新用 save_arr";
					var_dump($save_arr);
				}


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = $original_code_co->bbs($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, 1);
				$arr['code'] = $view->render();

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

				if ($test) echo $error_message;

			}

		}
		catch (Exception $e) {

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'エラー';
			$arr['alert_message'] = '削除できませんでした。';

			if ($test) echo $e->getMessage();
		}


		if ($test)
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}








	/**
	* BBSコメント編集フォーム表示
	*
	*/
	public function post_show_edit_bbs_comment_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['bbs_comment_no'] = 16;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|match_pattern["^[1-9]\d*$"]|check_bbs_comment_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_comment_no = $val->validated('bbs_comment_no');


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

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				$db_bbs_comment_arr = $model_co->get_bbs_comment($validated_bbs_comment_no);
				$db_community_arr = $model_co->get_community($db_bbs_comment_arr['community_no'], null);

				$db_bbs_comment_arr['image'] = (isset($db_bbs_comment_arr['image'])) ? unserialize($db_bbs_comment_arr['image']) : null;
				$db_bbs_comment_arr['movie'] = (isset($db_bbs_comment_arr['movie'])) ? unserialize($db_bbs_comment_arr['movie']) : null;

				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				// 日時
				$datetime_past = $original_common_date->sql_format('-30 minutes');

				if (isset($db_bbs_comment_arr['user_no'], $this->user_no) and $db_bbs_comment_arr['user_no'] == $this->user_no)
				{
					$authority_edit = true;
				}
				else if ($db_bbs_comment_arr['renewal_date'] > $datetime_past and $db_bbs_comment_arr['host'] == $this->host and $db_bbs_comment_arr['user_agent'] == $this->user_agent)
				{
					$authority_edit = true;
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_edit)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ユーザー情報
				// --------------------------------------------------

				if ($db_bbs_comment_arr['profile_no'])
				{
					$profile_arr = $model_user->get_profile($db_bbs_comment_arr['profile_no']);
				}
				else if ($db_bbs_comment_arr['user_no'])
				{
					$profile_arr = $model_user->get_user_data_personal_box($db_bbs_comment_arr['user_no'], null);
				}
				else
				{
					$profile_arr = null;
				}


				// --------------------------------------------------
				//    画像URL設定
				// --------------------------------------------------

				if (isset($db_bbs_comment_arr['bbs_thread_no'], $db_bbs_comment_arr['bbs_comment_no']))
				{
					$image_url_base = $this->uri_base . 'assets/img/bbs/comment/' . $db_bbs_comment_arr['bbs_comment_no'] . '/';
				}
				else
				{
					$image_url_base = $this->uri_base . 'assets/img/bbs/thread/' . $db_bbs_comment_arr['bbs_thread_no'] . '/';
				}


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = View::forge('parts/form_common_view');
				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set('login_user_no', $this->user_no);
				$view->set('datetime_now', $datetime_now);
				$view->set('profile_arr', $profile_arr);
				$view->set_safe('online_limit', $config_arr['online_limit']);
				$view->set_safe('anonymity', $config_arr['anonymity']);
				$view->set('func_name', 'saveBbsComment');
				$view->set('func_argument_arr', array('null', $validated_bbs_comment_no));
				$view->set('func_name_return', 'removeEditBbsCommentForm');
				$view->set('func_argument_return_arr', array($validated_bbs_comment_no));
				$view->set('func_name_delete', 'deleteBbsComment');
				$view->set('func_argument_delete_arr', array($validated_bbs_comment_no));
				$view->set('data_arr', $db_bbs_comment_arr);
				$view->set('image_url_base', $image_url_base);
				$view->set_safe('title_off', true);

				$arr['code'] = $view->render();



				if (isset($test))
				{
					echo "validated_bbs_comment_no";
					var_dump($validated_bbs_comment_no);

					echo "<br>authority_edit";
					var_dump($authority_edit);

					echo "<br>profile_arr";
					var_dump($profile_arr);

					echo "db_bbs_comment_arr";
					var_dump($db_bbs_comment_arr);

					echo "db_community_arr";
					var_dump($db_community_arr);

					echo $view->render();
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
				*/
				//echo $error_message;
				//$arr['test'] = 'エラー ' . $error_message;
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
	* BBSコメント書き込み・更新
	*
	* @return string HTMLコード
	*/
	public function post_save_bbs_comment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['bbs_thread_no'] = 3;
			//$_POST['bbs_comment_no'] = 19;
			$_POST['handle_name'] = 'ハンドルネーム';
			$_POST['comment'] = 'テスト';
			//$_POST['anonymity'] = true;
			//$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
			//$_POST['image_delete_1'] = 1;
			//$_POST['movie_delete_1'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');

			if (Input::post('bbs_thread_no'))
			{
				$val->add_field('bbs_thread_no', 'BBS Thread No', 'required|check_bbs_thread_no');
			}
			else if (Input::post('bbs_comment_no'))
			{
				$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|check_bbs_comment_no');
			}
			else
			{
				throw new Exception('Error');
			}

			if ( ! $this->user_no) $val->add_field('handle_name', 'ハンドルネーム', 'required|min_length[1]|max_length[50]');
			$val->add_field('comment', 'コメント', 'required|min_length[1]|max_length[3000]');
			$val->add_field('movie_url', 'Movie URL', 'valid_url|check_movie_url');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_thread_no = $val->validated('bbs_thread_no');
				$validated_bbs_comment_no = $val->validated('bbs_comment_no');
				$validated_handle_name = $val->validated('handle_name');
				$validated_comment = $val->validated('comment');
				$validated_anonymity = (Input::post('anonymity')) ? 1: null;
				$validated_movie_url = $val->validated('movie_url');
				$validated_image_1_delete = (Input::post('image_1_delete')) ? 1: null;
				//$validated_movie_delete_1 = (Input::post('movie_delete_1')) ? 1: null;


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$model_notifications = new Model_Notifications();
				$model_notifications->agent_type = $this->agent_type;
				$model_notifications->user_no = $this->user_no;
				$model_notifications->language = $this->language;
				$model_notifications->uri_base = $this->uri_base;
				$model_notifications->uri_current = $this->uri_current;

				$model_common = new Model_Common();
				$model_common->user_no = $this->user_no;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->host = $this->host;
				$original_code_co->user_agent = $this->user_agent;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;

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

				if ($validated_bbs_thread_no)
				{
					$db_bbs_thread_arr = $model_co->get_bbs_thread($validated_bbs_thread_no);
					$db_community_arr = $model_co->get_community($db_bbs_thread_arr['community_no'], null);
					$db_bbs_thread_no = $db_bbs_thread_arr['bbs_thread_no'];
				}
				else if ($validated_bbs_comment_no)
				{
					$db_bbs_comment_arr = $model_co->get_bbs_comment($validated_bbs_comment_no);
					$db_community_arr = $model_co->get_community($db_bbs_comment_arr['community_no'], null);
					$db_bbs_thread_no = $db_bbs_comment_arr['bbs_thread_no'];
				}

				$image_arr = (isset($db_bbs_comment_arr['image'])) ? $db_bbs_comment_arr['image'] : null;
				//$movie_arr = (isset($db_bbs_comment_arr['movie'])) ? $db_bbs_comment_arr['movie'] : null;
				$movie_arr = null;



				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				if (isset($db_bbs_comment_arr))
				{
					// 日時
					$datetime_past = $original_common_date->sql_format('-30 minutes');

					if (isset($db_bbs_comment_arr['user_no'], $this->user_no) and $db_bbs_comment_arr['user_no'] == $this->user_no)
					{
						$authority_edit = true;
					}
					else if ($db_bbs_comment_arr['renewal_date'] > $datetime_past and $db_bbs_comment_arr['host'] == $this->host and $db_bbs_comment_arr['user_agent'] == $this->user_agent)
					{
						$authority_edit = true;
					}
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_bbs_comment'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


				// --------------------------------------------------
				//   削除済みユーザー
				// --------------------------------------------------

				if ($login_profile_data_arr === false)
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


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$user_no = null;
				$profile_no = null;
				$handle_name = null;
				$movie = null;

				if (isset($login_profile_data_arr['profile_no']))
				{
					$user_no = $login_profile_data_arr['author_user_no'];
					$profile_no = $login_profile_data_arr['profile_no'];
				}
				else if (isset($login_profile_data_arr['user_no']))
				{
					$user_no = $login_profile_data_arr['user_no'];
				}
				else
				{
					$handle_name = $validated_handle_name;
				}


				// ------------------------------
				//    動画
				// ------------------------------

				if ($validated_movie_url)
				{
					//echo '$movie_arr';
					//var_dump($movie_arr);
					//if ($movie_arr) $movie_arr = unserialize($movie_arr);

					//echo '$movie_arr';
					//var_dump($movie_arr);

					$movie_arr = $original_func_common->return_movie(array($validated_movie_url), $movie_arr, Config::get('limit_bbs_comment_movie'));
					//echo '$movie_arr';
					//var_dump($movie_arr);

					if ($movie_arr) $movie_arr = serialize($movie_arr);
				}
				else
				{
					$movie_arr = null;
				}

				$save_arr = array(
					'regi_date' => $datetime_now,
					'renewal_date' => $datetime_now,
					'sort_date' => $datetime_now,
					'community_no' => $db_community_arr['community_no'],
					'bbs_thread_no' => $validated_bbs_thread_no,
					'user_no' => $user_no,
					'profile_no' => $profile_no,
					'anonymity' => $validated_anonymity,
					'handle_name' => $handle_name,
					'comment' => $validated_comment,
					'image' => $image_arr,
					'movie' => $movie_arr,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				);

				if (isset($test))
				{
					echo "<br>挿入用　save_arr";
					var_dump($save_arr);
				}


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo "バリデーション後";
					var_dump($validated_bbs_thread_no, $validated_bbs_comment_no, $validated_handle_name, $validated_comment, $validated_movie_url);

					if (isset($db_bbs_thread_arr))
					{
						echo '$db_bbs_thread_arr';
						Debug::dump($db_bbs_thread_arr);
					}

					if (isset($db_bbs_comment_arr))
					{
						echo '$db_bbs_comment_arr';
						Debug::dump($db_bbs_comment_arr);
					}

					echo '$db_community_arr';
					Debug::dump($db_community_arr);

					echo "<br>authority_edit";
					var_dump($authority_edit);

					echo '$authority_arr';
					Debug::dump($authority_arr);

					echo "<br>login_profile_data_arr";
					var_dump($login_profile_data_arr);
					/*
					echo "<br>validated_bbs_reply_no";
					var_dump($validated_bbs_reply_no);

					echo "<br>更新用 save_arr";
					var_dump($save_arr);
					*/
				}
				//exit();


				// --------------------------------------------------
				//    アップロードされた画像があるかの確認
				// --------------------------------------------------
				/*
				$uploaded_image_existence = false;

				if (isset($_FILES))
				{
					foreach ($_FILES as $key => $value)
					{
						if ($value['size'] > 0) $uploaded_image_existence = true;
					}
				}
				*/
				//echo "<br>FILES";
				//var_dump($_FILES);
				//echo "<br>uploaded_image_existence";
				//var_dump($uploaded_image_existence);






				$code_output = false;


				// --------------------------------------------------
				//    データベース挿入　二重書き込み防止機能あり
				// --------------------------------------------------

				if ($validated_bbs_thread_no)
				{

					$validated_bbs_comment_no = $model_co->insert_bbs_comment($save_arr);

					$notifications_bbs_comment_no = $validated_bbs_comment_no;

					//echo "<br>validated_bbs_comment_no";
					//var_dump($validated_bbs_comment_no);


					if ($validated_bbs_comment_no and $uploaded_image_existence)
					{
						$authority_edit = true;
					}
					else if ($validated_bbs_comment_no and $uploaded_image_existence === false)
					{
						// アップロードされた画像がない場合、更新しない
						$validated_bbs_comment_no = false;
					}
					else if ($validated_bbs_comment_no === false)
					{
						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = '二重書き込みです。';
						throw new Exception('Error');
					}

					$code_output = true;



					// --------------------------------------------------
					//   お知らせ保存
					// --------------------------------------------------

					$game_list_arr = $original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

					$save_notifications_arr = array(
						'regi_date' => $datetime_now,
						'target_user_no' => null,
						'community_no' => $db_bbs_thread_arr['community_no'],
						'game_no' => $game_list_arr[0],
						'type1' => 'uc',
						'type2' => 'bbs_comment',
						'title' => $db_bbs_thread_arr['title'],
						'anonymity' => $validated_anonymity,
						'name' => $handle_name,
						'comment' => $validated_comment,
						'bbs_thread_no' => $db_bbs_thread_arr['bbs_thread_no'],
						'bbs_comment_no' => $notifications_bbs_comment_no
					);

					if (isset($login_profile_data_arr['profile_no'])) $save_notifications_arr['profile_no'] = $login_profile_data_arr['profile_no'];

					$model_notifications->save_notifications($save_notifications_arr);

					// $original_func_common->save_notifications(array(
						// 'regi_date' => $datetime_now,
						// 'target_user_no' => null,
						// 'community_no' => $db_bbs_thread_arr['community_no'],
						// 'game_no' => $game_list_arr[0],
						// 'type1' => 'uc',
						// 'type2' => 'bbs_comment',
						// 'title' => $db_bbs_thread_arr['title'],
						// 'anonymity' => $validated_anonymity,
						// 'name' => $handle_name,
						// 'comment' => $validated_comment,
						// 'bbs_thread_no' => $db_bbs_thread_arr['bbs_thread_no'],
						// 'bbs_comment_no' => $notifications_bbs_comment_no
					// ));

				}



				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				if ($validated_bbs_comment_no and $authority_edit)
				{

					// --------------------------------------------------
					//   画像設定
					// --------------------------------------------------

					// 保存先パス設定
					$path = DOCROOT . 'assets/img/bbs/comment/' . $validated_bbs_comment_no . '/';

					// 名前設定
					$image_name = '';


					// --------------------------------------------------
					//   画像削除
					// --------------------------------------------------

					// if ($validated_image_delete_1)
					// {
						// $original_func_common->image_delete($path, $image_name, array(1));
						// $save_arr['image'] = null;
					// }

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
						$result_upload_image_arr = $original_func_common->zebra_image_save2($path, $image_name_arr, true, Config::get('limit_bbs_comment_image'));

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

						/*
						if ($width_height_arr)
						{
							$image_arr = $width_height_arr;
							$save_arr['image'] = serialize($image_arr);
						}
						else
						{
							$save_arr['image'] = null;
						}
						*/
					}


					// --------------------------------------------------
					//    データベース更新
					// --------------------------------------------------

					unset($save_arr['regi_date'], $save_arr['user_no'], $save_arr['profile_no']);
					$save_arr['bbs_thread_no'] = $db_bbs_thread_no;
					$result = $model_co->update_bbs_comment($validated_bbs_comment_no, $save_arr);

					//echo "<br>更新用 save_arr";
					//var_dump($save_arr);

					//var_dump($result);

					$code_output = true;

				}


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

				if ($code_output)
				{
					$view = $original_code_co->bbs($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, 1);
					$arr['code'] = $view->render();
				}
				else
				{
					$arr['code'] = null;
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

				//echo $error_message;
				//$arr['test'] = 'エラー ' . $error_message;

			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//echo $e->getMessage();
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
	* BBSコメント　削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_bbs_comment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		$test = false;

		if ($test)
		{
			$_POST['bbs_comment_no'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|check_bbs_comment_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_comment_no = $val->validated('bbs_comment_no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;

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

				$db_bbs_comment_arr = $model_co->get_bbs_comment($validated_bbs_comment_no);
				$db_community_arr = $model_co->get_community($db_bbs_comment_arr['community_no'], null);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				// 日時
				$datetime_past = $original_common_date->sql_format('-30 minutes');

				if (isset($db_bbs_comment_arr['user_no'], $this->user_no) and $db_bbs_comment_arr['user_no'] == $this->user_no)
				{
					$authority_edit = true;
				}
				else if ($db_bbs_comment_arr['renewal_date'] > $datetime_past and $db_bbs_comment_arr['host'] == $this->host and $db_bbs_comment_arr['user_agent'] == $this->user_agent)
				{
					$authority_edit = true;
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_edit and ! $authority_arr['operate_bbs_delete'] and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				if ($test)
				{
					echo '$validated_bbs_comment_no';
					Debug::dump($validated_bbs_comment_no);

					echo '$db_bbs_comment_arr';
					Debug::dump($db_bbs_comment_arr);

					echo '$db_community_arr';
					Debug::dump($db_community_arr);

					echo "<br>authority_edit";
					var_dump($authority_edit);
				}



				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);
				//echo "<br>login_profile_data_arr";
				//var_dump($login_profile_data_arr);



				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array(
					'on_off' => null,
					'renewal_date' => $datetime_now,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				);


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->delete_bbs_comment($db_bbs_comment_arr['bbs_thread_no'], $validated_bbs_comment_no, $save_arr);

				if ($test)
				{
					echo '$save_arr';
					Debug::dump($save_arr);
				}


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = $original_code_co->bbs($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, 1);
				$arr['code'] = $view->render();

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

				if ($test) echo $error_message;

			}

		}
		catch (Exception $e) {

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'エラー';
			$arr['alert_message'] = '削除できませんでした。';

			if ($test) echo $e->getMessage();
		}


		if ($test)
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}





	/**
	* BBS返信投稿フォーム表示
	*
	*/
	public function post_show_write_bbs_reply_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['bbs_comment_no'] = 36;
			$_POST['bbs_reply_no'] = 28;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|match_pattern["^[1-9]\d*$"]|check_bbs_comment_no');
			if (Input::post('bbs_reply_no')) $val->add_field('bbs_reply_no', 'BBS Reply No', 'required|match_pattern["^[1-9]\d*$"]|check_bbs_reply_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_comment_no = $val->validated('bbs_comment_no');
				$validated_bbs_reply_no = $val->validated('bbs_reply_no');


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

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				$db_bbs_comment_arr = $model_co->get_bbs_comment($validated_bbs_comment_no);
				$db_bbs_reply_arr = ($validated_bbs_reply_no) ? $model_co->get_bbs_reply($validated_bbs_reply_no) : null;
				$db_community_arr = $model_co->get_community($db_bbs_comment_arr['community_no'], null);

				$db_bbs_comment_arr['image'] = null;
				$db_bbs_comment_arr['movie'] = null;

				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_bbs_comment'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


				// --------------------------------------------------
				//    投稿者ハンドルネーム取得
				// --------------------------------------------------

				if ($db_bbs_reply_arr)
				{
					if ($db_bbs_reply_arr['profile_no'])
					{
						$db_profile_arr = $model_user->get_profile($db_bbs_reply_arr['profile_no']);
						$comment_to_handle_name = $db_profile_arr['handle_name'];
					}
					else if ($db_bbs_reply_arr['user_no'])
					{
						$db_profile_arr = $model_user->get_user_data($db_bbs_reply_arr['user_no'], null);
						$comment_to_handle_name = $db_profile_arr['handle_name'];
					}
					else
					{
						$comment_to_handle_name = $db_bbs_reply_arr['handle_name'];
					}

					$comment_to = $comment_to_handle_name . 'さんへ' . "\n\n";
				}
				else
				{
					$comment_to = null;
				}



				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$arr['code'] = '<div class="bbs_reply_enclosure_form">';

				$view = View::forge('parts/form_common_view');
				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set('login_user_no', $this->user_no);
				$view->set('datetime_now', $datetime_now);
				$view->set('profile_arr', $login_profile_data_arr);
				$view->set_safe('online_limit', $config_arr['online_limit']);
				$view->set_safe('anonymity', $config_arr['anonymity']);
				$view->set('comment_to', $comment_to);
				$view->set('func_name', 'saveBbsReply');
				$view->set('func_argument_arr', array($validated_bbs_comment_no, 'null'));
				$view->set('func_name_return', 'removeWriteBbsReplyForm');
				$view->set('func_argument_return_arr', array($validated_bbs_comment_no));
				$view->set_safe('title_off', true);
				//$view->set_safe('image_movie_off', false);

				$arr['code'] .= $view->render();

				$arr['code'] .= '</div>';


				if (isset($test))
				{
					Debug::$js_toggle_open = true;


					echo "validated_bbs_comment_no";
					Debug::dump($validated_bbs_comment_no);

					echo "<br>login_profile_data_arr";
					Debug::dump($login_profile_data_arr);

					echo "db_bbs_comment_arr";
					Debug::dump($db_bbs_comment_arr);

					if (isset($db_bbs_reply_arr))
					{
						echo "db_bbs_reply_arr";
						Debug::dump($db_bbs_reply_arr);
					}

					echo "db_community_arr";
					Debug::dump($db_community_arr);

					echo "comment_to";
					Debug::dump($comment_to);


					//echo $view->render();
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
				*/
				//echo $error_message;
				//$arr['test'] = 'エラー ' . $error_message;
			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			if (isset($test)) echo $e->getMessage();
		}


		//var_dump($arr);

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
	* BBS返信編集フォーム表示
	*
	*/
	public function post_show_edit_bbs_reply_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['bbs_reply_no'] = 7;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('bbs_reply_no', 'BBS Reply No', 'required|match_pattern["^[1-9]\d*$"]|check_bbs_reply_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_reply_no = $val->validated('bbs_reply_no');


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

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティデータ取得
				// --------------------------------------------------

				$db_bbs_reply_arr = $model_co->get_bbs_reply($validated_bbs_reply_no);
				$db_community_arr = $model_co->get_community($db_bbs_reply_arr['community_no'], null);

				$db_bbs_reply_arr['image'] = (isset($db_bbs_reply_arr['image'])) ? unserialize($db_bbs_reply_arr['image']) : null;
				$db_bbs_reply_arr['movie'] = (isset($db_bbs_reply_arr['movie'])) ? unserialize($db_bbs_reply_arr['movie']) : null;

				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				// 日時
				$datetime_past = $original_common_date->sql_format('-30 minutes');

				if (isset($db_bbs_reply_arr['user_no'], $this->user_no) and $db_bbs_reply_arr['user_no'] == $this->user_no)
				{
					$authority_edit = true;
				}
				else if ($db_bbs_reply_arr['renewal_date'] > $datetime_past and $db_bbs_reply_arr['host'] == $this->host and $db_bbs_reply_arr['user_agent'] == $this->user_agent)
				{
					$authority_edit = true;
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_edit)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ユーザー情報
				// --------------------------------------------------

				if ($db_bbs_reply_arr['profile_no'])
				{
					$profile_arr = $model_user->get_profile($db_bbs_reply_arr['profile_no']);
				}
				else if ($db_bbs_reply_arr['user_no'])
				{
					$profile_arr = $model_user->get_user_data_personal_box($db_bbs_reply_arr['user_no'], null);
				}
				else
				{
					$profile_arr = null;
				}


				// --------------------------------------------------
				//    画像URL設定
				// --------------------------------------------------

				if (isset($db_bbs_reply_arr['bbs_reply_no'], $db_bbs_reply_arr['bbs_comment_no']))
				{
					$image_url_base = $this->uri_base . 'assets/img/bbs/reply/' . $db_bbs_reply_arr['bbs_reply_no'] . '/';
				}
				else
				{
					$image_url_base = $this->uri_base . 'assets/img/bbs/reply/' . $db_bbs_reply_arr['bbs_reply_no'] . '/';
				}


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = View::forge('parts/form_common_view');
				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set('login_user_no', $this->user_no);
				$view->set('datetime_now', $datetime_now);
				$view->set('profile_arr', $profile_arr);
				$view->set_safe('online_limit', $config_arr['online_limit']);
				$view->set_safe('anonymity', $config_arr['anonymity']);
				$view->set('func_name', 'saveBbsReply');
				$view->set('func_argument_arr', array('null', $validated_bbs_reply_no));
				$view->set('func_name_return', 'removeEditBbsReplyForm');
				$view->set('func_argument_return_arr', array($validated_bbs_reply_no));
				$view->set('func_name_delete', 'deleteBbsReply');
				$view->set('func_argument_delete_arr', array($validated_bbs_reply_no));
				$view->set('data_arr', $db_bbs_reply_arr);
				$view->set_safe('title_off', true);
				$view->set('image_url_base', $image_url_base);

				$arr['code'] = $view->render();



				if (isset($test))
				{
					echo "validated_bbs_reply_no";
					var_dump($validated_bbs_reply_no);

					echo "<br>authority_edit";
					var_dump($authority_edit);

					echo "<br>profile_arr";
					var_dump($profile_arr);

					echo '$db_bbs_reply_arr';
					var_dump($db_bbs_reply_arr);

					echo '$db_community_arr';
					var_dump($db_community_arr);

					echo $view->render();
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
				*/
				//echo $error_message;
				//$arr['test'] = 'エラー ' . $error_message;
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
	* BBS返信書き込み・更新
	*
	* @return string HTMLコード
	*/
	public function post_save_bbs_reply()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['bbs_comment_no'] = 6;
			$_POST['bbs_reply_no'] = 20;
			$_POST['handle_name'] = 'バグ';
			$_POST['comment'] = 'なんでやねん！2';
			//$_POST['anonymity'] = true;
			$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';

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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');

			if (Input::post('bbs_comment_no'))
			{
				$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|check_bbs_comment_no');
			}
			else if (Input::post('bbs_reply_no'))
			{
				$val->add_field('bbs_reply_no', 'BBS Reply No', 'required|check_bbs_reply_no');
			}
			else
			{
				throw new Exception('Error');
			}

			if ( ! $this->user_no) $val->add_field('handle_name', 'ハンドルネーム', 'required|min_length[1]|max_length[50]');
			$val->add_field('comment', 'コメント', 'required|min_length[1]|max_length[3000]');
			$val->add_field('movie_url', 'Movie URL', 'valid_url|check_movie_url');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_comment_no = $val->validated('bbs_comment_no');
				$validated_bbs_reply_no = $val->validated('bbs_reply_no');
				$validated_handle_name = $val->validated('handle_name');
				$validated_comment = $val->validated('comment');
				$validated_anonymity = (Input::post('anonymity')) ? 1: null;
				$validated_movie_url = $val->validated('movie_url');
				$validated_image_1_delete = (Input::post('image_1_delete')) ? 1: null;


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$model_notifications = new Model_Notifications();
				$model_notifications->agent_type = $this->agent_type;
				$model_notifications->user_no = $this->user_no;
				$model_notifications->language = $this->language;
				$model_notifications->uri_base = $this->uri_base;
				$model_notifications->uri_current = $this->uri_current;

				$model_common = new Model_Common();
				$model_common->user_no = $this->user_no;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->host = $this->host;
				$original_code_co->user_agent = $this->user_agent;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;

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

				if ($validated_bbs_comment_no)
				{
					$db_bbs_comment_arr = $model_co->get_bbs_comment($validated_bbs_comment_no);
					$db_community_arr = $model_co->get_community($db_bbs_comment_arr['community_no'], null);
					$db_bbs_thread_no = $db_bbs_comment_arr['bbs_thread_no'];
					$db_bbs_comment_no = $db_bbs_comment_arr['bbs_comment_no'];
				}
				else if ($validated_bbs_reply_no)
				{
					$db_bbs_reply_arr = $model_co->get_bbs_reply($validated_bbs_reply_no);
					$db_community_arr = $model_co->get_community($db_bbs_reply_arr['community_no'], null);
					$db_bbs_thread_no = $db_bbs_reply_arr['bbs_thread_no'];
					$db_bbs_comment_no = $db_bbs_reply_arr['bbs_comment_no'];
				}

				$image_arr = (isset($db_bbs_reply_arr['image'])) ? $db_bbs_reply_arr['image'] : null;
				//$movie_arr = (isset($db_bbs_reply_arr['movie'])) ? $db_bbs_reply_arr['movie'] : null;
				$movie_arr = null;


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				if (isset($db_bbs_reply_arr))
				{
					// 日時
					$datetime_past = $original_common_date->sql_format('-30 minutes');

					if (isset($db_bbs_reply_arr['user_no'], $this->user_no) and $db_bbs_reply_arr['user_no'] == $this->user_no)
					{
						$authority_edit = true;
					}
					else if ($db_bbs_reply_arr['renewal_date'] > $datetime_past and $db_bbs_reply_arr['host'] == $this->host and $db_bbs_reply_arr['user_agent'] == $this->user_agent)
					{
						$authority_edit = true;
					}
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_bbs_comment'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


				// --------------------------------------------------
				//   削除済みユーザー
				// --------------------------------------------------

				if ($login_profile_data_arr === false)
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
				$result_check_upload_image_arr = $original_func_common->check_upload_image($image_name_arr, Config::get('limit_bbs_reply_image'));

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

				$user_no = null;
				$profile_no = null;
				$handle_name = null;
				$movie = null;

				if (isset($login_profile_data_arr['profile_no']))
				{
					$user_no = $login_profile_data_arr['author_user_no'];
					$profile_no = $login_profile_data_arr['profile_no'];
				}
				else if (isset($login_profile_data_arr['user_no']))
				{
					$user_no = $login_profile_data_arr['user_no'];
				}
				else
				{
					$handle_name = $validated_handle_name;
				}


				// ------------------------------
				//    動画
				// ------------------------------

				if ($validated_movie_url)
				{
					$movie_arr = $original_func_common->return_movie(array($validated_movie_url), $movie_arr, Config::get('limit_bbs_reply_movie'));
					if ($movie_arr) $movie_arr = serialize($movie_arr);
				}
				else
				{
					$movie_arr = null;
				}


				$save_arr = array(
					'regi_date' => $datetime_now,
					'renewal_date' => $datetime_now,
					'sort_date' => $datetime_now,
					'community_no' => $db_community_arr['community_no'],
					'bbs_thread_no' => $db_bbs_thread_no,
					'bbs_comment_no' => $db_bbs_comment_no,
					'user_no' => $user_no,
					'profile_no' => $profile_no,
					'anonymity' => $validated_anonymity,
					'handle_name' => $handle_name,
					'comment' => $validated_comment,
					'image' => null,
					'movie' => $movie_arr,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				);

				if (isset($test))
				{
					echo "<br>挿入用　save_arr";
					var_dump($save_arr);
				}



				// --------------------------------------------------
				//    挿入や更新が成功した場合のみコードを出力する
				// --------------------------------------------------

				$code_output = false;


				// --------------------------------------------------
				//    データベース挿入　二重書き込み防止機能あり
				// --------------------------------------------------

				if ($validated_bbs_comment_no)
				{

					$validated_bbs_reply_no = $model_co->insert_bbs_reply($save_arr);

					$notifications_bbs_reply_no = $validated_bbs_reply_no;


					if ($validated_bbs_reply_no and $uploaded_image_existence)
					{
						$authority_edit = true;
					}
					else if ($validated_bbs_reply_no and $uploaded_image_existence === false)
					{
						// アップロードされた画像がない場合、更新しない
						$validated_bbs_reply_no = false;
					}
					else if ($validated_bbs_comment_no === false)
					{
						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = '二重書き込みです。';
						throw new Exception('Error');
					}

					$code_output = true;


					// --------------------------------------------------
					//   お知らせ保存
					// --------------------------------------------------

					$db_bbs_thread_arr = $model_co->get_bbs_thread($db_bbs_thread_no);
					$game_list_arr = $original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

					$save_notifications_arr = array(
						'regi_date' => $datetime_now,
						'target_user_no' => null,
						'community_no' => $db_bbs_thread_arr['community_no'],
						'game_no' => $game_list_arr[0],
						'type1' => 'uc',
						'type2' => 'bbs_reply',
						'title' => $db_bbs_thread_arr['title'],
						'anonymity' => $validated_anonymity,
						'name' => $handle_name,
						'comment' => $validated_comment,
						'bbs_thread_no' => $db_bbs_thread_no,
						'bbs_comment_no' => $db_bbs_comment_no,
						'bbs_reply_no' => $notifications_bbs_reply_no
					);

					if (isset($login_profile_data_arr['profile_no'])) $save_notifications_arr['profile_no'] = $login_profile_data_arr['profile_no'];

					$model_notifications->save_notifications($save_notifications_arr);

					// $original_func_common->save_notifications(array(
						// 'regi_date' => $datetime_now,
						// 'target_user_no' => null,
						// 'community_no' => $db_bbs_thread_arr['community_no'],
						// 'game_no' => $game_list_arr[0],
						// 'type1' => 'uc',
						// 'type2' => 'bbs_reply',
						// 'title' => $db_bbs_thread_arr['title'],
						// 'anonymity' => $validated_anonymity,
						// 'name' => $handle_name,
						// 'comment' => $validated_comment,
						// 'bbs_thread_no' => $db_bbs_thread_no,
						// 'bbs_comment_no' => $db_bbs_comment_no,
						// 'bbs_reply_no' => $validated_bbs_reply_no
					// ));

				}


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				if ($validated_bbs_reply_no and $authority_edit)
				{

					// --------------------------------------------------
					//   画像設定
					// --------------------------------------------------

					// 保存先パス設定
					$path = DOCROOT . 'assets/img/bbs/reply/' . $validated_bbs_reply_no . '/';

					// 名前設定
					$image_name = '';


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
						$result_upload_image_arr = $original_func_common->zebra_image_save2($path, $image_name_arr, true, Config::get('limit_bbs_reply_image'));

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

						/*
						if ($width_height_arr)
						{
							$image_arr = $width_height_arr;
							$save_arr['image'] = serialize($image_arr);
						}
						else
						{
							$save_arr['image'] = null;
						}
						*/
					}


					// --------------------------------------------------
					//    データベース更新
					// --------------------------------------------------

					//unset($save_arr['regi_date'], $save_arr['community_no'], $save_arr['bbs_thread_no'], $save_arr['bbs_comment_no'], $save_arr['user_no'], $save_arr['profile_no']);
					unset($save_arr['regi_date'], $save_arr['user_no'], $save_arr['profile_no']);
					$result = $model_co->update_bbs_reply($validated_bbs_reply_no, $save_arr);

					$code_output = true;

				}


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo "バリデーション後";
					var_dump($validated_bbs_comment_no, $validated_bbs_reply_no, $validated_handle_name, $validated_comment, $validated_anonymity);

					if (isset($db_bbs_comment_arr))
					{
						echo '$db_bbs_comment_arr';
						Debug::dump($db_bbs_comment_arr);
					}

					if (isset($db_bbs_reply_arr))
					{
						echo '$db_bbs_reply_arr';
						Debug::dump($db_bbs_reply_arr);
					}

					echo '$db_community_arr';
					Debug::dump($db_community_arr);

					echo "<br>authority_edit";
					var_dump($authority_edit);

					echo "<br>login_profile_data_arr";
					var_dump($login_profile_data_arr);

					echo "<br>validated_bbs_reply_no";
					var_dump($validated_bbs_reply_no);

					echo "<br>更新用 save_arr";
					var_dump($save_arr);

				}



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

				if ($code_output)
				{
					$view = $original_code_co->bbs($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, 1);
					$arr['code'] = $view->render();
				}
				else
				{
					$arr['code'] = null;
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

				//echo $error_message;
				//$arr['test'] = 'エラー ' . $error_message;

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
	* BBS返信　削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_bbs_reply()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		$test = false;

		if ($test)
		{
			$_POST['bbs_reply_no'] = 3;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');

			$val->add_field('bbs_reply_no', 'BBS Reply No', 'required|check_bbs_reply_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_bbs_reply_no = $val->validated('bbs_reply_no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;

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

				$db_bbs_reply_arr = $model_co->get_bbs_reply($validated_bbs_reply_no);
				$db_community_arr = $model_co->get_community($db_bbs_reply_arr['community_no'], null);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// ------------------------------
				//    編集権限
				// ------------------------------

				$authority_edit = false;

				// 日時
				$datetime_past = $original_common_date->sql_format('-30 minutes');

				if (isset($db_bbs_reply_arr['user_no'], $this->user_no) and $db_bbs_reply_arr['user_no'] == $this->user_no)
				{
					$authority_edit = true;
				}
				else if ($db_bbs_reply_arr['renewal_date'] > $datetime_past and $db_bbs_reply_arr['host'] == $this->host and $db_bbs_reply_arr['user_agent'] == $this->user_agent)
				{
					$authority_edit = true;
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_edit and ! $authority_arr['operate_bbs_delete'] and ! Auth::member(100))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array(
					'on_off' => null,
					'renewal_date' => $datetime_now,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				);


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->delete_bbs_reply($db_bbs_reply_arr['bbs_comment_no'], $validated_bbs_reply_no, $save_arr);

				if ($test)
				{
					echo '$save_arr';
					Debug::dump($save_arr);
				}


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$view = $original_code_co->bbs($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, 1);
				$arr['code'] = $view->render();



				if ($test)
				{
					echo '$validated_bbs_reply_no';
					Debug::dump($validated_bbs_reply_no);

					echo '$db_bbs_reply_arr';
					Debug::dump($db_bbs_reply_arr);

					echo '$db_community_arr';
					Debug::dump($db_community_arr);

					echo "<br>authority_edit";
					var_dump($authority_edit);

					echo "<br>login_profile_data_arr";
					var_dump($login_profile_data_arr);
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
				$arr['alert_message'] = '削除できませんでした。';

				if ($test) echo $error_message;

			}

		}
		catch (Exception $e) {

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'エラー';
			$arr['alert_message'] = '削除できませんでした。';

			if ($test) echo $e->getMessage();
		}


		if ($test)
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}







	/**
	* 参加申請メンバーを表示する
	*
	* @return string HTMLコード
	*/
	public function post_read_member_provisional()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['community_no'] = 1;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   フォームが正しい経緯で送信されていません。
			// --------------------------------------------------
			/*
			$cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
			$post_csrf_token = Input::post('fuel_csrf_token');

			if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			{
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
				throw new Exception('Error');
			}
			*/

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Co');

			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page = $val->validated('page');
				$validated_community_no = $val->validated('community_no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->host = $this->host;
				$original_code_co->user_agent = $this->user_agent;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				/*$member_arr = unserialize($db_community_arr['member']);
				$provisional_arr = (isset($db_community_arr['provisional'])) ? unserialize($db_community_arr['provisional']) : null;
				$config_arr = unserialize($db_community_arr['config']);

				$db_user_data_arr = $model_user->get_user_data($this->user_no, null);
				$db_profile_arr = (isset($validated_profile_no)) ? $model_user->get_profile($validated_profile_no) : null;
				$participation_community_arr = (isset($db_user_data_arr['participation_community'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community']) : array();
				$participation_community_secret_arr = (isset($db_user_data_arr['participation_community_secret'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community_secret']) : array();
				*/

				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_member'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				$arr['code'] = $original_code_co->member_provisional($db_community_arr, $validated_page);

				/*
				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = 'コミュニティに参加しました。';
				*/

				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_page';
					var_dump($validated_page);

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$db_community_arr';
					var_dump($db_community_arr);

					echo '$authority_arr';
					var_dump($authority_arr);


					//echo $original_code_co->member($db_community_arr, $validated_page, $validated_type);

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
				$arr['alert_message'] = 'コミュニティに参加できませんでした。' . $error_message;

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
	* BANされたメンバーを表示する
	*
	* @return string HTMLコード
	*/
	public function post_read_member_ban()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['community_no'] = 1;
		}


		$arr = array();

		try
		{
			/*
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
			*/

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_callable('Original_Rule_Co');

			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_page = $val->validated('page');
				$validated_community_no = $val->validated('community_no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->host = $this->host;
				$original_code_co->user_agent = $this->user_agent;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_member'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------

				$arr['code'] = $original_code_co->member_ban($db_community_arr, $validated_page);

				/*
				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = 'コミュニティに参加しました。';
				*/

				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_page';
					var_dump($validated_page);

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$db_community_arr';
					var_dump($db_community_arr);

					echo '$authority_arr';
					var_dump($authority_arr);


					//echo $original_code_co->member($db_community_arr, $validated_page, $validated_type);

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
				$arr['alert_message'] = 'コミュニティに参加できませんでした。' . $error_message;

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
	* コミュニティに参加する
	*
	* @return string HTMLコード
	*/
	public function post_join_community()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$this->user_no = 1;
			$_POST['community_no'] = 1;
			$_POST['select_profile'] = 'user';
			$_POST['type'] = 2;
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
			$val->add_callable('Original_Rule_Co');

			$val->add_field('community_no', 'Community No', 'required|check_community_no');
			if (Input::post('select_profile') != 'user') $val->add_field('select_profile', 'Select Profile', 'required|check_profile_author');
			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(1|2)$/');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				if (Input::post('select_profile') != 'user') $validated_profile_no = $val->validated('select_profile');
				$validated_type = $val->validated('type');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);
				$provisional_arr = (isset($db_community_arr['provisional'])) ? unserialize($db_community_arr['provisional']) : null;
				$ban_arr = (isset($db_community_arr['ban'])) ? unserialize($db_community_arr['ban']) : null;
				$config_arr = unserialize($db_community_arr['config']);

				$db_user_data_arr = $model_user->get_user_data($this->user_no, null);
				$db_profile_arr = (isset($validated_profile_no)) ? $model_user->get_profile($validated_profile_no) : null;
				$participation_community_arr = (isset($db_user_data_arr['participation_community'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community']) : array();
				$participation_community_secret_arr = (isset($db_user_data_arr['participation_community_secret'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community_secret']) : array();


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   メンバー登録済み
				// --------------------------------------------------

				if ($authority_arr['member'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'メンバー登録済み';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   参加申請がすでに出されています。
				// --------------------------------------------------

				if (isset($provisional_arr[$this->user_no]))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '参加申請がすでに出されています。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   BANされています。
				// --------------------------------------------------

				if (isset($ban_arr[$this->user_no]))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = 'このコミュニティへの参加を禁じられています。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_user_data_arr = null;


				// ------------------------------
				//    参加メンバー　誰でも参加
				// ------------------------------

				//$config_arr['participation_type'] = 2;

				if ($config_arr['participation_type'] == 1)
				{
					$member_arr[$this->user_no]['profile_no'] = (isset($validated_profile_no)) ? (int) $validated_profile_no : null;
					$member_arr[$this->user_no]['administrator'] = false;
					$member_arr[$this->user_no]['moderator'] = false;
					$member_arr[$this->user_no]['access_date'] = $datetime_now;
					$member_arr[$this->user_no]['mail_all'] = true;
					//ksort($member_arr);

					$save_commutniy_arr['member'] = serialize($member_arr);


					// ------------------------------
					//    参加コミュニティ　User Data
					// ------------------------------

					if (empty($db_profile_arr) or isset($db_profile_arr['open_profile']))
					{
						if ( ! in_array($validated_community_no, $participation_community_arr)) array_push($participation_community_arr, $validated_community_no);
						if (count($participation_community_arr) > 0)
						{
							sort($participation_community_arr);
							//$save_user_data_arr['participation_community'] = serialize($participation_community_arr);
							$save_user_data_arr['participation_community'] = $original_func_common->return_db_array('php_db', $participation_community_arr);
						}

					}
					else
					{
						if ( ! in_array($validated_community_no, $participation_community_secret_arr)) array_push($participation_community_secret_arr, $validated_community_no);
						if (count($participation_community_secret_arr) > 0)
						{
							sort($participation_community_secret_arr);
							//$save_user_data_arr['participation_community_secret'] = serialize($participation_community_secret_arr);
							$save_user_data_arr['participation_community_secret'] = $original_func_common->return_db_array('php_db', $participation_community_secret_arr);
						}
					}

				}

				// ------------------------------
				//    参加申請
				// ------------------------------

				else
				{
					$provisional_arr[$this->user_no]['profile_no'] = (isset($validated_profile_no)) ? (int) $validated_profile_no : null;
					$provisional_arr[$this->user_no]['datetime'] = $datetime_now;

					//ksort($provisional_arr);

					$save_commutniy_arr['provisional'] = serialize($provisional_arr);
				}


				// ------------------------------
				//    参加メンバー数
				// ------------------------------

				$save_commutniy_arr['member_total'] = count($member_arr);



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$db_community_arr';
					var_dump($db_community_arr);



					echo '$config_arr';
					var_dump($config_arr);


					echo '$member_arr';
					var_dump($member_arr);

					if (isset($provisional_arr))
					{
						echo '$provisional_arr';
						var_dump($provisional_arr);
					}


					echo '$db_user_data_arr';
					var_dump($db_user_data_arr);

					if (isset($validated_profile_no))
					{
						echo '$validated_profile_no';
						var_dump($validated_profile_no);

						echo '$db_profile_arr';
						var_dump($db_profile_arr);
					}


					if (isset($participation_community_arr))
					{
						echo '$participation_community_arr';
						var_dump($participation_community_arr);
					}

					if (isset($participation_community_secret_arr))
					{
						echo '$participation_community_secret_arr';
						var_dump($participation_community_secret_arr);
					}


					echo '$save_commutniy_arr';
					var_dump($save_commutniy_arr);

					echo '$save_user_data_arr';
					var_dump($save_user_data_arr);

					echo '$this->user_no';
					var_dump($this->user_no);

					//echo '$authority_arr';
					//var_dump($authority_arr);
					/*
					echo '$save_arr';
					var_dump($save_arr);
					*/
				}
				//exit();


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->update_community_and_user_data($validated_community_no, $save_commutniy_arr, $this->user_no, $save_user_data_arr);


				// --------------------------------------------------
				//   Access Date更新
				// --------------------------------------------------

				//$original_func_common->renew_access_date($this->user_no, null, $db_community_arr);


				// --------------------------------------------------
				//   未読通知を既読通知にする　新規に参加するとこれまでの通知が大量に表示されるからそれを防止する
				// --------------------------------------------------

				$change_notifications_already_arr = array('community_no' => $validated_community_no);
				$result_arr = $original_func_common->change_notifications_already($change_notifications_already_arr);



				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = ($validated_type == 1) ? 'コミュニティに参加しました。' : 'コミュニティに参加申請しました。';
				//$arr['alert_message'] = 'コミュニティに参加しました。';

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
				$arr['alert_message'] = 'コミュニティに参加できませんでした。' . $error_message;

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
	* コミュニティから退会する
	*
	* @return string HTMLコード
	*/
	public function post_withdraw_community()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
			$_POST['type'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');
			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(1|2)$/');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_type = $val->validated('type');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);
				$provisional_arr = (isset($db_community_arr['provisional'])) ? unserialize($db_community_arr['provisional']) : array();
				$config_arr = unserialize($db_community_arr['config']);

				$db_user_data_arr = $model_user->get_user_data($this->user_no, null);
				//$db_profile_arr = (isset($validated_profile_no)) ? $model_user->get_profile($validated_profile_no) : null;
				//$participation_community_arr = (isset($db_user_data_arr['participation_community'])) ? unserialize($db_user_data_arr['participation_community']) : array();
				//$participation_community_secret_arr = (isset($db_user_data_arr['participation_community_secret'])) ? unserialize($db_user_data_arr['participation_community_secret']) : array();

				$participation_community_arr = (isset($db_user_data_arr['participation_community'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community']) : array();
				$participation_community_secret_arr = (isset($db_user_data_arr['participation_community_secret'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community_secret']) : array();


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);

				// 参加申請のチェック
				if ($config_arr['participation_type'] == 2 and $db_community_arr['provisional'])
				{
					$provisional_arr = unserialize($db_community_arr['provisional']);
					$provisional_member = (isset($provisional_arr[$this->user_no])) ? true : false;
				}
				else
				{
					$provisional_member = false;
				}


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['member'] and ! $provisional_member)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   管理者は退会できません。
				// --------------------------------------------------

				if ($authority_arr['administrator'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '管理者は退会できません。';
					throw new Exception('Error');
				}



				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				//$participation_community_secret_arr = array(1,2,5,3, 51, 4);


				// ------------------------------
				//    配列から削除
				// ------------------------------

				unset($member_arr[$this->user_no]);
				unset($provisional_arr[$this->user_no]);

				$array_search_key = array_search($validated_community_no, $participation_community_arr);
				if ($array_search_key !== false) array_splice($participation_community_arr, $array_search_key, 1);

				$array_search_key = array_search($validated_community_no, $participation_community_secret_arr);
				if ($array_search_key !== false) array_splice($participation_community_secret_arr, $array_search_key, 1);

				//array_splice($participation_community_arr, array_search($validated_community_no, $participation_community_arr), 1);
				//array_splice($participation_community_secret_arr, array_search($validated_community_no, $participation_community_secret_arr), 1);


				// ------------------------------
				//    配列の並び替え
				// ------------------------------

				//ksort($member_arr);
				//ksort($provisional_arr);
				sort($participation_community_arr);
				sort($participation_community_secret_arr);


				// ------------------------------
				//    保存用の配列
				// ------------------------------

				$save_commutniy_arr['member'] = serialize($member_arr);
				$save_commutniy_arr['provisional'] = (count($provisional_arr) > 0) ? serialize($provisional_arr) : null;
				$save_user_data_arr['participation_community'] = (count($participation_community_arr) > 0) ? $original_func_common->return_db_array('php_db', $participation_community_arr) : null;
				$save_user_data_arr['participation_community_secret'] = (count($participation_community_secret_arr) > 0) ? $original_func_common->return_db_array('php_db', $participation_community_secret_arr) : null;
				//$save_user_data_arr['participation_community'] = (count($participation_community_arr) > 0) ? serialize($participation_community_arr) : null;
				//$save_user_data_arr['participation_community_secret'] = (count($participation_community_secret_arr) > 0) ? serialize($participation_community_secret_arr) : null;


				// ------------------------------
				//    参加メンバー数
				// ------------------------------

				$save_commutniy_arr['member_total'] = count($member_arr);



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$db_community_arr';
					var_dump($db_community_arr);



					echo '$config_arr';
					var_dump($config_arr);


					echo '$member_arr';
					var_dump($member_arr);

					if (isset($provisional_arr))
					{
						echo '$provisional_arr';
						var_dump($provisional_arr);
					}


					echo '$db_user_data_arr';
					var_dump($db_user_data_arr);

					if (isset($validated_profile_no))
					{
						echo '$validated_profile_no';
						var_dump($validated_profile_no);

						echo '$db_profile_arr';
						var_dump($db_profile_arr);
					}


					echo '$participation_community_arr';
					var_dump($participation_community_arr);

					echo '$participation_community_secret_arr';
					var_dump($participation_community_secret_arr);


					echo '$save_commutniy_arr';
					var_dump($save_commutniy_arr);

					echo '$save_user_data_arr';
					var_dump($save_user_data_arr);


					echo '$authority_arr';
					var_dump($authority_arr);

				}
				//exit();


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->update_community_and_user_data($validated_community_no, $save_commutniy_arr, $this->user_no, $save_user_data_arr);


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = ($validated_type == 1) ? 'コミュニティを退会しました。' : 'コミュニティへの参加申請を取り消しました。';
				//$arr['alert_message'] = 'コミュニティを退会しました。';

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
				$arr['alert_message'] = 'コミュニティから退会できませんでした。' . $error_message;

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
	* コミュニティから退会・BANさせる　メンバー
	*
	* @return string HTMLコード
	*/
	public function post_withdraw_ban_community_member()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;

			$_POST['type'] = 'user';
			$_POST['no'] = 5;

			//$_POST['type'] = 'profile';
			//$_POST['no'] = 2;

			$_POST['ban'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');
			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(user|profile)$/');
			$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_type = $val->validated('type');
				$validated_no = $val->validated('no');
				$validated_ban = (Input::post('ban')) ? 1: null;


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);
				$ban_arr = (isset($db_community_arr['ban'])) ? unserialize($db_community_arr['ban']) : array();
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//    User No 取得
				// --------------------------------------------------

				if ($validated_type == 'user')
				{
					// プロフィールNoが登録されているのに、ユーザーNoで削除しようとした場合はエラー
					if (empty($member_arr[$validated_no]['profile_no'])) $user_no = $validated_no;
				}
				else
				{
					foreach ($member_arr as $key => $value)
					{
						if ($value['profile_no'] == $validated_no)
						{
							$user_no = $key;
							break;
						}
					}
				}


				// --------------------------------------------------
				//   所属・存在していないため、退会させられないメンバー
				// --------------------------------------------------

				if (empty($user_no) or empty($member_arr[$user_no]))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? '所属・存在していないため、退会させられないメンバー' : 'Error';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   管理者は退会させられない
				// --------------------------------------------------

				if ($member_arr[$user_no]['administrator'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? '管理者は退会させられない' : 'Error';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    データ取得　参加コミュニティ情報
				// --------------------------------------------------

				$db_user_data_arr = $model_user->get_user_data($user_no, null);

				$participation_community_arr = (isset($db_user_data_arr['participation_community'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community']) : array();
				$participation_community_secret_arr = (isset($db_user_data_arr['participation_community_secret'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community_secret']) : array();


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_member'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				// ------------------------------
				//    BANする場合
				// ------------------------------

				if ($validated_ban)
				{
					$ban_arr[$user_no]['profile_no'] = ($validated_type == 'profile') ? $validated_no : null;
					$ban_arr[$user_no]['ban_date'] = $datetime_now;
				}


				// ------------------------------
				//    配列から削除
				// ------------------------------

				unset($member_arr[$user_no]);
				//array_splice($participation_community_arr, array_search($validated_community_no, $participation_community_arr), 1);
				//array_splice($participation_community_secret_arr, array_search($validated_community_no, $participation_community_secret_arr), 1);

				$array_search_key = array_search($validated_community_no, $participation_community_arr);
				if ($array_search_key !== false) array_splice($participation_community_arr, $array_search_key, 1);

				$array_search_key = array_search($validated_community_no, $participation_community_secret_arr);
				if ($array_search_key !== false) array_splice($participation_community_secret_arr, $array_search_key, 1);


				// ------------------------------
				//    配列の並び替え
				// ------------------------------

				sort($participation_community_arr);
				sort($participation_community_secret_arr);


				// ------------------------------
				//    保存用の配列
				// ------------------------------

				$save_commutniy_arr['member'] = serialize($member_arr);
				$save_commutniy_arr['ban'] = (count($ban_arr) > 0) ? serialize($ban_arr) : null;
				$save_user_data_arr['participation_community'] = (count($participation_community_arr) > 0) ? $original_func_common->return_db_array('php_db', $participation_community_arr) : null;
				$save_user_data_arr['participation_community_secret'] = (count($participation_community_secret_arr) > 0) ? $original_func_common->return_db_array('php_db', $participation_community_secret_arr) : null;


				// ------------------------------
				//    参加メンバー数
				// ------------------------------

				$save_commutniy_arr['member_total'] = count($member_arr);



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$validated_type';
					var_dump($validated_type);

					echo '$validated_no';
					var_dump($validated_no);

					echo '$validated_ban';
					var_dump($validated_ban);


					echo '$db_community_arr';
					var_dump($db_community_arr);

					echo '$config_arr';
					var_dump($config_arr);

					echo '$member_arr';
					var_dump($member_arr);

					echo '$user_no';
					var_dump($user_no);


					echo '$db_user_data_arr';
					var_dump($db_user_data_arr);

					echo '$ban_arr';
					var_dump($ban_arr);

					echo '$participation_community_arr';
					var_dump($participation_community_arr);

					echo '$participation_community_secret_arr';
					var_dump($participation_community_secret_arr);


					echo '$save_commutniy_arr';
					var_dump($save_commutniy_arr);

					echo '$save_user_data_arr';
					var_dump($save_user_data_arr);


					echo '$authority_arr';
					var_dump($authority_arr);

				}
				//exit();



				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->update_community_and_user_data($validated_community_no, $save_commutniy_arr, $user_no, $save_user_data_arr);


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$alert_message = ($validated_ban) ? 'コミュニティからBANしました。' : 'コミュニティから退会させました。';

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = $alert_message;

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

				$alert_message = ($validated_ban) ? 'コミュニティからBANできませんでした。' : 'コミュニティから退会させられませんでした。';

				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = $alert_message . $error_message;

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
	* モデレーター認定・解除　メンバー
	*
	* @return string HTMLコード
	*/
	public function post_set_moderator_member()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;

			$_POST['type'] = 'user';
			$_POST['no'] = 39;

			//$_POST['type'] = 'profile';
			//$_POST['no'] = 2;

			//$_POST['ban'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');
			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(user|profile)$/');
			$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_type = $val->validated('type');
				$validated_no = $val->validated('no');
				//$validated_ban = (Input::post('ban')) ? 1: null;


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//    User No 取得
				// --------------------------------------------------

				if ($validated_type == 'user')
				{
					// プロフィールNoが登録されているのに、ユーザーNoで認定・解除しようとした場合はエラー
					if (empty($member_arr[$validated_no]['profile_no'])) $user_no = $validated_no;
				}
				else
				{
					foreach ($member_arr as $key => $value)
					{
						if ($value['profile_no'] == $validated_no)
						{
							$user_no = $key;
							break;
						}
					}
				}


				// --------------------------------------------------
				//   所属・存在していないため、認定・解除させられないメンバー
				// --------------------------------------------------

				if (empty($user_no) or empty($member_arr[$user_no]))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? '所属・存在していないため、認定・解除させられないメンバー' : 'Error';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   管理者は認定・解除させられない
				// --------------------------------------------------

				if ($member_arr[$user_no]['administrator'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? '管理者は認定・解除させられない' : 'Error';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    データ取得　参加コミュニティ情報
				// --------------------------------------------------

				if ($validated_type == 'user')
				{
					$users_data_arr = \Security::htmlentities($model_user->get_user_data_list_in_personal_box_member(array($validated_no)));
				}
				else
				{
					$profile_arr = \Security::htmlentities($model_user->get_profile_list_in_personal_box_member(array($validated_no)));
				}


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['administrator'] and $member_arr[$this->user_no]['moderator'] !== false)
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				if ($authority_arr['administrator'])
				{
					$member_arr[$user_no]['moderator'] = ($member_arr[$user_no]['moderator']) ? false : true;
				}
				else
				{
					$member_arr[$this->user_no]['moderator'] = false;
				}


				// ------------------------------
				//    保存用の配列
				// ------------------------------

				$save_commutniy_arr['member'] = serialize($member_arr);


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->update_community($validated_community_no, $save_commutniy_arr);




				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$code = null;

				if (isset($profile_arr[$member_arr[$user_no]['profile_no']]))
				{
					$pro_arr = $profile_arr[$member_arr[$user_no]['profile_no']];
				}
				else if (isset($users_data_arr[$user_no]))
				{
					$pro_arr = $users_data_arr[$user_no];
				}
				else
				{
					$pro_arr = null;
				}

				//var_dump($arr);

				$view = \View::forge('parts/personal_box_ver3_view');
				//$view->set_safe('app_mode', $app_mode);
				$view->set('uri_base', $this->uri_base);
				$view->set('profile_arr', $pro_arr);
				$view->set_safe('online_limit', $config_arr['online_limit']);


				// ------------------------------
				//    管理用ボタン表示
				// ------------------------------

				if ( ! $member_arr[$user_no]['administrator'])
				{

					// ------------------------------
					//    退会・BAN
					// ------------------------------

					if ($this->user_no != $user_no and $authority_arr['operate_member'])
					{
						$view->set_safe('add_button_member_withdraw', true);
						$view->set_safe('add_button_member_ban', true);
					}

					// ------------------------------
					//    モデレーター認定・解除
					// ------------------------------

					if ($authority_arr['administrator'])
					{
						($member_arr[$user_no]['moderator']) ? $view->set_safe('add_button_member_moderator_withdraw', true) : $view->set_safe('add_button_member_moderator', true);
					}
					else if ($this->user_no == $user_no and $authority_arr['moderator'])
					{
						$view->set_safe('add_button_member_moderator_withdraw', true);
					}

					$view->set('community_no', $db_community_arr['community_no']);

				}

				$view->set_safe('add_explanation', true);

				$arr['code'] = $view->render();


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$validated_type';
					var_dump($validated_type);

					echo '$validated_no';
					var_dump($validated_no);


					echo '$db_community_arr';
					var_dump($db_community_arr);

					echo '$config_arr';
					var_dump($config_arr);

					echo '$member_arr';
					var_dump($member_arr);

					echo '$user_no';
					var_dump($user_no);


					//echo '$db_user_data_arr';
					//var_dump($db_user_data_arr);



					/*
					echo '$save_commutniy_arr';
					var_dump($save_commutniy_arr);

					echo '$save_user_data_arr';
					var_dump($save_user_data_arr);
					*/

					echo '$authority_arr';
					var_dump($authority_arr);

					echo $arr['code'];

				}
				//exit();



				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------
				/*
				$alert_message = ($validated_ban) ? 'コミュニティからBANしました。' : 'コミュニティから退会させました。';

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = $alert_message;
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

				$alert_message = ($validated_ban) ? 'コミュニティからBANできませんでした。' : 'コミュニティから退会させられませんでした。';

				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = $alert_message . $error_message;

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
	* メンバー承認
	*
	* @return string HTMLコード
	*/
	public function post_approval_member()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;

			//$_POST['type'] = 'user';
			//$_POST['no'] = 3;

			$_POST['type'] = 'profile';
			$_POST['no'] = 4;

			$_POST['on_off'] = true;
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
			$val->add_callable('Original_Rule_Co');

			$val->add_field('community_no', 'Community No', 'required|check_community_no');

			if (Input::post('type') == 'user')
			{
				$val->add_field('no', 'User No', 'required|check_user_data');
			}
			else
			{
				$val->add_field('no', 'Profile No', 'required|check_profile');
			}

			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(user|profile)$/');
			//$val->add_field('no', 'No', 'required|match_pattern["^[1-9]\d*$"]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_type = $val->validated('type');
				$validated_no = $val->validated('no');
				$validated_on_off = (Input::post('on_off')) ? 1: null;


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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


				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);
				$provisional_arr = ($db_community_arr['provisional']) ? unserialize($db_community_arr['provisional']) : array();
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_member'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    User No ＆ 参加コミュニティ 取得
				// --------------------------------------------------

				if ($validated_type == 'user')
				{
					$user_no = $validated_no;
					$db_user_data_arr = $model_user->get_user_data($user_no, null);
				}
				else
				{
					$db_profile_arr = $model_user->get_profile($validated_no);
					$user_no = $db_profile_arr['author_user_no'];
					$db_user_data_arr = $model_user->get_user_data($user_no, null);
				}

				$participation_community_arr = (isset($db_user_data_arr['participation_community'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community']) : array();
				$participation_community_secret_arr = (isset($db_user_data_arr['participation_community_secret'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community_secret']) : array();


				// --------------------------------------------------
				//   仮申請されていないメンバー
				// --------------------------------------------------

				if ($validated_type == 'user' and (empty($provisional_arr[$user_no]) or isset($provisional_arr[$user_no]['profile_no'])))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? '仮申請されていないメンバー User' : 'Error';
					throw new Exception('Error');
				}
				else if ($validated_type == 'profile' and empty($provisional_arr[$user_no]['profile_no']))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? '仮申請されていないメンバー Profile' : 'Error';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   メンバーに登録済み
				// --------------------------------------------------

				if (isset($member_arr[$user_no]))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? 'メンバーに登録済み' : 'Error';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				// ------------------------------
				//    仮申請
				// ------------------------------

				unset($provisional_arr[$user_no]);
				$save_commutniy_arr['provisional'] = (count($provisional_arr) > 0) ? serialize($provisional_arr) : null;


				// ------------------------------
				//    承認する場合
				// ------------------------------

				if ($validated_on_off)
				{

					$member_arr = array_reverse($member_arr, true);

					if ($validated_type == 'user')
					{
						$member_arr[$user_no]['profile_no'] = null;
						$member_arr[$user_no]['administrator'] = false;
						$member_arr[$user_no]['moderator'] = false;
						$member_arr[$user_no]['access_date'] = '0000-00-00 00:00:00';
						$member_arr[$user_no]['mail_all'] = true;
					}
					else
					{
						$member_arr[$user_no]['profile_no'] = (int) $validated_no;
						$member_arr[$user_no]['administrator'] = false;
						$member_arr[$user_no]['moderator'] = false;
						$member_arr[$user_no]['access_date'] = '0000-00-00 00:00:00';
						$member_arr[$user_no]['mail_all'] = true;
					}

					$member_arr = array_reverse($member_arr, true);


					// ------------------------------
					//    メンバー
					// ------------------------------

					$save_commutniy_arr['member'] = serialize($member_arr);


					// ------------------------------
					//    参加メンバー数
					// ------------------------------

					$save_commutniy_arr['member_total'] = count($member_arr);


					// ------------------------------
					//    参加コミュニティ　User Data
					// ------------------------------

					if (empty($db_profile_arr) or isset($db_profile_arr['open_profile']))
					{
						if ( ! in_array($validated_community_no, $participation_community_arr)) array_push($participation_community_arr, $validated_community_no);
						if (count($participation_community_arr) > 0)
						{
							sort($participation_community_arr);
							$save_user_data_arr['participation_community'] = $original_func_common->return_db_array('php_db', $participation_community_arr);
						}
					}
					else
					{
						if ( ! in_array($validated_community_no, $participation_community_secret_arr)) array_push($participation_community_secret_arr, $validated_community_no);
						if (count($participation_community_secret_arr) > 0)
						{
							sort($participation_community_secret_arr);
							//echo '$participation_community_secret_arr';
					//var_dump($participation_community_secret_arr);
							$save_user_data_arr['participation_community_secret'] = $original_func_common->return_db_array('php_db', $participation_community_secret_arr);
						}
					}

				}


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				if (isset($save_user_data_arr))
				{
					$result = $model_co->update_community_and_user_data($validated_community_no, $save_commutniy_arr, $user_no, $save_user_data_arr);
				}
				else
				{
					$result = $model_co->update_community($validated_community_no, $save_commutniy_arr);
				}




				// --------------------------------------------------
				//    仮申請人数
				// --------------------------------------------------

				$arr['provisional_member_total'] = count($provisional_arr);



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$validated_type';
					var_dump($validated_type);

					echo '$validated_no';
					var_dump($validated_no);

					echo '$validated_on_off';
					var_dump($validated_on_off);


					echo '$db_community_arr';
					var_dump($db_community_arr);

					echo '$member_arr';
					var_dump($member_arr);

					echo '$provisional_arr';
					var_dump($provisional_arr);

					echo '$config_arr';
					var_dump($config_arr);

					echo '$user_no';
					var_dump($user_no);

					echo '$participation_community_arr';
					var_dump($participation_community_arr);

					echo '$participation_community_secret_arr';
					var_dump($participation_community_secret_arr);

					echo '$authority_arr';
					var_dump($authority_arr);


					echo '$member_arr';
					var_dump($member_arr);


					echo '$save_commutniy_arr';
					var_dump($save_commutniy_arr);

					if (isset($save_user_data_arr))
					{
						echo '$save_user_data_arr';
						var_dump($save_user_data_arr);
					}

				}
				//exit();


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				//$alert_message = ($validated_ban) ? 'コミュニティからBANしました。' : 'コミュニティから退会させました。';

				$arr['alert_color'] = 'success';
				//$arr['alert_title'] = 'OK';
				//$arr['alert_message'] = $alert_message;

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
	* BAN解除
	*
	* @return string HTMLコード
	*/
	public function post_lift_ban_member()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;

			$_POST['type'] = 'user';
			$_POST['no'] = 3;

			//$_POST['type'] = 'profile';
			//$_POST['no'] = 4;
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
			$val->add_callable('Original_Rule_Co');

			$val->add_field('community_no', 'Community No', 'required|check_community_no');

			if (Input::post('type') == 'user')
			{
				$val->add_field('no', 'User No', 'required|check_user_data');
			}
			else
			{
				$val->add_field('no', 'Profile No', 'required|check_profile');
			}

			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(user|profile)$/');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_type = $val->validated('type');
				$validated_no = $val->validated('no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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


				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$ban_arr = ($db_community_arr['ban']) ? unserialize($db_community_arr['ban']) : array();
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_member'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    User No 取得
				// --------------------------------------------------

				if ($validated_type == 'user')
				{
					$user_no = $validated_no;
					//$db_user_data_arr = $model_user->get_user_data($user_no, null);
				}
				else
				{
					$db_profile_arr = $model_user->get_profile($validated_no);
					$user_no = $db_profile_arr['author_user_no'];
					//$db_user_data_arr = $model_user->get_user_data($user_no, null);
				}


				// --------------------------------------------------
				//   BANされていないメンバー
				// --------------------------------------------------

				if ($validated_type == 'user' and (empty($ban_arr[$user_no]) or isset($ban_arr[$user_no]['profile_no'])))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? 'BANされていないメンバー User' : 'Error';
					throw new Exception('Error');
				}
				else if ($validated_type == 'profile' and empty($provisional_arr[$user_no]['profile_no']))
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'Error';
					$arr['alert_message'] = (Fuel::$env == 'development') ? 'BANされていないメンバー Profile' : 'Error';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				// ------------------------------
				//    仮申請
				// ------------------------------

				unset($ban_arr[$user_no]);
				$save_commutniy_arr['ban'] = (count($ban_arr) > 0) ? serialize($ban_arr) : null;


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->update_community($validated_community_no, $save_commutniy_arr);



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$validated_type';
					var_dump($validated_type);

					echo '$validated_no';
					var_dump($validated_no);


					echo '$db_community_arr';
					var_dump($db_community_arr);

					echo '$ban_arr';
					var_dump($ban_arr);

					echo '$config_arr';
					var_dump($config_arr);

					echo '$user_no';
					var_dump($user_no);

					echo '$authority_arr';
					var_dump($authority_arr);

					echo '$save_commutniy_arr';
					var_dump($save_commutniy_arr);

				}
				//exit();



				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				//$alert_message = ($validated_ban) ? 'コミュニティからBANしました。' : 'コミュニティから退会させました。';

				$arr['alert_color'] = 'success';
				//$arr['alert_title'] = 'OK';
				//$arr['alert_message'] = $alert_message;

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
	* 通知・メール一斉送信
	*
	* @return string HTMLコード
	*/
	public function post_send_mail_all()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
			$_POST['mail_no'] = 1;
			$_POST['subject'] = '件名 sendmail';
			$_POST['body'] = '本文 sendmail';
			//$_POST['send'] = true;
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
			$val->add_callable('Original_Rule_Co');

			$val->add_field('community_no', 'Community No', 'required|check_community_no');
			$val->add_field('mail_no', 'Mail No', 'required|valid_string[numeric]|numeric_between[1,10]');
			$val->add_field('subject', '件名', 'required|min_length[1]|max_length[50]');
			$val->add_field('body', '本文', 'required|min_length[1]|max_length[1000]');
			$val->add_field('host', 'Host', 'required');
			$val->add_field('user_agent', 'User Agent', 'required');

			if ($val->run(array('host' => $this->host, 'user_agent' => $this->user_agent)))
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_mail_no = $val->validated('mail_no');
				$validated_subject = $val->validated('subject');
				$validated_body = $val->validated('body');
				$validated_send = (Input::post('send')) ? 1: null;
				$validated_host = $val->validated('host');
				$validated_user_agent = $val->validated('user_agent');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$mail_arr = unserialize($db_community_arr['mail']);
				$member_arr = unserialize($db_community_arr['member']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_send_all_mail'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}



				// --------------------------------------------------
				//    保存のみ
				// --------------------------------------------------

				if (empty($validated_send))
				{

					// --------------------------------------------------
					//    保存用の配列作成
					// --------------------------------------------------

					$mail_arr['mail'][$validated_mail_no - 1]['subject'] = $validated_subject;
					$mail_arr['mail'][$validated_mail_no - 1]['body'] = $validated_body;
					$save_commutniy_arr['mail'] = serialize($mail_arr);


					// --------------------------------------------------
					//    データベース更新　保存
					// --------------------------------------------------

					$result = $model_co->update_community($validated_community_no, $save_commutniy_arr);

				}

				// --------------------------------------------------
				//    メール一斉送信
				// --------------------------------------------------

				else
				{

					// --------------------------------------------------
					//    送信用データ作成
					// --------------------------------------------------

					//$mail_arr['log'] = array(0 => array('user_no' => 1, 'mail_no' => 1, 'datetime' => '2014-09-24 00:00:00'));
					//$mail_arr['count'] = 1;


					// ------------------------------
					//    最新の送信ログが前日の場合、カウントをゼロに戻す
					// ------------------------------

					$mail_count = 0;

					if (isset($mail_arr['log'][0]['datetime']))
					{
						$datetime = new DateTime();
						$datetime_ymd_now = $datetime->format("Y-m-d");

						$datetime = new DateTime($mail_arr['log'][0]['datetime']);
						$datetime_ymd_log = $datetime->format("Y-m-d");

						$mail_count = ($datetime_ymd_now == $datetime_ymd_log) ? $mail_arr['count'] : 0;
					}

					$mail_limit = Config::get('limit_mail_all') - $mail_count;


					// --------------------------------------------------
					//   送信数限界
					// --------------------------------------------------

					if ($mail_limit < 1)
					{
						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = (Fuel::$env == 'development') ? '送信数限界' : 'Error';
						throw new Exception('Error');
					}


					// --------------------------------------------------
					//    重複チェック
					// --------------------------------------------------

					//$condition = serialize(array('community_no' => $db_community_arr['community_no'], 'mail_no' => $validated_mail_no));
					//$check_mail_all_duplication = $model_co->check_mail_all_duplication($datetime_now, $condition);
					/*
					$mail_arr['log'][0] = array('user_no' => 2, 'datetime' => '2014-09-25 00:00:00');
					$mail_arr['log'][1] = array('user_no' => 3, 'datetime' => '2014-09-24 00:00:00');
					$mail_arr['log'][2] = array('user_no' => 4, 'datetime' => '2014-09-23 00:00:00');
					$mail_arr['log'][3] = array('user_no' => 5, 'datetime' => '2014-09-22 00:00:00');
					$mail_arr['log'][4] = array('user_no' => 6, 'datetime' => '2014-09-21 00:00:00');
					*/


					// --------------------------------------------------
					//   前の送信から10分経つと再度送信を行えます。
					// --------------------------------------------------

					if (isset($mail_arr['log'][0]['datetime']))
					{

						$original_common_date = new Original\Common\Date();
						$datetime_past = $original_common_date->sql_format("-10minutes");

						if ($mail_arr['log'][0]['datetime'] > $datetime_past)
						{
							$arr['alert_color'] = 'danger';
							$arr['alert_title'] = 'エラー';
							$arr['alert_message'] = '通知を送信してからまだ時間が経っていません。前の送信から10分経つと再度送信を行えます。';
							throw new Exception('Error');
						}

					}


					// --------------------------------------------------
					//    保存用の配列作成
					// --------------------------------------------------

					// ------------------------------
					//    コミュニティ
					// ------------------------------

					$mail_arr['mail'][$validated_mail_no - 1]['subject'] = $validated_subject;
					$mail_arr['mail'][$validated_mail_no - 1]['body'] = $validated_body;
					$mail_arr['count'] = $mail_count + 1;

					array_unshift($mail_arr['log'], array('user_no' => $this->user_no, 'datetime' => $datetime_now));
					if (count($mail_arr['log']) > Config::get('limit_mail_all_send_user_log')) array_pop($mail_arr['log']);

					$save_commutniy_arr['mail'] = serialize($mail_arr);


					// ------------------------------
					//    Mail All
					// ------------------------------

					$add_body = '[ ' . $db_community_arr['name'] . ' ]' . "\n";

					$save_mail_all_arr['id'] = md5(uniqid(rand(), true));
					$save_mail_all_arr['regi_date'] = $datetime_now;
					$save_mail_all_arr['user_no'] = $this->user_no;
					$save_mail_all_arr['mail_condition'] = serialize(array('community_no' => (int) $db_community_arr['community_no'], 'mail_no' => $validated_mail_no));
					$save_mail_all_arr['subject'] = $validated_subject;
					$save_mail_all_arr['body'] = $add_body . $validated_body;
					$save_mail_all_arr['host'] = mb_substr($validated_host, 0, 255, 'UTF-8');
					$save_mail_all_arr['user_agent'] = mb_substr($validated_user_agent, 0, 255, 'UTF-8');


					// --------------------------------------------------
					//    データベース更新　保存
					// --------------------------------------------------

					$result = $model_co->update_community_and_mail_all($validated_community_no, $save_commutniy_arr, $save_mail_all_arr);


					// --------------------------------------------------
					//    送信可能ユーザー数計算　最大10万件
					// --------------------------------------------------

					$result_user_arr = $model_co->get_participation_community_user($db_community_arr['community_no'], 1, 100000);
					$user_arr = $result_user_arr[0];


					// --------------------------------------------------
					//   プロフィール取得
					// --------------------------------------------------

					$user_no_arr = array();
					$profile_no_arr = array();

					foreach ($user_arr as $key => $value)
					{
						($member_arr[$value['user_no']]['profile_no']) ? array_push($profile_no_arr, $member_arr[$value['user_no']]['profile_no']) : array_push($user_no_arr, $value['user_no']);
					}

					if (count($user_no_arr) > 0)
					{
						$user_data_arr = $model_user->get_user_data_list_in_personal_box($user_no_arr);
					}
					if (count($profile_no_arr) > 0)
					{
						$profile_arr = $model_user->get_profile_list_in_personal_box($profile_no_arr, false);
					}


					// --------------------------------------------------
					//   送信ユーザー数計算
					// --------------------------------------------------

					$send_user_count = 0;

					foreach ($user_arr as $key => $value)
					{
						if ($member_arr[$value['user_no']]['mail_all'])
						{

							if ($member_arr[$value['user_no']]['profile_no'])
							{
								// プロフィールがオンの場合 +1
								if (isset($profile_arr[$member_arr[$value['user_no']]['profile_no']]['on_off'])) $send_user_count++;
							}
							else
							{
								// プロフィールがオンの場合 +1
								if (isset($user_data_arr[$value['user_no']]['on_off'])) $send_user_count++;
							}
						}
					}
					/*
					echo '$user_arr';
					var_dump($user_arr);

					echo '$send_user_count';
					var_dump($send_user_count);
					*/



					// --------------------------------------------------
					//    ログインユーザー情報
					// --------------------------------------------------

					$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


					// --------------------------------------------------
					//   お知らせ保存
					// --------------------------------------------------

					//$db_user_data_arr = $model_user->get_user_data($this->user_no, null);
					$game_list_arr = $original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

					$save_notifications_arr = array(
						'regi_date' => $datetime_now,
						'target_user_no' => null,
						'community_no' => $db_community_arr['community_no'],
						'game_no' => $game_list_arr[0],
						'type1' => 'uc',
						'type2' => 'mail_all',
						'title' => $validated_subject,
						'name' => null,
						'comment' => $validated_body
					);

					if (isset($login_profile_data_arr['profile_no'])) $save_notifications_arr['profile_no'] = $login_profile_data_arr['profile_no'];

					$model_notifications->save_notifications($save_notifications_arr);

					// $original_func_common->save_notifications(array(
						// 'regi_date' => $datetime_now,
						// 'target_user_no' => null,
						// 'community_no' => $db_community_arr['community_no'],
						// 'game_no' => $game_list_arr[0],
						// 'type1' => 'uc',
						// 'type2' => 'mail_all',
						// 'title' => $validated_subject,
						// 'name' => null,
						// 'comment' => $validated_body
					// ));

				}






				if (isset($test))
				{
					//Debug::$js_toggle_open = true;
					/*
					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$validated_mail_no';
					var_dump($validated_mail_no);

					echo '$validated_subject';
					var_dump($validated_subject);

					echo '$validated_body';
					var_dump($validated_body);

					echo '$validated_send';
					var_dump($validated_send);
					*/
					/*
					if (isset($datetime_ymd_now, $datetime_ymd_log))
					{
						echo '$datetime_ymd_now';
						var_dump($datetime_ymd_now);

						echo '$datetime_ymd_log';
						var_dump($datetime_ymd_log);
					}



					echo '$db_community_arr';
					var_dump($db_community_arr);

					echo '$mail_arr';
					var_dump($mail_arr);

					//echo '$config_arr';
					//var_dump($config_arr);

					//echo '$user_no';
					//var_dump($user_no);

					//echo '$authority_arr';
					//var_dump($authority_arr);

					echo '$save_commutniy_arr';
					var_dump($save_commutniy_arr);

					if (isset($mail_limit))
					{
						echo '$mail_limit';
						var_dump($mail_limit);
					}

					if (isset($mail_limit))
					{
						echo '$save_mail_all_arr';
						var_dump($save_mail_all_arr);
					}
					*/

				}
				//exit();





				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$alert_message = ($validated_send) ? '通知を送信しました。　合計 ： ' . $send_user_count . ' 通' : '保存しました。';

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = $alert_message;

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
				//$arr['test'] = $error_message;
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
	* プロフィール選択フォーム　読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_select_profile_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$this->user_no = 'a';
			$_POST['page'] = '2';
			$_POST['community_no'] = '1';
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
			$val->add_callable('Original_Rule_Co');

			$val->add_field('user_no', 'User No', 'required|check_user_data');
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');


			if ($val->run(array('user_no' => $this->user_no)))
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_user_no = $val->validated('user_no');
				$validated_page = $val->validated('page');
				$validated_community_no = $val->validated('community_no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//    コミュニティ　データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);


				// --------------------------------------------------
				//   コード作成
				// --------------------------------------------------

				$code = $original_code_co->select_profile_form($db_community_arr, $validated_page);

				$arr['code'] = $code;


				if (isset($test))
				{
					echo "バリデーション後";
					var_dump($validated_user_no, $validated_page, $validated_community_no);

					echo $code;
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
				$arr['alert_message'] = 'エラーが起こりました。';

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
	* プロフィール選択保存
	*
	* @return string HTMLコード
	*/
	public function post_save_select_profile()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$this->user_no = 1;
			$_POST['community_no'] = 1;
			$_POST['select_profile'] = 9;
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
			$val->add_callable('Original_Rule_Co');

			$val->add_field('community_no', 'Community No', 'required|check_community_no');

			if (Input::post('select_profile') != 'user')
			{
				$val->add_field('select_profile', 'Select Profile', 'required|check_profile_author');
			}

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				if (Input::post('select_profile') != 'user') $validated_profile_no = $val->validated('select_profile');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

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



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);

				$db_user_data_arr = $model_user->get_user_data($this->user_no, null);
				$db_profile_arr = (isset($validated_profile_no)) ? $model_user->get_profile($validated_profile_no) : null;
				$participation_community_arr = (isset($db_user_data_arr['participation_community'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community']) : array();
				$participation_community_secret_arr = (isset($db_user_data_arr['participation_community_secret'])) ? $original_func_common->return_db_array('db_php', $db_user_data_arr['participation_community_secret']) : array();


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['member'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				if (isset($validated_profile_no))
				{
					$member_arr[$this->user_no]['profile_no'] = (int) $validated_profile_no;
				}
				else
				{
					$member_arr[$this->user_no]['profile_no'] = null;
				}

				$save_community_arr['member'] = serialize($member_arr);



				// ------------------------------
				//    参加コミュニティ　User Data
				// ------------------------------

				if (empty($db_profile_arr) or isset($db_profile_arr['open_profile']))
				{
					if ( ! in_array($validated_community_no, $participation_community_arr)) array_push($participation_community_arr, $validated_community_no);

					// 同じコミュニティが残らないように、逆側の配列から削除する
					//array_splice($participation_community_secret_arr, array_search($validated_community_no, $participation_community_secret_arr), 1);

					$array_search_key = array_search($validated_community_no, $participation_community_secret_arr);
					if ($array_search_key !== false) array_splice($participation_community_secret_arr, $array_search_key, 1);

				}
				else
				{
					if ( ! in_array($validated_community_no, $participation_community_secret_arr)) array_push($participation_community_secret_arr, $validated_community_no);

					// 同じコミュニティが残らないように、逆側の配列から削除する
					//array_splice($participation_community_arr, array_search($validated_community_no, $participation_community_arr), 1);

					$array_search_key = array_search($validated_community_no, $participation_community_arr);
					if ($array_search_key !== false) array_splice($participation_community_arr, $array_search_key, 1);

				}


				if (count($participation_community_arr) > 0)
				{
					sort($participation_community_arr);
					$save_user_data_arr['participation_community'] = $original_func_common->return_db_array('php_db', $participation_community_arr);
				}
				else
				{
					$save_user_data_arr['participation_community'] = null;
				}

				if (count($participation_community_secret_arr) > 0)
				{
					sort($participation_community_secret_arr);
					$save_user_data_arr['participation_community_secret'] = $original_func_common->return_db_array('php_db', $participation_community_secret_arr);
				}
				else
				{
					$save_user_data_arr['participation_community_secret'] = null;
				}




				if (isset($test))
				{
					//Debug::$js_toggle_open = true;


					if (isset($validated_profile_no))
					{
						echo '$validated_profile_no';
						var_dump($validated_profile_no);
					}

					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$db_community_arr';
					Debug::dump($db_community_arr);

					echo '$member_arr';
					var_dump($member_arr);

					echo '$authority_arr';
					var_dump($authority_arr);

					echo '$save_community_arr';
					var_dump($save_community_arr);

					echo '$save_user_data_arr';
					var_dump($save_user_data_arr);

				}
				//exit();


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->update_community_and_user_data($validated_community_no, $save_community_arr, $this->user_no, $save_user_data_arr);


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
				$arr['alert_message'] = '保存できませんでした。';

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
	* メール設定保存
	*
	* @return string HTMLコード
	*/
	public function post_save_config_mail()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
			//$_POST['mail_all'] = 1;
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

			$val->add_callable('Original_Rule_Co');

			$val->add_field('community_no', 'Community No', 'required|check_community_no');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_mail_all = (Input::post('mail_all')) ? 1: null;


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				//$original_common_date = new Original\Common\Date();
				//$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;



				// --------------------------------------------------
				//    データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$member_arr = unserialize($db_community_arr['member']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['member'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				if ($validated_mail_all)
				{
					$member_arr[$this->user_no]['mail_all'] = true;
				}
				else
				{
					$member_arr[$this->user_no]['mail_all'] = false;
				}

				$save_community_arr['member'] = serialize($member_arr);



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;


					echo '$validated_community_no';
					var_dump($validated_community_no);

					echo '$db_community_arr';
					Debug::dump($db_community_arr);

					echo '$member_arr';
					var_dump($member_arr);

					echo '$authority_arr';
					var_dump($authority_arr);

					echo '$save_community_arr';
					var_dump($save_community_arr);

				}
				//exit();


				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------

				$result = $model_co->update_community($validated_community_no, $save_community_arr);


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
				$arr['alert_message'] = '保存できませんでした。';

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
	* コミュニティ設定保存
	*
	* @return string HTMLコード
	*/
	public function post_save_config_community()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		// $test = true;

		if (isset($test))
		{
// 			$_POST['community_no'] = 1;
//
// 			$_POST['community_name'] = 'AAA';
// 			$_POST['community_description'] = 'BBB';
// 			$_POST['community_description_mini'] = 'ミニ';
// 			// $_POST['delete_image_ids'] = 'x1tvpa2w6e543s9n';
// 			// $_POST['thumbnail_delete'] = 1;
// 			$_POST['community_id'] = 'az1979';
// 			$_POST['game_list'] = '1,7';

			// $_POST['participation_type'] = 1;
			// $_POST['online_limit'] = 24;
			// $_POST['anonymity'] = 1;



			$_POST['community_no'] = 12;

			$_POST['community_name'] = 'New コミュニティ';
			$_POST['community_description'] = '新しく作ったコミュニティです。';
			$_POST['community_description_mini'] = 'ミニ';
			// $_POST['delete_image_ids'] = 'x1tvpa2w6e543s9n';
			// $_POST['thumbnail_delete'] = 1;
			$_POST['community_id'] = 'ddd';
			$_POST['game_list'] = '386';

			// $_POST['participation_type'] = 1;
			// $_POST['online_limit'] = 24;
			// $_POST['anonymity'] = 1;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_callable('Original_Rule_Common');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');

			if (Input::post('community_name'))
			{
				$val->add_field('community_name', 'コミュニティの名前', 'required|min_length[1]|max_length[50]');
				$val->add_field('community_description', 'コミュニティの説明文', 'required|min_length[1]|max_length[3000]');
				$val->add_field('community_description_mini', 'コミュニティの説明文（100文字以内）', 'required|min_length[1]|max_length[100]');
				$val->add_field('community_id', 'コミュニティID', 'required|min_length[3]|max_length[50]|valid_string[alpha,lowercase,numeric,dashes]|community_id_duplication');
				$val->add_field('game_list', 'Game List', 'required|check_game_existence');
				$val->add_field('delete_image_ids', 'Delete Image IDs', 'valid_string[alpha,lowercase,numeric,dashes,commas]');

				$config_type = 'basis';
			}
			else if (Input::post('participation_type'))
			{
				$val->add_field('participation_type', 'Participation Type', 'required|valid_string[numeric]|numeric_between[1,2]');
				$val->add_field('online_limit', 'Online Limit', 'required|valid_string[numeric]|numeric_between[1,168]');

				$config_type = 'community';
			}



			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');

				if ($config_type == 'basis')
				{

					$validated_community_name = $val->validated('community_name');
					$validated_community_description = $val->validated('community_description');
					$validated_community_description_mini = $val->validated('community_description_mini');
					$validated_thumbnail_delete = (Input::post('thumbnail_delete')) ? 1: null;
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

					$validated_delete_image_ids = $val->validated('delete_image_ids') ?? null;

				}
				else if ($config_type == 'community')
				{
					$validated_participation_type = (int)$val->validated('participation_type');
					$validated_open = (Input::post('open')) ? 1: null;
					$validated_online_limit = (int)$val->validated('online_limit');
					$validated_anonymity = (Input::post('anonymity')) ? true: false;
				}


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;

				$original_code_co = new Original\Code\Co();
				$original_code_co->app_mode = $this->app_mode;
				$original_code_co->agent_type = $this->agent_type;
				$original_code_co->user_no = $this->user_no;
				$original_code_co->language = $this->language;
				$original_code_co->uri_base = $this->uri_base;
				$original_code_co->uri_current = $this->uri_current;

				$original_func_common = new Original\Func\Common();
				$original_func_common->app_mode = $this->app_mode;
				$original_func_common->agent_type = $this->agent_type;
				$original_func_common->user_no = $this->user_no;
				$original_func_common->language = $this->language;
				$original_func_common->uri_base = $this->uri_base;
				$original_func_common->uri_current = $this->uri_current;

				$original_func_image = new Original\Func\Image();


				// --------------------------------------------------
				//   データベースに保存する情報取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$config_arr = unserialize($db_community_arr['config']);
				$db_thumbnail = ($db_community_arr['thumbnail']) ? 1 : null;


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);
// \Debug::dump(USER_NO, $db_community_arr, $config_arr, $db_thumbnail, $authority_arr);

				// --------------------------------------------------
				//   権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['operate_config_community'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();

				$save_arr['renewal_date'] = $datetime_now;
				$save_arr['sort_date'] = $datetime_now;

				if ($config_type == 'basis')
				{
					$save_arr['name'] = $validated_community_name;
					$save_arr['description'] = $validated_community_description;
					$save_arr['description_mini'] = $validated_community_description_mini;
					$save_arr['game_list'] = $validated_game_list;
					$save_arr['tag'] = null;
					$save_arr['community_id'] = $validated_community_id;
					$save_arr['thumbnail'] = $db_thumbnail;
				}
				else if ($config_type == 'community')
				{
					$save_config_arr = array();

					// 並び替え
					$save_config_arr['participation_type'] = $config_arr['participation_type'];
					$save_config_arr['online_limit'] = $config_arr['online_limit'];
					$save_config_arr['anonymity'] = $config_arr['anonymity'];
					$save_config_arr['read_announcement'] = $config_arr['read_announcement'];
					$save_config_arr['read_bbs'] = $config_arr['read_bbs'];
					$save_config_arr['read_member'] = $config_arr['read_member'];
					$save_config_arr['read_additional_info'] = $config_arr['read_additional_info'];
					$save_config_arr['operate_announcement'] = $config_arr['operate_announcement'];
					$save_config_arr['operate_bbs_thread'] = $config_arr['operate_bbs_thread'];
					$save_config_arr['operate_bbs_comment'] = $config_arr['operate_bbs_comment'];
					$save_config_arr['operate_bbs_delete'] = $config_arr['operate_bbs_delete'];
					$save_config_arr['operate_member'] = $config_arr['operate_member'];
					$save_config_arr['operate_send_all_mail'] = $config_arr['operate_send_all_mail'];
					$save_config_arr['operate_config_community'] = $config_arr['operate_config_community'];

					$save_config_arr['participation_type'] = $validated_participation_type;
					$save_config_arr['online_limit'] = $validated_online_limit;
					$save_config_arr['anonymity'] = $validated_anonymity;

					$save_arr['open'] = $validated_open;
					$save_arr['config'] = serialize($save_config_arr);
				}

				// $original_size_arr = getimagesize($_FILES['thumbnail']['tmp_name']);
				// \Debug::dump($_FILES, $original_size_arr);
				// exit();


				// --------------------------------------------------
				//    画像　削除・保存
				// --------------------------------------------------

				if ($config_type == 'basis')
				{

					// --------------------------------------------------
					//   パス設定
					// --------------------------------------------------

					$path = DOCROOT . 'assets/img/community/' . $validated_community_no . '/';


					// --------------------------------------------------
					//   画像削除
					// --------------------------------------------------

					if ($validated_delete_image_ids)
					{
						$temp_arr = array(
							'type' => 'hero_community',
							'community_no' => $validated_community_no,
							'delete_image_ids' => $validated_delete_image_ids
						);
						//\Debug::dump($validated_delete_image_ids);
						$result_arr = $original_func_image->delete_images($temp_arr);

					}
					//\Debug::dump($_FILES);
					//$arr['test'] = 'aaa';
					//return $this->response($arr);

					// --------------------------------------------------
					//   画像保存
					// --------------------------------------------------

					$temp_arr = [
						'type' => 'hero_community',
						'community_no' => $validated_community_no,
						'quality' => 50
					];

					$result_upload_images_arr = $original_func_image->save_images($temp_arr);
					// $arr['test'] = $result_upload_images_arr;
					// $arr['test1'] = $_FILES;


					// --------------------------------------------------
					//   サムネイル削除
					// --------------------------------------------------

					if ($validated_thumbnail_delete)
					{
						$temp_arr = ['path' => $path];
						$original_func_image->delete_thumbnail($temp_arr);
						$save_arr['thumbnail'] = null;
					}


					// --------------------------------------------------
					//   サムネイル保存
					// --------------------------------------------------

					$temp_arr = ['path' => $path];
					$result_upload_thumbnail_arr = $original_func_image->save_thumbnail($temp_arr);
					if ($result_upload_thumbnail_arr['done']) $save_arr['thumbnail'] = 1;
					// \Debug::dump($result_upload_thumbnail_arr);




					if ($result_upload_images_arr['error'] or $result_upload_thumbnail_arr['error'])
					{
						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = 'エラー';
						$arr['alert_message'] = 'アップロードされた画像に問題があります。';
						throw new Exception('Error');
					}

				}


				// if (isset($test))
				// {
				//
				// 	\Debug::$js_toggle_open = true;
				//
				// 	echo '$validated_community_no';
				// 	\Debug::dump($validated_community_no);
				//
				// 	echo '$validated_delete_image_ids';
				// 	\Debug::dump($validated_delete_image_ids);
				//
				// 	echo '$save_arr';
				// 	\Debug::dump($save_arr);
				//
				// }


				// --------------------------------------------------
				//   データベースに保存
				// --------------------------------------------------

				$result = $model_co->update_community($validated_community_no, $save_arr);


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

				if (isset($test)) echo $error_message;
				//$arr['test'] = 'エラー ' . $error_message;
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
	* 閲覧権限保存
	*
	* @return string HTMLコード
	*/
	public function post_save_config_read()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
			$_POST['read_announcement_1'] = 'true';
			$_POST['read_announcement_2'] = null;
			$_POST['read_announcement_3'] = 'true';
			$_POST['read_bbs_1'] = 'true';
			$_POST['read_bbs_2'] = null;
			$_POST['read_bbs_3'] = 'true';
			$_POST['read_member_1'] = 'true';
			$_POST['read_member_2'] = null;
			$_POST['read_member_3'] = 'true';
			$_POST['read_additional_info_1'] = 'true';
			$_POST['read_additional_info_2'] = null;
			$_POST['read_additional_info_3'] = 'true';
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_read_announcement_1 = (Input::post('read_announcement_1')) ? 1: null;
				$validated_read_announcement_2 = (Input::post('read_announcement_2')) ? 2: null;
				$validated_read_announcement_3 = (Input::post('read_announcement_3')) ? 3: null;
				$validated_read_bbs_1 = (Input::post('read_bbs_1')) ? 1: null;
				$validated_read_bbs_2 = (Input::post('read_bbs_2')) ? 2: null;
				$validated_read_bbs_3 = (Input::post('read_bbs_3')) ? 3: null;
				$validated_read_member_1 = (Input::post('read_member_1')) ? 1: null;
				$validated_read_member_2 = (Input::post('read_member_2')) ? 2: null;
				$validated_read_member_3 = (Input::post('read_member_3')) ? 3: null;
				$validated_read_additional_info_1 = (Input::post('read_additional_info_1')) ? 1: null;
				$validated_read_additional_info_2 = (Input::post('read_additional_info_2')) ? 2: null;
				$validated_read_additional_info_3 = (Input::post('read_additional_info_3')) ? 3: null;

				//var_dump($validated_community_no, $validated_read_announcement_1, $validated_read_announcement_2, $validated_read_announcement_3);



				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//   データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   編集権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['administrator'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '編集権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();

				$save_arr['renewal_date'] = $datetime_now;
				$save_arr['sort_date'] = $datetime_now;


				$read_announcement_arr = array();
				if ($validated_read_announcement_1) array_push($read_announcement_arr, $validated_read_announcement_1);
				if ($validated_read_announcement_2) array_push($read_announcement_arr, $validated_read_announcement_2);
				if ($validated_read_announcement_3) array_push($read_announcement_arr, $validated_read_announcement_3);

				$read_bbs_arr = array();
				if ($validated_read_bbs_1) array_push($read_bbs_arr, $validated_read_bbs_1);
				if ($validated_read_bbs_2) array_push($read_bbs_arr, $validated_read_bbs_2);
				if ($validated_read_bbs_3) array_push($read_bbs_arr, $validated_read_bbs_3);

				$read_member_arr = array();
				if ($validated_read_member_1) array_push($read_member_arr, $validated_read_member_1);
				if ($validated_read_member_2) array_push($read_member_arr, $validated_read_member_2);
				if ($validated_read_member_3) array_push($read_member_arr, $validated_read_member_3);

				$read_additional_info_arr = array();
				if ($validated_read_additional_info_1) array_push($read_additional_info_arr, $validated_read_additional_info_1);
				if ($validated_read_additional_info_2) array_push($read_additional_info_arr, $validated_read_additional_info_2);
				if ($validated_read_additional_info_3) array_push($read_additional_info_arr, $validated_read_additional_info_3);

				// 一度削除すると順番が正確なものになる
				//unset($config_arr['read_announcement'], $config_arr['read_bbs'], $config_arr['read_member'], $config_arr['read_additional_info']);

				$config_arr['read_announcement'] = $read_announcement_arr;
				$config_arr['read_bbs'] = $read_bbs_arr;
				$config_arr['read_member'] = $read_member_arr;
				$config_arr['read_additional_info'] = $read_additional_info_arr;

				$save_arr['config'] = serialize($config_arr);


				// --------------------------------------------------
				//   データベースに保存
				// --------------------------------------------------

				$result = $model_co->update_community($validated_community_no, $save_arr);
				//var_dump($result);


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
	* 操作権限保存
	*
	* @return string HTMLコード
	*/
	public function post_save_config_operate()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
			$_POST['read_announcement_1'] = 'true';
			$_POST['read_announcement_2'] = null;
			$_POST['read_announcement_3'] = 'true';
			$_POST['read_bbs_1'] = 'true';
			$_POST['read_bbs_2'] = null;
			$_POST['read_bbs_3'] = 'true';
			$_POST['read_member_1'] = 'true';
			$_POST['read_member_2'] = null;
			$_POST['read_member_3'] = 'true';
			$_POST['read_additional_info_1'] = 'true';
			$_POST['read_additional_info_2'] = null;
			$_POST['read_additional_info_3'] = 'true';
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');
				$validated_operate_announcement_3 = (Input::post('operate_announcement_3')) ? 3: null;
				$validated_operate_bbs_comment_1 = (Input::post('operate_bbs_comment_1')) ? 1: null;
				$validated_operate_bbs_comment_2 = (Input::post('operate_bbs_comment_2')) ? 2: null;
				$validated_operate_bbs_comment_3 = (Input::post('operate_bbs_comment_3')) ? 3: null;
				$validated_operate_bbs_delete_3 = (Input::post('operate_bbs_delete_3')) ? 3: null;
				$validated_operate_bbs_thread_1 = (Input::post('operate_bbs_thread_1')) ? 1: null;
				$validated_operate_bbs_thread_2 = (Input::post('operate_bbs_thread_2')) ? 2: null;
				$validated_operate_bbs_thread_3 = (Input::post('operate_bbs_thread_3')) ? 3: null;
				$validated_operate_member_3 = (Input::post('operate_member_3')) ? 3: null;
				$validated_operate_send_all_mail_3 = (Input::post('operate_send_all_mail_3')) ? 3: null;
				$validated_operate_config_community_3 = (Input::post('operate_config_community_3')) ? 3: null;

				//var_dump($validated_community_no, $validated_read_announcement_1, $validated_read_announcement_2, $validated_read_announcement_3);



				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//   データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);
				$config_arr = unserialize($db_community_arr['config']);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   編集権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['administrator'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '編集権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();

				$save_arr['renewal_date'] = $datetime_now;
				$save_arr['sort_date'] = $datetime_now;


				$operate_announcement_arr = array();
				if ($validated_operate_announcement_3) array_push($operate_announcement_arr, $validated_operate_announcement_3);

				$operate_bbs_comment_arr = array();
				if ($validated_operate_bbs_comment_1) array_push($operate_bbs_comment_arr, $validated_operate_bbs_comment_1);
				if ($validated_operate_bbs_comment_2) array_push($operate_bbs_comment_arr, $validated_operate_bbs_comment_2);
				if ($validated_operate_bbs_comment_3) array_push($operate_bbs_comment_arr, $validated_operate_bbs_comment_3);

				$operate_bbs_delete_arr = array();
				if ($validated_operate_bbs_delete_3) array_push($operate_bbs_delete_arr, $validated_operate_bbs_delete_3);

				$operate_bbs_thread_arr = array();
				if ($validated_operate_bbs_thread_1) array_push($operate_bbs_thread_arr, $validated_operate_bbs_thread_1);
				if ($validated_operate_bbs_thread_2) array_push($operate_bbs_thread_arr, $validated_operate_bbs_thread_2);
				if ($validated_operate_bbs_thread_3) array_push($operate_bbs_thread_arr, $validated_operate_bbs_thread_3);

				$operate_member_arr = array();
				if ($validated_operate_member_3) array_push($operate_member_arr, $validated_operate_member_3);

				$operate_send_all_mail_arr = array();
				if ($validated_operate_send_all_mail_3) array_push($operate_send_all_mail_arr, $validated_operate_send_all_mail_3);

				$operate_config_community_arr = array();
				if ($validated_operate_config_community_3) array_push($operate_config_community_arr, $validated_operate_config_community_3);

				// 一度削除すると順番が正確なものになる
				//unset($config_arr['operate_announcement'], $config_arr['operate_bbs_thread'], $config_arr['operate_bbs_comment'], $config_arr['operate_bbs_delete'], $config_arr['operate_member'], $config_arr['operate_send_all_mail'], $config_arr['operate_config_community']);

				$config_arr['operate_announcement'] = $operate_announcement_arr;
				$config_arr['operate_bbs_thread'] = $operate_bbs_thread_arr;
				$config_arr['operate_bbs_comment'] = $operate_bbs_comment_arr;
				$config_arr['operate_bbs_delete'] = $operate_bbs_delete_arr;
				$config_arr['operate_member'] = $operate_member_arr;
				$config_arr['operate_send_all_mail'] = $operate_send_all_mail_arr;
				$config_arr['operate_config_community'] = $operate_config_community_arr;

				$save_arr['config'] = serialize($config_arr);


				// --------------------------------------------------
				//   データベースに保存
				// --------------------------------------------------

				$result = $model_co->update_community($validated_community_no, $save_arr);


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
	* ユーザーコミュニティ削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_user_community()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 2;
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

			$val->add_callable('Original_Rule_Co');
			$val->add_field('community_no', 'Community No', 'required|check_community_no');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_community_no = $val->validated('community_no');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();

				// インスタンス作成
				$model_co = new Model_Co();
				$model_co->agent_type = $this->agent_type;
				$model_co->user_no = $this->user_no;
				$model_co->language = $this->language;
				$model_co->uri_base = $this->uri_base;
				$model_co->uri_current = $this->uri_current;

				$original_func_co = new Original\Func\Co();
				$original_func_co->app_mode = $this->app_mode;
				$original_func_co->agent_type = $this->agent_type;
				$original_func_co->user_no = $this->user_no;
				$original_func_co->language = $this->language;
				$original_func_co->uri_base = $this->uri_base;
				$original_func_co->uri_current = $this->uri_current;


				// --------------------------------------------------
				//   データ取得
				// --------------------------------------------------

				$db_community_arr = $model_co->get_community($validated_community_no, null);


				// --------------------------------------------------
				//   権限
				// --------------------------------------------------

				$authority_arr = $original_func_co->authority($db_community_arr);


				// --------------------------------------------------
				//   編集権限がありません。
				// --------------------------------------------------

				if ( ! $authority_arr['administrator'])
				{
					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = 'エラー';
					$arr['alert_message'] = '編集権限がありません。';
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//    保存用の配列作成
				// --------------------------------------------------

				$save_arr = array();

				$save_arr['on_off'] = null;


				// --------------------------------------------------
				//   データベースに保存
				// --------------------------------------------------

				$result = $model_co->update_community($validated_community_no, $save_arr);



				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_community_no';
					var_dump($validated_community_no);

				}
				//exit();


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
				$arr['alert_message'] = '削除できませんでした。';

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
