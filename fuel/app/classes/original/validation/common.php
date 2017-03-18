<?php

namespace Original\Validation;

class Common
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// ------------------------------
	//   共通
	// ------------------------------

	private $agent_type = null;
	private $host = null;
	private $user_agent = null;
	private $user_no = null;
	private $language = null;
	private $uri_base = null;
	private $uri_current = null;
	private $app_mode = null;



	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $model_user = null;
	private $model_co = null;
	private $model_game = null;
	private $model_bbs = null;
	private $original_func_co = null;
	private $original_common_date = null;
	private $original_validation_fieldsetex = null;


	public $test = true;



	// --------------------------------------------------
	//   コンストラクター
	// --------------------------------------------------

	public function __construct()
	{

		// ------------------------------
		//   プロパティセット
		// ------------------------------

		// if (isset($arr['agent_type'])) $this->set_agent_type($arr['agent_type']);
		// if (isset($arr['host'])) $this->set_host($arr['host']);
		// if (isset($arr['user_agent'])) $this->set_user_agent($arr['user_agent']);
		// if (isset($arr['user_no'])) $this->set_user_no($arr['user_no']);
		// if (isset($arr['language'])) $this->set_language($arr['language']);
		// if (isset($arr['uri_base'])) $this->set_uri_base($arr['uri_base']);
		// if (isset($arr['uri_current'])) $this->set_uri_current($arr['uri_current']);
		// if (isset($arr['app_mode'])) $this->set_app_mode($arr['app_mode']);
//
		// $this->set_limit_bbs_thread_list();
		// $this->set_limit_bbs_thread();
		// $this->set_limit_bbs_comment();
		// $this->set_limit_bbs_reply();
		// $this->set_pagination_times();




		// ------------------------------
		//   インスタンス作成
		// ------------------------------
		/*
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

		$this->model_game = new \Model_Game();
		$this->model_game->agent_type = AGENT_TYPE;
		$this->model_game->user_no = USER_NO;
		$this->model_game->language = LANGUAGE;
		$this->model_game->uri_base = URI_BASE;
		$this->model_game->uri_current = URI_CURRENT;

		$this->model_bbs = new \Model_Bbs();

		$this->original_func_co = new \Original\Func\Co();
		$this->original_func_co->app_mode = APP_MODE;
		$this->original_func_co->agent_type = AGENT_TYPE;
		$this->original_func_co->user_no = USER_NO;
		$this->original_func_co->language = LANGUAGE;
		$this->original_func_co->uri_base = URI_BASE;
		$this->original_func_co->uri_current = URI_CURRENT;
		*/
		//$this->original_common_date = new \Original\Common\Date();

		//echo 'aaaaaaaaaaaaaa';
		//exit();

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();


	}






	// --------------------------------------------------
	//   共通
	// --------------------------------------------------


	/**
	* CSRFチェック
	*
	* @param string $argument
	*/
	public function csrf($argument)
	{

		$cookie_csrf_token = \Input::cookie(\Config::get('security.csrf_token_key', 'fuel_csrf_token'));
		$post_csrf_token = $argument;

		if (\Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
		{
			throw new \Exception('フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。');
		}
		else
		{
			return true;
		}

	}



	/**
	* Page
	*
	* @param string $argument
	*/
	public function page($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run(array('page' => $argument)))
		{
			return $val->validated('page');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}




	// --------------------------------------------------
	//   Game No
	// --------------------------------------------------

	/**
	* Game No
	*
	* @param string $argument
	*/
	public function game_no($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('game_no', 'Game No', 'required|check_game_no');

		if ($val->run(array('game_no' => $argument)))
		{
			return $val->validated('game_no');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}

	/**
	* バリデーションルール　ゲームNoの存在チェック
	*
	* @param integer $game_no ゲームNo
	* @return boolean
	*/
	public static function _validation_check_game_no($game_no) {

		if (preg_match('/^[1-9]\d*$/', $game_no) !== 1) return false;

		$model_game = new \Model_Game();
		$result = $model_game->get_game_data($game_no);

		return ($result) ? true : false;

	}



	/**
	* 関連ゲームの存在チェック　　コンマ区切りのゲームNo　例）1,2,3
	*
	* @param string $argument
	*/
	public function game_list($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('target', '関連ゲーム', 'required|check_game_list');

		if ($val->run(array('target' => $argument)))
		{
			return $val->validated('target');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}



	/**
	* バリデーションルール　ゲームリストの存在チェック
	*
	* @param string $str 変換前配列
	* @return boolean
	*/
	public static function _validation_check_game_list($str)
	{

		if ($str)
		{
			$game_no_arr = explode(',', $str);
			$game_no_arr_count = count($game_no_arr);

			// 11個以上登録しようとした場合エラー
			if ($game_no_arr_count > \Config::get('limit_regist_game')) return false;

			// 数字じゃない場合エラー
			foreach ($game_no_arr as $key => $value)
			{
				if ( ! is_numeric($value)) return false;
			}

			// データベースから取得
			$model_game = new \Model_Game();
			$result = $model_game->get_game_name('ja', $game_no_arr);

			//var_dump($game_no_arr_count, $result, count($result));

			// ゲームが存在していない場合エラー
			if ($game_no_arr_count != count($result)) return false;

		}

		return true;

	}




	// --------------------------------------------------
	//   Community No
	// --------------------------------------------------

	/**
	* Community No
	*
	* @param string $argument
	*/
	public function community_no($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('community_no', 'Community No', 'required|check_community_no');

		if ($val->run(array('community_no' => $argument)))
		{
			return $val->validated('community_no');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　コミュニティの存在チェック
	*
	* @param integer $community_no コミュニティNo
	* @return boolean
	*/
	public static function _validation_check_community_no($community_no) {

		if (preg_match('/^[1-9]\d*$/', $community_no) !== 1) return false;

		$model_co = new \Model_Co();
		$result = $model_co->get_community($community_no, null);

		return ($result) ? true : false;

	}




	// --------------------------------------------------
	//   Community ID
	// --------------------------------------------------

	/**
	* Community ID
	*
	* @param string $argument
	*/
	public function community_id($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('community_id', 'Community ID', 'required|valid_string[alpha,lowercase,numeric,dashes]|check_community_id');

		if ($val->run(array('community_id' => $argument)))
		{
			return $val->validated('community_id');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　コミュニティの存在チェック
	*
	* @param string $community_id コミュニティID
	* @return boolean
	*/
	public static function _validation_check_community_id($community_id)
	{

		$model_co = new \Model_Co();
		$result_arr = $model_co->get_community(null, $community_id);

		return ($result_arr) ? true : false;

	}




	// --------------------------------------------------
	//   Game Data ID
	// --------------------------------------------------

	/**
	* Game Data ID
	*
	* @param string $argument
	*/
	public function game_data_id($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('game_data_id', 'Game Data ID', 'required|valid_string[alpha,lowercase,numeric,dashes]|check_game_data_id');

		if ($val->run(array('game_data_id' => $argument)))
		{
			return $val->validated('game_data_id');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　IDの存在チェック
	*
	* @param string $game_data_id ゲームID
	* @return boolean
	*/
	public static function _validation_check_game_data_id($game_data_id)
	{

		$model_game = new \Model_Game();
		$result_arr = $model_game->get_game_data(null, $game_data_id);

		return ($result_arr) ? true : false;

	}




	// --------------------------------------------------
	//   BBS
	// --------------------------------------------------


	/**
	* Type BBS
	*
	* @param string $argument
	*/
	public function type_bbs($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(gc|gc_appoint|uc|uc_appoint)$/');

		if ($val->run(array('type' => $argument)))
		{
			return $val->validated('type');
		}
		else
		{

			$this->throw_exception($val->error());
		}

	}



	// --------------------------------------------------
	//   スレッドNo　GC
	// --------------------------------------------------

	/**
	* BBS Thread No / GC
	*
	* @param string $argument
	*/
	public function bbs_thread_no_gc($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('bbs_thread_no', 'BBS Thread No', 'required|check_bbs_thread_no_gc');

		if ($val->run(array('bbs_thread_no' => $argument)))
		{
			return $val->validated('bbs_thread_no');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　/ BBS スレッドの存在チェック / GC
	*
	* @param integer $bbs_thread_no
	* @return boolean
	*/
	public static function _validation_check_bbs_thread_no_gc($bbs_thread_no) {

		if (preg_match('/^[1-9]\d*$/', $bbs_thread_no) !== 1) return false;


		$temp_arr = array(
			'type' => 'gc',
			'bbs_thread_no' => $bbs_thread_no,
			'page' => 1,
			'limit' => 1
		);

		// スレッド取得
		$model_bbs = new \Model_Bbs();
		$result_arr = $model_bbs->get_bbs_thread_gc($temp_arr);


		//$test = true;

		if (isset($test))
		{
			\Debug::$js_toggle_open = true;

			echo '$bbs_thread_no';
			\Debug::dump($bbs_thread_no);

			echo '$result_arr';
			\Debug::dump($result_arr);
		}

		if (isset($result_arr[0]['game_no'])) return true;


		return false;

	}



	// --------------------------------------------------
	//   スレッドNo　UC
	// --------------------------------------------------

	/**
	* BBS Thread No / UC
	*
	* @param string $argument
	*/
	public function bbs_thread_no_uc($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('bbs_thread_no', 'BBS Thread No', 'required|check_bbs_thread_no_uc');

		if ($val->run(array('bbs_thread_no' => $argument)))
		{
			return $val->validated('bbs_thread_no');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　/ BBS スレッドの存在チェック / UC
	*
	* @param integer $bbs_thread_no
	* @return boolean
	*/
	public static function _validation_check_bbs_thread_no_uc($bbs_thread_no) {

		if (preg_match('/^[1-9]\d*$/', $bbs_thread_no) !== 1) return false;


		$temp_arr = array(
			'bbs_thread_no' => $bbs_thread_no,
			'page' => 1,
			'limit' => 1
		);

		// BBSスレッド取得
		$model_bbs = new \Model_Bbs();
		$result_arr = $model_bbs->get_bbs_thread_uc($temp_arr);


		//$test = true;

		if (isset($test))
		{
			\Debug::$js_toggle_open = true;

			echo '$result_arr';
			\Debug::dump($result_arr);
		}


		// コミュニティの存在チェック
		if (isset($result_arr[0]['community_no']))
		{
			$model_co = new \Model_Co();
			$result_arr = $model_co->get_community($result_arr[0]['community_no']);
			if ($result_arr) return true;
		}

		return false;

	}



	// --------------------------------------------------
	//   コメントNo　GC
	// --------------------------------------------------

	/**
	* BBS Comment No / GC
	*
	* @param string $argument
	*/
	public function bbs_comment_no_gc($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|check_bbs_comment_no_gc');

		if ($val->run(array('bbs_comment_no' => $argument)))
		{
			return $val->validated('bbs_comment_no');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　/ BBS コメントの存在チェック / GC
	*
	* @param integer $bbs_comment_no
	* @return boolean
	*/
	public static function _validation_check_bbs_comment_no_gc($bbs_comment_no) {

		if (preg_match('/^[1-9]\d*$/', $bbs_comment_no) !== 1) return false;


		// コメント取得
		$temp_arr = array(
			'type' => 'gc',
			'bbs_comment_no' => $bbs_comment_no,
			'page' => 1,
			'limit' => 1
		);

		$model_bbs = new \Model_Bbs();
		$result_comment_arr = $model_bbs->get_bbs_comment_gc($temp_arr);


		// スレッド取得
		if (isset($result_comment_arr[0]['bbs_thread_no']))
		{
			$temp_arr = array(
				'type' => 'gc',
				'bbs_thread_no' => $result_comment_arr[0]['bbs_thread_no'],
				'page' => 1,
				'limit' => 1
			);

			$result_thread_arr = $model_bbs->get_bbs_thread_gc($temp_arr);
		}


		//$test = true;

		if (isset($test))
		{
			\Debug::$js_toggle_open = true;

			echo '$result_comment_arr';
			\Debug::dump($result_comment_arr);

			echo '$result_thread_arr';
			\Debug::dump($result_thread_arr);
		}

		// コメントとスレッド両方が存在する場合にtrue
		return (isset($result_comment_arr[0]['bbs_comment_no'], $result_thread_arr[0]['bbs_thread_no'])) ? true : false;

	}



	// --------------------------------------------------
	//   コメントNo　UC
	// --------------------------------------------------

	/**
	* BBS Comment No / UC
	*
	* @param string $argument
	*/
	public function bbs_comment_no_uc($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('bbs_comment_no', 'BBS Comment No', 'required|check_bbs_comment_no_uc');

		if ($val->run(array('bbs_comment_no' => $argument)))
		{
			return $val->validated('bbs_comment_no');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　/ BBS コメントの存在チェック / UC
	*
	* @param integer $no BBSコメントNo
	* @return boolean
	*/
	public static function _validation_check_bbs_comment_no_uc($bbs_comment_no) {

		if (preg_match('/^[1-9]\d*$/', $bbs_comment_no) !== 1) return false;


		// コメント取得
		$temp_arr = array(
			'type' => 'gc',
			'bbs_comment_no' => $bbs_comment_no,
			'page' => 1,
			'limit' => 1
		);

		$model_bbs = new \Model_Bbs();
		$result_comment_arr = $model_bbs->get_bbs_comment_uc($temp_arr);


		// スレッド取得
		if (isset($result_comment_arr[0]['bbs_thread_no']))
		{
			$temp_arr = array(
				'type' => 'gc',
				'bbs_thread_no' => $result_comment_arr[0]['bbs_thread_no'],
				'page' => 1,
				'limit' => 1
			);

			$result_thread_arr = $model_bbs->get_bbs_thread_uc($temp_arr);
		}


		// ユーザーコミュニティ取得
		if (isset($result_thread_arr[0]['community_no']))
		{
			$model_co = new \Model_Co();
			$result_community_arr = $model_co->get_community($result_thread_arr[0]['community_no']);
		}


		//$test = true;

		if (isset($test))
		{
			\Debug::$js_toggle_open = true;

			echo '$result_comment_arr';
			\Debug::dump($result_comment_arr);

			echo '$result_thread_arr';
			\Debug::dump($result_thread_arr);

			echo '$result_community_arr';
			\Debug::dump($result_community_arr);
		}

		// コメント、スレッド、コミュニティが存在する場合にtrue
		return (isset($result_comment_arr[0]['bbs_comment_no'], $result_thread_arr[0]['bbs_thread_no'], $result_community_arr['community_no'])) ? true : false;

	}



	// --------------------------------------------------
	//   返信No　GC
	// --------------------------------------------------

	/**
	* BBS Reply No / GC
	*
	* @param string $argument
	*/
	public function bbs_reply_no_gc($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('bbs_reply_no', 'BBS Reply No', 'required|check_bbs_reply_no_gc');

		if ($val->run(array('bbs_reply_no' => $argument)))
		{
			return $val->validated('bbs_reply_no');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　/ BBS 返信の存在チェック / GC
	*
	* @param integer $no BBSスレッドNo
	* @return boolean
	*/
	public static function _validation_check_bbs_reply_no_gc($bbs_reply_no) {

		if (preg_match('/^[1-9]\d*$/', $bbs_reply_no) !== 1) return false;


		$model_bbs = new \Model_Bbs();
		$model_co = new \Model_Co();


		$temp_arr = array(
			'bbs_reply_no' => $bbs_reply_no,
			'page' => 1,
			'limit' => 1
		);

		// 返信取得
		$result_bbs_reply = $model_bbs->get_bbs_reply_gc($temp_arr);


		// コメント取得
		if (isset($result_bbs_reply[0]['bbs_comment_no']))
		{

			$temp_arr = array(
				'bbs_comment_no' => $result_bbs_reply[0]['bbs_comment_no'],
				'page' => 1,
				'limit' => 1
			);

			$result_bbs_comment = $model_bbs->get_bbs_comment_gc($temp_arr);

		}


		// スレッド取得
		if (isset($result_bbs_comment[0]['bbs_thread_no']))
		{

			$temp_arr = array(
				'bbs_thread_no' => $result_bbs_comment[0]['bbs_thread_no'],
				'page' => 1,
				'limit' => 1
			);

			$result_bbs_thread = $model_bbs->get_bbs_thread_gc($temp_arr);

		}


		return (isset($result_bbs_reply, $result_bbs_comment, $result_bbs_thread)) ? true : false;

	}



	// --------------------------------------------------
	//   返信No　UC
	// --------------------------------------------------

	/**
	* BBS Reply No / UC
	*
	* @param string $argument
	*/
	public function bbs_reply_no_uc($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('bbs_reply_no', 'BBS Reply No', 'required|check_bbs_reply_no_uc');

		if ($val->run(array('bbs_reply_no' => $argument)))
		{
			return $val->validated('bbs_reply_no');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* バリデーションルール　/ BBS 返信の存在チェック / UC
	*
	* @param integer $no BBSスレッドNo
	* @return boolean
	*/
	public static function _validation_check_bbs_reply_no_uc($bbs_reply_no) {

		if (preg_match('/^[1-9]\d*$/', $bbs_reply_no) !== 1) return false;

		//echo 'aaaaaaaa';
		//\Debug::dump($bbs_reply_no);


		$model_bbs = new \Model_Bbs();
		$model_co = new \Model_Co();


		$temp_arr = array(
			'bbs_reply_no' => $bbs_reply_no,
			'page' => 1,
			'limit' => 1
		);

		// 返信取得
		$result_bbs_reply = $model_bbs->get_bbs_reply_uc($temp_arr);


		// コメント取得
		if (isset($result_bbs_reply[0]['bbs_comment_no']))
		{

			$temp_arr = array(
				'bbs_comment_no' => $result_bbs_reply[0]['bbs_comment_no'],
				'page' => 1,
				'limit' => 1
			);

			$result_bbs_comment = $model_bbs->get_bbs_comment_uc($temp_arr);

		}


		// スレッド取得
		if (isset($result_bbs_comment[0]['bbs_thread_no']))
		{

			$temp_arr = array(
				'bbs_thread_no' => $result_bbs_comment[0]['bbs_thread_no'],
				'page' => 1,
				'limit' => 1
			);

			$result_bbs_thread = $model_bbs->get_bbs_thread_uc($temp_arr);

		}


		//echo '$result_bbs_thread';
		//\Debug::dump($result_bbs_thread);

		// ユーザーコミュニティ取得
		if (isset($result_bbs_thread[0]['community_no']))
		{
			$result_community = $model_co->get_community($result_bbs_thread[0]['community_no']);
		}

		//\Debug::dump($result_bbs_reply, $result_bbs_comment, $result_bbs_thread, $result_community);

		//var_dump($result_bbs_reply, $result_bbs_comment, $result_bbs_thread, $result_community);

		return (isset($result_bbs_reply, $result_bbs_comment, $result_bbs_thread, $result_community)) ? true : false;

	}







	// --------------------------------------------------
	//   ハンドルネーム、タイトル、コメント
	// --------------------------------------------------

	/**
	* ハンドルネーム
	*
	* @param string $argument
	*/
	public function handle_name($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_field('handle_name', 'ハンドルネーム', 'required|min_length[1]|max_length[50]');

		if ($val->run(array('handle_name' => $argument)))
		{
			return $val->validated('handle_name');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* タイトル
	*
	* @param string $argument
	*/
	public function title($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_field('title', 'タイトル', 'required|min_length[1]|max_length[50]');

		if ($val->run(array('title' => $argument)))
		{
			return $val->validated('title');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}


	/**
	* コメント
	*
	* @param string $argument
	*/
	public function comment($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_field('comment', 'コメント', 'required|min_length[1]|max_length[3000]');

		if ($val->run(array('comment' => $argument)))
		{
			return $val->validated('comment');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}



	/**
	* Movie URL
	*
	* @param string $argument
	*/
	public function movie_url($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();https://www.youtube.com/watch?v=fmShPzYD9ng
		$val->add_callable($this);
		$val->add_field('movie_url', '動画URL', 'valid_url|check_movie_url');

		if ($val->run(array('movie_url' => $argument)))
		{
			return $val->validated('movie_url');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}



	/**
	* バリデーションルール　動画URLチェック
	*
	* @param string $str 変換前配列
	* @return boolean
	*/
	public static function _validation_check_movie_url($url)
	{
		$parse_arr = parse_url($url);

		if (isset($parse_arr['host']))
		{
			if (strpos($parse_arr['host'], 'youtube.com') !== false or strpos($parse_arr['host'], 'youtu.be') !== false)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		return true;

	}








	// --------------------------------------------------
	//   Wiki
	// --------------------------------------------------


	/**
	* Wiki No
	*
	* @param string $argument
	*/
	public function wiki_no($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable($this);
		$val->add_field('target', 'Wiki No', 'required|check_wiki_no');


		if ($val->run(array('target' => $argument)))
		{
			return $val->validated('target');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}



	/**
	* バリデーションルール　Wiki No
	*
	* @param integer $argument Wiki No
	* @return boolean
	*/
	public static function _validation_check_wiki_no($argument) {

		if (preg_match('/^[1-9]\d*$/', $argument) !== 1) return false;

		$tmp_arr = array(
			'wiki_no' => $argument
		);

		$model_wiki = new \Model_Wiki();
		$result_arr = $model_wiki->get_wiki($tmp_arr);
		// \Debug::dump($argument, $result_arr);
		return (isset($result_arr['data_arr'][0]['wiki_no'])) ? true : false;

	}





	/**
	* Wiki ID
	*
	* @param string $argument
	*/
	public function wiki_id($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_field('target', 'WikiのURL', 'required|valid_string[alpha,lowercase,numeric,dashes]|min_length[3]|max_length[30]');


		if ($val->run(array('target' => $argument)))
		{
			return $val->validated('target');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}







	/**
	* Wiki Name
	*
	* @param string $argument
	*/
	public function wiki_name($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_field('target', 'Wikiの名前', 'required|min_length[3]|max_length[50]');


		if ($val->run(array('target' => $argument)))
		{
			return $val->validated('target');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}




	/**
	* Wiki Comment
	*
	* @param string $argument
	*/
	public function wiki_comment($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_field('target', 'Wikiの説明文', 'required|min_length[1]|max_length[100]');


		if ($val->run(array('target' => $argument)))
		{
			return $val->validated('target');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}



	/**
	* Wiki Password
	*
	* @param string $argument
	*/
	public function wiki_password($argument)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_field('target', '管理者パスワード', 'required|valid_string[alpha,numeric]|min_length[6]|max_length[32]');


		if ($val->run(array('target' => $argument)))
		{

			$original_common_text = new \Original\Common\Text();
			$password_strength = $original_common_text->check_password_strength($val->validated('target'));

			//\Debug::dump($password_strength);


			// ------------------------------
			//    パスワードの強度が足りません。
			// ------------------------------

			if ($password_strength < 2)
			{
				$this->throw_exception(array('Error' => 'パスワードの強度が足りません。'));
			}


			return $val->validated('target');
		}
		else
		{
			$this->throw_exception($val->error());
		}

	}






	/**
	* エラーを投げる
	*
	* @param string $argument
	*/
	public function throw_exception($error_arr)
	{

		$error_message = '';

		if (count($error_arr) > 0)
		{
			foreach ($error_arr as $key => $value)
			{
				$error_message .= $value;
			}
		}

		throw new \Exception($error_message);

	}








	/**
	* テスト用出力
	*
	* @return string HTMLコード
	*/
	public function test_output()
	{

		unset($this->test);

		if (isset($this->test))
		{
			\Debug::$js_toggle_open = true;

			echo '$this->agent_type';
			\Debug::dump($this->agent_type);

			echo '$this->host';
			\Debug::dump($this->host);

			echo '$this->user_agent';
			\Debug::dump($this->user_agent);

			echo '$this->user_no';
			\Debug::dump($this->user_no);

			echo '$this->language';
			\Debug::dump($this->language);

			echo '$this->uri_base';
			\Debug::dump($this->uri_base);

			echo '$this->uri_current';
			\Debug::dump($this->uri_current);

			echo '$this->app_mode';
			\Debug::dump($this->app_mode);



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



			echo '$this->limit_bbs_thread_list';
			\Debug::dump($this->limit_bbs_thread_list);

			echo '$this->limit_bbs_thread';
			\Debug::dump($this->limit_bbs_thread);

			echo '$this->limit_bbs_comment';
			\Debug::dump($this->limit_bbs_comment);

			echo '$this->limit_bbs_reply';
			\Debug::dump($this->limit_bbs_reply);

			echo '$this->pagination_times';
			\Debug::dump($this->pagination_times);



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
