<?php

/*
  必要なデータ

  string / $type / メンバーのタイプ
  integer / $community_no / コミュニティNo
  array / $member_arr / メンバー配列
  array / $config_arr / コンフィグ配列
  array / $users_data_arr / ユーザー配列
  array / $profile_arr / プロフィール配列
  array / $authority_arr / 権限配列
  boolean / $first_load / 最初の読み込み時はtrue
  integer / $provisional_member_total / 参加承認申請を行ったユーザーの人数
  string / $code_pagination / ページャーのコード

  オプション
*/


//\Debug::dump(USER_NO, $member_arr, $authority_arr);
// exit();

?>


<?php if ($first_load): ?>

<div class="member_box" id="panel_member">

  <div class="btn-group margin_bottom_20">
    <button type="button" class="btn btn-default ladda-button active" id="read_member_all" data-style="expand-right" data-spinner-color="#000000" data-page="1" onclick="GAMEUSERS.uc.readMember(this, null, <?=$community_no?>, 'all', 1, 0, 0, 0)">すべて</button>
    <button type="button" class="btn btn-default ladda-button" id="read_member_moderator" data-style="expand-right" data-spinner-color="#000000" data-page="1" onclick="GAMEUSERS.uc.readMember(this, null, <?=$community_no?>, 'moderator', 1, 0, 0, 0)">モデレーター</button>
    <button type="button" class="btn btn-default ladda-button" id="read_member_administrator" data-style="expand-right" data-spinner-color="#000000" data-page="1" onclick="GAMEUSERS.uc.readMember(this, null, <?=$community_no?>, 'administrator', 1, 0, 0, 0)">管理者</button>
<?php if ($authority_arr['operate_member']): ?>
    <button type="button" class="btn btn-default ladda-button" id="read_member_ban" data-style="expand-right" data-spinner-color="#000000" data-page="1" onclick="GAMEUSERS.uc.readMember(this, null, <?=$community_no?>, 'ban', 1, 0)">BAN解除</button>
  <?php if ($config_arr['participation_type'] == 2): ?>
    <button type="button" class="btn btn-default ladda-button" id="read_member_provisional" data-style="expand-right" data-spinner-color="#000000" data-page="1" onclick="GAMEUSERS.uc.readMember(this, null, <?=$community_no?>, 'provisional', 1, 0, 0, 0)">メンバー承認 <span class="badge"><?=$provisional_member_total?></span></button>
  <?php endif; ?>
<?php endif; ?>
  </div>

  <div class="community_member_show_profile_button" id="community_member_show_profile_button" data-state="false"><button type="submit" class="btn btn-warning btn-sm" onclick="GAMEUSERS.uc.showMemberProfile(this, false)"><span class="glyphicon glyphicon-comment"></span> <span id="community_member_show_profile_button_text">プロフィールコメントを表示する</span></button></div>

  <article id="member_box">

<?php endif; ?>


<?php foreach ($member_arr as $key => $value): ?>
    <div class="member_profile_box">
<?php

$temp_arr = $profile_arr[$value['profile_no']] ?? $users_data_arr[$key] ?? null;

$view = \View::forge('parts/personal_box_ver3_view');
$view->set('profile_arr', $temp_arr);
$view->set_safe('online_limit', $config_arr['online_limit']);
$view->set_safe('add_explanation', true);



// ---------------------------------------------
//    管理用ボタン表示
// ----------------------------------------------

if ($type === 'provisional')
{
  $view->set('community_no', $community_no);
  $view->set_safe('add_button_member_provisional', true);
}
else if ($type === 'ban')
{
  $view->set('community_no', $community_no);
  $view->set_safe('add_button_member_lift_ban', true);
}
else if ( ! $value['administrator'])
{

	// ---------------------------------------------
	//    退会・BAN
	// ---------------------------------------------

	if (USER_NO != $key and $authority_arr['operate_member'])
	{
		$view->set_safe('add_button_member_withdraw', true);
		$view->set_safe('add_button_member_ban', true);
	}

	// ---------------------------------------------
	//    モデレーター認定・解除
	// ---------------------------------------------

	if ($authority_arr['administrator'])
	{
		($value['moderator']) ? $view->set_safe('add_button_member_moderator_withdraw', true) : $view->set_safe('add_button_member_moderator', true);
	}
	else if (USER_NO == $key and $authority_arr['moderator'])
	{
		$view->set_safe('add_button_member_moderator_withdraw', true);
	}

	$view->set('community_no', $community_no);

}

echo $view->render();

?>
    </div>
<?php endforeach; ?>

<?=$code_pagination?>

<?php if ($first_load): ?>

  </article>

</div>

<?php endif; ?>
