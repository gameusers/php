<?php

require_once APPPATH . "tests/common/func.php";

/**
* @group App
* @group Controller
* @group Rest
*/
class Test_Controller_Login extends Common_Func
{
	
	public $base_url = 'http://localhost/gameusers/public/login/';
	
	
	// テスト前 テーブルを空にする
	// protected function setUp()
	// {
		// \DBUtil::truncate_table('users_login');
		// \DBUtil::truncate_table('users_data');
	// }
	
	// テスト前 テーブルを空にする
	public static function setUpBeforeClass()
	{
		\DBUtil::truncate_table('users_login');
		\DBUtil::truncate_table('users_data');
	}
	
	
	/**
	* アカウント作成
	* @group login
	* @dataProvider dp_action_registration
	*/
	public function test_action_registration($post_data_arr)
	{
		$url = $this->base_url . 'registration.php';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		// echo "\n\n" . "output : result" . "\n";
		// var_dump($result);
		// echo "\n";
		
		if ($post_data_arr['registration_username'] == 'user10_administrator')
		{
			$query = \DB::update('users_login');
			$query->set(array('group' => 100));
			$query->where('id', '=', 10);
			$arr = $query->execute();
		}
		
		$this->assertArrayHasKey('user_no', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_action_registration()
	{
		return array(
			array(array('registration_username' => 'user1', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user2', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user3', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user4', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user5', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user6', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user7', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user8', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user9', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user10_administrator', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user11_delete', 'registration_password'=> '0123456a')),
		);
	}
	
	
	/**
	* アカウント作成　エラーチェック
	* @group login
	* @dataProvider dp_action_registration_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_action_registration_error($post_data_arr)
	{
		$url = $this->base_url . 'registration.php';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('user_no', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_action_registration_error()
	{
		return array(
			
			// 文字数不足
			array(array('registration_username' => 'us', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user1', 'registration_password'=> '0123a')),
			array(array('registration_username' => 'us', 'registration_password'=> '0123a')),
			
			// 文字数多すぎ
			array(array('registration_username' => 'abcdefghijklmnopqrstuvwxyz', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user1', 'registration_password'=> 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz')),
			array(array('registration_username' => 'abcdefghijklmnopqrstuvwxyz', 'registration_password'=> 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz')),
			
			// アルファベット以外
			array(array('registration_username' => 'あいうえお', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user1', 'registration_password'=> 'あいうえおか')),
			array(array('registration_username' => 'あいうえお', 'registration_password'=> 'あいうえおか')),
			
			// Null
			array(array('registration_username' => null, 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user1', 'registration_password'=> null)),
			array(array('registration_username' => null, 'registration_password'=> null)),
			
			// 0
			array(array('registration_username' => 0, 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user1', 'registration_password'=> 0)),
			array(array('registration_username' => 0, 'registration_password'=> 0)),
			
			// ''
			array(array('registration_username' => '', 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user1', 'registration_password'=> '')),
			array(array('registration_username' => '', 'registration_password'=> '')),
			
			// true
			array(array('registration_username' => true, 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user1', 'registration_password'=> true)),
			array(array('registration_username' => true, 'registration_password'=> true)),
			
			// false
			array(array('registration_username' => false, 'registration_password'=> '0123456a')),
			array(array('registration_username' => 'user1', 'registration_password'=> false)),
			array(array('registration_username' => false, 'registration_password'=> false)),
			
		);
	}
	
	
	/**
	* ログインを試みる　ユーザーネームとパスワード
	* @group login
	* @dataProvider dp_action_try
	*/
	public function test_action_try($post_data_arr)
	{
		$url = $this->base_url . 'try.php';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('user_no', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_action_try()
	{
		return array(
			array(array('login_username' => 'user1', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user2', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user3', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user4', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user5', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user6', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user7', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user8', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user9', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user10_administrator', 'login_password'=> '0123456a')),
		);
	}
	
	
	
	/**
	* ログインを試みる　ユーザーネームとパスワード　エラーチェック
	* @group login
	* @dataProvider dp_action_try_error
	* @expectedException PHPUnit_Framework_Exception
	*/
	public function test_action_try_error($post_data_arr)
	{
		$url = $this->base_url . 'try.php';
		$result = $this->use_curl($url, $post_data_arr);
		$result = json_decode($result, true);
		
		$this->assertArrayHasKey('user_no', $result);
	}
	
	/**
	* データプロバイダー
	*/
	public function dp_action_try_error()
	{
		return array(
			
			// 文字数不足
			array(array('login_username' => 'us', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user1', 'login_password'=> '0123a')),
			array(array('login_username' => 'us', 'login_password'=> '0123a')),
			
			// 文字数多すぎ
			array(array('login_username' => 'abcdefghijklmnopqrstuvwxyz', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user1', 'login_password'=> 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz')),
			array(array('login_username' => 'abcdefghijklmnopqrstuvwxyz', 'login_password'=> 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz')),
			
			// アルファベット以外
			array(array('login_username' => 'あいうえお', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user1', 'login_password'=> 'あいうえおか')),
			array(array('login_username' => 'あいうえお', 'login_password'=> 'あいうえおか')),
			
			// Null
			array(array('login_username' => null, 'login_password'=> '0123456a')),
			array(array('login_username' => 'user1', 'login_password'=> null)),
			array(array('login_username' => null, 'login_password'=> null)),
			
			// 0
			array(array('login_username' => 0, 'login_password'=> '0123456a')),
			array(array('login_username' => 'user1', 'login_password'=> 0)),
			array(array('login_username' => 0, 'login_password'=> 0)),
			
			// ''
			array(array('login_username' => '', 'login_password'=> '0123456a')),
			array(array('login_username' => 'user1', 'login_password'=> '')),
			array(array('login_username' => '', 'login_password'=> '')),
			
			// true
			array(array('login_username' => true, 'login_password'=> '0123456a')),
			array(array('login_username' => 'user1', 'login_password'=> true)),
			array(array('login_username' => true, 'login_password'=> true)),
			
			// false
			array(array('login_username' => false, 'login_password'=> '0123456a')),
			array(array('login_username' => 'user1', 'login_password'=> false)),
			array(array('login_username' => false, 'login_password'=> false)),
			
		);
	}
	
	
	/**
	* テスト後、テーブルを空にする
	*/
	public static function tearDownAfterClass()
	{
		//\DBUtil::truncate_table('users_login');
		//\DBUtil::truncate_table('users_data');
	}
	
}