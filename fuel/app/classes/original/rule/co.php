<?php

class Original_Rule_Co
{
	
	/**
	* コミュニティの存在チェック
	*
	* @param integer $community_no コミュニティNo
	* @return boolean 
	*/
	public static function _validation_check_community_no($community_no) {
		
		if (preg_match('/^[1-9]\d*$/', $community_no) !== 1) return false;
		
		// コミュニティデータ取得
		$model_co = new Model_Co();
		$result = $model_co->get_community($community_no, null);
		
		return ($result) ? true : false;
		
	}

	
	
	/**
	* コミュニティIDの重複チェック
	*
	* @param string $str コミュニティID
	* @return boolean 
	*/
	public static function _validation_community_id_duplication($community_id) {
		
		$community_no = Input::post('community_no');
		
		// 確認
		$model_co = new Model_Co();
		$result = $model_co->check_community_id_duplication($community_no, $community_id);
		
		return ($result) ? false : true;
		
	}
	
	
	
	/**
	* 告知の存在チェック
	*
	* @param integer $announcement_no 告知No
	* @return boolean 
	*/
	public static function _validation_check_announcement_no($announcement_no) {
		
		// 告知データ取得
		$model_co = new Model_Co();
		$result_announcement = $model_co->get_announcement($announcement_no);
		
		if ($result_announcement)
		{
			// コミュニティデータ取得
			$model_co = new Model_Co();
			$result_community = $model_co->get_community($result_announcement['community_no'], null);
			
			if ($result_community) return true;
		}
		
		return false;
		
	}
	
	
	
		
	
	/**
	* BBS スレッドの存在チェック
	*
	* @param integer $no BBSスレッドNo
	* @return boolean 
	*/
	public static function _validation_check_bbs_thread_no($no) {
		
		if (preg_match('/^[1-9]\d*$/', $no) !== 1) return false;
		
		// BBSスレッド取得
		$model_co = new Model_Co();
		$result_bbs_thread = $model_co->get_bbs_thread($no);
		
		// コミュニティ取得
		if (isset($result_bbs_thread['community_no']))
		{
			$result_community = $model_co->get_community($result_bbs_thread['community_no']);
			
			if ($result_community) return true;
		}
		
		return false;
		
	}
	
	
	/**
	* BBS コメントの存在チェック
	*
	* @param integer $no BBSスレッドNo
	* @return boolean 
	*/
	public static function _validation_check_bbs_comment_no($no) {
		
		if (preg_match('/^[1-9]\d*$/', $no) !== 1) return false;
		
		// BBSコメント取得
		$model_co = new Model_Co();
		
		$result_bbs_comment = $model_co->get_bbs_comment($no);
		
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
		
		return (isset($result_bbs_comment, $result_bbs_thread, $result_community)) ? true : false;
		
	}
	
	
	/**
	* BBS 返信の存在チェック
	*
	* @param integer $no BBSスレッドNo
	* @return boolean 
	*/
	public static function _validation_check_bbs_reply_no($no) {
		
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