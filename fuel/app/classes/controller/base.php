<?php

class Controller_Base extends Controller
{

	protected $agent_type = null;
	protected $os = null;
	protected $host = null;
	protected $user_agent = null;
	protected $user_no = null;
	protected $user_id = null;
	protected $language = null;
	protected $uri_base = null;
	protected $uri_current = null;
	protected $app_mode = null;
	protected $ad_block = null;

	protected $initial_data = null;

	public function before()
	{

		// --------------------------------------------------
		//   テストモード
		// --------------------------------------------------

		if (Config::get('test_mode') == true)
		{
			Fuel::$env = Fuel::TEST;
		}


		// --------------------------------------------------
		//   PC・スマホ・タブレット チェック
		// --------------------------------------------------

		$original_common_mobilecheck = new Original\Common\Mobilecheck();
		$mobilecheck_arr = $original_common_mobilecheck->return_agent_type2();
		$this->agent_type = $mobilecheck_arr['device'];
		$this->os = $mobilecheck_arr['os'];

		define("AGENT_TYPE", $this->agent_type);
		define("OS", $this->os);


		// --------------------------------------------------
		//   ホスト＆ ユーザーエージェント
		// --------------------------------------------------

		$this->host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];

		define("HOST", $this->host);
		define("USER_AGENT", $this->user_agent);


		// --------------------------------------------------
		//   ログインNo
		// --------------------------------------------------

		$this->user_no = (Auth::check()) ? Auth::get_user_id() : null;

		define("USER_NO", $this->user_no);


		// --------------------------------------------------
		//   プレイヤーID
		// --------------------------------------------------

		$this->user_id = null;

		if (isset($this->user_no))
		{
			$model_user = new Model_User();
			$base_user_data_arr = $model_user->get_user_id_cach($this->user_no);
			$this->user_id = $base_user_data_arr['user_id'];
		}

		define("USER_ID", $this->user_id);


		// --------------------------------------------------
		//   言語
		// --------------------------------------------------

		$language = Cookie::get('language', null);
		if ($language == null and isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$languages_arr = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$language = $languages_arr[0];
		}
		(isset($language) && preg_match('/^ja/i', $language)) ? Config::set('language', 'ja') : Config::set('language', 'en');
		$this->language = Lang::get_lang();

		define("LANGUAGE", $this->language);


		// --------------------------------------------------
		//   言語データ読み込み
		// --------------------------------------------------

		Lang::load('common');


		// --------------------------------------------------
		//   URI
		// --------------------------------------------------

		$this->uri_base = Uri::base(false);
		$this->uri_current = Uri::current();

		define("URI_BASE", $this->uri_base);
		define("URI_CURRENT", $this->uri_current);



		// --------------------------------------------------
		//   App Mode
		// --------------------------------------------------

		define("APP_MODE", $this->app_mode);


		// --------------------------------------------------
		//   広告の非表示
		// --------------------------------------------------

		if ($this->user_no == 1 or Auth::member(100)) $this->ad_block = true;

		define("AD_BLOCK", $this->ad_block);




		// ------------------------------
		//   ページャーの数字表示回数
		// ------------------------------

		if (AGENT_TYPE != 'smartphone')
		{
			define("PAGINATION_TIMES", (int) \Config::get('pagination_times'));
		}
		else
		{
			define("PAGINATION_TIMES", (int) \Config::get('pagination_times_sp'));
		}


		// --------------------------------------------------
		//   初期ステート
		// --------------------------------------------------

		// $this->initialStateArr['agentType'] = AGENT_TYPE;
		// $this->initialStateArr['host'] = HOST;
		// $this->initialStateArr['userAgent'] = USER_AGENT;
		// $this->initialStateArr['userNo'] = USER_NO;
		// $this->initialStateArr['playerId'] = USER_ID;
		// $this->initialStateArr['language'] = LANGUAGE;
		// $this->initialStateArr['urlBase'] = URI_BASE;
		// $this->initialStateArr['adBlock'] = AD_BLOCK;
		// $this->initialStateArr['paginationColumn'] = PAGINATION_TIMES;


		// --------------------------------------------------
		//   未読予約保存IDがある場合、保存する
		// --------------------------------------------------

		$model_notifications = new Model_Notifications();
		$model_notifications->agent_type = $this->agent_type;
		$model_notifications->user_no = $this->user_no;
		$model_notifications->language = $this->language;
		$model_notifications->uri_base = $this->uri_base;
		$model_notifications->uri_current = $this->uri_current;

		$model_notifications->save_notifications_id();

	}

}
