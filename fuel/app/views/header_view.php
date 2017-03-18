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
	//$code_notifications .= '          <a class="header_notifications_bell" id="header_notifications"' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'pl', 'user_id' => $user_id, 'notifications' => true)))) . '>' . "\n";
	$code_notifications .= '          <a class="header_notifications_bell" id="header_notifications" href="#" data-user_no="' . $user_no . '">' . "\n";
	$code_notifications .= '            <div class="header_notifications"><span class="glyphicon glyphicon-bell" aria-hidden="true"></span></div>' . "\n";
	$code_notifications .= '            <div class="header_notifications_number"><span class="badge" id="header_notifications_unread_total" data-reading="false" data-unread_id="">-</span></div>' . "\n";
	$code_notifications .= '          </a>' . "\n";
}
else
{
	$code_notifications = null;
}
//$code_notifications = (isset($user_id)) ? '          <div class="header_notifications"><a class="header_notifications_bell"' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'pl', 'user_id' => $user_id, 'notifications' => true)))) . '><span class="glyphicon glyphicon-bell" aria-hidden="true"></span></div><div class="header_notifications"><span class="badge" id="header_notifications_unread_total" data-reading="false">-</span></a></div>' . "\n" : null;

// リフレッシュアイコン
if (isset($app_mode))
{
	$code_refresh = '<div class="header_refresh"><a class="header_refresh_icon" href="#" onclick="appChangePage({\'reload\':\'thisPage\'})"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a></div>';
}
else
{
	$code_refresh = null;
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
          <div class="header_title"><a class="header_title"<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'index')))); ?>>Game Users</a></div><?=$code_notifications?><?=$code_refresh?>
        </div>
      </div>
      <nav class="collapse navbar-collapse">
        <ul class="nav navbar-nav">

<?php

//$code_notifications = (isset($user_id)) ? '          <li><a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'pl', 'user_id' => $user_id, 'notifications' => true)))) . '><span class="glyphicon glyphicon-bell" aria-hidden="true"></span> <span class="badge" id="header_notifications_unread_total">-</span></a></li>' . "\n" : null;

$segments_arr = Uri::segments();
$segment0 = (isset($segments_arr[0])) ? $segments_arr[0] : null;
$segment1 = (isset($segments_arr[1])) ? $segments_arr[1] : null;
$segment2 = (isset($segments_arr[2])) ? $segments_arr[2] : null;

$active_home = ($segment0 == 'pl') ? ' class="active"' : null;
$active_login_logout = ($segment0 == 'login' || $segment0 == 'logout') ? ' class="active"' : null;

$code_player = (isset($user_id)) ? '          <li' . $active_home . '><a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'pl', 'user_id' => $user_id)))) . '>プレイヤー</a></li>' . "\n" : null;
$code_login_logout = (empty($user_no)) ? '          <li' . $active_login_logout . ' id="header_login_logout"><a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'login')))) . '>ログイン</a></li>' . "\n" : '          <li' . $active_login_logout . ' id="header_login_logout"><a' . $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'logout')))) . '>ログアウト</a></li>' . "\n";

?>
<?=$code_player?>
          <li><a href="<?php echo URI_BASE . 'help';?>">ヘルプ</a></li>
<?=$code_login_logout?>
        </ul>
      </nav>
    </div>
  </header>
