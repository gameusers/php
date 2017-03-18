<?php
// Game Users Wiki オリジナルプラグイン

function plugin_gu_ad_convert() 
{
	$argument_arr = func_get_args();
	return gu_ad_tag($argument_arr);
}



function plugin_gu_ad_inline()
{
	$argument_arr = func_get_args();
	return gu_ad_tag($argument_arr);
}



function gu_ad_tag($argument_arr) 
{
	
	if (empty($argument_arr[0])) return null;
	
	$name = htmlspecialchars($argument_arr[0], ENT_QUOTES);
	
	$code = '<aside class="gu_ad_user" id="gu_ad_user_' . $name . '"></aside>' . "\n\n";
	
	return $code;
	
}
?>
