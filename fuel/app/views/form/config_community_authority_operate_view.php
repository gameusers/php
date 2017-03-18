<?php

/*
  必要なデータ

  オプション
*/

?>

<div id="config_authority_operate">

  <h2 id="heading_black">操作権限</h2>

  <div class="panel panel-default">
    <div class="panel-body">

      <p class="margin_bottom_20 padding_bottom_20 border_bottom_dashed">操作権限の設定が行えます。操作権限はコミュニティの管理者しか編集することができません。<br><br>※ モデレーターとはコミュニティの管理者（あなた）によって、特別な権限が与えられたコミュニティメンバーのことです。メンバーのタブから設定することができます。</p>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">告知作成・編集</p>
        <p>告知の作成・編集をモデレーターに任せることができます。この項目をチェックする場合、必ず告知の閲覧権限をモデレーターに与えてください。閲覧できない場合、告知の作成・編集はできません。</p>

        <div class="checkbox"><label><input type="checkbox" id="operate_announcement_3"<?php if (in_array(3, $config_arr['operate_announcement'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">掲示板　スレッド作成</p>
        <p>掲示板のスレッドを作成する権限です。この項目をチェックする場合、必ず掲示板の閲覧権限を同じ相手に与えてください。閲覧できない場合、スレッドの作成もできません。</p>

        <div class="checkbox"><label><input type="checkbox" id="operate_bbs_thread_1"<?php if (in_array(1, $config_arr['operate_bbs_thread'])) echo ' checked'; ?>> 一般ユーザー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="operate_bbs_thread_2"<?php if (in_array(2, $config_arr['operate_bbs_thread'])) echo ' checked'; ?>> コミュニティメンバー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="operate_bbs_thread_3"<?php if (in_array(3, $config_arr['operate_bbs_thread'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">掲示板　コメント・返信</p>
        <p>掲示板への書き込み権限です。この項目をチェックする場合、必ず掲示板の閲覧権限を同じ相手に与えてください。閲覧できない場合、コメント・返信もできません。</p>

        <div class="checkbox"><label><input type="checkbox" id="operate_bbs_comment_1"<?php if (in_array(1, $config_arr['operate_bbs_comment'])) echo ' checked'; ?>> 一般ユーザー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="operate_bbs_comment_2"<?php if (in_array(2, $config_arr['operate_bbs_comment'])) echo ' checked'; ?>> コミュニティメンバー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="operate_bbs_comment_3"<?php if (in_array(3, $config_arr['operate_bbs_comment'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">掲示板　削除</p>
        <p>掲示板のスレッド、コメント、返信の削除をモデレーターに任せることができます。この項目をチェックする場合、必ず掲示板の閲覧権限をモデレーターに与えてください。閲覧できない場合、削除もできません。</p>

        <div class="checkbox"><label><input type="checkbox" id="operate_bbs_delete_3"<?php if (in_array(3, $config_arr['operate_bbs_delete'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">メンバー承認・退会</p>
        <p>メンバーの参加承認（上部にある「コミュニティ参加」の設定で、「承認後に参加」を選んだ場合）、強制退会、BAN（強制退会後、参加禁止）をモデレーターに任せることができます。この項目をチェックする場合、必ずメンバーの閲覧権限をモデレーターに与えてください。閲覧できない場合、操作もできません。</p>

        <div class="checkbox"><label><input type="checkbox" id="operate_member_3"<?php if (in_array(3, $config_arr['operate_member'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">通知・メール一斉送信</p>
        <p>コミュニティメンバーに向けて、通知・メールの一斉送信ができる権限を、モデレーターに与えることができます。</p>

        <div class="checkbox"><label><input type="checkbox" id="operate_send_all_mail_3"<?php if (in_array(3, $config_arr['operate_send_all_mail'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">コミュニティ基本設定・コミュニティ追加設定</p>
        <p>コミュニティの設定をモデレーターに任せることができます。</p>

        <div class="checkbox"><label><input type="checkbox" id="operate_config_community_3"<?php if (in_array(3, $config_arr['operate_config_community'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div id="alert"></div>

      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_config_operate" onclick="GAMEUSERS.uc.saveConfigAuthorityOperate(this, <?=$community_no?>)"><span class="ladda-label">送信する</span></button></div>

    </div>
  </div>

</div>
