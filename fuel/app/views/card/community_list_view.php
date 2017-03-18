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

<?php foreach ($community_arr as $key => $value): ?>

<?php

// --------------------------------------------------
//   サムネイル
// --------------------------------------------------

$thumbnail_url = null;

if ($value['thumbnail'])
{
  $thumbnail_url = URI_BASE . 'assets/img/community/' . $value['community_no'] . '/thumbnail.jpg';
}
else if (isset($value['regi_date']))
{
  $datetime_thumbnail_none = new DateTime($value['regi_date']);
  $thumbnail_none_second = $datetime_thumbnail_none->format('s');
  $thumbnail_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
}


// --------------------------------------------------
//   時間
// --------------------------------------------------

$datetime = new DateTime($value['regi_date']);
$interval = $datetime_now->diff($datetime);

if ($interval->format('%m') >= 1)
{
  $interval_time = $interval->format('%m ヶ月前');
}
else if ($interval->format('%d') >= 1)
{
  $interval_time = $interval->format('%d 日前');
}
else if ($interval->format('%h') >= 1)
{
  $interval_time = $interval->format('%h 時間前');
}
else if ($interval->format('%i') >= 1)
{
  $interval_time = $interval->format('%i 分前');
}
else
{
  $interval_time = $interval->format('%s 秒前');
}


// --------------------------------------------------
//   URL ＆ ゲーム名
// --------------------------------------------------

$community_url = URI_BASE . 'uc/' . $value['community_id'];
$game_url = null;
$game_name = null;

if (isset($game_names_arr[$value['game_no']]))
{
  $game_url = URI_BASE . 'gc/' . $game_names_arr[$value['game_no']]['id'];
  $game_name = $game_names_arr[$value['game_no']]['name'];
}


?>

<?php if ( ! AGENT_TYPE): // PC版 ?>

<a href="<?=$community_url?>" class="card_link">
  <section class="card card_long">
    <div class="left"><img src="<?=$thumbnail_url?>" width="128" height="128"></div>
    <div class="right">
      <h2 class="title"><?=$value['name']?></h2>
      <p class="comment"><?=$value['description_mini']?></p>
      <div class="info">
        <p class="type"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?=$value['member_total']?>人</p>
        <p class="time"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?=$interval_time?></p>
        <p class="name" id="jslink" data-jslink="<?=$game_url?>"><?=$game_name?></p>
      </div>
    </div>
  </section>
</a>

<?php else: // スマホ・タブレット版 ?>

<a href="<?=$community_url?>" class="card_link">
  <section class="card_s" id="feed_card">
    <div class="top">
      <div class="left"><img src="<?=$thumbnail_url?>" width="96" height="96"></div>
      <div class="right">
        <div class="title"><h2><?=$value['name']?></h2></div>
        <div class="info">
          <p class="type"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?=$value['member_total']?>人</p>
          <p class="time"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?=$interval_time?></p>
        </div>
        <div class="name"><div><p id="jslink" data-jslink="<?=$game_url?>"><?=$game_name?></p></div></div>
      </div>
    </div>
    <p class="bottom"><?=$value['description_mini']?></p>
  </section>
</a>

<?php endif; ?>

<?php endforeach; ?>
