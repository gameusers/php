<?php

namespace Fuel\Tasks;

class Cron
{

	/**
	 * 通知
	 *
	 * @return string
	 */
	public static function notifications()
	{

		$original_func_common = new \Original\Func\Common();
		$original_func_common->send_notification_mail();

	}


	/**
	 * SNS送信
	 *
	 * @return string
	 */
	public static function sns()
	{

		$original_func_common = new \Original\Func\Common();
		$original_func_common->send_sns();

	}


	/**
	 * サイトマップ作成
	 *
	 * @return string
	 */
	public static function sitemap()
	{

		$original_func_common = new \Original\Func\Common();
		$original_func_common->output_sitemap();

	}



	/**
	 * Amazon API データ取得
	 *
	 * @return string
	 */
	public static function amazon_api()
	{

		$original_func_amazon = new \Original\Func\Amazon();
		$original_func_amazon->save_api_data();

	}



}
