<?php

/*
 * 必要なデータ
 * string / $uri_base
 * array / $db_user_arr / データ
 * 
 * オプション
 * boolean / $app_mode / アプリモードの場合、リンク変更
 */

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_code_basic = new Original\Code\Basic();
if (isset($app_mode)) $original_code_basic->app_mode = $app_mode;

$original_common_crypter = new Original\Common\Crypter();


// --------------------------------------------------
//   コードの復号化
// --------------------------------------------------

$decrypted_code = ($db_user_arr['code']) ? $original_common_crypter->decrypt($db_user_arr['code']) : null;


// --------------------------------------------------
//   リンク＆ハンドルネーム
// --------------------------------------------------

if ($db_user_arr['profile_no'] and $db_user_arr['open_profile'] === null)
{
	$link_code = ' href="javascript:void(0)"';
	$handle_name = $db_user_arr['profile_handle_name'];
}
else if ($db_user_arr['profile_no'] and $db_user_arr['open_profile'])
{
	$link_code = $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'pl', 'user_id' => $db_user_arr['user_id'], 'profile_no' => $db_user_arr['profile_no']))));
	$handle_name = $db_user_arr['profile_handle_name'];
}
else
{
	$link_code = $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'pl', 'user_id' => $db_user_arr['user_id']))));
	$handle_name = $db_user_arr['user_handle_name'];
}


// --------------------------------------------------
//   送信ボタンタイトル
// --------------------------------------------------

$button_title = ($type == 'lottery') ? '当選' : '編集';


// --------------------------------------------------
//   User No & Profile No
// --------------------------------------------------

$user_no = ($db_user_arr['user_no']) ? $db_user_arr['user_no'] : 'null';
$profile_no = ($db_user_arr['profile_no']) ? $db_user_arr['profile_no'] : 'null';

//var_dump($db_user_arr);

 ?>

<h2 id="heading_black">当選者決定＆編集</h2>

<div class="panel panel-default">
  <div class="panel-body">
    
    <div class="margin_bottom_20">★ <a<?=$link_code?>><?=$handle_name?></a></div>
    <div class="margin_bottom_20"><input type="text" class="form-control" id="type" placeholder="商品の種類" value="<?=$db_user_arr['type']?>"></div>
    <div class="margin_bottom_20"><input type="number" class="form-control" id="sum" placeholder="当選金額" value="<?=$db_user_arr['sum']?>"></div>
    <div class="margin_bottom_20"><input type="text" class="form-control" id="unit" placeholder="単位" value="<?=$db_user_arr['unit']?>"></div>
    <div class="margin_bottom_20"><input type="text" class="form-control" id="code" placeholder="コードなど" value="<?=$decrypted_code?>"></div>
    <button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="savePresentUser(this, '<?=$db_user_arr['present_no']?>')"><span class="ladda-label"><?=$button_title?></span></button>
            
  </div>
</div>
