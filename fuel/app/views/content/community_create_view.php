<?php

/*
  必要なデータ

  オプション

*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------


?>

<h2 class="element_shadow" id="heading_black">コミュニティ作成</h2>

<div class="panel panel-default element_shadow" id="config_community_basis">
  <div class="panel-body">

  <p class="margin_bottom_15 padding_bottom_20 border_bottom_dashed">ギルドやクランのメンバー交流用コミュニティ、ゲーム配信者が視聴者と交流するためのコミュニティなどに利用してください。ゲームに関係するなら、どんな内容のコミュニティでも作成できます。こちらで入力する内容は後で変更できますので、気軽にコミュニティを作成してみてください。<br><br>コミュニティを作成するには、<a href="<?php echo URI_BASE . 'login'; ?>">Game Usersにログイン</a>する必要があります。</p>

  <p class="font_weight_bold">コミュニティの名前（50文字以内）</p>
  <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
    <input type="text" class="form-control" id="community_name" maxlength="50" placeholder="コミュニティの名前" value="">
  </div>

  <p class="font_weight_bold">コミュニティの説明文</p>
  <p>コミュニティの説明文です。参加する人がコミュニティについて理解しやすいような説明文にしてください。3000文字以内。</p>
  <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
    <textarea class="form-control" id="community_description" maxlength="3000" placeholder="コミュニティの説明文"></textarea>
  </div>

  <p class="font_weight_bold">コミュニティの説明文（一覧用）</p>
  <p>Game Usersのトップページなどで表示されるコミュニティ一覧用の短い説明文です。100文字以内。</p>
  <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
    <textarea class="form-control" id="community_description_mini" maxlength="100" placeholder="コミュニティの説明文"></textarea>
  </div>

  <p class="font_weight_bold">コミュニティID</p>
  <p class="margin_top_10">コミュニティIDはあなたのコミュニティのURLとして使われます。　https://gameusers.org/uc/コミュニティID<br>利用できる文字は半角英数字（アルファベット大文字禁止）とハイフン( - )アンダースコア( _ )です。3文字以上、50文字以内。</p>
  <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
    <input type="text" class="form-control" id="community_id" maxlength="50" placeholder="コミュニティID" value="">
  </div>

  <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed" id="game_list_form_box">
    <p class="font_weight_bold">関連ゲーム</p>
    <p class="community_bbs_post_comment_about_image">作成するコミュニティに関連のあるゲームを選んでください。10個までゲームを登録できますが、最も関連の深いゲームは一番最初に登録してください。Game Usersのトップページなどで表示されるコミュニティ一覧では、最初に選択された1件のゲーム名しか表示されません。<br><br>下の欄にゲーム名を入力するとゲームを検索できます。目当てのゲームが検索しても出てこない場合は、上部にあるタブから「ゲーム登録」を選んで、登録してください。</p>

    <div id="scrollable-dropdown-menu">
      <input type="text" class="form-control typeahead" id="game_name" placeholder="ゲーム名">
    </div>

    <div class="clearfix" id="game_list" data-game-list="[]"></div>
  </div>

  <div id="alert"></div>

  <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.index.createCommunity(this)"><span class="ladda-label">作成する</span></button></div>

  </div>
</div>
