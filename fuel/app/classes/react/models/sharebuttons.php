<?php

declare(strict_types=1);

namespace React\Models;

class ShareButtons extends \Model_Crud
{

	// --------------------------------------------------
	//   シェアボタン
	// --------------------------------------------------

	// ------------------------------
	//    取得
	// ------------------------------

	/**
	* デザインテーマとアイコンテーマをすべて取得
	* @param array $arr
	* @return array
	*/
	public function selectDesignIconThemes(array $arr): array
	{


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = \DB::select(
			'name',
			'id',
			'author',
            ['website_name', 'websiteName'],
			['website_url', 'websiteUrl']
		)->from('share_buttons_themes');

		$query->where('on_off', '=', 1);
        $query->where('type', '=', 'design');

		$query->order_by('regi_date','desc');
		$desingArr = $query->execute()->as_array();


        $query = \DB::select(
			'name',
			'id',
			'author',
			['website_name', 'websiteName'],
			['website_url', 'websiteUrl'],
            ['file_format', 'fileFormat']
		)->from('share_buttons_themes');

		$query->where('on_off', '=', 1);
        $query->where('type', '=', 'icon');

		$query->order_by('regi_date','desc');
		$iconArr = $query->execute()->as_array();


        $returnArr = [];

        foreach ($desingArr as $key => $value) {
            $themeNameId = $value['name'] . '-' . $value['id'];
            unset($value['name']);
            unset($value['id']);

            $returnArr['designArr'][$themeNameId] = $value;
        }

        foreach ($iconArr as $key => $value) {
            $themeNameId = $value['name'] . '-' . $value['id'];
            unset($value['name']);
            unset($value['id']);

            $returnArr['iconArr'][$themeNameId] = $value;
        }


        // \Debug::dump($desingArr);
        // \Debug::dump($iconArr);
        //
        // \Debug::dump($returnArr);


        return $returnArr;
		// return ['designArr' => $desingArr, 'iconArr' => $iconArr];

	}


}
