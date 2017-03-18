<?php

/*
 * 必要なデータ
 *
 * string / $uri_base
 * boolean / $app_mode / アプリモードの場合、リンク変更
 * integer / $game_no / ゲームNo
 * integer / $online_limit / オンライン表示される時間設定
 * boolean / $all / パネル、説明文、送信ボタンを含む場合
 *
 * array / $db_profile_arr / プロフィールの配列
 * array / $config_arr / 設定の配列
 *
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
 *
 */

 ?>

<?php if (isset($db_profile_arr)): ?>

<?php if (isset($all)): ?>
      <div id="panel_select_profile_form">

        <h2 id="heading_black">プロフィール変更</h2>

        <div class="panel panel-default">
          <div class="panel-body">

            <p class="margin_bottom_10 padding_bottom_20 border_bottom_dashed">このゲームコミュニティに書き込むときに使用するプロフィールを変更できます。プロフィールはプレイヤーページで作成することができます。プレイヤーページへは最上部にあるメニュー「プレイヤー」からアクセスしてください。</p>


            <div id="gc_select_profile_form_content">
<?php endif; ?>

<?php foreach ($db_profile_arr as $key => $value): ?>

<?php

// --------------------------------------------------
//    ラジオボタン
// --------------------------------------------------

$checked = null;


// --------------------------------------------------
//    追加プロフィールの場合
// --------------------------------------------------

if (isset($value['profile_no']))
{
	$radio_value = $value['profile_no'];
	if (isset($config_arr[$game_no]['profile_no']))
	{
		if ($config_arr[$game_no]['profile_no'] == $value['profile_no']) $checked = ' checked';
	}
}

// --------------------------------------------------
//    メインプロフィールの場合
// --------------------------------------------------

else
{
	$radio_value = 'user';
	//var_dump($config_arr);
	//if (empty($config_arr[$game_no])) $checked = ' checked';
	if (isset($config_arr[$game_no]['user_no']))
	{
		if ($config_arr[$game_no]['user_no'] == $value['user_no']) $checked = ' checked';
	}
}

?>

              <div class="select_profile_form_box">
                <div class="select_profile_form_radio">
                  <label><input type="radio" name="select_profile" value="<?=$radio_value?>"<?=$checked?>><span class="select_profile_form_text"><?=$value['profile_title']?></span></label>
                </div>
              </div>


<?php

// --------------------------------------------------
//   パーソナルボックス
// --------------------------------------------------

$view = View::forge('parts/personal_box_view2');
$view->set_safe('app_mode', $app_mode);
$view->set('uri_base', $this->uri_base);

if (isset($value['profile_no']))
{
	$good_type = 'profile';
	$good_no = $value['profile_no'];
}
else if (isset($value['user_no']))
{
	$good_type = 'user';
	$good_no = $value['user_no'];
}

$view->set('profile_arr', $value);

$view->set_safe('online_limit', $online_limit);
$view->set('good_type', $good_type);
$view->set('good_no', $good_no);
$view->set('good', $value['good']);

echo $view->render() . "\n";

?>

<?php endforeach; ?>

<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------
//var_dump($pagination_total, $pagination_limit);
if ($pagination_total > $pagination_limit)
{

	echo '        <div class="select_profile_form_pagination">' . "\n";

	$view_pagination = View::forge('parts/pagination_view');
	$view_pagination->set_safe('page', $pagination_page);
	$view_pagination->set_safe('total', $pagination_total);
	$view_pagination->set_safe('limit', $pagination_limit);
	$view_pagination->set_safe('times', $pagination_times);
	$view_pagination->set_safe('function_name', $pagination_function_name);
	$view_pagination->set_safe('argument_arr', $pagination_argument_arr);
	echo $view_pagination->render();

	echo '        </div>' . "\n\n";

}

?>

<?php if (isset($all)): ?>
            </div>


            <div id="alert"></div>

            <div class="margin_top_15"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="saveGcSelectProfile(this, <?=$game_no?>)"><span class="ladda-label">送信する</span></button></div>

          </div>
        </div>
      </div>
<?php endif; ?>

<?php endif; ?>
