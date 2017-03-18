<?php

/*
  必要なデータ

  オプション
*/

// --------------------------------------------------
//   変数設定
// --------------------------------------------------

// if ($type === 'gc')
// {
//   $model_game = new \Model_Game();
//   $db_game_data_arr = $model_game->get_game_data($no, null);
//   $game_community_id = $db_game_data_arr['id'];
// }

?>

<div class="panel panel-primary" id="bbs_thread_list_box">

  <div class="panel-heading">スレッド一覧</div>

  <div class="panel-body">

    <p class="margin_bottom_15"><a href="<?=BBS_URL?>" onclick="readBbs(this, 1, '<?=$type?>', <?=$no?>, 0, 1, 1)" data-invalid-link="true">まとめて表示する</a></p>

    <ul class="bbs_list">
<?php

$original_common_date = new \Original\Common\Date();
$datetime_past = $original_common_date->sql_format("-3days");

foreach ($thread_arr as $key => $value)
{

	echo '      <li class="bbs_list">' . "\n";
	echo '        <a href="' . BBS_URL . '/' . $value['bbs_id'] . '" class="bbs_list_title" onclick="readBbs(this, 1, \'' . $type . '_appoint\', ' . $value['bbs_thread_no'] . ', 0, 1, 1)" data-invalid-link="true">' . $value['title'] . ' (' . ($value['comment_total'] + $value['reply_total']) . ")\n";

	// NEW画像
	if ($datetime_past < $value['sort_date'])
	{
		$image_arr = array('newpink', 'neworange', 'newgreen');
		$random_key = array_rand($image_arr, 1);
		echo '        <img class="bbs_list_new_image" src="' . URI_BASE . 'assets/img/common/' . $image_arr[$random_key] . '.gif" />' . "\n";
	}

	echo '        </a>' . "\n";
	echo '      </li>' . "\n\n";

}

?>
    </ul>

<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_total > $pagination_limit)
{
	echo '    <div class="bbs_list_pagination">' . "\n";
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

  </div>

</div>
