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

$key = Config::get('security.csrf_token_key', 'fuel_csrf_token');


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
        <p class="text-muted2"><button type="button" class="btn btn-default" id="app_go_to_top"><span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span> トップへ戻る</button></p>
      </div>
    </div>

  </footer>
