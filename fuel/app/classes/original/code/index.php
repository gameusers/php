<?php

namespace Original\Code;

class Index
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// PC・スマホ・タブレット
	public $agent_type = null;

	// ホスト
	public $host = null;

	// ユーザーエージェント
	public $user_agent = null;

	// ユーザーNo
	public $user_no = null;

	// 言語
	public $language = null;

	// URI
	public $uri_base = null;
	public $uri_current = null;

	// アプリモード
	public $app_mode = null;





	/**
	* ゲーム登録フォーム　説明文含め全体
	*
	* @param array $arr
	* @return array
	*/
	public function form_register_game(array $arr): array
	{

		// --------------------------------------------------
		//    コード作成
		// --------------------------------------------------

		$view = \View::forge('content/register_game_view');
		$code = $view->render();


		$return_arr = array(
			'code' => $code,
			'state' => [
				'group' => 'feed',
				'content' => 'register_game'
			],
			'url' => URI_BASE . 'in/feed/register_game',
			'meta_title' => 'Game Users - ゲーム登録',
			'meta_keywords' => 'ゲーム,登録',
			'meta_description' => 'Game Usersでゲーム登録を行うページです。'
		);


		return $return_arr;

	}



	/**
	* ゲーム登録フォーム　中身のフォームのみ
	*
	* @param array $arr
	* @return array HTMLコード
	*/
	public function register_game($arr)
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$arr['keyword'] = 'aaaaaaaaaaaaaaaaaaaaa';
			$arr['page'] = 1;
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');

		$val->add_field('keyword', 'Keyword', 'min_length[1]|max_length[100]');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
		if (isset($arr['game_no'])) $val->add_field('game_no', 'Game No', 'check_game_no');
		$val->add_field('history_no', 'History No', 'numeric_between[0,' . \Config::get('limit_registration_game_data_log') . ']');

		if ($val->run($arr))
		{
			$validated_keyword = ($val->validated('keyword')) ? $val->validated('keyword') : null;
			$validated_page = $val->validated('page');
			$validated_game_no = ($val->validated('game_no')) ? $val->validated('game_no') : null;
			$validated_history_no = $val->validated('history_no');
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
			//echo $error_message;
			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_game = new \Model_Game();
		$model_image = new \Model_Image();
		$original_func_common = new \Original\Func\Common();


		// --------------------------------------------------
		//   定数・変数
		// --------------------------------------------------

		$language = 'ja';
		$administrator = (\Auth::member(100)) ? true : false;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		if (isset($validated_game_no, $validated_history_no))
		{
			$db_game_data_arr = $model_game->search_game_data_form_history($validated_game_no, $validated_history_no);
		}
		else if (isset($validated_game_no))
		{
			$db_game_data_arr = $model_game->search_game_data_form_new($validated_game_no);
		}
		else
		{
			$limit = (AGENT_TYPE != 'smartphone') ? \Config::get('index_search_game_data_form') : \Config::get('index_search_game_data_form_sp');
			$return_arr = $model_game->search_game_data_form($language, $validated_keyword, $validated_page, $limit);
			$db_game_data_arr = $return_arr[0];
			$total = $return_arr[1];
		}



		// --------------------------------------------------
		//    $db_game_data_arrの処理
		// --------------------------------------------------

		$game_data_arr = [];

		if (count($db_game_data_arr) > 0)
		{

			foreach ($db_game_data_arr as $key => $value)
			{
				$game_data_arr[$key]['game_no'] = $value['game_no'];
				$game_data_arr[$key]['approval'] = $value['approval'];
				$game_data_arr[$key]['renewal_date'] = $value['renewal_date'];
				$game_data_arr[$key]['id'] = $value['id'];
				$game_data_arr[$key]['kana'] = $value['kana'];
				$game_data_arr[$key]['twitter_hashtag_ja'] = $value['twitter_hashtag_ja'];
				$game_data_arr[$key]['user_no'] = $value['user_no'];
				$game_data_arr[$key]['name'] = $value['name'];
				$game_data_arr[$key]['subtitle'] = $value['subtitle'];

				$history_arr = unserialize($value['history']);
				$config_arr = unserialize($value['config']);

				if (isset($config_arr[$language]))
				{
					$game_data_arr[$key]['name_fixed'] = $config_arr[$language][0];
					$similarity_fixed_count = $config_arr[$language][1];
				}
				else
				{
					$game_data_arr[$key]['name_fixed'] = false;
					$similarity_fixed_count = 0;
				}


				// --------------------------------------------------
				//    similarity
				// --------------------------------------------------

				$similarity_exploded_arr = explode('/-*-/', $value['similarity']);
				array_shift($similarity_exploded_arr);
				array_pop($similarity_exploded_arr);

				for ($i=0; $i < 20; $i++)
				{
					$similarity_fixed = ($i + 1 <= $similarity_fixed_count) ? true : false;

					if (isset($similarity_exploded_arr[$i]))
					{
						$game_data_arr[$key]['similarity_' . $i] = array($similarity_exploded_arr[$i], $similarity_fixed);
					}
					else
					{
						$game_data_arr[$key]['similarity_' . $i] = array('', $similarity_fixed);
					}
				}


				if (isset($validated_history_no)) $game_data_arr[$key]['history_no'] = $validated_history_no;

				$game_data_arr[$key]['history_count'] = (isset($history_arr[$language])) ? count($history_arr[$language]) : 0;

				$game_data_arr[$key]['on_off_advertisement'] = ($value['on_off_advertisement']) ? 1 : null;
				$game_data_arr[$key]['advertisement'] = ($value['advertisement']) ? unserialize($value['advertisement']) : null;

				$game_data_arr[$key]['thumbnail'] = $value['thumbnail'];


				$db_image_arr = [];

				if ($administrator)
				{
					$temp_arr = array(
						'game_no' => $value['game_no']
					);
					$db_image_arr = $model_image->select_header_hero_image_game_edit($temp_arr);
				}

				$game_data_arr[$key]['hero_image_arr'] = $db_image_arr;


				$game_data_arr[$key]['hardware'] = ($value['hardware']) ? $original_func_common->return_db_array('db_php', $value['hardware']) : [];
				$game_data_arr[$key]['genre'] = ($value['genre']) ? $original_func_common->return_db_array('db_php', $value['genre']) : [];
				$game_data_arr[$key]['players_max'] = ($value['players_max'] != 0) ? $value['players_max'] : null;


				for ($i=1; $i <= 5 ; $i++)
				{
					if ($value['release_date_' . $i])
					{
						$datetime = new \DateTime($value['release_date_' . $i]);
						$game_data_arr[$key]['release_date_' . $i] = $datetime->format("Y-m-d");
					}
					else
					{
						$game_data_arr[$key]['release_date_' . $i] = null;
					}
				}


				if ($value['developer'])
				{
					$temp_arr = $original_func_common->return_db_array('db_php', $value['developer']);
					$db_developer_arr = $model_game->select_data_developer_for_game_data(array('developer_no_arr' => $temp_arr));

					$developer_arr = [];

					foreach ($temp_arr as $key_developer => $value_developer)
					{
						$developer_arr[$value_developer]['developer_no'] = $db_developer_arr[$value_developer]['developer_no'];
						$developer_arr[$value_developer]['name'] = $db_developer_arr[$value_developer]['name'];
						$developer_arr[$value_developer]['studio'] = $db_developer_arr[$value_developer]['studio'];
					}

					$game_data_arr[$key]['developer'] = $developer_arr;
					//\Debug::dump($temp_arr, $db_developer_arr, $developer_arr);
				}
				else
				{
					$game_data_arr[$key]['developer'] = [];
				}



				$db_data_link_arr = [];

				if ($administrator)
				{
					$db_data_link_arr = $model_game->select_data_link(array('game_no' => $value['game_no']));
					//\Debug::dump($validated_game_no, $db_data_link_arr);
				}

				$game_data_arr[$key]['link_arr'] = $db_data_link_arr;


			}

		}
		else
		{

			$game_data_arr[0]['game_no'] = 'new';
			$game_data_arr[0]['approval'] = 0;
			$game_data_arr[0]['renewal_date'] = 0;
			$game_data_arr[0]['id'] = null;
			$game_data_arr[0]['kana'] = null;
			$game_data_arr[0]['twitter_hashtag_ja'] = null;
			$game_data_arr[0]['user_no'] = 0;
			$game_data_arr[0]['name'] = ($validated_keyword) ? $validated_keyword : null;
			$game_data_arr[0]['name_fixed'] = false;
			$game_data_arr[0]['subtitle'] = null;

			for ($i=0; $i < 20; $i++)
			{
				$game_data_arr[0]['similarity_' . $i] = array('', false);
			}

			$game_data_arr[0]['history_count'] = 0;

			$game_data_arr[0]['on_off_advertisement'] = null;
			$game_data_arr[0]['advertisement'] = null;

			$game_data_arr[0]['thumbnail'] = null;
			$game_data_arr[0]['hardware'] = [];
			$game_data_arr[0]['genre'] = [];
			$game_data_arr[0]['players_max'] = null;
			$game_data_arr[0]['release_date_1'] = null;
			$game_data_arr[0]['release_date_2'] = null;
			$game_data_arr[0]['release_date_3'] = null;
			$game_data_arr[0]['release_date_4'] = null;
			$game_data_arr[0]['release_date_5'] = null;
			$game_data_arr[0]['developer'] = [];

			$game_data_arr[0]['link_arr'] = [];

		}



		// --------------------------------------------------
		//    コード作成
		// --------------------------------------------------

		$view = \View::forge('parts/game_data_form_view');
		$view->set('game_data_arr', $game_data_arr);
		$view->set_safe('administrator', $administrator);
		$code = $view->render();


		// --------------------------------------------------
		//    ページャー　プロフィール
		// --------------------------------------------------

		if (isset($validated_game_no))
		{

		}
		else if ($total > $limit)
		{
			// ページャーの数字表示回数取得
			$pagination_times = (AGENT_TYPE != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set('page', $validated_page);
			$view_pagination->set('total', $total);
			$view_pagination->set('limit', $limit);
			$view_pagination->set('times', $pagination_times);
			$view_pagination->set('function_name', 'GAMEUSERS.index.searchGameData');
			$view_pagination->set('argument_arr', array());

			$code .= $view_pagination->render();
		}



		//$test = true;

		if (isset($test))
		{
			if (isset($validated_keyword)) echo '$validated_keyword = ' . $validated_keyword . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';
			if (isset($validated_game_no)) echo '$validated_game_no = ' . $validated_game_no . '<br>';
			if (isset($validated_history_no)) echo '$validated_history_no = ' . $validated_history_no . '<br>';
			//\Debug::dump($validated_game_no);

			if (isset($db_game_data_arr))
			{
				echo '$db_game_data_arr';
				\Debug::dump($db_game_data_arr);
			}

			if (isset($total))
			{
				echo '$total';
				\Debug::dump($total);
			}

			if (isset($game_data_arr))
			{
				echo '$game_data_arr';
				\Debug::dump($game_data_arr);
			}


			echo $code;


			exit();
		}

		//return $code;
		return array('code' => $code);

	}



	/**
	* 開発登録フォーム
	*
	* @param array $arr
	* @return array HTMLコード
	*/
	public function form_developer($arr)
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
		//   管理者のみ
		// --------------------------------------------------

		if ( ! \Auth::member(100))
		{
			throw new Exception('Error');
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');

		$val->add_field('keyword', 'Keyword', 'min_length[1]|max_length[100]');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_keyword = ($val->validated('keyword')) ? $val->validated('keyword') : null;
			$validated_page = $val->validated('page');
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}
			//echo $error_message;
			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_game = new \Model_Game();


		// --------------------------------------------------
		//   定数・変数
		// --------------------------------------------------

		$limit = 5;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_data_developer_arr = [];
		$total = 0;

		if ($validated_keyword)
		{
			$result_arr = $model_game->select_data_developer_for_form([
				'keyword' => $validated_keyword,
				'page' => $validated_page,
				'limit' => $limit
			]);
			$db_data_developer_arr = $result_arr['arr'];
			$total = $result_arr['total'];
			//\Debug::dump($db_data_developer_arr);
		}




		// --------------------------------------------------
		//   データ配列作成
		// --------------------------------------------------

		$data_arr = [];

		for ($i=0; $i < $limit; $i++)
		{
			if (isset($db_data_developer_arr[$i]))
			{
				$data_arr[$i] = $db_data_developer_arr[$i];
			}
			else
			{
				$data_arr[$i]['developer_no'] = null;
				$data_arr[$i]['name'] = null;
				$data_arr[$i]['abbreviation'] = null;
				$data_arr[$i]['studio'] = null;
				$data_arr[$i]['abbreviation_studio'] = null;
			}
		}
		//\Debug::dump($data_arr);



		// --------------------------------------------------
		//    ページャー
		// --------------------------------------------------

		$code_pagination = null;

		if ($total > $limit)
		{
			// ページャーの数字表示回数取得
			$pagination_times = (AGENT_TYPE != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set('page', $validated_page);
			$view_pagination->set('total', $total);
			$view_pagination->set('limit', $limit);
			$view_pagination->set('times', $pagination_times);
			$view_pagination->set('function_name', 'GAMEUSERS.index.searchDeveloperForm');
			$view_pagination->set('argument_arr', array());

			$code_pagination = $view_pagination->render();
		}


		// --------------------------------------------------
		//    コード作成
		// --------------------------------------------------

		$view = \View::forge('form/form_developer_view');
		$view->set('data_arr', $data_arr);
		$view->set('keyword', $validated_keyword);
		$view->set_safe('code_pagination', $code_pagination);
		$code = $view->render();






		//$test = true;

		if (isset($test))
		{
			if (isset($validated_keyword)) echo '$validated_keyword = ' . $validated_keyword . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';
			if (isset($validated_game_no)) echo '$validated_game_no = ' . $validated_game_no . '<br>';
			if (isset($validated_history_no)) echo '$validated_history_no = ' . $validated_history_no . '<br>';
			//\Debug::dump($validated_game_no);

			if (isset($db_game_data_arr))
			{
				echo '$db_game_data_arr';
				\Debug::dump($db_game_data_arr);
			}

			if (isset($total))
			{
				echo '$total';
				\Debug::dump($total);
			}

			if (isset($game_data_arr))
			{
				echo '$game_data_arr';
				\Debug::dump($game_data_arr);
			}


			echo $code;


			exit();
		}

		//return $code;
		return array('code' => $code);

	}



	/**
	* 開発登録
	*
	* @param array $arr
	* @return array HTMLコード
	*/
	public function save_developer($arr)
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{

			$arr['developer_no'] = 5;
			$arr['name'] = 'テスト開発2';
			$arr['abbreviation'] = 'テスト開発 略称';
			$arr['studio'] = 'スタジオ';
			$arr['abbreviation_studio'] = 'スタジオ 略称';
		}


		// --------------------------------------------------
		//   管理者のみ
		// --------------------------------------------------

		if ( ! \Auth::member(100))
		{
			throw new Exception('Error');
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');

		$val->add_field('developer_no', 'developer_no', 'match_pattern["^[1-9]\d*$"]');
		$val->add_field('name', 'name', 'required|min_length[1]|max_length[100]');
		$val->add_field('abbreviation', 'abbreviation', 'min_length[1]|max_length[100]');
		$val->add_field('studio', 'studio', 'min_length[1]|max_length[100]');
		$val->add_field('abbreviation_studio', 'name', 'min_length[1]|max_length[100]');

		if ($val->run($arr))
		{
			$validated_developer_no = ($val->validated('developer_no')) ? $val->validated('developer_no') : null;
			$validated_name = $val->validated('name');
			$validated_abbreviation = ($val->validated('abbreviation')) ? $val->validated('abbreviation') : null;
			$validated_studio = ($val->validated('studio')) ? $val->validated('studio') : null;
			$validated_abbreviation_studio = ($val->validated('abbreviation_studio')) ? $val->validated('abbreviation_studio') : null;
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}
			//echo $error_message;
			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_game = new \Model_Game();


		// --------------------------------------------------
		//   保存用配列作成
		// --------------------------------------------------

		$save_arr = [];

		if ($validated_developer_no)
		{
			$save_arr['update'][0]['developer_no'] = $validated_developer_no;
			$save_arr['update'][0]['name'] = $validated_name;
			$save_arr['update'][0]['abbreviation'] = $validated_abbreviation;
			$save_arr['update'][0]['studio'] = $validated_studio;
			$save_arr['update'][0]['abbreviation_studio'] = $validated_abbreviation_studio;
		}
		else
		{
			$save_arr['insert'][0]['name'] = $validated_name;
			$save_arr['insert'][0]['abbreviation'] = $validated_abbreviation;
			$save_arr['insert'][0]['studio'] = $validated_studio;
			$save_arr['insert'][0]['abbreviation_studio'] = $validated_abbreviation_studio;
		}


		// --------------------------------------------------
		//   データベース更新
		// --------------------------------------------------

		$result = $model_game->insert_update_data_developer($save_arr);



		// --------------------------------------------------
		//   アラート　成功
		// --------------------------------------------------

		$return_arr['alert_color'] = 'success';
		$return_arr['alert_title'] = 'OK';
		$return_arr['alert_message'] = '登録完了';



		if (isset($test))
		{
			if (isset($validated_name)) echo '$validated_name = ' . $validated_name . '<br>';
			if (isset($validated_abbreviation)) echo '$validated_abbreviation = ' . $validated_abbreviation . '<br>';
			if (isset($validated_studio)) echo '$validated_studio = ' . $validated_studio . '<br>';
			if (isset($validated_abbreviation_studio)) echo '$validated_abbreviation_studio = ' . $validated_abbreviation_studio . '<br>';


			if (isset($save_arr))
			{
				echo '$save_arr';
				\Debug::dump($save_arr);
			}

		}


		return $return_arr;

	}



	/**
	* ジャンル登録フォーム
	*
	* @param array $arr
	* @return array HTMLコード
	*/
	public function form_genre($arr)
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
		//   管理者のみ
		// --------------------------------------------------

		if ( ! \Auth::member(100))
		{
			throw new Exception('Error');
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');

		$val->add_field('keyword', 'Keyword', 'min_length[1]|max_length[100]');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_keyword = ($val->validated('keyword')) ? $val->validated('keyword') : null;
			$validated_page = $val->validated('page');
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}
			//echo $error_message;
			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_game = new \Model_Game();


		// --------------------------------------------------
		//   定数・変数
		// --------------------------------------------------

		$limit = 50;


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_data_genre_arr = [];
		$total = 0;

		$result_arr = $model_game->select_data_genre_for_form([
			'keyword' => $validated_keyword,
			'page' => $validated_page,
			'limit' => $limit
		]);
		$db_data_genre_arr = $result_arr['arr'];
		$total = $result_arr['total'];
		//\Debug::dump($db_data_genre_arr);

		//exit();




		// --------------------------------------------------
		//   データ配列作成
		// --------------------------------------------------

		$data_arr = $db_data_genre_arr;

		for ($i=0; $i < 3; $i++)
		{
			$temp_arr['genre_no'] = null;
			$temp_arr['sort'] = null;
			$temp_arr['name'] = null;
			array_push($data_arr, $temp_arr);
		}
		//\Debug::dump($data_arr);



		// --------------------------------------------------
		//    ページャー
		// --------------------------------------------------

		$code_pagination = null;

		if ($total > $limit)
		{
			// ページャーの数字表示回数取得
			$pagination_times = (AGENT_TYPE != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

			$view_pagination = \View::forge('parts/pagination_view');
			$view_pagination->set('page', $validated_page);
			$view_pagination->set('total', $total);
			$view_pagination->set('limit', $limit);
			$view_pagination->set('times', $pagination_times);
			$view_pagination->set('function_name', 'GAMEUSERS.index.searchGenreForm');
			$view_pagination->set('argument_arr', array());

			$code_pagination = $view_pagination->render();
		}


		// --------------------------------------------------
		//    コード作成
		// --------------------------------------------------

		$view = \View::forge('form/form_genre_view');
		$view->set('data_arr', $data_arr);
		$view->set('keyword', $validated_keyword);
		$view->set_safe('code_pagination', $code_pagination);
		$code = $view->render();






		//$test = true;

		if (isset($test))
		{
			if (isset($validated_keyword)) echo '$validated_keyword = ' . $validated_keyword . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';
			if (isset($validated_game_no)) echo '$validated_game_no = ' . $validated_game_no . '<br>';
			if (isset($validated_history_no)) echo '$validated_history_no = ' . $validated_history_no . '<br>';
			//\Debug::dump($validated_game_no);

			if (isset($db_game_data_arr))
			{
				echo '$db_game_data_arr';
				\Debug::dump($db_game_data_arr);
			}

			if (isset($total))
			{
				echo '$total';
				\Debug::dump($total);
			}

			if (isset($game_data_arr))
			{
				echo '$game_data_arr';
				\Debug::dump($game_data_arr);
			}


			echo $code;


			exit();
		}

		//return $code;
		return array('code' => $code);

	}



	/**
	* ジャンル登録
	*
	* @param array $arr
	* @return array HTMLコード
	*/
	public function save_genre($arr)
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{

			$arr['genre_no'] = 3;
			$arr['sort'] = '3';
			$arr['name'] = 'パズル2';
		}


		// --------------------------------------------------
		//   管理者のみ
		// --------------------------------------------------

		if ( ! \Auth::member(100))
		{
			throw new Exception('Error');
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();
		$val->add_callable('Original_Rule_Common');

		$val->add_field('genre_no', 'genre_no', 'match_pattern["^[1-9]\d*$"]');
		$val->add_field('sort', 'sort', 'required|match_pattern["^[1-9]\d*$"]');
		$val->add_field('name', 'name', 'required|min_length[1]|max_length[100]');

		if ($val->run($arr))
		{
			$validated_genre_no = ($val->validated('genre_no')) ? $val->validated('genre_no') : null;
			$validated_sort = $val->validated('sort');
			$validated_name = $val->validated('name');
		}
		else
		{
			$error_message = null;

			if (isset($test) and count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value) $error_message .= $value;
			}
			//echo $error_message;
			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_game = new \Model_Game();


		// --------------------------------------------------
		//   保存用配列作成
		// --------------------------------------------------

		$save_arr = [];

		if ($validated_genre_no)
		{
			$save_arr['update'][0]['genre_no'] = $validated_genre_no;
			$save_arr['update'][0]['sort'] = $validated_sort;
			$save_arr['update'][0]['name'] = $validated_name;
		}
		else
		{
			$save_arr['insert'][0]['sort'] = $validated_sort;
			$save_arr['insert'][0]['name'] = $validated_name;
		}


		// --------------------------------------------------
		//   データベース更新
		// --------------------------------------------------

		$result = $model_game->insert_update_data_genre($save_arr);



		// --------------------------------------------------
		//   アラート　成功
		// --------------------------------------------------

		$return_arr['alert_color'] = 'success';
		$return_arr['alert_title'] = 'OK';
		$return_arr['alert_message'] = '登録完了 No.' . $validated_genre_no;



		if (isset($test))
		{
			if (isset($validated_genre_no)) echo '$validated_genre_no = ' . $validated_genre_no . '<br>';
			if (isset($validated_sort)) echo '$validated_sort = ' . $validated_sort . '<br>';
			if (isset($validated_name)) echo '$validated_name = ' . $validated_name . '<br>';


			if (isset($save_arr))
			{
				echo '$save_arr';
				\Debug::dump($save_arr);
			}

		}


		return $return_arr;

	}




	/**
	* コミュニティ作成フォーム
	*
	* @param array $arr
	* @return array
	*/
	public function form_community_create(array $arr): array
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

		$view = \View::forge('content/community_create_view');
		$code = $view->render();



		$return_arr = array(
			'code' => $code,
			'state' => [
				'group' => 'community',
				'content' => 'create'
			],
			'url' => URI_BASE . 'in/community/create',
			'meta_title' => 'Game Users - コミュニティ作成',
			'meta_keywords' => 'コミュニティ作成',
			'meta_description' => 'Game Usersにコミュニティを作成する。'
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




	/**
	* 音訓索引
	*
	* @param array $arr
	* @return array HTMLコード
	*/
	public function game_index($arr)
	{

		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();

		$val->add_field('keyword', 'Keyword', 'min_length[1]|max_length[1]');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_first_load = (isset($arr['first_load'])) ? true : false;
			$validated_keyword = $val->validated('keyword');
			$validated_page = $val->validated('page');
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
			//echo $error_message;
			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_common = new \Model_Common();
		$model_game = new \Model_Game();
		$model_gc = new \Model_Gc();


		// --------------------------------------------------
		//   定数・変数
		// --------------------------------------------------

		$limit = (AGENT_TYPE != 'smartphone') ? \Config::get('index_search_game_list') : \Config::get('index_search_game_list_sp');
		$language = 'ja';


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'game_no' => null,
			'language' => $language,
			'get_total' => true,
			'page' => $validated_page,
			'limit' => $limit
		);

		// キーワードで検索する場合
		if ($validated_keyword)
		{
			$keyword_1 = mb_convert_kana($validated_keyword, "KVC", "UTF-8");
			$temp_arr['keyword_1'] = $keyword_1;

			$keyword_2 = mb_convert_kana(mb_convert_kana($keyword_1 . "゛", "k", "UTF-8"), "KV", "UTF-8");// 濁点　例：が
			$keyword_3 = mb_convert_kana(mb_convert_kana($keyword_1 . "゜", "k", "UTF-8"), "KV", "UTF-8");// 半濁点　例：ぱ

			if (mb_strlen($keyword_2, 'UTF-8') == 1) $temp_arr['keyword_2'] = $keyword_2;
			if (mb_strlen($keyword_3, 'UTF-8') == 1) $temp_arr['keyword_3'] = $keyword_3;
		}

		$result_arr = $model_game->search_game_list($temp_arr);
		$db_game_data_arr = $result_arr[0];
		$total = $result_arr[1];



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;
		$code_menu = null;

		if (count($db_game_data_arr) > 0)
		{

			// メニュー
			if ($validated_first_load)
			{
				$view = \View::forge('parts/game_index_menu_view');
				$code_menu = $view->render() . "\n";
			}

			// コード
			$view = \View::forge('parts/game_index_view');
			$view->set('db_game_data_arr', $db_game_data_arr);
			$code = $view->render() . "\n";


			// --------------------------------------------------
			//   ページャー
			// --------------------------------------------------

			if ($total > $limit)
			{
				$pagination_times = (AGENT_TYPE != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

				$code .= '<div class="margin_top_40">' . "\n";
				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set_safe('page', $validated_page);
				$view_pagination->set_safe('total', $total);
				$view_pagination->set_safe('limit', $limit);
				$view_pagination->set_safe('times', $pagination_times);
				$view_pagination->set_safe('function_name', 'GAMEUSERS.common.readGameIndex');
				$view_pagination->set_safe('argument_arr', array());

				$code .= $view_pagination->render();
				$code .= '</div>' . "\n";

			}

		}



		//$test = true;

		if (isset($test))
		{
			if (isset($validated_keyword)) echo '$validated_keyword = ' . $validated_keyword . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';

			if (isset($db_game_data_arr))
			{
				echo '$db_game_data_arr';
				\Debug::dump($db_game_data_arr);
			}

			if (isset($total))
			{
				echo '$total';
				\Debug::dump($total);
			}


			echo $code;


			exit();
		}


		return array('code_menu' => $code_menu, 'code' => $code);

	}



}
