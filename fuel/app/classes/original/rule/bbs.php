<?php

class Original_Rule_Bbs
{
	
	/**
	* ゲームコミュニティ　BBS スレッドの存在チェック
	*
	* @param integer $no BBSスレッドNo
	* @return boolean 
	*/
	public static function _validation_check_gc_bbs_thread_no($no) {
		
		if (preg_match('/^[1-9]\d*$/', $no) !== 1) return false;
		
		
		$get_bbs_thread_list_arr = array(
			'type' => 'gc',
			'bbs_thread_no' => $no,
			'page' => 1,
			'limit' => 1
		);
		
		// BBSスレッド取得
		$model_bbs = new Model_Bbs();
		$result_arr = $model_bbs->get_bbs_thread_list($get_bbs_thread_list_arr);
		
		
		//$test = true;
		
		if (isset($test))
		{
			\Debug::$js_toggle_open = true;
			
			echo '$no';
			\Debug::dump($no);
			
			echo '$result_arr';
			\Debug::dump($result_arr);
		}
		
		if (isset($result_arr[0]['game_no'])) return true;
		
		
		return false;
		
	}
	
	
	
	/**
	* ユーザーコミュニティ　BBS スレッドの存在チェック
	*
	* @param integer $no BBSスレッドNo
	* @return boolean 
	*/
	public static function _validation_check_uc_bbs_thread_no($no) {
		
		if (preg_match('/^[1-9]\d*$/', $no) !== 1) return false;
		
		
		$get_bbs_thread_list_arr = array(
			'type' => 'uc',
			'bbs_thread_no' => $no,
			'page' => 1,
			'limit' => 1
		);
		
		// BBSスレッド取得
		$model_bbs = new Model_Bbs();
		$result_arr = $model_bbs->get_bbs_thread_list($get_bbs_thread_list_arr);
		
		
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
			$model_co = new Model_Co();
			$result_arr = $model_co->get_community($result_arr[0]['community_no']);
			
			if ($result_arr) return true;
		}
		
		return false;
		
	}
	
	
	/**
	* ゲームコミュニティ　BBS コメントの存在チェック
	*
	* @param integer $no BBSスレッドNo
	* @return boolean 
	*/
	public static function _validation_check_gc_bbs_comment_no($no) {
		
		if (preg_match('/^[1-9]\d*$/', $no) !== 1) return false;
		
		
		// BBSコメント取得
		$get_bbs_comment_list_arr = array(
			'type' => 'gc',
			'bbs_comment_no' => $no,
			'page' => 1,
			'limit' => 1
		);
		
		$model_bbs = new Model_Bbs();
		$result_comment_arr = $model_bbs->get_bbs_comment_list($get_bbs_comment_list_arr);
		
		
		// BBSスレッド取得
		if (isset($result_comment_arr[0]['bbs_thread_no']))
		{
			$get_bbs_thread_list_arr = array(
				'type' => 'gc',
				'bbs_thread_no' => $result_comment_arr[0]['bbs_thread_no'],
				'page' => 1,
				'limit' => 1
			);
			
			$result_thread_arr = $model_bbs->get_bbs_thread_list($get_bbs_thread_list_arr);
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
	
	
	
	/**
	* ユーザーコミュニティ　BBS コメントの存在チェック
	*
	* @param integer $no BBSスレッドNo
	* @return boolean 
	*/
	public static function _validation_check_uc_bbs_comment_no($no) {
		
		if (preg_match('/^[1-9]\d*$/', $no) !== 1) return false;
		
		
		// BBSコメント取得
		$get_bbs_comment_list_arr = array(
			'type' => 'uc',
			'bbs_comment_no' => $no,
			'page' => 1,
			'limit' => 1
		);
		
		$model_bbs = new Model_Bbs();
		$result_comment_arr = $model_bbs->get_bbs_comment_list($get_bbs_comment_list_arr);
		
		
		// BBSスレッド取得
		if (isset($result_comment_arr[0]['bbs_thread_no']))
		{
			$get_bbs_thread_list_arr = array(
				'type' => 'uc',
				'bbs_thread_no' => $result_comment_arr[0]['bbs_thread_no'],
				'page' => 1,
				'limit' => 1
			);
			
			$result_thread_arr = $model_bbs->get_bbs_thread_list($get_bbs_thread_list_arr);
		}
		
		
		// コミュニティ取得
		if (isset($result_thread_arr[0]['community_no']))
		{
			$model_co = new Model_Co();
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
		
		// コメントとスレッド、コミュニティが存在する場合にtrue
		return (isset($result_comment_arr[0]['bbs_comment_no'], $result_thread_arr[0]['bbs_thread_no'], $result_community_arr['community_no'])) ? true : false;
		
	}
	
	
	
	
	
	/**
	* BBS 返信の存在チェック
	*
	* @param integer $no BBSスレッドNo
	* @return boolean 
	*/
	public static function _validation_check_uc_bbs_reply_no($no) {
		
		if (preg_match('/^[1-9]\d*$/', $no) !== 1) return false;
		
		$model_co = new Model_Co();
		
		// BBS返信取得
		$result_bbs_reply = $model_co->get_bbs_reply($no);
		
		// BBSコメント取得
		if (isset($result_bbs_reply['bbs_comment_no']))
		{
			$result_bbs_comment = $model_co->get_bbs_comment($result_bbs_reply['bbs_comment_no']);
		}
		
		// BBSスレッド取得
		if (isset($result_bbs_comment['bbs_thread_no']))
		{
			$result_bbs_thread = $model_co->get_bbs_thread($result_bbs_comment['bbs_thread_no']);
		}
		
		// コミュニティ取得
		if (isset($result_bbs_thread['community_no']))
		{
			$result_community = $model_co->get_community($result_bbs_thread['community_no']);
		}
		
		//var_dump($result_bbs_reply, $result_bbs_comment, $result_bbs_thread, $result_community);
		
		return (isset($result_bbs_reply, $result_bbs_comment, $result_bbs_thread, $result_community)) ? true : false;
		
	}
	
}