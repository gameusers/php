<?php

class Controller_Present extends Controller_Base
{


	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();

		// 言語データ読み込み
		//Lang::load('cod_ghosts');
	}


	/**
	* コミュニティ表示
	*/
	public function action_index()
	{

		//$test = true;


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
		//   共通処理
		// --------------------------------------------------

		$original_code_present = new Original\Code\Present();
		$original_code_present->app_mode = $this->app_mode;
		$original_code_present->agent_type = $this->agent_type;
		$original_code_present->user_no = $this->user_no;
		$original_code_present->language = $this->language;
		$original_code_present->uri_base = $this->uri_base;
		$original_code_present->uri_current = $this->uri_current;

		$original_code_basic = new Original\Code\Basic();
		$original_code_basic->app_mode = $this->app_mode;
		$original_code_basic->agent_type = $this->agent_type;
		$original_code_basic->user_no = $this->user_no;
		$original_code_basic->language = $this->language;
		$original_code_basic->uri_base = $this->uri_base;
		$original_code_basic->uri_current = $this->uri_current;

		$original_func_common = new Original\Func\Common();
		$original_func_common->app_mode = $this->app_mode;
		$original_func_common->agent_type = $this->agent_type;
		$original_func_common->user_no = $this->user_no;
		$original_func_common->language = $this->language;
		$original_func_common->uri_base = $this->uri_base;
		$original_func_common->uri_current = $this->uri_current;

		$original_common_text = new Original\Common\Text();



		// --------------------------------------------------
		//   Access Date更新
		// --------------------------------------------------

		if (isset($this->user_no)) $original_func_common->renew_access_date($this->user_no, null, null);



		// --------------------------------------------------
		//   トップ画像の設定
		// --------------------------------------------------

		$top_image_arr = array('amazon_gift' => array('width' => 500, 'height' => 500));
		$top_image_renewal_date = '2015-07-06 00:00:00';


		// --------------------------------------------------
		//   エントリーユーザー
		// --------------------------------------------------

		$code_present_entry_users_1 = $original_code_present->read_present_users(array('page' => 1, 'previous' => null, 'winner' => null));
		$code_present_entry_users_2 = $original_code_present->read_present_users(array('page' => 1, 'previous' => 2, 'winner' => null));
		$code_present_winner_users_1 = $original_code_present->read_present_users(array('page' => 1, 'previous' => 2, 'winner' => true));
		$code_present_winner_users_2 = $original_code_present->read_present_users(array('page' => 1, 'previous' => 3, 'winner' => true));
		$code_present_winner_users_3 = $original_code_present->read_present_users(array('page' => 1, 'previous' => 4, 'winner' => true));



		// --------------------------------------------------
		//   Adsense
		// --------------------------------------------------

		$view_adsense = View::forge('common/adsense_view2');
		$view_adsense->set_safe('app_mode', $this->app_mode);
		$view_adsense->set_safe('ad_block', $this->ad_block);



		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";
			$session_id = Session::key('session_id');
			echo '$session_id';
			var_dump($session_id);

			$fuelcid = Cookie::get('fuelcid');
			echo '$fuelcid';
			var_dump($fuelcid);

			$session_cookie = Crypt::decode($fuelcid);
			echo '$session_cookie';
			var_dump($session_cookie);

		}
		//exit();






		// ------------------------------
		//    コンテンツ
		// ------------------------------

		// ページ特有のID
		$content_id = $original_common_text->random_text_lowercase(10);

		$view_content = View::forge('present_view');
		$view_content->set_safe('app_mode', $this->app_mode);
		$view_content->set('content_id', $content_id);
		$view_content->set_safe('login_user_no', $this->user_no);
		$view_content->set('uri_base', $this->uri_base);

		$view_content->set('top_image_arr', $top_image_arr);
		$view_content->set('top_image_renewal_date', $top_image_renewal_date);


		// ----- コード -----

		$view_content->set_safe('code_present_entry_users_1', $code_present_entry_users_1);
		$view_content->set_safe('code_present_entry_users_2', $code_present_entry_users_2);
		$view_content->set_safe('code_present_winner_users_1', $code_present_winner_users_1);
		$view_content->set_safe('code_present_winner_users_2', $code_present_winner_users_2);
		$view_content->set_safe('code_present_winner_users_3', $code_present_winner_users_3);


		// ----- 広告 -----

		$view_content->set_safe('code_adsense', $view_adsense);


		// --------------------------------------------------
		//   ◆ 以下Appにコピー　終わり
		// --------------------------------------------------





		// --------------------------------------------------
		//   ◆◆◆　出力　◆◆◆
		// --------------------------------------------------

		// ------------------------------
		//    Meta
		// ------------------------------

		$this->data_meta['lang'] = $this->language;
		$this->data_meta['title'] = 'Game Users - Amazonギフト券プレゼントイベント';
		$this->data_meta['keywords'] = 'ゲームユーザーズ,ゲーム,SNS,コミュニティ';

		// description 改行削除＆文字数調節
		$description = str_replace(array("\r\n","\r","\n"), ' ', 'ゲームの募集を投稿するとAmazonギフト券が抽選で当たるプレゼントイベントを実施中！');
		$description = (mb_strlen($description) > 100) ? mb_substr($description, 0, 99, 'UTF-8') . '…' : $description;
		$this->data_meta['description'] = $description;

		$this->data_meta['og_title'] = $this->data_meta['title'];
		$this->data_meta['og_type'] = 'article';
		$this->data_meta['og_description'] = $this->data_meta['description'];
		$this->data_meta['og_url'] = $this->uri_current;
		$this->data_meta['og_image'] = $this->uri_base . 'assets/img/index/image_1.png';
		$this->data_meta['og_site_name'] = 'Game Users';

		$this->data_meta['favicon_url'] = $this->uri_base . 'favicon.ico';


		// ------------------------------
		//    Meta　スタイルシート
		// ------------------------------

		$this->data_meta['css_arr'] = array(
			Config::get('css_bootstrap'),
			Config::get('css_ladda'),
			Config::get('css_typeahead')
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
			Config::get('js_ladda_spin'),
			Config::get('js_ladda'),
			Config::get('js_typeahead'),
			Config::get('js_i18next')
		);


		// ----- 追加　本番環境では軽量バージョンを読み込む -----

		if (Fuel::$env == 'development')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic'), 'style.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'present.js');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'present.min.js');
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

}
