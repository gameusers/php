<?php

/*
 * 必要なデータ
 *
 * string / $datetime_now / アクセス時間算出のための日時
 *
 * string / $type / 種類 　gc gc_appoint uc uc_appoint
 * string / $type_gc_or_uc / 種類 　gc uc
 *
 * array / $thread_arr / スレッドの配列
 * array / $comment_arr / コメントの配列
 * array / $reply_arr / 返信の配列
 * array / $user_data_arr / ユーザーの配列
 * array / $profile_arr / プロフィールの配列
 * integer / $online_limit / オンライン表示される時間設定
 * integer / $anonymity / 匿名
 * array / $login_profile_data_arr / ログインユーザー情報の配列
 * array / $authority_arr / 権限の配列
 *
 * integer / $pagination_page / ページ
 * integer / $pagination_total / 総数
 * integer / $pagination_limit / 1ページの表示数
 * string / $pagination_function_name / 関数名
 * integer / $pagination_argument_arr / 関数引数
 *
 * オプション
 *
 */



// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_common_convert = new Original\Common\Convert();
$original_common_date = new Original\Common\Date();

$original_code_common = new Original\Code\Common();
$original_code_common->agent_type = AGENT_TYPE;
$original_code_common->uri_base = URI_BASE;


$pagination_comment_page = $pagination_comment_page ?? 1;

$pagination_comment_on = $pagination_comment_on ?? true;
$pagination_reply_on = $pagination_reply_on ?? true;

$s = (AGENT_TYPE) ? '_s' : null;


?>


<div class="bbs_box" id="bbs_box">

<?php foreach ($thread_arr as $key_thread => $value_thread): ?>
<?php

// --------------------------------------------------
//   編集権限の有無
// --------------------------------------------------

$authority_edit = false;

if (isset($profile_arr[$value_thread['profile_no']]))
{
	if ($profile_arr[$value_thread['profile_no']]['author_user_no'] == USER_NO) $authority_edit = true;
}
else if (isset($user_data_arr[$value_thread['user_no']]))
{
	if ($value_thread['user_no'] == USER_NO) $authority_edit = true;
}
else
{
	$datetime_past = $original_common_date->sql_format('-30 minutes');

	if ($value_thread['renewal_date'] > $datetime_past and $value_thread['host'] == HOST and $value_thread['user_agent'] == USER_AGENT) $authority_edit = true;
}


// --------------------------------------------------
//   BBSスレッド　パーソナルボックス
// --------------------------------------------------

$view_personal_box = View::forge('parts/personal_box_ver3_view');
$view_personal_box->set_safe('datetime_now', $datetime_now);
$view_personal_box->set_safe('regi_date', $value_thread['regi_date']);

if (isset($profile_arr[$value_thread['profile_no']]))
{
	$view_personal_box->set_safe('profile_arr', $profile_arr[$value_thread['profile_no']]);
}
else if (isset($user_data_arr[$value_thread['user_no']]))
{
	$view_personal_box->set_safe('profile_arr', $user_data_arr[$value_thread['user_no']]);
}

$view_personal_box->set_safe('online_limit', $online_limit);
if (isset($value_thread['handle_name'])) $view_personal_box->set_safe('handle_name', $value_thread['handle_name']);
if (isset($value_thread['anonymity'])) $view_personal_box->set_safe('anonymity', true);
$view_personal_box->set_safe('good_type', 'bbs_thread_' . $type_gc_or_uc);
$view_personal_box->set_safe('good_no', $value_thread['bbs_thread_no']);
$view_personal_box->set_safe('good', $value_thread['good']);

$code_personal_box = $view_personal_box->render();


// --------------------------------------------------
//   BBSスレッド　画像・動画
// --------------------------------------------------

$code_image_movie = null;

if (isset($value_thread['image']))
{
	$code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->image(URI_BASE . 'assets/img/bbs_' . $type_gc_or_uc . '/thread/' . $value_thread['bbs_thread_no'] . '/image_1.jpg', $value_thread['image']['image_1'], $value_thread['renewal_date']);
	$code_image_movie .= '        </div>' . "\n";
}
else if (isset($value_thread['movie']))
{
  $code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->movie($value_thread['movie']);
  $code_image_movie .= '        </div>' . "\n";
}

?>

  <article class="bbs_panel" id="bbs_thread_<?=$value_thread['bbs_thread_no']?>" data-anchor="<?=$value_thread['bbs_id']?>">

    <div class="heading">
      <h2>
        <a href="<?=BBS_URL?>/<?=$value_thread['bbs_id']?>" class="gc_recruitment_title_text" onclick="readBbsIndividual(this, '<?=$type_gc_or_uc?>', <?=$no?>, '<?=$value_thread['bbs_id']?>', 1, 0, 1, 1)" data-invalid-link="true"><?=$value_thread['title']?></a>
      </h2>
    </div>

    <div class="contents" id="bbs_thread_box">

      <div id="bbs_thread_content">

        <div class="pb<?=$s?>">
<?=$code_personal_box?>
        </div>

        <div class="comment_box<?=$s?>">
<?=$code_image_movie?>

          <p class="comment"><?php echo nl2br($original_common_convert->auto_linker($value_thread['comment'])); ?></p>
        </div>


        <div class="menu" id="control_comment_menu_main">

          <span class="date<?=$s?>"><?php echo $original_common_date->datetime_convert($value_thread['renewal_date'], $datetime_now); ?></span>

<?php if ($authority_edit): ?>
          <span class="button<?=$s?>"><button type="submit" class="btn btn-info btn-xs ladda-button" data-style="slide-right" data-size="xs" id="submit" onclick="showEditBbsThreadForm(this, '<?=$type_gc_or_uc?>', <?=$value_thread['bbs_thread_no']?>)">編集</button></span>
<?php elseif ($authority_arr['operate_bbs_delete'] or Auth::member(100)): ?>
          <span class="button<?=$s?>"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_delete" onclick="deleteBbsThread(this, '<?=$type_gc_or_uc?>', <?=$value_thread['bbs_thread_no']?>)">削除</button></span>
<?php endif; ?>

        </div>

      </div>

    </div>


<?php

// --------------------------------------------------
//   コメント投稿フォーム
// --------------------------------------------------

if ($type_gc_or_uc == 'gc' or ($type_gc_or_uc == 'uc' and $authority_arr['operate_bbs_comment']))
{

	echo '    <div class="bbs_comment_form' . $s . '" id="bbs_write_form">' . "\n";
  //echo '    <div id="bbs_write_form">' . "\n";
  //echo '    <hr class="style11">' . "\n";

	$view = View::forge('parts/form_common_ver2_view');
	$view->set_safe('datetime_now', $datetime_now);
	$view->set('profile_arr', $login_profile_data_arr);
	$view->set_safe('online_limit', $online_limit);
	$view->set_safe('anonymity', $anonymity);
  $view->set_safe('send_button_label', 'コメントする');
	$view->set('func_name', 'saveBbsComment');
	$view->set('func_argument_arr', array("'$type_gc_or_uc'", $value_thread['bbs_thread_no'], 'null'));
	$view->set_safe('title_off', true);
	echo $view->render();

	echo '    </div>' . "\n";
}

?>


<?php if (isset($comment_arr[$value_thread['bbs_thread_no']])): ?>

<?php

// --------------------------------------------------
//   BBS　コメント＆返信
// --------------------------------------------------

$view_comment_reply = View::forge('parts/bbs_comment_view');
$view_comment_reply->set_safe('datetime_now', $datetime_now);
$view_comment_reply->set_safe('online_limit', $online_limit);
$view_comment_reply->set_safe('authority_arr', $authority_arr);
$view_comment_reply->set_safe('type_gc_or_uc', $type_gc_or_uc);
$view_comment_reply->set_safe('no', $no);
$view_comment_reply->set_safe('comment_arr', $comment_arr[$value_thread['bbs_thread_no']]);
$view_comment_reply->set_safe('reply_arr', $reply_arr);
$view_comment_reply->set_safe('user_data_arr', $user_data_arr);
$view_comment_reply->set_safe('profile_arr', $profile_arr);

$view_comment_reply->set_safe('pagination_comment_on', $pagination_comment_on);
$view_comment_reply->set_safe('pagination_reply_on', $pagination_reply_on);

// ページャー　コメント
$view_comment_reply->set('pagination_comment_page', $pagination_comment_page);
$view_comment_reply->set('pagination_comment_total', $value_thread['comment_total']);

echo $view_comment_reply->render();

?>

<?php endif; ?>

  </article>

<?php endforeach; ?>


<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_total > $pagination_limit and empty($appoint))
{
	$view_pagination = View::forge('parts/pagination_view');
// \Debug::dump(BBS_URL);
  if ( ! defined('INDIVIDUAL')) $view_pagination->set_safe('url', BBS_URL);

	$view_pagination->set_safe('page', $pagination_page);
	$view_pagination->set_safe('total', $pagination_total);
	$view_pagination->set_safe('limit', $pagination_limit);
	$view_pagination->set_safe('times', $pagination_times);
	$view_pagination->set_safe('function_name', 'readBbs');
	$view_pagination->set_safe('argument_arr', $pagination_argument_arr);

	echo '<div class="margin_bottom_30">';
	echo $view_pagination->render();
	echo '</div>';
}

?>

<?php if (isset($individual)) : ?>
  <div class="btn-group" role="group">

    <a class="btn btn-default ladda-button" href="<?=BBS_URL . '/' . $value_thread['bbs_id']?>" role="button" data-style="expand-right" data-spinner-color="#000000" onclick="readBbs(this, 1, '<?=$type_gc_or_uc?>_appoint', <?=$value_thread['bbs_thread_no']?>, 1, 1, 1)" data-invalid-link="true"><span class="ladda-label">スレッドに戻る</span></a>

    <a class="btn btn-default ladda-button" href="<?=BBS_URL?>" role="button" data-style="expand-right" data-spinner-color="#000000" onclick="readBbs(this, 1, '<?=$type_gc_or_uc?>', <?=$no?>, 1, 1, 1)" data-invalid-link="true"><span class="ladda-label">他のスレッドを見る</span></a>

  </div>
<?php elseif (isset($appoint)): ?>
  <a class="btn btn-default ladda-button" href="<?=BBS_URL?>" role="button" data-style="expand-right" data-spinner-color="#000000" onclick="readBbs(this, 1, '<?=$type_gc_or_uc?>', <?=$no?>, 1, 1, 1)" data-invalid-link="true"><span class="ladda-label">他のスレッドを見る</span></a>
<?php endif; ?>

</div>
