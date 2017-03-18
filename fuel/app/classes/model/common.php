<?php

class Model_Common extends Model_Crud
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
	//   ユーザーコミュニティ
	// --------------------------------------------------
	
	
	/**
	* ユーザーコミュニティを更新順に取得
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array コミュニティ情報
	*/
	public function get_community($page, $limit)
	{
		$offset = $limit * ($page - 1);
		
		$query = DB::select('community_no', 'regi_date', 'community_id', 'name', 'description_mini', 'game_list', 'thumbnail', 'member_total')->from('community');
		$query->where('on_off', '=', 1);
		$query->order_by('sort_date', 'DESC');
		$query->limit($limit);
		$query->offset($offset);
		$return_arr = $query->execute()->as_array();
		
		$total = DB::count_last_query();
		
		return array($return_arr, $total);
		
	}
	
	
	/**
	* ユーザーコミュニティを更新順に取得　配列で検索
	* @param integer $page ページ
	* @param integer $page ページ
	* @param integer $limit リミット
	* @return array コミュニティ情報
	*/
	public function get_community_participation($game_list, $page, $limit)
	{
		
		$game_no_arr = explode(',', $game_list);
		array_shift($game_no_arr);
		array_pop($game_no_arr);
		
		$offset = $limit * ($page - 1);
		
		$query = DB::select('community_no', 'regi_date', 'community_id', 'name', 'description_mini', 'game_list', 'thumbnail', 'member_total')->from('community');
		$query->where('on_off', '=', 1);
		$query->where('community_no', 'in', $game_no_arr);
		$query->order_by('sort_date', 'DESC');
		$query->limit($limit);
		$query->offset($offset);
		$return_arr = $query->execute()->as_array();
		
		$total = DB::count_last_query();
		
		return array($return_arr, $total);
		
	}
	
	
	
	/**
	* 最新の利用規約に同意しているかをチェックして
	* 同意していない場合は最新版の日付を保存
	* @param array $arr 拡張性のため念のため
	* @return array
	*/
	public function check_and_update_user_terms($arr = null)
	{
		
		// ログインしてない場合は処理しない
		if ( ! $this->user_no) return false;
		
		
		// データ取得
		$query = DB::select('user_terms')->from('users_data');
		$query->where('on_off', '=', 1);
		$query->where('user_no', '=', $this->user_no);
		$db_user_data_arr = $query->execute()->current();
		
		// シリアライズされたデータ取得
		$user_terms = $db_user_data_arr['user_terms'];
		
		// アンシリアライズ
		$user_terms_arr = (isset($user_terms)) ? unserialize($user_terms) : array();
		
		// 最新バージョンを取得
		$new_user_terms_version = Config::get('user_terms_version');
		
		// 最新版に同意済みの場合はなにも処理しない
		if ( ! in_array($new_user_terms_version, $user_terms_arr))
		{
			// 配列の「最初」に最新バージョンを追加
			// Original\Code\Common() user_termsで利用するため配列の最初が最新版でなければならない
			array_unshift($user_terms_arr, $new_user_terms_version);
			
			$save_arr['user_terms'] = serialize($user_terms_arr);
			
			// データベース更新
			$query = DB::update('users_data');
			$query->set($save_arr);
			$query->where('user_no', '=', $this->user_no);
			$result = $query->execute();
		}
		
		// if (isset($user_terms_arr))
		// {
			// echo '$user_terms_arr';
			// var_dump($user_terms_arr);
		// }
// 		
		// if (isset($save_arr))
		// {
			// echo '$save_arr';
			// var_dump($save_arr);
		// }
// 		
		// if (isset($result))
		// {
			// echo '$result';
			// var_dump($result);
		// }
		
	}
	
	
	
}