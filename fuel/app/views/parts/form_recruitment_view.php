<?php

/*
 * 必要なデータ
 * string / $uri_base
 * integer / $login_user_no / ログインユーザーNo
 * string / $app_mode / アプリかどうか
 * string / $datetime_now / アクセス時間算出のための日時
 * array / $profile_arr
 * integer / $online_limit
 * boolean / $anonymity / 匿名化チェックボックスを表示させる場合 true
 * string / $func_name / 送信のonclickに設定する関数名
 * array / $func_argument_arr
 * boolean / $form_type / フォームのタイプ recruitment_new / recruitment_edit / reply_new / reply_edit
 * integer / $game_no / ゲームNo
 * string / $recruitment_id / 募集ID
 * string / $recruitment_reply_id / 返信ID
 *
 * オプション
 *
 * array / $data_arr / あらかじめ挿入しておくデータ
 * string / $func_name_return / 戻るのonclickに設定する関数名
 * array / $func_argument_return_arr
 * string / $func_name_delete / 削除のonclickに設定する関数名
 * array / $func_argument_delete_arr
 * boolean / $mode_reply / 画像＆動画が不要の場合 true
 */

// --------------------------------------------------
//   初期処理
// --------------------------------------------------


// ------------------------------
//    関数の引数を処理
// ------------------------------

$func_argument = (isset($func_argument_arr)) ? 'this, ' . implode(',', $func_argument_arr) : 'this';
$func_argument_return = (isset($func_argument_return_arr)) ? 'this, ' . implode(',', $func_argument_return_arr) : 'this';
$func_argument_delete = (isset($func_argument_delete_arr)) ? 'this, ' . implode(',', $func_argument_delete_arr) : 'this';


// ------------------------------
//    あらかじめ挿入されるデータを処理
// ------------------------------

$selected_type_1 =  null;
$selected_type_2 = null;
$selected_type_3 = null;
$selected_type_4 = null;
$selected_type_5 = null;
$data_handle_name = null;
$data_etc_title = null;
$data_comment = null;
$checked_anonymity = null;
$selected_open_type_1 =  null;
$selected_open_type_2 = null;
$selected_open_type_3 = null;

if (isset($data_arr))
{

	if ($form_type == 'recruitment_new' or $form_type == 'recruitment_edit')
	{
		if ($data_arr['type'] == 1) $selected_type_1 = ' selected';
		if ($data_arr['type'] == 2) $selected_type_2 = ' selected';
		if ($data_arr['type'] == 3) $selected_type_3 = ' selected';
		if ($data_arr['type'] == 4) $selected_type_4 = ' selected';
		if ($data_arr['type'] == 5) $selected_type_5 = ' selected';

		$data_etc_title = $data_arr['etc_title'];
	}

	$data_handle_name = $data_arr['handle_name'];
	$data_comment = $data_arr['comment'];
	if ($data_arr['anonymity']) $checked_anonymity = ' checked';

	if ($data_arr['open_type'] == 1) $selected_open_type_1 = ' selected';
	if ($data_arr['open_type'] == 2) $selected_open_type_2 = ' selected';
	if ($data_arr['open_type'] == 3) $selected_open_type_3 = ' selected';

	if ($form_type == 'recruitment_edit')
	{
		$image_url_base = $uri_base . 'assets/img/recruitment/recruitment/' . $recruitment_id . '/';
	}
	else if ($form_type == 'reply_edit')
	{
		$image_url_base = $uri_base . 'assets/img/recruitment/reply/' . $recruitment_reply_id . '/';
	}
	//var_dump($data_arr, $id_hardware_no_id_arr);
}

if (isset($comment_to))
{
	$data_comment = $comment_to;
}


// ------------------------------
//    利用規約
// ------------------------------

$original_code_common = new Original\Code\Common();
$original_code_common->user_no = $login_user_no;
$code_user_terms = $original_code_common->user_terms();



// ------------------------------
//    Google Adwords コンバージョン
// ------------------------------

//$code_goog_report_conversion = ($form_type == 'recruitment_new') ? 'goog_report_conversion();' : null;

?>

<aside<?php if ($form_type !== 'recruitment_new') echo ' style="padding:10px"';?>>

  <div class="clearfix" id="form_recruitment" data-form_type="<?=$form_type?>" data-game_no="<?=$game_no?>" data-recruitment_id="<?=$recruitment_id?>" data-recruitment_reply_id="<?=$recruitment_reply_id?>" data-specific_recruitment_reply_id="<?=$specific_recruitment_reply_id?>">

<?php

// --------------------------------------------------
//   名前欄
// --------------------------------------------------

if (isset($profile_arr))
{

	// --------------------------------------------------
	//   パーソナルボックス
	// --------------------------------------------------

	echo '          <div id="personal_box">' . "\n";
	$view_personal_box = View::forge('parts/personal_box_view2');
	$view_personal_box->set_safe('app_mode', $app_mode);
	$view_personal_box->set_safe('uri_base', $uri_base);
	$view_personal_box->set_safe('datetime_now', $datetime_now);
	$view_personal_box->set_safe('profile_arr', $profile_arr);
	$view_personal_box->set_safe('online_limit', $online_limit);
	echo $view_personal_box->render() . "\n";
	echo '          </div>' . "\n\n";


	// --------------------------------------------------
	//   パーソナルボックス　匿名
	// --------------------------------------------------

	if ($anonymity)
	{
		echo '          <div id="personal_box_anonymity">' . "\n";
		$view_personal_box = View::forge('parts/personal_box_view2');
		$view_personal_box->set_safe('app_mode', $app_mode);
		$view_personal_box->set_safe('uri_base', $uri_base);
		$view_personal_box->set_safe('datetime_now', $datetime_now);
		$view_personal_box->set_safe('anonymity', true);
		echo $view_personal_box->render() . "\n";
		echo '          </div>' . "\n\n";
	}

}
// $mode_reply = true;
?>


    <div class="gc_recruitment_form_box">

<?php if (empty($mode_reply)): ?>

      <p class="help_title" id="help_title_login"><span class="glyphicon glyphicon glyphicon-question-sign" aria-hidden="true"></span> ログイン・通知について</p>

      <p class="gc_recruitment_form_select_title_explanation" id="help_text_login">※ Game Usersにログインしていると、募集に返信してくれた人にだけ自分のIDを公開することができるようになります （通常はすべての人に対して公開されます）。また自分が書き込んだ募集に返信が書き込まれた際に、ブラウザまたはアプリで通知が届くようになります。</p>

      <select class="form-control" id="recruitment_type">
        <option value="1"<?=$selected_type_1?>>プレイヤー募集</option>
        <option value="2"<?=$selected_type_2?>>フレンド募集</option>
        <option value="3"<?=$selected_type_3?>>ギルド・クランメンバー募集</option>
        <option value="4"<?=$selected_type_4?>>売買・交換相手募集</option>
        <option value="5"<?=$selected_type_5?>>その他の募集</option>
      </select>

      <p class="gc_recruitment_form_select_title_explanation" id="recruitment_type_explanation_1">今日だけ、1度だけ、一緒にイベントを遊んだり、共にクエストのクリアを目指したり、そういった形で短時間一緒にプレイする相手を探す場合に選択してください。長期間、遊ぶ相手を探す場合は、フレンド募集、ギルド・クランメンバー募集を選択してください。</p>
      <p class="gc_recruitment_form_select_title_explanation" id="recruitment_type_explanation_2">お互いのIDを交換しあって、繰り返し遊ぶ相手を見つけたい場合はこちらを選択してください。</p>
      <p class="gc_recruitment_form_select_title_explanation" id="recruitment_type_explanation_3">ギルドやクランなど、作ったチームに参加してくれる人を募集する場合はこちらを選択してください。</p>
      <p class="gc_recruitment_form_select_title_explanation" id="recruitment_type_explanation_4">ゲーム内のアイテムを売買したり、交換したりする相手を探したい場合はこちらを選択してください。現実のお金が絡む取引は禁止です。</p>
      <p class="gc_recruitment_form_select_title_explanation" id="recruitment_type_explanation_5">上記以外の募集はこちらを選んでください。</p>

      <div class="form-group" id="etc_title_box">
        <input type="text" class="form-control" id="etc_title" maxlength="50" placeholder="タイトル" value="<?=$data_etc_title?>">
      </div>

<?php endif; ?>



<?php if (empty($profile_arr)): ?>
      <div class="form-group">
        <input type="text" class="form-control" id="handle_name" maxlength="50" placeholder="ハンドルネーム" value="<?=$data_handle_name?>">
      </div>
<?php endif; ?>

      <div class="form-group">
        <textarea class="form-control" id="comment" rows="3" maxlength="1000" placeholder="コメント"><?=$data_comment?></textarea>
      </div>

<?php if ($anonymity and isset($profile_arr)): ?>
      <div class="checkbox margin_top_25 margin_bottom_25"><label><input type="checkbox" id="anonymity"<?=$checked_anonymity?>> ななしにする</label></div>
<?php endif; ?>


      <div class="margin_bottom_20">

        <div class="btn-group margin_bottom_5">
          <button type="button" class="btn btn-default" id="image_button">画像アップロード</button>
          <button type="button" class="btn btn-default" id="movie_button">動画投稿</button>
        </div>

        <div class="form-group margin_bottom_30" id="image">
          <p class="form_common_image_movie_explanation">アップロードできる画像の種類はJPEG、PNG、GIF、BMPで、ファイルサイズが2MB以内のものです。</p>

<?php

// --------------------------------------------------
//   画像
// --------------------------------------------------

if (isset($data_arr['image'], $image_url_base))
{

	// ------------------------------
	//    URL設定
	// ------------------------------

	foreach ($data_arr['image'] as $key => $value)
	{
		if ($value['width'] and $value['height'])
		{
			echo '                  <div class="form_common_image_box">' . "\n";
			echo '                    <div class="form_common_image"><img src="' . $image_url_base . '/' . $key . '.jpg?' . strtotime($data_arr['renewal_date']) . '" width="80"></div>' . "\n";
			echo '                    <div class="form_common_image_delete_checkbox"><input type="checkbox" id="' . $key . '_delete"> 削除</div>' . "\n";
			echo '                  </div>' . "\n";
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

    </div>



    <div class="gc_recruitment_form_box">

      <p class="font_weight_bold margin_top_20"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span> ID・その他の情報 （未記入でもOK）</p>

        <div class="btn-group margin_bottom_5">
<?php if (count($db_id_arr) > 0): ?>
          <button type="button" class="btn btn-default" id="id_select_button">ID選択</button>
<?php endif; ?>
          <button type="button" class="btn btn-default" id="id_input_button">ID入力</button>
          <button type="button" class="btn btn-default" id="info_button">その他入力</button>
        </div>

        <div id="recruitment_form_id_select_box">

          <p class="gc_recruitment_form_select_id_title">ID選択</p>
          <p class="gc_recruitment_form_write_id_explanation">掲載するIDを選んでください。チェックした順番に並び順が決まります。複数掲載したい場合は、重要なIDからチェックしていきましょう。掲載できるIDは「ID選択」「ID入力」合わせて3つまでです。</p>

<?=$code_selecte_id?>

        </div>


        <div class="margin_bottom_30" id="recruitment_form_id_input_box">

          <p class="gc_recruitment_form_select_id_title">ID入力</p>
          <p class="gc_recruitment_form_write_id_explanation">左側の選択フォームでIDが関連しているハードを選んでください。該当するハードがない場合（スマホゲームなど）は最初に表示されている「ID」を選択してください。右側のフォームにはIDを入力します。<?php if (count($db_id_arr) > 0) echo '掲載できるIDは「ID選択」「ID入力」合わせて3つまでです。' ?></p>

<?php

// --------------------------------------------------
//   ID入力
// --------------------------------------------------

for ($i=1; $i <= 3; $i++) {

	$id_input_hardware_no = null;
	$id_input = null;

	if (isset($id_hardware_no_id_arr[$i - 1]))
	{
		$id_input_hardware_no = key($id_hardware_no_id_arr[$i - 1]);
		$id_input = $id_hardware_no_id_arr[$i - 1][$id_input_hardware_no];
	}

	echo '          <div class="form-inline margin_top_10">' . "\n";
	echo '            <div class="form-group">' . "\n";
	echo '              <select class="form-control" id="id_input_hardware_no_' . $i . '">' . "\n";

	echo '                <option value="">ID</option>' . "\n";

	foreach ($db_hardware_arr as $key => $value)
	{
		$id_input_selected = ($value['hardware_no'] == $id_input_hardware_no) ? ' selected' : null;
		echo '                <option value="' . $value['hardware_no'] . '"' . $id_input_selected . '>' . $value['abbreviation'] . '</option>' . "\n";
	}

	echo '              </select>' . "\n";
	echo '            </div>' . "\n";
	echo '            <div class="form-group">' . "\n";
	echo '              <input type="text" class="form-control" id="id_input_' . $i . '" maxlength="100" placeholder="ID ' . $i  . '" value="' . $id_input . '">' . "\n";
	echo '            </div>' . "\n";
	echo '          </div>' . "\n\n";

}

?>

        </div>


        <div class="padding_bottom_20" id="recruitment_form_info_input_box">

          <p class="gc_recruitment_form_select_id_title">その他にも掲載したい情報がある場合は、こちらに入力してください</p>
          <p class="gc_recruitment_form_write_id_explanation">例）部屋番号　12-3456-7890<br>　　入室パスワード　abcdefg</p>

<?php

// --------------------------------------------------
//   情報入力
// --------------------------------------------------

for ($i=1; $i <= 5; $i++) {

	echo '          <div class="form-inline margin_top_10">' . "\n";
	echo '            <div class="form-group">' . "\n";
	echo '              <input type="text" class="form-control" id="info_title_' . $i . '" maxlength="50" placeholder="タイトル ' . $i . '" value="' . $data_arr['info_title_' . $i] . '">' . "\n";
	echo '            </div>' . "\n";
	echo '            <div class="form-group">' . "\n";
	echo '              <input type="text" class="form-control" id="info_' . $i . '" maxlength="100" placeholder="情報 ' . $i . '" value="' . $data_arr['info_' . $i] . '">' . "\n";
	echo '            </div>' . "\n";
	echo '          </div>' . "\n\n";

}

?>

        </div>


<?php if ($form_type == 'recruitment_new' or $form_type == 'recruitment_edit'):// 1 ?>
        <select class="form-control margin_top_10" id="recruitment_open_type">
          <option value="1"<?=$selected_open_type_1?>>誰にでも公開</option>
<?php if ($login_user_no):// 2-1 ?>
          <option value="2"<?=$selected_open_type_2?>>返信者に公開（全員）</option>
          <option value="3"<?=$selected_open_type_3?>>返信者に公開（選択）</option>
<?php endif;// 2-1 ?>
        </select>

        <p class="gc_recruitment_form_select_title_explanation" id="recruitment_open_type_explanation_1">このページにアクセスした人なら誰でもID・その他の情報を見ることができます。</p>
<?php if ($login_user_no):// 2-2 ?>
        <p class="gc_recruitment_form_select_title_explanation" id="recruitment_open_type_explanation_2">ログインして返信したユーザー全員に、自動でID・その他の情報を公開します。</p>
        <p class="gc_recruitment_form_select_title_explanation" id="recruitment_open_type_explanation_3">ログインして返信したユーザーの中からID・その他の情報を公開する相手を選べます。</p>
<?php endif;// 2-2 ?>
<?php else:// 1 ?>
        <select class="form-control margin_top_10" id="recruitment_open_type">
          <option value="1"<?=$selected_open_type_1?>>誰にでも公開</option>
<?php if ($login_user_no):// 2-3 ?>
          <option value="2"<?=$selected_open_type_2?>>募集者に公開</option>
<?php if ($recruitment_open_type == 3):// 3-1 ?>
          <option value="3"<?=$selected_open_type_3?>>両者同意後に公開</option>
<?php endif;// 3-1 ?>
<?php endif;// 2-3 ?>
        </select>

        <p class="gc_recruitment_form_select_title_explanation" id="recruitment_open_type_explanation_1">このページにアクセスした人なら誰でもID・その他の情報を見ることができます。</p>
<?php if ($login_user_no):// 2-4 ?>
        <p class="gc_recruitment_form_select_title_explanation" id="recruitment_open_type_explanation_2">募集者だけにID・その他の情報を公開します。</p>
<?php if ($recruitment_open_type == 3):// 3-2 ?>
        <p class="gc_recruitment_form_select_title_explanation" id="recruitment_open_type_explanation_3">募集者があなたに対して自分のID・その他の情報を公開したときに、同時に相手もあなたのID・その他の情報を見れるようになります。</p>
<?php endif;// 3-2 ?>
<?php endif;// 2-4 ?>
<?php endif;// 1 ?>

    </div>





<?php if (empty($mode_reply)): ?>

<?php

// --------------------------------------------------
//   募集期間
// --------------------------------------------------

$data_limit_days = null;
$data_limit_hours = null;
$data_limit_minutes = null;

if (isset($data_arr['limit_date']))
{

	$datetime_limit = new DateTime($data_arr['limit_date']);
	$datetime_now_obj = new DateTime();
	$interval = $datetime_now_obj->diff($datetime_limit);

	if ($datetime_now_obj < $datetime_limit)
	{
		if ($interval->format('%d') >= 1) $data_limit_days = $interval->format('%a');
		if ($interval->format('%h') >= 1) $data_limit_hours = $interval->format('%h');
		if ($interval->format('%i') >= 1) $data_limit_minutes = $interval->format('%i');
	}

}
else
{
	$interval_time = '-';
}

?>

    <div class="gc_recruitment_form_box">

      <p class="font_weight_bold margin_top_20"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span> 募集期間 （未記入でもOK）</p>
      <p class="gc_recruitment_form_write_id_explanation">募集期間を設定する場合は数字を入力してください。例）今日から10日後まで募集したい場合は、日数に10を入力してください。<br>入力できる範囲は、1日～365日、1時間～24時間、1～59分です。募集期間が過ぎると、募集者と返信者のIDが自動的に非表示になります。無期限に募集を掲載したい場合は未記入にしてください。</p>

      <div class="form-inline margin_top_10 margin_bottom_20">
        <div class="form-group">
          <input type="number" class="form-control" id="limit_days" min="1" max="365" placeholder="日数" value="<?=$data_limit_days?>">
        </div>
        <div class="form-group">
          <input type="number" class="form-control" id="limit_hours" min="1" max="24" placeholder="時間" value="<?=$data_limit_hours?>">
        </div>
        <div class="form-group">
          <input type="number" class="form-control" id="limit_minutes" min="5" max="59" placeholder="分" value="<?=$data_limit_minutes?>">
        </div>
      </div>

<?php if ($form_type == 'recruitment_edit'): ?>
      <div class="gc_recruitment_form_close_button"><button type="submit" class="btn btn-info ladda-button" data-style="expand-right" id="submit_delete" onclick="<?=$func_name?>(<?=$func_argument?>, true)"><span class="ladda-label">募集を締め切る</span></button></div>
<?php endif; ?>

    </div>

<?php endif; ?>


    <div class="gc_recruitment_form_box">

      <p class="font_weight_bold padding_top_20"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span> Twitter</p>

        <div class="checkbox padding_top_10 padding_bottom_10">
          <label>
            <input type="checkbox" id="recruitment_twitter"> Twitterでこの募集をみんなに広めよう！（Twitterアカウントを持っている人はチェック）
          </label>
        </div>

    </div>

<?php if( isset($code_user_terms)) : ?>
    <div class="gc_recruitment_form_box padding_top_20 padding_bottom_10">
<?=$code_user_terms?>
    </div>
<?php endif; ?>


    <div class="clearfix">

      <div id="alert" class="padding_top_20"></div>

      <div class="form_common_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="<?=$func_name?>(<?=$func_argument?>, null)"><span class="ladda-label">送信する</span></button></div>
<?php if (isset($func_name_return)): ?>
      <div class="form_common_submit_left"><button type="button" class="btn btn-success" onclick="<?=$func_name_return?>(<?=$func_argument_return?>)">戻る</button></div>
<?php endif; ?>
<?php if (isset($func_name_delete)): ?>
      <div class="form_common_submit_right"><button type="submit" class="btn btn-info ladda-button" data-style="expand-right" id="submit_delete" onclick="<?=$func_name_delete?>(<?=$func_argument_delete?>)"><span class="ladda-label">削除する</span></button></div>
<?php endif; ?>

    </div>

  </div>

</aside>
