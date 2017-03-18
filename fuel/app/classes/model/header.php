<?php

class Model_Header extends Model_Crud
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------



	/**
	* ヘッダー用にデータを取得する
	* @param array $arr
	* @return array
	*/
	public function select_header(array $arr): array
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$community_no = $arr['community_no'] ?? null;

		$language = $arr['language'] ?? 'ja';
		$game_no_arr = $arr['game_no_arr'] ?? null;


		// --------------------------------------------------
		//   コミュニティのヒーローイメージを取得
		// --------------------------------------------------

		if ($community_no)
		{
			// --------------------------------------------------
			//   画像取得
			// --------------------------------------------------

			$query = DB::select(
				array('image_id', 'imageId'),
				array('renewal_date', 'renewalDate'),
				array('community_no', 'communityNo')
			)->from('image');

			$query->where('on_off', '=', 1);
			$query->where('type', '=', 'hero_community');
			$query->where('community_no', '=', $community_no);

			$query->limit(1);
			$query->offset(0);

			$image_arr = $query->execute()->current();


			// --------------------------------------------------
			//   コミュニティ取得
			// --------------------------------------------------

			$query = DB::select(
				array('renewal_date', 'communityRenewalDate'),
				array('community_id', 'communityId'),
				array('name', 'communityName')
			)->from('community');

			$query->where('community_no', '=', $community_no);

			$query->limit(1);
			$query->offset(0);

			$community_arr = $query->execute()->current();


			// --------------------------------------------------
			//   配列合成
			// --------------------------------------------------

			$image_arr = $image_arr ?? [];
			$header_arr = array_merge($image_arr, $community_arr);

		}

// \Debug::dump($header_arr);

		// --------------------------------------------------
		//   コミュニティのヒーローイメージがない場合
		//   またはゲームページの場合
		// --------------------------------------------------

		if (empty($header_arr['imageId']))
		{

			// --------------------------------------------------
			//   画像取得
			// --------------------------------------------------

			$query = DB::select(
				array('image_id', 'imageId'),
				array('renewal_date', 'renewalDate'),
				array('game_no', 'gameNo')
			)->from('image');

			$query->where('on_off', '=', 1);
			$query->where('type', '=', 'hero_game');
			if ($game_no_arr) $query->where('game_no', 'in', $game_no_arr);

			$query->order_by(DB::expr('RAND()'));
			$query->limit(1);
			$query->offset(0);

			$image_arr = $query->execute()->current();


			// --------------------------------------------------
			//   Game No 設定
			// --------------------------------------------------

			$game_no = $image_arr['gameNo'] ?? $game_no_arr[0];
// \Debug::dump($image_arr, $game_no);

			// --------------------------------------------------
			//   ゲーム取得
			// --------------------------------------------------

			$query = DB::select(
				array('game_no', 'gameNo'),
				array('renewal_date', 'gameRenewalDate'),
				array('id', 'gameId'),
				array('name_' . $language, 'gameName'),
				array('subtitle', 'gameSubtitle'),
				array('thumbnail', 'gameThumbnail'),
				array('hardware', 'hardwareObj'),
				array('genre', 'genreObj'),
				array('release_date_1', 'releaseDate1'),
				array('release_date_2', 'releaseDate2'),
				array('release_date_3', 'releaseDate3'),
				array('release_date_4', 'releaseDate4'),
				array('release_date_5', 'releaseDate5'),
				array('players_max', 'playersMax'),
				array('developer', 'developerObj')
			)->from('game_data');

			$query->where('game_no', '=', $game_no);

			$query->limit(1);
			$query->offset(0);

			$game_data_arr = $query->execute()->current();


			// --------------------------------------------------
			//   配列合成
			// --------------------------------------------------

			$image_arr = $image_arr ?? [];
			$header_arr = array_merge($image_arr, $game_data_arr);
// $header_arr['gameName'] = '<script>alert("1)</script>';

			// --------------------------------------------------
			//   型変換
			// --------------------------------------------------

			if ($header_arr['playersMax']) $header_arr['playersMax'] = (int) $header_arr['playersMax'];
			// \Debug::dump((int) $header_arr['playersMax']);

			// --------------------------------------------------
			//   ハードウェアNo・ジャンルNo・開発No処理
			// --------------------------------------------------

			$original_func_common = new \Original\Func\Common();
			$hardware_no_arr = $original_func_common->return_db_array('db_php', $header_arr['hardwareObj']);
			$genre_no_arr = $original_func_common->return_db_array('db_php', $header_arr['genreObj']);
			$developer_no_arr = $original_func_common->return_db_array('db_php', $header_arr['developerObj']);


			// --------------------------------------------------
			//   ハードウェア取得
			// --------------------------------------------------

			if (count($hardware_no_arr) > 0)
			{
				$query = DB::select(array('name_' . $language, 'name'), array('abbreviation_' . $language, 'abbreviation'))->from('hardware');
				$query->where('hardware_no', 'in', $hardware_no_arr);
				$db_hardware_arr = $query->execute()->as_array();

				$header_arr['hardwareObj'] = $db_hardware_arr;
			}


			// --------------------------------------------------
			//   ジャンル取得
			// --------------------------------------------------

			if (count($genre_no_arr) > 0)
			{
				$query = DB::select('name')->from('data_genre');
				$query->where('genre_no', 'in', $genre_no_arr);
				$db_genre_arr = $query->execute()->as_array();

				$header_arr['genreObj'] = $db_genre_arr;
			}
// \Debug::dump($genre_no_arr);

			// --------------------------------------------------
			//   開発取得
			// --------------------------------------------------

			if (count($developer_no_arr) > 0)
			{
				$query = DB::select('name', 'studio')->from('data_developer');
				$query->where('developer_no', 'in', $developer_no_arr);
				$db_developer_arr = $query->execute()->as_array();
// \Debug::dump($db_developer_arr);
				$header_arr['developerObj'] = $db_developer_arr;
			}
// $header_arr['developerObj'] = null;

			// --------------------------------------------------
			//   リンク取得
			// --------------------------------------------------

			$query = DB::select('type', 'name', 'url')->from('data_link');
			$query->where('game_no', '=', $game_no);
			$db_link_arr = $query->execute()->as_array();
			$header_arr['linkObj'] = $db_link_arr;
// $header_arr['linkObj'][0]['type'] = 'official';
// $header_arr['linkObj'][0]['url'] = '<script>alert("1)</script>';

		}


		// \Debug::dump($header_arr);


		return $header_arr;

	}


}
