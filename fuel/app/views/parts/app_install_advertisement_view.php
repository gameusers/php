<?php

$code = null;

// --------------------------------------------------
//    Android
// --------------------------------------------------

if ($os == 'android')
{
	
	// ポイッとヒーロー
	if ($game_no == 48)
	{
		$code = '  <a href="https://www.gamefeat.net/webapi/v1/reportClick?ad_id=4890&site_id=6298" id="external_link" data-type="chain"><img src="https://www.gamefeat.net/webapi/v1/requestImg?ad_id=4890&site_id=6298&display=icon" /></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
	// 乖離性ミリオンアーサー
	if ($game_no == 86)
	{
		$code = '  <a href="https://smart-c.jp/c?i=1H21aI0e9cZM016e3&guid=ON" id="external_link" data-type="chain"><img src="https://image.smart-c.jp/i?i=1H21aI0e9cZM016e3"></a></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
	// 戦の海賊
	else if ($game_no == 179)
	{
		$code = '  <a href="https://smart-c.jp/c?i=4EiAlP0a51Et016e3&guid=ON" id="external_link" data-type="chain"><img src="https://image.smart-c.jp/i?i=4EiAlP0a51Et016e3"></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
	// 崩壊学園
	else if ($game_no == 212)
	{
		$code = '  <a href="https://smart-c.jp/c?i=2IWvYi0YRTLt016e3&guid=ON" id="external_link" data-type="chain"><img src="https://image.smart-c.jp/i?i=2IWvYi0YRTLt016e3"></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
	// ドラゴンシャウト
	else if ($game_no == 221)
	{
		$code = '  <a href="http://linkage-m.net/system/link.php?i=5603a337bfe4d&m=5600df4255631&guid=ON" id="external_link" data-type="chain"><img src="http://linkage-m.net/system/data.php?i=5603a337bfe4d&m=5600df4255631" width="114" height="114" border="0" /></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
}

// --------------------------------------------------
//    iOS
// --------------------------------------------------

else if ($os == 'ios')
{
	
	// ポイッとヒーロー
	if ($game_no == 48)
	{
		$code = '  <a href="https://www.gamefeat.net/webapi/v1/reportClick?ad_id=4889&site_id=6298" id="external_link" data-type="chain"><img src="https://www.gamefeat.net/webapi/v1/requestImg?ad_id=4889&site_id=6298&display=icon" /></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
	// 乖離性ミリオンアーサー
	if ($game_no == 86)
	{
		$code = '  <a href="https://smart-c.jp/c?i=0pxSbc06L3nK016e3&guid=ON" id="external_link" data-type="chain"><img src="https://image.smart-c.jp/i?i=0pxSbc06L3nK016e3"></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
	// 戦の海賊
	else if ($game_no == 179)
	{
		$code = '  <a href="https://smart-c.jp/c?i=4ppYLn0QF0Nn016e3&guid=ON" id="external_link" data-type="chain"><img src="https://image.smart-c.jp/i?i=4ppYLn0QF0Nn016e3"></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
	// 崩壊学園
	else if ($game_no == 212)
	{
		$code = '  <a href="https://smart-c.jp/c?i=4GDGzv08eZfM016e3&guid=ON" id="external_link" data-type="chain"><img src="https://image.smart-c.jp/i?i=4GDGzv08eZfM016e3"></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
	// ドラゴンシャウト
	else if ($game_no == 221)
	{
		$code = '  <a href="http://linkage-m.net/system/link.php?i=5603a3dedb7ca&m=5600df4255631&guid=ON" id="external_link" data-type="chain"><img src="http://linkage-m.net/system/data.php?i=5603a3dedb7ca&m=5600df4255631" width="114" height="114" border="0" /></a>
  <div class="margin_top_10">アプリダウンロードはこちらから</div>';
	}
	
}

?>

<?php if ($code): ?>
<div class="margin_bottom_20">
<?=$code?>
</div>
<?php endif; ?>
