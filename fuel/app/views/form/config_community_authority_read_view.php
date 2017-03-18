<?php

/*
  必要なデータ

  オプション
*/

?>

<div id="config_authority_read">

  <h2 id="heading_black">閲覧権限</h2>

  <div class="panel panel-default">
    <div class="panel-body">

      <p class="margin_bottom_20 padding_bottom_20 border_bottom_dashed">閲覧権限の設定が行えます。閲覧権限はコミュニティの管理者しか編集することができません。<br><br>すべての項目をチェックしたままにすると、誰でも閲覧できるコミュニティになります。コミュニティメンバーだけで交流をしたい場合は、一般ユーザーのチェックをすべて外してください。外部の人間はコミュニティの内部を覗くことができなくなります。<br><br>※ モデレーターとはコミュニティの管理者（あなた）によって、特別な権限が与えられたコミュニティメンバーのことです。メンバーのタブから設定することができます。</p>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">告知</p>
        <p>コミュニティページの一番見やすい場所に表示される情報です。</p>

        <div class="checkbox"><label><input type="checkbox" id="read_announcement_1"<?php if (in_array(1, $config_arr['read_announcement'])) echo ' checked'; ?>> 一般ユーザー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="read_announcement_2"<?php if (in_array(2, $config_arr['read_announcement'])) echo ' checked'; ?>> コミュニティメンバー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="read_announcement_3"<?php if (in_array(3, $config_arr['read_announcement'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">掲示板</p>
        <p>コミュニティメンバーや一般ユーザーと交流できる掲示板です。</p>

        <div class="checkbox"><label><input type="checkbox" id="read_bbs_1"<?php if (in_array(1, $config_arr['read_bbs'])) echo ' checked'; ?>> 一般ユーザー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="read_bbs_2"<?php if (in_array(2, $config_arr['read_bbs'])) echo ' checked'; ?>> コミュニティメンバー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="read_bbs_3"<?php if (in_array(3, $config_arr['read_bbs'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">メンバー</p>
        <p>コミュニティに参加している全メンバーの情報が確認できます。</p>

        <div class="checkbox"><label><input type="checkbox" id="read_member_1"<?php if (in_array(1, $config_arr['read_member'])) echo ' checked'; ?>> 一般ユーザー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="read_member_2"<?php if (in_array(2, $config_arr['read_member'])) echo ' checked'; ?>> コミュニティメンバー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="read_member_3"<?php if (in_array(3, $config_arr['read_member'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">コミュニティ情報　追加情報</p>
        <p>次回のアップデートでコミュニティ情報に、新たな情報を追加できるようにする予定です（現在は意味のない項目です）。</p>

        <div class="checkbox"><label><input type="checkbox" id="read_additional_info_1"<?php if (in_array(1, $config_arr['read_additional_info'])) echo ' checked'; ?>> 一般ユーザー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="read_additional_info_2"<?php if (in_array(2, $config_arr['read_additional_info'])) echo ' checked'; ?>> コミュニティメンバー</label></div>
        <div class="checkbox"><label><input type="checkbox" id="read_additional_info_3"<?php if (in_array(3, $config_arr['read_additional_info'])) echo ' checked'; ?>> モデレーター</label></div>
      </div>

      <div id="alert"></div>

      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_config_read" onclick="GAMEUSERS.uc.saveConfigAuthorityRead(this, <?=$community_no?>)"><span class="ladda-label">送信する</span></button></div>

    </div>
  </div>

</div>
