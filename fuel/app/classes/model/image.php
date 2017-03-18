<?php

class Model_Image extends Model_Crud
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------



	/**
	* Image 取得
	* @param array $arr
	* @return array
	*/
	public function select_image($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$image_id = $arr['image_id'];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from('image');
		// $query->where('on_off', '=', 1); // これは不必要、この条件をonにするとエラーが起こるのでこのまま
		$query->where('image_id', '=', $image_id);
		$result_arr = $query->execute()->as_array();

// \Debug::dump($image_id, $result_arr);
		return $result_arr;

	}



	/**
	* 削除済みの Image No 取得
	* @return array
	*/
	public function select_deleted_image_no()
	{

		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('image_no')->from('image');
		$query->where('on_off', '=', null);
		$query->limit(1);
		$query->offset(0);
		$result_arr = $query->execute()->current();

		$image_no = $result_arr['image_no'] ?? null;


		return $image_no;

	}



	/**
	* ヘッダー用にヒーローイメージを取得する
	* @param array $arr
	* @return array
	*/
	public function select_header_hero_image_game($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'] ?? 'ja';
		$game_no_arr = $arr['game_no_arr'] ?? null;


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('image.image_id', 'image.renewal_date', 'image.game_no', array('game_data.id', 'game_id'), array('game_data.name_' . $language, 'game_name'))->from('image');
		$query->join('game_data', 'LEFT');
		$query->on('image.game_no', '=', 'game_data.game_no');

		$query->where('image.on_off', '=', 1);
		$query->where('image.type', '=', 'hero_game');
		//if ($game_no) $query->where('image.game_no', '=', $game_no);
		if ($game_no_arr) $query->where('image.game_no', 'in', $game_no_arr);

		$query->order_by(DB::expr('RAND()'));
		$query->limit(1);
		$query->offset(0);
		$result_arr = $query->execute()->as_array();

		$image_arr = $result_arr[0] ?? null;

		return $image_arr;

	}



	/**
	* ヘッダー用にヒーローイメージを取得する　編集用
	* @param array $arr
	* @return array
	*/
	public function select_header_hero_image_game_edit($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$game_no = $arr['game_no'];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('image_id', 'on_off')->from('image');
		//$query->where('on_off', '=', 1);
		$query->where('game_no', '=', $game_no);
		$query->where('type', '=', 'hero_game');
		$result_arr = $query->execute()->as_array();

		//$image_arr = $result_arr;

		return $result_arr;

	}




	/**
	* ヘッダー用にヒーローイメージを取得する
	* @param array $arr
	* @return array
	*/
	public function select_header_hero_image_community($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$community_no = $arr['community_no'];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('image_id', 'renewal_date')->from('image');

		$query->where('on_off', '=', 1);
		$query->where('type', '=', 'hero_community');
		$query->where('community_no', '=', $community_no);
		$query->limit(1);
		$query->offset(0);
		$result_arr = $query->execute()->as_array();

		$image_arr = $result_arr[0] ?? null;

		return $image_arr;

	}



	/**
	* ヘッダー用にヒーローイメージ（コミュニティ）を取得する　編集用
	* @param array $arr
	* @return array
	*/
	public function select_header_hero_image_community_edit($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$community_no = $arr['community_no'];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from('image');
		// $query->where('on_off', '=', 1);
		$query->where('community_no', '=', $community_no);
		$query->where('type', '=', 'hero_community');
		$result_arr = $query->execute()->as_array();

		return $result_arr;

	}






	/**
	* Image 挿入　入れ子の配列の形で $arr を作ること
	* @param array $arr
	* @return array
	*/
	public function insert_image($arr)
	{

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
				$query = DB::insert('image');
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

		}

		return array('error' => true);

	}




	/**
	* Image 更新　入れ子の配列の形で $arr を作ること
	* @param array $arr
	* @return array
	*/
	public function update_image($arr)
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
				$query = DB::update('image');
				$query->set($value);
				if (isset($value['image_no'])) $query->where('image_no', '=', $value['image_no']);
				if (isset($value['image_id'])) $query->where('image_id', '=', $value['image_id']);
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


}
