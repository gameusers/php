<?php

class Controller_Logout extends Controller_Base
{


	/**
	* 事前処理
	*/
	public function before()
	{

		parent::before();


		// --------------------------------------------------
		//   ログインしていない場合はログインにリダイレクト
		// --------------------------------------------------

		if (empty($this->user_no)) Response::redirect('login');


		// --------------------------------------------------
		//   言語データ読み込み
		// --------------------------------------------------

		Lang::load('login_logout');

	}



	/**
	* ログアウトページ表示
	*/
	public function action_index()
	{

		// --------------------------------------------------
		//   メンテナンス表示
		// --------------------------------------------------

		if (Config::get('maintenance') == 2)
		{
			if ( ! Auth::member(100)) return Response::forge(View::forge('maintenance_view'), 503);
		}



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
		//   ◆◆◆　出力　◆◆◆
		// --------------------------------------------------

		$this->data_meta['lang'] = $this->language;
		$this->data_meta['title'] = __('logout_title');
		$this->data_meta['keywords'] = __('logout_keyword');
		$this->data_meta['description'] = __('logout_description');

		$this->data_meta['og_title'] = $this->data_meta['title'];
		$this->data_meta['og_type'] = 'article';
		$this->data_meta['og_description'] = $this->data_meta['description'];
		$this->data_meta['og_url'] = $this->uri_current;
		$this->data_meta['og_image'] = $this->uri_base . 'assets/img/social/ogp_image.jpg';
		$this->data_meta['og_site_name'] = __('site_name');

		$this->data_meta['favicon_url'] = $this->uri_base . 'favicon.ico';


		// ------------------------------
		//    Meta　スタイルシート
		// ------------------------------

		$this->data_meta['css_arr'] = array(
			Config::get('css_bootstrap'),
			Config::get('css_ladda'),
			Config::get('css_typeahead'),
			'login.css'
		);


		// ------------------------------
		//    Meta　Javascript
		// ------------------------------

		$this->data_meta['js_arr'] = array(
			Config::get('js_jquery'),
			Config::get('js_jquery_cookie'),
			Config::get('js_bootstrap'),
			Config::get('js_jquery_easing'),
			Config::get('js_jquery_autosize'),
			// Config::get('js_jquery_fastclick'),
			Config::get('js_jquery_magnific_popup'),
			Config::get('js_ladda_spin'),
			Config::get('js_ladda'),
			Config::get('js_typeahead'),
			Config::get('js_i18next')
		);


		// ----- 追加　本番環境では軽量バージョンを読み込む -----

		if (Fuel::$env == 'development')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic'), 'style.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'logout.js');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'logout.min.js');
		}


		// ------------------------------
		//    ヘッダー
		// ------------------------------

		$view_header = View::forge('header_view');
		$view_header->set_safe('app_mode', $this->app_mode);
		$view_header->set('user_no', $this->user_no);
		$view_header->set('user_id', $this->user_id);
		$view_header->set('uri_base', $this->uri_base);


		// ------------------------------
		//    フッター
		// ------------------------------

		$view_footer = View::forge('footer_view');
		$view_footer->set_safe('app_mode', $this->app_mode);
		$view_footer->set('user_no', $this->user_no);


		// ------------------------------
		//    Javascript変数コード
		// ------------------------------

		$original_js_arr = array(
			'uri_base' => $this->uri_base,
			'uri_current' => $this->uri_current,
			'language' => $this->language,
			'agent_type' => $this->agent_type,
			'content_id' => $content_id
		);

		$code_original_js = $original_code_basic->javascript($original_js_arr);


		// ------------------------------
		//    コード出力
		// ------------------------------

		$view = View::forge('base_view', $this->data_meta);
		$view->set_safe('app_mode', $this->app_mode);
		$view->set_safe('original_js', $code_original_js);
		$view->set('header', $view_header);
		$view->set('content', $view_content);
		$view->set('footer', $view_footer);

		return Response::forge($view);

	}


	/**
	* ログアウト
	*/
	public function action_try()
	{

		$arr = array();

		try
		{

			// --------------------------------------------------
			//   フォームが正しい経緯で送信されていません。
			// --------------------------------------------------

			$cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
			$post_csrf_token = Input::post('fuel_csrf_token');

			if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			{
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = __('error_logout');
				$arr['alert_message'] = __('logout_try_error_message_1');
				throw new Exception('Error');
			}


			// --------------------------------------------------
			//   ログアウト
			// --------------------------------------------------

			$auth = Auth::instance();
			$auth->logout();

		}
		catch (Exception $e) {}

		/*
		if (Security::check_token())
		{
			$auth = Auth::instance();
			$auth->logout();

			$alert_color = null;
			$alert_title = null;
			$alert_message = null;
			$alert_add_class = null;
		}
		else
		{
			// アラート　フォームが正しい経緯で送信されていません。
			$alert_color = 'warning';
			$alert_title = __('error_logout');
			$alert_message = __('logout_try_error_message_1');
			$alert_add_class = null;
		}
		*/
		// CSRF対策
		//$csr_token = Security::fetch_token();

		echo json_encode($arr);

	}

}
