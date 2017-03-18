<?php

class Model_Bbs extends Model_Crud
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




	// --------------------------------------------------
	//   コミュニティ
	// --------------------------------------------------


	// --------------------------------------------------
	//   BBS
	// --------------------------------------------------

	// ------------------------------
	//    取得
	// ------------------------------


	/**
	* BBS取得 SNS送信用
	* @param array $arr
	* @return array
	*/
	public function select_bbs_for_sns($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		if ($arr['type'] === 'gc_bbs_thread') $table_name = 'bbs_thread_gc';
		else if ($arr['type'] === 'gc_bbs_comment') $table_name = 'bbs_comment_gc';
		else if ($arr['type'] === 'gc_bbs_reply') $table_name = 'bbs_reply_gc';

		$bbs_id = $arr['bbs_id'];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('*')->from($table_name);

		$query->where('bbs_id', '=', $bbs_id);
		$query->where('on_off', '=', 1);
		$result_arr = $query->execute()->current();

		return $result_arr;

	}



	/**
	* 個別BBSデータ 取得
	* @param array $arr
	* @return array
	*/
	public function select_bbs_individual($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$type = $arr['type'];
		$game_no = $arr['game_no'] ?? null;
		$community_no = $arr['community_no'] ?? null;
		$bbs_id = $arr['bbs_id'];

		$return_arr = null;
		$user_no_arr = [];
		$profile_no_arr = [];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		if ($type === 'gc' or $type === 'gc_appoint')
		{

			// ---------------------------------------------
			//   スレッド 検索
			// ---------------------------------------------

			$query = DB::select('*')->from('bbs_thread_gc');
			$query->where('on_off', '=', 1);
			$query->where('bbs_id', '=', $bbs_id);
			$query->where('game_no', '=', $game_no);
			$result_arr = $query->execute()->as_array();

			if (count($result_arr) > 0)
			{
				$return_arr = array(
					'type' => 'bbs_thread_gc',
					'bbs_thread_no' => $result_arr[0]['bbs_thread_no']
				);
				return $return_arr;
			}


			// ---------------------------------------------
			//   コメント 検索
			// ---------------------------------------------

			$query = DB::select('*')->from('bbs_comment_gc');
			$query->where('on_off', '=', 1);
			$query->where('bbs_id', '=', $bbs_id);
			$query->where('game_no', '=', $game_no);
			$result_arr = $query->execute()->as_array();

			if (count($result_arr) > 0)
			{

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$bbs_thread_no = $result_arr[0]['bbs_thread_no'];
				$bbs_comment_no = $result_arr[0]['bbs_comment_no'];

				$return_arr['type'] = 'bbs_comment_gc';
				$return_arr['comment_arr'][$bbs_thread_no] = $result_arr;

				// $return_arr = array(
				// 	'type' => 'bbs_comment_gc',
				// 	'comment_arr' => $result_arr[0]
				// );


				$query = DB::select('*')->from('bbs_reply_gc');
				$query->where('on_off', '=', 1);
				$query->where('game_no', '=', $game_no);
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$query->limit(LIMIT_BBS_REPLY);
				$query->offset(0);
				$result_arr = $query->execute()->as_array();

				//\Debug::dump($result_arr);

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$return_arr['reply_arr'][$bbs_comment_no] = $result_arr;



				$query = DB::select('*')->from('bbs_thread_gc');
				$query->where('on_off', '=', 1);
				$query->where('game_no', '=', $game_no);
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$result_arr = $query->execute()->as_array();

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$return_arr['thread_arr'] = $result_arr;
				$return_arr['user_no_arr'] = $user_no_arr;
				$return_arr['profile_no_arr'] = $profile_no_arr;


				return $return_arr;
			}


			// ---------------------------------------------
			//   返信 検索
			// ---------------------------------------------

			$query = DB::select('*')->from('bbs_reply_gc');
			$query->where('on_off', '=', 1);
			$query->where('bbs_id', '=', $bbs_id);
			$query->where('game_no', '=', $game_no);
			$result_arr = $query->execute()->as_array();

			if (count($result_arr) > 0)
			{

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$bbs_thread_no = $result_arr[0]['bbs_thread_no'];
				$bbs_comment_no = $result_arr[0]['bbs_comment_no'];

				$return_arr['type'] = 'bbs_reply_gc';
				$return_arr['reply_arr'][$bbs_comment_no] = $result_arr;



				$query = DB::select('*')->from('bbs_comment_gc');
				$query->where('on_off', '=', 1);
				$query->where('game_no', '=', $game_no);
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$result_arr = $query->execute()->as_array();

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$return_arr['comment_arr'][$bbs_thread_no] = $result_arr;



				$query = DB::select('*')->from('bbs_thread_gc');
				$query->where('on_off', '=', 1);
				$query->where('game_no', '=', $game_no);
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$result_arr = $query->execute()->as_array();

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$return_arr['thread_arr'] = $result_arr;
				$return_arr['user_no_arr'] = $user_no_arr;
				$return_arr['profile_no_arr'] = $profile_no_arr;


				return $return_arr;
			}

		}

		else if ($type === 'uc' or $type === 'uc_appoint')
		{

			// ---------------------------------------------
			//   スレッド 検索
			// ---------------------------------------------

			$query = DB::select('*')->from('bbs_thread');
			$query->where('on_off', '=', 1);
			$query->where('bbs_id', '=', $bbs_id);
			$query->where('community_no', '=', $community_no);
			$result_arr = $query->execute()->as_array();

			if (count($result_arr) > 0)
			{
				$return_arr = array(
					'type' => 'bbs_thread_uc',
					'bbs_thread_no' => $result_arr[0]['bbs_thread_no']
				);
				return $return_arr;
			}


			// ---------------------------------------------
			//   コメント 検索
			// ---------------------------------------------

			$query = DB::select('*')->from('bbs_comment');
			$query->where('on_off', '=', 1);
			$query->where('bbs_id', '=', $bbs_id);
			$query->where('community_no', '=', $community_no);
			$result_arr = $query->execute()->as_array();

			if (count($result_arr) > 0)
			{

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$bbs_thread_no = $result_arr[0]['bbs_thread_no'];
				$bbs_comment_no = $result_arr[0]['bbs_comment_no'];

				$return_arr['type'] = 'bbs_comment_uc';
				$return_arr['comment_arr'][$bbs_thread_no] = $result_arr;

				// $return_arr = array(
				// 	'type' => 'bbs_comment_gc',
				// 	'comment_arr' => $result_arr[0]
				// );


				$query = DB::select('*')->from('bbs_reply');
				$query->where('on_off', '=', 1);
				$query->where('community_no', '=', $community_no);
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$query->limit(LIMIT_BBS_REPLY);
				$query->offset(0);
				$result_arr = $query->execute()->as_array();

				//\Debug::dump($result_arr);

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$return_arr['reply_arr'][$bbs_comment_no] = $result_arr;



				$query = DB::select('*')->from('bbs_thread');
				$query->where('on_off', '=', 1);
				$query->where('community_no', '=', $community_no);
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$result_arr = $query->execute()->as_array();

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$return_arr['thread_arr'] = $result_arr;
				$return_arr['user_no_arr'] = $user_no_arr;
				$return_arr['profile_no_arr'] = $profile_no_arr;


				return $return_arr;
			}


			// ---------------------------------------------
			//   返信 検索
			// ---------------------------------------------

			$query = DB::select('*')->from('bbs_reply');
			$query->where('on_off', '=', 1);
			$query->where('bbs_id', '=', $bbs_id);
			$query->where('community_no', '=', $community_no);
			$result_arr = $query->execute()->as_array();

			if (count($result_arr) > 0)
			{

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$bbs_thread_no = $result_arr[0]['bbs_thread_no'];
				$bbs_comment_no = $result_arr[0]['bbs_comment_no'];

				$return_arr['type'] = 'bbs_reply_uc';
				$return_arr['reply_arr'][$bbs_comment_no] = $result_arr;



				$query = DB::select('*')->from('bbs_comment');
				$query->where('on_off', '=', 1);
				$query->where('community_no', '=', $community_no);
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$result_arr = $query->execute()->as_array();

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$return_arr['comment_arr'][$bbs_thread_no] = $result_arr;



				$query = DB::select('*')->from('bbs_thread');
				$query->where('on_off', '=', 1);
				$query->where('community_no', '=', $community_no);
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$result_arr = $query->execute()->as_array();

				foreach ($result_arr as $key => &$value)
				{
					if ($value['user_no']) array_push($user_no_arr, $value['user_no']);
					if ($value['profile_no']) array_push($profile_no_arr, $value['profile_no']);
					if ($value['image']) $value['image'] = unserialize($value['image']);
					if ($value['movie']) $value['movie'] = unserialize($value['movie']);
				}
				unset($value);

				$return_arr['thread_arr'] = $result_arr;
				$return_arr['user_no_arr'] = $user_no_arr;
				$return_arr['profile_no_arr'] = $profile_no_arr;


				return $return_arr;
			}

		}


		return $return_arr;

	}




	/**
	* BBS ID 作成
	* @param array $arr
	* @return array
	*/
	public function get_free_bbs_id($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$original_common_text = new Original\Common\Text();

		$type = $arr['type'];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		for ($i=0; $i < 5; $i++)
		{

			if ($type === 'gc')
			{

				$bbs_id = $original_common_text->random_text_lowercase(16);
//$bbs_id = '12dk5dgg00cbhdba';

				// ---------------------------------------------
				//   スレッド 検索
				// ---------------------------------------------

				$query = DB::select('bbs_thread_no')->from('bbs_thread_gc');
				$query->where('on_off', '=', 1);
				$query->where('bbs_id', '=', $bbs_id);
				$db_thread_arr = $query->execute()->as_array();


				// ---------------------------------------------
				//   コメント 検索
				// ---------------------------------------------

				$query = DB::select('*')->from('bbs_comment_gc');
				$query->where('on_off', '=', 1);
				$query->where('bbs_id', '=', $bbs_id);
				$db_comment_arr = $query->execute()->as_array();


				// ---------------------------------------------
				//   返信 検索
				// ---------------------------------------------

				$query = DB::select('*')->from('bbs_reply_gc');
				$query->where('on_off', '=', 1);
				$query->where('bbs_id', '=', $bbs_id);
				$db_reply_arr = $query->execute()->as_array();

				if (count($db_thread_arr) === 0 and count($db_comment_arr) === 0 and count($db_reply_arr) === 0)
				{
					return $bbs_id;
				}

			}
			else if ($type === 'uc')
			{

				$bbs_id = $original_common_text->random_text_lowercase(16);
//$bbs_id = '12dk5dgg00cbhdba';

				// ---------------------------------------------
				//   スレッド 検索
				// ---------------------------------------------

				$query = DB::select('bbs_thread_no')->from('bbs_thread');
				$query->where('on_off', '=', 1);
				$query->where('bbs_id', '=', $bbs_id);
				$db_thread_arr = $query->execute()->as_array();


				// ---------------------------------------------
				//   コメント 検索
				// ---------------------------------------------

				$query = DB::select('*')->from('bbs_comment');
				$query->where('on_off', '=', 1);
				$query->where('bbs_id', '=', $bbs_id);
				$db_comment_arr = $query->execute()->as_array();


				// ---------------------------------------------
				//   返信 検索
				// ---------------------------------------------

				$query = DB::select('*')->from('bbs_reply');
				$query->where('on_off', '=', 1);
				$query->where('bbs_id', '=', $bbs_id);
				$db_reply_arr = $query->execute()->as_array();

				if (count($db_thread_arr) === 0 and count($db_comment_arr) === 0 and count($db_reply_arr) === 0)
				{
					return $bbs_id;
				}

			}

		}

		exit();

	}





	/**
	* スレッド取得　/ GC
	* @param array $arr
	* @return array
	*/
	public function get_bbs_thread_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		$limit = (isset($arr['limit'])) ? $arr['limit'] : null;

		// 個別に読み込む場合
		$bbs_thread_no = (isset($arr['bbs_thread_no'])) ? $arr['bbs_thread_no'] : null;

		// ゲームコミュニティ
		$game_no = (isset($arr['game_no'])) ? $arr['game_no'] : null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('bbs_thread_no', 'bbs_id', 'regi_date', 'renewal_date', 'sort_date', 'game_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'title', 'comment', 'image', 'movie', 'comment_total', 'reply_total', 'good', 'host', 'user_agent')->from('bbs_thread_gc');

		if ($bbs_thread_no) $query->where('bbs_thread_no', '=', $bbs_thread_no);
		if ($game_no) $query->where('game_no', '=', $game_no);

		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* スレッド取得　/ UC
	* @param array $arr
	* @return array
	*/
	public function get_bbs_thread_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		$limit = (isset($arr['limit'])) ? $arr['limit'] : null;

		// 個別に読み込む場合
		$bbs_thread_no = (isset($arr['bbs_thread_no'])) ? $arr['bbs_thread_no'] : null;

		// ユーザーコミュニティ
		$community_no = (isset($arr['community_no'])) ? $arr['community_no'] : null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('bbs_thread_no', 'bbs_id', 'regi_date', 'renewal_date', 'sort_date', 'community_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'title', 'comment', 'image', 'movie', 'comment_total', 'reply_total', 'good', 'host', 'user_agent')->from('bbs_thread');

		if ($bbs_thread_no) $query->where('bbs_thread_no', '=', $bbs_thread_no);
		if ($community_no) $query->where('community_no', '=', $community_no);

		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* ゲームコミュニティ　スレッド総数取得　更新日 2015/9
	* @param integer $community_no コミュニティNo
	* @return array
	*/
	public function get_bbs_thread_total_gc($game_no)
	{
		$language = 'ja';

		$query = DB::select('bbs_thread_total_' . $language)->from('game_community');
		$query->where('game_no', '=', $game_no);
		$arr = $query->execute()->current();
		//\Debug::dump($arr['bbs_thread_total_' . $language]);
		return $arr['bbs_thread_total_' . $language];
	}


	/**
	* ユーザーコミュニティ　スレッド総数取得　更新日 2015/9
	* @param integer $community_no コミュニティNo
	* @return array
	*/
	public function get_bbs_thread_total_uc($community_no)
	{
		$query = DB::select('bbs_thread_total')->from('community');
		$query->where('community_no', '=', $community_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr['bbs_thread_total'];
	}


	/**
	* スレッドを取得　 スレッドNoで検索
	* @param integer $community_no コミュニティNo
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array
	*/
	/*
	public function get_bbs_thread($bbs_thread_no)
	{
		$query = DB::select('bbs_thread_no', 'renewal_date', 'community_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'title', 'comment', 'image', 'movie', 'comment_total', 'good', 'host', 'user_agent')->from('bbs_thread');
		$query->where('bbs_thread_no', '=', $bbs_thread_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}
	*/


	/**
	* コメント取得　/ GC
	* @param array $arr
	* @return array
	*/
	public function get_bbs_comment_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		$limit = (isset($arr['limit'])) ? $arr['limit'] : null;

		// まとめて読み込む場合
		$bbs_thread_no = (isset($arr['bbs_thread_no'])) ? $arr['bbs_thread_no'] : null;

		// 個別に読み込む場合
		$bbs_comment_no = (isset($arr['bbs_comment_no'])) ? $arr['bbs_comment_no'] : null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('bbs_comment_no', 'bbs_id', 'regi_date', 'renewal_date', 'game_no', 'bbs_thread_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'reply_total', 'good', 'host', 'user_agent')->from('bbs_comment_gc');

		if ($bbs_thread_no) $query->where('bbs_thread_no', '=', $bbs_thread_no);
		if ($bbs_comment_no) $query->where('bbs_comment_no', '=', $bbs_comment_no);

		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* コメント取得　/ UC
	* @param array $arr
	* @return array
	*/
	public function get_bbs_comment_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		$limit = (isset($arr['limit'])) ? $arr['limit'] : null;

		// まとめて読み込む場合
		$bbs_thread_no = (isset($arr['bbs_thread_no'])) ? $arr['bbs_thread_no'] : null;

		// 個別に読み込む場合
		$bbs_comment_no = (isset($arr['bbs_comment_no'])) ? $arr['bbs_comment_no'] : null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('bbs_comment_no', 'bbs_id', 'regi_date', 'renewal_date', 'community_no', 'bbs_thread_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'reply_total', 'good', 'host', 'user_agent')->from('bbs_comment');

		if ($bbs_thread_no) $query->where('bbs_thread_no', '=', $bbs_thread_no);
		if ($bbs_comment_no) $query->where('bbs_comment_no', '=', $bbs_comment_no);

		$query->where('on_off', '=', 1);
		$query->order_by('sort_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}




	/**
	* 返信取得　/ GC
	* @param array $arr
	* @return array
	*/
	public function get_bbs_reply_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		$limit = (isset($arr['limit'])) ? $arr['limit'] : null;

		// まとめて読み込む場合
		$bbs_comment_no = (isset($arr['bbs_comment_no'])) ? $arr['bbs_comment_no'] : null;

		// 個別に読み込む場合
		$bbs_reply_no = (isset($arr['bbs_reply_no'])) ? $arr['bbs_reply_no'] : null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('bbs_reply_no', 'bbs_id', 'regi_date', 'renewal_date', 'game_no', 'bbs_thread_no', 'bbs_comment_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'good', 'host', 'user_agent')->from('bbs_reply_gc');

		if ($bbs_comment_no) $query->where('bbs_comment_no', '=', $bbs_comment_no);
		if ($bbs_reply_no) $query->where('bbs_reply_no', '=', $bbs_reply_no);

		$query->where('on_off', '=', 1);
		$query->order_by('regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return array_reverse($arr);

	}



	/**
	* 返信取得　/ UC
	* @param array $arr
	* @return array
	*/
	public function get_bbs_reply_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		$limit = (isset($arr['limit'])) ? $arr['limit'] : null;

		// まとめて読み込む場合
		$bbs_comment_no = (isset($arr['bbs_comment_no'])) ? $arr['bbs_comment_no'] : null;

		// 個別に読み込む場合
		$bbs_reply_no = (isset($arr['bbs_reply_no'])) ? $arr['bbs_reply_no'] : null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('bbs_reply_no', 'bbs_id', 'regi_date', 'renewal_date', 'community_no', 'bbs_thread_no', 'bbs_comment_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'good', 'host', 'user_agent')->from('bbs_reply');

		if ($bbs_comment_no) $query->where('bbs_comment_no', '=', $bbs_comment_no);
		if ($bbs_reply_no) $query->where('bbs_reply_no', '=', $bbs_reply_no);

		$query->where('on_off', '=', 1);
		$query->order_by('regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return array_reverse($arr);

	}




	/**
	* 返信を取得　 返信Noで検索
	* @param integer $bbs_reply_no 返信No
	* @return array
	*/
	/*
	public function get_bbs_reply($bbs_reply_no)
	{
		$query = DB::select('bbs_reply_no', 'renewal_date', 'community_no', 'bbs_thread_no', 'bbs_comment_no', 'user_no', 'profile_no', 'anonymity', 'handle_name', 'comment', 'image', 'movie', 'good', 'host', 'user_agent')->from('bbs_reply');
		$query->where('bbs_reply_no', '=', $bbs_reply_no);
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->current();

		return $arr;
	}
	*/





	// ------------------------------
	//    更新
	// ------------------------------

	/**
	* スレッド挿入　GC
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_thread_gc($arr)
	{

		//$language = 'ja';

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_thread_arr = $arr['bbs_thread_arr'];


		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_thread_no')->from('bbs_thread_gc');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('game_no', '=', $bbs_thread_arr['game_no']);
		$query->where('title', '=', $bbs_thread_arr['title']);
		$query->where('comment', '=', $bbs_thread_arr['comment']);
		$query->where('host', '=', $bbs_thread_arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();


		if (isset($dupli_arr)) return array('error' => true);


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();



			// --------------------------------------------------
			//   スレッド挿入
			// --------------------------------------------------

			$query = DB::insert('bbs_thread_gc');
			$query->set($bbs_thread_arr);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date＆スレッド数更新
			// --------------------------------------------------

			$query = DB::update('game_community');

			$query->set(array(
				'sort_date' => $bbs_thread_arr['renewal_date'],
				'bbs_renewal_date_ja' => $bbs_thread_arr['renewal_date'],
				'bbs_thread_total_ja' => DB::expr('bbs_thread_total_ja + 1'))
			);

			$query->where('game_no', '=', $bbs_thread_arr['game_no']);
			$result_community_arr = $query->execute();



			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('bbs_thread_no' => $result_thread_arr[0], 'error' => false);

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
	* スレッド挿入　UC
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_thread_uc($arr)
	{

		//$language = 'ja';

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_thread_arr = $arr['bbs_thread_arr'];


		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_thread_no')->from('bbs_thread');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('community_no', '=', $bbs_thread_arr['community_no']);
		$query->where('title', '=', $bbs_thread_arr['title']);
		$query->where('comment', '=', $bbs_thread_arr['comment']);
		$query->where('host', '=', $bbs_thread_arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();


		if (isset($dupli_arr)) return array('error' => true);


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   スレッド挿入
			// --------------------------------------------------

			$query = DB::insert('bbs_thread');
			$query->set($bbs_thread_arr);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date、BBS更新時間、スレッド数更新
			// --------------------------------------------------

			$query = DB::update('community');

			$query->set(array(
				'sort_date' => $bbs_thread_arr['renewal_date'],
				'bbs_renewal_date' => $bbs_thread_arr['renewal_date'],
				'bbs_thread_total' => DB::expr('bbs_thread_total + 1')
			));

			$query->where('community_no', '=', $bbs_thread_arr['community_no']);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('bbs_thread_no' => $result_thread_arr[0], 'error' => false);

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
	* スレッド更新　GC
	* @param array $arr 配列
	* @return array
	*/
	public function update_bbs_thread_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$game_no = $arr['game_no'];
		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_thread_arr = $arr['bbs_thread_arr'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();



			// --------------------------------------------------
			//   スレッド更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');
			$query->set($bbs_thread_arr);
			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('game_community');
			$query->set(array('sort_date' => $bbs_thread_arr['renewal_date']));
			$query->where('game_no', '=', $game_no);
			$result_community_arr = $query->execute();



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
			//echo $e->getMessage();
			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}





	/**
	* スレッド更新　UC
	* @param array $arr 配列
	* @return array
	*/
	public function update_bbs_thread_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$community_no = $arr['community_no'];
		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_thread_arr = $arr['bbs_thread_arr'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   スレッド更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');
			$query->set($bbs_thread_arr);
			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date、BBS更新時間、更新
			// --------------------------------------------------

			$query = DB::update('community');

			$query->set(array(
				'sort_date' => $bbs_thread_arr['renewal_date'],
				'bbs_renewal_date' => $bbs_thread_arr['renewal_date']
			));

			$query->where('community_no', '=', $community_no);
			$result_community_arr = $query->execute();


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
			//echo $e->getMessage();
			// --------------------------------------------------
			//   ロールバック
			// --------------------------------------------------

			DB::rollback_transaction();

		}

		return false;

	}




	/**
	* スレッド削除
	* @param array $arr 配列
	* @return array
	*/
	public function delete_bbs_thread_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$game_no = $arr['game_no'];
		$bbs_thread_no = $arr['bbs_thread_no'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   スレッド更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'on_off' => null
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのスレッド総数更新
			// --------------------------------------------------

			$query = DB::update('game_community');

			$query->set(array(
				'bbs_thread_total_ja' => DB::expr('bbs_thread_total_ja - 1'))
			);

			$query->where('game_no', '=', $game_no);
			$result_community_arr = $query->execute();


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



	/**
	* スレッド削除　UC
	* @param array $arr 配列
	* @return array
	*/
	public function delete_bbs_thread_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$community_no = $arr['community_no'];
		$bbs_thread_no = $arr['bbs_thread_no'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   スレッド更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');

			$query->set(array(
				'on_off' => null
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのスレッド総数更新
			// --------------------------------------------------

			$query = DB::update('community');

			$query->set(array(
				'bbs_thread_total' => DB::expr('bbs_thread_total - 1')
			));

			$query->where('community_no', '=', $community_no);
			$result_community_arr = $query->execute();



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





	/**
	* コメント挿入
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_comment_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_comment_arr = $arr['bbs_comment_arr'];


		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_comment_no')->from('bbs_comment_gc');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('bbs_thread_no', '=', $bbs_comment_arr['bbs_thread_no']);
		$query->where('comment', '=', $bbs_comment_arr['comment']);
		$query->where('host', '=', $bbs_comment_arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();

		if (isset($dupli_arr)) return array('error' => true);


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   コメント更新
			// --------------------------------------------------

			$query = DB::insert('bbs_comment_gc');
			$query->set($bbs_comment_arr);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date＆コメント数更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'sort_date' => $bbs_comment_arr['renewal_date'],
				'comment_total' => DB::expr('comment_total + 1')
			));

			$query->where('bbs_thread_no', '=', $bbs_comment_arr['bbs_thread_no']);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   ゲームコミュニティ　Sort Date 掲示板更新時間　更新
			// --------------------------------------------------

			$query = DB::update('game_community');

			$query->set(array(
				'sort_date' => $bbs_comment_arr['renewal_date'],
				'bbs_renewal_date_ja' => $bbs_comment_arr['renewal_date']
			));

			$query->where('game_no', '=', $bbs_comment_arr['game_no']);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('bbs_comment_no' => $result_comment_arr[0], 'error' => false);

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
	* コメント挿入　UC
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_comment_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_comment_arr = $arr['bbs_comment_arr'];


		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_comment_no')->from('bbs_comment');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('bbs_thread_no', '=', $bbs_comment_arr['bbs_thread_no']);
		$query->where('comment', '=', $bbs_comment_arr['comment']);
		$query->where('host', '=', $bbs_comment_arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();

		if (isset($dupli_arr)) return array('error' => true);


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   コメント挿入
			// --------------------------------------------------

			$query = DB::insert('bbs_comment');
			$query->set($bbs_comment_arr);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date＆コメント数更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');

			$query->set(array(
				'sort_date' => $bbs_comment_arr['renewal_date'],
				'comment_total' => DB::expr('comment_total + 1')
			));

			$query->where('bbs_thread_no', '=', $bbs_comment_arr['bbs_thread_no']);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date、BBS更新時間、更新
			// --------------------------------------------------

			$query = DB::update('community');

			$query->set(array(
				'sort_date' => $bbs_comment_arr['renewal_date'],
				'bbs_renewal_date' => $bbs_comment_arr['renewal_date']
			));

			$query->where('community_no', '=', $bbs_comment_arr['community_no']);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('bbs_comment_no' => $result_comment_arr[0], 'error' => false);

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
	* コメント更新　UC
	* @param array $arr 配列
	* @return array
	*/
	public function update_bbs_comment_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$game_no = $arr['game_no'];
		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_comment_no = $arr['bbs_comment_no'];
		$bbs_comment_arr = $arr['bbs_comment_arr'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   コメント更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment_gc');
			$query->set($bbs_comment_arr);
			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date 更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'sort_date' => $bbs_comment_arr['renewal_date']
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date、BBS更新時間、更新
			// --------------------------------------------------

			$query = DB::update('game_community');

			$query->set(array(
				'sort_date' => $bbs_comment_arr['renewal_date'],
				'bbs_renewal_date_ja' => $bbs_comment_arr['renewal_date']
			));

			$query->where('game_no', '=', $game_no);
			$result_community_arr = $query->execute();


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




	/**
	* コメント更新　UC
	* @param array $arr 配列
	* @return array
	*/
	public function update_bbs_comment_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$community_no = $arr['community_no'];
		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_comment_no = $arr['bbs_comment_no'];
		$bbs_comment_arr = $arr['bbs_comment_arr'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   コメント更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');
			$query->set($bbs_comment_arr);
			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date 更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');

			$query->set(array(
				'sort_date' => $bbs_comment_arr['renewal_date']
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date、BBS更新時間、更新
			// --------------------------------------------------

			$query = DB::update('community');

			$query->set(array(
				'sort_date' => $bbs_comment_arr['renewal_date'],
				'bbs_renewal_date' => $bbs_comment_arr['renewal_date']
			));

			$query->where('community_no', '=', $community_no);
			$result_community_arr = $query->execute();


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




	/**
	* コメント削除　GC
	* @param array $arr 配列
	* @return array
	*/
	public function delete_bbs_comment_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_comment_no = $arr['bbs_comment_no'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信総数取得
			// --------------------------------------------------

			$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_reply_gc');
			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$query->where('on_off', '=', 1);
			$reply_total = $query->execute()->current()['total'];
// \Debug::dump($reply_total);

			// --------------------------------------------------
			//   スレッド更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment_gc');

			$query->set(array(
				'on_off' => null
			));

			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのコメント数更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'comment_total' => DB::expr('comment_total - 1'),
				'reply_total' => DB::expr('reply_total - ' . $reply_total)
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


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




	/**
	* コメント削除　UC
	* @param array $arr 配列
	* @return array
	*/
	public function delete_bbs_comment_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_comment_no = $arr['bbs_comment_no'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信総数取得
			// --------------------------------------------------

			$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_reply');
			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$query->where('on_off', '=', 1);
			$reply_total = $query->execute()->current()['total'];

			//\Debug::dump($reply_total, $bbs_thread_no);


			// --------------------------------------------------
			//   コメント更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');

			$query->set(array(
				'on_off' => null
			));

			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのコメント数、返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');

			$query->set(array(
				'comment_total' => DB::expr('comment_total - 1'),
				'reply_total' => DB::expr('reply_total - ' . $reply_total)
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


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





	/**
	* 返信挿入　GC
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_reply_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_reply_arr = $arr['bbs_reply_arr'];


		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_reply_no')->from('bbs_reply_gc');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('bbs_comment_no', '=', $bbs_reply_arr['bbs_comment_no']);
		$query->where('comment', '=', $bbs_reply_arr['comment']);
		$query->where('host', '=', $bbs_reply_arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();

		if (isset($dupli_arr)) return array('error' => true);


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			$query = DB::insert('bbs_reply_gc');
			$query->set($bbs_reply_arr);
			$result_reply_arr = $query->execute();


			// --------------------------------------------------
			//   コメントのSort Date＆返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment_gc');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date'],
				'reply_total' => DB::expr('reply_total + 1')
			));

			$query->where('bbs_comment_no', '=', $bbs_reply_arr['bbs_comment_no']);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date'],
				'reply_total' => DB::expr('reply_total + 1')
			));

			$query->where('bbs_thread_no', '=', $bbs_reply_arr['bbs_thread_no']);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('game_community');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date']
			));

			$query->where('game_no', '=', $bbs_reply_arr['game_no']);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('bbs_reply_no' => $result_reply_arr[0], 'error' => false);

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
	* 返信挿入　UC
	* @param array $arr 配列
	* @return array
	*/
	public function insert_bbs_reply_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_reply_arr = $arr['bbs_reply_arr'];


		// --------------------------------------------------
		//   30分前の日時を取得
		// --------------------------------------------------

		$original_common_date = new \Original\Common\Date();
		$datetime_past = $original_common_date->sql_format("-30minutes");


		// --------------------------------------------------
		//   重複チェック
		// --------------------------------------------------

		$query = DB::select('bbs_reply_no')->from('bbs_reply');
		$query->where('regi_date', '>', $datetime_past);
		$query->where('bbs_comment_no', '=', $bbs_reply_arr['bbs_comment_no']);
		$query->where('comment', '=', $bbs_reply_arr['comment']);
		$query->where('host', '=', $bbs_reply_arr['host']);
		$query->where('on_off', '=', 1);
		$dupli_arr = $query->execute()->current();

		if (isset($dupli_arr)) return array('error' => true);


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			$query = DB::insert('bbs_reply');
			$query->set($bbs_reply_arr);
			$result_reply_arr = $query->execute();


			// --------------------------------------------------
			//   コメントのSort Date＆返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date'],
				'reply_total' => DB::expr('reply_total + 1')
			));

			$query->where('bbs_comment_no', '=', $bbs_reply_arr['bbs_comment_no']);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date'],
				'reply_total' => DB::expr('reply_total + 1')
			));

			$query->where('bbs_thread_no', '=', $bbs_reply_arr['bbs_thread_no']);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('community');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date']
			));

			$query->where('community_no', '=', $bbs_reply_arr['community_no']);
			$result_community_arr = $query->execute();


			// --------------------------------------------------
			//   コミット
			// --------------------------------------------------

			DB::commit_transaction();


			// --------------------------------------------------
			//   結果
			// --------------------------------------------------

			return array('bbs_reply_no' => $result_reply_arr[0], 'error' => false);

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
	* 返信更新　GC
	* @param integer $bbs_reply_no
	* @param array $arr 保存用配列
	* @return array
	*/
	public function update_bbs_reply_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$game_no = $arr['game_no'];
		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_comment_no = $arr['bbs_comment_no'];
		$bbs_reply_no = $arr['bbs_reply_no'];
		$bbs_reply_arr = $arr['bbs_reply_arr'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			$query = DB::update('bbs_reply_gc');
			$query->set($bbs_reply_arr);
			$query->where('bbs_reply_no', '=', $bbs_reply_no);
			$result_reply_arr = $query->execute();


			// --------------------------------------------------
			//   コメントのSort Date＆返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment_gc');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date']
			));

			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date 更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date']
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('game_community');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date']
			));

			$query->where('game_no', '=', $game_no);
			$result_community_arr = $query->execute();


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





	/**
	* 返信更新　UC
	* @param integer $bbs_reply_no
	* @param array $arr 保存用配列
	* @return array
	*/
	public function update_bbs_reply_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$community_no = $arr['community_no'];
		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_comment_no = $arr['bbs_comment_no'];
		$bbs_reply_no = $arr['bbs_reply_no'];
		$bbs_reply_arr = $arr['bbs_reply_arr'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			$query = DB::update('bbs_reply');
			$query->set($bbs_reply_arr);
			$query->where('bbs_reply_no', '=', $bbs_reply_no);
			$result_reply_arr = $query->execute();


			// --------------------------------------------------
			//   コメントのSort Date＆返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date']
			));

			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドのSort Date 更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date']
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


			// --------------------------------------------------
			//   コミュニティのSort Date更新
			// --------------------------------------------------

			$query = DB::update('community');

			$query->set(array(
				'sort_date' => $bbs_reply_arr['renewal_date']
			));

			$query->where('community_no', '=', $community_no);
			$result_community_arr = $query->execute();


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





	/**
	* 返信削除　GC
	* @param array $arr
	* @return array
	*/
	public function delete_bbs_reply_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_comment_no = $arr['bbs_comment_no'];
		$bbs_reply_no = $arr['bbs_reply_no'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			$query = DB::update('bbs_reply_gc');

			$query->set(
				array('on_off' => null
			));

			$query->where('bbs_reply_no', '=', $bbs_reply_no);
			$result_reply_arr = $query->execute();


			// --------------------------------------------------
			//   コメントの返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment_gc');

			$query->set(array(
				'reply_total' => DB::expr('reply_total - 1')
			));

			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドの返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'reply_total' => DB::expr('reply_total - 1')
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


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



	/**
	* 返信削除　UC
	* @param array $arr
	* @return array
	*/
	public function delete_bbs_reply_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$bbs_thread_no = $arr['bbs_thread_no'];
		$bbs_comment_no = $arr['bbs_comment_no'];
		$bbs_reply_no = $arr['bbs_reply_no'];


		try
		{

			// --------------------------------------------------
			//   トランザクション開始
			// --------------------------------------------------

			DB::start_transaction();


			// --------------------------------------------------
			//   返信更新
			// --------------------------------------------------

			$query = DB::update('bbs_reply');

			$query->set(
				array('on_off' => null
			));

			$query->where('bbs_reply_no', '=', $bbs_reply_no);
			$result_reply_arr = $query->execute();


			// --------------------------------------------------
			//   コメントの返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_comment');

			$query->set(array(
				'reply_total' => DB::expr('reply_total - 1')
			));

			$query->where('bbs_comment_no', '=', $bbs_comment_no);
			$result_comment_arr = $query->execute();


			// --------------------------------------------------
			//   スレッドの返信数更新
			// --------------------------------------------------

			$query = DB::update('bbs_thread');

			$query->set(array(
				'reply_total' => DB::expr('reply_total - 1')
			));

			$query->where('bbs_thread_no', '=', $bbs_thread_no);
			$result_thread_arr = $query->execute();


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





	/**
	* BBS一覧取得　/ GC
	* @param array $arr
	* @return array
	*/
	public function get_bbs_list_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		// 共通
		$language = 'ja';
		$page = (isset($arr['page'])) ? $arr['page'] : null;
		$limit_slice = (isset($arr['limit'])) ? $arr['limit'] : null;
		$limit = 100;
		$keyword = (isset($arr['keyword'])) ? $arr['keyword'] : null;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		//$offset = $limit * ($page - 1);
		$offset = 0;


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		// スレッド
		$query = DB::select(
			'bbs_thread_gc.bbs_thread_no',
			'bbs_thread_gc.regi_date',
			'bbs_thread_gc.game_no',
			'bbs_thread_gc.anonymity',
			'bbs_thread_gc.handle_name',
			array('users_data.handle_name', 'user_handle_name'),
			array('profile.handle_name', 'profile_handle_name'),
			'bbs_thread_gc.title',
			'bbs_thread_gc.comment',
			'game_data.game_no',
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.id', 'game_id'),
			'game_data.thumbnail'
		)->from('bbs_thread_gc');

		$query->join('game_data', 'LEFT');
		$query->on('bbs_thread_gc.game_no', '=', 'game_data.game_no');
		$query->join('users_data', 'LEFT');
		$query->on('bbs_thread_gc.user_no', '=', 'users_data.user_no');
		$query->join('profile', 'LEFT');
		$query->on('bbs_thread_gc.profile_no', '=', 'profile.profile_no');

		$query->where('bbs_thread_gc.on_off', '=', 1);

		if ($keyword)
		{
			$query->where_open();
			$query->where('game_data.name_' . $language, 'like', '%' . $keyword . '%');
			$query->or_where('game_data.similarity_' . $language, 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		$query->order_by('bbs_thread_gc.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$thread_arr = $query->execute()->as_array();

		//$thread_total = DB::count_last_query();


		// コメント
		$query = DB::select(
			'bbs_comment_gc.bbs_thread_no',
			'bbs_comment_gc.regi_date',
			'bbs_comment_gc.game_no',
			'bbs_comment_gc.anonymity',
			'bbs_comment_gc.handle_name',
			array('users_data.handle_name', 'user_handle_name'),
			array('profile.handle_name', 'profile_handle_name'),
			'bbs_comment_gc.comment',
			'game_data.game_no',
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.id', 'game_id'),
			'game_data.thumbnail'
		)->from('bbs_comment_gc');

		$query->join('game_data', 'LEFT');
		$query->on('bbs_comment_gc.game_no', '=', 'game_data.game_no');
		$query->join('users_data', 'LEFT');
		$query->on('bbs_comment_gc.user_no', '=', 'users_data.user_no');
		$query->join('profile', 'LEFT');
		$query->on('bbs_comment_gc.profile_no', '=', 'profile.profile_no');

		$query->where('bbs_comment_gc.on_off', '=', 1);

		if ($keyword)
		{
			$query->where_open();
			$query->where('game_data.name_' . $language, 'like', '%' . $keyword . '%');
			$query->or_where('game_data.similarity_' . $language, 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		$query->order_by('bbs_comment_gc.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$comment_arr = $query->execute()->as_array();

		//$comment_total = DB::count_last_query();


		// 返信
		$query = DB::select(
			'bbs_reply_gc.bbs_thread_no',
			'bbs_reply_gc.regi_date',
			'bbs_reply_gc.game_no',
			'bbs_reply_gc.anonymity',
			'bbs_reply_gc.handle_name',
			array('users_data.handle_name', 'user_handle_name'),
			array('profile.handle_name', 'profile_handle_name'),
			'bbs_reply_gc.comment',
			'game_data.game_no',
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.id', 'game_id'),
			'game_data.thumbnail'
		)->from('bbs_reply_gc');

		$query->join('game_data', 'LEFT');
		$query->on('bbs_reply_gc.game_no', '=', 'game_data.game_no');
		$query->join('users_data', 'LEFT');
		$query->on('bbs_reply_gc.user_no', '=', 'users_data.user_no');
		$query->join('profile', 'LEFT');
		$query->on('bbs_reply_gc.profile_no', '=', 'profile.profile_no');

		$query->where('bbs_reply_gc.on_off', '=', 1);

		if ($keyword)
		{
			$query->where_open();
			$query->where('game_data.name_' . $language, 'like', '%' . $keyword . '%');
			$query->or_where('game_data.similarity_' . $language, 'like', '%' . $keyword . '%');
			$query->where_close();
		}

		$query->order_by('bbs_reply_gc.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$reply_arr = $query->execute()->as_array();

		//$reply_total = DB::count_last_query();





		// 合成
		$bbs_list_arr = array_merge($thread_arr, $comment_arr, $reply_arr);

		if (count($bbs_list_arr) > 0)
		{

			// 並び替え
			foreach($bbs_list_arr as $key => $value)
			{
				$date_arr[$key] = $value['regi_date'];
			}

			array_multisort($date_arr, SORT_DESC, SORT_NATURAL, $bbs_list_arr);


			// 取り出し
			$offset_slice = $limit_slice * ($page - 1);
			$bbs_list_arr = array_slice($bbs_list_arr, $offset_slice, $limit_slice);


			$total = 200;

		}
		else
		{
			$total = 0;
		}






		//\Debug::$js_toggle_open = true;

		//echo '$thread_arr';
		//\Debug::dump($thread_arr);

		//echo '$comment_arr';
		//\Debug::dump($comment_arr);

		//echo '$reply_arr';
		//\Debug::dump($reply_arr);

		// echo '$thread_total';
		// \Debug::dump($thread_total);
//
		// echo '$comment_total';
		// \Debug::dump($comment_total);
//
		// echo '$reply_total';
		// \Debug::dump($reply_total);


		//\Debug::dump($thread_arr, $comment_arr, $reply_arr, ($thread_total + $comment_total + $reply_total));
		//\Debug::dump($bbs_list_arr);



		return array('bbs_list_arr' => $bbs_list_arr, 'total' => $total);

	}





	/**
	* BBSスレッドのコメント数、返信数を再計算する
	* @param array $arr 配列
	* @return array
	*/
	public function calculate_total($arr)
	{

		// --------------------------------------------------
		//   ユーザーコミュニティ
		// --------------------------------------------------

		$query = DB::select('bbs_thread_no')->from('bbs_thread');
		//$query->where('on_off', '=', 1);
		$db_thread_arr = $query->execute()->as_array();


		foreach ($db_thread_arr as $key => $value)
		{

			// --------------------------------------------------
			//   コメント数取得
			// --------------------------------------------------

			$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_comment');
			$query->where('bbs_thread_no', '=', $value['bbs_thread_no']);
			$query->where('on_off', '=', 1);
			$comment_total = $query->execute()->current()['total'];


			// --------------------------------------------------
			//   返信数取得
			// --------------------------------------------------

			$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_reply');
			$query->join('bbs_comment', 'LEFT');
			$query->on('bbs_reply.bbs_comment_no', '=', 'bbs_comment.bbs_comment_no');
			$query->where('bbs_reply.bbs_thread_no', '=', $value['bbs_thread_no']);
			$query->where('bbs_reply.on_off', '=', 1);
			$query->where('bbs_comment.on_off', '=', 1);
			$reply_total = $query->execute()->current()['total'];


			// --------------------------------------------------
			//   保存
			// --------------------------------------------------

			$query = DB::update('bbs_thread');

			$query->set(array(
				'comment_total' => $comment_total,
				'reply_total' => $reply_total
			));

			$query->where('bbs_thread_no', '=', $value['bbs_thread_no']);
			$result_thread_arr = $query->execute();

			//echo 'bbs_thread_no = ' . $value['bbs_thread_no'];
			//\Debug::dump($comment_total, $reply_total);
		}



		// --------------------------------------------------
		//   ユーザーコミュニティ
		// --------------------------------------------------

		$query = DB::select('bbs_thread_no')->from('bbs_thread_gc');
		//$query->where('on_off', '=', 1);
		$db_thread_arr = $query->execute()->as_array();


		foreach ($db_thread_arr as $key => $value)
		{

			// --------------------------------------------------
			//   コメント数取得
			// --------------------------------------------------

			$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_comment_gc');
			$query->where('bbs_thread_no', '=', $value['bbs_thread_no']);
			$query->where('on_off', '=', 1);
			$comment_total = $query->execute()->current()['total'];


			// --------------------------------------------------
			//   返信数取得
			// --------------------------------------------------

			$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_reply_gc');
			$query->join('bbs_comment_gc', 'LEFT');
			$query->on('bbs_reply_gc.bbs_comment_no', '=', 'bbs_comment_gc.bbs_comment_no');
			$query->where('bbs_reply_gc.bbs_thread_no', '=', $value['bbs_thread_no']);
			$query->where('bbs_reply_gc.on_off', '=', 1);
			$query->where('bbs_comment_gc.on_off', '=', 1);
			$reply_total = $query->execute()->current()['total'];


			// --------------------------------------------------
			//   保存
			// --------------------------------------------------

			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'comment_total' => $comment_total,
				'reply_total' => $reply_total
			));

			$query->where('bbs_thread_no', '=', $value['bbs_thread_no']);
			$result_thread_arr = $query->execute();

			echo 'bbs_thread_no = ' . $value['bbs_thread_no'];
			\Debug::dump($comment_total, $reply_total);
		}

		//\Debug::dump($db_thread_arr);
		//exit();

	}


}
