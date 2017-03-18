<?php

namespace Original\Func;

class Common
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
	* 画像をまとめて削除する
	*
	* @param string $path パス
	* @param string $name 画像の名前
	* @param array $number_arr 番号
	*/
	public function image_delete($path, $name, $number_arr = array())
	{

		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$original_common_image = new \Original\Common\Image();


		// --------------------------------------------------
		//   処理
		// --------------------------------------------------

		foreach ($number_arr as $key => $value)
		{
			$path_1 = $path . $name . $value . '_original.jpg';
			$path_2 = $path . $name . $value . '.jpg';

			$original_common_image->delete($path_1);
			$original_common_image->delete($path_2);
		}

	}


	/**
	* 画像保存
	*
	* @param string $path パス
	* @param string $name 画像の名前
	* @param array $delete_original オリジナルを削除する場合はTrue
	* @return array 画像のサイズが記述された配列
	*/
	public function zebra_image_save($path, $name, $delete_original, $limit)
	{

		// --------------------------------------------------
		//    デフォルトの値
		// --------------------------------------------------

		$error = 'upload_image_error';


		// --------------------------------------------------
		//    アップロードされた画像数チェック
		// --------------------------------------------------

		$files_count = 0;

		foreach ($_FILES as $key => $value)
		{
			if ($value['size'] > 0) $files_count++;
		}

		//echo "<br>files_count";
		//var_dump($files_count);


		if ($files_count <= $limit)
		{

			// ------------------------------
			//    パス設定
			// ------------------------------

			$config = array(
				'path' => $path
			);

			\Upload::process($config);


			// ------------------------------
			//    画像検証
			// ------------------------------

			if (\Upload::is_valid())
			{

				//echo "Upload::get_files()";
				//var_dump(\Upload::get_files());

				// リミット以上の画像がアップロードされた場合、中止
				if (count(\Upload::get_files()) > $limit) return false;

				//画像を一旦保存
				\Upload::save();

				// Zebra_Image ライブラリ読み込み
				require APPPATH . 'vendor/zebraimage/Zebra_Image.php';

				foreach (\Upload::get_files() as $key => $value)
				{

					// 画像Noをフィールド名から取得
					$image_number = mb_substr($value['field'], -1, 1);

					//echo "key, value, image_number";
					//var_dump($key, $value, $image_number);
					//exit();

					// ------------------------------
					//    Zebra_Image
					// ------------------------------

					// ----- オリジナル保存 -----

					$image = new \Zebra_Image();
					$image->source_path = $value['saved_to'] . $value['saved_as'];
					$image->target_path = $path . $name . 'original_' . $image_number . '.jpg';
					$image->jpeg_quality = 75;
					$image->resize(0, 0, ZEBRA_IMAGE_CROP_CENTER);

					$original_width = $image->source_width;
					$original_height = $image->source_height;

					// サイズが大きすぎる場合はサイズを固定する
					if ($original_width > 1000 or $original_height > 1000)
					{
						if ($original_width > $original_height)
						{
							$image_width = 1000;
							$image_height = (int) round(($image_width / $original_width) * $original_height);
						}
						else
						{
							$image_height = 1000;
							$image_width = (int) round(($image_height / $original_height) * $original_width);
						}
					}
					else
					{
						$image_width = $original_width;
						$image_height = $original_height;
					}

					// オリジナル削除
					if ($delete_original and file_exists($image->target_path)) unlink($image->target_path);

					//var_dump($image);


					// ----- リサイズ画像保存 -----

					$image = new \Zebra_Image();
					$image->source_path = $value['saved_to'] . $value['saved_as'];
					$image->target_path = $path . $name . $image_number . '.jpg';
					$image->jpeg_quality = 75;
					$image->preserve_aspect_ratio = true;
					$image->enlarge_smaller_images = true;
					$image->preserve_time = true;
					$image->resize($image_width, $image_height, ZEBRA_IMAGE_CROP_CENTER);
					//var_dump($image);



					// 元画像削除
					if (file_exists($value['saved_to'] . $value['saved_as'])) unlink($value['saved_to'] . $value['saved_as']);


					// データベースに保存する画像情報
					$width_height_arr[$image_number]['width'] = $image_width;
					$width_height_arr[$image_number]['height'] = $image_height;

				}

			}

		}

		// 返す値
		if (isset($width_height_arr))
		{
			$result = $width_height_arr;
		}
		else
		{
			$result = $error;
		}

		return $result;

	}



	/**
	* 画像保存　Version 2　<input type="file" name="image_1">　inputの名前を画像名にする
	* サムネイルの場合は、inputのnameにthumbnailという文字を含めること
	*
	* @param string $path パス
	* @param array $image_name_arr 画像名を決まったものに制限する
	* @param boolean $delete_original オリジナルを削除する場合はTrue
	* @param integer $limit 決まった数以上の画像がアップロードされた場合はエラーにする
	* @return array 画像のサイズが記述された配列
	*/
	public function zebra_image_save2($path, $image_name_arr, $delete_original, $limit)
	{

		// --------------------------------------------------
		//    デフォルトの値
		// --------------------------------------------------

		$error = false;
		$size_arr = null;


		// --------------------------------------------------
		//    アップロードされた画像数チェック
		// --------------------------------------------------

		$image_count = 0;

		foreach ($_FILES as $key => $value)
		{
			if ($value['size'] > 0) $image_count++;

			if ( ! in_array($key, $image_name_arr)) return array('image_count' => $image_count, 'size_arr' => $size_arr, 'error' => true);
		}

		//echo "<br>image_count";
		//var_dump($image_count);
		//exit();

		if ($image_count != 0 and $image_count <= $limit)
		{

			// ------------------------------
			//    パス設定
			// ------------------------------

			$config = array(
				'auto_process' => false,
				'path' => $path
			);

			\Upload::process($config);


			// ------------------------------
			//    画像検証
			// ------------------------------

			if (\Upload::is_valid())
			{

				//画像を一旦保存
				\Upload::save();

				// Zebra_Image ライブラリ読み込み
				require APPPATH . 'vendor/zebraimage/Zebra_Image.php';


				foreach (\Upload::get_files() as $key => $value)
				{

					//echo "key, value";
					//var_dump($key, $value);
					//exit();

					// ------------------------------
					//    Zebra_Image
					// ------------------------------

					// ----- オリジナル保存 -----

					$image = new \Zebra_Image();
					$image->source_path = $value['saved_to'] . $value['saved_as'];
					$image->target_path = $path . $value['field'] . '_original.jpg';
					$image->chmod_value = 0644;
					$image->jpeg_quality = 75;
					$image->resize(0, 0, ZEBRA_IMAGE_CROP_CENTER);

					$original_width = $image->source_width;
					$original_height = $image->source_height;

					// サイズが大きすぎる場合はサイズを固定する
					if ($original_width > 1000 or $original_height > 1000)
					{
						if ($original_width > $original_height)
						{
							$image_width = 1000;
							$image_height = (int) round(($image_width / $original_width) * $original_height);
						}
						else
						{
							$image_height = 1000;
							$image_width = (int) round(($image_height / $original_height) * $original_width);
						}
					}
					else
					{
						$image_width = $original_width;
						$image_height = $original_height;
					}

					// オリジナル削除
					if ($delete_original and file_exists($image->target_path)) unlink($image->target_path);

					//var_dump($image);


					// ----- サムネイルの場合 -----

					// inputのnameにthumbnailが含まれている場合、サイズを固定
					if (strpos($value['field'], 'thumbnail') !== false)
					{
						$image_width = 128;
						$image_height = 128;
					}


					// ----- リサイズ画像保存 -----

					$image = new \Zebra_Image();
					$image->source_path = $value['saved_to'] . $value['saved_as'];
					$image->target_path = $path . $value['field'] . '.jpg';
					$image->chmod_value = 0644;
					$image->jpeg_quality = 75;
					$image->preserve_aspect_ratio = true;
					$image->enlarge_smaller_images = true;
					$image->preserve_time = true;
					$image->resize($image_width, $image_height, ZEBRA_IMAGE_CROP_CENTER);
					//var_dump($image);


					// 元画像削除
					if (file_exists($value['saved_to'] . $value['saved_as'])) unlink($value['saved_to'] . $value['saved_as']);


					// データベースに保存する画像情報
					$size_arr[$value['field']]['width'] = $image_width;
					$size_arr[$value['field']]['height'] = $image_height;

				}

			}
			else
			{
				$error = true;
			}

		}


		return array('image_count' => $image_count, 'size_arr' => $size_arr, 'error' => $error);

	}




	/**
	* 画像保存　Version 3
	*
	* @param array $arr 設定配列
	*        string path 保存先のパス
	*        boolean delete_original_image オリジナルを削除する場合はTrue
	*        string limit 決まった数以上の画像がアップロードされた場合はエラーにする
	* @return array 画像のサイズが記述された配列
	*/
	public function zebra_image_save3($arr)
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

		$path = $arr['path'] ?? DOCROOT . 'assets/img/u/';
		$delete_original_image = $arr['delete_original_image'];
		$limit = $arr['limit'];
		$game_no = $arr['game_no'];

		$max_width = $arr['max_width'];
		$max_height = $arr['max_height'];
		$max_width_s = $arr['max_width_s'];
		$max_height_s = $arr['max_height_s'];
		$quality = $arr['quality'];

		$error = false;
		$size_arr = null;
		$save_insert_arr = [];
		$save_update_arr = [];


		// --------------------------------------------------
		//    アップロードされた画像数チェック
		// --------------------------------------------------

		$image_count = 0;

		foreach ($_FILES as $key => $value)
		{
			if ($value['size'] > 0) $image_count++;
			if ($image_count > $limit) return array('error' => true);
		}



		// --------------------------------------------------
		//    画像保存
		// --------------------------------------------------

		// ---------------------------------------------
		//    設定
		// ---------------------------------------------

		$config = array(
			'auto_process' => false,
			// 'max_size' => 10485760,
			'path' => $path
		);

		//if (\Auth::member(100)) $config['max_size'] = 10485760;

		\Upload::process($config);


		// ---------------------------------------------
		//    画像保存
		// ---------------------------------------------

		if (\Upload::is_valid())
		{

			// ----------------------------------------
			//    tempフォルダに画像を保存
			// ----------------------------------------

			\Upload::save();


			// ----------------------------------------
			//    Zebra_Image ライブラリ読み込み
			// ----------------------------------------

			require APPPATH . 'vendor/zebraimage/Zebra_Image.php';

			//\Debug::dump(\Upload::get_files());


			// ----------------------------------------
			//    画像保存
			// ----------------------------------------

			foreach (\Upload::get_files() as $key => $value)
			{

				// ----------------------------------------
				//    ID作成　重複チェックも行う
				// ----------------------------------------

				$image_id = explode('image_', $value['field'])[1];
				//$image_id = 'ukuvy4dlj9idf6p8';
				//$create_image_id = true;
				$authority_edit = false;


				// -----------------------------------
				//    編集
				// -----------------------------------

				if (mb_strlen($image_id) === 16)
				{

					$db_image_arr = $model_image->select_image(array('image_id' => $image_id))[0];

// \Debug::dump($db_image_arr);
// continue;
					// 配列がない場合、処理停止
					if (count($db_image_arr) === 0) exit();

					//　管理者の場合
					if ($db_image_arr['admin'] and \Auth::member(100))
					{
						$authority_edit = true;
					}

					// 編集権限がある場合
					else if ($db_image_arr['user_no'] and $db_image_arr['user_no'] === USER_NO)
					{
						$authority_edit = true;
					}

					// 30分以内で本人の場合
					else if ($db_image_arr['renewal_date'] > $datetime_past and $db_image_arr['host'] == HOST and $db_image_arr['user_agent'] == USER_AGENT)
					{
						$authority_edit = true;
					}

					// 権限がない場合、処理停止
					else
					{
						exit();
					}

				}

				// -----------------------------------
				//    新規作成
				// -----------------------------------

				else
				{
					do
					{
						$image_id = $original_common_text->random_text_lowercase(16);
					}
					while (file_exists($path . $image_id . '.jpg'));
				}

				// \Debug::dump($image_id, $db_image_arr);




				// ----------------------------------------
				//    Width Height 取得
				// ----------------------------------------

				$original_size_arr = getimagesize($value['saved_to'] . $value['saved_as']);
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
				$image->source_path = $value['saved_to'] . $value['saved_as'];
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
				$image->source_path = $value['saved_to'] . $value['saved_as'];
				$image->target_path = $path . $image_id . '_s.jpg';
				$image->chmod_value = 0644;
				$image->jpeg_quality = $quality;
				$image->resize($image_width_s, $image_height_s, ZEBRA_IMAGE_CROP_CENTER);


				// ----------------------------------------
				//    オリジナル画像削除
				// ----------------------------------------

				if (file_exists($value['saved_to'] . $value['saved_as'])) unlink($value['saved_to'] . $value['saved_as']);


				// ----------------------------------------
				//    データベースに保存する画像情報
				// ----------------------------------------

				if ($authority_edit)
				{
					$temp_arr = array(
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
				else
				{
					$temp_arr = array(
						'image_id' => $image_id,
						'approval' => 1,
						'regi_date' => $datetime_now,
						'renewal_date' => $datetime_now,
						'type' => 'hero_game',
						'admin' => 1,
						'game_no' => $game_no,
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

		}
		else
		{
			$error = true;
		}


		// --------------------------------------------------
		//    データベース保存
		// --------------------------------------------------

		//\Debug::dump($save_insert_arr, $save_update_arr);

		if (count($save_insert_arr) > 0)
		{
			$model_image->insert_image($save_insert_arr);
		}

		if (count($save_update_arr) > 0)
		{
			$model_image->update_image($save_update_arr);
		}



		return array('error' => $error);

	}




	/**
	* 画像削除
	*
	* @param array $arr 設定配列
	*        string path 保存先のパス
	*        boolean delete_original_image オリジナルを削除する場合はTrue
	*        string limit 決まった数以上の画像がアップロードされた場合はエラーにする
	* @return array 画像のサイズが記述された配列
	*/
	public function delete_images($arr)
	{

		// --------------------------------------------------
		//    インスタンス
		// --------------------------------------------------

		$model_image = new \Model_Image();
		$original_common_image = new \Original\Common\Image();
		$original_common_text = new \Original\Common\Text();

		$original_common_date = new \Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		$datetime_past = $original_common_date->sql_format("-30 minutes");


		// --------------------------------------------------
		//    変数設定
		// --------------------------------------------------

		$path = $arr['path'] ?? DOCROOT . 'assets/img/u/';
		$delete_image_ids_arr = explode(',', $arr['delete_image_ids']);
		$save_update_arr = [];


		// --------------------------------------------------
		//    削除
		// --------------------------------------------------

		foreach ($delete_image_ids_arr as $key => $value)
		{

			$db_image_arr = $model_image->select_image(array('image_id' => $value))[0];

			//\Debug::dump($db_image_arr);
			// continue;

			// 配列がない場合、処理停止
			if (count($db_image_arr) === 0) exit();

			//　管理者の場合
			if ($db_image_arr['admin'] and \Auth::member(100)){}

			// 編集権限がある場合
			else if ($db_image_arr['user_no'] and $db_image_arr['user_no'] === USER_NO){}

			// 30分以内で本人の場合
			else if ($db_image_arr['renewal_date'] > $datetime_past and $db_image_arr['host'] == HOST and $db_image_arr['user_agent'] == USER_AGENT){}

			// 権限がない場合、処理停止
			else
			{
				return array('error' => true);
			}


			$path_1 = $path . $value . '.jpg';
			$path_2 = $path . $value . '_s.jpg';
			$original_common_image->delete($path_1);
			$original_common_image->delete($path_2);

			$temp_arr = array(
				'image_id' => $value,
				'on_off' => null
			);

			array_push($save_update_arr, $temp_arr);

		}


		// --------------------------------------------------
		//    データベース保存
		// --------------------------------------------------

		// \Debug::dump($save_update_arr);

		if (count($save_update_arr) > 0)
		{
			$model_image->update_image($save_update_arr);
		}



		return array('error' => false);

	}




	/**
	* 画像検証
	*
	* @param array $image_name_arr 画像名を決まったものに制限する
	* @param integer $limit 決まった数以上の画像がアップロードされた場合はエラーにする
	* @return array array(成功、エラー)
	*/
	public function check_upload_image($image_name_arr, $limit)
	{

		// --------------------------------------------------
		//    アップロードされた画像数チェック
		// --------------------------------------------------

		$image_count = 0;

		foreach ($_FILES as $key => $value)
		{
			if ($value['size'] > 0) $image_count++;

			// 想定外の名前の画像がアップロードされた場合はエラー
			if ( ! in_array($key, $image_name_arr)) return array(false, true);
		}

		// 画像がない場合
		if ($image_count == 0)
		{
			return array(false, false);
		}
		// 想定より多い画像数アップロードされた場合はエラー
		else if ($image_count > $limit)
		{
			return array(false, true);
		}


		// --------------------------------------------------
		//    画像検証
		// --------------------------------------------------

		if (\Upload::is_valid())
		{
			return array(true, false);
		}
		else
		{
			return array(false, true);
		}

	}




	/**
	* データベースに保存する動画情報作成
	*
	* @param array $url_arr URL
	* @param array $db_arr データベースに保存されているMovie
	* @return array シリアライズされた配列
	*/
	public function return_movie($url_arr, $db_arr, $limit)
	{
		//echo "return_movie";
		//var_dump($url_arr, $db_arr, $limit);

		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$original_common_movie = new \Original\Common\Movie();


		// --------------------------------------------------
		//   処理
		// --------------------------------------------------

		if ($db_arr == null) $db_arr = array();
		$arr = $db_arr;

		foreach ($url_arr as $key => $value)
		{

			$movie_arr = $original_common_movie->return_type_id($value);
			//var_dump($movie_arr);

			$duplication = false;

			foreach ($arr as $key_two => $value_two)
			{
				$check_key = array_keys($value_two);

				if ($movie_arr['type'] == $check_key[0] and $movie_arr['id'] == $value_two[$check_key[0]])
				{
					$duplication = true;
				}
			}
			//var_dump($duplication);
			if ( ! $duplication and $movie_arr['type'] and $movie_arr['id']) array_push($arr, array($movie_arr['type'] => $movie_arr['id']));

		}

		//var_dump($arr);

		if (count($arr) > $limit or count($arr) == 0)
		{
			//echo "aaa";
			//var_dump(count($arr));
			return null;
		}

		return $arr;

	}




	/**
	* データベースに保存する配列作成　/,1,2,3,/
	*
	* @param array $type タイプ
	* @param array $data データ
	* @return string
	*/
	public function return_db_array($type, $data)
	{

		// --------------------------------------------------
		//   Javascriptで送信されたデータをデータベースに保存する形式に変換　/,1,2,3,/
		// --------------------------------------------------

		if ($type == 'js_db')
		{
			$data_arr = explode(',', $data);
			array_unshift($data_arr, '/');
			array_push($data_arr, '/');
			$return_data = implode(',', $data_arr);
		}

		// --------------------------------------------------
		//   PHPの配列をデータベースに保存する形式に変換　/,1,2,3,/
		// --------------------------------------------------

		else if ($type == 'php_db')
		{
			array_unshift($data, '/');
			array_push($data, '/');
			$return_data = implode(',', $data);
		}

		// --------------------------------------------------
		//   データベースに保存された形式をPHPの配列に変換　array()
		// --------------------------------------------------

		else if ($type == 'db_php')
		{
			$data_arr = explode(',', $data);
			array_shift($data_arr);
			array_pop($data_arr);
			$return_data = $data_arr;
		}


		return $return_data;

	}




	/**
	* Access Date更新
	*
	* @param integer $user_no User No
	* @param integer $profile_no Profile No
	* @param array $db_community_arr ユーザーコミュニティ情報
	* @return boolean or array コミュニティのメンバー情報を同時に更新した場合は、メンバー情報を返す
	*/
	public function renew_access_date($user_no, $profile_no, $db_community_arr)
	{
		//if ($this->user_no == 1) echo 'test<br>';
		// --------------------------------------------------
		//   日時
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();


		// --------------------------------------------------
		//   短時間に複数回アクセスした場合は更新しない
		//   オプションで時間設定
		// --------------------------------------------------

		$session_access_date = \Session::get('access_date');
		//if ($this->user_no == 1) echo $session_access_date . "<br>";
		if (isset($session_access_date))
		{
			$datetime_session = new \DateTime($session_access_date);

			$datetime_past = new \DateTime();
			$datetime_past->modify(\Config::get('renew_access_date_interval'));
			/*
			echo "<br><br><br><br>";
			echo '$datetime_now';
			var_dump($datetime_now);
			echo '$datetime_session';
			var_dump($datetime_session->format("Y-m-d H:i:s"));
			echo '$datetime_past';
			var_dump($datetime_past->format("Y-m-d H:i:s"));
			*/

			//echo '$session_access_date';
			//var_dump($session_access_date);
			//echo "aaaaa";

			if ($datetime_session > $datetime_past) return false;
		}

		\Session::set('access_date', $datetime_now);


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;


		//if ($this->user_no == 1) var_dump($db_community_arr);

		if (isset($db_community_arr))
		{

			// --------------------------------------------------
			//   配列の一番後ろに追加しなおす
			//   （コミュニティにアクセスした順番を記録するため）
			// --------------------------------------------------

			$member_arr = unserialize($db_community_arr['member']);

			$copy_arr = $member_arr[$user_no];
			unset($member_arr[$user_no]);
			$copy_arr['access_date'] = $datetime_now;
			$member_arr[$user_no] = $copy_arr;

			//$arr = array_reverse($arr, true);

			// --------------------------------------------------
			//   Access Date更新　コミュニティのメンバー情報も同時に
			// --------------------------------------------------

			if (isset($member_arr[$user_no]['profile_no']))
			{
				$result = $model_user->update_profile_access_date($datetime_now, $member_arr[$user_no]['profile_no'], $db_community_arr['community_no'], serialize($member_arr));
			}
			else
			{
				$result = $model_user->update_user_data_access_date($datetime_now, $user_no, $db_community_arr['community_no'], serialize($member_arr));
			}

			return $member_arr;
			//var_dump($result);

		}
		else
		{

			// --------------------------------------------------
			//   Access Date更新
			// --------------------------------------------------

			if (isset($profile_no))
			{
				//if ($this->user_no == 1) var_dump('ccc');
				$result = $model_user->update_profile_access_date($datetime_now, $profile_no, null, null);
			}
			else
			{
				//if ($this->user_no == 1) var_dump('ddd');
				$result = $model_user->update_user_data_access_date($datetime_now, $user_no, null, null);
			}
			//if ($this->user_no == 1) var_dump('eee');
		}

		return $result;

	}




	/**
	* お知らせ挿入
	*
	* @param array $arr array('target_user_no' => null, 'community_no' => null, 'game_no' => null, 'type1' => null, 'type2' => null, 'title' => null, 'name' => null, 'comment' => null)
	* @return boolean or array コミュニティのメンバー情報を同時に更新した場合は、メンバー情報を返す
	*/
	/*
	public function save_notifications($arr)
	{


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_notifications = new \Model_Notifications();
		$model_notifications->agent_type = $this->agent_type;
		$model_notifications->user_no = $this->user_no;
		$model_notifications->language = $this->language;
		$model_notifications->uri_base = $this->uri_base;
		$model_notifications->uri_current = $this->uri_current;

		$original_common_text = new \Original\Common\Text();
		$original_common_date = new \Original\Common\Date();



		// --------------------------------------------------
		//   保存用配列作成
		// --------------------------------------------------

		$save_arr = array();


		// --------------------------------------------------
		//   ID
		// --------------------------------------------------

		$save_arr['id'] = $original_common_text->random_text_lowercase(15);


		// --------------------------------------------------
		//   日時
		// --------------------------------------------------

		$save_arr['regi_date'] = (isset($arr['regi_date'])) ? $arr['regi_date'] : $original_common_date->sql_format();


		// --------------------------------------------------
		//   ユーザーNo
		// --------------------------------------------------

		$save_arr['user_no'] = $this->user_no;


		// --------------------------------------------------
		//   プロフィール No
		// --------------------------------------------------

		if (isset($arr['profile_no'])) $save_arr['profile_no'] = $arr['profile_no'];


		// --------------------------------------------------
		//   Target ユーザーNo
		// --------------------------------------------------

		$save_arr['target_user_no'] = $arr['target_user_no'];


		// --------------------------------------------------
		//   Community No
		// --------------------------------------------------

		if (isset($arr['community_no'])) $save_arr['community_no'] = $arr['community_no'];


		// --------------------------------------------------
		//   ゲームNo
		// --------------------------------------------------

		$save_arr['game_no'] = $arr['game_no'];


		// --------------------------------------------------
		//   Type1
		// --------------------------------------------------

		$save_arr['type1'] = $arr['type1'];


		// --------------------------------------------------
		//   Type2
		// --------------------------------------------------

		$save_arr['type2'] = $arr['type2'];


		// --------------------------------------------------
		//   Argument
		// --------------------------------------------------

		$argument_arr = array();

		// ゲームコミュニティ
		if (isset($arr['recruitment_id'])) $argument_arr['recruitment_id'] = $arr['recruitment_id'];

		// ユーザーコミュニティ
		if (isset($arr['bbs_thread_no'])) $argument_arr['bbs_thread_no'] = $arr['bbs_thread_no'];
		if (isset($arr['bbs_comment_no'])) $argument_arr['bbs_comment_no'] = $arr['bbs_comment_no'];
		if (isset($arr['bbs_reply_no'])) $argument_arr['bbs_reply_no'] = $arr['bbs_reply_no'];

		if (count($argument_arr) > 0) $save_arr['argument'] = serialize($argument_arr);


		// --------------------------------------------------
		//   タイトル
		// --------------------------------------------------

		$save_arr['title'] = $arr['title'];


		// --------------------------------------------------
		//   匿名
		// --------------------------------------------------

		if (isset($arr['anonymity'])) $save_arr['anonymity'] = 1;


		// --------------------------------------------------
		//   名前
		// --------------------------------------------------

		$save_arr['name'] = $arr['name'];


		// --------------------------------------------------
		//   コメント
		// --------------------------------------------------

		$save_arr['comment'] = $arr['comment'];


		// --------------------------------------------------
		//   データベースに挿入
		// --------------------------------------------------

		$result_arr = $model_notifications->insert_notifications($save_arr);



		// --------------------------------------------------
		//   お知らせ 削除　1/100の確率で古いデータ削除
		// --------------------------------------------------

		//$random_number = mt_rand(1, 100);
		//if ($random_number == 1) $result_delete = $model_notifications->delete_notifications();


		//var_dump($save_arr);

		return $result_arr;

	}
	*/



	/**
	* Twitterの送信メッセージを作成する
	*
	* @param integer $equipment_no ロードアウトNo
	* @param string $title タイトル
	* @param string $explanation 説明文
	* @return string
	*/
	public function create_twitter_message($arr) {

		$twitter_message = null;



		// --------------------------------------------------
		//   ゲームデータ取得
		// --------------------------------------------------

		$model_game = new \Model_Game();
		$model_game->agent_type = $this->agent_type;
		$model_game->user_no = $this->user_no;
		$model_game->language = $this->language;
		$model_game->uri_base = $this->uri_base;
		$model_game->uri_current = $this->uri_current;

		$db_game_data_arr = $model_game->get_game_data($arr['game_no'], null);



		// --------------------------------------------------
		//   必要な情報作成
		// --------------------------------------------------

		// ---------------------------------------------
		//   コメント
		// ---------------------------------------------

		$comment = $arr['comment'];

		$pat_sub = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/'); // 正規表現向けのエスケープ処理
		$pat  = '/((http|https):\/\/[0-9a-z' . $pat_sub . ']+)/i'; // 正規表現パターン
		$rep  = '<URL>'; // \\1が正規表現にマッチした文字列に置き換わります

		$comment = preg_replace ($pat, $rep, $comment); // 実処理


		// ---------------------------------------------
		//   ゲーム名
		// ---------------------------------------------

		$game_name = $db_game_data_arr['name_' . $arr['language']];
		if (mb_strlen($game_name) > 50) $game_name = mb_substr($game_name, 0, 50, 'UTF-8') . '…';


		// ---------------------------------------------
		//   ハッシュタグ
		// ---------------------------------------------

		$hashtag = $db_game_data_arr['twitter_hashtag_' . $arr['language']];
		if ( ! $hashtag) $hashtag = \Config::get('twitter_hashtag');



		// --------------------------------------------------
		//    募集
		// --------------------------------------------------

		if ($arr['message_type'] === 'gc_recruitment')
		{

			// ---------------------------------------------
			//    募集の種類
			// ---------------------------------------------

			if ($arr['recruitument_type'] == 1) $type = 'プレイヤー募集';
			else if ($arr['recruitument_type'] == 2) $type = 'フレンド募集';
			else if ($arr['recruitument_type'] == 3) $type = 'ギルド・クランメンバー募集';
			else if ($arr['recruitument_type'] == 4) $type = '売買・交換相手募集';
			else $type = 'その他の募集';


			// ---------------------------------------------
			//    返信の場合
			// ---------------------------------------------

			if ($arr['form_type'] == 'reply_new' or $arr['form_type'] == 'reply_edit') $type .= 'への返信';


			// ---------------------------------------------
			//    URL　cronから呼び出すときは URI_BASEが正常に働かないため、そのまま記述する
			// ---------------------------------------------

			$game_id = $db_game_data_arr['id'];
			$url = 'https://gameusers.org/gc/' . $game_id . '/rec/' . $arr['recruitment_id'];

		}


		// --------------------------------------------------
		//    募集
		// --------------------------------------------------

		else if ($arr['message_type'] === 'gc_bbs_thread' or $arr['message_type'] === 'gc_bbs_comment' or $arr['message_type'] === 'gc_bbs_reply')
		{

			// ---------------------------------------------
			//    URL　cronから呼び出すときは URI_BASEが正常に働かないため、そのまま記述する
			// ---------------------------------------------

			$game_id = $db_game_data_arr['id'];
			$url = 'https://gameusers.org/gc/' . $game_id . '/bbs/' . $arr['bbs_id'];

		}


		// ---------------------------------------------
		//   タイトル
		// ---------------------------------------------

		$title = (isset($type)) ? $game_name . ' / ' . $type : $game_name;


		// ---------------------------------------------
		//   開発環境ではURLを変更する　テスト用
		// ---------------------------------------------

		//$url = str_replace('https://192.168.10.2/gameusers/public/', 'https://gameusers.org/', $url);
		//if (\Fuel::$env != 'production') $url = https://gameusers.org/';
		if (\Fuel::$env == 'development') $url = str_replace(URI_BASE, 'https://gameusers.org/', $url);




		// --------------------------------------------------
		//   コメント作成
		// --------------------------------------------------

		// 完成例文
		// 今からプレイします！参加してくれる人募集！初回プレイの人が望ましいです。よろしくお願いします！ [ポイッとヒーロー / プレイヤー募集] https://t.co/2OFTLJieb6 #CoD AW;
		// 参加したいです！ [ポイッとヒーロー / プレイヤー募集への返信] https://t.co/2OFTLJieb6 #CoD AW;

		// 短縮 URL　t.co　はhttpsのURLを23文字に固定変換する　詳しくは　http://internet.watch.impress.co.jp/docs/news/20130221_588789.html

		// コメントを除いた文字数を取得　URLは短縮後の仮想アドレスを代入して計算
		// ローカルアドレスなど、外部からアクセスできないURLの場合は、文字数オーバーのエラーが出てツイートできない
		$pre_twitter_message = "[{$title}] https://t.co/0123456789 #{$hashtag}";

		$message_length = 134 - mb_strlen($pre_twitter_message);

		// 長いコメントをカット　合計135文字にする
		if (mb_strlen($comment) > $message_length) $comment = mb_substr($comment, 0, $message_length, 'UTF-8') . '…';

		// 完成
		$twitter_message = "{$comment} [{$title}] {$url} #{$hashtag}";


		//$test = true;

		if (isset($test))
		{

			echo 'mb_strlen($game_name)';
			var_dump(mb_strlen($game_name));

			echo '$db_game_data_arr';
			var_dump($db_game_data_arr);

			echo '$arr';
			var_dump($arr);

			echo '$game_name';
			var_dump($game_name);

			echo 'mb_strlen($twitter_message)';
			var_dump(mb_strlen($twitter_message));

		}


        return $twitter_message;

	}




	/**
	* 通知・メールを送信する
	*
	* @return string
	*/
	public function send_notification_mail()
	{

		// メールを送信するのは gc / recruitment_reply と uc / mail_all の場合だけ
		// それ以外はアプリの通知のみになる
		// メールの送信機会が多すぎると迷惑になるため

		// 注文を1件取得し、個人向けの場合は複数件同時に送信する
		// まとめて送信する注文の場合、1件の注文だけ処理される

		try
		{

			// テスト
			//$test = true;

			// 通知を送信しない
			//$not_send = true;


			// --------------------------------------------------
			//   メンテナンスの場合、処理中止
			// --------------------------------------------------

			if (\Config::get('maintenance') == 2) exit();


			// --------------------------------------------------
			//   実行時間を制限緩和
			// --------------------------------------------------

			set_time_limit(600);


			// --------------------------------------------------
			//   インスタンス作成
			// --------------------------------------------------

			$model_co = new \Model_Co();
			$model_user = new \Model_User();
			$model_gc = new \Model_Gc();
			$model_notifications = new \Model_Notifications();
			$original_common_notification = new \Original\Common\Notification();
			$original_common_mail = new \Original\Common\Mail();
			$original_common_crypter = new \Original\Common\Crypter();
			$original_common_date = new \Original\Common\Date();
			$original_common_notification = new \Original\Common\Notification();


			// --------------------------------------------------
			//   通知注文 削除　1/1000の確率で古いデータ削除
			// --------------------------------------------------

			$random_number = mt_rand(1, 1000);
			if (empty($test) and $random_number == 1) $result_delete = $model_notifications->delete_notifications();;




			// --------------------------------------------------
			//   通知注文を取得
			// --------------------------------------------------

			$db_notification_arr = $model_notifications->read_notifications_for_send();
			//$db_notification_arr = $model_notifications->read_notifications_for_send('individual');

			//if (count($db_notification_arr) === 0) $db_notification_arr = $model_notifications->read_notifications_for_send();


			// --------------------------------------------------
			//   注文がない場合は処理停止
			// --------------------------------------------------

			if (empty($db_notification_arr)) exit();
			//if (count($db_notification_arr) === 0) exit();


			// --------------------------------------------------
			//   送信開始時間取得
			// --------------------------------------------------

			$send_start_date = $original_common_date->sql_format();


			// --------------------------------------------------
			//   現在送信中の場合は処理停止
			// --------------------------------------------------

			if ($db_notification_arr['send_status'] === 'sending')
			{

				// --------------------------------------------------
				//   15分以上経っているSendingはエラーにする
				// --------------------------------------------------

				$datetime_start = new \DateTime($db_notification_arr['send_start_date']);
				$datetime_sending_off = new \DateTime('-15minutes');

				if ($datetime_start < $datetime_sending_off and empty($not_send))
				{
					$model_notifications->update_notification($db_notification_arr['id'], null, array('send_stop_date' => $send_start_date, 'send_status' => 'error_sending_timeover'));
				}

				exit();

			}



			// --------------------------------------------------
			//   $uri_baseの設定
			// --------------------------------------------------

			//$uri_base =  (\Fuel::$env == 'production') ? 'https://gameusers.org/' : 'https://192.168.10.2/gameusers/public/';
			$uri_base =  (\Fuel::$env == 'production') ? 'https://gameusers.org/' : 'http://localhost/gameusers/public/';


			$game_no = $db_notification_arr['game_no'];


			// 個人に向けての通知
			if ($db_notification_arr['target_user_no'])
			{
				$notification_type = 'individual';
			}
			// ゲームコミュニティの通知
			else if ($db_notification_arr['type1'] === 'gc')
			{
				$notification_type = 'gc';
			}
			// ユーザーコミュニティの通知
			else if ($db_notification_arr['type1'] === 'uc')
			{
				$notification_type = 'uc';
				$member_arr = unserialize($db_notification_arr['member']);
			}



			// --------------------------------------------------
			//   Statusをsendingに変更
			// --------------------------------------------------

			if (empty($not_send)) $result_status_arr = $model_notifications->update_notification($db_notification_arr['id'], null, array('send_start_date' => $send_start_date, 'send_status' => 'sending'));



			// --------------------------------------------------
			//   これまでに送信したユーザー（Latest Sent Users）の処理
			// --------------------------------------------------

			$send_latest_users_arr = (isset($db_notification_arr['send_latest_users'])) ? unserialize($db_notification_arr['send_latest_users']) : array();



			// --------------------------------------------------
			//   ユーザー情報取得
			//   ゲームページ ： 通知を受ける設定にしているユーザー
			//   コミュニティ ： 所属ユーザー
			// --------------------------------------------------

			// 通知・メール一斉送信　一回の処理人数　デフォルト 300
			$limit_mail_task = \Config::get('limit_mail_task');


			// ----------------------------------------
			//   個人に向けての通知　複数のユーザーをまとめて取得する
			// ----------------------------------------

			if ($notification_type == 'individual')
			{
				$result_user_arr = $model_notifications->get_notifications_target_users_list(array('page' => $db_notification_arr['send_page'], 'limit' => $limit_mail_task));
			}

			// ----------------------------------------
			//   ゲームページの通知
			// ----------------------------------------

			else if ($notification_type == 'gc')
			{
				// onのユーザーのみ取得
				$result_user_arr = $model_gc->get_users_game_community_participation_users_list(array('game_no' => $game_no, 'page' => $db_notification_arr['send_page'], 'limit' => $limit_mail_task));
			}

			// ----------------------------------------
			//   コミュニティの通知
			// ----------------------------------------

			else if ($notification_type == 'uc')
			{
				// onのユーザーのみ取得
				$result_user_arr = $model_co->get_participation_community_user($db_notification_arr['community_no'], $db_notification_arr['send_page'], $limit_mail_task);
			}

			$user_arr = $result_user_arr[0];
			$user_total = $result_user_arr[1];
			$limit_page = (($user_total % $limit_mail_task) > 0) ? floor($user_total / $limit_mail_task) + 1 : floor($user_total / $limit_mail_task);



			// --------------------------------------------------
			//   ハンドルネーム取得
			//   on_offがオフになっているプロフィールの場合は取得しない
			// --------------------------------------------------

			$user_no_arr = array();
			$profile_no_arr = array();

			foreach ($user_arr as $key => $value)
			{

				// User No　Profile No取得
				if ($notification_type == 'individual' or $notification_type == 'gc')
				{
					if ($value['config'])
					{
						$config = unserialize($value['config']);

						if (isset($config[$game_no]['profile_no']))
						{
							array_push($profile_no_arr, $config[$game_no]['profile_no']);
						}
						else if (isset($config[$game_no]['user_no']))
						{
							array_push($user_no_arr, $config[$game_no]['user_no']);
						}
					}
					else
					{
						if (isset($value['user_no'])) array_push($user_no_arr, $value['user_no']);
					}
				}
				else if ($notification_type == 'uc')
				{
					($member_arr[$value['user_no']]['profile_no']) ? array_push($profile_no_arr, $member_arr[$value['user_no']]['profile_no']) : array_push($user_no_arr, $value['user_no']);
				}

			}

			// データ取得 onのプロフィールのみ取得
			if (count($user_no_arr) > 0)
			{
				$user_data_arr = $model_user->get_user_data_list_in_personal_box($user_no_arr);
			}

			if (count($profile_no_arr) > 0)
			{
				$profile_arr = $model_user->get_profile_list_in_personal_box($profile_no_arr, true);
			}






			// --------------------------------------------------
			//   メッセージ作成　全体送信用
			// --------------------------------------------------

			$browser_title = null;
			$browser_body = null;
			$browser_icon = null;
			$browser_tag = null;
			$browser_url = null;
			$browser_vibrate = 1;
			$browser_ttl = null;
			$browser_urgency = null;
			$browser_topic = null;

			$app_message = null;

			$mail_title = null;
			$mail_message = null;


			if ($notification_type !== 'individual')
			{

				// ----------------------------------------
				//   共通
				// ----------------------------------------

				$temp_url = null;
				$temp_tag = null;

				if (isset($db_notification_arr['argument']))
				{
					$temp_argument_arr = unserialize($db_notification_arr['argument']);
				}


				// --------------------------------------------------
				//   ゲームページ
				// --------------------------------------------------

				if ($db_notification_arr['type1'] == 'gc')
				{

					$browser_url = $uri_base . 'gc/' . $db_notification_arr['game_id'];
					$browser_icon = $uri_base . 'assets/img/game/' . $db_notification_arr['game_no'] . '/thumbnail.jpg';
					$browser_tag = $db_notification_arr['game_no'] . '_' . $db_notification_arr['type1'] . '_' . $db_notification_arr['type2'];
					$browser_topic = $browser_tag;


					// --------------------------------------------------
					//   募集投稿
					// --------------------------------------------------

					if ($db_notification_arr['type2'] == 'recruitment')
					{
						$browser_title = '募集掲示板：' . $db_notification_arr['title'];
						$browser_body = (mb_strlen($db_notification_arr['comment']) > 100) ? mb_substr($db_notification_arr['comment'], 0, 99, 'UTF-8') . '…' : $db_notification_arr['comment'];
						$browser_url .= '/rec/' . $temp_argument_arr['recruitment_id'];

						$app_message = '「' . $db_notification_arr['game_name'] . ' - ゲームページ」に新しい募集が投稿されました。';
					}

				}


				// --------------------------------------------------
				//   コミュニティ
				// --------------------------------------------------

				if ($db_notification_arr['type1'] == 'uc')
				{

					$browser_body = (mb_strlen($db_notification_arr['title'] . ' / ' . $db_notification_arr['comment']) > 100) ? mb_substr($db_notification_arr['title'] . ' / ' . $db_notification_arr['comment'], 0, 99, 'UTF-8') . '…' : $db_notification_arr['title'] . ' / ' . $db_notification_arr['comment'];
					$browser_url = $uri_base . 'uc/' . $db_notification_arr['community_id'];
					$browser_icon = $uri_base . 'assets/img/community/' . $db_notification_arr['community_no'] . '/thumbnail.jpg';
					$browser_tag = $db_notification_arr['community_no'] . '_' . $db_notification_arr['type1'] . '_' . $db_notification_arr['type2'];
					$browser_topic = $browser_tag;


					// --------------------------------------------------
					//   通知の一斉送信
					// --------------------------------------------------

					if ($db_notification_arr['type2'] == 'mail_all')
					{
						$browser_title = '通知一斉送信：' . $db_notification_arr['community_name'];

						$app_message = '「' . $db_notification_arr['community_name'] . '」から通知の一斉送信が行われました。';

						$mail_title = $db_notification_arr['title'];
						$mail_message = $db_notification_arr['comment'];
						$mail_message .= "\n\n" . '[ ' . $db_notification_arr['community_name'] . ' ]' . "\n";
						$mail_message .= $uri_base . 'uc/' . $db_notification_arr['community_id'];
					}


					// --------------------------------------------------
					//   告知
					// --------------------------------------------------

					if ($db_notification_arr['type2'] == 'announcement')
					{
						$browser_title = '告知更新：' . $db_notification_arr['community_name'];

						$app_message = '「' . $db_notification_arr['community_name'] . '」で告知が更新されました。';
					}


					// --------------------------------------------------
					//   BBSスレッド作成
					// --------------------------------------------------

					if ($db_notification_arr['type2'] == 'bbs_thread')
					{
						$browser_title = 'スレッド作成：' . $db_notification_arr['community_name'];

						$app_message = '「' . $db_notification_arr['community_name'] . '」に「' . $db_notification_arr['title'] . '」というスレッドが立てられました。';
					}


					// --------------------------------------------------
					//   BBSコメント投稿
					// --------------------------------------------------

					if ($db_notification_arr['type2'] == 'bbs_comment')
					{
						$browser_title = 'コメント投稿：' . $db_notification_arr['community_name'];

						$app_message = '「' . $db_notification_arr['community_name'] . '」の「' . $db_notification_arr['title'] . '」にコメントが書き込まれました。';
					}


					// --------------------------------------------------
					//   BBS返信投稿
					// --------------------------------------------------

					if ($db_notification_arr['type2'] == 'bbs_reply')
					{
						$browser_title = '返信投稿：' . $db_notification_arr['community_name'];

						$app_message = '「' . $db_notification_arr['community_name'] . '」の「' . $db_notification_arr['title'] . '」に返信が書き込まれました。';
					}

				}

			}




			// --------------------------------------------------
			//   ブラウザ・アプリ・メールの送信用配列作成
			//   foreachでまわして、送信済みユーザーの配列も作成する
			//   個人への送信の場合は、メッセージと宛先の入った配列を作成する
			//   （全体へ送信する場合のメッセージは上で作っている）
			// --------------------------------------------------

			$browser_arr = array();
			$app_android_arr = array();
			$app_ios_arr = array();
			$mail_arr = array();
			$id_arr = array();


			foreach ($user_arr as $key => $value)
			{

				// --------------------------------------------------
				//   送信しないケース　個人向け
				// --------------------------------------------------

				if ($notification_type == 'individual')
				{

					// IDを配列に追加　まとめてStatusをoffにするために必要
					if (isset($value['id'])) array_push($id_arr, $value['id']);


					// プロフィールが削除されている場合は次へ
					if (empty($value['users_data_on_off']))
					{
						//if (isset($test)) echo "プロフィールが削除されている場合は次へ : key = {$key} / target_user_no = {$value['target_user_no']}<br>";
						continue;
					}

					// 通知を受けられないユーザーの場合は次へ
					if (empty($value['users_data_notification_on_off']))
					{
						//if (isset($test)) echo "通知を受けられないユーザーの場合は次へ : key = {$key} / target_user_no = {$value['target_user_no']}<br>";
						continue;
					}

					// NGユーザーに含まれている場合は次へ
					$ng_user_arr = ($value['ng_user']) ? unserialize($value['ng_user']) : null;

					if (isset($value['profile_no'], $ng_user_arr['profile_' . $value['profile_no']]))
					{
						//if (isset($test)) echo "NGユーザーに含まれている場合は次へ（profile_no） : key = {$key} / target_user_no = {$value['target_user_no']}<br>";
						continue;
					}
					else if (isset($value['user_no'], $ng_user_arr['user_' . $value['user_no']]))
					{
						//if (isset($test)) echo "NGユーザーに含まれている場合は次へ（user_no） : key = {$key} / target_user_no = {$value['target_user_no']}<br>";
						continue;
					}

				}

				// --------------------------------------------------
				//   送信しないケース　ゲームページ
				// --------------------------------------------------

				else if ($notification_type == 'gc')
				{

					// 自分の行為に対して通知は行わない
					if ($db_notification_arr['user_no'] == $value['user_no']) continue;

					// 送信済みユーザーに含まれている場合は次へ
					if (in_array($value['user_no'], $send_latest_users_arr)) continue;


					$config_arr = ($value['config']) ? unserialize($value['config']) : null;

					// 追加プロフィールが削除されている場合は次へ
					if (isset($config_arr[$db_notification_arr['game_no']]['profile_no']))
					{
						$profile_no = $config_arr[$db_notification_arr['game_no']]['profile_no'];
						if (empty($profile_arr[$profile_no])) continue;
					}


					// NGユーザーに含まれている場合は次へ
					$ng_user_arr = ($value['ng_user']) ? unserialize($value['ng_user']) : null;

					if (isset($db_notification_arr['profile_no']))
					{
						if (isset($ng_user_arr['profile_' . $db_notification_arr['profile_no']])) continue;
					}
					else if (isset($db_notification_arr['user_no']))
					{
						if (isset($ng_user_arr['user_' . $db_notification_arr['user_no']])) continue;
					}

				}

				// --------------------------------------------------
				//   送信しないケース　コミュニティ
				// --------------------------------------------------

				else if ($notification_type == 'uc')
				{

					// 自分の行為に対して通知は行わない
					if ($db_notification_arr['user_no'] == $value['user_no']) continue;

					// 送信済みユーザーに含まれている場合は次へ
					if (in_array($value['user_no'], $send_latest_users_arr)) continue;


					// member_arrに存在しない場合は次へ
					if (empty($member_arr[$value['user_no']])) continue;

					// ユーザーコミュニティの設定で通知を受信しないようにしてる場合は次へ
					if ( ! $member_arr[$value['user_no']]['mail_all']) continue;

					// member_arrに追加プロフィールで登録されている
					if (isset($member_arr[$value['user_no']]['profile_no']))
					{
						// 追加プロフィールが削除されている場合は次へ
						if (empty($profile_arr[$member_arr[$value['user_no']]['profile_no']])) continue;
					}

				}


				// --------------------------------------------------
				//   送信済みユーザーに登録
				// --------------------------------------------------

				array_unshift($send_latest_users_arr, $value['user_no']);





				// --------------------------------------------------
				//   メッセージ作成　個人へ送信用
				// --------------------------------------------------

				// 個人向きではない場合、次へ
				//if ($notification_type !== 'individual') continue;


				// 通知設定のアンシリアライズ
				$notification_data_arr = unserialize($value['notification_data']);

				if (isset($test, $notification_data_arr))
				{
					echo '<br><br><br><br>';
					echo 'AAA $notification_data_arr';
					\Debug::dump($notification_data_arr);
				}


				// $individual_browser_endpoint = null;
				// $individual_browser_public_key = null;
				// $individual_browser_auth_token = null;
				$individual_browser_title = null;
				$individual_browser_body = null;
				$individual_browser_icon = null;
				$individual_browser_tag = null;
				$individual_browser_url = null;
				$individual_browser_vibrate = null;
				$individual_browser_ttl = null;
				$individual_browser_urgency = null;
				$individual_browser_topic = null;

				$individual_app_message = null;

				$individual_mail_title = null;
				$individual_mail_message = null;


				// --------------------------------------------------
				//   メッセージ作成　個人送信用　募集への返信
				// --------------------------------------------------

				if ($notification_type === 'individual' and $value['type1'] == 'gc' and $value['type2'] == 'recruitment_reply')
				{

					// ----------------------------------------
					//   共通
					// ----------------------------------------

					$temp_url = null;
					$temp_tag = null;

					$temp_url = $uri_base . 'gc/' . $value['game_id'];
					if (isset($value['argument']))
					{
						$temp_url .= '/rec/' . unserialize($value['argument'])['recruitment_id'];
					}
					//\Debug::dump($temp_url);

					$temp_tag = $value['game_no'] . '_' . $value['type1'] . '_' . $value['type2'];


					// ----------------------------------------
					//   ブラウザ用
					// ----------------------------------------

					$individual_browser_title = '募集への返信: ' . $value['title'] . '';
					$individual_browser_body = (mb_strlen($value['comment']) > 100) ? mb_substr($value['comment'], 0, 99, 'UTF-8') . '…' : $value['comment'];
					$individual_browser_icon = $uri_base . 'assets/img/game/' . $value['game_no'] . '/thumbnail.jpg';
					$individual_browser_tag = $temp_tag;
					$individual_browser_url = $temp_url;
					$individual_browser_vibrate = 1;
					$individual_browser_ttl = null;
					$individual_browser_urgency = null;
					$individual_browser_topic = $temp_tag;


					// ----------------------------------------
					//   アプリ用
					// ----------------------------------------

					$individual_app_message = '「' . $value['game_name'] . ' / ' . $value['title'] . '」に返信が届きました。';


					// ----------------------------------------
					//   メール用
					// ----------------------------------------

					$individual_mail_title = 'Re: ' . $value['title'];

					if ($value['profile_no'] and isset($profile_arr[$value['profile_no']]['handle_name']))
					{
						$individual_mail_message = $profile_arr[$value['profile_no']]['handle_name'] . "\n\n";
					}
					else if ($value['user_no'] and isset($user_data_arr[$value['user_no']]['handle_name']))
					{
						$individual_mail_message = $user_data_arr[$value['user_no']]['handle_name'] . "\n\n";
					}

					$individual_mail_message .= $value['comment'];
					$individual_mail_message .= "\n\n" . '[ ' . $db_notification_arr['game_name'] . ' ]' . "\n";
					$individual_mail_message .= $temp_url;

				}




				// --------------------------------------------------
				//   送信用配列作成　ブラウザー
				// --------------------------------------------------

				if ($notification_data_arr['on_off_browser'] and count($notification_data_arr['receive_browser']) > 0)
				{

					foreach ($notification_data_arr['receive_browser'] as $key2 => $value2)
					{

						if (isset($notification_data_arr['browser_info'][$value2]))
						{
							$temp_arr = $notification_data_arr['browser_info'][$value2];

							// 全体へ送信
							if ($browser_title and $browser_body)
							{
								$original_common_notification->set_web_push_arr(array(
									'endpoint' => $temp_arr['endpoint'],
									'public_key' => $temp_arr['public_key'],
									'auth_token' => $temp_arr['auth_token'],
									'title' => $browser_title,
									'body' => $browser_body,
									'icon' => $browser_icon,
									'tag' => $browser_tag,
									'url' => $browser_url,
									'vibrate' => $browser_vibrate,
									'ttl' => $browser_ttl,
									'urgency' => $browser_urgency,
									'topic' => $browser_topic,
								));
							}
							// 個人へ送信
							else if ($individual_browser_title and $individual_browser_body)
							{
								$original_common_notification->set_web_push_arr(array(
									'endpoint' => $temp_arr['endpoint'],
									'public_key' => $temp_arr['public_key'],
									'auth_token' => $temp_arr['auth_token'],
									'title' => $individual_browser_title,
									'body' => $individual_browser_body,
									'icon' => $individual_browser_icon,
									'tag' => $individual_browser_tag,
									'url' => $individual_browser_url,
									'vibrate' => $individual_browser_vibrate,
									'ttl' => $individual_browser_ttl,
									'urgency' => $individual_browser_urgency,
									'topic' => $individual_browser_topic,
								));
							}
						}

					}

				}


				// --------------------------------------------------
				//   送信用配列作成　アプリ / $app_android_arr
				// --------------------------------------------------

				if ($notification_data_arr['on_off_app'] and isset($notification_data_arr['device_info'][$notification_data_arr['receive_device']]))
				{

					$temp_arr = $notification_data_arr['device_info'][$notification_data_arr['receive_device']];

					if ($temp_arr['type'] === 'Android')
					{

						// 全体へ送信
						if ($app_message)
						{
							$temp_key = md5($app_message);
							if (empty($app_android_arr[$temp_key]['token'])) $app_android_arr[$temp_key]['token'] = array();
							array_push($app_android_arr[$temp_key]['token'], $temp_arr['token']);
							$app_android_arr[$temp_key]['message'] = $app_message;
						}
						// 個人へ送信
						else if ($individual_app_message)
						{
							$temp_key = md5($individual_app_message);
							if (empty($app_android_arr[$temp_key]['token'])) $app_android_arr[$temp_key]['token'] = array();
							array_push($app_android_arr[$temp_key]['token'], $temp_arr['token']);
							$app_android_arr[$temp_key]['message'] = $individual_app_message;
						}

					}

				}

				// --------------------------------------------------
				//   送信用配列作成　メール / $mail_arr
				// --------------------------------------------------

				if ($notification_data_arr['on_off_mail'] and isset($value['email']))
				{

					// メールアドレス復号化
					$decrypted_email = $original_common_crypter->decrypt($value['email']);


					// 全体へ送信
					if ($mail_message)
					{
						array_push($mail_arr, array('email' => $decrypted_email));
					}
					// 個人へ送信
					else if ($individual_mail_message)
					{
						array_push($mail_arr, array('email' => $decrypted_email, 'individual_title' => $individual_mail_title, 'individual_message' => $individual_mail_message));
					}

				}

			}








			// --------------------------------------------------
			//   ◆◆◆ 送信　ブラウザ
			// --------------------------------------------------

			if (count($original_common_notification->get_web_push_arr()) > 0)
			{
				if (isset($test)) echo "【Browser】<br><br>";

				if (empty($not_send)) $original_common_notification->send_web_push();
				//$original_common_notification->send_web_push();

			}



			// --------------------------------------------------
			//   ◆◆◆ 送信　アプリ
			// --------------------------------------------------

			if (count($app_android_arr) > 0)
			{
				foreach ($app_android_arr as $key => $value)
				{
					if (isset($test)) echo "【app Android】<br><br>";

					if (isset($not_send)) break;

					$result = $original_common_notification->send_all_android($value['token'], $value['message'], null);

					// 全体に送信
					// if (isset($app_message))
					// {
					// 	$result = $original_common_notification->send_all_android($value['token'], $app_message, null);
					// }
					// // 個別ユーザーに送信
					// else if (isset($value['individual_message']))
					// {
					// 	$result = $original_common_notification->send_all_android($value['token'], $value['individual_message'], null);
					// }
					//echo '$result';
					//var_dump($result);
				}
			}



			// --------------------------------------------------
			//   ◆◆◆ 送信　メール　1通ずつ送信
			// --------------------------------------------------

			if (count($mail_arr) > 0)
			{
				foreach ($mail_arr as $key => $value) {

					if (isset($test)) echo "【mail】<br><br>";

					if (isset($not_send)) break;

					// 全体に送信
					if (isset($mail_title, $mail_message))
					{
						$result = $original_common_mail->to('mail@gameusers.org', 'Game Users', $value['email'], null, $mail_title, $mail_message);
					}
					// 個別ユーザーに送信
					else if (isset($value['individual_title'], $value['individual_message']))
					{
						$result = $original_common_mail->to('mail@gameusers.org', 'Game Users', $value['email'], null, $value['individual_title'], $value['individual_message']);
					}

				}
			}





			// --------------------------------------------------
			//   データベース保存　Status、Page、Latest Sent Users
			// --------------------------------------------------

			// 個人へ送信
			if ($notification_type == 'individual')
			{

				$datetime_now = $original_common_date->sql_format();
				$save_arr['send_start_date'] = $send_start_date;
				$save_arr['send_stop_date'] = $datetime_now;
				$save_arr['send_status'] = 'off';

				// echo '<br>$id_arr';
				// var_dump($id_arr);
//
				// echo '<br>$save_arr';
				// var_dump($save_arr);

				if (empty($not_send)) $result_arr = $model_notifications->update_notification(null, $id_arr, $save_arr);

			}
			// 全体へ送信
			else
			{

				// 送信済みユーザー　余分な件数を削除
				array_splice($send_latest_users_arr, $limit_mail_task);

				$save_arr['send_latest_users'] = (count($send_latest_users_arr) > 0) ? serialize($send_latest_users_arr) : null;

				$page_next = $db_notification_arr['send_page'] + 1;

				if ($page_next > $limit_page)
				{
					$datetime_now = $original_common_date->sql_format();
					$save_arr['send_stop_date'] = $datetime_now;
					$save_arr['send_status'] = 'off';
				}
				else
				{
					$save_arr['send_page'] = $page_next;
					$save_arr['send_status'] = 'on';
				}

				if (empty($not_send)) $result_arr = $model_notifications->update_notification($db_notification_arr['id'], null, $save_arr);

			}



			if (isset($test))
			{
				\Debug::$js_toggle_open = true;

				echo '<br>$db_notification_arr';
				\Debug::dump($db_notification_arr);

				echo '<br>$notification_type';
				\Debug::dump($notification_type);


				// if (isset($member_arr))
				// {
				// 	echo '<br>$member_arr';
				// 	\Debug::dump($member_arr);
				// }

				echo '<br>$send_latest_users_arr';
				\Debug::dump($send_latest_users_arr);

				// echo '<br>$limit_mail_task';
				// \Debug::dump($limit_mail_task);

				echo '<br>$user_arr';
				\Debug::dump($user_arr);

				// echo '<br>$user_total';
				// \Debug::dump($user_total);
				//
				// echo '<br>$limit_page';
				// \Debug::dump($limit_page);
				//
				// if (isset($user_no_arr))
				// {
				// 	echo '<br>$user_no_arr';
				// 	\Debug::dump($user_no_arr);
				// }
				//
				// if (isset($profile_no_arr))
				// {
				// 	echo '<br>$profile_no_arr';
				// 	\Debug::dump($profile_no_arr);
				// }
				//
				// if (isset($user_data_arr))
				// {
				// 	echo '<br>$user_data_arr';
				// 	\Debug::dump($user_data_arr);
				// }
				//
				// if (isset($profile_arr))
				// {
				// 	echo '<br>$profile_arr';
				// 	\Debug::dump($profile_arr);
				// }
				//

				echo '<br>get_web_push_arr';
				\Debug::dump($original_common_notification->get_web_push_arr());

				if (isset($app_android_arr))
				{
					echo '<br>$app_android_arr';
					\Debug::dump($app_android_arr);
				}
				//
				// if (isset($app_ios_arr))
				// {
				// 	echo '<br>$app_ios_arr';
				// 	\Debug::dump($app_ios_arr);
				// }
				//
				if (isset($mail_arr))
				{
					echo '<br>$mail_arr';
					\Debug::dump($mail_arr);
				}



				// if (isset($browser_title))
				// {
				// 	echo '<br>$browser_title';
				// 	\Debug::dump($browser_title);
				// }
				//
				// if (isset($browser_body))
				// {
				// 	echo '<br>$browser_body';
				// 	\Debug::dump($browser_body);
				// }

				//
				// if (isset($app_message))
				// {
				// 	echo '<br>$app_message';
				// 	\Debug::dump($app_message);
				// }
				//
				// if (isset($mail_title))
				// {
				// 	echo '<br>$mail_title';
				// 	\Debug::dump($mail_title);
				// }
				//
				// if (isset($mail_message))
				// {
				// 	echo '<br>$mail_message';
				// 	\Debug::dump($mail_message);
				// }
				//
				if (isset($id_arr))
				{
					echo '<br>$id_arr';
					\Debug::dump($id_arr);
				}

				if (isset($save_arr))
				{
					echo '<br>$save_arr';
					\Debug::dump($save_arr);
				}

			}

			//exit();



		}
		catch (Exception $e)
		{

			// --------------------------------------------------
			//   データベース保存　Error
			// --------------------------------------------------

			$datetime_now = $original_common_date->sql_format();
			if (empty($not_send)) $model_notifications->update_notification($db_notification_arr['id'], null, array('send_stop_date' =>$datetime_now, 'send_status' =>'error'));


			if (isset($test))
			{
				\Debug::dump($e);
			}

		}

	}



	/**
	* サイトマップ作成
	*
	* @return string
	*/
	public function send_sns()
	{


		try
		{


			// --------------------------------------------------
			//   メンテナンスの場合、処理中止
			// --------------------------------------------------

			if (\Config::get('maintenance') == 2) exit();


			// --------------------------------------------------
			//   インスタンス作成
			// --------------------------------------------------

			$model_sns = new \Model_Sns();
			$model_bbs = new \Model_Bbs();
			$model_gc = new \Model_Gc();
			$model_user = new \Model_User();
			$original_func_common = new \Original\Func\Common();
			$original_common_twitter = new \Original\Common\Twitter();


			// --------------------------------------------------
			//   データ取得
			// --------------------------------------------------

			$temp_arr = [
				'on_off' => 1
			];

			$send_sns_arr = $model_sns->select_send_sns_for_cron($temp_arr);


			// --------------------------------------------------
			//   データが存在しない場合は削除
			// --------------------------------------------------

			if (empty($send_sns_arr['send_sns_id'])) exit();


			// --------------------------------------------------
			//   変数代入
			// --------------------------------------------------

			$recruitument_type = 1;
			$comment = null;

			if ($send_sns_arr['bbs_id'])
			{
				$db_bbs_arr = $model_bbs->select_bbs_for_sns($send_sns_arr);
				$comment = $db_bbs_arr['comment'];
			}
			else if ($send_sns_arr['recruitment_id'])
			{
				$db_recruitment_arr = $model_gc->get_recruitment_appoint($send_sns_arr['recruitment_id']);
				$recruitument_type = $db_recruitment_arr['type'];
				$comment = $db_recruitment_arr['comment'];
			}


			// --------------------------------------------------
			//    Twitterメッセージ作成
			// --------------------------------------------------

			$temp_arr = array(
			  'message_type' => $send_sns_arr['type'],
			  'form_type' => null,
			  'game_no' => $send_sns_arr['game_no'],
			  'language' => 'ja',
			  'bbs_id' => $send_sns_arr['bbs_id'],
			  'recruitment_id' => $send_sns_arr['recruitment_id'],
			  'recruitument_type' => $recruitument_type,
			  'comment' => $comment
			);

			$twitter_message = $original_func_common->create_twitter_message($temp_arr);
// \Debug::dump($send_sns_arr, $twitter_message);
// exit();


			// --------------------------------------------------
			//   データ削除
			// --------------------------------------------------

			unset($temp_arr);

			$temp_arr[0] = [
				'send_sns_id' => $send_sns_arr['send_sns_id']
			];

			$result_arr = $model_sns->delete_send_sns($temp_arr);



			// --------------------------------------------------
			//    コンシューマーキー取得
			// --------------------------------------------------

			$consumer_key = \Config::get('twitter_consumer_key');
			$consumer_secret = \Config::get('twitter_consumer_secret');


			// --------------------------------------------------
			//    アクセストークン取得
			// --------------------------------------------------

			$access_token = \Config::get('twitter_access_token');
			$access_token_secret = \Config::get('twitter_access_token_secret');
			// $result_twitter_arr = $model_user->get_twitter_access_token(10);
			// $access_token = $result_twitter_arr['access_token'];
			// $access_token_secret = $result_twitter_arr['access_token_secret'];


			// --------------------------------------------------
			//    Tweetする
			// --------------------------------------------------

			$result_tweet = $original_common_twitter->post_message($consumer_key, $consumer_secret, $access_token, $access_token_secret, $twitter_message);

			// \Debug::dump($send_sns_arr, $twitter_message);
			// \Debug::dump($result_tweet);
			// exit();



		}
		catch (Exception $e)
		{

		}

	}




	/**
	* サイトマップ作成
	*
	* @return string
	*/
	public function output_sitemap()
	{

		$datetime = new \DateTime();
		$datetime_now = $datetime->format(\DateTime::W3C);

		$model_sitemap = new \Model_Sitemap();

		// Wiki
		$db_wiki_arr = $model_sitemap->wiki(null);

		// ゲームコミュニティ
		$db_game_community_arr = $model_sitemap->game_community(null);

		// ユーザーコミュニティ
		$db_user_community_arr = $model_sitemap->user_community(null);

		// ユーザー
		$db_users_arr = $model_sitemap->users(null);

		//\Debug::dump($db_wiki_arr);

		$view = \View::forge('parts/sitemap_view');
		$view->set_safe('datetime_now', $datetime_now);
		$view->set_safe('db_wiki_arr', $db_wiki_arr);
		$view->set_safe('db_game_community_arr', $db_game_community_arr);
		$view->set_safe('db_user_community_arr', $db_user_community_arr);
		$view->set_safe('db_users_arr', $db_users_arr);
		$code = $view->render();





		if (\Fuel::$env == 'development')
		{
			echo $code;

			$sitemap_address = DOCROOT . 'sitemap.xml';
		}
		else
		{
			$sitemap_address = '/var/www/public/sitemap.xml';
		}

		// ファイル保存
		$fp = fopen($sitemap_address, 'w');
		fwrite($fp, $code);
		fclose($fp);

	}





	/**
	* Access Date更新
	*
	* @param integer $arr['game_no'] 参加したゲームコミュニティNo
	* @param integer $arr['community_no'] 参加したユーザーコミュニティNo
	* @param array $arr 配列
	* @return boolean 正常に処理した場合、true
	*/
	public function change_notifications_already($arr)
	{

		$language = 'ja';


		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		// インスタンス作成
		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;

		$model_notifications = new \Model_Notifications();
		$model_notifications->agent_type = $this->agent_type;
		$model_notifications->user_no = $this->user_no;
		$model_notifications->language = $this->language;
		$model_notifications->uri_base = $this->uri_base;
		$model_notifications->uri_current = $this->uri_current;

		$model_gc = new \Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;

		$original_func_common = new \Original\Func\Common();
		$original_func_common->agent_type = $this->agent_type;
		$original_func_common->user_no = $this->user_no;
		$original_func_common->language = $this->language;
		$original_func_common->uri_base = $this->uri_base;
		$original_func_common->uri_current = $this->uri_current;



		// --------------------------------------------------
		//  データベースから取得
		// --------------------------------------------------

		$db_users_data_arr = $model_user->get_user_data($this->user_no, null);



		// --------------------------------------------------
		//    既読IDの処理
		//    例）$already_read_id_arr = array('0zj0pnd2vlw2eex', '2rwgyd1sbzyu5ub')
		// --------------------------------------------------

		if ($db_users_data_arr['notifications_already_read_id'])
		{
			// アンシリアライズ
			$pre_already_read_id_arr = unserialize($db_users_data_arr['notifications_already_read_id']);

			// 日付を削除したIDのみの配列を作る
			$already_read_id_arr = array();

			foreach ($pre_already_read_id_arr as $key => $value) {
				array_push($already_read_id_arr, $value['id']);
			}
		}
		else
		{
			$already_read_id_arr = null;
		}

		// 既読通知の読み込みで、既読IDがない場合は、code＝nullを返して終わり
		// if ($unread === false and $already_read_id_arr === null)
		// {
			// return null;
		// }



		// --------------------------------------------------
		//    すべての未読を既読にする
		// --------------------------------------------------

		$user_no = null;
		$participation_community_no_arr = array();
		$notification_recruitment_arr = null;


		if (empty($arr['community_no']) and empty($arr['game_no']))
		{

			$user_no = $this->user_no;


			// --------------------------------------------------
			//    参加しているユーザーコミュニティのNoを配列化する処理
			//    例）$participation_community_no_arr = array(1,2,3,4,5)
			// --------------------------------------------------

			// 公開
			if ($db_users_data_arr['participation_community'])
			{
				$participation_community_arr = $original_func_common->return_db_array('db_php', $db_users_data_arr['participation_community']);
			}
			else
			{
				$participation_community_arr = array();
			}

			// 非公開
			if ($db_users_data_arr['participation_community_secret'])
			{
				$participation_community_secret_arr = $original_func_common->return_db_array('db_php', $db_users_data_arr['participation_community_secret']);
			}
			else
			{
				$participation_community_secret_arr = array();
			}

			// 公開と非公開を合成
			$participation_community_no_arr = array_merge($participation_community_arr, $participation_community_secret_arr);



			// --------------------------------------------------
			//    新規募集があったときに通知を受ける設定にしているゲームNoを取得
			//    例）$notification_recruitment = array(1,2,3,4,5)
			// --------------------------------------------------

			$db_user_game_community = $model_gc->get_user_game_community();
			$notification_recruitment_arr = (isset($db_user_game_community['notification_recruitment'])) ? $original_func_common->return_db_array('db_php', $db_user_game_community['notification_recruitment']) : null;

		}


		// --------------------------------------------------
		//    ユーザーコミュニティに参加した場合、ユーザーコミュニティの未読を既読にする
		// --------------------------------------------------

		else if (isset($arr['community_no']))
		{
			$participation_community_no_arr = array($arr['community_no']);
		}


		// --------------------------------------------------
		//    ゲームコミュニティで新規募集があったときに通知を受ける設定にした場合、ゲームコミュニティの未読を既読にする
		// --------------------------------------------------

		else if (isset($arr['game_no']))
		{
			$user_no = $this->user_no;
			$notification_recruitment_arr = array($arr['game_no']);
		}




		// --------------------------------------------------
		//    データ読み込み
		// --------------------------------------------------

		//$limit = ($this->agent_type != 'smartphone') ? \Config::get('limit_notification') : \Config::get('limit_notification_sp');
		$limit = 100000;


		$read_notifications_argument_arr = array(
			'language' => $language,
			'unread' => true,
			'already_read_id_arr' => $already_read_id_arr,
			'user_no' => $user_no,
			'participation_community_no_arr' => $participation_community_no_arr,
			'notification_recruitment_arr' => $notification_recruitment_arr,
			'page' => 1,
			'limit' => $limit
		);

		$result_arr = $model_notifications->read_notifications($read_notifications_argument_arr);
		$db_notifications_arr = $result_arr['data'];
		$total = $result_arr['total'];




		// --------------------------------------------------
		//    保存するIDの配列を作成する
		// --------------------------------------------------

		$save_unread_id_arr = array();

		foreach ($db_notifications_arr as $key => $value) {
			array_push($save_unread_id_arr, $value['id']);
		}



		// --------------------------------------------------
		//    未読予約する＆未読予約したIDを保存する
		// --------------------------------------------------

		$result_count = count($save_unread_id_arr);

		if ($result_count > 0)
		{
			$result_arr = $model_notifications->save_notifications_id_reservation($save_unread_id_arr);
			$model_notifications->save_notifications_id();
		}




		//$test = true;

		if (isset($test))
		{
			\Debug::$js_toggle_open = true;

			echo '$db_users_data_arr';
			\Debug::dump($db_users_data_arr);

			echo '$db_notifications_arr';
			\Debug::dump($db_notifications_arr);

			echo '$total';
			\Debug::dump($total);

			echo '$save_unread_id_arr';
			\Debug::dump($save_unread_id_arr);



		}


		return array('save_count' => 0);

	}

}
