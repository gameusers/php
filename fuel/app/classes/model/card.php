<?php

class Model_Card extends Model_Crud
{

	// --------------------------------------------------
	//   コミュニティ
	// --------------------------------------------------

	// ------------------------------
	//    取得
	// ------------------------------

	/**
	* ゲームページ　BBS　スレッド取得
	* @param array $arr
	* @return array
	*/
	public function select_bbs_thread_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$datetime = $arr['datetime'];
		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 100;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			DB::expr("'bbs_thread_gc' as 'type'"),
			'bbs_thread_gc.bbs_thread_no',
			'bbs_thread_gc.bbs_id',
			array('bbs_thread_gc.regi_date', 'date'),
			'bbs_thread_gc.title', 'bbs_thread_gc.comment',
			'bbs_thread_gc.image',
			'bbs_thread_gc.movie',
			'bbs_thread_gc.comment_total',
			'bbs_thread_gc.reply_total',
			'game_data.game_no',
			array('game_data.id', 'game_id'),
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.thumbnail', 'game_thumbnail')
		)->from('bbs_thread_gc');

		$query->join('game_data', 'LEFT');
		$query->on('bbs_thread_gc.game_no', '=', 'game_data.game_no');

		$query->where('bbs_thread_gc.regi_date', '>', $datetime);
		$query->where('bbs_thread_gc.on_off', '=', 1);

		$query->order_by('bbs_thread_gc.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* ゲームページ　BBS　コメント取得
	* @param array $arr
	* @return array
	*/
	public function select_bbs_comment_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$datetime = $arr['datetime'];
		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 100;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			DB::expr("'bbs_comment_gc' as 'type'"),
			'bbs_comment_gc.bbs_comment_no',
			'bbs_comment_gc.bbs_id',
			array('bbs_comment_gc.regi_date', 'date'),
			'bbs_comment_gc.comment',
			'bbs_comment_gc.image',
			'bbs_comment_gc.movie',
			// 'bbs_comment_gc.reply_total',
			'bbs_thread_gc.bbs_thread_no',
			'bbs_thread_gc.title',
			'bbs_thread_gc.comment_total',
			'bbs_thread_gc.reply_total',
			'game_data.game_no',
			array('game_data.id', 'game_id'),
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.thumbnail', 'game_thumbnail')
		)->from('bbs_comment_gc');

		$query->join('bbs_thread_gc', 'LEFT');
		$query->on('bbs_comment_gc.bbs_thread_no', '=', 'bbs_thread_gc.bbs_thread_no');

		$query->join('game_data', 'LEFT');
		$query->on('bbs_comment_gc.game_no', '=', 'game_data.game_no');

		$query->where('bbs_comment_gc.regi_date', '>', $datetime);
		$query->where('bbs_comment_gc.on_off', '=', 1);

		$query->order_by('bbs_comment_gc.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* ゲームページ　BBS　返信取得
	* @param array $arr
	* @return array
	*/
	public function select_bbs_reply_gc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$datetime = $arr['datetime'];
		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 100;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			DB::expr("'bbs_reply_gc' as 'type'"),
			'bbs_reply_gc.bbs_reply_no',
			'bbs_reply_gc.bbs_id',
			array('bbs_reply_gc.regi_date', 'date'),
			'bbs_reply_gc.comment',
			'bbs_reply_gc.image',
			'bbs_reply_gc.movie',
			'bbs_thread_gc.bbs_thread_no',
			'bbs_thread_gc.title',
			'bbs_thread_gc.comment_total',
			'bbs_thread_gc.reply_total',
			'game_data.game_no',
			array('game_data.id', 'game_id'),
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.thumbnail', 'game_thumbnail')
		)->from('bbs_reply_gc');

		$query->join('bbs_thread_gc', 'LEFT');
		$query->on('bbs_reply_gc.bbs_thread_no', '=', 'bbs_thread_gc.bbs_thread_no');

		$query->join('game_data', 'LEFT');
		$query->on('bbs_reply_gc.game_no', '=', 'game_data.game_no');

		$query->where('bbs_reply_gc.regi_date', '>', $datetime);
		$query->where('bbs_reply_gc.on_off', '=', 1);

		$query->order_by('bbs_reply_gc.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* コミュニティ　BBS　スレッド取得
	* @param array $arr
	* @return array
	*/
	public function select_bbs_thread_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$datetime = $arr['datetime'];
		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 100;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			DB::expr("'bbs_thread_uc' as 'type'"),
			'bbs_thread.bbs_thread_no',
			'bbs_thread.bbs_id',
			array('bbs_thread.regi_date', 'date'),
			'bbs_thread.title',
			'bbs_thread.comment',
			'bbs_thread.image',
			'bbs_thread.movie',
			'bbs_thread.comment_total',
			'bbs_thread.reply_total',
			'community.community_no',
			'community.community_id',
			array('community.name', 'community_name'),
			array('community.thumbnail', 'community_thumbnail')
		)->from('bbs_thread');

		$query->join('community', 'LEFT');
		$query->on('bbs_thread.community_no', '=', 'community.community_no');

		$query->where('bbs_thread.regi_date', '>', $datetime);
		$query->where('bbs_thread.on_off', '=', 1);
		$query->where('community.open', '=', 1);

		$query->order_by('bbs_thread.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* コミュニティ　BBS　コメント取得
	* @param array $arr
	* @return array
	*/
	public function select_bbs_comment_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$datetime = $arr['datetime'];
		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 100;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			DB::expr("'bbs_comment_uc' as 'type'"),
			'bbs_comment.bbs_comment_no',
			'bbs_comment.bbs_id',
			array('bbs_comment.regi_date', 'date'),
			'bbs_comment.comment',
			'bbs_comment.image',
			'bbs_comment.movie',
			'bbs_thread.bbs_thread_no',
			'bbs_thread.title',
			'bbs_thread.comment_total',
			'bbs_thread.reply_total',
			'community.community_no',
			'community.community_id',
			array('community.name', 'community_name'),
			array('community.thumbnail', 'community_thumbnail')
		)->from('bbs_comment');

		$query->join('bbs_thread', 'LEFT');
		$query->on('bbs_comment.bbs_thread_no', '=', 'bbs_thread.bbs_thread_no');

		$query->join('community', 'LEFT');
		$query->on('bbs_comment.community_no', '=', 'community.community_no');

		$query->where('bbs_comment.regi_date', '>', $datetime);
		$query->where('bbs_comment.on_off', '=', 1);
		$query->where('community.open', '=', 1);

		$query->order_by('bbs_comment.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}


	/**
	* コミュニティ　BBS　返信取得
	* @param array $arr
	* @return array
	*/
	public function select_bbs_reply_uc($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$datetime = $arr['datetime'];
		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 100;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			DB::expr("'bbs_reply_uc' as 'type'"),
			'bbs_reply.bbs_reply_no',
			'bbs_reply.bbs_id',
			array('bbs_reply.regi_date', 'date'),
			'bbs_reply.comment',
			'bbs_reply.image',
			'bbs_reply.movie',
			'bbs_thread.bbs_thread_no',
			'bbs_thread.title',
			'bbs_thread.comment_total',
			'bbs_thread.reply_total',
			'community.community_no',
			'community.community_id',
			array('community.name', 'community_name'),
			array('community.thumbnail', 'community_thumbnail')
		)->from('bbs_reply');

		$query->join('bbs_thread', 'LEFT');
		$query->on('bbs_reply.bbs_thread_no', '=', 'bbs_thread.bbs_thread_no');

		$query->join('community', 'LEFT');
		$query->on('bbs_reply.community_no', '=', 'community.community_no');

		$query->where('bbs_reply.regi_date', '>', $datetime);
		$query->where('bbs_reply.on_off', '=', 1);
		$query->where('community.open', '=', 1);

		$query->order_by('bbs_reply.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* ゲームページ　募集　コメント取得
	* @param array $arr
	* @return array
	*/
	public function select_recruitment_comment($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$datetime = $arr['datetime'];
		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 100;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			DB::expr("'recruitment_comment' as 'type'"),
			'recruitment.recruitment_id',
			array('recruitment.renewal_date', 'date'),
			array('recruitment.etc_title', 'title'),
			'recruitment.comment',
			'recruitment.image',
			'recruitment.movie',
			'game_data.game_no',
			array('game_data.id', 'game_id'),
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.thumbnail', 'game_thumbnail')
		)->from('recruitment');

		$query->join('game_data', 'LEFT');
		$query->on('recruitment.game_no', '=', 'game_data.game_no');

		$query->where('recruitment.renewal_date', '>', $datetime);
		$query->where('recruitment.on_off', '=', 1);

		$query->order_by('recruitment.renewal_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* ゲームページ　募集　返信取得
	* @param array $arr
	* @return array
	*/
	public function select_recruitment_reply($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$datetime = $arr['datetime'];
		$page = $arr['page'] ?? 1;
		$limit = $arr['limit'] ?? 100;


		// --------------------------------------------------
		//   オフセット計算
		// --------------------------------------------------

		$offset = $limit * ($page - 1);


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			DB::expr("'recruitment_reply' as 'type'"),
			'recruitment_reply.recruitment_reply_id',
			array('recruitment_reply.regi_date', 'date'),
			'recruitment_reply.comment',
			'recruitment_reply.image',
			'recruitment_reply.movie',
			'recruitment.recruitment_id',
			array('recruitment.etc_title', 'title'),
			'game_data.game_no',
			array('game_data.id', 'game_id'),
			array('game_data.name_' . $language, 'game_name'),
			array('game_data.thumbnail', 'game_thumbnail')
		)->from('recruitment_reply');

		$query->join('recruitment', 'LEFT');
		$query->on('recruitment_reply.recruitment_id', '=', 'recruitment.recruitment_id');

		$query->join('game_data', 'LEFT');
		$query->on('recruitment_reply.game_no', '=', 'game_data.game_no');

		$query->where('recruitment_reply.regi_date', '>', $datetime);
		$query->where('recruitment_reply.on_off', '=', 1);

		$query->order_by('recruitment.regi_date','desc');
		$query->limit($limit);
		$query->offset($offset);
		$arr = $query->execute()->as_array();


		return $arr;

	}



	/**
	* フッター　最近更新されたゲームページ・最近アクセスしたゲームページ用
	* @param array $arr
	* @return array
	*/
	public function select_footer_game_data($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$type = $arr['type'];
		$game_no_arr = $arr['game_no_arr'] ?? null;
		$limit = $arr['limit'];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('game_data.game_no', 'game_data.renewal_date', 'game_data.id', array('game_data.name_' . $language, 'name'), 'game_data.thumbnail')->from('game_data');
		$query->join('game_community', 'LEFT');
		$query->on('game_data.game_no', '=', 'game_community.game_no');

		if ($type === 'gc_renewal')
		{
			$query->order_by('game_community.sort_date','desc');
		}
		else if ($type === 'gc_access' and $game_no_arr)
		{

			$query->where('game_data.game_no', 'in', $game_no_arr);
		}

		$query->limit($limit);
		$query->offset(0);
		$result_arr = $query->execute()->as_array('game_no');


		// --------------------------------------------------
		//   クッキーの順番通りに並び替え
		// --------------------------------------------------

		if ($type === 'gc_access' and $game_no_arr)
		{
			$temp_arr = [];

			foreach ($game_no_arr as $key => $value)
			{
				if (isset($result_arr[$value])) array_push($temp_arr, $result_arr[$value]);
			}

			$result_arr = $temp_arr;
		}


		return $result_arr;

	}



	/**
	* フッター　最近アクセスしたコミュニティ用
	* @param array $arr
	* @return array
	*/
	public function select_footer_community($arr)
	{

		// --------------------------------------------------
		//   変数代入
		// --------------------------------------------------

		$language = $arr['language'];
		$type = $arr['type'];
		$community_no_arr = $arr['community_no_arr'] ?? null;
		$limit = $arr['limit'];


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select('community_no', 'renewal_date', 'community_id', 'name', 'thumbnail')->from('community');

		$query->where('community_no', 'in', $community_no_arr);

		$query->limit($limit);
		$query->offset(0);
		$result_arr = $query->execute()->as_array('community_no');


		// --------------------------------------------------
		//   クッキーの順番通りに並び替え
		// --------------------------------------------------

		if ($type === 'uc_access' and $community_no_arr)
		{
			$temp_arr = [];

			foreach ($community_no_arr as $key => $value)
			{
				if (isset($result_arr[$value])) array_push($temp_arr, $result_arr[$value]);
			}

			$result_arr = $temp_arr;
		}


		return $result_arr;

	}


}
