<div class="btn-group modal_notifications_button">
  <button type="button" class="btn btn-default ladda-button active" id="read_notifications_unread" data-style="expand-right" data-spinner-color="#000000" onclick="readNotifications(this, 1, <?=$login_user_no?>)">未読</button>
  <button type="button" class="btn btn-default ladda-button" id="read_notifications_already" data-style="expand-right" data-spinner-color="#000000" onclick="readNotifications(this, 1, <?=$login_user_no?>)">既読</button>
</div>

<div class="modal_notifications_change_all_unread_button"><button type="submit" class="btn btn-default ladda-button" id="notifications_change_all_unread_button" data-style="expand-right" data-spinner-color="#000000" onclick="changeAllUnreadToAlready(this)"><span class="ladda-label">すべて既読に</span></button></div>

<div class="notifications_box" id="notifications_box" data-type="unread"><?=$code_notifications?></div>

