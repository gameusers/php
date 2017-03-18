<?php

namespace Original\Code;

class Gc2
{
	
	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------
	
	// ------------------------------
	//   インスタンス
	// ------------------------------
	
	private $model_common = null;
	private $model_game = null;
	private $model_gc = null;
	private $original_validation_common = null;
	private $original_common_date = null;
	
	
	
	// --------------------------------------------------
	//   コンストラクター
	// --------------------------------------------------
	
	public function __construct()
	{
		
		// ------------------------------
		//   インスタンス作成
		// ------------------------------
		
		// $this->model_user = new \Model_User();
		// $this->model_user->agent_type = AGENT_TYPE;
		// $this->model_user->user_no = USER_NO;
		// $this->model_user->language = LANGUAGE;
		// $this->model_user->uri_base = URI_BASE;
		// $this->model_user->uri_current = URI_CURRENT;
		
		// $this->model_co = new \Model_Co();
		// $this->model_co->agent_type = AGENT_TYPE;
		// $this->model_co->user_no = USER_NO;
		// $this->model_co->language = LANGUAGE;
		// $this->model_co->uri_base = URI_BASE;
		// $this->model_co->uri_current = URI_CURRENT;
		
		$this->model_common = new \Model_Common();
		$this->model_common->agent_type = AGENT_TYPE;
		$this->model_common->user_no = USER_NO;
		$this->model_common->language = LANGUAGE;
		$this->model_common->uri_base = URI_BASE;
		$this->model_common->uri_current = URI_CURRENT;
		
		$this->model_game = new \Model_Game();
		$this->model_game->agent_type = AGENT_TYPE;
		$this->model_game->user_no = USER_NO;
		$this->model_game->language = LANGUAGE;
		$this->model_game->uri_base = URI_BASE;
		$this->model_game->uri_current = URI_CURRENT;
		
		$this->model_gc = new \Model_Gc();
		$this->model_gc->agent_type = AGENT_TYPE;
		$this->model_gc->user_no = USER_NO;
		$this->model_gc->language = LANGUAGE;
		$this->model_gc->uri_base = URI_BASE;
		$this->model_gc->uri_current = URI_CURRENT;
		
		$this->original_validation_common = new \Original\Validation\Common();
		
		$this->original_common_date = new \Original\Common\Date();
		
		
		
		// ------------------------------
		//   定数設定
		// ------------------------------
		
		if (AGENT_TYPE != 'smartphone')
		{
			define("INDEX_LIMIT_RECRUITMENT", (int) \Config::get('index_limit_recruitment'));
		}
		else
		{
			define("INDEX_LIMIT_RECRUITMENT", (int) \Config::get('index_limit_recruitment_sp'));
		}
		
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
		//   募集取得
		// --------------------------------------------------
		
		//$limit = ($this->agent_type != 'smartphone') ? \Config::get('index_limit_recruitment') : \Config::get('index_limit_recruitment_sp');
		
		$db_recruitment_arr = array();
		$game_no_arr = array();
		
		// キーワードで検索する場合
		if (isset($arr['keyword']))
		{
			$db_game_data_arr = $this->model_game->search_game_name($language, $arr['keyword'], 20);
			
			// ゲームNo取得
			foreach ($db_game_data_arr as $key => $value)
			{
				array_push($game_no_arr, $value['game_no']);
			}
			
			if (count($game_no_arr) > 0)
			{
				
				$result_arr = $this->model_gc->get_recruitment(array(
					'game_no_arr' => $game_no_arr,
					'language' => $language,
					'get_total' => true,
					'page' => $arr['page'],
					'limit' => INDEX_LIMIT_RECRUITMENT
				));
				$db_recruitment_arr = $result_arr[0];
				$total = $result_arr[1];
				
			}
			
			//var_dump($game_no_arr, $db_recruitment_arr);
			//exit();
		}
		else
		{
			$result_arr = $this->model_gc->get_recruitment(array(
				'game_no' => null,
				'language' => $language,
				'get_total' => true,
				'page' => $arr['page'],
				'limit' => INDEX_LIMIT_RECRUITMENT
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
			$game_names_arr = $this->model_game->get_game_name($language, $game_no_arr);
			
		}
		else if (count($game_no_arr) > 0)
		{
			// ゲーム名取得
			$game_names_arr = $this->model_game->get_game_name($language, $game_no_arr);
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
			
			$view = \View::forge('parts/recruitment_list_view');
			$view->set_safe('app_mode', APP_MODE);
			$view->set('uri_base', URI_BASE);
			$view->set('db_recruitment_arr', $db_recruitment_arr);
			$view->set('game_names_arr', $game_names_arr);
			//$view->set_safe('all', $all);
			
			$code = $view->render() . "\n";
			
			
			// --------------------------------------------------
			//   ページャー
			// --------------------------------------------------
			
			if ($total > INDEX_LIMIT_RECRUITMENT)
			{
				
				$code .= '<div class="margin_top_20">' . "\n";
				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set_safe('page', $arr['page']);
				$view_pagination->set_safe('total', $total);
				$view_pagination->set_safe('limit', INDEX_LIMIT_RECRUITMENT);
				$view_pagination->set_safe('times', PAGINATION_TIMES);
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
	
	
	
	
}