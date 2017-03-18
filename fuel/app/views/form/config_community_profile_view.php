<?php

/*
  必要なデータ

  integer / $community_no / コミュニティNo
  string / $code_select_profile_form / プロフィール

  オプション
*/

?>

<div id="select_profile_form_box">

  <h2 id="heading_black">プロフィール変更</h2>

  <div class="panel panel-default">
    <div class="panel-body">

      <p class="margin_bottom_10 padding_bottom_20 border_bottom_dashed">コミュニティに参加するプロフィールを変更できます。</p>

<?=$code_select_profile_form?>

      <div id="alert"></div>

      <div class="margin_top_15"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.saveConfigSelectProfile(this, <?=$community_no?>)"><span class="ladda-label">送信する</span></button></div>

    </div>
  </div>

</div>
