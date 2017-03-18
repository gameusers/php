<?php

class Model_Co extends Model_Crud
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
	* コミュニティデータを取得
	* @return array
	*/
	public function get_community($community_no, $community_id = null)
	{
		$query = DB::select('*')->from('community');
		if ($community_no) $query->where('community_no', '=', $community_no);
		if ($community_id) $query->where('community_id', '=', $community_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}


	/**
	* コミュニティIDの重複をチェックする
	* @return array
	*/
	public function check_community_id_duplication($community_no, $community_id)
	{
		$query = DB::select('community_no')->from('community');
		if ($community_no) $query->where('community_no', '!=', $community_no);
		if ($community_id) $query->where('community_id', '=', $community_id);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}


	// ------------------------------
	//    更新
	// ------------------------------

	/**
	* コミュニティを挿入 Community
	* @return array
	*/
	public function insert_community($save_arr)
	{
		$query = DB::insert('community');
		$query->set($save_arr);
		$arr = $query->execute();

		return $arr;
	}


	/**
	* コミュニティを更新 Community
	* @return array
	*/
	public function update_community($community_no, $save_arr)
	{
		$query = DB::update('community');
		$query->set($save_arr);
		$query->where('community_no', '=', $community_no);
		$arr = $query->execute();

		return $arr;
	}


	/**
	* コミュニティとUser Dataを同時に更新
	* コミュニティに参加する・退会する場合などに利用
	* @return array
	*/
	public function update_community_and_user_data($community_no, $save_commutniy_arr, $user_no, $save_user_data_arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   コミュニティ更新
			// --------------------------------------------------

			$query = DB::update('community');
			$query->set($save_commutniy_arr);
			$query->where('community_no', '=', $community_no);
			$arr = $query->execute();


			// --------------------------------------------------
			//   User Data更新
			// --------------------------------------------------

			if ($save_user_data_arr)
			{
				$query = DB::update('users_data');
				$query->set($save_user_data_arr);
				$query->where('user_no', '=', $user_no);
				$arr = $query->execute();
			}
			/*
			$community = '';
			foreach ($save_commutniy_arr as $key => $value) {
				$community .= $key . ' = ' . $value . "\n";
			}

			$user_data = '';
			foreach ($save_user_data_arr as $key => $value) {
				$user_data .= $key . ' = ' . $value . "\n";
			}

			Log::error('エラー : ' . $community_no . "\n" . $community . "\n" . $user_no . "\n" . $user_data);
			*/
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
	* コミュニティを更新 Community　コンフィグのみ
	* @return array
	*/
	/*
	public function update_community_config_only($community_no, $renewal_date, $config)
	{
		$query = DB::update('community');
		$query->set(array('renewal_date' => $renewal_date, 'sort_date' => $renewal_date, 'config' => $config));
		$query->where('community_no', '=', $community_no);
		$arr = $query->execute();

		return $arr;
	}
	*/



	// --------------------------------------------------
	//   告知
	// --------------------------------------------------

	// ------------------------------
	//    取得
	// ------------------------------

	/**
	* 告知を取得
	* @return array
	*/
	public function get_announcement($announcement_no)
	{
		$query = DB::select('*')->from('announcement');
		$query->where('announcement_no', '=', $announcement_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}


	/**
	* 告知を一覧で取得　コミュニティNoで検索
	* @param integer $community_no コミュニティNo
	* @param integer $page ページ
	* @return array
	*/
	public function get_announcement_list($community_no, $page)
	{
		$limit = 1;
		$offset = $limit * ($page - 1);

		$query = DB::select('*')->from('announcement');
		$query->where('community_no', '=', $community_no);
		$query->where('on_off', '=', 1);
		$query->order_by('renewal_date','desc');
		$query->limit(1);
		$query->offset($offset);
		$arr = $query->execute()->current();

		return $arr;
	}


	/**
	* 告知を一覧で取得　総数取得
	* @param integer $community_no コミュニティNo
	* @return array
	*/
	public function get_announcement_list_total($community_no)
	{
		$query = DB::select(DB::expr('COUNT(*) as total'))->from('announcement');
		$query->where('community_no', '=', $community_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr['total'];
	}


	// ------------------------------
	//    更新
	// ------------------------------

	/**
	* 告知挿入
	* @return array
	*/
	public function insert_announcement($arr)
	{

		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('announcement_no')->from('announcement');
		$query->where('community_no', '=', $arr['community_no']);
		$query->where('user_no', '=', $arr['user_no']);
		$query->where('profile_no', '=', $arr['profile_no']);
		$query->where('title', '=', $arr['title']);
		$query->where('comment', '=', $arr['comment']);
		$query->where('image', '=', $arr['image']);
		$query->where('movie', '=', $arr['movie']);
		$dupli_arr = $query->execute()->current();

		//var_dump($arr);
		//var_dump($dupli_arr);
		//

		if (isset($dupli_arr))
		{
			return array('error' => true);
		}
		//exit();

		$query = DB::insert('announcement');
		$query->set($arr);
		$arr = $query->execute();

		return $arr;
	}


	/**
	* 告知更新
	* @return array
	*/
	public function update_announcement($announcement_no, $arr)
	{
		$query = DB::update('announcement');
		$query->set($arr);
		$query->where('announcement_no', '=', $announcement_no);
		$arr = $query->execute();

		return $arr;
	}


	/**
	* 告知削除
	* @return array
	*/
	public function delete_announcement($announcement_no, $renewal_date)
	{
		$query = DB::update('announcement');
		$query->set(array('on_off' => null, 'renewal_date' => $renewal_date));
		$query->where('announcement_no', '=', $announcement_no);
		$arr = $query->execute();

		return $arr;
	}







	// --------------------------------------------------
	//   BBS
	// --------------------------------------------------

	// ------------------------------
	//    取得
	// ------------------------------

	/**
	* スレッドを取得
	* @param integer $community_no コミュニティNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	public function get_bbs_thread_list($community_no, $page, $limit)
	{
		$offset = $limit * ($page - 1);

		$query = DB::select('bbs_thread_no', 'regi_date', 'renewal_date', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'title', 'comment', 'image', 'movie', 'comment_total', 'good', 'host', 'user_agent')->from('bbs_thread');
		$query->where('community_no', '=', $community_no);
		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();

		return $arr;
	}


	/**
	* スレッドを取得
	* @param integer $community_no コミュニティNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	public function get_bbs_thread_appoint_thread_no($bbs_thread_no)
	{
		$query = DB::select('bbs_thread_no', 'regi_date', 'renewal_date', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'title', 'comment', 'image', 'movie', 'comment_total', 'good', 'host', 'user_agent')->from('bbs_thread');
		$query->where('bbs_thread_no', '=', $bbs_thread_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->as_array();

		return $arr;
	}


	/**
	* スレッドを取得　一覧用
	* @param integer $community_no コミュニティNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	public function get_bbs_thread_list_title_only($community_no, $page, $limit)
	{
		$offset = $limit * ($page - 1);

		$query = DB::select('bbs_thread_no', 'title')->from('bbs_thread');
		$query->where('community_no', '=', $community_no);
		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();

		return $arr;
	}


	/**
	* スレッドを取得　総数取得
	* @param integer $community_no コミュニティNo
	* @return array
	*/
	public function get_bbs_thread_list_total($community_no)
	{
		$query = DB::select('bbs_thread_total')->from('community');
		$query->where('community_no', '=', $community_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();
		/*
		$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_thread');
		$query->where('community_no', '=', $community_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();
		*/
		return $arr['bbs_thread_total'];
	}


	/**
	* スレッドを取得　 スレッドNoで検索
	* @param integer $community_no コミュニティNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	public function get_bbs_thread($bbs_thread_no)
	{
		$query = DB::select('bbs_thread_no', 'bbs_id', 'renewal_date', 'community_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'title', 'comment', 'image', 'movie', 'comment_total', 'good', 'host', 'user_agent')->from('bbs_thread');
		$query->where('bbs_thread_no', '=', $bbs_thread_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}



	/**
	* コメントを取得
	* @param integer $bbs_thread_no スレッドNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	public function get_bbs_comment_list($bbs_thread_no, $page, $limit)
	{
		$offset = $limit * ($page - 1);

		//$query = DB::select('*')->from('bbs_comment');
		$query = DB::select('bbs_comment_no', 'regi_date', 'renewal_date', 'community_no', 'bbs_thread_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'reply_total', 'good', 'host', 'user_agent')->from('bbs_comment');
		$query->where('bbs_thread_no', '=', $bbs_thread_no);
		$query->where('on_off', '=', 1);
		//$query->order_by('regi_date','desc');
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();

		//return array_reverse($arr);
		return $arr;
	}


	/**
	* コメントを取得　 コメントNoで検索
	* @param integer $bbs_comment_no コメントNo
	* @return array
	*/
	public function get_bbs_comment($bbs_comment_no)
	{
		$query = DB::select('bbs_comment_no', 'renewal_date', 'community_no', 'bbs_thread_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'reply_total', 'good', 'host', 'user_agent')->from('bbs_comment');
		$query->where('bbs_comment_no', '=', $bbs_comment_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}



	/**
	* 返信を取得
	* @param integer $bbs_comment_no コメントNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	public function get_bbs_reply_list($bbs_comment_no, $page, $limit)
	{
		$offset = $limit * ($page - 1);

		//$query = DB::select('*')->from('bbs_reply');
		$query = DB::select('bbs_reply_no', 'regi_date', 'renewal_date', 'community_no', 'bbs_thread_no', 'bbs_comment_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'good', 'host', 'user_agent')->from('bbs_reply');
		$query->where('bbs_comment_no', '=', $bbs_comment_no);
		$query->where('on_off', '=', 1);
		$query->order_by('regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();

		return array_reverse($arr);
	}


	/**
	* 返信を取得　 返信Noで検索
	* @param integer $bbs_reply_no 返信No
	* @return array
	*/
	public function get_bbs_reply($bbs_reply_no)
	{
		$query = DB::select('bbs_reply_no', 'renewal_date', 'community_no', 'bbs_thread_no', 'bbs_comment_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'good', 'host', 'user_agent')->from('bbs_reply');
		$query->where('bbs_reply_no', '=', $bbs_reply_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}





	// ------------------------------
	//    更新
	// ------------------------------

	/**
	* スレッド挿入
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_thread($arr)
	{

		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_thread_no')->from('bbs_thread');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('community_no', '=', $arr['community_no']);
		$query->where('title', '=', $arr['title']);
		$query->where('comment', '=', $arr['comment']);
		$query->where('host', '=', $arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();


		if (empty($dupli_arr))
		{
			try
			{

				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------

				DB::start_transaction();


				// --------------------------------------------------
				//   スレッド数取得
				// --------------------------------------------------

				$query = DB::select('bbs_thread_total')->from('community');
				$query->where('community_no', '=', $arr['community_no']);
				$result_thread_arr = $query->execute()->current();
				$thread_total = $result_thread_arr['bbs_thread_total'] + 1;


				// --------------------------------------------------
				//   スレッド挿入
				// --------------------------------------------------

				$query = DB::insert('bbs_thread');
				$query->set($arr);
				$result_thread_arr = $query->execute();


				// --------------------------------------------------
				//   コミュニティのSort Date＆スレッド数更新
				// --------------------------------------------------

				$query = DB::update('community');
				$query->set(array('sort_date' => $arr['renewal_date'], 'bbs_thread_total' => $thread_total));
				$query->where('community_no', '=', $arr['community_no']);
				$result_community_arr = $query->execute();


				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------

				DB::commit_transaction();


				// --------------------------------------------------
				//   結果
				// --------------------------------------------------

				return $result_thread_arr[0];

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
	* スレッド更新
	* @param array $arr 配列
	* @return array
	*/
	public function update_bbs_thread($no, $arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   スレッド更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');
			$query->set($arr);
			$query->where('bbs_thread_no', '=', $no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('community');
			$query->set(array('sort_date' => $arr['renewal_date']));
			$query->where('community_no', '=', $arr['community_no']);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return $result_comment_arr;

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
	* スレッド削除
	* @param array $arr 配列
	* @return array
	*/
	public function delete_bbs_thread($community_no, $bbs_thread_no, $arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   スレッド数取得
			// --------------------------------------------------

			$query = DB::select('bbs_thread_total')->from('community');
			$query->where('community_no', '=', $community_no);
			$result_thread_arr = $query->execute()->current();
			$thread_total = $result_thread_arr['bbs_thread_total'] - 1;


			// --------------------------------------------------
			//   スレッド更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');
			$query->set($arr);
			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのスレッド数更新
			// --------------------------------------------------

			$query = DB::update('community');
			$query->set(array('bbs_thread_total' => $thread_total));
			$query->where('community_no', '=', $community_no);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return $result_comment_arr;

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
	* コメント挿入
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_comment($arr)
	{

		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_comment_no')->from('bbs_comment');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('bbs_thread_no', '=', $arr['bbs_thread_no']);
		$query->where('comment', '=', $arr['comment']);
		$query->where('host', '=', $arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();


		if (empty($dupli_arr))
		{
			try
			{

				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------

				DB::start_transaction();


				// --------------------------------------------------
				//   スレッドのコメント数取得
				// --------------------------------------------------

				$query = DB::select('comment_total')->from('bbs_thread');
				$query->where('bbs_thread_no', '=', $arr['bbs_thread_no']);
				$result_thread_arr = $query->execute()->current();
				$comment_total = $result_thread_arr['comment_total'] + 1;


				// --------------------------------------------------
				//   コメント更新
				// --------------------------------------------------

				$query = DB::insert('bbs_comment');
				$query->set($arr);
				$result_comment_arr = $query->execute();


				// --------------------------------------------------
				//   スレッドのSort Date＆コメント数更新
				// --------------------------------------------------

				$query = DB::update('bbs_thread');
				$query->set(array('sort_date' => $arr['renewal_date'], 'comment_total' => $comment_total));
				$query->where('bbs_thread_no', '=', $arr['bbs_thread_no']);
				$result_thread_arr = $query->execute();


				// --------------------------------------------------
				//   コミュニティのSort Date更新
				// --------------------------------------------------

				$query = DB::update('community');
				$query->set(array('sort_date' => $arr['renewal_date']));
				$query->where('community_no', '=', $arr['community_no']);
				$result_community_arr = $query->execute();


				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------

				DB::commit_transaction();


				// --------------------------------------------------
				//   結果
				// --------------------------------------------------

				return $result_comment_arr[0];

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
	* コメント更新
	* @param array $arr 配列
	* @return array
	*/
	public function update_bbs_comment($no, $arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   コメント更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');
			$query->set($arr);
			$query->where('bbs_comment_no', '=', $no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date 更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');
			$query->set(array('sort_date' => $arr['renewal_date']));
			$query->where('bbs_thread_no', '=', $arr['bbs_thread_no']);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('community');
			$query->set(array('sort_date' => $arr['renewal_date']));
			$query->where('community_no', '=', $arr['community_no']);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return $result_comment_arr;

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
	* コメント削除
	* @param array $arr 配列
	* @return array
	*/
	public function delete_bbs_comment($bbs_thread_no, $bbs_comment_no, $arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   コメント数取得
			// --------------------------------------------------

			$query = DB::select('comment_total')->from('bbs_thread');
			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute()->current();
			$comment_total = $result_thread_arr['comment_total'] - 1;


			// --------------------------------------------------
			//   スレッド更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');
			$query->set($arr);
			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのコメント数更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');
			$query->set(array('comment_total' => $comment_total));
			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


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
	* 返信挿入
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_reply($arr)
	{

		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_reply_no')->from('bbs_reply');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('bbs_comment_no', '=', $arr['bbs_comment_no']);
		$query->where('comment', '=', $arr['comment']);
		$query->where('host', '=', $arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();


		if (empty($dupli_arr))
		{
			try
			{

				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------

				DB::start_transaction();


				// --------------------------------------------------
				//   コメントの返信数取得
				// --------------------------------------------------

				$query = DB::select('reply_total')->from('bbs_comment');
				$query->where('bbs_comment_no', '=', $arr['bbs_comment_no']);
				$result_comment_arr = $query->execute()->current();
				$reply_total = $result_comment_arr['reply_total'] + 1;


				// --------------------------------------------------
				//   返信更新
				// --------------------------------------------------

				$query = DB::insert('bbs_reply');
				$query->set($arr);
				$result_reply_arr = $query->execute();


				// --------------------------------------------------
				//   コメントのSort Date＆返信数更新
				// --------------------------------------------------

				$query = DB::update('bbs_comment');
				$query->set(array('sort_date' => $arr['renewal_date'], 'reply_total' => $reply_total));
				$query->where('bbs_comment_no', '=', $arr['bbs_comment_no']);
				$result_comment_arr = $query->execute();


				// --------------------------------------------------
				//   スレッドのSort Date更新
				// --------------------------------------------------

				$query = DB::update('bbs_thread');
				$query->set(array('sort_date' => $arr['renewal_date']));
				$query->where('bbs_thread_no', '=', $arr['bbs_thread_no']);
				$result_thread_arr = $query->execute();


				// --------------------------------------------------
				//   コミュニティのSort Date更新
				// --------------------------------------------------

				$query = DB::update('community');
				$query->set(array('sort_date' => $arr['renewal_date']));
				$query->where('community_no', '=', $arr['community_no']);
				$result_community_arr = $query->execute();


				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------

				DB::commit_transaction();


				// --------------------------------------------------
				//   結果
				// --------------------------------------------------

				return $result_reply_arr[0];

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
	* 返信更新
	* @param integer $bbs_reply_no
	* @param array $arr 保存用配列
	* @return array
	*/
	public function update_bbs_reply($bbs_reply_no, $arr)
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

			$query = DB::update('bbs_reply');
			$query->set($arr);
			$query->where('bbs_reply_no', '=', $bbs_reply_no);
			$result_reply_arr = $query->execute();


			// --------------------------------------------------
			//   コメントのSort Date＆返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');
			$query->set(array('sort_date' => $arr['renewal_date']));
			$query->where('bbs_comment_no', '=', $arr['bbs_comment_no']);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date 更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');
			$query->set(array('sort_date' => $arr['renewal_date']));
			$query->where('bbs_thread_no', '=', $arr['bbs_thread_no']);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('community');
			$query->set(array('sort_date' => $arr['renewal_date']));
			$query->where('community_no', '=', $arr['community_no']);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return $result_reply_arr;

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
	* @param integer $bbs_comment_no
	* @param integer $bbs_reply_no
	* @param array $arr 保存用配列
	* @return array
	*/
	public function delete_bbs_reply($bbs_comment_no, $bbs_reply_no, $arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信数取得
			// --------------------------------------------------

			$query = DB::select('reply_total')->from('bbs_comment');
			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute()->current();
			$reply_total = $result_comment_arr['reply_total'] - 1;


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			$query = DB::update('bbs_reply');
			$query->set($arr);
			$query->where('bbs_reply_no', '=', $bbs_reply_no);
			$result_reply_arr = $query->execute();


			// --------------------------------------------------
			//   コメントの返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');
			$query->set(array('reply_total' => $reply_total));
			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


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




	// ------------------------------
	//    メール一斉送信
	// ------------------------------


	/**
	* メール一斉送信の情報を取得
	* @return array
	*/
	public function get_mail_all()
	{
		$query = DB::select('*')->from('mail_all');
		$query->where_open();
		$query->where('status', '=', 'on');
		$query->or_where('status', '=', 'sending');
		$query->where_close();
		$query->order_by('regi_date','asc');
		$query->limit(1);
		$query->offset(0);
		$arr = $query->execute()->current();

		return $arr;
	}


	/**
	* コミュニティの参加者、総数を取得
	* @return array
	*/
	public function get_participation_community_user($community_no, $page, $limit)
	{
		$offset = $limit * ($page - 1);

		$community_no_like = '%,' . $community_no . ',%';

		$query = DB::select('users_data.user_no', 'users_data.notification_data', 'users_login.email')->from('users_data');
		$query->join('users_login', 'LEFT');
		$query->on('users_data.user_no', '=', 'users_login.id');
		//$query->where('users_login.email', '!=', null);
		$query->where('users_data.on_off', '=', 1);
		$query->where('users_data.notification_on_off', '=', 1);
		$query->where_open();
		$query->where('users_data.participation_community', 'like', $community_no_like);
		$query->or_where('users_data.participation_community_secret', 'like', $community_no_like);
		$query->where_close();
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();

		$total = DB::count_last_query();

		return array($arr, $total);
	}


	/**
	* コミュニティとMail Allを同時に更新
	* メールを一斉送信する場合に利用
	* @return array
	*/
	public function update_community_and_mail_all($community_no, $save_commutniy_arr, $save_mail_all_arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   コミュニティ更新
			// --------------------------------------------------

			$query = DB::update('community');
			$query->set($save_commutniy_arr);
			$query->where('community_no', '=', $community_no);
			$arr = $query->execute();


			// --------------------------------------------------
			//   Mail All挿入
			// --------------------------------------------------

			$query = DB::insert('mail_all');
			$query->set($save_mail_all_arr);
			$arr = $query->execute();


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
	* Mail Allを更新
	* @return array
	*/
	public function update_mail_all($id, $save_mail_all_arr)
	{

		// --------------------------------------------------
		//   Mail All更新
		// --------------------------------------------------

		$query = DB::update('mail_all');
		$query->set($save_mail_all_arr);
		$query->where('id', '=', $id);
		$arr = $query->execute();

		return $arr;

	}


	/**
	* Mail All送信注文削除
	* @return array
	*/
	public function delete_old_mail_all()
	{

		// --------------------------------------------------
		//   90日前の日時を取得
		// --------------------------------------------------

		$original_common_date = new Original\Common\Date();
		$pre_datetime = $original_common_date->sql_format('-90 days');


		// --------------------------------------------------
		//   古いデータをまとめて削除
		// --------------------------------------------------

		$query = DB::delete('mail_all');
		$query->where('regi_date', '<', $pre_datetime);
		$arr = $query->execute();

		return $arr;

	}






}
