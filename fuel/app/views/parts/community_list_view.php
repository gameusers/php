<?php

/*
 * 必要なデータ
 * string / $uri_base
 * string / $datetime_now_object / アクセス時間算出のための日時
 * array / $profile_arr
 * integer / $online_limit
 * boolean / $anonymity
 * string / $func_name / 送信のonclickに設定する関数名
 * array / $func_argument_arr
 * 
 * オプション
 * boolean / $app_mode / アプリモードの場合、リンク変更
 * string / $func_name_return / 戻るのonclickに設定する関数名
 * array / $func_argument_return_arr
 * string / $func_name_delete / 削除のonclickに設定する関数名
 * array / $func_argument_delete_arr
 * array / $data_arr / あらかじめ挿入しておくデータ
 * boolean / $title_off / タイトルが不要の場合 true
 * boolean / $image_movie_off / 画像＆動画が不要の場合 true
 */

$original_code_basic = new Original\Code\Basic();
$original_code_basic->app_mode = $app_mode;
 
 ?>

 <?php foreach ($community_arr as $key => $value): ?>
    <section class="media padding_bottom_15 border_bottom_dashed">
      <a class="pull-left"<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'uc', 'community_id' => $value['community_id'])))); ?>>
<?php
$datetime_thumbnail_none = new DateTime($value['regi_date']);
$thumbnail_none_second = $datetime_thumbnail_none->format('s');
$community_thumbnail_url = ($value['thumbnail']) ? $uri_base . 'assets/img/community/' . $value['community_no'] . '/thumbnail.jpg' : $uri_base . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
?>
        <img class="media-object img-rounded" src="<?=$community_thumbnail_url?>" width="64px" height="64px">
      </a>
      <div class="media-body media_list">
<?php if ($app_mode): ?>
        <div<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'uc', 'community_id' => $value['community_id']))), true); ?>>
          <h1 class="media-heading index_uc_community_list_title"><span class="app_link_color"><?=$value['name']?></span></h1>
          <p class="index_uc_community_list_text"><?=$value['description_mini']?></p>
        </div>
<?php else: ?>
        <h1 class="media-heading index_uc_community_list_title"><a<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'uc', 'community_id' => $value['community_id'])))); ?>><?=$value['name']?></a></h1>
        <p class="index_uc_community_list_text"><?=$value['description_mini']?></p>
<?php endif; ?>
        <div class="community_list_label_box clearfix">
          <div class="original_label_game bgc_lightcoral"><?=$value['member_total']?>人</div> <div class="original_label_game bgc_lightseagreen"><?php echo '<a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'gc', 'id' => $game_names_arr[$value['game_no']]['id'])))) . ' class="orignal_label_game">' . $game_names_arr[$value['game_no']]['name'] . '</a>'?></div>
        </div>
      </div>
    </section>

<?php endforeach; ?>
