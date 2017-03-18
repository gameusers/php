<?php

/*
  必要なデータ
  boolean / $first_load / 最初の読み込み
  array / $db_game_data_arr / ゲームデータの配列

 オプション

*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$heading_output = '';


?>

<?php foreach ($db_game_data_arr as $key => $value): ?>

<?php

$heading = mb_convert_kana($value['kana'], 'k', 'UTF-8'); // 半角カタカナにする
$heading = mb_substr($heading , 0, 1, 'UTF-8');// 一文字取得
$heading = mb_convert_kana($heading, 'K', 'UTF-8'); // 全角カタカナにする

if ( ! $heading) $heading = 'フリガナ未設定';

if ($heading_output != $heading)
{
  $heading_output = $heading;
  $change = true;
}
else
{
  $change = false;
}

?>

<?php if ($change && $key != 0): ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<?php if ($change): ?>

<div class="panel-group" role="tablist">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="collapseListGroupHeading<?=$key?>">
      <a class="collapsed" data-toggle="collapse" href="#collapseListGroup<?=$key?>">
        <h4 class="panel-title"><?=$heading_output?> 行</h4>
      </a>
    </div>
    <div id="collapseListGroup<?=$key?>" class="panel-collapse collapse" role="tabpanel">
      <div class="list-group">
<?php endif; ?>
        <a class="list-group-item" href="<?php echo URI_BASE . 'gc/' . $value['id']; ?>"><?=$value['name']?></a>
<?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
