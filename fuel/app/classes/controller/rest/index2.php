<?php

class Controller_Rest_Index2 extends Controller_Rest_Base
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

	private $original_code_bbs = null;
	private $original_wiki_set = null;




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

		$this->original_code_bbs = new Original\Code\Bbs();


		// 管理者の場合、Wikiの処理を可能にする
		if (\Auth::member(100)) $this->original_wiki_set = new Original\Wiki\Set();

	}




	/**
	* ゲームコミュニティ　交流BBS一覧　検索
	*
	* @return string HTMLコード
	*/
	public function post_search_gc_bbs_list()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['keyword'] = 'アサシン';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$this->original_code_bbs->set_page(Input::post('page'));
			$this->original_code_bbs->set_keyword(Input::post('keyword'));
			$arr['code'] = $this->original_code_bbs->get_code_bbs_list_gc();

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



	/**
	* Wiki作成
	*
	* @return string HTMLコード
	*/
	/*
	public function post_create_wiki()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['game_no'] = 48;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   処理
			// --------------------------------------------------

			$this->original_wiki_set->set_game_no(Input::post('game_no'));
			$this->original_wiki_set->set_game_id();
			$arr['result'] =  $this->original_wiki_set->copy_wiki();

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
	*/

	

}
