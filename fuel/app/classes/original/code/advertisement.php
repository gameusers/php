<?php

namespace Original\Code;

class Advertisement
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
	private $login_profile_data_arr = null;
	private $datetime_now = null;
	private $online_limit = null;
	private $anonymity = null;
	
	
	
	// ------------------------------
	//   インスタンス
	// ------------------------------
	
	private $model_game = null;
	private $model_amazon = null;
	private $model_user = null;
	// private $model_co = null;
	// private $model_notifications = null;
	// private $model_present = null;
	// private $model_bbs = null;
	// private $original_func_common = null;
	// private $original_func_co = null;
	
	private $original_validation_common = null;
	private $original_common_date = null;
	
	
	public $test = true;
	
	
	
	
	// --------------------------------------------------
	//   共通　Setter / Getter
	// --------------------------------------------------
	
	//public static function before()
	//public static function _init()
	public function __construct()
	{
		
		// ------------------------------
		//   インスタンス作成
		// ------------------------------
		
		$this->model_game = new \Model_Game();
		$this->model_game->agent_type = AGENT_TYPE;
		$this->model_game->user_no = USER_NO;
		$this->model_game->language = LANGUAGE;
		$this->model_game->uri_base = URI_BASE;
		$this->model_game->uri_current = URI_CURRENT;
		
		
		
		$this->model_amazon = new \Model_Amazon();
		
		
		$this->model_user = new \Model_User();
		$this->model_user->agent_type = AGENT_TYPE;
		$this->model_user->user_no = USER_NO;
		$this->model_user->language = LANGUAGE;
		$this->model_user->uri_base = URI_BASE;
		$this->model_user->uri_current = URI_CURRENT;
		/*
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
		*/
		$this->original_validation_common = new \Original\Validation\Common();
		
		$this->original_common_date = new \Original\Common\Date();
		
		
		
		// ------------------------------
		//   定数設定
		// ------------------------------
		/*
		if (AGENT_TYPE != 'smartphone')
		{
			define("LIMIT_BBS_THREAD_LIST", (int) \Config::get('limit_bbs_thread_list'));
			define("LIMIT_BBS_THREAD", (int) \Config::get('limit_bbs_thread'));
			define("LIMIT_BBS_COMMENT", (int) \Config::get('limit_bbs_comment'));
			define("LIMIT_BBS_REPLY", (int) \Config::get('limit_bbs_reply'));
		}
		else
		{
			define("LIMIT_BBS_THREAD_LIST", (int) \Config::get('limit_bbs_thread_list_sp'));
			define("LIMIT_BBS_THREAD", (int) \Config::get('limit_bbs_thread_sp'));
			define("LIMIT_BBS_COMMENT", (int) \Config::get('limit_bbs_comment_sp'));
			define("LIMIT_BBS_REPLY", (int) \Config::get('limit_bbs_reply_sp'));
		}
		*/
	}
	
	
	
	

	
	
	// --------------------------------------------------
	//   共通
	// --------------------------------------------------
	
	
	/**
	* Setter / ゲームNo / 共通
	*
	* @param string $argument ゲームNo
	*/
	public function set_game_no($argument)
	{
		$this->game_no = (int) $this->original_validation_common->game_no($argument);;
	}
	
	public function get_game_no()
	{
		return $this->game_no;
	}
	
	
		
		
	// --------------------------------------------------
	//   コード取得
	// --------------------------------------------------
	
	
	/**
	* アプリインストール広告
	*
	* @return string HTMLコード
	*/
	public function get_code_install_app()
	{
		
		// --------------------------------------------------
		//   必要な変数がない場合は処理停止、空を返す
		// --------------------------------------------------
		
		if ( ! $this->get_game_no()) return null;
		
		
		
		$db_game_data_arr = $this->model_game->search_game_data_advertisement(array('language' => 'ja', 'game_no' => $this->get_game_no()));
		
		
		
		$arr = array();
		
		foreach ($db_game_data_arr as $key => $value)
		{
			$advertisement_arr = unserialize($value['advertisement']);
			
			$arr[$value['game_no']] = $advertisement_arr;
			$arr[$value['game_no']]['name'] = $value['name'];
		}
		
		
		
		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------
		
		$view = \View::forge('parts/app_install_advertisement_view2');
		
		$view->set('arr', $arr);
		
		return $view->render();
		
	}
	
	
	
	
	
	/**
	* スライドゲームリスト
	*
	* @return string HTMLコード
	*/
	public function code_ad_amazon_slide($arr)
	{
		
		// --------------------------------------------------
		//   アプリのときは表示しない
		// --------------------------------------------------
		
		if (APP_MODE) return null;
		
		
		
		$language = 'ja';
		$limit = (AGENT_TYPE != 'smartphone') ? \Config::get('limit_ad_amazon_slide') : \Config::get('limit_ad_amazon_slide_sp');
		
		
		// --------------------------------------------------
		//   登録されているAmazonトラッキングIDを取得する
		// --------------------------------------------------
		
		// デフォルトのID
		$amazon_tracking_id = \Config::get('amazon_tracking_id');
		
		// ユーザーのID
		if (isset($arr['user_no']))
		{
			$db_user_data_arr = $this->model_user->get_user_data($arr['user_no'], null);
			
			if (isset($db_user_data_arr['user_advertisement']))
			{
				$user_advertisement_arr = unserialize($db_user_data_arr['user_advertisement']);
				
				if (isset($user_advertisement_arr['amazon_tracking_id']))
				{
					$amazon_tracking_id = $user_advertisement_arr['amazon_tracking_id'];
				}
				else
				{
					return null;
				}
				//\Debug::dump($amazon_tracking_id);
			}
			else
			{
				return null;
			}
		}
		
		
		
		// --------------------------------------------------
		//   24時間前の日付
		// --------------------------------------------------
		
		$datetime_past = $this->original_common_date->sql_format('-24hours');
		
		
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------
		
		$temp_arr = array('page' => 1, 'limit' => $limit, 'datetime_past' => $datetime_past);
		$data_arr = $this->model_amazon->get_ad_amazon_slide($temp_arr);
		
		
		
		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------
		
		$view = \View::forge('parts/slide_amazon_ad_view');
		$view->set('data_arr', $data_arr);
		$view->set('amazon_tracking_id', $amazon_tracking_id);
		$code = $view->render();
		
		
		
		//$test = true;
		
		if (isset($test))
		{
				
			\Debug::$js_toggle_open = true;
			
			echo '$data_arr';
			\Debug::dump($data_arr);
			
			echo $code;
		}
		
		//exit();
		
		
		
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