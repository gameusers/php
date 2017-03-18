<?php
// Game Users Wiki オリジナルプラグイン

function plugin_gu_popup_movie_convert() 
{
	$argument_arr = func_get_args();
	return gu_popup_movie_tag($argument_arr);
}



function plugin_gu_popup_movie_inline()
{
	$argument_arr = func_get_args();
	return gu_popup_movie_tag($argument_arr);
}



function gu_popup_movie_tag($argument_arr) 
{
	
	if (empty($argument_arr[0])) return null;
	
	$id = htmlspecialchars($argument_arr[0], ENT_QUOTES);
	
	$code = '<div class="video_box">' . "\n";
	$code .= '  <div class="video_thumbnail"><img src="https://img.youtube.com/vi/' . $id . '/mqdefault.jpg" width="320" height="180" /></div>' . "\n";
	$code .= '  <div class="video_play_button" id="video_popup" onclick="popupMovie(this)" data-url="https://www.youtube.com/watch?v=' . $id . '"><img src="https://gameusers.org/assets/img/common/movie_play_button.png"></div>' . "\n";
	$code .= '</div>' . "\n\n";
	
	return $code;
	
}
?>
