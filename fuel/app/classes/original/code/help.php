<?php

namespace Original\Code;

class Help
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// ------------------------------
	//   インスタンス
	// ------------------------------

	// private $model_common = null;
	// private $model_game = null;
	// private $model_gc = null;
	// private $original_validation_common = null;
	// private $original_common_date = null;



	// --------------------------------------------------
	//   コンストラクター
	// --------------------------------------------------

	public function __construct()
	{

		// ------------------------------
		//   インスタンス作成
		// ------------------------------

	}




	/**
	* ヘルプ
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function code_help($arr)
	{

		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();

		$val->add_field('first_load', 'first_load', 'match_value[1]');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');
		$val->add('list', 'List')->add_rule('match_pattern', '/^(top|login|game|community|wiki|player|app)$/');
		$val->add('content', 'Content')->add_rule('match_pattern', '/^(top_about|top_notification|top_user_terms|top_privacy_policy|top_special|login_about|login_account_create|login|game_about|game_bbs|game_recruitment|game_recruitment_sample|game_config_profile|game_config_id|game_register|community_about|community_join|community_notification|community_config_profile|community_create|community_member|community_config_basic|community_config_community|community_config_authority|community_config_delete|wiki_about|wiki_create|wiki_config|wiki_edit_basic|wiki_edit_ad|wiki_edit_movie|wiki_edit_twitter|player_about|player_profile|player_community|player_config_basic|player_config_notification|player_config_ad|player_config_wiki|player_notification_browser|app_about|app_how_to_use)$/');

		if ($val->run($arr))
		{
			$validated_first_load = ($val->validated('first_load')) ? 1: null;
			$validated_page = $val->validated('page');
			$validated_list = ($val->validated('list')) ? $val->validated('list') : null;
			$validated_content = ($val->validated('content')) ? $val->validated('content') : null;
		}
		else
		{
			$error_message = '';
			if (count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value)
				{
					$error_message .= $value;
				}
			}
			//echo $error_message;
			throw new \Exception($error_message);
		}


		// --------------------------------------------------
		//   一覧
		// --------------------------------------------------

		if($validated_list)
		{
			$view_list = \View::forge('help/help_list_view');
			$view_list->set('page', $validated_page);
			$view_list->set('list', $validated_list);
			$code_list = $view_list->render();
		}


		// --------------------------------------------------
		//   コンテンツ
		// --------------------------------------------------

		if ($validated_content)
		{
			$view_content = \View::forge('help/help_' . $validated_content . '_view');
			$code_content = $view_content->render();
		}


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code_arr['code_all'] = null;
		$code_arr['code_list'] = null;
		$code_arr['code_content'] = null;

		if ($validated_first_load)
		{
			$view = \View::forge('parts/content_view');
			$view->set('id_name', 'help');
			$view->set_safe('content1_arr', array($code_list));
			$view->set_safe('content2_arr', array($code_content));
			$code_all = $view->render();

			$code_arr['code_all'] = $code_all;
		}
		else
		{
			if (isset($code_list))
			{
				$code_arr['code_list'] = $code_list;
			}

			if (isset($code_content))
			{
				$code_arr['code_content'] = $code_content;
			}
		}






		//$test = true;

		if (isset($test))
		{
			if (isset($validated_first_load)) echo '$validated_first_load = ' . $validated_first_load . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';
			if (isset($validated_list)) echo '$validated_list = ' . $validated_list . '<br>';
			if (isset($validated_content)) echo '$validated_content = ' . $validated_content . '<br>';

			if (isset($code_list)) echo $code_list;
			if (isset($code_content)) echo $code_content;
			if (isset($code_all)) echo $code_all;
		}




		return $code_arr;

	}




}
