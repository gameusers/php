<?php

require_once APPPATH . "tests/common/func.php";

/**
* @group App
* @group Controller
* @group Rest
*/
class Test_Controller_Rest_User extends Common_Func
{
	
	public $base_url = 'http://localhost/gameusers/public/rest/index/';
	
	
	// テスト前 テーブルを空にする
	public static function setUpBeforeClass()
	{
		\DBUtil::truncate_table('game_data');
		\DBUtil::truncate_table('community');
	}
	
	
	/**
	* ゲーム登録
	* @group save_game_data
	* @dataProvider dp_post_save_game_data
	*/
	public function test_post_save_game_data($post_data_arr)
	{
		$url = $this->base_url . 'save_game_data.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		// echo "\n\n" . "output : result" . "\n";
		// var_dump($result);
		// echo "\n";
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_game_data()
	{
		return array(
			
			// 新規登録
			array(array('login_user_no' => 1, 'name' => 'スーパーマリオブラザーズ', 'similarity_1'=> '')),
			array(array('login_user_no' => 2, 'name' => 'クルクルランド', 'similarity_1'=> '')),
			array(array('login_user_no' => 3, 'name' => 'ドラゴンクエスト', 'similarity_1'=> 'ドラクエ', 'similarity_2'=> 'DQ', 'similarity_3'=> 'Dragon Quest')),
			array(array('login_user_no' => 4, 'name' => 'ドラゴンクエストII', 'similarity_1'=> 'ドラクエ', 'similarity_2'=> 'DQ2', 'similarity_3'=> 'Dragon Quest')),
			array(array('login_user_no' => 5, 'name' => 'ドラゴンクエストIII', 'similarity_1'=> 'ドラクエ', 'similarity_2'=> 'DQ3', 'similarity_3'=> 'Dragon Quest')),
			array(array('login_user_no' => 10, 'name' => '源平討魔伝')),
			array(array('login_user_no' => 10, 'name' => 'ゼルダの伝説')),
			array(array('login_user_no' => 10, 'name' => 'リンクの冒険')),
			array(array('login_user_no' => 10, 'name' => 'プラントvs.ゾンビ', 'similarity_1'=> 'ガーデンウォーフェア', 'similarity_1'=> 'プラゾン')),
			array(array('login_user_no' => 10, 'name' => 'ぽっちゃり☆プリンセス', 'similarity_1'=> 'ぽちゃぷり')),
			
			// 編集
			array(array('login_user_no' => 6, 'game_no' => 4, 'name' => 'ドラゴンクエストII', 'similarity_1'=> 'ドラクエ', 'similarity_2'=> 'DQ2', 'similarity_3'=> 'Dragon Quest', 'similarity_4'=> '悪霊の神々')),
			array(array('login_user_no' => 7, 'game_no' => 5, 'name' => 'ドラゴンクエストIII', 'similarity_1'=> 'ドラクエ', 'similarity_2'=> 'DQ3', 'similarity_3'=> 'Dragon Quest', 'similarity_4'=> 'そして伝説へ… ')),
			
		);
	}
	
	
	/**
	* ゲーム登録　エラーチェック
	* @group save_game_data
	* @dataProvider dp_post_save_game_data_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_save_game_data_error($post_data_arr)
	{
		$url = $this->base_url . 'save_game_data.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_game_data_error()
	{
		return array(
			
			// 新規登録　ログインしていない
			array(array('name' => 'エラーゲーム')),
			
			// 新規登録　文字数多すぎ
			array(array('login_user_no' => 1, 'name' => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz')),
			array(array('login_user_no' => 1, 'name' => 'abcdefghijklmnopqrstuvwxyz', 'similarity_1'=> 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz')),
			
			// 新規登録　なし
			array(array('login_user_no' => 1)),
			array(array('login_user_no' => 1, 'similarity_1'=> null)),
			
			// 新規登録　Null
			array(array('login_user_no' => 1, 'name' => null, 'similarity_1'=> null)),
			
			// 新規登録　0　nameが '0' 扱いになる
			//array(array('login_user_no' => 1, 'name' => 0, 'similarity_1'=> 0)),
			
			// 新規登録　''
			array(array('login_user_no' => 1, 'name' => '', 'similarity_1'=> '')),
			
			// 新規登録　true　nameが '1' 扱いになる
			//array(array('login_user_no' => 1, 'name' => true, 'similarity_1'=> true)),
			
			// 新規登録　false
			array(array('login_user_no' => 1, 'name' => false, 'similarity_1'=> false)),
			
			
			// 編集　ログインしていない
			array(array('game_no' => 1, 'name' => 'エラーゲーム')),
			
			// 編集　文字数多すぎ
			array(array('login_user_no' => 1, 'game_no' => 1, 'name' => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz')),
			array(array('login_user_no' => 1, 'game_no' => 1, 'name' => 'abcdefghijklmnopqrstuvwxyz', 'similarity_1'=> 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz')),
			
			// 編集　なし
			array(array('login_user_no' => 1, 'game_no' => 1, 'similarity_1'=> null)),
			array(array('login_user_no' => 1, 'game_no' => 1)),
			
			// 編集　Null
			array(array('login_user_no' => 1, 'game_no' => 1, 'name' => null, 'similarity_1'=> null)),
			
			// 編集　''
			array(array('login_user_no' => 1, 'game_no' => 1, 'name' => '', 'similarity_1'=> '')),
			
			// 編集　false
			array(array('login_user_no' => 1, 'game_no' => 1, 'name' => false, 'similarity_1'=> false)),
			
			// 編集　存在しないゲームNo
			//array(array('login_user_no' => 1, 'game_no' => 0, 'name' => 'エラーゲーム', 'similarity_1'=> 'エラーゲーム')),
			array(array('login_user_no' => 1, 'game_no' => 10000, 'name' => 'エラーゲーム', 'similarity_1'=> 'エラーゲーム')),
			array(array('login_user_no' => 1, 'game_no' => 'a', 'name' => 'エラーゲーム', 'similarity_1'=> 'エラーゲーム')),
			array(array('login_user_no' => 1, 'game_no' => 'あ', 'name' => 'エラーゲーム', 'similarity_1'=> 'エラーゲーム')),
			//array(array('login_user_no' => 1, 'game_no' => null, 'name' => 'エラーゲーム', 'similarity_1'=> 'エラーゲーム')),
			//array(array('login_user_no' => 1, 'game_no' => '', 'name' => 'エラーゲーム', 'similarity_1'=> 'エラーゲーム')),
			//array(array('login_user_no' => 1, 'game_no' => true, 'name' => 'エラーゲーム', 'similarity_1'=> 'エラーゲーム')),
			//array(array('login_user_no' => 1, 'game_no' => false, 'name' => 'エラーゲーム', 'similarity_1'=> 'エラーゲーム')),
			
		);
	}
	
	
	
	
	
	
	/**
	* ゲーム登録用　ゲーム読み込み　検索
	* @group search_game_data
	* @dataProvider dp_post_search_game_data
	*/
	public function test_post_search_game_data($post_data_arr)
	{
		$url = $this->base_url . 'search_game_data.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		// echo "\n\n" . "output : result" . "\n";
		// var_dump($result);
		// echo "\n";
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_search_game_data()
	{
		return array(
			
			// 名前検索
			array(array('keyword' => 'マリオ', 'page' => 1)),
			array(array('keyword' => 'スーパーマリオブラザーズ', 'page' => 1)),
			
			// 関連ワード検索
			array(array('keyword' => 'ドラクエ', 'page' => 1)),
			array(array('keyword' => 'DQ', 'page' => 1)),
			array(array('keyword' => 'ガーデン', 'page' => 1)),
			
			// 存在しないゲームの場合は、新規登録フォーム
			array(array('keyword' => 'AAA', 'page' => 1)),
			
		);
	}
	
	
	/**
	* ゲーム登録用　ゲーム読み込み　検索　エラーチェック
	* @group search_game_data
	* @dataProvider dp_post_search_game_data_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_search_game_data_error($post_data_arr)
	{
		$url = $this->base_url . 'search_game_data.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_search_game_data_error()
	{
		return array(
			
			// 文字数多すぎ
			array(array('keyword' => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz', 'page'=> 1)),
			
			// なし
			array(array('keyword' => 'ゼルダの伝説')),
			
			// Null
			array(array('keyword' => 'ゼルダの伝説', 'page'=> null)),
			
			// 0
			array(array('keyword' => 'ゼルダの伝説', 'page'=> 0)),
			
			// ''
			array(array('keyword' => 'ゼルダの伝説', 'page'=> '')),
			
			// true　数字の1として扱われる
			//array(array('keyword' => 'ゼルダの伝説', 'page'=> true)),
			
			// false
			array(array('keyword' => 'ゼルダの伝説', 'page'=> false)),
			
			// 存在しないページでもコードは返ってくる
			//array(array('keyword' => 'ゼルダの伝説', 'page'=> 10000)),
			
		);
	}
	
	
	
	/**
	* ゲーム登録用　ゲーム読み込み　個別履歴
	* @group read_game_data
	* @dataProvider dp_post_read_game_data
	*/
	public function test_post_read_game_data($post_data_arr)
	{
		$url = $this->base_url . 'read_game_data.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		// echo "\n\n" . "output : result" . "\n";
		// var_dump($result);
		// echo "\n";
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_read_game_data()
	{
		return array(
			
			array(array('game_no' => 1, 'history_no' => 0)),
			array(array('game_no' => 1, 'history_no' => 1)),
			array(array('game_no' => 1, 'history_no' => 2)),
			array(array('game_no' => 2, 'history_no' => 0)),
			array(array('game_no' => 3, 'history_no' => 0)),
			array(array('game_no' => 4, 'history_no' => 0)),
			array(array('game_no' => 5, 'history_no' => 0)),
			
		);
	}
	
	
	/**
	* ゲーム登録用　ゲーム読み込み　個別履歴　エラーチェック
	* @group read_game_data
	* @dataProvider dp_post_read_game_data_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_read_game_data_error($post_data_arr)
	{
		$url = $this->base_url . 'read_game_data.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_read_game_data_error()
	{
		return array(
			
			// 存在しない番号
			array(array('game_no' => 10000, 'history_no' => 1)),
			array(array('game_no' => 1, 'history_no' => 51)),
			
			// なし
			array(array('history_no' => 0)),
			//array(array('game_no' => 1)),// これはhistoryの一番最初が返ってくる
			
			// Null
			array(array('game_no' => null, 'history_no' => 1)),
			array(array('game_no' => null, 'history_no' => null)),
			
			// 0
			array(array('game_no' => 0, 'history_no' => 1)),
			array(array('game_no' => 0, 'history_no' => 0)),
			
			// ''
			array(array('game_no' => '', 'history_no' => 1)),
			array(array('game_no' => '', 'history_no' => '')),
			
		);
	}
	
	
	
	
	
	
	/**
	* ユーザーコミュニティ作成
	* 要ログイン
	* community_name(required) / community_description(required) / community_description_mini(required) / community_id(required) / game_list(required)
	* 
	* @group create_user_community
	* @dataProvider dp_post_create_user_community
	*/
	public function test_post_create_user_community($post_data_arr)
	{
		$url = $this->base_url . 'create_user_community.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_create_user_community()
	{
		return array(
			
			array(array('login_user_no' => 1, 'community_name'=> 'User1のコミュニティ', 'community_description'=> '説明文', 'community_description_mini'=> '説明文ミニ', 'community_id'=> 'community_user1', 'game_list'=> '1,2,3,4')),
			array(array('login_user_no' => 2, 'community_name'=> 'User2のコミュニティ', 'community_description'=> '説明文', 'community_description_mini'=> '説明文ミニ', 'community_id'=> 'community_user2', 'game_list'=> '5')),
			array(array('login_user_no' => 3, 'community_name'=> 'User3のコミュニティ', 'community_description'=> '説明文', 'community_description_mini'=> '説明文ミニ', 'community_id'=> 'community_user3', 'game_list'=> '6,7')),
			array(array('login_user_no' => 4, 'community_name'=> 'User4のコミュニティ 削除用', 'community_description'=> '説明文', 'community_description_mini'=> '説明文ミニ', 'community_id'=> 'community_user4', 'game_list'=> '8')),
			
		);
	}
	
	
	/**
	* ユーザーコミュニティ作成　エラーチェック
	* @group create_user_community2
	* @dataProvider dp_post_create_user_community_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_create_user_community_error($post_data_arr)
	{
		$url = $this->base_url . 'create_user_community.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_create_user_community_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '1')),
			
			// 必要項目が足りない
			array(array('community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error')),
			
			// 不正な文字列
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'あ', 'game_list'=> '1')),
			
			// Null
			array(array('login_user_no' => 1, 'community_name'=> null, 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> null, 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> null, 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> null, 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> null)),
			
			// ''
			array(array('login_user_no' => 1, 'community_name'=> '', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> '', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> '', 'community_id'=> 'error', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> '', 'game_list'=> '1')),
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'error', 'game_list'=> '')),
			
			// コミュニティID重複
			array(array('login_user_no' => 1, 'community_name'=> 'エラー', 'community_description'=> 'エラー', 'community_description_mini'=> 'エラー', 'community_id'=> 'community_user1', 'game_list'=> '1')),
			
		);
	}
	
	
	
}