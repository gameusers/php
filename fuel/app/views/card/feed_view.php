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
$amazon_count = 0;
$amazon_renewal_date = null;
$amazon_last = false;
$thumbnail_box = false;
$thumbnail_menu_box = false;

$aos_arr = ['fade', 'fade-up', 'fade-down', 'fade-left', 'fade-right', 'fade-up-right', 'fade-up-left', 'fade-down-right', 'fade-down-left', 'flip-up', 'flip-down', 'flip-left', 'flip-right', 'slide-up', 'slide-down', 'slide-left', 'slide-right', 'zoom-in', 'zoom-in-up', 'zoom-in-down', 'zoom-in-left', 'zoom-in-right', 'zoom-out', 'zoom-out-up', 'zoom-out-down', 'zoom-out-left', 'zoom-out-right'];


?>

<?php foreach ($arr as $key => $value): ?>

<?php

// --------------------------------------------------
//   カードの種類
// --------------------------------------------------

$card_type = 'normal';

if (isset($value['image']) or isset($value['movie']))
{
  $card_type = 'medium';
}
else if ($value['type'] === 'thumbnail_box_start')
{
  $card_type = 'thumbnail_box_start';
}
else if ($value['type'] === 'thumbnail_box_end')
{
  $card_type = 'thumbnail_box_end';
}
else if ($value['type'] === 'amazon')
{
  $card_type = 'thumbnail';
}
else if ($value['type'] === 'amazon_last')
{
  $amazon_last = true;
  continue;
}
else if ($value['type'] === 'thumbnail_menu_box_start')
{
  $card_type = 'thumbnail_menu_box_start';
}
else if ($value['type'] === 'thumbnail_menu_box_end')
{
  $card_type = 'thumbnail_menu_box_end';
}
else if ($value['type'] === 'adsense_300x250')
{
  $card_type = 'adsense';
}
else if ($value['type'] === 'about_amazon_ad')
{
  $card_type = 'about_amazon_ad';
}




if ($card_type === 'normal' or $card_type === 'medium')
{

  // --------------------------------------------------
  //   カテゴリー
  // --------------------------------------------------

  if ($value['type'] === 'bbs_thread_gc' or $value['type'] === 'bbs_comment_gc' or $value['type'] === 'bbs_reply_gc')
  {
    $category = '交流掲示板';
  }
  else if ($value['type'] === 'recruitment_comment' or $value['type'] === 'recruitment_reply')
  {
    $category = '募集掲示板';
  }
  else if ($value['type'] === 'bbs_thread_uc' or $value['type'] === 'bbs_comment_uc' or $value['type'] === 'bbs_reply_uc')
  {
    $category = 'コミュニティ';
  }


  // --------------------------------------------------
  //   サムネイル
  // --------------------------------------------------

  $img_url = null;

  if (isset($value['game_thumbnail']) or isset($value['community_thumbnail']))
  {
    if ($value['type'] === 'bbs_thread_uc' or $value['type'] === 'bbs_comment_uc' or $value['type'] === 'bbs_reply_uc')
    {
      $img_url = URI_BASE . 'assets/img/community/' . $value['community_no'] . '/thumbnail.jpg';
    }
    else {
      $img_url = URI_BASE . 'assets/img/game/' . $value['game_no'] . '/thumbnail.jpg';
    }
  }
  else if (isset($value['date']))
  {
    $datetime_thumbnail_none = new DateTime($value['date']);
    $thumbnail_none_second = $datetime_thumbnail_none->format('s');
    $img_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
  }


  // --------------------------------------------------
  //   画像
  // --------------------------------------------------

  $code_image_movie = null;

  if (isset($value['image']))
  {
    if ($value['type'] === 'bbs_thread_gc')
    {
      $img_url = URI_BASE . 'assets/img/bbs_gc/thread/' . $value['bbs_thread_no'] . '/image_1.jpg';
    }
    else if ($value['type'] === 'bbs_comment_gc')
    {
      $img_url = URI_BASE . 'assets/img/bbs_gc/comment/' . $value['bbs_comment_no'] . '/image_1.jpg';
    }
    else if ($value['type'] === 'bbs_reply_gc')
    {
      $img_url = URI_BASE . 'assets/img/bbs_gc/reply/' . $value['bbs_reply_no'] . '/image_1.jpg';
    }
    else if ($value['type'] === 'bbs_thread_uc')
    {
      $img_url = URI_BASE . 'assets/img/bbs_uc/thread/' . $value['bbs_thread_no'] . '/image_1.jpg';
    }
    else if ($value['type'] === 'bbs_comment_uc')
    {
      $img_url = URI_BASE . 'assets/img/bbs_uc/comment/' . $value['bbs_comment_no'] . '/image_1.jpg';
    }
    else if ($value['type'] === 'bbs_reply_uc')
    {
      $img_url = URI_BASE . 'assets/img/bbs_uc/reply/' . $value['bbs_reply_no'] . '/image_1.jpg';
    }
    else if ($value['type'] === 'recruitment_comment')
    {
      $img_url = URI_BASE . 'assets/img/recruitment/recruitment/' . $value['recruitment_id'] . '/image_1.jpg';
    }
    else if ($value['type'] === 'recruitment_reply')
    {
      $img_url = URI_BASE . 'assets/img/recruitment/reply/' . $value['recruitment_reply_id'] . '/image_1.jpg';
    }


    $top_type = ($value['image']['image_1']['width'] >= 300) ? 'top' : 'top_v';
    $code_image_movie = '<div class="' . $top_type . '"><a href="' . $img_url . '" id="modal_image"><img src="' . $img_url . '"></a></div>';

  }


  // --------------------------------------------------
  //   動画
  // --------------------------------------------------

  if (isset($value['movie']))
  {
    if (isset($value['movie'][0]['youtube']))
    {
      $youtube_id = $value['movie'][0]['youtube'];
      $code_image_movie = '<div class="top"><img src="https://img.youtube.com/vi/' . $youtube_id . '/mqdefault.jpg" width="300" height="169">';
      $code_image_movie .= '<img src="' . URI_BASE . 'assets/img/common/movie_play_button.png" class="video_play_button" id="video_popup" onclick="popupMovie(this)" data-url="https://www.youtube.com/watch?v=' . $youtube_id . '"></div>';
    }
  }




  // --------------------------------------------------
  //   コメント数
  // --------------------------------------------------

  if (isset($value['comment_total'], $value['reply_total']))
  {
    $total = ' <span class="glyphicon glyphicon-comment margin_left_5" aria-hidden="true"></span> ' . ($value['comment_total'] + $value['reply_total']);
  }
  else
  {
    $total = null;
  }


  // --------------------------------------------------
  //   時間
  // --------------------------------------------------

  $datetime = new DateTime($value['date']);
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
  //   ゲーム・コミュニティ名
  // --------------------------------------------------

  if ($category === '交流掲示板' or $category === '募集掲示板')
  {
    $game_community_name = $value['game_name'];
  }
  else
  {
    $game_community_name = $value['community_name'];
  }


  // --------------------------------------------------
  //   リンク
  // --------------------------------------------------

  if ($category === '交流掲示板')
  {
    $link_url = URI_BASE . 'gc/' . $value['game_id'];
    $link_url_individual = $link_url . '/bbs/' . $value['bbs_id'];
  }
  else if ($category === '募集掲示板')
  {
    $link_url = URI_BASE . 'gc/' . $value['game_id'];
    $link_url_individual = $link_url . '/rec/' . $value['recruitment_id'];
  }
  else if ($category === 'コミュニティ')
  {
    $link_url = URI_BASE . 'uc/' . $value['community_id'];
    $link_url_individual = $link_url . '/bbs/' . $value['bbs_id'];
  }


}
else if ($card_type === 'thumbnail_box_start')
{
  $thumbnail_box = true;
}
else if ($card_type === 'thumbnail_box_end')
{
  $thumbnail_box = false;
}

else if ($card_type === 'thumbnail')
{
  $amazon_renewal_date = $value['renewal_date'];
  $img_url = 'https://images-fe.ssl-images-amazon.com/images/I/' . $value['image_id'] . '._SL160_.jpg';
  $amazon_count++;

  //if($thumbnail_menu_box) $thumbnail_count++;
}
else if ($card_type === 'thumbnail_menu_box_start')
{
  $thumbnail_menu_box = true;
}
else if ($card_type === 'thumbnail_menu_box_end')
{
  $thumbnail_menu_box = false;
}



// --------------------------------------------------
//   AOS
// --------------------------------------------------

$aos_random_key = null;
$code_aos = null;
$random_num = mt_rand(1, 100);

if ($random_num <= 15) $aos_random_key = array_rand($aos_arr, 1);
if ($aos_random_key) $code_aos = ' data-aos="' . $aos_arr[$aos_random_key] . '"';




?>

<?php if ($card_type === 'normal'): // デフォルトサイズ ?>

<?php if ( ! AGENT_TYPE): // PC版 ?>

<a href="<?=$link_url_individual?>" class="card_link" id="feed_card"<?=$code_aos?>>
  <section class="card">
    <div class="left"><img src="<?=$img_url?>" width="128" height="128"></div>
    <div class="right">
      <h2 class="title"><?=$value['title']?><?=$total?></h2>
      <p class="comment"><?=$value['comment']?></p>
      <div class="info">
        <p class="type"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> <?=$category?></p>
        <p class="time"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?=$interval_time?></p>
        <p class="name" id="jslink" data-jslink="<?=$link_url?>"><?=$game_community_name?></p>
      </div>
    </div>
  </section>
</a>

<?php else: // スマホ・タブレット版 ?>

<a href="<?=$link_url_individual?>" class="card_link" id="feed_card"<?=$code_aos?>>
  <section class="card_s"<?php if (AGENT_TYPE === 'tablet') echo ' style="max-width: 300px;"'?>>
    <div class="top">
      <div class="left"><img src="<?=$img_url?>" width="96" height="96"></div>
      <div class="right">
        <div class="title"><h2><?=$value['title']?><?=$total?></h2></div>
        <div class="info">
          <p class="type"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> <?=$category?></p>
          <p class="time"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?=$interval_time?></p>
        </div>
        <div class="name"><div><p id="jslink" data-jslink="<?=$link_url?>"><?=$game_community_name?></p></div></div>
      </div>
    </div>
    <p class="bottom"><?=$value['comment']?></p>
  </section>
</a>

<?php endif; ?>


<?php elseif ($card_type === 'medium'): // 中サイズ ?>

<?php if ( ! AGENT_TYPE): // PC版 ?>

<section class="card_medium" id="feed_card"<?=$code_aos?>>
  <?=$code_image_movie?>
  <a href="<?=$link_url_individual?>" class="card_link">
    <div class="bottom">
      <h2 class="title"><?=$value['title']?><?=$total?></h2>
      <p class="comment"><?=$value['comment']?></p>
      <div class="info">
        <div class="type_time">
          <p class="type"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> <?=$category?></p>
          <p class="time"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?=$interval_time?></p>
        </div>
        <p class="name" id="jslink" data-jslink="<?=$link_url?>"><?=$game_community_name?></p>
      </div>
    </div>
  </a>
</section>

<?php else: // スマホ・タブレット版 ?>

<section class="card_medium_s" id="feed_card"<?=$code_aos?><?php if (AGENT_TYPE === 'tablet') echo ' style="max-width: 300px;"'?>>
  <?=$code_image_movie?>
  <a href="<?=$link_url_individual?>" class="card_link">
    <div class="bottom">
      <h2 class="title"><?=$value['title']?><?=$total?></h2>
      <p class="comment"><?=$value['comment']?></p>
      <div class="info">
        <p class="type"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> <?=$category?></p>
        <p class="time"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?=$interval_time?></p>
      </div>
      <div class="info2">
        <p class="name" id="jslink" data-jslink="<?=$link_url?>"><?=$game_community_name?></p>
      </div>
    </div>
  </a>
</section>

<?php endif; ?>


<?php elseif($card_type === 'thumbnail_box_start'): // サムネイルボックス（4個まとめ）スタート ?>

<aside class="card_thumbnail_box<?php if ($amazon_last) echo '_last'; ?>" id="feed_card">

<?php elseif($card_type === 'thumbnail_menu_box_start'): // PCメニュー用　サムネイル広告（4個まとめ）スタート ?>

<aside class="ad_thumbnail_box">

<?php elseif($card_type === 'thumbnail'): // サムネイル ?>

<?php

if ($amazon_last)
{
  $code_data_hide = ' data-thumbnail-hide="false"';
  $code_feed_card = ' id="feed_card"';
}
else if ($thumbnail_box or isset($amazon_thumbnail_menu))
{
  $code_data_hide = ' data-thumbnail-hide="false"';
  $code_feed_card = null;
}
else
{
  $code_data_hide = ' data-thumbnail-hide="true"';
  $code_feed_card = ' id="feed_card"';
}

?>

<a href="http://www.amazon.co.jp/gp/product/<?=$value['asin']?>/?ie=UTF8&camp=247&creative=1211&linkCode=ur2&tag=<?=$amazon_tracking_id?>" target="_blank" rel="nofollow"<?=$code_data_hide?><?=$code_feed_card?><?=$code_aos?>>
  <div class="card_thumbnail" title="<?=$value['title']?>">
    <div class="amazon" style="background: url(<?=$img_url?>) no-repeat center center; background-size: cover;">
<?php if ($value['discount_rate'] > 50): ?>
      <div class="caption"><div class="price"><?=$value['discount_rate']?>% OFF</div></div>
<?php endif; ?>
    </div>

  </div>
</a>

<?php elseif ($card_type === 'thumbnail_box_end' or $card_type === 'thumbnail_menu_box_end'): // サムネイルボックス（4個まとめ）エンド ?>

<?php if ($amazon_last and AGENT_TYPE): ?>

<div class="card_thumbnail" id="feed_card">
  <div class="amazon" id="about_amazon_ad" style="background: url(<?php echo URI_BASE . 'assets/img/common/about_amazon.png'; ?>) no-repeat center center; background-size: cover;" data-title="Amazon 広告について" data-text="価格および発送可能時期は表示された日付/時刻の時点のものであり、変更される場合があります。本商品の購入においては、購入の時点で [Amazon.co.jp もしくは Javari.jp またはその他の関連アマゾンサイトの適用ある方] に表示されている価格および発送可能時期の情報が適用されます。">
    <div class="caption"><div class="ad">広告について / 更新<br><?=$amazon_renewal_date?></div></div>
  </div>
</div>

<?php endif; ?>

</aside>


<?php elseif($card_type === 'adsense'): // アドセンス ?>

<div class="card_adsense_300x250" id="feed_card">
<?=$code_adsense_300x250?>
</div>

<?php endif; ?>


<?php endforeach; ?>



<?php if(isset($about_amazon) and AGENT_TYPE !== 'smartphone'): // アマゾン広告について ?>

<div class="card_thumbnail" id="feed_card">
  <div class="amazon" id="about_amazon_ad" style="background: url(<?php echo URI_BASE . 'assets/img/common/about_amazon.png'; ?>) no-repeat center center; background-size: cover;" data-title="Amazon 広告について" data-text="価格および発送可能時期は表示された日付/時刻の時点のものであり、変更される場合があります。本商品の購入においては、購入の時点で [Amazon.co.jp もしくは Javari.jp またはその他の関連アマゾンサイトの適用ある方] に表示されている価格および発送可能時期の情報が適用されます。">
    <div class="caption"><div class="ad">広告について / 更新<br><?=$amazon_renewal_date?></div></div>
  </div>
</div>

<?php endif; ?>

<?php if(isset($about_amazon) and $amazon_count > 0): // アマゾン広告用　小さいイメージ ?>

<img src="https://ir-jp.amazon-adsystem.com/e/ir?t=<?=$amazon_tracking_id?>&l=ur2&o=9" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />

<?php endif; ?>
