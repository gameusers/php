<?php

/*
 * 必要なデータ
 *
 * オプション
 * boolean / $app_mode / アプリの場合
 */

 ?>


  <footer>

<?php

// --------------------------------------------------
//   CSRF トークン
// --------------------------------------------------

//echo Form::csrf() . "\n\n";

$key = Config::get('security.csrf_token_key', 'fuel_csrf_token');

//$key = 'csrf_token';

//echo '$key';
//var_dump($key);

//Session::delete($key);
//Cookie::delete($key);

$session_csrf_token = Session::get($key);
$cookie_csrf_token = Cookie::get($key);

if (isset($session_csrf_token, $cookie_csrf_token) and $session_csrf_token == $cookie_csrf_token)
{
	//echo '続き<br><br>';
	$generated_token = $session_csrf_token;
}
else
{
	//echo '新規発行<br><br>';
	$generated_token = Security::generate_token();
	Session::set($key, $generated_token);
	Cookie::set($key, $generated_token);
}

echo '    <input name="' . Config::get('security.csrf_token_key', 'fuel_csrf_token') . '" value="' . $generated_token . '" type="hidden" id="form_fuel_csrf_token" />'. "\n\n";


// echo 'Session::get($key)';
// var_dump(Session::get($key));
//
// echo 'Cookie::get($key)';
// var_dump(Cookie::get($key));



// --------------------------------------------------
//   アクセス解析
// --------------------------------------------------

$view_analytics = View::forge('common/analytics_view');
if (isset($app_mode)) $view_analytics ->set_safe('app_mode', $app_mode);

echo $view_analytics->render() . "\n";


// --------------------------------------------------
//   ユーザーNoの処理
// --------------------------------------------------

$read_notifications_user_no = ($user_no) ? $user_no : 'null';


?>
    <div id="footer">
      <div class="container">
<?php if (isset($app_mode)): ?>
        <p class="text-muted2"><button type="button" class="btn btn-default" id="app_go_to_top"><span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span> トップへ戻る</button></p>
<?php else: ?>
        <p class="text-muted">Copyright © 2013-2016 Game Users All Rights Reserved.</p>
<?php endif; ?>
      </div>
    </div>

    <aside class="modal fade" id="notifications_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="btn-group modal_notifications_button">
            <button type="button" class="btn btn-default ladda-button active" id="read_notifications_unread" data-style="expand-right" data-spinner-color="#000000" onclick="readNotifications(this, 1, <?=$read_notifications_user_no?>)">未読</button>
            <button type="button" class="btn btn-default ladda-button" id="read_notifications_already" data-style="expand-right" data-spinner-color="#000000" onclick="readNotifications(this, 1, <?=$read_notifications_user_no?>)">既読</button>
          </div>
          <div class="modal_notifications_change_all_unread_button"><button type="submit" class="btn btn-default ladda-button" id="notifications_change_all_unread_button" data-style="expand-right" data-spinner-color="#000000" onclick="changeAllUnreadToAlready(this)"><span class="ladda-label">すべて既読に</span></button></div>
          <div class="notifications_box" id="notifications_box" data-type="unread"></div>
        </div>
      </div>
    </aside>

  </footer>
