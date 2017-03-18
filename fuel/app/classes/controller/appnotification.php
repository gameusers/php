<?php

class Controller_Appnotification extends Controller_Base
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
		
		// --------------------------------------------------
		//   メンテナンス表示
		// --------------------------------------------------
		
		if (Config::get('maintenance') == 2)
		{
			if ( ! Auth::member(100)) return Response::forge(View::forge('maintenance_view'), 503);
		}
		
		
		$this->app_mode = true;
		
		
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
		
		// $original_code_common2 = new Original\Code\Common2();
// 		
		// $original_code_index = new Original\Code\Index();
		// $original_code_index->app_mode = $this->app_mode;
		// $original_code_index->agent_type = $this->agent_type;
		// $original_code_index->user_no = $this->user_no;
		// $original_code_index->language = $this->language;
		// $original_code_index->uri_base = $this->uri_base;
		// $original_code_index->uri_current = $this->uri_current;
// 		
		// $original_func_common = new Original\Func\Common();
		// $original_func_common->app_mode = $this->app_mode;
		// $original_func_common->agent_type = $this->agent_type;
		// $original_func_common->user_no = $this->user_no;
		// $original_func_common->language = $this->language;
		// $original_func_common->uri_base = $this->uri_base;
		// $original_func_common->uri_current = $this->uri_current;
// 		
		// $original_code_gc = new Original\Code\Gc();
		// $original_code_gc->app_mode = $this->app_mode;
		// $original_code_gc->agent_type = $this->agent_type;
		// $original_code_gc->user_no = $this->user_no;
		// $original_code_gc->language = $this->language;
		// $original_code_gc->uri_base = $this->uri_base;
		// $original_code_gc->uri_current = $this->uri_current;
// 		
		// $original_code_bbs = new Original\Code\Bbs();
		// $original_code_advertisement = new \Original\Code\Advertisement();
		
		$original_common_text = new Original\Common\Text();
		
		
		
		
		
		
		// --------------------------------------------------
		//  データベースから取得
		// --------------------------------------------------
		
		$db_users_data_arr = $model_user->get_user_data($this->user_no, null);
		
		
		// --------------------------------------------------
		//  コード作成
		// --------------------------------------------------
		
		//$type = ($validated_type == 'unread') ? true : false;
		
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
		// $view_content->set_safe('app_mode', $this->app_mode);
		// $view_content->set('content_id', $content_id);
		$view_content->set_safe('login_user_no', $this->user_no);
		// $view_content->set('uri_base', $this->uri_base);
// 		
		// $view_content->set('top_image_arr', $top_image_arr);
		// $view_content->set('top_image_renewal_date', $top_image_renewal_date);
		
		
		// ----- コード -----
		
		$view_content->set_safe('code_notifications', $code_notifications);
		// $view_content->set_safe('code_slide_game_list', $code_slide_game_list);
		// $view_content->set_safe('code_game_community', $code_game_community);
		// $view_content->set_safe('code_gc_bbs_list', $code_gc_bbs_list);
		// $view_content->set_safe('code_game_list', $code_game_list);
		// $view_content->set_safe('code_user_community', $code_user_community);
		// $view_content->set_safe('code_game_data_form', $code_game_data_form);
		// $view_content->set_safe('code_social', $view_social);
		// $view_content->set_safe('code_adsense', $view_adsense);
		// $view_content->set_safe('code_adsense_rectangle', $view_adsense_rectangle);
		// $view_content->set_safe('code_ad_amazon_slide', $code_ad_amazon_slide);
		
		
		
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
		$this->data_meta['title'] = 'Game Users';
		$this->data_meta['keywords'] = 'ゲームユーザーズ,ゲーム,SNS,コミュニティ';
		
		// description 改行削除＆文字数調節
		$description = str_replace(array("\r\n","\r","\n"), ' ', '「Game Users」は、ゲームユーザーのためのSNS・コミュニティサイトです。');
		$description = (mb_strlen($description) > 100) ? mb_substr($description, 0, 99, 'UTF-8') . '…' : $description;
		$this->data_meta['description'] = $description;
		
		$this->data_meta['og_title'] = $this->data_meta['title'];
		$this->data_meta['og_type'] = 'website';
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
			Config::get('css_typeahead'),
			Config::get('css_jquery_swiper')
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
			Config::get('js_jquery_fastclick'),
			Config::get('js_jquery_imagesloaded'),
			Config::get('js_ladda_spin'),
			Config::get('js_ladda'),
			Config::get('js_typeahead'),
			Config::get('js_i18next'),
			Config::get('js_jquery_swiper')
		);
		
		
		// ----- 追加　本番環境では軽量バージョンを読み込む -----
		
		if (Fuel::$env == 'development')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic'), 'style.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'index.js');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'index.min.js');
		}
		
		
		// ------------------------------
		//    ヘッダー
		// ------------------------------
		
		$view_header = View::forge('app/app_header_view');
		$view_header->set_safe('app_mode', $this->app_mode);
		$view_header->set('user_no', $this->user_no);
		$view_header->set('user_id', $this->user_id);
		$view_header->set('uri_base', $this->uri_base);
		
		
		// ------------------------------
		//    フッター
		// ------------------------------
		
		$view_footer = View::forge('app/app_footer_view');
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
	
}
