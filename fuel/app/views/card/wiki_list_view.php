<?php

/*
  必要なデータ

  オプション

*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------


// --------------------------------------------------
//   変数
// --------------------------------------------------

$datetime_now = new DateTime();


?>

<?php foreach ($wiki_arr as $key => $value): ?>

<?php

// --------------------------------------------------
//   サムネイル
// --------------------------------------------------

$thumbnail_url = null;

if (isset($game_names_arr[$value['game_no']]['thumbnail']))
{
  $thumbnail_url = URI_BASE . 'assets/img/game/' . $value['game_no'] . '/thumbnail.jpg';
}
else if (isset($value['renewal_date']))
{
  $datetime_thumbnail_none = new DateTime($value['renewal_date']);
  $thumbnail_none_second = $datetime_thumbnail_none->format('s');
  $thumbnail_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
}


// --------------------------------------------------
//   URL ＆ ゲーム名
// --------------------------------------------------

$wiki_url = null;
$wiki_url = URI_BASE . 'wiki/' . $value['wiki_id'];

if (isset($game_names_arr[$value['game_no']]))
{
  $game_url = URI_BASE . 'gc/' . $game_names_arr[$value['game_no']]['id'];
  $game_name = $game_names_arr[$value['game_no']]['name'];
}


?>

<?php if ( ! AGENT_TYPE): // PC版 ?>

<a href="<?=$wiki_url?>" class="card_link">
  <section class="card card_long">
    <div class="left"><img src="<?=$thumbnail_url?>" width="128" height="128"></div>
    <div class="right">
      <h2 class="title"><?=$value['wiki_name']?></h2>
      <p class="comment"><?=$value['wiki_comment']?></p>
      <div class="info">
        <p class="name" id="jslink" data-jslink="<?=$game_url?>"><?=$game_name?></p>
      </div>
    </div>
  </section>
</a>

<?php else: // スマホ・タブレット版 ?>

<a href="<?=$wiki_url?>" class="card_link">
  <section class="card_s">
    <div class="top">
      <div class="left"><img src="<?=$thumbnail_url?>" width="96" height="96"></div>
      <div class="right">
        <div class="title"><h2><?=$value['wiki_name']?></h2></div>
        <div class="name_tall"><div><p id="jslink" data-jslink="<?=$game_url?>"><?=$game_name?></p></div></div>
      </div>
    </div>
    <p class="bottom"><?=$value['wiki_comment']?></p>
  </section>
</a>

<?php endif; ?>

<?php endforeach; ?>
