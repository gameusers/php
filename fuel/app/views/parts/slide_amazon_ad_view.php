<?php

/*
 * 必要なデータ
 *
 * array / $data_arr / 広告データ
 * string / $amazon_tracking_id / AmazonトラッキングID
 *
 * オプション
 *
 */

?>

<?php if (count($data_arr) > 0): ?>
<div class="swiper-container padding_bottom_10" id="swiper_container_slide_amazon_ad">
  <div class="swiper-wrapper">

<?php

foreach ($data_arr as $key => $value)
{
	// 画像のURL
	if ($value['image_id'])
	{
		$img_url = 'https://images-na.ssl-images-amazon.com/images/I/' . $value['image_id'] . '._SL110_.jpg';
	}
	else
	{
		$random_number = mt_rand(0, 59);
		if ($random_number < 10) $random_number = '0' . $random_number;
		$img_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $random_number . '.png';
	}

	$title = mb_strimwidth($value['title'], 0, 100, "…", "UTF-8");
	$price = ($value['price']) ? number_format($value['price']) : '?';

	// ツールチップを出す方向を設定
	// if ($key == 0)
	// {
		// $tooltip_placement = 'right';
	// }
	// else if ($key == ($limit - 1))
	// {
		// $tooltip_placement = 'left';
	// }
	// else
	// {
		// $tooltip_placement = 'top';
	// }

	// リンクURL
	// if (isset($amazon_associates_tag_1, $amazon_associates_tag_2))
	// {
		// if ($key % 2 == 0)
		// {
			// $amazon_associates_tag = $amazon_associates_tag_2;
		// }
		// else
		// {
			// $amazon_associates_tag = $amazon_associates_tag_1;
		// }
	// }
	// else
	// {
		// $amazon_associates_tag = $amazon_associates_tag_1;
	// }


	$link = 'http://www.amazon.co.jp/gp/product/' . $value['asin'] . '/?ie=UTF8&camp=247&creative=1211&linkCode=ur2&tag=' . $amazon_tracking_id;


	echo '    <div class="swiper-slide ad_amazon_slide_item">' . "\n";
	echo '      <a href="' . $link . '" rel="nofollow" target="_blank"><img src="' . $img_url . '" class="img-rounded" width="80" height="100" border="0" /></a>' . "\n";
	echo '      <div class="ad_amazon_slide_label bgc_dodgerblue" data-toggle="tooltip" data-placement="top" title="' . $title . '"><a href="' . $link . '" class="orignal_label_game" rel="nofollow" target="_blank">' . $title . '</a></div>' . "\n";
	echo '      <div class="ad_amazon_slide_label bgc_salmon"><a href="' . $link . '" class="orignal_label_game" rel="nofollow" target="_blank">' . $price . ' 円</a></div>' . "\n";
	echo '      <img src="https://ir-jp.amazon-adsystem.com/e/ir?t=' . $amazon_tracking_id . '&l=ur2&o=9" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />' . "\n";
	echo '    </div>' . "\n\n";

}

?>
  </div>
</div>
<div class="ad_amazon_slide_datetime">Last updated: <?=$data_arr[0]['renewal_date']?> / <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="価格および発送可能時期は表示された日付/時刻の時点のものであり、変更される場合があります。本商品の購入においては、購入の時点で [Amazon.co.jp もしくは Javari.jp またはその他の関連アマゾンサイトの適用ある方] に表示されている価格および発送可能時期の情報が適用されます。">広告について</a></div>
<?php endif; ?>
