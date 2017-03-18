<?php

if ($game_no and $thumbnail)
{
	$img_url = URI_BASE . 'assets/img/game/' . $game_no . '/thumbnail.jpg';
}
else
{
	$img_url = URI_BASE . 'assets/img/index/gameusers_thumbnail.png';
	
}

// PCの場合
if (AGENT_TYPE == '')
{
	$thumbnail_class = 'common_heading_thumbnail';
	$img_width = 100;
	$img_height = 100;
	$h1_class = '';
}
else
{
	$thumbnail_class = 'common_heading_thumbnail_sp';
	$img_width = 60;
	$img_height = 60;
	$h1_class = ' class="common_heading_h1_sp"';
}

?>

<div class="common_heading">
  <span>
    <div class="<?=$thumbnail_class?>"><img src="<?=$img_url?>" class="img-rounded" width="<?=$img_width?>" height="<?=$img_height?>" /></div>
    <div class="common_heading_text">
      <h1<?=$h1_class?>><?=$title?></h1>
      <p class="common_heading_text_description">
        <?=$description?>
      </p>
    </div>
  </span>
</div>
