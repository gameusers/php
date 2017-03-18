<?php

/*
  必要なデータ

  オプション
*/

?>

<div id="config_delete_community">

  <h2 id="heading_black">コミュニティ削除</h2>

  <div class="panel panel-default">
    <div class="panel-body">

      <p>コミュニティを削除する場合は、以下のフォームに delete と入力して送信してください。コミュニティを削除する場合は予め掲示板などで参加メンバーにその旨を伝えておいてください。一度削除すると元には戻せませんので、よく考えてから削除を行ってください。<br><br>※ コミュニティを削除できるのはコミュニティの管理者だけです。</p>

      <div class="form-group">
        <input type="text" class="form-control" id="delete_community_verification" maxlength="6" placeholder="確認キーワード">
      </div>

      <div id="alert"></div>

      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.deleteCommunity(this, <?=$community_no?>)"><span class="ladda-label">削除する</span></button></div>

    </div>
  </div>

</div>
