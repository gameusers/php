<?php

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_common_convert = new Original\Common\Convert();

$original_code_common = new Original\Code\Common();
$original_code_common->agent_type = AGENT_TYPE;
$original_code_common->uri_base = URI_BASE;

$original_common_date = new Original\Common\Date();


$s = (AGENT_TYPE) ? '_s' : null;



// --------------------------------------------------
//   パーソナルボックス
// --------------------------------------------------

$view_personal_box = View::forge('parts/personal_box_ver3_view');
$view_personal_box->set_safe('datetime_now', $datetime_now);
$view_personal_box->set_safe('regi_date', $announcement_regi_date);
$view_personal_box->set_safe('profile_arr', $profile_arr);
$view_personal_box->set_safe('online_limit', $online_limit);
$code_personal_box = $view_personal_box->render();


// --------------------------------------------------
//   画像・動画
// --------------------------------------------------

$code_image_movie = null;

if (isset($announcement_image_arr))
{
	$code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->image(URI_BASE . 'assets/img/announcement/' . $announcement_no . '/image_1.jpg', $announcement_image_arr, $announcement_renewal_date);
	$code_image_movie .= '        </div>' . "\n";
}
else if (isset($announcement_movie_arr))
{
  $code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->movie($announcement_movie_arr);
  $code_image_movie .= '        </div>' . "\n";
}

?>

<div id="announcement_box" class="announcement_panel <?php if ( ! $authority_arr['operate_announcement']) echo ' margin_bottom_40'; ?>" data-page="<?=$page?>">

  <h2 id="heading_black">告知</h2>

  <div class="contents" id="announcement_content_box">

    <div class="pb">
<?=$code_personal_box?>
    </div>

    <div class="comment_box<?=$s?>">
<?=$code_image_movie?>
      <p class="title"><?=$announcement_title?></p>
      <p class="comment"><?php echo nl2br($original_common_convert->auto_linker($announcement_comment)); ?></p>
    </div>

    <div class="menu">
      <span class="date"><?php echo $original_common_date->datetime_convert($announcement_renewal_date, $datetime_now); ?></span>
<?php if ($authority_arr['operate_announcement']): ?>
      <span class="button"><button type="submit" class="btn btn-info btn-xs ladda-button" data-style="slide-right" data-size="xs" id="submit_edit_announcement_form"  onclick="GAMEUSERS.uc.showAnnouncementForm(this, <?=$community_no?>, <?=$announcement_no?>)">編集</button></span>
<?php endif; ?>
    </div>


<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($total > $limit)
{
	echo '      <div class="pagination">' . "\n";
	$view_content_announcement = View::forge('parts/pagination_view');
	$view_content_announcement->set_safe('page', $page);
	$view_content_announcement->set_safe('total', $total);
	$view_content_announcement->set_safe('limit', $limit);
	$view_content_announcement->set_safe('times', $times);
	$view_content_announcement->set_safe('function_name', $function_name);
	$view_content_announcement->set_safe('argument_arr', $argument_arr);
	echo $view_content_announcement->render();
	echo '      </div>' . "\n";

}

?>

  </div>

</div>
