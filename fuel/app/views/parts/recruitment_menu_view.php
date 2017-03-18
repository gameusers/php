<?php

/*
  必要なデータ

  オプション
*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

// $view_adsense = \View::forge('common/adsense_rectangle_ver2_view');
// $code_adsense_rectangle = $view_adsense->render();


// --------------------------------------------------
//   変数設定
// --------------------------------------------------

// $class_main = ( ! AGENT_TYPE) ? 'main_pc' : 'main_s';


// --------------------------------------------------
//   募集検索
// --------------------------------------------------

$gc_recruitment_search_checkbox = null;

foreach ($db_hardware_arr as $key => $value)
{
	$gc_recruitment_search_checkbox .= '      <div class="gc_recruitment_search_checkbox"><label><input type="checkbox" id="search_recruitment_hardware_id_no" value="' . $value['hardware_no'] . '">' . $value['abbreviation'] . '</label></div>' . "\n";
}


?>

<div class="margin_bottom_20"><button type="submit" class="btn btn-default ladda-button left_menu_button" data-style="expand-right" data-spinner-color="#000000" onclick="modalReadFormRecruitmentNotificationConfig(this, <?=$game_no?>)"><span class="glyphicon glyphicon-bell"> <span class="ladda-label">募集通知</span></button></div>


<div class="margin_bottom_20"><button type="submit" class="btn btn-default ladda-button left_menu_button" data-style="expand-right" data-spinner-color="#000000" onclick="modalReadFormRecruitment(this, <?=$game_no?>)"><span class="glyphicon glyphicon-pencil"> <span class="ladda-label">募集投稿</span></button></div>


<div class="panel panel-primary" id="search_recruitment_box" data-game_no="<?=$game_no?>">

  <div class="panel-heading">募集検索</div>

  <div class="panel-body">

    <p class="margin_bottom_15">投稿された募集の中から、条件を設定して検索することができます。</p>

    <p class="font_weight_bold">募集の種類</p>

    <div class="checkbox clearfix margin_top_15">
      <div class="gc_recruitment_search_checkbox"><label><input type="checkbox" id="search_recruitment_type" value="1">プレイヤー募集</label></div>
      <div class="gc_recruitment_search_checkbox"><label><input type="checkbox" id="search_recruitment_type" value="2">フレンド募集</label></div>
      <div class="gc_recruitment_search_checkbox"><label><input type="checkbox" id="search_recruitment_type" value="3">ギルド・クランメンバー募集</label></div>
      <div class="gc_recruitment_search_checkbox"><label><input type="checkbox" id="search_recruitment_type" value="4">売買・交換相手募集</label></div>
      <div class="gc_recruitment_search_checkbox"><label><input type="checkbox" id="search_recruitment_type" value="5">その他の募集</label></div>
    </div>

    <p class="font_weight_bold">IDの種類</p>

    <div class="checkbox clearfix margin_top_15">
      <div class="gc_recruitment_search_checkbox"><label><input type="checkbox" id="search_recruitment_id_null" value="true">ID</label></div>
<?=$gc_recruitment_search_checkbox?>
    </div>


    <div class="form-group">
      <input type="text" class="form-control" id="search_recruitment_keyword" maxlength="50" placeholder="キーワード" value="">
    </div>



    <div id="alert" class="margin_top_20"></div>

    <div class="form_common_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="readRecruitment(this, 1, <?=$game_no?>, null, true, true)"><span class="ladda-label">検索する</span></button></div>

  </div>

</div>
