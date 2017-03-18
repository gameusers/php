<?php

class Original_Rule_Advertisement
{
	
	/**
	* 広告Noのチェック
	*
	* @param integer $no 広告No
	* @return boolean 
	*/
	public static function _validation_check_advertisement_no($no) {
		
		if ($no === null) return true;
		if (preg_match('/^[1-9]\d*$/', $no) !== 1) return false;
		
		
		$temp_arr = array(
			'advertisement_no' => $no,
			'limit' => 1,
			'page' => 1
		);
		
		// 広告データ取得
		$model_advertisement = new Model_Advertisement();
		$temp_arr = $model_advertisement->get_advertisement($temp_arr);
		$result_arr = $temp_arr['data_arr'];
		
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
	
}