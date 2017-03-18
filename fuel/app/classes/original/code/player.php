<?php

namespace Original\Code;

class Player
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------



	/**
	* 通知設定
	*
	* @param array $arr 条件
	* @return string HTMLコード
	*/
	public function config_basic($arr)
	{

		// --------------------------------------------------
		//   ログインする必要があります。
		// --------------------------------------------------

		if ( ! USER_NO)
		{
			$return_arr['alert_color'] = 'danger';
			$return_arr['alert_title'] = 'エラー';
			$return_arr['alert_message'] = 'ログインする必要があります。';
			return $return_arr;
		}


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$model_present = new \Model_Present();
		$model_user = new \Model_User();

		$db_users_data_arr = $model_user->get_user_data(USER_NO, null);
		$db_users_login_arr = $model_user->get_user_login(USER_NO);
		$db_present_winner_arr = $model_present->get_present_winner(array('user_no' => USER_NO));


		// --------------------------------------------------
		//   画像
		// --------------------------------------------------

		$top_image_arr = (isset($db_users_data_arr['top_image'])) ? unserialize($db_users_data_arr['top_image']) : null;


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('form/config_player_basic_view');

		$view->set('page_title', $db_users_data_arr['page_title']);
		$view->set('renewal_date', $db_users_data_arr['renewal_date']);
		$view->set('top_image_arr', $top_image_arr);
		$view->set('username', $db_users_login_arr['username']);
		$view->set('user_id', $db_users_data_arr['user_id']);
		$view->set('db_present_winner_arr', $db_present_winner_arr);
		$return_arr['code'] = $view->render();



		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;

			echo '<br><br><br><br>';

			echo '<br>$db_present_winner_arr';
			\Debug::dump($db_present_winner_arr);

			echo $return_arr['code'];

		}

		return $return_arr;

	}



	/**
	* 通知設定
	*
	* @param array $arr 条件
	* @return string HTMLコード
	*/
	public function config_notification($arr)
	{

		// --------------------------------------------------
		//   ログインする必要があります。
		// --------------------------------------------------

		if ( ! USER_NO)
		{
			$return_arr['alert_color'] = 'danger';
			$return_arr['alert_title'] = 'エラー';
			$return_arr['alert_message'] = 'ログインする必要があります。';
			return $return_arr;
		}


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$model_user = new \Model_User();
		$db_notification_data_arr = unserialize($model_user->get_user_data(USER_NO, null)['notification_data']);


		// ----------------------------------------
		//   Eメールアドレス取得
		// ----------------------------------------

		$db_users_login_arr = $model_user->get_user_login(USER_NO);

		// Eメール復号化
		if (isset($db_users_login_arr['email']))
		{
			$original_common_crypter = new \Original\Common\Crypter();
			$decrypted_email = $original_common_crypter->decrypt($db_users_login_arr['email']);
		}
		else
		{
			$decrypted_email = null;
		}


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('form/config_notification_view');
		$view->set('notification_data_arr', $db_notification_data_arr);
		$view->set('email', $decrypted_email);
		$return_arr['code'] = $view->render();



		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;

			echo '<br><br><br><br>';

			echo '<br>$db_notification_data_arr';
			\Debug::dump($db_notification_data_arr);

			echo $return_arr['code'];

		}

		return $return_arr;

	}




	/**
	* 広告設定
	*
	* @param array $arr 条件
	* @return string HTMLコード
	*/
	public function config_advertisement($arr)
	{

		// --------------------------------------------------
		//   ログインする必要があります。
		// --------------------------------------------------

		if ( ! USER_NO)
		{
			$return_arr['alert_color'] = 'danger';
			$return_arr['alert_title'] = 'エラー';
			$return_arr['alert_message'] = 'ログインする必要があります。';
			return $return_arr;
		}


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_user = new \Model_User();


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$db_users_data_arr = $model_user->get_user_data(USER_NO, null);


		// --------------------------------------------------
		//   AmazonトラッキングID
		// --------------------------------------------------

		if (isset($db_users_data_arr['user_advertisement']))
		{
			$user_advertisement_arr = unserialize($db_users_data_arr['user_advertisement']);
			$amazon_tracking_id = (isset($user_advertisement_arr['amazon_tracking_id'])) ? $user_advertisement_arr['amazon_tracking_id'] : null;
		}
		else
		{
			$amazon_tracking_id = null;
		}


		// --------------------------------------------------
		//   広告コード登録フォーム取得
		// --------------------------------------------------

		$temp_arr = array(
			'page' => 1
		);

		$code_form_advertisement_list = $this->form_advertisement_list($temp_arr);


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('form/form_advertisement_view');
		$view->set('amazon_tracking_id', $amazon_tracking_id);
		$view->set_safe('code_form_advertisement_list', $code_form_advertisement_list);

		$return_arr['code'] = $view->render() . "\n";



		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;

			echo '<br><br><br><br>';

			// echo '<br>$db_advertisement_arr';
			// \Debug::dump($db_advertisement_arr);

			echo $return_arr['code'];

		}


		return $return_arr;

	}





	/**
	* 広告編集フォーム取得
	*
	* @param array $arr 検索条件
	* @return string HTMLコード
	*/
	public function form_advertisement_list($arr)
	{

		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$model_advertisement = new \Model_Advertisement();


		// --------------------------------------------------
		//   リミット
		// --------------------------------------------------

		$limit = (AGENT_TYPE != 'smartphone') ? \Config::get('limit_edit_advertisement') : \Config::get('limit_edit_advertisement_sp');


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$tmp_arr = array(
			'user_no' => USER_NO,
			'page' => $arr['page'],
			'limit' => $limit,
			'get_total' => true
		);

		if (\Auth::member(100))
		{
			// 承認されていない広告を表示する
			//$temp_arr['approval_null'] = 1;
			unset($tmp_arr['user_no']);

			$result_arr = $model_advertisement->get_advertisement_admin($tmp_arr);
		}
		else
		{
			$result_arr = $model_advertisement->get_advertisement($tmp_arr);
		}

		$db_advertisement_arr = $result_arr['data_arr'];
		$total = $result_arr['total'];


		// --------------------------------------------------
		//   新規作成用フォームのために、空の配列を追加する　配列の要素がリミットの値（10個）になる
		// --------------------------------------------------

		$cnt = count($db_advertisement_arr);
		$tmp_arr = array_fill(0, $limit - $cnt, array());
		$db_advertisement_arr = array_merge($db_advertisement_arr, $tmp_arr);



		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$view = \View::forge('form/form_advertisement_list_view');
		$view->set('db_advertisement_arr', $db_advertisement_arr);
		$view->set('limit', $limit);

		// ページャー
		$view->set('pagination_page', $arr['page']);
		$view->set('pagination_total', $total);
		$view->set('pagination_limit', $limit);
		$view->set('pagination_times', PAGINATION_TIMES);
		$view->set('pagination_function_name', 'GAMEUSERS.player.readFormAdvertisement');
		$view->set('pagination_argument_arr', array());


		$code = $view->render() . "\n";




		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;

			echo '<br><br><br><br>';

			echo '<br>$limit';
			\Debug::dump($limit);

			echo '<br>$cnt';
			\Debug::dump($cnt);

			echo '<br>$total';
			\Debug::dump($total);



			echo '<br>$db_advertisement_arr';
			\Debug::dump($db_advertisement_arr);

			echo $code;

		}

		//exit();





		return $code;

	}



}
