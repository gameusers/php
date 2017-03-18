<?php

/*
 * 必要なデータ
 * string / $content_id / コンテンツID
 * 
 * オプション
 * boolean / $app_mode / アプリの場合
 */

?>

    <div class="container content_box" id="<?=$content_id?>">

      <h3><?php echo __('logout'); ?></h3>
      <p class="explanation"><?php echo __('logout_view_explanation'); ?></p>
      <div id="alert_logout"></div>
      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_logout"><span class="ladda-label"><?php echo __('logout_view_logout_button'); ?></span></button></div>
      
    </div>