<?php

class Model_Sns extends Model_Crud
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------



	/**
	* send sns 取得
	* @param array $arr
	* @return array
	*/
	public function select_send_sns_for_cron($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$on_off = $arr['on_off'] ?? null;
		$approval = $arr['approval'] ?? null;
		$limit = $arr['limit'] ?? 1;
		$page = $arr['page'] ?? 1;
		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from('send_sns');

		if ($on_off) $query->where('on_off', '=', $on_off);
		if ($approval) $query->where('approval', '=', $approval);

		$query->order_by('regi_date','asc');
		$query->limit($limit);
		$query->offset($offset);

		$result_arr = $query->execute()->current();

// \Debug::dump($image_id, $result_arr);
		return $result_arr;

	}






	/**
	* Image 挿入　入れ子の配列の形で $arr を作ること
	* @param array $arr
	* @return array
	*/
	public function insert_send_sns($arr)
	{

// \Debug::dump($arr);
// exit();

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   挿入
			// --------------------------------------------------

			foreach ($arr as $key => $value)
			{
				$query = DB::insert('send_sns');
				$query->set($value);
				$result_arr = $query->execute();
			}


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------
			//\Debug::dump($result_arr);
			return array('error' => false);

		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

			// \Debug::dump($e);

		}

		return array('error' => true);

	}




	/**
	* Image 更新　入れ子の配列の形で $arr を作ること
	* @param array $arr
	* @return array
	*/
	public function update_send_sns($arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   更新
			// --------------------------------------------------

			foreach ($arr as $key => $value)
			{
				$query = DB::update('send_sns');
				$query->set($value);
				if (isset($value['send_sns_no'])) $query->where('send_sns_no', '=', $value['send_sns_no']);
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

			//\Debug::dump($e);

		}

		return array('error' => true);

	}




	/**
	* send sns 削除
	* @param array $arr 配列
	* @return array
	*/
	public function delete_send_sns($arr)
	{

		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   削除
			// --------------------------------------------------

			foreach ($arr as $key => $value)
			{
				$query = DB::delete('send_sns');
				$query->where('send_sns_id', '=', $value['send_sns_id']);
				$query->execute();
			}


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
