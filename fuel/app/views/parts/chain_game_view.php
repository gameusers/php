<?php

/*
 * 必要なデータ
 * string / $uri_base
 * array / $db_notifications_arr / データ
 * array / $unread_id / 
 * 
 * オプション
 * boolean / $app_mode / アプリモードの場合、リンク変更
 */

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------
//$app_mode = true;
$original_common_convert = new \Original\Common\Convert();

$original_code_basic = new Original\Code\Basic();
if (isset($app_mode)) $original_code_basic->app_mode = $app_mode;

//var_dump($db_notifications_arr);

 ?>

<input type="hidden" id="unread_id_reservation" data-unread_id="<?=$unread_id?>">

<?php foreach ($db_notifications_arr as $key => $value): ?>
<?php if ($key == 0): ?>
  <div class="player_notifications_box_first" id="player_notifications_box">
<?php else: ?>
  <div class="player_notifications_box" id="player_notifications_box">
<?php endif; ?>
    
    <div class="player_notifications_left_box">
<?php

// --------------------------------------------------
//    サムネイル
// --------------------------------------------------

if ($value['thumbnail'])
{
	if (isset($value['profile_no']))
	{
		$thumbnail_url = $uri_base . 'assets/img/profile/' . $value['profile_no'] . '/thumbnail.jpg';
	}
	else
	{
		$thumbnail_url = $uri_base . 'assets/img/user/' . $value['user_no'] . '/thumbnail.jpg';
	}
}
else
{
	$datetime_thumbnail_none = new DateTime($value['regi_date']);
	$thumbnail_none_second = $datetime_thumbnail_none->format('s');
	$thumbnail_url = $uri_base . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
}

?>
      <img class="img-rounded" src="<?=$thumbnail_url?>" width="64px" height="64px">
<?php if (isset($value['read_all'])): ?>
      <div class="player_notifications_read_all_button_center"><button class="btn btn-default btn-sm player_notifications_read_all_button" type="submit" id="player_notifications_read_all_button">全文</button></div>
<?php endif; ?>
    </div>
    
    <div class="player_notifications_right_box media_list">
<?php if ($app_mode): ?>
      <div<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array($value['link_page_arr'])), true); ?>>
        <h4 class="player_notifications_title"><span class="app_link_color"><?=$value['name']?></span></h4>
        <p class="player_notifications_text">
          <?=$value['notification']?><br>
          <div class="player_notifications_short" id="player_notifications_short"><?=$value['comment_short']?></div>
        </p>
      </div>
<?php else: ?>
      <h4 class="player_notifications_title"><a<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array($value['link_page_arr']))); ?>><?=$value['name']?></a></h4>
      <p class="player_notifications_text">
        <?=$value['notification']?><br>
        <div class="player_notifications_short" id="player_notifications_short"><?=$value['comment_short']?></div>
      </p>
<?php endif; ?>
<?php if (isset($value['comment_long'])): ?>
      <p class="player_notifications_text">
        <div class="player_notifications_long" id="player_notifications_long"><?php echo nl2br($original_common_convert->auto_linker($value['comment_long'])); ?></div>
      </p>
<?php endif; ?>
<?php if (isset($value['game_name'])): ?>
      <div class="community_list_label_box clearfix">
        <div class="original_label_game bgc_lightseagreen"><?php echo '<a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'gc', 'id' => $value['game_id'])))) . ' class="orignal_label_game">' . $value['game_name'] . '</a>'?></div>
      </div>
<?php endif; ?>
    </div>
    
  </div>
  
<?php endforeach; ?>