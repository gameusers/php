<?php

class Original_Rule_Gc
{
	
	/**
	* 募集の存在チェック　IDで検索
	*
	* @param integer $community_no コミュニティNo
	* @return boolean 
	*/
	public static function _validation_check_recruitment_id($recruitment_id) {
		
		// コミュニティデータ取得
		$model_gc = new Model_Gc();
		$result = $model_gc->get_recruitment_appoint($recruitment_id);
		
		return ($result) ? true : false;
		
	}
	
	
	/**
	* 募集の存在＆編集権限チェック　IDで検索
	*
	* @param integer $community_no コミュニティNo
	* @return boolean 
	*/
	public static function _validation_check_recruitment_id_authority_edit($recruitment_id) {
		
		// コミュニティデータ取得
		$model_gc = new Model_Gc();
		$result_arr = $model_gc->get_recruitment_appoint($recruitment_id);
		
		//var_dump($result_arr);
		
		if ($result_arr)
		{
			$login_user_no = (Auth::check()) ? Auth::get_user_id() : null;
			
			$datetime_renewal = new DateTime($result_arr['renewal_date']);
			$datetime_past = new DateTime('-30 minutes');
			
			//var_dump($datetime_renewal, $datetime_past);
			
			$author_recruitment_arr = (Session::get('author_recruitment_arr')) ? Session::get('author_recruitment_arr') : array();
			
			if (isset($login_user_no) and $result_arr['user_no'] == $login_user_no)
			{
				return true;
			}
			else if ($datetime_renewal > $datetime_past and in_array($recruitment_id, $author_recruitment_arr))
			{
				return true;
			}
		}
		
		return false;
		
	}

	
	
	/**
	* 募集の返信存在チェック　IDで検索
	*
	* @param integer $community_no コミュニティNo
	* @return boolean 
	*/
	public static function _validation_check_recruitment_reply_id($recruitment_reply_id) {
		
		// コミュニティデータ取得
		$model_gc = new Model_Gc();
		$result = $model_gc->get_recruitment_reply_appoint($recruitment_reply_id);
		
		return ($result) ? true : false;
		
	}
	
	
	/**
	* 募集の返信存在＆編集権限チェック　IDで検索
	*
	* @param integer $community_no コミュニティNo
	* @return boolean 
	*/
	public static function _validation_check_recruitment_reply_id_authority_edit($recruitment_reply_id) {
		
		// コミュニティデータ取得
		$model_gc = new Model_Gc();
		$result_arr = $model_gc->get_recruitment_reply_appoint($recruitment_reply_id);
		
		if ($result_arr)
		{
			$login_user_no = (Auth::check()) ? Auth::get_user_id() : null;
			
			$datetime_renewal = new DateTime($result_arr['renewal_date']);
			$datetime_past = new DateTime('-30 minutes');
			
			//var_dump($datetime_renewal, $datetime_past);
			
			$author_reply_arr = (Session::get('author_reply_arr')) ? Session::get('author_reply_arr') : array();
			
			if (isset($login_user_no) and $result_arr['user_no'] == $login_user_no)
			{
				return true;
			}
			else if ($datetime_renewal > $datetime_past and in_array($recruitment_reply_id, $author_reply_arr))
			{
				return true;
			}
		}
		
		return false;
		
	}
	
	
}