<?php

/*
  必要なデータ

  integer / $community_no / コミュニティNo
  array / $authority_arr / 権限配列

  オプション
*/

?>

<div id="config_notification">

  <h2 id="heading_black">通知設定</h2>

  <div class="panel panel-default">
  	<div class="panel-body">

      <p class="margin_bottom_20">コミュニティから受けとるアプリの通知・メールの設定が行えます。詳しい通知の設定はプレイヤーページで行えます。</p>

      <div class="form-group form_community_config_mail border_top_dashed border_bottom_dashed">
        <p class="font_weight_bold">受信設定</p>
        <p>コミュニティの管理者、モデレーターは所属するメンバーに向けて、アプリでの通知、またはメールを一斉送信することができます。その通知を受信する場合はチェックしてください。</p>
        <div class="checkbox"><label><input type="checkbox" id="mail_all"<?php if ($authority_arr['mail_all']) echo ' checked'; ?>> 通知を受信する</label></div>
      </div>


      <div id="alert"></div>

      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.saveConfigNotification(this, <?=$community_no?>)"><span class="ladda-label">送信する</span></button></div>

    </div>
  </div>

</div>
