<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Auth;

/**
 * SimpleAuth basic login driver
 *
 * @package     Fuel
 * @subpackage  Auth
 */
class Auth_Login_Simpleauth extends \Auth_Login_Driver
{
	/**
	 * Load the config and setup the remember-me session if needed
	 */
	public static function _init()
	{
		\Config::load('simpleauth', true, true, true);

		// setup the remember-me session object if needed
		if (\Config::get('simpleauth.remember_me.enabled', false))
		{
			static::$remember_me = \Session::forge(array(
				'driver' => 'cookie',
				'cookie' => array(
					'cookie_name' => \Config::get('simpleauth.remember_me.cookie_name', 'rmcookie'),
				),
				'encrypt_cookie' => true,
				'expire_on_close' => false,
				'expiration_time' => \Config::get('simpleauth.remember_me.expiration', 86400 * 31),
			));
		}

		// 言語データ読み込み
		\Lang::load('simpleauth');
	}

	/**
	 * @var  Database_Result  when login succeeded
	 */
	protected $user = null;

	/**
	 * @var  array  value for guest login
	 */
	protected static $guest_login = array(
		'id' => 0,
		'username' => 'guest',
		'group' => '0',
		'login_hash' => false,
		'email' => false
	);

	/**
	 * @var  array  SimpleAuth class config
	 */
	protected $config = array(
		'drivers' => array('group' => array('Simplegroup')),
		'additional_fields' => array('profile_fields'),
	);

	/**
	 * Check for login
	 *
	 * @return  bool
	 */
	protected function perform_check()
	{
		// fetch the username and login hash from the session
		$username    = \Session::get('username');
		$login_hash  = \Session::get('login_hash');

		// only worth checking if there's both a username and login-hash
		if ( ! empty($username) and ! empty($login_hash))
		{
			if (is_null($this->user) or ($this->user['username'] != $username and $this->user != static::$guest_login))
			{
				$this->user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
					->where('username', '=', $username)
					->from(\Config::get('simpleauth.table_name'))
					->execute(\Config::get('simpleauth.db_connection'))->current();
			}

			// return true when login was verified, and either the hash matches or multiple logins are allowed
			if ($this->user and (\Config::get('simpleauth.multiple_logins', false) or $this->user['login_hash'] === $login_hash))
			{
				return true;
			}
		}

		// not logged in, do we have remember-me active and a stored user_id?
		elseif (static::$remember_me and $user_id = static::$remember_me->get('user_id', null))
		{
			return $this->force_login($user_id);
		}

		// no valid login when still here, ensure empty session and optionally set guest_login
		$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
		\Session::delete('username');
		\Session::delete('login_hash');

		return false;
	}

	/**
	 * Check the user exists
	 *
	 * @return  bool
	 */
	public function validate_user($username = null, $password = null, $twitter_id = null, $auth_type = null, $auth_id = null)
	{
		//$username_or_email = trim($username_or_email) ?: trim(\Input::post(\Config::get('simpleauth.username_post_key', 'username')));
		//$password = trim($password) ?: trim(\Input::post(\Config::get('simpleauth.password_post_key', 'password')));
		/*
		if (empty($username_or_email) or empty($password))
		{
			return false;
		}
		*/

		if (isset($username, $password))
		{
			$user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
				->join('users_data', 'LEFT')
				->on('users_data.user_no', '=', 'users_login.id')
				->where('users_data.on_off', '=', 1)
				->where('username', '=', $username)
				->where('password', '=', $this->hash_password($password))
				->from(\Config::get('simpleauth.table_name'))
				->execute(\Config::get('simpleauth.db_connection'))->current();
		}
		else if (isset($twitter_id))
		{
			$user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
				->join('users_data', 'LEFT')
				->on('users_data.user_no', '=', 'users_login.id')
				->where('users_data.on_off', '=', 1)
				->where('twitter_id', '=', $this->hash_password($twitter_id))
				->from(\Config::get('simpleauth.table_name'))
				->execute(\Config::get('simpleauth.db_connection'))->current();
		}
		else if (isset($auth_type, $auth_id))
		{
			$user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
				->join('users_data', 'LEFT')
				->on('users_data.user_no', '=', 'users_login.id')
				->where('users_data.on_off', '=', 1)
				->where_open()
				->where('auth_type1', '=', $auth_type)
				->and_where('auth_id1', '=', $this->hash_password($auth_id))
				->where_close()
				->or_where_open()
				->where('auth_type2', '=', $auth_type)
				->and_where('auth_id2', '=', $this->hash_password($auth_id))
				->or_where_close()
				->or_where_open()
				->where('auth_type3', '=', $auth_type)
				->and_where('auth_id3', '=', $this->hash_password($auth_id))
				->or_where_close()
				->from(\Config::get('simpleauth.table_name'))
				->execute(\Config::get('simpleauth.db_connection'))->current();
		}

		return $user ?: false;
	}

	/**
	 * Login user
	 *
	 * @param   string
	 * @param   string
	 * @param   int
	 * @param   string
	 * @return  bool
	 */
	public function login($username = null, $password = null, $twitter_id = null, $auth_type = null, $auth_id = null)
	{
		if ( ! ($this->user = $this->validate_user($username, $password, $twitter_id, $auth_type, $auth_id)))
		{
			$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
			\Session::delete('username');
			\Session::delete('login_hash');
			return false;
		}

		// register so Auth::logout() can find us
		Auth::_register_verified($this);

		\Session::set('username', $this->user['username']);
		\Session::set('login_hash', $this->create_login_hash());
		\Session::instance()->rotate();


		// ----- ログインログ記録 -----

		$query = \DB::select('login_log')->from('users_data');
		$query->where('user_no', '=', $this->user['id']);
		$arr = $query->execute()->current();
		$log = $arr['login_log'];
		$log_arr = unserialize($log);

		// 日時を取得
		$original_common_date = new \Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();

		// ホスト ＆ ユーザーエージェント取得
		$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$host_user_agent = $host . '/---/' . $user_agent;

		$log_arr[$datetime_now] = $host_user_agent;

		// 履歴30以上で削除
		if (count($log_arr) > 30) {
			array_shift($log_arr);
		}

		$query = \DB::update('users_data');
		$query->set(array('login_log' => serialize($log_arr)));
		$query->where('user_no', '=', $this->user['id']);
		$return = $query->execute();


		return true;
	}

	/**
	 * Force login user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function force_login($user_id = '')
	{
		if (empty($user_id))
		{
			return false;
		}

		$this->user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where_open()
			->where('id', '=', $user_id)
			->where_close()
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'))
			->current();

		if ($this->user == false)
		{
			$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
			\Session::delete('username');
			\Session::delete('login_hash');
			return false;
		}

		\Session::set('username', $this->user['username']);
		\Session::set('login_hash', $this->create_login_hash());
		return true;
	}

	/**
	 * Logout user
	 *
	 * @return  bool
	 */
	public function logout()
	{
		$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
		\Session::delete('username');
		\Session::delete('login_hash');
		return true;
	}

	/**
	 * Create new user
	 *
	 * @param   string
	 * @param   string
	 * @param   string  must contain valid email address
	 * @param   int     group id
	 * @param   Array
	 * @return  bool
	 */
	public function create_user($username = null, $password = null, $email = null, $group = 1, Array $profile_fields = array(), $twitter_id = null, $twitter_access_token = null, $twitter_access_token_secret = null, $auth_type = null, $auth_id = null, $handle_name = null)
	{

		$original_common_text = new \Original\Common\Text();

		// ID＆パスワード
		if (isset($username, $password))
		{
			$username = (string) $username;
			$password = $this->hash_password((string) $password);

			$same_users = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where('username', '=', $username)
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'));

			if ($same_users->count() > 0)
			{
				throw new \SimpleUserUpdateException(__('simpleauth_error_id_duplication'), 1);
			}
		}
		// Twitter
		else if (isset($twitter_id, $twitter_access_token, $twitter_access_token_secret))
		{
			$username = $original_common_text->random_text(25);
			$password = $original_common_text->random_text(32);

			$twitter_id = $this->hash_password($twitter_id);

			$original_common_crypter = new \Original\Common\Crypter();
			$twitter_access_token = $original_common_crypter->encrypt($twitter_access_token);
			$twitter_access_token_secret = $original_common_crypter->encrypt($twitter_access_token_secret);

			$same_users = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where('twitter_id', '=', $twitter_id)
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'));

			if ($same_users->count() > 0)
			{
				throw new \SimpleUserUpdateException(__('simpleauth_error_id_duplication'), 2);
			}
		}
		// Google OAuth & Open ID
		else if (isset($auth_type, $auth_id))
		{
			$username = $original_common_text->random_text(25);
			$password = $original_common_text->random_text(32);

			$auth_id = $this->hash_password($auth_id);

			$same_users = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where_open()
			->where('auth_id1', '=', $auth_id)
			->or_where('auth_id2', '=', $auth_id)
			->or_where('auth_id3', '=', $auth_id)
			->where_close()
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'));

			if ($same_users->count() > 0)
			{
				throw new \SimpleUserUpdateException(__('simpleauth_error_id_duplication'), 3);
			}
		}
		else
		{
			throw new \SimpleUserUpdateException('Error', 4);
		}
		/*
		echo "-----";
		var_dump($username, $password, $email, $group, $profile_fields, $twitter_id, $twitter_access_token, $twitter_access_token_secret, $auth_type, $auth_id);
		echo "-----";
		*/
		//exit();

		try
		{
			// トランザクション開始
			\DB::start_transaction();

			$sql = 'SELECT COUNT(*) FROM users_data';//簡略化出来る　DB::count_records('users');
			$count_users_data = \DB::query($sql)->execute()->current();
			$user_no = $count_users_data['COUNT(*)'] + 1;


			// ----- users_login -----

			$login = array(
			'id'        => (int) $user_no,
			'username'        => $username,
			'password'        => $password,
			'email'           => $email,
			'group'        => (int) $group,
			'profile_fields'  => serialize($profile_fields),
			'last_login'      => 0,
			'login_hash'      => '',
			'created_at'      => \Date::forge()->get_timestamp(),
			'twitter_id'        => $twitter_id,
			'twitter_access_token'        => $twitter_access_token,
			'twitter_access_token_secret'        => $twitter_access_token_secret,
			'auth_type1'        => $auth_type,
			'auth_id1'        => $auth_id,
			);
			\DB::insert(\Config::get('simpleauth.table_name'))
				->set($login)
				->execute(\Config::get('simpleauth.db_connection'));


			// ----- users_data -----

			// 日時を取得
			$original_common_date = new \Original\Common\Date();
			$datetime_now = $original_common_date->sql_format();

			if (empty($handle_name))
			{
				$handle_name = 'User ' . $user_no;
			}

			// ログインログ作成
			$log_arr = array();

			// ホスト ＆ ユーザーエージェント取得
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$host_user_agent = $host . '/---/' . $user_agent;

			$log_arr[$datetime_now] = $host_user_agent;

			$data = array(
			'user_no'        => (int) $user_no,
			'renewal_date'        => $datetime_now,
			'access_date'        => $datetime_now,
			'user_id'        => $original_common_text->random_text_lowercase(25),
			'handle_name'        => $handle_name,
			'notification_data' => 'a:7:{s:6:"on_off";b:1;s:14:"on_off_browser";b:0;s:10:"on_off_app";b:0;s:11:"on_off_mail";b:0;s:12:"browser_info";N;s:14:"receive_device";N;s:11:"device_info";N;}',
			'login_log'        => serialize($log_arr)
			);
			\DB::insert('users_data')
				->set($data)
				->execute(\Config::get('simpleauth.db_connection'));


			// ----- users_game_community -----

			$login = array(
			'user_no'        => (int) $user_no
			);
			\DB::insert('users_game_community')
				->set($login)
				->execute(\Config::get('simpleauth.db_connection'));


			// コミット
			\DB::commit_transaction();
		}
		catch (Exception $e)
		{
			// ロールバック
			\DB::rollback_transaction();
		}

	}


	/**
	* 情報更新
	* @param integer $user_no ユーザーNo
	* @param array $arr 更新データ
	*/
	public function update_user($user_no, $arr)
	{

		// パスワード
		if (array_key_exists('login_password', $arr))
		{
			$login_password = $this->hash_password((string) $arr['login_password']);

			// 保存
			$query = \DB::update('users_login');
			$query->set(array('password' => $login_password));
			$query->where('id', '=', $user_no);
			$return = $query->execute();
		}

		// Twitter情報
		if (array_key_exists('twitter_id', $arr) and array_key_exists('twitter_access_token', $arr) and array_key_exists('twitter_access_token_secret', $arr))
		{
			if (isset($arr['twitter_id'], $arr['twitter_access_token'], $arr['twitter_access_token_secret']))
			{
				$twitter_id = $this->hash_password($arr['twitter_id']);
				$access_token = $arr['twitter_access_token'];
				$access_token_secret = $arr['twitter_access_token_secret'];

				$original_common_crypter = new \Original\Common\Crypter();
				$twitter_access_token = $original_common_crypter->encrypt($access_token);
				$twitter_access_token_secret = $original_common_crypter->encrypt($access_token_secret);
				//var_dump($twitter_id, $access_token, $access_token_secret);

				// すでにTwitter情報が登録されているかチェック
				$same_users = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
				->where('twitter_id', '=', $twitter_id)
				->from(\Config::get('simpleauth.table_name'))
				->execute(\Config::get('simpleauth.db_connection'));

				if ($same_users->count() > 0)
				{
					throw new \SimpleUserUpdateException(__('simpleauth_error_id_duplication'), 1);
				}

				// 更新
				$query = \DB::update('users_login');
				$query->set(array('twitter_id' => $twitter_id, 'twitter_access_token' => $twitter_access_token, 'twitter_access_token_secret' => $twitter_access_token_secret));
				$query->where('id', '=', $user_no);
				$return = $query->execute();

				//var_dump($return);
			}
			else
			{
				throw new \SimpleUserUpdateException('Error', 2);
			}
		}

		// Auth更新
		if (array_key_exists('auth_no', $arr) and array_key_exists('auth_type', $arr) and array_key_exists('auth_id', $arr))
		{
			$auth_no = $arr['auth_no'];
			$auth_type = $arr['auth_type'];
			$auth_id = $this->hash_password($arr['auth_id']);

			// 更新
			$query = \DB::update('users_login');
			$query->set(array('auth_type' . $auth_no => $auth_type, 'auth_id' . $auth_no => $auth_id));
			$query->where('id', '=', $user_no);
			$return = $query->execute();
		}



		// ログインID（再ログインするので一番最後に）
		if (array_key_exists('login_username', $arr))
		{
			// 保存
			$query = \DB::update('users_login');
			$query->set(array('username' => $arr['login_username']));
			$query->where('id', '=', $user_no);
			$return = $query->execute();

			// 再ログイン
			$this->force_login($user_no);
		}
	}

	/**
	 * Update a user's properties
	 * Note: Username cannot be updated, to update password the old password must be passed as old_password
	 *
	 * @param   Array  properties to be updated including profile fields
	 * @param   string
	 * @return  bool
	 */
	 /*
	public function update_user($values, $username = null)
	{
		$username = $username ?: $this->user['username'];
		$current_values = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where('username', '=', $username)
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'));

		if (empty($current_values))
		{
			throw new \SimpleUserUpdateException('Username not found', 4);
		}

		$update = array();
		if (array_key_exists('username', $values))
		{
			throw new \SimpleUserUpdateException('Username cannot be changed.', 5);
		}
		if (array_key_exists('password', $values))
		{
			if (empty($values['old_password'])
				or $current_values->get('password') != $this->hash_password(trim($values['old_password'])))
			{
				throw new \SimpleUserWrongPassword('Old password is invalid');
			}

			$password = trim(strval($values['password']));
			if ($password === '')
			{
				throw new \SimpleUserUpdateException('Password can\'t be empty.', 6);
			}
			$update['password'] = $this->hash_password($password);
			unset($values['password']);
		}
		if (array_key_exists('old_password', $values))
		{
			unset($values['old_password']);
		}
		if (array_key_exists('email', $values))
		{
			$email = filter_var(trim($values['email']), FILTER_VALIDATE_EMAIL);
			if ( ! $email)
			{
				throw new \SimpleUserUpdateException('Email address is not valid', 7);
			}
			$matches = \DB::select()
				->where('email', '=', $email)
				->where('id', '!=', $current_values[0]['id'])
				->from(\Config::get('simpleauth.table_name'))
				->execute(\Config::get('simpleauth.db_connection'));
			if (count($matches))
			{
				throw new \SimpleUserUpdateException('Email address is already in use', 11);
			}
			$update['email'] = $email;
			unset($values['email']);
		}
		if (array_key_exists('group', $values))
		{
			if (is_numeric($values['group']))
			{
				$update['group'] = (int) $values['group'];
			}
			unset($values['group']);
		}
		if ( ! empty($values))
		{
			$profile_fields = @unserialize($current_values->get('profile_fields')) ?: array();
			foreach ($values as $key => $val)
			{
				if ($val === null)
				{
					unset($profile_fields[$key]);
				}
				else
				{
					$profile_fields[$key] = $val;
				}
			}
			$update['profile_fields'] = serialize($profile_fields);
		}

		$update['updated_at'] = \Date::forge()->get_timestamp();

		$affected_rows = \DB::update(\Config::get('simpleauth.table_name'))
			->set($update)
			->where('username', '=', $username)
			->execute(\Config::get('simpleauth.db_connection'));

		// Refresh user
		if ($this->user['username'] == $username)
		{
			$this->user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
				->where('username', '=', $username)
				->from(\Config::get('simpleauth.table_name'))
				->execute(\Config::get('simpleauth.db_connection'))->current();
		}

		return $affected_rows > 0;
	}
	*/

	/**
	 * Change a user's password
	 *
	 * @param   string
	 * @param   string
	 * @param   string  username or null for current user
	 * @return  bool
	 */
	public function change_password($old_password, $new_password, $username = null)
	{
		try
		{
			return (bool) $this->update_user(array('old_password' => $old_password, 'password' => $new_password), $username);
		}
		// Only catch the wrong password exception
		catch (SimpleUserWrongPassword $e)
		{
			return false;
		}
	}

	/**
	 * Generates new random password, sets it for the given username and returns the new password.
	 * To be used for resetting a user's forgotten password, should be emailed afterwards.
	 *
	 * @param   string  $username
	 * @return  string
	 */
	public function reset_password($username)
	{
		$new_password = \Str::random('alnum', 8);
		$password_hash = $this->hash_password($new_password);

		$affected_rows = \DB::update(\Config::get('simpleauth.table_name'))
			->set(array('password' => $password_hash))
			->where('username', '=', $username)
			->execute(\Config::get('simpleauth.db_connection'));

		if ( ! $affected_rows)
		{
			throw new \SimpleUserUpdateException('Failed to reset password, user was invalid.', 8);
		}

		return $new_password;
	}

	/**
	 * Deletes a given user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function delete_user($username)
	{
		if (empty($username))
		{
			throw new \SimpleUserUpdateException('Cannot delete user with empty username', 9);
		}

		$affected_rows = \DB::delete(\Config::get('simpleauth.table_name'))
			->where('username', '=', $username)
			->execute(\Config::get('simpleauth.db_connection'));

		return $affected_rows > 0;
	}

	/**
	 * Creates a temporary hash that will validate the current login
	 *
	 * @return  string
	 */
	public function create_login_hash()
	{
		if (empty($this->user))
		{
			throw new \SimpleUserUpdateException('User not logged in, can\'t create login hash.', 10);
		}

		$last_login = \Date::forge()->get_timestamp();
		//$login_hash = sha1(\Config::get('simpleauth.login_hash_salt').$this->user['username'].$last_login);
		$login_hash = sha1(\Config::get('simpleauth.login_hash_salt').$this->user['username']);
		// $last_loginを削除したのは、マルチデバイスで同時にログインできるようにするため　http://h19e.jugem.jp/?eid=76

		\DB::update(\Config::get('simpleauth.table_name'))
			->set(array('last_login' => $last_login, 'login_hash' => $login_hash))
			->where('username', '=', $this->user['username'])
			->execute(\Config::get('simpleauth.db_connection'));

		$this->user['login_hash'] = $login_hash;

		return $login_hash;
	}

	/**
	 * Get the user's ID
	 *
	 * @return  Array  containing this driver's ID & the user's ID
	 */
	public function get_user_id()
	{
		if (empty($this->user))
		{
			return false;
		}

		return (int) $this->user['id'];
	}

	/**
	 * Get the user's groups
	 *
	 * @return  Array  containing the group driver ID & the user's group ID
	 */
	public function get_groups()
	{
		if (empty($this->user))
		{
			return false;
		}

		return array(array('Simplegroup', $this->user['group']));
	}

	/**
	 * Getter for user data
	 *
	 * @param  string  name of the user field to return
	 * @param  mixed  value to return if the field requested does not exist
	 *
	 * @return  mixed
	 */
	public function get($field, $default = null)
	{
		if (isset($this->user[$field]))
		{
			return $this->user[$field];
		}
		elseif (isset($this->user['profile_fields']))
		{
			return $this->get_profile_fields($field, $default);
		}

		return $default;
	}

	/**
	 * Get the user's emailaddress
	 *
	 * @return  string
	 */
	public function get_email()
	{
		return $this->get('email', false);
	}

	/**
	 * Get the user's screen name
	 *
	 * @return  string
	 */
	public function get_screen_name()
	{
		if (empty($this->user))
		{
			return false;
		}

		return $this->user['username'];
	}

	/**
	 * Get the user's profile fields
	 *
	 * @return  Array
	 */
	public function get_profile_fields($field = null, $default = null)
	{
		if (empty($this->user))
		{
			return false;
		}

		if (isset($this->user['profile_fields']))
		{
			is_array($this->user['profile_fields']) or $this->user['profile_fields'] = @unserialize($this->user['profile_fields']);
		}
		else
		{
			$this->user['profile_fields'] = array();
		}

		return is_null($field) ? $this->user['profile_fields'] : \Arr::get($this->user['profile_fields'], $field, $default);
	}

	/**
	 * Extension of base driver method to default to user group instead of user id
	 */
	public function has_access($condition, $driver = null, $user = null)
	{
		if (is_null($user))
		{
			$groups = $this->get_groups();
			$user = reset($groups);
		}
		return parent::has_access($condition, $driver, $user);
	}

	/**
	 * Extension of base driver because this supports a guest login when switched on
	 */
	public function guest_login()
	{
		return \Config::get('simpleauth.guest_login', true);
	}
}

// end of file simpleauth.php
