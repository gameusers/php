<br>

<h2 id="heading_black">メールアドレス削除</h2>

<div class="panel panel-default" id="delete_email">
  <div class="panel-body">

  <p>メールアドレスを削除する。</p>

  <div class="form-group">
    <input type="email" class="form-control" id="email" placeholder="メールアドレス">
  </div>

  <div id="alert"></div>

  <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="deleteEmail()"><span class="ladda-label">削除する</span></button></div>


  </div>
</div>


<?=$code_config_advertisement?>


<h2 id="heading_black">Wiki テンプレートからコピー</h2>

<div class="panel panel-default" id="wiki_copy_template">
  <div class="panel-body">

  <p>Wikiのテンプレートから各Wikiにコピーする　plugin、lib、skin、pukiwiki.ini.phpなど。</p>

  <div class="margin_top_15">
    <div class=""><label><input type="checkbox" id="plugin" value="1"> plugin</label></div>
    <div class=""><label><input type="checkbox" id="lib" value="2"> lib</label></div>
    <div class=""><label><input type="checkbox" id="skin" value="3"> skin</label></div>
    <div class=""><label><input type="checkbox" id="pukiwiki_ini" value="4"> pukiwiki.ini</label></div>
    <div class=""><label><input type="checkbox" id="etc" value="5"> etc（その他のファイル、バージョンアップ時など）</label></div>
  </div>

  <div id="alert"></div>

  <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.admin.wikiCopyTemplate(this)"><span class="ladda-label">送信する</span></button></div>


  </div>
</div>


<p><a href="<?php echo URI_BASE . 'admin/phpinfo'?>">phpinfo</a></p>

<p><a href="<?php echo URI_BASE . 'admin/sharebuttonsdatajson'?>">Share Buttons Update data.json</a></p>
