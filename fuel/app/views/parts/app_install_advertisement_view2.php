<?php if (count($arr) > 0 and OS): ?>
<aside id="ad_app_install_box">
  
<?php foreach ($arr as $key => $value): ?>
<?php if ($value[OS]['link'] and $value[OS]['image']): ?>
  <div id="ad_app_install_main_<?=$key?>">
    <div class="ad_app_install_left">
      <a href="<?=$value[OS]['link']?>" id="external_link" data-type="chain"><img src="<?=$value[OS]['image']?>" width="100" height="100" /></a>
    </div>
    
    <div class="ad_app_install_title"><?=$value['name']?></div>
    <p><?=$value['blurb']?><br><a href="<?=$value[OS]['link']?>">アプリダウンロードはこちらから</a></p>
  </div>
<?php endif; ?>

<?php endforeach; ?>
  
  <div class="ad_app_install_another clearfix">
<?php foreach ($arr as $key => $value): ?>
<?php if ($value[OS]['link'] and $value[OS]['image']): ?>
    <div class="ad_app_install_another_sub" onclick="changeAdAppInstall(<?=$key?>)"><img src="<?=$value[OS]['image']?>" width="40" height="40" /></div>
<?php endif; ?>
<?php endforeach; ?>
  </div>
  
</aside>

<?php endif; ?>
