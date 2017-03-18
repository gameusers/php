<?php

namespace Original\Code;

class Co
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

	// アプリモード
	public $app_mode = null;



	/**
	* 告知
	*
	* @param array $arr
	* @return array
	*/
	public function announcement($arr)
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['first_load'] = 1;
			$_POST['community_no'] = 1;
			$_POST['page'] = 1;
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');
		$val->add_callable('Original_Rule_Co');

		$val->add_field('community_no', 'Community No', 'required|check_community_no');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			//$validated_first_load = (isset($arr['first_load'])) ? true : false;
			$validated_community_no = $val->validated('community_no');
			$validated_page = $val->validated('page');
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}

			throw new \Exception($error_message);
		}


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		// 日時
		$original_common_date = new \Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();

		// インスタンス作成
		$model_user = new \Model_User();
		$model_user->agent_type = USER_AGENT;
		$model_user->user_no = USER_NO;
		$model_user->language = LANGUAGE;
		$model_user->uri_base = URI_BASE;
		$model_user->uri_current = URI_CURRENT;

		$model_co = new \Model_Co();
		$model_co->agent_type = USER_AGENT;
		$model_co->user_no = USER_NO;
		$model_co->language = LANGUAGE;
		$model_co->uri_base = URI_BASE;
		$model_co->uri_current = URI_CURRENT;

		$original_func_co = new \Original\Func\Co();
		$original_func_co->agent_type = USER_AGENT;
		$original_func_co->user_no = USER_NO;
		$original_func_co->language = LANGUAGE;
		$original_func_co->uri_base = URI_BASE;
		$original_func_co->uri_current = URI_CURRENT;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_community_arr = $model_co->get_community($validated_community_no, null);
		$config_arr = unserialize($db_community_arr['config']);
		$authority_arr = $original_func_co->authority($db_community_arr);



		// --------------------------------------------------
		//   告知作成
		// --------------------------------------------------

		$code = null;

		if ($authority_arr['read_announcement'] or \Auth::member(100))
		{

			$db_announcement_arr = $model_co->get_announcement_list($validated_community_no, $validated_page);
			$db_announcement_total = $model_co->get_announcement_list_total($validated_community_no);


			if (isset($db_announcement_arr))
			{

				// ------------------------------
				//    画像・動画のUnserialize
				// ------------------------------

				if (isset($db_announcement_arr['image'])) $db_announcement_arr['image'] = unserialize($db_announcement_arr['image'])['image_1'];
				if (isset($db_announcement_arr['movie'])) $db_announcement_arr['movie'] = unserialize($db_announcement_arr['movie']);


				// ------------------------------
				//    送信者のプロフィール取得
				// ------------------------------

				if ($db_announcement_arr['profile_no'])
				{
					$announcement_user_no = null;
					$announcement_profile_no = $db_announcement_arr['profile_no'];
					$profile_arr = $model_user->get_profile($announcement_profile_no);
				}
				else
				{
					$announcement_user_no = $db_announcement_arr['user_no'];
					$announcement_profile_no = null;
					$profile_arr = $model_user->get_user_data_personal_box($announcement_user_no, null);
				}



				// ------------------------------
				//    コード作成
				// ------------------------------

				$view = \View::forge('parts/announcement_view');

				// 権限
				$view->set_safe('authority_arr', $authority_arr);

				// アナウンスメント
				$view->set('community_no', $validated_community_no);
				$view->set('announcement_no', $db_announcement_arr['announcement_no']);
				$view->set('announcement_title', $db_announcement_arr['title']);
				$view->set('announcement_comment', $db_announcement_arr['comment']);
				$view->set('announcement_image_arr', $db_announcement_arr['image']);
				$view->set('announcement_movie_arr', $db_announcement_arr['movie']);
				$view->set('announcement_regi_date', $db_announcement_arr['regi_date']);
				$view->set('announcement_renewal_date', $db_announcement_arr['renewal_date']);

				// パーソナルボックス
				$view->set('user_no', $announcement_user_no);
				$view->set('datetime_now', $datetime_now);
				$view->set('profile_arr', $profile_arr);
				$view->set('online_limit', $config_arr['online_limit']);

				// ページャー
				$view->set('page', $validated_page);
				$view->set('total', $db_announcement_total);
				$view->set('limit', 1);
				$view->set('times', PAGINATION_TIMES);
				$view->set('function_name', 'GAMEUSERS.uc.readAnnouncement');
				$view->set('argument_arr', array($validated_community_no, 1, 0));
				$code = $view->render();

			}

		}

		if ( ! $code) $code = '<article id="announcement_box"></article>';


		return ['code' => $code];

	}


	/**
	* BBS スレッド一覧
	*
	* @param array $db_community_arr コミュニティ情報
	* @param array $authority_arr 権限
	* @param array $page ページ
	* @return string HTMLコード
	*/
	public function bbs_thread_list($db_community_arr, $authority_arr, $page)
	{

		// --------------------------------------------------
		//   権限チェック
		// --------------------------------------------------

		if ( ! $authority_arr['read_bbs']) return null;


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		// インスタンス作成
		$model_co = new \Model_Co();
		$model_co->agent_type = $this->agent_type;
		$model_co->user_no = $this->user_no;
		$model_co->language = $this->language;
		$model_co->uri_base = $this->uri_base;
		$model_co->uri_current = $this->uri_current;


		// ------------------------------
		//    スレッド一覧取得
		// ------------------------------

		$limit_bbs_thread_list = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_thread_list') : \Config::get('limit_bbs_thread_list_sp');
		$db_bbs_thread_arr = $model_co->get_bbs_thread_list_title_only($db_community_arr['community_no'], $page, $limit_bbs_thread_list);
		$db_bbs_thread_total = $model_co->get_bbs_thread_list_total($db_community_arr['community_no']);

		if ($db_bbs_thread_total > 0)
		{

			$view = \View::forge('parts/bbs_thread_list_view');

			$view->set('community_no', $db_community_arr['community_no']);
			$view->set('thread_arr', $db_bbs_thread_arr);
			$view->set('thread_total', $db_bbs_thread_total);

			// ページャー
			// ページャーの数字表示回数取得
			$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');
			$view->set('pagination_page', $page);
			$view->set('pagination_total', $db_bbs_thread_total);
			$view->set('pagination_limit', $limit_bbs_thread_list);
			$view->set('pagination_times', $pagination_times);
			$view->set('pagination_function_name', 'readBbsThreadList');
			$view->set('pagination_argument_arr', array($db_community_arr['community_no']));

			$code = $view->render();

		}
		else
		{
			$code = null;
		}
		//var_dump($db_bbs_thread_arr, $db_bbs_thread_total);

		//echo $view->render();

		return $code;

	}




	/**
	* BBS
	*
	* @param array $db_community_arr コミュニティ情報
	* @param array $authority_arr 権限
	* @param array $login_profile_data_arr ログインユーザーのプロフィール
	* @param array $page ページ
	* @return string HTMLコード
	*/
	public function bbs_appoint_thread_no($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, $bbs_thread_no)
	{

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

		$model_co = new \Model_Co();
		$model_co->agent_type = $this->agent_type;
		$model_co->user_no = $this->user_no;
		$model_co->language = $this->language;
		$model_co->uri_base = $this->uri_base;
		$model_co->uri_current = $this->uri_current;

		$config_arr = unserialize($db_community_arr['config']);


		// --------------------------------------------------
		//    BBS
		// --------------------------------------------------

		$view_content_bbs = null;

		if ($authority_arr['read_bbs'] or \Auth::member(100))
		{

			// ------------------------------
			//    スレッド一覧取得
			// ------------------------------

			$limit_bbs_thread = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_thread') : \Config::get('limit_bbs_thread_sp');
			$db_bbs_thread_arr = $model_co->get_bbs_thread_appoint_thread_no($bbs_thread_no);
			$db_bbs_thread_total = 1;

			$bbs_user_no_arr = array();
			$bbs_profile_no_arr = array();

			//var_dump($db_bbs_thread_arr);
			//exit();

			if (isset($db_bbs_thread_arr))
			{



				// ------------------------------
				//    コメント＆返信一覧取得
				// ------------------------------

				$limit_bbs_comment = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_comment') : \Config::get('limit_bbs_comment_sp');
				$db_bbs_comment_arr = null;

				$limit_bbs_reply = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_reply') : \Config::get('limit_bbs_reply_sp');
				$db_bbs_reply_arr = null;


				foreach ($db_bbs_thread_arr as $key => &$value)
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


					if ($value['comment_total'] > 0)
					{
						$db_bbs_comment_arr[$value['bbs_thread_no']] = $model_co->get_bbs_comment_list($value['bbs_thread_no'], 1, $limit_bbs_comment);


						foreach ($db_bbs_comment_arr[$value['bbs_thread_no']] as $key_comment => &$value_comment)
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


							if ($value_comment['reply_total'] > 0)
							{
								$db_bbs_reply_arr[$value_comment['bbs_comment_no']] = $model_co->get_bbs_reply_list($value_comment['bbs_comment_no'], 1, $limit_bbs_reply);


								foreach ($db_bbs_reply_arr[$value_comment['bbs_comment_no']] as $key_reply => &$value_reply) {

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

						//$db_bbs_comment_arr[$value['bbs_thread_no']] = $model_main->get_bbs_reply_list

					}
				}

				unset($value, $value_comment, $value_reply);
				/*
				echo "db_bbs_thread_arr";
				var_dump($db_bbs_thread_arr);

				echo "db_bbs_comment_arr";
				var_dump($db_bbs_comment_arr);

				echo "db_bbs_reply_arr";
				var_dump($db_bbs_reply_arr);
				*/

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
				/*
				echo "bbs_user_no_arr";
				var_dump($bbs_user_no_arr);

				echo "bbs_profile_no_arr";
				var_dump($bbs_profile_no_arr);


				echo "bbs_user_data_arr";
				var_dump($bbs_user_data_arr);

				echo "bbs_profile_arr";
				var_dump($bbs_profile_arr);
				*/

				$view_content_bbs = \View::forge('parts/bbs_view');

				$view_content_bbs->set_safe('app_mode', $this->app_mode);
				$view_content_bbs->set_safe('agent_type', $this->agent_type);
				$view_content_bbs->set_safe('login_user_no', $this->user_no);
				$view_content_bbs->set_safe('host', $this->host);
				$view_content_bbs->set_safe('user_agent', $this->user_agent);
				$view_content_bbs->set('uri_base', $this->uri_base);

				$view_content_bbs->set('datetime_now', $datetime_now);
				//var_dump($db_community_arr);
				$view_content_bbs->set('community_no', $db_community_arr['community_no']);
				$view_content_bbs->set_safe('appoint', true);

				$view_content_bbs->set('thread_arr', $db_bbs_thread_arr);
				$view_content_bbs->set('comment_arr', $db_bbs_comment_arr);
				$view_content_bbs->set('reply_arr', $db_bbs_reply_arr);
				$view_content_bbs->set('user_data_arr', $bbs_user_data_arr);
				$view_content_bbs->set('profile_arr', $bbs_profile_arr);
				$view_content_bbs->set('online_limit', $config_arr['online_limit']);
				$view_content_bbs->set_safe('anonymity', $config_arr['anonymity']);
				$view_content_bbs->set('login_profile_data_arr', $login_profile_data_arr);
				$view_content_bbs->set_safe('authority_arr', $authority_arr);

				// ページャー
				// ページャーの数字表示回数取得
				$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');
				$view_content_bbs->set('pagination_page', 1);
				$view_content_bbs->set('pagination_total', $db_bbs_thread_total);
				$view_content_bbs->set('pagination_limit', $limit_bbs_thread);
				$view_content_bbs->set('pagination_times', $pagination_times);
				$view_content_bbs->set('pagination_argument_arr', array($db_community_arr['community_no']));

				$view_content_bbs->set_safe('pagination_comment_limit', $limit_bbs_comment);
				$view_content_bbs->set_safe('pagination_reply_limit', $limit_bbs_reply);

			}

		}


		return $view_content_bbs;

	}




	/**
	* BBS
	*
	* @param array $db_community_arr コミュニティ情報
	* @param array $authority_arr 権限
	* @param array $login_profile_data_arr ログインユーザーのプロフィール
	* @param array $page ページ
	* @return string HTMLコード
	*/
	public function bbs($db_community_arr, $authority_arr, $login_profile_data_arr, $datetime_now, $page)
	{

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

		$model_co = new \Model_Co();
		$model_co->agent_type = $this->agent_type;
		$model_co->user_no = $this->user_no;
		$model_co->language = $this->language;
		$model_co->uri_base = $this->uri_base;
		$model_co->uri_current = $this->uri_current;

		$config_arr = unserialize($db_community_arr['config']);


		// --------------------------------------------------
		//    BBS
		// --------------------------------------------------

		$view_content_bbs = null;

		if ($authority_arr['read_bbs'] or \Auth::member(100))
		{

			// ------------------------------
			//    スレッド一覧取得
			// ------------------------------

			$limit_bbs_thread = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_thread') : \Config::get('limit_bbs_thread_sp');
			$db_bbs_thread_arr = $model_co->get_bbs_thread_list($db_community_arr['community_no'], $page, $limit_bbs_thread);
			$db_bbs_thread_total = $model_co->get_bbs_thread_list_total($db_community_arr['community_no']);

			$bbs_user_no_arr = array();
			$bbs_profile_no_arr = array();

			//var_dump($db_bbs_thread_arr);
			//exit();

			if (isset($db_bbs_thread_arr))
			{



				// ------------------------------
				//    コメント＆返信一覧取得
				// ------------------------------

				$limit_bbs_comment = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_comment') : \Config::get('limit_bbs_comment_sp');
				$db_bbs_comment_arr = null;

				$limit_bbs_reply = ($this->agent_type != 'smartphone') ? \Config::get('limit_bbs_reply') : \Config::get('limit_bbs_reply_sp');
				$db_bbs_reply_arr = null;


				foreach ($db_bbs_thread_arr as $key => &$value)
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


					if ($value['comment_total'] > 0)
					{
						$db_bbs_comment_arr[$value['bbs_thread_no']] = $model_co->get_bbs_comment_list($value['bbs_thread_no'], 1, $limit_bbs_comment);


						foreach ($db_bbs_comment_arr[$value['bbs_thread_no']] as $key_comment => &$value_comment)
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


							if ($value_comment['reply_total'] > 0)
							{
								$db_bbs_reply_arr[$value_comment['bbs_comment_no']] = $model_co->get_bbs_reply_list($value_comment['bbs_comment_no'], 1, $limit_bbs_reply);


								foreach ($db_bbs_reply_arr[$value_comment['bbs_comment_no']] as $key_reply => &$value_reply) {

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

						//$db_bbs_comment_arr[$value['bbs_thread_no']] = $model_main->get_bbs_reply_list

					}
				}

				unset($value, $value_comment, $value_reply);
				/*
				echo "db_bbs_thread_arr";
				var_dump($db_bbs_thread_arr);

				echo "db_bbs_comment_arr";
				var_dump($db_bbs_comment_arr);

				echo "db_bbs_reply_arr";
				var_dump($db_bbs_reply_arr);
				*/

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
				/*
				echo "bbs_user_no_arr";
				var_dump($bbs_user_no_arr);

				echo "bbs_profile_no_arr";
				var_dump($bbs_profile_no_arr);


				echo "bbs_user_data_arr";
				var_dump($bbs_user_data_arr);

				echo "bbs_profile_arr";
				var_dump($bbs_profile_arr);
				*/

				$view_content_bbs = \View::forge('parts/bbs_view');

				$view_content_bbs->set_safe('app_mode', $this->app_mode);
				$view_content_bbs->set_safe('agent_type', $this->agent_type);
				$view_content_bbs->set_safe('login_user_no', $this->user_no);
				$view_content_bbs->set_safe('host', $this->host);
				$view_content_bbs->set_safe('user_agent', $this->user_agent);
				$view_content_bbs->set('uri_base', $this->uri_base);

				$view_content_bbs->set('datetime_now', $datetime_now);

				$view_content_bbs->set('thread_arr', $db_bbs_thread_arr);
				$view_content_bbs->set('comment_arr', $db_bbs_comment_arr);
				$view_content_bbs->set('reply_arr', $db_bbs_reply_arr);
				$view_content_bbs->set('user_data_arr', $bbs_user_data_arr);
				$view_content_bbs->set('profile_arr', $bbs_profile_arr);
				$view_content_bbs->set('online_limit', $config_arr['online_limit']);
				$view_content_bbs->set_safe('anonymity', $config_arr['anonymity']);
				$view_content_bbs->set('login_profile_data_arr', $login_profile_data_arr);
				$view_content_bbs->set_safe('authority_arr', $authority_arr);

				// ページャー
				// ページャーの数字表示回数取得
				$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');
				$view_content_bbs->set('pagination_page', $page);
				$view_content_bbs->set('pagination_total', $db_bbs_thread_total);
				$view_content_bbs->set('pagination_limit', $limit_bbs_thread);
				$view_content_bbs->set('pagination_times', $pagination_times);
				$view_content_bbs->set('pagination_argument_arr', array($db_community_arr['community_no']));

				$view_content_bbs->set_safe('pagination_comment_limit', $limit_bbs_comment);
				$view_content_bbs->set_safe('pagination_reply_limit', $limit_bbs_reply);

			}





			// 権限

			//$view_content_bbs->set_safe('authority_operate_bbs_comment', $authority_arr['operate_bbs_comment']);
			//$view_content_bbs->set_safe('authority_operate_bbs_comment_delete', $authority_arr['operate_bbs_comment_delete']);

		}

		//$db_bbs_thread_total
		return $view_content_bbs;

	}




	/**
	* プロフィール選択フォーム
	*
	* @param integer $user_no ユーザーNo
	* @param integer $online_limit オンラインリミット
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function select_profile_form($db_community_arr, $page)
	{

		$code = null;

		if (isset($this->user_no))
		{

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


			// --------------------------------------------------
			//    コミュニティ　データ取得
			// --------------------------------------------------

			//$db_community_arr = $model_co->get_community($community_no, null);
			$member_arr = unserialize($db_community_arr['member']);
			$config_arr = unserialize($db_community_arr['config']);

			if (isset($member_arr[$this->user_no]['profile_no']))
			{
				$selected_profile = $member_arr[$this->user_no]['profile_no'];
			}
			else
			{
				$selected_profile = 'user';
			}


			// --------------------------------------------------
			//    プレイヤープロフィール　データ取得
			// --------------------------------------------------

			$db_users_data_arr = $model_user->get_user_data($this->user_no, null);


			// --------------------------------------------------
			//    追加プロフィール　データ取得
			// --------------------------------------------------

			// Limit取得
			$limit_profile = ($this->agent_type != 'smartphone') ? \Config::get('limit_select_profile_form') : \Config::get('limit_select_profile_form_sp');

			// プロフィールデータ取得
			$db_profile_arr = $model_user->get_profile_list($this->user_no, $page, $limit_profile);

			// 総数取得
			$total_profile = $model_user->get_profile_list_total($this->user_no);


			// --------------------------------------------------
			//    プロフィール設定
			// --------------------------------------------------

			$profile_arr = $db_profile_arr;


			// --------------------------------------------------
			//    プロフィール合成
			// --------------------------------------------------

			if ($page == 1) array_unshift($profile_arr, $db_users_data_arr);


			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$code .= '            <div id="select_profile_form_content">' . "\n";

			foreach ($profile_arr as $key => $value)
			{

				$checked = null;

				// プロフィールが追加プロフィールの場合
				if (isset($value['profile_no']))
				{
					$radio_value = \Security::htmlentities($value['profile_no']);
					if ($selected_profile == $value['profile_no']) $checked = ' checked';
				}
				// プロフィールがプレイヤープロフィールの場合
				else
				{
					$radio_value = 'user';
					if ($selected_profile == 'user') $checked = ' checked';
				}



				$code .= '            <div class="select_profile_form_box">' . "\n";

				$code .= '              <div class="select_profile_form_radio">' . "\n";
				$code .= '                <label><input type="radio" name="select_profile" value="' . $radio_value . '"' . $checked . '><span class="select_profile_form_text">' . \Security::htmlentities($value['profile_title']) . '</span></label>' . "\n";
				$code .= '              </div>' . "\n";

				$code .= '            </div>' . "\n";


				// --------------------------------------------------
				//   パーソナルボックス
				// --------------------------------------------------

				$view = \View::forge('parts/personal_box_view2');
				$view->set_safe('app_mode', $this->app_mode);
				$view->set('uri_base', $this->uri_base);

				if (isset($value['profile_no']))
				{
					$good_type = 'profile';
					$good_no = $value['profile_no'];
				}
				else if (isset($value['user_no']))
				{
					$good_type = 'user';
					$good_no = $value['user_no'];
				}

				$view->set('profile_arr', $value);

				$view->set_safe('online_limit', $config_arr['online_limit']);
				$view->set('good_type', $good_type);
				$view->set('good_no', $good_no);
				$view->set('good', $value['good']);

				$code .= $view->render() . "\n";

			}


			// --------------------------------------------------
			//   ページャー
			// --------------------------------------------------

			if ($total_profile > $limit_profile)
			{
				// ページャーの数字表示回数取得
				$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

				$code .= '        <div class="select_profile_form_pagination">' . "\n";

				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set('page', $page);
				$view_pagination->set('total', $total_profile);
				$view_pagination->set('limit', $limit_profile);
				$view_pagination->set('times', $pagination_times);
				$view_pagination->set('function_name', 'GAMEUSERS.uc.readConfigSelectProfile');
				$view_pagination->set('argument_arr', array($db_community_arr['community_no']));

				$code .= $view_pagination->render();

				$code .=  '        </div>' . "\n";

			}

			$code .= '            </div>' . "\n";

		}



		/*
		echo "<br><br><br><br>";

		echo '$member_arr';
		var_dump($member_arr);

		//echo $code;

		echo '$db_users_data_arr';
		var_dump($db_users_data_arr);

		echo '$db_profile_arr';
		var_dump($db_profile_arr);

		echo '$profile_arr';
		var_dump($profile_arr);
		*/
		return $code;

	}



	/**
	* メンバー
	*
	* @param array $arr
	* @return array
	*/
	public function member($arr)
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['first_load'] = 1;
			$_POST['community_no'] = 1;
			$_POST['type'] = 'moderator';
			$_POST['page'] = 1;
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');
		$val->add_callable('Original_Rule_Co');

		$val->add_field('community_no', 'Community No', 'required|check_community_no');
		$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(all|moderator|administrator|provisional|ban)$/');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_first_load = (isset($arr['first_load'])) ? true : false;
			$validated_community_no = (int) $val->validated('community_no');
			$validated_type = $val->validated('type');
			$validated_page = (int) $val->validated('page');
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}

			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_co = new \Model_Co();

		$model_user = new \Model_User();
		$model_user->agent_type = USER_AGENT;
		$model_user->user_no = USER_NO;
		$model_user->language = LANGUAGE;
		$model_user->uri_base = URI_BASE;
		$model_user->uri_current = URI_CURRENT;

		$original_func_co = new \Original\Func\Co();
		$original_func_co->agent_type = USER_AGENT;
		$original_func_co->user_no = USER_NO;
		$original_func_co->language = LANGUAGE;
		$original_func_co->uri_base = URI_BASE;
		$original_func_co->uri_current = URI_CURRENT;



		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_community_arr = $model_co->get_community($validated_community_no, null);
		$config_arr = unserialize($db_community_arr['config']);
		$authority_arr = $original_func_co->authority($db_community_arr);

		if ($validated_type === 'provisional' and $authority_arr['operate_member'])
		{
			$member_arr = unserialize($db_community_arr['provisional']);
		}
		else if ($validated_type === 'ban' and $authority_arr['operate_member'])
		{
			$member_arr = unserialize($db_community_arr['ban']);

		}
		else
		{
			$member_arr = unserialize($db_community_arr['member']);
		}


		// --------------------------------------------------
		//   参加申請メンバーがいない場合は空を返す
		// --------------------------------------------------

		// if ( ! $member_arr) return ['code' => null];
		if ( ! $member_arr) $member = [];

		// \Debug::dump($member_arr);
		// exit();

		// --------------------------------------------------
		//   参加申請をしているメンバーの人数
		// --------------------------------------------------

		$provisional_member_total = 0;

		if ($authority_arr['operate_member'] and $db_community_arr['provisional'])
		{
			$provisional_member_arr = unserialize($db_community_arr['provisional']);
			$provisional_member_total = count($provisional_member_arr);
		}


		// --------------------------------------------------
		//   閲覧する権限がない場合は処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['read_member']) return null;


		// --------------------------------------------------
		//   配列を反転させる（アクセス順に並び替えるため、重要）
		// --------------------------------------------------

		$member_arr = array_reverse($member_arr, true);


		// --------------------------------------------------
		//   役割ごとのメンバー抽出
		// --------------------------------------------------

		if ($validated_type === 'moderator')
		{
			$temp_arr = [];
			foreach ($member_arr as $key => $value)
			{
				if ($value['moderator']) $temp_arr[$key] = $value;
			}
			$member_arr = $temp_arr;
		}
		else if ($validated_type === 'administrator')
		{
			$temp_arr = [];
			foreach ($member_arr as $key => $value)
			{
				if ($value['administrator']) $temp_arr[$key] = $value;
			}
			$member_arr = $temp_arr;
		}


		// --------------------------------------------------
		//   指定ページ部分の配列抜き出し
		// --------------------------------------------------

		$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_member') : \Config::get('limit_member_sp');
		$offset = $limit * ($validated_page - 1);
		$sliced_member_arr = array_slice($member_arr, $offset, $limit, true);


		// --------------------------------------------------
		//   User No & Profile No 取得
		// --------------------------------------------------

		$user_no_arr = $profile_no_arr = [];

		foreach ($sliced_member_arr as $key => $value)
		{
			($value['profile_no']) ? array_push($profile_no_arr, $value['profile_no']) : array_push($user_no_arr, $key);
		}


		// --------------------------------------------------
		//   プロフィール　データ取得
		// --------------------------------------------------

		$users_data_arr = (count($user_no_arr) > 0) ? $model_user->get_user_data_list_in_personal_box_member($user_no_arr) : [];
		$profile_arr = (count($profile_no_arr) > 0) ? $model_user->get_profile_list_in_personal_box_member($profile_no_arr) : [];

		//\Debug::dump($users_data_arr, $profile_arr);



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// ---------------------------------------------
		//   ページャー
		// ---------------------------------------------

		$code_pagination = null;
		$total = count($member_arr);

		if ($total > $limit)
		{
			//$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$code_pagination .= '<div class="community_member_pagination">' . "\n";
			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set_safe('url', URI_BASE . 'uc/' . $db_community_arr['community_id'] . '/member');
			$view_pagination->set_safe('page', $validated_page);
			$view_pagination->set_safe('total', $total);
			$view_pagination->set_safe('limit', $limit);
			$view_pagination->set_safe('times', PAGINATION_TIMES);
			$view_pagination->set_safe('function_name', 'GAMEUSERS.uc.readMember');
			$view_pagination->set_safe('argument_arr', array($validated_community_no, "'" . $validated_type . "'", 1, 1, 1, 1));
			$code_pagination .= $view_pagination->render();
			$code_pagination .= '</div>' . "\n";

		}


		// ---------------------------------------------
		//   メンバー
		// ---------------------------------------------

		$code = null;

		$view = \View::forge('parts/member_view');

		$view->set_safe('type', $validated_type);
		$view->set('community_no', $validated_community_no);
		$view->set('member_arr', $sliced_member_arr);
		$view->set('config_arr', $config_arr);
		$view->set('users_data_arr', $users_data_arr);
		$view->set('profile_arr', $profile_arr);
		$view->set_safe('authority_arr', $authority_arr);
		$view->set_safe('first_load', $validated_first_load);
		$view->set('provisional_member_total', $provisional_member_total);
		$view->set_safe('code_pagination', $code_pagination);

		//if ($validated_type === 'provisional')

		//$view->set_safe('add_button_member_provisional', true);

		$return_arr['code'] = $view->render();


		$add_page_url = ($validated_page === 1) ? null : '/' . $validated_page;
		$add_page_meta_title = ($validated_page === 1) ? null : ' Page ' . $validated_page;

		$return_arr = array(
			'code' => $view->render(),
			'state' => [
				'group' => 'member',
				'content' => 'index',
				'function' => 'readMember',
				'page' => $validated_page,
				'communityNo' => $validated_community_no,
				'type' => $validated_type,
			],
			'url' => URI_BASE . 'uc/' . $db_community_arr['community_id'] . '/member' . $add_page_url,
			'meta_title' => $db_community_arr['name'] . ' - メンバー' . $add_page_meta_title,
			'meta_keywords' => $db_community_arr['name'] . ',メンバー',
			'meta_description' => '参加メンバー一覧ページ。'
		);




		if (isset($test))
		{
			if (isset($validated_first_load)) echo '$validated_first_load = ' . $validated_first_load . '<br>';
			if (isset($validated_community_no)) echo '$validated_community_no = ' . $validated_community_no . '<br>';
			if (isset($validated_type)) echo '$validated_type = ' . $validated_type . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';


			if (isset($db_community_arr))
			{
				echo '$db_community_arr';
				\Debug::dump($db_community_arr);
			}

			if (isset($member_arr))
			{
				echo '$member_arr';
				\Debug::dump($member_arr);
			}

			if (isset($config_arr))
			{
				echo '$config_arr';
				\Debug::dump($config_arr);
			}


			echo $return_arr['code'];


			exit();

		}



		return $return_arr;

	}



	/**
	* 参加申請メンバー
	*
	* @param array $db_community_arr コミュニティ情報
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function member_provisional($db_community_arr, $page)
	{

		// --------------------------------------------------
		//   参加申請メンバーがいない場合は空を返す
		// --------------------------------------------------

		if ($db_community_arr['provisional'] === null) return null;


		// --------------------------------------------------
		//   初期処理
		// --------------------------------------------------

		$member_arr = unserialize($db_community_arr['provisional']);
		$config_arr = unserialize($db_community_arr['config']);


		// --------------------------------------------------
		//   配列を反転させる（アクセス順に並び替えるため、重要）
		// --------------------------------------------------

		//$member_arr = array_reverse($member_arr, true);


		// --------------------------------------------------
		//   役割ごとのメンバー抽出
		// --------------------------------------------------
		/*
		if ($type == 'moderator')
		{
			$moderator_arr = array();
			foreach ($member_arr as $key => $value) {
				if ($value['moderator'] === true) $moderator_arr[$key] = $value;
			}
			$member_arr = $moderator_arr;
		}
		else if ($type == 'administrator')
		{
			$administrator_arr = array();
			foreach ($member_arr as $key => $value) {
				if ($value['administrator'] === true) $administrator_arr[$key] = $value;
			}
			$member_arr = $administrator_arr;
		}
		*/

		// --------------------------------------------------
		//   指定ページ部分の配列抜き出し
		// --------------------------------------------------

		$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_member') : \Config::get('limit_member_sp');
		$offset = $limit * ($page - 1);

		$sliced_member_arr = array_slice($member_arr, $offset, $limit, true);


		// --------------------------------------------------
		//   User No & Profile No 取得
		// --------------------------------------------------

		$user_no_arr = array();
		$profile_no_arr = array();

		foreach ($sliced_member_arr as $key => $value)
		{
			($value['profile_no']) ? array_push($profile_no_arr, $value['profile_no']) : array_push($user_no_arr, $key);
		}


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;

		$original_func_co = new \Original\Func\Co();
		$original_func_co->agent_type = $this->agent_type;
		$original_func_co->user_no = $this->user_no;
		$original_func_co->language = $this->language;
		$original_func_co->uri_base = $this->uri_base;
		$original_func_co->uri_current = $this->uri_current;


		// --------------------------------------------------
		//   権限
		// --------------------------------------------------

		//$authority_arr = $original_func_co->authority($db_community_arr);


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$users_data_arr = (count($user_no_arr) > 0) ? \Security::htmlentities($model_user->get_user_data_list_in_personal_box_member($user_no_arr)) : array();
		$profile_arr = (count($profile_no_arr) > 0) ? \Security::htmlentities($model_user->get_profile_list_in_personal_box_member($profile_no_arr)) : array();

		//$users_data_arr = \Security::htmlentities($model_user->get_user_data_list_in_personal_box_member($user_no_arr));
		//$profile_arr = \Security::htmlentities($model_user->get_profile_list_in_personal_box_member($profile_no_arr));


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		foreach ($sliced_member_arr as $key => $value) {

			if (isset($profile_arr[$value['profile_no']]))
			{
				$arr = $profile_arr[$value['profile_no']];
			}
			else if (isset($users_data_arr[$key]))
			{
				$arr = $users_data_arr[$key];
			}
			else
			{
				$arr = null;
			}
			//$arr = ($value['profile_no']) ? $profile_arr[$value['profile_no']] : $users_data_arr[$key];

			$view = \View::forge('parts/personal_box_view2');
			$view->set_safe('app_mode', $this->app_mode);
			$view->set('uri_base', $this->uri_base);
			$view->set('profile_arr', $arr);
			$view->set_safe('online_limit', $config_arr['online_limit']);


			// ------------------------------
			//    管理用ボタン表示
			// ------------------------------
			/*
			if ( ! $value['administrator'])
			{

				// ------------------------------
				//    退会・BAN
				// ------------------------------

				if ($this->user_no != $key and $authority_arr['operate_member'])
				{
					$view->set_safe('add_button_member_withdraw', true);
					$view->set_safe('add_button_member_ban', true);
				}

				// ------------------------------
				//    モデレーター認定・解除
				// ------------------------------

				if ($authority_arr['administrator'])
				{
					($value['moderator']) ? $view->set_safe('add_button_moderator_withdraw', true) : $view->set_safe('add_button_moderator', true);
				}
				else if ($this->user_no == $key and $authority_arr['moderator'])
				{
					$view->set_safe('add_button_moderator_withdraw', true);
				}

				$view->set('community_no', $db_community_arr['community_no']);

			}
			*/
			$view->set('community_no', $db_community_arr['community_no']);
			$view->set_safe('add_button_member_provisional', true);
			$view->set_safe('add_explanation', true);

			$code .= $view->render();

		}


		// --------------------------------------------------
		//   ページャー
		// --------------------------------------------------

		$total = count($member_arr);

		if ($total > $limit)
		{
			$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$code .= '<div class="community_member_pagination">' . "\n";
			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set_safe('page', $page);
			$view_pagination->set_safe('total', $total);
			$view_pagination->set_safe('limit', $limit);
			$view_pagination->set_safe('times', $pagination_times);
			$view_pagination->set_safe('function_name', 'readMember');
			$view_pagination->set_safe('argument_arr', array($db_community_arr['community_no']));
			$code .= $view_pagination->render();
			$code .= '</div>' . "\n";

		}

		/*
		echo "<br><br><br><br>";

		echo '$member_arr';
		var_dump($member_arr);

		echo '$sliced_member_arr';
		var_dump($sliced_member_arr);

		echo '$user_no_arr';
		var_dump($user_no_arr);

		echo '$profile_no_arr';
		var_dump($profile_no_arr);

		echo '$users_data_arr';
		var_dump($users_data_arr);

		echo '$profile_arr';
		var_dump($profile_arr);

		echo ($code);

		echo "<br><br><br><br>";
		*/

		return $code;

	}



	/**
	* Banされたメンバー
	*
	* @param array $db_community_arr コミュニティ情報
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function member_ban($db_community_arr, $page)
	{

		// --------------------------------------------------
		//   Banされているメンバーがいない場合は空を返す
		// --------------------------------------------------

		if ($db_community_arr['ban'] === null) return null;


		// --------------------------------------------------
		//   初期処理
		// --------------------------------------------------

		$member_arr = unserialize($db_community_arr['ban']);
		$config_arr = unserialize($db_community_arr['config']);


		// --------------------------------------------------
		//   指定ページ部分の配列抜き出し
		// --------------------------------------------------

		$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_member') : \Config::get('limit_member_sp');
		$offset = $limit * ($page - 1);

		$sliced_member_arr = array_slice($member_arr, $offset, $limit, true);


		// --------------------------------------------------
		//   User No & Profile No 取得
		// --------------------------------------------------

		$user_no_arr = array();
		$profile_no_arr = array();

		foreach ($sliced_member_arr as $key => $value)
		{
			($value['profile_no']) ? array_push($profile_no_arr, $value['profile_no']) : array_push($user_no_arr, $key);
		}


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;

		$original_func_co = new \Original\Func\Co();
		$original_func_co->agent_type = $this->agent_type;
		$original_func_co->user_no = $this->user_no;
		$original_func_co->language = $this->language;
		$original_func_co->uri_base = $this->uri_base;
		$original_func_co->uri_current = $this->uri_current;


		// --------------------------------------------------
		//   権限
		// --------------------------------------------------

		//$authority_arr = $original_func_co->authority($db_community_arr);


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$users_data_arr = (count($user_no_arr) > 0) ? \Security::htmlentities($model_user->get_user_data_list_in_personal_box_member($user_no_arr)) : array();
		$profile_arr = (count($profile_no_arr) > 0) ? \Security::htmlentities($model_user->get_profile_list_in_personal_box_member($profile_no_arr)) : array();


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		foreach ($sliced_member_arr as $key => $value) {

			if (isset($profile_arr[$value['profile_no']]))
			{
				$arr = $profile_arr[$value['profile_no']];
			}
			else if (isset($users_data_arr[$key]))
			{
				$arr = $users_data_arr[$key];
			}
			else
			{
				$arr = null;
			}
			//$arr = ($value['profile_no']) ? $profile_arr[$value['profile_no']] : $users_data_arr[$key];

			$view = \View::forge('parts/personal_box_view2');
			$view->set_safe('app_mode', $this->app_mode);
			$view->set('uri_base', $this->uri_base);
			$view->set('profile_arr', $arr);
			$view->set_safe('online_limit', $config_arr['online_limit']);

			$view->set('community_no', $db_community_arr['community_no']);
			$view->set_safe('add_button_member_lift_ban', true);
			$view->set_safe('add_explanation', true);

			$code .= $view->render();

		}


		// --------------------------------------------------
		//   ページャー
		// --------------------------------------------------

		$total = count($member_arr);

		if ($total > $limit)
		{
			$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$code .= '<div class="community_member_pagination">' . "\n";
			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set_safe('page', $page);
			$view_pagination->set_safe('total', $total);
			$view_pagination->set_safe('limit', $limit);
			$view_pagination->set_safe('times', $pagination_times);
			$view_pagination->set_safe('function_name', 'readMember');
			$view_pagination->set_safe('argument_arr', array($db_community_arr['community_no']));
			$code .= $view_pagination->render();
			$code .= '</div>' . "\n";

		}

		/*
		echo "<br><br><br><br>";

		echo '$member_arr';
		var_dump($member_arr);

		echo '$sliced_member_arr';
		var_dump($sliced_member_arr);

		echo '$user_no_arr';
		var_dump($user_no_arr);

		echo '$profile_no_arr';
		var_dump($profile_no_arr);

		echo '$users_data_arr';
		var_dump($users_data_arr);

		echo '$profile_arr';
		var_dump($profile_arr);

		echo ($code);

		echo "<br><br><br><br>";
		*/

		return $code;

	}






	/**
	* データ
	*
	* @param array $arr
	* @return array
	*/
	public function data($arr)
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');
		$val->add_callable('Original_Rule_Co');

		$val->add_field('community_no', 'Community No', 'required|check_community_no');

		if ($val->run($arr))
		{
			$validated_community_no = $val->validated('community_no');
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}

			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_co = new \Model_Co();

		$model_game = new \Model_Game();
		$model_game->agent_type = AGENT_TYPE;
		$model_game->user_no = USER_NO;
		$model_game->language = LANGUAGE;
		$model_game->uri_base = URI_BASE;
		$model_game->uri_current = URI_CURRENT;

		$original_func_co = new \Original\Func\Co();
		$original_func_co->agent_type = AGENT_TYPE;
		$original_func_co->user_no = USER_NO;
		$original_func_co->language = LANGUAGE;
		$original_func_co->uri_base = URI_BASE;
		$original_func_co->uri_current = URI_CURRENT;

		$original_func_common = new \Original\Func\Common();
		$original_func_common->agent_type = AGENT_TYPE;
		$original_func_common->user_no = USER_NO;
		$original_func_common->language = LANGUAGE;
		$original_func_common->uri_base = URI_BASE;
		$original_func_common->uri_current = URI_CURRENT;

		$original_code_co = new \Original\Code\Co();
		$original_code_co->agent_type = AGENT_TYPE;
		$original_code_co->host = HOST;
		$original_code_co->user_no = USER_NO;
		$original_code_co->language = LANGUAGE;
		$original_code_co->uri_base = URI_BASE;
		$original_code_co->uri_current = URI_CURRENT;



		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_community_arr = $model_co->get_community($validated_community_no, null);
		$member_arr = unserialize($db_community_arr['member']);
		$member_total = count($member_arr);

		$config_arr = unserialize($db_community_arr['config']);
		$authority_arr = $original_func_co->authority($db_community_arr);

		$code_select_profile_form = null;
		if (USER_NO) $code_select_profile_form = $original_code_co->select_profile_form($db_community_arr, 1);




		// --------------------------------------------------
		//   メンバー登録申請中の場合、参加申請を取り消すボタンを表示するための変数
		// --------------------------------------------------

		$provisional_member = false;

		if ($config_arr['participation_type'] == 2 and $db_community_arr['provisional'])
		{
			$provisional_arr = unserialize($db_community_arr['provisional']);
			if (isset($provisional_arr[USER_NO])) $provisional_member = true;
		}


		// --------------------------------------------------
		//   関連ゲーム
		// --------------------------------------------------

		$game_names_arr = [];

		if ($db_community_arr['game_list'])
		{

			// 配列化
			$game_no_arr = $original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

			if (count($game_no_arr) > 0)
			{
				// 重複番号削除
				$game_no_arr = array_unique($game_no_arr);

				// ゲーム名取得
				$game_names_arr = $model_game->get_game_name(LANGUAGE, $game_no_arr);
			}

		}

		//\Debug::dump($game_names_arr);
		// exit();


		// ---------------------------------------------
		//   コード作成
		// ---------------------------------------------

		//$code = null;

		$view = \View::forge('content/community_data_view');
		$view->set('community_no', $validated_community_no);
		$view->set('community_id', $db_community_arr['community_id']);
		$view->set('description', $db_community_arr['description']);
		$view->set('member_total', $member_total);
		$view->set('game_names_arr', $game_names_arr);
		$view->set_safe('config_arr', $config_arr);
		$view->set_safe('authority_arr', $authority_arr);
		$view->set_safe('provisional_member', $provisional_member);

		$view->set_safe('code_select_profile_form', $code_select_profile_form);

		//$return_arr['code'] = $view->render();



		$return_arr = array(
			'code' => $view->render(),
			'state' => [
				'group' => 'data',
				'content' => 'index'
			],
			'url' => URI_BASE . 'uc/' . $db_community_arr['community_id'] . '/data',
			'meta_title' => $db_community_arr['name'] . ' - データ',
			'meta_keywords' => $db_community_arr['name'] . ',データ',
			'meta_description' => 'コミュニティに関するデータ'
		);



		if (isset($test))
		{
			if (isset($validated_first_load)) echo '$validated_first_load = ' . $validated_first_load . '<br>';
			if (isset($validated_community_no)) echo '$validated_community_no = ' . $validated_community_no . '<br>';
			if (isset($validated_type)) echo '$validated_type = ' . $validated_type . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';


			if (isset($db_community_arr))
			{
				echo '$db_community_arr';
				\Debug::dump($db_community_arr);
			}

			if (isset($member_arr))
			{
				echo '$member_arr';
				\Debug::dump($member_arr);
			}

			if (isset($config_arr))
			{
				echo '$config_arr';
				\Debug::dump($config_arr);
			}


			echo $return_arr['code'];


			exit();

		}



		return $return_arr;

	}




	/**
	* 通知
	*
	* @param array $arr
	* @return array
	*/
	public function notification($arr)
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');
		$val->add_callable('Original_Rule_Co');

		$val->add_field('community_no', 'Community No', 'required|check_community_no');

		if ($val->run($arr))
		{
			$validated_community_no = $val->validated('community_no');
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}

			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_co = new \Model_Co();

		$original_func_co = new \Original\Func\Co();
		$original_func_co->agent_type = AGENT_TYPE;
		$original_func_co->user_no = USER_NO;
		$original_func_co->language = LANGUAGE;
		$original_func_co->uri_base = URI_BASE;
		$original_func_co->uri_current = URI_CURRENT;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_community_arr = $model_co->get_community($validated_community_no, null);
		$authority_arr = $original_func_co->authority($db_community_arr);


		// --------------------------------------------------
		//   メール一斉送信
		// --------------------------------------------------

		// $notification_arr = null;
		// $notification_datetime = null;
		// $notification_limit = null;

		if ($authority_arr['operate_send_all_mail'])
		{
			$notification_arr = unserialize($db_community_arr['mail']);

			// 日時
			$datetime = new \DateTime();
			$notification_datetime = $datetime->format('Y/n/d');


			// --------------------------------------------------
			//    最新の送信ログが前日の場合、カウントをゼロに戻す
			// --------------------------------------------------

			$count = 0;

			if (isset($notification_arr['log'][0]['datetime']))
			{
				$datetime = new \DateTime();
				$datetime_ymd_now = $datetime->format('Y-m-d');

				$datetime = new \DateTime($notification_arr['log'][0]['datetime']);
				$datetime_ymd_log = $datetime->format("Y-m-d");

				$count = ($datetime_ymd_now == $datetime_ymd_log) ? $notification_arr['count'] : 0;
			}

			$notification_limit = \Config::get('limit_mail_all') - $count;
		}
		else
		{
			exit();
		}





		// \Debug::dump($notification_arr);
		// exit();


		// ---------------------------------------------
		//   コード作成
		// ---------------------------------------------

		//$code = null;

		$view = \View::forge('content/community_notification_view');
		$view->set('community_no', $validated_community_no);
		$view->set('notification_arr', $notification_arr);
		$view->set('notification_datetime', $notification_datetime);
		$view->set('notification_limit', $notification_limit);

		$return_arr = array(
			'code' => $view->render(),
			'state' => [
				'group' => 'notification',
				'content' => 'index'
			],
			'url' => URI_BASE . 'uc/' . $db_community_arr['community_id'] . '/notification',
			'meta_title' => $db_community_arr['name'] . ' - 通知',
			'meta_keywords' => $db_community_arr['name'] . ',通知',
			'meta_description' => 'メンバーに向けて通知の一斉送信ができます。'
		);



		if (isset($test))
		{
			if (isset($validated_community_no)) echo '$validated_community_no = ' . $validated_community_no . '<br>';


			if (isset($db_community_arr))
			{
				echo '$db_community_arr';
				\Debug::dump($db_community_arr);
			}

			if (isset($notification_arr))
			{
				echo '$notification_arr';
				\Debug::dump($notification_arr);
			}

			if (isset($config_arr))
			{
				echo '$config_arr';
				\Debug::dump($config_arr);
			}


			echo $return_arr['code'];


			exit();

		}



		return $return_arr;

	}




	/**
	* 通知
	*
	* @param array $arr
	* @return array
	*/
	public function config($arr)
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['community_no'] = 1;
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');
		$val->add_callable('Original_Rule_Co');

		$val->add_field('community_no', 'Community No', 'required|check_community_no');

		if ($val->run($arr))
		{
			$validated_community_no = $val->validated('community_no');
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}

			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_co = new \Model_Co();
		$model_image = new \Model_Image();

		$model_game = new \Model_Game();
		$model_game->agent_type = AGENT_TYPE;
		$model_game->user_no = USER_NO;
		$model_game->language = LANGUAGE;
		$model_game->uri_base = URI_BASE;
		$model_game->uri_current = URI_CURRENT;

		$original_func_co = new \Original\Func\Co();
		$original_func_co->agent_type = AGENT_TYPE;
		$original_func_co->user_no = USER_NO;
		$original_func_co->language = LANGUAGE;
		$original_func_co->uri_base = URI_BASE;
		$original_func_co->uri_current = URI_CURRENT;

		$original_func_common = new \Original\Func\Common();
		$original_func_common->agent_type = AGENT_TYPE;
		$original_func_common->user_no = USER_NO;
		$original_func_common->language = LANGUAGE;
		$original_func_common->uri_base = URI_BASE;
		$original_func_common->uri_current = URI_CURRENT;

		$original_code_co = new \Original\Code\Co();
		$original_code_co->agent_type = AGENT_TYPE;
		$original_code_co->host = HOST;
		$original_code_co->user_no = USER_NO;
		$original_code_co->language = LANGUAGE;
		$original_code_co->uri_base = URI_BASE;
		$original_code_co->uri_current = URI_CURRENT;



		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_community_arr = $model_co->get_community($validated_community_no, null);
		$config_arr = unserialize($db_community_arr['config']);
		$authority_arr = $original_func_co->authority($db_community_arr);
		$code_select_profile_form = $original_code_co->select_profile_form($db_community_arr, 1);
		$db_image_arr = $model_image->select_header_hero_image_community_edit(['community_no' => $validated_community_no]);


		// --------------------------------------------------
		//   関連ゲーム
		// --------------------------------------------------

		$game_names_arr = [];

		if ($db_community_arr['game_list'])
		{

			// 配列化
			$game_no_arr = $original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

			if (count($game_no_arr) > 0)
			{
				// 重複番号削除
				$game_no_arr = array_unique($game_no_arr);

				// ゲーム名取得
				$game_names_arr = $model_game->get_game_name(LANGUAGE, $game_no_arr);
			}

		}
// \Debug::dump($db_image_arr);

		// ---------------------------------------------
		//   コード作成
		// ---------------------------------------------

		$code_profile = $code_nofitication = $code_basic = $code_community = $code_authority_read = $code_authority_operate = $code_delete = null;


		// プロフィール変更　通知設定
		if ($authority_arr['member'])
		{
			$view = \View::forge('form/config_community_profile_view');
			$view->set('community_no', $validated_community_no);
			$view->set_safe('code_select_profile_form', $code_select_profile_form);

			$code_profile = $view->render();


			$view = \View::forge('form/config_community_notification_view');
			$view->set('community_no', $validated_community_no);
			$view->set_safe('authority_arr', $authority_arr);
			$code_nofitication = $view->render();
		}


		// コミュニティ基本設定　コミュニティ設定
		if ($authority_arr['administrator'] or $authority_arr['operate_config_community'])
		{
			$view = \View::forge('form/config_community_basic_view');
			$view->set('community_no', $validated_community_no);
			$view->set('db_community_arr', $db_community_arr);
			$view->set('game_no_arr', $game_no_arr);
			$view->set('game_names_arr', $game_names_arr);
			$view->set('db_image_arr', $db_image_arr);
			$code_basic = $view->render();

			$view = \View::forge('form/config_community_community_view');
			$view->set('community_no', $validated_community_no);
			$view->set('open', $db_community_arr['open']);
			$view->set('config_arr', $config_arr);
			$code_community = $view->render();
		}


		// 権限
		if ($authority_arr['administrator'])
		{
			$view = \View::forge('form/config_community_authority_read_view');
			$view->set('community_no', $validated_community_no);
			$view->set('config_arr', $config_arr);
			$code_authority_read = $view->render();

			$view = \View::forge('form/config_community_authority_operate_view');
			$view->set('community_no', $validated_community_no);
			$view->set('config_arr', $config_arr);
			$code_authority_operate = $view->render();

			$view = \View::forge('form/config_community_delete_view');
			$view->set('community_no', $validated_community_no);
			$code_delete = $view->render();
		}





		$return_arr = array(
			'code_profile' => $code_profile,
			'code_nofitication' => $code_nofitication,
			'code_basic' => $code_basic,
			'code_community' => $code_community,
			'code_authority_read' => $code_authority_read,
			'code_authority_operate' => $code_authority_operate,
			'code_delete' => $code_delete,
			'state' => [
				'group' => 'config',
				'content' => 'index'
			],
			'url' => URI_BASE . 'uc/' . $db_community_arr['community_id'] . '/config',
			'meta_title' => $db_community_arr['name'] . ' - プロフィール設定',
			'meta_keywords' => $db_community_arr['name'] . ',プロフィール設定',
			'meta_description' => 'プロフィール設定ページ。'
		);



		if (isset($test))
		{
			if (isset($validated_community_no)) echo '$validated_community_no = ' . $validated_community_no . '<br>';


			if (isset($db_community_arr))
			{
				echo '$db_community_arr';
				\Debug::dump($db_community_arr);
			}

			if (isset($game_names_arr))
			{
				echo '$game_names_arr';
				\Debug::dump($game_names_arr);
			}

			if (isset($config_arr))
			{
				echo '$config_arr';
				\Debug::dump($config_arr);
			}


			// echo $return_arr['code_profile'];
			// echo $return_arr['code_nofitication'];
			// echo $return_arr['code_basic'];
			// echo $return_arr['code_community'];
			echo $return_arr['code_authority_read'];
			echo $return_arr['code_authority_operate'];
			echo $return_arr['code_delete'];

			exit();

		}



		return $return_arr;

	}


}
