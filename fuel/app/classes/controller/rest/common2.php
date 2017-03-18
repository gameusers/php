<?php

class Controller_Rest_Common2 extends Controller_Rest_Base
{
	
	
	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------
	
	// ------------------------------
	//   インスタンス
	// ------------------------------
	
	private $model_user = null;
	private $model_co = null;
	private $model_bbs = null;
	private $original_func_co = null;
	
	private $original_code_common2 = null;
	
	
	
	
	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
		
		
		// --------------------------------------------------
		//   CSRFチェック
		// --------------------------------------------------
		
		$original_validation_common = new Original\Validation\Common();
		$original_validation_common->csrf(Input::post('fuel_csrf_token'));
		
		
		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------
		
		$this->original_code_common2 = new Original\Code\Common2();
		
		
	}
	
	
	
	
	/**
	* スライドゲームリスト
	*
	* @return string HTMLコード
	*/
	public function post_slide_game_list()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			$_POST['type'] = 'register';
		}
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------
			
			$this->original_code_common2->set_code_slide_game_list_type(Input::post('type'));
			$result_arr = $this->original_code_common2->code_slide_game_list(false);
			$arr['code'] = $result_arr['code'];
			$arr['count'] = $result_arr['count'];
			
		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}
		
		
		// --------------------------------------------------
		//   出力
		// --------------------------------------------------
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
}