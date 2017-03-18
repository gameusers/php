<?php

/*
 * 必要なデータ
 *
 * integer / $login_user_no / ログインしているユーザーのユーザーNo
 * string / $datetime_now / アクセス時間算出のための日時
 * integer / $game_no / ゲームNo
 * integer / $online_limit / オンライン表示される時間設定
 *
 * array / $db_recruitment_arr / 募集の配列
 * array / $db_recruitment_reply_arr / 返信の配列
 * array / $db_hardware_arr / ハードウェアの配列
 * array / $personal_box_user_arr / ユーザーの配列
 * array / $personal_box_profile_arr / プロフィールの配列
 *
 * integer / $pagination_page / ページ
 * integer / $pagination_total / 総数
 * integer / $pagination_limit / 1ページの表示数
 * integer / $pagination_times / 番号の表示数
 * string / $pagination_function_name / 関数名
 * integer / $pagination_argument_arr / 関数引数
 *
 * オプション
 * boolean / $appoint / 1つの募集を指定して読み込んだ場合　ページャーなしにする
 */



// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_common_convert = new Original\Common\Convert();
$original_common_date = new Original\Common\Date();

$original_code_common = new Original\Code\Common();
$original_code_common->agent_type = AGENT_TYPE;
$original_code_common->uri_base = URI_BASE;

$datetime_now_obj = new DateTime($datetime_now);
$datetime_past = $original_common_date->sql_format('-30 minutes');

$s = (AGENT_TYPE) ? '_s' : null;

?>

<?php if (isset($db_recruitment_arr)): ?>

<div class="recruitment_box" id="recruitment_box">

<?php foreach ($db_recruitment_arr as $key => $value): ?>

<?php

// --------------------------------------------------
//    募集の種類
// --------------------------------------------------

if ($value['type'] == 1) $recruitument_type = 'プレイヤー募集';
else if ($value['type'] == 2) $recruitument_type = 'フレンド募集';
else if ($value['type'] == 3) $recruitument_type = 'ギルド・クランメンバー募集';
else if ($value['type'] == 4) $recruitument_type = '売買・交換相手募集';
else if ($value['type'] == 5) $recruitument_type = 'その他の募集';


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

if (isset($game_id) and empty($appoint))
{
	$code_title = '<a class="gc_recruitment_title_text" href="' . URI_BASE . 'gc/' . $game_id . '/rec/' . $value['recruitment_id'] . '" onclick="readRecruitment(this, 1, ' . $game_no . ', \'' . $value['recruitment_id'] . '\', 0, 1, 1)" data-invalid-link="true">' . $title . '</a>';
}
else
{
	$code_title = $title;
}


// --------------------------------------------------
//    書き込みユーザー＆承認ユーザー
// --------------------------------------------------

$write_users_arr = ($value['write_users']) ? $value['write_users'] : array();
$approval_users_arr = ($value['approval_users']) ? $value['approval_users'] : array();


// --------------------------------------------------
//    アクセスしたユーザーがID、情報を見る権限があるか
// --------------------------------------------------

$id_info_open_authority = (isset($value['user_no'], $login_user_no) and $value['user_no'] == $login_user_no) ? true : false;



// --------------------------------------------------
//   募集　パーソナルボックス
// --------------------------------------------------

$deleted_user = false;

$view_personal_box = View::forge('parts/personal_box_ver3_view');
$view_personal_box->set_safe('datetime_now', $datetime_now);
$view_personal_box->set_safe('regi_date', $value['regi_date']);

if (isset($personal_box_profile_arr[$value['profile_no']]))
{
	$view_personal_box->set_safe('profile_arr', $personal_box_profile_arr[$value['profile_no']]);
}
else if (isset($personal_box_user_arr[$value['user_no']]))
{
	$view_personal_box->set_safe('profile_arr', $personal_box_user_arr[$value['user_no']]);
}
else
{
	if ($value['user_no'] or $value['profile_no']) $deleted_user = true;
}

$view_personal_box->set_safe('online_limit', $online_limit);
if (isset($value['handle_name'])) $view_personal_box->set_safe('handle_name', $value['handle_name']);
if (isset($value['anonymity'])) $view_personal_box->set_safe('anonymity', true);
$view_personal_box->set_safe('good_type', 'recruitment');
$view_personal_box->set_safe('good_no', $value['recruitment_id']);
$view_personal_box->set_safe('good', $value['good']);

$code_personal_box = $view_personal_box->render() . "\n";


// --------------------------------------------------
//   募集　画像・動画
// --------------------------------------------------

$code_image_movie = null;

if (isset($value['image']))
{
	$code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->image(URI_BASE . 'assets/img/recruitment/recruitment/' . $value['recruitment_id'] . '/image_1.jpg', $value['image']['image_1'], $value['renewal_date']);
	$code_image_movie .= '        </div>' . "\n";
}
else if (isset($value['movie']))
{
  $code_image_movie = '        <div class="image_movie">';
	$code_image_movie .= $original_code_common->movie($value['movie']);
  $code_image_movie .= '        </div>' . "\n";
}

?>

  <article class="rec_panel" id="recruitment_<?=$value['recruitment_id']?>">

    <div class="heading">
      <h2 id="recruitment_title"><?=$code_title?></h2>
    </div>

    <div class="contents">

      <div id="recruitment_content">

        <div class="pb<?=$s?>">
<?=$code_personal_box?>
        </div>

        <div class="comment_box<?=$s?>">
<?=$code_image_movie?>

          <p class="comment"><?php echo nl2br($original_common_convert->auto_linker($value['comment'])); ?></p>
        </div>


        <div class="recruitment_id_box">

<?php

// --------------------------------------------------
//   募集　募集期間
// --------------------------------------------------

$deadline = false;

if ($value['limit_date'])
{
	$datetime_limit = new DateTime($value['limit_date']);
	$interval = $datetime_now_obj->diff($datetime_limit);

	if ($datetime_now_obj > $datetime_limit)
	{
		$interval_time = '<span class="gc_recruitment_deadline">締め切り</span>';
		$deadline = true;
	}
	else if ($interval->format('%d') >= 1)
	{
		$interval_time = $interval->format('残り %a 日');
	}
	else if ($interval->format('%h') >= 1)
	{
		$interval_time = $interval->format('残り %h 時間');
	}
	else
	{
		$interval_time = $interval->format('残り %i 分');
	}
}
else
{
	$interval_time = '-';
}


// --------------------------------------------------
//   募集　公開条件
// --------------------------------------------------

$recruitment_open_type = null;
$recruitment_open_type_explanation = null;
$code_open_type = null;

if ($value['open_type'] == 1)
{
	$recruitment_open_type = '誰にでも公開';
	$recruitment_open_type_explanation = 'このページにアクセスした人なら誰でもID・その他の情報を見ることができます。';
}
else if ($value['open_type'] == 2)
{
	$recruitment_open_type = '返信者に公開（全員）';
	$recruitment_open_type_explanation = 'ログインして返信しましょう！返信した人全員にID・その他の情報が公開されます。';
}
else
{
	$recruitment_open_type = '返信者に公開（選択）';
	$recruitment_open_type_explanation = 'ログインして返信しましょう！募集者がログインして返信した人の中からID・その他の情報を公開する相手を決めます。';
}

$code_open_type = '      	  <div class="gc_recruitment_id_list">' . "\n";
$code_open_type .= '      	    <span class="label label-info gc_recruitment_id_label">公開条件</span><span class="gc_recruitment_id"><a href="javascript:void(0)" onclick="showIdExplanation(this, \'recruitment\', \'' . $value['recruitment_id'] . '\')">' . $recruitment_open_type . '</a></span>' . "\n";
$code_open_type .= '      	  </div>' . "\n\n";

$code_open_type .= '          <div class="collapse gc_recruitment_id_explanation" id="collapseRecruitmentIdExplanation">' . "\n";
$code_open_type .= '            <div class="well">' . $recruitment_open_type_explanation . '</div>' . "\n";
$code_open_type .= '          </div>' . "\n\n";


// --------------------------------------------------
//   募集　ID
// --------------------------------------------------

$code_id_info = null;

for ($i=1; $i <= 3; $i++) {

	// ------------------------------
	//    IDの種類
	// ------------------------------

	$hardware_label = null;

	if (isset($value['id_hardware_no_' . $i], $value['id_' . $i]))
	{
		$hardware_label = $db_hardware_arr[$value['id_hardware_no_' . $i]]['abbreviation'];
	}
	else if (isset($value['id_' . $i]))
	{
		$hardware_label = 'ID';
	}


	// ------------------------------
	//    ID
	// ------------------------------

	$id = null;

	if ($hardware_label)
	{

		if ($deadline)
		{
			$id = '*****';
		}
		else if ($value['open_type'] == 1)
		{
			$id = $value['id_' . $i];
			$id_info_open_authority = true;
		}
		else if ($value['open_type'] == 2)
		{
			//$write_users_arr = ($value['write_users']) ? $value['write_users'] : array();

			if ($value['user_no'] != $login_user_no and in_array($login_user_no, $write_users_arr))
			{
				$id = $value['id_' . $i];
				$id_info_open_authority = true;
			}
			else
			{
				$id = '*****';
			}
		}
		else if ($value['open_type'] == 3)
		{
			//$approval_users_arr = ($value['approval_users']) ? $value['approval_users'] : array();

			if ($value['user_no'] != $login_user_no and in_array($login_user_no, $approval_users_arr))
			{
				$id = $value['id_' . $i];
				$id_info_open_authority = true;
			}
			else
			{
				$id = '*****';
			}
		}

		$code_id_info .= '          <div class="gc_recruitment_id_list">' . "\n";
		$code_id_info .= '            <span class="label label-danger gc_recruitment_id_label">' . $hardware_label . '</span><span class="gc_recruitment_id">' . $id . '</span>' . "\n";
		$code_id_info .= '          </div>' . "\n\n";

	}

}


// --------------------------------------------------
//   募集　情報
// --------------------------------------------------

for ($i=1; $i <= 5; $i++) {

	$info = null;

	if ($value['info_title_' . $i] and $value['info_' . $i])
	{

		if ($deadline)
		{
			$info = '*****';
		}
		else if ($value['open_type'] == 1)
		{
			$info = $value['info_' . $i];
		}
		else if ($value['open_type'] == 2)
		{
			//$write_users_arr = ($value['write_users']) ? $value['write_users'] : array();

			if ($value['user_no'] != $login_user_no and in_array($login_user_no, $write_users_arr))
			{
				$info = $value['info_' . $i];
			}
			else
			{
				$info = '*****';
			}
		}
		else if ($value['open_type'] == 3)
		{
			//$approval_users_arr = ($value['approval_users']) ? $value['approval_users'] : array();

			if ($value['user_no'] != $login_user_no and in_array($login_user_no, $approval_users_arr))
			{
				$info = $value['info_' . $i];
			}
			else
			{
				$info = '*****';
			}
		}

		$code_id_info .= '          <div class="gc_recruitment_id_list">' . "\n";
		$code_id_info .= '            <span class="label label-success gc_recruitment_id_label">' . $value['info_title_' . $i] . '</span><span class="gc_recruitment_id">' . $info . '</span>' . "\n";
		$code_id_info .= '          </div>' . "\n";

	}

}


// --------------------------------------------------
//   募集　コード出力
// --------------------------------------------------

if (isset($code_id_info))
{
	echo $code_open_type;
	echo $code_id_info;
}

?>

          <div class="gc_recruitment_id_list">
            <span class="label label-primary gc_recruitment_id_label">募集期間</span><span class="gc_recruitment_id"><?=$interval_time?></span>
          </div>

<?php

// --------------------------------------------------
//   募集　通知方法
// --------------------------------------------------

$notification_method = '-';

if (isset($personal_box_user_arr[$value['user_no']]))
{
	if ($personal_box_user_arr[$value['user_no']]['notification_on_off'])
	{
    $notification_method_arr = [];

		if ($personal_box_user_arr[$value['user_no']]['notification_data']['on_off_browser']) array_push($notification_method_arr, 'ブラウザ');

    if ($personal_box_user_arr[$value['user_no']]['notification_data']['on_off_app']) array_push($notification_method_arr, 'アプリ');

    if ($personal_box_user_arr[$value['user_no']]['notification_data']['on_off_mail']) array_push($notification_method_arr, 'メール');

    $notification_method = implode(' / ', $notification_method_arr);
	}
}

?>

          <div class="gc_recruitment_id_list">
            <span class="label label-warning gc_recruitment_id_label">通知方法</span><span class="gc_recruitment_id"><?=$notification_method?></span>
          </div>

        </div>

        <div class="menu" id="control_comment_menu_main">
          <span class="date<?=$s?>"><?php echo $original_common_date->datetime_convert($value['renewal_date'], $datetime_now); ?></span>

<?php

// --------------------------------------------------
//   募集　編集ボタン
// --------------------------------------------------

$authority_edit = false;

if (isset($login_user_no) and $value['user_no'] == $login_user_no)
{
	$authority_edit = true;
}
else if ($value['renewal_date'] > $datetime_past)
{
	$author_recruitment_arr = (Session::get('author_recruitment_arr')) ? Session::get('author_recruitment_arr') : array();
	if (in_array($value['recruitment_id'], $author_recruitment_arr)) $authority_edit = true;
}


if ($authority_edit)
{
	echo '          <span class="button' . $s . '"><button type="submit" class="btn btn-info btn-xs ladda-button" data-style="slide-right" data-size="xs" id="submit" onclick="showRecruitmentForm(this, ' . $game_no . ', \'recruitment_edit\', \'' . $value['recruitment_id'] . '\', null)">編集</button></span>' . "\n";
}
else if (Auth::member(100))
{
	echo '          <span class="button' . $s . '"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_delete" onclick="deleteRecruitment(this, \'' . $value['recruitment_id'] . '\', null)">削除</button></span>' . "\n";
}

?>

<?php if ( ! $deleted_user) : ?>
          <span class="button<?=$s?>"><button type="submit" class="btn btn-default btn-xs ladda-button" data-style="slide-right" data-size="xs" data-spinner-color="#000000" id="submit_reply" onclick="showRecruitmentForm(this, <?=$game_no?>, 'reply_new', '<?=$value['recruitment_id']?>', null)">返信</button></span>
<?php endif; ?>
        </div>

      </div>




        <div id="recruitment_reply_box">
<?php

// --------------------------------------------------
//   返信
// --------------------------------------------------

if (isset($db_recruitment_reply_arr[$value['recruitment_id']]))
{

	$view_reply = View::forge('parts/recruitment_reply_view');
	$view_reply->set_safe('login_user_no', $login_user_no);
	$view_reply->set_safe('datetime_now', $datetime_now);
	$view_reply->set_safe('online_limit', $online_limit);
	$view_reply->set_safe('game_no', $game_no);

	$view_reply->set_safe('recruitment_id', $value['recruitment_id']);
	$view_reply->set_safe('open_type', $value['open_type']);
	$view_reply->set_safe('recruitment_user_no', $value['user_no']);
	$view_reply->set_safe('db_recruitment_reply_arr', $db_recruitment_reply_arr);
	$view_reply->set_safe('db_hardware_arr', $db_hardware_arr);
	$view_reply->set_safe('personal_box_user_arr', $personal_box_user_arr);
	$view_reply->set_safe('personal_box_profile_arr', $personal_box_profile_arr);
	$view_reply->set_safe('ng_user_arr', $ng_user_arr);
	$view_reply->set_safe('ng_id_arr', $ng_id_arr);
	$view_reply->set_safe('write_users_arr', $write_users_arr);
	$view_reply->set_safe('approval_users_arr', $approval_users_arr);
	$view_reply->set_safe('deadline', $deadline);

	$view_reply->set_safe('pagination_page_reply', $pagination_page_reply);
	$view_reply->set_safe('pagination_total_reply', $value['reply_total_' . $language]);
	$view_reply->set_safe('pagination_limit_reply', $pagination_limit_reply);
	$view_reply->set_safe('pagination_times_reply', $pagination_times_reply);
	$view_reply->set_safe('pagination_function_name_reply', $pagination_function_name_reply);
	$view_reply->set_safe('pagination_argument_arr_reply', array($game_no, "'" . $value['recruitment_id'] . "'"));

	echo $view_reply->render();

}

?>
        </div>




    </div>

  </article>


<?php endforeach; ?>

<?php if ($more_button) : ?>
      <a class="btn btn-default ladda-button" href="<?=URI_BASE?>gc/<?=$game_id?>/rec" role="button" data-style="expand-right" data-spinner-color="#000000" onclick="readRecruitment(this, 1, <?=$game_no?>, null, 1, 1, 1)" data-invalid-link="true"><span class="ladda-label">他の募集を見る</span></a>
<?php endif; ?>

<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_total > $pagination_limit and empty($appoint))
{

	$view_pagination = View::forge('parts/pagination_view');
  $view_pagination->set_safe('url', URI_BASE . 'gc/' . $game_id . '/rec');
	$view_pagination->set_safe('page', $pagination_page);
	$view_pagination->set_safe('total', $pagination_total);
	$view_pagination->set_safe('limit', $pagination_limit);
	$view_pagination->set_safe('times', $pagination_times);
	$view_pagination->set_safe('function_name', $pagination_function_name);
	$view_pagination->set_safe('argument_arr', $pagination_argument_arr);
	echo $view_pagination->render();

}

?>

</div>

<?php endif; ?>
