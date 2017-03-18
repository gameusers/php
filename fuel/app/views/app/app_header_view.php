<?php

/*
 * 必要なデータ
 * integer / $user_no
 * string / $user_id
 * string / $uri_base
 * 
 * オプション
 * boolean / $app_mode / アプリの場合
 */

//$app_mode = true;
$original_code_basic = new Original\Code\Basic();
if (isset($app_mode)) $original_code_basic->app_mode = $app_mode;


// 通知ベル
if (isset($user_id))
{
	$code_notifications = "\n";
	$code_notifications .= '          <a class="header_notifications_bell" href="javascript:void(0)" onclick="appReadContentsIndex()">' . "\n";
	$code_notifications .= '            <div class="header_notifications"><span class="glyphicon glyphicon-bell" aria-hidden="true"></span></div>' . "\n";
	$code_notifications .= '            <div class="header_notifications_number"><span class="badge" id="header_notifications_unread_total" data-reading="false" data-unread_id="">-</span></div>' . "\n";
	$code_notifications .= '          </a>' . "\n";
}
else
{
	$code_notifications = null;
}

 ?>

  <header class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div class="navbar-brand">
          <div class="header_title"><a class="header_title" href="<?=$uri_base?>" id="external_link">Game Users</a></div><?=$code_notifications?>
        </div>
      </div>
      <nav class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          
<?php

$segments_arr = Uri::segments();
$segment0 = (isset($segments_arr[0])) ? $segments_arr[0] : null;
$segment1 = (isset($segments_arr[1])) ? $segments_arr[1] : null;
$segment2 = (isset($segments_arr[2])) ? $segments_arr[2] : null;

$active_home = ($segment0 == 'pl') ? ' class="active"' : null;
$active_login_logout = ($segment0 == 'login' || $segment0 == 'logout') ? ' class="active"' : null;

$code_login_logout = (empty($user_no)) ? '          <li' . $active_login_logout . ' id="header_login_logout"><a href="javascript:void(0)" onclick="appReadContentsLogin()">Login</a></li>' . "\n" : '          <li' . $active_login_logout . ' id="header_login_logout"><a href="javascript:void(0)" onclick="appReadContentsLogout()">Logout</a></li>' . "\n";

?>
<?=$code_login_logout?>
        </ul>
      </nav>
    </div>
  </header>
