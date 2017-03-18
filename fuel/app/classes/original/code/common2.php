<?php

namespace Original\Code;

class Common2
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $model_common = null;
	private $model_game = null;
	private $model_gc = null;
	private $original_validation_common = null;
	private $original_validation_fieldsetex = null;
	private $original_common_date = null;


	private $code_slide_game_list_type = null;
	private $code_slide_game_list_game_no_arr = null;


	// --------------------------------------------------
	//   コンストラクター
	// --------------------------------------------------

	public function __construct()
	{

		// ------------------------------
		//   インスタンス作成
		// ------------------------------

		// $this->model_common = new \Model_Common();
		// $this->model_common->agent_type = AGENT_TYPE;
		// $this->model_common->user_no = USER_NO;
		// $this->model_common->language = LANGUAGE;
		// $this->model_common->uri_base = URI_BASE;
		// $this->model_common->uri_current = URI_CURRENT;

		$this->model_game = new \Model_Game();
		$this->model_game->agent_type = AGENT_TYPE;
		$this->model_game->user_no = USER_NO;
		$this->model_game->language = LANGUAGE;
		$this->model_game->uri_base = URI_BASE;
		$this->model_game->uri_current = URI_CURRENT;

		// $this->model_gc = new \Model_Gc();
		// $this->model_gc->agent_type = AGENT_TYPE;
		// $this->model_gc->user_no = USER_NO;
		// $this->model_gc->language = LANGUAGE;
		// $this->model_gc->uri_base = URI_BASE;
		// $this->model_gc->uri_current = URI_CURRENT;

		$this->original_validation_common = new \Original\Validation\Common();
		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();

		$this->original_common_date = new \Original\Common\Date();



		// ------------------------------
		//   定数設定
		// ------------------------------

		if (AGENT_TYPE != 'smartphone')
		{
			define("INDEX_LIMIT_RECRUITMENT", (int) \Config::get('index_limit_recruitment'));
		}
		else
		{
			define("INDEX_LIMIT_RECRUITMENT", (int) \Config::get('index_limit_recruitment_sp'));
		}

	}



	/**
	* Setter / スライドゲームリスト　type
	*
	* @param array $argument
	*/
	public function set_code_slide_game_list_type($argument = null)
	{

		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(renew|register|access)$/');

		if ($val->run(array('type' => $argument)))
		{
			$this->code_slide_game_list_type = $val->validated('type');
		}
		else
		{
			$this->original_validation_common->throw_exception($val->error());
		}

	}

	public function get_code_slide_game_list_type()
	{
		return $this->code_slide_game_list_type;
	}




	/**
	* Setter / スライドゲームリスト　game_no_arr
	*
	* @param array $argument_arr
	*/
	public function set_code_slide_game_list_game_no_arr($argument_arr = null)
	{

		if ($argument_arr)
		{
			foreach ($argument_arr as $key => $value)
			{
				if (is_numeric($value) == false) $this->original_validation_common->throw_exception(array('Cookie Game No Arr Error'));
			}
			$this->code_slide_game_list_game_no_arr = $argument_arr;
		}
		else
		{
			$this->code_slide_game_list_game_no_arr = null;
		}

	}

	public function get_code_slide_game_list_game_no_arr()
	{
		return $this->code_slide_game_list_game_no_arr;
	}




	/**
	* スライドゲームリスト
	*
	* @return string HTMLコード
	*/
	public function code_slide_game_list($select_menu)
	{

		$language = 'ja';

		$limit = (AGENT_TYPE != 'smartphone') ? \Config::get('limit_slide_game_list') : \Config::get('limit_slide_game_list_sp');

		//$limit = mt_rand(1,20);
		//$type = 'renew';
		//$type = 'access';
		//$game_no_arr = array(227, 226, 225, 224);

		// --------------------------------------------------
		//   クッキー取得
		// --------------------------------------------------

		// $gc_access_game_no = \Cookie::get('gc_access', null);
		// $gc_access_game_no_arr = ($gc_access_game_no) ? explode(',', $gc_access_game_no): null;
		// $this->set_code_slide_game_list_game_no_arr($gc_access_game_no_arr);


		// 最近アクセスしたゲームコミュニティがない場合は、最近更新されたコミュニティを表示する
		// $on_access = true;
		//
		// if ($this->get_code_slide_game_list_type() == 'access' and $gc_access_game_no_arr == null)
		// {
		// 	$this->set_code_slide_game_list_type('renew');
		// 	$on_access = false;
		// }



		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		//$game_data_arr = $this->model_game->get_game_data_slide_game_list($language, $limit, $this->get_code_slide_game_list_type(), $this->get_code_slide_game_list_game_no_arr());
		$game_data_arr = $this->model_game->get_game_data_slide_game_list($language, $limit, 'renew', null);

		//$count = count($game_data_arr);
		//$game_data_arr = array();


		//\Debug::dump($this->get_code_slide_game_list_game_no_arr(), $game_data_arr);


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		//$code = null;

		$view = \View::forge('common/slide_game_list_view');

		//$view->set_safe('type', $this->get_code_slide_game_list_type());

		//$view->set_safe('select_menu', $select_menu);
		$view->set('game_data_arr', $game_data_arr);
		//$view->set('game_data_arr_count', $count);
		//$view->set_safe('on_access', $on_access);

		$result_arr['code'] = $view->render();
		//$result_arr['count'] = $count;



		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;

			echo '$game_data_arr';
			\Debug::dump($game_data_arr);

			echo $code;
		}

		//exit();



		return $result_arr;

	}




}
