<?php

class Controller_Rest_App extends Controller_Rest_Base
{
	
	/**
	* 事前処理
	*/
	public function before()
	{
		//$_POST['app_mode'] = true;
		
		parent::before();
		
		//$this->uri_base = 'https://192.168.10.2/gameusers/public/';
		//$this->uri_current = $this->uri_base . Uri::string();
		
		$this->app_mode = true;
		//if ( ! defined(APP_MODE)) define("APP_MODE", true);
		
		// --------------------------------------------------
		//   未読予約保存IDがある場合、保存する
		// --------------------------------------------------
		
		$model_notifications = new Model_Notifications();
		$model_notifications->agent_type = $this->agent_type;
		$model_notifications->user_no = $this->user_no;
		$model_notifications->language = $this->language;
		$model_notifications->uri_base = $this->uri_base;
		$model_notifications->uri_current = $this->uri_current;
		
		$model_notifications->save_notifications_id();
		
	}
	
	
	
	/**
	* テスト
	*
	* @return string HTMLコード
	*/
	/*
	public function post_test()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		
		$arr = array();
		
		$auth = Auth::instance();
		$arr['test'] = $auth->get_user_id();
		
		
		if (isset($test))
		{
			var_dump($arr);
			//echo $view->render();
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	*/
	
	
	/**
	* ヘッダー
	*
	* @return string HTMLコード
	*/
	public function post_header()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		
		$arr = array();
		
		
		// ------------------------------
		//    ヘッダー
		// ------------------------------
		
		$view = View::forge('app/app_header_view');
		$view->set_safe('app_mode', $this->app_mode);
		$view->set('user_no', $this->user_no);
		$view->set('user_id', $this->user_id);
		$view->set('uri_base', $this->uri_base);
		$arr['code'] = $view->render();
		
		
		if (isset($test))
		{
			var_dump($arr);
			//echo $view->render();
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	/**
	* フッター
	*
	* @return string HTMLコード
	*/
	public function post_footer()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		
		$arr = array();
		
		
		// ------------------------------
		//    フッター
		// ------------------------------
		
		$view = View::forge('app/app_footer_view');
		$view->set_safe('app_mode', $this->app_mode);
		$view->set('user_no', $this->user_no);
		$arr['code'] = $view->render();
		
		
		// ------------------------------
		//    広告の非表示
		// ------------------------------
		
		//$arr['ad_block'] = $this->ad_block;
		
		
		if (isset($test))
		{
			var_dump($arr);
			//echo $view->render();
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	
	/**
	* コンテンツ index.php
	*
	* @return string HTMLコード
	*/
	public function post_contents_index()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			//$_POST['game_id'] = 'cod_aw';
		}
		
		
		if (USER_NO)
		{
			$result_arr = $this->contents_notification();
		}
		else
		{
			$result_arr = $this->contents_login();
		}
		
		$arr['code'] = $result_arr['code'];
		$arr['content_id'] = $result_arr['content_id'];
		
		
		
		if (isset($test))
		{
			echo $arr['code'];
			//\Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}
		
	}
	
	
	
	
	
	
	
	
	/**
	* コンテンツ login.php
	*
	* @return string HTMLコード
	*/
	public function post_contents_login()
	{
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			
		}
		
		
		$result_arr = $this->contents_login();
		
		$arr['code'] = $result_arr['code'];
		$arr['content_id'] = $result_arr['content_id'];
		
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
	* コンテンツ login.php
	*
	* @return string HTMLコード
	*/
	function contents_login()
	{
		
		// --------------------------------------------------
		//    ◆ App.php オリジナル
		// --------------------------------------------------
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			
		}
		
		
		// --------------------------------------------------
		//    配列作成
		// --------------------------------------------------
		
		$arr = array();
		
		
		// --------------------------------------------------
		//   言語データ読み込み
		// --------------------------------------------------
		
		Lang::load('login_logout');
		
		
		
		
		
		// --------------------------------------------------
		//   ◆ 以下Appにコピー
		// --------------------------------------------------
		
		// --------------------------------------------------
		//   共通処理　インスタンス作成
		// --------------------------------------------------
		
		$original_code_basic = new Original\Code\Basic();
		$original_code_basic->app_mode = $this->app_mode;
		$original_code_basic->agent_type = $this->agent_type;
		$original_code_basic->user_no = $this->user_no;
		$original_code_basic->language = $this->language;
		$original_code_basic->uri_base = $this->uri_base;
		$original_code_basic->uri_current = $this->uri_current;
		
		$original_code_common = new Original\Code\Common();
		$original_code_common->app_mode = $this->app_mode;
		$original_code_common->agent_type = $this->agent_type;
		$original_code_common->user_no = $this->user_no;
		$original_code_common->language = $this->language;
		$original_code_common->uri_base = $this->uri_base;
		$original_code_common->uri_current = $this->uri_current;
		
		$original_common_text = new Original\Common\Text();
		
		
		// --------------------------------------------------
		//   セッション設定
		// --------------------------------------------------
		
		Session::set('redirect_type', Input::get('tp'));
		Session::set('redirect_id', Input::get('id'));
		
		
		// --------------------------------------------------
		//   アラート
		// --------------------------------------------------
		
		$alert_color = Session::get_flash('error_alert_color');
		$alert_title = Session::get_flash('error_title');
		$alert_message = Session::get_flash('error_message');
		$alert_add_class = null;
		
		$code_alert = (isset($alert_color, $alert_title, $alert_message)) ? $original_code_basic->alert($alert_color, $alert_title, $alert_message, $alert_add_class) : null;
		
		
		
		// --------------------------------------------------
		//   利用規約
		// --------------------------------------------------
		
		$code_user_terms_1 = $original_code_common->user_terms();
		$code_user_terms_2 = $original_code_common->user_terms();
		$code_user_terms_3 = $original_code_common->user_terms();
		
		
		// ------------------------------
		//    コンテンツ
		// ------------------------------
		
		// ページ特有のID
		$content_id = $original_common_text->random_text_lowercase(10);
		
		$view_content = View::forge('login_view');
		$view_content->set_safe('app_mode', $this->app_mode);
		$view_content->set('content_id', $content_id);
		$view_content->set('language', $this->language);
		$view_content->set('uri_base', $this->uri_base);
		
		$view_content->set_safe('code_alert', $code_alert);
		$view_content->set_safe('code_user_terms_1', $code_user_terms_1);
		$view_content->set_safe('code_user_terms_2', $code_user_terms_2);
		$view_content->set_safe('code_user_terms_3', $code_user_terms_3);
		
		
		// --------------------------------------------------
		//   ◆ 以下Appにコピー　終わり
		// --------------------------------------------------
		
		
		
		// --------------------------------------------------
		//    ◆ App.php オリジナル
		// --------------------------------------------------
		
		//return $view_content->render();
		$arr['code'] = $view_content->render();
		$arr['content_id'] = $content_id;
		
		return $arr;
// 		
		// if (isset($test))
		// {
			// var_dump($arr);
		// }
		// else
		// {
			// return $this->response($arr);
		// }
		
	}
	
	
	
	/**
	* コンテンツ logout.php
	*
	* @return string HTMLコード
	*/
	public function post_contents_logout()
	{
		
		// --------------------------------------------------
		//    ◆ App.php オリジナル
		// --------------------------------------------------
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			
		}
		
		
		// --------------------------------------------------
		//    配列作成
		// --------------------------------------------------
		
		$arr = array();
		
		
		// --------------------------------------------------
		//   言語データ読み込み
		// --------------------------------------------------
		
		Lang::load('login_logout');
		
		
		
		
		
		// --------------------------------------------------
		//   ◆ 以下Appにコピー
		// --------------------------------------------------
		
		// --------------------------------------------------
		//   共通処理　インスタンス作成
		// --------------------------------------------------
		
		$original_code_basic = new Original\Code\Basic();
		$original_code_basic->app_mode = $this->app_mode;
		$original_code_basic->agent_type = $this->agent_type;
		$original_code_basic->user_no = $this->user_no;
		$original_code_basic->language = $this->language;
		$original_code_basic->uri_base = $this->uri_base;
		$original_code_basic->uri_current = $this->uri_current;
		
		$original_common_text = new Original\Common\Text();
		
		
		
		// ------------------------------
		//    コンテンツ
		// ------------------------------
		
		// ページ特有のID
		$content_id = $original_common_text->random_text_lowercase(10);
		
		$view_content = View::forge('logout_view');
		$view_content->set_safe('app_mode', $this->app_mode);
		$view_content->set('content_id', $content_id);
		
		
		// --------------------------------------------------
		//   ◆ 以下Appにコピー　終わり
		// --------------------------------------------------
		
		
		
		// --------------------------------------------------
		//    ◆ App.php オリジナル
		// --------------------------------------------------
		
		$arr['code'] = $view_content->render();
		$arr['content_id'] = $content_id;
		
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
	* コンテンツ　通知
	*
	* @return string HTMLコード
	*/
	function contents_notification()
	{
		
		// --------------------------------------------------
		//    ◆ App.php オリジナル
		// --------------------------------------------------
		
		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------
		
		//$test = true;
		
		if (isset($test))
		{
			
		}
		
		
		// --------------------------------------------------
		//    配列作成
		// --------------------------------------------------
		
		$arr = array();
		
		
		
		// --------------------------------------------------
		//   ◆ 以下Appにコピー
		// --------------------------------------------------
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		$model_user = new Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;
		
		$original_code_basic = new Original\Code\Basic();
		$original_code_basic->app_mode = $this->app_mode;
		$original_code_basic->agent_type = $this->agent_type;
		$original_code_basic->user_no = $this->user_no;
		$original_code_basic->language = $this->language;
		$original_code_basic->uri_base = $this->uri_base;
		$original_code_basic->uri_current = $this->uri_current;
		
		$original_code_common = new Original\Code\Common();
		$original_code_common->app_mode = $this->app_mode;
		$original_code_common->agent_type = $this->agent_type;
		$original_code_common->user_no = $this->user_no;
		$original_code_common->language = $this->language;
		$original_code_common->uri_base = $this->uri_base;
		$original_code_common->uri_current = $this->uri_current;
		
		$original_common_text = new Original\Common\Text();
		
		
		
		
		// --------------------------------------------------
		//  データベースから取得
		// --------------------------------------------------
		
		$db_users_data_arr = $model_user->get_user_data($this->user_no, null);
		
		
		// --------------------------------------------------
		//  コード作成
		// --------------------------------------------------
		
		$result_arr = $original_code_common->notifications(true, true, $db_users_data_arr, 1);
		
		$code_notifications = $result_arr['code'];
		$arr['unread_id'] = $result_arr['unread_id'];
		
		
		
		//$test = true;
		
		if (isset($test))
		{
			//Debug::$js_toggle_open = true;
			
			echo "<br><br><br><br>";
			echo '$code_notifications';
			\Debug::dump($code_notifications);
			
			echo 'unread_id';
			\Debug::dump($arr['unread_id']);
			
		}
		//exit();
		
		
		
		
		// ------------------------------
		//    コンテンツ
		// ------------------------------
		
		// ページ特有のID
		$content_id = $original_common_text->random_text_lowercase(10);
		
		$view_content = View::forge('app/app_notification_view');
		$view_content->set_safe('login_user_no', $this->user_no);
		
		
		// ----- コード -----
		
		$view_content->set_safe('code_notifications', $code_notifications);
		
		
		
		// --------------------------------------------------
		//    ◆ App.php オリジナル
		// --------------------------------------------------
		
		//return $view_content->render();
		$arr['code'] = $view_content->render();
		$arr['content_id'] = $content_id;
		
		return $arr;
// 		
		// if (isset($test))
		// {
			// var_dump($arr);
		// }
		// else
		// {
			// return $this->response($arr);
		// }
		
	}
	
	
}