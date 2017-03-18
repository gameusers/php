<?php

/*
  必要なデータ
  string / $type / 種類　gc_renewal　gc_access　uc_access
  array / $data_arr / データ配列

  オプション

*/

?>

<?php foreach ($data_arr as $key => $value): ?>

<?php

// --------------------------------------------------
//   サムネイル
// --------------------------------------------------

$thumbnail_url = null;

if ($value['thumbnail'])
{
  if ($type === 'gc_renewal' or $type === 'gc_access')
  {
    $thumbnail_url = URI_BASE . 'assets/img/game/' . $value['game_no'] . '/thumbnail.jpg';
  }
  else
  {
    $thumbnail_url = URI_BASE . 'assets/img/community/' . $value['community_no'] . '/thumbnail.jpg';
  }
}
else if ($value['renewal_date'])
{
  $datetime_thumbnail_none = new DateTime($value['renewal_date']);
  $thumbnail_none_second = $datetime_thumbnail_none->format('s');
  $thumbnail_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
}


// --------------------------------------------------
//   リンクURL ＆ ゲーム名
// --------------------------------------------------

if ($type === 'gc_renewal' or $type === 'gc_access')
{
  $link_url = URI_BASE . 'gc/' . $value['id'];
}
else
{
  $link_url = URI_BASE . 'uc/' . $value['community_id'];
}



?>

<a href="<?=$link_url?>" class="card_link">
  <div class="card_game">
    <div class="image"><img src="<?=$thumbnail_url?>"></div>
    <div class="title"><?=$value['name']?></div>
  </div>
</a>

<?php endforeach; ?>
