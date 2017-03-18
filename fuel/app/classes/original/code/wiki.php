<?php

namespace Original\Code;

class Wiki
{


	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	private $page = null;


	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $model_wiki = null;
	private $model_game = null;
	private $model_advertisement = null;

	private $original_validation_common = null;
	private $original_validation_fieldsetex = null;

	private $original_func_common = null;



	// --------------------------------------------------
	//   コンストラクター
	// --------------------------------------------------

	public function __construct()
	{

		// ------------------------------
		//   インスタンス作成
		// ------------------------------

		$this->model_wiki = new \Model_Wiki();
		$this->model_game = new \Model_Game();
		$this->model_advertisement = new \Model_Advertisement();


		$this->original_validation_common = new \Original\Validation\Common();
		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();

		$this->original_func_common = new \Original\Func\Common();




		// ------------------------------
		//   定数設定
		// ------------------------------

		if (AGENT_TYPE != 'smartphone')
		{
			define("LIMIT_WIKI_LIST", (int) \Config::get('limit_wiki_list'));
		}
		else
		{
			define("LIMIT_WIKI_LIST", (int) \Config::get('limit_wiki_list_sp'));
		}

	}






	/**
	* Setter / ページ
	*
	* @param integer $argument
	*/
	public function set_page($argument)
	{
		if ($argument)
		{
			//echo 'aaaa';
			//exit();
			$this->page = (int) $this->original_validation_common->page($argument);
		}
		else
		{
			$this->page = null;
		}
	}

	public function get_page()
	{
		return $this->page;
	}




	/**
	* Wiki タブ
	*
	* @param array $arr 条件
	* @return string HTMLコード
	*/
	public function tab($arr)
	{

		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(index|player)$/');

		if ($val->run($arr))
		{
			$validated_type = $val->validated('type');
		}
		else
		{
			$error_message = '';
			if (count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value)
				{
					$error_message .= $value;
				}
			}

			$return_arr['alert_color'] = 'danger';
			$return_arr['alert_title'] = 'エラー';
			$return_arr['alert_message'] = $error_message;
			$return_arr['code'] = null;
			return $return_arr;

		}


		// --------------------------------------------------
		//   条件設定
		// --------------------------------------------------

		if ($validated_type == 'index')
		{

			$temp_arr = array(
				'list' => array('on' => 1, 'initial_reading' => 1),
				'create' => array('on' => 1, 'initial_reading' => null),
				'edit_list' => array('on' => 1, 'initial_reading' => null)
			);

			$this->set_page(1);

		}
		else if ($validated_type == 'player')
		{

			// --------------------------------------------------
			//   ログインする必要があります。
			// --------------------------------------------------

			if ( ! USER_NO)
			{
				$return_arr['alert_color'] = 'danger';
				$return_arr['alert_title'] = 'エラー';
				$return_arr['alert_message'] = 'ログインする必要があります。';
				$return_arr['code'] = null;
				return $return_arr;
			}


			$temp_arr = array(
				'list' => array('on' => null, 'initial_reading' => null),
				'create' => array('on' => 1, 'initial_reading' => null),
				'edit_list' => array('on' => 1, 'initial_reading' => null)
			);

			$this->set_page(1);

		}


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		// ----------------------------------------
		//   一覧
		// ----------------------------------------

		$on_list = null;
		$code_list = null;

		if (isset($temp_arr['list']['on']))
		{
			$on_list = 1;
			if (isset($temp_arr['list']['initial_reading'])) $code_list = $this->wiki_list(array('edit' => false));
		}

		//$show_list = (isset($arr['list']['show'])) ? 1 : null;
		//$code_list = (isset($arr['list']['show'])) ? $this->wiki_list(array('edit' => false)) : null;


		// ----------------------------------------
		//   作成フォーム
		// ----------------------------------------

		$on_create = null;
		$code_create = null;

		if (isset($temp_arr['create']['on']))
		{
			$on_create = 1;
			if (isset($temp_arr['create']['initial_reading'])) $code_create = $this->create(array());
		}


		//$show_create = (isset($arr['create']['show'])) ? 1 : null;
		//$code_create = (isset($arr['create']['show'])) ? $this->create(array()) : null;


		// ----------------------------------------
		//   編集
		// ----------------------------------------

		$on_edit_list = null;
		$code_edit_list = null;

		if (isset($temp_arr['edit_list']['on']))
		{
			$on_edit_list = 1;
			if (isset($temp_arr['edit_list']['initial_reading'])) $code_edit_list = $this->wiki_list(array('edit' => true));
		}

		//$show_edit_list = (isset($arr['edit_list']['show'])) ? 1 : null;
		//$code_edit_list = (isset($arr['edit_list']['show'])) ? $this->wiki_list(array('edit' => true)) : null;



		// --------------------------------------------------
		//   メインコード
		// --------------------------------------------------

		$view = \View::forge('wiki/wiki_tab_view');

		$view->set_safe('on_list', $on_list);
		$view->set_safe('on_create', $on_create);
		$view->set_safe('on_edit_list', $on_edit_list);

		$view->set_safe('code_list', $code_list);
		$view->set_safe('code_create', $code_create);
		$view->set_safe('code_edit_list', $code_edit_list);

		$return_arr['code'] = $view->render();



		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;

			// if (isset($db_wiki_arr))
			// {
				// echo '$db_wiki_arr';
				// \Debug::dump($db_wiki_arr);
			// }

			echo $return_arr['code'];

		}

		return $return_arr;

	}




	/**
	* Wiki編集フォーム
	*
	* @param array $arr
	* @return array
	*/
	public function form_edit(array $arr): array
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$arr['page'] = 1;
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();

		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_page = (int) $val->validated('page');
		}
		else
		{
			$error_message = '';
			if (count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value)
				{
					$error_message .= $value;
				}
			}

			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   一覧か編集か
		// --------------------------------------------------

		$edit = true;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'limit' => LIMIT_WIKI_LIST,
			'page' => $validated_page,
			'get_total' => true
		);

		if ( ! \Auth::member(100)) $temp_arr['user_no'] = USER_NO;

		$result_arr = $this->model_wiki->get_wiki($temp_arr);
		$db_wiki_arr = $result_arr['data_arr'];
		$total = $result_arr['total'];


		// データがないときは処理停止
		if (count($db_wiki_arr) === 0) return null;

		//\Debug::dump($temp_arr);
		//\Debug::dump($db_wiki_arr);



		// game_no取得
		$game_no_arr = array();

		foreach ($db_wiki_arr as $key => &$value)
		{
			if ($value['game_list'])
			{
				$game_list_arr = $this->original_func_common->return_db_array('db_php', $value['game_list']);
				$game_no_arr = array_merge($game_no_arr, $game_list_arr);
				$value['game_no'] = $game_list_arr[0];
			}


			// ----------------------------------------
			//   広告データ取得
			// ----------------------------------------

			if (isset($value['wiki_user_advertisement']))
			{
				$value['wiki_user_advertisement'] = unserialize($value['wiki_user_advertisement']);
			}


		}
		unset($value);


		// 重複した値を削除
		$game_no_arr = array_unique($game_no_arr);


		// ゲームデータ取得
		$db_game_name_arr = $this->model_game->get_game_name('ja', $game_no_arr);




		// --------------------------------------------------
		//   広告データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'limit' => \Config::get('limit_advertisement'),
			'page' => 1,
			'user_no' => USER_NO
		);

		$result_arr = $this->model_advertisement->get_advertisement($temp_arr);
		$db_advertisement_arr = $result_arr['data_arr'];


		// echo '<br><br><br><br>';
		// \Debug::dump($db_wiki_arr);
		// exit();



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		//$edit_function_argument = ($arr['edit']) ? 'true' : 'false';

		$view = \View::forge('wiki/wiki_list_view');
		$view->set_safe('edit', $edit);
		$view->set('db_wiki_arr', $db_wiki_arr);
		$view->set('db_game_name_arr', $db_game_name_arr);
		$view->set('db_advertisement_arr', $db_advertisement_arr);

		// ページャー
		$view->set('pagination_page', $validated_page);
		$view->set('pagination_total', $total);
		$view->set('pagination_limit', LIMIT_WIKI_LIST);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_function_name', 'GAMEUSERS.wiki_config.readWikiListEdit');
		$view->set('pagination_argument_arr', []);

		$code = $view->render();



		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;


			echo '<br><br><br><br>';

			echo '$total';
			\Debug::dump($total);

			if (isset($db_wiki_arr))
			{
				echo '$db_wiki_arr';
				\Debug::dump($db_wiki_arr);
			}

			echo '$game_no_arr';
			\Debug::dump($game_no_arr);

			echo '$db_game_name_arr';
			\Debug::dump($db_game_name_arr);

			echo '$edit';
			\Debug::dump($edit);

		}



		$add_page_url = ($validated_page === 1) ? null : '/' . $validated_page;
		$add_page_meta_title = ($validated_page === 1) ? null : ' Page ' . $validated_page;

		$return_arr = array(
			'code' => $code,
			'state' => [
				'group' => 'wiki',
				'content' => 'edit',
				'function' => 'readWikiListEdit',
				'page' => $validated_page
			],
			'url' => URI_BASE . 'in/wiki/edit' . $add_page_url,
			'meta_title' => 'Game Users - Wiki編集' . $add_page_meta_title,
			'meta_keywords' => 'Wiki,編集',
			'meta_description' => 'Game UsersのWiki編集ページです。'
		);


		return $return_arr;

	}



	/**
	* Wiki 作成フォーム
	*
	* @param array $arr 配列
	* @return string HTMLコード
	*/
	public function create($arr)
	{

		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('wiki/wiki_create_view');
		$code = $view->render();


		return $code;

	}



	/**
	* Wiki 作成フォーム　新型
	*
	* @param array $arr
	* @return array
	*/
	public function form_create(array $arr): array
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$arr['keyword'] = null;
			$arr['page'] = 1;
		}


		// --------------------------------------------------
		//    コード作成
		// --------------------------------------------------

		$view = \View::forge('wiki/wiki_create_view');
		$code = $view->render();



		$return_arr = array(
			'code' => $code,
			'state' => [
				'group' => 'wiki',
				'content' => 'create'
			],
			'url' => URI_BASE . 'in/wiki/create',
			'meta_title' => 'Game Users - Wiki作成',
			'meta_keywords' => 'Wiki作成',
			'meta_description' => 'Game UsersにWikiを作成する。'
		);




		//$test = true;

		if (isset($test))
		{

			if (isset($db_game_data_arr))
			{
				echo '$db_game_data_arr';
				\Debug::dump($db_game_data_arr);
			}

			echo $code;

			exit();
		}


		return $return_arr;


	}



}
