<?php

/*
 * 必要なデータ
 * string / $uri_base
 * array / $db_present_users_arr / データ
 * array / $unread_id / 
 * 
 * オプション
 * boolean / $app_mode / アプリモードの場合、リンク変更
 */

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_code_basic = new Original\Code\Basic();
if (isset($app_mode)) $original_code_basic->app_mode = $app_mode;


// --------------------------------------------------
//   管理者
// --------------------------------------------------

$administrator = (Auth::member(100)) ? true : false;
//$administrator = true;
 ?>

<div class="list-group">

<?php

if ( ! $previous and ! $winner)
{
	$title = '今週のエントリー';
	$color = 'success';
}
if ($previous == 2 and ! $winner)
{
	$title = '先週のエントリー';
	$color = 'success';
}
if ($previous == 2 and $winner)
{
	$title = '先週の当選者';
	$color = 'warning';
}
else if ($previous > 2)
{
	$datetime = new DateTime($regi_date);
	$title = $datetime->format("Y-m-d") . '～ の当選者';
	$color = 'warning';
}

echo '  <a href="javascript:void(0)" class="list-group-item list-group-item-' . $color . '">' . $title . '</a>' . "\n";



foreach ($db_present_users_arr as $key => $value)
{
	
	// リンク
	if ($value['profile_no'] and $value['open_profile'] === null)
	{
		$link_code = ' href="javascript:void(0)"';
		$handle_name = $value['profile_handle_name'];
	}
	else if ($value['profile_no'] and $value['open_profile'])
	{
		$link_code = $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'pl', 'user_id' => $value['user_id'], 'profile_no' => $value['profile_no']))));
		$handle_name = $value['profile_handle_name'];
	}
	else
	{
		$link_code = $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'pl', 'user_id' => $value['user_id']))));
		$handle_name = $value['user_handle_name'];
	}
	
	// バッジ
	if ($winner)
	{
		$badge = $value['type'] . ' : ' . $value['sum'] . $value['unit'];
	}
	else
	{
		$badge = $value['total'];
	}
	
	echo '  <a' . $link_code . ' class="list-group-item">' . $handle_name . ' <span class="badge">' . $badge . '</span></a>' . "\n";
	
	
	// 管理者には編集用のリストが追加される
	if ($administrator and $winner)
	{
		$user_no = ($value['user_no']) ? $value['user_no'] : 'null';
		$profile_no = ($value['profile_no']) ? $value['profile_no'] : 'null';
		
		$onclick = ' onclick="showPresentUserEditForm(this, \'' . $value['regi_date'] .  '\', \'edit\', ' . $user_no . ', ' . $profile_no . ')"';
		echo '  <a href="javascript:void(0)" class="list-group-item list-group-item-info"' . $onclick . '>' . $handle_name . ' <span class="badge">編集</span></a>' . "\n";
	}
	
}

?>

</div>
