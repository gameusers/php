<?php

namespace Original\Func;

class Co
{
	
	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------
	
	// PC・スマホ・タブレット
	public $agent_type = null;
	
	// ホスト
	public $host = null;
	
	// ユーザーエージェント
	public $user_agent = null;
	
	// ユーザーNo
	public $user_no = null;
	
	// 言語
	public $language = null;
	
	// URI
	public $uri_base = null;
	public $uri_current = null;
	
	// アプリモード
	public $app_mode = null;
	
	
	
	/**
	* 権限
	*
	* @param array $db_community_arr コミュニティ情報
	* @return string 
	*/
	public function authority($db_community_arr)
	{
		
		
		// --------------------------------------------------
		//   メンバー・コンフィグ
		// --------------------------------------------------
		
		$member_arr = unserialize($db_community_arr['member']);
		$config_arr = unserialize($db_community_arr['config']);
		
		
		// --------------------------------------------------
		//   管理者・モデレーター User No取得
		// --------------------------------------------------
		
		$administrator = null;
		$moderator_arr = array();
		
		foreach ($member_arr as $key => $value) {
			if ($value['administrator']) $administrator = $key;
			if ($value['moderator']) array_push($moderator_arr, $key);
		}
		
		//echo "administrator, moderator_arr";
		//var_dump($administrator, $moderator_arr);
		
		
		
		// --------------------------------------------------
		//   権限
		// --------------------------------------------------
		
		$authority_arr = array();
		
		
		// ------------------------------
		//    管理者・モデレーター・メンバー
		// ------------------------------
		
		$authority_arr['administrator'] = ($administrator == $this->user_no) ? true : false;
		$authority_arr['moderator'] = (in_array($this->user_no, $moderator_arr)) ? true : false;
		$authority_arr['member'] = (array_key_exists($this->user_no, $member_arr)) ? true : false;
		
		
		// ------------------------------
		//    メール設定
		// ------------------------------
		
		$authority_arr['mail_all'] = ($authority_arr['member']) ? $member_arr[$this->user_no]['mail_all'] : false;
		
		
		// ------------------------------
		//    閲覧権限　告知
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['read_announcement'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['read_announcement']))
		{
			$authority_arr['read_announcement'] = true;
		}
		else if ($authority_arr['member'] and in_array(2, $config_arr['read_announcement']))
		{
			$authority_arr['read_announcement'] = true;
		}
		else if (in_array(1, $config_arr['read_announcement']))
		{
			$authority_arr['read_announcement'] = true;
		}
		else
		{
			$authority_arr['read_announcement'] = false;
		}
		
		
		// ------------------------------
		//    閲覧権限　BBS
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['read_bbs'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['read_bbs']))
		{
			$authority_arr['read_bbs'] = true;
		}
		else if ($authority_arr['member'] and in_array(2, $config_arr['read_bbs']))
		{
			$authority_arr['read_bbs'] = true;
		}
		else if (in_array(1, $config_arr['read_bbs']))
		{
			$authority_arr['read_bbs'] = true;
		}
		else
		{
			$authority_arr['read_bbs'] = false;
		}
		
		
		// ------------------------------
		//    閲覧権限　メンバー
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['read_member'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['read_member']))
		{
			$authority_arr['read_member'] = true;
		}
		else if ($authority_arr['member'] and in_array(2, $config_arr['read_member']))
		{
			$authority_arr['read_member'] = true;
		}
		else if (in_array(1, $config_arr['read_member']))
		{
			$authority_arr['read_member'] = true;
		}
		else
		{
			$authority_arr['read_member'] = false;
		}
		
		
		// ------------------------------
		//    閲覧権限　コミュニティ情報　その他の情報
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['read_additional_info'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['read_additional_info']))
		{
			$authority_arr['read_additional_info'] = true;
		}
		else if ($authority_arr['member'] and in_array(2, $config_arr['read_additional_info']))
		{
			$authority_arr['read_additional_info'] = true;
		}
		else if (in_array(1, $config_arr['read_additional_info']))
		{
			$authority_arr['read_additional_info'] = true;
		}
		else
		{
			$authority_arr['read_additional_info'] = false;
		}
		
		
		// ------------------------------
		//    操作権限　告知
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['operate_announcement'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['operate_announcement']))
		{
			$authority_arr['operate_announcement'] = true;
		}
		else
		{
			$authority_arr['operate_announcement'] = false;
		}
		
		
		// ------------------------------
		//    操作権限　BBSスレッド作成
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['operate_bbs_thread'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['operate_bbs_thread']))
		{
			$authority_arr['operate_bbs_thread'] = true;
		}
		else if ($authority_arr['member'] and in_array(2, $config_arr['operate_bbs_thread']))
		{
			$authority_arr['operate_bbs_thread'] = true;
		}
		else if (in_array(1, $config_arr['operate_bbs_thread']))
		{
			$authority_arr['operate_bbs_thread'] = true;
		}
		else
		{
			$authority_arr['operate_bbs_thread'] = false;
		}
		
		// ------------------------------
		//    操作権限　BBSコメント書き込み
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['operate_bbs_comment'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['operate_bbs_comment']))
		{
			$authority_arr['operate_bbs_comment'] = true;
		}
		else if ($authority_arr['member'] and in_array(2, $config_arr['operate_bbs_comment']))
		{
			$authority_arr['operate_bbs_comment'] = true;
		}
		else if (in_array(1, $config_arr['operate_bbs_comment']))
		{
			$authority_arr['operate_bbs_comment'] = true;
		}
		else
		{
			$authority_arr['operate_bbs_comment'] = false;
		}
		
		
		// ------------------------------
		//    操作権限　BBS削除
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['operate_bbs_delete'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['operate_bbs_delete']))
		{
			$authority_arr['operate_bbs_delete'] = true;
		}
		else
		{
			$authority_arr['operate_bbs_delete'] = false;
		}
		
		
		// ------------------------------
		//    操作権限　メンバー承認・退会
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['operate_member'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['operate_member']))
		{
			$authority_arr['operate_member'] = true;
		}
		else
		{
			$authority_arr['operate_member'] = false;
		}
		
		
		// ------------------------------
		//    操作権限　メール一斉送信
		// ------------------------------
		
		if ($administrator == $this->user_no)
		{
			$authority_arr['operate_send_all_mail'] = true;
		}
		else if (in_array($this->user_no, $moderator_arr) and in_array(3, $config_arr['operate_send_all_mail']))
		{
			$authority_arr['operate_send_all_mail'] = true;
		}
		else
		{
			$authority_arr['operate_send_all_mail'] = false;
		}
		
		
		// ------------------------------
		//    操作権限　コミュニティ設定
		// ------------------------------
		
		if ($authority_arr['administrator'])
		{
			$authority_arr['operate_config_community'] = true;
		}
		else if ($authority_arr['moderator'] and in_array(3, $config_arr['operate_config_community']))
		{
			$authority_arr['operate_config_community'] = true;
		}
		else
		{
			$authority_arr['operate_config_community'] = false;
		}
		
		
		return $authority_arr;
		
	}
	
	
	
	/**
	* ログインユーザーのプロフィール取得
	*
	* @param array $db_community_arr コミュニティ情報
	* @return string 
	*/
	public function login_profile_data($db_community_arr)
	{
		
		// --------------------------------------------------
		//   メンバー
		// --------------------------------------------------
		
		$member_arr = unserialize($db_community_arr['member']);
		
		
		// --------------------------------------------------
		//   共通処理　インスタンス作成
		// --------------------------------------------------
		
		$model_user = new \Model_User();
		$model_user->agent_type = $this->agent_type;
		$model_user->user_no = $this->user_no;
		$model_user->language = $this->language;
		$model_user->uri_base = $this->uri_base;
		$model_user->uri_current = $this->uri_current;
		
		
		// --------------------------------------------------
		//    ログインユーザー情報
		// --------------------------------------------------
		
		if ($this->user_no)
		{
			if (isset($member_arr[$this->user_no]['profile_no']))
			{
				$login_profile_no = $member_arr[$this->user_no]['profile_no'];
				$login_profile_data_arr = $model_user->get_profile($login_profile_no);
				if ($login_profile_data_arr === null) $login_profile_data_arr = false;
			}
			else
			{
				$login_user_no = $this->user_no;
				$login_profile_data_arr = $model_user->get_user_data_personal_box($login_user_no, null);
				if ($login_profile_data_arr === null) $login_profile_data_arr = false;
			}
		}
		else
		{
			$login_profile_data_arr = null;
		}
		
		
		return $login_profile_data_arr;
		
	}
	

}