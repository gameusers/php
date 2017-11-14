<?php

class Controller_Gc extends Controller_Base
{


	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}


	/**
	* ゲームページ表示
	*/
	public function action_index($param_1 = null, $param_2 = null, $param_3 = null, $param_4 = null)
	{


		// --------------------------------------------------
		//   メンテナンス表示
		// --------------------------------------------------

		if (Config::get('maintenance') == 2)
		{
			if ( ! Auth::member(100)) return Response::forge(View::forge('maintenance_view'), 503);
		}


		// --------------------------------------------------
		//   クッキー取得
		// --------------------------------------------------

		$cookie_footer_type = Cookie::get('footer_type', null);




		// --------------------------------------------------
		//   共通処理
		// --------------------------------------------------

		// 日時
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();

		// インスタンス作成
		$model_gc = new Model_Gc();
		$model_gc->agent_type = $this->agent_type;
		$model_gc->user_no = $this->user_no;
		$model_gc->language = $this->language;
		$model_gc->uri_base = $this->uri_base;
		$model_gc->uri_current = $this->uri_current;

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

		$original_code_basic = new Original\Code\Basic();
		$original_code_basic->app_mode = $this->app_mode;
		$original_code_basic->agent_type = $this->agent_type;
		$original_code_basic->user_no = $this->user_no;
		$original_code_basic->language = $this->language;
		$original_code_basic->uri_base = $this->uri_base;
		$original_code_basic->uri_current = $this->uri_current;

		$original_code_gc = new Original\Code\Gc();
		$original_code_gc->app_mode = $this->app_mode;
		$original_code_gc->agent_type = $this->agent_type;
		$original_code_gc->host = HOST;
		$original_code_gc->user_agent = USER_AGENT;
		$original_code_gc->user_no = $this->user_no;
		$original_code_gc->language = $this->language;
		$original_code_gc->uri_base = $this->uri_base;
		$original_code_gc->uri_current = $this->uri_current;

		$original_code_bbs = new Original\Code\Bbs();
		$original_code_advertisement = new Original\Code\Advertisement();
		$original_code_common2 = new Original\Code\Common2();

		$original_func_common = new Original\Func\Common();
		$original_func_common->app_mode = $this->app_mode;
		$original_func_common->agent_type = $this->agent_type;
		$original_func_common->user_no = $this->user_no;
		$original_func_common->language = $this->language;
		$original_func_common->uri_base = $this->uri_base;
		$original_func_common->uri_current = $this->uri_current;

		$original_common_text = new Original\Common\Text();
		$original_code_card = new Original\Code\Card();



		// --------------------------------------------------
		//   バリデーション　コミュニティIDチェック
		// --------------------------------------------------

		$val = Validation::forge();
		$val->add_field('game_id', 'Game ID', 'required|valid_string[alpha,lowercase,numeric,dashes]');
		$val->add_field('read_no_read_id', 'Read No Read ID', 'valid_string[alpha,lowercase,numeric]');

		if ($val->run(array('game_id' => $param_1, 'read_no_read_id' => $param_3)))
		{
			$validated_game_id = $val->validated('game_id');
			$db_game_data_arr = $model_game->get_game_data(null, $validated_game_id);
			$game_no = $db_game_data_arr['game_no'];

			if (empty($db_game_data_arr)) throw new HttpNotFoundException;
		}
		else
		{
			throw new HttpNotFoundException;
		}


		// --------------------------------------------------
		//   ログイン後に戻ってくるページ設定
		// --------------------------------------------------

		if (USER_NO === null)
		{
			Session::set('redirect_type', 'gc');
			Session::set('redirect_id', $validated_game_id);
		}


		// --------------------------------------------------
		//    game_community
		// --------------------------------------------------

		$db_game_community = $model_game->get_game_community(array('game_no' => $game_no));


		// --------------------------------------------------
		//    ログインユーザー情報
		// --------------------------------------------------

		$login_user_data_arr = $model_user->get_login_user_data($game_no);
		$db_users_game_community_arr = $login_user_data_arr[0];
		$login_profile_data_arr = $login_user_data_arr[1];


		// --------------------------------------------------
		//   Access Date更新
		// --------------------------------------------------

		if (isset($this->user_no))
		{

			$users_game_community_config = unserialize($db_users_game_community_arr['config']);

			if (isset($users_game_community_config[$game_no]['profile_no']))
			{
				//if ($this->user_no == 1) var_dump('aaa');
				$result_renew_access_date = $original_func_common->renew_access_date(null, $users_game_community_config[$game_no]['profile_no'], null);
			}
			else
			{
				//if ($this->user_no == 1) var_dump('bbb');
				$result_renew_access_date = $original_func_common->renew_access_date($this->user_no, null, null);
			}

			// フォームなどのアクセス日時を揃える
			if ($result_renew_access_date) $login_profile_data_arr['access_date'] = $datetime_now;

		}


		// --------------------------------------------------
		//   最近アクセスしたゲームページ　クッキー作成
		// --------------------------------------------------

		$cookie_gc_access_game_no = Cookie::get('gc_access', null);

		if (isset($cookie_gc_access_game_no))
		{
			$temp_arr = explode(',', $cookie_gc_access_game_no);

			$gc_access_game_no_arr = [];

			foreach ($temp_arr as $key => $value)
			{
				if ($value != $game_no and $key < 30) array_push($gc_access_game_no_arr, $value);
			}

			array_unshift($gc_access_game_no_arr, $game_no);
		}
		else
		{
			$gc_access_game_no_arr = array($game_no);
		}

		$gc_access_save = implode(',', $gc_access_game_no_arr);
		Cookie::set('gc_access', $gc_access_save, 7776000);




		// --------------------------------------------------
		//   ゲーム名
		// --------------------------------------------------

		$language = 'ja';
		$game_title = $db_game_data_arr['name_' . $language];
		define('GAME_NAME', $db_game_data_arr['name_' . $language]);
		$thumbnail = $db_game_data_arr['thumbnail'];



		// --------------------------------------------------
		//   変数設定
		// --------------------------------------------------

		$group = ($param_2) ? $param_2 : 'bbs';
		$content = 'index';

		// if ($param_3)
		// {
		// 	if (ctype_digit($param_3))
		// 	{
		// 		$content = 'index';
		// 	}
		// 	else
		// 	{
		// 		$content = $param_3;
		// 	}
		// } else {
		// 	$content = 'index';
		// }


		// --------------------------------------------------
		//   ヘッダー　タブ
		// --------------------------------------------------

		$tab_bbs_arr['url'] = URI_BASE . 'gc/' . $validated_game_id . '/bbs';
		$tab_bbs_arr['group'] = 'bbs';
		$tab_bbs_arr['text'] = '交流';
		$tab_bbs_arr['active'] = ($group === 'bbs') ? true : false;

		$tab_rec_arr['url'] = URI_BASE . 'gc/' . $validated_game_id . '/rec';
		$tab_rec_arr['group'] = 'rec';
		$tab_rec_arr['text'] = '募集';
		$tab_rec_arr['active'] = ($group === 'rec') ? true : false;

		$tab_config_arr['url'] = URI_BASE . 'gc/' . $validated_game_id . '/config';
		$tab_config_arr['group'] = 'config';
		$tab_config_arr['text'] = '設定';
		$tab_config_arr['active'] = ($group === 'config') ? true : false;

		$tab_help_arr['url'] = URI_BASE . 'gc/' . $validated_game_id . '/help';
		$tab_help_arr['group'] = 'help';
		$tab_help_arr['text'] = null;
		$tab_help_arr['active'] = ($group === 'help') ? true : false;


		// --------------------------------------------------
		//   content_data設定　Javascriptから参照するオブジェクト
		// --------------------------------------------------

		$contents_data['initial_load']['group'] = $group;
		$contents_data['initial_load']['content'] = $content;

		$contents_data['opened_content']['bbs'] = 'index';
		$contents_data['opened_content']['rec'] = 'index';
		$contents_data['opened_content']['config'] = 'index';
		$contents_data['opened_content']['help'] = 'index';
		$contents_data['opened_content'][$group] = $content;

		$contents_data['bbs_index']['url'] = URI_BASE . 'gc/' . $validated_game_id . '/bbs';
		$contents_data['rec_index']['url'] = URI_BASE . 'gc/' . $validated_game_id . '/rec';

		$contents_data['config_index']['state'] = ['group' => 'config', 'content' => 'index'];
		$contents_data['config_index']['url'] = URI_BASE . 'gc/' . $validated_game_id . '/config';
		$contents_data['config_index']['meta_title'] = GAME_NAME . ' - 設定';
		$contents_data['config_index']['meta_keywords'] = GAME_NAME . ',設定';
		$contents_data['config_index']['meta_description'] = GAME_NAME . ' 設定ページ';

		$contents_data['help_index']['state'] = ['group' => 'help', 'content' => 'index'];
		$contents_data['help_index']['url'] = URI_BASE . 'gc/' . $validated_game_id . '/help';
		$contents_data['help_index']['meta_title'] = GAME_NAME . ' - ヘルプ';
		$contents_data['help_index']['meta_keywords'] = GAME_NAME . ',ヘルプ';
		$contents_data['help_index']['meta_description'] = 'ゲームページについて';



		// --------------------------------------------------
		//   コンテンツ読み込み
		// --------------------------------------------------

		$code_bbs_thread_list = $code_bbs = $code_recruitment_menu = $code_recruitment = $code_config = $code_help_menu = $code_help = null;


		// ---------------------------------------------
		//   交流掲示板
		// ---------------------------------------------

		if ($group === 'bbs')
		{

			$bbs_id = null;
			$bbs_page = 1;
			$bbs_page_comment = 1;

			if (mb_strlen($param_3) === 16)
			{
				$bbs_id = $param_3;
				$bbs_page_comment = $param_4 ?? 1;
			}
			else if (ctype_digit($param_3))
			{
				$bbs_page = $param_3;
			}
			else if ($param_3)
			{
				throw new HttpNotFoundException;
			}


			// --------------------------------------------------
			//    交流掲示板　スレッド一覧
			// --------------------------------------------------

			$original_code_bbs->set_game_no($game_no);
			$original_code_bbs->set_page(1);
			$code_bbs_thread_list = $original_code_bbs->get_code_thread_list_gc();


			// --------------------------------------------------
			//   交流掲示板
			// --------------------------------------------------

			// ---------------------------------------------
			//    個別
			// ---------------------------------------------

			if ($group === 'bbs' and $bbs_id)
			{

				// ----------------------------------------
				//    コード
				// ----------------------------------------

				$temp_arr = array(
					'type' => 'gc',
					'game_no' => $game_no,
					'bbs_id' => $bbs_id,
					'page_comment' => $bbs_page_comment
				);

				$temp_arr = $original_code_bbs->get_code_bbs_individual_gc($temp_arr);

				$code_bbs = $temp_arr['code'];
				$contents_data['bbs_index']['state'] = $temp_arr['state'];
				$contents_data['bbs_index']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['bbs_index']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['bbs_index']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['bbs_index']['meta_description'] = $temp_arr['meta_description'];

// \Debug::dump($code_bbs);
// exit();

			}

			// ---------------------------------------------
			//    まとめて
			// ---------------------------------------------

			else
			{

				// ----------------------------------------
				//    コード
				// ----------------------------------------

				$original_code_bbs->set_login_profile_data_arr_gc($login_profile_data_arr);
				$original_code_bbs->set_datetime_now($datetime_now);
				$original_code_bbs->set_page($bbs_page);
				$temp_arr = $original_code_bbs->get_code_bbs_gc();

				$code_bbs = $temp_arr['code'];
				$contents_data['bbs_index']['state'] = $temp_arr['state'];
				$contents_data['bbs_index']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['bbs_index']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['bbs_index']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['bbs_index']['meta_description'] = $temp_arr['meta_description'];

			}

		}


		// ---------------------------------------------
		//   募集掲示板
		// ---------------------------------------------

		else if ($group === 'rec')
		{

			$more_button = true;
			$recruitment_search_type_arr = null;
			$recruitment_id = null;
			$recruitment_page = 1;

			if ($param_3 === 'player') $recruitment_search_type_arr = array(1);
			else if ($param_3 === 'friend') $recruitment_search_type_arr = array(2);
			else if ($param_3 === 'member') $recruitment_search_type_arr = array(3);
			else if ($param_3 === 'trade') $recruitment_search_type_arr = array(4);
			else if ($param_3 === 'etc') $recruitment_search_type_arr = array(5);
			else if (mb_strlen($param_3) === 16) $recruitment_id = $param_3;
			else if (ctype_digit($param_3)) $recruitment_page = $param_3;

			$more_button = ($recruitment_id) ? true : false;


			// --------------------------------------------------
			//    ハードウェア
			// --------------------------------------------------

			$db_hardware_arr = $model_game->get_hardware_sort($language);


			// --------------------------------------------------
			//    募集メニュー
			// --------------------------------------------------

			$temp_arr = array(
				'game_no' => $game_no,
				'db_hardware_arr' => $db_hardware_arr
			);

			$code_recruitment_menu = $original_code_gc->recruitment_menu($temp_arr)['code'];


			// --------------------------------------------------
			//    募集
			// --------------------------------------------------

			$code_recruitment_arr = array(
				'game_id' => $validated_game_id,
				'game_no' => $game_no,
				'recruitment_id' => $recruitment_id,
				'db_users_game_community_arr' => $db_users_game_community_arr,
				'login_profile_data_arr' => $login_profile_data_arr,
				'db_hardware_arr' => $db_hardware_arr,
				'datetime_now' => $datetime_now,
				'more_button' => $more_button,
				'search_type' => $recruitment_search_type_arr,
				'search_hardware_id_no' => null,
				'search_id_null' => null,
				'search_keyword' => null,
				'page' => $recruitment_page
			);

			$temp_arr = $original_code_gc->recruitment($code_recruitment_arr);
			$code_recruitment = $temp_arr['code'];

			$code_rec = $temp_arr['code'];
			$contents_data['rec_index']['state'] = $temp_arr['state'];
			$contents_data['rec_index']['url'] = $temp_arr['url'];
			$meta_title = $contents_data['rec_index']['meta_title'] = $temp_arr['meta_title'];
			$meta_keywords = $contents_data['rec_index']['meta_keywords'] = $temp_arr['meta_keywords'];
			$meta_description = $contents_data['rec_index']['meta_description'] = $temp_arr['meta_description'];

		}


		// ---------------------------------------------
		//   設定
		// ---------------------------------------------

		else if ($group === 'config')
		{

			if ( ! USER_NO) throw new HttpNotFoundException;

			$code_config = $original_code_gc->gc_select_profile_form($game_no, true, 1);
			$code_config .= $original_code_gc->edit_game_id_form($game_no, true, 1);
			$meta_title = $contents_data['config_index']['meta_title'];
			$meta_keywords = $contents_data['config_index']['meta_keywords'];
			$meta_description = $contents_data['config_index']['meta_description'];

		}


		// ---------------------------------------------
		//   ヘルプ
		// ---------------------------------------------

		else if ($group === 'help')
		{

			$original_code_help = new \Original\Code\Help();

			$temp_arr = array(
				'first_load' => null,
				'page' => 1,
				'list' => 'game',
				'content' => 'game_about',
			);

			$temp_arr = $original_code_help->code_help($temp_arr);

			$code_help_menu = $temp_arr['code_list'];
			$code_help = $temp_arr['code_content'];
			$meta_title = $contents_data['help_index']['meta_title'];
			$meta_keywords = $contents_data['help_index']['meta_keywords'];
			$meta_description = $contents_data['help_index']['meta_description'];


			// ----------------------------------------
			//    Meta
			// ----------------------------------------

			// $meta_title = $tab_help_arr['meta_title'];
			// $meta_keywords = $tab_help_arr['meta_keywords'];
			// $meta_description = $tab_help_arr['meta_description'];



		}



		// --------------------------------------------------
		//    タブ New 画像
		// --------------------------------------------------

		// $datetime_past = $original_common_date->sql_format('-3 days');
		//
		// // 募集
		// if ($datetime_past < $db_game_community['recruitment_renewal_date_' . $language])
		// {
		// 	$new_image_recruitment = true;
		// }
		// else
		// {
		// 	$new_image_recruitment = false;
		// }
		//
		// // 交流BBS
		// if ($datetime_past < $db_game_community['bbs_renewal_date_' . $language])
		// {
		// 	$new_image_bbs = true;
		// }
		// else
		// {
		// 	$new_image_bbs = false;
		// }


		// --------------------------------------------------
		//   ソーシャルボタン
		// --------------------------------------------------

		$view_social = ($this->app_mode) ? null : View::forge('common/social_view2');



		// --------------------------------------------------
		//   フッター　カード
		// --------------------------------------------------

		$temp_arr = array(
			'type' => 'renewal_game'
		);

		$footer_card_arr = $original_code_card->footer($temp_arr)['code'];




		//$test = true;

		if (isset($test))
		{
			Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

			echo '$db_game_data_arr';
			Debug::dump($db_game_data_arr);

			echo '$this->agent_type';
			Debug::dump($this->agent_type);

			echo '$this->os';
			Debug::dump($this->os);



		}
		//exit();



		// --------------------------------------------------
		//   募集総数
		// --------------------------------------------------

		//$recruitment_total = $db_game_community['recruitment_total_' . $language];



		// ------------------------------
		//    コンテンツ
		// ------------------------------

		$view_main = View::forge('gc_view');

		$view_main->set_safe('group', $group);
		$view_main->set_safe('content', $content);
		$view_main->set('game_no', $game_no);
		$view_main->set('game_title', $game_title);

		// $view_content->set_safe('new_image_recruitment', $new_image_recruitment);
		// $view_content->set_safe('new_image_bbs', $new_image_bbs);

		$view_main->set('game_id', $validated_game_id);


		// ----- コード -----

		$view_main->set_safe('code_bbs_thread_list', $code_bbs_thread_list);
		$view_main->set_safe('code_bbs', $code_bbs);
		$view_main->set_safe('code_recruitment_menu', $code_recruitment_menu);
		$view_main->set_safe('code_recruitment', $code_recruitment);
		$view_main->set_safe('code_config', $code_config);
		$view_main->set_safe('code_help_menu', $code_help_menu);
		$view_main->set_safe('code_help', $code_help);
		$view_main->set_safe('code_social', $view_social);





		// --------------------------------------------------
		//   ◆◆◆　出力　◆◆◆
		// --------------------------------------------------

		// ---------------------------------------------
		//    Meta
		// ---------------------------------------------

		$view_meta = View::forge('parts/meta_view');
		$view_meta->set('title', $meta_title);
		$view_meta->set('keywords', $meta_keywords);
		$view_meta->set('description', $meta_description);
		$view_meta->set('og_type', 'article');



		// ---------------------------------------------
		//    スタイルシート
		// ---------------------------------------------

		$this->data_meta['css_arr'] = array(
			Config::get('css_reset_min'),
			Config::get('css_bootstrap'),
			Config::get('css_ladda'),
			Config::get('css_typeahead'),
			Config::get('css_jquery_magnific_popup'),
			Config::get('css_jquery_swiper'),
			Config::get('css_jquery_auto_hiding_navigation'),
			Config::get('css_jquery_pnotify')
		);

		// ---------------------------------------------
		//    スマホ・タブレットのときに読み込む
		// ---------------------------------------------

		if (AGENT_TYPE === 'smartphone' or AGENT_TYPE === 'tablet')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_lastsidebar'));
		}


		// ---------------------------------------------
		//    Javascript
		// ---------------------------------------------

		$this->data_meta['js_arr'] = array(
			Config::get('js_jquery'),
			Config::get('js_jquery_cookie'),
			Config::get('js_bootstrap'),
			Config::get('js_jquery_autosize'),
			// Config::get('js_jquery_fastclick'),
			Config::get('js_jquery_magnific_popup'),
			Config::get('js_jquery_imagesloaded'),
			Config::get('js_ladda_spin'),
			Config::get('js_ladda'),
			Config::get('js_typeahead'),
			Config::get('js_jquery_swiper'),
			Config::get('js_jquery_auto_hiding_navigation'),
			Config::get('js_jquery_jrumble'),
			Config::get('js_jquery_sticky-kit'),
			Config::get('js_jquery_pnotify'),
			Config::get('js_masonry')
		);


		// ---------------------------------------------
		//    スマホ・タブレットのときに読み込む
		// ---------------------------------------------

		if (AGENT_TYPE === 'smartphone' or AGENT_TYPE === 'tablet')
		{
			array_push($this->data_meta['js_arr'], Config::get('js_lastsidebar'));
		}


		// ---------------------------------------------
		//    本番環境では軽量バージョンを読み込む
		// ---------------------------------------------

		if (Fuel::$env == 'development')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic'), 'style.css', 'new.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'gc.js', 'bbs.js');

			if (AGENT_TYPE === 'smartphone') array_push($this->data_meta['css_arr'], 'sp.css');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css', 'new.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'gc.min.js', 'bbs.min.js');
		}




		// ---------------------------------------------
		//    ヘッダー
		// ---------------------------------------------

		$temp_arr = [];

		array_push($temp_arr, $tab_bbs_arr);
		array_push($temp_arr, $tab_rec_arr);
		if (USER_NO) array_push($temp_arr, $tab_config_arr);
		array_push($temp_arr, $tab_help_arr);

		$view_header = View::forge('header_ver2_view');
		//$view_header->set('login_user_id', USER_ID);
		$view_header->set('game_no_arr', [$game_no]);
		$view_header->set('tab_arr', $temp_arr);


		// ---------------------------------------------
		//    フッター
		// ---------------------------------------------

		$view_footer = View::forge('footer_ver2_view');
		$view_footer->set_safe('cookie_footer_type', $cookie_footer_type);
		$view_footer->set_safe('code_card', $footer_card_arr);


		// ---------------------------------------------
		//    Javascript変数コード
		// ---------------------------------------------

		$original_js_arr = array(
			'uri_base' => $this->uri_base,
			'uri_current' => $this->uri_current,
			'language' => $this->language,
			'agent_type' => $this->agent_type,
			'game_no' => $game_no,
			'contents_data' => $contents_data
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

		$code_original_js = $original_code_basic->javascript_json_encode($original_js_arr);



		// ---------------------------------------------
		//    コード出力
		// ---------------------------------------------

		$view = View::forge('base_ver2_view', $this->data_meta);
		$view->set_safe('lang', $language);
		$view->set_safe('original_js', $code_original_js);
		$view->set('meta', $view_meta);
		$view->set('header', $view_header);
		$view->set('main', $view_main);
		$view->set('footer', $view_footer);

		return Response::forge($view);

	}

}
