<?php

/*
  必要なデータ

  integer / $community_no / コミュニティNo
  string / $community_id / コミュニティID
  string / $description / コミュニティ説明文
  integer / $member_total / 参加メンバー数
  array / $game_names_arr / 関連ゲーム用配列
  array / $config_arr / コンフィグ配列
  array / $authority_arr / 権限配列
  boolean / $provisional_member / メンバー登録申請中の場合、参加申請を取り消すボタンを表示するための変数

  オプション
*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_common_convert = new Original\Common\Convert();


// --------------------------------------------------
//   変数
// --------------------------------------------------



?>

<div id="select_profile_form_box">

  <h2 id="heading_black">コミュニティについて</h2>

  <div class="panel panel-default">
    <div class="panel-body">

<?php

// --------------------------------------------------
//   コミュニティについて
// --------------------------------------------------

echo nl2br($original_common_convert->auto_linker($description)) . "\n\n";


// --------------------------------------------------
//   参加 ＆ 関連ゲーム
// --------------------------------------------------

$temp = ($config_arr['participation_type'] == 1) ? '誰でも参加' : '承認後に参加';

echo '      <div class="community_member_label_box clearfix">' . "\n";
echo '        <div class="original_label_game bgc_salmon">' . $temp . '</div> ' . "\n";
echo '        <div class="original_label_game bgc_lightcoral">' . $member_total . '人</div> ' . "\n";

foreach ($game_names_arr as $key => $value)
{
  echo '        <div class="original_label_game bgc_lightseagreen"><a href="' . URI_BASE . 'gc/' . $value['id'] . '" class="orignal_label_game">' . $value['name'] . '</a></div>';
}

echo '      </div>' . "\n\n";

?>


<?php if ($authority_arr['member']): ?>
      <div class="margin_top_20" id="withdraw_community">
        <div id="alert"></div>
        <button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.withdrawCommunity(this, <?=$community_no?>, 1)"><span class="ladda-label">退会する</span></button>
      </div>
<?php elseif ($provisional_member): ?>
      <div class="margin_top_20" id="withdraw_community">
        <div id="alert"></div>
        <button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.withdrawCommunity(this, <?=$community_no?>, 2)"><span class="ladda-label">参加申請を取り消す</span></button>
      </div>
<?php elseif (USER_NO): ?>
      <div class="join_community_select_profile" id="join_community">
        <div class="margin_bottom_15">コミュニティに参加するプロフィールを選んでください。選んだプロフィールは後で変更することもできます。</div>
<?=$code_select_profile_form?>
        <div id="alert"></div>
<?php if ($config_arr['participation_type'] == 1): ?>
        <div class="margin_top_15"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.joinCommunity(this, <?=$community_no?>, 1)"><span class="ladda-label">参加する</span></button></div>
<?php elseif ($config_arr['participation_type'] == 2): ?>
        <div class="margin_top_15"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.joinCommunity(this, <?=$community_no?>, 2)"><span class="ladda-label">参加申請する</span></button></div>
<?php endif; ?>
      </div>
<?php else: ?>
      <div class="margin_top_20">
        <div class="alert alert-info" role="alert">コミュニティに参加するにはログインする必要があります。以下の「参加する」ボタンを押すと、ログインページに移行します。そこでログインを済ませると、このページに戻ってきますので、再度「参加する」ボタンを押してください。</div>
        <a href="<?=URI_BASE?>login" type="button" class="btn btn-success">参加する</a>
      </div>
<?php endif; ?>

    </div>
  </div>

</div>
