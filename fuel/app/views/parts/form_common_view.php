<?php

/*
 * 必要なデータ
 * string / $uri_base
 * integer / $login_user_no / ログインユーザーNo
 * string / $datetime_now / アクセス時間算出のための日時
 * boolean / $user_terms_approval / 利用規約を承認している場合 true
 * array / $profile_arr
 * integer / $online_limit
 * boolean / $anonymity / 匿名化チェックボックスを表示させる場合 true
 * string / $func_name / 送信のonclickに設定する関数名
 * array / $func_argument_arr
 *
 * オプション
 * string / $comment_to / ○○さんへ
 * string / $func_name_return / 戻るのonclickに設定する関数名
 * array / $func_argument_return_arr
 * string / $func_name_delete / 削除のonclickに設定する関数名
 * array / $func_argument_delete_arr
 * array / $data_arr / あらかじめ挿入しておくデータ
 * boolean / $title_off / タイトルが不要の場合 true
 * boolean / $image_movie_off / 画像＆動画が不要の場合 true
 */

 // --------------------------------------------------
//   初期処理
// --------------------------------------------------

// ------------------------------
//    関数の引数を処理
// ------------------------------

$func_argument = 'this, ' . implode(',', $func_argument_arr);

$func_argument_return = (isset($func_argument_return_arr)) ? 'this, ' . implode(',', $func_argument_return_arr) : 'this';
$func_argument_delete = (isset($func_argument_delete_arr)) ? 'this, ' . implode(',', $func_argument_delete_arr) : 'this';


// ------------------------------
//    あらかじめ挿入されるデータを処理
// ------------------------------

if (isset($data_arr))
{
	$data_handle_name = $data_arr['handle_name'];

	$data_comment = $data_arr['comment'];
	$checked_anonymity = ($data_arr['anonymity']) ? ' checked' : null;

	if (empty($title_off)) $data_title = $data_arr['title'];
}
else
{
	$data_handle_name = null;
	$data_title = null;
	$data_comment = null;
	$checked_anonymity = null;
}

// ------------------------------
//    利用規約
// ------------------------------

//if (empty($app_user_terms_approval)) $app_user_terms_approval = false;

$original_code_common = new Original\Code\Common();
$original_code_common->user_no = $login_user_no;
$code_user_terms = $original_code_common->user_terms();


// ------------------------------
//    ○○さんへ
// ------------------------------

$code_comment_to = (isset($comment_to)) ? $comment_to : null;

?>

<div class="clearfix" id="form_box">

<?php

// --------------------------------------------------
//   名前欄
// --------------------------------------------------

//if (empty($app_mode)) $app_mode = null;

if (isset($profile_arr))
{
	/*if (empty($datetime_now))
	{
		$original_common_date = new Original\Common\Date();
		$datetime_now = $original_common_date->sql_format();
		echo "empty";
	}*/
	// --------------------------------------------------
	//   パーソナルボックス
	// --------------------------------------------------

	echo '        <div id="personal_box">' . "\n";
	$view_personal_box = View::forge('parts/personal_box_view2');
	$view_personal_box->set_safe('app_mode', $app_mode);
	$view_personal_box->set_safe('uri_base', $uri_base);
	$view_personal_box->set_safe('datetime_now', $datetime_now);
	$view_personal_box->set_safe('profile_arr', $profile_arr);
	$view_personal_box->set_safe('online_limit', $online_limit);
	echo $view_personal_box->render() . "\n";
	echo '        </div>' . "\n\n";


	// --------------------------------------------------
	//   パーソナルボックス　匿名
	// --------------------------------------------------

	if ($anonymity)
	{
		echo '        <div id="personal_box_anonymity">' . "\n";
		$view_personal_box = View::forge('parts/personal_box_view2');
		$view_personal_box->set_safe('app_mode', $app_mode);
		$view_personal_box->set_safe('uri_base', $uri_base);
		$view_personal_box->set_safe('datetime_now', $datetime_now);
		$view_personal_box->set_safe('anonymity', true);
		echo $view_personal_box->render() . "\n";
		echo '        </div>' . "\n\n";
	}

}
else
{
	echo '        <div class="form-group">'. "\n";
	echo '          <input type="text" class="form-control" id="handle_name" placeholder="ハンドルネーム（未記入でもOK！）" value="' . $data_handle_name . '">'. "\n";
	echo '        </div>'. "\n";
}

?>

<?php if (empty($title_off)): ?>
        <div class="form-group">
          <input type="text" class="form-control" id="title" placeholder="タイトル" value="<?=$data_title?>">
        </div>
<?php endif; ?>

        <div class="form-group">
          <textarea class="form-control" id="comment" rows="3" maxlength="3000"><?=$code_comment_to?><?=$data_comment?></textarea>
        </div>

<?php if ($anonymity and isset($profile_arr)): ?>
        <div class="checkbox margin_bottom_15"><label><input type="checkbox" id="anonymity"<?=$checked_anonymity?>> ななしにする</label></div>
<?php endif; ?>

<?php if (empty($image_movie_off)): ?>
        <div class="margin_bottom_20">

        <div class="btn-group margin_bottom_5">
          <button type="button" class="btn btn-default" id="image_button">画像アップロード</button>
          <button type="button" class="btn btn-default" id="movie_button">動画投稿</button>
        </div>

        <div class="form-group margin_bottom_30" id="image">
      	  <p class="form_common_image_movie_explanation">アップロードできる画像の種類はJPEG、PNG、GIF、BMPで、ファイルサイズが3MB以内のものです。</p>

<?php

// --------------------------------------------------
//   画像
// --------------------------------------------------

if (isset($data_arr['image'], $image_url_base))
{
	//var_dump($data_arr);
	// ------------------------------
	//    URL設定
	// ------------------------------
	/*
	if (isset($data_arr['bbs_thread_no'], $data_arr['bbs_comment_no']))
	{
		$image_url_base = $uri_base . 'assets/img/bbs/comment/' . $data_arr['bbs_comment_no'] . '/';
	}
	else
	{
		$image_url_base = $uri_base . 'assets/img/bbs/thread/' . $data_arr['bbs_thread_no'] . '/';
	}
	*/
	foreach ($data_arr['image'] as $key => $value)
	{
		if ($value['width'] and $value['height'])
		{
			echo '              <div class="form_common_image_box">' . "\n";
			echo '              	 <div class="form_common_image"><img src="' . $image_url_base . '/' . $key . '.jpg?' . strtotime($data_arr['renewal_date']) . '" width="80"></div>' . "\n";
			echo '               <div class="form_common_image_delete_checkbox"><input type="checkbox" id="' . $key . '_delete"> 削除</div>' . "\n";
			echo '              </div>' . "\n";
		}
	}

}

?>

          <div class="margin_top_10"><input type="file" class="form_common_image_file" name="image_1" id="image_1"></div>
        </div>

<?php

// --------------------------------------------------
//   動画URL作成
// --------------------------------------------------

$movie_url = null;

if (isset($data_arr['movie']))
{
	foreach ($data_arr['movie'] as $key => $value)
	{
		if (isset($value['youtube'])) $movie_url = 'https://www.youtube.com/watch?v=' . $value['youtube'];
	}
}

?>

        <div class="form-group margin_bottom_30" id="movie">
      	  <p class="form_common_image_movie_explanation">YouTube のURLが登録できます。動画が視聴できるページのURLをブラウザからコピーして貼り付けてください。</p>
      	  <ul>
      	    <li>YouTube - https://www.youtube.com/watch?v=__</li>
          </ul>
          <div class="margin_top_10"><input type="text" class="form-control" id="movie_url" placeholder="動画URL" value="<?=$movie_url?>"></div>
        </div>

        </div>
<?php endif; ?>

<?php if(isset($code_user_terms)) : ?>
        <div class="margin_bottom_20">
<?=$code_user_terms?>
        </div>
<?php endif; ?>

        <div id="alert"></div>

        <div class="form_common_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="<?=$func_name?>(<?=$func_argument?>)"><span class="ladda-label">送信する</span></button></div>
<?php if (isset($func_name_return)): ?>
        <div class="form_common_submit_left"><button type="button" class="btn btn-success" onclick="<?=$func_name_return?>(<?=$func_argument_return?>)">戻る</button></div>
<?php endif; ?>
<?php if (isset($func_name_delete)): ?>
        <div class="form_common_submit_right"><button type="submit" class="btn btn-info ladda-button" data-style="expand-right" id="submit_delete" onclick="<?=$func_name_delete?>(<?=$func_argument_delete?>)"><span class="ladda-label">削除する</span></button></div>
<?php endif; ?>

</div>
