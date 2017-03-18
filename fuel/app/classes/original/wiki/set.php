<?php

namespace Original\Wiki;

class Set
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $model_game = null;
	private $model_wiki = null;
	private $model_advertisement = null;

	private $original_validation_common = null;
	private $original_validation_fieldsetex = null;
	private $original_code_advertisement = null;
	private $original_func_common = null;
	private $original_common_date = null;
	private $original_common_text = null;


	// ------------------------------
	//   一般
	// ------------------------------

	// private $game_no = null;
	// private $game_id = null;
	// private $wiki_title = null;

	private $wiki_no = null;
	private $wiki_id = null;
	private $wiki_name = null;
	private $wiki_comment = null;
	private $wiki_password = null;
	private $game_list = null;
	private $datetime_now = null;



	// --------------------------------------------------
	//   コンストラクター
	// --------------------------------------------------

	public function __construct()
	{
		// 管理者でない場合、処理停止
		//if ( ! \Auth::member(100)) exit();

		// --------------------------------------------------
		//   ログインする必要があります。
		// --------------------------------------------------

		if ( ! USER_NO)
		{
			throw new \Exception('ログインする必要があります。');
		}


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$this->model_game = new \Model_Game();
		$this->model_game->agent_type = AGENT_TYPE;
		$this->model_game->user_no = USER_NO;
		$this->model_game->language = LANGUAGE;
		$this->model_game->uri_base = URI_BASE;
		$this->model_game->uri_current = URI_CURRENT;

		$this->model_wiki = new \Model_Wiki();

		$this->model_advertisement = new \Model_Advertisement();


		$this->original_validation_common = new \Original\Validation\Common();
		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();

		$this->original_code_advertisement = new \Original\Code\Advertisement();

		$this->original_func_common = new \Original\Func\Common();

		$this->original_common_date = new \Original\Common\Date();
		$this->set_datetime_now();

		$this->original_common_text = new \Original\Common\Text();

	}




	// --------------------------------------------------
	//   Setter / Getter
	// --------------------------------------------------




	/**
	* Setter / datetime_now
	*
	* @param string $argument
	*/
	public function set_datetime_now()
	{
		$this->datetime_now = $this->original_common_date->sql_format();
	}

	public function get_datetime_now()
	{
		return $this->datetime_now;
	}




	/**
	* Setter / wiki_no
	*
	* @param string $argument
	*/
	public function set_wiki_no($argument)
	{
		if ($argument)
		{
			$this->wiki_no = (int) $this->original_validation_common->wiki_no($argument);
		}
		else
		{
			$this->wiki_no = null;
		}
	}

	public function get_wiki_no()
	{
		return $this->wiki_no;
	}




	/**
	* Setter / wiki_id
	*
	* @param string $argument
	*/
	public function set_wiki_id($argument)
	{
		$this->wiki_id = $this->original_validation_common->wiki_id($argument);
	}

	public function get_wiki_id()
	{
		return $this->wiki_id;
	}



	/**
	* Setter / wiki_name
	*
	* @param string $argument
	*/
	public function set_wiki_name($argument)
	{
		$this->wiki_name = $this->original_validation_common->wiki_name($argument);
	}

	public function get_wiki_name()
	{
		return $this->wiki_name;
	}




	/**
	* Setter / wiki_comment
	*
	* @param string $argument
	*/
	public function set_wiki_comment($argument)
	{
		$this->wiki_comment = $this->original_validation_common->wiki_comment($argument);
	}

	public function get_wiki_comment()
	{
		return $this->wiki_comment;
	}




	/**
	* Setter / wiki_password
	*
	* @param string $argument
	*/
	public function set_wiki_password($argument)
	{
		if ($argument)
		{
			$this->wiki_password = $this->original_validation_common->wiki_password($argument);
		}
		else
		{
			$this->wiki_password = null;
		}
	}

	public function get_wiki_password()
	{
		return $this->wiki_password;
	}




	/**
	* Setter / game_list / コンマ区切りのゲームNo　例）1,2,3
	*
	* @param string $argument
	*/
	public function set_game_list($argument)
	{
		$this->game_list = $this->original_validation_common->game_list($argument);
		$this->game_list = $this->original_func_common->return_db_array('js_db', $this->game_list);
	}

	public function get_game_list()
	{
		return $this->game_list;
	}






	/**
	* Setter / ゲームNo
	*
	* @param array $argument
	*/
	/*
	public function set_game_no($argument)
	{
		$this->game_no = (int) $argument;
	}

	public function get_game_no()
	{
		return $this->game_no;
	}
	*/

	/**
	* Setter / ゲームID
	* 新規に取得する場合、$this->game_noが必要
	*
	* @param array $argument
	*/
	/*
	public function set_game_id($argument = null)
	{
		if ($argument)
		{
			$this->game_id = $argument;
		}
		else
		{
			$db_game_data_arr = $this->model_game->get_game_data($this->get_game_no());
			$this->game_id = $db_game_data_arr['id'];
		}

		//\Debug::dump($this->game_id);
	}

	public function get_game_id()
	{
		return $this->game_id;
	}
	*/


	/**
	* Setter / Wiki タイトル
	*
	* @param array $argument
	*/
	// public function set_wiki_title($argument)
	// {
		// $this->wiki_title = $argument;
	// }
//
	// public function get_wiki_title()
	// {
		// return $this->wiki_title;
	// }





	// --------------------------------------------------
	//   共通
	// --------------------------------------------------

	/**
	* Wikiを作成・編集する
	*
	*/
	public function save_wiki()
	{

		// --------------------------------------------------
		//   URLとパスワードを同じ文字列にすることはできません。
		// --------------------------------------------------

		if ($this->get_wiki_id() === $this->get_wiki_password())
		{
			throw new \Exception('URLとパスワードを同じ文字列にすることはできません。');
		}



		// --------------------------------------------------
		//   Wiki ID 重複チェック
		// --------------------------------------------------

		$temp_arr = array(
			'wiki_id' => $this->get_wiki_id()
		);

		$result_arr = $this->model_wiki->get_wiki($temp_arr);
		$db_wiki_arr = $result_arr['data_arr'];

		$check_duplication_wiki_id = (isset($db_wiki_arr[0]['wiki_no'])) ? true : false;



		// --------------------------------------------------
		//   処理
		// --------------------------------------------------

		// 新規作成の場合
		if ($this->get_wiki_no() === null)
		{

			// --------------------------------------------------
			//   Wiki IDの重複エラー
			// --------------------------------------------------

			if ($check_duplication_wiki_id) throw new \Exception('そのURLはすでに使われています。');


			// --------------------------------------------------
			//   パスワードを入力してください。
			// --------------------------------------------------

			if ( ! $this->get_wiki_password()) throw new \Exception('パスワードを入力してください。');


			// --------------------------------------------------
			//   関連ゲームを選択してください。
			// --------------------------------------------------

			if ( ! $this->get_game_list()) throw new \Exception('関連ゲームを選択してください。');


			// --------------------------------------------------
			//   パスワード　ハッシュ化
			// --------------------------------------------------

			$password_hash = md5($this->get_wiki_password());


			// --------------------------------------------------
			//   保存用配列作成
			// --------------------------------------------------

			$save_arr = array(
				'regi_date' => $this->get_datetime_now(),
				'renewal_date' => $this->get_datetime_now(),
				'sort_date' => $this->get_datetime_now(),
				'user_no' => USER_NO,
				'wiki_id' => $this->get_wiki_id(),
				'wiki_password' => $password_hash,
				'wiki_name' => $this->get_wiki_name(),
				'wiki_comment' => $this->get_wiki_comment(),
				'game_list' => $this->get_game_list()
			);

			$result_insert_wiki_arr = $this->model_wiki->insert_wiki($save_arr);



			// --------------------------------------------------
			//   Wiki作成
			// --------------------------------------------------

			if ($result_insert_wiki_arr['error'])
			{
				throw new \Exception('エラーが起こりました。');
			}
			else
			{
				$dir_pass = APPPATH . 'classes/original/wiki/template';
				$new_dir_pass = DOCROOT . 'wiki/' . $this->get_wiki_id();

				$this->dir_copy($dir_pass, $new_dir_pass);
			}


			// --------------------------------------------------
			//   pukiwiki.ini.php 変更
			// --------------------------------------------------

			$ini_name = $this->get_wiki_name();
			$ini_password = $password_hash;

			$this->change_ini($this->get_wiki_id(), $ini_name, $ini_password);


			$test_type = 'insert';

		}
		// 編集の場合
		else
		{

			// --------------------------------------------------
			//   データ取得
			// --------------------------------------------------

			$temp_arr = array(
				'wiki_no' => $this->get_wiki_no()
			);

			$result_arr = $this->model_wiki->get_wiki($temp_arr);
			$db_wiki_arr = $result_arr['data_arr'];

			//\Debug::dump($db_wiki_arr);
			//exit();

			// --------------------------------------------------
			//   他人のWikiは編集できない
			// --------------------------------------------------

			if ($db_wiki_arr[0]['user_no'] != USER_NO) throw new \Exception('Error 1');


			// --------------------------------------------------
			//   Wiki IDの重複エラー
			// --------------------------------------------------

			if ($db_wiki_arr[0]['wiki_id'] !== $this->get_wiki_id() and $check_duplication_wiki_id) throw new \Exception('そのURLはすでに使われています。');


			// --------------------------------------------------
			//   パスワード　ハッシュ化
			// --------------------------------------------------

			$password_hash = ($this->get_wiki_password()) ? md5($this->get_wiki_password()) : null;


			// --------------------------------------------------
			//   保存用配列作成・データ保存
			// --------------------------------------------------

			$save_arr = array(
				'wiki_no' => $this->get_wiki_no(),
				'renewal_date' => $this->get_datetime_now(),
				'sort_date' => $this->get_datetime_now(),
				'wiki_id' => $this->get_wiki_id(),
				'wiki_name' => $this->get_wiki_name(),
				'wiki_comment' => $this->get_wiki_comment(),
				'game_list' => $this->get_game_list()
			);

			// 管理者パスワード
			if ($password_hash) $save_arr['wiki_password'] = $password_hash;
			// echo '$save_arr';
			// \Debug::dump($save_arr);
			// exit();
			$this->model_wiki->update_wiki($save_arr);



			// --------------------------------------------------
			//   pukiwiki.ini.php 変更
			// --------------------------------------------------

			$ini_name = ($db_wiki_arr[0]['wiki_name'] !== $this->get_wiki_name()) ? $this->get_wiki_name() : null;
			$ini_password = ($password_hash) ? $password_hash : null;

			$this->change_ini($db_wiki_arr[0]['wiki_id'], $ini_name, $ini_password);



			// --------------------------------------------------
			//   フォルダ名変更
			// --------------------------------------------------

			if ($db_wiki_arr[0]['wiki_id'] !== $this->get_wiki_id())
			{
				$this->wiki_rename($db_wiki_arr[0]['wiki_id'], $this->get_wiki_id());
			}



			$test_type = 'update';
		}








		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;


			echo $test_type . '<br><br>';

			if (isset($db_wiki_arr))
			{
				echo '$db_wiki_arr';
				\Debug::dump($db_wiki_arr);
			}

			echo '$save_arr';
			\Debug::dump($save_arr);

		}


		return true;


	}






	/**
	* Wiki広告設定保存
	*
	*/
	public function save_advertisement($arr)
	{


		// --------------------------------------------------
		//   Wiki Noがない場合処理停止
		// --------------------------------------------------

		if ( ! $this->get_wiki_no())
		{
			throw new \Exception('Error 1');
		}


		// --------------------------------------------------
		//   Wikiの所有者でない場合は処理停止
		// --------------------------------------------------

		$temp_arr = array(
			'wiki_no' => $this->get_wiki_no(),
			'limit' => 1,
			'page' => 1
		);

		$db_wiki_arr = $this->model_wiki->get_wiki($temp_arr);

		if (isset($db_wiki_arr['data_arr'][0]['user_no']))
		{
			if ($db_wiki_arr['data_arr'][0]['user_no'] != USER_NO)
			{
				throw new \Exception('Error 2');
			}
		}


		// --------------------------------------------------
		//   広告が存在しているかチェック
		// --------------------------------------------------

		if ($arr['wiki_1'])
		{
			$tmp_arr = array(
				'user_no' => USER_NO,
				'name' => $arr['wiki_1'],
				'limit' => 1,
				'page' => 1
			);

			$result_arr = $this->model_advertisement->get_advertisement($tmp_arr);
			$db_advertisement_wiki_1_arr = $result_arr['data_arr'];

			if (empty($db_advertisement_wiki_1_arr[0]['advertisement_no']))
			{
				throw new \Exception('Error 3');
			}
		}


		if ($arr['wiki_2'])
		{
			$tmp_arr = array(
				'user_no' => USER_NO,
				'name' => $arr['wiki_2'],
				'limit' => 1,
				'page' => 1
			);

			$result_arr = $this->model_advertisement->get_advertisement($tmp_arr);
			$db_advertisement_wiki_2_arr = $result_arr['data_arr'];

			if (empty($db_advertisement_wiki_2_arr[0]['advertisement_no']))
			{
				throw new \Exception('Error 4');
			}
		}



		// --------------------------------------------------
		//   保存用配列作成
		// --------------------------------------------------

		$save_arr = array(
			'wiki_no' => $this->get_wiki_no(),
			'wiki_user_advertisement' => serialize($arr)
		);


		// --------------------------------------------------
		//   データベースに保存
		// --------------------------------------------------

		$result_arr = $this->model_wiki->update_wiki($save_arr);


		//\Debug::dump($db_advertisement_wiki_1_arr);


		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;


			echo '$arr';
			\Debug::dump($arr);

			echo '$db_wiki_arr';
			\Debug::dump($db_wiki_arr);

			if (isset($db_advertisement_wiki_1_arr))
			{
				echo '$db_advertisement_wiki_1_arr';
				\Debug::dump($db_advertisement_wiki_1_arr);
			}

			if (isset($db_advertisement_wiki_2_arr))
			{
				echo '$db_advertisement_wiki_2_arr';
				\Debug::dump($db_advertisement_wiki_2_arr);
			}

			echo '$save_arr';
			\Debug::dump($save_arr);

		}


		return true;


	}





	/**
	* Wikiを削除する
	*
	* @param string $id
	*/
	public function delete_wiki()
	{

		// --------------------------------------------------
		//   Wiki No がない場合 （存在確認済み）、処理停止
		// --------------------------------------------------

		if ( ! $this->get_wiki_no()) return false;



		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'wiki_no' => $this->get_wiki_no()
		);

		$result_arr = $this->model_wiki->get_wiki($temp_arr);
		$db_wiki_arr = $result_arr['data_arr'];



		// --------------------------------------------------
		//   他人のWikiは削除できない　（アドミン除く）
		// --------------------------------------------------

		if ( ! \Auth::member(100) and $db_wiki_arr[0]['user_no'] != USER_NO) throw new \Exception('Error 1');



		// --------------------------------------------------
		//   Wikiをリネームする　削除はしない
		// --------------------------------------------------

		$old_id = $db_wiki_arr[0]['wiki_id'];
		$new_id = 'delete_' . $this->original_common_text->random_text_lowercase(10) . '_' . $old_id;

		$this->wiki_rename($db_wiki_arr[0]['wiki_id'], $new_id);



		// --------------------------------------------------
		//   WikiをOffにする
		// --------------------------------------------------

		$save_arr = array(
			'wiki_no' => $this->get_wiki_no(),
			'on_off' => null
		);

		$this->model_wiki->update_wiki($save_arr);



		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;


			if (isset($db_wiki_arr))
			{
				echo '$db_wiki_arr';
				\Debug::dump($db_wiki_arr);
			}

			echo '$new_id';
			\Debug::dump($new_id);

		}


		// 削除する際のコード
		// $dir_pass = DOCROOT . 'wiki/' . $this->get_game_id();
		// $this->unlink_recursive($dir_pass, true);


		return true;

	}






	/**
	* Wikiのテンプレートから各Wikiにコピーする　plugin、lib、skinなど
	*
	* @param string $id
	*/
	public function copy_template($arr)
	{

		// --------------------------------------------------
		//   Wiki No がない場合 （存在確認済み）、処理停止
		// --------------------------------------------------

		$temp_arr = array(
			'limit' => 10000,
			'page' => 1
		);

		$db_wiki_arr = $this->model_wiki->get_wiki($temp_arr);



		foreach ($db_wiki_arr['data_arr'] as $key => $value)
		{

			$wiki_id = $value['wiki_id'];
			$wiki_name = $value['wiki_name'];
			$wiki_password = $value['wiki_password'];

			//echo $wiki_id . '<br><br>';


			// ハースストーンだけフォルダのパーミッションが違うため、自分でコピーしなければならない。Apacheからは処理できないためエラーになる。
			if ($wiki_id == 'hearthstone') continue;


			// --------------------------------------------------
			//   plugin をコピー
			// --------------------------------------------------

			if (isset($arr['plugin']))
			{
				$dir_pass = APPPATH . 'classes/original/wiki/template/plugin';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/plugin';
				$result = $this->dir_copy($dir_pass, $new_dir_pass);
			}


			// --------------------------------------------------
			//   skin をコピー
			// --------------------------------------------------
			//
			if (isset($arr['skin']))
			{
				$dir_pass = APPPATH . 'classes/original/wiki/template/skin';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/skin';
				$result = $this->dir_copy($dir_pass, $new_dir_pass);
			}


			// --------------------------------------------------
			//   lib をコピー
			// --------------------------------------------------

			if (isset($arr['lib']))
			{
				$dir_pass = APPPATH . 'classes/original/wiki/template/lib';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/lib';
				$result = $this->dir_copy($dir_pass, $new_dir_pass);
			}



			// --------------------------------------------------
			//   pukiwiki.ini.php をコピー
			// --------------------------------------------------

			if (isset($arr['pukiwiki_ini']))
			{
				$dir_pass = APPPATH . 'classes/original/wiki/template/pukiwiki.ini.php';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/pukiwiki.ini.php';
				copy($dir_pass, $new_dir_pass);

				// iniをデータベースと同じ情報に書き換え
				$this->change_ini($wiki_id, $wiki_name, $wiki_password);
			}


			// --------------------------------------------------
			//   etc （その他のファイル、バージョンアップ時など） をコピー
			// --------------------------------------------------
			
			if (isset($arr['etc']))
			{
				$dir_pass = APPPATH . 'classes/original/wiki/template/.htaccess';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/.htaccess';
				copy($dir_pass, $new_dir_pass);

				$dir_pass = APPPATH . 'classes/original/wiki/template/.htpasswd';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/.htpasswd';
				copy($dir_pass, $new_dir_pass);

				$dir_pass = APPPATH . 'classes/original/wiki/template/default.ini.php';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/default.ini.php';
				copy($dir_pass, $new_dir_pass);

				$dir_pass = APPPATH . 'classes/original/wiki/template/en.lng.php';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/en.lng.php';
				copy($dir_pass, $new_dir_pass);

				$dir_pass = APPPATH . 'classes/original/wiki/template/index.php';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/index.php';
				copy($dir_pass, $new_dir_pass);

				$dir_pass = APPPATH . 'classes/original/wiki/template/ja.lng.php';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/ja.lng.php';
				copy($dir_pass, $new_dir_pass);

				$dir_pass = APPPATH . 'classes/original/wiki/template/keitai.ini.php';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/keitai.ini.php';
				copy($dir_pass, $new_dir_pass);

				$dir_pass = APPPATH . 'classes/original/wiki/template/rules.ini.php';
				$new_dir_pass = DOCROOT . 'wiki/' . $wiki_id . '/rules.ini.php';
				copy($dir_pass, $new_dir_pass);
			}

		}




		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;


			if (isset($db_wiki_arr))
			{
				echo '$db_wiki_arr';
				\Debug::dump($db_wiki_arr);
			}

			// echo '$new_id';
			// \Debug::dump($new_id);

		}


		return true;

	}




	/**
	* Wiki ini変更
	*
	* @param string $argument
	*/
	public function change_ini($wiki_id, $wiki_name, $wiki_password)
	{

		// --------------------------------------------------
		//   IDがなく、名前またはパスワードがない場合、処理停止
		// --------------------------------------------------

		if ( ! $wiki_id and (! $wiki_name or ! $wiki_password)) return false;


		// --------------------------------------------------
		//   ファイルを開く
		// --------------------------------------------------

		$lines_arr = file('wiki/' . $wiki_id . '/pukiwiki.ini.php');


		// --------------------------------------------------
		//   ini編集
		// --------------------------------------------------

		foreach ($lines_arr as $key => &$value)
		{

			// Wikiの名前
			if ($wiki_name)
			{
				$pattern = '/page_title = \'.*\'/i';
				$replacement = "page_title = '" . $wiki_name ."'";
				$value = preg_replace($pattern, $replacement, $value);
			}

			// 管理者パスワード
			if ($wiki_password)
			{
				$pattern = '/adminpass = \'.*\'/i';
				$replacement = "adminpass = '{x-php-md5}" . $wiki_password ."'";
				$value = preg_replace($pattern, $replacement, $value);
			}


			// 編集の認証を切る　一時的に使用
			//$pattern = '/edit_auth = [01]/i';
			//$replacement = "edit_auth = 0";
			//$value = preg_replace($pattern, $replacement, $value);

			// 変更されたときに通知するかどうか、1にすると通知、0は通知しない　動作しないためボツ
			// $pattern = '/notify = [01]/i';
			// $replacement = "notify = " . $notify ."";
			// $value = preg_replace($pattern, $replacement, $value);

		}
		unset($value);


		//\Debug::$js_toggle_open = true;
		//\Debug::dump($lines_arr);



		// --------------------------------------------------
		//   ini書き込み
		// --------------------------------------------------

		file_put_contents('wiki/' . $wiki_id . '/pukiwiki.ini.php', $lines_arr);


		return true;

	}





	/**
	* Wikiフォルダ　リネーム
	*
	* @param string $old_id　古いID
	* @param string $new_id　新しいID
	*/
	function wiki_rename($old_id, $new_id)
	{

		$old_dir_pass = DOCROOT . 'wiki/' . $old_id;
		$new_dir_pass = DOCROOT . 'wiki/' . $new_id;

		//\Debug::dump($old_dir_pass);
		//\Debug::dump($new_dir_pass);

		if (is_dir($old_dir_pass))
		{
			rename($old_dir_pass, $new_dir_pass);
			return true;
		}
		else
		{
			return false;
		}

	}



	/**
	* ディレクトリ　コピー
	*
	* @param string $dir_name　コピー元のパス
	* @param string $new_dir　コピー先のパス
	*/
	function dir_copy($dir_name, $new_dir)
	{
		if (!is_dir($new_dir))
		{
			mkdir($new_dir);
		}

		if (is_dir($dir_name))
		{
			if ($dh = opendir($dir_name))
			{
				while (($file = readdir($dh)) !== false)
				{
					if ($file == "." || $file == "..")
					{
						continue;
					}

					if (is_dir($dir_name . "/" . $file))
					{
						$this->dir_copy($dir_name . "/" . $file, $new_dir . "/" . $file);
					}
					else
					{
						//echo $file . "\n\n";
						copy($dir_name . "/" . $file, $new_dir . "/" . $file);
					}
				}
			closedir($dh);
			}
		}
		return true;
	}



	/**
	* パーミッション変更
	*
	* @param string $id
	*/
	public function set_permission()
	{

		// $dir_pass = DOCROOT . 'wiki/' . $id;

		// パーミッション変更
		//chmod($dir_pass, 0755);

		// chmod($dir_pass . '/.htaccess', 0644);
		// chmod($dir_pass . '/en.lng.php', 0644);
		// chmod($dir_pass . '/ja.lng.php', 0644);
		// chmod($dir_pass . '/default.ini.php', 0644);
		// chmod($dir_pass . '/index.php', 0644);
		// chmod($dir_pass . '/keitai.ini.php', 0644);
		// chmod($dir_pass . '/pukiwiki.ini.php', 0644);
		// chmod($dir_pass . '/rules.ini.php', 0644);

		//chmod($dir_pass . '/attach', 0777);
		//chmod($dir_pass . '/attach/.htaccess', 0644);
		//chmod($dir_pass . '/attach/index.html', 0644);

		//chmod($dir_pass . '/backup', 0777);
		//chmod($dir_pass . '/backup/.htaccess', 0644);
		//chmod($dir_pass . '/backup/index.html', 0644);

		//chmod($dir_pass . '/cache', 0777);
		//chmod($dir_pass . '/cache/.htaccess', 0644);
		//chmod($dir_pass . '/cache/index.html', 0644);
		//chmod($dir_pass . '/cache/3A636F6E666967.ref', 0666);

	}










	/**
	* ディレクトリ削除
	*
	* @param string $dir　削除するフォルダのパス
	* @param string $deleteRootToo　削除するフォルダで指定したフォルダも含めて削除する場合はtrue
	*/
	function unlink_recursive($dir, $deleteRootToo)
	{
		if(!$dh = @opendir($dir))
		{
			return;
		}

		while (false !== ($obj = readdir($dh)))
		{
			if($obj == '.' || $obj == '..')
			{
				continue;
			}

			if (!@unlink($dir . '/' . $obj))
			{
				$this->unlink_recursive($dir.'/'.$obj, true);
			}
		}

		closedir($dh);

		if ($deleteRootToo)
		{
			@rmdir($dir);
		}

		return;
	}




	/**
	* 圧縮ファイル解凍
	*
	* @param string $dir　削除するフォルダのパス
	* @param string $deleteRootToo　削除するフォルダで指定したフォルダも含めて削除する場合はtrue
	*/
	/*
	function extract_zip($dir)
	{

		// ZIPファイルのパス指定
		$zip_path = './zip/hoge.zip';

		$zip = new ZipArchive();

		// ZIPファイルをオープン
		$res = $zip->open($zip_path);

		// zipファイルのオープンに成功した場合
		if ($res === true) {

			// 圧縮ファイル内の全てのファイルを指定した解凍先に展開する
			$zip->extractTo('./zip/');

			// ZIPファイルをクローズ
			$zip->close();

		}

	}
	*/



}
