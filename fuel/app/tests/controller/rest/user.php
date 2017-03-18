<?php

require_once APPPATH . "tests/common/func.php";

/**
* @group App
* @group Controller
* @group Rest
*/
class Test_Controller_Rest_User extends Common_Func
{
	
	public $base_url = 'http://localhost/gameusers/public/rest/user/';
	
	
	// テスト前 テーブルを空にする
	public static function setUpBeforeClass()
	{
		\DBUtil::truncate_table('profile');
	}
	
	
	
	
	/**
	* プロフィール保存
	* user_no / profile_noが存在する場合は編集。存在しない場合は新規挿入。3タイプある。
	* @group save_profile
	* @dataProvider dp_post_save_profile
	*/
	public function test_post_save_profile($post_data_arr)
	{
		$url = $this->base_url . 'save_profile.json';
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
	public function dp_post_save_profile()
	{
		return array(
			
			// プレイヤープロフィール編集
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'プロフィールタイトル', 'handle_name' => 'User 1', 'explanation' => 'サムネイルあり', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1.jpg')),
			array(array('login_user_no' => 2, 'user_no' => 2, 'profile_title' => 'プロフィールタイトル', 'handle_name' => 'User 2', 'explanation' => 'サムネイルあり', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user2.jpg')),
			array(array('login_user_no' => 3, 'user_no' => 3, 'profile_title' => 'プロフィールタイトル', 'handle_name' => 'User 3', 'explanation' => 'サムネイルあり', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user3.jpg')),
			array(array('login_user_no' => 4, 'user_no' => 4, 'profile_title' => 'プロフィールタイトル', 'handle_name' => 'User 4', 'explanation' => 'サムネイルあり', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user4.jpg')),
			array(array('login_user_no' => 5, 'user_no' => 5, 'profile_title' => 'プロフィールタイトル', 'handle_name' => 'User 5', 'explanation' => 'サムネイルあり', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user5.png')),
			array(array('login_user_no' => 6, 'user_no' => 6, 'profile_title' => 'プロフィールタイトル', 'handle_name' => 'User 6', 'explanation' => 'サムネイルなし', 'status' => 'ステータス')),
			array(array('login_user_no' => 7, 'user_no' => 7, 'profile_title' => 'プロフィールタイトル', 'handle_name' => 'User 7', 'explanation' => 'サムネイルなし', 'status' => 'ステータス')),
			
			// 追加プロフィール追加
			// User 1
			array(array('login_user_no' => 1, 'profile_title' => '追加プロフィール1', 'handle_name' => 'User 1 追加 1', 'explanation' => 'サムネイルあり　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '1', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile1.jpg')),
			array(array('login_user_no' => 1, 'profile_title' => '追加プロフィール2', 'handle_name' => 'User 1 追加 2', 'explanation' => 'サムネイルあり　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '2,3', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile2.jpg')),
			array(array('login_user_no' => 1, 'profile_title' => '追加プロフィール3', 'handle_name' => 'User 1 追加 3', 'explanation' => 'サムネイルあり　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '4,5,8', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile3.jpg')),
			array(array('login_user_no' => 1, 'profile_title' => '追加プロフィール4', 'handle_name' => 'User 1 追加 4', 'explanation' => 'サムネイルあり　非公開プロフィール', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile4.jpg')),
			array(array('login_user_no' => 1, 'profile_title' => '追加プロフィール5', 'handle_name' => 'User 1 追加 5', 'explanation' => 'サムネイルあり　非公開プロフィール', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile5.jpg')),
			
			// 削除用
			array(array('login_user_no' => 1, 'profile_title' => '削除用追加プロフィール6', 'handle_name' => 'User 1 削除用追加 6', 'explanation' => 'サムネイルあり　非公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile4.jpg')),
			array(array('login_user_no' => 1, 'profile_title' => '削除用追加プロフィール7', 'handle_name' => 'User 1 削除用追加 6', 'explanation' => 'サムネイルあり　非公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile5.jpg')),
			
			// 編集
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => '追加プロフィール1', 'handle_name' => 'User 1 追加 1', 'explanation' => 'サムネイルあり　公開プロフィール　編集済み', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '1', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile1.jpg')),
			array(array('login_user_no' => 1, 'profile_no' => 2, 'profile_title' => '追加プロフィール2', 'handle_name' => 'User 1 追加 2', 'explanation' => 'サムネイルあり　公開プロフィール　編集済み', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '2,3', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user1_game_profile2.jpg')),
			
			
			// User 2
			array(array('login_user_no' => 2, 'profile_title' => '追加プロフィール1', 'handle_name' => 'User 2 追加 1', 'explanation' => 'サムネイルあり　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '10', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user2_game_profile1.jpg')),
			array(array('login_user_no' => 2, 'profile_title' => '追加プロフィール2', 'handle_name' => 'User 2 追加 2', 'explanation' => 'サムネイルあり　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '6,5', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user2_game_profile2.jpg')),
			array(array('login_user_no' => 2, 'profile_title' => '追加プロフィール3', 'handle_name' => 'User 2 追加 3', 'explanation' => 'サムネイルあり　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '3,1,4', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user2_game_profile3.jpg')),
			array(array('login_user_no' => 2, 'profile_title' => '追加プロフィール4', 'handle_name' => 'User 2 追加 4', 'explanation' => 'サムネイルあり　非公開プロフィール', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user2_game_profile4.jpg')),
			array(array('login_user_no' => 2, 'profile_title' => '追加プロフィール5', 'handle_name' => 'User 2 追加 5', 'explanation' => 'サムネイルなし　非公開プロフィール', 'status' => 'ステータス')),
			
			// 削除用
			array(array('login_user_no' => 2, 'profile_title' => '削除用追加プロフィール6', 'handle_name' => 'User 2 削除用追加 6', 'explanation' => 'サムネイルあり　非公開プロフィール', 'status' => 'ステータス', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user2_game_profile4.jpg')),
			array(array('login_user_no' => 2, 'profile_title' => '削除用追加プロフィール7', 'handle_name' => 'User 2 削除用追加 7', 'explanation' => 'サムネイルなし　非公開プロフィール', 'status' => 'ステータス')),
			
			
			// User 3
			array(array('login_user_no' => 3, 'profile_title' => '追加プロフィール1', 'handle_name' => 'User 3 追加 1', 'explanation' => 'サムネイルあり　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '2', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user3_game_profile1.jpg')),
			array(array('login_user_no' => 3, 'profile_title' => '追加プロフィール2', 'handle_name' => 'User 3 追加 2', 'explanation' => 'サムネイルあり　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true, 'game_list' => '4', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user3_game_profile2.jpg')),
			array(array('login_user_no' => 3, 'profile_title' => '追加プロフィール3', 'handle_name' => 'User 3 追加 3', 'explanation' => 'サムネイルあり　非公開プロフィール', 'status' => 'ステータス', 'game_list' => '6,7,8,9,10', 'thumbnail' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/thumbnail_user3_game_profile3.jpg')),
			
			// User 4
			array(array('login_user_no' => 4, 'profile_title' => '追加プロフィール1', 'handle_name' => 'User 4 追加 1', 'explanation' => 'サムネイルなし　公開プロフィール', 'status' => 'ステータス', 'open_profile' => true)),
			
			// User 5
			array(array('login_user_no' => 5, 'profile_title' => '追加プロフィール1', 'handle_name' => 'User 5 追加 1', 'explanation' => 'サムネイルなし　非公開プロフィール', 'status' => 'ステータス')),
			
		);
	}
	
	
	/**
	* プロフィール保存　エラーチェック
	* @group save_profile
	* @dataProvider dp_post_save_profile_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_save_profile_error($post_data_arr)
	{
		$url = $this->base_url . 'save_profile.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_profile_error()
	{
		return array(
		
			// ----- プレイヤープロフィール編集 -----
			
			// ログインしていない
			array(array('user_no' => 1, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			
			// 必要項目が存在しない
			array(array('login_user_no' => 1, 'user_no' => 1, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー')),
			
			// 他人のプロフィールを編集
			array(array('login_user_no' => 1, 'user_no' => 2, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			
			// 存在しないプロフィールを編集
			array(array('login_user_no' => 1, 'user_no' => 10000, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 'a', 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 'あ', 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			
			// 文字数多すぎ
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => $this->length_over, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => $this->length_over, 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => $this->length_over, 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => $this->length_over)),
			
			// Null
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => null, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => null, 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => null, 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => null)),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => null, 'handle_name' => null, 'explanation' => null, 'status' => null)),
			
			// ''
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => '', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => '', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => '', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => '')),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_title' => '', 'handle_name' => '', 'explanation' => '', 'status' => '')),
			
			
			// ----- 追加プロフィール編集 -----
			
			// ログインしていない
			array(array('profile_no' => 1, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			
			// 必要項目が存在しない
			array(array('login_user_no' => 1, 'profile_no' => 1, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー')),
			
			// 他人のプロフィールを編集
			array(array('login_user_no' => 1, 'profile_no' => 8, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			
			// 存在しないプロフィールを編集
			array(array('login_user_no' => 1, 'profile_no' => 10000, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 'a', 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 'あ', 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			
			// 文字数多すぎ
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => $this->length_over, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => $this->length_over, 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => $this->length_over, 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => $this->length_over)),
			
			// Null
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => null, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => null, 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => null, 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => null)),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => null, 'handle_name' => null, 'explanation' => null, 'status' => null)),
			
			// ''
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => '', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => '', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => '', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => '')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => '', 'handle_name' => '', 'explanation' => '', 'status' => '')),
			
			// ゲームリスト　エラー
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => 'a')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => 'a,b,c')),
			array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => '10000')),
			
			// 0、null、''を送信した場合、game_listはnullとして保存される
			//array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー3', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => '0')),
			//array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => null)),
			//array(array('login_user_no' => 1, 'profile_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => '')),
			
			
			// ----- プロフィール新規追加 -----
			
			// ログインしていない
			array(array('handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			
			// 必要項目が存在しない
			array(array('login_user_no' => 1, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー')),
			
			// 文字数多すぎ
			array(array('login_user_no' => 1, 'profile_title' => $this->length_over, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => $this->length_over, 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => $this->length_over, 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => $this->length_over)),
			
			// Null
			array(array('login_user_no' => 1, 'profile_title' => null, 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => null, 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => null, 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => null)),
			array(array('login_user_no' => 1, 'profile_title' => null, 'handle_name' => null, 'explanation' => null, 'status' => null)),
			
			// ''
			array(array('login_user_no' => 1, 'profile_title' => '', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => '', 'explanation' => 'エラー', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => '', 'status' => 'エラー')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => '')),
			array(array('login_user_no' => 1, 'profile_title' => '', 'handle_name' => '', 'explanation' => '', 'status' => '')),
			
			// ゲームリスト　エラー
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => 'a')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => 'a,b,c')),
			array(array('login_user_no' => 1, 'profile_title' => 'エラー', 'handle_name' => 'エラー', 'explanation' => 'エラー', 'status' => 'エラー', 'game_list' => '10000')),
			
		);
	}
	
	
	
	
	/**
	* プロフィール読み込み
	* @group read_profile
	* @dataProvider dp_post_read_profile
	*/
	public function test_post_read_profile($post_data_arr)
	{
		$url = $this->base_url . 'read_profile.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('profile', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_read_profile()
	{
		return array(
			array(array('page' => 1, 'user_no'=> 1)),
			array(array('page' => 1, 'user_no'=> 2)),
			array(array('page' => 1, 'user_no'=> 3)),
		);
	}
	
	
	/**
	* プロフィール読み込み　エラーチェック
	* @group read_profile
	* @dataProvider dp_post_read_profile_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_read_profile_error($post_data_arr)
	{
		$url = $this->base_url . 'read_profile.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('profile', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_read_profile_error()
	{
		return array(
			
			// 必要項目が足りない
			array(array('user_no'=> 1)),
			array(array('page' => 1)),
			array(array()),
			
			// 少さすぎる数字・大きすぎる数字
			array(array('page' => 0, 'user_no'=> 1)),
			array(array('page' => 10000, 'user_no'=> 1)),
			array(array('page' => 0, 'user_no'=> 2)),
			array(array('page' => 10000, 'user_no'=> 2)),
			array(array('page' => 0, 'user_no'=> 3)),
			array(array('page' => 10000, 'user_no'=> 3)),
			array(array('page' => 1, 'user_no'=> 0)),
			array(array('page' => 1, 'user_no'=> 10000)),
			array(array('page' => 0, 'user_no'=> 0)),
			array(array('page' => 10000, 'user_no'=> 10000)),
			
			// 不正な文字列
			array(array('page' => 'a', 'user_no'=> 1)),
			array(array('page' => 'あ', 'user_no'=> 1)),
			array(array('page' => 'a', 'user_no'=> 2)),
			array(array('page' => 'あ', 'user_no'=> 2)),
			array(array('page' => 'a', 'user_no'=> 3)),
			array(array('page' => 'あ', 'user_no'=> 3)),
			array(array('page' => 1, 'user_no'=> 'a')),
			array(array('page' => 1, 'user_no'=> 'あ')),
			array(array('page' => 'a', 'user_no'=> 'a')),
			array(array('page' => 'あ', 'user_no'=> 'あ')),
			
			// Null
			array(array('page' => null, 'user_no'=> 1)),
			array(array('page' => null, 'user_no'=> 2)),
			array(array('page' => null, 'user_no'=> 3)),
			array(array('page' => 1, 'user_no'=> null)),
			array(array('page' => null, 'user_no'=> null)),
			
		);
	}
	
	
	
	
	/**
	* プロフィール編集フォーム表示
	* プレイヤープロフィール、追加プロフィール、新規追加　3種類のフォームを表示する
	* @group show_edit_profile_form
	* @dataProvider dp_post_show_edit_profile_form
	*/
	public function test_post_show_edit_profile_form($post_data_arr)
	{
		$url = $this->base_url . 'show_edit_profile_form.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_show_edit_profile_form()
	{
		return array(
			
			// 新規追加
			array(array('login_user_no' => 1, 'user_no' => null, 'profile_no'=> null)),
			
			// プレイヤープロフィール編集
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_no'=> null)),
			
			// 追加プロフィール編集
			array(array('login_user_no' => 1, 'user_no' => null, 'profile_no'=> 1))
			
		);
	}
	
	
	/**
	* プロフィール編集フォーム表示　エラーチェック
	* @group show_edit_profile_form
	* @dataProvider dp_post_show_edit_profile_form_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_show_edit_profile_form_error($post_data_arr)
	{
		$url = $this->base_url . 'show_edit_profile_form.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('code', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_show_edit_profile_form_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null, 'user_no' => 1, 'profile_no'=> null)),
			
			// 必要項目が足りない
			array(array('user_no' => 1, 'profile_no'=> null)),
			
			// 少さすぎる数字・大きすぎる数字
			array(array('login_user_no' => 1, 'user_no' => 0, 'profile_no'=> null)),
			array(array('login_user_no' => 1, 'user_no' => null, 'profile_no'=> 0)),
			array(array('login_user_no' => 1, 'user_no' => 10000, 'profile_no'=> null)),
			array(array('login_user_no' => 1, 'user_no' => null, 'profile_no'=> 10000)),
			
			// 全部に値が入っている
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_no'=> 1)),
			
			// 不正な文字列
			array(array('login_user_no' => 1, 'user_no' => 'a', 'profile_no'=> 1)),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_no'=> 'a')),
			array(array('login_user_no' => 1, 'user_no' => 'あ', 'profile_no'=> 1)),
			array(array('login_user_no' => 1, 'user_no' => 1, 'profile_no'=> 'あ')),
			
		);
	}
	
	
	
	
	/**
	* プロフィール削除
	* プレイヤープロフィール、追加プロフィール、新規追加　3種類のフォームを表示する
	* @group delete_profile
	* @dataProvider dp_post_delete_profile
	*/
	public function test_post_delete_profile($post_data_arr)
	{
		$url = $this->base_url . 'delete_profile.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_delete_profile()
	{
		return array(
			
			array(array('login_user_no' => 1, 'profile_no'=> 6)),
			array(array('login_user_no' => 1, 'profile_no'=> 7)),
			array(array('login_user_no' => 2, 'profile_no'=> 13)),
			array(array('login_user_no' => 2, 'profile_no'=> 14)),
			
		);
	}
	
	
	/**
	* プロフィール削除　エラーチェック
	* @group delete_profile2
	* @dataProvider dp_post_delete_profile_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_delete_profile_error($post_data_arr)
	{
		$url = $this->base_url . 'delete_profile.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_delete_profile_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null, 'profile_no'=> 1)),
			
			// 必要項目が足りない
			array(array('profile_no'=> 1)),
			array(array('login_user_no' => 1)),
			
			// 少さすぎる数字・大きすぎる数字
			array(array('login_user_no' => 1, 'profile_no'=> 0)),
			array(array('login_user_no' => 1, 'profile_no'=> 10000)),
			
			// 不正な文字列
			array(array('login_user_no' => 1, 'profile_no'=> 'a')),
			array(array('login_user_no' => 1, 'profile_no'=> 'あ')),
				
			// Null
			array(array('login_user_no' => 1, 'profile_no'=> null)),
			
			// ''
			array(array('login_user_no' => 1, 'profile_no'=> '')),
			
		);
	}
	
	
	
	
	/**
	* 参加コミュニティ読み込み
	* @group read_participation_community
	* @dataProvider dp_post_read_participation_community
	*/
	/*
	public function test_post_read_participation_community($post_data_arr)
	{
		$url = $this->base_url . 'read_participation_community.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	*/
	/**
	* データプロバイダー
	*/
	public function dp_post_read_participation_community()
	{
		return array(
			
			array(array('login_user_no' => 1, 'profile_no'=> 6)),
			array(array('login_user_no' => 1, 'profile_no'=> 7)),
			array(array('login_user_no' => 2, 'profile_no'=> 13)),
			array(array('login_user_no' => 2, 'profile_no'=> 14)),
			
		);
	}
	
	
	/**
	* 参加コミュニティ読み込み　エラーチェック
	* @group read_participation_community2
	* @dataProvider dp_post_read_participation_community_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	/*
	public function test_post_read_participation_community_error($post_data_arr)
	{
		$url = $this->base_url . 'delete_profile.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('code', $result);
	}
	*/
	/**
	* データプロバイダー
	*/
	public function dp_post_read_participation_community_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null, 'profile_no'=> 1)),
			
			// 必要項目が足りない
			array(array('profile_no'=> 1)),
			array(array('login_user_no' => 1)),
			
			// 少さすぎる数字・大きすぎる数字
			array(array('login_user_no' => 1, 'profile_no'=> 0)),
			array(array('login_user_no' => 1, 'profile_no'=> 10000)),
			
			// 不正な文字列
			array(array('login_user_no' => 1, 'profile_no'=> 'a')),
			array(array('login_user_no' => 1, 'profile_no'=> 'あ')),
				
			// Null
			array(array('login_user_no' => 1, 'profile_no'=> null)),
			
			// ''
			array(array('login_user_no' => 1, 'profile_no'=> '')),
			
		);
	}
	
	
	
	
	/**
	* ページ設定保存
	* 要ログイン
	* page_title(required) / user_id(required) / 画像 top_image_1 / 画像 top_image_2 / 画像 top_image_3 / top_image_1_delete / top_image_2_delete / top_image_3_delete
	* 
	* @group save_config_page
	* @dataProvider dp_post_save_config_page
	*/
	public function test_post_save_config_page($post_data_arr)
	{
		$url = $this->base_url . 'save_config_page.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_config_page()
	{
		return array(
			
			array(array('login_user_no' => 1, 'page_title'=> 'User1のページ', 'user_id'=> 'user1', 'top_image_1' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/top_user1_1.jpg', 'top_image_2' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/top_user1_2.jpg', 'top_image_3' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/top_user1_3.jpg')),
			array(array('login_user_no' => 2, 'page_title'=> 'User2のページ', 'user_id'=> 'user2', 'top_image_1' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/top_user2_1.jpg', 'top_image_2' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/top_user2_2.jpg')),
			array(array('login_user_no' => 3, 'page_title'=> 'User3のページ', 'user_id'=> 'user3_1', 'top_image_1' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/top_user3_1.jpg', 'top_image_2' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/top_user3_2.jpg')),
			array(array('login_user_no' => 3, 'page_title'=> 'User3のページ 画像1つ削除後', 'user_id'=> 'user3', 'top_image_2_delete' => 1)),
			
		);
	}
	
	
	/**
	* ページ設定保存　エラーチェック
	* @group save_config_page
	* @dataProvider dp_post_save_config_page_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_save_config_page_error($post_data_arr)
	{
		$url = $this->base_url . 'save_config_page.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_config_page_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null, 'page_title'=> 'エラー', 'user_id'=> 'error')),
			
			// 必要項目が足りない
			array(array('page_title'=> 'エラー', 'user_id'=> 'error')),
			array(array('login_user_no' => 1, 'user_id'=> 'error')),
			array(array('login_user_no' => 1, 'page_title'=> 'エラー')),
			
			// 不正な文字列
			array(array('login_user_no' => 1, 'page_title'=> 'エラー', 'user_id'=> 'あ')),
			
			// Null
			array(array('login_user_no' => 1, 'page_title'=> null, 'user_id'=> 'error')),
			array(array('login_user_no' => 1, 'page_title'=> 'エラー', 'user_id'=> null)),
			
			// ''
			array(array('login_user_no' => 1, 'page_title'=> '', 'user_id'=> 'error')),
			array(array('login_user_no' => 1, 'page_title'=> 'エラー', 'user_id'=> '')),
			
			// エラー画像
			array(array('login_user_no' => 1, 'page_title'=> 'エラー', 'user_id'=> 'error', 'top_image_1' => '@C:/xampp/htdocs/gameusers/public/assets/img/test/error.bmp')),
			
		);
	}
	
	
	
	
	
	/**
	* Eメール仮登録
	* 要ログイン
	* email(required)
	* 
	* @group save_email
	* @dataProvider dp_post_save_email
	*/
	public function test_post_save_email($post_data_arr)
	{
		$url = $this->base_url . 'save_email.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$query = DB::select('hash')->from('provisional_mail');
		$query->where('user_no', '=', $post_data_arr['login_user_no']);
		$arr = $query->execute()->current();
		
		// 本登録
		$config_mail_url = 'http://localhost/gameusers/public/config/mail/' . $arr['hash'];
		$config_mail_result = $this->use_curl($config_mail_url, array());
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_email()
	{
		return array(
			
			array(array('login_user_no' => 1, 'email'=> 'rodinia@hotmail.co.jp')),
			array(array('login_user_no' => 2, 'email'=> 'info@reaf.net')),
			array(array('login_user_no' => 3, 'email'=> 'private-leaf@k.vodafone.ne.jp')),
			array(array('login_user_no' => 4, 'email'=> 'dev@livion.tv')),
			
		);
	}
	
	
	/**
	* Eメール仮登録　エラーチェック
	* @group save_email
	* @dataProvider dp_post_save_email_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_save_email_error($post_data_arr)
	{
		$url = $this->base_url . 'save_email.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_email_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null, 'email'=> 'rodinia@hotmail.co.jp')),
			
			// 必要項目が足りない
			array(array('email'=> 'rodinia@hotmail.co.jp')),
			array(array('login_user_no' => 1)),
			
			// 不正な文字列
			array(array('login_user_no' => 1, 'email'=> 'あ@hotmail.co.jp')),
			array(array('login_user_no' => 1, 'email'=> 'abcdefg')),
			
			// Null
			array(array('login_user_no' => 1, 'email'=> null)),
			
			// ''
			array(array('login_user_no' => 1, 'email'=> '')),
			
			// 重複登録
			array(array('login_user_no' => 1, 'email'=> 'rodinia@hotmail.co.jp')),
			
		);
	}
	
	
	
	/**
	* Eメール削除
	* 要ログイン
	* 
	* @group delete_email
	* @dataProvider dp_post_delete_email
	*/
	public function test_post_delete_email($post_data_arr)
	{
		$url = $this->base_url . 'delete_email.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_delete_email()
	{
		return array(
			
			array(array('login_user_no' => 4)),
			
		);
	}
	
	
	/**
	* Eメール削除　エラーチェック
	* @group delete_email
	* @dataProvider dp_post_delete_email_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_delete_email_error($post_data_arr)
	{
		$url = $this->base_url . 'delete_email.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_delete_email_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null)),
			
			// 必要項目が足りない
			array(array()),
			
		);
	}
	
	
	
	
	
	
	/**
	* ログインID保存
	* 要ログイン
	* login_username(required)
	* 
	* @group save_login_username
	* @dataProvider dp_post_save_login_username
	*/
	public function test_post_save_login_username($post_data_arr)
	{
		$url = $this->base_url . 'save_login_username.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_login_username()
	{
		return array(
			
			array(array('login_user_no' => 1, 'login_username'=> 'user1_1')),
			array(array('login_user_no' => 1, 'login_username'=> 'user1')),
			array(array('login_user_no' => 2, 'login_username'=> 'user2_1')),
			array(array('login_user_no' => 2, 'login_username'=> 'user2')),
			
		);
	}
	
	
	/**
	* ログインID保存　エラーチェック
	* @group save_login_username
	* @dataProvider dp_post_save_login_username_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_save_login_username_error($post_data_arr)
	{
		$url = $this->base_url . 'save_login_username.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_login_username_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null, 'login_username'=> 'error')),
			
			// 必要項目が足りない
			array(array('login_username'=> 'error')),
			array(array('login_user_no' => 1)),
			
			// 少なすぎる文字列
			array(array('login_user_no' => 1, 'login_username'=> 'a')),
			
			// 多すぎる文字列
			array(array('login_user_no' => 1, 'login_username'=> $this->length_over)),
			
			// 不正な文字列
			array(array('login_user_no' => 1, 'login_username'=> 'あ')),
			
			// Null
			array(array('login_user_no' => 1, 'login_username'=> null)),
			
			// ''
			array(array('login_user_no' => 1, 'login_username'=> '')),
			
			// 重複登録
			array(array('login_user_no' => 2, 'login_username'=> 'user2')),
			
		);
	}
	
	
	
	
	/**
	* ログインパスワード保存
	* 要ログイン
	* login_password(required)
	* 
	* @group save_login_password
	* @dataProvider dp_post_save_login_password
	*/
	public function test_post_save_login_password($post_data_arr)
	{
		$url = $this->base_url . 'save_login_password.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_login_password()
	{
		return array(
			
			array(array('login_user_no' => 1, 'login_password'=> '0123456b')),
			array(array('login_user_no' => 1, 'login_password'=> '0123456a')),
			array(array('login_user_no' => 2, 'login_password'=> '0123456b')),
			array(array('login_user_no' => 2, 'login_password'=> '0123456a')),
			
		);
	}
	
	
	/**
	* ログインパスワード保存　エラーチェック
	* @group save_login_password
	* @dataProvider dp_post_save_login_password_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_save_login_password_error($post_data_arr)
	{
		$url = $this->base_url . 'save_login_password.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_save_login_password_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null, 'login_password'=> '0123456a')),
			
			// 必要項目が足りない
			array(array('login_password'=> '0123456a')),
			array(array('login_user_no' => 1)),
			
			// 少なすぎる文字列
			array(array('login_user_no' => 1, 'login_password'=> 'a')),
			
			// 多すぎる文字列
			array(array('login_user_no' => 1, 'login_password'=> $this->length_over)),
			
			// 不正な文字列
			array(array('login_user_no' => 1, 'login_password'=> 'あ')),
			
			// Null
			array(array('login_user_no' => 1, 'login_password'=> null)),
			
			// ''
			array(array('login_user_no' => 1, 'login_password'=> '')),
			
		);
	}
	
	
	
	
	/**
	* アカウント削除
	* 要ログイン
	* 
	* @group delete_player_account
	* @dataProvider dp_post_delete_player_account
	*/
	public function test_post_delete_player_account($post_data_arr)
	{
		$url = $this->base_url . 'delete_player_account.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_delete_player_account()
	{
		return array(
			
			array(array('login_user_no' => 11)),
			
		);
	}
	
	
	/**
	* アカウント削除　エラーチェック
	* @group delete_player_account
	* @dataProvider dp_post_delete_player_account_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_post_delete_player_account_error($post_data_arr)
	{
		$url = $this->base_url . 'delete_player_account.json';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertEquals('success', $result['alert_color']);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_post_delete_player_account_error()
	{
		return array(
			
			// ログインしていない
			array(array('login_user_no' => null)),
			
			// 必要項目が足りない
			array(array()),
			
		);
	}
	
}