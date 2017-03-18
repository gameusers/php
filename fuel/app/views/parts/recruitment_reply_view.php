<?php

/*
 * 必要なデータ
 *
 * integer / $login_user_no / ログインしているユーザーのユーザーNo
 * string / $datetime_now / アクセス時間算出のための日時
 * integer / $game_no / ゲームNo
 * integer / $online_limit / オンライン表示される時間設定
 *
 * string / $recruitment_id / 募集ID
 * integer / $open_type / ID・情報の公開方法
 * integer / $recruitment_user_no / 募集の著者User No
 * array / $db_recruitment_reply_arr / 返信の配列
 * array / $db_hardware_arr / ハードウェアの配列
 * array / $personal_box_user_arr / ユーザーの配列
 * array / $personal_box_profile_arr / プロフィールの配列
 * array / $ng_user_arr / NGユーザーの配列
 * array / $ng_id_arr / NG IDの配列
 * array / $write_users_arr / 書き込みユーザーの配列
 * array / $approval_users_arr / 承認ユーザーの配列
 * boolean / $deadline / 募集が締め切りになっている場合 true
 *
 * オプション
 *
 */
//$deadline = false;

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_common_convert = new Original\Common\Convert();
$original_common_date = new Original\Common\Date();

$original_code_common = new Original\Code\Common();
$original_code_common->agent_type = AGENT_TYPE;
$original_code_common->uri_base = URI_BASE;

$datetime_past = $original_common_date->sql_format('-30 minutes');

$s = (AGENT_TYPE) ? '_s' : null;

?>


<?php if (isset($db_recruitment_reply_arr[$recruitment_id])): ?>

<?php foreach ($db_recruitment_reply_arr[$recruitment_id] as $key_reply => $value_reply): ?>

<?php

// --------------------------------------------------
//   返信　パーソナルボックス
// --------------------------------------------------

$deleted_user = false;
$login_user = true;

$view_personal_box = View::forge('parts/personal_box_ver3_view');
$view_personal_box->set_safe('datetime_now', $datetime_now);
$view_personal_box->set_safe('regi_date', $value_reply['regi_date']);

if (isset($personal_box_profile_arr[$value_reply['profile_no']]))
{
	$view_personal_box->set_safe('profile_arr', $personal_box_profile_arr[$value_reply['profile_no']]);
}
else if (isset($personal_box_user_arr[$value_reply['user_no']]))
{
	$view_personal_box->set_safe('profile_arr', $personal_box_user_arr[$value_reply['user_no']]);
}
else
{
	if ($value_reply['user_no'] or $value_reply['profile_no']) $deleted_user = true;

	$login_user = false;
}

$view_personal_box->set_safe('online_limit', $online_limit);
if (isset($value_reply['handle_name'])) $view_personal_box->set_safe('handle_name', $value_reply['handle_name']);
if (isset($value_reply['anonymity'])) $view_personal_box->set_safe('anonymity', true);
$view_personal_box->set_safe('good_type', 'recruitment_reply');
$view_personal_box->set_safe('good_no', $value_reply['recruitment_reply_id']);
$view_personal_box->set_safe('good', $value_reply['good']);

$code_personal_box = $view_personal_box->render() . "\n";


// --------------------------------------------------
//   募集　画像・動画
// --------------------------------------------------

$code_image_movie = null;

if (isset($value_reply['image']))
{
  $code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->image(URI_BASE . 'assets/img/recruitment/reply/' . $value_reply['recruitment_reply_id'] . '/image_1.jpg', $value_reply['image']['image_1'], $value_reply['renewal_date']);
	$code_image_movie .= '        </div>' . "\n";
}
else if (isset($value_reply['movie']))
{
  $code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->movie($value_reply['movie']);
  $code_image_movie .= '        </div>' . "\n";
}

?>

          <div class="gc_reply_enclosure bre_margin<?=$s?>" id="recruitment_reply_<?=$value_reply['recruitment_reply_id']?>">

            <div id="gc_reply_content">

              <div class="pb<?=$s?>">
<?=$code_personal_box?>
              </div>

              <div class="comment_box<?=$s?>">
<?=$code_image_movie?>

                <p class="comment"><?php echo nl2br($original_common_convert->auto_linker($value_reply['comment'])); ?></p>
              </div>

            <!-- <div class="gc_reply_comment"><?php echo nl2br($original_common_convert->auto_linker($value_reply['comment'])); ?></div> -->

      	    <div class="gc_recruitment_reply_id_box">

<?php

// --------------------------------------------------
//   返信　公開条件
// --------------------------------------------------

$recruitment_open_type = null;
$recruitment_open_type_explanation = null;
$code_open_type = null;

if ($value_reply['open_type'] == 1)
{
	$recruitment_open_type = '誰にでも公開';
	$recruitment_open_type_explanation = 'このページにアクセスした人なら誰でもID・その他の情報を見ることができます。';
}
else if ($value_reply['open_type'] == 2)
{
	$recruitment_open_type = '募集者に公開';
	$recruitment_open_type_explanation = '募集者だけにID・その他の情報が公開されます。';
}
else
{
	$recruitment_open_type = '両者同意後に公開';
	$recruitment_open_type_explanation = '募集者が返信者に自分のID・情報を公開したときに、同時に相手のID・その他の情報を見れるようになります。';
}

$code_open_type = '      	  <div class="gc_recruitment_id_list">' . "\n";
$code_open_type .= '      	    <span class="label label-info gc_recruitment_id_label">公開条件</span><span class="gc_recruitment_id"><a href="javascript:void(0)" onclick="showIdExplanation(this, \'reply\', \'' . $value_reply['recruitment_reply_id'] . '\')">' . $recruitment_open_type . '</a></span>' . "\n";
$code_open_type .= '      	  </div>' . "\n\n";

$code_open_type .= '          <div class="collapse gc_recruitment_id_explanation" id="collapseReplyIdExplanation">' . "\n";
$code_open_type .= '            <div class="well">' . $recruitment_open_type_explanation . '</div>' . "\n";
$code_open_type .= '          </div>' . "\n\n";


// --------------------------------------------------
//   返信　ID
// --------------------------------------------------

//$id_explanation_type = null;
//$id_explanation_existence = null;
$code_id_info = null;

for ($i=1; $i <= 3; $i++) {

	// ------------------------------
	//    IDの種類
	// ------------------------------

	$hardware_label = null;

	if (isset($value_reply['id_hardware_no_' . $i], $value_reply['id_' . $i]))
	{
		$hardware_label = $db_hardware_arr[$value_reply['id_hardware_no_' . $i]]['abbreviation'];
	}
	else if (isset($value_reply['id_' . $i]))
	{
		$hardware_label = 'ID';
	}


	// ------------------------------
	//    IDの種類
	// ------------------------------

	$id = null;

	if ($hardware_label)
	{
		if ($deadline)
		{
			$id = '*****';
		}
		else if ($value_reply['open_type'] == 1)
		{
			$id = $value_reply['id_' . $i];
		}
		else if ($value_reply['open_type'] == 2)
		{

			if ($recruitment_user_no == $login_user_no)
			{
				$id = $value_reply['id_' . $i];
			}
			else
			{
				$id = '*****';
			}
		}
		else if ($value_reply['open_type'] == 3)
		{
			//$approval_users_arr = ($value['approval_users']) ? $value['approval_users'] : array();

			if ($recruitment_user_no == $login_user_no and in_array($value_reply['user_no'], $approval_users_arr))
			{
				$id = $value_reply['id_' . $i];
			}
			else
			{
				$id = '*****';
			}
		}

		$code_id_info .= '      	      <div class="gc_recruitment_id_list">' . "\n";
		$code_id_info .= '      	        <span class="label label-danger gc_recruitment_id_label">' . $hardware_label . '</span><span class="gc_recruitment_id">' . $id . '</span>' . "\n";
		$code_id_info .= '      	      </div>' . "\n\n";

	}

}


// --------------------------------------------------
//   返信　情報
// --------------------------------------------------

for ($i=1; $i <= 5; $i++) {

	$info = null;

	if ($value_reply['info_title_' . $i] and $value_reply['info_' . $i])
	{
		if ($deadline)
		{
			$info = '*****';
		}
		else if ($value_reply['open_type'] == 1)
		{
			$info = $value_reply['info_' . $i];
		}
		else if ($value_reply['open_type'] == 2)
		{
			if ($recruitment_user_no == $login_user_no)
			{
				$info = $value_reply['info_' . $i];
			}
			else
			{
				$info = '*****';
			}
		}
		else if ($value_reply['open_type'] == 3)
		{
			//$approval_users_arr = ($value['approval_users']) ? $value['approval_users'] : array();

			if ($recruitment_user_no == $login_user_no and in_array($value_reply['user_no'], $approval_users_arr))
			{
				$info = $value_reply['info_' . $i];
			}
			else
			{
				$info = '*****';
			}
		}

		$code_id_info .= '      	      <div class="gc_recruitment_id_list">' . "\n";
		$code_id_info .= '      	        <span class="label label-success gc_recruitment_id_label">' . $value_reply['info_title_' . $i] . '</span><span class="gc_recruitment_id">' . $info . '</span>' . "\n";
		$code_id_info .= '      	      </div>' . "\n\n";

	}

}


// --------------------------------------------------
//   返信　コード出力
// --------------------------------------------------

if (isset($code_id_info))
{
	echo $code_open_type;
	echo $code_id_info;
}

?>

<?php

// --------------------------------------------------
//   返信　通知方法
// --------------------------------------------------

$notification_method = '-';

if (isset($personal_box_user_arr[$value_reply['user_no']]))
{
	if ($personal_box_user_arr[$value_reply['user_no']]['notification_on_off'])
	{
    $notification_method_arr = [];

		if ($personal_box_user_arr[$value_reply['user_no']]['notification_data']['on_off_browser']) array_push($notification_method_arr, 'ブラウザ');

    if ($personal_box_user_arr[$value_reply['user_no']]['notification_data']['on_off_app']) array_push($notification_method_arr, 'アプリ');

    if ($personal_box_user_arr[$value_reply['user_no']]['notification_data']['on_off_mail']) array_push($notification_method_arr, 'メール');

    $notification_method = implode(' / ', $notification_method_arr);
	}
}

?>

      	      <div class="gc_recruitment_id_list">
      	        <span class="label label-warning gc_recruitment_reply_id_label">通知方法</span><span class="gc_recruitment_id"><?=$notification_method?></span>
      	      </div>

            </div>

<?php

// --------------------------------------------------
//   ID・情報公開ボタン
// --------------------------------------------------

 if ($open_type == 3 and isset($login_user_no) and $recruitment_user_no == $login_user_no and $value_reply['user_no'] != $login_user_no)
 {
 	if (in_array($value_reply['user_no'], $approval_users_arr))
	{
		$button_label_approval = '<span class="glyphicon glyphicon-exclamation-sign padding_right_5" aria-hidden="true"></span> ID・情報を公開中';
		$data_registration = 0;
	}
	else
	{
		$button_label_approval = 'ID・情報を公開する';
		$data_registration = 1;
	}

	// 返信者がログインしているユーザーの場合、ID・情報を公開するボタンを表示する
	if ($login_user)
	{
		$ar_user_no = 'null';
		$ar_profile_no = 'null';

		if ($value_reply['profile_no'])
		{
			$ar_profile_no = $value_reply['profile_no'];
		}
		else
		{
			$ar_user_no = $value_reply['user_no'];
		}

		echo '            <div class="gc_recruitment_reply_show_id_info"><button type="submit" class="btn btn-default btn-sm ladda-button" data-style="expand-right" data-spinner-color="#000000" data-registration="' . $data_registration . '" onclick="approvalRecruitment(this, \'' . $recruitment_id . '\', ' . $ar_user_no . ', ' . $ar_profile_no . ')"><span class="ladda-label">' . $button_label_approval . '</span></button></div>';

	}
 }

?>

            <div class="menu" id="">

              <span class="date<?=$s?>"><?php echo $original_common_date->datetime_convert($value_reply['renewal_date'], $datetime_now); ?></span>

<?php

// --------------------------------------------------
//   返信　編集ボタン
// --------------------------------------------------

$authority_edit = false;

if (isset($login_user_no) and $value_reply['user_no'] == $login_user_no)
{
	$authority_edit = true;
}
else if ($value_reply['renewal_date'] > $datetime_past)
{
	$author_reply_arr = (Session::get('author_reply_arr')) ? Session::get('author_reply_arr') : array();
	if (in_array($value_reply['recruitment_reply_id'], $author_reply_arr)) $authority_edit = true;
}


if ( ! $deleted_user and $authority_edit)
{

//if (isset($login_user_no) and $value_reply['user_no'] == $login_user_no)
//{
	echo '              <span class="button' . $s . '"><button type="submit" class="btn btn-info btn-xs ladda-button" data-style="slide-right" data-size="xs" id="submit" onclick="showRecruitmentForm(this, ' . $game_no . ', \'reply_edit\', \'' . $value_reply['recruitment_id'] . '\', \'' . $value_reply['recruitment_reply_id'] . '\')">編集</button></span>' . "\n";
}
else if ((isset($login_user_no) and $recruitment_user_no == $login_user_no) or Auth::member(100))
{
	echo '              <span class="button' . $s . '"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_delete" onclick="deleteRecruitment(this, \'' . $recruitment_id . '\', \'' . $value_reply['recruitment_reply_id'] . '\')">削除</button></span>' . "\n";
}

?>

<?php if ( ! $deleted_user) : ?>
              <span class="button<?=$s?>"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_reply" onclick="showRecruitmentForm(this, <?=$game_no?>, 'reply_new', '<?=$value_reply['recruitment_id']?>', '<?=$value_reply['recruitment_reply_id']?>')">返信</button></span>
<?php endif; ?>
            </div>

            </div>

          </div>

<?php endforeach; ?>

<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_total_reply > $pagination_limit_reply)
{

	echo '              <div style="padding: 20px 0 10px 10px">' . "\n";
	$view_pagination = View::forge('parts/pagination_view');
	$view_pagination->set_safe('page', $pagination_page_reply);
	$view_pagination->set_safe('total', $pagination_total_reply);
	$view_pagination->set_safe('limit', $pagination_limit_reply);
	$view_pagination->set_safe('times', $pagination_times_reply);
	$view_pagination->set_safe('function_name', $pagination_function_name_reply);
	$view_pagination->set_safe('argument_arr', $pagination_argument_arr_reply);
	echo $view_pagination->render();
	echo '              </div>' . "\n";

}

?>

<?php endif; ?>
