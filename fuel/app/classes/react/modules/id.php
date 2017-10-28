<?php

declare(strict_types=1);

namespace React\Modules;

class Id
{


    /**
     * ランダムなIDを生成する
     * @param  number $length 生成するID文字数
     * @return string         ランダムなID
     */
	public function createRandomId($length) {

		//使用する文字
		$char = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$charlen = mb_strlen($char);
		$result = "";

		for($i=1; $i <= $length; $i++){
			$index = mt_rand(0, $charlen - 1);
			$result .= mb_substr($char, $index, 1);
		}

		return $result;

	}


}
