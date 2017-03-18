<?php

namespace Original\Code;

class Common
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
	* 画像コード
	*
	* @param array $path パス
	* @param array $size_arr サイズ
	* @param array $datetime 日時
	* @return string HTMLコード
	*/
	public function image($path, $size_arr, $datetime = null)
	{

		if ($datetime) $path .= '?' . strtotime($datetime);

		if ($this->agent_type == 'smartphone')
		{
			$width = '100%';
		}
		else if ($size_arr['width'] >= 800)
		{
			$width = '100%';
		}
		else if ($size_arr['width'] >= 600)
		{
			$width = '75%';
		}
		else if ($size_arr['width'] >= 300)
		{
			$width = '50%';
		}
		else
		{
			$width = $size_arr['width'] . 'px';
		}


		$code = '<a href="' . $path .'" id="modal_image">';
		$code .= '<img src="' . $path .'" width="' . $width . '" style="max-width:' . $size_arr['width'] . 'px">';
		$code .= '</a>';


		return $code;

	}


	/**
	* 動画コード
	*
	* @param array $db_community_arr コミュニティ情報
	* @param array $authority_arr 権限
	* @param array $page ページ
	* @return string HTMLコード
	*/
	public function movie($movie_arr)
	{

		$code = '';

		if (isset($movie_arr[0]['youtube']))
		{
			$code .= '<div class="video_box">' . "\n";

			$code .= '  <div class="video_thumbnail"><img src="https://img.youtube.com/vi/' . $movie_arr[0]['youtube'] . '/mqdefault.jpg" width="100%" /></div>' . "\n";
			$code .= '  <div class="video_play_button" id="video_popup" onclick="popupMovie(this)" data-url="https://www.youtube.com/watch?v=' . $movie_arr[0]['youtube'] . '"><img src="' . $this->uri_base . 'assets/img/common/movie_play_button.png" width="100%" /></div>' . "\n";
			$code .= '</div>' . "\n\n";
		}


		return $code;

	}



	/**
	* コミュニティ検索
	*
	* @param array $condition_arr 検索条件
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function search_community_list($condition_arr, $page)
	{

		$language = 'ja';


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_common = new \Model_Common();
		$model_common->agent_type = $this->agent_type;
		$model_common->user_no = $this->user_no;
		$model_common->language = $this->language;
		$model_common->uri_base = $this->uri_base;
		$model_common->uri_current = $this->uri_current;

		$model_game = new \Model_Game();
		$model_game->agent_type = $this->agent_type;
		$model_game->user_no = $this->user_no;
		$model_game->language = $this->language;
		$model_game->uri_base = $this->uri_base;
		$model_game->uri_current = $this->uri_current;


		// --------------------------------------------------
		//   コミュニティ取得
		// --------------------------------------------------

		$limit = ($this->agent_type != 'smartphone') ? \Config::get('index_limit_community') : \Config::get('index_limit_community_sp');

		if (isset($condition_arr['user_no'], $condition_arr['game_list']))
		{
			$result_arr = $model_common->get_community_participation($condition_arr['game_list'], $page, $limit);
		}
		else
		{
			$result_arr = $model_common->get_community($page, $limit);
		}

		$community_arr = $result_arr[0];
		$total = $result_arr[1];
		//$community_arr = \Security::htmlentities($model_index->get_community(1, $limit));
		//echo '$community_arr';
		//var_dump($community_arr);

		// --------------------------------------------------
		//   ゲーム名取得
		// --------------------------------------------------

		if (count($community_arr) > 0)
		{

			$game_no_arr = array();

			// ゲームNo取得
			foreach ($community_arr as $key => &$value)
			{
				$exploded_game_list_arr = explode(',', $value['game_list']);
				array_shift($exploded_game_list_arr);
				array_pop($exploded_game_list_arr);
				array_push($game_no_arr, $exploded_game_list_arr[0]);
				$value['game_no'] = $exploded_game_list_arr[0];
			}
			unset($value);

			// 重複番号削除
			$game_no_arr = array_unique($game_no_arr);

			// ゲーム名取得
			//$game_names_arr = \Security::htmlentities($model_game->get_game_name($this->language, $game_no_arr));
			$game_names_arr = $model_game->get_game_name($language, $game_no_arr);

		}
		/*
		echo '$community_arr';
		var_dump($community_arr);

		//echo "<br><br><br><br>";

		echo '$game_no_arr';
		var_dump($game_no_arr);

		echo '$game_names_arr';
		var_dump($game_names_arr);
		exit();
		*/

		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (count($community_arr) > 0)
		{

			$view = \View::forge('parts/community_list_view');
			$view->set_safe('app_mode', $this->app_mode);
			$view->set('uri_base', $this->uri_base);
			$view->set('community_arr', $community_arr);
			$view->set('game_names_arr', $game_names_arr);

			$code = $view->render() . "\n";


			// --------------------------------------------------
			//   ページャー
			// --------------------------------------------------

			if ($total > $limit)
			{
				$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

				$code .= '<div class="margin_top_20">' . "\n";
				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set_safe('page', $page);
				$view_pagination->set_safe('total', $total);
				$view_pagination->set_safe('limit', $limit);
				$view_pagination->set_safe('times', $pagination_times);

				if (isset($condition_arr['user_no'], $condition_arr['game_list']))
				{
					$view_pagination->set_safe('function_name', 'GAMEUSERS.player.readParticipationCommunity');
					$view_pagination->set_safe('argument_arr', array($condition_arr['user_no']));
				}
				else
				{
					$view_pagination->set_safe('function_name', 'searchCommunityList');
					$view_pagination->set_safe('argument_arr', array());
				}

				$code .= $view_pagination->render();
				$code .= '</div>' . "\n";

			}

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
	* 利用規約
	*
	* @param array $condition_arr 検索条件
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function user_terms()
	{

		$language = 'ja';


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;

		$original_common_text = new \Original\Common\Text();


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$new_user_terms_version = \Config::get('user_terms_version');

		if ($this->user_no)
		{
			$db_user_data = $model_user->get_user_data($this->user_no, null);
			$user_terms_arr = (isset($db_user_data['user_terms'])) ? unserialize($db_user_data['user_terms']) : null;

			//$user_terms_arr = array('20150701', '20150702');

			$user_terms_approval_version = (isset($user_terms_arr[0])) ? $user_terms_arr[0] : null;
		}
		else
		{
			$user_terms_approval_version = \Session::get('user_terms_approval_version');
		}

		// echo '$new_user_terms_version';
		// var_dump($new_user_terms_version);
//
		// echo '$user_terms_approval_version';
		// var_dump($user_terms_approval_version);


		// --------------------------------------------------
		//   同意済みかそうでないかのチェック
		// --------------------------------------------------

		// 同意済み
		if (isset($new_user_terms_version, $user_terms_approval_version) and $new_user_terms_version == $user_terms_approval_version)
		{
			$approval = true;
		}
		// 同意していない
		else
		{
			$approval = false;
		}


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		if ($approval)
		{
			$code = null;
		}
		else
		{
			$random_id = $original_common_text->random_text_lowercase(15);
			//var_dump($random_id);

			$view = \View::forge('parts/user_terms_view');
			$view->set('random_id', $random_id);
			$code = $view->render();
		}



		return $code;

	}





	/**
	* お知らせ
	*
	* @param boolean $save_notifications 未読のお知らせを記録する場合true
	* @param boolean $unread 未読を読み込む場合true
	* @param array $db_users_data_arr User Data
	* @param integer $page
	* @return string HTMLコード
	*/
	public function notifications($save_notifications, $unread, $db_users_data_arr, $page)
	{

		$language = 'ja';


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		// インスタンス作成
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
		$original_func_common->agent_type = $this->agent_type;
		$original_func_common->user_no = $this->user_no;
		$original_func_common->language = $this->language;
		$original_func_common->uri_base = $this->uri_base;
		$original_func_common->uri_current = $this->uri_current;



		// --------------------------------------------------
		//    既読IDの処理
		//    例）$already_read_id_arr = array('0zj0pnd2vlw2eex', '2rwgyd1sbzyu5ub')
		// --------------------------------------------------

		if ($db_users_data_arr['notifications_already_read_id'])
		{
			// アンシリアライズ
			$pre_already_read_id_arr = unserialize($db_users_data_arr['notifications_already_read_id']);

			// 日付を削除したIDのみの配列を作る
			$already_read_id_arr = array();
			foreach ($pre_already_read_id_arr as $key => $value) {
				array_push($already_read_id_arr, $value['id']);
			}
		}
		else
		{
			$already_read_id_arr = null;
		}

		// 既読通知の読み込みで、既読IDがない場合は、code＝nullを返して終わり
		if ($unread === false and $already_read_id_arr === null)
		{
			return null;
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
		//    例）$notification_recruitment_arr = array(1,2,3,4,5)
		// --------------------------------------------------

		$db_user_game_community = $model_gc->get_user_game_community();
		$notification_recruitment_arr = (isset($db_user_game_community['notification_recruitment'])) ? $original_func_common->return_db_array('db_php', $db_user_game_community['notification_recruitment']) : null;



		// --------------------------------------------------
		//    データ読み込み
		// --------------------------------------------------

		if ($this->app_mode)
		{
			$limit = \Config::get('limit_notification_app');
		}
		else if ($this->agent_type != 'smartphone')
		{
			$limit = \Config::get('limit_notification');
		}
		else
		{
			$limit = \Config::get('limit_notification_sp');
		}

		//$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_notification') : \Config::get('limit_notification_sp');


		$read_notifications_argument_arr = array(
			'language' => $language,
			'unread' => $unread,
			'already_read_id_arr' => $already_read_id_arr,
			'user_no' => $this->user_no,
			'participation_community_no_arr' => $participation_community_no_arr,
			'notification_recruitment_arr' => $notification_recruitment_arr,
			'page' => $page,
			'limit' => $limit
		);

		$result_arr = $model_notifications->read_notifications($read_notifications_argument_arr);
		$db_notifications_arr = $result_arr['data'];
		$total = $result_arr['total'];

		// \Debug::$js_toggle_open = true;
		// \Debug::dump($result_arr);

		$notifications_arr = $save_unread_id_arr = array();

		foreach ($db_notifications_arr as $key => $value) {

			$notifications_arr[$key]['regi_date'] = $value['regi_date'];
			$notifications_arr[$key]['user_no'] = $value['user_no'];
			//if (isset($value['profile_no'])) $notifications_arr[$key]['profile_no'] = $value['profile_no'];
			$notifications_arr[$key]['profile_no'] = $value['profile_no'];


			// --------------------------------------------------
			//    ハンドルネーム＆サムネイル
			// --------------------------------------------------

			// 匿名
			if ($value['anonymity'])
			{
				$notifications_arr[$key]['name'] = 'ななしさん';
				$notifications_arr[$key]['thumbnail'] = null;
			}
			else
			{
				// プロフィール
				if ($value['profile_handle_name'])
				{
					$notifications_arr[$key]['name'] = $value['profile_handle_name'];
					$notifications_arr[$key]['thumbnail'] = $value['profile_thumbnail'];
				}
				// ユーザー
				else if ($value['user_handle_name'])
				{
					$notifications_arr[$key]['name'] = $value['user_handle_name'];
					$notifications_arr[$key]['thumbnail'] = $value['user_thumbnail'];
				}
				// 非ログイン
				else
				{
					if ($value['name'])
					{
						$notifications_arr[$key]['name'] = $value['name'];
					}
					else
					{
						$notifications_arr[$key]['name'] = 'ななしさん';
					}

					$notifications_arr[$key]['thumbnail'] = null;
				}
				//$notifications_arr[$key]['name'] = ($value['user_handle_name']) ? $value['user_handle_name'] : $value['name'];
				//$notifications_arr[$key]['thumbnail'] = $value['user_thumbnail'];
			}

			// 改行削除＆文字数調節
			$comment = str_replace(array("\r\n","\r","\n"), ' ', $value['comment']);
			$notifications_arr[$key]['comment_short'] = (mb_strlen($comment) > 100) ? mb_substr($comment, 0, 99, 'UTF-8') . '…' : $comment;

			$notifications_arr[$key]['game_name'] = $value['game_name'];
			$notifications_arr[$key]['game_id'] = $value['game_id'];


			// --------------------------------------------------
			//    ゲームコミュニティ
			// --------------------------------------------------

			if ($value['type1'] == 'gc' and $value['type2'] == 'recruitment')
			{
				$argument_arr = unserialize($value['argument']);
				$notifications_arr[$key]['link_page_arr'] = array('type' => 'gc', 'id' => $value['game_id'], 'recruitment_id' => $argument_arr['recruitment_id']);
				$notifications_arr[$key]['notification'] = '「' . $value['title'] . '」が投稿されました。';
			}

			if ($value['type1'] == 'gc' and $value['type2'] == 'recruitment_reply')
			{
				$argument_arr = unserialize($value['argument']);
				$notifications_arr[$key]['link_page_arr'] = array('type' => 'gc', 'id' => $value['game_id'], 'recruitment_id' => $argument_arr['recruitment_id']);
				$notifications_arr[$key]['notification'] = '「' . $value['title'] . '」に返信が書き込まれました。';
			}


			// --------------------------------------------------
			//    ユーザーコミュニティ
			// --------------------------------------------------

			// $bbs_id = (isset($argument_arr['bbs_id'])) ? $argument_arr['bbs_id'] : null;
			// \Debug::dump($argument_arr, $bbs_id);

			if ($value['type1'] == 'uc' and $value['type2'] == 'announcement')
			{
				$notifications_arr[$key]['link_page_arr'] = array('type' => 'uc', 'community_id' => $value['community_id']);
				$notifications_arr[$key]['notification'] = '「' . $value['community_name'] . '」で告知が更新されました。';
				$notifications_arr[$key]['comment_long'] = $value['comment'];
				$notifications_arr[$key]['read_all'] = true;
			}

			if ($value['type1'] == 'uc' and $value['type2'] == 'mail_all')
			{
				$notifications_arr[$key]['link_page_arr'] = array('type' => 'uc', 'community_id' => $value['community_id']);
				$notifications_arr[$key]['notification'] = '「' . $value['community_name'] . '」から通知の一斉送信が行われました。';
				$notifications_arr[$key]['comment_long'] = $value['comment'];
				$notifications_arr[$key]['read_all'] = true;
			}

			if ($value['type1'] == 'uc' and $value['type2'] == 'bbs_thread')
			{
				$argument_arr = unserialize($value['argument']);
				$bbs_id = (isset($argument_arr['bbs_id'])) ? $argument_arr['bbs_id'] : null;
				$notifications_arr[$key]['link_page_arr'] = array('type' => 'uc', 'community_id' => $value['community_id'], 'bbs_thread_no' => $argument_arr['bbs_thread_no'], 'bbs_id' => $bbs_id);
				$notifications_arr[$key]['notification'] = '「' . $value['community_name'] . '」に「' . $value['title'] . '」というスレッドが立てられました。';
			}

			if ($value['type1'] == 'uc' and $value['type2'] == 'bbs_comment')
			{
				//\Debug::dump($value);
				$argument_arr = unserialize($value['argument']);
				$bbs_id = (isset($argument_arr['bbs_id'])) ? $argument_arr['bbs_id'] : null;
				$notifications_arr[$key]['link_page_arr'] = array('type' => 'uc', 'community_id' => $value['community_id'], 'bbs_thread_no' => $argument_arr['bbs_thread_no'], 'bbs_comment_no' => $argument_arr['bbs_comment_no'], 'bbs_id' => $bbs_id);
				$notifications_arr[$key]['notification'] = '「' . $value['community_name'] . '」の「' . $value['title'] . '」にコメントが書き込まれました。';
			}

			if ($value['type1'] == 'uc' and $value['type2'] == 'bbs_reply')
			{
				$argument_arr = unserialize($value['argument']);
				$bbs_id = (isset($argument_arr['bbs_id'])) ? $argument_arr['bbs_id'] : null;
				$notifications_arr[$key]['link_page_arr'] = array('type' => 'uc', 'community_id' => $value['community_id'], 'bbs_thread_no' => $argument_arr['bbs_thread_no'], 'bbs_comment_no' => $argument_arr['bbs_comment_no'], 'bbs_reply_no' => $argument_arr['bbs_reply_no'], 'bbs_id' => $bbs_id);
				$notifications_arr[$key]['notification'] = '「' . $value['community_name'] . '」の「' . $value['title'] . '」に返信が書き込まれました。';
			}


			// --------------------------------------------------
			//    未読を保存する場合、保存するIDの配列を作成する
			// --------------------------------------------------

			if ($unread)
			{
				array_push($save_unread_id_arr, $value['id']);
			}

		}


		// if ($this->user_no == 1)
		// {
			// echo "<pre>";
			// var_dump($db_notifications_arr, $notifications_arr);
			// echo "</pre>";
		// }

		//exit();


		// --------------------------------------------------
		//    未読IDを保存する
		//    当初は未読お知らせを表示してもその瞬間に既読お知らせになる扱いではなく、
		//    次に他のページを開いた瞬間に、未読IDを既読IDとして保存していたがやめて、すぐに保存する方式に。そのため処理が二度手間になっている
		//    ↑やっぱり上記はやらないことにした
		// --------------------------------------------------

		if ($save_notifications)
		{
			$result_arr = $model_notifications->save_notifications_id_reservation($save_unread_id_arr);
			$unread_id = null;

			// すぐに保存
			//$model_notifications->save_notifications_id();
		}
		else
		{
			$unread_id = implode(',', $save_unread_id_arr);
		}



		//$test = true;

		if (isset($test))
		{
			echo "<br><br><br><br>";

			// echo '$participation_community_arr';
			// var_dump($participation_community_arr);
			//
			// echo '$participation_community_secret_arr';
			// var_dump($participation_community_secret_arr);
			//
			// echo '$participation_community_no_arr';
			// var_dump($participation_community_no_arr);
			//
			// echo '$db_notifications_arr';
			// var_dump($db_notifications_arr);
			//
			// echo '$notifications_arr';
			// \Debug::dump($notifications_arr);
			//
			// echo '$total';
			// var_dump($total);
			//
			// echo '$save_unread_id_arr';
			// var_dump($save_unread_id_arr);
			//
			//
			// echo '$unread_id';
			// var_dump($unread_id);

		}


		// --------------------------------------------------
		//    コード作成
		// --------------------------------------------------

		$code = ($total > 0) ? '<div class="notifications_inside_box">' : null;

		$view_notifications = \View::forge('parts/notifications_view');
		$view_notifications->set('uri_base', $this->uri_base);
		$view_notifications->set('app_mode', $this->app_mode);
		$view_notifications->set('db_notifications_arr', $notifications_arr);
		$view_notifications->set('unread_id', $unread_id);
		$code .= $view_notifications->render();

		//var_dump($code_notifications);
		//var_dump($total, $limit);


		// --------------------------------------------------
		//    ページャー　プロフィール
		// --------------------------------------------------

		if ($total > $limit)
		{
			// ページャーの数字表示回数取得
			$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set('page', $page);
			$view_pagination->set('total', $total);
			$view_pagination->set('limit', $limit);
			$view_pagination->set('times', $pagination_times);
			$view_pagination->set('function_name', 'readNotifications');
			$view_pagination->set('argument_arr', array($this->user_no));

			$code .= '<div class="margin_top_20">' . "\n";
			$code .= $view_pagination->render();
			$code .= '</div>' . "\n";
		}

		// すべて既読にするボタン
		// if ($total > $limit)
		// {
			// $code .= '<div class="margin_top_20"><button type="submit" class="btn btn-warning ladda-button" data-style="expand-right" data-spinner-color="#000000" onclick="changeAllUnreadAlready(this)"><span class="ladda-label">すべて既読にする</span></button></div>';
		// }

		$code .= ($total > 0) ? '</div>' : null;



		//$code = null;

		return array('code' => $code, 'unread_id' => implode(',', $save_unread_id_arr));

	}







	/**
	* Wiki一覧
	*
	* @param array $condition_arr 検索条件
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function wiki_list($arr)
	{

		$language = 'ja';


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------
		/*
		$model_common = new \Model_Common();
		$model_common->agent_type = $this->agent_type;
		$model_common->user_no = $this->user_no;
		$model_common->language = $this->language;
		$model_common->uri_base = $this->uri_base;
		$model_common->uri_current = $this->uri_current;

		$model_game = new \Model_Game();
		$model_game->agent_type = $this->agent_type;
		$model_game->user_no = $this->user_no;
		$model_game->language = $this->language;
		$model_game->uri_base = $this->uri_base;
		$model_game->uri_current = $this->uri_current;

		$model_gc = new \Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;


		// --------------------------------------------------
		//   募集取得
		// --------------------------------------------------

		$limit = ($this->agent_type != 'smartphone') ? \Config::get('index_limit_recruitment') : \Config::get('index_limit_recruitment_sp');

		$db_recruitment_arr = array();
		$game_no_arr = array();

		// キーワードで検索する場合
		if (isset($arr['keyword']))
		{
			$db_game_data_arr = $model_game->search_game_name($language, $arr['keyword'], 20);

			// ゲームNo取得
			foreach ($db_game_data_arr as $key => $value)
			{
				array_push($game_no_arr, $value['game_no']);
			}

			if (count($game_no_arr) > 0)
			{

				$result_arr = $model_gc->get_recruitment(array(
					'game_no_arr' => $game_no_arr,
					'language' => $language,
					'get_total' => true,
					'page' => $arr['page'],
					'limit' => $limit
				));
				$db_recruitment_arr = $result_arr[0];
				$total = $result_arr[1];

			}

			//var_dump($game_no_arr, $db_recruitment_arr);
			//exit();
		}
		else
		{
			$result_arr = $model_gc->get_recruitment(array(
				'game_no' => null,
				'language' => $language,
				'get_total' => true,
				'page' => $arr['page'],
				'limit' => $limit
			));
			$db_recruitment_arr = $result_arr[0];
			$total = $result_arr[1];
		}



		// --------------------------------------------------
		//   ゲーム名取得
		// --------------------------------------------------

		if (count($db_recruitment_arr) > 0)
		{

			// ゲームNo取得
			$game_no_arr = array();

			foreach ($db_recruitment_arr as $key => $value)
			{
				array_push($game_no_arr, $value['game_no']);
			}

			// 重複番号削除
			$game_no_arr = array_unique($game_no_arr);
			// var_dump($db_recruitment_arr, $game_no_arr);
			// exit();
			// ゲーム名取得
			$game_names_arr = $model_game->get_game_name($language, $game_no_arr);

		}
		else if (count($game_no_arr) > 0)
		{
			// ゲーム名取得
			$game_names_arr = $model_game->get_game_name($language, $game_no_arr);
			//var_dump($game_names_arr);

			foreach ($game_names_arr as $key => $value) {

				$db_recruitment_arr[$key]['type'] = 6;
				$db_recruitment_arr[$key]['etc_title'] = null;
				$db_recruitment_arr[$key]['comment'] = '募集を投稿しよう！';
				$db_recruitment_arr[$key]['sort_date'] = '-';
				$db_recruitment_arr[$key]['game_no'] = $value['game_no'];
				$db_recruitment_arr[$key]['recruitment_id'] = null;

			}


			//return 'ゲームあり ： 0件';
		}
		else
		{
			return '検索結果 ： 0件<br>登録されていないゲームです。';
		}


		//$test = true;

		if (isset($test))
		{
			if (isset($db_game_data_arr))
			{
				echo '<br>$db_game_data_arr';
				var_dump($db_game_data_arr);
			}

			echo '<br>$db_recruitment_arr';
			var_dump($db_recruitment_arr);

			echo '<br>$total';
			var_dump($total);

			if (isset($game_no_arr))
			{
				echo '<br>$game_no_arr';
				var_dump($game_no_arr);
			}

			if (isset($game_names_arr))
			{
				echo '<br>$game_names_arr';
				var_dump($game_names_arr);
			}
		}

		//exit();


		//$all = (isset($arr['all'])) ? true : false;



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (count($db_recruitment_arr) > 0)
		{
			//\Debug::dump($db_recruitment_arr);

			$view = \View::forge('parts/recruitment_list_view');
			$view->set_safe('app_mode', $this->app_mode);
			$view->set('uri_base', $this->uri_base);
			$view->set('db_recruitment_arr', $db_recruitment_arr);
			$view->set('game_names_arr', $game_names_arr);
			//$view->set_safe('all', $all);

			$code = $view->render() . "\n";


			// --------------------------------------------------
			//   ページャー
			// --------------------------------------------------

			if ($total > $limit)
			{
				$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

				$code .= '<div class="margin_top_20">' . "\n";
				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set_safe('page', $arr['page']);
				$view_pagination->set_safe('total', $total);
				$view_pagination->set_safe('limit', $limit);
				$view_pagination->set_safe('times', $pagination_times);
				$view_pagination->set_safe('function_name', 'searchGameCommunityRecruitment');
				$view_pagination->set_safe('argument_arr', array());

				$code .= $view_pagination->render();
				$code .= '</div>' . "\n";

			}

		}
		*/


		$view = \View::forge('parts/wiki_list_view');
		//$view->set('uri_base', $this->uri_base);
		//$view->set('db_recruitment_arr', $db_recruitment_arr);
		//$view->set('game_names_arr', $game_names_arr);
		//$view->set_safe('all', $all);
		$code = $view->render();



		return $code;

	}



}
