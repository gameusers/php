<?php

/*
  必要なデータ

  オプション
*/

?>

  <h2 class="element_shadow" id="heading_black">Wiki作成</h2>

  <div class="panel panel-default element_shadow" id="wiki_edit_box" data-wiki_no="">
    <div class="panel-body">

      <p class="margin_bottom_15 padding_bottom_20 border_bottom_dashed">
          みんなで記事を編集してコンテンツを充実させていけるWikiを作成することができます。ゲームの攻略Wikiなどにぜひ利用してください！　※ Wikiを作成するには、<a href="<?php echo Uri::create('login');?>">Game Usersにログイン</a>する必要があります。<br><br>作成したWikiには広告を掲載することができるので、そこから収益を得ることも可能です。Wikiに掲載する広告はプレイヤーページの広告設定タブから登録してください（ログイン後、上部メニューよりアクセス）<br><br>こちらで入力する内容は後で変更できますので、気軽にWikiを作成してみてください。<br><br>

          <strong>Wiki 使用ルール</strong><br><br>
          ・ ゲームに関連していないWikiは作れません<br>
          ・ 定期的に確認して荒らしやスパムが湧いていたら対応しましょう<br>
          ・ 公序良俗に反する内容は削除してください（18禁コメントや差別発言など）<br>
          ・ 最初は作った人が頑張ってコンテンツを充実させましょう！<br><br>

          ※ ルールに違反しているWikiや、コンテンツが少ないまま放置されているWikiは削除されることがあります。</p>

      <p class="font_weight_bold">WikiのURL</p>
      <p>半角英数字（アルファベット大文字禁止）とハイフン( - )アンダースコア( _ )を利用して入力してください。3文字以上、30文字以内。</p>
      <div class="input-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <span class="input-group-addon">https://gameusers.org/wiki/</span>
        <input type="text" class="form-control" id="wiki_id" maxlength="30" value="">
      </div>

      <p class="font_weight_bold">Wikiの名前</p>
      <p>Wikiの名前を入力してください。3文字以上、50文字以内。</p>
      <div class="margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <input type="text" class="form-control" id="wiki_name" maxlength="50" value="">
      </div>

      <p class="font_weight_bold">Wikiの説明文</p>
      <p>Wikiの説明文です。名前と共にWiki一覧に表示されます。100文字以内。</p>
      <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <textarea class="form-control" id="wiki_comment" maxlength="100"></textarea>
      </div>

      <p class="font_weight_bold">管理者パスワード</p>
      <p>Wikiの編集を凍結したり、アップロードされた画像を消去するときに、管理者パスワードが必要になります。半角英数字を利用して入力してください。6文字以上、32文字以内。WikiのURLと同じ文字列は使用できません。</p>
      <div class="form-group">
        <input type="password" class="form-control" id="wiki_password" maxlength="32" placeholder="管理者パスワード" value="">
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <input type="password" class="form-control" id="wiki_password_confirm" maxlength="32" placeholder="管理者パスワード再入力">
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed" id="game_list_form_box">
        <p class="font_weight_bold">関連ゲーム</p>
        <p class="community_bbs_post_comment_about_image">作成するWikiに関連のあるゲームを選んでください。<br><br>下の欄にゲーム名を入力するとゲームを検索できます。目当てのゲームが検索しても出てこない場合は、上部にあるタブから「ゲーム＞登録」を選んで、登録してください。</p>

        <div id="scrollable-dropdown-menu">
          <input type="text" class="form-control typeahead" id="game_name" placeholder="ゲーム名">
        </div>

        <div class="clearfix" id="game_list" data-game-list="[]"></div>
      </div>

      <div class="margin_bottom_15"><label><input type="checkbox" id="user_terms_approval"> Wiki 使用ルールに同意します</label></div>

      <div id="alert"></div>

      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.wiki_config.saveWiki(this)"><span class="ladda-label">作成する</span></button></div>

    </div>
  </div>
