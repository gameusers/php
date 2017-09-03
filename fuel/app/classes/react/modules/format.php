<?php

declare(strict_types=1);

namespace React\Modules;

class Format
{

    /**
     * データベースに保存するフォーマットに変換
     * またはデータベースに保存されているデータを利用時に適切なデータに変換する
     * @return string [description]
     */
	public function convertDatabaseData(string $type, $data) {


        // --------------------------------------------------
		//   CSV をデータベースに保存する形式に変換　/,1,2,3,/
		// --------------------------------------------------

		if ($type == 'csv into dbformat')
		{
			$tempArr = explode(',', $data);
			array_unshift($tempArr, '/');
			array_push($tempArr, '/');
			$returnData = implode(',', $tempArr);
		}

		// --------------------------------------------------
		//   PHP の配列をデータベースに保存する形式に変換　/,1,2,3,/
		// --------------------------------------------------

		else if ($type == 'array into dbformat')
		{
            $tempArr = $data;
			array_unshift($tempArr, '/');
			array_push($tempArr, '/');
			$returnData = implode(',', $tempArr);
		}

		// --------------------------------------------------
		//   データベースに保存された形式をPHPの配列に変換　array()
		// --------------------------------------------------

		else if ($type == 'dbformat into array')
		{
			$tempArr = explode(',', $data);
			array_shift($tempArr);
			array_pop($tempArr);
			$returnData = $tempArr;
		}


		return $returnData;

	}



}
