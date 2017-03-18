<?php

class Controller_Config extends Controller_Base
{
	
	public function before()
	{
		parent::before();
		
		// 言語データ読み込み
		//Lang::load('cod_ghosts');
	}
	
	
	
	/**
	* メール本登録
	*/
	public function action_mail($hash)
	{
		
		//var_dump($hash);
		
		// --------------------------------------------------
		//   メンテナンス表示
		// --------------------------------------------------
		
		if (Config::get('maintenance') == 2)
		{
			if ( ! Auth::member(100)) return Response::forge(View::forge('maintenance_view'), 503);
		}
		
		
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------
		
		// インスタンス作成
		$model_user = new Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;
		
		$model_mail = new Model_Mail();
		
		$original_code_basic = new Original\Code\Basic();
		$original_code_basic->app_mode = $this->app_mode;
		$original_code_basic->agent_type = $this->agent_type;
		$original_code_basic->user_no = $this->user_no;
		$original_code_basic->language = $this->language;
		$original_code_basic->uri_base = $this->uri_base;
		$original_code_basic->uri_current = $this->uri_current;
		
		$model_notifications = new Model_Notifications();
		$model_notifications->agent_type = $this->agent_type;
		$model_notifications->user_no = $this->user_no;
		$model_notifications->language = $this->language;
		$model_notifications->uri_base = $this->uri_base;
		$model_notifications->uri_current = $this->uri_current;
		
		
		
		// --------------------------------------------------
		//   ハッシュをチェックして本登録する
		// --------------------------------------------------
		
		$mail_user_no = $model_mail->check_provisional_hash($hash);
		//var_dump($mail_user_no);
		
		// ハッシュが存在しない場合は404エラー
		if ( empty($mail_user_no))
		{
			throw new HttpNotFoundException;
		}
		// 本登録
		else
		{
			$model_mail->save_mail($hash);
			
			// 通知できるユーザーか更新
			$model_notifications->update_notification_on_off();
		}
		
		// データ取得　User IDをビューに渡す用
		$db_user_data = $model_user->get_user_data($mail_user_no, null);
		
		//var_dump($db_user_data);
		
		
		// --------------------------------------------------
		//   ◆◆◆　出力　◆◆◆
		// --------------------------------------------------
		
		// ------------------------------
		//    Meta
		// ------------------------------
		
		$this->data_meta['lang'] = $this->language;
		$this->data_meta['title'] = 'メール登録完了';
		$this->data_meta['keywords'] = '';
		$this->data_meta['description'] = '';
		
		$this->data_meta['og_title'] = '';
		$this->data_meta['og_type'] = 'article';
		$this->data_meta['og_description'] = '';
		$this->data_meta['og_url'] = '';
		$this->data_meta['og_image'] = '';
		$this->data_meta['og_site_name'] = '';
		
		$this->data_meta['favicon_url'] = $this->uri_base . 'favicon.ico';
		
		
		// ------------------------------
		//    Meta　スタイルシート
		// ------------------------------
		
		$this->data_meta['css_arr'] = array(
			Config::get('css_bootstrap'),
			Config::get('css_ladda'),
			Config::get('css_typeahead'),
			Config::get('css_basic'),
			'style.css'
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
			Config::get('js_ladda_spin'),
			Config::get('js_ladda'),
			Config::get('js_typeahead'),
			Config::get('js_i18next')
		);
		
		// ----- 追加　本番環境では軽量バージョンを読み込む -----
		
		if (Fuel::$env == 'development')
		{
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), 'player.js');
		}
		else
		{
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), 'player.min.js');
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
		//    コンテンツ
		// ------------------------------
		
		$view_content = View::forge('config_mail_view');
		$view_content->set_safe('app_mode', $this->app_mode);
		$view_content->set('uri_base', $this->uri_base);
		$view_content->set('user_id', $db_user_data['user_id']);
		
		
		
		// ------------------------------
		//    Javascript変数コード
		// ------------------------------
		
		$original_js_arr = array('uri_base' => $this->uri_base, 'uri_current' => $this->uri_current, 'language' => $this->language);
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
