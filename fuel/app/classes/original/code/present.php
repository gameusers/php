<?php

namespace Original\Code;

class Present
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
	* 抽選エントリーユーザー読み込み
	*
	* @param array $condition_arr 検索条件
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function read_present_users($arr)
	{
		
		//$test = true;
		
		$language = 'ja';
		
		
		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------
		
		$model_present = new \Model_Present();
		$model_present->agent_type = $this->agent_type;
		$model_present->user_no = $this->user_no;
		$model_present->language = $this->language;
		$model_present->uri_base = $this->uri_base;
		$model_present->uri_current = $this->uri_current;
		
		
		// --------------------------------------------------
		//   今週の日曜日を取得
		// --------------------------------------------------
		
		// todayを入れると時刻が00:00:00になる
		$datetime = new \DateTime('today');
		
		// $datetime->format('w')では0～6の数字が返ってくる、それで曜日を判定
		// 日0、月1、火2、水3、木4、金5、土6
		
		//$datetime = new \DateTime('2015-08-02 00:00:00');
		//$arr['previous'] = 2;
		
		// previousに2を入れると先週になる、先々週の場合は3
		if (isset($arr['previous']))
		{
			// 今日が日曜日でない場合、そのまま処理
			if ($datetime->format('w') != 0)
			{
				$datetime->modify('-' . $arr['previous'] . ' Sunday');
			}
			// 今日が日曜日の場合、previousの数を減らす
			else
			{
				$datetime->modify('-' . ($arr['previous'] - 1) . ' Sunday');
				//$datetime->modify('-1 Sunday');
				//var_dump($arr['previous'] - 1);
			}
		}
		else
		{
			if ($datetime->format('w') != 0) $datetime->modify('previous Sunday');
		}
		
		$regi_date = $datetime->format("Y-m-d H:i:s");
		//var_dump($regi_date);
		
		
		// --------------------------------------------------
		//   表示件数取得
		// --------------------------------------------------
		
		$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_present_users') : \Config::get('limit_present_users_sp');
		
		
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------
		
		if (isset($arr['winner'])) $limit = 100;
		
		$db_result_arr = $model_present->get_present_users(array('regi_date' => $regi_date, 'page' => $arr['page'], 'limit' => $limit, 'get_total' => true, 'winner' => $arr['winner']));
		
		$db_present_users_arr = $db_result_arr[0];
		$total = $db_result_arr[1];
		
		
		
		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------
		
		$code = null;
		
		if (count($db_present_users_arr) > 0)
		{
			
			$view = \View::forge('parts/present_users_view');
			$view->set_safe('app_mode', $this->app_mode);
			$view->set('uri_base', $this->uri_base);
			$view->set('db_present_users_arr', $db_present_users_arr);
			$view->set('regi_date', $regi_date);
			$view->set('previous', $arr['previous']);
			$view->set('winner', $arr['winner']);
			
			$code = $view->render() . "\n";
			
			
			// --------------------------------------------------
			//   ページャー
			// --------------------------------------------------
			
			if ($total > $limit)
			{
				$pagination_times = ($this->agent_type != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');
				
				$code .= '<div class="margin_top_20">' . "\n";
				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set('page', $arr['page']);
				$view_pagination->set('total', $total);
				$view_pagination->set('limit', $limit);
				$view_pagination->set('times', $pagination_times);
				$view_pagination->set('function_name', 'readPresentEntryUsers');
				
				$argument_arr = ($arr['previous']) ? array($arr['previous']) : array();
				$view_pagination->set('argument_arr', $argument_arr);
				
				$code .= $view_pagination->render();
				$code .= '</div>' . "\n";
				
			}
			
		}
		
		
		if (isset($test))
		{
			echo '<br>$regi_date';
			var_dump($regi_date);
			
			echo '<br>$limit';
			var_dump($limit);
			
			echo '<br>$db_result_arr';
			var_dump($db_result_arr);
		}
		
		
		return $code;
		
	}
	
	
	
	
	
	/**
	* 当選者ユーザー読み込み
	*
	* @param array $condition_arr 検索条件
	* @param integer $page ページ
	* @return string HTMLコード
	*/
	public function present_users_edit_form($arr)
	{
		
		//$test = true;
		
		$language = 'ja';
		
		
		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------
		
		$model_present = new \Model_Present();
		$model_present->agent_type = $this->agent_type;
		$model_present->user_no = $this->user_no;
		$model_present->language = $this->language;
		$model_present->uri_base = $this->uri_base;
		$model_present->uri_current = $this->uri_current;
		
		
		// --------------------------------------------------
		//   日付を取得
		// --------------------------------------------------
		
		$datetime = new \DateTime($arr['regi_date']);
		$regi_date = $datetime->format("Y-m-d H:i:s");
		
		
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------
		
		// 抽選
		if ($arr['type'] == 'lottery')
		{
			$db_result_arr = $model_present->get_present_users(array('page' => $arr['page'], 'limit' => 1000000, 'regi_date' => $regi_date));
		}
		// 編集
		else
		{
			$db_result_arr = $model_present->get_present_users(array('page' => $arr['page'], 'limit' => 1, 'regi_date' => $regi_date, 'user_no' => $arr['user_no'], 'profile_no' => $arr['profile_no']));
			//var_dump($arr, $db_result_arr);
		}
		
		$db_present_users_arr = $db_result_arr[0];
		$total = $db_result_arr[1];
		//var_dump($db_present_users_arr);
		
		// --------------------------------------------------
		//   配列作成　抽選
		// --------------------------------------------------
		
		$lottery_arr = array();
		$db_user_arr = array();
		
		if ($arr['type'] == 'lottery')
		{
			
			foreach ($db_present_users_arr as $key => $value)
			{
				for ($i=0; $i < $value['total']; $i++)
				{ 
					if ($value['type'] == null and $value['user_no'] != 1) array_push($lottery_arr, $value['present_no']);
				}
			}
			//var_dump($lottery_arr);
			
			if (count($lottery_arr) > 0)
			{
				$random_key = array_rand($lottery_arr, 1);
				
				foreach ($db_present_users_arr as $key => $value) {
					
					if ($value['present_no'] == $lottery_arr[$random_key])
					{
						$db_user_arr = $value;
						break;
					}
					
				}
			}
			
			
		}
		
		// --------------------------------------------------
		//   配列作成　編集
		// --------------------------------------------------
		
		else
		{
			// var_dump($db_result_arr);
			// exit();
			if (isset($db_present_users_arr[0])) $db_user_arr = $db_present_users_arr[0];
		}
		
		
		//var_dump($lottery_arr, $random_key, $db_user_arr);
		
		
		
		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------
		
		$code = null;
		
		if (count($db_user_arr) > 0)
		{
			
			$view = \View::forge('parts/present_users_edit_view');
			$view->set_safe('app_mode', $this->app_mode);
			$view->set('uri_base', $this->uri_base);
			$view->set('db_user_arr', $db_user_arr);
			$view->set('type', $arr['type']);
			
			$code = $view->render() . "\n";
			
		}
		
		
		if (isset($test))
		{
			echo '<br>$regi_date';
			var_dump($regi_date);
			
			echo '<br>$db_result_arr';
			var_dump($db_result_arr);
		}
		
		
		return $code;
		
	}
	
	
}