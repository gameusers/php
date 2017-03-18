<?php



?>

<div class="content_box">

<?php

// --------------------------------------------------
//   Adsense
// --------------------------------------------------

if (isset($code_adsense))
{
	echo '<div class="margin_top_10 margin_bottom_20">' . "\n";
	echo $code_adsense;
	echo '</div>' . "\n\n";
}

?>

<?=$code_heading?>

<?=$code_social?>

<?=$code_slide_game_list?>


  <ul id="bsTab" class="nav nav-tabs bsTab">
    <li class="active"><a href="#tab_help">ヘルプ</a></li>
    <li><a href="#tab_inquiry">お問い合わせ</a></li>
  </ul>


  <div id="myTabContent" class="tab-content padding_top_20">

    <div class="tab-pane fade in active" id="tab_help">

<?=$code_help?>

    </div>


    <div class="tab-pane fade" id="tab_inquiry">

      <h2 id="heading_black">お問い合わせ</h2>

      <div class="panel panel-default" id="config_community_basis">
        <div class="panel-body">

          <p class="margin_bottom_15 padding_bottom_20 border_bottom_dashed">Game Usersへの連絡はこちらのフォーム、または<a href="<?php echo URI_BASE . 'uc/official'; ?>">Game Users 公式コミュニティ</a>を利用して行ってください。</p>

          <p class="font_weight_bold">名前（ハンドルネーム）</p>
          <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
            <input type="text" class="form-control" id="inquiry_name" maxlength="50" placeholder="名前（ハンドルネーム）" value="">
          </div>

          <p class="font_weight_bold">メールアドレス（任意）</p>
          <p>返信が必要な場合は記入してください。</p>
          <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
            <input type="email" class="form-control" id="inquiry_email" maxlength="50" placeholder="メールアドレス" value="">
          </div>

          <p class="font_weight_bold">お問い合わせ内容</p>
          <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
            <textarea class="form-control" id="inquiry_comment" maxlength="3000" rows="4" placeholder="お問い合わせ内容"></textarea>
          </div>

          <div id="alert"></div>

          <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.help.sendInquiry(this)"><span class="ladda-label">送信する</span></button></div>

        </div>
      </div>

    </div>


  </div>


<div class="margin_top_40">
<?=$code_ad_amazon_slide?>
</div>



<?php

// --------------------------------------------------
//   Adsense
// --------------------------------------------------

if (isset($code_adsense_rectangle))
{
	echo '<div class="margin_top_20">' . "\n";
	echo $code_adsense_rectangle;
	echo '</div>' . "\n\n";
}

?>


</div>
