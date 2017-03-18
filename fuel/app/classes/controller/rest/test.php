<?php

class Controller_Rest_Test extends Controller_Rest_Base
{
	
	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}
	
	
	
	/**
	* ゲーム名検索
	*
	* @return string HTMLコード
	*/
	public function post_test()
	{
		return $this->response(array('success' => true));
		
	}
	
	
}