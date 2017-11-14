<?php

class Controller_Login extends Controller_Base
{


	/**
	* 事前処理
	*/
	public function before()
	{

		parent::before();


		// --------------------------------------------------
		//   ログインしている場合はログアウトにリダイレクト
		//   リダイレクトへのアクセスはリダイレクトしない
		//   Twitterでツイートする場合はリダイレクトしない
		// --------------------------------------------------

		// Twitterメッセージ取得
		$twitter_message = Session::get('twitter_message');

		$segments_arr = Uri::segments();
		$segment1 = (isset($segments_arr[1])) ? $segments_arr[1] : null;
		if (isset($this->user_no) and $segment1 != 'redirect' and ! $twitter_message) Response::redirect('logout');


		// --------------------------------------------------
		//   言語データ読み込み
		// --------------------------------------------------

		Lang::load('login_logout');

	}


	/**
	* ログインページ表示
	*/
	public function action_index()
	{

		//echo "<br><br><br><br>";
		//var_dump(Input::get('tp'), Input::get('id'));

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

		// Session::set('redirect_type', Input::get('tp'));
		// Session::set('redirect_id', Input::get('id'));


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
		//   ◆◆◆　出力　◆◆◆
		// --------------------------------------------------

		$this->data_meta['lang'] = $this->language;
		$this->data_meta['title'] = __('login_title');
		$this->data_meta['keywords'] = __('login_keyword');
		$this->data_meta['description'] = __('login_description');

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
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'login.js');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'login.min.js');
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

		$original_js_arr = array('uri_base' => $this->uri_base, 'uri_current' => $this->uri_current, 'language' => $this->language, 'agent_type' => $this->agent_type, 'content_id' => $content_id);
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
	* ログインを試みる　ユーザーネームとパスワード
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
				$arr['alert_title'] = __('error_login');
				$arr['alert_message'] = __('login_try_login_error_message_2');
				throw new Exception('Error');
			}

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			$val = Validation::forge();
			$val->add_field('login_username', 'ID', 'required|min_length[3]|max_length[25]|valid_string[alpha,numeric,dashes]');
			$val->add_field('login_password', __('password'), 'required|min_length[6]|max_length[32]|valid_string[alpha,numeric,dashes]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_login_username = $val->validated('login_username');
				$validated_login_password = $val->validated('login_password');


				// --------------------------------------------------
				//   ログインする
				// --------------------------------------------------

				$auth = Auth::instance();

				if ($auth->login($validated_login_username, $validated_login_password, null, null, null))
				{

					// ログインが成功した場合の目印になるので消すな
					$arr['user_no'] = $auth->get_user_id();




					// --------------------------------------------------
					//   利用規約　同意した最新バージョンをセッションに保存
					// --------------------------------------------------

					Session::set('user_terms_approval_version', Config::get('user_terms_version'));


					// --------------------------------------------------
					//   最新の利用規約に同意しているかをチェック、してない場合は保存
					// --------------------------------------------------

					$model_common = new Model_Common();
					$model_common->user_no = $arr['user_no'];
					$model_common->check_and_update_user_terms();


					// --------------------------------------------------
					//   セッション取得　特定ページにリダイレクトする
					// --------------------------------------------------


					// Session::set('redirect_type', 'gc');
					// Session::set('redirect_id', 'assassins-creed-unity');

					/*
					$redirect_type = Session::get('redirect_type');
					$redirect_id = Session::get('redirect_id');

					if ($redirect_type and $redirect_id)
					{

						// アプリの場合はセッションを削除、アプリでない場合はリダイレクトページで削除される
						if ($this->app_mode)
						{
							Session::delete('redirect_type');
							Session::delete('redirect_id');
						}

						$arr['redirect_type'] = $redirect_type;
						$arr['redirect_id'] = $redirect_id;

					}

					// --------------------------------------------------
					//   プレイヤーページにリダイレクトする
					// --------------------------------------------------

					else
					{

						$model_user = new Model_User();
						$model_user->agent_type = $this->agent_type;
						$model_user->user_no = $this->user_no;
						$model_user->language = $this->language;
						$model_user->uri_base = $this->uri_base;
						$model_user->uri_current = $this->uri_current;

						$db_users_data_arr = $model_user->get_user_data($auth->get_user_id(), null);
						$arr['user_id'] = $db_users_data_arr['user_id'];
						$arr['redirect_type'] = null;
						$arr['redirect_id'] = null;

					}
					*/
				}
				else
				{

					// --------------------------------------------------
					//   入力したID、パスワードが正しくありません。
					// --------------------------------------------------

					$arr['alert_color'] = 'danger';
					$arr['alert_title'] = __('error_login');
					$arr['alert_message'] = __('login_try_login_error_message_1');
					throw new Exception('Error');

				}

			}
			else
			{

				// --------------------------------------------------
				//   アラート　エラー
				// --------------------------------------------------
				//$arr['alert_title'] = 'aaa';
				//throw new Exception('Error');

				//$arr['alert_color'] = 'warning';
				//$arr['alert_title'] = __('error_login');

				$error_message = '';
				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$error_message .= $value;
					}
				}

				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = __('error_login');
				$arr['alert_message'] = $error_message;

				// if (count($val->error()) > 0)
				// {
					// foreach ($val->error() as $key => $value) {
						// $arr['alert_message'] .= $value;
					// }
				// }


				//throw new Exception('Error');

			}
		}
		catch (Exception $e) {}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		echo json_encode($arr);

	}



	/**
	* ログイン後のリダイレクト
	*/
	public function action_redirect()
	{

		// --------------------------------------------------
		//   ログインしていない場合、エラーページへ飛ばす
		// --------------------------------------------------

		// if (empty($this->user_no))
		// {
			// throw new HttpNotFoundException;
		// }


		// --------------------------------------------------
		//   セッション取得、破棄
		// --------------------------------------------------

		$redirect_type = Session::get('redirect_type');
		$redirect_id = Session::get('redirect_id');

		Session::delete('redirect_type');
		Session::delete('redirect_id');


		// \Debug::dump($redirect_type);
		// \Debug::dump($redirect_id);
		// \Debug::dump($this->user_no);
		// exit();


		if (Auth::member(100))
		{
			Response::redirect('admin');
		}
		else if (Session::get('app'))
		{
			Session::delete('app');
		}
		else if ($redirect_type == 'cod_ghosts')
		{
			Response::redirect('cod/ghosts/user' . '/' . $this->user_no);
		}
		else if ($redirect_type == 'index')
		{
			Response::redirect(URI_BASE);
		}
		else if ($redirect_type == 'gc' and isset($redirect_id))
		{
			Response::redirect('gc' . '/' . $redirect_id);
		}
		else if ($redirect_type == 'uc' and isset($redirect_id))
		{
			Response::redirect('uc' . '/' . $redirect_id);
		}
		else if ($redirect_type == 'pl' and isset($redirect_id))
		{
			Response::redirect('pl' . '/' . $redirect_id);
		}
		else if ($this->user_no)
		{

			// --------------------------------------------------
			//   インスタンス作成
			// --------------------------------------------------

			$model_user = new Model_User();
			$model_user->agent_type = $this->agent_type;
			$model_user->user_no = $this->user_no;
			$model_user->language = $this->language;
			$model_user->uri_base = $this->uri_base;
			$model_user->uri_current = $this->uri_current;

			$user_data_arr = $model_user->get_user_data($this->user_no, null);
			//var_dump($this->user_no);

			Response::redirect('pl' . '/' . $user_data_arr['user_id']);

		}
		else
		{
			// エラーページへ飛ばす
			throw new HttpNotFoundException;
		}

	}




	/**
	* アカウントを作成する　ユーザーネームとパスワード
	*/
	public function action_registration()
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
				$arr['alert_title'] = __('error_account');
				$arr['alert_message'] = __('login_registration_error_message_3');
				throw new Exception('Error');
			}


			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			$val = Validation::forge();
			$val->add_field('registration_username', 'ID', 'required|min_length[3]|max_length[25]|valid_string[alpha,numeric,dashes]');
			$val->add_field('registration_password', __('password'), 'required|min_length[6]|max_length[32]|valid_string[alpha,numeric,dashes]');


			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_registration_username = $val->validated('registration_username');
				$validated_registration_password = $val->validated('registration_password');


				// --------------------------------------------------
				//   パスワードの強度判定
				// --------------------------------------------------

				$original_common_text = new Original\Common\Text();
				$password_strength = $original_common_text->check_password_strength($validated_registration_password);


				// ------------------------------
				//    パスワードの強度が足りません。
				// ------------------------------

				if ($password_strength < 2)
				{
					$arr['alert_color'] = 'warning';
					$arr['alert_title'] = __('error_password');
					$arr['alert_message'] = __('login_registration_error_message_1');
					throw new Exception('Error');
				}


				// ------------------------------
				//    IDとパスワードに同じ文字列を使用することはできません。
				// ------------------------------

				if ($validated_registration_username == $validated_registration_password)
				{
					$arr['alert_color'] = 'warning';
					$arr['alert_title'] = __('error_password');
					$arr['alert_message'] = __('login_registration_error_message_2');
					throw new Exception('Error');
				}


				// --------------------------------------------------
				//   アカウント作成
				// --------------------------------------------------

				if ($validated_registration_username and $validated_registration_password)
				{
					try
					{
						$auth = Auth::instance();
						$auth->create_user($validated_registration_username, $validated_registration_password, null, 1, array(), null, null, null, null, null, null);


						// --------------------------------------------------
						//   ログインする
						// --------------------------------------------------

						if ($auth->login($validated_registration_username, $validated_registration_password, null, null, null))
						{
							$arr['user_no'] = $auth->get_user_id();


							$model_user = new Model_User();
							$model_user->agent_type = $this->agent_type;
							$model_user->user_no = $this->user_no;
							$model_user->language = $this->language;
							$model_user->uri_base = $this->uri_base;
							$model_user->uri_current = $this->uri_current;

							$db_users_data_arr = $model_user->get_user_data($arr['user_no'], null);
							$arr['user_id'] = $db_users_data_arr['user_id'];

						}


						// --------------------------------------------------
						//   利用規約　同意した最新バージョンをセッションに保存
						// --------------------------------------------------

						Session::set('user_terms_approval_version', Config::get('user_terms_version'));


						// --------------------------------------------------
						//   最新の利用規約に同意しているかをチェック、してない場合は保存
						// --------------------------------------------------

						$model_common = new Model_Common();
						$model_common->user_no = $arr['user_no'];
						$model_common->check_and_update_user_terms();


						// --------------------------------------------------
						//  アカウントを作成した場合は、必ずプロフィールに飛ぶ
						// --------------------------------------------------

						Session::delete('redirect_type');
						Session::delete('redirect_id');


					}
					catch(Exception $e)
					{

						// --------------------------------------------------
						//   アカウント作成エラー
						// --------------------------------------------------

						$arr['alert_color'] = 'danger';
						$arr['alert_title'] = __('error_account');
						$arr['alert_message'] = $e->getMessage();// Simpleauthのエラーを取得する
						throw new Exception('Error');

					}
				}

			}
			else
			{

				// --------------------------------------------------
				//   バリデーションエラー
				// --------------------------------------------------

				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = __('error_account');
				$arr['alert_message'] = '';

				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$arr['alert_message'] .= $value;
					}
				}

			}
		}
		catch (Exception $e) {}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		echo json_encode($arr);

		//echo json_encode(array('user_no' => $user_no, 'error_encrypt' => $error_encrypt, 'error_alert_color' => $error_alert_color,  'error_title' => $error_title, 'error_message' => $error_message, 'csr_token' => $csr_token));

	}



	/**
	* opauthでログイン
	* @param string $_provider ログインする外部サイト
	*/
	public function action_auth($_provider, $type = null, $id = null)
    {

		// --------------------------------------------------
		//   Twitterでログインをキャンセルした場合
		// --------------------------------------------------

		if (Input::get('denied'))
		{
			Session::delete('auth_type');
        	Response::redirect('login');
		}


    	$this->_config = Config::load('opauth', 'opauth');

		$auth_type = null;

		if ($_provider == 'twitter')
		{
			$strategy_name = 'Twitter';
			$auth_type = $strategy_name;
			Session::set('auth_type', $auth_type);
		}
		else if ($_provider == 'google')
		{
			$strategy_name = 'Google';
			$auth_type = 'Google';
			Session::set('auth_type', $auth_type);
		}
		else if ($_provider == 'openid')
		{
			$strategy_name = 'OpenID';

			//$openid_url = Input::post('openid_url');

			if ($type  == 'yahoo_co_jp')
			{
				$auth_type = 'Yahoo! JAPAN';
				$_POST['openid_url'] = 'http://yahoo.co.jp/';
			}
			else if ($type  == 'yahoo_com')
			{
				$auth_type = 'Yahoo!';
				$_POST['openid_url'] = 'https://me.yahoo.com/';
			}
			/*
			else if ($type  == 'google')
			{
				$auth_type = 'Google';
				$_POST['openid_url'] = 'https://www.google.com/accounts/o8/id';
			}
			*/
			/*
			else if ($type  == 'nttid')
			{
				$auth_type = 'docomo / goo / OCN';
				$_POST['openid_url'] = 'http://www.nttid.jp/';
			}
			*/
			/*
			else if ($type  == 'mixi')
			{
				$auth_type = 'mixi';
				$_POST['openid_url'] = 'http://www.mixi.jp/';
			}
			*/
			/*
			else if ($type  == 'docomo')
			{
				$auth_type = 'docomo / goo / OCN';
				$_POST['openid_url'] = 'https://i.mydocomo.com/';
			}
			*/
			else if ($type  == 'biglobe')
			{
				$auth_type = 'BIGLOBE';
				$_POST['openid_url'] = 'http://biglobe.ne.jp/';
			}
			else if ($type  == 'livedoor')
			{
				$auth_type = 'livedoor';
				$_POST['openid_url'] = 'http://www.livedoor.com/';
			}
			else if ($type  == 'hatena')
			{
				$auth_type = 'はてな';
				$_POST['openid_url'] = 'http://www.hatena.ne.jp/' . $id . '/';
			}
			else if ($type  == 'aol')
			{
				$auth_type = 'AOL';
				$_POST['openid_url'] = 'https://openid.aol.com/';
			}

			if ($auth_type)
			{
				Session::set('auth_type', $auth_type);
			}
		}



		if (array_key_exists($strategy_name, Arr::get($this->_config, 'Strategy')))
        {
        	// Appの場合
        	if (Input::get('app'))
			{
				//echo 'app';
				//exit();
				Session::set('app', true);
			}

            $_oauth = new Opauth($this->_config, true);
        }
        else
        {
            return Response::forge('Strategy not supported');
        }

    }


	/**
	* opauthでログイン　コールバック
	*/
	public function action_callback()
    {

    	$this->_config = Config::load('opauth', 'opauth');

        $_opauth = new Opauth($this->_config, false);

        switch($_opauth->env['callback_transport'])
        {
            case 'session':
                session_start();
				//var_dump($_SESSION['opauth']);
				$response = $_SESSION['opauth'];
				unset($_SESSION['opauth']);
            break;
		}

		//exit();

		// GoogleのOAuthログインでキャンセルした場合、ログインページに戻る
		if (array_key_exists('error', $response) and Session::get('auth_type') == 'Google')
		{
			Session::delete('auth_type');
			Response::redirect('login');
		}
		else if (array_key_exists('error', $response))
		{
			//echo '<strong style="color: red;">Authentication error: </strong> Opauth returns error auth response.'."<br>\n";
			Session::set_flash('error_alert_color', 'danger');
			Session::set_flash('error_title', __('error_login'));
			Session::set_flash('error_message', 'Error Code : login_callback_1');
			Session::delete('auth_type');
			Response::redirect('login');
			//var_dump($response);
		}
        else
        {
            if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid']))
            {
                //echo '<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.'."<br>\n";
				Session::set_flash('error_alert_color', 'danger');
				Session::set_flash('error_title', __('error_login'));
				Session::set_flash('error_message', 'Error Code : login_callback_2');
				Session::delete('auth_type');
	        	Response::redirect('login');
            }
            elseif (!$_opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason))
            {
                //echo '<strong style="color: red;">Invalid auth response: </strong>'.$reason.".<br>\n";
				Session::set_flash('error_alert_color', 'danger');
				Session::set_flash('error_title', __('error_login'));
				Session::set_flash('error_message', 'Error Code : login_callback_3');
				Session::delete('auth_type');
	        	Response::redirect('login');
            }
            else
            {
                //echo '<strong style="color: green;">OK: </strong>Auth response is validated.'."<br>\n";
				//var_dump(Session::get());

				//var_dump($response);
				//exit();


				$auth_type = Session::get('auth_type');
				$uid = $response['auth']['uid'];
				$twitter_access_token = null;
				$twitter_access_token_secret = null;

				$auth = Auth::instance();


				if ($auth_type == 'Twitter')
				{

					// Twitterメッセージ取得
					$twitter_message = Session::get('twitter_message');

					// アクセストークン取得
					$twitter_access_token = $response['auth']['credentials']['token'];
					$twitter_access_token_secret = $response['auth']['credentials']['secret'];


					// --------------------------------------------------
					//   Twitterでツイートする　ログイン・アカウント作成はしない　ツイート後、指定ページに帰る
					// --------------------------------------------------

					if ($twitter_message)
					{

						// コンシューマーキー取得
						$consumer_key = Config::get('twitter_consumer_key');
						$consumer_secret = Config::get('twitter_consumer_secret');

						// ツイートする
						$original_common_twitter = new Original\Common\Twitter();
						$result_tweet = $original_common_twitter->post_message($consumer_key, $consumer_secret, $twitter_access_token, $twitter_access_token_secret, $twitter_message);

						// Twitterメッセージのセッション削除
						Session::delete('twitter_message');

						// ログインしてる場合はTwitter情報を登録
						if ($this->user_no)
						{
							try
							{
								$auth->update_user($this->user_no, array('twitter_id' => $uid, 'twitter_access_token' => $twitter_access_token, 'twitter_access_token_secret' => $twitter_access_token_secret));
							}
							catch (Exception $e) {}
						}

						// アラートセッション設定
						Session::set('alert_title', 'Twitter');
						Session::set('alert_message', 'ツイートしました。');
						Session::set('alert_color', 'success');
						//var_dump($twitter_message, $uid, $this->user_no, Session::get());

						//exit();
					}

					// --------------------------------------------------
					//   Twitterでログイン、アカウントがない場合は作成
					// --------------------------------------------------

					else if ($auth->login(null, null, $uid, null, null) === false)
					{
						$auth->create_user(null, null, null, 1, array(), $uid, $twitter_access_token, $twitter_access_token_secret, null, null, null);
						$auth->login(null, null, $uid, null, null);
					}

					$login_success_user_no = $auth->get_user_id();

					//var_dump(Auth::get_user_id());
					//exit();
				}
				else if ($auth_type == 'Google')
				{

					// --------------------------------------------------
					//   Google OAuthでログイン、アカウントがない場合は作成
					// --------------------------------------------------

					$open_id = $response['auth']['open_id'];

					// Open IDでログインできない場合
					if ($auth->login(null, null, null, $auth_type, $open_id) === false)
					{
						// OAuthのuidでログインする
						if ($auth->login(null, null, null, $auth_type, $uid) === false)
						{
							// いずれでもログインできなかった場合、アカウントを作成
							$auth->create_user(null, null, null, 1, array(), null, null, null, $auth_type, $uid, null);
							$auth->login(null, null, null, $auth_type, $uid);
						}
					}
					// Open IDでログインできた場合、OAuthのuidに変更
					else
					{
						$user_no = Auth::get_user_id();
						$auth->update_user($user_no, array('auth_no' => 1, 'auth_type' => $auth_type, 'auth_id' => $uid));
					}

					$login_success_user_no = $auth->get_user_id();
				}
				else
				{

					// --------------------------------------------------
					//   OpenIDでログイン、アカウントがない場合は作成
					// --------------------------------------------------

					if ($auth->login(null, null, null, $auth_type, $uid) === false)
					{
						$auth->create_user(null, null, null, 1, array(), null, null, null, $auth_type, $uid, null);
						$auth->login(null, null, null, $auth_type, $uid);
					}
					//echo "openid login<br>";
					//var_dump($auth->get_user_id());

					$login_success_user_no = $auth->get_user_id();
				}

				Session::delete('auth_type');
				//var_dump(Session::get());
				//var_dump($auth_type);
				//var_dump($uid);
				//var_dump($twitter_access_token);
				//var_dump($twitter_access_token_secret);



				// --------------------------------------------------
				//   利用規約　同意した最新バージョンをセッションに保存
				// --------------------------------------------------

				Session::set('user_terms_approval_version', Config::get('user_terms_version'));


				// --------------------------------------------------
				//   最新の利用規約に同意しているかをチェック、してない場合は保存
				// --------------------------------------------------

				if (isset($login_success_user_no))
				{
					$model_common = new Model_Common();
					$model_common->user_no = $login_success_user_no;
					$model_common->check_and_update_user_terms();
				}


				// --------------------------------------------------
				//   Appの場合はリダイレクト先にUser IDを追加する
				// --------------------------------------------------

				$redirect_plus = '';

				if (Session::get('app'))
				{

					// --------------------------------------------------
					//   セッション取得
					// --------------------------------------------------

					$redirect_type = Session::get('redirect_type');
					$redirect_id = Session::get('redirect_id');

					// ユーザーコミュニティへ
					if ($redirect_type == 'uc' and $redirect_id)
					{
						$redirect_plus = '/uc/' . $redirect_id;
					}
					// プレイヤーページへ
					else
					{

						$user_no = Auth::get_user_id();

						$model_user = new Model_User();
						$model_user->agent_type = $this->agent_type;
						$model_user->user_no = $this->user_no;
						$model_user->language = $this->language;
						$model_user->uri_base = $this->uri_base;
						$model_user->uri_current = $this->uri_current;

						$user_data_arr = $model_user->get_user_data($user_no, null);

						$redirect_plus = '/pl/' . $user_data_arr['user_id'];

					}

				}




				// --------------------------------------------------
				//   リダイレクト
				// --------------------------------------------------

				Response::redirect('login/redirect' . $redirect_plus);
				exit();

				/**
				 * It's all good. Go ahead with your application-specific authentication logic
				 */
			}
		}


		// --------------------------------------------------
		//   リダイレクト
		// --------------------------------------------------

		Response::redirect('login');


		//return Response::forge(var_dump($response));
	}

}
