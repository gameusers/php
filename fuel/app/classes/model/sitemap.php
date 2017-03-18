<?php

class Model_Sitemap extends Model_Crud
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




	/**
	* ユーザー
	* @param array $arr 検索条件
	* @return array
	*/
	public function users($arr)
	{
		$query = DB::select('renewal_date', 'user_id')->from('users_data');
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->as_array();

		return $arr;
	}


	/**
	* ユーザーコミュニティ
	* @param array $arr 検索条件
	* @return array
	*/
	public function user_community($arr)
	{
		$query = DB::select('sort_date', 'community_id')->from('community');
		$query->where('on_off', '=', 1);
		$arr = $query->execute()->as_array();

		return $arr;
	}


	/**
	* ゲームコミュニティ
	* @param array $arr 検索条件
	* @return array
	*/
	public function game_community($arr)
	{
		$query = DB::select('game_community.sort_date', 'game_data.id')->from('game_data');
		$query->join('game_community', 'LEFT');
		$query->on('game_data.game_no', '=', 'game_community.game_no');

		$query->where('game_data.on_off', '=', 1);
		$arr = $query->execute()->as_array();

		return $arr;
	}


	/**
	* Wiki
	* @return array
	*/
	public function wiki($arr)
	{
		$query = DB::select('wiki_id')->from('wiki');
		$query->where('on_off', '=', 1);
		$result_arr = $query->execute()->as_array();

		return $result_arr;
	}

}
