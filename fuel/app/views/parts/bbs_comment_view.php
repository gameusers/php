<?php

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_common_convert = new Original\Common\Convert();
$original_common_date = new Original\Common\Date();

$original_code_common = new Original\Code\Common();
$original_code_common->agent_type = AGENT_TYPE;
$original_code_common->uri_base = URI_BASE;

$pagination_comment_on = $pagination_comment_on ?? true;
$pagination_reply_on = $pagination_reply_on ?? true;

$s = (AGENT_TYPE) ? '_s' : null;

?>


<div id="bbs_comment_box">

<?php foreach ($comment_arr as $key => $value): ?>
<?php

// --------------------------------------------------
//   編集権限の有無
// --------------------------------------------------

$authority_edit = false;

if (isset($profile_arr[$value['profile_no']]))
{
	if ($profile_arr[$value['profile_no']]['author_user_no'] == USER_NO) $authority_edit = true;
}
else if (isset($user_data_arr[$value['user_no']]))
{
	if ($value['user_no'] == USER_NO) $authority_edit = true;
}
else
{
	// 日時
	$original_common_date = new Original\Common\Date();
	$datetime_past = $original_common_date->sql_format('-30 minutes');

	if ($value['renewal_date'] > $datetime_past and $value['host'] == HOST and $value['user_agent'] == USER_AGENT) $authority_edit = true;
}


// --------------------------------------------------
//   パーソナルボックス
// --------------------------------------------------

$view_personal_box = View::forge('parts/personal_box_ver3_view');
$view_personal_box->set_safe('app_mode', APP_MODE);
$view_personal_box->set_safe('uri_base', URI_BASE);
$view_personal_box->set_safe('datetime_now', $datetime_now);
$view_personal_box->set_safe('regi_date', $value['regi_date']);

if (isset($profile_arr[$value['profile_no']]))
{
	$view_personal_box->set_safe('profile_arr', $profile_arr[$value['profile_no']]);
}
else if (isset($user_data_arr[$value['user_no']]))
{
	$view_personal_box->set_safe('profile_arr', $user_data_arr[$value['user_no']]);
}

$view_personal_box->set_safe('online_limit', $online_limit);
if (isset($value['handle_name'])) $view_personal_box->set_safe('handle_name', $value['handle_name']);
if (isset($value['anonymity'])) $view_personal_box->set_safe('anonymity', true);
$view_personal_box->set_safe('good_type', 'bbs_comment_' . $type_gc_or_uc);
$view_personal_box->set_safe('good_no', $value['bbs_comment_no']);
$view_personal_box->set_safe('good', $value['good']);

$code_personal_box = $view_personal_box->render() . "\n";


// --------------------------------------------------
//   BBSコメント　画像・動画
// --------------------------------------------------

$code_image_movie = null;

if (isset($value['image']))
{
	$code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->image(URI_BASE . 'assets/img/bbs_' . $type_gc_or_uc . '/comment/' . $value['bbs_comment_no'] . '/image_1.jpg', $value['image']['image_1'], $value['renewal_date']);
	$code_image_movie .= '        </div>' . "\n";
}
else if (isset($value['movie']))
{
	//echo $original_code_common->movie($value['movie']);
  $code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->movie($value['movie']);
  $code_image_movie .= '        </div>' . "\n";
}

?>

  <hr class="style-two">

  <div class="bbs_comment_box" id="bbs_comment_<?=$value['bbs_comment_no']?>" data-anchor="<?=$value['bbs_id']?>">

    <div id="bbs_comment_only">

      <div id="bbs_comment_content">

        <div class="pb<?=$s?>">
<?=$code_personal_box?>
        </div>

        <div class="comment_box<?=$s?>">
<?=$code_image_movie?>

          <p class="comment"><?php echo nl2br($original_common_convert->auto_linker($value['comment'])); ?></p>
        </div>


        <div class="menu" id="control_comment_menu_main">

          <span class="date<?=$s?>">
            <a href="<?=BBS_URL?>/<?=$value['bbs_id']?>" onclick="readBbsIndividual(this, '<?=$type_gc_or_uc?>', <?=$no?>, '<?=$value['bbs_id']?>', 1, 1, 1, 1)" data-invalid-link="true">
              <?php echo $original_common_date->datetime_convert($value['renewal_date'], $datetime_now); ?>
            </a>
          </span>

<?php if ($authority_edit): ?>
          <span class="button<?=$s?>"><button type="submit" class="btn btn-info btn-xs ladda-button" data-style="slide-right" data-size="xs" id="submit" onclick="showEditBbsCommentForm(this, '<?=$type_gc_or_uc?>', <?=$value['bbs_comment_no']?>)">編集</button></span>
<?php elseif ($authority_arr['operate_bbs_delete'] or Auth::member(100)): ?>
          <span class="button<?=$s?>"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_delete" onclick="deleteBbsComment(this, '<?=$type_gc_or_uc?>', <?=$value['bbs_comment_no']?>)">削除</button></span>
<?php endif; ?>
<?php if ($authority_arr['operate_bbs_comment'] or $type_gc_or_uc == 'gc'): ?>
          <span class="button<?=$s?>"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_reply" onclick="showWriteBbsReplyForm(this, '<?=$type_gc_or_uc?>', <?=$value['bbs_comment_no']?>, null)">返信</button></span>
<?php endif; ?>

        </div>

      </div>

    </div>


<?php

// --------------------------------------------------
//   返信
// --------------------------------------------------

echo '      <div class="bbs_reply_box" id="bbs_reply_box">' . "\n\n";

if (isset($reply_arr[$value['bbs_comment_no']]))
{

	$view_reply = View::forge('parts/bbs_reply_view');
	$view_reply->set_safe('datetime_now', $datetime_now);
	$view_reply->set_safe('authority_arr', $authority_arr);
	$view_reply->set_safe('online_limit', $online_limit);
	$view_reply->set_safe('reply_arr', $reply_arr[$value['bbs_comment_no']]);
	$view_reply->set_safe('user_data_arr', $user_data_arr);
	$view_reply->set_safe('profile_arr', $profile_arr);
	$view_reply->set_safe('type_gc_or_uc', $type_gc_or_uc);
  $view_reply->set_safe('no', $no);
  $view_reply->set_safe('pagination_reply_on', $pagination_reply_on);
	$view_reply->set_safe('pagination_page', 1);
	$view_reply->set_safe('pagination_total', $value['reply_total']);
	echo $view_reply->render();

}

echo '      </div>' . "\n\n";

?>

  </div>

<?php endforeach; ?>



<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_comment_total > LIMIT_BBS_COMMENT and $pagination_comment_on)
{

	echo '    <div style="padding:15px 0 15px 10px">' . "\n\n";
	echo '      <div style="padding:0 0 5px 0">コメントをもっと見る</div>' . "\n\n";

	$view_pagination = View::forge('parts/pagination_view');
//\Debug::dump(BBS_ID);

  if (defined('INDIVIDUAL'))

  {
    // \Debug::dump('INDIVIDUAL定義済み');
    $url = (defined('BBS_ID')) ? BBS_URL . '/' .  BBS_ID : null;
    $view_pagination->set_safe('url', $url);
  }
  // else
  // {
  //   \Debug::dump('INDIVIDUAL未定義');
  // }
  //
  // if (defined('BBS_ID'))
  // {
  //   \Debug::dump('BBS_ID定義済み');
  // }
  // else
  // {
  //   \Debug::dump('BBS_ID未定義');
  // }

	$view_pagination->set_safe('page', $pagination_comment_page);
	$view_pagination->set_safe('total', $pagination_comment_total);
	$view_pagination->set_safe('limit', LIMIT_BBS_COMMENT);
	$view_pagination->set_safe('times', PAGINATION_TIMES);
	$view_pagination->set_safe('function_name', 'readBbsComment');
	$view_pagination->set_safe('argument_arr', array("'$type_gc_or_uc'", $value['bbs_thread_no'], 1, 1, 1));
	echo $view_pagination->render() . "\n\n";

	echo '    </div>' . "\n\n";

}

?>

</div>
