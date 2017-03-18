<?php

class Controller_Rest_Present extends Controller_Rest_Base
{
	
	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}
	
	
	
	/**
	* 抽選エントリーユーザー読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_present_entry_users()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['previous'] = 4;
		}
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------
			
			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------
			
			$val = Validation::forge();
			
			$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add_field('previous', 'Previous', 'valid_string[numeric]|numeric_min[2]|numeric_max[10]');
			
			
			if ($val->run())
			{
				
				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------
				
				$validated_page = $val->validated('page');
				$validated_previous = ($val->validated('previous')) ? $val->validated('previous') : null;
				
				
				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------
				
				// 日時
				$original_common_date = new Original\Common\Date();
				$datetime_now = $original_common_date->sql_format();
				
				// インスタンス作成
				$original_code_present = new Original\Code\Present();
				$original_code_present->app_mode = $this->app_mode;
				$original_code_present->agent_type = $this->agent_type;
				$original_code_present->user_no = $this->user_no;
				$original_code_present->language = $this->language;
				$original_code_present->uri_base = $this->uri_base;
				$original_code_present->uri_current = $this->uri_current;
				
				
				
				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------
				
				$code = $original_code_present->read_present_users(array('page' => $validated_page, 'previous' => $validated_previous, 'winner' => null));
				
				
				$arr['code'] = $code;
				
				
				if (isset($test))
				{
					echo '<br>$validated_page';
					var_dump($validated_page);
				}
				
			}
			else
			{
				
				// --------------------------------------------------
				//   アラート　エラー
				// --------------------------------------------------
				/*
				$error_message = '';
				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$error_message .= $value;
					}
				}
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '保存できませんでした。' . $error_message;
				
				if (isset($test)) echo $error_message;
				*/
			}
			
		}
		catch (Exception $e) {
			if (isset($test)) echo $e->getMessage();
		}
		
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	
	/**
	* 抽選エントリーユーザー読み込み
	*
	* @return string HTMLコード
	*/
	public function post_show_present_user_edit_form()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			// $_POST['regi_date'] = '2015-07-05 00:00:00';
			// $_POST['type'] = 'lottery';
			
			$_POST['regi_date'] = '2015-06-28 00:00:00';
			$_POST['type'] = 'edit';
			$_POST['user_no'] = 8;
			$_POST['profile_no'] = null;
		}
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   運営者のみ
			// --------------------------------------------------
			
			if ( ! Auth::member(100))
			{
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '運営者のみ';
				throw new Exception('Error');
			}
			
			
			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------
			
			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------
			
			$val = Validation::forge();
			
			$val->add_callable('Original_Rule_User');
			
			$val->add_field('regi_date', 'regi_date', 'required');
			$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(lottery|edit)$/');
			if (Input::post('user_no')) $val->add_field('user_no', 'User No', 'required|check_user_data');
			if (Input::post('profile_no')) $val->add_field('profile_no', 'Profile No', 'required|check_profile');
			
			
			if ($val->run())
			{
				
				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------
				
				$validated_regi_date = $val->validated('regi_date');
				$validated_type = $val->validated('type');
				$validated_user_no = $val->validated('user_no');
				$validated_profile_no = ($val->validated('profile_no')) ? $val->validated('profile_no') : null;
				
				
				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------
				
				// インスタンス作成
				$original_code_present = new Original\Code\Present();
				$original_code_present->app_mode = $this->app_mode;
				$original_code_present->agent_type = $this->agent_type;
				$original_code_present->user_no = $this->user_no;
				$original_code_present->language = $this->language;
				$original_code_present->uri_base = $this->uri_base;
				$original_code_present->uri_current = $this->uri_current;
				
				
				
				// --------------------------------------------------
				//    コード作成
				// --------------------------------------------------
				
				if ($validated_type == 'lottery')
				{
					$code = $original_code_present->present_users_edit_form(array('page' => 1, 'regi_date' => $validated_regi_date, 'type' => 'lottery'));
				}
				else
				{
					$code = $original_code_present->present_users_edit_form(array('page' => 1, 'regi_date' => $validated_regi_date, 'type' => 'edit', 'user_no' => $validated_user_no, 'profile_no' => $validated_profile_no));
				}
				
				$arr['code'] = $code;
				
				
				
				if (isset($test))
				{
					if (isset($validated_regi_date))
					{
						echo '<br>$validated_regi_date';
						var_dump($validated_regi_date);
					}
					
					if (isset($validated_type))
					{
						echo '<br>$validated_type';
						var_dump($validated_type);
					}
					
					if (isset($validated_user_no))
					{
						echo '<br>$validated_user_no';
						var_dump($validated_user_no);
					}
					
					if (isset($validated_profile_no))
					{
						echo '<br>$validated_profile_no';
						var_dump($validated_profile_no);
					}
				}
				
			}
			else
			{
				
				// --------------------------------------------------
				//   アラート　エラー
				// --------------------------------------------------
				/*
				$error_message = '';
				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$error_message .= $value;
					}
				}
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '保存できませんでした。' . $error_message;
				
				if (isset($test)) echo $error_message;
				*/
			}
			
		}
		catch (Exception $e) {
			if (isset($test)) echo $e->getMessage();
		}
		
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	
	/**
	* 当選者の情報保存
	*
	* @return string HTMLコード
	*/
	public function post_save_present_user()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			
			//$_POST['regi_date'] = '2015-07-05 00:00:00';
			//$_POST['user_no'] = 1;
			$_POST['present_no'] = 6;
			$_POST['type'] = 'Amazonギフト券';
			$_POST['sum'] = '500';
			$_POST['unit'] = '円';
			$_POST['code'] = 'aaaaaaaaaaaaaaa';
			
		}
		
		
		$arr = array();
		
		try
		{
			
			// --------------------------------------------------
			//   運営者のみ
			// --------------------------------------------------
			
			if ( ! Auth::member(100))
			{
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '運営者のみ';
				throw new Exception('Error');
			}
			
			
			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------
			
			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------
			
			$val = Validation::forge();
			
			$val->add_callable('Original_Rule_User');
			/*
			$val->add_field('regi_date', 'regi_date', 'required');
			$val->add_field('user_no', 'User No', 'required|check_user_data');
			if (Input::post('profile_no')) $val->add_field('profile_no', 'Profile No', 'required|check_profile');
			*/
			$val->add_field('present_no', 'present_no', 'required|match_pattern["^[1-9]\d*$"]');
			$val->add_field('type', 'type', 'min_length[1]|max_length[50]');
			$val->add_field('sum', 'sum', 'match_pattern["^[1-9]\d*$"]');
			$val->add_field('unit', 'unit', 'min_length[1]|max_length[50]');
			$val->add_field('code', 'code', 'min_length[1]|max_length[255]');
			
			
			
			if ($val->run())
			{
				
				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------
				
				// $validated_regi_date = $val->validated('regi_date');
				// $validated_user_no = $val->validated('user_no');
				// $validated_profile_no = ($val->validated('profile_no')) ? $val->validated('profile_no') : null;
				$validated_present_no = $val->validated('present_no');
				$validated_type = ($val->validated('type')) ? $val->validated('type') : null;
				$validated_sum = ($val->validated('sum')) ? $val->validated('sum') : null;
				$validated_unit = ($val->validated('unit')) ? $val->validated('unit') : null;
				$validated_code = ($val->validated('code')) ? $val->validated('code') : null;
				
				
				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------
				
				// インスタンス作成
				$model_present = new Model_Present();
				$model_present->agent_type = $this->agent_type;
				$model_present->user_no = $this->user_no;
				$model_present->language = $this->language;
				$model_present->uri_base = $this->uri_base;
				$model_present->uri_current = $this->uri_current;
				
				$original_common_crypter = new Original\Common\Crypter();
				
				
				// --------------------------------------------------
				//   コードの暗号化
				// --------------------------------------------------
				
				$encrypted_code = ($validated_code) ? $original_common_crypter->encrypt($validated_code) : null;
				
				
				// --------------------------------------------------
				//    保存用配列作成
				// --------------------------------------------------
				
				$save_arr['type'] = $validated_type;
				$save_arr['sum'] = $validated_sum;
				$save_arr['unit'] = $validated_unit;
				$save_arr['code'] = $encrypted_code;
				
				
				// --------------------------------------------------
				//    データベース更新
				// --------------------------------------------------
				
				$result_arr = $model_present->update_present_users($validated_present_no, $save_arr);
				
				
				
				
				if (isset($test))
				{
					
					echo "バリデーション";
					
					echo '$validated_present_no';
					var_dump($validated_present_no);
					
					echo '$validated_type';
					var_dump($validated_type);
					
					echo '$validated_sum';
					var_dump($validated_sum);
					
					echo '$validated_unit';
					var_dump($validated_unit);
					
					echo '$validated_code';
					var_dump($validated_code);
					
					
					echo '$save_arr';
					var_dump($save_arr);
				}
				
				//exit();
				
				
			}
			else
			{
				
				// --------------------------------------------------
				//   アラート　エラー
				// --------------------------------------------------
				
				$error_message = '';
				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$error_message .= $value;
					}
				}
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '保存できませんでした。' . $error_message;
				
				if (isset($test)) echo $error_message;
				
			}
			
		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
			//echo $e->getMessage();
			if (isset($test)) echo $e->getMessage();
		}
		
		
		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
}