<?php

/*
  必要なデータ

  integer / $community_no / コミュニティNo
  array / $authority_arr / 権限配列

  オプション
*/

?>

<div id="config_community_basis">

  <h2 id="heading_black">基本設定</h2>

  <div class="panel panel-default">
    <div class="panel-body">

     <p class="margin_bottom_15 padding_bottom_20 border_bottom_dashed">コミュニティの基本設定が行えます。</p>

     <p class="font_weight_bold">コミュニティの名前（50文字以内）</p>
     <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
       <input type="text" class="form-control" id="community_name" maxlength="50" placeholder="コミュニティの名前" value="<?=$db_community_arr['name']?>">
     </div>

     <p class="font_weight_bold">コミュニティの説明文（3000文字以内）</p>
     <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
       <textarea class="form-control" id="community_description" maxlength="3000" placeholder="コミュニティの説明文"><?=$db_community_arr['description']?></textarea>
     </div>

     <p class="font_weight_bold">コミュニティの説明文（一覧用）</p>
     <p>Game Usersのトップページなどで表示されるコミュニティ一覧用の短い説明文です。100文字以内。</p>
     <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
       <textarea class="form-control" id="community_description_mini" maxlength="100" placeholder="コミュニティの説明文（100文字以内）"><?=$db_community_arr['description_mini']?></textarea>
     </div>

     <div class="form-group margin_bottom_20 padding_bottom_15 border_bottom_dashed">
        <p class="font_weight_bold">トップ画像</p>
        <p class="margin_bottom_10">このページのトップに表示される画像です。アップロードできる画像の種類はJPEG、PNG、GIF、BMPで、ファイルサイズが3MB以内のものです。1920 × 1080、1280 × 720などの横長で大きめの画像をアップロードすると綺麗に表示されます（壁紙サイズを推奨）<br>アクセスした端末の画面サイズによって表示される範囲が変わります。画像の端の方は表示されないことがあり、特にスマートフォンでは画像の中央部分しか表示されません。見切れてしまうため、端の方に文字が入った画像はトップ画像に向いていないかもしれません。</p>

<?php

if (count($db_image_arr) > 0)
{
  foreach ($db_image_arr as $key => $value)
  {
    $image_path = URI_BASE . 'assets/img/u/' . $value['image_id'] . '.jpg';
// \Debug::dump($value, $image_path, file_exists($image_path));
    if ($value['on_off'])
    {
      echo '        <div class="form_image_list">' . "\n";
      echo '          <div class="form_image_list_image"><img src="' . $image_path . '?' . strtotime($value['renewal_date']) . '" width="80"></div>' . "\n";
      echo '          <div class="form_image_list_checkbox"><input type="checkbox" id="delete_image" data-id="' . $value['image_id'] . '"> 削除</div>' . "\n";
      echo '        </div>' . "\n";
    };

    echo '        <input type="file" id="image_' . $value['image_id'] . '" class="form_image_list_file">';
  }
}
else
{
  echo '        <input type="file" id="image_1" class="form_image_list_file">';
}

?>


     </div>

     <div class="form-group margin_bottom_20 padding_bottom_15 border_bottom_dashed">
       <p class="font_weight_bold">サムネイル画像</p>
       <p class="community_bbs_post_comment_about_image">コミュニティ一覧で、コミュニティ情報と共に表示される小さな画像です。アップロードされた画像は自動的に正方形にリサイズされます。アップロードできる画像の種類はJPEG、PNG、GIF、BMPで、ファイルサイズが3MB以内のものです。</p>

<?php if ($db_community_arr['thumbnail']): ?>
       <div class="form_image_list">
         <div class="form_image_list_image"><img class="img-rounded" src="<?=URI_BASE?>assets/img/community/<?=$community_no?>/thumbnail.jpg?<?php echo strtotime($db_community_arr['renewal_date']); ?>" width="64px" height="64px"></div>
         <div class="form_image_list_checkbox"><input type="checkbox" id="thumbnail_delete"> 削除</div>
       </div>
<?php endif; ?>

       <input type="file" name="thumbnail" id="thumbnail" class="form_image_list_file">
     </div>

     <p class="font_weight_bold">コミュニティID</p>
     <p class="margin_top_10">コミュニティIDはコミュニティのURLとして使われます。https://gameusers.org/uc/コミュニティID<br>利用できる文字は半角英数字（アルファベット大文字禁止）とハイフン( - )アンダースコア( _ )です。3文字以上、50文字以内。</p>
     <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
       <input type="text" class="form-control" id="community_id" maxlength="50" placeholder="コミュニティID" value="<?=$db_community_arr['community_id']?>">
     </div>

     <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed" id="game_list_form_box">
       <p class="font_weight_bold">関連ゲーム</p>
       <p class="community_bbs_post_comment_about_image">コミュニティに関連のあるゲームを選んでください。10個までゲームを登録できますが、最も関連の深いゲームは一番最初に登録してください。コミュニティ一覧では、最初に選択された1件のゲーム名しか表示されません。<br><br>下の欄にゲーム名を入力するとゲームを検索できます。目当てのゲームが検索しても出てこない場合は、<a href="<?=URI_BASE?>">Game Usersトップページ</a>にあるタブから「ゲーム登録」を選んで、登録してください。</p>

       <div id="scrollable-dropdown-menu">
         <input type="text" class="form-control typeahead" id="game_name" placeholder="ゲーム名">
       </div>

       <datalist id="game_data"></datalist>

<?php

// --------------------------------------------------
//    ゲームリスト
// --------------------------------------------------

if (isset($game_names_arr))
{

  echo '<div class="clearfix" id="game_list" data-game-list="[' . implode(',', $game_no_arr) . ']">';

  foreach ($game_names_arr as $key => $value) {
    echo '<div class="original_label_game bgc_lightseagreen cursor_pointer" id="game_list_no_' . $key . '" onclick="GAMEUSERS.common.deleteGameListNo(this, ' . $key . ')">' . $value['name'] . '</div>';
  }

  echo '</div>';
}
else
{
  echo '<div class="clearfix" id="game_list" data-game-list="[]"></div>';
}

?>

     </div>


     <div id="alert"></div>

     <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.saveConfigCommunityBasis(this, <?=$community_no?>)"><span class="ladda-label">送信する</span></button></div>

   </div>
 </div>

</div>
