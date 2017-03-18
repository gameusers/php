<?php

/*
 * 必要なデータ
 * array / $notification_data_arr / データ
 *
 * オプション
 */

?>

<?php if ($db_present_winner_arr): ?>

<?php $original_common_crypter = new Original\Common\Crypter(); ?>

<h2 id="heading_black">当選！</h2>

<div class="panel panel-default" id="config_page">
  <div class="panel-body">

    <p class="margin_bottom_20">おめでとうございます！Game Usersが行っているギフト券プレゼントイベントで当選しました。当選したコードは3ヶ月経つと表示されなくなります。必ずそれまでにコードを使用してください。</p>

    <p class="margin_top_30 font_weight_bold">当選コード番号</p>

<?php

foreach ($db_present_winner_arr as $key => $value) {

	$datetime = new DateTime($value['regi_date']);
	$datetime_regi_date = $datetime->format("Y-m-d");

	$decrypted_code = ($value['code']) ? $original_common_crypter->decrypt($value['code']) : null;

	echo '<p>' . $datetime_regi_date . ' / ' . $value['type'] . ' ' . $value['sum'] . ' ' . $value['unit'] . ' / <span class="font_weight_bold">' . $decrypted_code . '</span></p>' . "\n";

}

?>

    <p class="margin_top_20"><a href="http://www.amazon.co.jp/gp/help/customer/display.html?nodeId=642976" id="external_link">Amazonギフト券の使い方</a></p>

  </div>
</div>

<?php endif; ?>



<h2 id="heading_black">ページ設定</h2>

<div class="panel panel-default" id="config_page">
  <div class="panel-body">

    <p class="margin_bottom_20">プレイヤーページの設定が行えます。</p>

    <div class="form-group margin_top_10">

      <p class="font_weight_bold">トップ画像</p>
      <p class="community_bbs_post_comment_about_image">アップロードできる画像の種類はJPEG、PNG、GIFで、ファイルサイズが3MB以内のものです。</p>

<?php

if ($top_image_arr)
{
	foreach ($top_image_arr as $key => $value)
	{
		if ($value['width'] and $value['height'] and $key != 'none')
		{
			echo '      <div class="form_image_list">' . "\n";
			echo '        <div class="form_image_list_image"><img src="' . URI_BASE . 'assets/img/user/' . USER_NO . '/' . $key . '.jpg?' . strtotime($renewal_date) . '" width="80"></div>' . "\n";
			echo '        <div class="form_image_list_checkbox"><input type="checkbox" id="' . $key . '_delete"> 削除</div>' . "\n";
			echo '      </div>' . "\n";
		}
	}
}

?>

      <input type="file" name="top_image_1" id="top_image_1" class="form_image_list_file">
      <input type="file" name="top_image_2" id="top_image_2" class="form_image_list_file">
      <input type="file" name="top_image_3" id="top_image_3" class="form_image_list_file">

    </div>

    <p class="margin_top_30 font_weight_bold">ページタイトル</p>
    <p>このページのタイトルになります。</p>

    <div class="form-group">
      <input type="text" class="form-control" id="page_title" maxlength="50" placeholder="ページタイトル" value="<?=$page_title?>">
    </div>

    <p class="margin_top_10 font_weight_bold">プレイヤーID</p>
    <p>このページのURLになります（ログインIDではありません）。 https://gameusers.org/pl/プレイヤーID<br>利用できる文字は半角英数字（アルファベット大文字禁止）とハイフン( - )アンダースコア( _ )です。3文字以上、50文字以内。</p>

    <div class="form-group">
      <input type="text" class="form-control" id="user_id" maxlength="50" placeholder="プレイヤーID" value="<?=$user_id?>">
    </div>

    <div id="alert_config_page"></div>

    <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_config_page" onclick="GAMEUSERS.player.saveConfigPage(this)"><span class="ladda-label">送信する</span></button></div>

  </div>
</div>



<h2 id="heading_black">アカウント設定</h2>

<div class="panel panel-default" id="config_account">
  <div class="panel-body">

  <p class="margin_bottom_20">アカウントの設定が行えます。ログインID＆ログインパスワードを変更したい場合はこちらから設定してください。<br>※ TwitterやGoogleなど、他サイトのアカウントでログインした場合は、ログインID＆ログインパスワードを設定する必要はありませんが、設定した場合はどちらからでもログインすることができるようになります。</p>

  <p class="font_weight_bold margin_top_20">ログインID</p>
  <p>利用できる文字は半角英数字とハイフン( - )アンダースコア( _ )です。3文字以上、25文字以内。ログインパスワードと同じ文字列は利用できません。</p>
  <div class="form-group">
    <input type="text" class="form-control" id="login_username" maxlength="25" placeholder="ログインID" value="<?=$username?>">
  </div>

  <div id="alert_login_username"></div>

  <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_login_username" onclick="GAMEUSERS.player.saveLoginUsername(this)"><span class="ladda-label">送信する</span></button></div>

  <p class="font_weight_bold margin_top_20">ログインパスワード</p>
  <p>利用できる文字は半角英数字とハイフン( - )アンダースコア( _ )です。6文字以上、32文字以内。ログインIDと同じ文字列は利用できません。数字のみのパスワードも利用できません。</p>
  <div class="form-group">
    <input type="password" class="form-control" id="login_password" maxlength="32" placeholder="パスワード">
  </div>

  <div class="form-group">
    <input type="password" class="form-control" id="login_password_verification" maxlength="32" placeholder="パスワード再入力">
  </div>

  <div id="alert_login_password"></div>

  <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_login_password" onclick="GAMEUSERS.player.saveLoginPassword(this)"><span class="ladda-label">送信する</span></button></div>

  </div>
</div>



<h2 id="heading_black">アカウント削除</h2>

<div class="panel panel-default" id="config_delete_player_account">
  <div class="panel-body">

  <p>アカウントを削除する場合は、以下のフォームに delete と入力して送信してください。<br><br>アカウントを削除すると、登録されているアプリのデバイス情報、メールアドレスは自動的に削除され、参加しているコミュニティから通知を受け取ることはできなくなります。またこれまでコミュニティ内で書き込んだ内容については自動的に削除されませんので、もし書き込みなどを削除したい場合は、必ずアカウントを削除する前に削除してください。一度アカウントを削除すると、以後、削除できなくなります。自分がオーナーになっているコミュニティも自動的に削除されますので、参加者がいる場合には、閉鎖することを通知してからアカウントを削除することをお勧めします。</p>

  <div class="form-group">
    <input type="text" class="form-control" id="delete_player_account_verification" maxlength="6" placeholder="確認キーワード">
  </div>

  <div id="alert"></div>

  <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.player.deletePlayerAccount(this)"><span class="ladda-label">削除する</span></button></div>

  </div>
</div>
