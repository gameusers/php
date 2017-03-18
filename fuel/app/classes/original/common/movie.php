<?php

namespace Original\Common;

class Movie
{
	
	/**
	* 画像削除
	*
	* @param string $path パス
	* @param string $name 画像の名前
	* @param array $number_arr 番号
	*/
	public function return_type_id($url)
	{
		
		$type = null;
		$id = null;
		
		$parse_arr = parse_url($url);
		
		
		// --------------------------------------------------
		//    タイプ設定
		// --------------------------------------------------
		
		if (isset($parse_arr['host']))
		{
			if (strpos($parse_arr['host'], 'youtube.com') !== false or strpos($parse_arr['host'], 'youtu.be') !== false)
			{
				$type = 'youtube';
			}
		}
		
		
		// --------------------------------------------------
		//    ID抽出
		// --------------------------------------------------
		
		if (isset($parse_arr['query']))
		{
			parse_str($parse_arr['query'], $qs);
			//var_dump($qs);
			
			if (isset($qs['v']))
			{
				$id = $qs['v'];
			}
			else if(isset($qs['vi']))
			{
				$id = $qs['vi'];
			}
		}
		
		if (empty($id) and isset($parse_arr['path']))
		{
			$path = explode('/', trim($parse_arr['path'], '/'));
			$id = $path[count($path)-1];
		}
		
		//var_dump($id);
		
		
		if (isset($type, $id))
		{
			return array('type' => $type, 'id' => $id);
		}
		else
		{
			return false;
		}
		
	}
	

}