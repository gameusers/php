<?php

class Model_Wiki extends Model_Crud
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------



	/**
	* Wikiデータ取得
	* @param array $arr
	* @return array
	*/
	public function get_wiki($arr)
	{

		// --------------------------------------------------
		//   値セット
		// --------------------------------------------------

		$limit = (isset($arr['limit'])) ? $arr['limit'] : 1;
		$page = (isset($arr['page'])) ? $arr['page'] : 1;
		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from('wiki');

		$query->where('on_off', '=', 1);

		if (isset($arr['wiki_no'])) $query->where('wiki_no', '=', $arr['wiki_no']);
		if (isset($arr['wiki_id'])) $query->where('wiki_id', '=', $arr['wiki_id']);
		if (isset($arr['user_no'])) $query->where('user_no', '=', $arr['user_no']);
		if (isset($arr['wiki_name'])) $query->where('wiki_name', '=', $arr['wiki_name']);
		if (isset($arr['wiki_comment'])) $query->where('wiki_comment', '=', $arr['wiki_comment']);

		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);

		$data_arr = $query->execute()->as_array();
		// echo DB::last_query();

		// --------------------------------------------------
		//   レコード行数取得
		// --------------------------------------------------

		$total = (isset($arr['get_total'])) ? DB::count_last_query() : null;


		return array('data_arr' => $data_arr, 'total' => $total);

	}





	/**
	* Wiki ID 重複チェック
	* @param array $arr
	* @return array
	*/
	public function check_duplication_wiki_id($arr)
	{

		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('wiki_no')->from('wiki');

		$query->where('on_off', '=', 1);

		$query->where('wiki_id', '=', $arr['wiki_id']);
		//$query->where('user_no', '!=', USER_NO);

		$query->limit(1);
		$query->offset(0);

		$db_duplication_arr = $query->execute()->as_array();


		//\Debug::dump($db_duplication_arr);

		return (isset($db_duplication_arr[0]['wiki_no'])) ? true : false;

	}





	/**
	* Wiki挿入
	* @param array $arr
	* @return array
	*/
	public function insert_wiki($arr)
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

			$query = DB::insert('wiki');
			$query->set($arr);
			$result_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('wiki_no' => $result_arr[0], 'error' => false);

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return array('error' => true);

	}



	/**
	* 広告更新
	* @param array $arr
	* @return array
	*/
	public function update_wiki($arr)
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

			$query = DB::update('wiki');
			$query->set($arr);
			$query->where('wiki_no', '=', $arr['wiki_no']);
			$result_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('error' => false);

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return array('error' => true);

	}



}
