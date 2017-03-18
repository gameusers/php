<?php

class Original_Rule_Wiki
{
	
	/**
	* Wiki IDのチェック
	*
	* @param integer $id Wiki ID
	* @return boolean 
	*/
	/*
	public static function _validation_check_wiki_id($id) {
		
		$temp_arr = array(
			'wiki_id' => $id,
			'limit' => 1,
			'page' => 1
		);
		
		// BBSスレッド取得
		$model_wiki = new Model_Wiki();
		$result_arr = $model_wiki->get_wiki($temp_arr);
		
		
		//$test = true;
		
		if (isset($test))
		{
			\Debug::$js_toggle_open = true;
			
			echo '$no';
			\Debug::dump($no);
			
			echo '$result_arr';
			\Debug::dump($result_arr);
		}
		
		return (isset($result_arr[0]['advertisement_no'])) ? true : false;
		
	}
	*/
}