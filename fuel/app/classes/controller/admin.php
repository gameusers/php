<?php

class Controller_Admin extends Controller_Base
{


	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();


		// --------------------------------------------------
		//   管理者以外 404エラーへ飛ばす
		// --------------------------------------------------

		if ( ! Auth::member(100))
		{
			throw new HttpNotFoundException;
		}

	}


	/**
	* コミュニティ表示
	*/
	public function action_index($community_id = null)
	{



		// --------------------------------------------------
		//   メンテナンス表示
		// --------------------------------------------------

		// if (Config::get('maintenance') == 2)
		// {
		// 	if ( ! Auth::member(100)) return Response::forge(View::forge('maintenance_view'), 503);
		// }

// \Debug::dump(PHP_INT_SIZE);
		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();

		// インスタンス作成
		$model_game = new Model_Game();
		$model_game->agent_type = $this->agent_type;
		$model_game->user_no = $this->user_no;
		$model_game->language = $this->language;
		$model_game->uri_base = $this->uri_base;
		$model_game->uri_current = $this->uri_current;

		$model_user = new Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;

		$model_co = new Model_Co();
		$model_co->agent_type = $this->agent_type;
		$model_co->user_no = $this->user_no;
		$model_co->language = $this->language;
		$model_co->uri_base = $this->uri_base;
		$model_co->uri_current = $this->uri_current;

		//$model_bbs = new Model_Bbs();

		$original_func_co = new Original\Func\Co();
		$original_func_co->app_mode = $this->app_mode;
		$original_func_co->agent_type = $this->agent_type;
		$original_func_co->user_no = $this->user_no;
		$original_func_co->language = $this->language;
		$original_func_co->uri_base = $this->uri_base;
		$original_func_co->uri_current = $this->uri_current;

		$original_code_basic = new Original\Code\Basic();
		$original_code_basic->app_mode = $this->app_mode;
		$original_code_basic->agent_type = $this->agent_type;
		$original_code_basic->user_no = $this->user_no;
		$original_code_basic->language = $this->language;
		$original_code_basic->uri_base = $this->uri_base;
		$original_code_basic->uri_current = $this->uri_current;

		$original_code_co = new Original\Code\Co();
		$original_code_co->app_mode = $this->app_mode;
		$original_code_co->agent_type = $this->agent_type;
		$original_code_co->user_no = $this->user_no;
		$original_code_co->language = $this->language;
		$original_code_co->uri_base = $this->uri_base;
		$original_code_co->uri_current = $this->uri_current;

		$original_func_common = new Original\Func\Common();
		$original_func_common->app_mode = $this->app_mode;
		$original_func_common->agent_type = $this->agent_type;
		$original_func_common->user_no = $this->user_no;
		$original_func_common->language = $this->language;
		$original_func_common->uri_base = $this->uri_base;
		$original_func_common->uri_current = $this->uri_current;

		$original_common_text = new Original\Common\Text();

		$original_code_player = new Original\Code\Player();



		// $url = "https://gameusers.org/";
		//
		// //データを取得
		// $json = @file_get_contents("http://cloud.feedly.com/v3/feeds/feed%2F".rawurlencode($url));
		//
		// //JSONデータを連想配列に変換
		// $array = json_decode($json,true);
		// Debug::dump($array);



		// --------------------------------------------------
		//   SNS送信
		// --------------------------------------------------

		// $original_func_common = new \Original\Func\Common();
		// $original_func_common->send_sns();



		// --------------------------------------------------
		//   タイプ名変更
		// --------------------------------------------------

		// $query = DB::select('image_no')->from('image');
		// $arr = $query->execute()->as_array();
		// $save_arr = [];
		//
		// try
		// {
		//
		// 	DB::start_transaction();
		//
		// 	foreach ($arr as $key => $value)
		// 	{
		//
		// 		$save_arr['image_no'] = $value['image_no'];
		// 		$save_arr['type'] = 'hero_game';
		//
		// 		$query = DB::update('image');
		// 		$query->set($save_arr);
		// 		$query->where('image_no', '=', $save_arr['image_no']);
		// 		$result_arr = $query->execute();
		//
		// 		//\Debug::dump($save_arr);
		//
		// 	}
		//
		// 	DB::commit_transaction();
		//
		// }
		// catch (Exception $e)
		// {
		// 	DB::rollback_transaction();
		// }



		// --------------------------------------------------
		//   BBSにID割り振り
		// --------------------------------------------------

		// $query = DB::select('bbs_thread_no')->from('bbs_thread_gc');
		// $arr = $query->execute()->as_array();
		// $save_arr = [];
		//
		// try
		// {
		//
		// 	DB::start_transaction();
		//
		// 	foreach ($arr as $key => $value)
		// 	{
		//
		// 		$save_arr['bbs_thread_no'] = $value['bbs_thread_no'];
		// 		$save_arr['bbs_id'] = $original_common_text->random_text_lowercase(16);
		//
		// 		//\Debug::dump($save_arr);
		//
		// 		$query = DB::update('bbs_thread_gc');
		// 		$query->set($save_arr);
		// 		$query->where('bbs_thread_no', '=', $save_arr['bbs_thread_no']);
		// 		$result_thread_arr = $query->execute();
		//
		// 	}
		//
		// 	DB::commit_transaction();
		//
		// }
		// catch (Exception $e)
		// {
		// 	DB::rollback_transaction();
		// }
		//
		//
		//
		//
		// $query = DB::select('bbs_thread_no')->from('bbs_thread');
		// $arr = $query->execute()->as_array();
		// $save_arr = [];
		//
		// try
		// {
		//
		// 	DB::start_transaction();
		//
		// 	foreach ($arr as $key => $value)
		// 	{
		//
		// 		$save_arr['bbs_thread_no'] = $value['bbs_thread_no'];
		// 		$save_arr['bbs_id'] = $original_common_text->random_text_lowercase(16);
		//
		// 		//\Debug::dump($save_arr);
		//
		// 		$query = DB::update('bbs_thread');
		// 		$query->set($save_arr);
		// 		$query->where('bbs_thread_no', '=', $save_arr['bbs_thread_no']);
		// 		$result_thread_arr = $query->execute();
		//
		// 	}
		//
		// 	DB::commit_transaction();
		//
		// }
		// catch (Exception $e)
		// {
		// 	DB::rollback_transaction();
		// }
		//
		//
		//
		//
		// $query = DB::select('bbs_comment_no')->from('bbs_comment_gc');
		// $arr = $query->execute()->as_array();
		// $save_arr = [];
		//
		// try
		// {
		//
		// 	DB::start_transaction();
		//
		// 	foreach ($arr as $key => $value)
		// 	{
		//
		// 		$save_arr['bbs_comment_no'] = $value['bbs_comment_no'];
		// 		$save_arr['bbs_id'] = $original_common_text->random_text_lowercase(16);
		//
		// 		//\Debug::dump($save_arr);
		//
		// 		$query = DB::update('bbs_comment_gc');
		// 		$query->set($save_arr);
		// 		$query->where('bbs_comment_no', '=', $save_arr['bbs_comment_no']);
		// 		$result_thread_arr = $query->execute();
		//
		// 	}
		//
		// 	DB::commit_transaction();
		//
		// }
		// catch (Exception $e)
		// {
		// 	DB::rollback_transaction();
		// }
		//
		//
		//
		// $query = DB::select('bbs_comment_no')->from('bbs_comment');
		// $arr = $query->execute()->as_array();
		// $save_arr = [];
		//
		// try
		// {
		//
		// 	DB::start_transaction();
		//
		// 	foreach ($arr as $key => $value)
		// 	{
		//
		// 		$save_arr['bbs_comment_no'] = $value['bbs_comment_no'];
		// 		$save_arr['bbs_id'] = $original_common_text->random_text_lowercase(16);
		//
		// 		//\Debug::dump($save_arr);
		//
		// 		$query = DB::update('bbs_comment');
		// 		$query->set($save_arr);
		// 		$query->where('bbs_comment_no', '=', $save_arr['bbs_comment_no']);
		// 		$result_thread_arr = $query->execute();
		//
		// 	}
		//
		// 	DB::commit_transaction();
		//
		// }
		// catch (Exception $e)
		// {
		// 	DB::rollback_transaction();
		// }
		//
		//
		//
		//
		// $query = DB::select('bbs_reply_no')->from('bbs_reply_gc');
		// $arr = $query->execute()->as_array();
		// $save_arr = [];
		//
		// try
		// {
		//
		// 	DB::start_transaction();
		//
		// 	foreach ($arr as $key => $value)
		// 	{
		//
		// 		$save_arr['bbs_reply_no'] = $value['bbs_reply_no'];
		// 		$save_arr['bbs_id'] = $original_common_text->random_text_lowercase(16);
		//
		// 		//\Debug::dump($save_arr);
		//
		// 		$query = DB::update('bbs_reply_gc');
		// 		$query->set($save_arr);
		// 		$query->where('bbs_reply_no', '=', $save_arr['bbs_reply_no']);
		// 		$result_thread_arr = $query->execute();
		//
		// 	}
		//
		// 	DB::commit_transaction();
		//
		// }
		// catch (Exception $e)
		// {
		// 	DB::rollback_transaction();
		// }
		//
		//
		//
		// $query = DB::select('bbs_reply_no')->from('bbs_reply');
		// $arr = $query->execute()->as_array();
		// $save_arr = [];
		//
		// try
		// {
		//
		// 	DB::start_transaction();
		//
		// 	foreach ($arr as $key => $value)
		// 	{
		//
		// 		$save_arr['bbs_reply_no'] = $value['bbs_reply_no'];
		// 		$save_arr['bbs_id'] = $original_common_text->random_text_lowercase(16);
		//
		// 		//\Debug::dump($save_arr);
		//
		// 		$query = DB::update('bbs_reply');
		// 		$query->set($save_arr);
		// 		$query->where('bbs_reply_no', '=', $save_arr['bbs_reply_no']);
		// 		$result_thread_arr = $query->execute();
		//
		// 	}
		//
		// 	DB::commit_transaction();
		//
		// }
		// catch (Exception $e)
		// {
		// 	DB::rollback_transaction();
		// }



		//\Debug::dump($arr, $save_arr);







		// --------------------------------------------------
		//   WebPushテスト
		// --------------------------------------------------

		// $original_common_notification = new \Original\Common\Notification();
		//
		// $original_common_notification->set_web_push_arr(array(
		// 	'endpoint' => '',
		// 	'public_key' => '',
		// 	'auth_token' => '',
		// 	'title' => '募集への返信: フレンド募集！4',
		// 	'body' => '僕もこのゲームやってます。よかったらフレンドになってください。4',
		// 	'icon' => 'https://gameusers.org/assets/img/game/1/thumbnail.jpg',
		// 	'tag' => '13_uc_mail_all',
		// 	'url' => 'https://gameusers.org/gc/the-last-of-us',
		// 	'vibrate' => null,
		// 	'ttl' => null,
		// 	'urgency' => null,
		// 	'topic' => '13_uc_mail_all',
		// ));
		//
		// $result = $original_common_notification->send_web_push();
		//
		// \Debug::dump($result);


		// --------------------------------------------------
		//   notification_data更新
		// --------------------------------------------------

		// try
		// {
		//
		// 	// --------------------------------------------------
		// 	//   トランザクション開始
		// 	// --------------------------------------------------
		//
		// 	DB::start_transaction();
		//
		//
		// 	$query = DB::select('user_no', 'notification_on_off', 'notification_data')->from('users_data');
		// 	$db_user_data = $query->execute()->as_array();
		//
		// 	foreach ($db_user_data as $key => $value)
		// 	{
		// 		$db_notification_data_arr = unserialize($value['notification_data']);
		// 		//$db_notification_data_arr = unserialize('a:4:{s:6:"on_off";b:1;s:11:"app_or_mail";s:4:"mail";s:14:"receive_device";s:9:"undefined";s:11:"device_info";N;}');
		// 		//continue;
		// 		//\Debug::dump($db_notification_data_arr);
		// 		//'a:4:{s:6:"on_off";b:1;s:11:"app_or_mail";s:4:"mail";s:14:"receive_device";N;s:11:"device_info";N;}';
		//
		//
		// 		$arr['on_off'] = $db_notification_data_arr['on_off'];
		//
		// 		if ($value['notification_on_off'])
		// 		{
		// 			if (empty($db_notification_data_arr['app_or_mail']))
		// 			{
		// 				$arr['on_off_browser'] = $db_notification_data_arr['on_off_browser'];
		// 				$arr['on_off_app'] = $db_notification_data_arr['on_off_app'];
		// 				$arr['on_off_mail'] = $db_notification_data_arr['on_off_mail'];
		// 				//\Debug::dump($key);
		// 			}
		// 			else if ($db_notification_data_arr['app_or_mail'] == 'app')
		// 			{
		// 				$arr['on_off_browser'] = false;
		// 				$arr['on_off_app'] = true;
		// 				$arr['on_off_mail'] = false;
		// 			}
		// 			else if ($db_notification_data_arr['app_or_mail'] == 'mail')
		// 			{
		// 				$arr['on_off_browser'] = false;
		// 				$arr['on_off_app'] = false;
		// 				$arr['on_off_mail'] = true;
		// 			}
		// 			else
		// 			{
		// 				$arr['on_off_browser'] = false;
		// 				$arr['on_off_app'] = false;
		// 				$arr['on_off_mail'] = false;
		// 			}
		// 		}
		// 		else
		// 		{
		// 			$arr['on_off_browser'] = false;
		// 			$arr['on_off_app'] = false;
		// 			$arr['on_off_mail'] = false;
		// 		}
		//
		//
		// 		//unset($db_notification_data_arr['app_or_mail']);
		// 		$arr['receive_browser'] = null;
		// 		$arr['browser_info'] = null;
		//
		//
		// 		if ($db_notification_data_arr['receive_device'] == 'undefined')
		// 		{
		// 			$arr['receive_device'] = null;
		// 		}
		// 		else if ($db_notification_data_arr['receive_device'])
		// 		{
		// 			$arr['receive_device'] = explode('_', $db_notification_data_arr['receive_device'])[1];
		// 		}
		// 		else
		// 		{
		// 			$arr['receive_device'] = null;
		// 		}
		//
		//
		// 		if ($db_notification_data_arr['device_info'])
		// 		{
		// 			$temp_arr = [];
		// 			foreach ($db_notification_data_arr['device_info'] as $key2 => &$value2)
		// 			{
		// 				$temp_id = $value2['id'];
		// 				unset($value2['id']);
		// 				$temp_arr[$temp_id] = $value2;
		// 			}
		// 			unset($value2);
		//
		// 			$arr['device_info'] = $temp_arr;
		// 		}
		// 		else
		// 		{
		// 			$arr['receive_device'] = null;
		// 			$arr['device_info'] = null;
		// 		}
		//
		//
		// 		//$arr['device_info'] = $db_notification_data_arr['device_info'];
		//
		//
		// 		$save_arr['notification_data'] = serialize($arr);
		//
		// 		\Debug::dump($value['user_no'], $arr, $save_arr);
		//
		//
		// 		$query = DB::update('users_data');
		// 		$query->set($save_arr);
		// 		$query->where('user_no', '=', $value['user_no']);
		// 		$result_arr = $query->execute();
		//
		//
		// 	}
		//
		//
		// 	// --------------------------------------------------
		// 	//   コミット
		// 	// --------------------------------------------------
		//
		// 	DB::commit_transaction();
		//
		// }
		// catch (Exception $e)
		// {
		//
		// 	\Debug::dump($e);
		// 	// --------------------------------------------------
		// 	//   ロールバック
		// 	// --------------------------------------------------
		//
		// 	DB::rollback_transaction();
		//
		// }




		// BBSスレッドのコメント数、返信数を再計算する
		// $model_bbs = new Model_Bbs();
		// $model_bbs->calculate_total(array());

		//$original_func_common = new \Original\Func\Common();
		//$original_func_common->send_notification_mail();








		// Amazonデータ更新
		// $original_func_amazon = new \Original\Func\Amazon();
		// $original_func_amazon->save_api_data();



		//phpinfo();

		//$original_wiki_set = new Original\Wiki\Set();
		//$original_wiki_set->copy_wiki('test');
		//$original_wiki_set->delete_wiki('test');


		//Debug::$js_toggle_open = true;

		/*
		// BBS reply total 計算 gc
		$query = DB::select('bbs_thread_no')->from('bbs_thread_gc');
		$thread_arr = $query->execute()->as_array();

		foreach ($thread_arr as $key => $value)
		{
			\Debug::dump($value['bbs_thread_no']);

			$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_reply_gc');
			$query->where('bbs_thread_no', '=', $value['bbs_thread_no']);
			$query->where('on_off', '=', 1);
			$reply_arr = $query->execute()->as_array();

			\Debug::dump($reply_arr[0]['total']);


			$query = DB::update('bbs_thread_gc');

			$query->set(array(
				'reply_total' => $reply_arr[0]['total']
			));

			$query->where('bbs_thread_no', '=', $value['bbs_thread_no']);
			$result_thread_arr = $query->execute();

		}



		// BBS reply total 計算 uc
		$query = DB::select('bbs_thread_no')->from('bbs_thread');
		$thread_arr = $query->execute()->as_array();

		foreach ($thread_arr as $key => $value)
		{
			\Debug::dump($value['bbs_thread_no']);

			$query = DB::select(DB::expr('COUNT(*) as total'))->from('bbs_reply');
			$query->where('bbs_thread_no', '=', $value['bbs_thread_no']);
			$query->where('on_off', '=', 1);
			$reply_arr = $query->execute()->as_array();

			\Debug::dump($reply_arr[0]['total']);


			$query = DB::update('bbs_thread');

			$query->set(array(
				'reply_total' => $reply_arr[0]['total']
			));

			$query->where('bbs_thread_no', '=', $value['bbs_thread_no']);
			$result_thread_arr = $query->execute();

		}
		*/



		// --------------------------------------------------
		//    広告編集
		// --------------------------------------------------

		$temp_arr = array(
			'page' => 1,
			//'user_no' => USER_NO,
			'all' => true
		);

		$code_config_advertisement = $original_code_player->config_advertisement('api', $temp_arr)['code'];
		//$code_config_advertisement = null;
		//\Debug::dump($code_config_advertisement);
//exit();
		//$test = true;

		if (isset($test))
		{
			//Debug::$js_toggle_open = true;

			// echo "<br><br><br><br>";
			// var_dump($tab_class_active_arr);
//
			// echo '$member_arr';
			// var_dump($member_arr);

		}
		//exit();






		// --------------------------------------------------
		//   ◆◆◆　出力　◆◆◆
		// --------------------------------------------------

		// ------------------------------
		//    Meta
		// ------------------------------

		$this->data_meta['lang'] = $this->language;
		$this->data_meta['title'] = '管理者ページ';
		$this->data_meta['keywords'] = 'キーワード';

		// description 改行削除＆文字数調節
		$description = str_replace(array("\r\n","\r","\n"), ' ', '説明文');
		$description = (mb_strlen($description) > 100) ? mb_substr($description, 0, 99, 'UTF-8') . '…' : $description;
		$this->data_meta['description'] = $description;

		$this->data_meta['og_title'] = $this->data_meta['title'];
		$this->data_meta['og_type'] = 'article';
		$this->data_meta['og_description'] = $this->data_meta['description'];
		$this->data_meta['og_url'] = $this->uri_current;
		$this->data_meta['og_image'] = (isset($meta_og_image)) ? $meta_og_image : null;
		$this->data_meta['og_site_name'] = 'Game Users';

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
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'player.js', 'admin.js');
		}
		else
		{
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'player.min.js', 'admin.min.js');
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

		// ページ特有のID
		$content_id = $original_common_text->random_text_lowercase(10);

		$view_content = View::forge('admin_view');
		$view_content->set_safe('app_mode', $this->app_mode);
		$view_content->set('uri_base', $this->uri_base);

		$view_content->set_safe('code_config_advertisement', $code_config_advertisement);


		// ------------------------------
		//    Javascript変数コード
		// ------------------------------

		$original_js_arr = array('uri_base' => $this->uri_base, 'uri_current' => $this->uri_current, 'language' => $this->language, 'content_id' => $content_id);
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
	* phpinfo表示
	*/
	public function action_phpinfo()
	{
		phpinfo();
	}


}
