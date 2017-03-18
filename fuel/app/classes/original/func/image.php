<?php

namespace Original\Func;

class Image
{


	public function before()
	{



	}



	/**
	* 被らない Image ID 作成
	*
	* @return string image_id
	*/
	public function create_image_id()
	{

		// --------------------------------------------------
		//    インスタンス
		// --------------------------------------------------

		$model_image = new \Model_Image();
		$original_common_text = new \Original\Common\Text();


		// --------------------------------------------------
		//    ループ
		// --------------------------------------------------

		for ($i=0; $i < 10; $i++)
		{

			// --------------------------------------------------
			//    ID作成
			// --------------------------------------------------

			$image_id = $original_common_text->random_text_lowercase(16);


			// --------------------------------------------------
			//    データ取得
			// --------------------------------------------------

			$temp_arr = ['image_id' => $image_id];
			$db_image_arr = $model_image->select_image($temp_arr);

			if (count($db_image_arr) === 0) return $image_id;

		}


		// --------------------------------------------------
		//    エラー
		// --------------------------------------------------

		throw new Exception('Error');

	}




	/**
	* サムネイル保存
	*
	* @param array $arr
	*        string path 保存先のパス
	* @return array 処理が終わったかどうか
	*/
	public function save_thumbnail($arr)
	{

		// --------------------------------------------------
		//    変数設定
		// --------------------------------------------------

		$path = $arr['path'] ?? null;
		$resize_width = $resize_height = 128;


		// --------------------------------------------------
		//    サムネイルがアップロードされていない場合は処理停止
		// --------------------------------------------------

		if (empty($_FILES['thumbnail']) or $_FILES['thumbnail']['size'] === 0 or ! $path) return ['done' => false, 'error' => false];


		// --------------------------------------------------
		//    画像データ取得
		// --------------------------------------------------

		$image_data_arr = getimagesize($_FILES['thumbnail']['tmp_name']);
		$width = $image_data_arr[0];
		$height = $image_data_arr[1];
		$original_path = $_FILES['thumbnail']['tmp_name'];
		// \Debug::dump($path, $_FILES, $image_data_arr);


		// --------------------------------------------------
		//    設定　パス設定
		// --------------------------------------------------

		$config = array(
			'auto_process' => false,
			'path' => $path
		);

		\Upload::process($config);


		// --------------------------------------------------
		//    アップロードされたファイルチェック
		//    error 4はアップロードされたファイルがないという意味
		//    それ以外のエラーがある場合は処理停止
		// --------------------------------------------------

		foreach(\Upload::get_errors() as $key => $value)
		{
			foreach ($value['errors'] as $key2 => $value2)
			{
				if ($value2['error'] !== 4) return array('done' => false, 'error' => true);
			}
		}

		// --------------------------------------------------
		//    画像検証にひっかかった場合は処理停止
		// --------------------------------------------------

		//if ( ! \Upload::is_valid()) return ['done' => false, 'error' => true];


		// --------------------------------------------------
		//    フォルダの存在を確認してない場合は作成
		// --------------------------------------------------

		if ( ! file_exists($path)) {
			if (mkdir($path, 0755)) {

			} else {
				return array('done' => false, 'error' => true);
			}
		}


		// --------------------------------------------------
		//    Zebra_Image ライブラリ読み込み
		// --------------------------------------------------

		require_once APPPATH . 'vendor/zebraimage/Zebra_Image.php';


		// --------------------------------------------------
		//    オリジナル画像保存
		// --------------------------------------------------

		$image = new \Zebra_Image();
		$image->source_path = $original_path;
		$image->target_path = $path . 'thumbnail_original.jpg';
		$image->chmod_value = 0644;
		$image->jpeg_quality = 75;
		$image->resize(0, 0, ZEBRA_IMAGE_CROP_CENTER);
// \Debug::dump($image);

		// --------------------------------------------------
		//    リサイズ画像保存
		// --------------------------------------------------

		$image = new \Zebra_Image();
		$image->source_path = $original_path;
		$image->target_path = $path . 'thumbnail.jpg';
		$image->chmod_value = 0644;
		$image->jpeg_quality = 75;
		$image->preserve_aspect_ratio = true;
		$image->enlarge_smaller_images = true;
		$image->preserve_time = true;
		$image->resize($resize_width, $resize_height, ZEBRA_IMAGE_CROP_CENTER);


		// --------------------------------------------------
		//    処理完了
		// --------------------------------------------------

		return ['done' => true, 'error' => false];

	}



	/**
	* サムネイル削除
	*
	* @param array $arr
	*        string path 保存先のパス
	* @return array エラーが起きたかどうか
	*/
	public function delete_thumbnail($arr)
	{

		// --------------------------------------------------
		//    変数設定
		// --------------------------------------------------

		$path = $arr['path'] ?? null;


		// --------------------------------------------------
		//    pathがない場合は処理停止
		// --------------------------------------------------

		if ( ! $path) return ['done' => false];


		// --------------------------------------------------
		//    削除
		// --------------------------------------------------

		$path_1 = $path . 'thumbnail_original.jpg';
		$path_2 = $path . 'thumbnail.jpg';

		if (file_exists($path_1)) unlink($path_1);
		if (file_exists($path_2)) unlink($path_2);

	}




	/**
	* 画像保存　データベースの更新含む
	*
	* @param array $arr 設定配列
	*        string path 保存先のパス
	*        boolean delete_original_image オリジナルを削除する場合はTrue
	*        string limit 決まった数以上の画像がアップロードされた場合はエラーにする
	* @return array 画像のサイズが記述された配列
	*/
	public function save_images($arr)
	{

		// --------------------------------------------------
		//    アップロードされた画像がない場合、処理停止
		// --------------------------------------------------

		if (count($_FILES) === 0) return array('done' => false, 'error' => false);


		// --------------------------------------------------
		//    インスタンス
		// --------------------------------------------------

		$model_image = new \Model_Image();
		$original_common_text = new \Original\Common\Text();
		$original_common_date = new \Original\Common\Date();


		// --------------------------------------------------
		//    変数設定
		// --------------------------------------------------

		$path = DOCROOT . 'assets/img/u/';
		//$path = DOCROOT . 'assets/img/test/';

		$limit = $arr['limit'] ?? 5;

		$admin = (\Auth::member(100)) ? 1 : null;
		$approval = ($admin) ? 1 : null;

		$type = $arr['type'];
		$game_no = $arr['game_no'] ?? null;
		$community_no = $arr['community_no'] ?? null;

		$max_width = $arr['max_width'] ?? 1280;
		$max_height = $arr['max_height'] ?? 720;
		$max_width_s = $arr['max_width_s'] ?? 640;
		$max_height_s = $arr['max_height_s'] ?? 360;
		$quality = $arr['quality'] ?? 75;

		$datetime_now = $original_common_date->sql_format();
		$datetime_past = $original_common_date->sql_format('-30 minutes');

		$save_insert_arr = [];
		$save_update_arr = [];
		$authority_update = false;
		$update_deleted_record = false;



		// --------------------------------------------------
		//    設定
		// --------------------------------------------------

		$config = array(
			'auto_process' => false,
			'path' => $path
		);

		// 管理者の場合、最大アップロードサイズを10MBにする
		if (\Auth::member(100)) $config['max_size'] = 10485760;

		\Upload::process($config);


		// --------------------------------------------------
		//    アップロードされたファイルチェック
		//    error 4はアップロードされたファイルがないという意味
		//    それ以外のエラーがある場合は処理停止
		// --------------------------------------------------

		foreach(\Upload::get_errors() as $key => $value)
		{
			foreach ($value['errors'] as $key2 => $value2)
			{
				if ($value2['error'] !== 4) return array('done' => false, 'error' => true);
			}
		}


		// --------------------------------------------------
		//    アップロードされた画像数チェック　多い場合は処理停止
		// --------------------------------------------------

		$get_files_arr = \Upload::get_files();

		if (count($get_files_arr) > $limit) return array('done' => false, 'error' => true);
//\Debug::dump($get_files_arr, \Upload::get_errors());


		// --------------------------------------------------
		//    Zebra_Image ライブラリ読み込み
		// --------------------------------------------------

		require_once APPPATH . 'vendor/zebraimage/Zebra_Image.php';


		// ---------------------------------------------
		//    画像保存
		// ---------------------------------------------

		foreach ($get_files_arr as $key => $value)
		{


			// ---------------------------------------------
			//    フィールドにimage_を含んでいる場合のみ処理
			// ---------------------------------------------

			if (strpos($value['field'], 'image_') === false) continue;


			// ---------------------------------------------
			//    フィールドからimage_idを取得する
			// ---------------------------------------------

			$image_id = explode('image_', $value['field'])[1];


			// ---------------------------------------------
			//    編集　image_idが16文字の場合は編集とみなす
			// ---------------------------------------------

			if (mb_strlen($image_id) === 16)
			{

				// ---------------------------------------------
				//    データ取得
				// ---------------------------------------------

				$temp_arr = $model_image->select_image(array('image_id' => $image_id));


				// ---------------------------------------------
				//    配列がない場合、処理停止
				// ---------------------------------------------

				if (count($temp_arr) === 0) return ['done' => false, 'error' => true];


				// ---------------------------------------------
				//    データ代入
				// ---------------------------------------------

				$db_image_arr = $temp_arr[0];
				$image_no = $db_image_arr['image_no'];


				// ---------------------------------------------
				//    権限チェック
				// ---------------------------------------------

				if ($type === 'hero_game')
				{
					if ($admin) $authority_update = true;
				}
				else if ($type === 'hero_community')
				{
					if ($db_image_arr['community_no'] and $db_image_arr['community_no'] == $community_no) $authority_update = true;
				}


				// ---------------------------------------------
				//    権限がない場合処理停止
				// ---------------------------------------------

				if ( ! $authority_update) return ['done' => false, 'error' => true];

			}

			// ---------------------------------------------
			//    新規作成
			// ---------------------------------------------

			else
			{

				// ---------------------------------------------
				//    コミュニティのヒーローイメージは1つしかアップロードできない
				// ---------------------------------------------

				if ($type === 'hero_community')
				{
					$db_image_arr = $model_image->select_header_hero_image_community_edit(['community_no' => $community_no]);
					if (count($db_image_arr) > 1) return ['done' => false, 'error' => true];
				}


				// ---------------------------------------------
				//    削除されたレコードがある場合はそちらを更新する
				// ---------------------------------------------

				$image_no = $model_image->select_deleted_image_no();
				if ($image_no) $update_deleted_record = true;


				// ---------------------------------------------
				//    Image ID 作成
				// ---------------------------------------------

				$image_id = $this->create_image_id();

			}



			// ----------------------------------------
			//    Width Height 取得
			// ----------------------------------------

			$original_size_arr = getimagesize($value['file']);
			$original_width = $original_size_arr[0];
			$original_height = $original_size_arr[1];


			// ----------------------------------------
			//    Width Height が大きすぎる場合は制限する
			// ----------------------------------------

			if ($original_width > $max_width or $original_height > $max_height)
			{
				if ($original_width > $original_height)
				{
					$image_width = $max_width;
					$image_height = (int) round(($image_width / $original_width) * $original_height);
				}
				else
				{
					$image_height = $max_height;
					$image_width = (int) round(($image_height / $original_height) * $original_width);
				}
			}
			else
			{
				$image_width = $original_width;
				$image_height = $original_height;
			}


			// ----------------------------------------
			//    通常サイズ保存
			// ----------------------------------------

			$image = new \Zebra_Image();
			$image->source_path = $value['file'];
			$image->target_path = $path . $image_id . '.jpg';
			$image->chmod_value = 0644;
			$image->jpeg_quality = $quality;
			$image->resize($image_width, $image_height, ZEBRA_IMAGE_CROP_CENTER);


			// ----------------------------------------
			//    Width Height スモールサイズ
			// ----------------------------------------

			if ($original_width > $max_width_s or $original_height > $max_height_s)
			{
				if ($original_width > $original_height)
				{
					$image_width_s = $max_width_s;
					$image_height_s = (int) round(($image_width_s / $original_width) * $original_height);
				}
				else
				{
					$image_height_s = $max_height_s;
					$image_width_s = (int) round(($image_height_s / $original_height) * $original_width);
				}
			}
			else
			{
				$image_width_s = $original_width;
				$image_height_s = $original_height;
			}


			// ----------------------------------------
			//    スモールサイズの画像保存
			// ----------------------------------------

			$image = new \Zebra_Image();
			$image->source_path = $value['file'];
			$image->target_path = $path . $image_id . '_s.jpg';
			$image->chmod_value = 0644;
			$image->jpeg_quality = $quality;
			$image->resize($image_width_s, $image_height_s, ZEBRA_IMAGE_CROP_CENTER);


			// ----------------------------------------
			//    データベースに保存する画像情報
			// ----------------------------------------

			if ($authority_update)
			{
				$temp_arr = array(
					'image_no' => $image_no,
					'image_id' => $image_id,
					'on_off' => 1,
					'renewal_date' => $datetime_now,
					'width' => $image_width,
					'height' => $image_height,
					'width_s' => $image_width_s,
					'height_s' => $image_height_s
				);

				array_push($save_update_arr, $temp_arr);
			}

			else if ($update_deleted_record)
			{
				$temp_arr = array(
					'image_no' => $image_no,
					'image_id' => $image_id,
					'on_off' => 1,
					'approval' => $approval,
					'regi_date' => $datetime_now,
					'renewal_date' => $datetime_now,
					'type' => $type,
					'admin' => $admin,
					'user_no' => USER_NO,
					'game_no' => $game_no,
					'community_no' => $community_no,
					'width' => $image_width,
					'height' => $image_height,
					'width_s' => $image_width_s,
					'height_s' => $image_height_s,
					'host' => HOST,
					'user_agent' => USER_AGENT
				);

				array_push($save_update_arr, $temp_arr);
			}

			else
			{
				$temp_arr = array(
					'image_id' => $image_id,
					'approval' => $approval,
					'regi_date' => $datetime_now,
					'renewal_date' => $datetime_now,
					'type' => $type,
					'admin' => $admin,
					'user_no' => USER_NO,
					'game_no' => $game_no,
					'community_no' => $community_no,
					'width' => $image_width,
					'height' => $image_height,
					'width_s' => $image_width_s,
					'height_s' => $image_height_s,
					'host' => HOST,
					'user_agent' => USER_AGENT
				);

				array_push($save_insert_arr, $temp_arr);
			}

		}



		// --------------------------------------------------
		//    データベース保存
		// --------------------------------------------------

		if (count($save_insert_arr) > 0)
		{
			$model_image->insert_image($save_insert_arr);
		}

		if (count($save_update_arr) > 0)
		{
			$model_image->update_image($save_update_arr);
		}


		// \Debug::$js_toggle_open = true;
		// \Debug::dump($_FILES, \Upload::get_files(), \Upload::get_errors());
		// \Debug::dump($save_insert_arr, $save_update_arr);
		// exit();


		return ['done' => true, 'error' => false];

	}




	/**
	* 画像削除　データベースの更新含む
	*
	* @param array $arr 設定配列
	*        string path 保存先のパス
	*        string delete_image_ids gk42bozep97dcfmj,w8kcbs2qzpe2pfkc
	* @return array エラーかどうか
	*/
	public function delete_images($arr)
	{

		// --------------------------------------------------
		//    インスタンス
		// --------------------------------------------------

		$model_image = new \Model_Image();
		$original_common_text = new \Original\Common\Text();
		$original_common_date = new \Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		$datetime_past = $original_common_date->sql_format("-30 minutes");


		// --------------------------------------------------
		//    変数設定
		// --------------------------------------------------

		$path = DOCROOT . 'assets/img/u/';
		$admin = (\Auth::member(100)) ? 1 : null;
		$type = $arr['type'];
		$game_no = $arr['game_no'] ?? null;
		$community_no = $arr['community_no'] ?? null;
		$delete_image_ids_arr = explode(',', $arr['delete_image_ids']);

		$authority_update = false;
		$save_update_arr = [];


		// --------------------------------------------------
		//    削除
		// --------------------------------------------------

		foreach ($delete_image_ids_arr as $key => $value)
		{

			// ---------------------------------------------
			//    データ取得
			// ---------------------------------------------

			$db_image_arr = $model_image->select_image(array('image_id' => $value))[0];


			// --------------------------------------------------
			//    配列がない場合、処理停止
			// --------------------------------------------------

			if (count($db_image_arr) === 0) exit();


			// ---------------------------------------------
			//    権限チェック
			// ---------------------------------------------

			if ($type === 'hero_game')
			{
				if ($admin) $authority_update = true;
			}
			else if ($type === 'hero_community')
			{
				if ($db_image_arr['community_no'] and $db_image_arr['community_no'] == $community_no) $authority_update = true;
			}


			// ---------------------------------------------
			//    権限がない場合処理停止
			// ---------------------------------------------

			if ( ! $authority_update) return ['done' => false, 'error' => true];


			// --------------------------------------------------
			//    削除
			// --------------------------------------------------
			// \Debug::dump($value);
			// exit();
			$path_1 = $path . $value . '.jpg';
			$path_2 = $path . $value . '_s.jpg';
			if (file_exists($path_1)) unlink($path_1);
			if (file_exists($path_2)) unlink($path_2);


			// --------------------------------------------------
			//    データベース
			// --------------------------------------------------

			$temp_arr = array(
				'image_no' => $db_image_arr['image_no'],
				'on_off' => null
			);

			array_push($save_update_arr, $temp_arr);
			//\Debug::dump($db_image_arr, $type, $authority_update, $save_update_arr);

		}


		// --------------------------------------------------
		//    データベース保存
		// --------------------------------------------------

		//\Debug::dump($save_update_arr);

		if (count($save_update_arr) > 0)
		{
			$model_image->update_image($save_update_arr);
		}


		return ['done' => true, 'error' => false];

	}

}
