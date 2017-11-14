<?php

class Controller_Index extends Controller_Base
{


	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}


	/**
	* トップ表示
	*/
	public function action_index($param_1 = null, $param_2 = null, $param_3 = null)
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

		$original_code_common2 = new Original\Code\Common2();

		$original_code_index = new Original\Code\Index();
		$original_code_index->app_mode = $this->app_mode;
		$original_code_index->agent_type = $this->agent_type;
		$original_code_index->user_no = $this->user_no;
		$original_code_index->language = $this->language;
		$original_code_index->uri_base = $this->uri_base;
		$original_code_index->uri_current = $this->uri_current;

		$original_func_common = new Original\Func\Common();
		$original_func_common->app_mode = $this->app_mode;
		$original_func_common->agent_type = $this->agent_type;
		$original_func_common->user_no = $this->user_no;
		$original_func_common->language = $this->language;
		$original_func_common->uri_base = $this->uri_base;
		$original_func_common->uri_current = $this->uri_current;

		$original_code_gc = new Original\Code\Gc();
		$original_code_gc->app_mode = $this->app_mode;
		$original_code_gc->agent_type = $this->agent_type;
		$original_code_gc->user_no = $this->user_no;
		$original_code_gc->language = $this->language;
		$original_code_gc->uri_base = $this->uri_base;
		$original_code_gc->uri_current = $this->uri_current;

		$original_code_bbs = new Original\Code\Bbs();
		$original_code_advertisement = new \Original\Code\Advertisement();
		$original_code_wiki = new Original\Code\Wiki();
		$original_common_text = new Original\Common\Text();
		$original_code_card = new Original\Code\Card();



		// --------------------------------------------------
		//   バリデーション　Param チェック
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = Validation::forge();
		$val->add('param_1', 'param_1')->add_rule('match_pattern', '/^(feed|community|wiki|help)$/');

		if ( ! $val->run(array('param_1' => $param_1)))
		{
			throw new HttpNotFoundException;
		}


		// --------------------------------------------------
		//   ログイン後に戻ってくるページ設定
		// --------------------------------------------------

		if (USER_NO === null)
		{
			Session::set('redirect_type', 'index');
			Session::set('redirect_id', null);
		}


		// --------------------------------------------------
		//   Access Date更新
		// --------------------------------------------------

		if (USER_NO) $original_func_common->renew_access_date(USER_NO, null, null);




		// --------------------------------------------------
		//   変数設定
		// --------------------------------------------------

		$language = 'ja';
		$feed = 'all';

		$group = ($param_1) ? $param_1 : 'feed';

		if ($param_2)
		{
			if (ctype_digit($param_2))
			{
				$content = 'index';
				$page = (int) $param_2;
			}
			else
			{
				$content = $param_2;
				$page = 1;
			}
		} else {
			$content = 'index';
			$page = 1;
		}

		if ($group === 'feed' and $content !== 'index')
		{
			$feed = $content;
			$content = 'index';
		}


		// --------------------------------------------------
		//   ヘッダー　タブ
		// --------------------------------------------------

		$tab_feed_arr['url'] = URI_BASE . 'in/feed';
		$tab_feed_arr['group'] = 'feed';
		$tab_feed_arr['text'] = 'フィード';
		$tab_feed_arr['active'] = ($group === 'feed') ? true : false;

		$tab_community_arr['url'] = URI_BASE . 'in/community';
		$tab_community_arr['group'] = 'community';
		$tab_community_arr['text'] = 'コミュニティ';
		$tab_community_arr['active'] = ($group === 'community') ? true : false;

		$tab_wiki_arr['url'] = URI_BASE . 'in/wiki';
		$tab_wiki_arr['group'] = 'wiki';
		$tab_wiki_arr['text'] = 'Wiki';
		$tab_wiki_arr['active'] = ($group === 'wiki') ? true : false;

		$tab_help_arr['url'] = URI_BASE . 'in/help';
		$tab_help_arr['group'] = 'help';
		$tab_help_arr['text'] = null;
		$tab_help_arr['active'] = ($group === 'help') ? true : false;



		// --------------------------------------------------
		//   content_data設定　Javascriptから参照するオブジェクト
		// --------------------------------------------------

		$contents_data['initial_load']['group'] = $group;
		$contents_data['initial_load']['content'] = $content;

		$contents_data['opened_content']['feed'] = 'index';
		$contents_data['opened_content']['community'] = 'index';
		$contents_data['opened_content']['wiki'] = 'index';
		$contents_data['opened_content']['help'] = 'index';
		$contents_data['opened_content'][$group] = $content;

		$contents_data['feed_index']['url'] = URI_BASE . 'in/feed';
		$contents_data['feed_register_game']['url'] = URI_BASE . 'in/feed/register_game';

		$contents_data['community_index']['url'] = URI_BASE . 'in/community';
		$contents_data['community_create']['url'] = URI_BASE . 'in/community/create';

		$contents_data['wiki_index']['url'] = URI_BASE . 'in/wiki';
		$contents_data['wiki_create']['url'] = URI_BASE . 'in/wiki/create';
		$contents_data['wiki_edit']['url'] = URI_BASE . 'in/wiki/edit';

		$contents_data['help_index']['state'] = ['group' => 'help', 'content' => 'index'];
		$contents_data['help_index']['url'] = URI_BASE . 'in/help';
		$contents_data['help_index']['meta_title'] = 'Game Users - ヘルプ';
		$contents_data['help_index']['meta_keywords'] = 'ヘルプ';
		$contents_data['help_index']['meta_description'] = 'Game Usersについて';



		// --------------------------------------------------
		//   コンテンツ読み込み
		// --------------------------------------------------

		$code_feed = $code_feed_pagination = $code_register_game = $code_community_index = $code_community_create = $code_wiki_list = $code_wiki_create = $code_wiki_edit = $code_help_menu = $code_help = null;

		//$page = (ctype_digit($content)) ? $content : 1;


		// ---------------------------------------------
		//   フィード
		// ---------------------------------------------

		if ($group === 'feed')
		{


			// ----------------------------------------
			//   すべて
			// ----------------------------------------

			if ($feed === 'all')
			{

				$temp_arr = array(
					'type' => 'all',
					'page' => $page
				);

				$temp_arr = $original_code_card->feed($temp_arr);

			}


			// ----------------------------------------
			//   交流掲示板
			// ----------------------------------------

			else if ($feed === 'bbs')
			{

				$page = (ctype_digit($param_3)) ? (int) $param_3 : 1;

				$temp_arr = array(
					'type' => 'bbs',
					'page' => $page
				);

				$temp_arr = $original_code_card->feed($temp_arr);

			}


			// ----------------------------------------
			//   募集掲示板
			// ----------------------------------------

			else if ($feed === 'recruitment')
			{

				$page = (ctype_digit($param_3)) ? (int) $param_3 : 1;

				$temp_arr = array(
					'type' => 'recruitment',
					'page' => $page
				);

				$temp_arr = $original_code_card->feed($temp_arr);

			}


			// ----------------------------------------
			//   コミュニティ
			// ----------------------------------------

			else if ($feed === 'community')
			{

				$page = (ctype_digit($param_3)) ? (int) $param_3 : 1;

				$temp_arr = array(
					'type' => 'community',
					'page' => $page
				);

				$temp_arr = $original_code_card->feed($temp_arr);

			}


			// ----------------------------------------
			//   コミュニティ
			// ----------------------------------------

			else if ($feed === 'community')
			{

				$page = (ctype_digit($param_3)) ? (int) $param_3 : 1;

				$temp_arr = array(
					'type' => 'community',
					'page' => $page
				);

				$temp_arr = $original_code_card->feed($temp_arr);

			}


			// ----------------------------------------
			//   ゲーム登録
			// ----------------------------------------

			else if ($feed === 'register_game')
			{

				$temp_arr = $original_code_index->form_register_game([]);

				$code_register_game = $temp_arr['code'];
				$contents_data['feed_register_game']['state'] = $temp_arr['state'];
				$contents_data['feed_register_game']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['feed_register_game']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['feed_register_game']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['feed_register_game']['meta_description'] = $temp_arr['meta_description'];
			}


			if ($feed !== 'register_game')
			{
				$code_feed = $temp_arr['code_feed'];
				$code_feed_pagination = $temp_arr['code_feed_pagination'];
				$contents_data['feed_index']['state'] = $temp_arr['state'];
				$contents_data['feed_index']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['feed_index']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['feed_index']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['feed_index']['meta_description'] = $temp_arr['meta_description'];
			}



			//\Debug::dump($group, $content, $page, $feed, $contents_data, $temp_arr);
			// exit();


		}


		// ---------------------------------------------
		//   コミュニティ
		// ---------------------------------------------

		else if ($group === 'community')
		{

			// ----------------------------------------
			//   コミュニティ一覧
			// ----------------------------------------

			if ($content === 'index')
			{

				$temp_arr = array(
					'type' => 'all',
					'page' => $page
				);

				$temp_arr = $original_code_card->community_list($temp_arr);

				$code_community_index = $temp_arr['code'];
				$contents_data['community_index']['state'] = $temp_arr['state'];
				$contents_data['community_index']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['community_index']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['community_index']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['community_index']['meta_description'] = $temp_arr['meta_description'];
//\Debug::dump($temp_arr);
			}

			// ----------------------------------------
			//   コミュニティ作成
			// ----------------------------------------

			else
			{

				$temp_arr = $original_code_index->form_community_create([]);

				$code_community_create = $temp_arr['code'];
				$contents_data['community_create']['state'] = $temp_arr['state'];
				$contents_data['community_create']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['community_create']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['community_create']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['community_create']['meta_description'] = $temp_arr['meta_description'];

			}

		}


		// ---------------------------------------------
		//   Wiki
		// ---------------------------------------------

		else if ($group === 'wiki')
		{

			// ----------------------------------------
			//   Wiki一覧
			// ----------------------------------------

			if ($content === 'index')
			{

				$temp_arr = array(
					'page' => $page
				);

				$temp_arr = $original_code_card->wiki_list($temp_arr);

				$code_wiki_list = $temp_arr['code'];
				$contents_data['wiki_index']['state'] = $temp_arr['state'];
				$contents_data['wiki_index']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['wiki_index']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['wiki_index']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['wiki_index']['meta_description'] = $temp_arr['meta_description'];

			}

			// ----------------------------------------
			//   Wiki作成
			// ----------------------------------------

			else if ($content === 'create')
			{

				$temp_arr = $original_code_wiki->form_create([]);

				$code_wiki_create = $temp_arr['code'];
				$contents_data['wiki_create']['state'] = $temp_arr['state'];
				$contents_data['wiki_create']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['wiki_create']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['wiki_create']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['wiki_create']['meta_description'] = $temp_arr['meta_description'];
// \Debug::dump($code_wiki_create);
			}


			// ----------------------------------------
			//   Wiki編集
			// ----------------------------------------

			else if ($content === 'edit')
			{

				$temp_arr = array(
					'page' => 1
				);

				$temp_arr = $original_code_wiki->form_edit($temp_arr);

				$code_wiki_edit = $temp_arr['code'];
				$contents_data['wiki_edit']['state'] = $temp_arr['state'];
				$contents_data['wiki_edit']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['wiki_edit']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['wiki_edit']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['wiki_edit']['meta_description'] = $temp_arr['meta_description'];

			}


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
				'list' => 'top',
				'content' => 'top_about',
			);

			$temp_arr = $original_code_help->code_help($temp_arr);

			$code_help_menu = $temp_arr['code_list'];
			$code_help = $temp_arr['code_content'];
			$meta_title = $contents_data['help_index']['meta_title'];
			$meta_keywords = $contents_data['help_index']['meta_keywords'];
			$meta_description = $contents_data['help_index']['meta_description'];

		}








		// --------------------------------------------------
		//   サイドメニュー用　Amazonサムネイル広告
		// --------------------------------------------------

		$code_amazon_menu_1 = null;
		$code_amazon_menu_2 = null;
		$code_amazon_menu_3 = null;

		if ( ! AGENT_TYPE)
		{
			$temp_arr = array(
				'type' => 'amazon_menu',
				'page' => 1
			);

			$result_arr = $original_code_card->feed($temp_arr);
			$code_amazon_menu_1 = $result_arr['code_amazon_menu_1'];
			$code_amazon_menu_2 = $result_arr['code_amazon_menu_2'];
			$code_amazon_menu_3 = $result_arr['code_amazon_menu_3'];
		}



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



		// ----------------------------------------
		//    Meta
		// ----------------------------------------

		if ( ! $param_1)
		{
			$meta_title = 'Game Users';
			$meta_keywords = 'ゲームユーザーズ,ゲーム,SNS,コミュニティ';
			$meta_description = 'Game UsersはゲームユーザーのためのSNS・コミュニティサイトです。';
		}




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



		// --------------------------------------------------
		//    コンテンツ
		// --------------------------------------------------

		$view_main = View::forge('index_view');

		$view_main->set_safe('group', $group);
		$view_main->set_safe('content', $content);
		$view_main->set_safe('feed', $feed);


		// ----- コード -----

		$view_main->set_safe('code_feed', $code_feed);
		$view_main->set_safe('code_feed_pagination', $code_feed_pagination);
		$view_main->set_safe('code_register_game', $code_register_game);
		$view_main->set_safe('code_community_index', $code_community_index);
		$view_main->set_safe('code_community_create', $code_community_create);
		$view_main->set_safe('code_wiki_list', $code_wiki_list);
		$view_main->set_safe('code_wiki_create', $code_wiki_create);
		$view_main->set_safe('code_wiki_edit', $code_wiki_edit);
		$view_main->set_safe('code_help_menu', $code_help_menu);
		$view_main->set_safe('code_help', $code_help);
		$view_main->set_safe('code_amazon_menu_1', $code_amazon_menu_1);
		$view_main->set_safe('code_amazon_menu_2', $code_amazon_menu_2);
		$view_main->set_safe('code_amazon_menu_3', $code_amazon_menu_3);
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
		$view_meta->set('og_type', 'website');


		// ----------------------------------------
		//    Meta　スタイルシート
		// ----------------------------------------

		$this->data_meta['css_arr'] = array(
			Config::get('css_reset_min'),
			Config::get('css_bootstrap'),
			Config::get('css_ladda'),
			Config::get('css_typeahead'),
			Config::get('css_jquery_magnific_popup'),
			Config::get('css_jquery_swiper'),
			Config::get('css_jquery_auto_hiding_navigation'),
			Config::get('css_jquery_pnotify'),
			Config::get('css_jquery_contextMenu')
		);

		// ---------------------------------------------
		//    スマホのときに読み込む
		// ---------------------------------------------

		if (AGENT_TYPE === 'smartphone')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_lastsidebar'));
		}

		// ---------------------------------------------
		//    タブレットのときに読み込む
		// ---------------------------------------------

		else if (AGENT_TYPE === 'tablet')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_lastsidebar'), Config::get('css_aos'));
		}

		// ---------------------------------------------
		//    PCのときに読み込む
		// ---------------------------------------------

		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_aos'));
		}



		// ----------------------------------------
		//    Meta　Javascript
		// ----------------------------------------

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
			Config::get('js_masonry'),
			Config::get('js_jquery_contextMenu'),
			Config::get('js_jquery_ui_position')
		);

		// ---------------------------------------------
		//    スマホのときに読み込む
		// ---------------------------------------------

		if (AGENT_TYPE === 'smartphone')
		{
			array_push($this->data_meta['js_arr'], Config::get('js_lastsidebar'), Config::get('js_jquery_trunk8'));
		}

		// ---------------------------------------------
		//    タブレットのときに読み込む
		// ---------------------------------------------

		else if (AGENT_TYPE === 'tablet')
		{
			array_push($this->data_meta['js_arr'], Config::get('js_lastsidebar'), Config::get('js_jquery_trunk8'), Config::get('js_aos'));
		}

		// ---------------------------------------------
		//    PCのときに読み込む
		// ---------------------------------------------

		else
		{
			array_push($this->data_meta['js_arr'], Config::get('js_aos'));
		}


		// ---------------------------------------------
		//    本番環境では軽量バージョンを読み込む
		// ---------------------------------------------

		if (Fuel::$env == 'development')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic'), 'style.css', 'new.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'index.js', 'wiki_config.js');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css', 'new.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'index.min.js', 'wiki_config.min.js');
		}


		// ---------------------------------------------
		//    ヘッダー
		// ---------------------------------------------

		$temp_arr = [];

		array_push($temp_arr, $tab_feed_arr);
		array_push($temp_arr, $tab_community_arr);
		array_push($temp_arr, $tab_wiki_arr);
		array_push($temp_arr, $tab_help_arr);

		$view_header = View::forge('header_ver2_view');
		$view_header->set('game_no_arr', null);
		$view_header->set('tab_arr', $temp_arr);
// \Debug::dump($view_header->render());
// exit();

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
			'contents_data' => $contents_data
		);

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
