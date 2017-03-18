<?php

/*
  必要なデータ
  array / $data_arr / 開発データ
  string / $code_pagination / ページャーコード

  オプション

*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------


?>


<div id="form_register_genre">

  <h2 class="element_shadow" id="heading_black">ジャンル登録</h2>

  <div class="panel panel-default element_shadow">
    <div class="panel-body">

      <p class="margin_bottom_15 padding_bottom_20 border_bottom_dashed"></p>

      <div class="form-group">
        <input type="text" class="form-control" id="keyword" maxlength="50" placeholder="キーワード" value="<?=$keyword?>">
      </div>

      <div class="margin_bottom_20 padding_bottom_20  border_bottom_dashed">
        <button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="search_developer_form" onclick="GAMEUSERS.index.searchGenreForm(this, 1)"><span class="ladda-label">検索する</span></button>
      </div>


      <?php foreach ($data_arr as $key => $value): ?>
      <div class="margin_bottom_10" id="register_genre">

        <!-- <p style="margin: 20px 0 10px 0;"><span class="label label-default">Genre No. <?=$value['genre_no']?></span></p> -->

        <div class="form-inline">

          <div class="input-group">
            <div class="input-group-addon">Genre No.<?=$value['genre_no']?></div>
            <div class="form-group">
              <input type="number" class="form-control" id="sort" min="1" placeholder="並び替えNo" value="<?=$value['sort']?>">
            </div>
          </div>

          <div class="form-group">
            <input type="text" class="form-control" id="name" maxlength="100" placeholder="ジャンル名" value="<?=$value['name']?>">
          </div>

          <?php $genre_no = $value['genre_no'] ?? 'null'; ?>
          <button type="submit" class="btn btn-success ladda-button" data-style="expand-right" onclick="GAMEUSERS.index.saveGenre(this, <?=$genre_no?>)"><span class="ladda-label">登録する</span></button>

        </div>

      </div>
      <?php endforeach; ?>

      <div id="alert"></div>

<?=$code_pagination?>

    </div>
  </div>


</div>
