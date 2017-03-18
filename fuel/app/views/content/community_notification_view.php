<?php

/*
  必要なデータ

  integer / $community_no / コミュニティNo
  array / $notification_arr / 通知配列
  array / $notification_datetime / 日時
  array / $notification_limit / 通知を送信できる回数

  オプション
*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------



// --------------------------------------------------
//   変数
// --------------------------------------------------



?>

<div>

  <h2 id="heading_black">通知一斉送信</h2>

  <div class="panel panel-default">
    <div class="panel-body">

      <p class="margin_bottom_20">アプリでの通知・メールの受信を許可しているコミュニティメンバー全員に対して、通知・メールを一斉送信できます。一斉送信ができるのは1日に3回までです。</p>

      <div class="well"><?=$notification_datetime?>　あと <span id="mail_limit"><?=$notification_limit?></span> 回通知を一斉送信できます。</div>

<?php

foreach ($notification_arr['mail'] as $key => $value)
{
$mbpx = ($key == 9) ? 0 : 40;

echo '      <div class="form-group"><input type="text" class="form-control" id="mail_subject_' . ($key + 1) . '" maxlength="50" placeholder="件名 ' . ($key + 1) . '" value="' . $value['subject'] . '"></div>' . "\n";
echo '      <div class="form-group"><textarea class="form-control" id="mail_body_' . ($key + 1) . '" maxlength="1000" placeholder="本文 ' . ($key + 1) . '">' . $value['body'] . '</textarea></div>' . "\n";
echo '      <div id="alert_mail_all_' . ($key + 1) . '"></div>' . "\n\n";

$disabled = ($notification_limit < 1) ? ' disabled="disabled"' : null;
$disabled_text = ($notification_limit < 1) ? '送信できません' : '送信する';

echo '      <div class="margin_bottom_' . $mbpx. '"><button type="submit" class="btn btn-info ladda-button" data-style="expand-right" id="submit_save_mail_all_' . ($key + 1) . '" onclick="GAMEUSERS.uc.sendSaveNotification(this, ' . $community_no . ', ' . ($key + 1) . ', false)"><span class="ladda-label">保存する</span></button> <button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_send_mail_all_' . ($key + 1) . '" onclick="GAMEUSERS.uc.sendSaveNotification(this, ' . $community_no . ', ' . ($key + 1) . ', true)"' . $disabled . '><span class="ladda-label">' . $disabled_text. '</span></button></div>' . "\n\n";
}

?>

    </div>
  </div>

</div>
