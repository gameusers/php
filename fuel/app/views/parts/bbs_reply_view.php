<?php

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_common_convert = new Original\Common\Convert();
$original_common_date = new Original\Common\Date();

$original_code_common = new Original\Code\Common();
$original_code_common->agent_type = AGENT_TYPE;
$original_code_common->uri_base = URI_BASE;

$pagination_reply_on = $pagination_reply_on ?? true;

$s = (AGENT_TYPE) ? '_s' : null;

// \Debug::dump($authority_arr);
?>

<?php foreach ($reply_arr as $key => $value): ?>
<?php

// --------------------------------------------------
//   初期設定
// --------------------------------------------------

$authority_edit = false;
$deleted_user = false;

$renewal_date = null;
$user_no = null;
$profile_no = null;
$thumbnail = null;
$handle_name = null;
$level_id = null;
$level = null;
$status = null;
$link = false;

if (isset($value['anonymity']))
{
	$user_no = $value['user_no'];
	$handle_name = '匿名';
	$status = 'Anonymity';
}
else if (isset($value['profile_no']))
{
	$user_no = $value['user_no'];
	$profile_no = $value['profile_no'];

	if (isset($profile_arr[$profile_no], $profile_arr[$profile_no]['on_off']))
	{
		$renewal_date = $profile_arr[$profile_no]['renewal_date'];
		$handle_name = $profile_arr[$profile_no]['handle_name'];
		$level_id = 'level_profile_' . $profile_no;
		$level = $profile_arr[$profile_no]['level'];
		$status = $profile_arr[$profile_no]['status'];
		$user_id = $profile_arr[$profile_no]['user_id'];
		if ($profile_arr[$profile_no]['open_profile']) $link = true;

		if ($profile_arr[$profile_no]['thumbnail']) $thumbnail = true;
		//var_dump($profile_arr);
		//if ($profile_arr[$profile_no]['author_user_no'] == $login_user_no) $authority_edit = true;
	}
	else
	{
		$deleted_user = true;
	}



}
else if (isset($value['user_no']))
{
	$user_no = $value['user_no'];
	$profile_no = null;

	if (isset($user_data_arr[$user_no], $user_data_arr[$user_no]['on_off']))
	{
		$renewal_date = $user_data_arr[$user_no]['renewal_date'];
		$handle_name = $user_data_arr[$user_no]['handle_name'];
		$level_id = 'level_user_' . $user_no;
		$level = $user_data_arr[$user_no]['level'];
		$status = $user_data_arr[$user_no]['status'];
		$user_id = $user_data_arr[$user_no]['user_id'];
		$link = true;

		if ($user_data_arr[$user_no]['thumbnail']) $thumbnail = true;
		//var_dump($user_data_arr);
		//if ($user_no == $login_user_no) $authority_edit = true;
	}
	else
	{
		$deleted_user = true;
	}

}
else
{
	$handle_name = $value['handle_name'];
	$status = '一般ユーザー';
}

if ($deleted_user)
{
	$handle_name = '削除済みユーザー';
	$status = 'Deleted';
}


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

if ($link)
{
	$original_code_basic = new Original\Code\Basic();
	$original_code_basic->app_mode = APP_MODE;
}



// --------------------------------------------------
//   返信　パーソナルボックス
// --------------------------------------------------

$view_personal_box = View::forge('parts/personal_box_ver3_view');
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
$view_personal_box->set_safe('good_type', 'bbs_reply_' . $type_gc_or_uc);
$view_personal_box->set_safe('good_no', $value['bbs_reply_no']);
$view_personal_box->set_safe('good', $value['good']);

$code_personal_box = $view_personal_box->render() . "\n";


// --------------------------------------------------
//   BBS返信　画像・動画
// --------------------------------------------------

$code_image_movie = null;

if (isset($value['image']))
{
  $code_image_movie = '        <div class="image_movie">';
  $code_image_movie .= $original_code_common->image(URI_BASE . 'assets/img/bbs_' . $type_gc_or_uc . '/reply/' . $value['bbs_reply_no'] . '/image_1.jpg', $value['image']['image_1'], $value['renewal_date']);
	$code_image_movie .= '        </div>' . "\n";
}
else if (isset($value['movie']))
{
  $code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->movie($value['movie']);
  $code_image_movie .= '        </div>' . "\n";
}


?>
        <div class="bbs_reply_enclosure bre_margin<?=$s?>" id="bbs_reply_<?=$value['bbs_reply_no']?>" data-anchor="<?=$value['bbs_id']?>">

          <div id="bbs_reply_content">

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
              <span class="button<?=$s?>"><button type="submit" class="btn btn-info btn-xs ladda-button" data-style="slide-right" data-size="xs" id="submit" onclick="showEditBbsReplyForm(this, '<?=$type_gc_or_uc?>', <?=$value['bbs_reply_no']?>)">編集</button></span>
<?php elseif ($authority_arr['operate_bbs_delete'] or Auth::member(100)): ?>
              <span class="button<?=$s?>"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_delete" onclick="deleteBbsReply(this, '<?=$type_gc_or_uc?>', <?=$value['bbs_reply_no']?>)">削除</button></span>
<?php elseif ($authority_arr['operate_bbs_comment']): ?>
              <span class="button<?=$s?>"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_reply" onclick="showWriteBbsReplyForm(this, '<?=$type_gc_or_uc?>', <?=$value['bbs_comment_no']?>, <?=$value['bbs_reply_no']?>)">返信</button></span>
<?php endif; ?>
            </div>

          </div>

        </div>

<?php endforeach; ?>

<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_total > LIMIT_BBS_REPLY and $pagination_reply_on)
{
	echo '    <div style="padding:15px 0 10px 10px">' . "\n\n";
  echo '      <div style="padding:0 0 5px 0">返信をもっと見る</div>' . "\n\n";

	$view_pagination = View::forge('parts/pagination_view');
	$view_pagination->set_safe('page', $pagination_page);
	$view_pagination->set_safe('total', $pagination_total);
	$view_pagination->set_safe('limit', LIMIT_BBS_REPLY);
	$view_pagination->set_safe('times', PAGINATION_TIMES);
	$view_pagination->set_safe('function_name', 'readBbsReply');
	$view_pagination->set_safe('argument_arr', array("'$type_gc_or_uc'", $value['bbs_comment_no']));
	echo $view_pagination->render() . "\n\n";

	echo '    </div>' . "\n\n";
}

?>
