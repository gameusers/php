<?php

// --------------------------------------------------
//   共通処理
// --------------------------------------------------

$original_code_basic = new Original\Code\Basic();
$original_code_basic->app_mode = $app_mode;


// デフォルトの設定
if (isset($user_no) or isset($profile_no))
{
	$profile_heading = 'プロフィール編集';
	$submit_button_label = '編集する';
}
else
{
	$profile_heading = 'プロフィール追加';
	$submit_button_label = '追加する';
}


if (empty($profile_title)) $profile_title = null;
if (empty($handle_name)) $handle_name = null;
if (empty($explanation)) $explanation = null;
if (empty($status)) $status = null;
if (empty($thumbnail)) $thumbnail = null;
if (empty($user_no)) $user_no = null;
if (empty($profile_no)) $profile_no = null;

if ($user_no)
{
	$type = 'user';
	$no = $user_no;
}
else if ($profile_no)
{
	$type = 'profile';
	$no = $profile_no;
}
else
{
	$type = 'add';
	$no = null;
}

$open_profile_checked = (isset($open_profile) or ( ! $user_no and ! $profile_no)) ? null : ' checked';

?>

<div id="edit_profile_form_<?=$type?>_<?=$no?>">

  <h2 id="heading_black"><?=$profile_heading?></h2>

  <div class="panel panel-default">
    <div class="panel-body">

<?php if ($type == 'user'): ?>
      <p>このプロフィールはGame Usersで一番重要なプロフィールになります。ゲームのプレイヤー（あなた）の情報を入力してください。</p>
<?php else: ?>
      <p>ゲームごとにプロフィールを作成することができます。コミュニティに参加する際に、そのゲーム独自のプロフィールを作成してから参加すると、他のメンバーがあなたがどんなプレイヤーなのか把握しやすくなります。</p>
<?php endif; ?>

      <p class="font_weight_bold margin_top_20">プロフィールタイトル（20文字以内）</p>
      <div class="form-group">
        <input type="text" class="form-control" id="profile_title" maxlength="20" placeholder="プロフィールタイトル" value="<?=$profile_title?>">
      </div>

      <p class="font_weight_bold margin_top_20">ハンドルネーム（30文字以内）</p>
      <div class="form-group">
        <input type="text" class="form-control" id="handle_name" maxlength="30" placeholder="ハンドルネーム" value="<?=$handle_name?>">
      </div>

      <p class="font_weight_bold margin_top_20">コメント（3000文字以内）</p>
      <div class="form-group">
        <textarea class="form-control" id="explanation" maxlength="3000" placeholder="コメント"><?=$explanation?></textarea>
      </div>

      <p class="font_weight_bold margin_top_20">ステータス（20文字以内）</p>
      <p>入力例） ゲーム内の職業（戦士・魔法使い）、役割（リーダー・団長）、または自分の状態（暇・忙しい）など。この欄は自由に使ってください。</p>
      <div class="form-group">
        <input type="text" class="form-control" id="status" maxlength="20" placeholder="ステータス" value="<?=$status?>">
      </div>

      <div class="form-group margin_top_20">
        <p class="font_weight_bold">サムネイル</p>
        <p class="community_bbs_post_comment_about_image">ハンドルネームと共に表示される小さな画像です。アップロードされた画像は自動的に正方形にリサイズされます。アップロードできる画像の種類はJPEG、PNG、GIF、BMPで、ファイルサイズが3MB以内のものです。</p>
<?php if ($thumbnail): ?>
        <div class="form_image_list">
          <div class="form_image_list_image"><img class="img-rounded" src="<?=$uri_base?>assets/img/<?=$type?>/<?=$no?>/thumbnail.jpg?<?php echo mt_rand()?>" width="50px" height="50px"></div>
          <div class="form_image_list_checkbox"><input type="checkbox" id="thumbnail_delete"> 削除</div>
        </div>
<?php endif; ?>

        <input type="file" name="thumbnail" id="thumbnail" class="form_image_list_file">
      </div>

<?php if ( ! $user_no): ?>
      <p class="font_weight_bold margin_top_30">プロフィールの公開・非公開</p>
      <p>例えば、他の人に隠れて参加したいコミュニティがある場合、非公開にしたプロフィールで参加することができます。他のユーザーは、非公開プロフィールで参加したコミュニティから当ページにたどり着くことはできません。また非公開にしたプロフィールは、このページ内に表示されることもありません（第三者からは見えません）。プロフィールを非公開にしたい場合は以下をチェックして保存してください。</p>
      <div class="checkbox margin_bottom_30">
        <label><input type="checkbox" id="open_profile"<?=$open_profile_checked?>> プロフィールを非公開にする</label>
      </div>

      <div class="form-group">
        <p class="font_weight_bold">関連ゲーム</p>
        <p class="community_bbs_post_comment_about_image">このプロフィールに関連するゲームを選択してください。ゲームは10個まで選択できます。<br><br>下の欄にゲーム名を入力するとゲームを検索できます。目当てのゲームが検索しても出てこない場合は、<?php echo '<a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'index')))) . '>Game Usersトップページ</a>'?>にあるゲームタブから「登録」を選んで、ゲームを登録してください。</p>

        <div id="scrollable-dropdown-menu">
          <input type="text" class="form-control typeahead" id="game_name" placeholder="ゲーム名">
        </div>

        <datalist id="game_data"></datalist>

<?php

// ゲームリスト
if (isset($game_list))
{
	//game_list
	$game_list_arr = explode(',', $game_list);
	array_shift($game_list_arr);
	array_pop($game_list_arr);

	echo '<div class="margin_bottom_10 clearfix" id="game_list" data-game-list="[' . implode(',', $game_list_arr) . ']">';
	foreach ($game_list_arr as $key => $value) {
		echo '<div class="original_label_game bgc_lightseagreen cursor_pointer" id="game_no_' . $value . '" onclick="GAMEUSERS.player.deleteGameListNo(this, ' . $profile_no . ', ' . $value . ')">' . $game_names_arr[$value]['name'] . '</div>';
	}
	echo '</div>';
}
else
{
	echo '<div class="margin_bottom_10 clearfix" id="game_list" data-game-list="[]">';
	echo '</div>';
}

?>

      </div>
<?php endif; ?>

      <div class="form_alert_edit_profile" id="alert_edit_profile"></div>

      <div class="form_profile_submit">

<?php if ($user_no): ?>
        <div class="form_profile_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_edit_profile" onclick="GAMEUSERS.player.saveProfile(this, <?=$user_no?>, 0)"><span class="ladda-label"><?=$submit_button_label?></span></button></div>
        <div class="form_profile_submit_left"><button type="button" class="btn btn-success" onclick="GAMEUSERS.player.hideEditProfileForm(this, <?=$user_no?>, 0)">戻る</button></div>
<?php elseif ($profile_no): ?>
        <div class="form_profile_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_edit_profile" onclick="GAMEUSERS.player.saveProfile(this, 0, <?=$profile_no?>)"><span class="ladda-label"><?=$submit_button_label?></span></button></div>
        <div class="form_profile_submit_left"><button type="button" class="btn btn-success" onclick="GAMEUSERS.player.hideEditProfileForm(this, 0, <?=$profile_no?>)">戻る</button></div>
        <div class="form_profile_submit_delete"><button type="submit" class="btn btn-info ladda-button" data-style="expand-right" id="submit_delete_profile" onclick="GAMEUSERS.player.deleteProfile(this, <?=$profile_no?>)"><span class="ladda-label">削除する</span></button></div>
<?php else: ?>
        <div class="form_profile_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_edit_profile" onclick="GAMEUSERS.player.saveProfile(this, 0, 0)"><span class="ladda-label"><?=$submit_button_label?></span></button></div>
        <div class="form_profile_submit_left"><button type="button" class="btn btn-success" onclick="GAMEUSERS.player.hideEditProfileForm(this, 0, 0)">戻る</button></div>
<?php endif; ?>

      </div>

    </div>
  </div>

</div>
