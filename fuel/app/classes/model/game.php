<?php

class Model_Game extends Model_Crud
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




	/**
	* ゲームデータを取得（Game Noで検索、配列）
	* @return array
	*/
	public function get_game_name($language, $game_no_arr)
	{
		$query = DB::select('game_no', 'id', array('name_' . $language, 'name'), 'thumbnail')->from('game_data');
		$query->where('game_no', 'in', $game_no_arr);
		$result_arr = $query->execute()->as_array('game_no');

		return $result_arr;
	}


	/**
	* game_communityを取得
	* @return array
	*/
	public function get_game_community($arr)
	{
		$query = DB::select('*')->from('game_community');
		if (isset($arr['game_no'])) $query->where('game_no', '=', $arr['game_no']);
		$result_arr = $query->execute()->current();

		return $result_arr;
	}


	/**
	* ゲームデータを取得（キーワードで検索）
	* @return array
	*/
	public function search_game_name($language, $keyword, $limit)
	{
		$query = DB::select('game_no', 'id', array('name_' . $language, 'name'))->from('game_data');

		if ($keyword)
		{
			$query->where_open();
			$query->where('name_' . $language, 'like', '%' . $keyword . '%');
			$query->or_where('similarity_' . $language, 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		$query->order_by('renewal_date','desc');
		$query->limit($limit);
		$query->offset(0);
		$result_arr = $query->execute()->as_array();

		return $result_arr;
	}


	/**
	* ゲームデータを取得（Game Noで検索）
	* @return array
	*/
	public function get_game_data($game_no, $id = null)
	{
		$query = DB::select('*')->from('game_data');
		if ($game_no) $query->where('game_no', '=', $game_no);
		if ($id) $query->where('id', '=', $id);
		$result_arr = $query->execute()->current();

		return $result_arr;
	}


	/**
	* ゲームデータを取得（キーワードで検索）ゲーム登録用
	* @return array
	*/
	public function search_game_data_form($language, $keyword, $page, $limit)
	{
		$language = 'ja';
		$offset = $limit * ($page - 1);

		$query = DB::select('game_no', 'approval', 'renewal_date', 'id', 'kana', 'twitter_hashtag_ja', array('name_' . $language, 'name'), 'subtitle', array('similarity_' . $language, 'similarity'), 'user_no', 'history', 'config', 'on_off_advertisement', 'advertisement', 'thumbnail', 'hardware', 'genre', 'players_max', 'release_date_1', 'release_date_2', 'release_date_3', 'release_date_4', 'release_date_5', 'developer')->from('game_data');

		if ($keyword)
		{
			$query->where_open();
			$query->where('name_' . $language, 'like', '%' . $keyword . '%');
			$query->or_where('similarity_' . $language, 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		$query->order_by('renewal_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$result_arr = $query->execute()->as_array();

		$total = DB::count_last_query();

		return array($result_arr, $total);
	}


	/**
	* ゲームデータを取得（キーワードで検索）ゲーム登録用　ゲームNo指定　新しいもの一件
	* @return array
	*/
	public function search_game_data_form_new($game_no)
	{
		$language = 'ja';

		$query = DB::select('game_no', 'approval', 'renewal_date', 'id', 'kana', 'twitter_hashtag_ja', array('name_' . $language, 'name'), 'subtitle', array('similarity_' . $language, 'similarity'), 'user_no', 'history', 'config', 'on_off_advertisement', 'advertisement', 'thumbnail', 'hardware', 'genre', 'players_max', 'release_date_1', 'release_date_2', 'release_date_3', 'release_date_4', 'release_date_5', 'developer')->from('game_data');
		$query->where('game_no', '=', $game_no);
		$result_arr = $query->execute()->as_array();

		return $result_arr;
	}


	/**
	* ゲームデータを取得（キーワードで検索）ゲーム登録用　更新履歴
	* @return array
	*/
	public function search_game_data_form_history($game_no, $history_no)
	{
		$language = 'ja';

		$query = DB::select('*')->from('game_data');
		$query->where('game_no', '=', $game_no);
		$result_arr = $query->execute()->as_array();

		$history_arr = unserialize($result_arr[0]['history']);

		if (isset($history_arr[$language][$history_no]))
		{
			$result_arr[0]['renewal_date'] = $history_arr[$language][$history_no]['renewal_date'];
			$result_arr[0]['name'] = $history_arr[$language][$history_no]['name_' . $language];
			$result_arr[0]['similarity'] = $history_arr[$language][$history_no]['similarity_' . $language];
			$result_arr[0]['user_no'] = $history_arr[$language][$history_no]['user_no'];
		}
		else
		{
			return null;
		}

		return $result_arr;
	}




	/**
	* ゲームデータを取得（キーワードで検索）ゲーム登録用
	* @param string $arr['language'] 言語
	* @param string $arr['keyword'] キーワード
	* @param integer $arr['page'] ページ
	* @param integer $arr['limit'] リミット
	* @return array
	*/
	public function search_game_list($arr)
	{
		//$language = 'ja';
		$offset = $arr['limit'] * ($arr['page'] - 1);

		$query = DB::select('game_data.game_no', 'game_data.id', array('game_data.name_' . $arr['language'], 'name'), 'game_data.kana')->from('game_data');
		$query->join('game_community', 'LEFT');
		$query->on('game_data.game_no', '=', 'game_community.game_no');

		if (isset($arr['keyword_1']))
		{
			$query->where_open();
			$query->where('game_data.kana', 'like', $arr['keyword_1'] . '%');
			if (isset($arr['keyword_2'])) $query->or_where('game_data.kana', 'like', $arr['keyword_2'] . '%');
			if (isset($arr['keyword_3'])) $query->or_where('game_data.kana', 'like', $arr['keyword_3'] . '%');
			//$query->where('game_data.kana', 'like', mb_convert_kana($arr['keyword'], "KVC") . '%');
			//$query->or_where('similarity_' . $language, 'like', '%' . $keyword . '%');
			$query->where_close();
			//var_dump($arr);
		}

		$query->order_by('game_data.kana','asc');
		$query->limit($arr['limit']);
		$query->offset($offset);
		$result_arr = $query->execute()->as_array();

		$total = DB::count_last_query();

		return array($result_arr, $total);
	}





	/**
	* ゲームデータを取得　広告用
	* @param string $arr['language'] 言語
	* @return array
	*/
	public function search_game_data_advertisement($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$language = (isset($arr['language'])) ? $arr['language'] : 'ja';
		$game_no = (isset($arr['game_no'])) ? $arr['game_no'] : null;

		$limit_app_install = (AGENT_TYPE == 'smartphone') ? Config::get('limit_app_install_sp') : Config::get('limit_app_install');
		//\Debug::dump($limit_app_install);
		//$limit_app_install = Config::get('limit_app_install_sp');

		// メイン取得
		$query = DB::select('game_no', array('name_' . $language, 'name'), 'advertisement')->from('game_data');
		$query->where('on_off_advertisement', '=', 1);
		$query->where('game_no', '=', $game_no);
		$query->limit(1);
		$query->offset(0);
		$result_main_arr = $query->execute()->as_array();

		// サブ取得
		$query = DB::select('game_no', array('name_' . $language, 'name'), 'advertisement')->from('game_data');
		$query->where('on_off_advertisement', '=', 1);
		$query->where('game_no', '!=', $game_no);
		$query->order_by(DB::expr('RAND()'));
		$query->limit($limit_app_install);
		$query->offset(0);
		$result_sub_arr = $query->execute()->as_array();

		// 合成
		$result_arr = array_merge($result_main_arr, $result_sub_arr);

		// シャッフル
		shuffle($result_arr);

		//\Debug::$js_toggle_open = true;
		//\Debug::dump($result_main_arr, $result_sub_arr, $result_arr);

		//exit();

		return $result_arr;

	}





	/**
	* ゲームデータを取得　スライドゲームリスト用
	* @return array
	*/
	public function get_game_data_slide_game_list($language, $limit, $type, $game_no_arr)
	{

		//if ($this->user_no == 1) \Debug::dump($type);

		$query = DB::select('game_data.game_no', 'game_data.renewal_date', 'game_data.id', array('game_data.name_' . $language, 'name'), 'game_data.thumbnail')->from('game_data');
		$query->join('game_community', 'LEFT');
		$query->on('game_data.game_no', '=', 'game_community.game_no');

		if ($type == 'renew')
		{
			$query->order_by('game_community.sort_date','desc');
		}
		else if ($type == 'register')
		{
			$query->order_by('game_data.game_no','desc');
		}
		else if ($type == 'access' && $game_no_arr)
		{
			$query->where('game_data.game_no', 'in', $game_no_arr);
		}

		$query->limit($limit);
		$query->offset(0);
		$result_arr = $query->execute()->as_array('game_no');


		// クッキーの順番通りに並び替え
		if ($type == 'access' && $game_no_arr)
		{
			$temp_arr = array();

			foreach ($game_no_arr as $key => $value)
			{
				if (isset($result_arr[$value])) array_push($temp_arr, $result_arr[$value]);
			}

			$result_arr = $temp_arr;
		}


		return $result_arr;
	}












	/**
	* ゲーム登録更新
	* @return array
	*/
	public function update_game_data($game_no, $arr)
	{
		$query = DB::update('game_data');
		$query->set($arr);
		$query->where('game_no', '=', $game_no);
		$result_arr = $query->execute();

		return $result_arr;
	}


	/**
	* ゲーム登録挿入
	* @return array
	*/
	public function insert_game_data($arr, $datetime_now)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   game_data
			// --------------------------------------------------

			$query = DB::insert('game_data');
			$query->set($arr);
			$result_game_data_arr = $query->execute();


			// --------------------------------------------------
			//   game_community
			// --------------------------------------------------

			$query = DB::insert('game_community');
			$query->set(array('renewal_date' => $datetime_now, 'sort_date' => $datetime_now));
			$result_game_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return $result_game_community_arr;
	}




	// --------------------------------------------------
	//   ゲームハード
	// --------------------------------------------------


	/**
	* ゲームハード情報をソート順に取得
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array コミュニティ情報
	*/
	public function get_hardware_sort($language)
	{
		//$query = DB::select('hardware_no', array('name_' . $language, 'name'), array('abbreviation_' . $language, 'abbreviation'))->from('hardware');
		$query = DB::select('hardware_no', array('abbreviation_' . $language, 'abbreviation'))->from('hardware');
		$query->where('online_id', '=', 1);
		$query->order_by('sort', 'ASC');
		$result_arr = $query->execute()->as_array('hardware_no');

		return $result_arr;
	}


	/**
	* ゲームハード情報をGame Noで取得
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array コミュニティ情報
	*/
	public function get_hardware($hardware_no)
	{
		$query = DB::select('hardware_no')->from('hardware');
		$query->where('online_id', '=', 1);
		$query->where('hardware_no', '=', $hardware_no);
		$result_arr = $query->execute()->current();

		return $result_arr;
	}



	/**
	* ゲームハード情報を取得　ゲーム登録用　管理者のみ
	* @param string $language 言語
	* @return array
	*/
	public function get_hardware_register_game($language)
	{
		$query = DB::select('hardware_no', array('name_' . $language, 'abbreviation'))->from('hardware');
		$query->order_by('sort', 'ASC');
		$result_arr = $query->execute()->as_array('hardware_no');

		return $result_arr;
	}





	// --------------------------------------------------
	//   ID
	// --------------------------------------------------


	/**
	* IDを取得
	* @param integer $bbs_comment_no コメントNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	public function get_game_id($arr)
	{

		$query = DB::select('game_id_no', 'sort_no', 'game_no', 'hardware_no', 'id')->from('game_id');

		if (isset($arr['game_no']))
		{
			$query->where_open();
			$query->where('game_no', '=', $arr['game_no']);
			$query->or_where('game_no', '=', null);
			$query->where_close();
		}

		$query->where('user_no', '=', $this->user_no);
		$query->where('on_off', '=', 1);
		$query->order_by('sort_no','asc');

		if (isset($arr['page'], $arr['limit']))
		{
			$offset = $arr['limit'] * ($arr['page'] - 1);
			$query->limit($arr['limit']);
			$query->offset($offset);
		}

		$result_arr = $query->execute()->as_array();

		//echo DB::last_query();

		$total = DB::count_last_query();

		return array($result_arr, $total);

		//return $arr;
	}



	/**
	* IDを取得　game_id_noで検索
	*
	* @param array $game_id_no ゲームIDNo
	* @return string
	*/
	public function get_game_id_simple($game_id_no)
	{
		$query = DB::select('*')->from('game_id');
		$query->where('game_id_no', '=', $game_id_no);
		$result_arr = $query->execute()->current();

		return $result_arr;
	}


	/**
	* ゲームID挿入・編集
	* @return array
	*/
	public function insert_update_game_id($arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();



			// --------------------------------------------------
			//   game_id
			// --------------------------------------------------

			foreach ($arr as $key => $value) {

				// 削除
				if ($value['delete'] and $value['game_id_no'])
				{
					$query = DB::update('game_id');
					$query->set(array('on_off' => null));
					$query->where('game_id_no', '=', $value['game_id_no']);
					$query->where('user_no', '=', $value['user_no']);
					$result_arr = $query->execute();
				}
				// 更新
				else if ($value['game_id_no'])
				{
					unset($value['delete']);

					$query = DB::update('game_id');
					$query->set($value);
					$query->where('game_id_no', '=', $value['game_id_no']);
					$query->where('user_no', '=', $value['user_no']);
					$result_arr = $query->execute();
				}
				// 挿入
				else
				{
					// 重複チェック
					$query = DB::select('game_id_no')->from('game_id');
					$query->where('game_no', '=', $value['game_no']);
					$query->where('hardware_no', '=', $value['hardware_no']);
					$query->where('id', '=', $value['id']);
					$result_arr = $query->execute()->current();
					//var_dump($result_arr);

					// 重複していない場合、挿入
					if ( ! $result_arr)
					{
						unset($value['game_id_no'], $value['delete']);

						$query = DB::insert('game_id');
						$query->set($value);
						$result_arr = $query->execute();
					}


				}

				//var_dump($value);

			}


			//echo 'aaa';
			//var_dump($arr);

			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return $result_arr;
	}








	/**
	* 開発取得
	* @return array
	*/
	public function select_search_data_developer($arr)
	{
		$keyword = ($arr['keyword']) ?? null;
		$page = ($arr['page']) ?? 1;
		$limit = ($arr['limit']) ?? 20;
		$offset = $limit * ($page - 1);

		$query = DB::select('*')->from('data_developer');

		if ($keyword)
		{
			$query->where_open();
			$query->where('name', 'like', '%' . $keyword . '%');
			$query->or_where('abbreviation', 'like', '%' . $keyword . '%');
			$query->or_where('studio', 'like', '%' . $keyword . '%');
			$query->or_where('abbreviation_studio', 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		$query->limit($limit);
		$query->offset($offset);
		$result_arr = $query->execute()->as_array();

		return $result_arr;
	}


	/**
	* 開発取得　開発登録フォーム用
	* @return array
	*/
	public function select_data_developer_for_form($arr)
	{
		$keyword = ($arr['keyword']) ?? null;
		$page = ($arr['page']) ?? 1;
		$limit = ($arr['limit']) ?? 20;
		$offset = $limit * ($page - 1);

		$query = DB::select('*')->from('data_developer');

		if ($keyword)
		{
			$query->where_open();
			$query->where('name', 'like', '%' . $keyword . '%');
			$query->or_where('abbreviation', 'like', '%' . $keyword . '%');
			$query->or_where('studio', 'like', '%' . $keyword . '%');
			$query->or_where('abbreviation_studio', 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		$query->limit($limit);
		$query->offset($offset);
		$result_arr = $query->execute()->as_array();

		$total = DB::count_last_query();

		return ['arr' => $result_arr, 'total' => $total];
	}



	/**
	* 開発取得　ゲーム登録用
	* @return array
	*/
	public function select_data_developer_for_game_data($arr)
	{
		$developer_no_arr = ($arr['developer_no_arr']) ?? null;

		$query = DB::select('*')->from('data_developer');
		if ($developer_no_arr) $query->where('developer_no', 'in', $developer_no_arr);

		$query->limit(20);
		$query->offset(0);
		$result_arr = $query->execute()->as_array('developer_no');

		return $result_arr;
	}




	/**
	* 開発更新
	* @return array
	*/
	// public function update_data_developer($arr)
	// {
	// 	$query = DB::update('data_developer');
	// 	$query->set($arr);
	// 	$query->where('developer_no', '=', $arr['developer_no']);
	// 	$result_arr = $query->execute();
	//
	// 	return $result_arr;
	// }


	/**
	* 開発挿入・更新
	* @return array
	*/
	public function insert_update_data_developer($arr)
	{

		$update_arr = $arr['update'] ?? null;
		$insert_arr = $arr['insert'] ?? null;

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   更新
			// --------------------------------------------------

			if ($update_arr)
			{
				foreach ($update_arr as $key => $value)
				{
					$query = DB::update('data_developer');
					$query->set($value);
					$query->where('developer_no', '=', $value['developer_no']);
					$result_arr = $query->execute();
				}
			}


			// --------------------------------------------------
			//   挿入
			// --------------------------------------------------

			if ($insert_arr)
			{
				foreach ($insert_arr as $key => $value)
				{
					$query = DB::insert('data_developer');
					$query->set($value);
					$result_arr = $query->execute();
				}
			}



			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();

			$return = true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

			$return = false;

		}

		return $return;

	}




	/**
	* ジャンル取得
	* @return array
	*/
	public function select_data_genre()
	{
		$query = DB::select('*')->from('data_genre');
		$query->order_by('sort', 'ASC');
		$result_arr = $query->execute()->as_array();

		return $result_arr;
	}


	/**
	* ジャンル取得　登録フォーム用
	* @return array
	*/
	public function select_data_genre_for_form($arr)
	{
		$keyword = ($arr['keyword']) ?? null;
		$page = ($arr['page']) ?? 1;
		$limit = ($arr['limit']) ?? 20;
		$offset = $limit * ($page - 1);

		$query = DB::select('*')->from('data_genre');

		if ($keyword)
		{
			$query->where_open();
			$query->where('name', 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		$query->order_by('sort', 'ASC');
		$query->limit($limit);
		$query->offset($offset);
		$result_arr = $query->execute()->as_array();

		$total = DB::count_last_query();

		return ['arr' => $result_arr, 'total' => $total];
	}



	/**
	* ジャンル挿入・更新
	* @return array
	*/
	public function insert_update_data_genre($arr)
	{

		$update_arr = $arr['update'] ?? null;
		$insert_arr = $arr['insert'] ?? null;

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   更新
			// --------------------------------------------------

			if ($update_arr)
			{
				foreach ($update_arr as $key => $value)
				{
					$query = DB::update('data_genre');
					$query->set($value);
					$query->where('genre_no', '=', $value['genre_no']);
					$result_arr = $query->execute();
				}
			}


			// --------------------------------------------------
			//   挿入
			// --------------------------------------------------

			if ($insert_arr)
			{
				foreach ($insert_arr as $key => $value)
				{
					$query = DB::insert('data_genre');
					$query->set($value);
					$result_arr = $query->execute();
				}
			}



			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();

			$return = true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

			$return = false;

		}

		return $return;

	}





	/**
	* リンク取得
	* @return array
	*/
	public function select_data_link($arr)
	{
		$game_no = $arr['game_no'];

		$query = DB::select('*')->from('data_link');
		$query->where('game_no', '=', $game_no);
		$query->limit(20);
		$query->offset(0);
		$result_arr = $query->execute()->as_array();

		return $result_arr;
	}



	/**
	* リンク取得　更新用
	* @return array
	*/
	public function select_data_link_for_update($arr)
	{
		$game_no = $arr['game_no'];

		$query = DB::select('*')->from('data_link');

		$query->where_open();
		$query->where('game_no', '=', $game_no);
		$query->or_where('url', '=', null);
		$query->where_close();

		$query->limit(20);
		$query->offset(0);
		$result_arr = $query->execute()->as_array();

		return $result_arr;
	}



	/**
	* リンク挿入・更新
	* @return array
	*/
	public function insert_update_data_link($arr)
	{

		$update_arr = $arr['update'] ?? null;
		$insert_arr = $arr['insert'] ?? null;


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   更新
			// --------------------------------------------------

			if ($update_arr)
			{
				foreach ($update_arr as $key => $value)
				{
					$query = DB::update('data_link');
					$query->set($value);
					$query->where('link_no', '=', $value['link_no']);
					$result_arr = $query->execute();
				}
			}


			// --------------------------------------------------
			//   挿入
			// --------------------------------------------------

			if ($insert_arr)
			{
				foreach ($insert_arr as $key => $value)
				{
					$query = DB::insert('data_link');
					$query->set($value);
					$result_arr = $query->execute();
				}
			}



			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();

			$return = true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

			$return = false;

		}

		return $return;

	}




	// --------------------------------------------------
	//   ヘッダー用
	// --------------------------------------------------


	/**
	* ゲームハード情報取得
	* @param array $arr
	* @return array
	*/
	public function select_hardware_for_header($arr)
	{

		$language = $arr['language'];
		$hardware_no_arr = $arr['hardware_no_arr'];

		$query = DB::select('hardware_no', array('abbreviation_' . $language, 'abbreviation'))->from('hardware');
		$query->where('hardware_no', 'in', $hardware_no_arr);
		$result_arr = $query->execute()->as_array();

		return $result_arr;

	}


	/**
	* ジャンル取得
	* @return array
	*/
	public function select_data_genre_for_header($arr)
	{

		$genre_no_arr = $arr['genre_no_arr'];

		$query = DB::select('genre_no', 'name')->from('data_genre');
		$query->where('genre_no', 'in', $genre_no_arr);
		$result_arr = $query->execute()->as_array();

		return $result_arr;

	}


	/**
	* 開発取得
	* @return array
	*/
	public function select_data_developer_for_header($arr)
	{

		$developer_no_arr = $arr['developer_no_arr'];

		$query = DB::select('*')->from('data_developer');
		$query->where('developer_no', 'in', $developer_no_arr);
		$result_arr = $query->execute()->as_array();

		return $result_arr;

	}


	/**
	* リンク取得
	* @return array
	*/
	public function select_data_link_for_header($arr)
	{

		$game_no = $arr['game_no'];

		$query = DB::select('type', 'name', 'url')->from('data_link');
		$query->where('game_no', '=', $game_no);
		$result_arr = $query->execute()->as_array();

		return $result_arr;

	}


}
