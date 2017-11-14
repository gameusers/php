<?php

class Controller_Uc extends Controller_Base
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

		$model_image = new Model_Image();

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
		$original_code_co->host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$original_code_co->user_agent = $_SERVER['HTTP_USER_AGENT'];
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

		$original_validation_common = new \Original\Validation\Common();
		$original_common_text = new Original\Common\Text();
		$original_code_bbs = new Original\Code\Bbs();
		$original_code_advertisement = new \Original\Code\Advertisement();
		$original_code_card = new Original\Code\Card();


		// --------------------------------------------------
		//   バリデーション　コミュニティIDチェック
		// --------------------------------------------------

		try
		{
			$validated_community_id = $original_validation_common->community_id($param_1);
		}
		catch (Exception $e)
		{
			throw new HttpNotFoundException;
		}


		// --------------------------------------------------
		//   バリデーション　Param 2 チェック
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = Validation::forge();
		$val->add('param_2', 'param_2')->add_rule('match_pattern', '/^(bbs|member|data|notification|config|help)$/');

		if ( ! $val->run(array('param_2' => $param_2)))
		{
			throw new HttpNotFoundException;
		}



		// --------------------------------------------------
		//   ログイン後に戻ってくるページ設定
		// --------------------------------------------------

		if (USER_NO === null)
		{
			Session::set('redirect_type', 'uc');
			Session::set('redirect_id', $validated_community_id);
		}



		// --------------------------------------------------
		//   変数設定
		// --------------------------------------------------

		$group = ($param_2) ? $param_2 : 'bbs';
		$content = 'index';
		// \Debug::dump($group);
		// exit();



		// --------------------------------------------------
		//   変数設定
		// --------------------------------------------------

		$language = 'ja';

		$db_community_arr = $model_co->get_community(null, $validated_community_id);
		$community_no = $db_community_arr['community_no'];
		$member_arr = unserialize($db_community_arr['member']);
		$config_arr = unserialize($db_community_arr['config']);
		$authority_arr = $original_func_co->authority($db_community_arr);

		$community_game_no_arr = $original_func_common->return_db_array('db_php', $db_community_arr['game_list']);
		$community_name = $db_community_arr['name'];
		//\Debug::dump($db_community_arr, $community_game_no_arr);



		// --------------------------------------------------
		//   Access Date更新
		// --------------------------------------------------

		if (USER_NO)
		{
			if ($authority_arr['member'])
			{
				$result_renew_access_date = $original_func_common->renew_access_date(USER_NO, null, $db_community_arr);
				if (is_array($result_renew_access_date)) $member_arr = $result_renew_access_date;
			}
			else
			{
				$original_func_common->renew_access_date(USER_NO, null, null);
			}
		}


		// --------------------------------------------------
		//   最近アクセスしたコミュニティ　クッキー作成
		// --------------------------------------------------

		$cookie_uc_access_community_no = Cookie::get('uc_access', null);

		if (isset($cookie_uc_access_community_no))
		{
			$temp_arr = explode(',', $cookie_uc_access_community_no);

			$uc_access_community_no_arr = [];

			foreach ($temp_arr as $key => $value)
			{
				if ($value != $community_no and $key < 30) array_push($uc_access_community_no_arr, $value);
			}

			array_unshift($uc_access_community_no_arr, $community_no);
		}
		else
		{
			$uc_access_community_no_arr = array($community_no);
		}

		$uc_access_save = implode(',', $uc_access_community_no_arr);
		Cookie::set('uc_access', $uc_access_save, 7776000);






		// --------------------------------------------------
		//   ヘッダー　タブ
		// --------------------------------------------------

		//if (empty($param_2)) $param_2 = 'bbs';
		// 表示するコンテンツがない場合はデータを表示する
		if ($group === 'bbs' and ! $authority_arr['read_announcement'] and ! $authority_arr['read_bbs']) $group = 'data';

		$tab_bbs_arr['url'] = URI_BASE . 'uc/' . $validated_community_id . '/bbs';
		$tab_bbs_arr['group'] = 'bbs';
		$tab_bbs_arr['text'] = 'トップ';
		$tab_bbs_arr['active'] = ($group === 'bbs') ? true : false;

		$tab_member_arr['url'] = URI_BASE . 'uc/' . $validated_community_id . '/member';
		$tab_member_arr['group'] = 'member';
		$tab_member_arr['text'] = 'メンバー';
		$tab_member_arr['active'] = ($group === 'member') ? true : false;

		$tab_data_arr['url'] = URI_BASE . 'uc/' . $validated_community_id . '/data';
		$tab_data_arr['group'] = 'data';
		$tab_data_arr['text'] = ($authority_arr['member']) ? 'データ' : '参加';
		$tab_data_arr['active'] = ($group === 'data') ? true : false;

		$tab_notification_arr['url'] = URI_BASE . 'uc/' . $validated_community_id . '/notification';
		$tab_notification_arr['group'] = 'notification';
		$tab_notification_arr['text'] = '通知';
		$tab_notification_arr['active'] = ($group === 'notification') ? true : false;

		$tab_config_arr['url'] = URI_BASE . 'uc/' . $validated_community_id . '/config';
		$tab_config_arr['group'] = 'config';
		$tab_config_arr['text'] = '設定';
		$tab_config_arr['active'] = ($group === 'config') ? true : false;

		$tab_help_arr['url'] = URI_BASE . 'uc/' . $validated_community_id . '/help';
		$tab_help_arr['group'] = 'help';
		$tab_help_arr['text'] = null;
		$tab_help_arr['active'] = ($group === 'help') ? true : false;
		// $tab_help_arr['meta_title'] = $db_community_arr['name'] . ' - ヘルプ';
		// $tab_help_arr['meta_keywords'] = $db_community_arr['name'] . ',ヘルプ';
		// $tab_help_arr['meta_description'] = $db_community_arr['name'] . ' ヘルプ';


		// --------------------------------------------------
		//   content_data設定　Javascriptから参照するオブジェクト
		// --------------------------------------------------

		$contents_data['initial_load']['group'] = $group;
		$contents_data['initial_load']['content'] = $content;

		$contents_data['opened_content']['bbs'] = 'index';
		$contents_data['opened_content']['member'] = 'index';
		$contents_data['opened_content']['data'] = 'index';
		$contents_data['opened_content']['notification'] = 'index';
		$contents_data['opened_content']['config'] = 'index';
		$contents_data['opened_content']['help'] = 'index';
		$contents_data['opened_content'][$group] = $content;

		$contents_data['bbs_index']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/bbs';
		$contents_data['member_index']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/member';
		$contents_data['data_index']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/data';
		$contents_data['notification_index']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/notification';

		$contents_data['config_index']['state'] = ['group' => 'config', 'content' => 'index'];
		$contents_data['config_index']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/config';
		$contents_data['config_index']['meta_title'] = $db_community_arr['name'] . ' - プロフィール設定';
		$contents_data['config_index']['meta_keywords'] = $db_community_arr['name'] . ',プロフィール設定';
		$contents_data['config_index']['meta_description'] = 'プロフィール設定ページ。';

		$contents_data['config_notification']['state'] = ['group' => 'config', 'content' => 'notification'];
		$contents_data['config_notification']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/config/notification';
		$contents_data['config_notification']['meta_title'] = $db_community_arr['name'] . ' - 通知設定';
		$contents_data['config_notification']['meta_keywords'] = $db_community_arr['name'] . ',通知設定';
		$contents_data['config_notification']['meta_description'] = '通知設定ページ。';

		$contents_data['config_basic']['state'] = ['group' => 'config', 'content' => 'basic'];
		$contents_data['config_basic']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/config/basic';
		$contents_data['config_basic']['meta_title'] = $db_community_arr['name'] . ' - コミュニティ基本設定';
		$contents_data['config_basic']['meta_keywords'] = $db_community_arr['name'] . ',コミュニティ基本設定';
		$contents_data['config_basic']['meta_description'] = 'コミュニティ基本設定ページ。';

		$contents_data['config_community']['state'] = ['group' => 'config', 'content' => 'community'];
		$contents_data['config_community']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/config/community';
		$contents_data['config_community']['meta_title'] = $db_community_arr['name'] . ' - コミュニティ追加設定';
		$contents_data['config_community']['meta_keywords'] = $db_community_arr['name'] . ',コミュニティ追加設定';
		$contents_data['config_community']['meta_description'] = 'コミュニティ追加設定ページ。';

		$contents_data['config_authority_read']['state'] = ['group' => 'config', 'content' => 'authority_read'];
		$contents_data['config_authority_read']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/config/authority_read';
		$contents_data['config_authority_read']['meta_title'] = $db_community_arr['name'] . ' - コミュニティ閲覧権限';
		$contents_data['config_authority_read']['meta_keywords'] = $db_community_arr['name'] . ',コミュニティ閲覧権限';
		$contents_data['config_authority_read']['meta_description'] = 'コミュニティ閲覧権限設定ページ。';

		$contents_data['config_authority_operate']['state'] = ['group' => 'config', 'content' => 'authority_operate'];
		$contents_data['config_authority_operate']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/config/authority_operate';
		$contents_data['config_authority_operate']['meta_title'] = $db_community_arr['name'] . ' - コミュニティ操作権限';
		$contents_data['config_authority_operate']['meta_keywords'] = $db_community_arr['name'] . ',コミュニティ操作権限';
		$contents_data['config_authority_operate']['meta_description'] = 'コミュニティ操作権限設定ページ。';

		$contents_data['config_delete']['state'] = ['group' => 'config', 'content' => 'delete'];
		$contents_data['config_delete']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/config/delete';
		$contents_data['config_delete']['meta_title'] = $db_community_arr['name'] . ' - コミュニティ削除';
		$contents_data['config_delete']['meta_keywords'] = $db_community_arr['name'] . ',コミュニティ削除';
		$contents_data['config_delete']['meta_description'] = 'コミュニティ削除ページ。';

		$contents_data['help_index']['state'] = ['group' => 'help', 'content' => 'index'];
		$contents_data['help_index']['url'] = URI_BASE . 'uc/' . $validated_community_id . '/help';
		$contents_data['help_index']['meta_title'] = $db_community_arr['name'] . ' - ヘルプ';
		$contents_data['help_index']['meta_keywords'] = $db_community_arr['name'] . ',ヘルプ';
		$contents_data['help_index']['meta_description'] = 'コミュニティについて';



		// --------------------------------------------------
		//   コンテンツ読み込み
		// --------------------------------------------------

		$code_announcement = $code_bbs_thread_list = $code_bbs = $code_member = $code_data = $code_notification = $code_config_profile = $code_config_nofitication = $code_config_basic = $code_config_community = $code_config_authority_read = $code_config_authority_operate = $code_config_delete = $code_help_menu = $code_help = null;


		// ---------------------------------------------
		//   告知 ＆ 掲示板
		// ---------------------------------------------

		if ($group === 'bbs')
		{

			// --------------------------------------------------
			//   告知　閲覧権限のチェックは関数内に含まれている
			// --------------------------------------------------

			$temp_arr = array(
				'community_no' => $community_no,
				'page' => 1
			);

			$code_announcement = $original_code_co->announcement($temp_arr)['code'];



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
			//    スレッド一覧
			// --------------------------------------------------

			$original_code_bbs->set_community_no($db_community_arr['community_no']);
			$original_code_bbs->set_db_community_arr($db_community_arr);
			$original_code_bbs->set_authority_arr($authority_arr);
			$original_code_bbs->set_type('uc');
			$original_code_bbs->set_page(1);
			$code_bbs_thread_list = $original_code_bbs->get_code_thread_list_uc();


			// ---------------------------------------------
			//    掲示板　個別
			// ---------------------------------------------

			if ($group === 'bbs' and $bbs_id)
			{

				// ----------------------------------------
				//    コード
				// ----------------------------------------

				$temp_arr = array(
					'type' => 'uc',
					'community_no' => $community_no,
					'bbs_id' => $bbs_id,
					'page_comment' => $bbs_page_comment
				);

				$temp_arr = $original_code_bbs->get_code_bbs_individual_uc($temp_arr);

				$code_bbs = $temp_arr['code'];
				$contents_data['bbs_index']['state'] = $temp_arr['state'];
				$contents_data['bbs_index']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['bbs_index']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['bbs_index']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['bbs_index']['meta_description'] = $temp_arr['meta_description'];

			}

			// ---------------------------------------------
			//    掲示板　まとめて
			// ---------------------------------------------

			else
			{

				// --------------------------------------------------
				//    ログインユーザー情報
				// --------------------------------------------------

				$login_profile_data_arr = $original_func_co->login_profile_data($db_community_arr);


				// ----------------------------------------
				//    コード
				// ----------------------------------------

				$original_code_bbs->set_login_profile_data_arr_uc($login_profile_data_arr);
				$original_code_bbs->set_datetime_now($datetime_now);
				$original_code_bbs->set_page($bbs_page);
				$temp_arr = $original_code_bbs->get_code_bbs_uc();

				$code_bbs = $temp_arr['code'];
				$contents_data['bbs_index']['state'] = $temp_arr['state'];
				$contents_data['bbs_index']['url'] = $temp_arr['url'];
				$meta_title = $contents_data['bbs_index']['meta_title'] = $temp_arr['meta_title'];
				$meta_keywords = $contents_data['bbs_index']['meta_keywords'] = $temp_arr['meta_keywords'];
				$meta_description = $contents_data['bbs_index']['meta_description'] = $temp_arr['meta_description'];

			}

		}


		// ---------------------------------------------
		//   メンバー
		// ---------------------------------------------

		else if ($group === 'member')
		{

			$page = (ctype_digit($param_3)) ? (int) $param_3 : 1;


			// ----------------------------------------
			//    コード
			// ----------------------------------------

			$temp_arr = array(
				'first_load' => true,
				'community_no' => $community_no,
				'type' => 'all',
				'page' => $page,
			);

			$temp_arr = $original_code_co->member($temp_arr);

			$code_member = $temp_arr['code'];
			$contents_data['member_index']['state'] = $temp_arr['state'];
			$contents_data['member_index']['url'] = $temp_arr['url'];
			$meta_title = $contents_data['member_index']['meta_title'] = $temp_arr['meta_title'];
			$meta_keywords = $contents_data['member_index']['meta_keywords'] = $temp_arr['meta_keywords'];
			$meta_description = $contents_data['member_index']['meta_description'] = $temp_arr['meta_description'];


			// --------------------------------------------------
			//   メンバー登録申請中の人数
			// --------------------------------------------------

			if ($authority_arr['operate_member'] and $db_community_arr['provisional'])
			{
				$provisional_member_arr = unserialize($db_community_arr['provisional']);
				$provisional_member_total = count($provisional_member_arr);
			}
			else
			{
				$provisional_member_total = 0;
			}

		}

		// ---------------------------------------------
		//   データ
		// ---------------------------------------------

		else if ($group === 'data')
		{

			$temp_arr = array(
				'community_no' => $community_no
			);

			$original_code_co = new Original\Code\Co();
			$temp_arr = $original_code_co->data($temp_arr);

			$code_data = $temp_arr['code'];
			$contents_data['data_index']['state'] = $temp_arr['state'];
			$contents_data['data_index']['url'] = $temp_arr['url'];
			$meta_title = $contents_data['data_index']['meta_title'] = $temp_arr['meta_title'];
			$meta_keywords = $contents_data['data_index']['meta_keywords'] = $temp_arr['meta_keywords'];
			$meta_description = $contents_data['data_index']['meta_description'] = $temp_arr['meta_description'];

		}


		// ---------------------------------------------
		//   通知
		// ---------------------------------------------

		else if ($group === 'notification')
		{

			$temp_arr = array(
				'community_no' => $community_no
			);

			$original_code_co = new Original\Code\Co();
			$temp_arr = $original_code_co->notification($temp_arr);

			$code_notification = $temp_arr['code'];
			$contents_data['notification_index']['state'] = $temp_arr['state'];
			$contents_data['notification_index']['url'] = $temp_arr['url'];
			$meta_title = $contents_data['notification_index']['meta_title'] = $temp_arr['meta_title'];
			$meta_keywords = $contents_data['notification_index']['meta_keywords'] = $temp_arr['meta_keywords'];
			$meta_description = $contents_data['notification_index']['meta_description'] = $temp_arr['meta_description'];

		}


		// ---------------------------------------------
		//   設定
		// ---------------------------------------------

		else if ($group === 'config')
		{

			$content = ($param_3) ? $param_3 : 'index';

// \Debug::dump($group, $content);
			$temp_arr = array(
				'community_no' => $community_no
			);

			$original_code_co = new Original\Code\Co();
			$temp_arr = $original_code_co->config($temp_arr);

			$code_config_profile = $temp_arr['code_profile'];
			$code_config_nofitication = $temp_arr['code_nofitication'];
			$code_config_basic = $temp_arr['code_basic'];
			$code_config_community = $temp_arr['code_community'];
			$code_config_authority_read = $temp_arr['code_authority_read'];
			$code_config_authority_operate = $temp_arr['code_authority_operate'];
			$code_config_delete = $temp_arr['code_delete'];

			$meta_title = $contents_data[$group . '_' . $content]['meta_title'];
			$meta_keywords = $contents_data[$group . '_' . $content]['meta_keywords'];
			$meta_description = $contents_data[$group . '_' . $content]['meta_description'];

			// $contents_data['config_index']['state'] = $temp_arr['state'];
			// $contents_data['config_index']['url'] = $temp_arr['url'];
			// $meta_title = $contents_data['config_index']['meta_title'] = $temp_arr['meta_title'];
			// $meta_keywords = $contents_data['config_index']['meta_keywords'] = $temp_arr['meta_keywords'];
			// $meta_description = $contents_data['config_index']['meta_description'] = $temp_arr['meta_description'];

//	$contents_data['config_notification']['state']
			// \Debug::dump($temp_arr);
			// exit();


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
				'list' => 'community',
				'content' => 'community_about',
			);

			$temp_arr = $original_code_help->code_help($temp_arr);
			$code_help_menu = $temp_arr['code_list'];
			$code_help = $temp_arr['code_content'];
			$meta_title = $contents_data['help_index']['meta_title'];
			$meta_keywords = $contents_data['help_index']['meta_keywords'];
			$meta_description = $contents_data['help_index']['meta_description'];


			// ----------------------------------------
			//   Meta ＆ タブ
			//   state（戻るボタンを押したときに使用するJS関数）
			// ----------------------------------------

			// $tab_help_arr['state'] = '{ "group": "help", "content": "index" }';
			// $meta_title = $tab_help_arr['meta_title'];
			// $meta_keywords = $tab_help_arr['meta_keywords'];
			// $meta_description = $tab_help_arr['meta_description'];
			//\Debug::dump($code_help);

		}



		// --------------------------------------------------
		//    トップの表示・非表示
		// --------------------------------------------------

		//$show_top = true;
		//$tab_class_active_top = true;
		//$tab_class_active_member = false;
		// $tab_class_active_arr = array('top' => true, 'member' => false, 'about_community' => false);
		//
		// if (empty($code_announcement) and empty($view_content_create_bbs_thread) and empty($view_content_bbs))
		// {
		// 	//$show_top = false;
		//
		// 	if ($authority_arr['read_member'])
		// 	{
		// 		//$show_member = false;
		// 		$tab_class_active_arr = array('top' => false, 'member' => true, 'about_community' => false);
		// 	}
		// 	else
		// 	{
		// 		$tab_class_active_arr = array('top' => false, 'member' => false, 'about_community' => true);
		// 	}
		//
		// }


		// --------------------------------------------------
		//   ソーシャルボタン
		// --------------------------------------------------

		$view_social = ($this->app_mode) ? null : View::forge('common/social_view');


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
			//Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

			if (isset($db_community_arr['top_image']))
			{
				echo 'top_image';
				var_dump(unserialize($db_community_arr['top_image']));
			}

		}



		// --------------------------------------------------
		//    コンテンツ
		// --------------------------------------------------

		$view_main = View::forge('uc_view');

		$view_main->set_safe('group', $group);
		$view_main->set_safe('content', $content);
		$view_main->set('community_no', $db_community_arr['community_no']);
		$view_main->set('datetime_now', $datetime_now);
		$view_main->set_safe('authority_arr', $authority_arr);



		// ----- コード -----

		$view_main->set_safe('code_announcement', $code_announcement);
		$view_main->set_safe('code_bbs_thread_list', $code_bbs_thread_list);
		$view_main->set_safe('code_bbs', $code_bbs);
		$view_main->set_safe('code_member', $code_member);
		$view_main->set_safe('code_data', $code_data);
		$view_main->set_safe('code_notification', $code_notification);

		$view_main->set_safe('code_config_profile', $code_config_profile);
		$view_main->set_safe('code_config_nofitication', $code_config_nofitication);
		$view_main->set_safe('code_config_basic', $code_config_basic);
		$view_main->set_safe('code_config_community', $code_config_community);
		$view_main->set_safe('code_config_authority_read', $code_config_authority_read);
		$view_main->set_safe('code_config_authority_operate', $code_config_authority_operate);
		$view_main->set_safe('code_config_delete', $code_config_delete);

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
			array_push($this->data_meta['js_arr'], Config::get('js_basic'), Config::get('js_common'), 'uc.js', 'bbs.js');
		}
		else
		{
			array_push($this->data_meta['css_arr'], Config::get('css_basic_min'), 'style.min.css', 'new.min.css');
			array_push($this->data_meta['js_arr'], Config::get('js_basic_min'), Config::get('js_common_min'), 'uc.min.js', 'bbs.min.js');
		}


		// ---------------------------------------------
		//    ヘッダー
		// ---------------------------------------------
//\Debug::dump($authority_arr);
		$temp_arr = [];

		if ($authority_arr['read_announcement'] or $authority_arr['read_bbs']) array_push($temp_arr, $tab_bbs_arr);
		if ($authority_arr['read_member']) array_push($temp_arr, $tab_member_arr);
		array_push($temp_arr, $tab_data_arr);
		if ($authority_arr['operate_send_all_mail']) array_push($temp_arr, $tab_notification_arr);
		if (USER_NO and $authority_arr['member']) array_push($temp_arr, $tab_config_arr);
		array_push($temp_arr, $tab_help_arr);

		$view_header = View::forge('header_ver2_view');
		$view_header->set('game_no_arr', $community_game_no_arr);
		$view_header->set('tab_arr', $temp_arr);
		$view_header->set('community_no', $community_no);
		$view_header->set('community_id', $validated_community_id);
		$view_header->set('community_name', $community_name);



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
			'community_no' => $community_no,
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
