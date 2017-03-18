<?php

class Model_Good extends Model_Crud
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
	//   Goodボタン　データ取得
	// --------------------------------------------------
	
	/**
	* Good Log 取得
	* @return array
	*/
	public function get_good_log($arr)
	{
		$query = DB::select('*')->from('good_log');
		
		$query->where('type', '=', $arr['type']);
		if (isset($arr['no'])) $query->where('no', '=', $arr['no']);
		if (isset($arr['id'])) $query->where('id', '=', $arr['id']);
		if (isset($arr['user_no'])) $query->where('user_no', '=', $arr['user_no']);
		if (isset($arr['host'])) $query->where('host', '=', $arr['host']);
		
		//if(array_key_exists('user_no', $arr)) $query->where('user_no', '=', $arr['user_no']);
		//if(array_key_exists('host', $arr)) $query->where('host', '=', $arr['host']);
		$result_arr = $query->execute()->current();
		//echo DB::last_query();
		return $result_arr;
	}
	
	
	
	// --------------------------------------------------
	//   Goodボタン　データ更新
	// --------------------------------------------------
	
	/**
	* Goodボタン　User Data
	* @return array
	*/
	public function plus_good_user_data($user_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------
		
		$db_user_data_arr = $model_user->get_user_data($user_no, null);
		$good = $db_user_data_arr['good'] + 1;
		$level = $db_user_data_arr['level'] + 1;
		
		
		if (isset($db_user_data_arr) and $user_no != $this->user_no)
		{
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   データ更新
				// --------------------------------------------------
				
				$query = DB::update('users_data');
				$query->set(array('good' => DB::expr('good + 1'), 'level' => DB::expr('level + 1')));
				$query->where('user_no', '=', $user_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'user', 'no' => $user_no, 'target_user_no' => $user_no, 'target_profile_no' => null, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				
				// --------------------------------------------------
				//   結果
				// --------------------------------------------------
				
				$result = true;
				$level_id_user = 'level_user_' . $user_no;
				$level_user = $level;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	/**
	* Goodボタン　プロフィール
	* @return array
	*/
	public function plus_good_profile($profile_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		
		// --------------------------------------------------
		//   Profile取得
		// --------------------------------------------------
		
		$db_profile_arr = $model_user->get_profile($profile_no);
		$author_user_no = $db_profile_arr['author_user_no'];
		$good = $db_profile_arr['good'] + 1;
		$level_profile = $db_profile_arr['level'] + 1;
		//echo '$db_profile_arr';
		//var_dump($db_profile_arr);
		
		
		if (isset($db_profile_arr) and $author_user_no != $this->user_no)
		{
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   User Data更新
				// --------------------------------------------------
				
				if ($db_profile_arr['open_profile'])
				{
					$db_user_data_arr = $model_user->get_user_data($author_user_no, null);
					$level_user = $db_user_data_arr['level'] + 1;
					
					$query = DB::update('users_data');
					$query->set(array('level' => DB::expr('level + 1')));
					$query->where('user_no', '=', $author_user_no);
					$arr = $query->execute();
					
					$level_id_user = 'level_user_' . $author_user_no;
				}
				else
				{
					$level_id_user = null;
					$level_user = null;
				}
				
				
				// --------------------------------------------------
				//   Profile更新
				// --------------------------------------------------
				
				$query = DB::update('profile');
				$query->set(array('good' => DB::expr('good + 1'), 'level' => DB::expr('level + 1')));
				$query->where('profile_no', '=', $profile_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'profile', 'no' => $profile_no, 'target_user_no' => $author_user_no, 'target_profile_no' => $profile_no, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				
				// --------------------------------------------------
				//   結果
				// --------------------------------------------------
				
				$result = true;
				$level_id_profile = 'level_profile_' . $profile_no;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodボタン　募集
	* @return array
	*/
	public function plus_good_recruitment($recruitment_id)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_gc = new Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;
		
		
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------
		
		$db_recruitment_arr = $model_gc->get_recruitment_appoint($recruitment_id);
		
		//var_dump($db_recruitment_arr);
		//exit();
		
		$target_user_no = $db_recruitment_arr['user_no'];
		$target_profile_no = $db_recruitment_arr['profile_no'];
		$good = $db_recruitment_arr['good'] + 1;
		
		
		if (isset($this->user_no) and $target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		else if ($db_recruitment_arr['anonymity'] or ($target_user_no == null and $target_profile_no == null))
		{
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   募集更新
				// --------------------------------------------------
			
				$query = DB::update('recruitment');
				$query->set(array('good' => DB::expr('good + 1')));
				$query->where('recruitment_id', '=', $recruitment_id);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'recruitment', 'id' => $recruitment_id, 'target_user_no' => $target_user_no, 'target_profile_no' => null, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$level_user = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   募集更新
				// --------------------------------------------------
			
				$query = DB::update('recruitment');
				$query->set(array('good' => DB::expr('good + 1')));
				$query->where('recruitment_id', '=', $recruitment_id);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   ユーザーデータ更新
				// --------------------------------------------------
				
				if (isset($db_user_data_arr))
				{
					$query = DB::update('users_data');
					$query->set(array('level' => DB::expr('level + 1')));
					$query->where('user_no', '=', $target_user_no);
					$arr = $query->execute();
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'recruitment', 'id' => $recruitment_id, 'target_user_no' => $target_user_no, 'target_profile_no' => null, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = 'level_user_' . $target_user_no;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$level_profile = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   募集更新
				// --------------------------------------------------
			
				$query = DB::update('recruitment');
				$query->set(array('good' => DB::expr('good + 1')));
				$query->where('recruitment_id', '=', $recruitment_id);
				$arr = $query->execute();
				
				
				$level_id_user = null;
				$level_user = null;
				
				if (isset($db_profile_arr))
				{
					
					// --------------------------------------------------
					//   User Data更新
					// --------------------------------------------------
					
					if ($db_profile_arr['open_profile'])
					{
						$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
						$level_user = $db_user_data_arr['level'] + 1;
						
						$query = DB::update('users_data');
						$query->set(array('level' => DB::expr('level + 1')));
						$query->where('user_no', '=', $target_user_no);
						$arr = $query->execute();
						
						$level_id_user = 'level_user_' . $target_user_no;
					}
					
					
					// --------------------------------------------------
					//   Profile更新
					// --------------------------------------------------
					
					$query = DB::update('profile');
					$query->set(array('level' => DB::expr('level + 1')));
					$query->where('profile_no', '=', $target_profile_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'recruitment', 'id' => $recruitment_id, 'target_user_no' => $target_user_no, 'target_profile_no' => $target_profile_no, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_profile = 'level_profile_' . $target_profile_no;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodボタン　募集　返信
	* @return array
	*/
	public function plus_good_recruitment_reply($recruitment_reply_id)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_gc = new Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;
		
		
		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------
		
		$db_recruitment_reply_arr = $model_gc->get_recruitment_reply_appoint($recruitment_reply_id);
		
		//var_dump($db_recruitment_reply_arr);
		//exit();
		
		$target_user_no = $db_recruitment_reply_arr['user_no'];
		$target_profile_no = $db_recruitment_reply_arr['profile_no'];
		$good = $db_recruitment_reply_arr['good'] + 1;
		
		
		if (isset($this->user_no) and $target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		else if ($db_recruitment_reply_arr['anonymity'] or ($target_user_no == null and $target_profile_no == null))
		{
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   募集返信更新
				// --------------------------------------------------
			
				$query = DB::update('recruitment_reply');
				$query->set(array('good' => DB::expr('good + 1')));
				$query->where('recruitment_reply_id', '=', $recruitment_reply_id);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'recruitment_reply', 'id' => $recruitment_reply_id, 'target_user_no' => $target_user_no, 'target_profile_no' => null, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$level_user = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   募集返信更新
				// --------------------------------------------------
			
				$query = DB::update('recruitment_reply');
				$query->set(array('good' => DB::expr('good + 1')));
				$query->where('recruitment_reply_id', '=', $recruitment_reply_id);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   ユーザーデータ更新
				// --------------------------------------------------
				
				if (isset($db_user_data_arr))
				{
					$query = DB::update('users_data');
					$query->set(array('level' => DB::expr('level + 1')));
					$query->where('user_no', '=', $target_user_no);
					$arr = $query->execute();
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'recruitment_reply', 'id' => $recruitment_reply_id, 'target_user_no' => $target_user_no, 'target_profile_no' => null, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = 'level_user_' . $target_user_no;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$level_profile = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   募集返信更新
				// --------------------------------------------------
			
				$query = DB::update('recruitment_reply');
				$query->set(array('good' => DB::expr('good + 1')));
				$query->where('recruitment_reply_id', '=', $recruitment_reply_id);
				$arr = $query->execute();
				
				
				$level_id_user = null;
				$level_user = null;
				
				if (isset($db_profile_arr))
				{
					
					// --------------------------------------------------
					//   User Data更新
					// --------------------------------------------------
					
					if ($db_profile_arr['open_profile'])
					{
						$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
						$level_user = $db_user_data_arr['level'] + 1;
						
						$query = DB::update('users_data');
						$query->set(array('level' => DB::expr('level + 1')));
						$query->where('user_no', '=', $target_user_no);
						$arr = $query->execute();
						
						$level_id_user = 'level_user_' . $target_user_no;
					}
					
					
					// --------------------------------------------------
					//   Profile更新
					// --------------------------------------------------
					
					$query = DB::update('profile');
					$query->set(array('level' => DB::expr('level + 1')));
					$query->where('profile_no', '=', $target_profile_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'recruitment_reply', 'id' => $recruitment_reply_id, 'target_user_no' => $target_user_no, 'target_profile_no' => $target_profile_no, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_profile = 'level_profile_' . $target_profile_no;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodボタン　告知
	* @return array
	*/
	/*
	public function plus_good_announcement($announcement_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_co = new Model_Co();
		$model_co->user_no = $this->user_no;
		$model_co->host = $this->host;
		$model_co->user_agent = $this->user_agent;
		
		
		// --------------------------------------------------
		//   告知取得
		// --------------------------------------------------
		
		$db_announcement_arr = $model_co->get_announcement($announcement_no);
		$target_user_no = $db_announcement_arr['user_no'];
		$target_profile_no = $db_announcement_arr['profile_no'];
		$announcement_good = $db_announcement_arr['good'] + 1;
		
		
		if ($target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id = null;
			$level = null;
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$user_data_level = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				// --------------------------------------------------
				//   告知更新
				// --------------------------------------------------
			
				$query = DB::update('announcement');
				$query->set(array('good' => $announcement_good));
				$query->where('announcement_no', '=', $announcement_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   ユーザーデータ更新
				// --------------------------------------------------
				
				$query = DB::update('users_data');
				$query->set(array('level' => $user_data_level));
				$query->where('user_no', '=', $target_user_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'announcement', 'no' => $announcement_no, 'target_user_no' => $target_user_no, 'target_profile_no' => null, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id = 'level_user_' . $target_user_no;
				$level = $user_data_level;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id = null;
				$level = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$profile_level = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				// --------------------------------------------------
				//   告知更新
				// --------------------------------------------------
			
				$query = DB::update('announcement');
				$query->set(array('good' => $announcement_good));
				$query->where('announcement_no', '=', $announcement_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   ユーザーデータ更新
				// --------------------------------------------------
				
				$query = DB::update('profile');
				$query->set(array('level' => $profile_level));
				$query->where('profile_no', '=', $target_profile_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				$query->set(array('regi_date' => $datetime_now, 'type' => 'announcement', 'no' => $announcement_no, 'target_user_no' => $target_user_no, 'target_profile_no' => $target_profile_no, 'user_no' => $this->user_no, 'host' => $this->host, 'user_agent' => $this->user_agent));
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id = 'level_profile_' . $target_profile_no;
				$level = $profile_level;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id = null;
				$level = null;
				
			}
			
		}
		
		return array('result' => $result, 'level_id' => $level_id, 'level' => $level);
		
	}
	*/
	
	
	
	
	/**
	* Goodボタン　BBSスレッド　GC
	* @return array
	*/
	public function plus_good_bbs_thread_gc($bbs_thread_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_bbs = new Model_Bbs();
		
		
		
		// --------------------------------------------------
		//   BBSスレッド取得
		// --------------------------------------------------
		
		$temp_arr = array(
			'bbs_thread_no' => $bbs_thread_no,
			'page' => 1,
			'limit' => 1
		);
		
		$temp_arr = $model_bbs->get_bbs_thread_gc($temp_arr);
		$db_bbs_thread_arr = $temp_arr[0];
		
		$target_user_no = $db_bbs_thread_arr['user_no'];
		$target_profile_no = $db_bbs_thread_arr['profile_no'];
		$good = $db_bbs_thread_arr['good'] + 1;
		
		//\Debug::dump($db_bbs_thread_arr);
		
		//exit();
		
		
		if (isset($this->user_no) and $target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		else if ($db_bbs_thread_arr['anonymity'] or ($target_user_no == null and $target_profile_no == null))
		{
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSスレッド更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_thread_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_thread_gc',
					'no' => $bbs_thread_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$level_user = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSスレッド更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_thread_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$arr = $query->execute();
				
				
				if (isset($db_user_data_arr))
				{
					
					// --------------------------------------------------
					//   ユーザーデータ更新
					// --------------------------------------------------
					
					$query = DB::update('users_data');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('user_no', '=', $target_user_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_thread_gc',
					'no' => $bbs_thread_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = 'level_user_' . $target_user_no;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$level_profile = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSスレッド更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_thread_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$arr = $query->execute();
				
				
				$level_id_user = null;
				$level_user = null;
				
				if (isset($db_profile_arr))
				{
					
					// --------------------------------------------------
					//   User Data更新
					// --------------------------------------------------
					
					if ($db_profile_arr['open_profile'])
					{
						$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
						$level_user = $db_user_data_arr['level'] + 1;
						
						$query = DB::update('users_data');
						$query->set(array('level' => DB::expr('level + 1')));
						$query->where('user_no', '=', $target_user_no);
						$arr = $query->execute();
						
						$level_id_user = 'level_user_' . $target_user_no;
					}
					
					
					// --------------------------------------------------
					//   Profile更新
					// --------------------------------------------------
					
					$query = DB::update('profile');
					$query->set(array('level' => DB::expr('level + 1')));
					$query->where('profile_no', '=', $target_profile_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_thread_gc',
					'no' => $bbs_thread_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => $target_profile_no,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_profile = 'level_profile_' . $target_profile_no;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodボタン　BBSスレッド　UC
	* @return array
	*/
	public function plus_good_bbs_thread_uc($bbs_thread_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_co = new Model_Co();
		$model_co->user_no = $this->user_no;
		$model_co->host = $this->host;
		$model_co->user_agent = $this->user_agent;
		
		
		
		// --------------------------------------------------
		//   BBSスレッド取得
		// --------------------------------------------------
		
		$db_bbs_thread_arr = $model_co->get_bbs_thread($bbs_thread_no);
		$target_user_no = $db_bbs_thread_arr['user_no'];
		$target_profile_no = $db_bbs_thread_arr['profile_no'];
		$good = $db_bbs_thread_arr['good'] + 1;
		
		
		if (isset($this->user_no) and $target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		else if ($db_bbs_thread_arr['anonymity'] or ($target_user_no == null and $target_profile_no == null))
		{
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSスレッド更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_thread');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_thread_uc',
					'no' => $bbs_thread_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$level_user = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSスレッド更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_thread');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$arr = $query->execute();
				
				
				if (isset($db_user_data_arr))
				{
					
					// --------------------------------------------------
					//   ユーザーデータ更新
					// --------------------------------------------------
					
					$query = DB::update('users_data');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('user_no', '=', $target_user_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_thread_uc',
					'no' => $bbs_thread_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = 'level_user_' . $target_user_no;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$level_profile = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSスレッド更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_thread');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_thread_no', '=', $bbs_thread_no);
				$arr = $query->execute();
				
				
				$level_id_user = null;
				$level_user = null;
				
				if (isset($db_profile_arr))
				{
					
					// --------------------------------------------------
					//   User Data更新
					// --------------------------------------------------
					
					if ($db_profile_arr['open_profile'])
					{
						$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
						$level_user = $db_user_data_arr['level'] + 1;
						
						$query = DB::update('users_data');
						
						$query->set(array(
							'level' => DB::expr('level + 1')
						));
						
						$query->where('user_no', '=', $target_user_no);
						$arr = $query->execute();
						
						$level_id_user = 'level_user_' . $target_user_no;
					}
					
					
					// --------------------------------------------------
					//   Profile更新
					// --------------------------------------------------
					
					$query = DB::update('profile');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('profile_no', '=', $target_profile_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_thread_uc',
					'no' => $bbs_thread_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => $target_profile_no,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_profile = 'level_profile_' . $target_profile_no;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodボタン　BBSコメント　GC
	* @return array
	*/
	public function plus_good_bbs_comment_gc($bbs_comment_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_bbs = new Model_Bbs();
		
		
		// --------------------------------------------------
		//   BBSコメント取得
		// --------------------------------------------------
		
		$temp_arr = array(
			'bbs_comment_no' => $bbs_comment_no,
			'page' => 1,
			'limit' => 1
		);
		
		$temp_arr = $model_bbs->get_bbs_comment_gc($temp_arr);
		$db_bbs_comment_arr = $temp_arr[0];
		
		$target_user_no = $db_bbs_comment_arr['user_no'];
		$target_profile_no = $db_bbs_comment_arr['profile_no'];
		$good = $db_bbs_comment_arr['good'] + 1;
		
		
		if (isset($this->user_no) and $target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		else if ($db_bbs_comment_arr['anonymity'] or ($target_user_no == null and $target_profile_no == null))
		{
			
			// --------------------------------------------------
			//   匿名または一般ユーザーが投稿したコメント
			// --------------------------------------------------
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_comment_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_comment_gc',
					'no' => $bbs_comment_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$level_user = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_comment_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$arr = $query->execute();
				
				
				if (isset($db_user_data_arr))
				{
					
					// --------------------------------------------------
					//   ユーザーデータ更新
					// --------------------------------------------------
					
					$query = DB::update('users_data');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('user_no', '=', $target_user_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_comment_gc',
					'no' => $bbs_comment_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = 'level_user_' . $target_user_no;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$level_profile = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_comment_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$arr = $query->execute();
				
				
				$level_id_user = null;
				$level_user = null;
				
				if (isset($db_profile_arr))
				{
					
					// --------------------------------------------------
					//   User Data更新
					// --------------------------------------------------
					
					if ($db_profile_arr['open_profile'])
					{
						$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
						$level_user = $db_user_data_arr['level'] + 1;
						
						
						$query = DB::update('users_data');
						
						$query->set(array(
							'level' => DB::expr('level + 1')
						));
						
						$query->where('user_no', '=', $target_user_no);
						$arr = $query->execute();
						
						
						$level_id_user = 'level_user_' . $target_user_no;
					}
					
					
					// --------------------------------------------------
					//   Profile更新
					// --------------------------------------------------
					
					$query = DB::update('profile');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('profile_no', '=', $target_profile_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_comment_gc',
					'no' => $bbs_comment_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => $target_profile_no,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				
				$result = true;
				$level_id_profile = 'level_profile_' . $target_profile_no;
				$level_profile = $level_profile;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodボタン　BBSコメント　UC
	* @return array
	*/
	public function plus_good_bbs_comment_uc($bbs_comment_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_co = new Model_Co();
		$model_co->user_no = $this->user_no;
		$model_co->host = $this->host;
		$model_co->user_agent = $this->user_agent;
		
		
		
		// --------------------------------------------------
		//   BBSコメント取得
		// --------------------------------------------------
		
		$db_bbs_comment_arr = $model_co->get_bbs_comment($bbs_comment_no);
		$target_user_no = $db_bbs_comment_arr['user_no'];
		$target_profile_no = $db_bbs_comment_arr['profile_no'];
		$good = $db_bbs_comment_arr['good'] + 1;
		
		
		if (isset($this->user_no) and $target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		else if ($db_bbs_comment_arr['anonymity'] or ($target_user_no == null and $target_profile_no == null))
		{
			
			// --------------------------------------------------
			//   匿名または一般ユーザーが投稿したコメント
			// --------------------------------------------------
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_comment');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_comment_uc',
					'no' => $bbs_comment_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$level_user = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_comment');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$arr = $query->execute();
				
				
				if (isset($db_user_data_arr))
				{
					
					// --------------------------------------------------
					//   ユーザーデータ更新
					// --------------------------------------------------
					
					$query = DB::update('users_data');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('user_no', '=', $target_user_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_comment_uc',
					'no' => $bbs_comment_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = 'level_user_' . $target_user_no;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$level_profile = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_comment');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_comment_no', '=', $bbs_comment_no);
				$arr = $query->execute();
				
				
				$level_id_user = null;
				$level_user = null;
				
				if (isset($db_profile_arr))
				{
					
					// --------------------------------------------------
					//   User Data更新
					// --------------------------------------------------
					
					if ($db_profile_arr['open_profile'])
					{
						$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
						$level_user = $db_user_data_arr['level'] + 1;
						
						
						$query = DB::update('users_data');
						
						$query->set(array(
							'level' => DB::expr('level + 1')
						));
						
						$query->where('user_no', '=', $target_user_no);
						$arr = $query->execute();
						
						
						$level_id_user = 'level_user_' . $target_user_no;
					}
					
					
					// --------------------------------------------------
					//   Profile更新
					// --------------------------------------------------
					
					$query = DB::update('profile');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('profile_no', '=', $target_profile_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_comment_uc',
					'no' => $bbs_comment_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => $target_profile_no,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				
				$result = true;
				$level_id_profile = 'level_profile_' . $target_profile_no;
				$level_profile = $level_profile;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodボタン　BBS返信　GC
	* @return array
	*/
	public function plus_good_bbs_reply_gc($bbs_reply_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_bbs = new Model_Bbs();
		
		
		
		// --------------------------------------------------
		//   BBS返信取得
		// --------------------------------------------------
		
		$temp_arr = array(
			'bbs_reply_no' => $bbs_reply_no,
			'page' => 1,
			'limit' => 1
		);
		
		$temp_arr = $model_bbs->get_bbs_reply_gc($temp_arr);
		$db_bbs_reply_arr = $temp_arr[0];
		
		$target_user_no = $db_bbs_reply_arr['user_no'];
		$target_profile_no = $db_bbs_reply_arr['profile_no'];
		$good = $db_bbs_reply_arr['good'] + 1;
		
		
		if (isset($this->user_no) and $target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		else if ($db_bbs_reply_arr['anonymity'] or ($target_user_no == null and $target_profile_no == null))
		{
			
			// --------------------------------------------------
			//   匿名または一般ユーザーが投稿したコメント
			// --------------------------------------------------
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_reply_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_reply_no', '=', $bbs_reply_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_reply_gc',
					'no' => $bbs_reply_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$level_user = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_reply_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_reply_no', '=', $bbs_reply_no);
				$arr = $query->execute();
				
				
				if (isset($db_user_data_arr))
				{
					
					// --------------------------------------------------
					//   ユーザーデータ更新
					// --------------------------------------------------
					
					$query = DB::update('users_data');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('user_no', '=', $target_user_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_reply_gc',
					'no' => $bbs_reply_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = 'level_user_' . $target_user_no;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$level_profile = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_reply_gc');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_reply_no', '=', $bbs_reply_no);
				$arr = $query->execute();
				
				
				$level_id_user = null;
				$level_user = null;
				
				if (isset($db_profile_arr))
				{
					
					// --------------------------------------------------
					//   User Data更新
					// --------------------------------------------------
					
					if ($db_profile_arr['open_profile'])
					{
						$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
						$level_user = $db_user_data_arr['level'] + 1;
						
						$query = DB::update('users_data');
						
						$query->set(array(
							'level' => DB::expr('level + 1')
						));
						
						$query->where('user_no', '=', $target_user_no);
						$arr = $query->execute();
						
						$level_id_user = 'level_user_' . $target_user_no;
					}
					
					
					// --------------------------------------------------
					//   Profile更新
					// --------------------------------------------------
					
					$query = DB::update('profile');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('profile_no', '=', $target_profile_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_reply_gc',
					'no' => $bbs_reply_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => $target_profile_no,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				
				$result = true;
				$level_id_profile = 'level_profile_' . $target_profile_no;
				$level_profile = $level_profile;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodボタン　BBS返信　UC
	* @return array
	*/
	public function plus_good_bbs_reply_uc($bbs_reply_no)
	{
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->user_no = $this->user_no;
		$model_user->host = $this->host;
		$model_user->user_agent = $this->user_agent;
		
		$model_co = new Model_Co();
		$model_co->user_no = $this->user_no;
		$model_co->host = $this->host;
		$model_co->user_agent = $this->user_agent;
		
		
		
		// --------------------------------------------------
		//   BBS返信取得
		// --------------------------------------------------
		
		$db_bbs_reply_arr = $model_co->get_bbs_reply($bbs_reply_no);
		$target_user_no = $db_bbs_reply_arr['user_no'];
		$target_profile_no = $db_bbs_reply_arr['profile_no'];
		$good = $db_bbs_reply_arr['good'] + 1;
		
		
		if (isset($this->user_no) and $target_user_no == $this->user_no)
		{
			
			// --------------------------------------------------
			//   結果　自分で自分を評価することはできません。
			// --------------------------------------------------
			
			$result = 'error1';
			$level_id_user = null;
			$level_user = null;
			$level_id_profile = null;
			$level_profile = null;
			
		}
		else if ($db_bbs_reply_arr['anonymity'] or ($target_user_no == null and $target_profile_no == null))
		{
			
			// --------------------------------------------------
			//   匿名または一般ユーザーが投稿したコメント
			// --------------------------------------------------
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_reply');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_reply_no', '=', $bbs_reply_no);
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_reply_uc',
					'no' => $bbs_reply_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no == null)
		{
			
			// --------------------------------------------------
			//   ユーザーデータ取得
			// --------------------------------------------------
			
			$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
			$level_user = $db_user_data_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_reply');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_reply_no', '=', $bbs_reply_no);
				$arr = $query->execute();
				
				
				if (isset($db_user_data_arr))
				{
					
					// --------------------------------------------------
					//   ユーザーデータ更新
					// --------------------------------------------------
					
					$query = DB::update('users_data');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('user_no', '=', $target_user_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_reply_uc',
					'no' => $bbs_reply_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => null,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				$result = true;
				$level_id_user = 'level_user_' . $target_user_no;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		else if ($target_user_no and $target_profile_no)
		{
			
			// --------------------------------------------------
			//   プロフィール取得
			// --------------------------------------------------
			
			$db_profile_arr = $model_user->get_profile($target_profile_no);
			$level_profile = $db_profile_arr['level'] + 1;
			
			try
			{
				
				// --------------------------------------------------
				//   トランザクション開始
				// --------------------------------------------------
				
				DB::start_transaction();
				
				
				// --------------------------------------------------
				//   BBSコメント更新
				// --------------------------------------------------
			
				$query = DB::update('bbs_reply');
				
				$query->set(array(
					'good' => DB::expr('good + 1')
				));
				
				$query->where('bbs_reply_no', '=', $bbs_reply_no);
				$arr = $query->execute();
				
				
				$level_id_user = null;
				$level_user = null;
				
				if (isset($db_profile_arr))
				{
					
					// --------------------------------------------------
					//   User Data更新
					// --------------------------------------------------
					
					if ($db_profile_arr['open_profile'])
					{
						$db_user_data_arr = $model_user->get_user_data($target_user_no, null);
						$level_user = $db_user_data_arr['level'] + 1;
						
						
						$query = DB::update('users_data');
						
						$query->set(array(
							'level' => DB::expr('level + 1')
						));
						
						$query->where('user_no', '=', $target_user_no);
						$arr = $query->execute();
						
						
						$level_id_user = 'level_user_' . $target_user_no;
					}
					
					
					// --------------------------------------------------
					//   Profile更新
					// --------------------------------------------------
					
					$query = DB::update('profile');
					
					$query->set(array(
						'level' => DB::expr('level + 1')
					));
					
					$query->where('profile_no', '=', $target_profile_no);
					$arr = $query->execute();
					
				}
				
				
				// --------------------------------------------------
				//   Goodログ挿入
				// --------------------------------------------------
				
				$query = DB::insert('good_log');
				
				$query->set(array(
					'regi_date' => $datetime_now,
					'type' => 'bbs_reply_uc',
					'no' => $bbs_reply_no,
					'target_user_no' => $target_user_no,
					'target_profile_no' => $target_profile_no,
					'user_no' => $this->user_no,
					'host' => $this->host,
					'user_agent' => $this->user_agent
				));
				
				$arr = $query->execute();
				
				
				// --------------------------------------------------
				//   コミット
				// --------------------------------------------------
				
				DB::commit_transaction();
				
				
				$result = true;
				$level_id_profile = 'level_profile_' . $target_profile_no;
				$level_profile = $level_profile;
				
			}
			catch (Exception $e)
			{
				
				// --------------------------------------------------
				//   ロールバック
				// --------------------------------------------------
				
				DB::rollback_transaction();
				
				
				// --------------------------------------------------
				//   結果　データベースエラー
				// --------------------------------------------------
				
				$result = 'error_db';
				$level_id_user = null;
				$level_user = null;
				$level_id_profile = null;
				$level_profile = null;
				
			}
			
		}
		
		return array('result' => $result, 'good' => $good, 'level_id_user' => $level_id_user, 'level_user' => $level_user, 'level_id_profile' => $level_id_profile, 'level_profile' => $level_profile);
		
	}
	
	
	
	
	/**
	* Goodログ削除
	* @return array
	*/
	public function delete_good_log()
	{
		
		// --------------------------------------------------
		//   30日前の日時を取得
		// --------------------------------------------------
		
		$original_common_date = new Original\Common\Date();
		$pre_datetime = $original_common_date->sql_format('-30 day');
		
		
		// --------------------------------------------------
		//   古いデータをまとめて削除
		// --------------------------------------------------
		
		$query = DB::delete('good_log');
		$query->where('regi_date', '<', $pre_datetime);
		$result = $query->execute();
		
		
		return $result;
		
	}
	
	
}