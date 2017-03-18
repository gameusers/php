<?php

/*
 * 必要なデータ
 * string / $uri_base
 * array / $profile_arr /login_profile_data_arrなど
 * integer / $online_limit
 *
 * オプション
 * boolean / $app_mode / アプリモードの場合、リンク変更
 *
 * string / $datetime_now / アクセス時間算出のための日時
 * string / $regi_date / サムネイル画像がない場合に画像を決定するための日時
 * string / $handle_name
 * boolean / $anonymity / ななしにする場合
 * string / $good_type / Goodボタンが必要な場合
 * integer / $good_no
 * integer / $good
 * boolean / $link_force_off / リンクを表示しない
 *
 * boolean / $add_explanation / 説明文を追加する場合
 * boolean / $add_button_member_withdraw / コミュニティから退会させる場合
 * boolean / $add_button_member_ban / コミュニティからBANさせる場合
 * boolean / $add_button_member_moderator / コミュニティでモデレーターを認定する場合
 * boolean / $add_button_member_moderator_withdraw / コミュニティでモデレーターを解除する場合
 * boolean / $add_button_member_provisional / コミュニティメンバーの仮申請を承認する場合
 * boolean / $add_button_member_lift_ban / コミュニティメンバーのBANを解除する場合
 */



// --------------------------------------------------
//   初期処理
// --------------------------------------------------

$thumbnail = null;
$status = null;
$link = false;
$access_date = null;
$level_id = null;
$personal_box_id = null;
$online_offline = 'on';
$admin = false;
$s = (AGENT_TYPE) ? '_s' : null;


// --------------------------------------------------
//   匿名
// --------------------------------------------------

if (isset($anonymity))
{
	$handle_name = 'ななしさん';
	$status = '774';
	$online_offline = null;
}


// --------------------------------------------------
//   プロフィール
// --------------------------------------------------

else if (isset($profile_arr['profile_no']))
{

	if (isset($profile_arr['on_off']))
	{
		$link = ($profile_arr['open_profile']) ? true : false;
		$level_id = 'level_profile_' . $profile_arr['profile_no'];
		$status = $profile_arr['status'];
	}
	else
	{
		$handle_name = '削除済みユーザー';
		$status = 'Deleted';
		$online_offline = null;
		unset($profile_arr['thumbnail'], $profile_arr['explanation']);
		//var_dump($profile_arr);
	}

	$personal_box_id = 'profile_' . $profile_arr['profile_no'];

}


// --------------------------------------------------
//   メインプロフィール
// --------------------------------------------------

else if (isset($profile_arr['user_no']))
{

	if (isset($profile_arr['on_off']))
	{
		$link = true;
		$level_id = 'level_user_' . $profile_arr['user_no'];
		$status = $profile_arr['status'];
	}
	else
	{
		$handle_name = '削除済みユーザー';
		$status = 'Deleted';
		$online_offline = 'off';
		unset($profile_arr['thumbnail'], $profile_arr['explanation']);
	}
	$personal_box_id = 'user_' . $profile_arr['user_no'];


	// 管理者の場合、プロフィールへのリンクを掲載しない
	if (in_array($profile_arr['user_id'], Config::get('admin_id_arr')))
	{
    $admin = true;
    $online_offline = null;
		$link_force_off = true;
		$status = '<span class="admin_status">' . $status . '</span>';
	}

}


// --------------------------------------------------
//   一般ユーザー
// --------------------------------------------------

else if (isset($handle_name))
{
	$status = '一般ユーザー';
	$online_offline = null;
}


// --------------------------------------------------
//   ななしさん
// --------------------------------------------------

else
{
	$handle_name = 'ななしさん';
	$status = '774';
	$online_offline = null;
}



// サムネイルがない場合の画像
if (isset($regi_date))
{
	$datetime_thumbnail_none = new DateTime($regi_date);
	$thumbnail_none_second = $datetime_thumbnail_none->format('s');
}




// --------------------------------------------------
//   プロフィールへのリンク用
// --------------------------------------------------

$link_profile_no = (isset($profile_arr['profile_no'])) ? $profile_arr['profile_no'] : null;


?>

<div class="personal_box" id="personal_box_<?=$personal_box_id?>">

  <div class="box">

    <div class="left">
<?php if ($link and empty($link_force_off) and isset($level_id)): ?>
      <a href="<?=URI_BASE . 'pl/' . $profile_arr['user_id']?>">
<?php endif; ?>
<?php if (isset($regi_date) and (isset($anonymity) or empty($profile_arr['thumbnail']))): ?>
        <img class="img-rounded" src="<?=URI_BASE?>assets/img/common/thumbnail_none_<?=$thumbnail_none_second?>.png" width="48px" height="48px">
<?php elseif (isset($anonymity) or empty($profile_arr['thumbnail'])): ?>
        <img class="img-rounded" src="<?=URI_BASE?>assets/img/common/thumbnail_none.png" width="48px" height="48px">
<?php elseif(isset($profile_arr['profile_no'])): ?>
        <img class="img-rounded" src="<?=URI_BASE?>assets/img/profile/<?=$profile_arr['profile_no']?>/thumbnail.jpg?<?php echo strtotime($profile_arr['renewal_date']); ?>" width="48px" height="48px">
<?php elseif(isset($profile_arr['user_no'])): ?>
        <img class="img-rounded" src="<?=URI_BASE?>assets/img/user/<?=$profile_arr['user_no']?>/thumbnail.jpg?<?php echo strtotime($profile_arr['renewal_date']); ?>" width="48px" height="48px">
<?php endif; ?>
<?php if ($link and empty($link_force_off) and isset($level_id)): ?>
      </a>
<?php endif; ?>
    </div>

    <div class="right">
<?php if ($link and empty($link_force_off)): ?>
      <div class="handle_name">
        <a href="<?=URI_BASE . 'pl/' . $profile_arr['user_id']?>"><?=$profile_arr['handle_name']?><?php if (AGENT_TYPE !== 'smartphone') echo ' - Lv.<span id="' . $level_id . '">' . $profile_arr['level'] . '</span>'?></a>
      </div>
<?php elseif (isset($level_id)): ?>
      <div class="handle_name"><?=$profile_arr['handle_name']?><?php if (AGENT_TYPE !== 'smartphone' and ! $admin) echo ' - Lv.<span id="' . $level_id . '">' . $profile_arr['level'] . '</span>'?></div>
<?php else: ?>
      <div class="handle_name"><?=$handle_name?></div>
<?php endif; ?>
      <div class="status"><span class="glyphicon glyphicon-fire"></span> <?=$status?></div>
      <div class="info">
<?php

// --------------------------------------------------
//   オンライン・オフライン
// --------------------------------------------------

if ($online_offline == 'on' and isset($profile_arr['access_date']))
{

	if (isset($datetime_now))
	{
		$datetime_now = new DateTime($datetime_now);
	}
	else
	{
		$datetime_now = new DateTime();
	}
	$datetime_access = new DateTime($profile_arr['access_date']);
	$interval = $datetime_now->diff($datetime_access);

	$datetime_safe = new DateTime("-{$online_limit}hours");

	if ($datetime_access < $datetime_safe)
	{
		echo '        <div class="offline">OFFLINE</div>' . "\n";
	}
	else
	{
		if ($interval->format('%d') >= 1)
		{
			$interval_time = $interval->format('%d 日前');
		}
		else if ($interval->format('%h') >= 1)
		{
			$interval_time = $interval->format('%h 時間前');
		}
		else if ($interval->format('%i') >= 1)
		{
			$interval_time = $interval->format('%i 分前');
		}
		else
		{
			$interval_time = $interval->format('%s 秒前');
		}
		echo '        <div class="online">' . $interval_time . ' <span class="glyphicon glyphicon-ok"></span></div>' . "\n";
	}
}
else if ($online_offline == 'off')
{
	echo '        <div class="offline">OFFLINE</div>' . "\n";
}


// --------------------------------------------------
//   Goodボタン
// --------------------------------------------------

if (isset($good_type, $good_no, $good))
{
	// onclick="plusGood('recruitment', 'xtklbe1caeoxiw23')"  id="good_recruitment_xtklbe1caeoxiw23" この形にするため　文字列を''で囲むため
	$good_no_id = ($good_type == 'recruitment' or $good_type == 'recruitment_reply') ? "'" . $good_no . "'" : $good_no;

	echo '          <div class="community_personal_event_button"><button type="submit" class="btn btn-success btn-xs ladda-button" data-style="slide-right" data-size="xs" id="good_button_' . $good_type . '_' . $good_no . '" data-toggle="popover" onclick="plusGood(this, \'' . $good_type . '\', ' . $good_no_id . ')"><span class="glyphicon glyphicon-thumbs-up"></span> <span class="good_count" id="good_' . $good_type . '_' . $good_no . '">' . $good . '</span></button></div>';
}



// --------------------------------------------------
//   退会ボタン　引数設定
// --------------------------------------------------

if (isset($profile_arr['profile_no']))
{
	$withdraw_type = 'profile';
	$withdraw_no = $profile_arr['profile_no'];
}
else if (isset($profile_arr['user_no']))
{
	$withdraw_type = 'user';
	$withdraw_no = $profile_arr['user_no'];
}
else
{
	$withdraw_type = '';
	$withdraw_no = '';
}


// --------------------------------------------------
//   退会・BANボタン
// --------------------------------------------------
// \Debug::dump($add_button_member_withdraw);
if (isset($add_button_member_withdraw))
{
	echo '          <div class="community_personal_event_button"><button type="submit" class="btn btn-info btn-xs ladda-button" data-style="slide-right" data-size="xs" id="withdraw_' . $personal_box_id . '" onclick="GAMEUSERS.uc.withdrawBanCommunityMember(this, ' . $community_no . ', \'' . $withdraw_type . '\', ' . $withdraw_no . ', null)"><span class="glyphicon glyphicon-remove"></span> 退会</button></div>' . "\n";
	echo '          <div class="community_personal_event_button"><button type="submit" class="btn btn-info btn-xs ladda-button" data-style="slide-right" data-size="xs" id="ban_' . $personal_box_id . '" onclick="GAMEUSERS.uc.withdrawBanCommunityMember(this, ' . $community_no . ', \'' . $withdraw_type . '\', ' . $withdraw_no . ', true)"><span class="glyphicon glyphicon-trash"></span> BAN</button></div>' . "\n";
}


// --------------------------------------------------
//   モデレーター認定・解除ボタン
// --------------------------------------------------

if (isset($add_button_member_moderator))
{
	echo '          <div class="community_personal_event_button"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" id="moderator_' . $personal_box_id . '" onclick="GAMEUSERS.uc.setModeratorMember(this, ' . $community_no . ', \'' . $withdraw_type . '\', ' . $withdraw_no . ', 1)"><span class="glyphicon glyphicon-book"></span> モデレーター認定</button></div>' . "\n";
}
else if (isset($add_button_member_moderator_withdraw))
{
	echo '          <div class="community_personal_event_button"><button type="submit" class="btn btn-danger btn-xs ladda-button" data-style="slide-right" data-size="xs" id="moderator_' . $personal_box_id . '" onclick="GAMEUSERS.uc.setModeratorMember(this, ' . $community_no . ', \'' . $withdraw_type . '\', ' . $withdraw_no . ', 0)"><span class="glyphicon glyphicon-remove"></span> モデレーター解除</button></div>' . "\n";
}


// --------------------------------------------------
//   メンバー承認ボタン
// --------------------------------------------------

if (isset($add_button_member_provisional))
{
	echo '          <div class="community_personal_event_button"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" id="approval_' . $personal_box_id . '_plus" onclick="GAMEUSERS.uc.approvalMember(this, ' . $community_no . ', \'' . $withdraw_type . '\', ' . $withdraw_no . ', 1)"><span class="glyphicon glyphicon-plus"></span> 承認</button></div>' . "\n";
	echo '          <div class="community_personal_event_button"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" id="approval_' . $personal_box_id . '_minus" onclick="GAMEUSERS.uc.approvalMember(this, ' . $community_no . ', \'' . $withdraw_type . '\', ' . $withdraw_no . ', 0)"><span class="glyphicon glyphicon-minus"></span> 非承認</button></div>' . "\n";
}


// --------------------------------------------------
//   Ban解除ボタン
// --------------------------------------------------

if (isset($add_button_member_lift_ban))
{
	echo '          <div class="community_personal_event_button"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" id="lift_ban_' . $personal_box_id . '" onclick="GAMEUSERS.uc.liftBanMember(this, ' . $community_no . ', \'' . $withdraw_type . '\', ' . $withdraw_no . ')"><span class="glyphicon glyphicon-remove"></span> BAN解除</button></div>' . "\n";
}

?>

      </div>

    </div>

  </div>

<?php

// --------------------------------------------------
//   コミュニティ　メンバー　プロフィールコメント表示
// --------------------------------------------------

if (isset($add_explanation, $profile_arr['explanation']))
{
	$original_common_convert = new Original\Common\Convert();
	echo '          <p class="personal_box_explanation element_hidden" id="personal_box_explanation">' . "\n";
	echo nl2br($original_common_convert->auto_linker($profile_arr['explanation'])) . "\n";
	echo '          </p>' . "\n";
}

?>

</div>
