<?php

class Original_Rule_Common
{
	
	
	/**
	* ゲーム存在チェック
	*
	* @param string $str 変換前配列
	* @return boolean 
	*/
	public static function _validation_check_game_existence($str)
	{
		
		if ($str)
		{
			$game_no_arr = explode(',', $str);
			$game_no_arr_count = count($game_no_arr);
			
			// 11個以上登録しようとした場合エラー
			if ($game_no_arr_count > Config::get('limit_regist_game')) return false;
			
			// 数字じゃない場合エラー
			foreach ($game_no_arr as $key => $value)
			{
				if ( ! is_numeric($value)) return false;
			}
			
			// データベースから取得
			$model_game = new Model_Game();
			$result = $model_game->get_game_name('ja', $game_no_arr);
			
			//var_dump($game_no_arr_count, $result, count($result));
			
			// ゲームが存在していない場合エラー
			if ($game_no_arr_count != count($result)) return false;
			
		}
		
		return true;
		
	}
	
	
	/**
	* ゲームNoの存在チェック
	*
	* @param integer $community_no コミュニティNo　同じものがvalidation common.phpにある
	* @return boolean 
	*/
	public static function _validation_check_game_no($game_no) {
		
		if (preg_match('/^[1-9]\d*$/', $game_no) !== 1) return false;
		
		// コミュニティデータ取得
		$model_game = new Model_Game();
		$result = $model_game->get_game_data($game_no);
		
		return ($result) ? true : false;
		
	}
	
	
	
	/**
	* ハードウェアNoの存在チェック
	*
	* @param integer $hardware_no ハードウェアNo
	* @return boolean 
	*/
	public static function _validation_check_hardware_no($hardware_no) {
		
		if ( ! $hardware_no) return true;
		
		if (preg_match('/^[1-9]\d*$/', $hardware_no) !== 1) return false;
		
		// コミュニティデータ取得
		$model_game = new Model_Game();
		$result = $model_game->get_hardware($hardware_no);
		
		return ($result) ? true : false;
		
	}
	
	
	
	
	/**
	* 募集で入力したゲームIDの存在チェック　編集権限チェック
	*
	* @param integer $community_no コミュニティNo
	* @return boolean 
	*/
	public static function _validation_check_game_id($game_id_no) {
		//$game_id_no = 200;
		// コミュニティデータ取得
		$model_game = new Model_Game();
		$result_arr = $model_game->get_game_id_simple($game_id_no);
		
		if (isset($result_arr['user_no']))
		{
			$login_user_no = (Auth::check()) ? Auth::get_user_id() : null;
			if ($result_arr['user_no'] == $login_user_no) return true;
		}
		
		//var_dump($result_arr);
		
		return false;
		//return ($result) ? true : false;
		
	}
	
	
	
	
	/**
	* 動画URLチェック
	*
	* @param string $str 変換前配列　同じものがvalidation common.phpにある
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

}