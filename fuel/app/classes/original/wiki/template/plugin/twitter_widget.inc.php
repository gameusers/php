<?php
/*
 Twitterウィジェット表示プラグイン
 twitter_widget.inc.php

 by http://kaz-ic.net/

 Released under the MIT License.
 http://opensource.org/licenses/MIT
*/

function plugin_twitter_widget_convert(){
	$params = array(
		'data-widget-id'  => '',
		'width'           => '520',
		'height'          => '600',
		'data-theme'      => 'light',
		'data-link_color' => '#66ABFF',
		'href'            => 'https://twitter.com/',
		'by'              => 'Tweets'
	);

	if(!func_num_args()) return FALSE;
	$args = func_get_args();
	if(!empty($args)) foreach($args as $arg) twitter_widget_check_arg($arg, $params);

	return <<<EOD
<a class="twitter-timeline" href="{$params['href']}" data-widget-id="{$params['data-widget-id']}" width="{$params['width']}" height="{$params['height']}" data-theme="{$params['data-theme']}" data-link_color="{$params['data-link_color']}">{$params['by']}</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
EOD;
}

function plugin_twitter_widget_inline(){
	$args = func_get_args();
	return call_user_func_array('plugin_twitter_widget_convert', $args);
}

function twitter_widget_check_arg($val, & $params){

	// オリジナル追加
	$val = htmlspecialchars($val, ENT_QUOTES);

	if(!strpos($val, '=')) return;
	list($l_val, $v_val) = explode('=', strtolower($val));
	foreach(array_keys($params) as $key){
		if(strpos($l_val, $key) === 0){
			$params[$key] = $v_val;
			return;
		}
	}
}
?>
