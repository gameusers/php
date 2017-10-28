<?php

declare(strict_types=1);

namespace React\Models;

class ShareButtons extends \Model_Crud
{


    // --------------------------------------------------
	//   取得
	// --------------------------------------------------

	/**
	* デザインテーマとアイコンテーマを取得
	* @param array $arr
	* @return array
	*/
    public function selectFirstThemes(array $arr): array
    {


        // --------------------------------------------------
        //   返り値の配列
        // --------------------------------------------------

        $returnArr = [];


        // --------------------------------------------------
        //   検索に使用する値を代入する
        // --------------------------------------------------

        $limit = $arr['limit'] ?? 20;
        $page = $arr['page'] ?? 1;
        $offset = $limit * ($page - 1);
        // $type = $arr['type'] ?? 'all';

        $limitRandom = $arr['limitRandom'] ?? 2;


        // --------------------------------------------------
        //   データベースアクセス
        // --------------------------------------------------

        $query = \DB::select(
            'name',
            'id',
            'author',
            ['website_name', 'websiteName'],
            ['website_url', 'websiteUrl'],
            'data'
        )->from('share_buttons_themes');

        $query->where('on_off', '=', 1);
        $query->where('type', '=', 'design');

        $query->order_by('share_buttons_themes_no','desc');
        $query->limit($limit);
        $query->offset($offset);

        $designArr = $query->execute()->as_array();
        $returnArr['designThemesTotal'] = \DB::count_last_query();


        $query = \DB::select(
            'name',
            'id',
            'author',
            ['website_name', 'websiteName'],
            ['website_url', 'websiteUrl'],
            ['file_format', 'fileFormat'],
            'data'
        )->from('share_buttons_themes');

        $query->where('on_off', '=', 1);
        $query->where('type', '=', 'icon');

        $query->order_by('share_buttons_themes_no','desc');
        $query->limit($limit);
        $query->offset($offset);

        $iconArr = $query->execute()->as_array();
        $returnArr['iconThemesTotal'] = \DB::count_last_query();



        // --------------------------------------------------
        //   トップに表示するテーマをランダムに取得する
        // --------------------------------------------------

        $query = \DB::select(
            'name',
            'id',
            'author',
            ['website_name', 'websiteName'],
            ['website_url', 'websiteUrl'],
            'data'
        )->from('share_buttons_themes');

        $query->where('on_off', '=', 1);
        $query->where('type', '=', 'design');

        $query->order_by(\DB::expr('RAND()'));
        $query->limit($limitRandom);
        $query->offset(0);

        $designRandomArr = $query->execute()->as_array();


        $query = \DB::select(
            'name',
            'id',
            'author',
            ['website_name', 'websiteName'],
            ['website_url', 'websiteUrl'],
            'data'
        )->from('share_buttons_themes');

        $query->where('on_off', '=', 1);
        $query->where('type', '=', 'icon');

        $query->order_by(\DB::expr('RAND()'));
        $query->limit($limitRandom);
        $query->offset(0);

        $iconRandomArr = $query->execute()->as_array();



        // --------------------------------------------------
        //   返り値の配列に代入
        // --------------------------------------------------

        foreach ($designArr as $key => $value) {
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

        foreach ($designRandomArr as $key => $value) {
            $themeNameId = $value['name'] . '-' . $value['id'];
            unset($value['name']);
            unset($value['id']);

            $returnArr['designRandomArr'][$themeNameId] = $value;
        }

        foreach ($iconRandomArr as $key => $value) {
            $themeNameId = $value['name'] . '-' . $value['id'];
            unset($value['name']);
            unset($value['id']);

            $returnArr['iconRandomArr'][$themeNameId] = $value;
        }



        // \Debug::dump($offset);

        // \Debug::dump($designArr);
        // \Debug::dump($iconArr);

        // \Debug::dump($designRandomArr);
        // \Debug::dump($designMergedArr);

        // \Debug::dump($iconRandomArr);
        // \Debug::dump($iconMergedArr);


        return $returnArr;

    }



    /**
    * デザインテーマを取得
    * @param array $arr
    * @return array
    */
    public function selectDesignThemes(array $arr): array
    {

        // --------------------------------------------------
        //   返り値の配列
        // --------------------------------------------------

        $returnArr = [];


        // --------------------------------------------------
        //   検索に使用する値を代入する
        // --------------------------------------------------

        $limit = $arr['limit'] ?? 20;
        $page = $arr['page'] ?? 1;
        $offset = $limit * ($page - 1);


        // --------------------------------------------------
        //   データベースアクセス
        // --------------------------------------------------

        $query = \DB::select(
            'name',
            'id',
            'author',
            ['website_name', 'websiteName'],
            ['website_url', 'websiteUrl'],
            'data'
        )->from('share_buttons_themes');

        $query->where('on_off', '=', 1);
        $query->where('type', '=', 'design');

        $query->order_by('share_buttons_themes_no','desc');
        $query->limit($limit);
        $query->offset($offset);

        $resultArr = $query->execute()->as_array();


        // --------------------------------------------------
        //   返り値の配列に代入
        // --------------------------------------------------

        foreach ($resultArr as $key => $value) {
            $themeNameId = $value['name'] . '-' . $value['id'];
            unset($value['name']);
            unset($value['id']);

            $returnArr[$themeNameId] = $value;
        }


        return $returnArr;

    }



    /**
    * アイコンテーマを取得
    * @param array $arr
    * @return array
    */
    public function selectIconThemes(array $arr): array
    {

        // --------------------------------------------------
        //   返り値の配列
        // --------------------------------------------------

        $returnArr = [];


        // --------------------------------------------------
        //   検索に使用する値を代入する
        // --------------------------------------------------

        $limit = $arr['limit'] ?? 20;
        $page = $arr['page'] ?? 1;
        $offset = $limit * ($page - 1);


        // --------------------------------------------------
        //   データベースアクセス
        // --------------------------------------------------

        $query = \DB::select(
            'name',
            'id',
            'author',
            ['website_name', 'websiteName'],
            ['website_url', 'websiteUrl'],
            ['file_format', 'fileFormat'],
            'data'
        )->from('share_buttons_themes');

        $query->where('on_off', '=', 1);
        $query->where('type', '=', 'icon');

        $query->order_by('share_buttons_themes_no','desc');
        $query->limit($limit);
        $query->offset($offset);

        $resultArr = $query->execute()->as_array();


        // --------------------------------------------------
        //   返り値の配列に代入
        // --------------------------------------------------

        foreach ($resultArr as $key => $value) {
            $themeNameId = $value['name'] . '-' . $value['id'];
            unset($value['name']);
            unset($value['id']);

            $returnArr[$themeNameId] = $value;
        }


        return $returnArr;

    }



    // --------------------------------------------------
    //   挿入
    // --------------------------------------------------

	/**
	* data.json を読み込んで data に入力する
	* @param array $arr
	* @return array
	*/
	public function updateThemesData(array $arr): array
	{


        // --------------------------------------------------
        //   返り値の配列
        // --------------------------------------------------

        $returnArr = [];


		// --------------------------------------------------
		//   データベースから登録されているテーマを取得
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
		$designArr = $query->execute()->as_array();


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



        // --------------------------------------------------
        //   トランザクション開始
        // --------------------------------------------------

        \DB::start_transaction();


        // --------------------------------------------------
        //   share_buttons_themes の data を更新
        // --------------------------------------------------

        \Debug::dump($designArr);
        \Debug::dump($iconArr);


        // --------------------------------------------------
        //   data.json ファイル取得
        // --------------------------------------------------

        foreach ($designArr as $key => $value) {
            $url = "https://gameusers.org/react/contents/app/share-buttons/themes-design/{$value['name']}-{$value['id']}/data.json";
            $json = file_get_contents($url);

            $query = \DB::update('share_buttons_themes');
            $query->set(['data' => $json]);
            $query->where('id', '=', $value['id']);
            $resultArr = $query->execute();
        }

        foreach ($iconArr as $key => $value) {
            $url = "https://gameusers.org/react/contents/app/share-buttons/themes-icon/{$value['name']}-{$value['id']}/data.json";
            $json = file_get_contents($url);

            $query = \DB::update('share_buttons_themes');
            $query->set(['data' => $json]);
            $query->where('id', '=', $value['id']);
            $resultArr = $query->execute();
        }


        // --------------------------------------------------
        //   コミット
        // --------------------------------------------------

        \DB::commit_transaction();



		return $returnArr;

	}





    /**
    * アプリ / シェアボタン / 有料プラン申し込み
    * @param array $arr
    * @return array
    */
    public function insertPaidPlan(array $arr): array
    {

        // --------------------------------------------------
        //   必要なデータがない場合は処理停止
        // --------------------------------------------------

        if (
            empty($_POST['plan']) ||
            empty($_POST['webSiteName']) ||
            empty($_POST['webSiteUrl']) ||
            empty($_POST['stripeToken']) ||
            empty($_POST['stripeTokenType']) ||
            empty($_POST['stripeEmail']) ||
            empty(USER_NO) ||
            empty(DEVICE_TYPE) ||
            empty(DEVICE_OS) ||
            empty(LANGUAGE) ||
            empty(HOST) ||
            empty(USER_AGENT)
        ) {
            throw new Exception();
        }



        // --------------------------------------------------
        //   データ取得・設定
        // --------------------------------------------------

        $returnArr = [];

        $plan = $_POST['plan'];
        $webSiteName = $_POST['webSiteName'];
        $webSiteUrl = $_POST['webSiteUrl'];

        $stripeToken = $_POST['stripeToken'];
        $stripeTokenType = $_POST['stripeTokenType'];
        $stripeEmail = $_POST['stripeEmail'];


        // --------------------------------------------------
        //   日時作成
        // --------------------------------------------------

        $modulesDatetime = new \React\Modules\Datetime();
        $datetimeNow = $modulesDatetime->databaseFormat();


        // --------------------------------------------------
        //   Eメールを暗号化する
        // --------------------------------------------------

        $modulesEncryption = new \React\Modules\Encryption();
        $stripeEncryptedEmail = $modulesEncryption->encrypt($stripeEmail);

        // \Debug::dump($datetimeNow, $plan, $webSiteName, $webSiteUrl, $stripeToken, $stripeTokenType, $stripeEmail, $stripeEncryptedEmail, DEVICE_TYPE, DEVICE_OS, LANGUAGE, HOST, USER_AGENT);

        // exit();


        // --------------------------------------------------
        //   Stripe API Key を設定する
        // --------------------------------------------------

        if (\Fuel::$env === 'production') {
            $stripeSecretKey = \Config::get('stripe_secret_key');
        } else {
            $stripeSecretKey = \Config::get('stripe_secret_key_test_mode');
        }

        \Stripe\Stripe::setApiKey($stripeSecretKey);


        // --------------------------------------------------
        //   Stripe で請求する
        // --------------------------------------------------

        if ($plan === 'premium') {

            $charge = \Stripe\Charge::create(array(
              'amount' => 1000,
              'currency' => 'jpy',
              'description' => 'Share Buttons Premium Plan',
              'source' => $stripeToken,
            ));

        } else if ($plan === 'business') {

            $charge = \Stripe\Charge::create(array(
              'amount' => 3000,
              'currency' => 'jpy',
              'description' => 'Share Buttons Business Plan',
              'source' => $stripeToken,
            ));

        } else {
            throw new Exception();
        }

        // \Debug::dump($charge);


        // --------------------------------------------------
        //   返ってきたデータから必要なデータを取り出す
        // --------------------------------------------------

        $paymentId = $charge->id ?? null;
        $amount = $charge->amount ?? null;
        $currency = $charge->currency ?? null;
        // $status = $charge->status ?? null;

        // \Debug::dump($paymentId, $amount, $currency);



        // --------------------------------------------------
        //   データベースに保存
        // --------------------------------------------------

        $query = \DB::insert('share_buttons_paid_plan');

        $query->set([
            'regi_date' => $datetimeNow,
            'plan' => $plan,
            'web_site_name' => $webSiteName,
            'web_site_url' => $webSiteUrl,
            'payment_id' => $paymentId,
            'amount' => $amount,
            'currency' => $currency,
            'stripe_token_type' => $stripeTokenType,
            'stripe_email' => $stripeEncryptedEmail,
            'user_no' => USER_NO,
            'device_type' => DEVICE_TYPE,
            'device_os' => DEVICE_OS,
            'language' => LANGUAGE,
            'host' => HOST,
            'user_agent' => USER_AGENT
        ]);

        $query->execute();


        return $returnArr;

    }


}
