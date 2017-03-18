<?php

class Model_Amazon extends Model_Crud
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------



	/**
	* カード　サムネイル用
	* @param array $arr
	* @return array
	*/
	public function select_card_thumbnail($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 30;
		$datetime_past = $arr['datetime_past'] ?? null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(DB::expr("'amazon' as 'type'"), 'renewal_date', 'asin', 'title', 'discount_rate', 'image_id')->from('amazon');
		$query->where('on_off', '=', 1);
		$query->where('image_id', '!=', null);
		$query->where('renewal_date', '>', $datetime_past);
		$query->order_by(DB::expr('RAND()'));
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();

		if (count($arr) < $limit)
		{
			$query = DB::select(DB::expr("'amazon' as 'type'"), 'renewal_date', 'asin', 'title', 'discount_rate', 'image_id')->from('amazon');
			$query->where('on_off', '=', 1);
			$query->where('image_id', '!=', null);
			$query->order_by(DB::expr('RAND()'));
			$query->limit($limit);
			$query->offset($offset);
			$arr = $query->execute()->as_array();
		}


		return $arr;

	}



	/**
	* アマゾンスライド広告用　旧型の広告 2016/11/05
	* @param array $arr
	* @return array
	*/
	public function get_ad_amazon_slide($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		$limit = (isset($arr['limit'])) ? $arr['limit'] : null;
		$datetime_past = (isset($arr['datetime_past'])) ? $arr['datetime_past'] : null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('renewal_date', 'asin', 'title', 'price', 'image_id')->from('amazon');
		$query->where('on_off', '=', 1);
		if ($datetime_past) $query->where('renewal_date', '>', $datetime_past);
		$query->order_by(DB::expr('RAND()'));
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();

		if (count($arr) < 20)
		{
			$query = DB::select('renewal_date', 'asin', 'title', 'price', 'image_id')->from('amazon');
			$query->where('on_off', '=', 1);
			$query->order_by(DB::expr('RAND()'));
			$query->limit($limit);
			$query->offset($offset);
			$arr = $query->execute()->as_array();
		}


		return $arr;

	}



	/**
	* すでに登録済みかどうかのチェック用データ取得
	* @param array $arr
	* @return array
	*/
	public function check_data($arr)
	{

		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('asin')->from('amazon');

		$query->where('on_off', '=', 1);
		$query->where('asin', 'in', $arr);
		//$query->where('ad_default', '=', 1);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* データ挿入
	* @param array $arr 配列
	* @return array
	*/
	public function insert_data($arr)
	{

		//$language = 'ja';

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$insert_arr = $arr['insert_arr'];
		//\Debug::dump($insert_arr);


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();



			// --------------------------------------------------
			//   挿入
			// --------------------------------------------------

			$query = DB::insert('amazon');
			$query->columns(array('regi_date', 'renewal_date', 'asin', 'title', 'list_price', 'price', 'discount_rate', 'sales_rank', 'image_id'));

			foreach ($insert_arr as $key => $value)
			{
				$query->values($value);
			}

			$result_arr = $query->execute();



			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return $result_arr;
			//return array('bbs_thread_no' => $result_thread_arr[0], 'error' => false);

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
	* データ更新
	* @param array $arr 配列
	* @return array
	*/
	public function update_data($arr)
	{

		//$language = 'ja';

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$update_arr = $arr['update_arr'];
		//\Debug::dump($insert_arr);


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();



			// --------------------------------------------------
			//   更新
			// --------------------------------------------------

			foreach ($update_arr as $key => $value)
			{
				$query = DB::update('amazon');
				$query->set($value);
				$query->where('asin', '=', $value['asin']);
				$result_arr = $query->execute();
			}


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return $result_arr;
			//return array('bbs_thread_no' => $result_thread_arr[0], 'error' => false);

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
