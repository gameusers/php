<?php

class Controller_Pl extends Controller_Base
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
	* プロフィール表示
	*/
	public function action_index($user_id = null, $read_type = null, $read_no = null)
	{

		// --------------------------------------------------
		//   メンテナンス表示
		// --------------------------------------------------

		if (Config::get('maintenance') == 2)
		{
			if ( ! Auth::member(100)) return Response::forge(View::forge('maintenance_view'), 503);
		}



		// --------------------------------------------------
		//   読み込む内容指定
		// --------------------------------------------------

		if (isset($read_type))
		{
			if ($read_type == 'notifications') Cookie::set('read_type', 'notifications');
			if ($read_type == 'prof') Cookie::set('read_type', 'prof');
		}



		// --------------------------------------------------
		//   共通処理　インスタンス作成
		// --------------------------------------------------

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

		$model_present = new Model_Present();
		$model_present->agent_type = $this->agent_type;
		$model_present->user_no = $this->user_no;
		$model_present->language = $this->language;
		$model_present->uri_base = $this->uri_base;
		$model_present->uri_current = $this->uri_current;

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

		$original_func_common = new Original\Func\Common();
		$original_func_common->app_mode = $this->app_mode;
		$original_func_common->agent_type = $this->agent_type;
		$original_func_common->user_no = $this->user_no;
		$original_func_common->language = $this->language;
		$original_func_common->uri_base = $this->uri_base;
		$original_func_common->uri_current = $this->uri_current;

		$original_code_player = new Original\Code\Player();
		// $original_code_player->app_mode = $this->app_mode;
		// $original_code_player->agent_type = $this->agent_type;
		// $original_code_player->user_no = $this->user_no;
		// $original_code_player->language = $this->language;
		// $original_code_player->uri_base = $this->uri_base;
		// $original_code_player->uri_current = $this->uri_current;

		$original_code_wiki = new Original\Code\Wiki();

		$original_common_text = new Original\Common\Text();

		$original_code_advertisement = new \Original\Code\Advertisement();



		// --------------------------------------------------
		//   Access Date更新
		// --------------------------------------------------

		if (isset($this->user_no)) $original_func_common->renew_access_date($this->user_no, null, null);


		// --------------------------------------------------
		//   バリデーション　ユーザーIDチェック
		// --------------------------------------------------

		$val = Validation::forge();
		$val->add_field('user_id', 'User ID', 'required|valid_string[alpha,lowercase,numeric,dashes]');

		if ($val->run(array('user_id' => $user_id)))
		{
			$validated_user_id = $val->validated('user_id');
			$db_users_data_arr = $model_user->get_user_data(null, $validated_user_id);

			if (empty($db_users_data_arr)) throw new HttpNotFoundException;
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
			Session::set('redirect_type', 'pl');
			Session::set('redirect_id', $validated_user_id);
		}




		// --------------------------------------------------
		//   著者チェック
		// --------------------------------------------------

		$check_author = ($db_users_data_arr['user_no'] == USER_NO) ? true : false;



		// --------------------------------------------------
		//   画像
		// --------------------------------------------------

		if (isset($db_users_data_arr['top_image']))
		{
			$top_image_arr = unserialize($db_users_data_arr['top_image']);
			$meta_og_image = $this->uri_base . 'assets/img/user/' . $db_users_data_arr['user_no'] . '/' . key($top_image_arr) . '.jpg';
		}
		else
		{
			$meta_og_image = $this->uri_base . 'assets/img/social/ogp_image.jpg';
			$top_image_arr = null;
		}


		// --------------------------------------------------
		//   プレイヤープロフィール作成
		// --------------------------------------------------

		$appoint = false;

		$view_content_main_profile = View::forge('parts/profile_view');
		$view_content_main_profile->set('app_mode', $this->app_mode);
		$view_content_main_profile->set('uri_base', $this->uri_base);
		$view_content_main_profile->set('login_user_no', $this->user_no);
		$view_content_main_profile->set('profile_arr', $db_users_data_arr);
		$view_content_main_profile->set('online_limit', Config::get('online_limit'));
		$view_content_main_profile->set_safe('link_force_off', true);
		$view_content_main_profile->set_safe('appoint', $appoint);
		//$view_content_main_profile->set('language', $this->language);
		//var_dump($db_users_data_arr, strtotime($db_users_data_arr['renewal_date']));


		// --------------------------------------------------
		//    追加プロフィール作成
		// --------------------------------------------------

		// Limit取得
		$limit_profile = ($this->agent_type != 'smartphone') ? Config::get('limit_profile') : Config::get('limit_profile_sp');

		// プロフィールデータ取得
		if ($read_type == 'prof')
		{
			$db_profiles_arr = $model_user->get_profile($read_no);

			// プロフィールが存在しない場合、Not found
			if ( ! $db_profiles_arr) throw new HttpNotFoundException;

			// 本人のプロフィールでない場合、Not found
			if ($db_users_data_arr['user_no'] != $db_profiles_arr['author_user_no']) throw new HttpNotFoundException;

			$db_profiles_arr = array($db_profiles_arr);

			$appoint = true;
			//var_dump($db_users_data_arr, $db_profiles_arr);
			//exit();
			//
		}
		else
		{
			$db_profiles_arr = $model_user->get_profile_list($db_users_data_arr['user_no'], 1, $limit_profile);
		}


		// 総数取得
		$total_profile = $model_user->get_profile_list_total($db_users_data_arr['user_no']);


		// ----- ゲームデータ処理 -----

		$game_no_arr = array();

		foreach ($db_profiles_arr as $key => $value) {

			if ($value['game_list'])
			{
				$arr = explode(',', $value['game_list']);
				array_shift($arr);
				array_pop($arr);
				$game_no_arr = array_merge($game_no_arr, $arr);
			}

		}

		if (count($game_no_arr) > 0)
		{
			// 重複番号削除
			$game_no_arr = array_unique($game_no_arr);

			// ゲーム名取得
			$game_names_arr = $model_game->get_game_name($this->language, $game_no_arr);
			//echo "<br><br><br><br>";
			//var_dump($game_no_arr, $game_names_arr);
		}
		else
		{
			$game_names_arr = null;
		}

		//var_dump($appoint);
		//exit();
		// ----- コード作成 -----

		$code_profiles = '';

		foreach ($db_profiles_arr as $key => $value) {

			$view_content_profiles = View::forge('parts/profile_view');
			$view_content_profiles->set('app_mode', $this->app_mode);
			$view_content_profiles->set('uri_base', $this->uri_base);
			$view_content_profiles->set('login_user_no', $this->user_no);
			$view_content_profiles->set('profile_arr', $value);
			$view_content_profiles->set('online_limit', Config::get('online_limit'));
			$view_content_profiles->set('game_names_arr', $game_names_arr);
			$view_content_profiles->set_safe('link_force_off', true);
			$view_content_profiles->set('author_user_no', $db_users_data_arr['user_no']);
			$view_content_profiles->set_safe('appoint', $appoint);
			$code_profiles .= $view_content_profiles->render() . "\n\n";

		}


		// --------------------------------------------------
		//    ページャー　プロフィール
		// --------------------------------------------------

		// ページャーの数字表示回数取得
		$pagination_profile_times = ($this->agent_type != 'smartphone') ? Config::get('pagination_times') : Config::get('pagination_times_sp');

		if ($total_profile > $limit_profile and ! $appoint)
		{
			$view_content_pagination_profile = View::forge('parts/pagination_view');
			$view_content_pagination_profile->set('page', 1);
			$view_content_pagination_profile->set('total', $total_profile);
			$view_content_pagination_profile->set('limit', $limit_profile);
			$view_content_pagination_profile->set('times', $pagination_profile_times);
			$view_content_pagination_profile->set('function_name', 'GAMEUSERS.player.readProfile');
			$view_content_pagination_profile->set('argument_arr', array($db_users_data_arr['user_no']));
		}
		else
		{
			$view_content_pagination_profile = null;
		}



		// --------------------------------------------------
		//    参加コミュニティ
		// --------------------------------------------------

		$code_participation_community = null;
		$code_participation_community_secret = null;

		if (isset($db_users_data_arr['participation_community']))
		{
			$code_participation_community = $original_code_common->search_community_list(array('user_no' => $db_users_data_arr['user_no'], 'game_list' => $db_users_data_arr['participation_community']), 1);
		}

		if ($check_author and isset($db_users_data_arr['participation_community_secret']))
		{
			$code_participation_community_secret = $original_code_common->search_community_list(array('user_no' => $db_users_data_arr['user_no'], 'game_list' => $db_users_data_arr['participation_community_secret']), 1);
		}



		// --------------------------------------------------
		//    当選
		// --------------------------------------------------

		// if ($check_author)
		// {
		// 	$db_present_winner_arr = $model_present->get_present_winner(array('user_no' => $db_users_data_arr['user_no']));
		// }
		// else
		// {
		// 	$db_present_winner_arr = null;
		// }






		// --------------------------------------------------
		//    広告設定
		// --------------------------------------------------

		//$code_config_advertisement = ($check_author) ? $original_code_player->config_advertisement(array()) : null;


		// --------------------------------------------------
		//   Wiki設定 （後からロードされる仕組みが導入されている）
		// --------------------------------------------------

		$code_wiki_tab = $original_code_wiki->tab(array('type' => 'player'))['code'];


		// --------------------------------------------------
		//   ソーシャルボタン
		// --------------------------------------------------

		$view_social = ($this->app_mode) ? null : View::forge('common/social_view');


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

		$code_ad_amazon_slide = $original_code_advertisement->code_ad_amazon_slide(array());



		//$test = true;

		if (isset($test))
		{

			echo "<br><br><br><br>";

			//echo 'FUEL_ENV';
			//var_dump($_SERVER['FUEL_ENV']);

			echo '$db_users_data_arr';
			var_dump($db_users_data_arr);

			// echo '$limit_profile';
			// var_dump($limit_profile);
//
			// echo '$db_profiles_arr';
			// var_dump($db_profiles_arr);
//
			// echo '$total_profile';
			// var_dump($total_profile);
//
			// echo '$top_image_arr';
			// var_dump($top_image_arr);

		}



		// ------------------------------
		//    コンテンツ
		// ------------------------------

		// ページ特有のID
		$content_id = $original_common_text->random_text_lowercase(10);

		$view_content = View::forge('player_view');
		$view_content->set_safe('app_mode', $this->app_mode);
		$view_content->set_safe('agent_type', $this->agent_type);
		$view_content->set('content_id', $content_id);
		$view_content->set_safe('login_user_no', $this->user_no);
		$view_content->set('page_user_no', $db_users_data_arr['user_no']);
		$view_content->set('uri_base', $this->uri_base);

		$view_content->set_safe('check_author', $check_author);

		$view_content->set('renewal_date', $db_users_data_arr['renewal_date']);
		$view_content->set('top_image_arr', $top_image_arr);
		if (empty($db_users_data_arr['explanation'])) $view_content->set_safe('first_access', true);

		//$view_content->set('db_present_winner_arr', $db_present_winner_arr);


		// ----- ユーザー本人の場合　設定用データ -----

		// if ($check_author)
		// {
		// 	$db_users_login_arr = $model_user->get_user_login($this->user_no);
		// 	$view_content->set('username', $db_users_login_arr['username']);
		// }


		// ----- コード -----

		$view_content->set_safe('main_profile', $view_content_main_profile);
		$view_content->set_safe('profiles', $code_profiles);
		$view_content->set_safe('code_pagination_profile', $view_content_pagination_profile);
		$view_content->set_safe('code_participation_community', $code_participation_community);
		$view_content->set_safe('code_participation_community_secret', $code_participation_community_secret);
		$view_content->set_safe('code_social', $view_social);
		$view_content->set_safe('code_adsense', $view_adsense);
		$view_content->set_safe('code_adsense_rectangle', $view_adsense_rectangle);
		$view_content->set_safe('code_ad_amazon_slide', $code_ad_amazon_slide);
		//$view_content->set_safe('code_config_advertisement', $code_config_advertisement);
		$view_content->set_safe('code_wiki_tab', $code_wiki_tab);



		// --------------------------------------------------
		//   ◆◆◆　出力　◆◆◆
		// --------------------------------------------------

		// ------------------------------
		//    Meta
		// ------------------------------

		$this->data_meta['lang'] = $this->language;
		$this->data_meta['title'] = (isset($db_users_data_arr['page_title'])) ? $db_users_data_arr['page_title'] : $db_users_data_arr['handle_name'];
		$this->data_meta['keywords'] = $db_users_data_arr['handle_name'];

		// description 改行削除＆文字数調節
		$description = str_replace(array("\r\n","\r","\n"), ' ', $db_users_data_arr['explanation']);
		$description = (mb_strlen($description) > 100) ? mb_substr($description, 0, 99, 'UTF-8') . '…' : $description;
		$this->data_meta['description'] = $description;

		$this->data_meta['og_title'] = $this->data_meta['title'];
		$this->data_meta['og_type'] = 'article';
		$this->data_meta['og_description'] = $this->data_meta['description'];
		$this->data_meta['og_url'] = $this->uri_current;
		$this->data_meta['og_image'] = $meta_og_image;
		$this->data_meta['og_site_name'] = 'Game Users';

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
			Config::get('js_jquery_fastclick'),
			Config::get('js_jquery_pnotify'),
			Config::get('js_jquery_magnific_popup'),
			Config::get('js_jquery_imagesloaded'),
			Config::get('js_ladda_spin'),
			Config::get('js_ladda'),
			Config::get('js_typeahead'),
			Config::get('js_i18next'),
			Config::get('js_jquery_swiper'),
			Config::get('js_jquery_perfect_scrollbar')
		);


		// ----- 追加　本番環境では軽量バージョンを読み込む -----

		if (Fuel::$env == 'development')
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic'), 'style.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'player.js', 'wiki_config.js');

			if ($check_author) array_push($this->data_meta['js_arr'], 'webpush/webpush.js');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'player.min.js', 'wiki_config.min.js');

			if ($check_author) array_push($this->data_meta['js_arr'], 'webpush/webpush.min.js');
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

		$original_js_arr = array('uri_base' => $this->uri_base, 'uri_current' => $this->uri_current, 'language' => $this->language, 'agent_type' => $this->agent_type, 'content_id' => $content_id, 'webpush_type' => 'player');
		$code_original_js = $original_code_basic->javascript($original_js_arr);



		// ------------------------------
		//    コード出力
		// ------------------------------

		$view = View::forge('base_view', $this->data_meta);
		$view->set_safe('app_mode', $this->app_mode);
		$view->set_safe('original_js', $code_original_js);
		if ($check_author) $view->set_safe('manifest', true);
		$view->set('header', $view_header);
		$view->set('content', $view_content);
		$view->set('footer', $view_footer);

		return Response::forge($view);

	}

}
