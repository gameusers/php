<?php

// --------------------------------------------------
//   共通処理
// --------------------------------------------------

$original_code_basic = new Original\Code\Basic();
$original_code_basic->app_mode = $app_mode;


// --------------------------------------------------
//   初期処理
// --------------------------------------------------

if (isset($profile_arr['profile_no']))
{
	$profile_type = 'profile';
	$open_profile = ($profile_arr['open_profile']) ? true : false;

	$no = $profile_arr['profile_no'];
	$good = $profile_arr['good'];
	$author_user_no = $profile_arr['author_user_no'];
}
else if (isset($profile_arr['user_no']))
{
	$profile_type = 'user';
	$open_profile = true;

	$no = $profile_arr['user_no'];
	$good = $profile_arr['good'];
	$author_user_no = $profile_arr['user_no'];
}


// --------------------------------------------------
//   プロフィールBox　最初のタグ
// --------------------------------------------------

if ($profile_type == 'profile')
{
	echo '<article id="profile_box_' . $profile_arr['profile_no'] . '">' . "\n\n";
}
else if ($profile_type == 'user')
{
	echo '<article id="profile_box_user">' . "\n\n";
}
else
{
	echo '<article id="profile_box_add">' . "\n\n";
}


// --------------------------------------------------
//   プロフィールタイトル設定
// --------------------------------------------------

$profile_title = ($profile_arr['profile_title']) ? $profile_arr['profile_title'] : 'プレイヤープロフィール';


?>

  <h2 id="heading_black"><?=$profile_title?></h2>

  <div class="panel panel-default">
    <div class="panel-body">

<?php

// --------------------------------------------------
//   パーソナルボックス
// --------------------------------------------------

$view_personal_box = View::forge('parts/personal_box_view2');
$view_personal_box->set_safe('app_mode', $app_mode);
$view_personal_box->set_safe('uri_base', $uri_base);
$view_personal_box->set_safe('profile_arr', $profile_arr);
$view_personal_box->set_safe('online_limit', $online_limit);
$view_personal_box->set_safe('good_type', $profile_type);
$view_personal_box->set_safe('good_no', $no);
$view_personal_box->set_safe('good', $good);
if (isset($link_force_off)) $view_personal_box->set_safe('link_force_off', true);

echo $view_personal_box->render() . "\n";

?>

<?php

// --------------------------------------------------
//   説明文
// --------------------------------------------------

if ($author_user_no == $login_user_no and $profile_type == 'user' and empty($profile_arr['explanation']))
{
	echo '    <p>プレイヤープロフィールを設定しましょう！下の「編集ボタン」を押してください。</p>';
}
else
{
	$original_common_convert = new Original\Common\Convert();
	echo '    <p>' . nl2br($original_common_convert->auto_linker($profile_arr['explanation'])) . '</p>' . "\n\n";
}



// --------------------------------------------------
//   ゲームデータ処理
// --------------------------------------------------
//var_dump($profile_arr);
if (isset($profile_arr['game_list']))
{
	//game_list
	$game_list_arr = explode(',', $profile_arr['game_list']);
	array_shift($game_list_arr);
	array_pop($game_list_arr);

	echo '    <div class="original_label_box clearfix">' . "\n";
	foreach ($game_list_arr as $key => $value) {
		//echo '      <div class="original_label_game bgc_lightseagreen">' . $game_names_arr[$value]['name'] . '</div>' . "\n";
		echo '<div class="original_label_game bgc_lightseagreen"><a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'gc', 'id' => $game_names_arr[$value]['id'])))) . ' class="orignal_label_game">' . $game_names_arr[$value]['name'] . '</a></div>';
	}
	echo '    </div>' . "\n\n";
}


// --------------------------------------------------
//   作者用メニュー
// --------------------------------------------------

if ($login_user_no == $author_user_no)
{
	echo '    <div class="user_profile_author_menu">' . "\n";

	// プロフィール
	if ($open_profile)
	{
		echo '      <div class="original_label bgc_black">公開プロフィール</div>' . "\n";
	}
	else
	{
		echo '      <div class="original_label bgc_darkgray">非公開プロフィール</div>' . "\n";
	}

	if ($profile_type == 'user')
	{
		echo '      <button type="button" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-spinner-color="#000000" id="submit_show_edit_profile_form" onclick="GAMEUSERS.player.showEditProfileForm(this, ' . $no . ', null)">編集</button>' . "\n";
	}
	else if ($profile_type == 'profile')
	{
		echo '      <button type="button" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-spinner-color="#000000" id="submit_show_edit_profile_form" onclick="GAMEUSERS.player.showEditProfileForm(this, null, ' . $no . ')">編集</button>' . "\n";
	}

	echo '    </div>' . "\n";
}

?>

    </div>
  </div>

</article>

<?php if ($appoint) : ?>
    <div class=""><button type="submit" class="btn btn-default ladda-button" data-style="expand-right" data-spinner-color="#000000" onclick="GAMEUSERS.player.readProfile(this, 1, <?=$author_user_no?>)"><span class="ladda-label">他のプロフィールを見る</span></button></div>
<?php endif; ?>
