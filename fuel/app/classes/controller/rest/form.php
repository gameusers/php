<?php

class Controller_Rest_Form extends Controller_Rest_Base
{


	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	private $model_user = null;
	private $model_game = null;
	private $model_co = null;
	private $original_code_gc = null;
	private $original_func_co = null;
	private $original_func_common = null;
	private $original_validation_common = null;
	private $original_common_date = null;


	private $type = null;
	private $game_no = null;
	private $community_no = null;
	private $db_users_game_community_arr = null;
	private $db_community_arr = null;
	private $login_profile_data_arr = null;
	private $datetime_now = null;



	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();


		// --------------------------------------------------
		//   CSRFチェック
		// --------------------------------------------------

		$this->original_validation_common = new Original\Validation\Common();
		$this->original_validation_common->csrf(Input::post('fuel_csrf_token'));


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		//$this->original_code_bbs = new Original\Code\Bbs();

		$this->model_user = new Model_User();
		$this->model_user->agent_type = $this->agent_type;
		$this->model_user->user_no = $this->user_no;
		$this->model_user->language = $this->language;
		$this->model_user->uri_base = $this->uri_base;
		$this->model_user->uri_current = $this->uri_current;

		$this->model_game = new Model_Game();
		$this->model_game->agent_type = $this->agent_type;
		$this->model_game->user_no = $this->user_no;
		$this->model_game->language = $this->language;
		$this->model_game->uri_base = $this->uri_base;
		$this->model_game->uri_current = $this->uri_current;

		$this->model_co = new Model_Co();
		$this->model_co->agent_type = $this->agent_type;
		$this->model_co->user_no = $this->user_no;
		$this->model_co->language = $this->language;
		$this->model_co->uri_base = $this->uri_base;
		$this->model_co->uri_current = $this->uri_current;

		$this->original_code_gc = new Original\Code\Gc();
		$this->original_code_gc->app_mode = $this->app_mode;
		$this->original_code_gc->agent_type = $this->agent_type;
		$this->original_code_gc->host = HOST;
		$this->original_code_gc->user_agent = USER_AGENT;
		$this->original_code_gc->user_no = $this->user_no;
		$this->original_code_gc->language = $this->language;
		$this->original_code_gc->uri_base = $this->uri_base;
		$this->original_code_gc->uri_current = $this->uri_current;

		$this->original_func_co = new Original\Func\Co();
		$this->original_func_co->app_mode = $this->app_mode;
		$this->original_func_co->agent_type = $this->agent_type;
		$this->original_func_co->user_no = $this->user_no;
		$this->original_func_co->language = $this->language;
		$this->original_func_co->uri_base = $this->uri_base;
		$this->original_func_co->uri_current = $this->uri_current;

		$this->original_func_common = new Original\Func\Common();
		$this->original_func_common->app_mode = $this->app_mode;
		$this->original_func_common->agent_type = $this->agent_type;
		$this->original_func_common->user_no = $this->user_no;
		$this->original_func_common->language = $this->language;
		$this->original_func_common->uri_base = $this->uri_base;
		$this->original_func_common->uri_current = $this->uri_current;


		$this->original_validation_common = new \Original\Validation\Common();

		$this->original_common_date = new \Original\Common\Date();




		// --------------------------------------------------
		//    Type セット
		// --------------------------------------------------

		//$_POST['type'] = 'uc';
		$this->set_type(Input::post('type'));


		// --------------------------------------------------
		//    Game No セット
		// --------------------------------------------------

		//$_POST['game_no'] = '1';
		$this->set_game_no(Input::post('game_no'));
		//echo Input::post('game_no');


		// --------------------------------------------------
		//    Community No セット
		// --------------------------------------------------

		//$_POST['community_no'] = '1';
		$this->set_community_no(Input::post('community_no'));



		// --------------------------------------------------
		//    ログインユーザー情報　セット
		// --------------------------------------------------

		$this->set_login_profile_data_arr();
		//\Debug::dump($this->get_login_profile_data_arr());


		// --------------------------------------------------
		//    日時
		// --------------------------------------------------

		$this->set_datetime_now();

	}




	/**
	* Setter / Type
	*
	* @param integer $argument
	*/
	public function set_type($argument)
	{
		if ($argument)
		{
			if ($argument == 'gc' or $argument == 'uc')
			{
				$this->type = $argument;
			}
			else
			{
				throw new \Exception('Error');
			}
		}
		else
		{
			$this->type = null;
		}
	}

	public function get_type()
	{
		return $this->type;
	}




	/**
	* Setter / Game No
	*
	* @param integer $argument
	*/
	public function set_game_no($argument)
	{
		if ($argument)
		{
			$this->game_no = (int) $this->original_validation_common->game_no($argument);
		}
		else
		{
			$this->game_no = null;
		}
	}

	public function get_game_no()
	{
		return $this->game_no;
	}



	/**
	* Setter / Community No
	*
	* @param integer $argument
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



	/**
	* Setter / ログインユーザー情報
	*
	* @param integer $argument
	*/
	public function set_login_profile_data_arr()
	{
		if ($this->get_type() == 'gc' and $this->get_game_no())
		{
			$login_user_data_arr = $this->model_user->get_login_user_data($this->get_game_no());
			$this->db_users_game_community_arr = $login_user_data_arr[0];
			$this->login_profile_data_arr = $login_user_data_arr[1];
			//$this->login_profile_data_arr = (int) $this->original_validation_common->game_no($argument);
		}
		else if ($this->get_type() == 'uc' and $this->get_community_no())
		{
			$this->db_community_arr = $this->model_co->get_community($this->get_community_no(), null);
			$this->login_profile_data_arr = $this->original_func_co->login_profile_data($this->db_community_arr);
			//\Debug::dump($login_profile_data_arr);
		}
		else
		{
			$this->login_profile_data_arr = null;
		}
	}

	public function get_login_profile_data_arr()
	{
		return $this->login_profile_data_arr;
	}



	/**
	* Setter / 日時
	*
	* @param integer $argument
	*/
	public function set_datetime_now()
	{
		$this->datetime_now = $this->original_common_date->sql_format();;
	}

	public function get_datetime_now()
	{
		return $this->datetime_now;
	}




	/**
	* スレッド作成
	*
	* @return string HTMLコード
	*/
	public function post_modal_read_bbs_create_thread()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['content_id'] = 'eqf51l0q8e';
			//$_POST['type'] = 'gc';
			$this->set_type('uc');
			$this->set_community_no(9);
			$this->set_login_profile_data_arr();
		}

		//echo 'aaa';
		//exit();


		$arr = array();

		try
		{


			if ($this->get_type() == 'gc')
			{
				$no = $this->get_game_no();
				$anonymity = true;
			}
			else
			{
				$no = $this->get_community_no();
				$config_arr = unserialize($this->db_community_arr['config']);
				$anonymity = ($config_arr['anonymity']) ? true : false;
			}


			// \Debug::dump($this->login_profile_data_arr);
			// \Debug::dump($this->db_community_arr);
			// \Debug::dump($config_arr);
			// echo 'anonymity = ';
			// \Debug::dump($anonymity);

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$view_form_common = View::forge('parts/form_common_view');
			$view_form_common->set('app_mode', APP_MODE);
			$view_form_common->set('uri_base', URI_BASE);
			$view_form_common->set('login_user_no', USER_NO);
			$view_form_common->set('datetime_now', $this->get_datetime_now());
			$view_form_common->set('profile_arr', $this->login_profile_data_arr);
			$view_form_common->set('online_limit', Config::get('online_limit'));
			$view_form_common->set('anonymity', $anonymity);
			$view_form_common->set('func_name', 'saveBbsThread');
			$view_form_common->set('func_argument_arr', array("'" . $this->get_type() . "'", $no, 'null'));


			//$code = '<aside class="bbs_create_thread_box">' . "\n";
			//$code .= $view_form_common->render();
			//$code .= '</aside>' . "\n";


			//$form_id = Input::post('content_id') . '_' . $no . '_bbs_create_thread';
			$form_id = 'bbs_create_thread';

			$view = View::forge('parts/modal_view');
			$view->set('form_id', $form_id);
			$view->set('title', 'スレッド作成');
			$view->set_safe('body', $view_form_common);

			$arr['code'] = $view->render();

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

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
	* 募集通知
	*
	* @return string HTMLコード
	*/
	public function post_modal_read_notification_recruitment_config()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['content_id'] = 'eqf51l0q8e';
			//$_POST['type'] = 'gc';
			//$_POST['type'] = 'uc';
			//$_POST['game_no'] = 1;
			//$_POST['page'] = 3;
		}

		//echo 'aaa';
		//exit();


		$arr = array();

		try
		{

			// --------------------------------------------------
			//    users_game_communityのnotification_recruitmentに、このゲームのゲームNoが登録されているかのチェック
			//    新規募集通知の初期値がはいか、いいえか
			// --------------------------------------------------

			$db_notification_recruitment = $this->db_users_game_community_arr['notification_recruitment'];

			if ($db_notification_recruitment)
			{
				$notification_recruitment_arr = $this->original_func_common->return_db_array('db_php', $db_notification_recruitment);
			}
			else
			{
				$notification_recruitment_arr = array();
			}

			$notification_recruitment_on = (in_array($this->get_game_no(), $notification_recruitment_arr)) ? true : false;
			//\Debug::dump($db_notification_recruitment, $notification_recruitment_arr, $this->get_game_no(), $notification_recruitment_on);


			// --------------------------------------------------
			//   ゲームタイトル
			// --------------------------------------------------

			$db_game_data_arr = $this->model_game->get_game_data($this->get_game_no(), null);
			$language = 'ja';
			$game_title = $db_game_data_arr['name_' . $language];


			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$view_recruitment_notification_config = View::forge('parts/form_recruitment_notification_view');
			$view_recruitment_notification_config->set('login_user_no', USER_NO);
			$view_recruitment_notification_config->set('game_no', $this->get_game_no());
			$view_recruitment_notification_config->set('game_title', $game_title);
			$view_recruitment_notification_config->set('notification_recruitment_on', $notification_recruitment_on);



			$form_id = $this->get_game_no() . '_recruitment_notification_config';

			$view = View::forge('parts/modal_view');
			$view->set('form_id', $form_id);
			$view->set('title', '募集通知');
			$view->set_safe('body', $view_recruitment_notification_config);

			$arr['code'] = $view->render();

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

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
	* 募集投稿フォーム
	*
	* @return string HTMLコード
	*/
	public function post_modal_form_recruitment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['content_id'] = 'eqf51l0q8e';
			//$_POST['type'] = 'gc';
			//$_POST['type'] = 'uc';
			//$_POST['game_no'] = 1;
			//$_POST['page'] = 3;
			//$this->set_game_no(1);
		}

		//echo 'aaa';
		//exit();


		$arr = array();

		try
		{

			$language = 'ja';


			// --------------------------------------------------
			//    ハードウェア
			// --------------------------------------------------

			$db_hardware_arr = $this->model_game->get_hardware_sort($language);


			$code_form_recruitment = $this->original_code_gc->form_recruitment($this->get_game_no(), 'recruitment_new', null, null, $this->login_profile_data_arr, $db_hardware_arr, $this->get_datetime_now());


			//\Debug::dump($this->login_profile_data_arr);

			//echo $code_form_recruitment;

			// --------------------------------------------------
			//    users_game_communityのnotification_recruitmentに、このゲームのゲームNoが登録されているかのチェック
			//    新規募集通知の初期値がはいか、いいえか
			// --------------------------------------------------

			// $db_notification_recruitment = $this->db_users_game_community_arr['notification_recruitment'];
//
			// if ($db_notification_recruitment)
			// {
				// $notification_recruitment_arr = $this->original_func_common->return_db_array('db_php', $db_notification_recruitment);
			// }
			// else
			// {
				// $notification_recruitment_arr = array();
			// }
//
			// $notification_recruitment_on = (in_array($this->get_game_no(), $notification_recruitment_arr)) ? true : false;


			// --------------------------------------------------
			//   ゲームタイトル
			// --------------------------------------------------

			// $db_game_data_arr = $this->model_game->get_game_data($this->get_game_no(), null);
			// $language = 'ja';
			// $game_title = $db_game_data_arr['name_' . $language];


			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			// $view_recruitment_notification_config = View::forge('parts/form_recruitment_notification_view');
			// $view_recruitment_notification_config->set('login_user_no', USER_NO);
			// $view_recruitment_notification_config->set('game_no', $this->get_game_no());
			// $view_recruitment_notification_config->set('game_title', $game_title);
			// $view_recruitment_notification_config->set('notification_recruitment_on', $notification_recruitment_on);
//


			$form_id = $this->get_game_no() . '_form_recruitment';

			$view = View::forge('parts/modal_view');
			$view->set('form_id', $form_id);
			$view->set('title', '募集投稿');
			$view->set_safe('body', $code_form_recruitment);
			//$view->set('modal_size', 'modal-lg');

			$arr['code'] = $view->render();

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

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
	* 通知受信
	*
	* @return string HTMLコード
	*/
	public function post_modal_announcement()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['content_id'] = 'eqf51l0q8e';
			$this->set_type('uc');
			$this->set_community_no(9);
			$this->set_login_profile_data_arr();
		}


		$arr = array();

		try
		{


			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$view = View::forge('parts/form_common_view');
			$view->set_safe('app_mode', APP_MODE);
			$view->set('uri_base', URI_BASE);
			$view->set('login_user_no', USER_NO);
			$view->set('datetime_now', $this->get_datetime_now());
			$view->set('profile_arr', $this->get_login_profile_data_arr());
			$view->set_safe('online_limit', Config::get('online_limit'));
			$view->set_safe('anonymity', false);
			$view->set('func_name', 'GAMEUSERS.uc.saveAnnouncement');

			$func_argument_arr = array($this->get_community_no(), 'null');
			$view->set('func_argument_arr', $func_argument_arr);
			// $view->set('func_name_return', 'removeAnnouncementForm');
			// $view->set('func_argument_return_arr', null);



			$form_id = $this->get_community_no() . '_announcement';

			$view_modal = View::forge('parts/modal_view');
			$view_modal->set('form_id', $form_id);
			$view_modal->set('title', '告知を作成する');
			$view_modal->set_safe('body', $view);
			//$view_modal->set('modal_size', 'modal-lg');

			$arr['code'] = $view_modal->render();

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

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
