<?php

class Model_Advertisement extends Model_Crud
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------



	/**
	* 広告取得
	* @param array $arr
	* @return array
	*/
	public function get_advertisement($arr)
	{

		// --------------------------------------------------
		//   値設定
		// --------------------------------------------------

		$limit = $arr['limit'];
		$offset = $limit * ($arr['page'] - 1);
		//\Debug::dump($arr, $limit, $offset);

		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from('advertisement');

		$query->where('on_off', '=', 1);

		if (isset($arr['advertisement_no'])) $query->where('advertisement_no', '=', $arr['advertisement_no']);

		if (isset($arr['approval_null']))
		{
			$query->where('approval', '=', null);
		}
		else if (isset($arr['approval']))
		{
			$query->where('approval', '=', 1);
		}

		if (isset($arr['user_no'])) $query->where('user_no', '=', $arr['user_no']);
		if (isset($arr['name'])) $query->where('name', '=', $arr['name']);

		$query->limit($limit);
		$query->offset($offset);

		$data_arr = $query->execute()->as_array();


		// --------------------------------------------------
		//   レコード行数取得
		// --------------------------------------------------

		$total = (isset($arr['get_total'])) ? DB::count_last_query() : null;


		return array('data_arr' => $data_arr, 'total' => $total);

	}


	/**
	* 広告取得　管理者用
	* @param array $arr
	* @return array
	*/
	public function get_advertisement_admin($arr)
	{

		// --------------------------------------------------
		//   値設定
		// --------------------------------------------------

		$limit = $arr['limit'];
		$offset = $limit * ($arr['page'] - 1);
		//\Debug::dump($arr, $limit, $offset);

		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from('advertisement');

		$query->where('administration', '=', null);

		$query->order_by('renewal_date','desc');
		$query->order_by('approval','asc');
		$query->limit($limit);
		$query->offset($offset);

		$data_arr = $query->execute()->as_array();


		// --------------------------------------------------
		//   レコード行数取得
		// --------------------------------------------------

		$total = (isset($arr['get_total'])) ? DB::count_last_query() : null;


		return array('data_arr' => $data_arr, 'total' => $total);

	}


	/**
	* 広告取得　1件だけ
	* @param array $arr
	* @return array
	*/
	public function get_advertisement_current($arr)
	{

		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from('advertisement');

		$query->where('on_off', '=', 1);

		if (isset($arr['advertisement_no'])) $query->where('advertisement_no', '=', $arr['advertisement_no']);
		if (isset($arr['not_advertisement_no'])) $query->where('advertisement_no', '!=', $arr['not_advertisement_no']);
		if (isset($arr['user_no'])) $query->where('user_no', '=', $arr['user_no']);
		if (isset($arr['name'])) $query->where('name', '=', $arr['name']);

		$query->limit(1);
		$query->offset(0);

		$result_arr = $query->execute()->current();

		//echo DB::last_query();
		return $result_arr;

	}




	/**
	* 広告取得　Wikiのデフォルト広告取得
	* @param array $arr
	* @return array
	*/
	public function get_advertisement_wiki_admin_default($arr)
	{

		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('ad_default', 'name', 'code', 'code_sp')->from('advertisement');

		$query->where('on_off', '=', 1);
		$query->where('administration', '=', 1);

		$query->where_open();
		$query->where('ad_default', '=', 'wiki_1');
		$query->or_where('ad_default', '=', 'wiki_2');
		$query->or_where('ad_default', '=', 'wiki_3');
		$query->where_close();

		$query->limit(3);
		$query->offset(0);

		$result_arr = $query->execute()->as_array('ad_default');

		//echo DB::last_query();
		return $result_arr;

	}




	/**
	* 広告取得　Wikiのユーザー作成デフォルト広告取得
	* @param array $arr
	* @return array
	*/
	public function get_advertisement_wiki_user_default($arr)
	{

		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from('advertisement');

		$query->where('on_off', '=', 1);
		$query->where('user_no', '=', $arr['user_no']);

		$query->where_open();
		$query->where('approval', '!=', 2);
		$query->or_where('approval', '=', null);
		$query->where_close();

		// $query->where_open();
//
		// $query->where_open();
		// $query->where('ad_default', '=', 'wiki_1');
		// $query->or_where('ad_default', '=', 'wiki_2');
		// $query->where_close();

		if (count($arr['ad_name_arr']) > 0)
		{
			// $query->or_where_open();
//
			// foreach ($arr['ad_name_arr'] as $key => $value)
			// {
				// if ($key === 0)
				// {
					// $query->where('name', '=', $value);
				// }
				// else
				// {
					// $query->or_where('name', '=', $value);
				// }
			// }
//
			// $query->where_close();


			$query->where('name', 'in', $arr['ad_name_arr']);

		}





		//$query->where_close();

		$query->limit(10);
		$query->offset(0);

		$result_arr = $query->execute()->as_array();

		//echo DB::last_query();
		return $result_arr;

	}





	/**
	* 広告挿入
	* @param array $arr
	* @return array
	*/
	public function insert_advertisement($arr)
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

			$query = DB::insert('advertisement');
			$query->set($arr);
			$result_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('advertisement_no' => $result_arr[0], 'error' => false);

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
	public function update_advertisement($arr)
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

			$query = DB::update('advertisement');
			$query->set($arr);
			$query->where('advertisement_no', '=', $arr['advertisement_no']);
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




	/**
	* 広告更新
	* @param array $arr
	* @return array
	*/
	public function change_ad_default($arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();



			// --------------------------------------------------
			//   同じad_defaultがあるかチェック
			// --------------------------------------------------

			$query = DB::select('advertisement_no')->from('advertisement');

			$query->where('on_off', '=', 1);
			$query->where('user_no', '=', USER_NO);
			$query->where('ad_default', '=', $arr['ad_default']);
			$query->limit(1);
			$query->offset(0);

			$db_advertisement_arr = $query->execute()->as_array();

			//\Debug::dump($db_advertisement_arr);


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			if (isset($db_advertisement_arr[0]['advertisement_no']))
			{
				$query = DB::update('advertisement');
				$query->set(array('ad_default' => null));
				$query->where('advertisement_no', '=', $db_advertisement_arr[0]['advertisement_no']);
				$result_arr = $query->execute();
			}



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
