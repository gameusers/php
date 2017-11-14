<?php

class Controller_Help extends Controller_Base
{


	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}


	/**
	* コミュニティ表示
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
		//   インスタンス作成
		// --------------------------------------------------

		$original_code_basic = new Original\Code\Basic();
		$original_code_basic->app_mode = $this->app_mode;
		$original_code_basic->agent_type = $this->agent_type;
		$original_code_basic->user_no = $this->user_no;
		$original_code_basic->language = $this->language;
		$original_code_basic->uri_base = $this->uri_base;
		$original_code_basic->uri_current = $this->uri_current;

		$original_code_advertisement = new Original\Code\Advertisement();
		$original_code_common2 = new Original\Code\Common2();
		$original_code_help = new Original\Code\Help();


		// --------------------------------------------------
		//   見出し
		// --------------------------------------------------

		$view_heading = View::forge('parts/common_heading_view');
		$view_heading->set('game_no', null);
		$view_heading->set('title', 'ヘルプ＆お問い合わせ');
		$view_heading->set('description', 'Game Users');
		$view_heading->set('thumbnail', null);


		// --------------------------------------------------
		//   コンテンツ
		// --------------------------------------------------

		$temp_arr = array(
			'first_load' => 1,
			'page' => 1,
			'list' => 'top',
			'content' => 'top_about'
		);

		$code_help = $original_code_help->code_help($temp_arr)['code_all'];


		// --------------------------------------------------
		//   ソーシャルボタン
		// --------------------------------------------------

		//$view_social = ($this->app_mode) ? null : View::forge('common/social_view');


		// --------------------------------------------------
		//   スライドゲームリスト
		// --------------------------------------------------

		// $result_arr = $original_code_common2->code_slide_game_list(true);
		// $code_slide_game_list = $result_arr['code'];


		// --------------------------------------------------
		//   Adsense
		// --------------------------------------------------

		$view_adsense = View::forge('common/adsense_view2');
		$view_adsense->set_safe('app_mode', $this->app_mode);
		$view_adsense->set_safe('ad_block', $this->ad_block);

		$view_adsense_rectangle = View::forge('common/adsense_rectangle_view');
		$view_adsense_rectangle->set_safe('app_mode', $this->app_mode);
		$view_adsense_rectangle->set_safe('ad_block', $this->ad_block);


		// --------------------------------------------------
		//   Amazonスライド広告
		// --------------------------------------------------

		//$code_ad_amazon_slide = $original_code_advertisement->code_ad_amazon_slide(array());





		//$test = true;

		if (isset($test))
		{
			Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

			// echo '$db_game_data_arr';
			// Debug::dump($db_game_data_arr);
			//
			// echo '$this->agent_type';
			// Debug::dump($this->agent_type);
			//
			// echo '$this->os';
			// Debug::dump($this->os);



		}
		//exit();



		// ------------------------------
		//    コンテンツ
		// ------------------------------

		$view_content = View::forge('help_view');

		// ----- コード -----

		$view_content->set_safe('code_heading', $view_heading);
		$view_content->set_safe('code_help', $code_help);

		$view_content->set_safe('code_social', null);
		$view_content->set_safe('code_slide_game_list', null);
		$view_content->set_safe('code_ad_amazon_slide', null);



		// --------------------------------------------------
		//   ◆◆◆　出力　◆◆◆
		// --------------------------------------------------

		// ------------------------------
		//    Meta
		// ------------------------------

		$meta_title = 'ヘルプ＆お問い合わせ - Game Users';
		$meta_keywords = 'Game Users,ヘルプ';
		$meta_description = 'のフレンド募集・メンバー募集を行うなら、Game Usersを利用してください。あなたの募集に対して返信が書き込まれると、アプリの通知やメールで連絡が来るのですぐにやりとりが始められます。';

		$meta_twitter_description = null;


		$this->data_meta['lang'] = $this->language;
		$this->data_meta['title'] = $meta_title;
		$this->data_meta['keywords'] = $meta_keywords;

		// description 改行削除＆文字数調節
		$description = str_replace(array("\r\n","\r","\n"), ' ', $meta_description);
		$this->data_meta['description'] = $description;

		$this->data_meta['og_title'] = $this->data_meta['title'];
		$this->data_meta['og_type'] = 'article';
		$this->data_meta['og_description'] = $this->data_meta['description'];
		$this->data_meta['og_url'] = $this->uri_current;
		$this->data_meta['og_image'] =  $this->uri_base . 'assets/img/social/ogp_image.jpg';
		$this->data_meta['og_site_name'] = 'Game Users';

		$this->data_meta['twitter_description'] = $meta_twitter_description;

		$this->data_meta['favicon_url'] = $this->uri_base . 'favicon.ico';


		// ------------------------------
		//    Meta　スタイルシート
		// ------------------------------

		$this->data_meta['css_arr'] = array(
			Config::get('css_bootstrap'),
			Config::get('css_ladda'),
			Config::get('css_typeahead'),
			Config::get('css_jquery_pnotify'),
			Config::get('css_jquery_magnific_popup'),
			Config::get('css_jquery_swiper'),
			Config::get('css_jquery_perfect_scrollbar')
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
			Config::get('js_jquery_pnotify'),
			Config::get('js_jquery_magnific_popup'),
			Config::get('js_jquery_imagesloaded'),
			Config::get('js_ladda_spin'),
			Config::get('js_ladda'),
			Config::get('js_typeahead'),
			Config::get('js_i18next'),
			Config::get('js_adwords_post_recruitment'),
			Config::get('js_adwords_conversion_async'),
			Config::get('js_jquery_swiper'),
			Config::get('js_jquery_perfect_scrollbar')
		);


		// ----- 追加　本番環境では軽量バージョンを読み込む -----

		if (Fuel::$env == 'development')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic'), 'style.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'help.js');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'help.min.js');
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
			'agent_type' => $this->agent_type
		);

		// アラートセッション取得
		$alert_title = Session::get('alert_title');
		$alert_message = Session::get('alert_message');
		$alert_color = Session::get('alert_color');

		if (isset($alert_title, $alert_message, $alert_color))
		{
			$original_js_arr['alert_title'] = $alert_title;
			$original_js_arr['alert_message'] = $alert_message;
			$original_js_arr['alert_color'] = $alert_color;

			Session::delete('alert_title');
			Session::delete('alert_message');
			Session::delete('alert_color');
		}

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

}
