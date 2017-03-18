<?php

?>

<?php if (AGENT_TYPE != ''): ?>
<div class="swiper-container" id="swiper_container_<?=$id_name?>">

  <div class="swiper-wrapper">

    <div class="swiper-slide" id="content_left_box">
<?php foreach ($content1_arr as $key => $value): ?>
<?=$value?>
<?php endforeach; ?>
    </div>

    <div class="swiper-slide" id="content_right_box">
<?php foreach ($content2_arr as $key => $value): ?>
<?=$value?>
<?php endforeach; ?>
    </div>

  </div>

  <div class="swiper-button-prev content_prev_button" id="content_prev_button_<?=$id_name?>"></div>
  <div class="swiper-button-next content_next_button" id="content_next_button_<?=$id_name?>"></div>

</div>
<?php else: ?>
<div class="content_main_box" id="content_<?=$id_name?>">

  <div class="content_left_box" id="content_left_box">

<?php foreach ($content1_arr as $key => $value): ?>
<?=$value . "\n"?>
<?php endforeach; ?>

  </div>

  <div class="content_right_box" id="content_right_box">

<?php foreach ($content2_arr as $key => $value): ?>
<?=$value?>
<?php endforeach; ?>

  </div>

</div>
<?php endif; ?>
