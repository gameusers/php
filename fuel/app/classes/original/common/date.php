<?php

namespace Original\Common;

class Date
{

	/**
	* SQLに保存する形式で日時を返す
	*
	* @param string $string 日付の変更（-1 dayなど）
	* @return string 日時を返す
	*/
	public function sql_format($string = null) {

		$datetime = new \DateTime();
		if ($string)
		{
			$datetime->modify($string);
		}
		$result = $datetime->format("Y-m-d H:i:s");

		return $result;

	}


	/**
	* 日時を○○前に変換する
	*
	* @param string $datetime_comparison 比較する日時
	* @param string $datetime_now 今の日時
	* @return string 変換された日時を返す
	*/
	public function datetime_convert($comparison, $now = null) {

		if ($now)
		{
			$datetime_now = new \DateTime($now);
		}
		else
		{
			$datetime_now = new \DateTime();
		}

		$datetime_comparison = new \DateTime($comparison);
		$interval = $datetime_now->diff($datetime_comparison);

		if ($interval->format('%y') >= 1)
		{
			$interval_time = $interval->format('%y年前');
		}
		else if ($interval->format('%m') >= 1)
		{
			$interval_time = $interval->format('%mヶ月前');
		}
		else if ($interval->format('%d') >= 1)
		{
			$interval_time = $interval->format('%d日前');
		}
		else if ($interval->format('%h') >= 1)
		{
			$interval_time = $interval->format('%h時間前');
		}
		else if ($interval->format('%i') >= 1)
		{
			$interval_time = $interval->format('%i分前');
		}
		else
		{
			$interval_time = $interval->format('%s秒前');
		}

		return $interval_time;

	}

}
