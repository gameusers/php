<?php

/*
 * 必要なデータ
 * array / $game_no / ゲームNo
 * string / $id_1～5 / ID 1～5
 * string / $info_1～5 / Info 1～5
 * 
 * オプション
 * 
 */

$chain_url = null;

if ($game_no == 48 and isset($id_1, $info_1))  // 48 ポイッとヒーロー
{
	$chain_url = 'http://chain.poitto-hero.com/join?id=' . $id_1 . '&key=' . $info_1;
}


if ($chain_url)
{
	echo '            <a href="' . $chain_url . '" id="external_link" type="button" class="btn btn-success btn-sm" role="button" data-type="chain"><span class="glyphicon glyphicon-flash" aria-hidden="true"></span> 参加する！</a>' . "\n";
}

?>
