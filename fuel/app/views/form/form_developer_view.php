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


<div id="form_register_developer">

  <h2 class="element_shadow" id="heading_black">開発登録</h2>

  <div class="panel panel-default element_shadow">
    <div class="panel-body">

      <p class="margin_bottom_15 padding_bottom_20 border_bottom_dashed"></p>

      <div class="form-group">
        <input type="text" class="form-control" id="keyword" maxlength="50" placeholder="キーワード" value="<?=$keyword?>">
      </div>

      <div class="">
        <button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="search_developer_form" onclick="GAMEUSERS.index.searchDeveloperForm(this, 1)"><span class="ladda-label">検索する</span></button>
      </div>


      <?php foreach ($data_arr as $key => $value): ?>
      <div class="margin_bottom_20 padding_bottom_20 border_bottom_dashed" id="register_developer">

        <p style="margin: 20px 0 10px 0;"><span class="label label-default">Developer No. <?=$value['developer_no']?></span></p>

        <div class="form-group">
          <input type="text" class="form-control" id="name" maxlength="100" placeholder="名前" value="<?=$value['name']?>">
        </div>

        <div class="form-group">
          <input type="text" class="form-control" id="abbreviation" maxlength="100" placeholder="略称" value="<?=$value['abbreviation']?>">
        </div>

        <div class="form-group">
          <input type="text" class="form-control" id="studio" maxlength="100" placeholder="スタジオ名" value="<?=$value['studio']?>">
        </div>

        <div class="form-group">
          <input type="text" class="form-control" id="abbreviation_studio" maxlength="100" placeholder="スタジオ略称" value="<?=$value['abbreviation_studio']?>">
        </div>

        <div id="alert"></div>

        <?php $developer_no = $value['developer_no'] ?? 'null'; ?>
        <button type="submit" class="btn btn-success ladda-button" data-style="expand-right" onclick="GAMEUSERS.index.saveDeveloper(this, <?=$developer_no?>)"><span class="ladda-label">登録する</span></button>

      </div>
      <?php endforeach; ?>

<?=$code_pagination?>

    </div>
  </div>


</div>
