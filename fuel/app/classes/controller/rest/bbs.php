<?php

class Controller_Rest_Bbs extends Controller_Rest_Base
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




	/**
	* 事前処理
	*/
	public function before()
	{
		// \Debug::dump($_POST);
		// exit();
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

		$this->model_bbs = new \Model_Bbs();
		$this->model_bbs->agent_type = $this->agent_type;
		$this->model_bbs->user_no = $this->user_no;
		$this->model_bbs->language = $this->language;
		$this->model_bbs->uri_base = $this->uri_base;
		$this->model_bbs->uri_current = $this->uri_current;


	}



	/**
	* コンストラクター
	*/
	/*
	public function __construct($arr)
	{

		// ------------------------------
		//   インスタンス作成
		// ------------------------------

		$temp_arr = array(
			'app_mode' => $this->app_mode,
			'agent_type' => $this->agent_type,
			'host' => $this->host,
			'user_agent' => $this->user_agent,
			'user_no' => $this->user_no,
			'language' => $this->language,
			'uri_base' => $this->uri_base,
			'uri_current' => $this->uri_current
		);

		$this->original_code_bbs = new Original\Code\Bbs($temp_arr);


		$this->model_user = new \Model_User();
		$this->model_user->agent_type = $this->agent_type;
		$this->model_user->user_no = $this->user_no;
		$this->model_user->language = $this->language;
		$this->model_user->uri_base = $this->uri_base;
		$this->model_user->uri_current = $this->uri_current;

		$this->model_co = new \Model_Co();
		$this->model_co->agent_type = $this->agent_type;
		$this->model_co->user_no = $this->user_no;
		$this->model_co->language = $this->language;
		$this->model_co->uri_base = $this->uri_base;
		$this->model_co->uri_current = $this->uri_current;

		$this->model_bbs = new \Model_Bbs();
		$this->model_bbs->agent_type = $this->agent_type;
		$this->model_bbs->user_no = $this->user_no;
		$this->model_bbs->language = $this->language;
		$this->model_bbs->uri_base = $this->uri_base;
		$this->model_bbs->uri_current = $this->uri_current;

		$this->original_func_co = new \Original\Func\Co();
		$this->original_func_co->app_mode = $this->app_mode;
		$this->original_func_co->agent_type = $this->agent_type;
		$this->original_func_co->user_no = $this->user_no;
		$this->original_func_co->language = $this->language;
		$this->original_func_co->uri_base = $this->uri_base;
		$this->original_func_co->uri_current = $this->uri_current;

		$this->original_common_date = new \Original\Common\Date();


	}
	*/



	/**
	* スレッド一覧読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_thread_list()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['type'] = 'gc';
			//$_POST['type'] = 'uc';
			$_POST['no'] = 1;
			$_POST['page'] = 3;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$this->original_code_bbs->set_type(Input::post('type'));
			$this->original_code_bbs->set_page(Input::post('page'));

			if (Input::post('type') == 'gc')
			{
				$this->original_code_bbs->set_game_no(Input::post('no'));
				$arr['code'] = $this->original_code_bbs->get_code_thread_list_gc();
			}
			else
			{
				$this->original_code_bbs->set_community_no(Input::post('no'));
				$arr['code'] = $this->original_code_bbs->get_code_thread_list_uc();
			}

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
	* BBS読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['type'] = 'gc';
			//$_POST['type'] = 'gc_appoint';
			$_POST['type'] = 'uc';
			//$_POST['type'] = 'uc_appoint';
			$_POST['no'] = 1;
			$_POST['page'] = 1;

		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc')
			{
				$this->original_code_bbs->set_game_no(Input::post('no'));
				$this->original_code_bbs->set_page(Input::post('page'));
				$arr = $this->original_code_bbs->get_code_bbs_gc();
			}
			else if (Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_gc(Input::post('no'));
				$arr = $this->original_code_bbs->get_code_bbs_appoint_gc();
			}
			else if (Input::post('type') == 'uc')
			{
				$this->original_code_bbs->set_community_no(Input::post('no'));
				$this->original_code_bbs->set_page(Input::post('page'));
				$arr = $this->original_code_bbs->get_code_bbs_uc();
			}
			else if (Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_uc(Input::post('no'));
				$arr = $this->original_code_bbs->get_code_bbs_appoint_uc();
			}

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
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}



	/**
	* コメント　読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_comment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['type'] = 'gc';
			//$_POST['type'] = 'gc_appoint';
			$_POST['type'] = 'uc';
			//$_POST['type'] = 'uc_appoint';
			$_POST['bbs_thread_no'] = 1;
			$_POST['page'] = 1;

		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_gc(Input::post('bbs_thread_no'));
				$this->original_code_bbs->set_page(Input::post('page'));
				if (Input::post('individual')) $this->original_code_bbs->set_individual(1);

				$arr = $this->original_code_bbs->get_code_bbs_comment_gc();
			}
			else
			{
				$this->original_code_bbs->set_bbs_thread_no_uc(Input::post('bbs_thread_no'));
				$this->original_code_bbs->set_page(Input::post('page'));
				if (Input::post('individual')) $this->original_code_bbs->set_individual(1);

				$arr = $this->original_code_bbs->get_code_bbs_comment_uc();
			}

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
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}




	/**
	* 返信　読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_reply()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['type'] = 'gc';
			//$_POST['type'] = 'gc_appoint';
			//$_POST['type'] = 'uc';
			//$_POST['type'] = 'uc_appoint';
			$_POST['bbs_comment_no'] = 21;
			$_POST['page'] = 1;

		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_gc(Input::post('bbs_comment_no'));
				$this->original_code_bbs->set_page(Input::post('page'));

				$arr['code'] = $this->original_code_bbs->get_code_bbs_reply_gc();
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_uc(Input::post('bbs_comment_no'));
				$this->original_code_bbs->set_page(Input::post('page'));

				$arr['code'] = $this->original_code_bbs->get_code_bbs_reply_uc();
			}

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
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}




	/**
	* BBSスレッド編集フォーム表示
	*
	*/
	public function post_show_edit_bbs_thread_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['type'] = 'gc';
			//$_POST['type'] = 'uc';
			$_POST['bbs_thread_no'] = 1;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_gc(Input::post('bbs_thread_no'));

				$arr['code'] = $this->original_code_bbs->get_code_edit_bbs_form_gc();
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_uc(Input::post('bbs_thread_no'));

				$arr['code'] = $this->original_code_bbs->get_code_edit_bbs_form_uc();
			}

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
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}



	/**
	* BBSコメント編集フォーム表示
	*
	*/
	public function post_show_edit_bbs_comment_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['type'] = 'gc';
			//$_POST['type'] = 'uc';
			$_POST['bbs_comment_no'] = 18;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_gc(Input::post('bbs_comment_no'));

				$arr['code'] = $this->original_code_bbs->get_code_edit_bbs_comment_form_gc();
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_uc(Input::post('bbs_comment_no'));

				$arr['code'] = $this->original_code_bbs->get_code_edit_bbs_comment_form_uc();
			}

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
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}



	/**
	* BBS返信編集フォーム表示
	*
	*/
	public function post_show_edit_bbs_reply_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['type'] = 'gc';
			//$_POST['type'] = 'uc';
			$_POST['bbs_reply_no'] = 31;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_reply_no_gc(Input::post('bbs_reply_no'));

				$arr['code'] = $this->original_code_bbs->get_code_edit_bbs_reply_form_gc();
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_reply_no_uc(Input::post('bbs_reply_no'));

				$arr['code'] = $this->original_code_bbs->get_code_edit_bbs_reply_form_uc();
			}

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
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}




	/**
	* BBS返信投稿フォーム表示
	*
	*/
	public function post_show_write_bbs_reply_form()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['type'] = 'uc';
			//$_POST['type'] = 'uc';
			$_POST['bbs_comment_no'] = 78;
			$_POST['bbs_reply_no'] = 57;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_gc(Input::post('bbs_comment_no'));
				if (Input::post('bbs_reply_no')) $this->original_code_bbs->set_bbs_reply_no_gc(Input::post('bbs_reply_no'));

				$arr['code'] = $this->original_code_bbs->get_code_write_bbs_reply_form_gc();
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_uc(Input::post('bbs_comment_no'));
				if (Input::post('bbs_reply_no')) $this->original_code_bbs->set_bbs_reply_no_uc(Input::post('bbs_reply_no'));

				$arr['code'] = $this->original_code_bbs->get_code_write_bbs_reply_form_uc();
			}

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
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}







	/**
	* BBSスレッド　作成・更新
	*
	* @return string HTMLコード
	*/
	public function post_save_bbs_thread()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$original_common_date = new Original\Common\Date();
			$datetime_now = $original_common_date->sql_format();

			//runkit_constant_remove('USER_NO');

			$_POST['type'] = 'uc';
			//$_POST['type'] = 'uc';
			$_POST['no'] = 1;
			$_POST['bbs_thread_no'] = 77;
			$_POST['handle_name'] = 'ハンドルネーム';
			$_POST['title'] = 'テスト';
			$_POST['comment'] = $datetime_now;
			//$_POST['anonymity'] = true;
			//$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
			//$_POST['image_1_delete'] = 1;
			//$_POST['movie_1_delete'] = 1;
		}



		$arr = array();

		try
		{

			//var_dump(Input::post('title'));

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_game_no(Input::post('no'));
				if (Input::post('bbs_thread_no')) $this->original_code_bbs->set_bbs_thread_no_gc(Input::post('bbs_thread_no'));

				if ( ! USER_NO and Input::post('handle_name')) $this->original_code_bbs->set_handle_name(Input::post('handle_name'));
				$this->original_code_bbs->set_title(Input::post('title'));
				$this->original_code_bbs->set_comment(Input::post('comment'));
				if (USER_NO and Input::post('anonymity')) $this->original_code_bbs->set_anonymity_on(Input::post('anonymity'));
				if (Input::post('movie_url')) $this->original_code_bbs->set_movie_url(Input::post('movie_url'));
				if (Input::post('image_1_delete')) $this->original_code_bbs->set_image_1_delete(Input::post('image_1_delete'));

				$result_arr = $this->original_code_bbs->save_bbs_thread_gc();
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_community_no(Input::post('no'));
				if (Input::post('bbs_thread_no')) $this->original_code_bbs->set_bbs_thread_no_uc(Input::post('bbs_thread_no'));

				if ( ! USER_NO and Input::post('handle_name')) $this->original_code_bbs->set_handle_name(Input::post('handle_name'));
				$this->original_code_bbs->set_title(Input::post('title'));
				$this->original_code_bbs->set_comment(Input::post('comment'));
				if (USER_NO and Input::post('anonymity')) $this->original_code_bbs->set_anonymity_on(Input::post('anonymity'));
				if (Input::post('movie_url')) $this->original_code_bbs->set_movie_url(Input::post('movie_url'));
				if (Input::post('image_1_delete')) $this->original_code_bbs->set_image_1_delete(Input::post('image_1_delete'));

				$result_arr = $this->original_code_bbs->save_bbs_thread_uc();
			}

			if (isset($result_arr))
			{
				// $arr['code_bbs_thread_list'] = $result_arr['code_bbs_thread_list'];
				// $arr['code_bbs'] = $result_arr['code_bbs'];
				$arr = $result_arr;
				// \Debug::dump($result_arr);
			}


			// $arr['code_bbs_thread_list'] = 'aaa';
			// $arr['code_bbs'] = 'bbb';

		}
		catch (Exception $e)
		{

			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();

		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			//if (isset($arr['code_bbs'])) echo $arr['code_bbs'];
			\Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}






















	/**
	* BBSコメント書き込み・更新
	*
	* @return string HTMLコード
	*/
	public function post_save_bbs_comment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$original_common_date = new Original\Common\Date();
			$datetime_now = $original_common_date->sql_format();

			//$_POST['type'] = 'gc';
			$_POST['type'] = 'gc';
			$_POST['bbs_thread_no'] = 153;
			//$_POST['bbs_comment_no'] = 50;
			$_POST['handle_name'] = 'ハンドルネーム';
			$_POST['comment'] = 'コメント' . $datetime_now;
			//$_POST['anonymity'] = true;
			//$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';
			//$_POST['image_delete_1'] = 1;
		}



		$arr = array();

		try
		{

			//var_dump(Input::post('title'));

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_gc(Input::post('bbs_thread_no'));
				if (Input::post('bbs_comment_no')) $this->original_code_bbs->set_bbs_comment_no_gc(Input::post('bbs_comment_no'));

				if ( ! USER_NO and Input::post('handle_name')) $this->original_code_bbs->set_handle_name(Input::post('handle_name'));
				$this->original_code_bbs->set_comment(Input::post('comment'));
				if (USER_NO and Input::post('anonymity')) $this->original_code_bbs->set_anonymity_on(Input::post('anonymity'));
				if (Input::post('movie_url')) $this->original_code_bbs->set_movie_url(Input::post('movie_url'));
				if (Input::post('image_1_delete')) $this->original_code_bbs->set_image_1_delete(Input::post('image_1_delete'));

				$arr = $this->original_code_bbs->save_bbs_comment_gc();


				// スレッド一覧
				$db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_gc(array('page' => 1, 'limit' => 1, 'bbs_thread_no' => $this->original_code_bbs->get_bbs_thread_no()));

				//\Debug::dump($db_bbs_thread_arr);

				$this->original_code_bbs->set_type('gc');
				$this->original_code_bbs->set_page(1);

				$this->original_code_bbs->set_game_no($db_bbs_thread_arr[0]['game_no']);
				$arr['code_bbs_thread_list'] = $this->original_code_bbs->get_code_thread_list_gc();

			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_uc(Input::post('bbs_thread_no'));
				if (Input::post('bbs_comment_no')) $this->original_code_bbs->set_bbs_comment_no_uc(Input::post('bbs_comment_no'));

				if ( ! USER_NO and Input::post('handle_name')) $this->original_code_bbs->set_handle_name(Input::post('handle_name'));
				$this->original_code_bbs->set_comment(Input::post('comment'));
				if (USER_NO and Input::post('anonymity')) $this->original_code_bbs->set_anonymity_on(Input::post('anonymity'));
				if (Input::post('movie_url')) $this->original_code_bbs->set_movie_url(Input::post('movie_url'));
				if (Input::post('image_1_delete')) $this->original_code_bbs->set_image_1_delete(Input::post('image_1_delete'));

				$arr = $this->original_code_bbs->save_bbs_comment_uc();


				// スレッド一覧
				$db_bbs_thread_arr = $this->model_bbs->get_bbs_thread_uc(array('page' => 1, 'limit' => 1, 'bbs_thread_no' => $this->original_code_bbs->get_bbs_thread_no()));

				//\Debug::dump($db_bbs_thread_arr);

				$this->original_code_bbs->set_type('uc');
				$this->original_code_bbs->set_page(1);

				$this->original_code_bbs->set_community_no($db_bbs_thread_arr[0]['community_no']);
				$arr['code_bbs_thread_list'] = $this->original_code_bbs->get_code_thread_list_uc();

			}


			// $this->original_code_bbs->set_type(Input::post('type'));
			// $this->original_code_bbs->set_page(Input::post('page'));
//
			// if (Input::post('type') == 'gc')
			// {
				// $this->original_code_bbs->set_game_no(Input::post('no'));
				// $arr['code'] = $this->original_code_bbs->get_code_thread_list_gc();
			// }
			// else
			// {
				// $this->original_code_bbs->set_community_no(Input::post('no'));
				// $arr['code'] = $this->original_code_bbs->get_code_thread_list_uc();
			// }

		}
		catch (Exception $e)
		{

			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();

		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}
















	/**
	* BBS返信書き込み・更新
	*
	* @return string HTMLコード
	*/
	public function post_save_bbs_reply()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['type'] = 'gc';
			$_POST['type'] = 'uc';
			$_POST['bbs_comment_no'] = 29;
			//$_POST['bbs_reply_no'] = 30;
			$_POST['handle_name'] = 'AAA';
			$_POST['comment'] = 'BBB';
			//$_POST['anonymity'] = true;
			//$_POST['movie_url'] = 'https://www.youtube.com/watch?v=vI21ULEAWOM';

		}



		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_gc(Input::post('bbs_comment_no'));
				if (Input::post('bbs_reply_no')) $this->original_code_bbs->set_bbs_reply_no_gc(Input::post('bbs_reply_no'));

				if ( ! USER_NO and Input::post('handle_name')) $this->original_code_bbs->set_handle_name(Input::post('handle_name'));
				$this->original_code_bbs->set_comment(Input::post('comment'));
				if (USER_NO and Input::post('anonymity')) $this->original_code_bbs->set_anonymity_on(Input::post('anonymity'));
				if (Input::post('movie_url')) $this->original_code_bbs->set_movie_url(Input::post('movie_url'));
				if (Input::post('image_1_delete')) $this->original_code_bbs->set_image_1_delete(Input::post('image_1_delete'));

				$arr = $this->original_code_bbs->save_bbs_reply_gc();


				// スレッド一覧
				$db_bbs_reply_arr = $this->model_bbs->get_bbs_reply_gc(array('page' => 1, 'limit' => 1, 'bbs_reply_no' => $this->original_code_bbs->get_bbs_reply_no()));

				//\Debug::dump($db_bbs_thread_arr);

				$this->original_code_bbs->set_type('gc');
				$this->original_code_bbs->set_page(1);

				$this->original_code_bbs->set_game_no($db_bbs_reply_arr[0]['game_no']);
				$arr['code_bbs_thread_list'] = $this->original_code_bbs->get_code_thread_list_gc();

			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{

				$this->original_code_bbs->set_bbs_comment_no_uc(Input::post('bbs_comment_no'));
				if (Input::post('bbs_reply_no')) $this->original_code_bbs->set_bbs_reply_no_uc(Input::post('bbs_reply_no'));

				if ( ! USER_NO and Input::post('handle_name')) $this->original_code_bbs->set_handle_name(Input::post('handle_name'));
				$this->original_code_bbs->set_comment(Input::post('comment'));
				if (USER_NO and Input::post('anonymity')) $this->original_code_bbs->set_anonymity_on(Input::post('anonymity'));
				if (Input::post('movie_url')) $this->original_code_bbs->set_movie_url(Input::post('movie_url'));
				if (Input::post('image_1_delete')) $this->original_code_bbs->set_image_1_delete(Input::post('image_1_delete'));

				$arr = $this->original_code_bbs->save_bbs_reply_uc();


				// スレッド一覧
				$db_bbs_reply_arr = $this->model_bbs->get_bbs_reply_uc(array('page' => 1, 'limit' => 1, 'bbs_reply_no' => $this->original_code_bbs->get_bbs_reply_no()));

				//\Debug::dump($db_bbs_thread_arr);

				$this->original_code_bbs->set_type('uc');
				$this->original_code_bbs->set_page(1);

				$this->original_code_bbs->set_community_no($db_bbs_reply_arr[0]['community_no']);
				$arr['code_bbs_thread_list'] = $this->original_code_bbs->get_code_thread_list_uc();
			}

		}
		catch (Exception $e)
		{

			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();

		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}









	/**
	* BBSスレッド　削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_bbs_thread()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['type'] = 'gc';
			//$_POST['type'] = 'uc';
			$_POST['bbs_thread_no'] = 66;
		}



		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_gc(Input::post('bbs_thread_no'));

				$result_arr = $this->original_code_bbs->delete_bbs_thread_gc();

				$arr['code_bbs_thread_list'] = $result_arr['code_bbs_thread_list'];
				$arr['code_bbs'] = $result_arr['code_bbs'];
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_thread_no_uc(Input::post('bbs_thread_no'));

				$result_arr = $this->original_code_bbs->delete_bbs_thread_uc();

				$arr['code_bbs_thread_list'] = $result_arr['code_bbs_thread_list'];
				$arr['code_bbs'] = $result_arr['code_bbs'];
			}

		}
		catch (Exception $e)
		{

			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();

		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}




	/**
	* BBSコメント　削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_bbs_comment()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['type'] = 'gc';
			$_POST['type'] = 'uc';
			$_POST['bbs_comment_no'] = 82;
		}



		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_gc(Input::post('bbs_comment_no'));
				$result_arr = $this->original_code_bbs->delete_bbs_comment_gc();
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_comment_no_uc(Input::post('bbs_comment_no'));
				$result_arr = $this->original_code_bbs->delete_bbs_comment_uc();
			}

			$arr['code_bbs_thread_list'] = $result_arr['code_bbs_thread_list'];
			$arr['code_bbs'] = $result_arr['code_bbs'];

		}
		catch (Exception $e)
		{

			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();

		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			//if (isset($arr['code'])) echo $arr['code'];
			echo $arr['code_bbs_thread_list'];
			echo $arr['code_bbs'];
		}
		else
		{
			return $this->response($arr);
		}

	}




	/**
	* BBS返信　削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_bbs_reply()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['type'] = 'gc';
			//$_POST['type'] = 'uc';
			$_POST['bbs_reply_no'] = 18;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			if (Input::post('type') == 'gc' or Input::post('type') == 'gc_appoint')
			{
				$this->original_code_bbs->set_bbs_reply_no_gc(Input::post('bbs_reply_no'));
				$result_arr = $this->original_code_bbs->delete_bbs_reply_gc();
			}
			else if (Input::post('type') == 'uc' or Input::post('type') == 'uc_appoint')
			{
				$this->original_code_bbs->set_bbs_reply_no_uc(Input::post('bbs_reply_no'));
				$result_arr = $this->original_code_bbs->delete_bbs_reply_uc();
			}

			$arr['code_bbs_thread_list'] = $result_arr['code_bbs_thread_list'];
			$arr['code_bbs'] = $result_arr['code_bbs'];

		}
		catch (Exception $e)
		{

			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();

		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			//if (isset($arr['code'])) echo $arr['code'];
			echo $arr['code_bbs_thread_list'];
			echo $arr['code_bbs'];
		}
		else
		{
			return $this->response($arr);
		}

	}



}
