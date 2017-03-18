<?php

/*
 * 必要なデータ
 *
 * string / $uri_base
 * boolean / $app_mode / アプリモードの場合、リンク変更
 * integer / $game_no / ゲームNo
 * integer / $online_limit / オンライン表示される時間設定
 * boolean / $all / パネル、説明文、送信ボタンを含む場合
 *
 * array / $db_id_arr / IDの配列
 * array / $db_hardware_arr / ハードウェアの配列
 * array / $db_game_data_arr / ゲームデータの配列
 *
 * integer / $pagination_page / ページ
 * integer / $pagination_total / 総数
 * integer / $pagination_limit / 1ページの表示数
 * integer / $pagination_times / 番号の表示数
 * string / $pagination_function_name / 関数名
 * integer / $pagination_argument_arr / 関数引数
 *
 * オプション
 *
 */

$original_code_basic = new Original\Code\Basic();

//var_dump($db_id_arr, $db_hardware_arr, $game_name);

 ?>

<?php if (isset($db_id_arr)): ?>

<?php if (isset($all)): ?>
      <div id="panel_game_id_form">

        <h2 id="heading_black">ID登録</h2>

        <div class="panel panel-default" id="gc_game_id_form_box">
          <div class="panel-body">

            <p class="margin_bottom_10 padding_bottom_20 border_bottom_dashed">ここでIDを登録しておくと、募集を書き込むときにIDを入力する手間が省けます。<br><br>順番は整数の半角数字を入力してください。番号が小さい順に上に表示されるようになります。<br>登録するIDが、ゲームハードに関係するIDの場合はゲームハードを選択してください。PCの場合はPCを選択してください。それ以外（スマホなど）は最初に表示されている「ID : ゲーム名」を選択してください。<br><br>「ID : ゲーム名」を選択して、最初に表示されているゲーム以外のゲームを登録したい場合は、ゲーム名検索と書かれているフォームに、ゲーム名を入力して検索してください。検索しても出てこないゲームは、<a class="header_title"<?php echo $original_code_basic->change_page_tag(array('uri_base' => $uri_base, 'page' => array(array('type' => 'index')))); ?>>Game Usersトップページ</a>で登録する必要があります。</p>


            <div class="padding_top_5" id="gc_game_id_form_content">
<?php endif; ?>

<?php foreach ($db_id_arr as $key => $value): ?>

<?php if ($key == 5) echo '              <div id="gc_add_game_id_form_box">' . "\n"; ?>

              <div class="form-inline gc_register_id_form_set" id="form_<?=($key + 1)?>" data-game_id_no="<?=$value['game_id_no']?>">

                <div class="form-group">
                  <input type="number" class="form-control" id="sort_no" min="1" placeholder="順番" value="<?=$value['sort_no']?>">
                </div>

                <div class="form-group">
                  <select class="form-control" id="hardware_no">
<?php

if (isset($db_game_data_arr[$value['game_no']]['name']))
{
	$original_game_name = $db_game_data_arr[$value['game_no']]['name'];
	$data_game_no = $value['game_no'];
}
else
{
	$original_game_name = $db_game_data_arr[$game_no]['name'];
	$data_game_no = $game_no;
}

$game_name = (mb_strlen($original_game_name) > 20) ? mb_substr($original_game_name, 0, 19, 'UTF-8') . '…' : $original_game_name;

echo '                    <option value="" id="simple_id">ID : ' . $game_name . '</option>' . "\n";

	foreach ($db_hardware_arr as $key_2 => $value_2)
	{
		$selected = ($value['hardware_no'] == $value_2['hardware_no']) ? ' selected' : null;
		echo '                    <option value="' . $value_2['hardware_no'] . '"' . $selected . '>' . $value_2['abbreviation'] . '</option>' . "\n";
	}

?>
                  </select>
                </div>

                <div class="form-group" id="game_name_form_group">
<?php if (isset($value['hardware_no'])): ?>
                  <input type="text" class="form-control" id="game_name" placeholder="ゲーム名検索" value="" data-game_no="">
<?php else : ?>
                  <input type="text" class="form-control" id="game_name" placeholder="ゲーム名検索" value="" data-game_no="<?=$data_game_no?>">
<?php endif; ?>
                </div>

                <div class="form-group">
                  <input type="text" class="form-control" id="id" maxlength="100" placeholder="ID" value="<?=$value['id']?>">
                </div>

<?php if (isset($value['id'])): ?>
                <div class="checkbox margin_left_10">
                  <label>
                    <input type="checkbox" id="delete" value="1"> 削除
                  </label>
                </div>
<?php endif; ?>
              </div>

<?php if ($key == 9) echo '              </div>' . "\n"; ?>

<?php endforeach; ?>


              <div class="margin_top_20 margin_bottom_20"><button class="btn btn-default" type="submit" onclick="addEditGameIdForm(this)"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 追加</button></div>


<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------
//var_dump($pagination_total, $pagination_limit);

if ($pagination_total > $pagination_limit)
{

	echo '        <div class="select_profile_form_pagination">' . "\n";

	$view_pagination = View::forge('parts/pagination_view');
	$view_pagination->set_safe('page', $pagination_page);
	$view_pagination->set_safe('total', $pagination_total);
	$view_pagination->set_safe('limit', $pagination_limit);
	$view_pagination->set_safe('times', $pagination_times);
	$view_pagination->set_safe('function_name', $pagination_function_name);
	$view_pagination->set_safe('argument_arr', $pagination_argument_arr);
	echo $view_pagination->render();

	echo '        </div>' . "\n\n";

}

?>

<?php if (isset($all)): ?>
            </div>


            <div id="alert"></div>

            <div class="margin_top_15"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="saveGameId(this)"><span class="ladda-label">送信する</span></button></div>

          </div>
        </div>
      </div>
<?php endif; ?>

<?php endif; ?>
