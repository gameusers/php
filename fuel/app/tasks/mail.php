<?php

namespace Fuel\Tasks;

class Mail
{

	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r robots
	 *
	 * or
	 *
	 * php oil r robots "Kill all Mice"
	 *
	 * @return string
	 */
	public static function all()
	{
		
		$original_func_common = new \Original\Func\Common();
		$original_func_common->send_notification_mail();
		
	}
	
}
