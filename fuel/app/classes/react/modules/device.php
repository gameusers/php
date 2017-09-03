<?php

namespace React\Modules;

class Device
{

	// public function getDeviceTypeAndOs() {
    //
	// 	$ua = $_SERVER['HTTP_USER_AGENT'];
    //
	// 	if((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') === false))
	// 	{
	// 		return array('deviceType' => 'tablet', 'os' => 'android');
	// 	}
	// 	else if((strpos($ua, 'iPad') !== false))
	// 	{
	// 		return array('deviceType' => 'tablet', 'os' => 'ios');
	// 	}
    //
	// 	$useragentsAndroid = array(
	// 		'Android',        // 1.5+ Android
	// 	);
    //
	// 	$useragentsIos = array(
	// 		'iPhone',         // Apple iPhone
	// 		'iPod',           // Apple iPod touch
	// 	);
    //
    //     $useragentsWindowsPhone = array(
	// 		'Windows Phone',
	// 	);
    //
	// 	$patternAndroid = '/' . implode('|', $useragentsAndroid) . '/i';
	// 	$patternIos = '/' . implode('|', $useragentsIos) . '/i';
	// 	$patternWindowsPhone = '/' . implode('|', $useragentsWindowsPhone) . '/i';
    //
    //
	// 	if (preg_match($patternAndroid, $ua))
	// 	{
	// 		return array('deviceType' => 'smartphone', 'os' => 'android');
	// 	}
	// 	else if (preg_match($patternIos, $ua))
	// 	{
	// 		return array('deviceType' => 'smartphone', 'os' => 'ios');
	// 	}
    //     else if (preg_match($patternWindowsPhone, $ua))
	// 	{
	// 		return array('deviceType' => 'smartphone', 'os' => 'windows');
	// 	}
	// 	else
	// 	{
	// 		return array('deviceType' => 'other', 'os' => 'other');
	// 	}
    //
	// }

}
