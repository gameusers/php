<?php

declare(strict_types=1);

class Controller_Api extends Controller_Rest
{

    /**
    * 事前処理
    */
    public function before()
    {

        parent::before();


        // --------------------------------------------------
        //   設定読み込み
        // --------------------------------------------------

        require_once(APPPATH . 'classes/react/configurations/config.php');

    }


	/**
	* API React バージョン用
	*
	* @return array 配列
	*/
	public function post_react()
    {

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		// $test = true;

		if (isset($test)) {

			Debug::$js_toggle_open = true;

            $_POST['csrfToken'] = '92fab32bb41475af1a8896e24829479953c81ce4cdda318e099a39f10db3c6e51ee589c8138c65a1adbdd17cf306cb557b3e26fb28d3ace5fdd1ceee73764291';

			// $_POST['apiType'] = 'selectNotification';
            // $_POST['readType'] = 'unread';
            // // $_POST['readType'] = 'alreadyRead';
            // $_POST['page'] = 2;


            // $_POST['apiType'] = 'updateAllUnreadToAlreadyRead';

            $_POST['apiType'] = 'insertShareButtonsPaidPlan';
            $_POST['plan'] = 'business';
            $_POST['webSiteName'] = 'Test Site';
            $_POST['webSiteUrl'] = 'https://www.example.com/';

		}



		try {


            // --------------------------------------------------
			//   CSRF対策 トークンチェック
			// --------------------------------------------------

            $instance = new \React\Modules\Security();
            if ( ! $instance->ckeckCsrfToken()) {
                throw new Exception('csrf');
            }



            // --------------------------------------------------
            //   通知 / 未読数を取得 / ヘッダーのベルの数字
            // --------------------------------------------------

            if (Input::post('apiType') === 'selectNotificationUnreadCount') {
                $instance = new \React\Models\Notification();
                $arr = $instance->selectUnreadCount(Input::post());
            }


            // --------------------------------------------------
            //   通知 / 取得
            // --------------------------------------------------

            else if (Input::post('apiType') === 'selectNotification') {
                $instance = new \React\Models\Notification();
                $arr = $instance->selectNotification(Input::post());
            }


            // --------------------------------------------------
            //   通知 / 予約IDを既読IDにする
            // --------------------------------------------------

            else if (Input::post('apiType') === 'updateReservationIdToAlreadyReadId') {
                $instance = new \React\Models\Notification();
                $arr = $instance->updateReservationIdToAlreadyReadId(Input::post());
            }


            // --------------------------------------------------
            //   通知 / すべて既読にする
            // --------------------------------------------------

            else if (Input::post('apiType') === 'updateAllUnreadToAlreadyRead') {
                $instance = new \React\Models\Notification();
                $arr = $instance->updateAllUnreadToAlreadyRead(Input::post());
            }



			// --------------------------------------------------
			//   フッター / カードのデータを取得
			//   gameCommunityRenewal / 最近更新されたゲームコミュニティ
            //   gameCommunityAccess / 最近アクセスしたゲームコミュニティ
            //   userCommunityAccess / 最近アクセスしたユーザーコミュニティ
			// --------------------------------------------------

			else if (Input::post('apiType') === 'selectFooterCard') {
                $instance = new \React\Models\Footer();
        		$arr = $instance->selectCard(Input::post());
			}



            // --------------------------------------------------
            //   アプリ / シェアボタン / 有料プラン申し込み
            // --------------------------------------------------

            else if (Input::post('apiType') === 'insertShareButtonsPaidPlan') {
                $instance = new \React\Models\ShareButtons();
                $arr = $instance->insertPaidPlan(Input::post());
            }



		} catch (Exception $e) {

			if (isset($test)) \Debug::dump($e);

			$arr['error'] = true;
            $arr['errorMessage'] = $e->getMessage();

		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test)) {
			\Debug::dump($arr);
		} else {
            $arr['error'] = false;
			return $this->response($arr);
		}

	}



	/**
	* API 外部公開用
	*
	* @return array 配列
	*/
	public function post_public()
    {

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		// $test = true;

		if (isset($test)) {
			Debug::$js_toggle_open = true;

			// $_POST['apiType'] = 'shareButtonsFirstThemes';
            // $_POST['apiType'] = 'shareButtonsDesignThemes';
            $_POST['apiType'] = 'shareButtonsIconThemes';
            $_POST['page'] = 2;


			// // $_POST['game_no'] = 1;
			// $_POST['bbs_id'] = 'lnntfuztqvqbqwqb';
			// $_POST['game_no'] = 1;

			//$_POST['page'] = 1;

			//$_POST['keyword'] = '';
			//$_POST['game_no'] = 386;
			//$_POST['history_no'] = 0;

			// $_POST['keyword'] = '';
			// $_POST['page'] = 1;
		}


		$arr = array();

		try {


            // --------------------------------------------------
			//   シェアボタンのテーマを返す / 最初のテーマを読み込む
			// --------------------------------------------------

			if (Input::post('apiType') === 'shareButtonsFirstThemes')
			{
                $instance = new \React\Models\ShareButtons();
                $arr = $instance->selectFirstThemes(Input::post());
                header('Access-Control-Allow-Origin: *');
			}


            // --------------------------------------------------
			//   シェアボタンのテーマを返す / designThemes のデータをJSONで返す
			// --------------------------------------------------

            else if (Input::post('apiType') === 'shareButtonsDesignThemes')
			{
                $instance = new \React\Models\ShareButtons();
                $arr = $instance->selectDesignThemes(Input::post());
                header('Access-Control-Allow-Origin: *');
			}


            // --------------------------------------------------
			//   シェアボタンのテーマを返す / iconThemes のデータをJSONで返す
			// --------------------------------------------------

            else if (Input::post('apiType') === 'shareButtonsIconThemes')
			{
                $instance = new \React\Models\ShareButtons();
                $arr = $instance->selectIconThemes(Input::post());
                header('Access-Control-Allow-Origin: *');
			}


		} catch (Exception $e) {

			if (isset($test)) \Debug::dump($e);

            $arr['error'] = true;
            $arr['errorMessage'] = $e->getMessage();

		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test)) {
			\Debug::dump($arr);
		} else {
			return $this->response($arr);
		}

	}

}
