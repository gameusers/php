<?php

/*
 * 必要なデータ
 * boolean / $app_mode / アプリモードの場合、リンク変更
 * string / $uri_base
 * string / $func_name / 送信のonclickに設定する関数名
 * array / $func_argument_arr
 * 
 * array / $db_recruitment_arr / 募集の配列
 * array / $game_names_arr / ゲームの配列
 * 
 * オプション
 */

$original_code_basic = new Original\Code\Basic();
$original_code_basic->app_mode = $app_mode;

$datetime_now = new DateTime();

//var_dump($db_recruitment_arr);

 ?>

 <?php foreach ($db_recruitment_arr as $key => $value): ?>
    <section class="media padding_bottom_15 border_bottom_dashed">

      <a class="pull-left" <?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'gc', 'id' => $game_names_arr[$value['game_no']]['id'], 'recruitment_id' => $value['recruitment_id'])))); ?>>
      	
<?php

if (isset($game_names_arr[$value['game_no']]['thumbnail']))
{
	echo '        <img class="media-object img-rounded" src="' . $uri_base . 'assets/img/game/' . $value['game_no'] . '/thumbnail.jpg" width="64px" height="64px">';
}
else
{
	
	if (isset($value['regi_date']))
	{
		$datetime_thumbnail_none = new DateTime($value['regi_date']);
		$thumbnail_none_second = $datetime_thumbnail_none->format('s');
		$thumbnail_url = $uri_base . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
	}
	else
	{
		$ramdom_number = mt_rand(0, 59);
		if ($ramdom_number < 10) $ramdom_number = '0' . $ramdom_number;
		$thumbnail_url = $uri_base . 'assets/img/common/thumbnail_none_' . $ramdom_number . '.png';
	}
	
	
	echo '        <img class="media-object img-rounded" src="' . $thumbnail_url . '" width="64px" height="64px">';
}

?>
      </a>

      <div class="media-body media_list">
<?php

// --------------------------------------------------
//    募集の種類
// --------------------------------------------------

if ($value['type'] == 1)
{
	$recruitument_type = 'プレイヤー募集';
}
else if ($value['type'] == 2)
{
	$recruitument_type = 'フレンド募集';
}
else if ($value['type'] == 3)
{
	$recruitument_type = 'ギルド・クランメンバー募集';
}
else if ($value['type'] == 4)
{
	$recruitument_type = '売買・交換相手募集';
}
else if ($value['type'] == 5)
{
	$recruitument_type = 'その他の募集';
}
else if ($value['type'] == 6)
{
	$recruitument_type = $game_names_arr[$value['game_no']]['name'];
}


// --------------------------------------------------
//   タイトル
// --------------------------------------------------

if ($value['etc_title'])
{
	$title = $value['etc_title'] . ' - ' . $recruitument_type;
}
else
{
	$title = $recruitument_type;
}


// --------------------------------------------------
//   コメント処理
// --------------------------------------------------

$comment = str_replace(array("\r\n","\r","\n"), ' ', $value['comment']);
$comment = (mb_strlen($comment) > 100) ? mb_substr($comment, 0, 99, 'UTF-8') . '…' : $comment;


// --------------------------------------------------
//   時間
// --------------------------------------------------

if ($value['sort_date'] == '-')
{
	$interval_time = '-';
}
else
{
	
	$datetime_sort = new DateTime($value['sort_date']);
	$interval = $datetime_now->diff($datetime_sort);
	
	if ($interval->format('%y') >= 1)
	{
		$interval_time = $interval->format('%y 年前');
	}
	else if ($interval->format('%m') >= 1)
	{
		$interval_time = $interval->format('%m ヶ月前');
	}
	else if ($interval->format('%d') >= 1)
	{
		$interval_time = $interval->format('%d 日前');
	}
	else if ($interval->format('%h') >= 1)
	{
		$interval_time = $interval->format('%h 時間前');
	}
	else if ($interval->format('%i') >= 1)
	{
		$interval_time = $interval->format('%i 分前');
	}
	else
	{
		$interval_time = $interval->format('%s 秒前');
	}
	
}


?>
<?php if ($app_mode): ?>
        <div<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'gc', 'id' => $game_names_arr[$value['game_no']]['id'], 'recruitment_id' => $value['recruitment_id']))), true); ?>>
          <h1 class="media-heading index_gc_recruitment_list_title"><span class="app_link_color"><?=$title?></span></h1>
          <p class="index_gc_recruitment_list_text"><?=$comment?></p>
        </div>
<?php else: ?>
        <h1 class="media-heading index_gc_recruitment_list_title"><a<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'gc', 'id' => $game_names_arr[$value['game_no']]['id'], 'recruitment_id' => $value['recruitment_id'])))); ?>><?=$title?></a></h1>
        <p class="index_gc_recruitment_list_text"><?=$comment?></p>
<?php endif; ?>
        <div class="community_list_label_box clearfix">
          <div class="original_label_game bgc_lightcoral"><?=$interval_time?></div> <div class="original_label_game bgc_lightseagreen"><?php echo '<a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'gc', 'id' => $game_names_arr[$value['game_no']]['id'])))) . ' class="orignal_label_game">' . $game_names_arr[$value['game_no']]['name'] . '</a>'?></div>
        </div>
      </div>
    </section>

<?php endforeach; ?>
