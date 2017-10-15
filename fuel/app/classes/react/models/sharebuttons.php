<?php

declare(strict_types=1);

namespace React\Models;

class ShareButtons extends \Model_Crud
{

    // --------------------------------------------------
	//   取得
	// --------------------------------------------------

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



    // --------------------------------------------------
    //   挿入
    // --------------------------------------------------

    /**
    * アプリ / シェアボタン / 有料プラン申し込み
    * @param array $arr
    * @return array
    */
    public function insertPaidPlan(array $arr): array
    {


        $returnArr = [];



        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey(\Config::get('stripe_secret_key_test_mode'));

        // Token is created using Stripe.js or Checkout!
        // Get the payment token ID submitted by the form:
        $token = $_POST['stripeToken'];
        // \Debug::dump($_POST);

        // Charge the user's card:
        $charge = \Stripe\Charge::create(array(
          'amount' => 1000,
          'currency' => 'jpy',
          'description' => 'Share Buttons Premium Plan',
          'source' => $token,
        ));


        // --------------------------------------------------
        //   データベースアクセス
        // --------------------------------------------------

        // $query = \DB::select(
        //     'name',
        //     'id',
        //     'author',
        //     ['website_name', 'websiteName'],
        //     ['website_url', 'websiteUrl']
        // )->from('share_buttons_themes');
        //
        // $query->where('on_off', '=', 1);
        // $query->where('type', '=', 'design');
        //
        // $query->order_by('regi_date','desc');
        // $desingArr = $query->execute()->as_array();
        //
        //
        // $query = \DB::select(
        //     'name',
        //     'id',
        //     'author',
        //     ['website_name', 'websiteName'],
        //     ['website_url', 'websiteUrl'],
        //     ['file_format', 'fileFormat']
        // )->from('share_buttons_themes');
        //
        // $query->where('on_off', '=', 1);
        // $query->where('type', '=', 'icon');
        //
        // $query->order_by('regi_date','desc');
        // $iconArr = $query->execute()->as_array();
        //
        //
        // $returnArr = [];
        //
        // foreach ($desingArr as $key => $value) {
        //     $themeNameId = $value['name'] . '-' . $value['id'];
        //     unset($value['name']);
        //     unset($value['id']);
        //
        //     $returnArr['designArr'][$themeNameId] = $value;
        // }
        //
        // foreach ($iconArr as $key => $value) {
        //     $themeNameId = $value['name'] . '-' . $value['id'];
        //     unset($value['name']);
        //     unset($value['id']);
        //
        //     $returnArr['iconArr'][$themeNameId] = $value;
        // }
        //
        //
        // // \Debug::dump($desingArr);
        // // \Debug::dump($iconArr);
        // //
        // // \Debug::dump($returnArr);
        //
        //
        return $returnArr;

    }


}
