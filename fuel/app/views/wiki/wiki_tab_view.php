<?php

/*
 * 必要なデータ
 * 
 * 
 * string / $code_wiki_list / 一覧
 * string / $code_wiki_create / 作成
 * array / $code_wiki_edit_list / 編集
 * 
 * オプション
 */


// --------------------------------------------------
//   active
// --------------------------------------------------

$active_list = null;
$active_create = null;
$active_edit_list = null;
 
if($code_list)
{
	$active_list = 'active';
}
else if($code_create)
{
	$active_create = 'active';
}
else if($code_edit_list)
{
	$active_edit_list = 'active';
}
else
{
	$active_create = 'active';
}

?>

<div class="btn-group margin_bottom_30">
<?php if($on_list): ?>
  <button type="button" class="btn btn-default ladda-button <?=$active_list?>" data-style="expand-right" data-spinner-color="#000000" onclick="GAMEUSERS.common.changeContents(this, 'wiki', 'list')">一覧</button>
<?php endif; ?>
<?php if($on_create): ?>
  <button type="button" class="btn btn-default ladda-button <?=$active_create?>" data-style="expand-right" data-spinner-color="#000000" onclick="GAMEUSERS.common.changeContents(this, 'wiki', 'create')">作成</button>
<?php endif; ?>
<?php if($on_edit_list): ?>
  <button type="button" class="btn btn-default ladda-button <?=$active_edit_list?>" id="button_change_contents_wiki_edit" data-style="expand-right" data-spinner-color="#000000" onclick="GAMEUSERS.common.changeContents(this, 'wiki', 'edit')">編集</button>
<?php endif; ?>
</div>

<article class="<?=$active_list?>" id="change_contents_wiki_list">
<?=$code_list?>
</article>

<article class="<?=$active_create?>" id="change_contents_wiki_create">
<?=$code_create?>
</article>

<article class="<?=$active_edit_list?>" id="change_contents_wiki_edit">
<?=$code_edit_list?>
</article>