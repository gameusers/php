<?php

class Model_Sharebuttons extends Model_Crud
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
	public function select_design_icon_themes($arr)
	{


		// --------------------------------------------------
		//   データベースアクセス
		// --------------------------------------------------

		$query = DB::select(
			'name',
			'id',
			'author',
			'website_name',
			'website_url'
		)->from('share_buttons_themes');

		$query->where('on_off', '=', 1);
        $query->where('type', '=', 'design');

		$query->order_by('regi_date','desc');
		$desingArr = $query->execute()->as_array();


        $query = DB::select(
			'name',
			'id',
			'author',
			'website_name',
			'website_url',
            'file_format'
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
