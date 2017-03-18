<?php

/*
 * 必要なデータ
 * 
 * string / $form_id / フォームのID
 * string / $title / タイトル
 * string / $body / ボディ
 * 
 * オプション
 * 
 * string / $modal_size / モーダルのサイズ　modal-lg
 */

$modal_size = (isset($modal_size)) ? ' ' . $modal_size : null;

?>

<aside class="modal fade" id="<?=$form_id?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog<?=$modal_size?>">
    <div class="modal-content">
      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=$title?></h4>
      </div>
      
      <div class="modal-body"><?=$body?></div>
      
    </div>
  </div>
</aside>