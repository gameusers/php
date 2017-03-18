<?php

?>

<div class="swiper-container padding_bottom_20" id="swiper_container_slide_game_list">
  <div class="swiper-wrapper">

<?php

foreach ($game_data_arr as $key => $value)
{
	if ($value['thumbnail'])
	{
		$img_url = URI_BASE . 'assets/img/game/' . $value['game_no'] . '/thumbnail.jpg';
	}
	else
	{
		$datetime_thumbnail_none = new DateTime($value['renewal_date']);
		$thumbnail_none_second = $datetime_thumbnail_none->format('s');
		$img_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
	}

	echo '    <div class="swiper-slide slide_game_list_item">' . "\n";
	echo '      <a href="' . URI_BASE . 'gc/' . $value['id'] . '"><img src="' . $img_url . '" class="img-rounded" width="64" height="64" border="0" /></a>' . "\n";
	//echo '      <div class="slide_game_list_label bgc_lightseagreen"><a href="' . URI_BASE . 'gc/' . $value['id'] . '" class="orignal_label_game">' . $value['name'] . '</a></div>' . "\n";
    echo '      <div class="slide_game_list_label bgc_lightseagreen"><a href="javascript:void(0)" class="orignal_label_game" data-toggle="tooltip" data-placement="top" title="' . $value['name'] . '">' . $value['name'] . '</a></div>' . "\n";
	echo '    </div>' . "\n";
}

?>

  </div>
</div>
