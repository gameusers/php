<?php

/*
  必要なデータ

  オプション

*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_code_index = new Original\Code\Index();


// --------------------------------------------------
//   変数
// --------------------------------------------------

$administrator = (\Auth::member(100)) ? true : false;


// --------------------------------------------------
//   コード作成
// --------------------------------------------------

$code_game_data_form = $original_code_index->register_game(array('page' => 1))['code'];



$code_form_developer = null;
$code_form_genre = null;

if ($administrator)
{
  $temp_arr = ['page' => 1];
  $code_form_developer = $original_code_index->form_developer($temp_arr)['code'];
  $code_form_genre = $original_code_index->form_genre($temp_arr)['code'];
}

// echo $code_form_developer;
// exit();

?>

<h2 class="element_shadow" id="heading_black">ゲーム登録</h2>

<div class="panel panel-default element_shadow" id="form_register_game">
  <div class="panel-body">

    <p class="margin_bottom_15 padding_bottom_20 border_bottom_dashed">ゲームを登録する場合はこちらのフォームを利用してください。<br><br>【登録の流れ】<br>1. ログインする<br>2. ゲーム名を検索する<br>3. 存在しない場合、新規登録用のフォームが表示される<br>4. ゲーム名・サブタイトルを入力し、登録ボタンを押す<br>5. 登録してもらったゲームをGame Users運営が確認<br>6. 運営が確認したゲームは編集できなくなります<br><br><font color="red">※ 同じゲームがすでに登録されていないか、十分に確認してから登録してください。アダルトゲームは登録しないようにしてください。</font><br><br>サブタイトルというのは例えば、ドラゴンクエストIII そして伝説へ… 「そして伝説へ…」の部分になります。未記入でも問題ありません。<br><br>ゲームを登録するとゲームページが同時に作成されます。登録直後はゲームページのURLは以下のようにランダムな文字列に設定され、運営が確認後、正式なURLに置き換わります。URLをブラウザのお気に入りに入れたり、ブログなどに掲載する場合は気をつけてください。
	  </p>

    <pre>https://gameusers.org/gc/iwoboi2zta58<br>　↓<br>https://gameusers.org/gc/dragon-quest3</pre>

    <p class="font_weight_bold margin_top_15 padding_top_20 border_top_dashed" id="search_game">ゲーム検索</p>
    <div class="form-group">
      <input type="text" class="form-control" id="keyword" maxlength="50" placeholder="ゲーム名" value="">
    </div>

    <div id="alert"></div>

    <div class="form_submit_button margin_bottom_20 padding_bottom_20 border_bottom_dashed"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="search_game_data" onclick="GAMEUSERS.index.searchGameData(this, 1)"><span class="ladda-label">検索する</span></button></div>

    <div id="game_data_box">
<?=$code_game_data_form?>
    </div>

  </div>
</div>

<?=$code_form_developer?>
<?=$code_form_genre?>
