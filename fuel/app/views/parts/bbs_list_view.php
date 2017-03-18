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
$original_code_basic->app_mode = APP_MODE;

$datetime_now = new DateTime();

//var_dump($db_recruitment_arr);

// if (USER_NO == 1)
// {
	// echo 'APP_MODE = ' . APP_MODE;
// }

 ?>

 <?php foreach ($bbs_list_arr as $key => $value): ?>
    <section class="media padding_bottom_15 border_bottom_dashed">

      <a class="pull-left" <?php echo $original_code_basic->change_page_tag(array('uri_base' => URI_BASE, 'page' => array(array('type' => 'gc', 'id' => $value['game_id'], 'bbs_thread_no' => $value['bbs_thread_no'])))); ?>>
      	
<?php

if (isset($value['thumbnail']))
{
	echo '        <img class="media-object img-rounded" src="' . URI_BASE . 'assets/img/game/' . $value['game_no'] . '/thumbnail.jpg" width="64px" height="64px">';
}
else
{
	
	$datetime_thumbnail_none = new DateTime($value['regi_date']);
	$thumbnail_none_second = $datetime_thumbnail_none->format('s');
	$thumbnail_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
	
	echo '        <img class="media-object img-rounded" src="' . $thumbnail_url . '" width="64px" height="64px">';
}

?>
      </a>

      <div class="media-body media_list">
<?php

// --------------------------------------------------
//   タイトル
// --------------------------------------------------

if (isset($value['title']))
{
	$title = $value['title'];
}
else if ($value['anonymity'])
{
	$title = 'ななしさん';
}
else if ($value['profile_handle_name'])
{
	$title = $value['profile_handle_name'];
}
else if ($value['user_handle_name'])
{
	$title = $value['user_handle_name'];
}
else if ($value['handle_name'])
{
	$title = $value['handle_name'];
}
else
{
	$title = 'ななしさん';
}


// --------------------------------------------------
//   コメント処理
// --------------------------------------------------

$comment = str_replace(array("\r\n","\r","\n"), ' ', $value['comment']);
$comment = (mb_strlen($comment) > 100) ? mb_substr($comment, 0, 99, 'UTF-8') . '…' : $comment;


// --------------------------------------------------
//   時間
// --------------------------------------------------


	$datetime_sort = new DateTime($value['regi_date']);
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



?>
<?php if (APP_MODE): ?>
        <div<?php echo $original_code_basic->change_page_tag(array('uri_base' => URI_BASE, 'page' => array(array('type' => 'gc', 'id' => $value['game_id'], 'bbs_thread_no' => $value['bbs_thread_no']))), true); ?>>
          <h1 class="media-heading index_gc_recruitment_list_title"><span class="app_link_color"><?=$title?></span></h1>
          <p class="index_gc_recruitment_list_text"><?=$comment?></p>
        </div>
<?php else: ?>
        <h1 class="media-heading index_gc_recruitment_list_title"><a<?php echo $original_code_basic->change_page_tag(array('uri_base' => URI_BASE, 'page' => array(array('type' => 'gc', 'id' => $value['game_id'], 'bbs_thread_no' => $value['bbs_thread_no'])))); ?>><?=$title?></a></h1>
        <p class="index_gc_recruitment_list_text"><?=$comment?></p>
<?php endif; ?>
        <div class="community_list_label_box clearfix">
          <div class="original_label_game bgc_lightcoral"><?=$interval_time?></div> <div class="original_label_game bgc_lightseagreen"><?php echo '<a' . $original_code_basic->change_page_tag(array('uri_base' => URI_BASE, 'page' => array(array('type' => 'gc', 'id' => $value['game_id'])))) . ' class="orignal_label_game">' . $value['game_name'] . '</a>'?></div>
        </div>
      </div>
    </section>

<?php endforeach; ?>

<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_total > INDEX_LIMIT_BBS)
{
	echo '    <div class="margin_top_20">' . "\n";
	$view_pagination = View::forge('parts/pagination_view');
	$view_pagination->set_safe('page', $pagination_page);
	$view_pagination->set_safe('total', $pagination_total);
	$view_pagination->set_safe('limit', $pagination_limit);
	$view_pagination->set_safe('times', $pagination_times);
	$view_pagination->set_safe('function_name', $pagination_function_name);
	$view_pagination->set_safe('argument_arr', $pagination_argument_arr);
	echo $view_pagination->render();
	echo '    </div>' . "\n";
}

?>

