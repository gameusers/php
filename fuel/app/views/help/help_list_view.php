<?php

/*
 * 必要なデータ
 *
 *
 * オプション
 *
 */

?>

<div class="margin_bottom_20">
  <select class="form-control" id="help_menu">
    <option value="top/top_about"<?php if ($list === 'top') echo ' selected'?>>ヘルプ トップ</option>
    <option value="login/login_about"<?php if ($list === 'login') echo ' selected'?>>ログイン＆アカウント</option>
    <option value="game/game_about"<?php if ($list === 'game') echo ' selected'?>>ゲームページ</option>
    <option value="community/community_about"<?php if ($list === 'community') echo ' selected'?>>コミュニティ</option>
    <option value="wiki/wiki_about"<?php if ($list === 'wiki') echo ' selected'?>>Wiki</option>
    <option value="player/player_about"<?php if ($list === 'player') echo ' selected'?>>プレイヤーページ</option>
    <option value="app/app_about"<?php if ($list === 'app') echo ' selected'?>>アプリ</option>
  </select>
</div>

<div class="panel panel-primary" id="help_list_box">

  <div class="panel-heading"><span class="glyphicon glyphicon glyphicon-th-list" aria-hidden="true"></span>　ヘルプ</div>

  <div class="panel-body">
    <ul class="bbs_list">

<?php

if ($list === 'top')
{
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'top_about\')">Game Usersとは？</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'top_notification\')">便利な通知機能</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'top_user_terms\')">利用規約</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'top_privacy_policy\')">プライバシーポリシー</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'top_special\')">Special Thanks</a></li>' . "\n";
}
else if ($list === 'login')
{
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'login_about\')">ログインとは？</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'login_account_create\')">アカウントを作成する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'login\')">ログインする</a></li>' . "\n";
}
else if ($list === 'game')
{
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'game_about\')">ゲームページとは？</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'game_bbs\')">交流掲示板の使い方</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'game_recruitment\')">募集掲示板の使い方</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'game_recruitment_sample\')">募集掲示板、投稿サンプル</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'game_config_profile\')">設定 ： ゲームページで使用するプロフィールを選択する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'game_config_id\')">設定 ： 募集掲示板で使用するIDを登録する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'game_register\')">ゲームを登録する（ゲームページを作成する）</a></li>' . "\n";
}
else if ($list === 'community')
{
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_about\')">コミュニティとは？</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_join\')">コミュニティに参加する・退会する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_notification\')">通知について</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_config_profile\')">設定 ： コミュニティに参加するプロフィールを変更する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_create\')">管理者用 ： コミュニティを作成する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_member\')">管理者用 ： メンバーの参加申請を承認する、退会させる、BANする、モデレーターにする</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_config_basic\')">管理者用 ： 基本設定</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_config_community\')">管理者用 ： コミュニティ設定</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_config_authority\')">管理者用 ： 閲覧権限・操作権限</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'community_config_delete\')">管理者用 ： コミュニティを削除する</a></li>' . "\n";
}
else if ($list === 'wiki')
{
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'wiki_about\')">Wikiとは？</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'wiki_create\')">Wikiを作成する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'wiki_config\')">Wikiの設定を編集する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'wiki_edit_basic\')">編集 ： 基本</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'wiki_edit_ad\')">編集 ： 広告を貼る</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'wiki_edit_movie\')">編集 ： YouTubeの動画を貼る</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'wiki_edit_twitter\')">編集 ： Twitterの埋め込みタイムラインを貼る</a></li>' . "\n";
}
else if ($list === 'player')
{
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'player_about\')">プレイヤーページとは？</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'player_profile\')">プロフィールを編集・追加する</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'player_community\')">参加コミュニティについて</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'player_config_basic\')">設定 ： 基本設定</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'player_config_notification\')">設定 ： 通知設定</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'player_config_ad\')">設定 ： 広告設定</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'player_config_wiki\')">設定 ： Wiki設定</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'player_notification_browser\')">ブラウザで通知を受ける方法</a></li>' . "\n";
}
else if ($list === 'app')
{
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'app_about\')">アプリとは？</a></li>' . "\n";
  echo '        <li class="bbs_list"><a href="javascript:void(0)" class="bbs_list_title" onclick="GAMEUSERS.common.readHelp(this, 1, null, \'app_how_to_use\')">アプリの使い方</a></li>' . "\n";
}

?>

    </ul>
  </div>

</div>
