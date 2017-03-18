<?php

class Controller_Rest_Base extends Controller_Rest
{

	// ----- 共通 -----

	// PC・スマホ・タブレット
	protected $agent_type = null;
	protected $os = null;

	// ホスト
	protected $host = null;

	// ユーザーエージェント
	protected $user_agent = null;

	// ユーザーNo
	protected $user_no = null;

	// 言語
	protected $language = null;

	// URI
	protected $uri_base = null;
	protected $uri_current = null;

	// アプリモード
	protected $app_mode = null;

	// 広告の非表示
	protected $ad_block = null;


	/**
	* 事前処理
	*/
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
		//   メンテナンス
		// --------------------------------------------------

		if (Config::get('maintenance') == 2)
		{
			if ( ! Auth::member(100)) exit();
		}


		// --------------------------------------------------
		//   荒らし防止
		// --------------------------------------------------

		if ($_SERVER['REMOTE_ADDR'] == '58.95.147.211') exit(); // i58-95-147-211.s11.a027.ap.plala.or.jp
		if ($_SERVER['REMOTE_ADDR'] == '220.44.94.39') exit(); // softbank220044094039.bbtec.net
		if ($_SERVER['REMOTE_ADDR'] == '124.27.130.212') exit(); // nttkyo537212.tkyo.nt.ftth.ppp.infoweb.ne.jp
		if ($_SERVER['REMOTE_ADDR'] == '210.138.6.103') exit(); // 103.6.138.210.rev.vmobile.jp
		if ($_SERVER['REMOTE_ADDR'] == '114.163.40.24') exit(); // p1024-ipbf2001sapodori.hokkaido.ocn.ne.jp

		// if (strpos(getenv("REMOTE_HOST"), 'tokynt01.ap.so-net.ne.jp') !== FALSE) {
			// exit();
		// }



		parent::before();


		// --------------------------------------------------
		//   ◆◆◆　共通　◆◆◆
		// --------------------------------------------------

		// ------------------------------
		//    PC・スマホ・タブレット チェック
		// ------------------------------

		$original_common_mobilecheck = new Original\Common\Mobilecheck();
		$mobilecheck_arr = $original_common_mobilecheck->return_agent_type2();
		$this->agent_type = $mobilecheck_arr['device'];
		$this->os = $mobilecheck_arr['os'];

		define("AGENT_TYPE", $this->agent_type);
		define("OS", $this->os);


		// ------------------------------
		//    ホスト ＆ ユーザーエージェント取得
		// ------------------------------

		$this->host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];

		define("HOST", $this->host);
		define("USER_AGENT", $this->user_agent);


		// ------------------------------
		//    ユーザーNo
		// ------------------------------

		if (Fuel::$env == 'test')
		{
			if (Input::post('login_user_no'))
			{
				$this->user_no = Input::post('login_user_no');
			}
			else
			{
				$this->user_no = (Auth::check()) ? Auth::get_user_id() : null;
			}
		}
		else
		{
			$this->user_no = (Auth::check()) ? Auth::get_user_id() : null;
		}

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


		// ------------------------------
		//    言語
		// ------------------------------

		$language = Cookie::get('language', null);
		if ($language == null)
		{
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$languages_arr = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
				$language = $languages_arr[0];
			}
			else
			{
				$language = null;
			}
		}
		(isset($language) && preg_match('/^en/i', $language)) ? Config::set('language', 'en') : Config::set('language', 'ja');
		$this->language = Lang::get_lang();

		define("LANGUAGE", $this->language);


		// --------------------------------------------------
		//   URI
		// --------------------------------------------------

		// if (Fuel::$env == 'production')
		// {
		// 	$this->uri_base = Uri::base(false);
		// 	$this->uri_current = Uri::current();
		// }
		// else if ($this->agent_type == "")
		// {
		// 	$this->uri_base = Uri::base(false);
		// 	$this->uri_current = Uri::current();
		// }
		// else
		// {
		// 	$this->uri_base = 'https://192.168.10.2/gameusers/public/';
		// 	$this->uri_current = $this->uri_base . Uri::string();
		// }

		$this->uri_base = Uri::base(false);
		$this->uri_current = Uri::current();

		define("URI_BASE", $this->uri_base);
		define("URI_CURRENT", $this->uri_current);


		// --------------------------------------------------
		//   App Mode
		// --------------------------------------------------

		if (Input::post('app_mode')) $this->app_mode = true;
		//$this->app_mode = true;
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

	}

}
