<?php

/*
 * 必要なデータ
 * array / $db_wiki_arr / 一覧用の配列
 *
 * string / $uri_base
 * string / $func_name / 送信のonclickに設定する関数名
 * array / $func_argument_arr
 *
 * array / $db_recruitment_arr / 募集の配列
 * array / $game_names_arr / ゲームの配列
 *
 * オプション
 */

 ?>

 <?php foreach ($db_wiki_arr as $key => $value): ?>

 <?php

if (isset($db_game_name_arr[$value['game_no']]['thumbnail']))
{
	$thumbnail_url = URI_BASE . 'assets/img/game/' . $value['game_no'] . '/thumbnail.jpg';
}
else
{
	$datetime_thumbnail_none = new DateTime($value['regi_date']);
	$thumbnail_none_second = $datetime_thumbnail_none->format('s');
	$thumbnail_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
}

$wiki_url = URI_BASE . 'wiki/' . $value['wiki_id'];
$game_url = URI_BASE . 'gc/' . $db_game_name_arr[$value['game_no']]['id'];
$game_name = $db_game_name_arr[$value['game_no']]['name'];

?>

    <section class="media <?php if ( ! $edit): ?>padding_bottom_15 <?php endif; ?>wiki_edit_ver1">

      <a href="<?=$wiki_url?>" class="pull-left"><img class="media-object img-rounded" src="<?=$thumbnail_url?>" width="64px" height="64px"></a>

      <div class="media-body media_list">

        <h1 class="media-heading index_gc_recruitment_list_title"><a href="<?=$wiki_url?>"><?=$value['wiki_name']?></a></h1>
        <p class="index_gc_recruitment_list_text"><?=$value['wiki_comment']?></p>

        <div class="community_list_label_box clearfix">
          <div class="original_label_game bgc_lightseagreen"><a href="<?=$game_url?>" class="orignal_label_game"><?=$game_name?></a></div>
        </div>

      </div>

<?php if ($edit): ?>
<div class="panel-group padding_top_20" role="tablist" aria-multiselectable="true" id="wiki_edit_box" data-wiki_no="<?=$value['wiki_no']?>">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_wiki_edit_<?=$value['wiki_no']?>" class="collapsed">Wikiを編集</a>
      </h4>
    </div>
    <div id="collapse_wiki_edit_<?=$value['wiki_no']?>" class="panel-collapse collapse wiki_edit_accordion" role="tabpanel" style="height: 0px;">

      <p class="font_weight_bold">WikiのURL</p>
      <p>半角英数字（アルファベット大文字禁止）とハイフン( - )アンダースコア( _ )を利用して入力してください。3文字以上、30文字以内。</p>
      <div class="input-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <span class="input-group-addon">https://gameusers.org/wiki/</span>
        <input type="text" class="form-control" id="wiki_id" maxlength="30" value="<?=$value['wiki_id']?>">
      </div>

      <p class="font_weight_bold">Wikiの名前</p>
      <p>Wikiの名前を入力してください。3文字以上、50文字以内。</p>
      <div class="margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <input type="text" class="form-control" id="wiki_name" maxlength="50" value="<?=$value['wiki_name']?>">
      </div>

      <p class="font_weight_bold">Wikiの説明文</p>
      <p>Wikiの説明文です。名前と共にWiki一覧に表示されます。100文字以内。</p>
      <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <textarea class="form-control" id="wiki_comment" maxlength="100"><?=$value['wiki_comment']?></textarea>
      </div>

      <p class="font_weight_bold">管理者パスワード</p>
      <p>Wikiの編集を凍結したり、アップロードされた画像を消去するときに、管理者パスワードが必要になります。半角英数字を利用して入力してください。6文字以上、32文字以内。<span class="font_color_red"> ※ 現在のパスワードから変更しない場合は空欄のまま送信してください。</span></p>
      <div class="form-group">
        <input type="password" class="form-control" id="wiki_password" maxlength="32" placeholder="管理者パスワード" value="">
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <input type="password" class="form-control" id="wiki_password_confirm" maxlength="32" placeholder="管理者パスワード再入力">
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed" id="game_list_form_box">
        <p class="font_weight_bold">関連ゲーム</p>
        <p class="community_bbs_post_comment_about_image">作成するWikiに関連のあるゲームを選んでください。<br><br>下の欄にゲーム名を入力するとゲームを検索できます。目当てのゲームが検索しても出てこない場合は、Game Usersトップページで「ゲーム登録」を選んで、登録してください。</p>

        <div id="scrollable-dropdown-menu">
          <input type="text" class="form-control typeahead" id="game_name" placeholder="ゲーム名">
        </div>

<?php

// --------------------------------------------------
//    ゲームリスト
// --------------------------------------------------

if (isset($value['game_list']))
{
	$game_list_arr = explode(',', $value['game_list']);
	array_shift($game_list_arr);
	array_pop($game_list_arr);

	echo '<div class="clearfix" id="game_list" data-game-list="[' . implode(',', $game_list_arr) . ']">';

	//\Debug::dump($game_list_arr);

	foreach ($game_list_arr as $key_list => $value_list) {
		echo '<div class="original_label_game bgc_lightseagreen cursor_pointer" id="game_list_no_' . $value_list . '" onclick="GAMEUSERS.common.deleteGameListNo(this, ' . $value_list . ')">' . $db_game_name_arr[$value_list]['name'] . '</div>';
	}

	echo '</div>';
}
else
{
	echo '<div class="clearfix" id="game_list" data-game-list="[]"></div>';
}

?>

      </div>

      <div id="alert"></div>

      <div class="clearfix">

        <div class="form_common_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.wiki_config.saveWiki(this)"><span class="ladda-label">編集する</span></button></div>

        <div class="form_common_submit_right"><button type="submit" class="btn btn-info ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.wiki_config.deleteWiki(this)"><span class="ladda-label">削除する</span></button></div>

      </div>

    </div>
  </div>
</div>


<div class="panel-group" role="tablist" aria-multiselectable="true" id="wiki_edit_advertisement_box" data-wiki_no="<?=$value['wiki_no']?>">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_wiki_edit_advertisement_<?=$value['wiki_no']?>" class="collapsed">Wikiに掲載する広告を設定</a>
      </h4>
    </div>
    <div id="collapse_wiki_edit_advertisement_<?=$value['wiki_no']?>" class="panel-collapse collapse wiki_edit_accordion" role="tabpanel" style="height: 0px;">

      <p class="font_weight_bold">デフォルト広告の設定</p>
      <p>デフォルト広告を設定すると、Wikiのすべてのページに広告を掲載することができるようになります（ページごとに広告を貼る必要がなくなります）。デフォルト広告に設定できるのはWikiのページの最上部（横長の広告）と下部のレクタングル広告（四角い広告の左側）の二箇所です。<br>ここで選択できる広告は、プレイヤーページの広告設定で登録した広告です。</span></p>

<?php

$wiki_1 = (isset($value['wiki_user_advertisement']['wiki_1'])) ? $value['wiki_user_advertisement']['wiki_1'] : null;
$wiki_2 = (isset($value['wiki_user_advertisement']['wiki_2'])) ? $value['wiki_user_advertisement']['wiki_2'] : null;
$amazon_slide_checked = (isset($value['wiki_user_advertisement']['amazon_slide'])) ? ' checked' : null;

//\Debug::dump($wiki_1, $wiki_2, $amazon_slide);

?>

      <p class="padding_top_10"><strong>ページ最上部（横長の広告推奨）</strong></p>
      <div class="margin_bottom_20">
        <select class="form-control" id="wiki_1">
          <option value=""></option>

<?php foreach($db_advertisement_arr as $key_wiki_1 => $value_wiki_1) : ?>
          <option value="<?=$value_wiki_1['name']?>"<?php if($wiki_1 == $value_wiki_1['name']) echo ' selected'; ?>><?=$value_wiki_1['name']?></option>
<?php endforeach; ?>
        </select>
      </div>

      <p class="padding_top_10"><strong>ページ下部（レクタングル広告推奨）</strong></p>
      <div class="margin_bottom_20">
        <select class="form-control" id="wiki_2">
          <option value=""></option>
<?php foreach($db_advertisement_arr as $key_wiki_2 => $value_wiki_2) : ?>
          <option value="<?=$value_wiki_2['name']?>"<?php if($wiki_2 == $value_wiki_2['name']) echo ' selected'; ?>><?=$value_wiki_2['name']?></option>
<?php endforeach; ?>
        </select>
      </div>

      <p class="padding_top_10"><strong>Amazonスライド広告</strong></p>
      <p class="padding_top_10">Game Usersがオリジナルで用意している、ゲーム関連の商品がスライドしながら表示される広告を利用することができます。AmazonトラッキングIDはプレイヤーページの広告設定で入力したものが使用されます。</p>
      <div class="margin_bottom_20"><input type="checkbox" id="amazon_slide"<?=$amazon_slide_checked?>> 利用する</div>


      <div id="alert"></div>

      <div class="clearfix">

        <div class="form_common_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.wiki_config.saveWikiAdvertisement(this)"><span class="ladda-label">設定する</span></button></div>

      </div>

    </div>
  </div>
</div>
<?php endif; ?>

    </section>



<?php endforeach; ?>

<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_total > $pagination_limit)
{
	echo '    <div class="margin_top_20">' . "\n";
	$view_pagination = View::forge('parts/pagination_view');
	$view_pagination->set_safe('page', $pagination_page);
	$view_pagination->set_safe('total', $pagination_total);
	$view_pagination->set_safe('limit', $pagination_limit);
	$view_pagination->set_safe('times', $pagination_times);
	$view_pagination->set_safe('function_name', $pagination_function_name);
	$view_pagination->set_safe('argument_arr', $pagination_argument_arr);
	echo $view_pagination->render();
	echo '    </div>' . "\n";
}

?>
