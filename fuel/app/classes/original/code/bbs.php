<?php

namespace Original\Code;

class Bbs
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// ------------------------------
	//   共通
	// ------------------------------
	/*
	private $agent_type = null;
	private $host = null;
	private $user_agent = null;
	private $user_no = null;
	private $language = null;
	private $uri_base = null;
	private $uri_current = null;
	private $app_mode = null;
	*/

	// ------------------------------
	//   クラス共通
	// ------------------------------

	private $type = null;
	private $page = null;
	private $page_comment = 1;
	private $login_profile_data_arr = null;
	private $datetime_now = null;
	private $online_limit = null;
	private $anonymity = null;
	private $keyword = null;

	private $individual = null;


	// ------------------------------
	//   ゲームコミュニティ
	// ------------------------------

	private $game_no = null;


	// ------------------------------
	//   ユーザーコミュニティ
	// ------------------------------

	private $community_no = null;
	private $db_community_arr = null;
	private $authority_arr = null;


	// ------------------------------
	//   その他
	// ------------------------------

	private $limit_bbs_thread_list = null;
	private $limit_bbs_thread = null;
	private $limit_bbs_comment = null;
	private $limit_bbs_reply = null;
	private $pagination_times = null;


	// ------------------------------
	//   スレッド、コメント、返信
	// ------------------------------

	private $db_bbs_thread_list_arr = null;

	private $db_bbs_thread_arr = null;
	private $db_bbs_thread_total = null;

	private $db_bbs_comment_arr = null;
	private $db_bbs_reply_arr = null;

	private $bbs_thread_no = null;
	private $bbs_comment_no = null;
	private $bbs_reply_no = null;


	// ------------------------------
	//   プロフィール
	// ------------------------------

	private $bbs_user_no_arr = array();
	private $bbs_profile_no_arr = array();
	private $bbs_user_data_arr = array();
	private $bbs_profile_arr = array();



	// ------------------------------
	//   ハンドルネーム、タイトル、コメントなど
	// ------------------------------

	private $handle_name = null;
	private $title = null;
	private $comment = null;
	private $movie_url = null;
	private $anonymity_on = null;
	private $image_1_delete = null;



	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $model_user = null;
	private $model_co = null;
	private $model_notifications = null;
	private $model_present = null;
	private $model_bbs = null;
	private $original_func_common = null;
	private $original_func_co = null;
	private $original_validation_common = null;
	private $original_common_date = null;


	public $test = true;




	// --------------------------------------------------
	//   コンストラクター
	// --------------------------------------------------

	//public static function before()
	//public static function _init()
	public function __construct()
	{

		// ------------------------------
		//   プロパティセット　基本
		// ------------------------------
		/*
		$this->agent_type = (string) AGENT_TYPE;
		$this->host = (string) $arr['host'];
		$this->user_agent = (string) $arr['user_agent'];
		$this->user_no = (string) $arr['user_no'];
		$this->language = (string) $arr['language'];
		$this->uri_base = (string) $arr['uri_base'];
		$this->uri_current = (string) $arr['uri_current'];
		$this->app_mode = (string) $arr['app_mode'];

		echo 'aaaaaaaaaaaaaaaaaaaaa';
		\Debug::dump(AGENT_TYPE, USER_NO, OS);
		*/
		// ------------------------------
		//   プロパティセット　規定値
		// ------------------------------

		// $this->set_limit_bbs_thread_list();
		// $this->set_limit_bbs_thread();
		// $this->set_limit_bbs_comment();
		// $this->set_limit_bbs_reply();
		// $this->set_pagination_times();



		// ------------------------------
		//   インスタンス作成
		// ------------------------------

		$this->model_user = new \Model_User();
		$this->model_user->agent_type = AGENT_TYPE;
		$this->model_user->user_no = USER_NO;
		$this->model_user->language = LANGUAGE;
		$this->model_user->uri_base = URI_BASE;
		$this->model_user->uri_current = URI_CURRENT;

		$this->model_co = new \Model_Co();
		$this->model_co->agent_type = AGENT_TYPE;
		$this->model_co->user_no = USER_NO;
		$this->model_co->language = LANGUAGE;
		$this->model_co->uri_base = URI_BASE;
		$this->model_co->uri_current = URI_CURRENT;

		$this->model_common = new \Model_Common();
		$this->model_common->agent_type = AGENT_TYPE;
		$this->model_common->user_no = USER_NO;
		$this->model_common->language = LANGUAGE;
		$this->model_common->uri_base = URI_BASE;
		$this->model_common->uri_current = URI_CURRENT;

		$this->model_notifications = new \Model_Notifications();
		$this->model_notifications->agent_type = AGENT_TYPE;
		$this->model_notifications->user_no = USER_NO;
		$this->model_notifications->language = LANGUAGE;
		$this->model_notifications->uri_base = URI_BASE;
		$this->model_notifications->uri_current = URI_CURRENT;

		$this->model_present = new \Model_Present();
		$this->model_present->agent_type = AGENT_TYPE;
		$this->model_present->user_no = USER_NO;
		$this->model_present->language = LANGUAGE;
		$this->model_present->uri_base = URI_BASE;
		$this->model_present->uri_current = URI_CURRENT;

		$this->model_bbs = new \Model_Bbs();

		$this->original_func_common = new \Original\Func\Common();
		$this->original_func_common->app_mode = APP_MODE;
		$this->original_func_common->agent_type = AGENT_TYPE;
		$this->original_func_common->user_no = USER_NO;
		$this->original_func_common->language = LANGUAGE;
		$this->original_func_common->uri_base = URI_BASE;
		$this->original_func_common->uri_current = URI_CURRENT;

		$this->original_func_co = new \Original\Func\Co();
		$this->original_func_co->app_mode = APP_MODE;
		$this->original_func_co->agent_type = AGENT_TYPE;
		$this->original_func_co->user_no = USER_NO;
		$this->original_func_co->language = LANGUAGE;
		$this->original_func_co->uri_base = URI_BASE;
		$this->original_func_co->uri_current = URI_CURRENT;

		$this->original_validation_common = new \Original\Validation\Common();

		$this->original_common_date = new \Original\Common\Date();



		// ------------------------------
		//   定数設定
		// ------------------------------

		if (AGENT_TYPE != 'smartphone')
		{
			define("LIMIT_BBS_THREAD_LIST", (int) \Config::get('limit_bbs_thread_list'));
			define("LIMIT_BBS_THREAD", (int) \Config::get('limit_bbs_thread'));
			define("LIMIT_BBS_COMMENT", (int) \Config::get('limit_bbs_comment'));
			define("LIMIT_BBS_REPLY", (int) \Config::get('limit_bbs_reply'));

			define("INDEX_LIMIT_BBS", (int) \Config::get('index_limit_bbs'));

		}
		else
		{
			define("LIMIT_BBS_THREAD_LIST", (int) \Config::get('limit_bbs_thread_list_sp'));
			define("LIMIT_BBS_THREAD", (int) \Config::get('limit_bbs_thread_sp'));
			define("LIMIT_BBS_COMMENT", (int) \Config::get('limit_bbs_comment_sp'));
			define("LIMIT_BBS_REPLY", (int) \Config::get('limit_bbs_reply_sp'));

			define("INDEX_LIMIT_BBS", (int) \Config::get('index_limit_bbs_sp'));
		}

		//define("LIMIT_BBS_THREAD_IMAGE", (int) \Config::get('limit_bbs_thread_image'));
		//define("LIMIT_BBS_COMMENT_IMAGE", (int) \Config::get('limit_bbs_comment_image'));
		//define("LIMIT_BBS_REPLY_IMAGE", (int) \Config::get('limit_bbs_reply_image'));

	}







	// --------------------------------------------------
	//   共通
	// --------------------------------------------------

	/**
	* Setter / タイプ
	*
	* @param string $argument
	*/
	public function set_individual($argument)
	{
		$this->individual = 1;
	}

	public function get_individual()
	{
		return $this->individual;
	}



	/**
	* Setter / タイプ
	*
	* @param string $argument
	*/
	public function set_type($argument)
	{
		//$this->type = (string) $argument;
		$this->type = (string) $this->original_validation_common->type_bbs($argument);
	}

	public function get_type()
	{
		return $this->type;
	}


	/**
	* Setter / ページ
	*
	* @param integer $argument
	*/
	public function set_page($argument)
	{
		if ($argument)
		{
			$this->page = (int) $this->original_validation_common->page($argument);
		}
		else
		{
			$this->page = null;
		}
	}

	public function get_page()
	{
		return $this->page;
	}


	/**
	* Setter / ページ　コメント
	*
	* @param integer $argument
	*/
	public function set_page_comment($argument)
	{
		if ($argument)
		{
			$this->page_comment = (int) $this->original_validation_common->page($argument);
		}
		else
		{
			$this->page_comment = null;
		}
	}

	public function get_page_comment()
	{
		return $this->page_comment;
	}



	/**
	* Setter / 日付 / 共通
	*
	* @param string $argument 日付
	*/
	public function set_datetime_now($argument = null)
	{
		if ($argument)
		{
			$this->datetime_now = (string) $argument;
		}
		else if ($this->datetime_now === null)
		{
			$this->datetime_now = $this->original_common_date->sql_format();
		}
	}

	public function get_datetime_now()
	{
		return $this->datetime_now;
	}



	/**
	* Setter / 検索キーワード / 共通
	*
	* @param string $argument
	*/
	public function set_keyword($argument = null)
	{
		if ($argument)
		{
			$this->keyword = $argument;
		}
	}

	public function get_keyword()
	{
		return $this->keyword;
	}





	/**
	* Setter / ログインプロフィール情報 / GC
	* 新規に取得する場合、$this->game_noが必要
	*
	* @param array $argument_arr ログインプロフィール情報
	*/
	public function set_login_profile_data_arr_gc($argument_arr = null)
	{
		if ($argument_arr)
		{
			$this->login_profile_data_arr = $argument_arr;
		}
		else if ($this->login_profile_data_arr === null and $this->game_no)
		{
			$login_user_data_arr = $this->model_user->get_login_user_data($this->game_no);
			$this->login_profile_data_arr = $login_user_data_arr[1];
		}
	}



	/**
	* Setter / ログインプロフィール情報 / UC
	* 新規に取得する場合、$this->db_community_arrが必要
	*
	* @param array $argument_arr ログインプロフィール情報
	*/
	public function set_login_profile_data_arr_uc($argument_arr = null)
	{
		if ($argument_arr)
		{
			$this->login_profile_data_arr = $argument_arr;
		}
		else if ($this->login_profile_data_arr === null and $this->db_community_arr)
		{
			$this->login_profile_data_arr = $this->original_func_co->login_profile_data($this->db_community_arr);
		}
	}

	public function get_login_profile_data_arr()
	{
		return $this->login_profile_data_arr;
	}










	/**
	* Setter / ゲームNo / 共通
	*
	* @param string $argument ゲームNo
	*/
	public function set_game_no($argument)
	{
		$language = 'ja';
		$this->game_no = (int) $this->original_validation_common->game_no($argument);

		$model_game = new \Model_Game();
		$db_game_data_arr = $model_game->get_game_data($this->game_no, null);
		$game_community_id = $db_game_data_arr['id'];


		if ( ! defined('GAME_NAME')) define('GAME_NAME', $db_game_data_arr['name_' . $language]);
		if ( ! defined('BBS_URL')) define('BBS_URL', URI_BASE . 'gc/' . $db_game_data_arr['id'] . '/bbs');
	}

	public function get_game_no()
	{
		return $this->game_no;
	}



	/**
	* Setter / コミュニティNo / 共通
	*
	* @param string $argument コミュニティNo
	*/
	public function set_community_no($argument)
	{

		if ($argument)
		{
			$this->community_no = (int) $this->original_validation_common->community_no($argument);
		}
		else
		{
			$this->community_no = null;
		}

	}

	public function get_community_no()
	{
		return $this->community_no;
	}





	// --------------------------------------------------
	//   定数
	// --------------------------------------------------


	/**
	* Setter / コミュニティ情報 / UC
	*
	* @param array $argument_arr コミュニティ情報
	*/
	public function set_db_community_arr($argument_arr = null)
	{
		if ($argument_arr)
		{
			$this->db_community_arr = $argument_arr;
		}
		else if ($this->db_community_arr === null and $this->community_no)
		{
			$this->db_community_arr = $this->model_co->get_community($this->community_no, null);
		}

		if ( ! defined('COMMUNITY_NAME')) define('COMMUNITY_NAME', $this->db_community_arr['name']);
		if ( ! defined('BBS_URL')) define('BBS_URL', URI_BASE . 'uc/' . $this->db_community_arr['community_id'] . '/bbs');

	}

	public function get_db_community_arr()
	{
		return $this->db_community_arr;
	}



	/**
	* Setter / 権限 / UC
	*
	* @param array $argument_arr 権限
	*/
	public function set_authority_arr($argument_arr = null)
	{
		if ($argument_arr)
		{
			$this->authority_arr = $argument_arr;
		}
		else if ($this->authority_arr === null and $this->db_community_arr)
		{
			$this->authority_arr = $this->original_func_co->authority($this->db_community_arr);
		}
	}

	public function get_authority_arr()
	{
		return $this->authority_arr;
	}



	/**
	* Setter / オンラインリミット / GC
	*
	* @param integer $argument オンラインリミット
	*/
	public function set_online_limit_gc($argument = null)
	{
		if ($argument)
		{
			$this->online_limit = $argument;
		}
		else if ($this->online_limit === null)
		{
			$this->online_limit = \Config::get('online_limit');
		}
	}


	/**
	* Setter / オンラインリミット / UC
	*
	* @param integer $argument オンラインリミット
	*/
	public function set_online_limit_uc($argument = null)
	{
		if ($argument)
		{
			$this->online_limit = $argument;
		}
		else if ($this->online_limit === null and $this->db_community_arr)
		{
			$config_arr = unserialize($this->db_community_arr['config']);
			$this->online_limit = $config_arr['online_limit'];
		}
	}

	public function get_online_limit()
	{
		return $this->online_limit;
	}



	/**
	* Setter / 匿名を認めるかどうか / GC
	*
	* @param integer $argument
	*/
	public function set_anonymity_gc($argument = null)
	{
		if ($argument)
		{
			$this->anonymity = $argument;
		}
		else if ($this->anonymity === null)
		{
			$this->anonymity = true;
		}
	}


	/**
	* Setter / 匿名を認めるかどうか / UC
	*
	* @param integer $argument
	*/
	public function set_anonymity_uc($argument = null)
	{
		if ($argument)
		{
			$this->anonymity = $argument;
		}
		else if ($this->anonymity === null and $this->db_community_arr)
		{
			$config_arr = unserialize($this->db_community_arr['config']);
			$this->anonymity = $config_arr['anonymity'];
		}
	}

	public function get_anonymity()
	{
		return $this->anonymity;
	}



	/**
	* Setter / 個別読み込み
	*
	* @param integer $argument
	*/
	/*
	public function set_appoint($argument)
	{
		$this->appoint = (boolean) $argument;
	}

	public function get_appoint()
	{
		return $this->appoint;
	}
	*/






	// --------------------------------------------------
	//   BBSデータ取得
	// --------------------------------------------------

	/**
	* Setter / スレッド一覧 / GC
	*
	* @param array $argument_arr スレッド
	*/
	public function set_db_bbs_thread_list_arr_gc($argument_arr = null)
	{

		if ($this->db_bbs_thread_list_arr === null and $this->game_no and $this->page)
		{

			$temp_arr = array(
				'game_no' => $this->game_no,
				'page' => $this->page,
				'limit' => LIMIT_BBS_THREAD_LIST
			);

			$this->db_bbs_thread_list_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);

		}

	}



	/**
	* Setter / スレッド一覧 / UC
	*
	* @param array $argument_arr スレッド
	*/
	public function set_db_bbs_thread_list_arr_uc($argument_arr = null)
	{

		if ($argument_arr)
		{
			$this->db_bbs_thread_list_arr = $argument_arr;
		}
		else if ($this->db_bbs_thread_list_arr === null and $this->community_no and $this->page)
		{

			$temp_arr = array(
				'community_no' => $this->community_no,
				'page' => $this->page,
				'limit' => LIMIT_BBS_THREAD_LIST
			);

			$this->db_bbs_thread_list_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);

		}/*
		else if ($this->db_bbs_thread_list_arr === null and $this->bbs_thread_no)
		{

			$temp_arr = array(
				'bbs_thread_no' => $this->bbs_thread_no,
				'page' => 1,
				'limit' => 1
			);

			$this->db_bbs_thread_list_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);

		}
		*/
	}

	public function get_db_bbs_thread_list_arr()
	{
		return $this->db_bbs_thread_list_arr;
	}




	/**
	* Setter / スレッド / GC
	*
	* @param array $argument_arr スレッド
	*/
	public function set_db_bbs_thread_arr_gc($argument_arr = null)
	{

		if ($argument_arr)
		{
			$this->db_bbs_thread_arr = $argument_arr;
		}
		else if ($this->db_bbs_thread_arr === null and $this->game_no and $this->page)
		{

			$temp_arr = array(
				'game_no' => $this->game_no,
				'page' => $this->page,
				'limit' => LIMIT_BBS_THREAD
			);

			$this->db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);

		}
		else if ($this->db_bbs_thread_arr === null and $this->bbs_thread_no)
		{
			$temp_arr = array(
				'bbs_thread_no' => $this->bbs_thread_no,
				'page' => 1,
				'limit' => 1
			);

			$this->db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);
		}

	}


	/**
	* Setter / スレッド / UC
	*
	* @param array $argument_arr スレッド
	*/
	public function set_db_bbs_thread_arr_uc($argument_arr = null)
	{

		if ($argument_arr)
		{
			$this->db_bbs_thread_arr = $argument_arr;
		}
		else if ($this->db_bbs_thread_arr === null and $this->community_no and $this->page)
		{
			$temp_arr = array(
				'community_no' => $this->community_no,
				'page' => $this->page,
				'limit' => LIMIT_BBS_THREAD
			);

			$this->db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
		}
		else if ($this->db_bbs_thread_arr === null and $this->bbs_thread_no)
		{
			$temp_arr = array(
				'bbs_thread_no' => $this->bbs_thread_no,
				'page' => 1,
				'limit' => 1
			);

			$this->db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
		}

	}

	public function get_db_bbs_thread_arr()
	{
		return $this->db_bbs_thread_arr;
	}





	/**
	* Setter / スレッド総数 / GC
	*
	* @param integer $argument スレッド総数
	*/
	public function set_db_bbs_thread_total_gc($argument = null)
	{

		if ($argument)
		{
			$this->db_bbs_thread_total = $argument;
		}
		else if ($this->db_bbs_thread_total === null and $this->game_no)
		{
			$this->db_bbs_thread_total = $this->model_bbs->get_bbs_thread_total_gc($this->game_no);
		}

	}


	/**
	* Setter / スレッド総数 / UC
	*
	* @param integer $argument スレッド総数
	*/
	public function set_db_bbs_thread_total_uc($argument = null)
	{
		//\Debug::dump($this->community_no);
		//exit();
		if ($argument)
		{
			$this->db_bbs_thread_total = $argument;
		}
		else if ($this->db_bbs_thread_total === null and $this->community_no)
		{
			$this->db_bbs_thread_total = $this->model_bbs->get_bbs_thread_total_uc($this->community_no);
		}

	}

	public function get_db_bbs_thread_total()
	{
		return $this->db_bbs_thread_total;
	}






	/**
	* Setter / スレッド処理 / GC
	*
	* @param array $argument_arr コメント
	*/
	public function set_process_db_bbs_thread_arr_gc($thread_total)
	{

		if ($thread_total > 0 and $this->db_bbs_thread_arr)
		{

			foreach ($this->db_bbs_thread_arr as $key => &$value)
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
					array_push($this->bbs_profile_no_arr, $value['profile_no']);
				}
				else if ($value['user_no'])
				{
					array_push($this->bbs_user_no_arr, $value['user_no']);
				}


				// ------------------------------
				//    コメント＆返信処理
				// ------------------------------

				$this->set_process_db_bbs_comment_arr_gc($value['comment_total'], $value['bbs_thread_no'], $this->page_comment);

			}

		}

	}




	/**
	* Setter / スレッド処理 / UC
	*
	* @param array $argument_arr コメント
	*/
	public function set_process_db_bbs_thread_arr_uc($thread_total)
	{

		if ($thread_total > 0 and $this->db_bbs_thread_arr)
		{

			foreach ($this->db_bbs_thread_arr as $key => &$value)
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
					array_push($this->bbs_profile_no_arr, $value['profile_no']);
				}
				else if ($value['user_no'])
				{
					array_push($this->bbs_user_no_arr, $value['user_no']);
				}


				// ------------------------------
				//    コメント＆返信処理
				// ------------------------------

				$this->set_process_db_bbs_comment_arr_uc($value['comment_total'], $value['bbs_thread_no'], $this->page_comment);

			}

		}

	}




	/**
	* Setter / コメントを取得して処理 / GC
	*
	* @param array $argument_arr コメント
	*/
	public function set_process_db_bbs_comment_arr_gc($comment_total, $bbs_thread_no, $page)
	{

		if ($comment_total > 0 and $bbs_thread_no and $page)
		{

			$temp_arr = array(
				'bbs_thread_no' => $bbs_thread_no,
				'page' => $page,
				'limit' => LIMIT_BBS_COMMENT
			);

			$this->db_bbs_comment_arr[$bbs_thread_no] = $this->model_bbs->get_bbs_comment_gc($temp_arr);


			foreach ($this->db_bbs_comment_arr[$bbs_thread_no] as $key_comment => &$value_comment)
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
					array_push($this->bbs_profile_no_arr, $value_comment['profile_no']);
				}
				else if ($value_comment['user_no'])
				{
					array_push($this->bbs_user_no_arr, $value_comment['user_no']);
				}


				// ------------------------------
				//    返信処理
				// ------------------------------

				$this->set_process_db_bbs_reply_arr_gc($value_comment['reply_total'], $value_comment['bbs_comment_no'], 1);

			}

		}

	}



	/**
	* Setter / コメントを取得して処理 / UC
	*
	* @param array $argument_arr コメント
	*/
	public function set_process_db_bbs_comment_arr_uc($comment_total, $bbs_thread_no, $page)
	{

		if ($comment_total > 0 and $bbs_thread_no and $page)
		{

			$temp_arr = array(
				'bbs_thread_no' => $bbs_thread_no,
				'page' => $page,
				'limit' => LIMIT_BBS_COMMENT
			);

			$this->db_bbs_comment_arr[$bbs_thread_no] = $this->model_bbs->get_bbs_comment_uc($temp_arr);


			foreach ($this->db_bbs_comment_arr[$bbs_thread_no] as $key_comment => &$value_comment)
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
					array_push($this->bbs_profile_no_arr, $value_comment['profile_no']);
				}
				else if ($value_comment['user_no'])
				{
					array_push($this->bbs_user_no_arr, $value_comment['user_no']);
				}


				// ------------------------------
				//    返信処理
				// ------------------------------

				$this->set_process_db_bbs_reply_arr_uc($value_comment['reply_total'], $value_comment['bbs_comment_no'], 1);

			}

		}

	}



	/**
	* Setter / 返信を取得して処理 / GC
	*
	* @param array $argument_arr コメント
	*/
	public function set_process_db_bbs_reply_arr_gc($reply_total, $bbs_comment_no, $page)
	{

		if ($reply_total > 0 and $bbs_comment_no and $page)
		{

			$temp_arr = array(
				'bbs_comment_no' => $bbs_comment_no,
				'page' => $page,
				'limit' => LIMIT_BBS_REPLY
			);

			$this->db_bbs_reply_arr[$bbs_comment_no] = $this->model_bbs->get_bbs_reply_gc($temp_arr);


			foreach ($this->db_bbs_reply_arr[$bbs_comment_no] as $key_reply => &$value_reply) {

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
					array_push($this->bbs_profile_no_arr, $value_reply['profile_no']);
				}
				else if ($value_reply['user_no'])
				{
					array_push($this->bbs_user_no_arr, $value_reply['user_no']);
				}

			}

		}

	}



	/**
	* Setter / 返信を取得して処理 / UC
	*
	* @param array $argument_arr コメント
	*/
	public function set_process_db_bbs_reply_arr_uc($reply_total, $bbs_comment_no, $page)
	{

		if ($reply_total > 0 and $bbs_comment_no and $page)
		{

			$temp_arr = array(
				'bbs_comment_no' => $bbs_comment_no,
				'page' => $page,
				'limit' => LIMIT_BBS_REPLY
			);

			$this->db_bbs_reply_arr[$bbs_comment_no] = $this->model_bbs->get_bbs_reply_uc($temp_arr);


			foreach ($this->db_bbs_reply_arr[$bbs_comment_no] as $key_reply => &$value_reply) {

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
					array_push($this->bbs_profile_no_arr, $value_reply['profile_no']);
				}
				else if ($value_reply['user_no'])
				{
					array_push($this->bbs_user_no_arr, $value_reply['user_no']);
				}

			}

		}

	}

	public function get_db_bbs_comment_arr()
	{
		return $this->db_bbs_comment_arr;
	}

	public function get_db_bbs_reply_arr()
	{
		return $this->db_bbs_reply_arr;
	}




	// --------------------------------------------------
	//   個別No
	// --------------------------------------------------


	/**
	* Setter / スレッドNo / GC
	*
	* @param integer $argument
	*/
	public function set_bbs_thread_no_gc($argument = null)
	{
		$this->bbs_thread_no = (int) $this->original_validation_common->bbs_thread_no_gc($argument);
	}


	/**
	* Setter / スレッドNo / UC
	*
	* @param integer $argument
	*/
	public function set_bbs_thread_no_uc($argument = null)
	{
		$this->bbs_thread_no = (int) $this->original_validation_common->bbs_thread_no_uc($argument);
	}


	public function get_bbs_thread_no()
	{
		return $this->bbs_thread_no;
	}



	/**
	* Setter / コメントNo / GC
	*
	* @param integer $argument
	*/
	public function set_bbs_comment_no_gc($argument = null)
	{
		$this->bbs_comment_no = (int) $this->original_validation_common->bbs_comment_no_gc($argument);
	}


	/**
	* Setter / コメントNo / UC
	*
	* @param integer $argument
	*/
	public function set_bbs_comment_no_uc($argument = null)
	{
		$this->bbs_comment_no = (int) $this->original_validation_common->bbs_comment_no_uc($argument);
	}


	public function get_bbs_comment_no()
	{
		return $this->bbs_comment_no;
	}



	/**
	* Setter / 返信No / GC
	*
	* @param integer $argument
	*/
	public function set_bbs_reply_no_gc($argument = null)
	{
		$this->bbs_reply_no = (int) $this->original_validation_common->bbs_reply_no_gc($argument);
	}


	/**
	* Setter / 返信No / UC
	*
	* @param integer $argument
	*/
	public function set_bbs_reply_no_uc($argument = null)
	{
		$this->bbs_reply_no = (int) $this->original_validation_common->bbs_reply_no_uc($argument);
	}


	public function get_bbs_reply_no()
	{
		return $this->bbs_reply_no;
	}






	// --------------------------------------------------
	//   Personal Box用プロフィール
	// --------------------------------------------------


	/**
	* Setter / Personal Box用　ユーザープロフィール配列 / 共通
	*
	* @param integer $argument_arr
	*/
	public function set_bbs_user_data_arr($argument_arr = null)
	{
		if ($argument_arr)
		{
			$this->bbs_user_data_arr = $argument_arr;
		}
		else if (count($this->bbs_user_no_arr) > 0)
		{
			$this->bbs_user_no_arr = array_unique($this->bbs_user_no_arr);
			$this->bbs_user_data_arr = $this->model_user->get_user_data_list_in_personal_box($this->bbs_user_no_arr);
		}
	}

	public function get_bbs_user_data_arr()
	{
		return $this->bbs_user_data_arr;
	}



	/**
	* Setter / Personal Box用　プロフィール配列 / 共通
	*
	* @param integer $argument_arr
	*/
	public function set_bbs_profile_arr($argument_arr = null)
	{
		if ($argument_arr)
		{
			$this->bbs_profile_arr = $argument_arr;
		}
		else if (count($this->bbs_profile_no_arr) > 0)
		{
			$this->bbs_profile_no_arr = array_unique($this->bbs_profile_no_arr);
			$this->bbs_profile_arr = $this->model_user->get_profile_list_in_personal_box($this->bbs_profile_no_arr);
		}
	}

	public function get_bbs_profile_arr()
	{
		return $this->bbs_profile_arr;
	}





	/**
	* Setter / ハンドルネーム / 共通
	*
	* @param string $argument
	*/
	public function set_handle_name($argument = null)
	{
		$this->handle_name = $this->original_validation_common->handle_name($argument);
	}

	public function get_handle_name()
	{
		return $this->handle_name;
	}


	/**
	* Setter / タイトル / 共通
	*
	* @param string $argument
	*/
	public function set_title($argument = null)
	{
		$this->title = $this->original_validation_common->title($argument);
	}

	public function get_title()
	{
		return $this->title;
	}


	/**
	* Setter / コメント / 共通
	*
	* @param string $argument
	*/
	public function set_comment($argument = null)
	{
		$this->comment = $this->original_validation_common->comment($argument);
	}

	public function get_comment()
	{
		return $this->comment;
	}



	/**
	* Setter / 動画URL / 共通
	*
	* @param string $argument
	*/
	public function set_movie_url($argument = null)
	{
		$this->movie_url = $this->original_validation_common->movie_url($argument);
	}

	public function get_movie_url()
	{
		return $this->movie_url;
	}



	/**
	* Setter / 書き込みを匿名にする / 共通
	*
	* @param integer $argument
	*/
	public function set_anonymity_on($argument = null)
	{
		if ($argument)
		{
			$this->anonymity_on = 1;
		}
	}

	public function get_anonymity_on()
	{
		return $this->anonymity_on;
	}



	/**
	* Setter / 画像削除 / 共通
	*
	* @param integer $argument
	*/
	public function set_image_1_delete($argument = null)
	{
		if ($argument)
		{
			$this->image_1_delete = 1;
		}
	}

	public function get_image_1_delete()
	{
		return $this->image_1_delete;
	}






	// --------------------------------------------------
	//   コード取得
	// --------------------------------------------------


	/**
	* BBS スレッド一覧　GC
	*
	* @return string HTMLコード
	*/
	public function get_code_thread_list_gc()
	{

		// --------------------------------------------------
		//   必要な変数がない場合は処理停止、空を返す
		// --------------------------------------------------

		if ( ! $this->get_game_no() or ! $this->get_page()) return null;


		// --------------------------------------------------
		//   スレッド、値セット、取得
		// --------------------------------------------------

		$this->set_db_bbs_thread_list_arr_gc();
		$this->set_db_bbs_thread_total_gc();



		// テスト
		// $this->test_output();
		//\Debug::dump($this->get_db_bbs_thread_total());
		// exit();



		// --------------------------------------------------
		//   スレッドがない場合は処理停止、空を返す　空だと最初にスレッドをたてたときにバグるので使用停止（20160714）
		// --------------------------------------------------

		//if (count($this->get_db_bbs_thread_list_arr()) == 0) return null;


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_thread_list_view');

		$view->set('no', $this->get_game_no());
		$view->set('type', 'gc');
		$view->set('thread_arr', $this->get_db_bbs_thread_list_arr());
		$view->set('thread_total', $this->get_db_bbs_thread_total());

		// ページャー
		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $this->get_db_bbs_thread_total());
		$view->set('pagination_limit', LIMIT_BBS_THREAD_LIST);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_function_name', 'readBbsThreadList');
		$view->set('pagination_argument_arr', array("'gc'", $this->get_game_no(), 1));

		return $view->render();

	}


	/**
	* BBS スレッド一覧　UC
	*
	* @return string HTMLコード
	*/
	public function get_code_thread_list_uc()
	{

		// --------------------------------------------------
		//   コミュニティ、権限　値セット
		// --------------------------------------------------

		$this->set_db_community_arr();
		$this->set_authority_arr();


		// --------------------------------------------------
		//   値取得
		// --------------------------------------------------

		$authority_arr = $this->get_authority_arr();


		// --------------------------------------------------
		//   必要な変数がない場合は処理停止、空を返す
		// --------------------------------------------------

		if ( ! $this->get_community_no() or ! $this->get_page() or ! $authority_arr) return null;


		// --------------------------------------------------
		//   権限チェック
		// --------------------------------------------------

		if ( ! $authority_arr['read_bbs']) return null;


		// --------------------------------------------------
		//   スレッド、値セット、取得
		// --------------------------------------------------

		$this->set_db_bbs_thread_list_arr_uc();
		$this->set_db_bbs_thread_total_uc();



		// テスト
		//$this->test_output();
		//\Debug::dump();
		//exit();



		// --------------------------------------------------
		//   スレッドがない場合は処理停止、空を返す
		// --------------------------------------------------

		//if (count($this->get_db_bbs_thread_list_arr()) == 0) return null;


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_thread_list_view');

		$view->set('no', $this->get_community_no());
		$view->set('type', 'uc');
		$view->set('thread_arr', $this->get_db_bbs_thread_list_arr());
		$view->set('thread_total', $this->get_db_bbs_thread_total());

		// ページャー
		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $this->get_db_bbs_thread_total());
		$view->set('pagination_limit', LIMIT_BBS_THREAD_LIST);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_function_name', 'readBbsThreadList');
		$view->set('pagination_argument_arr', array("'uc'", $this->get_community_no(), 1));

		return $view->render();

	}




	/**
	* BBS GC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_individual_gc($arr)
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$arr['game_no'] = 1;
			$arr['bbs_id'] = 'i4ruw418jxcbvyh5';
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');

		$val->add_field('game_no', 'Game No', 'required|check_game_no');
		$val->add_field('bbs_id', 'BBS ID', 'required|exact_length[16]|valid_string[alpha,lowercase,numeric]');
		$val->add_field('page_comment', 'Page Comment', 'match_pattern["^[1-9]\d*$"]');


		if ($val->run($arr))
		{
			$validated_game_no = $val->validated('game_no');
			$validated_bbs_id = $val->validated('bbs_id');
			$validated_page_comment = ($val->validated('page_comment')) ? $val->validated('page_comment') : 1;
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
		//   個別
		// --------------------------------------------------

		if ( ! defined('INDIVIDUAL')) define('INDIVIDUAL', true);
		if ( ! defined('BBS_ID')) define('BBS_ID', $validated_bbs_id);


		// --------------------------------------------------
		//   値セット
		// --------------------------------------------------

		$type = 'gc';
		// $game_no = $arr['game_no'] ?? null;
		// $bbs_id = $arr['bbs_id'];

		$pagination_comment_on = false;
		$pagination_reply_on = false;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'type' => $type,
			'game_no' => $validated_game_no,
			'bbs_id' => $validated_bbs_id
		);

		$result_arr = $this->model_bbs->select_bbs_individual($temp_arr);

// \Debug::dump($result_arr);
// exit();

		// --------------------------------------------------
		//   データが存在しない場合は処理停止
		// --------------------------------------------------

		if (empty($result_arr)) throw new \HttpNotFoundException;



		if ($result_arr['type'] === 'bbs_thread_gc')
		{
			$this->set_page(null);
			$this->set_page_comment($validated_page_comment);
			$this->set_bbs_thread_no_gc($result_arr['bbs_thread_no']);
			$return_arr = $this->get_code_bbs_appoint_gc();
			return $return_arr;
		}
		else if ($result_arr['type'] === 'bbs_comment_gc')
		{
			$pagination_comment_on = false;
			$pagination_reply_on = true;
		}
		else if ($result_arr['type'] === 'bbs_reply_gc')
		{
			$pagination_comment_on = false;
			$pagination_reply_on = false;
		}


		// --------------------------------------------------
		//   配列
		// --------------------------------------------------

		$thread_arr = $result_arr['thread_arr'] ?? null;
		$comment_arr = $result_arr['comment_arr'] ?? null;
		$reply_arr = $result_arr['reply_arr'] ?? null;


		// --------------------------------------------------
		//   プロフィール
		// --------------------------------------------------

		$this->bbs_user_no_arr = $result_arr['user_no_arr'];
		$this->bbs_profile_no_arr = $result_arr['profile_no_arr'];



		// if (isset($this->test))
		// {
		// 	\Debug::$js_toggle_open = true;
		//
		// 	echo '$game_no';
		// 	\Debug::dump($game_no);
		//
		// 	echo '$bbs_id';
		// 	\Debug::dump($bbs_id);
		//
		// 	echo '$thread_arr';
		// 	\Debug::dump($thread_arr);
		//
		// 	echo '$comment_arr';
		// 	\Debug::dump($comment_arr);
		//
		// 	echo '$reply_arr';
		// 	\Debug::dump($reply_arr);
		//
		// 	echo '$this->bbs_user_no_arr';
		// 	\Debug::dump($this->bbs_user_no_arr);
		//
		// 	echo '$this->bbs_profile_no_arr';
		// 	\Debug::dump($this->bbs_profile_no_arr);
		//
		// 	if (isset($code)) echo $code;
		//
		// }
		//
		// exit();



		// --------------------------------------------------
		//    Personal Box用　プロフィール　値入力
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();


		// --------------------------------------------------
		//   コミュニティ 値セット
		// --------------------------------------------------

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();

		$this->set_game_no($validated_game_no);



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_view');

		$view->set_safe('agent_type', AGENT_TYPE);
		$view->set_safe('host', HOST);
		$view->set_safe('user_agent', USER_AGENT);
		$view->set_safe('login_user_no', USER_NO);
		$view->set('uri_base', URI_BASE);
		$view->set_safe('app_mode', APP_MODE);

		$view->set('datetime_now', $this->get_datetime_now());
		$view->set('type_gc_or_uc', $type);
		$view->set('no', $this->get_game_no());
		$view->set('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('thread_arr', $thread_arr);
		$view->set('comment_arr', $comment_arr);
		$view->set('reply_arr', $reply_arr);
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());
		$view->set('login_profile_data_arr', $this->get_login_profile_data_arr());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		$view->set_safe('appoint', true);
		$view->set_safe('individual', true);
		$view->set_safe('pagination_comment_on', $pagination_comment_on);
		$view->set_safe('pagination_reply_on', $pagination_reply_on);

		// ページャー
		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $this->get_db_bbs_thread_total());
		$view->set('pagination_limit', LIMIT_BBS_THREAD);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_argument_arr', array("'gc'", $this->get_game_no(), 1));

		//$return_arr['code'] = $view->render();

		// \Debug::dump($comment_arr, $reply_arr);
		// exit();

		//\Debug::dump(BBS_URL);


		// --------------------------------------------------
		//   コード ＆ Meta
		// --------------------------------------------------

		$bbs_thread_no = $thread_arr[0]['bbs_thread_no'];

		$add_page_url = ($this->get_page() === 1 or is_null($this->get_page())) ? null : '/' . $this->get_page();

		if ($result_arr['type'] === 'bbs_comment_gc')
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'bbs',
					'content' => 'index',
					'function' => 'readBbsIndividual',
					'type' => 'gc',
					'no' => $this->get_game_no(),
					'bbsId' => BBS_ID,
					'pageComment' => $validated_page_comment
				],
				'url' => BBS_URL . '/' . $validated_bbs_id . $add_page_url,
				'meta_title' => GAME_NAME . ' - ' . $thread_arr[0]['title'] . 'へのコメント',
				'meta_keywords' => GAME_NAME . ',交流掲示板',
				'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $comment_arr[$bbs_thread_no][0]['comment'])
			);
		}
		else if ($result_arr['type'] === 'bbs_reply_gc')
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'bbs',
					'content' => 'index',
					'function' => 'readBbsIndividual',
					'type' => 'gc',
					'no' => $this->get_game_no(),
					'bbsId' => BBS_ID,
					'pageComment' => $validated_page_comment
				],
				'url' => BBS_URL . '/' . $validated_bbs_id . $add_page_url,
				'meta_title' => GAME_NAME . ' - ' . $thread_arr[0]['title'] . 'への返信',
				'meta_keywords' => GAME_NAME . ',交流掲示板',
				'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $reply_arr[$comment_arr[$bbs_thread_no][0]['bbs_comment_no']][0]['comment'])
			);
		}

		//\Debug::dump($return_arr);

		return $return_arr;

	}




	/**
	* BBS UC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_individual_uc($arr)
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$arr['community_no'] = 1;
			$arr['bbs_id'] = '9299slkines2onqf';
			$arr['bbs_id'] = 'acx4y0n0hq2z36v6';
			$arr['bbs_id'] = 'wxi2gbtnefsbquwb';
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
		$val->add_field('bbs_id', 'BBS ID', 'required|exact_length[16]|valid_string[alpha,lowercase,numeric]');
		$val->add_field('page_comment', 'Page Comment', 'match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_community_no = $val->validated('community_no');
			$validated_bbs_id = $val->validated('bbs_id');
			$validated_page_comment = ($val->validated('page_comment')) ? $val->validated('page_comment') : 1;
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
		//   個別
		// --------------------------------------------------

		if ( ! defined('INDIVIDUAL')) define('INDIVIDUAL', true);
		if ( ! defined('BBS_ID')) define('BBS_ID', $validated_bbs_id);


		// --------------------------------------------------
		//   値セット
		// --------------------------------------------------

		$type = 'uc';
		$pagination_comment_on = false;
		$pagination_reply_on = false;

//\Debug::dump($validated_community_no, $validated_bbs_id);
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'type' => $type,
			'community_no' => $validated_community_no,
			'bbs_id' => $validated_bbs_id
		);

		$result_arr = $this->model_bbs->select_bbs_individual($temp_arr);

//\Debug::dump($result_arr);
// exit();

		// --------------------------------------------------
		//   データが存在しない場合は処理停止
		// --------------------------------------------------

		if (empty($result_arr)) throw new \HttpNotFoundException;



		if ($result_arr['type'] === 'bbs_thread_uc')
		{
			$this->set_page(null);
			$this->set_page_comment($validated_page_comment);
			$this->set_bbs_thread_no_uc($result_arr['bbs_thread_no']);
			$return_arr = $this->get_code_bbs_appoint_uc();
			return $return_arr;
		}
		else if ($result_arr['type'] === 'bbs_comment_uc')
		{
			$pagination_comment_on = false;
			$pagination_reply_on = true;
		}
		else if ($result_arr['type'] === 'bbs_reply_uc')
		{
			$pagination_comment_on = false;
			$pagination_reply_on = false;
		}


		// --------------------------------------------------
		//   配列
		// --------------------------------------------------

		$thread_arr = $result_arr['thread_arr'] ?? null;
		$comment_arr = $result_arr['comment_arr'] ?? null;
		$reply_arr = $result_arr['reply_arr'] ?? null;


		// --------------------------------------------------
		//   プロフィール
		// --------------------------------------------------

		$this->bbs_user_no_arr = $result_arr['user_no_arr'];
		$this->bbs_profile_no_arr = $result_arr['profile_no_arr'];



		// --------------------------------------------------
		//   値セット
		// --------------------------------------------------

		$this->set_community_no($validated_community_no);
		$this->set_db_community_arr();

		//$db_community_arr = $this->get_db_community_arr();

		$this->set_authority_arr();
		$this->set_login_profile_data_arr_uc();
		$this->set_datetime_now();
		$this->set_online_limit_uc();
		$this->set_anonymity_uc();


		// --------------------------------------------------
		//   権限取得
		// --------------------------------------------------

		$authority_arr = $this->get_authority_arr();
//\Debug::dump($db_community_arr, $authority_arr);

		// --------------------------------------------------
		//   権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['read_bbs'] and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//   スレッド、値セット、取得
		// --------------------------------------------------

		$this->set_db_bbs_thread_arr_uc();
		$this->set_db_bbs_thread_total_uc();


		// --------------------------------------------------
		//    スレッド、コメント、返信処理
		// --------------------------------------------------

		$this->set_process_db_bbs_thread_arr_uc($this->get_db_bbs_thread_total());


		// --------------------------------------------------
		//    Personal Box用　プロフィール　値入力
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_view');

		$view->set('datetime_now', $this->get_datetime_now());

		$view->set('type_gc_or_uc', 'uc');
		$view->set('no', $this->get_community_no());
		$view->set('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('thread_arr', $thread_arr);
		$view->set('comment_arr', $comment_arr);
		$view->set('reply_arr', $reply_arr);
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());
		$view->set('login_profile_data_arr', $this->get_login_profile_data_arr());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		$view->set_safe('appoint', true);
		$view->set_safe('individual', true);
		$view->set_safe('pagination_comment_on', $pagination_comment_on);
		$view->set_safe('pagination_reply_on', $pagination_reply_on);

		// ページャー
		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $this->get_db_bbs_thread_total());
		$view->set('pagination_limit', LIMIT_BBS_THREAD);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_argument_arr', array("'uc'", $this->get_community_no(), true));



		// --------------------------------------------------
		//   コード ＆ Meta
		// --------------------------------------------------

		$bbs_thread_no = $thread_arr[0]['bbs_thread_no'];

		$add_page_url = ($this->get_page() === 1 or is_null($this->get_page())) ? null : '/' . $this->get_page();

		if ($result_arr['type'] === 'bbs_comment_uc')
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'bbs',
					'content' => 'index',
					'function' => 'readBbsIndividual',
					'type' => 'uc',
					'no' => $this->get_community_no(),
					'bbsId' => BBS_ID,
					'pageComment' => $validated_page_comment
				],
				'url' => BBS_URL . '/' . $validated_bbs_id . $add_page_url,
				'meta_title' => COMMUNITY_NAME . ' - ' . $thread_arr[0]['title'] . 'へのコメント',
				'meta_keywords' => COMMUNITY_NAME . ',交流掲示板',
				'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $comment_arr[$bbs_thread_no][0]['comment'])
			);
		}
		else if ($result_arr['type'] === 'bbs_reply_uc')
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'bbs',
					'content' => 'index',
					'function' => 'readBbsIndividual',
					'type' => 'uc',
					'no' => $this->get_community_no(),
					'bbsId' => BBS_ID,
					'pageComment' => $validated_page_comment
				],
				'url' => BBS_URL . '/' . $validated_bbs_id . $add_page_url,
				'meta_title' => COMMUNITY_NAME . ' - ' . $thread_arr[0]['title'] . 'への返信',
				'meta_keywords' => COMMUNITY_NAME . ',交流掲示板',
				'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $reply_arr[$comment_arr[$bbs_thread_no][0]['bbs_comment_no']][0]['comment'])
			);
		}


		// \Debug::dump($return_arr);
		// exit();

		return $return_arr;

	}





	/**
	* BBS GC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_gc()
	{

		// --------------------------------------------------
		//   値セット
		// --------------------------------------------------

		$this->set_online_limit_gc();
		$this->set_login_profile_data_arr_gc();


		// --------------------------------------------------
		//   必要な変数がない場合は処理停止、空を返す
		// --------------------------------------------------

		if ( ! $this->get_game_no() or ! $this->get_page() or ! $this->get_online_limit()) return null;


		// --------------------------------------------------
		//   スレッド、値セット、取得
		// --------------------------------------------------

		$this->set_db_bbs_thread_arr_gc();
		$this->set_db_bbs_thread_total_gc();


		// --------------------------------------------------
		//    スレッド、コメント、返信処理
		// --------------------------------------------------

		$this->set_process_db_bbs_thread_arr_gc($this->get_db_bbs_thread_total());


		// --------------------------------------------------
		//    Personal Box用　プロフィール　値入力
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_view');

		$view->set('datetime_now', $this->get_datetime_now());

		$view->set('type_gc_or_uc', 'gc');
		$view->set('no', $this->get_game_no());
		$view->set('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', true);

		$view->set('thread_arr', $this->get_db_bbs_thread_arr());
		$view->set('comment_arr', $this->get_db_bbs_comment_arr());
		$view->set('reply_arr', $this->get_db_bbs_reply_arr());
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());
		$view->set('login_profile_data_arr', $this->get_login_profile_data_arr());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		// ページャー
		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $this->get_db_bbs_thread_total());
		$view->set('pagination_limit', LIMIT_BBS_THREAD);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_argument_arr', array("'gc'", $this->get_game_no(), 1, 1, 1));


		//$meta_page = ($this->get_page() > 1) ? ' / 交流掲示板 Page ' . $this->get_page() : null;

		if ($this->get_page() === 1)
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'bbs',
					'content' => 'index',
					'function' => 'readBbs',
					'type' => 'gc',
					'no' => $this->get_game_no(),
					'page' => $this->get_page()
				],
				'url' => BBS_URL . '/' . $this->get_page(),
				'meta_title' => GAME_NAME . ' - Game Users',
				'meta_keywords' => GAME_NAME . ',交流掲示板,募集掲示板',
				'meta_description' => GAME_NAME . 'の情報交換を行うならGame Usersをぜひ利用してください。フレンド募集・メンバー募集ができる募集掲示板もあります。'
			);
		}
		else
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'bbs',
					'content' => 'index',
					'function' => 'readBbs',
					'type' => 'gc',
					'no' => $this->get_game_no(),
					'page' => $this->get_page()
				],
				'url' => BBS_URL . '/' . $this->get_page(),
				'meta_title' => GAME_NAME . ' - 交流掲示板 Page ' . $this->get_page(),
				'meta_keywords' => GAME_NAME . ',交流掲示板',
				'meta_description' => GAME_NAME . 'の情報交換を行うならGame Usersをぜひ利用してください。フレンド募集・メンバー募集ができる募集掲示板もあります。'
			);
		}


		return $return_arr;

	}



	/**
	* BBS UC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_uc()
	{

		// --------------------------------------------------
		//   値セット
		// --------------------------------------------------

		$this->set_db_community_arr();

		$db_community_arr = $this->get_db_community_arr();
		$this->set_community_no($db_community_arr['community_no']);

		$this->set_authority_arr();
		$this->set_login_profile_data_arr_uc();
		$this->set_datetime_now();
		$this->set_online_limit_uc();
		$this->set_anonymity_uc();


		// --------------------------------------------------
		//   権限取得
		// --------------------------------------------------

		$authority_arr = $this->get_authority_arr();


		// --------------------------------------------------
		//   権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['read_bbs'] and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//   スレッド、値セット、取得
		// --------------------------------------------------

		$this->set_db_bbs_thread_arr_uc();
		$this->set_db_bbs_thread_total_uc();


		// $db_bbs_thread_arr = $this->get_db_bbs_thread_arr();
		// if ( ! defined('BBS_ID')) define('BBS_ID', $db_bbs_thread_arr[0]['bbs_id']);


		// --------------------------------------------------
		//   スレッドがない場合、処理停止
		// --------------------------------------------------

		//if ($this->get_db_bbs_thread_total() == 0) return null;


		// --------------------------------------------------
		//    スレッド、コメント、返信処理
		// --------------------------------------------------

		$this->set_process_db_bbs_thread_arr_uc($this->get_db_bbs_thread_total());


		// --------------------------------------------------
		//    Personal Box用　プロフィール　値入力
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_view');

		$view->set('datetime_now', $this->get_datetime_now());

		$view->set('type_gc_or_uc', 'uc');
		$view->set('no', $this->get_community_no());
		$view->set('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('thread_arr', $this->get_db_bbs_thread_arr());
		$view->set('comment_arr', $this->get_db_bbs_comment_arr());
		$view->set('reply_arr', $this->get_db_bbs_reply_arr());
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());
		$view->set('login_profile_data_arr', $this->get_login_profile_data_arr());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		// ページャー
		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $this->get_db_bbs_thread_total());
		$view->set('pagination_limit', LIMIT_BBS_THREAD);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_argument_arr', array("'uc'", $this->get_community_no(), 1, 1, 1));


		if ($this->get_page() === 1)
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'bbs',
					'content' => 'index',
					'function' => 'readBbs',
					'type' => 'uc',
					'no' => $this->get_community_no(),
					'page' => $this->get_page()
				],
				'url' => BBS_URL . '/' . $this->get_page(),
				'meta_title' => COMMUNITY_NAME . ' - Game Users',
				'meta_keywords' => 'コミュニティ',
				'meta_description' => $db_community_arr['description_mini']
			);
		}
		else
		{
			$return_arr = array(
				'code' => $view->render(),
				'state' => [
					'group' => 'bbs',
					'content' => 'index',
					'function' => 'readBbs',
					'type' => 'uc',
					'no' => $this->get_community_no(),
					'page' => $this->get_page()
				],
				'url' => BBS_URL . '/' . $this->get_page(),
				'meta_title' => COMMUNITY_NAME . ' - 掲示板 Page ' . $this->get_page(),
				'meta_keywords' => 'コミュニティ,掲示板',
				'meta_description' => $db_community_arr['description_mini']
			);
		}


		// \Debug::dump($db_community_arr, $return_arr);
		// exit();

		return $return_arr;

	}



	/**
	* BBS GC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_appoint_gc()
	{


		// --------------------------------------------------
		//   BBS 値セット
		// --------------------------------------------------

		// スレッド
		$this->set_db_bbs_thread_arr_gc();

		// スレッド取得
		$db_bbs_thread_arr = $this->get_db_bbs_thread_arr();


		//\Debug::dump($db_bbs_thread_arr);

		// ゲームNo　違うゲームのBBSを読み込もうとした場合、Not Foundエラー
		if ($this->get_game_no() and $this->get_game_no() != $db_bbs_thread_arr[0]['game_no'])
		{
			throw new \HttpNotFoundException;
		}
		else
		{
			$this->set_game_no($db_bbs_thread_arr[0]['game_no']);
		}


		// スレッド総数
		$this->set_db_bbs_thread_total_gc();

		// スレッド、コメント、返信をセットして処理
		$this->set_process_db_bbs_thread_arr_gc($this->get_db_bbs_thread_total());


		// --------------------------------------------------
		//   個別
		// --------------------------------------------------

		if ( ! defined('INDIVIDUAL')) define('INDIVIDUAL', true);
		if ( ! defined('BBS_ID')) define('BBS_ID', $db_bbs_thread_arr[0]['bbs_id']);


		// --------------------------------------------------
		//    Personal Box用　プロフィール　値入力
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();


		// --------------------------------------------------
		//   コミュニティ 値セット
		// --------------------------------------------------

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();


		// --------------------------------------------------
		//   スレッドがない場合、処理停止
		// --------------------------------------------------

		if ($this->get_db_bbs_thread_total() == 0) return null;




		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_thread_arr';
			\Debug::dump($db_bbs_thread_arr);

			echo '$this->get_db_bbs_thread_total()';
			\Debug::dump($this->get_db_bbs_thread_total());

			echo '$this->get_login_profile_data_arr()';
			\Debug::dump($this->get_login_profile_data_arr());

			echo '$this->get_online_limit';
			\Debug::dump($this->get_online_limit());

			echo '$this->get_anonymity()';
			\Debug::dump($this->get_anonymity());

		}
		*/

		//exit();


		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_view');

		$view->set_safe('agent_type', AGENT_TYPE);
		$view->set_safe('host', HOST);
		$view->set_safe('user_agent', USER_AGENT);
		$view->set_safe('login_user_no', USER_NO);
		$view->set('uri_base', URI_BASE);
		$view->set_safe('app_mode', APP_MODE);

		$view->set('datetime_now', $this->get_datetime_now());

		$view->set('type_gc_or_uc', 'gc');
		$view->set('no', $this->get_game_no());
		$view->set('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('thread_arr', $this->get_db_bbs_thread_arr());
		$view->set('comment_arr', $this->get_db_bbs_comment_arr());
		$view->set('reply_arr', $this->get_db_bbs_reply_arr());
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());
		$view->set('login_profile_data_arr', $this->get_login_profile_data_arr());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		$view->set_safe('appoint', true);

		// ページャー
		$view->set('pagination_comment_page', $this->get_page_comment());

		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $this->get_db_bbs_thread_total());
		$view->set('pagination_limit', LIMIT_BBS_THREAD);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_argument_arr', array("'gc'", $this->get_game_no(), true));


		$temp_arr = $this->get_db_bbs_thread_arr()[0];

		$add_page_url = ($this->get_page_comment() === 1 or is_null($this->get_page_comment())) ? null : '/' . $this->get_page_comment();

		$return_arr = array(
			'code' => $view->render(),
			'state' => [
				'group' => 'bbs',
				'content' => 'index',
				'function' => 'readBbsIndividual',
				'type' => 'gc',
				'no' => $this->get_game_no(),
				'bbsId' => BBS_ID,
				'pageComment' => $this->get_page_comment()
			],
			'url' => BBS_URL . '/'. BBS_ID . $add_page_url,
			'meta_title' => GAME_NAME . ' - ' . $temp_arr['title'],
			'meta_keywords' => GAME_NAME . ',交流掲示板',
			'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $temp_arr['comment'])
		);


		return $return_arr;

	}




	/**
	* BBS UC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_appoint_uc()
	{


		// --------------------------------------------------
		//   BBS 値セット
		// --------------------------------------------------

		// スレッド
		$this->set_db_bbs_thread_arr_uc();

		// スレッド取得
		$db_bbs_thread_arr = $this->get_db_bbs_thread_arr();

//\Debug::dump($this->page, $db_bbs_thread_arr);
// exit();


		// コミュニティNo　違うコミュニティのBBSを読み込もうとした場合、Not Foundエラー
		if ($this->get_community_no() and $this->get_community_no() != $db_bbs_thread_arr[0]['community_no'])
		{
			throw new \HttpNotFoundException;
		}
		else
		{
			$this->set_community_no($db_bbs_thread_arr[0]['community_no']);
		}


		// スレッド総数
		$this->set_db_bbs_thread_total_uc();

		// スレッド、コメント、返信をセットして処理
		$this->set_process_db_bbs_thread_arr_uc($this->get_db_bbs_thread_total());



		// --------------------------------------------------
		//   個別
		// --------------------------------------------------

		if ( ! defined('INDIVIDUAL')) define('INDIVIDUAL', true);
		if ( ! defined('BBS_ID')) define('BBS_ID', $db_bbs_thread_arr[0]['bbs_id']);


		// --------------------------------------------------
		//    Personal Box用　プロフィール　値入力
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティ
		$this->set_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['read_bbs'] and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//   スレッドがない場合、処理停止
		// --------------------------------------------------

		if ($this->get_db_bbs_thread_total() == 0) return null;





		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_view');

		$view->set_safe('agent_type', AGENT_TYPE);
		$view->set_safe('host', HOST);
		$view->set_safe('user_agent', USER_AGENT);
		$view->set_safe('login_user_no', USER_NO);
		$view->set('uri_base', URI_BASE);
		$view->set_safe('app_mode', APP_MODE);

		$view->set('datetime_now', $this->get_datetime_now());

		$view->set('type_gc_or_uc', 'uc');
		$view->set('no', $this->get_community_no());
		$view->set('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('thread_arr', $this->get_db_bbs_thread_arr());
		$view->set('comment_arr', $this->get_db_bbs_comment_arr());
		$view->set('reply_arr', $this->get_db_bbs_reply_arr());
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());
		$view->set('login_profile_data_arr', $this->get_login_profile_data_arr());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		$view->set_safe('appoint', true);

		// ページャー
		$view->set('pagination_comment_page', $this->get_page_comment());

		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $this->get_db_bbs_thread_total());
		$view->set('pagination_limit', LIMIT_BBS_THREAD);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_argument_arr', array("'uc'", $this->get_community_no(), true));


		$temp_arr = $this->get_db_bbs_thread_arr()[0];

		$add_page_url = ($this->get_page_comment() === 1 or is_null($this->get_page_comment())) ? null : '/' . $this->get_page_comment();
		$add_page_meta_title = ($this->get_page_comment() != 1) ? ' Page ' . $this->get_page_comment() : null;

		$return_arr = array(
			'code' => $view->render(),
			'state' => [
				'group' => 'bbs',
				'content' => 'index',
				'function' => 'readBbsIndividual',
				'type' => 'uc',
				'no' => $this->get_community_no(),
				'bbsId' => BBS_ID,
				'pageComment' => $this->get_page_comment()
			],
			'url' => BBS_URL . '/'. BBS_ID . $add_page_url,
			'meta_title' => COMMUNITY_NAME . ' - ' . $temp_arr['title'] . $add_page_meta_title,
			'meta_keywords' => COMMUNITY_NAME . ',掲示板',
			'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $temp_arr['comment'])
		);

		return $return_arr;

	}






	/**
	* BBS　コメント　GC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_comment_gc()
	{

		// --------------------------------------------------
		//   BBS 値セット
		// --------------------------------------------------

		// スレッド
		$this->set_db_bbs_thread_arr_gc();

		// スレッド取得
		$db_bbs_thread_arr = $this->get_db_bbs_thread_arr();

		// コメント総数
		$comment_total = (isset($db_bbs_thread_arr[0]['comment_total'])) ? $db_bbs_thread_arr[0]['comment_total'] : 0;

		// コメント、返信をセットして処理
		$this->set_process_db_bbs_comment_arr_gc($comment_total, $this->get_bbs_thread_no(), $this->get_page());

		// コメント取得
		$temp_arr = $this->get_db_bbs_comment_arr();
		$db_bbs_comment_arr = $temp_arr[$this->get_bbs_thread_no()];


		// --------------------------------------------------
		//   強引に追加したコード
		// --------------------------------------------------

		if ($this->get_individual())
		{
			if ( ! defined('INDIVIDUAL')) define('INDIVIDUAL', true);
		}

		if ( ! $this->get_db_bbs_thread_arr())
		{
			$temp_arr = array(
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'page' => 1,
				'limit' => 1
			);

			$db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);
		}

		if ( ! defined('BBS_ID')) define('BBS_ID', $db_bbs_thread_arr[0]['bbs_id']);


		// --------------------------------------------------
		//   Personal Box用　プロフィール　値セット
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ゲームNo
		$this->set_game_no($db_bbs_thread_arr[0]['game_no']);

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();



		// テスト
		//$this->test_output();
		//exit();



		// --------------------------------------------------
		//   コメントがない場合、処理停止
		// --------------------------------------------------

		if ($comment_total == 0) return null;




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_comment_view');

		$view->set_safe('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		$view->set('type_gc_or_uc', 'gc');
		$view->set('no', $this->get_game_no());

		$view->set('comment_arr', $db_bbs_comment_arr);
		$view->set('reply_arr', $this->get_db_bbs_reply_arr());
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());

		$view->set('pagination_comment_page', $this->get_page());
		$view->set('pagination_comment_total', $comment_total);



		$add_page = ($this->page != 1) ? ' Page ' . $this->page : null;

		$return_arr = array(
			'code' => $view->render(),
			'meta_title' => GAME_NAME . ' - ' . $db_bbs_thread_arr[0]['title'] . $add_page,
			'meta_keywords' => GAME_NAME . ',交流掲示板',
			'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $db_bbs_thread_arr[0]['comment'])
		);

		return $return_arr;

	}




	/**
	* BBS　コメント　UC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_comment_uc()
	{

		// --------------------------------------------------
		//   BBS 値セット
		// --------------------------------------------------

		// スレッド
		$this->set_db_bbs_thread_arr_uc();

		// スレッド取得
		$db_bbs_thread_arr = $this->get_db_bbs_thread_arr();
//\Debug::dump($db_bbs_thread_arr);


		// コメント総数
		$comment_total = (isset($db_bbs_thread_arr[0]['comment_total'])) ? $db_bbs_thread_arr[0]['comment_total'] : 0;

		// コメント、返信をセットして処理
		$this->set_process_db_bbs_comment_arr_uc($comment_total, $this->get_bbs_thread_no(), $this->get_page());

		// コメント取得
		$temp_arr = $this->get_db_bbs_comment_arr();
		$db_bbs_comment_arr = $temp_arr[$this->get_bbs_thread_no()];


		// --------------------------------------------------
		//   強引に追加したコード
		// --------------------------------------------------

		if ($this->get_individual())
		{
			if ( ! defined('INDIVIDUAL')) define('INDIVIDUAL', true);
		}

		if ( ! $this->get_db_bbs_thread_arr())
		{
			$temp_arr = array(
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'page' => 1,
				'limit' => 1
			);

			$db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
		}

		if ( ! defined('BBS_ID')) define('BBS_ID', $db_bbs_thread_arr[0]['bbs_id']);

		// if ( ! defined('BBS_ID'))
		// {
		// 	if ( ! $this->get_db_bbs_thread_arr())
		// 	{
		// 		$temp_arr = array(
		// 			'bbs_thread_no' => $this->get_bbs_thread_no(),
		// 			'page' => 1,
		// 			'limit' => 1
		// 		);
		//
		// 		$db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
		// 	}
		//
		// 	define('BBS_ID', $db_bbs_thread_arr[0]['bbs_id']);
		// 	if ( ! defined('INDIVIDUAL')) define('INDIVIDUAL', true);
		// }


		// --------------------------------------------------
		//   Personal Box用　プロフィール　値セット
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($db_bbs_thread_arr[0]['community_no']);

		// コミュニティ
		$this->set_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['read_bbs'] and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//   コメントがない場合、処理停止
		// --------------------------------------------------

		if ($comment_total == 0) return null;




		// テスト
		//$this->test_output();
		//exit();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_comment_view');

		$view->set_safe('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		$view->set('type_gc_or_uc', 'uc');
		$view->set('no', $this->get_community_no());

		$view->set('comment_arr', $db_bbs_comment_arr);
		$view->set('reply_arr', $this->get_db_bbs_reply_arr());
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());

		$view->set('pagination_comment_page', $this->get_page());
		$view->set('pagination_comment_total', $comment_total);



		$add_page = ($this->page != 1) ? ' Page ' . $this->page : null;

		$return_arr = array(
			'code' => $view->render(),
			'meta_title' => COMMUNITY_NAME . ' - ' . $db_bbs_thread_arr[0]['title'] . $add_page,
			'meta_keywords' => COMMUNITY_NAME . ',掲示板',
			'meta_description' => str_replace(array("\r\n","\r","\n"), ' ', $db_bbs_thread_arr[0]['comment'])
		);


		return $return_arr;

	}




	/**
	* BBS 返信　GC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_reply_gc()
	{

		// --------------------------------------------------
		//   返信　値セット
		// --------------------------------------------------

		// 返信取得
		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$db_bbs_comment_arr = $this->model_bbs->get_bbs_comment_gc($temp_arr);

		// 返信総数
		$reply_total = (isset($db_bbs_comment_arr[0]['reply_total'])) ? $db_bbs_comment_arr[0]['reply_total'] : 0;

		// コメント、返信をセットして処理
		$this->set_process_db_bbs_reply_arr_gc($reply_total, $this->get_bbs_comment_no(), $this->get_page());

		// 返信取得
		$temp_arr = $this->get_db_bbs_reply_arr();
		$db_bbs_reply_arr = $temp_arr[$this->get_bbs_comment_no()];



		// --------------------------------------------------
		//   Personal Box用　プロフィール　値セット
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ゲームNo
		$this->set_game_no($db_bbs_reply_arr[0]['game_no']);

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();


		// --------------------------------------------------
		//   コメントがない場合、処理停止
		// --------------------------------------------------

		if ($reply_total == 0) return null;




		// テスト
		//$this->test_output();
		//exit();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_reply_view');

		$view->set_safe('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		$view->set('type_gc_or_uc', 'gc');
		$view->set('no', $this->get_game_no());

		$view->set('reply_arr', $db_bbs_reply_arr);
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());

		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $reply_total);


		return $view->render();

	}




	/**
	* BBS 返信　UC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_reply_uc()
	{

		// --------------------------------------------------
		//   返信　値セット
		// --------------------------------------------------

		// 返信取得
		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$db_bbs_comment_arr = $this->model_bbs->get_bbs_comment_uc($temp_arr);

		// 返信総数
		$reply_total = (isset($db_bbs_comment_arr[0]['reply_total'])) ? $db_bbs_comment_arr[0]['reply_total'] : 0;

		// コメント、返信をセットして処理
		$this->set_process_db_bbs_reply_arr_uc($reply_total, $this->get_bbs_comment_no(), $this->get_page());

		// 返信取得
		$temp_arr = $this->get_db_bbs_reply_arr();
		$db_bbs_reply_arr = $temp_arr[$this->get_bbs_comment_no()];



		// --------------------------------------------------
		//   Personal Box用　プロフィール　値セット
		// --------------------------------------------------

		$this->set_bbs_user_data_arr();
		$this->set_bbs_profile_arr();


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($db_bbs_comment_arr[0]['community_no']);

		// コミュニティ
		$this->set_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['read_bbs'] and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//   コメントがない場合、処理停止
		// --------------------------------------------------

		if ($reply_total == 0) return null;




		// テスト
		//$this->test_output();
		//exit();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/bbs_reply_view');

		$view->set_safe('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('authority_arr', $this->get_authority_arr());

		$view->set('type_gc_or_uc', 'uc');
		$view->set('no', $this->get_community_no());

		$view->set('reply_arr', $db_bbs_reply_arr);
		$view->set('user_data_arr', $this->get_bbs_user_data_arr());
		$view->set('profile_arr', $this->get_bbs_profile_arr());

		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $reply_total);


		return $view->render();

	}




	/**
	* BBS　編集フォーム　GC
	*
	* @return string HTMLコード
	*/
	public function get_code_edit_bbs_form_gc()
	{

		// --------------------------------------------------
		//   値セット
		// --------------------------------------------------

		// スレッド取得
		$temp_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'page' => 1,
			'limit' => 1
		);

		$temp_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);
		$db_bbs_thread_arr = $temp_arr[0];

		// 画像＆動画アンシリアライズ
		$db_bbs_thread_arr['image'] = (isset($db_bbs_thread_arr['image'])) ? unserialize($db_bbs_thread_arr['image']) : null;
		$db_bbs_thread_arr['movie'] = (isset($db_bbs_thread_arr['movie'])) ? unserialize($db_bbs_thread_arr['movie']) : null;


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ゲームNo
		$this->set_game_no($db_bbs_thread_arr['game_no']);

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();


		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_thread_arr) and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//    画像URL設定
		// --------------------------------------------------

		$image_url_base = URI_BASE . 'assets/img/bbs_gc/thread/' . $db_bbs_thread_arr['bbs_thread_no'] . '/';



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_thread_arr';
			\Debug::dump($db_bbs_thread_arr);

			echo '$authority_edit';
			\Debug::dump($authority_edit);

			//echo '$profile_arr';
			//\Debug::dump($profile_arr);

		}
		*/
		//exit();





		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/form_common_ver2_view');

		// $view->set_safe('app_mode', APP_MODE);
		// $view->set('uri_base', URI_BASE);
		// $view->set('login_user_no', USER_NO);
		$view->set('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('data_arr', $db_bbs_thread_arr);
		$view->set('profile_arr', $this->get_login_profile_data_arr());
		$view->set('image_url_base', $image_url_base);

		$view->set('func_name', 'saveBbsThread');
		$view->set('func_argument_arr', array("'gc'", $this->get_game_no(), $this->get_bbs_thread_no()));
		$view->set('func_name_return', 'removeEditBbsThreadForm');
		$view->set('func_argument_return_arr', array("'gc'", $this->get_game_no(), $this->get_bbs_thread_no()));
		$view->set('func_name_delete', 'deleteBbsThread');
		$view->set('func_argument_delete_arr', array("'gc'", $this->get_bbs_thread_no()));


		return $view->render();

	}




	/**
	* BBS　編集フォーム　UC
	*
	* @return string HTMLコード
	*/
	public function get_code_edit_bbs_form_uc()
	{

		// --------------------------------------------------
		//   値セット
		// --------------------------------------------------

		// スレッド取得
		$temp_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'page' => 1,
			'limit' => 1
		);

		$temp_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
		$db_bbs_thread_arr = $temp_arr[0];

		// 画像＆動画アンシリアライズ
		$db_bbs_thread_arr['image'] = (isset($db_bbs_thread_arr['image'])) ? unserialize($db_bbs_thread_arr['image']) : null;
		$db_bbs_thread_arr['movie'] = (isset($db_bbs_thread_arr['movie'])) ? unserialize($db_bbs_thread_arr['movie']) : null;


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($db_bbs_thread_arr['community_no']);

		// コミュニティ
		$this->set_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_thread_arr) and ! \Auth::member(100)) return null;



		// --------------------------------------------------
		//    画像URL設定
		// --------------------------------------------------

		$image_url_base = URI_BASE . 'assets/img/bbs_uc/thread/' . $db_bbs_thread_arr['bbs_thread_no'] . '/';



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_thread_arr';
			\Debug::dump($db_bbs_thread_arr);

			echo '$authority_edit';
			\Debug::dump($authority_edit);

			//echo '$profile_arr';
			//\Debug::dump($profile_arr);

		}
		*/
		//exit();





		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/form_common_ver2_view');

		// $view->set_safe('app_mode', APP_MODE);
		// $view->set('uri_base', URI_BASE);
		// $view->set('login_user_no', USER_NO);
		$view->set('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('data_arr', $db_bbs_thread_arr);
		$view->set('profile_arr', $this->get_login_profile_data_arr());
		$view->set('image_url_base', $image_url_base);

		$view->set('func_name', 'saveBbsThread');
		$view->set('func_argument_arr', array("'uc'", $this->get_community_no(), $this->get_bbs_thread_no()));
		$view->set('func_name_return', 'removeEditBbsThreadForm');
		$view->set('func_argument_return_arr', array("'uc'", $this->get_community_no(), $this->get_bbs_thread_no()));
		$view->set('func_name_delete', 'deleteBbsThread');
		$view->set('func_argument_delete_arr', array("'uc'", $this->get_bbs_thread_no()));


		return $view->render();

	}





	/**
	* コメント　編集フォーム　GC
	*
	* @return string HTMLコード
	*/
	public function get_code_edit_bbs_comment_form_gc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$temp_arr = $this->model_bbs->get_bbs_comment_gc($temp_arr);
		$db_bbs_comment_arr = $temp_arr[0];
		$this->set_bbs_thread_no_gc($db_bbs_comment_arr['bbs_thread_no']);

		// 画像＆動画アンシリアライズ
		$db_bbs_comment_arr['image'] = (isset($db_bbs_comment_arr['image'])) ? unserialize($db_bbs_comment_arr['image']) : null;
		$db_bbs_comment_arr['movie'] = (isset($db_bbs_comment_arr['movie'])) ? unserialize($db_bbs_comment_arr['movie']) : null;


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ゲームNo
		$this->set_game_no($db_bbs_comment_arr['game_no']);

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_comment_arr) and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//    画像URL設定
		// --------------------------------------------------

		$image_url_base = URI_BASE . 'assets/img/bbs_gc/comment/' . $db_bbs_comment_arr['bbs_comment_no'] . '/';



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_arr';
			\Debug::dump($db_bbs_arr);

			echo '$authority_edit';
			\Debug::dump($authority_edit);

			//echo '$profile_arr';
			//\Debug::dump($profile_arr);

		}
		*/
		//exit();





		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/form_common_ver2_view');

		// $view->set_safe('app_mode', APP_MODE);
		// $view->set('uri_base', URI_BASE);
		// $view->set('login_user_no', USER_NO);
		$view->set('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('data_arr', $db_bbs_comment_arr);
		$view->set('profile_arr', $this->get_login_profile_data_arr());
		$view->set('image_url_base', $image_url_base);
		$view->set_safe('title_off', true);

		$view->set('func_name', 'saveBbsComment');
		$view->set('func_argument_arr', array("'gc'", $this->get_bbs_thread_no(), $this->get_bbs_comment_no()));
		$view->set('func_name_return', 'removeEditBbsCommentForm');
		$view->set('func_argument_return_arr', array("'gc'", $this->get_bbs_comment_no()));
		$view->set('func_name_delete', 'deleteBbsComment');
		$view->set('func_argument_delete_arr', array("'gc'", $this->get_bbs_comment_no()));


		return $view->render();

	}





	/**
	* コメント　編集フォーム　UC
	*
	* @return string HTMLコード
	*/
	public function get_code_edit_bbs_comment_form_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$temp_arr = $this->model_bbs->get_bbs_comment_uc($temp_arr);
		$db_bbs_comment_arr = $temp_arr[0];
		$this->set_bbs_thread_no_uc($db_bbs_comment_arr['bbs_thread_no']);

		// 画像＆動画アンシリアライズ
		$db_bbs_comment_arr['image'] = (isset($db_bbs_comment_arr['image'])) ? unserialize($db_bbs_comment_arr['image']) : null;
		$db_bbs_comment_arr['movie'] = (isset($db_bbs_comment_arr['movie'])) ? unserialize($db_bbs_comment_arr['movie']) : null;


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($db_bbs_comment_arr['community_no']);

		// コミュニティ
		$this->set_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_comment_arr) and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//    画像URL設定
		// --------------------------------------------------

		$image_url_base = URI_BASE . 'assets/img/bbs_uc/comment/' . $db_bbs_comment_arr['bbs_comment_no'] . '/';



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_arr';
			\Debug::dump($db_bbs_arr);

			echo '$authority_edit';
			\Debug::dump($authority_edit);

			//echo '$profile_arr';
			//\Debug::dump($profile_arr);

		}
		*/
		//exit();





		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/form_common_ver2_view');

		// $view->set_safe('app_mode', APP_MODE);
		// $view->set('uri_base', URI_BASE);
		// $view->set('login_user_no', USER_NO);
		$view->set('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('data_arr', $db_bbs_comment_arr);
		$view->set('profile_arr', $this->get_login_profile_data_arr());
		$view->set('image_url_base', $image_url_base);
		$view->set_safe('title_off', true);

		$view->set('func_name', 'saveBbsComment');
		$view->set('func_argument_arr', array("'uc'", $this->get_bbs_thread_no(), $this->get_bbs_comment_no()));
		$view->set('func_name_return', 'removeEditBbsCommentForm');
		$view->set('func_argument_return_arr', array("'uc'", $this->get_bbs_comment_no()));
		$view->set('func_name_delete', 'deleteBbsComment');
		$view->set('func_argument_delete_arr', array("'uc'", $this->get_bbs_comment_no()));


		return $view->render();

	}





	/**
	* 返信　編集フォーム　GC
	*
	* @return string HTMLコード
	*/
	public function get_code_edit_bbs_reply_form_gc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_reply_no' => $this->get_bbs_reply_no(),
			'page' => 1,
			'limit' => 1
		);

		$temp_arr = $this->model_bbs->get_bbs_reply_gc($temp_arr);
		$db_bbs_reply_arr = $temp_arr[0];

		// 画像＆動画アンシリアライズ
		$db_bbs_reply_arr['image'] = (isset($db_bbs_reply_arr['image'])) ? unserialize($db_bbs_reply_arr['image']) : null;
		$db_bbs_reply_arr['movie'] = (isset($db_bbs_reply_arr['movie'])) ? unserialize($db_bbs_reply_arr['movie']) : null;


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ゲームNo
		$this->set_game_no($db_bbs_reply_arr['game_no']);

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();


		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_reply_arr) and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//    画像URL設定
		// --------------------------------------------------

		$image_url_base = URI_BASE . 'assets/img/bbs_gc/reply/' . $this->get_bbs_reply_no() . '/';


		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$this->get_bbs_reply_no()';
			\Debug::dump($this->get_bbs_reply_no());


			echo '$db_bbs_reply_arr';
			\Debug::dump($db_bbs_reply_arr);

			echo '$this->check_bbs_edit_authority($db_bbs_reply_arr)';
			\Debug::dump($this->check_bbs_edit_authority($db_bbs_reply_arr));

		}
		*/

		//exit();





		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/form_common_ver2_view');

		// $view->set_safe('app_mode', APP_MODE);
		// $view->set('uri_base', URI_BASE);
		// $view->set('login_user_no', USER_NO);
		$view->set('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('data_arr', $db_bbs_reply_arr);
		$view->set('profile_arr', $this->get_login_profile_data_arr());
		$view->set('image_url_base', $image_url_base);
		$view->set_safe('title_off', true);

		$view->set('func_name', 'saveBbsReply');
		$view->set('func_argument_arr', array("'gc'", $db_bbs_reply_arr['bbs_comment_no'], $this->get_bbs_reply_no()));
		$view->set('func_name_return', 'removeEditBbsReplyForm');
		$view->set('func_argument_return_arr', array("'gc'", $this->get_bbs_reply_no()));
		$view->set('func_name_delete', 'deleteBbsReply');
		$view->set('func_argument_delete_arr', array("'gc'", $this->get_bbs_reply_no()));


		return $view->render();

	}




	/**
	* 返信　編集フォーム　UC
	*
	* @return string HTMLコード
	*/
	public function get_code_edit_bbs_reply_form_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_reply_no' => $this->get_bbs_reply_no(),
			'page' => 1,
			'limit' => 1
		);

		$temp_arr = $this->model_bbs->get_bbs_reply_uc($temp_arr);
		$db_bbs_reply_arr = $temp_arr[0];

		// 画像＆動画アンシリアライズ
		$db_bbs_reply_arr['image'] = (isset($db_bbs_reply_arr['image'])) ? unserialize($db_bbs_reply_arr['image']) : null;
		$db_bbs_reply_arr['movie'] = (isset($db_bbs_reply_arr['movie'])) ? unserialize($db_bbs_reply_arr['movie']) : null;


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($db_bbs_reply_arr['community_no']);

		// コミュニティ
		$this->set_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();


		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_reply_arr) and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//    画像URL設定
		// --------------------------------------------------

		$image_url_base = URI_BASE . 'assets/img/bbs_uc/reply/' . $this->get_bbs_reply_no() . '/';



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_arr';
			\Debug::dump($db_bbs_arr);

			echo '$authority_edit';
			\Debug::dump($authority_edit);

			//echo '$profile_arr';
			//\Debug::dump($profile_arr);

		}
		*/
		//exit();





		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/form_common_ver2_view');

		// $view->set_safe('app_mode', APP_MODE);
		// $view->set('uri_base', URI_BASE);
		// $view->set('login_user_no', USER_NO);
		$view->set('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('data_arr', $db_bbs_reply_arr);
		$view->set('profile_arr', $this->get_login_profile_data_arr());
		$view->set('image_url_base', $image_url_base);
		$view->set_safe('title_off', true);

		$view->set('func_name', 'saveBbsReply');
		$view->set('func_argument_arr', array("'uc'", $db_bbs_reply_arr['bbs_comment_no'], $this->get_bbs_reply_no()));
		$view->set('func_name_return', 'removeEditBbsReplyForm');
		$view->set('func_argument_return_arr', array("'uc'", $this->get_bbs_reply_no()));
		$view->set('func_name_delete', 'deleteBbsReply');
		$view->set('func_argument_delete_arr', array("'uc'", $this->get_bbs_reply_no()));


		return $view->render();

	}




	/**
	* 返信　新規投稿フォーム　GC
	*
	* @return string HTMLコード
	*/
	public function get_code_write_bbs_reply_form_gc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		// コメント取得
		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$temp_arr = $this->model_bbs->get_bbs_comment_gc($temp_arr);
		$db_bbs_comment_arr = $temp_arr[0];


		// 返信取得
		if ($this->get_bbs_reply_no())
		{

			$temp_arr = array(
				'bbs_reply_no' => $this->get_bbs_reply_no(),
				'page' => 1,
				'limit' => 1
			);

			$temp_arr = $this->model_bbs->get_bbs_reply_gc($temp_arr);
			$db_bbs_reply_arr = $temp_arr[0];


			// --------------------------------------------------
			//   関係がないコメント、返信を読み込もうとした場合、処理停止
			// --------------------------------------------------

			if ($db_bbs_reply_arr['bbs_comment_no'] != $this->get_bbs_comment_no()) return null;

		}


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ゲームNo
		$this->set_game_no($db_bbs_comment_arr['game_no']);

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();


		// --------------------------------------------------
		//    投稿者ハンドルネーム取得
		// --------------------------------------------------

		if (isset($db_bbs_reply_arr))
		{
			if ($db_bbs_reply_arr['anonymity'])
			{
				$comment_to_handle_name = null;
			}
			else if ($db_bbs_reply_arr['profile_no'])
			{
				$db_profile_arr = $this->model_user->get_profile($db_bbs_reply_arr['profile_no']);
				$comment_to_handle_name = $db_profile_arr['handle_name'];
			}
			else if ($db_bbs_reply_arr['user_no'])
			{
				$db_profile_arr = $this->model_user->get_user_data($db_bbs_reply_arr['user_no'], null);
				$comment_to_handle_name = $db_profile_arr['handle_name'];
			}
			else
			{
				$comment_to_handle_name = $db_bbs_reply_arr['handle_name'];
			}

			if ($comment_to_handle_name)
			{
				$comment_to = $comment_to_handle_name . 'さんへ' . "\n\n";
			}
			else
			{
				$comment_to = '';
			}

		}
		else
		{
			$comment_to = null;
		}






		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_comment_arr';
			\Debug::dump($db_bbs_comment_arr);

			if (isset($db_bbs_reply_arr))
			{
				echo '$db_bbs_reply_arr';
				\Debug::dump($db_bbs_reply_arr);
			}

			echo '$this->get_bbs_comment_no()';
			\Debug::dump($this->get_bbs_comment_no());

			echo '$this->get_bbs_reply_no()';
			\Debug::dump($this->get_bbs_reply_no());

		}
		*/


		// テスト
		//$this->test_output();
		//exit();




		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------)

		//$s = (AGENT_TYPE) ? '_s' : null;

		$view = \View::forge('parts/form_common_ver2_view');

		$view->set('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('profile_arr', $this->get_login_profile_data_arr());
		$view->set_safe('title_off', true);
		$view->set('comment_to', $comment_to);

		$view->set('func_name', 'saveBbsReply');
		$view->set('func_argument_arr', array("'gc'", $this->get_bbs_comment_no(), 'null'));
		$view->set('func_name_return', 'removeWriteBbsReplyForm');
		$view->set('func_argument_return_arr', array("'uc'", $this->get_bbs_comment_no()));

		$code = '<div class="bbs_reply_enclosure_form">';
		$code .= $view->render();
		$code .= '</div>';


		return $code;

	}




	/**
	* 返信　新規投稿フォーム　UC
	*
	* @return string HTMLコード
	*/
	public function get_code_write_bbs_reply_form_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		// コメント取得
		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$temp_arr = $this->model_bbs->get_bbs_comment_uc($temp_arr);
		$db_bbs_comment_arr = $temp_arr[0];


		// 返信取得
		if ($this->get_bbs_reply_no())
		{

			$temp_arr = array(
				'bbs_reply_no' => $this->get_bbs_reply_no(),
				'page' => 1,
				'limit' => 1
			);

			$temp_arr = $this->model_bbs->get_bbs_reply_uc($temp_arr);
			$db_bbs_reply_arr = $temp_arr[0];


			// --------------------------------------------------
			//   関係がないコメント、返信を読み込もうとした場合、処理停止
			// --------------------------------------------------

			if ($db_bbs_reply_arr['bbs_comment_no'] != $this->get_bbs_comment_no()) return null;

		}


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($db_bbs_comment_arr['community_no']);

		// コミュニティ
		$this->set_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();


		//\Debug::dump($db_bbs_comment_arr);


		// --------------------------------------------------
		//    投稿者ハンドルネーム取得
		// --------------------------------------------------

		if (isset($db_bbs_reply_arr))
		{
			if ($db_bbs_reply_arr['anonymity'])
			{
				$comment_to_handle_name = null;
			}
			else if ($db_bbs_reply_arr['profile_no'])
			{
				$db_profile_arr = $this->model_user->get_profile($db_bbs_reply_arr['profile_no']);
				$comment_to_handle_name = $db_profile_arr['handle_name'];
			}
			else if ($db_bbs_reply_arr['user_no'])
			{
				$db_profile_arr = $this->model_user->get_user_data($db_bbs_reply_arr['user_no'], null);
				$comment_to_handle_name = $db_profile_arr['handle_name'];
			}
			else
			{
				$comment_to_handle_name = $db_bbs_reply_arr['handle_name'];
			}

			if ($comment_to_handle_name)
			{
				$comment_to = $comment_to_handle_name . 'さんへ' . "\n\n";
			}
			else
			{
				$comment_to = null;
			}
		}
		else
		{
			$comment_to = null;
		}






		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_comment_arr';
			\Debug::dump($db_bbs_comment_arr);

			if (isset($db_bbs_reply_arr))
			{
				echo '$db_bbs_reply_arr';
				\Debug::dump($db_bbs_reply_arr);
			}

			echo '$this->get_bbs_comment_no()';
			\Debug::dump($this->get_bbs_comment_no());

			echo '$this->get_bbs_reply_no()';
			\Debug::dump($this->get_bbs_reply_no());

		}
		*/


		// テスト
		//$this->test_output();
		//exit();


		//\Debug::dump('$this->get_anonymity() = ' . $this->get_anonymity());

		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------)

		$view = \View::forge('parts/form_common_ver2_view');

		// $view->set_safe('app_mode', APP_MODE);
		// $view->set('uri_base', URI_BASE);
		// $view->set('login_user_no', USER_NO);
		$view->set('datetime_now', $this->get_datetime_now());
		$view->set_safe('online_limit', $this->get_online_limit());
		$view->set_safe('anonymity', $this->get_anonymity());

		$view->set('profile_arr', $this->get_login_profile_data_arr());
		$view->set_safe('title_off', true);
		$view->set('comment_to', $comment_to);

		$view->set('func_name', 'saveBbsReply');
		$view->set('func_argument_arr', array("'uc'", $this->get_bbs_comment_no(), 'null'));
		$view->set('func_name_return', 'removeWriteBbsReplyForm');
		$view->set('func_argument_return_arr', array("'uc'", $this->get_bbs_comment_no()));

		$code = '<div class="bbs_reply_enclosure_form">';
		$code .= $view->render();
		$code .= '</div>';


		return $code;

	}





	/**
	* スレッド作成・編集　GC
	*
	* @return string HTMLコード
	*/
	public function save_bbs_thread_gc()
	{
		//return 'AAA';
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		if ($this->get_bbs_thread_no())
		{
			$temp_arr = array(
				'type' => 'uc',
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'page' => 1,
				'limit' => 1
			);

			$result_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);
			$db_bbs_thread_arr = $result_arr[0];

			$bbs_id = $db_bbs_thread_arr['bbs_id'];
		}
		else
		{
			$bbs_id = $this->model_bbs->get_free_bbs_id(array('type' => 'gc'));
		}


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();


		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ($this->get_bbs_thread_no())
		{
			if ( ! $this->check_bbs_edit_authority($db_bbs_thread_arr) and ! \Auth::member(100)) return null;
		}


		// --------------------------------------------------
		//   削除済みユーザー
		// --------------------------------------------------

		if ($this->get_login_profile_data_arr() === false) return null;


		// --------------------------------------------------
		//   画像検証
		// --------------------------------------------------

		$result_check_upload_image_arr = $this->original_func_common->check_upload_image(array('image_1'), \Config::get('limit_bbs_thread_image'));

		if ($result_check_upload_image_arr[1])
		{
			throw new \Exception('アップロードされた画像に問題があります。');
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

		if (isset($login_profile_data_arr['profile_no']))
		{
			$user_no = (int) $login_profile_data_arr['author_user_no'];
			$profile_no = (int) $login_profile_data_arr['profile_no'];
		}
		else if (isset($login_profile_data_arr['user_no']))
		{
			$user_no = (int) $login_profile_data_arr['user_no'];
		}


		// ------------------------------
		//    画像
		// ------------------------------

		$image_arr = (isset($db_bbs_thread_arr['image'])) ? $db_bbs_thread_arr['image'] : null;


		// ------------------------------
		//    動画
		// ------------------------------

		$movie_arr = (isset($db_bbs_thread_arr['movie'])) ? $db_bbs_thread_arr['movie'] : null;

		if ($this->get_movie_url())
		{
			if ($movie_arr) $movie_arr = unserialize($movie_arr);
			$movie_arr = $this->original_func_common->return_movie(array($this->get_movie_url()), $movie_arr, \Config::get('limit_bbs_thread_movie'));
			$movie_arr = serialize($movie_arr);
		}
		// 動画削除
		else if ($movie_arr and ! $this->get_movie_url())
		{
			$movie_arr = null;
		}



		$save_bbs_thread_arr = array(
			'bbs_id' => $bbs_id,
			'regi_date' => $this->get_datetime_now(),
			'renewal_date' => $this->get_datetime_now(),
			'sort_date' => $this->get_datetime_now(),
			'user_no' => $user_no,
			'profile_no' => $profile_no,
			'anonymity' => $this->get_anonymity_on(),
			'handle_name' => $this->get_handle_name(),
			'title' => $this->get_title(),
			'comment' => $this->get_comment(),
			'image' => $image_arr,
			'movie' => $movie_arr,
			'host' => HOST,
			'user_agent' => USER_AGENT
		);

		// ゲームNo
		$save_bbs_thread_arr['game_no'] = $this->get_game_no();

		$save_arr = array('bbs_thread_arr' => $save_bbs_thread_arr);



		// --------------------------------------------------
		//    新規書き込み
		// --------------------------------------------------

		if ($this->get_bbs_thread_no() === null)
		{

			$result_arr = $this->model_bbs->insert_bbs_thread_gc($save_arr);

			if ($result_arr['error'])
			{
				throw new \Exception('二重書き込みです。');
			}
			else
			{
				if ( ! $uploaded_image_existence) $dont_edit = true;
				$this->set_bbs_thread_no_gc($result_arr['bbs_thread_no']);
			}



			// --------------------------------------------------
			//   プレゼント抽選エントリーポイント　＋
			// --------------------------------------------------

			// $present_entry_arr = array(
			// 	'regi_date' => $this->get_datetime_now(),
			// 	'user_no' => USER_NO,
			// 	'type_1' => 'gc_bbs_thread',
			// 	'type_2' => 'plus',
			// 	'point' => \Config::get('present_point_gc_bbs_thread')
			// );
			//
			// if (isset($profile_no)) $present_entry_arr['profile_no'] = $profile_no;
			//
			// $this->model_present->plus_minus_point($present_entry_arr);


			//$dont_edit = true;

			// --------------------------------------------------
			//   お知らせ保存
			// --------------------------------------------------
			/*
			$game_list_arr = $this->original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

			$save_notifications_arr = array(
				'regi_date' => $this->get_datetime_now(),
				'target_user_no' => null,
				'community_no' => $this->get_community_no(),
				'game_no' => (int) $game_list_arr[0],
				'type1' => 'uc',
				'type2' => 'bbs_thread',
				'title' => $this->get_title(),
				'anonymity' => $this->get_anonymity_on(),
				'name' => $this->get_handle_name(),
				'comment' => $this->get_comment(),
				'bbs_thread_no' => $this->get_bbs_thread_no()
			);

			if (isset($profile_no)) $save_notifications_arr['profile_no'] = $profile_no;

			$this->model_notifications->save_notifications($save_notifications_arr);
			*/


			// --------------------------------------------------
			//    SNS　データベース保存
			// --------------------------------------------------

			$original_common_text = new \Original\Common\Text();
			$send_sns_id = $original_common_text->random_text_lowercase(16);
			$approval = (\Auth::member(100)) ? 1 : null;

			$save_send_sns_arr[0] = array(
				'send_sns_id' => $send_sns_id,
				'regi_date' => $this->get_datetime_now(),
				'approval' => $approval,
				'type' => 'gc_bbs_thread',
				'game_no' => $this->get_game_no(),
				'bbs_id' => $bbs_id
			);

			$model_sns = new \Model_Sns();
			$model_sns->insert_send_sns($save_send_sns_arr);


			$code_output = true;

		}




		// --------------------------------------------------
		//    編集
		// --------------------------------------------------

		if ($this->get_bbs_thread_no() and empty($dont_edit))
		{


			// --------------------------------------------------
			//   画像設定
			// --------------------------------------------------

			// 保存先パス設定
			$path = DOCROOT . 'assets/img/bbs_gc/thread/' . $this->get_bbs_thread_no() . '/';


			// --------------------------------------------------
			//   画像削除
			// --------------------------------------------------

			if ($this->get_image_1_delete())
			{
				$this->original_func_common->image_delete($path, 'image_', array(1));
				$save_bbs_thread_arr['image'] = null;
			}


			// --------------------------------------------------
			//   画像保存
			// --------------------------------------------------

			if ($uploaded_image_existence)
			{

				$image_name_arr = array('image_1');
				$result_upload_image_arr = $this->original_func_common->zebra_image_save2($path, $image_name_arr, true, \Config::get('limit_bbs_thread_image'));

				if (isset($test))
				{
					echo "count_FILES";
					\Debug::dump(count($_FILES));
					echo "<br>result_upload_image_arr";
					\Debug::dump($result_upload_image_arr);
				}

				if ($result_upload_image_arr['error'])
				{
					throw new \Exception('アップロードされた画像に問題があります。');
				}
				else if ($result_upload_image_arr['size_arr'])
				{
					$save_bbs_thread_arr['image'] = serialize($result_upload_image_arr['size_arr']);
				}

			}


			// --------------------------------------------------
			//    保存用配列編集
			// --------------------------------------------------

			// ----------------------------------------
			//    不要な要素を削除する
			// ----------------------------------------

			unset($save_bbs_thread_arr['bbs_id'], $save_bbs_thread_arr['regi_date'], $save_bbs_thread_arr['user_no'], $save_bbs_thread_arr['profile_no'], $save_bbs_thread_arr['game_no']);


			$save_arr['game_no'] = $this->get_game_no();
			$save_arr['bbs_thread_no'] = $this->get_bbs_thread_no();
			$save_arr['bbs_thread_arr'] = $save_bbs_thread_arr;


			// --------------------------------------------------
			//    データベース更新
			// --------------------------------------------------

			$result_arr = $this->model_bbs->update_bbs_thread_gc($save_arr);

			$code_output = true;

		}



		// --------------------------------------------------
		//   利用規約　同意した最新バージョンをセッションに保存
		// --------------------------------------------------

		\Session::set('user_terms_approval_version', \Config::get('user_terms_version'));


		// --------------------------------------------------
		//   最新の利用規約に同意しているかをチェック、してない場合は保存　ログインユーザーのみ
		// --------------------------------------------------

		$this->model_common->check_and_update_user_terms();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code_bbs_thread_list = null;
		$code_bbs = null;

		if (isset($code_output))
		{
			// BBSスレッド一覧
			$this->set_game_no($this->get_game_no());
			$this->set_page(1);
			$code_bbs_thread_list = $this->get_code_thread_list_gc();

			// BBS
			$code_bbs = $this->get_code_bbs_gc();
		}

		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$this->check_bbs_edit_authority($db_bbs_thread_arr)';
			\Debug::dump($this->check_bbs_edit_authority($db_bbs_thread_arr));

			echo '$login_profile_data_arr';
			\Debug::dump($login_profile_data_arr);


			if (isset($db_bbs_thread_arr))
			{
				echo '$db_bbs_thread_arr';
				\Debug::dump($db_bbs_thread_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_thread_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_thread_arr));
			}


			echo '$uploaded_image_existence';
			\Debug::dump($uploaded_image_existence);

			echo '$save_arr';
			\Debug::dump($save_arr);

			if (isset($save_notifications_arr))
			{
				echo '$save_notifications_arr';
				\Debug::dump($save_notifications_arr);
			}


			//echo '$code_output';
			//\Debug::dump($code_output);


			echo $code;

		}
		*/


		// テスト
		//$this->test_output();
		//exit();



		return array('code_bbs_thread_list' => $code_bbs_thread_list, 'code_bbs' => $code_bbs, 'bbs_id' => $bbs_id);

	}




	/**
	* スレッド作成・編集　UC
	*
	* @return string HTMLコード
	*/
	public function save_bbs_thread_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		if ($this->get_bbs_thread_no())
		{
			$temp_arr = array(
				'type' => 'uc',
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'page' => 1,
				'limit' => 1
			);

			$result_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
			$db_bbs_thread_arr = $result_arr[0];

			$bbs_id = $db_bbs_thread_arr['bbs_id'];
		}
		else
		{
			$bbs_id = $this->model_bbs->get_free_bbs_id(array('type' => 'uc'));
		}


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($this->get_community_no());

		// コミュニティ
		$this->set_db_community_arr();
		$db_community_arr = $this->get_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   BBSを操作する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['operate_bbs_thread'] and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ($this->get_bbs_thread_no())
		{
			if ( ! $this->check_bbs_edit_authority($db_bbs_thread_arr) and ! \Auth::member(100)) return null;
		}


		// --------------------------------------------------
		//   削除済みユーザー
		// --------------------------------------------------

		if ($this->get_login_profile_data_arr() === false) return null;


		// --------------------------------------------------
		//   画像検証
		// --------------------------------------------------

		$result_check_upload_image_arr = $this->original_func_common->check_upload_image(array('image_1'), \Config::get('limit_bbs_thread_image'));

		if ($result_check_upload_image_arr[1])
		{
			throw new \Exception('アップロードされた画像に問題があります。');
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

		if (isset($login_profile_data_arr['profile_no']))
		{
			$user_no = (int) $login_profile_data_arr['author_user_no'];
			$profile_no = (int) $login_profile_data_arr['profile_no'];
		}
		else if (isset($login_profile_data_arr['user_no']))
		{
			$user_no = (int) $login_profile_data_arr['user_no'];
		}


		// ------------------------------
		//    画像
		// ------------------------------

		$image_arr = (isset($db_bbs_thread_arr['image'])) ? $db_bbs_thread_arr['image'] : null;


		// ------------------------------
		//    動画
		// ------------------------------

		$movie_arr = (isset($db_bbs_thread_arr['movie'])) ? $db_bbs_thread_arr['movie'] : null;

		if ($this->get_movie_url())
		{
			if ($movie_arr) $movie_arr = unserialize($movie_arr);
			$movie_arr = $this->original_func_common->return_movie(array($this->get_movie_url()), $movie_arr, \Config::get('limit_bbs_thread_movie'));
			$movie_arr = serialize($movie_arr);
		}
		// 動画削除
		else if ($movie_arr and ! $this->get_movie_url())
		{
			$movie_arr = null;
		}



		$save_bbs_thread_arr = array(
			'bbs_id' => $bbs_id,
			'regi_date' => $this->get_datetime_now(),
			'renewal_date' => $this->get_datetime_now(),
			'sort_date' => $this->get_datetime_now(),
			'user_no' => $user_no,
			'profile_no' => $profile_no,
			'anonymity' => $this->get_anonymity_on(),
			'handle_name' => $this->get_handle_name(),
			'title' => $this->get_title(),
			'comment' => $this->get_comment(),
			'image' => $image_arr,
			'movie' => $movie_arr,
			'host' => HOST,
			'user_agent' => USER_AGENT
		);

		// コミュニティNo
		$save_bbs_thread_arr['community_no'] = $this->get_community_no();

		$save_arr = array('bbs_thread_arr' => $save_bbs_thread_arr);




		// --------------------------------------------------
		//    新規書き込み
		// --------------------------------------------------

		if ($this->get_bbs_thread_no() === null)
		{

			$result_arr = $this->model_bbs->insert_bbs_thread_uc($save_arr);

			if ($result_arr['error'])
			{
				throw new \Exception('二重書き込みです。');
			}
			else
			{
				if ( ! $uploaded_image_existence) $dont_edit = true;
				$this->set_bbs_thread_no_uc($result_arr['bbs_thread_no']);
			}


			//echo '$result_arr';
			//\Debug::dump($result_arr);

			//if ($uploaded_image_existence)


			// --------------------------------------------------
			//   お知らせ保存
			// --------------------------------------------------

			$game_list_arr = $this->original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

			$save_notifications_arr = array(
				'regi_date' => $this->get_datetime_now(),
				'target_user_no' => null,
				'community_no' => $this->get_community_no(),
				'game_no' => (int) $game_list_arr[0],
				'type1' => 'uc',
				'type2' => 'bbs_thread',
				'title' => $this->get_title(),
				'anonymity' => $this->get_anonymity_on(),
				'name' => $this->get_handle_name(),
				'comment' => $this->get_comment(),
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'bbs_id' => $bbs_id
			);

			if (isset($profile_no)) $save_notifications_arr['profile_no'] = $profile_no;

			$this->model_notifications->save_notifications($save_notifications_arr);

			$code_output = true;

		}




		// --------------------------------------------------
		//    編集
		// --------------------------------------------------

		if ($this->get_bbs_thread_no() and empty($dont_edit))
		{

			// --------------------------------------------------
			//   画像設定
			// --------------------------------------------------

			// 保存先パス設定
			$path = DOCROOT . 'assets/img/bbs_uc/thread/' . $this->get_bbs_thread_no() . '/';


			// --------------------------------------------------
			//   画像削除
			// --------------------------------------------------

			if ($this->get_image_1_delete())
			{
				$this->original_func_common->image_delete($path, 'image_', array(1));
				$save_bbs_thread_arr['image'] = null;
			}


			// --------------------------------------------------
			//   画像保存
			// --------------------------------------------------

			if ($uploaded_image_existence)
			{

				$image_name_arr = array('image_1');
				$result_upload_image_arr = $this->original_func_common->zebra_image_save2($path, $image_name_arr, true, \Config::get('limit_bbs_thread_image'));

				if (isset($test))
				{
					echo "count_FILES";
					\Debug::dump(count($_FILES));
					echo "<br>result_upload_image_arr";
					\Debug::dump($result_upload_image_arr);
				}

				if ($result_upload_image_arr['error'])
				{
					throw new \Exception('アップロードされた画像に問題があります。');
				}
				else if ($result_upload_image_arr['size_arr'])
				{
					$save_bbs_thread_arr['image'] = serialize($result_upload_image_arr['size_arr']);
				}

			}


			// --------------------------------------------------
			//    保存用配列編集
			// --------------------------------------------------

			// ----------------------------------------
			//    不要な要素を削除する
			// ----------------------------------------

			unset($save_bbs_thread_arr['bbs_id'], $save_bbs_thread_arr['regi_date'], $save_bbs_thread_arr['user_no'], $save_bbs_thread_arr['profile_no'], $save_bbs_thread_arr['community_no']);


			$save_arr['community_no'] = $this->get_community_no();
			$save_arr['bbs_thread_no'] = $this->get_bbs_thread_no();
			$save_arr['bbs_thread_arr'] = $save_bbs_thread_arr;


			// --------------------------------------------------
			//    データベース更新
			// --------------------------------------------------

			$result_arr = $this->model_bbs->update_bbs_thread_uc($save_arr);

			$code_output = true;

		}



		// --------------------------------------------------
		//   利用規約　同意した最新バージョンをセッションに保存
		// --------------------------------------------------

		\Session::set('user_terms_approval_version', \Config::get('user_terms_version'));


		// --------------------------------------------------
		//   最新の利用規約に同意しているかをチェック、してない場合は保存　ログインユーザーのみ
		// --------------------------------------------------

		$this->model_common->check_and_update_user_terms();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// $code = null;
//
		// if (isset($code_output))
		// {
			// $this->set_page(1);
			// $code = $this->get_code_bbs_uc();
		// }

		$code_bbs_thread_list = null;
		$code_bbs = null;

		if (isset($code_output))
		{
			// BBSスレッド一覧
			$this->set_community_no($this->get_community_no());
			$this->set_page(1);
			$code_bbs_thread_list = $this->get_code_thread_list_uc();

			// BBS
			$code_bbs = $this->get_code_bbs_uc();
		}



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$authority_arr';
			\Debug::dump($authority_arr);

			if (isset($db_bbs_thread_arr))
			{
				echo '$db_bbs_thread_arr';
				\Debug::dump($db_bbs_thread_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_thread_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_thread_arr));
			}


			echo '$uploaded_image_existence';
			\Debug::dump($uploaded_image_existence);

			echo '$save_arr';
			\Debug::dump($save_arr);

			if (isset($save_notifications_arr))
			{
				echo '$save_notifications_arr';
				\Debug::dump($save_notifications_arr);
			}

			echo $code;

		}
		*/


		// テスト
		//$this->test_output();
		//exit();


		return array('code_bbs_thread_list' => $code_bbs_thread_list, 'code_bbs' => $code_bbs, 'bbs_id' => $bbs_id);
		//return $code;

	}




	/**
	* コメント作成・編集　GC
	*
	* @return string HTMLコード
	*/
	public function save_bbs_comment_gc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		// スレッド取得
		$temp_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);
		$db_bbs_thread_arr = $result_arr[0];
		$this->set_game_no($db_bbs_thread_arr['game_no']);


		// コメント取得
		if ($this->get_bbs_comment_no())
		{
			$temp_arr = array(
				'bbs_comment_no' => $this->get_bbs_comment_no(),
				'page' => 1,
				'limit' => 1
			);

			$result_arr = $this->model_bbs->get_bbs_comment_gc($temp_arr);
			$db_bbs_comment_arr = $result_arr[0];

			$bbs_id = $db_bbs_comment_arr['bbs_id'];
		}
		else
		{
			$bbs_id = $this->model_bbs->get_free_bbs_id(array('type' => 'gc'));
		}


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();


		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ($this->get_bbs_comment_no())
		{
			if ( ! $this->check_bbs_edit_authority($db_bbs_comment_arr) and ! \Auth::member(100)) return null;
		}


		// --------------------------------------------------
		//   削除済みユーザー
		// --------------------------------------------------

		if ($this->get_login_profile_data_arr() === false) return null;


		// --------------------------------------------------
		//   画像検証
		// --------------------------------------------------

		$result_check_upload_image_arr = $this->original_func_common->check_upload_image(array('image_1'), \Config::get('limit_bbs_thread_image'));

		if ($result_check_upload_image_arr[1])
		{
			throw new \Exception('アップロードされた画像に問題があります。');
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

		if (isset($login_profile_data_arr['profile_no']))
		{
			$user_no = (int) $login_profile_data_arr['author_user_no'];
			$profile_no = (int) $login_profile_data_arr['profile_no'];
		}
		else if (isset($login_profile_data_arr['user_no']))
		{
			$user_no = (int) $login_profile_data_arr['user_no'];
		}


		// ------------------------------
		//    画像
		// ------------------------------

		$image_arr = (isset($db_bbs_comment_arr['image'])) ? $db_bbs_comment_arr['image'] : null;


		// ------------------------------
		//    動画
		// ------------------------------

		$movie_arr = (isset($db_bbs_comment_arr['movie'])) ? $db_bbs_comment_arr['movie'] : null;

		if ($this->get_movie_url())
		{
			if ($movie_arr) $movie_arr = unserialize($movie_arr);
			$movie_arr = $this->original_func_common->return_movie(array($this->get_movie_url()), $movie_arr, \Config::get('limit_bbs_comment_movie'));
			$movie_arr = serialize($movie_arr);
		}
		// 動画削除
		else if ($movie_arr and ! $this->get_movie_url())
		{
			$movie_arr = null;
		}


		$save_bbs_comment_arr = array(
			'bbs_id' => $bbs_id,
			'regi_date' => $this->get_datetime_now(),
			'renewal_date' => $this->get_datetime_now(),
			'sort_date' => $this->get_datetime_now(),
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'user_no' => $user_no,
			'profile_no' => $profile_no,
			'anonymity' => $this->get_anonymity_on(),
			'handle_name' => $this->get_handle_name(),
			'comment' => $this->get_comment(),
			'image' => $image_arr,
			'movie' => $movie_arr,
			'host' => HOST,
			'user_agent' => USER_AGENT
		);

		// コミュニティNo
		$save_bbs_comment_arr['game_no'] = $this->get_game_no();

		$save_arr = array('bbs_comment_arr' => $save_bbs_comment_arr);






		// --------------------------------------------------
		//    新規書き込み
		// --------------------------------------------------

		if ($this->get_bbs_comment_no() === null)
		{

			$result_arr = $this->model_bbs->insert_bbs_comment_gc($save_arr);

			if ($result_arr['error'])
			{
				throw new \Exception('二重書き込みです。');
			}
			else
			{
				if ( ! $uploaded_image_existence) $dont_edit = true;
				$this->set_bbs_comment_no_gc($result_arr['bbs_comment_no']);
			}



			// --------------------------------------------------
			//   プレゼント抽選エントリーポイント　＋
			// --------------------------------------------------

			// $present_entry_arr = array(
			// 	'regi_date' => $this->get_datetime_now(),
			// 	'user_no' => USER_NO,
			// 	'type_1' => 'gc_bbs_comment',
			// 	'type_2' => 'plus',
			// 	'point' => \Config::get('present_point_gc_bbs_comment')
			// );
			//
			// if (isset($profile_no)) $present_entry_arr['profile_no'] = $profile_no;
			//
			// $this->model_present->plus_minus_point($present_entry_arr);



			// --------------------------------------------------
			//   お知らせ保存
			// --------------------------------------------------
			/*
			$game_list_arr = $this->original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

			$save_notifications_arr = array(
				'regi_date' => $this->get_datetime_now(),
				'target_user_no' => null,
				'community_no' => $this->get_community_no(),
				'game_no' => (int) $game_list_arr[0],
				'type1' => 'uc',
				'type2' => 'bbs_comment',
				'title' => $this->get_title(),
				'anonymity' => $this->get_anonymity_on(),
				'name' => $this->get_handle_name(),
				'comment' => $this->get_comment(),
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'bbs_comment_no' => $this->get_bbs_comment_no()
			);

			if (isset($profile_no)) $save_notifications_arr['profile_no'] = $profile_no;

			$this->model_notifications->save_notifications($save_notifications_arr);
			*/


			// --------------------------------------------------
			//    SNS　データベース保存
			// --------------------------------------------------

			$original_common_text = new \Original\Common\Text();
			$send_sns_id = $original_common_text->random_text_lowercase(16);
			$approval = (\Auth::member(100)) ? 1 : null;

			$save_send_sns_arr[0] = array(
				'send_sns_id' => $send_sns_id,
				'regi_date' => $this->get_datetime_now(),
				'approval' => $approval,
				'type' => 'gc_bbs_comment',
				'game_no' => $this->get_game_no(),
				'bbs_id' => $bbs_id
			);

			$model_sns = new \Model_Sns();
			$model_sns->insert_send_sns($save_send_sns_arr);


			$code_output = true;

		}



		// --------------------------------------------------
		//    編集
		// --------------------------------------------------

		if ($this->get_bbs_comment_no() and empty($dont_edit))
		{

			// --------------------------------------------------
			//   画像設定
			// --------------------------------------------------

			// 保存先パス設定
			$path = DOCROOT . 'assets/img/bbs_gc/comment/' . $this->get_bbs_comment_no() . '/';


			// --------------------------------------------------
			//   画像削除
			// --------------------------------------------------

			if ($this->get_image_1_delete())
			{
				$this->original_func_common->image_delete($path, 'image_', array(1));
				$save_bbs_comment_arr['image'] = null;
			}


			// --------------------------------------------------
			//   画像保存
			// --------------------------------------------------

			if ($uploaded_image_existence)
			{

				$image_name_arr = array('image_1');
				$result_upload_image_arr = $this->original_func_common->zebra_image_save2($path, $image_name_arr, true, \Config::get('limit_bbs_comment_image'));

				if (isset($test))
				{
					echo "count_FILES";
					\Debug::dump(count($_FILES));
					echo "<br>result_upload_image_arr";
					\Debug::dump($result_upload_image_arr);
				}

				if ($result_upload_image_arr['error'])
				{
					throw new \Exception('アップロードされた画像に問題があります。');
				}
				else if ($result_upload_image_arr['size_arr'])
				{
					$save_bbs_comment_arr['image'] = serialize($result_upload_image_arr['size_arr']);
				}

			}


			// --------------------------------------------------
			//    保存用配列編集
			// --------------------------------------------------

			// ----------------------------------------
			//    不要な要素を削除する
			// ----------------------------------------

			unset($save_bbs_comment_arr['bbs_id'], $save_bbs_comment_arr['regi_date'], $save_bbs_comment_arr['user_no'], $save_bbs_comment_arr['profile_no'], $save_bbs_comment_arr['game_no']);


			$save_arr['game_no'] = $this->get_game_no();
			$save_arr['bbs_thread_no'] = $this->get_bbs_thread_no();
			$save_arr['bbs_comment_no'] = $this->get_bbs_comment_no();
			$save_arr['bbs_comment_arr'] = $save_bbs_comment_arr;


			// --------------------------------------------------
			//    データベース更新
			// --------------------------------------------------

			$result_arr = $this->model_bbs->update_bbs_comment_gc($save_arr);

			$code_output = true;

		}



		// --------------------------------------------------
		//   利用規約　同意した最新バージョンをセッションに保存
		// --------------------------------------------------

		\Session::set('user_terms_approval_version', \Config::get('user_terms_version'));


		// --------------------------------------------------
		//   最新の利用規約に同意しているかをチェック、してない場合は保存　ログインユーザーのみ
		// --------------------------------------------------

		$this->model_common->check_and_update_user_terms();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (isset($code_output))
		{
			$this->set_page(1);
			$code_bbs = $this->get_code_bbs_gc();
		}


		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$authority_arr';
			\Debug::dump($authority_arr);

			echo '$db_bbs_thread_arr';
			\Debug::dump($db_bbs_thread_arr);

			if (isset($db_bbs_comment_arr))
			{
				echo '$db_bbs_comment_arr';
				\Debug::dump($db_bbs_comment_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_thread_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_thread_arr));
			}


			echo '$uploaded_image_existence';
			\Debug::dump($uploaded_image_existence);


			echo '$save_arr';
			\Debug::dump($save_arr);

			if (isset($save_notifications_arr))
			{
				echo '$save_notifications_arr';
				\Debug::dump($save_notifications_arr);
			}

			//echo $code;

		}

		exit();
		*/

		// テスト
		//$this->test_output();
		//exit();



		return ['code_bbs' => $code_bbs, 'bbs_id' => $bbs_id];

	}





	/**
	* コメント作成・編集　UC
	*
	* @return string HTMLコード
	*/
	public function save_bbs_comment_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		// スレッド取得
		$temp_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
		$db_bbs_thread_arr = $result_arr[0];
		$this->set_community_no($db_bbs_thread_arr['community_no']);


		// コメント取得
		if ($this->get_bbs_comment_no())
		{
			$temp_arr = array(
				'bbs_comment_no' => $this->get_bbs_comment_no(),
				'page' => 1,
				'limit' => 1
			);

			$result_arr = $this->model_bbs->get_bbs_comment_uc($temp_arr);
			$db_bbs_comment_arr = $result_arr[0];

			$bbs_id = $db_bbs_comment_arr['bbs_id'];
		}
		else
		{
			$bbs_id = $this->model_bbs->get_free_bbs_id(array('type' => 'uc'));
		}


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($this->get_community_no());

		// コミュニティ
		$this->set_db_community_arr();
		$db_community_arr = $this->get_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   BBSを操作する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['operate_bbs_comment'] and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ($this->get_bbs_comment_no())
		{
			if ( ! $this->check_bbs_edit_authority($db_bbs_comment_arr) and ! \Auth::member(100)) return null;
		}


		// --------------------------------------------------
		//   削除済みユーザー
		// --------------------------------------------------

		if ($this->get_login_profile_data_arr() === false) return null;


		// --------------------------------------------------
		//   画像検証
		// --------------------------------------------------

		$result_check_upload_image_arr = $this->original_func_common->check_upload_image(array('image_1'), \Config::get('limit_bbs_thread_image'));

		if ($result_check_upload_image_arr[1])
		{
			throw new \Exception('アップロードされた画像に問題があります。');
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

		if (isset($login_profile_data_arr['profile_no']))
		{
			$user_no = (int) $login_profile_data_arr['author_user_no'];
			$profile_no = (int) $login_profile_data_arr['profile_no'];
		}
		else if (isset($login_profile_data_arr['user_no']))
		{
			$user_no = (int) $login_profile_data_arr['user_no'];
		}


		// ------------------------------
		//    画像
		// ------------------------------

		$image_arr = (isset($db_bbs_comment_arr['image'])) ? $db_bbs_comment_arr['image'] : null;


		// ------------------------------
		//    動画
		// ------------------------------

		$movie_arr = (isset($db_bbs_comment_arr['movie'])) ? $db_bbs_comment_arr['movie'] : null;

		if ($this->get_movie_url())
		{
			if ($movie_arr) $movie_arr = unserialize($movie_arr);
			$movie_arr = $this->original_func_common->return_movie(array($this->get_movie_url()), $movie_arr, \Config::get('limit_bbs_comment_movie'));
			$movie_arr = serialize($movie_arr);
		}
		// 動画削除
		else if ($movie_arr and ! $this->get_movie_url())
		{
			$movie_arr = null;
		}




		$save_bbs_comment_arr = array(
			'bbs_id' => $bbs_id,
			'regi_date' => $this->get_datetime_now(),
			'renewal_date' => $this->get_datetime_now(),
			'sort_date' => $this->get_datetime_now(),
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'user_no' => $user_no,
			'profile_no' => $profile_no,
			'anonymity' => $this->get_anonymity_on(),
			'handle_name' => $this->get_handle_name(),
			'comment' => $this->get_comment(),
			'image' => $image_arr,
			'movie' => $movie_arr,
			'host' => HOST,
			'user_agent' => USER_AGENT
		);

		// コミュニティNo
		$save_bbs_comment_arr['community_no'] = $this->get_community_no();

		$save_arr = array('bbs_comment_arr' => $save_bbs_comment_arr);




		// --------------------------------------------------
		//    新規書き込み
		// --------------------------------------------------

		if ($this->get_bbs_comment_no() === null)
		{

			$result_arr = $this->model_bbs->insert_bbs_comment_uc($save_arr);

			if ($result_arr['error'])
			{
				throw new \Exception('二重書き込みです。');
			}
			else
			{
				if ( ! $uploaded_image_existence) $dont_edit = true;
				$this->set_bbs_comment_no_uc($result_arr['bbs_comment_no']);
			}


			//echo '$result_arr';
			//\Debug::dump($result_arr);


			// --------------------------------------------------
			//   お知らせ保存
			// --------------------------------------------------

			$game_list_arr = $this->original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

			$save_notifications_arr = array(
				'regi_date' => $this->get_datetime_now(),
				'target_user_no' => null,
				'community_no' => $this->get_community_no(),
				'game_no' => (int) $game_list_arr[0],
				'type1' => 'uc',
				'type2' => 'bbs_comment',
				'title' => $db_bbs_thread_arr['title'],
				'anonymity' => $this->get_anonymity_on(),
				'name' => $this->get_handle_name(),
				'comment' => $this->get_comment(),
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'bbs_comment_no' => $this->get_bbs_comment_no(),
				'bbs_id' => $bbs_id
			);

			if (isset($profile_no)) $save_notifications_arr['profile_no'] = $profile_no;

			$this->model_notifications->save_notifications($save_notifications_arr);
// \Debug::dump($save_notifications_arr);
			$code_output = true;

		}



		// --------------------------------------------------
		//    編集
		// --------------------------------------------------

		if ($this->get_bbs_comment_no() and empty($dont_edit))
		{

			// --------------------------------------------------
			//   画像設定
			// --------------------------------------------------

			// 保存先パス設定
			$path = DOCROOT . 'assets/img/bbs_uc/comment/' . $this->get_bbs_comment_no() . '/';


			// --------------------------------------------------
			//   画像削除
			// --------------------------------------------------

			if ($this->get_image_1_delete())
			{
				$this->original_func_common->image_delete($path, 'image_', array(1));
				$save_bbs_comment_arr['image'] = null;
			}


			// --------------------------------------------------
			//   画像保存
			// --------------------------------------------------

			if ($uploaded_image_existence)
			{

				$image_name_arr = array('image_1');
				$result_upload_image_arr = $this->original_func_common->zebra_image_save2($path, $image_name_arr, true, \Config::get('limit_bbs_comment_image'));

				if (isset($test))
				{
					echo "count_FILES";
					\Debug::dump(count($_FILES));
					echo "<br>result_upload_image_arr";
					\Debug::dump($result_upload_image_arr);
				}

				if ($result_upload_image_arr['error'])
				{
					throw new \Exception('アップロードされた画像に問題があります。');
				}
				else if ($result_upload_image_arr['size_arr'])
				{
					$save_bbs_comment_arr['image'] = serialize($result_upload_image_arr['size_arr']);
				}

			}


			// --------------------------------------------------
			//    保存用配列編集
			// --------------------------------------------------

			// ----------------------------------------
			//    不要な要素を削除する
			// ----------------------------------------

			unset($save_bbs_comment_arr['bbs_id'], $save_bbs_comment_arr['regi_date'], $save_bbs_comment_arr['user_no'], $save_bbs_comment_arr['profile_no'], $save_bbs_comment_arr['community_no']);


			$save_arr['community_no'] = $this->get_community_no();
			$save_arr['bbs_thread_no'] = $this->get_bbs_thread_no();
			$save_arr['bbs_comment_no'] = $this->get_bbs_comment_no();
			$save_arr['bbs_comment_arr'] = $save_bbs_comment_arr;


			// --------------------------------------------------
			//    データベース更新
			// --------------------------------------------------

			$result_arr = $this->model_bbs->update_bbs_comment_uc($save_arr);

			$code_output = true;

		}



		// --------------------------------------------------
		//   利用規約　同意した最新バージョンをセッションに保存
		// --------------------------------------------------

		\Session::set('user_terms_approval_version', \Config::get('user_terms_version'));


		// --------------------------------------------------
		//   最新の利用規約に同意しているかをチェック、してない場合は保存　ログインユーザーのみ
		// --------------------------------------------------

		$this->model_common->check_and_update_user_terms();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (isset($code_output))
		{
			$this->set_page(1);
			$code_bbs = $this->get_code_bbs_uc();
		}


		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$authority_arr';
			\Debug::dump($authority_arr);

			echo '$db_bbs_thread_arr';
			\Debug::dump($db_bbs_thread_arr);

			if (isset($db_bbs_comment_arr))
			{
				echo '$db_bbs_comment_arr';
				\Debug::dump($db_bbs_comment_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_thread_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_thread_arr));
			}


			echo '$uploaded_image_existence';
			\Debug::dump($uploaded_image_existence);


			echo '$save_arr';
			\Debug::dump($save_arr);

			if (isset($save_notifications_arr))
			{
				echo '$save_notifications_arr';
				\Debug::dump($save_notifications_arr);
			}

			//echo $code;

		}

		exit();
		*/

		// テスト
		//$this->test_output();
		//exit();



		return ['code_bbs' => $code_bbs, 'bbs_id' => $bbs_id];

	}





	/**
	* 返信作成・編集　GC
	*
	* @return string HTMLコード
	*/
	public function save_bbs_reply_gc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		// コメント取得
		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_comment_gc($temp_arr);
		$db_bbs_comment_arr = $result_arr[0];
		$this->set_bbs_thread_no_gc($db_bbs_comment_arr['bbs_thread_no']);
		$this->set_game_no($db_bbs_comment_arr['game_no']);



		// スレッド取得
		$temp_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);
		$db_bbs_thread_arr = $result_arr[0];



		// 返信取得
		if ($this->get_bbs_reply_no())
		{
			$temp_arr = array(
				'bbs_reply_no' => $this->get_bbs_reply_no(),
				'page' => 1,
				'limit' => 1
			);

			$result_arr = $this->model_bbs->get_bbs_reply_gc($temp_arr);
			$db_bbs_reply_arr = $result_arr[0];

			$bbs_id = $db_bbs_reply_arr['bbs_id'];
		}
		else
		{
			$bbs_id = $this->model_bbs->get_free_bbs_id(array('type' => 'gc'));
		}


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_gc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_gc();

		// 匿名
		$this->set_anonymity_gc();



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ($this->get_bbs_reply_no())
		{
			if ( ! $this->check_bbs_edit_authority($db_bbs_reply_arr) and ! \Auth::member(100)) return null;
		}


		// --------------------------------------------------
		//   削除済みユーザー
		// --------------------------------------------------

		if ($this->get_login_profile_data_arr() === false) return null;


		// --------------------------------------------------
		//   画像検証
		// --------------------------------------------------

		$result_check_upload_image_arr = $this->original_func_common->check_upload_image(array('image_1'), \Config::get('limit_bbs_thread_image'));

		if ($result_check_upload_image_arr[1])
		{
			throw new \Exception('アップロードされた画像に問題があります。');
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

		if (isset($login_profile_data_arr['profile_no']))
		{
			$user_no = (int) $login_profile_data_arr['author_user_no'];
			$profile_no = (int) $login_profile_data_arr['profile_no'];
		}
		else if (isset($login_profile_data_arr['user_no']))
		{
			$user_no = (int) $login_profile_data_arr['user_no'];
		}


		// ------------------------------
		//    画像
		// ------------------------------

		$image_arr = (isset($db_bbs_reply_arr['image'])) ? $db_bbs_reply_arr['image'] : null;


		// ------------------------------
		//    動画
		// ------------------------------

		$movie_arr = (isset($db_bbs_reply_arr['movie'])) ? $db_bbs_reply_arr['movie'] : null;

		if ($this->get_movie_url())
		{
			if ($movie_arr) $movie_arr = unserialize($movie_arr);
			$movie_arr = $this->original_func_common->return_movie(array($this->get_movie_url()), $movie_arr, \Config::get('limit_bbs_comment_movie'));
			$movie_arr = serialize($movie_arr);
		}
		// 動画削除
		else if ($movie_arr and ! $this->get_movie_url())
		{
			$movie_arr = null;
		}


		$save_bbs_reply_arr = array(
			'bbs_id' => $bbs_id,
			'regi_date' => $this->get_datetime_now(),
			'renewal_date' => $this->get_datetime_now(),
			'sort_date' => $this->get_datetime_now(),
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'user_no' => $user_no,
			'profile_no' => $profile_no,
			'anonymity' => $this->get_anonymity_on(),
			'handle_name' => $this->get_handle_name(),
			'comment' => $this->get_comment(),
			'image' => $image_arr,
			'movie' => $movie_arr,
			'host' => HOST,
			'user_agent' => USER_AGENT
		);

		// ゲームNo
		$save_bbs_reply_arr['game_no'] = $this->get_game_no();

		$save_arr = array('bbs_reply_arr' => $save_bbs_reply_arr);

		// echo '$save_arr';
		// \Debug::dump($save_arr);
		// exit();


		// --------------------------------------------------
		//    新規書き込み
		// --------------------------------------------------

		if ($this->get_bbs_reply_no() === null)
		{

			$result_arr = $this->model_bbs->insert_bbs_reply_gc($save_arr);

			if ($result_arr['error'])
			{
				throw new \Exception('二重書き込みです。');
			}
			else
			{
				if ( ! $uploaded_image_existence) $dont_edit = true;
				$this->set_bbs_reply_no_gc($result_arr['bbs_reply_no']);
			}



			// --------------------------------------------------
			//   プレゼント抽選エントリーポイント　＋
			// --------------------------------------------------

			// $present_entry_arr = array(
			// 	'regi_date' => $this->get_datetime_now(),
			// 	'user_no' => USER_NO,
			// 	'type_1' => 'gc_bbs_reply',
			// 	'type_2' => 'plus',
			// 	'point' => \Config::get('present_point_gc_bbs_reply')
			// );
			//
			// if (isset($profile_no)) $present_entry_arr['profile_no'] = $profile_no;
			//
			// $this->model_present->plus_minus_point($present_entry_arr);



			// --------------------------------------------------
			//   お知らせ保存
			// --------------------------------------------------
			/*
			$game_list_arr = $this->original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

			$save_notifications_arr = array(
				'regi_date' => $this->get_datetime_now(),
				'target_user_no' => null,
				'community_no' => $this->get_community_no(),
				'game_no' => (int) $game_list_arr[0],
				'type1' => 'uc',
				'type2' => 'bbs_reply',
				'title' => $db_bbs_thread_arr['title'],
				'anonymity' => $this->get_anonymity_on(),
				'name' => $this->get_handle_name(),
				'comment' => $this->get_comment(),
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'bbs_comment_no' => $this->get_bbs_comment_no(),
				'bbs_reply_no' => $this->get_bbs_reply_no()
			);

			if (isset($profile_no)) $save_notifications_arr['profile_no'] = $profile_no;

			//$this->model_notifications->save_notifications($save_notifications_arr);
			*/


			// --------------------------------------------------
			//    SNS　データベース保存
			// --------------------------------------------------

			$original_common_text = new \Original\Common\Text();
			$send_sns_id = $original_common_text->random_text_lowercase(16);
			$approval = (\Auth::member(100)) ? 1 : null;

			$save_send_sns_arr[0] = array(
				'send_sns_id' => $send_sns_id,
				'regi_date' => $this->get_datetime_now(),
				'approval' => $approval,
				'type' => 'gc_bbs_reply',
				'game_no' => $this->get_game_no(),
				'bbs_id' => $bbs_id
			);

			$model_sns = new \Model_Sns();
			$model_sns->insert_send_sns($save_send_sns_arr);


			$code_output = true;

		}





		// --------------------------------------------------
		//    編集
		// --------------------------------------------------

		if ($this->get_bbs_reply_no() and empty($dont_edit))
		{

			// --------------------------------------------------
			//   画像設定
			// --------------------------------------------------

			// 保存先パス設定
			$path = DOCROOT . 'assets/img/bbs_gc/reply/' . $this->get_bbs_reply_no() . '/';


			// --------------------------------------------------
			//   画像削除
			// --------------------------------------------------

			if ($this->get_image_1_delete())
			{
				$this->original_func_common->image_delete($path, 'image_', array(1));
				$save_bbs_reply_arr['image'] = null;
			}


			// --------------------------------------------------
			//   画像保存
			// --------------------------------------------------

			if ($uploaded_image_existence)
			{

				$image_name_arr = array('image_1');
				$result_upload_image_arr = $this->original_func_common->zebra_image_save2($path, $image_name_arr, true, \Config::get('limit_bbs_comment_image'));

				if (isset($test))
				{
					echo "count_FILES";
					\Debug::dump(count($_FILES));
					echo "<br>result_upload_image_arr";
					\Debug::dump($result_upload_image_arr);
				}

				if ($result_upload_image_arr['error'])
				{
					throw new \Exception('アップロードされた画像に問題があります。');
				}
				else if ($result_upload_image_arr['size_arr'])
				{
					$save_bbs_reply_arr['image'] = serialize($result_upload_image_arr['size_arr']);
				}

			}


			// --------------------------------------------------
			//    保存用配列編集
			// --------------------------------------------------

			// ----------------------------------------
			//    不要な要素を削除する
			// ----------------------------------------

			unset($save_bbs_reply_arr['bbs_id'], $save_bbs_reply_arr['regi_date'], $save_bbs_reply_arr['user_no'], $save_bbs_reply_arr['profile_no'], $save_bbs_reply_arr['game_no']);


			$save_arr['game_no'] = $this->get_game_no();
			$save_arr['bbs_thread_no'] = $this->get_bbs_thread_no();
			$save_arr['bbs_comment_no'] = $this->get_bbs_comment_no();
			$save_arr['bbs_reply_no'] = $this->get_bbs_reply_no();
			$save_arr['bbs_reply_arr'] = $save_bbs_reply_arr;


			// --------------------------------------------------
			//    データベース更新
			// --------------------------------------------------

			$result_arr = $this->model_bbs->update_bbs_reply_gc($save_arr);

			$code_output = true;

		}


		// --------------------------------------------------
		//   利用規約　同意した最新バージョンをセッションに保存
		// --------------------------------------------------

		\Session::set('user_terms_approval_version', \Config::get('user_terms_version'));


		// --------------------------------------------------
		//   最新の利用規約に同意しているかをチェック、してない場合は保存　ログインユーザーのみ
		// --------------------------------------------------

		$this->model_common->check_and_update_user_terms();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (isset($code_output))
		{
			$this->set_page(1);
			$code_bbs = $this->get_code_bbs_gc();
		}



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;


			echo '$authority_arr';
			\Debug::dump($authority_arr);

			echo '$db_bbs_comment_arr';
			\Debug::dump($db_bbs_comment_arr);

			echo '$db_bbs_thread_arr';
			\Debug::dump($db_bbs_thread_arr);


			if (isset($db_bbs_reply_arr))
			{
				echo '$db_bbs_reply_arr';
				\Debug::dump($db_bbs_reply_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_reply_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_reply_arr));
			}


			echo '$uploaded_image_existence';
			\Debug::dump($uploaded_image_existence);


			if (isset($dont_edit))
			{
				echo '$dont_edit';
				\Debug::dump($dont_edit);
			}


			echo '$save_arr';
			\Debug::dump($save_arr);

			if (isset($save_notifications_arr))
			{
				echo '$save_notifications_arr';
				\Debug::dump($save_notifications_arr);
			}

			//echo $code;

		}

		//exit();
		*/

		// テスト
		//$this->test_output();
		//exit();



		return ['code_bbs' => $code_bbs, 'bbs_id' => $bbs_id];

	}




	/**
	* 返信作成・編集　UC
	*
	* @return string HTMLコード
	*/
	public function save_bbs_reply_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		// コメント取得
		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_comment_uc($temp_arr);
		$db_bbs_comment_arr = $result_arr[0];
		$this->set_bbs_thread_no_uc($db_bbs_comment_arr['bbs_thread_no']);
		$this->set_community_no($db_bbs_comment_arr['community_no']);



		// スレッド取得
		$temp_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
		$db_bbs_thread_arr = $result_arr[0];



		// 返信取得
		if ($this->get_bbs_reply_no())
		{
			$temp_arr = array(
				'type' => 'uc',
				'bbs_reply_no' => $this->get_bbs_reply_no(),
				'page' => 1,
				'limit' => 1
			);

			$result_arr = $this->model_bbs->get_bbs_reply_uc($temp_arr);
			$db_bbs_reply_arr = $result_arr[0];

			$bbs_id = $db_bbs_reply_arr['bbs_id'];
		}
		else
		{
			$bbs_id = $this->model_bbs->get_free_bbs_id(array('type' => 'uc'));
		}


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($this->get_community_no());

		// コミュニティ
		$this->set_db_community_arr();
		$db_community_arr = $this->get_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();





		// --------------------------------------------------
		//   BBSを操作する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['operate_bbs_comment'] and ! \Auth::member(100)) return null;


		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ($this->get_bbs_reply_no())
		{
			if ( ! $this->check_bbs_edit_authority($db_bbs_reply_arr) and ! \Auth::member(100)) return null;
		}


		// --------------------------------------------------
		//   削除済みユーザー
		// --------------------------------------------------

		if ($this->get_login_profile_data_arr() === false) return null;


		// --------------------------------------------------
		//   画像検証
		// --------------------------------------------------

		$result_check_upload_image_arr = $this->original_func_common->check_upload_image(array('image_1'), \Config::get('limit_bbs_thread_image'));

		if ($result_check_upload_image_arr[1])
		{
			throw new \Exception('アップロードされた画像に問題があります。');
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

		if (isset($login_profile_data_arr['profile_no']))
		{
			$user_no = (int) $login_profile_data_arr['author_user_no'];
			$profile_no = (int) $login_profile_data_arr['profile_no'];
		}
		else if (isset($login_profile_data_arr['user_no']))
		{
			$user_no = (int) $login_profile_data_arr['user_no'];
		}


		// ------------------------------
		//    画像
		// ------------------------------

		$image_arr = (isset($db_bbs_reply_arr['image'])) ? $db_bbs_reply_arr['image'] : null;


		// ------------------------------
		//    動画
		// ------------------------------

		$movie_arr = (isset($db_bbs_reply_arr['movie'])) ? $db_bbs_reply_arr['movie'] : null;

		if ($this->get_movie_url())
		{
			if ($movie_arr) $movie_arr = unserialize($movie_arr);
			$movie_arr = $this->original_func_common->return_movie(array($this->get_movie_url()), $movie_arr, \Config::get('limit_bbs_comment_movie'));
			$movie_arr = serialize($movie_arr);
		}
		// 動画削除
		else if ($movie_arr and ! $this->get_movie_url())
		{
			$movie_arr = null;
		}



		$save_bbs_reply_arr = array(
			'bbs_id' => $bbs_id,
			'regi_date' => $this->get_datetime_now(),
			'renewal_date' => $this->get_datetime_now(),
			'sort_date' => $this->get_datetime_now(),
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'user_no' => $user_no,
			'profile_no' => $profile_no,
			'anonymity' => $this->get_anonymity_on(),
			'handle_name' => $this->get_handle_name(),
			'comment' => $this->get_comment(),
			'image' => $image_arr,
			'movie' => $movie_arr,
			'host' => HOST,
			'user_agent' => USER_AGENT
		);

		// コミュニティNo
		$save_bbs_reply_arr['community_no'] = $this->get_community_no();

		$save_arr = array('bbs_reply_arr' => $save_bbs_reply_arr);




		// --------------------------------------------------
		//    新規書き込み
		// --------------------------------------------------

		if ($this->get_bbs_reply_no() === null)
		{

			$result_arr = $this->model_bbs->insert_bbs_reply_uc($save_arr);

			if ($result_arr['error'])
			{
				throw new \Exception('二重書き込みです。');
			}
			else
			{
				if ( ! $uploaded_image_existence) $dont_edit = true;
				$this->set_bbs_reply_no_uc($result_arr['bbs_reply_no']);
			}


			//echo '$result_arr';
			//\Debug::dump($result_arr);

			//if ($uploaded_image_existence)


			// --------------------------------------------------
			//   お知らせ保存
			// --------------------------------------------------

			$game_list_arr = $this->original_func_common->return_db_array('db_php', $db_community_arr['game_list']);

			$save_notifications_arr = array(
				'regi_date' => $this->get_datetime_now(),
				'target_user_no' => null,
				'community_no' => $this->get_community_no(),
				'game_no' => (int) $game_list_arr[0],
				'type1' => 'uc',
				'type2' => 'bbs_reply',
				'title' => $db_bbs_thread_arr['title'],
				'anonymity' => $this->get_anonymity_on(),
				'name' => $this->get_handle_name(),
				'comment' => $this->get_comment(),
				'bbs_thread_no' => $this->get_bbs_thread_no(),
				'bbs_comment_no' => $this->get_bbs_comment_no(),
				'bbs_reply_no' => $this->get_bbs_reply_no(),
				'bbs_id' => $bbs_id
			);

			if (isset($profile_no)) $save_notifications_arr['profile_no'] = $profile_no;

			$this->model_notifications->save_notifications($save_notifications_arr);


			$code_output = true;

		}





		// --------------------------------------------------
		//    編集
		// --------------------------------------------------

		if ($this->get_bbs_reply_no() and empty($dont_edit))
		{

			// --------------------------------------------------
			//   画像設定
			// --------------------------------------------------

			// 保存先パス設定
			$path = DOCROOT . 'assets/img/bbs_uc/reply/' . $this->get_bbs_reply_no() . '/';


			// --------------------------------------------------
			//   画像削除
			// --------------------------------------------------

			if ($this->get_image_1_delete())
			{
				$this->original_func_common->image_delete($path, 'image_', array(1));
				$save_bbs_reply_arr['image'] = null;
			}


			// --------------------------------------------------
			//   画像保存
			// --------------------------------------------------

			if ($uploaded_image_existence)
			{

				$image_name_arr = array('image_1');
				$result_upload_image_arr = $this->original_func_common->zebra_image_save2($path, $image_name_arr, true, \Config::get('limit_bbs_comment_image'));

				if (isset($test))
				{
					echo "count_FILES";
					\Debug::dump(count($_FILES));
					echo "<br>result_upload_image_arr";
					\Debug::dump($result_upload_image_arr);
				}

				if ($result_upload_image_arr['error'])
				{
					throw new \Exception('アップロードされた画像に問題があります。');
				}
				else if ($result_upload_image_arr['size_arr'])
				{
					$save_bbs_reply_arr['image'] = serialize($result_upload_image_arr['size_arr']);
				}

			}


			// --------------------------------------------------
			//    保存用配列編集
			// --------------------------------------------------

			// ----------------------------------------
			//    不要な要素を削除する
			// ----------------------------------------

			unset($save_bbs_reply_arr['bbs_id'], $save_bbs_reply_arr['regi_date'], $save_bbs_reply_arr['user_no'], $save_bbs_reply_arr['profile_no'], $save_bbs_reply_arr['community_no']);


			$save_arr['community_no'] = $this->get_community_no();
			$save_arr['bbs_thread_no'] = $this->get_bbs_thread_no();
			$save_arr['bbs_comment_no'] = $this->get_bbs_comment_no();
			$save_arr['bbs_reply_no'] = $this->get_bbs_reply_no();
			$save_arr['bbs_reply_arr'] = $save_bbs_reply_arr;


			// --------------------------------------------------
			//    データベース更新
			// --------------------------------------------------

			$result_arr = $this->model_bbs->update_bbs_reply_uc($save_arr);

			$code_output = true;

		}


		// --------------------------------------------------
		//   利用規約　同意した最新バージョンをセッションに保存
		// --------------------------------------------------

		\Session::set('user_terms_approval_version', \Config::get('user_terms_version'));


		// --------------------------------------------------
		//   最新の利用規約に同意しているかをチェック、してない場合は保存　ログインユーザーのみ
		// --------------------------------------------------

		$this->model_common->check_and_update_user_terms();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (isset($code_output))
		{
			$this->set_page(1);
			$code_bbs = $this->get_code_bbs_uc();
		}



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;


			echo '$authority_arr';
			\Debug::dump($authority_arr);

			echo '$db_bbs_comment_arr';
			\Debug::dump($db_bbs_comment_arr);

			echo '$db_bbs_thread_arr';
			\Debug::dump($db_bbs_thread_arr);


			if (isset($db_bbs_reply_arr))
			{
				echo '$db_bbs_reply_arr';
				\Debug::dump($db_bbs_reply_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_reply_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_reply_arr));
			}


			echo '$uploaded_image_existence';
			\Debug::dump($uploaded_image_existence);


			if (isset($dont_edit))
			{
				echo '$dont_edit';
				\Debug::dump($dont_edit);
			}


			echo '$save_arr';
			\Debug::dump($save_arr);

			if (isset($save_notifications_arr))
			{
				echo '$save_notifications_arr';
				\Debug::dump($save_notifications_arr);
			}

			//echo $code;

		}

		//exit();
		*/

		// テスト
		//$this->test_output();
		//exit();



		return ['code_bbs' => $code_bbs, 'bbs_id' => $bbs_id];

	}





	/**
	* スレッド削除　GC
	*
	* @return string HTMLコード
	*/
	public function delete_bbs_thread_gc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_thread_gc($temp_arr);
		$db_bbs_thread_arr = $result_arr[0];
		$this->set_game_no($db_bbs_thread_arr['game_no']);



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_thread_arr) and ! \Auth::member(100)) return null;



		// --------------------------------------------------
		//    保存用の配列作成
		// --------------------------------------------------

		$save_arr = array(
			'game_no' => $this->get_game_no(),
			'bbs_thread_no' => $this->get_bbs_thread_no()
		);


		// --------------------------------------------------
		//    データベース更新
		// --------------------------------------------------

		$result = $this->model_bbs->delete_bbs_thread_gc($save_arr);



		// --------------------------------------------------
		//   プレゼント抽選エントリーポイント　-
		// --------------------------------------------------

		$present_entry_arr = array(
			'regi_date' => $this->get_datetime_now(),
			'user_no' => $db_bbs_thread_arr['user_no'],
			'type_1' => 'gc_bbs_thread',
			'type_2' => 'minus',
			'point' => \Config::get('present_point_gc_bbs_thread')
		);

		if (isset($db_bbs_thread_arr['profile_no'])) $present_entry_arr['profile_no'] = $db_bbs_thread_arr['profile_no'];

		$this->model_present->plus_minus_point($present_entry_arr);



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		//$this->set_page(1);
		//$code = $this->get_code_bbs_gc();


		// BBSスレッド一覧
		$this->set_game_no($this->get_game_no());
		$this->set_page(1);
		$code_bbs_thread_list = $this->get_code_thread_list_gc();

		// BBS
		$code_bbs = $this->get_code_bbs_gc();



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_thread_arr';
			\Debug::dump($db_bbs_thread_arr);

			echo '$save_arr';
			\Debug::dump($save_arr);


			echo $code;

		}
		*/
		//exit();



		// テスト
		//$this->test_output();
		//exit();


		return array('code_bbs_thread_list' => $code_bbs_thread_list, 'code_bbs' => $code_bbs);
		//return $code;

	}





	/**
	* スレッド削除　UC
	*
	* @return string HTMLコード
	*/
	public function delete_bbs_thread_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_thread_uc($temp_arr);
		$db_bbs_thread_arr = $result_arr[0];


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($db_bbs_thread_arr['community_no']);

		// コミュニティ
		$this->set_db_community_arr();
		$db_community_arr = $this->get_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['operate_bbs_delete'] and ! $this->check_bbs_edit_authority($db_bbs_thread_arr) and ! \Auth::member(100)) return null;



		// --------------------------------------------------
		//    保存用の配列作成
		// --------------------------------------------------

		$save_arr = array(
			'community_no' => $this->get_community_no(),
			'bbs_thread_no' => $this->get_bbs_thread_no()
		);


		// --------------------------------------------------
		//    データベース更新
		// --------------------------------------------------

		$result = $this->model_bbs->delete_bbs_thread_uc($save_arr);







		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		//$this->set_page(1);
		//$code = $this->get_code_bbs_uc();

		// BBSスレッド一覧
		$this->set_community_no($this->get_community_no());
		$this->set_page(1);
		$code_bbs_thread_list = $this->get_code_thread_list_uc();

		// BBS
		$code_bbs = $this->get_code_bbs_uc();


		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$authority_arr';
			\Debug::dump($authority_arr);

			if (isset($db_bbs_thread_arr))
			{
				echo '$db_bbs_thread_arr';
				\Debug::dump($db_bbs_thread_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_thread_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_thread_arr));
			}

			echo '$save_arr';
			\Debug::dump($save_arr);


			echo $code;

		}
		*/

		// テスト
		//$this->test_output();
		//exit();


		return array('code_bbs_thread_list' => $code_bbs_thread_list, 'code_bbs' => $code_bbs);
		//return $code;

	}




	/**
	* コメント削除　GC
	*
	* @return string HTMLコード
	*/
	public function delete_bbs_comment_gc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_comment_gc($temp_arr);
		$db_bbs_comment_arr = $result_arr[0];
		$this->set_bbs_thread_no_gc($db_bbs_comment_arr['bbs_thread_no']);
		$this->set_game_no($db_bbs_comment_arr['game_no']);



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_comment_arr) and ! \Auth::member(100)) return null;



		// --------------------------------------------------
		//    保存用の配列作成
		// --------------------------------------------------

		$save_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'bbs_comment_no' => $this->get_bbs_comment_no()
		);


		// --------------------------------------------------
		//    データベース更新
		// --------------------------------------------------

		$result = $this->model_bbs->delete_bbs_comment_gc($save_arr);



		// --------------------------------------------------
		//   プレゼント抽選エントリーポイント　-
		// --------------------------------------------------

		$present_entry_arr = array(
			'regi_date' => $this->get_datetime_now(),
			'user_no' => $db_bbs_comment_arr['user_no'],
			'type_1' => 'gc_bbs_comment',
			'type_2' => 'minus',
			'point' => \Config::get('present_point_gc_bbs_comment')
		);

		if (isset($db_bbs_comment_arr['profile_no'])) $present_entry_arr['profile_no'] = $db_bbs_comment_arr['profile_no'];

		$this->model_present->plus_minus_point($present_entry_arr);



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// BBSスレッド一覧
		$this->set_game_no($this->get_game_no());
		$this->set_page(1);
		$code_bbs_thread_list = $this->get_code_thread_list_gc();

		// BBS
		$code_bbs = $this->get_code_bbs_gc();
//\Debug::dump($code_bbs);


		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_comment_arr';
			\Debug::dump($db_bbs_comment_arr);

			echo '$this->check_bbs_edit_authority($db_bbs_comment_arr)';
			\Debug::dump($this->check_bbs_edit_authority($db_bbs_comment_arr));

			echo '$save_arr';
			\Debug::dump($save_arr);


			echo $code;

		}
		*/
		//exit();


		return array('code_bbs_thread_list' => $code_bbs_thread_list, 'code_bbs' => $code_bbs);


	}




	/**
	* コメント削除　UC
	*
	* @return string HTMLコード
	*/
	public function delete_bbs_comment_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_comment_uc($temp_arr);
		$db_bbs_comment_arr = $result_arr[0];
		$this->set_bbs_thread_no_uc($db_bbs_comment_arr['bbs_thread_no']);
		$this->set_community_no($db_bbs_comment_arr['community_no']);


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($this->get_community_no());

		// コミュニティ
		$this->set_db_community_arr();
		$db_community_arr = $this->get_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['operate_bbs_delete'] and ! $this->check_bbs_edit_authority($db_bbs_comment_arr) and ! \Auth::member(100)) return null;



		// --------------------------------------------------
		//    保存用の配列作成
		// --------------------------------------------------

		$save_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'bbs_comment_no' => $this->get_bbs_comment_no()
		);


		// --------------------------------------------------
		//    データベース更新
		// --------------------------------------------------

		$result = $this->model_bbs->delete_bbs_comment_uc($save_arr);



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// BBSスレッド一覧
		$this->set_community_no($this->get_community_no());
		$this->set_page(1);
		$code_bbs_thread_list = $this->get_code_thread_list_uc();

		// BBS
		$code_bbs = $this->get_code_bbs_uc();


		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$authority_arr';
			\Debug::dump($authority_arr);

			if (isset($db_bbs_comment_arr))
			{
				echo '$db_bbs_comment_arr';
				\Debug::dump($db_bbs_comment_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_comment_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_comment_arr));
			}

			echo '$save_arr';
			\Debug::dump($save_arr);


			echo $code;

		}
		*/

		// テスト
		//$this->test_output();
		//exit();


		return array('code_bbs_thread_list' => $code_bbs_thread_list, 'code_bbs' => $code_bbs);

	}




	/**
	* 返信削除　GC
	*
	* @return string HTMLコード
	*/
	public function delete_bbs_reply_gc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_reply_no' => $this->get_bbs_reply_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_reply_gc($temp_arr);
		$db_bbs_reply_arr = $result_arr[0];
		$this->set_bbs_thread_no_gc($db_bbs_reply_arr['bbs_thread_no']);
		$this->set_bbs_comment_no_gc($db_bbs_reply_arr['bbs_comment_no']);
		$this->set_game_no($db_bbs_reply_arr['game_no']);



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $this->check_bbs_edit_authority($db_bbs_reply_arr) and ! \Auth::member(100)) return null;



		// --------------------------------------------------
		//    保存用の配列作成
		// --------------------------------------------------

		$save_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'bbs_reply_no' => $this->get_bbs_reply_no()
		);


		// --------------------------------------------------
		//    データベース更新
		// --------------------------------------------------

		$result = $this->model_bbs->delete_bbs_reply_gc($save_arr);



		// --------------------------------------------------
		//   プレゼント抽選エントリーポイント　-
		// --------------------------------------------------

		$present_entry_arr = array(
			'regi_date' => $this->get_datetime_now(),
			'user_no' => $db_bbs_reply_arr['user_no'],
			'type_1' => 'gc_bbs_reply',
			'type_2' => 'minus',
			'point' => \Config::get('present_point_gc_bbs_reply')
		);

		if (isset($db_bbs_reply_arr['profile_no'])) $present_entry_arr['profile_no'] = $db_bbs_reply_arr['profile_no'];

		$this->model_present->plus_minus_point($present_entry_arr);



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// BBSスレッド一覧
		$this->set_game_no($this->get_game_no());
		$this->set_page(1);
		$code_bbs_thread_list = $this->get_code_thread_list_gc();

		// BBS
		$code_bbs = $this->get_code_bbs_gc();


		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_bbs_reply_arr';
			\Debug::dump($db_bbs_reply_arr);

			echo '$this->check_bbs_edit_authority($db_bbs_reply_arr)';
			\Debug::dump($this->check_bbs_edit_authority($db_bbs_reply_arr));

			echo 'USER_AGENT';
			\Debug::dump(USER_AGENT);

			echo '$save_arr';
			\Debug::dump($save_arr);


			echo $code;

		}
		*/


		return array('code_bbs_thread_list' => $code_bbs_thread_list, 'code_bbs' => $code_bbs);

	}




	/**
	* 返信削除　UC
	*
	* @return string HTMLコード
	*/
	public function delete_bbs_reply_uc()
	{

		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'bbs_reply_no' => $this->get_bbs_reply_no(),
			'page' => 1,
			'limit' => 1
		);

		$result_arr = $this->model_bbs->get_bbs_reply_uc($temp_arr);
		$db_bbs_reply_arr = $result_arr[0];
		$this->set_bbs_thread_no_uc($db_bbs_reply_arr['bbs_thread_no']);
		$this->set_bbs_comment_no_uc($db_bbs_reply_arr['bbs_comment_no']);
		$this->set_community_no($db_bbs_reply_arr['community_no']);


		// --------------------------------------------------
		//   コミュニティ　値セット
		// --------------------------------------------------

		// コミュニティNo
		$this->set_community_no($this->get_community_no());

		// コミュニティ
		$this->set_db_community_arr();
		$db_community_arr = $this->get_db_community_arr();

		// 権限
		$this->set_authority_arr();

		// ログインプロフィール情報
		$this->set_login_profile_data_arr_uc();
		$login_profile_data_arr = $this->get_login_profile_data_arr();

		// 日付
		$this->set_datetime_now();

		// オンラインリミット
		$this->set_online_limit_uc();

		// 匿名
		$this->set_anonymity_uc();

		// 権限取得
		$authority_arr = $this->get_authority_arr();



		// --------------------------------------------------
		//   編集する権限がない場合、処理停止
		// --------------------------------------------------

		if ( ! $authority_arr['operate_bbs_delete'] and ! $this->check_bbs_edit_authority($db_bbs_reply_arr) and ! \Auth::member(100)) return null;



		// --------------------------------------------------
		//    保存用の配列作成
		// --------------------------------------------------

		$save_arr = array(
			'bbs_thread_no' => $this->get_bbs_thread_no(),
			'bbs_comment_no' => $this->get_bbs_comment_no(),
			'bbs_reply_no' => $this->get_bbs_reply_no()
		);


		// --------------------------------------------------
		//    データベース更新
		// --------------------------------------------------

		$result = $this->model_bbs->delete_bbs_reply_uc($save_arr);



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// BBSスレッド一覧
		$this->set_community_no($this->get_community_no());
		$this->set_page(1);
		$code_bbs_thread_list = $this->get_code_thread_list_uc();

		// BBS
		$code_bbs = $this->get_code_bbs_uc();


		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$authority_arr';
			\Debug::dump($authority_arr);

			if (isset($db_bbs_reply_arr))
			{
				echo '$db_bbs_reply_arr';
				\Debug::dump($db_bbs_reply_arr);

				echo '$this->check_bbs_edit_authority($db_bbs_reply_arr)';
				\Debug::dump($this->check_bbs_edit_authority($db_bbs_reply_arr));
			}

			echo '$save_arr';
			\Debug::dump($save_arr);


			echo $code;

		}
		*/


		return array('code_bbs_thread_list' => $code_bbs_thread_list, 'code_bbs' => $code_bbs);

	}






	/**
	* BBSを編集する権限があるかのチェック
	*
	* @return string HTMLコード
	*/
	public function check_bbs_edit_authority($arr)
	{

		$boolean = false;

		// 日時
		$datetime_past = $this->original_common_date->sql_format('-30 minutes');

		if (isset($arr['user_no']) and $arr['user_no'] == USER_NO)
		{
			$boolean = true;
		}
		else if ($arr['renewal_date'] > $datetime_past and $arr['host'] == HOST and $arr['user_agent'] == USER_AGENT)
		{
			$boolean = true;
		}

		return $boolean;

	}







	/**
	*  BBS一覧取得　GC
	*
	* @return string HTMLコード
	*/
	public function get_code_bbs_list_gc()
	{
		//echo 'aaa';
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		// コメント取得
		$temp_arr = array(
			'page' => $this->get_page(),
			'limit' => INDEX_LIMIT_BBS,
			'keyword' => $this->get_keyword()
		);

		$temp_arr = $this->model_bbs->get_bbs_list_gc($temp_arr);

		$bbs_list_arr = $temp_arr['bbs_list_arr'];
		$total = $temp_arr['total'];



		/*
		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$bbs_list_arr';
			\Debug::dump($bbs_list_arr);

			echo '$total';
			\Debug::dump($total);

		}
		*/


		// テスト
		//$this->test_output();
		//exit();


		if ($total == 0) return '検索結果 ： 0件';



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------)

		$view = \View::forge('parts/bbs_list_view');

		$view->set('datetime_now', $this->get_datetime_now());

		$view->set('bbs_list_arr', $bbs_list_arr);
		//$view->set('bbs_list_arr', $uri_base);

		// ページャー
		$view->set('pagination_page', $this->get_page());
		$view->set('pagination_total', $total);
		$view->set('pagination_limit', INDEX_LIMIT_BBS);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_function_name', 'searchGcBbsList');
		$view->set('pagination_argument_arr', array());

		$code = $view->render();


		return $code;

	}






	/**
	* テスト用出力
	*
	* @return string HTMLコード
	*/
	public function test_output()
	{

		//unset($this->test);

		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo 'AGENT_TYPE';
			\Debug::dump(AGENT_TYPE);

			echo 'HOST';
			\Debug::dump(HOST);

			echo 'USER_AGENT';
			\Debug::dump(USER_AGENT);

			echo 'USER_NO';
			\Debug::dump(USER_NO);

			echo 'LANGUAGE';
			\Debug::dump(LANGUAGE);

			echo 'URI_BASE';
			\Debug::dump(URI_BASE);

			echo 'URI_CURRENT';
			\Debug::dump(URI_CURRENT);

			echo 'APP_MODE';
			\Debug::dump(APP_MODE);



			echo '$this->type';
			\Debug::dump($this->type);

			echo '$this->page';
			\Debug::dump($this->page);

			echo '$this->login_profile_data_arr';
			\Debug::dump($this->login_profile_data_arr);

			echo '$this->datetime_now';
			\Debug::dump($this->datetime_now);

			echo '$this->online_limit';
			\Debug::dump($this->online_limit);

			echo '$this->anonymity';
			\Debug::dump($this->anonymity);



			echo '$this->game_no';
			\Debug::dump($this->game_no);



			echo '$this->community_no';
			\Debug::dump($this->community_no);

			echo '$this->db_community_arr';
			\Debug::dump($this->db_community_arr);

			echo '$this->authority_arr';
			\Debug::dump($this->authority_arr);



			echo 'LIMIT_BBS_THREAD_LIST';
			\Debug::dump(LIMIT_BBS_THREAD_LIST);

			echo 'LIMIT_BBS_THREAD';
			\Debug::dump(LIMIT_BBS_THREAD);

			echo 'LIMIT_BBS_COMMENT';
			\Debug::dump(LIMIT_BBS_COMMENT);

			echo 'LIMIT_BBS_REPLY';
			\Debug::dump(LIMIT_BBS_REPLY);

			echo 'PAGINATION_TIMES';
			\Debug::dump(PAGINATION_TIMES);


			echo '$this->db_bbs_thread_list_arr';
			\Debug::dump($this->db_bbs_thread_list_arr);

			echo '$this->db_bbs_thread_arr';
			\Debug::dump($this->db_bbs_thread_arr);

			echo '$this->db_bbs_thread_total';
			\Debug::dump($this->db_bbs_thread_total);

			echo '$this->bbs_thread_no';
			\Debug::dump($this->bbs_thread_no);


			echo '$this->db_bbs_comment_arr';
			\Debug::dump($this->db_bbs_comment_arr);

			echo '$this->db_bbs_reply_arr';
			\Debug::dump($this->db_bbs_reply_arr);


			echo '$this->bbs_user_no_arr';
			\Debug::dump($this->bbs_user_no_arr);

			echo '$this->bbs_profile_no_arr';
			\Debug::dump($this->bbs_profile_no_arr);

			echo '$this->bbs_user_data_arr';
			\Debug::dump($this->bbs_user_data_arr);

			echo '$this->bbs_profile_arr';
			\Debug::dump($this->bbs_profile_arr);


		}

	}


}
