<?php

namespace Original\Code;

class Gc
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
	* 募集
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function recruitment_menu($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = 'ja';
		$game_no = $arr['game_no'];

		if (empty($arr['db_hardware_arr']))
		{
			$model_game = new \Model_Game();
			$db_hardware_arr = $model_game->get_hardware_sort($language);
		}
		else
		{
			$db_hardware_arr = $arr['db_hardware_arr'];
		}


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/recruitment_menu_view');
		$view->set('game_no', $game_no);
		$view->set('db_hardware_arr', $db_hardware_arr);
		$arr['code'] = $view->render();


		return $arr;

	}




	/**
	* ゲームコミュニティ検索
	*
	* @param array $condition_arr 検索条件
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function search_recruitment_list($arr)
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
	* 募集
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function recruitment($arr)
	{

		$language = 'ja';


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		$model_gc = new \Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;

		$model_game = new \Model_Game();
		$model_game->agent_type = $this->agent_type;
		$model_game->user_no = $this->user_no;
		$model_game->language = $this->language;
		$model_game->uri_base = $this->uri_base;
		$model_game->uri_current = $this->uri_current;

		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;



		// --------------------------------------------------
		//   変数入力
		// --------------------------------------------------

		// if (isset($arr['game_id']))
		// {
		// 	$game_id = $arr['game_id'];
		// }
		// else
		// {
		// 	$db_game_data_arr = $model_game->get_game_data($arr['game_no'], null);
		// 	$game_id = $db_game_data_arr['id'];
		// }
		$language = 'ja';
		$db_game_data_arr = $model_game->get_game_data($arr['game_no'], null);
		$game_id = $db_game_data_arr['id'];
		if ( ! defined('GAME_NAME')) define('GAME_NAME', $db_game_data_arr['name_' . $language]);


		$game_no = (isset($arr['game_no'])) ? $arr['game_no'] : null;
		$recruitment_id = (isset($arr['recruitment_id'])) ? $arr['recruitment_id'] : null;
		$db_users_game_community_arr = (isset($arr['db_users_game_community_arr'])) ? $arr['db_users_game_community_arr'] : null;
		$login_profile_data_arr = (isset($arr['login_profile_data_arr'])) ? $arr['login_profile_data_arr'] : null;
		$db_hardware_arr = (isset($arr['db_hardware_arr'])) ? $arr['db_hardware_arr'] : null;
		$datetime_now = (isset($arr['datetime_now'])) ? $arr['datetime_now'] : null;
		$more_button = (isset($arr['more_button'])) ? $arr['more_button'] : null;
		$search_type = (isset($arr['search_type'])) ? $arr['search_type'] : null;
		$search_hardware_id_no = (isset($arr['search_hardware_id_no'])) ? $arr['search_hardware_id_no'] : null;
		$search_id_null = (isset($arr['search_id_null'])) ? $arr['search_id_null'] : null;
		$search_keyword = (isset($arr['search_keyword'])) ? $arr['search_keyword'] : null;
		$page = (isset($arr['page'])) ? (int) $arr['page'] : null;

// \Debug::dump($arr);

		// --------------------------------------------------
		//    募集
		// --------------------------------------------------

		// リミット　募集
		$limit_recruitment = ($this->agent_type != 'smartphone') ? \Config::get('limit_recruitment') : \Config::get('limit_recruitment_sp');

		// 募集取得
		if ($recruitment_id)
		{
			$db_recruitment_arr = array($model_gc->get_recruitment_appoint($recruitment_id));

			if ($db_recruitment_arr[0] === null) return null;

			//var_dump($db_recruitment_arr);
			//exit();

			// 募集総数取得
			$recruitment_total = null;
		}
		else
		{
			$db_get_recruitment_search_arr = $model_gc->get_recruitment_search($game_no, $language, $search_type, $search_hardware_id_no, $search_id_null, $search_keyword, $page, $limit_recruitment);

			//\Debug::dump($db_get_recruitment_search_arr);

			$db_recruitment_arr = $db_get_recruitment_search_arr[0];

			// 募集総数取得
			$recruitment_total = $db_get_recruitment_search_arr[1];
		}
// \Debug::dump($recruitment_total);



		$user_no_arr = array();
		$profile_no_arr = array();
		$db_recruitment_reply_arr = array();

		if (count($db_recruitment_arr) > 0)
		{

			// リミット　返信
			$limit_recruitment_reply = ($this->agent_type != 'smartphone') ? \Config::get('limit_recruitment_reply') : \Config::get('limit_recruitment_reply_sp');


			foreach ($db_recruitment_arr as $key => &$value) {


				// ------------------------------
				//    画像・動画のUnserialize
				// ------------------------------

				if (isset($value['image'])) $value['image'] = unserialize($value['image']);
				if (isset($value['movie'])) $value['movie'] = unserialize($value['movie']);


				// ------------------------------
				//    書き込みユーザー・承認ユーザーのUnserialize
				// ------------------------------

				if (isset($value['write_users'])) $value['write_users'] = unserialize($value['write_users']);
				if (isset($value['approval_users'])) $value['approval_users'] = unserialize($value['approval_users']);


				// ------------------------------
				//    ユーザーNo、プロフィールNo追加
				// ------------------------------

				if ($value['profile_no'])
				{
					array_push($profile_no_arr, $value['profile_no']);
				}
				else if ($value['user_no'])
				{
					array_push($user_no_arr, $value['user_no']);
				}


				// --------------------------------------------------
				//    返信
				// --------------------------------------------------

				if ($value['reply_total_' . $language] > 0)
				{

					// 返信取得
					$db_recruitment_reply_arr[$value['recruitment_id']] = $model_gc->get_recruitment_reply($value['recruitment_id'], 'ja', 1, $limit_recruitment_reply);
					//echo '【$db_recruitment_reply_arr】';
					//var_dump($db_recruitment_reply_arr);
					// exit();

					foreach ($db_recruitment_reply_arr[$value['recruitment_id']] as $key_reply => &$value_reply) {

						// ------------------------------
						//    画像・動画のUnserialize
						// ------------------------------

						if (isset($value_reply['image'])) $value_reply['image'] = unserialize($value_reply['image']);
						if (isset($value_reply['movie'])) $value_reply['movie'] = unserialize($value_reply['movie']);

						// echo '$key_reply';
						// var_dump($key_reply);
						// echo '$value_reply';
						// var_dump($value_reply);
						// echo '<br><br>';
						// ------------------------------
						//    ユーザーNo、プロフィールNo追加
						// ------------------------------

						if ($value_reply['profile_no'])
						{
							array_push($profile_no_arr, $value_reply['profile_no']);
						}
						else if ($value_reply['user_no'])
						{
							array_push($user_no_arr, $value_reply['user_no']);
						}

					}
					//exit();
					unset($value_reply);

				}

			}

			unset($value);

		}
		else
		{
			//\Debug::dump('aaa');
			$view = \View::forge('parts/recruitment_sample_view');
			$code = $view->render() . "\n\n";

			if (count($search_type) > 0)
			{
				if ($search_type[0] == 1) $meta_title = GAME_NAME . ' - プレイヤー募集掲示板';
				else if ($search_type[0] == 2) $meta_title = GAME_NAME . ' - フレンド募集掲示板';
				else if ($search_type[0] == 3) $meta_title = GAME_NAME . ' - メンバー募集掲示板';
				else if ($search_type[0] == 4) $meta_title = GAME_NAME . ' - 売買・交換相手募集掲示板';
				else if ($search_type[0] == 5) $meta_title = GAME_NAME . ' - その他の募集掲示板';
			}
			else
			{
				$meta_title = GAME_NAME . ' - 募集掲示板';
			}
//state.page, state.gameNo, state.recruitmentId
			$return_arr = array(
				'code' => $code,
				'state' => [
					'group' => 'rec',
					'content' => 'index'
				],
				'url' => URI_BASE . 'gc/' . $game_id . '/rec',
				'meta_title' => $meta_title,
				'meta_keywords' => GAME_NAME . ',募集掲示板',
				'meta_description' => GAME_NAME . 'のフレンド募集・メンバー募集を行うなら、Game Usersを利用しよう！返信が来ると、ブラウザ・アプリの通知やメールですぐにお知らせします！'
			);

			return $return_arr;
		}



		// ------------------------------
		//    Personal Box用プロフィール取得
		// ------------------------------

		$personal_box_user_arr = array();
		$personal_box_profile_arr = array();

		// 重複No削除
		$user_no_arr = array_unique($user_no_arr);
		$profile_no_arr = array_unique($profile_no_arr);

		if (count($user_no_arr) > 0)
		{
			$personal_box_user_arr = $model_user->get_user_data_list_in_personal_box($user_no_arr);

			foreach ($personal_box_user_arr as $key => &$value) {
				$value['notification_data'] = unserialize($value['notification_data']);
			}
			unset($value);
		}
		if (count($profile_no_arr) > 0)
		{
			$personal_box_profile_arr = $model_user->get_profile_list_in_personal_box($profile_no_arr, false);

			foreach ($personal_box_profile_arr as $key => &$value) {
				$value['notification_data'] = unserialize($value['notification_data']);
			}
			unset($value);
		}



		// --------------------------------------------------
		//    NGの処理
		// --------------------------------------------------

		$ng_user_arr = ($db_users_game_community_arr['ng_user']) ? unserialize($db_users_game_community_arr['ng_user']) : array();
		$ng_id_arr = ($db_users_game_community_arr['ng_id']) ? unserialize($db_users_game_community_arr['ng_id']) : array();


		//echo '<br><br><br>最終　　　　　$db_recruitment_reply_arr';
		//var_dump($db_recruitment_reply_arr);
		//exit();


		//$test = true;

		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

			// echo '$db_recruitment_arr';
			// var_dump($db_recruitment_arr);

			// echo '$user_no_arr';
			// var_dump($user_no_arr);
//
			// echo '$profile_no_arr';
			// var_dump($profile_no_arr);

			// if (isset($db_recruitment_reply_arr))
			// {
			// 	echo '$db_recruitment_reply_arr';
			// 	var_dump($db_recruitment_reply_arr);
			// }

			// if (isset($personal_box_user_arr))
			// {
				// echo '$personal_box_user_arr';
				// var_dump($personal_box_user_arr);
				// //var_dump(unserialize($personal_box_user_arr[1]['notification_data']));
			// }
//
			// if (isset($personal_box_profile_arr))
			// {
				// echo '$personal_box_profile_arr';
				// var_dump($personal_box_profile_arr);
			// }


			if (isset($recruitment_total))
			{
				echo '$recruitment_total';
				\Debug::dump($recruitment_total);
			}

		}

		//$recruitment_id = (isset($db_recruitment_arr)) ? $db_recruitment_arr['recruitment_id'] : null;
		//$recruitment_reply_id = (isset($db_recruitment_arr)) ? $db_recruitment_arr['recruitment_id'] : null;


		$view = \View::forge('parts/recruitment_view');
		$view->set('uri_base', $this->uri_base);
		$view->set_safe('app_mode', $this->app_mode);
		$view->set_safe('agent_type', $this->agent_type);
		$view->set_safe('login_user_no', $this->user_no);
		$view->set('datetime_now', $datetime_now);
		$view->set_safe('online_limit', \Config::get('online_limit'));
		$view->set('game_id', $game_id);
		$view->set('game_no', $game_no);
		$view->set_safe('language', 'ja');

		$view->set('db_recruitment_arr', $db_recruitment_arr);
		$view->set('db_recruitment_reply_arr', $db_recruitment_reply_arr);
		$view->set('db_hardware_arr', $db_hardware_arr);
		$view->set('personal_box_user_arr', $personal_box_user_arr);
		$view->set('personal_box_profile_arr', $personal_box_profile_arr);
		$view->set_safe('ng_user_arr', $ng_user_arr);
		$view->set_safe('ng_id_arr', $ng_id_arr);

		if (isset($recruitment_id)) $view->set_safe('appoint', true);

		$view->set_safe('more_button', $more_button);

		// ページャー
		// ページャーの数字表示回数取得
		$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');
		$view->set('pagination_page', $page);
		$view->set('pagination_total', $recruitment_total);
		$view->set('pagination_limit', $limit_recruitment);
		$view->set('pagination_times', $pagination_times);
		$view->set('pagination_function_name', 'readRecruitment');
		$view->set('pagination_argument_arr', array($game_no, 'null', 1, 1, 1));

		// 返信のページャー
		$view->set('pagination_page_reply', 1);
		$view->set('pagination_limit_reply', $limit_recruitment_reply);
		$view->set('pagination_times_reply', $pagination_times);
		$view->set_safe('pagination_function_name_reply', 'readRecruitmentReply');



		// \Debug::dump($db_recruitment_arr, $db_recruitment_reply_arr);
		// exit();


		$add_page_url = ($page === 1 or is_null($page)) ? null : '/' . $page;
		$add_page_meta_title = ($page === 1) ? null : ' Page ' . $page;
// \Debug::dump($page, $add_page_url);
		if ($more_button)
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'rec',
					'content' => 'index',
					'function' => 'readRecruitment',
					'page' => $page,
					'gameNo' => $game_no,
					'recruitmentId' => $recruitment_id
				],
				'url' => URI_BASE . 'gc/' . $game_id . '/rec/' . $recruitment_id . $add_page_url,
				'meta_title' => GAME_NAME . ' - ' . $db_recruitment_arr[0]['etc_title'] . $add_page_meta_title,
				'meta_keywords' => GAME_NAME . ',募集掲示板',
				'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $db_recruitment_arr[0]['comment'])
			);

			//exit();
		}
		else
		{

			if ($recruitment_id)
			{
				$url = URI_BASE . 'gc/' . $game_id . '/rec/' . $recruitment_id . $add_page_url;
			}
			else
			{
				$url = URI_BASE . 'gc/' . $game_id . '/rec' . $add_page_url;
			}

			if (count($search_type) > 0)
			{
				if ($search_type[0] == 1) $meta_title = GAME_NAME . ' - プレイヤー募集掲示板' . $add_page_meta_title;
				else if ($search_type[0] == 2) $meta_title = GAME_NAME . ' - フレンド募集掲示板' . $add_page_meta_title;
				else if ($search_type[0] == 3) $meta_title = GAME_NAME . ' - メンバー募集掲示板' . $add_page_meta_title;
				else if ($search_type[0] == 4) $meta_title = GAME_NAME . ' - 売買・交換相手募集掲示板' . $add_page_meta_title;
				else if ($search_type[0] == 5) $meta_title = GAME_NAME . ' - その他の募集掲示板' . $add_page_meta_title;
			}
			else
			{
				$meta_title = GAME_NAME . ' - 募集掲示板' . $add_page_meta_title;
			}

			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'rec',
					'content' => 'index',
					'function' => 'readRecruitment',
					'page' => $page,
					'gameNo' => $game_no,
					'recruitmentId' => $recruitment_id
				],
				'url' => $url,
				'meta_title' => $meta_title,
				'meta_keywords' => GAME_NAME . ',募集掲示板',
				'meta_description' => GAME_NAME . 'のフレンド募集・メンバー募集を行うなら、Game Usersを利用しよう！返信が来ると、ブラウザ・アプリの通知やメールですぐにお知らせします！'
			);
		}


		// \Debug::dump($return_arr);
		// exit();


		return $return_arr;

	}




	/**
	* 募集
	*
	* @param integer $game_no ゲームNo
	* @param string $recruitment_id 募集ID
	* @param array $db_users_game_community_arr ゲームコミュニティの設定配列
	* @param array $login_profile_data_arr プロフィール用のデータ
	* @param array $db_hardware_arr ハードウェアの情報配列
	* @param string $datetime_now 日付
	* @param boolean $more_button 他の募集を見るボタンが必要なら true
	* @param string $search_type 検索　タイプ 1,2,3
	* @param string $search_hardware_id_no 検索　ハードウェアNo 1,2,3
	* @param boolean $search_id_null 検索　ハードウェアのない、IDを検索する場合
	* @param string $search_keyword 検索　キーワード
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	/*
	public function recruitment_pre($game_no, $recruitment_id, $db_users_game_community_arr, $login_profile_data_arr, $db_hardware_arr, $datetime_now, $more_button, $search_type, $search_hardware_id_no, $search_id_null, $search_keyword, $page)
	{

		$language = 'ja';


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		$model_gc = new \Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;

		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;



		// --------------------------------------------------
		//    募集
		// --------------------------------------------------

		// リミット　募集
		$limit_recruitment = ($this->agent_type != 'smartphone') ? \Config::get('limit_recruitment') : \Config::get('limit_recruitment_sp');

		// 募集取得
		if ($recruitment_id)
		{
			$db_recruitment_arr = array($model_gc->get_recruitment_appoint($recruitment_id));

			if ($db_recruitment_arr[0] === null) return null;

			//var_dump($db_recruitment_arr);
			//exit();

			// 募集総数取得
			$recruitment_total = null;
		}
		else
		{
			//$db_recruitment_arr = $model_gc->get_recruitment($game_no, 'ja', $page, $limit_recruitment);
			//var_dump($search_type, $search_hardware_id_no, $search_id_null, $search_keyword);
			//exit();
			$db_get_recruitment_search_arr = $model_gc->get_recruitment_search($game_no, $language, $search_type, $search_hardware_id_no, $search_id_null, $search_keyword, $page, $limit_recruitment);

			$db_recruitment_arr = $db_get_recruitment_search_arr[0];

			// 募集総数取得
			$recruitment_total = $db_get_recruitment_search_arr[1];
		}


		//$recruitment_total = $model_gc->get_game_community_recruitment_total($game_no, 'ja');


		// echo '$db_recruitment_arr';
		// var_dump($db_recruitment_arr);
		// exit();

		$user_no_arr = array();
		$profile_no_arr = array();
		$db_recruitment_reply_arr = array();

		if (isset($db_recruitment_arr))
		{

			// リミット　返信
			$limit_recruitment_reply = ($this->agent_type != 'smartphone') ? \Config::get('limit_recruitment_reply') : \Config::get('limit_recruitment_reply_sp');


			foreach ($db_recruitment_arr as $key => &$value) {


				// ------------------------------
				//    画像・動画のUnserialize
				// ------------------------------

				if (isset($value['image'])) $value['image'] = unserialize($value['image']);
				if (isset($value['movie'])) $value['movie'] = unserialize($value['movie']);


				// ------------------------------
				//    書き込みユーザー・承認ユーザーのUnserialize
				// ------------------------------

				if (isset($value['write_users'])) $value['write_users'] = unserialize($value['write_users']);
				if (isset($value['approval_users'])) $value['approval_users'] = unserialize($value['approval_users']);


				// ------------------------------
				//    ユーザーNo、プロフィールNo追加
				// ------------------------------

				if ($value['profile_no'])
				{
					array_push($profile_no_arr, $value['profile_no']);
				}
				else if ($value['user_no'])
				{
					array_push($user_no_arr, $value['user_no']);
				}


				// --------------------------------------------------
				//    返信
				// --------------------------------------------------

				if ($value['reply_total_' . $language] > 0)
				{

					// 返信取得
					$db_recruitment_reply_arr[$value['recruitment_id']] = $model_gc->get_recruitment_reply($value['recruitment_id'], 'ja', 1, $limit_recruitment_reply);
					//echo '【$db_recruitment_reply_arr】';
					//var_dump($db_recruitment_reply_arr);
					// exit();

					foreach ($db_recruitment_reply_arr[$value['recruitment_id']] as $key_reply => &$value_reply) {

						// ------------------------------
						//    画像・動画のUnserialize
						// ------------------------------

						if (isset($value_reply['image'])) $value_reply['image'] = unserialize($value_reply['image']);
						if (isset($value_reply['movie'])) $value_reply['movie'] = unserialize($value_reply['movie']);

						// echo '$key_reply';
						// var_dump($key_reply);
						// echo '$value_reply';
						// var_dump($value_reply);
						// echo '<br><br>';
						// ------------------------------
						//    ユーザーNo、プロフィールNo追加
						// ------------------------------

						if ($value_reply['profile_no'])
						{
							array_push($profile_no_arr, $value_reply['profile_no']);
						}
						else if ($value_reply['user_no'])
						{
							array_push($user_no_arr, $value_reply['user_no']);
						}

					}
					//exit();
					unset($value_reply);

				}

			}

			unset($value);

		}



		// ------------------------------
		//    Personal Box用プロフィール取得
		// ------------------------------

		$personal_box_user_arr = array();
		$personal_box_profile_arr = array();

		// 重複No削除
		$user_no_arr = array_unique($user_no_arr);
		$profile_no_arr = array_unique($profile_no_arr);

		if (count($user_no_arr) > 0)
		{
			$personal_box_user_arr = $model_user->get_user_data_list_in_personal_box($user_no_arr);

			foreach ($personal_box_user_arr as $key => &$value) {
				$value['notification_data'] = unserialize($value['notification_data']);
			}
			unset($value);
		}
		if (count($profile_no_arr) > 0)
		{
			$personal_box_profile_arr = $model_user->get_profile_list_in_personal_box($profile_no_arr, false);

			foreach ($personal_box_profile_arr as $key => &$value) {
				$value['notification_data'] = unserialize($value['notification_data']);
			}
			unset($value);
		}



		// --------------------------------------------------
		//    NGの処理
		// --------------------------------------------------

		$ng_user_arr = ($db_users_game_community_arr['ng_user']) ? unserialize($db_users_game_community_arr['ng_user']) : array();
		$ng_id_arr = ($db_users_game_community_arr['ng_id']) ? unserialize($db_users_game_community_arr['ng_id']) : array();


		//echo '<br><br><br>最終　　　　　$db_recruitment_reply_arr';
		//var_dump($db_recruitment_reply_arr);
		//exit();


		//$test = true;

		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

			echo '$db_recruitment_arr';
			var_dump($db_recruitment_arr);

			// echo '$user_no_arr';
			// var_dump($user_no_arr);
//
			// echo '$profile_no_arr';
			// var_dump($profile_no_arr);

			if (isset($db_recruitment_reply_arr))
			{
				echo '$db_recruitment_reply_arr';
				var_dump($db_recruitment_reply_arr);
			}

			// if (isset($personal_box_user_arr))
			// {
				// echo '$personal_box_user_arr';
				// var_dump($personal_box_user_arr);
				// //var_dump(unserialize($personal_box_user_arr[1]['notification_data']));
			// }
//
			// if (isset($personal_box_profile_arr))
			// {
				// echo '$personal_box_profile_arr';
				// var_dump($personal_box_profile_arr);
			// }

		}

		//$recruitment_id = (isset($db_recruitment_arr)) ? $db_recruitment_arr['recruitment_id'] : null;
		//$recruitment_reply_id = (isset($db_recruitment_arr)) ? $db_recruitment_arr['recruitment_id'] : null;


		$view = \View::forge('parts/recruitment_view');
		$view->set('uri_base', $this->uri_base);
		$view->set_safe('app_mode', $this->app_mode);
		$view->set_safe('agent_type', $this->agent_type);
		$view->set_safe('login_user_no', $this->user_no);
		$view->set('datetime_now', $datetime_now);
		$view->set_safe('online_limit', \Config::get('online_limit'));
		$view->set('game_no', $game_no);
		$view->set_safe('language', 'ja');

		$view->set('db_recruitment_arr', $db_recruitment_arr);
		$view->set('db_recruitment_reply_arr', $db_recruitment_reply_arr);
		$view->set('db_hardware_arr', $db_hardware_arr);
		$view->set('personal_box_user_arr', $personal_box_user_arr);
		$view->set('personal_box_profile_arr', $personal_box_profile_arr);
		$view->set_safe('ng_user_arr', $ng_user_arr);
		$view->set_safe('ng_id_arr', $ng_id_arr);

		if (isset($recruitment_id)) $view->set_safe('appoint', true);

		$view->set_safe('more_button', $more_button);

		// ページャー
		// ページャーの数字表示回数取得
		$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');
		$view->set('pagination_page', $page);
		$view->set('pagination_total', $recruitment_total);
		$view->set('pagination_limit', $limit_recruitment);
		$view->set('pagination_times', $pagination_times);
		$view->set('pagination_function_name', 'readRecruitment');
		$view->set('pagination_argument_arr', array($game_no));

		// 返信のページャー
		$view->set('pagination_page_reply', 1);
		//$view->set('pagination_total_reply', $recruitment_total);
		$view->set('pagination_limit_reply', $limit_recruitment_reply);
		$view->set('pagination_times_reply', $pagination_times);
		$view->set_safe('pagination_function_name_reply', 'readRecruitmentReply');
		//$view->set('pagination_argument_arr_reply', array($game_no));

		$code = $view->render();

		return $code;

	}
	*/


	/**
	* 募集　返信
	*
	* @param array $db_community_arr コミュニティ情報
	* @param array $authority_arr 権限
	* @param array $login_profile_data_arr ログインユーザーのプロフィール
	* @param array $page ページ
	* @return string HTMLコード
	*/
	public function recruitment_reply($game_no, $recruitment_id, $db_users_game_community_arr, $login_profile_data_arr, $db_hardware_arr, $datetime_now, $page)
	{

		$language = 'ja';


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		$model_gc = new \Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;

		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;



		// --------------------------------------------------
		//    募集
		// --------------------------------------------------

		// リミット　募集
		//$limit_recruitment = ($this->agent_type != 'smartphone') ? \Config::get('limit_recruitment') : \Config::get('limit_recruitment_sp');

		// 募集取得
		if ($recruitment_id)
		{
			$db_recruitment_arr = array($model_gc->get_recruitment_appoint($recruitment_id));
		}
		else
		{
			exit();
		}


		$user_no_arr = array();
		$profile_no_arr = array();
		$db_recruitment_reply_arr = array();
		$deadline = false;

		if (isset($db_recruitment_arr))
		{

			// リミット　返信
			$limit_recruitment_reply = ($this->agent_type != 'smartphone') ? \Config::get('limit_recruitment_reply') : \Config::get('limit_recruitment_reply_sp');


			foreach ($db_recruitment_arr as $key => &$value) {

				// ------------------------------
				//    書き込みユーザー・承認ユーザーのUnserialize
				// ------------------------------

				if (isset($value['write_users'])) $value['write_users'] = unserialize($value['write_users']);
				if (isset($value['approval_users'])) $value['approval_users'] = unserialize($value['approval_users']);


				// ------------------------------
				//    締め切り
				// ------------------------------

				$datetime_limit = new \DateTime($value['limit_date']);
				$datetime_now_obj = new \DateTime();
				if ($datetime_now_obj > $datetime_limit) $deadline = true;


				// --------------------------------------------------
				//    返信
				// --------------------------------------------------

				if ($value['reply_total_' . $language] > 0)
				{

					// 返信取得
					$db_recruitment_reply_arr[$value['recruitment_id']] = $model_gc->get_recruitment_reply($value['recruitment_id'], $language, $page, $limit_recruitment_reply);
					//echo '【$db_recruitment_reply_arr】';
					//var_dump($db_recruitment_reply_arr);
					// exit();

					foreach ($db_recruitment_reply_arr[$value['recruitment_id']] as $key_reply => &$value_reply) {

						// ------------------------------
						//    画像・動画のUnserialize
						// ------------------------------

						if (isset($value_reply['image'])) $value_reply['image'] = unserialize($value_reply['image']);
						if (isset($value_reply['movie'])) $value_reply['movie'] = unserialize($value_reply['movie']);

						// echo '$key_reply';
						// var_dump($key_reply);
						// echo '$value_reply';
						// var_dump($value_reply);
						// echo '<br><br>';
						// ------------------------------
						//    ユーザーNo、プロフィールNo追加
						// ------------------------------

						if ($value_reply['profile_no'])
						{
							array_push($profile_no_arr, $value_reply['profile_no']);
						}
						else if ($value_reply['user_no'])
						{
							array_push($user_no_arr, $value_reply['user_no']);
						}

					}
					//exit();
					unset($value_reply);

				}

			}

			unset($value);

		}



		// ------------------------------
		//    Personal Box用プロフィール取得
		// ------------------------------

		$personal_box_user_arr = array();
		$personal_box_profile_arr = array();

		// 重複No削除
		$user_no_arr = array_unique($user_no_arr);
		$profile_no_arr = array_unique($profile_no_arr);

		if (count($user_no_arr) > 0)
		{
			$personal_box_user_arr = $model_user->get_user_data_list_in_personal_box($user_no_arr);

			foreach ($personal_box_user_arr as $key => &$value) {
				$value['notification_data'] = unserialize($value['notification_data']);
			}
			unset($value);
		}
		if (count($profile_no_arr) > 0)
		{
			$personal_box_profile_arr = $model_user->get_profile_list_in_personal_box($profile_no_arr, false);

			foreach ($personal_box_profile_arr as $key => &$value) {
				$value['notification_data'] = unserialize($value['notification_data']);
			}
			unset($value);
		}



		// --------------------------------------------------
		//    NGの処理
		// --------------------------------------------------

		$ng_user_arr = ($db_users_game_community_arr['ng_user']) ? unserialize($db_users_game_community_arr['ng_user']) : array();
		$ng_id_arr = ($db_users_game_community_arr['ng_id']) ? unserialize($db_users_game_community_arr['ng_id']) : array();


		// --------------------------------------------------
		//    書き込みユーザー＆承認ユーザー
		// --------------------------------------------------

		$write_users_arr = ($db_recruitment_arr[0]['write_users']) ? $db_recruitment_arr[0]['write_users'] : array();
		$approval_users_arr = ($db_recruitment_arr[0]['approval_users']) ? $db_recruitment_arr[0]['approval_users'] : array();



		//$test = true;

		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

			echo '$db_recruitment_arr';
			var_dump($db_recruitment_arr);

			// echo '$user_no_arr';
			// var_dump($user_no_arr);
//
			// echo '$profile_no_arr';
			// var_dump($profile_no_arr);

			if (isset($db_recruitment_reply_arr))
			{
				echo '$db_recruitment_reply_arr';
				var_dump($db_recruitment_reply_arr);
			}

			// if (isset($personal_box_user_arr))
			// {
				// echo '$personal_box_user_arr';
				// var_dump($personal_box_user_arr);
				// //var_dump(unserialize($personal_box_user_arr[1]['notification_data']));
			// }
//
			// if (isset($personal_box_profile_arr))
			// {
				// echo '$personal_box_profile_arr';
				// var_dump($personal_box_profile_arr);
			// }

		}

		//exit();


		$view_reply = \View::forge('parts/recruitment_reply_view');
		$view_reply->set('uri_base', $this->uri_base);
		$view_reply->set('app_mode', $this->app_mode);
		$view_reply->set_safe('agent_type', $this->agent_type);
		$view_reply->set('login_user_no', $this->user_no);
		$view_reply->set('datetime_now', $datetime_now);
		$view_reply->set('online_limit', \Config::get('online_limit'));
		$view_reply->set('game_no', $game_no);

		$view_reply->set('recruitment_id', $db_recruitment_arr[0]['recruitment_id']);
		$view_reply->set('open_type', $db_recruitment_arr[0]['open_type']);
		$view_reply->set('recruitment_user_no', $db_recruitment_arr[0]['user_no']);
		$view_reply->set('db_recruitment_reply_arr', $db_recruitment_reply_arr);
		$view_reply->set('db_hardware_arr', $db_hardware_arr);
		$view_reply->set('personal_box_user_arr', $personal_box_user_arr);
		$view_reply->set('personal_box_profile_arr', $personal_box_profile_arr);
		$view_reply->set('ng_user_arr', $ng_user_arr);
		$view_reply->set('ng_id_arr', $ng_id_arr);
		$view_reply->set('write_users_arr', $write_users_arr);
		$view_reply->set('approval_users_arr', $approval_users_arr);
		$view_reply->set_safe('deadline', $deadline);

		// ページャー
		// ページャーの数字表示回数取得
		$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');
		$view_reply->set('pagination_page_reply', $page);
		$view_reply->set('pagination_total_reply', $db_recruitment_arr[0]['reply_total_' . $language]);
		$view_reply->set('pagination_limit_reply', $limit_recruitment_reply);
		$view_reply->set('pagination_times_reply', $pagination_times);
		$view_reply->set('pagination_function_name_reply', 'readRecruitmentReply');
		$view_reply->set('pagination_argument_arr_reply', array($game_no, "'" . $db_recruitment_arr[0]['recruitment_id'] . "'"));

		$code = $view_reply->render();

		return $code;

	}




	/**
	* 募集・返信　新規・編集フォーム
	*
	* @param array $db_community_arr コミュニティ情報
	* @param array $authority_arr 権限
	* @param array $login_profile_data_arr ログインユーザーのプロフィール
	* @param array $page ページ
	* @return string HTMLコード
	*/
	public function form_recruitment($game_no, $form_type, $recruitment_id, $recruitment_reply_id, $login_profile_data_arr, $db_hardware_arr, $datetime_now)
	{

		// --------------------------------------------------
		//    管理者の場合は、ハンドルネームを入力できるようにする
		// --------------------------------------------------

		if (\Auth::member(100)) $this->user_no = null;
		if (\Auth::member(100)) $login_profile_data_arr = null;


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

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

		$original_code_common = new \Original\Code\Common();
		$original_code_common->user_no = USER_NO;
		$code_user_terms = $original_code_common->user_terms();


		// --------------------------------------------------
		//    データ取得
		// --------------------------------------------------

		// ID取得
		$result_arr = $model_game->get_game_id(array('game_no' => $game_no));
		$db_id_arr = $result_arr[0];


		// 編集する場合、募集・返信のデータ取得
		$data_arr = null;
		$recruitment_open_type = null;

		if ($form_type == 'recruitment_edit' and $recruitment_id)
		{
			$data_arr = $model_gc->get_recruitment_appoint($recruitment_id);
		}
		else if ($form_type == 'reply_new')
		{
			$recruitment_open_type = $model_gc->get_recruitment_appoint($recruitment_id)['open_type'];
			if ($recruitment_reply_id) $comment_to_arr = $model_gc->get_recruitment_reply_appoint($recruitment_reply_id);
		}
		else if ($form_type == 'reply_edit')
		{
			$recruitment_open_type = $model_gc->get_recruitment_appoint($recruitment_id)['open_type'];
			if ($recruitment_reply_id) $data_arr = $model_gc->get_recruitment_reply_appoint($recruitment_reply_id);
		}

		if (isset($data_arr['image'])) $data_arr['image'] = unserialize($data_arr['image']);
		if (isset($data_arr['movie'])) $data_arr['movie'] = unserialize($data_arr['movie']);



		// ○○さんへ追加
		$comment_to = null;
		$specific_recruitment_reply_id = null;

		if (isset($comment_to_arr))
		{
			$model_user = new \Model_User();
			$model_user->agent_type = $this->agent_type;
			$model_user->user_no = $this->user_no;
			$model_user->language = $this->language;
			$model_user->uri_base = $this->uri_base;
			$model_user->uri_current = $this->uri_current;

			if ($comment_to_arr['profile_no'])
			{
				$db_profile_arr = $model_user->get_profile($comment_to_arr['profile_no']);
				$comment_to_handle_name = $db_profile_arr['handle_name'];
			}
			else if ($comment_to_arr['user_no'])
			{
				$db_profile_arr = $model_user->get_user_data($comment_to_arr['user_no'], null);
				$comment_to_handle_name = $db_profile_arr['handle_name'];
			}
			else
			{
				$comment_to_handle_name = $comment_to_arr['handle_name'];
			}

			$comment_to = $comment_to_handle_name . 'さんへ' . "\n\n";


			// 特定の返信に対する返信
			$specific_recruitment_reply_id = $comment_to_arr['recruitment_reply_id'];

		}



		//$test = true;

		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

			echo '$db_id_arr';
			var_dump($db_id_arr);

			if (isset($db_recruitment_arr))
			{
				echo '$db_recruitment_arr';
				var_dump($db_recruitment_arr);
			}

			if (isset($db_recruitment_reply_arr))
			{
				echo '$db_recruitment_reply_arr';
				var_dump($db_recruitment_reply_arr);
			}

		}


		$func_name = 'saveRecruitment';
		$func_argument_arr = null;

		if ($form_type != 'recruitment_new')
		{
			$func_name_return = 'hideRecruitmentForm';
			$func_argument_return_arr = array("'" . $recruitment_id . "'");

			$func_name_delete = 'deleteRecruitment';
			$func_argument_delete_arr = array("'" . $recruitment_id . "'", "'" . $recruitment_reply_id . "'");
		}
		else
		{
			$func_name_return = null;
			$func_argument_return_arr = null;

			$func_name_delete = null;
			$func_argument_delete_arr = null;
		}


		$mode_reply = ($form_type == 'reply_new' or $form_type == 'reply_edit') ? true : null;


		//$form_type = 'recruitment_new';
		//$recruitment_id = null;
		//$recruitment_reply_id = null;


		// --------------------------------------------------
		//    ID選択コード作成
		// --------------------------------------------------

		$temp_arr = array(
			'game_no' => $game_no,
			'form_type' => $form_type,
			'recruitment_id' => $recruitment_id,
			'recruitment_reply_id' => $recruitment_reply_id,
			'page' => 1
		);

		$code_selecte_id_arr = $this->form_recruitment_select_id($temp_arr);
		$code_selecte_id = $code_selecte_id_arr['code'];
		$id_hardware_no_id_arr = $code_selecte_id_arr['id_hardware_no_id_arr'];

		//var_dump($id_hardware_no_id_arr);


		// --------------------------------------------------
		//    簡単入室ボタン　フォーム
		// --------------------------------------------------




		$view = \View::forge('parts/form_recruitment_view');
		$view->set('uri_base', $this->uri_base);
		$view->set('datetime_now', $datetime_now);
		$view->set_safe('app_mode', $this->app_mode);
		$view->set_safe('login_user_no', $this->user_no);
		$view->set('profile_arr', $login_profile_data_arr);
		$view->set_safe('online_limit', \Config::get('online_limit'));
		$view->set('db_hardware_arr', $db_hardware_arr);
		$view->set('db_id_arr', $db_id_arr);
		$view->set_safe('anonymity', true);
		$view->set('func_name', $func_name);
		$view->set('func_argument_arr', $func_argument_arr);
		$view->set('func_name_return', $func_name_return);
		$view->set('func_argument_return_arr', $func_argument_return_arr);
		$view->set('func_name_delete', $func_name_delete);
		$view->set('func_argument_delete_arr', $func_argument_delete_arr);

		$view->set('game_no', $game_no);
		$view->set('form_type', $form_type);
		$view->set('recruitment_id', $recruitment_id);
		$view->set('recruitment_reply_id', $recruitment_reply_id);
		$view->set('specific_recruitment_reply_id', $specific_recruitment_reply_id);
		$view->set_safe('recruitment_open_type', $recruitment_open_type);

		$view->set('data_arr', $data_arr);

		$view->set_safe('mode_reply', $mode_reply);
		$view->set('comment_to', $comment_to);

		$view->set_safe('code_selecte_id', $code_selecte_id);
		$view->set('id_hardware_no_id_arr', $id_hardware_no_id_arr);

		$view->set_safe('code_user_terms', $code_user_terms);


		$code = $view->render();

		return $code;

	}



	/**
	* 募集・返信　新規・編集フォーム
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function form_recruitment_select_id($arr)
	{


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

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
		//   変数入力
		// --------------------------------------------------

		$game_no = (isset($arr['game_no'])) ? $arr['game_no'] : null;
		$data_arr = (isset($arr['data_arr'])) ? $arr['data_arr'] : null;
		$db_hardware_arr = (isset($arr['db_hardware_arr'])) ? $arr['db_hardware_arr'] : null;
		$form_type = (isset($arr['form_type'])) ? $arr['form_type'] : null;
		$recruitment_id = (isset($arr['recruitment_id'])) ? $arr['recruitment_id'] : null;
		$recruitment_reply_id = (isset($arr['recruitment_reply_id'])) ? $arr['recruitment_reply_id'] : null;
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		//$page = 2;

		// --------------------------------------------------
		//    必要な情報がない場合は処理中断　nullを返す
		// --------------------------------------------------

		if ( ! $game_no or ! $form_type or ! $page)
		{
			return array('code' => null, 'id_hardware_no_id_arr' => array());
		}


		// --------------------------------------------------
		//    リミット取得
		// --------------------------------------------------

		$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_form_select_id') : \Config::get('limit_form_select_id_sp');



		// --------------------------------------------------
		//    データ取得　data_arr
		// --------------------------------------------------

		if ($data_arr)
		{

		}
		if ($form_type == 'recruitment_edit' and $recruitment_id)
		{
			$data_arr = $model_gc->get_recruitment_appoint($recruitment_id);
		}
		else if ($form_type == 'reply_edit' and $recruitment_reply_id)
		{
			$data_arr = $model_gc->get_recruitment_reply_appoint($recruitment_reply_id);
		}


		// --------------------------------------------------
		//    データ取得　ハードウェア
		// --------------------------------------------------

		if ( ! $db_hardware_arr)
		{
			$language = 'ja';
			$db_hardware_arr = $model_game->get_hardware_sort($language);
			//var_dump($db_hardware_arr);
		}


		// --------------------------------------------------
		//    データ取得　ID
		// --------------------------------------------------

		// 無理矢理対応するために作った変数
		$hurriedly_page = 1;
		$hurriedly_limit = 10000;

		// ID取得
		$temp_arr = array(
			'game_no' => $game_no,
			'page' => $hurriedly_page,
			'limit' => $hurriedly_limit,
		);

		$result_arr = $model_game->get_game_id($temp_arr);
		$db_id_arr = $result_arr[0];
		$total = $result_arr[1];

		unset($temp_arr);



		// --------------------------------------------------
		//    inputフォームのchecked用、配列作成
		// --------------------------------------------------

		$id_hardware_no_id_arr = array();
		$data_id_hardware_no_id_arr = array();

		for ($i=1; $i <= 3; $i++)
		{
			if (isset($data_arr['id_' . $i]))
			{
				array_push($id_hardware_no_id_arr, array($data_arr['id_hardware_no_' . $i] => $data_arr['id_' . $i]));
				//array_push($data_id_hardware_no_id_arr, "'" . $data_arr['id_hardware_no_' . $i] . '_' .  $data_arr['id_' . $i] . "'");
				array_push($data_id_hardware_no_id_arr, $data_arr['id_hardware_no_' . $i] . '_' .  $data_arr['id_' . $i]);
			}
		}



		//$test = true;

		if (isset($test))
		{

			echo '最初に入力されているデータ　$id_hardware_no_id_arr';
			var_dump($id_hardware_no_id_arr);

			echo '最初に入力されているデータ　$data_id_hardware_no_id_arr';
			var_dump($data_id_hardware_no_id_arr);

			echo '<br><br><br><br>';

		}


		// --------------------------------------------------
		//    ビューに送るデータ処理
		// --------------------------------------------------

		$view_arr = array();

		if (count($db_id_arr) > 0)
		{

			foreach ($db_id_arr as $key => $value)
			{

				// --------------------------------------------------
				//    inputフォームのchecked　$id_hardware_no_id_arrの処理
				// --------------------------------------------------

				$view_arr[$key]['checked'] = null;

				foreach ($id_hardware_no_id_arr as $key2 => $value2)
				{

					if (isset($value2[$value['hardware_no']], $value['id']))
					{
						if ($value2[$value['hardware_no']] == $value['id'])
						{
							$view_arr[$key]['checked'] = ' checked';

							// 選択されているIDは、ID入力の方に送らない
							unset($id_hardware_no_id_arr[$key2]);
						}
					}

				}

				// unset() で要素を削除した後、必要があれば array_values() で歯抜けになったのを詰めます。
				$id_hardware_no_id_arr = array_values($id_hardware_no_id_arr);



				// --------------------------------------------------
				//    hardware_no / hardware_name
				// --------------------------------------------------

				if (isset($db_hardware_arr[$value['hardware_no']]))
				{
					$view_arr[$key]['hardware_name'] = $db_hardware_arr[$value['hardware_no']]['abbreviation'];
					$view_arr[$key]['hardware_no'] = $value['hardware_no'];
				}
				else
				{
					$view_arr[$key]['hardware_name'] = 'ID';
					$view_arr[$key]['hardware_no'] = null;
				}


				// --------------------------------------------------
				//    ID
				// --------------------------------------------------

				$view_arr[$key]['id'] = $value['id'];


			}

		}


		// --------------------------------------------------
		//    ID選択用　javascript　data属性
		//    選択しているIDの情報をjavascriptに送る
		// --------------------------------------------------

		//$data_id_hardware_no_id_arr = array('5_aaaa', '8_123456', '90_bbbb');
		//$data_id_hardware_no_id_arr = array();

		foreach ($id_hardware_no_id_arr as $key => $value)
		{
			// echo 'aaaaaaaaaaaaaaa';
			// var_dump($value);

			$temp_value = key($value) . '_' . $value[key($value)];
			$temp_key = array_search($temp_value, $data_id_hardware_no_id_arr);

			//var_dump($temp_value, $temp_key);

			if ($temp_key !== false)
			{
				unset($data_id_hardware_no_id_arr[$temp_key]);
			}

		}

		// unset() で要素を削除した後、必要があれば array_values() で歯抜けになったのを詰めます。
		$data_id_hardware_no_id_arr = array_values($data_id_hardware_no_id_arr);



		if (count($data_id_hardware_no_id_arr) > 0)
		{
			//$data_id_hardware_no_id = '[' . implode(',', $data_id_hardware_no_id_arr) . ']';
			$data_id_hardware_no_id = implode('/-*-/', $data_id_hardware_no_id_arr);
		}
		else
		{
			//$data_id_hardware_no_id = '[]';
			$data_id_hardware_no_id = null;
		}



		// --------------------------------------------------
		//    Viewを分割
		//    game_idを全件取得してから分割するというめんどくさい方法で処理している
		// --------------------------------------------------

		$offset = $limit * ($page - 1);
		$view_arr = array_slice($view_arr, $offset, $limit);



		if (isset($test))
		{
			//echo '$data_arr';
			//var_dump($data_arr);

			//echo '$db_id_arr';
			//var_dump($db_id_arr);

			//echo '$total';
			//var_dump($total);

			echo 'ID入力用のデータ　$id_hardware_no_id_arr';
			var_dump($id_hardware_no_id_arr);

			echo '$data_id_hardware_no_id_arr';
			var_dump($data_id_hardware_no_id_arr);

			echo '$view_arr';
			var_dump($view_arr);
		}


		//exit();


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// --------------------------------------------------
		//   ページャー
		// --------------------------------------------------

		if ($total > $limit)
		{
			$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$code_pagination = '<div class="margin_top_20">' . "\n";
			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set_safe('page', $page);
			$view_pagination->set_safe('total', $total);
			$view_pagination->set_safe('limit', $limit);
			$view_pagination->set_safe('times', $pagination_times);
			$view_pagination->set_safe('function_name', 'readFormRecruitmentSelectGameId');

			// $argument_form_type = "'" . $form_type . "'";
			// $argument_recruitment_id = ($recruitment_id) ? "'" . $recruitment_id . "'" : "''";
			// $argument_recruitment_reply_id = ($recruitment_reply_id) ? "'" . $recruitment_reply_id . "'" : "''";
//
			// $view_pagination->set_safe('argument_arr', array($game_no, $argument_form_type, $argument_recruitment_id, $argument_recruitment_reply_id));

			$view_pagination->set_safe('argument_arr', array());

			$code_pagination .= $view_pagination->render();
			$code_pagination .= '</div>' . "\n";
		}
		else
		{
			$code_pagination = null;
		}


		$view = \View::forge('parts/form_recruitment_select_game_id_view');
		$view->set('view_arr', $view_arr);
		//$view->set_safe('id_hardware_no_id_arr', $id_hardware_no_id_arr);
		$view->set('data_id_hardware_no_id', $data_id_hardware_no_id);
		$view->set_safe('code_pagination', $code_pagination);

		$code = $view->render();

		//echo $code;
		return array('code' => $code, 'id_hardware_no_id_arr' => $id_hardware_no_id_arr);

	}




	/**
	* プロフィール選択フォーム
	*
	* @param integer $user_no ユーザーNo
	* @param integer $online_limit オンラインリミット
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function gc_select_profile_form($game_no, $all, $page)
	{


		$code = null;

		if (empty($this->user_no))
		{
			return $code;
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

		$model_gc = new \Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;


		// --------------------------------------------------
		//    設定　データ取得
		// --------------------------------------------------

		$db_user_game_community = $model_gc->get_user_game_community();
		$config_arr = (isset($db_user_game_community['config'])) ? unserialize($db_user_game_community['config']) : null;


		// --------------------------------------------------
		//    オンラインリミット
		// --------------------------------------------------

		$online_limit = \Config::get('online_limit');


		// --------------------------------------------------
		//    プレイヤープロフィール　データ取得
		// --------------------------------------------------

		$db_users_data_arr = $model_user->get_user_data($this->user_no, null);


		// --------------------------------------------------
		//    追加プロフィール　データ取得
		// --------------------------------------------------

		// Limit取得
		$limit_profile = ($this->agent_type != 'smartphone') ? \Config::get('limit_select_profile_form') : \Config::get('limit_select_profile_form_sp');

		$limit_profile = 4;

		// プロフィールデータ・総数取得
		$result_arr = $model_user->get_profile_list_game_no($this->user_no, $game_no, $page, $limit_profile);
		$db_profile_arr = $result_arr[0];
		$total_profile = $result_arr[1];


		//var_dump($config, $db_users_data_arr, $db_profile_arr, $total_profile);
		//exit();


		// --------------------------------------------------
		//    プロフィール合成
		// --------------------------------------------------

		if ($page == 1) array_unshift($db_profile_arr, $db_users_data_arr);

		//$config_arr = array(1 => array('profile_no' => 1));
		//$config_arr = array(1 => array('user_no' => 1));



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/gc_select_profile_form_view');

		$view->set_safe('app_mode', $this->app_mode);
		$view->set('uri_base', $this->uri_base);
		$view->set('game_no', $game_no);
		$view->set_safe('online_limit', $online_limit);
		$view->set_safe('all', $all);

		$view->set('db_profile_arr', $db_profile_arr);
		$view->set('config_arr', $config_arr);

		// ページャーの数字表示回数取得
		$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

		$view->set('pagination_page', $page);
		$view->set('pagination_total', $total_profile);
		$view->set('pagination_limit', $limit_profile);
		$view->set('pagination_times', $pagination_times);
		$view->set('pagination_function_name', 'readGcSelectProfileForm');
		$view->set('pagination_argument_arr', array($game_no));

		$code = $view->render();

		//echo $code;

		return $code;

	}




	/**
	* ゲームID登録・編集フォーム
	*
	* @param integer $user_no ユーザーNo
	* @param integer $online_limit オンラインリミット
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function edit_game_id_form($game_no, $all, $page)
	{


		$code = null;

		if (empty($this->user_no))
		{
			return $code;
		}


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		// インスタンス作成
		$model_game = new \Model_Game();
		$model_game->agent_type = $this->agent_type;
		$model_game->user_no = $this->user_no;
		$model_game->language = $this->language;
		$model_game->uri_base = $this->uri_base;
		$model_game->uri_current = $this->uri_current;


		// --------------------------------------------------
		//    データ取得
		// --------------------------------------------------

		// --------------------------------------------------
		//    ID
		// --------------------------------------------------

		$limit_edit_game_id_form = \Config::get('limit_edit_game_id_form');
		$result_arr = $model_game->get_game_id(array('page' => $page, 'limit' => $limit_edit_game_id_form));
		$db_id_arr = $result_arr[0];
		$total = $result_arr[1];

		//if (count($db_id_arr) < 5)
		//{
			$limit_for = 10 - count($db_id_arr);

			for ($i=0; $i < $limit_for; $i++)
			{
				array_push($db_id_arr, array('game_id_no' => null, 'sort_no' => null, 'game_no' => null, 'hardware_no' => null, 'id' => null));
			}
		//}


		// --------------------------------------------------
		//    ハードウェア
		// --------------------------------------------------

		$language = 'ja';
		$db_hardware_arr = $model_game->get_hardware_sort($language);


		// --------------------------------------------------
		//   ゲームデータ
		// --------------------------------------------------

		$game_no_arr = array($game_no);

		foreach ($db_id_arr as $key => $value) {
			if ($value['game_no']) array_push($game_no_arr, $value['game_no']);
		}

		$db_game_data_arr = $model_game->get_game_name($language, $game_no_arr);

		// if ($game_no)
		// {
			// $db_game_data_arr = $model_game->get_game_name($language, $game_no_arr);
//
			// //$result_arr = $model_game->get_game_data($game_no, null);
			// //$game_name = $result_arr['name_' . $language];
		// }
		// else
		// {
			// $game_name = null;
		// }

		//var_dump($db_id_arr, $db_game_data_arr);
		//exit();


		// --------------------------------------------------
		//    オンラインリミット
		// --------------------------------------------------

		$online_limit = \Config::get('online_limit');



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/edit_game_id_form_view');

		$view->set_safe('app_mode', $this->app_mode);
		$view->set('uri_base', $this->uri_base);
		$view->set('game_no', $game_no);
		$view->set_safe('online_limit', $online_limit);
		$view->set_safe('all', $all);

		$view->set('db_id_arr', $db_id_arr);
		$view->set('db_hardware_arr', $db_hardware_arr);
		$view->set('db_game_data_arr', $db_game_data_arr);


		// ページャーの数字表示回数取得
		$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

		$view->set('pagination_page', $page);
		$view->set('pagination_total', $total);
		$view->set('pagination_limit', $limit_edit_game_id_form);
		$view->set('pagination_times', $pagination_times);
		$view->set('pagination_function_name', 'readEditGameIdForm');
		$view->set('pagination_argument_arr', array());

		$code = $view->render();

		//echo $code;

		return $code;

	}



	/**
	* 参加するボタン
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function chain_button($arr)
	{

		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

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
		//   変数入力
		// --------------------------------------------------

		$game_no = (isset($arr['game_no'])) ? $arr['game_no'] : null;
		$data_arr = (isset($arr['data_arr'])) ? $arr['data_arr'] : null;
		$db_hardware_arr = (isset($arr['db_hardware_arr'])) ? $arr['db_hardware_arr'] : null;
		$form_type = (isset($arr['form_type'])) ? $arr['form_type'] : null;
		$recruitment_id = (isset($arr['recruitment_id'])) ? $arr['recruitment_id'] : null;
		$recruitment_reply_id = (isset($arr['recruitment_reply_id'])) ? $arr['recruitment_reply_id'] : null;
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		//$page = 2;

		// --------------------------------------------------
		//    必要な情報がない場合は処理中断　nullを返す
		// --------------------------------------------------

		if ( ! $game_no or ! $form_type or ! $page)
		{
			return array('code' => null, 'id_hardware_no_id_arr' => array());
		}


		// --------------------------------------------------
		//    リミット取得
		// --------------------------------------------------

		$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_form_select_id') : \Config::get('limit_form_select_id_sp');



		// --------------------------------------------------
		//    データ取得　data_arr
		// --------------------------------------------------

		if ($data_arr)
		{

		}
		if ($form_type == 'recruitment_edit' and $recruitment_id)
		{
			$data_arr = $model_gc->get_recruitment_appoint($recruitment_id);
		}
		else if ($form_type == 'reply_edit' and $recruitment_reply_id)
		{
			$data_arr = $model_gc->get_recruitment_reply_appoint($recruitment_reply_id);
		}


		// --------------------------------------------------
		//    データ取得　ハードウェア
		// --------------------------------------------------

		if ( ! $db_hardware_arr)
		{
			$language = 'ja';
			$db_hardware_arr = $model_game->get_hardware_sort($language);
			//var_dump($db_hardware_arr);
		}


		// --------------------------------------------------
		//    データ取得　ID
		// --------------------------------------------------

		// 無理矢理対応するために作った変数
		$hurriedly_page = 1;
		$hurriedly_limit = 10000;

		// ID取得
		$temp_arr = array(
			'game_no' => $game_no,
			'page' => $hurriedly_page,
			'limit' => $hurriedly_limit,
		);

		$result_arr = $model_game->get_game_id($temp_arr);
		$db_id_arr = $result_arr[0];
		$total = $result_arr[1];

		unset($temp_arr);



		// --------------------------------------------------
		//    inputフォームのchecked用、配列作成
		// --------------------------------------------------

		$id_hardware_no_id_arr = array();
		$data_id_hardware_no_id_arr = array();

		for ($i=1; $i <= 3; $i++)
		{
			if (isset($data_arr['id_' . $i]))
			{
				array_push($id_hardware_no_id_arr, array($data_arr['id_hardware_no_' . $i] => $data_arr['id_' . $i]));
				//array_push($data_id_hardware_no_id_arr, "'" . $data_arr['id_hardware_no_' . $i] . '_' .  $data_arr['id_' . $i] . "'");
				array_push($data_id_hardware_no_id_arr, $data_arr['id_hardware_no_' . $i] . '_' .  $data_arr['id_' . $i]);
			}
		}



		//$test = true;

		if (isset($test))
		{

			echo '最初に入力されているデータ　$id_hardware_no_id_arr';
			var_dump($id_hardware_no_id_arr);

			echo '最初に入力されているデータ　$data_id_hardware_no_id_arr';
			var_dump($data_id_hardware_no_id_arr);

			echo '<br><br><br><br>';

		}


		// --------------------------------------------------
		//    ビューに送るデータ処理
		// --------------------------------------------------

		$view_arr = array();

		if (count($db_id_arr) > 0)
		{

			foreach ($db_id_arr as $key => $value)
			{

				// --------------------------------------------------
				//    inputフォームのchecked　$id_hardware_no_id_arrの処理
				// --------------------------------------------------

				$view_arr[$key]['checked'] = null;

				foreach ($id_hardware_no_id_arr as $key2 => $value2)
				{

					if (isset($value2[$value['hardware_no']], $value['id']))
					{
						if ($value2[$value['hardware_no']] == $value['id'])
						{
							$view_arr[$key]['checked'] = ' checked';

							// 選択されているIDは、ID入力の方に送らない
							unset($id_hardware_no_id_arr[$key2]);
						}
					}

				}

				// unset() で要素を削除した後、必要があれば array_values() で歯抜けになったのを詰めます。
				$id_hardware_no_id_arr = array_values($id_hardware_no_id_arr);



				// --------------------------------------------------
				//    hardware_no / hardware_name
				// --------------------------------------------------

				if (isset($db_hardware_arr[$value['hardware_no']]))
				{
					$view_arr[$key]['hardware_name'] = $db_hardware_arr[$value['hardware_no']]['abbreviation'];
					$view_arr[$key]['hardware_no'] = $value['hardware_no'];
				}
				else
				{
					$view_arr[$key]['hardware_name'] = 'ID';
					$view_arr[$key]['hardware_no'] = null;
				}


				// --------------------------------------------------
				//    ID
				// --------------------------------------------------

				$view_arr[$key]['id'] = $value['id'];


			}

		}


		// --------------------------------------------------
		//    ID選択用　javascript　data属性
		//    選択しているIDの情報をjavascriptに送る
		// --------------------------------------------------

		//$data_id_hardware_no_id_arr = array('5_aaaa', '8_123456', '90_bbbb');
		//$data_id_hardware_no_id_arr = array();

		foreach ($id_hardware_no_id_arr as $key => $value)
		{
			// echo 'aaaaaaaaaaaaaaa';
			// var_dump($value);

			$temp_value = key($value) . '_' . $value[key($value)];
			$temp_key = array_search($temp_value, $data_id_hardware_no_id_arr);

			//var_dump($temp_value, $temp_key);

			if ($temp_key !== false)
			{
				unset($data_id_hardware_no_id_arr[$temp_key]);
			}

		}

		// unset() で要素を削除した後、必要があれば array_values() で歯抜けになったのを詰めます。
		$data_id_hardware_no_id_arr = array_values($data_id_hardware_no_id_arr);



		if (count($data_id_hardware_no_id_arr) > 0)
		{
			//$data_id_hardware_no_id = '[' . implode(',', $data_id_hardware_no_id_arr) . ']';
			$data_id_hardware_no_id = implode('/-*-/', $data_id_hardware_no_id_arr);
		}
		else
		{
			//$data_id_hardware_no_id = '[]';
			$data_id_hardware_no_id = null;
		}



		// --------------------------------------------------
		//    Viewを分割
		//    game_idを全件取得してから分割するというめんどくさい方法で処理している
		// --------------------------------------------------

		$offset = $limit * ($page - 1);
		$view_arr = array_slice($view_arr, $offset, $limit);



		if (isset($test))
		{
			//echo '$data_arr';
			//var_dump($data_arr);

			//echo '$db_id_arr';
			//var_dump($db_id_arr);

			//echo '$total';
			//var_dump($total);

			echo 'ID入力用のデータ　$id_hardware_no_id_arr';
			var_dump($id_hardware_no_id_arr);

			echo '$data_id_hardware_no_id_arr';
			var_dump($data_id_hardware_no_id_arr);

			echo '$view_arr';
			var_dump($view_arr);
		}


		//exit();


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// --------------------------------------------------
		//   ページャー
		// --------------------------------------------------

		if ($total > $limit)
		{
			$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$code_pagination = '<div class="margin_top_20">' . "\n";
			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set_safe('page', $page);
			$view_pagination->set_safe('total', $total);
			$view_pagination->set_safe('limit', $limit);
			$view_pagination->set_safe('times', $pagination_times);
			$view_pagination->set_safe('function_name', 'readFormRecruitmentSelectGameId');

			// $argument_form_type = "'" . $form_type . "'";
			// $argument_recruitment_id = ($recruitment_id) ? "'" . $recruitment_id . "'" : "''";
			// $argument_recruitment_reply_id = ($recruitment_reply_id) ? "'" . $recruitment_reply_id . "'" : "''";
//
			// $view_pagination->set_safe('argument_arr', array($game_no, $argument_form_type, $argument_recruitment_id, $argument_recruitment_reply_id));

			$view_pagination->set_safe('argument_arr', array());

			$code_pagination .= $view_pagination->render();
			$code_pagination .= '</div>' . "\n";
		}
		else
		{
			$code_pagination = null;
		}


		$view = \View::forge('parts/form_recruitment_select_game_id_view');
		$view->set('view_arr', $view_arr);
		//$view->set_safe('id_hardware_no_id_arr', $id_hardware_no_id_arr);
		$view->set('data_id_hardware_no_id', $data_id_hardware_no_id);
		$view->set_safe('code_pagination', $code_pagination);

		$code = $view->render();

		//echo $code;
		return array('code' => $code, 'id_hardware_no_id_arr' => $id_hardware_no_id_arr);

	}




	/**
	* ゲームコミュニティ検索
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function chain_game_form($arr)
	{

		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

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
		//   変数入力
		// --------------------------------------------------

		$game_no = (isset($arr['game_no'])) ? $arr['game_no'] : null;


		// --------------------------------------------------
		//    対応ゲームじゃない場合は処理中断　nullを返す
		// --------------------------------------------------

		// 48 ポイッとヒーロー

		// $support_game_no_arr = array(48);
//
		// if (in_array($game_no, $support_game_no_arr))
		// {
			// return array('code' => null);
		// }




		/*
		// --------------------------------------------------
		//    リミット取得
		// --------------------------------------------------

		$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_form_select_id') : \Config::get('limit_form_select_id_sp');



		// --------------------------------------------------
		//    データ取得　data_arr
		// --------------------------------------------------

		if ($data_arr)
		{

		}
		if ($form_type == 'recruitment_edit' and $recruitment_id)
		{
			$data_arr = $model_gc->get_recruitment_appoint($recruitment_id);
		}
		else if ($form_type == 'reply_edit' and $recruitment_reply_id)
		{
			$data_arr = $model_gc->get_recruitment_reply_appoint($recruitment_reply_id);
		}


		// --------------------------------------------------
		//    データ取得　ハードウェア
		// --------------------------------------------------

		if ( ! $db_hardware_arr)
		{
			$language = 'ja';
			$db_hardware_arr = $model_game->get_hardware_sort($language);
			//var_dump($db_hardware_arr);
		}


		// --------------------------------------------------
		//    データ取得　ID
		// --------------------------------------------------

		// 無理矢理対応するために作った変数
		$hurriedly_page = 1;
		$hurriedly_limit = 10000;

		// ID取得
		$temp_arr = array(
			'game_no' => $game_no,
			'page' => $hurriedly_page,
			'limit' => $hurriedly_limit,
		);

		$result_arr = $model_game->get_game_id($temp_arr);
		$db_id_arr = $result_arr[0];
		$total = $result_arr[1];

		unset($temp_arr);



		// --------------------------------------------------
		//    inputフォームのchecked用、配列作成
		// --------------------------------------------------

		$id_hardware_no_id_arr = array();
		$data_id_hardware_no_id_arr = array();

		for ($i=1; $i <= 3; $i++)
		{
			if (isset($data_arr['id_' . $i]))
			{
				array_push($id_hardware_no_id_arr, array($data_arr['id_hardware_no_' . $i] => $data_arr['id_' . $i]));
				//array_push($data_id_hardware_no_id_arr, "'" . $data_arr['id_hardware_no_' . $i] . '_' .  $data_arr['id_' . $i] . "'");
				array_push($data_id_hardware_no_id_arr, $data_arr['id_hardware_no_' . $i] . '_' .  $data_arr['id_' . $i]);
			}
		}



		//$test = true;

		if (isset($test))
		{

			echo '最初に入力されているデータ　$id_hardware_no_id_arr';
			var_dump($id_hardware_no_id_arr);

			echo '最初に入力されているデータ　$data_id_hardware_no_id_arr';
			var_dump($data_id_hardware_no_id_arr);

			echo '<br><br><br><br>';

		}


		// --------------------------------------------------
		//    ビューに送るデータ処理
		// --------------------------------------------------

		$view_arr = array();

		if (count($db_id_arr) > 0)
		{

			foreach ($db_id_arr as $key => $value)
			{

				// --------------------------------------------------
				//    inputフォームのchecked　$id_hardware_no_id_arrの処理
				// --------------------------------------------------

				$view_arr[$key]['checked'] = null;

				foreach ($id_hardware_no_id_arr as $key2 => $value2)
				{

					if (isset($value2[$value['hardware_no']], $value['id']))
					{
						if ($value2[$value['hardware_no']] == $value['id'])
						{
							$view_arr[$key]['checked'] = ' checked';

							// 選択されているIDは、ID入力の方に送らない
							unset($id_hardware_no_id_arr[$key2]);
						}
					}

				}

				// unset() で要素を削除した後、必要があれば array_values() で歯抜けになったのを詰めます。
				$id_hardware_no_id_arr = array_values($id_hardware_no_id_arr);



				// --------------------------------------------------
				//    hardware_no / hardware_name
				// --------------------------------------------------

				if (isset($db_hardware_arr[$value['hardware_no']]))
				{
					$view_arr[$key]['hardware_name'] = $db_hardware_arr[$value['hardware_no']]['abbreviation'];
					$view_arr[$key]['hardware_no'] = $value['hardware_no'];
				}
				else
				{
					$view_arr[$key]['hardware_name'] = 'ID';
					$view_arr[$key]['hardware_no'] = null;
				}


				// --------------------------------------------------
				//    ID
				// --------------------------------------------------

				$view_arr[$key]['id'] = $value['id'];


			}

		}


		// --------------------------------------------------
		//    ID選択用　javascript　data属性
		//    選択しているIDの情報をjavascriptに送る
		// --------------------------------------------------

		//$data_id_hardware_no_id_arr = array('5_aaaa', '8_123456', '90_bbbb');
		//$data_id_hardware_no_id_arr = array();

		foreach ($id_hardware_no_id_arr as $key => $value)
		{
			// echo 'aaaaaaaaaaaaaaa';
			// var_dump($value);

			$temp_value = key($value) . '_' . $value[key($value)];
			$temp_key = array_search($temp_value, $data_id_hardware_no_id_arr);

			//var_dump($temp_value, $temp_key);

			if ($temp_key !== false)
			{
				unset($data_id_hardware_no_id_arr[$temp_key]);
			}

		}

		// unset() で要素を削除した後、必要があれば array_values() で歯抜けになったのを詰めます。
		$data_id_hardware_no_id_arr = array_values($data_id_hardware_no_id_arr);



		if (count($data_id_hardware_no_id_arr) > 0)
		{
			//$data_id_hardware_no_id = '[' . implode(',', $data_id_hardware_no_id_arr) . ']';
			$data_id_hardware_no_id = implode('/-*-/', $data_id_hardware_no_id_arr);
		}
		else
		{
			//$data_id_hardware_no_id = '[]';
			$data_id_hardware_no_id = null;
		}



		// --------------------------------------------------
		//    Viewを分割
		//    game_idを全件取得してから分割するというめんどくさい方法で処理している
		// --------------------------------------------------

		$offset = $limit * ($page - 1);
		$view_arr = array_slice($view_arr, $offset, $limit);



		if (isset($test))
		{
			//echo '$data_arr';
			//var_dump($data_arr);

			//echo '$db_id_arr';
			//var_dump($db_id_arr);

			//echo '$total';
			//var_dump($total);

			echo 'ID入力用のデータ　$id_hardware_no_id_arr';
			var_dump($id_hardware_no_id_arr);

			echo '$data_id_hardware_no_id_arr';
			var_dump($data_id_hardware_no_id_arr);

			echo '$view_arr';
			var_dump($view_arr);
		}


		//exit();
		*/

		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/form_recruitment_chain_view');
		//$view->set('view_arr', $view_arr);
		//$view->set_safe('id_hardware_no_id_arr', $id_hardware_no_id_arr);
		//$view->set('data_id_hardware_no_id', $data_id_hardware_no_id);
		//$view->set_safe('code_pagination', $code_pagination);

		$code = $view->render();

		//echo $code;
		return array('code' => $code);

	}


}
