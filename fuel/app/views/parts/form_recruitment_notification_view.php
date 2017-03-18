<?php

/*
 * 必要なデータ
 * integer / login_user_no
 * integer / game_no
 * string / $game_title / ゲームタイトル
 * boolean / $notification_recruitment_on / users_game_communityのnotification_recruitmentに、このゲームのゲームNoが登録されているかのチェック　新規募集通知の初期値がはいか、いいえか
 *
 * オプション
 */


if ($notification_recruitment_on)
{
	$on_active = ' active';
	$off_active = '';
}
else
{
	$on_active = '';
	$off_active = ' active';
}

 ?>

<?php if ($login_user_no): ?>
<aside>
  <p class="margin_bottom_10"><?=$game_title?> ゲームページに新しい募集が投稿されたとき、通知を受信しますか？
    <ul class="config_notification_recruitment_list">
      <li class="margin_bottom_5">ベルでお知らせ（数字プラス）</li>
      <li>Game Usersアプリで通知</li>
    </ul>
  </p>

  <div class="btn-group margin_bottom_5">
    <button type="button" class="btn btn-default ladda-button<?=$on_active?>" data-style="expand-right" data-spinner-color="#000000" id="gc_notification_recruitment_on" onclick="saveGcNotification(this, <?=$game_no?>, 'recruitment', true)"><span class="ladda-label">はい</span></button>
    <button type="button" class="btn btn-default ladda-button<?=$off_active?>" data-style="expand-right" data-spinner-color="#000000" id="gc_notification_recruitment_off" onclick="saveGcNotification(this, <?=$game_no?>, 'recruitment', false)"><span class="ladda-label">いいえ</span></button>
  </div>
</aside>
<?php endif; ?>
