<?php

namespace Original\Common;

class Mobilecheck
{
	
	public function return_agent_type() {
		
		$ua = $_SERVER['HTTP_USER_AGENT'];
		
		if((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') === false)) {
			return 'tablet';
		} else if((strpos($ua, 'iPad') !== false)) {
			return 'tablet';
		}
		
		$useragents = array(
		'iPhone',         // Apple iPhone
		'iPod',           // Apple iPod touch
		'Android',        // 1.5+ Android
		'dream',          // Pre 1.5 Android
		'CUPCAKE',        // 1.5+ Android
		'BlackBerry', // BlackBerry
		'Windows Phone', // Windows Phone
		'Symbian', // Symbian
		'webOS',          // Palm Pre Experimental
		'incognito',      // Other iPhone browser
		'webmate'        // Other iPhone browser
		);
		$pattern = '/' . implode('|', $useragents) . '/i';
		
		if(preg_match($pattern, $ua)) {
			return 'smartphone';
		} else {
			return null;
		}
		
	}
	
	
	
	public function return_agent_type2() {
		
		$ua = $_SERVER['HTTP_USER_AGENT'];
		
		if((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') === false))
		{
			return array('device' => 'tablet', 'os' => 'android');
		}
		else if((strpos($ua, 'iPad') !== false))
		{
			return array('device' => 'tablet', 'os' => 'ios');
		}
		
		$useragents_android = array(
			'Android',        // 1.5+ Android
		);
		
		$useragents_ios = array(
			'iPhone',         // Apple iPhone
			'iPod',           // Apple iPod touch
		);
		
		$useragents_etc = array(
			'dream',          // Pre 1.5 Android
			'CUPCAKE',        // 1.5+ Android
			'BlackBerry', // BlackBerry
			'Windows Phone', // Windows Phone
			'Symbian', // Symbian
			'webOS',          // Palm Pre Experimental
			'incognito',      // Other iPhone browser
			'webmate'        // Other iPhone browser
		);
		
		$pattern_android = '/' . implode('|', $useragents_android) . '/i';
		$pattern_ios = '/' . implode('|', $useragents_ios) . '/i';
		$pattern_etc = '/' . implode('|', $useragents_etc) . '/i';
		
		
		if(preg_match($pattern_android, $ua))
		{
			return array('device' => 'smartphone', 'os' => 'android');
		}
		else if(preg_match($pattern_ios, $ua))
		{
			return array('device' => 'smartphone', 'os' => 'ios');
		}
		else if(preg_match($pattern_etc, $ua))
		{
			return array('device' => 'smartphone', 'os' => 'etc');
		}
		else
		{
			return array('device' => null, 'os' => null);
		}
		
	}

}