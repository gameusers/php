<?php

namespace Original\Common;

class Image
{
	
	/**
	* 画像削除
	*
	* @param string $path パス
	*/
	public function delete($path)
	{
		if (file_exists($path))
		{
			unlink($path);
			return true;
		}
		
		return false;
	}
	
	
	/**
	* 画像保存
	*
	* @param string $path パス
	* @param string $name 画像の名前
	* @param array $delete_original オリジナルを削除する場合はTrue
	* @return array 画像のサイズが記述された配列
	*/
	public function zebra_image_save($path, $path_original, $path_resize, $quality, $max_widht, $max_height, $delete_original = false)
	{
		
		if (count($_FILES) > 0)
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
					$image->target_path = $path_original;
					$image->jpeg_quality = $quality;
					$image->resize(0, 0, ZEBRA_IMAGE_CROP_CENTER);
					
					$original_width = $image->source_width;
					$original_height = $image->source_height;
					
					// サイズが大きすぎる場合はサイズを固定する
					if ($original_width > $max_widht or $original_height > $max_height)
					{
						if ($original_width > $original_height)
						{
							$image_width = $max_widht;
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
					
					// オリジナル削除
					if ($delete_original and file_exists($image->target_path)) unlink($image->target_path);
					
					//var_dump($image);
					
					
					// ----- リサイズ画像保存 -----
					
					$image = new \Zebra_Image();
					$image->source_path = $value['saved_to'] . $value['saved_as'];
					$image->target_path = $path_resize;
					$image->jpeg_quality = $quality;
					$image->preserve_aspect_ratio = true;
					$image->enlarge_smaller_images = true;
					$image->preserve_time = true;
					$image->resize($image_width, $image_height, ZEBRA_IMAGE_CROP_CENTER);
					//var_dump($image);
					
					
					
					// 元画像削除
					if (file_exists($value['saved_to'] . $value['saved_as'])) unlink($value['saved_to'] . $value['saved_as']);
					
					
					// データベースに保存する画像情報
					$image_arr[$image_number]['width'] = $image_width;
					$image_arr[$image_number]['height'] = $image_height;
					
				}
				
			}
			
		}
		
		//var_dump($image_arr);
		
		
		return $image_arr;
		
	}

}