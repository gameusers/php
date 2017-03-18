<?php

class Model_Gc extends Model_Crud
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




	// --------------------------------------------------
	//   コミュニティ
	// --------------------------------------------------

	// ------------------------------
	//    取得
	// ------------------------------

	/**
	* Game noを取得
	* @return array
	*/
	/*
	public function get_game_data($game_id)
	{
		$query = DB::select('game_no')->from('game_data');
		$query->where('id', '=', $game_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}
	*/






	/**
	* 募集を取得
	* @return array
	*/
	public function get_recruitment($arr)
	{
		$offset = $arr['limit'] * ($arr['page'] - 1);

		$query = DB::select('*')->from('recruitment');

		if (isset($arr['game_no']))
		{
			$query->where('game_no', '=', $arr['game_no']);
		}
		else if (isset($arr['game_no_arr']))
		{
			$query->where('game_no', 'in', $arr['game_no_arr']);
		}

		if (isset($arr['language'])) $query->where('language', '=', $arr['language']);
		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($arr['limit']);
		$query->offset($offset);
		$result_arr = $query->execute()->as_array();

		// $query = DB::select('recruitment.*', 'game_data.id')->from('recruitment');
		// $query->join('game_data', 'LEFT');
		// $query->on('recruitment.game_no', '=', 'game_data.game_no');
		// if (isset($arr['game_no'])) $query->where('recruitment.game_no', '=', $arr['game_no']);
		// if (isset($arr['language'])) $query->where('recruitment.language', '=', $arr['language']);
		// $query->where('recruitment.on_off', '=', 1);
		// $query->order_by('recruitment.sort_date','desc');
		// $query->limit($arr['limit']);
		// $query->offset($offset);
		// $result_arr = $query->execute()->as_array();

		$total = (isset($arr['get_total'])) ? DB::count_last_query() : null;

		return array($result_arr, $total);
	}



	/**
	* 募集を取得　検索
	* @return array
	*/
	public function get_recruitment_search($game_no, $language, $type_arr, $id_hardware_no_arr, $id_null, $keyword, $page, $limit)
	{
		$offset = $limit * ($page - 1);


		$query = DB::select('*')->from('recruitment');
		$query->where('game_no', '=', $game_no);
		$query->where('language', '=', $language);
		if ($type_arr) $query->where('type', 'in', $type_arr);

		if ($id_hardware_no_arr or $id_null)
		{

			$query->where_open();

			if ($id_hardware_no_arr)
			{
				$query->where_open();
				$query->where('id_hardware_no_1', 'in', $id_hardware_no_arr);
				$query->where('id_1', '!=', null);
				$query->where_close();

				$query->or_where_open();
				$query->where('id_hardware_no_2', 'in', $id_hardware_no_arr);
				$query->where('id_2', '!=', null);
				$query->or_where_close();

				$query->or_where_open();
				$query->where('id_hardware_no_3', 'in', $id_hardware_no_arr);
				$query->where('id_3', '!=', null);
				$query->or_where_close();
			}

			if ($id_null)
			{
				$query->or_where_open();
				$query->where('id_hardware_no_1', 'is', null);
				$query->where('id_1', '!=', null);
				$query->or_where_close();

				$query->or_where_open();
				$query->where('id_hardware_no_2', 'is', null);
				$query->where('id_2', '!=', null);
				$query->or_where_close();

				$query->or_where_open();
				$query->where('id_hardware_no_3', 'is', null);
				$query->where('id_3', '!=', null);
				$query->or_where_close();
			}

			$query->where_close();

		}

		if ($keyword)
		{
			$query->where_open();
			$query->where('handle_name', 'like', '%' . $keyword . '%');
			$query->or_where('etc_title', 'like', '%' . $keyword . '%');
			$query->or_where('comment', 'like', '%' . $keyword . '%');
			$query->or_where('info_title_1', 'like', '%' . $keyword . '%');
			$query->or_where('info_title_2', 'like', '%' . $keyword . '%');
			$query->or_where('info_title_3', 'like', '%' . $keyword . '%');
			$query->or_where('info_title_4', 'like', '%' . $keyword . '%');
			$query->or_where('info_title_5', 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		// var_dump($game_no, $language, $type_arr, $id_hardware_no_arr, $id_null, $keyword, $page, $limit);
		// exit();

		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();
		// echo '<br><br><br><br>';
		// echo DB::last_query();




		if ($type_arr or $id_hardware_no_arr or $id_null or $keyword)
		{
			$total = DB::count_last_query();
		}
		else
		{
			$total = $this->get_game_community_recruitment_total($game_no, 'ja');
		}

		//var_dump($arr);
		//exit();

		return array($arr, $total);
	}


	/**
	* 募集を取得　IDで1件だけ取得
	* @param string $recruitment_id ID
	* @return array
	*/
	public function get_recruitment_appoint($recruitment_id)
	{
		$query = DB::select('*')->from('recruitment');
		$query->where('recruitment_id', '=', $recruitment_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}



	/**
	* 募集　返信を取得
	* @param integer $bbs_comment_no コメントNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	public function get_recruitment_reply($recruitment_id, $language, $page, $limit)
	{
		$offset = $limit * ($page - 1);

		$query = DB::select('*')->from('recruitment_reply');
		$query->where('recruitment_id', '=', $recruitment_id);
		$query->where('language', '=', $language);
		$query->where('on_off', '=', 1);
		$query->order_by('regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();

		return array_reverse($arr);
	}


	/**
	* 募集　返信を取得　IDで1件だけ取得
	* @param string $recruitment_reply_id ID
	* @return array
	*/
	public function get_recruitment_reply_appoint($recruitment_reply_id)
	{
		$query = DB::select('*')->from('recruitment_reply');
		$query->where('recruitment_reply_id', '=', $recruitment_reply_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}


	/**
	* 募集　返信を取得　IDで1件だけ取得　募集IDと返信IDが一致する必要あり
	* @param string $recruitment_id ID
	* @param string $recruitment_reply_id ID
	* @return array
	*/
	public function get_recruitment_reply_appoint_double_check($recruitment_id, $recruitment_reply_id)
	{
		$query = DB::select('*')->from('recruitment_reply');
		$query->where('recruitment_reply_id', '=', $recruitment_reply_id);
		$query->where('recruitment_id', '=', $recruitment_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}



	/**
	* ゲームコミュニティ　募集総数を取得
	* @return array
	*/
	public function get_game_community_recruitment_total($game_no, $language)
	{
		$query = DB::select(array('recruitment_total_' . $language, 'recruitment_total'))->from('game_community');
		$query->where('game_no', '=', $game_no);
		$arr = $query->execute()->current();

		return $arr['recruitment_total'];
	}





	/**
	* ユーザーのゲームコミュニティ設定を取得
	*
	* @param array $db_community_arr コミュニティ情報
	* @return string
	*/
	public function get_user_game_community()
	{
		$query = DB::select('*')->from('users_game_community');
		$query->where('user_no', '=', $this->user_no);
		$arr = $query->execute()->current();

		return $arr;
	}



	/**
	* ユーザーのゲームコミュニティ設定を取得
	*
	* @param array $arr 検索条件
	* @return string
	*/
	public function get_users_game_community_participation_users_list($arr)
	{
		$offset = $arr['limit'] * ($arr['page'] - 1);

		$query = DB::select('users_data.user_no', 'users_data.notification_data', 'users_login.email', 'users_game_community.ng_user', 'users_game_community.config')->from('users_game_community');
		$query->join('users_data', 'LEFT');
		$query->on('users_game_community.user_no', '=', 'users_data.user_no');
		$query->join('users_login', 'LEFT');
		$query->on('users_game_community.user_no', '=', 'users_login.id');

		if (isset($arr['game_no'])) $query->where('users_game_community.notification_recruitment', 'like', '%,' . $arr['game_no'] . ',%');
		$query->where('users_data.on_off', '=', 1);
		$query->where('users_data.notification_on_off', '=', 1);

		$query->limit($arr['limit']);
		$query->offset($offset);

		$result_arr = $query->execute()->as_array();

		$total = DB::count_last_query();

		//echo DB::last_query();

		return array($result_arr, $total);
	}





	/**
	* 募集IDを取得
	* @return array
	*/
	/*
	public function get_recruitment_id($arr)
	{

		$query = DB::select('recruitment_id')->from('recruitment');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('comment', '=', $arr['comment']);
		$query->where('host', '=', $arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();

		$query = DB::select('recruitment_id')->from('recruitment');

		if (isset($arr['game_no']))
		{
			$query->where('game_no', '=', $arr['game_no']);
		}
		else if (isset($arr['game_no_arr']))
		{
			$query->where('game_no', 'in', $arr['game_no_arr']);
		}

		if (isset($arr['language'])) $query->where('language', '=', $arr['language']);
		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($arr['limit']);
		$query->offset($offset);
		$result_arr = $query->execute()->as_array();

		// $query = DB::select('recruitment.*', 'game_data.id')->from('recruitment');
		// $query->join('game_data', 'LEFT');
		// $query->on('recruitment.game_no', '=', 'game_data.game_no');
		// if (isset($arr['game_no'])) $query->where('recruitment.game_no', '=', $arr['game_no']);
		// if (isset($arr['language'])) $query->where('recruitment.language', '=', $arr['language']);
		// $query->where('recruitment.on_off', '=', 1);
		// $query->order_by('recruitment.sort_date','desc');
		// $query->limit($arr['limit']);
		// $query->offset($offset);
		// $result_arr = $query->execute()->as_array();

		$total = (isset($arr['get_total'])) ? DB::count_last_query() : null;

		return array($result_arr, $total);
	}
	*/






	/**
	* 募集を取得　検索
	* @return array
	*/
	public function get_wiki_read_bbs($game_no, $language)
	{

		// ゲームNo取得
		$query = DB::select('id')->from('game_data');
		$query->where('game_no', '=', $game_no);
		$result_arr = $query->execute()->as_array();
		$game_data_id = $result_arr[0]['id'];
		//\Debug::dump($game_no);



		// プレイヤー募集の総数
		$query = DB::select(DB::expr('COUNT(*) as total'))->from('recruitment');
		$query->where('game_no', '=', $game_no);
		$query->where('language', '=', $language);
		$query->where('type', '=', 1);
		$result_arr = $query->execute()->as_array();
		$type1 = (int) $result_arr[0]['total'];


		// フレンド募集の総数
		$query = DB::select(DB::expr('COUNT(*) as total'))->from('recruitment');
		$query->where('game_no', '=', $game_no);
		$query->where('language', '=', $language);
		$query->where('type', '=', 2);
		$result_arr = $query->execute()->as_array();
		$type2 = (int) $result_arr[0]['total'];


		// ギルド・クランメンバー募集の総数
		$query = DB::select(DB::expr('COUNT(*) as total'))->from('recruitment');
		$query->where('game_no', '=', $game_no);
		$query->where('language', '=', $language);
		$query->where('type', '=', 3);
		$result_arr = $query->execute()->as_array();
		$type3 = (int) $result_arr[0]['total'];


		// 売買・交換相手募集の総数
		$query = DB::select(DB::expr('COUNT(*) as total'))->from('recruitment');
		$query->where('game_no', '=', $game_no);
		$query->where('language', '=', $language);
		$query->where('type', '=', 4);
		$result_arr = $query->execute()->as_array();
		$type4 = (int) $result_arr[0]['total'];


		// その他の募集の総数
		$query = DB::select(DB::expr('COUNT(*) as total'))->from('recruitment');
		$query->where('game_no', '=', $game_no);
		$query->where('language', '=', $language);
		$query->where('type', '=', 5);
		$result_arr = $query->execute()->as_array();
		$type5 = (int) $result_arr[0]['total'];


		// 交流BBSスレッド一覧
		$limit = (AGENT_TYPE != 'smartphone') ? Config::get('limit_wiki_read_bbs') : Config::get('limit_wiki_read_bbs_sp');

		$query = DB::select('bbs_thread_no', 'bbs_id', 'title', 'comment_total', 'reply_total')->from('bbs_thread_gc');
		$query->where('game_no', '=', $game_no);
		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset(0);
		$bbs_list_arr = $query->execute()->as_array();

		//\Debug::dump($type1, $type2, $type3, $type4, $type5);
		//\Debug::dump($bbs_list_arr);


		return array('game_data_id' => $game_data_id, 'type1' => $type1, 'type2' => $type2, 'type3' => $type3, 'type4' => $type4, 'type5' => $type5, 'bbs_list_arr' => $bbs_list_arr);

	}








	// ------------------------------
	//    更新
	// ------------------------------

	/**
	* 募集挿入
	* @param array $arr 配列
	* @return array
	*/
	public function insert_recruitment($language, $arr)
	{

		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('recruitment_id')->from('recruitment');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('comment', '=', $arr['comment']);
		$query->where('host', '=', $arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();
		//\Debub::dump($dupli_arr);


		if (empty($dupli_arr))
		{
			try
			{

				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------

				DB::start_transaction();


				// --------------------------------------------------
				//   募集総数取得
				// --------------------------------------------------

				$query = DB::select('recruitment_total_' . $language)->from('game_community');
				$query->where('game_no', '=', $arr['game_no']);
				$result_game_community_total_arr = $query->execute()->current();
				$total = $result_game_community_total_arr['recruitment_total_' . $language] + 1;
				//var_dump($total);

				// --------------------------------------------------
				//   募集挿入
				// --------------------------------------------------

				$query = DB::insert('recruitment');
				$query->set($arr);
				$result_recruitment_arr = $query->execute();


				// --------------------------------------------------
				//   ゲームコミュニティのSort Date、募集更新時間、募集総数更新
				// --------------------------------------------------

				$query = DB::update('game_community');

				$query->set(array(
					'sort_date' => $arr['renewal_date'],
					'recruitment_renewal_date_' . $language => $arr['renewal_date'],
					'recruitment_total_' . $language => $total
				));

				$query->where('game_no', '=', $arr['game_no']);
				$result_game_community_arr = $query->execute();


				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------

				DB::commit_transaction();


				// --------------------------------------------------
				//   結果
				// --------------------------------------------------
				//var_dump('aaaaaaaaaaaaaaa');
				//var_dump($result_recruitment_arr);
				return true;

			}
			catch (Exception $e)
			{

				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------

				DB::rollback_transaction();

			}
		}

		return false;

	}



	/**
	* 募集更新
	* @param array $arr 配列
	* @return array
	*/
	public function update_recruitment($recruitment_id, $game_no, $arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   募集更新
			// --------------------------------------------------

			$query = DB::update('recruitment');
			$query->set($arr);
			$query->where('recruitment_id', '=', $recruitment_id);
			$result_recruitment_arr = $query->execute();


			// --------------------------------------------------
			//   ゲームコミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('game_community');
			$query->set(array('sort_date' => $arr['renewal_date']));
			$query->where('game_no', '=', $game_no);
			$result_game_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}




	/**
	* 募集更新
	* @param array $arr 配列
	* @return array
	*/
	public function update_recruitment_only($recruitment_id, $arr)
	{

		$query = DB::update('recruitment');
		$query->set($arr);
		$query->where('recruitment_id', '=', $recruitment_id);
		$result_arr = $query->execute();

		return $result_arr;

	}






	/**
	* 募集返信挿入
	* @param array $arr 配列
	* @return array
	*/
	public function insert_recruitment_reply($language, $arr)
	{

		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('recruitment_id')->from('recruitment_reply');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('comment', '=', $arr['comment']);
		$query->where('host', '=', $arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();
		//\Debug::dump($dupli_arr);


		if (empty($dupli_arr))
		{
			try
			{

				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------

				DB::start_transaction();


				// --------------------------------------------------
				//   返信総数取得
				// --------------------------------------------------

				$query = DB::select('reply_total_' . $language)->from('recruitment');
				$query->where('recruitment_id', '=', $arr['recruitment_id']);
				$result_recruitment_total_arr = $query->execute()->current();
				$total = $result_recruitment_total_arr['reply_total_' . $language] + 1;
				//var_dump($total);

				// --------------------------------------------------
				//   返信挿入
				// --------------------------------------------------

				$query = DB::insert('recruitment_reply');
				$query->set($arr);
				$result_recruitment_arr = $query->execute();


				// --------------------------------------------------
				//   募集のSort Date、返信総数更新
				// --------------------------------------------------

				$query = DB::update('recruitment');

				$query->set(array(
					'sort_date' => $arr['renewal_date'],
					'reply_total_' . $language => $total
				));

				$query->where('recruitment_id', '=', $arr['recruitment_id']);
				$result_game_community_arr = $query->execute();


				// --------------------------------------------------
				//   ゲームコミュニティのSort Date、募集更新時間、更新
				// --------------------------------------------------

				$query = DB::update('game_community');

				$query->set(array(
					'sort_date' => $arr['renewal_date'],
					'recruitment_renewal_date_' . $language => $arr['renewal_date'],
				));

				$query->where('game_no', '=', $arr['game_no']);
				$result_game_community_arr = $query->execute();


				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------

				DB::commit_transaction();


				// --------------------------------------------------
				//   結果
				// --------------------------------------------------

				return true;

			}
			catch (Exception $e)
			{

				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------

				DB::rollback_transaction();

			}
		}

		return false;

	}



	/**
	* 募集更新
	* @param array $arr 配列
	* @return array
	*/
	public function update_recruitment_reply($recruitment_reply_id, $game_no, $arr)
	{


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			$query = DB::update('recruitment_reply');
			$query->set($arr);
			$query->where('recruitment_reply_id', '=', $recruitment_reply_id);
			$result_recruitment_arr = $query->execute();


			// --------------------------------------------------
			//   ゲームコミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('game_community');
			$query->set(array('sort_date' => $arr['renewal_date']));
			$query->where('game_no', '=', $game_no);
			$result_game_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}





	/**
	* 募集更新
	* @param array $arr 配列
	* @return array
	*/
	public function update_users_game_community($arr)
	{

		$query = DB::update('users_game_community');
		$query->set($arr);
		$query->where('user_no', '=', $this->user_no);
		$result_arr = $query->execute();

		return $result_arr;

	}





	/**
	* 募集削除
	* @param array $arr 配列
	* @return array
	*/
	public function delete_recruitment($language, $game_no, $recruitment_id)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   募集削除
			// --------------------------------------------------

			$query = DB::update('recruitment');
			$query->set(array('on_off' => null));
			$query->where('recruitment_id', '=', $recruitment_id);
			$result_recruitment_arr = $query->execute();


			// --------------------------------------------------
			//   ゲームコミュニティの募集総数更新
			// --------------------------------------------------

			$query = DB::update('game_community');
			$query->set(array('recruitment_total_' . $language => DB::expr('recruitment_total_' . $language . ' - 1')));
			$query->where('game_no', '=', $game_no);
			$result_game_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}




	/**
	* 返信削除
	* @param array $arr 配列
	* @return array
	*/
	public function delete_recruitment_reply($language, $recruitment_id, $recruitment_reply_id)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信削除
			// --------------------------------------------------

			$query = DB::update('recruitment_reply');
			$query->set(array('on_off' => null));
			$query->where('recruitment_reply_id', '=', $recruitment_reply_id);
			$result_recruitment_arr = $query->execute();


			// --------------------------------------------------
			//   ゲームコミュニティの募集総数更新
			// --------------------------------------------------

			$query = DB::update('recruitment');
			$query->set(array('reply_total_' . $language => DB::expr('reply_total_' . $language . ' - 1')));
			$query->where('recruitment_id', '=', $recruitment_id);
			$result_game_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return true;

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}




}
