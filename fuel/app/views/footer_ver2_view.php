<?php

/*
  必要なデータ

  オプション

*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------



// --------------------------------------------------
//   メニュー　selected
// --------------------------------------------------

$footer_select_box_selected_1 = null;
$footer_select_box_selected_2 = null;
$footer_select_box_selected_3 = null;

if ($cookie_footer_type === 'gc_renewal')
{
  $footer_select_box_selected_1 = ' selected';
}
else if ($cookie_footer_type === 'gc_access')
{
  $footer_select_box_selected_2 = ' selected';
}
else if ($cookie_footer_type === 'uc_access')
{
  $footer_select_box_selected_3 = ' selected';
}


// --------------------------------------------------
//   CSRF トークン
// --------------------------------------------------

$key = Config::get('security.csrf_token_key', 'fuel_csrf_token');

$session_csrf_token = Session::get($key);
$cookie_csrf_token = Cookie::get($key);

if (isset($session_csrf_token, $cookie_csrf_token) and $session_csrf_token == $cookie_csrf_token)
{
  $generated_token = $session_csrf_token;
}
else
{
  $generated_token = Security::generate_token();
  Session::set($key, $generated_token);
  Cookie::set($key, $generated_token);
}

$code_csrf_token = '<input name="' . Config::get('security.csrf_token_key', 'fuel_csrf_token') . '" value="' . $generated_token . '" type="hidden" id="form_fuel_csrf_token" />';


// --------------------------------------------------
//   アクセス解析
// --------------------------------------------------

$view_analytics = View::forge('common/analytics_view');
$code_analytics = $view_analytics->render();


?>

<footer>

  <div class="btn-group btn-group-justified select_button" role="group">
    <div class="btn-group" role="group">
      <button type="button" class="btn btn-default active" id="footer_button_card" data-type="card"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span></button>
    </div>
    <div class="btn-group" role="group">
      <button type="button" class="btn btn-default" id="footer_button_index" data-type="index">音訓索引</button>
    </div>
  </div>


  <div class="content_card" id="footer_content_card">

    <div class="select_box">
      <select class="form-control" id="footer_select_box">
        <option value="gc_renewal"<?=$footer_select_box_selected_1?>>最近更新されたゲームページ</option>
        <option value="gc_access"<?=$footer_select_box_selected_2?>>最近アクセスしたゲームページ</option>
        <option value="uc_access"<?=$footer_select_box_selected_3?>>最近アクセスしたコミュニティ</option>
      </select>
    </div>

    <div class="card_box">
<?=$code_card?>
    </div>

  </div>


  <div class="index_box<?php if (AGENT_TYPE) echo '_s'; ?> element_hidden" id="footer_content_index">
    <div id="footer_game_index_menu" style="margin: 0 0 0 0;"></div>
    <div id="footer_game_index" style="margin: 20px 0 0 0;"></div>
  </div>


  <div class="copyright"><span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> Game Users All Rights Reserved.</div>


  <aside class="modal fade" id="notifications_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="btn-group modal_notifications_button">
          <button type="button" class="btn btn-default ladda-button active" id="read_notifications_unread" data-style="expand-right" data-spinner-color="#000000" onclick="readNotifications(this, 1, <?=USER_NO?>)">未読</button>
          <button type="button" class="btn btn-default ladda-button" id="read_notifications_already" data-style="expand-right" data-spinner-color="#000000" onclick="readNotifications(this, 1, <?=USER_NO?>)">既読</button>
        </div>
        <div class="modal_notifications_change_all_unread_button"><button type="submit" class="btn btn-default ladda-button" id="notifications_change_all_unread_button" data-style="expand-right" data-spinner-color="#000000" onclick="changeAllUnreadToAlready(this)"><span class="ladda-label">すべて既読に</span></button></div>
        <div class="notifications_box" id="notifications_box" data-type="unread"></div>
      </div>
    </div>
  </aside>

<?=$code_csrf_token?>

<?=$code_analytics?>

</footer>
