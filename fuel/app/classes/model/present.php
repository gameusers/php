<?php

class Model_Present extends Model_Crud
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
	
	
	
	
	// --------------------------------------------------
	//   プレゼント
	// --------------------------------------------------
	
	// ------------------------------
	//    取得
	// ------------------------------
	
	/**
	* ユーザーを取得
	* @return array
	*/
	public function get_present_users($arr)
	{
		$offset = $arr['limit'] * ($arr['page'] - 1);
		
		$query = DB::select(
			'present_users.*',
			array('users_data.handle_name', 'user_handle_name'),
			array('users_data.user_id', 'user_id'),
			array('profile.handle_name', 'profile_handle_name'),
			array('profile.open_profile', 'open_profile')
		)->from('present_users');
		
		$query->join('users_data', 'LEFT');
		$query->on('present_users.user_no', '=', 'users_data.user_no');
		$query->join('profile', 'LEFT');
		$query->on('present_users.profile_no', '=', 'profile.profile_no');
		
		if (isset($arr['regi_date'])) $query->where('present_users.regi_date', '=', $arr['regi_date']);
		if (isset($arr['winner'])) $query->where('present_users.type', '!=', null);
		if (isset($arr['user_no'])) $query->where('present_users.user_no', '=', $arr['user_no']);
		if (isset($arr['profile_no'])) $query->where('present_users.profile_no', '=', $arr['profile_no']);
		$query->where('users_data.on_off', '=', 1);
		
		$query->order_by('total','desc');
		$query->limit($arr['limit']);
		$query->offset($offset);
		$result_arr = $query->execute()->as_array();
		
		$total = (isset($arr['get_total'])) ? DB::count_last_query() : null;
		
		return array($result_arr, $total);
	}
	
	
	
	/**
	* プレイヤーページで当選者発表をするためのデータを取得
	* @return array
	*/
	public function get_present_winner($arr)
	{
		
		$query = DB::select(
			'present_users.*',
			array('users_data.handle_name', 'user_handle_name'),
			array('users_data.user_id', 'user_id'),
			array('profile.handle_name', 'profile_handle_name'),
			array('profile.open_profile', 'open_profile')
		)->from('present_users');
		
		$query->join('users_data', 'LEFT');
		$query->on('present_users.user_no', '=', 'users_data.user_no');
		$query->join('profile', 'LEFT');
		$query->on('present_users.profile_no', '=', 'profile.profile_no');
		
		$original_common_date = new Original\Common\Date();
		$limit_announcement_winner_date = $original_common_date->sql_format(Config::get('limit_announcement_winner_date'));
		
		$query->where('present_users.regi_date', '>', $limit_announcement_winner_date);
		$query->where('present_users.user_no', '=', $arr['user_no']);
		$query->where('present_users.type', '!=', null);
		$query->where('present_users.sum', '!=', null);
		$query->where('present_users.unit', '!=', null);
		$query->where('present_users.code', '!=', null);
		
		$query->order_by('regi_date','desc');
		$result_arr = $query->execute()->as_array();
		
		//var_dump($limit_announcement_winner_date, $result_arr);
		
		if (count($result_arr) > 0)
		{
			return $result_arr;
		}
		else
		{
			return null;
		}
		
	}
	
	
	
	
	/**
	* 更新
	* @param array $arr 配列
	* @return array
	*/
	public function update_present_users($present_no, $save_arr)
	{
		
		$query = DB::update('present_users');
		$query->set($save_arr);
		$query->where('present_no', '=', $present_no);
		$result_arr = $query->execute();
		
		return $result_arr;
		
	}
	
	
	
	
	/**
	* 挿入
	* @param array $arr 配列
	* @return array
	*/
	public function insert_present_users($save_arr)
	{
		
		$query = DB::insert('present_users');
		$query->set($save_arr);
		$result_arr = $query->execute();
		
		return $result_arr;
		
	}
	
	
	
	
	
	/**
	* 抽選エントリーポイントを増減
	* @return array
	*/
	public function plus_minus_point($arr)
	{
		
        //　プレゼント停止
        return false;
        
        
		// --------------------------------------------------
		//   0ポイントの場合は処理停止
		// --------------------------------------------------
		
		if ($arr['point'] == 0) return false;
		
		
		// --------------------------------------------------
		//   ログインしてない場合は処理停止
		// --------------------------------------------------
		
		if ( ! $this->user_no) return false;
		
		
		// --------------------------------------------------
		//   管理者の場合は処理停止
		// --------------------------------------------------
		
		if (Auth::member(100)) return false;
		
		
		
		// --------------------------------------------------
		//   今週の日曜日を取得
		// --------------------------------------------------
		
		$datetime_this_sunday = new DateTime('today');
		if ($datetime_this_sunday->format('w') != 0) $datetime_this_sunday->modify('previous Sunday');
		$regi_date = $datetime_this_sunday->format("Y-m-d H:i:s");
		
		
		// --------------------------------------------------
		//   削除対象の登録日が今週の日曜日より古い場合は、処理をしない
		// --------------------------------------------------
		
		$datetime_regi_date = new DateTime($arr['regi_date']);
		if ($datetime_this_sunday > $datetime_regi_date) return false;
		
		//var_dump($datetime_this_sunday, $datetime_regi_date);
		//exit();
		
		//$regi_date = '2015';
		//var_dump($regi_date);
		
		
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------
		
		$query = DB::select('*')->from('present_users');
		$query->where('regi_date', '=', $regi_date);
		$query->where('user_no', '=', $arr['user_no']);
		if (isset($arr['profile_no'])) $query->where('profile_no', '=', $arr['profile_no']);
		$result_arr = $query->execute()->current();
		
		
		// --------------------------------------------------
		//   データが存在する場合は更新
		// --------------------------------------------------
		
		if (isset($result_arr))
		{
			
			$type_1 = $arr['type_1'];
			
			if ($arr['type_2'] == 'plus')
			{
				$save_arr[$type_1] = $result_arr[$type_1] + $arr['point'];
				$save_arr['total'] = $result_arr['total'] + $arr['point'];
			}
			else
			{
				$save_arr[$type_1] = $result_arr[$type_1] - $arr['point'];
				$save_arr['total'] = $result_arr['total'] - $arr['point'];
			}
			
			$this->update_present_users($result_arr['present_no'], $save_arr);
			
		}
		
		// --------------------------------------------------
		//   データが存在しない場合は挿入
		// --------------------------------------------------
		
		else
		{
			
			$type_1 = $arr['type_1'];
			
			$save_arr['regi_date'] = $regi_date;
			$save_arr['renewal_date'] = $regi_date;
			$save_arr['user_no'] = $arr['user_no'];
			if (isset($arr['profile_no'])) $save_arr['profile_no'] = $arr['profile_no'];
			$save_arr[$type_1] = $arr['point'];
			$save_arr['total'] = $arr['point'];
			
			$this->insert_present_users($save_arr);
			
		}
		
		
		//var_dump($result_arr, $save_arr);
		
		return true;
		
	}
	
	
}