<?php
// Game Users Wiki オリジナルプラグイン

function plugin_gu_menu_read_bbs_convert() 
{
	$argument_arr = func_get_args();
	return gu_menu_read_bbs_tag($argument_arr);
}



function plugin_gu_menu_read_bbs_inline()
{
	$argument_arr = func_get_args();
	return gu_menu_read_bbs_tag($argument_arr);
}



function gu_menu_read_bbs_tag($argument_arr) 
{
	
	//if (empty($argument_arr[0])) return null;
	
	//$id = htmlspecialchars($argument_arr[0], ENT_QUOTES);
	
	$code = '<p id="gu_menu_read_bbs"></p>' . "\n\n";
	
	return $code;
	
}
?>
