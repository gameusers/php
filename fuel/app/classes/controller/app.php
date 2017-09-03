<?php

class Controller_App extends Controller
{


	/**
	* 事前処理
	*/
	// public function before()
	// {
	// 	parent::before();
	// }


	/**
	* ページ表示
	*/
	public function action_index($urlDirectory1 = null, $urlDirectory2 = null, $urlDirectory3 = null)
	{

        // --------------------------------------------------
		//   設定読み込み
		// --------------------------------------------------

        require_once(APPPATH . 'classes/react/configurations/config.php');



		// --------------------------------------------------
		//   メンテナンス中の場合、503 / メンテナンスビューを表示する
        //   メンテナンス中であっても管理者の場合は通常通りページを表示する
		// --------------------------------------------------

		if (Config::get('maintenance') == 2 and ! Auth::member(100)) {
			return Response::forge(View::forge('maintenance_view'), 503);
		}


		// $tempArr = unserialize('a:1:{s:7:"image_1";a:2:{s:5:"width";i:1000;s:6:"height";i:563;}}');
        // $tempArr = unserialize('a:1:{i:0;a:1:{s:7:"youtube";s:11:"M8-vje-bq9c";}}');
        // $json = json_encode($tempArr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        //
        // \Debug::dump($tempArr);
        // \Debug::dump($json);

		// --------------------------------------------------
		//   アクセスしたURLをチェック
        //   規定のURL以外にアクセスした場合、404 / Not Found を表示する
		// --------------------------------------------------

        // $pattern = '/^(share-buttons)$/';
        //
        // if ( ! preg_match($pattern, $urlDirectory1)) {
        //     throw new HttpNotFoundException;
        // }


		// --------------------------------------------------
		//   ログイン後にこのページに戻ってくる設定
		// --------------------------------------------------

		// if (USER_NO === null)
		// {
		// 	Session::set('redirect_type', 'app');
		// 	Session::set('redirect_id', null);
		// }


		// --------------------------------------------------
		//   Access Date更新
		// --------------------------------------------------

		// if (USER_NO) $original_func_common->renew_access_date(USER_NO, null, null);



        // --------------------------------------------------
        //   CSRF対策のトークンをセット
        // --------------------------------------------------

        $modulesSecurity = new \React\Modules\Security();
        $this->initialStateArr['csrfToken'] = $modulesSecurity->setCsrfToken();



        // --------------------------------------------------
		//   通知のデータ取得
		// --------------------------------------------------

        $tempArr = [];
        $modelsNotification = new \React\Models\Notification();
        $this->initialStateArr['notificationObj']['unreadCount'] = $modelsNotification->selectUnreadCount($tempArr)['unreadCount'];


		// --------------------------------------------------
		//   ヘッダーのデータ取得
		// --------------------------------------------------

		$tempArr = [
			// 'community_no' => 12,
			// 'game_no_arr' => [1,11,306,554]
			// 'game_no_arr' => [597]
			// 'game_no_arr' => [604]
			'gameNoArr' => [3]
            // 'gameNoArr' => [426]
            // 'gameNoArr' => [1]
            // 'gameNoArr' => [1,11,306,554]
		];

        $modelsHeader = new \React\Models\Header();
		$headerArr = $modelsHeader->selectHeader($tempArr);
		$this->initialStateArr['headerObj'] = $headerArr;


        // --------------------------------------------------
		//   フッターのデータ取得
		// --------------------------------------------------

		$tempArr = [];

        $modelsFooter = new \React\Models\Footer();
		$footerArr = $modelsFooter->selectCard($tempArr);
		$this->initialStateArr['footerObj'] = $footerArr;




		// ----------------------------------------
		//    Meta
		// ----------------------------------------

		if ($urlDirectory1 === 'share-buttons') {
			$title = 'Game Users Share Buttons';
			$metaKeywords = 'ゲームユーザーズ,シェアボタン';
			$metaDescription = 'Game UsersはゲームユーザーのためのSNS・コミュニティサイトです。';
            $metaOgType = 'article';

            // $description = str_replace(array("\r\n","\r","\n"), ' ', $description);
            // $description = (mb_strlen($description) > 120) ? mb_substr($description, 0, 119, 'UTF-8') . '…' : $description;
		}

        $title = 'Game Users Share Buttons';
        $metaKeywords = 'ゲームユーザーズ,シェアボタン';
        $metaDescription = 'Game UsersはゲームユーザーのためのSNS・コミュニティサイトです。';
        $metaOgType = 'article';



        // $test = true;

		if (isset($test)) {
			Debug::$js_toggle_open = true;

			echo "<br><br><br><br>";

            \Debug::dump($this->initialStateArr);

		}
		//exit();






		// --------------------------------------------------
		//    スタイルシート
		// --------------------------------------------------

		$cssArr = array(
			CSS_RESET_CDN_ARR,
            CSS_BOOTSTRAP_CDN_ARR,
            CSS_JQUERY_MAGNIFIC_POPUP_ARR,
            // CSS_JQUERY_CONTEXT_MENU_ARR
            CSS_REACT_CONTEXTMENU_ARR
            // CSS_JQUERY_AUTO_HIDING_NAVIGATION
            // URL_BASE . CSS_JQUERY_AUTO_HIDING_NAVIGATION,

			// Config::get('css_bootstrap'),
			// Config::get('css_ladda'),
			// Config::get('css_typeahead'),
			// Config::get('css_jquery_magnific_popup'),
			// Config::get('css_jquery_swiper'),
			// Config::get('css_jquery_pnotify'),
			// Config::get('css_jquery_contextMenu')
		);

		// ---------------------------------------------
		//    スマホのときに読み込む
		// ---------------------------------------------

		if (DEVICE_TYPE === 'smartphone') {
			// array_push($cssArr, Config::get('css_lastsidebar'));

		// ---------------------------------------------
		//    タブレットのときに読み込む
		// ---------------------------------------------

        } else if (DEVICE_TYPE === 'tablet') {
			// array_push($cssArr, Config::get('css_lastsidebar'), Config::get('css_aos'));

		// ---------------------------------------------
		//    PCのときに読み込む
		// ---------------------------------------------

        } else {
			// array_push($cssArr, Config::get('css_aos'));
		}



		// --------------------------------------------------
		//    Javascript
		// --------------------------------------------------

		$jsArr = array(
            JS_JQUERY_CDN_ARR,
            JS_JQUERY_AUTO_HIDING_NAVIGATION_ARR,
            JS_JQUERY_MAGNIFIC_POPUP_ARR,
            // JS_JQUERY_CONTEXT_MENU_UI_POSITION_ARR,
            // JS_JQUERY_CONTEXT_MENU_ARR
			// Config::get('js_jquery'),
			// Config::get('js_jquery_cookie'),
			// // Config::get('js_bootstrap'),
			// Config::get('js_jquery_autosize'),
			// Config::get('js_jquery_fastclick'),
			// Config::get('js_jquery_magnific_popup'),
			// Config::get('js_jquery_imagesloaded'),
			// Config::get('js_ladda_spin'),
			// Config::get('js_ladda'),
			// Config::get('js_typeahead'),
			// Config::get('js_jquery_swiper'),
			// Config::get('js_jquery_auto_hiding_navigation'),
			// Config::get('js_jquery_jrumble'),
			// Config::get('js_jquery_sticky-kit'),
			// Config::get('js_jquery_pnotify'),
			// Config::get('js_masonry'),
			// Config::get('js_jquery_contextMenu'),
			// Config::get('js_jquery_ui_position')
		);

		// ---------------------------------------------
		//    スマホのときに読み込む
		// ---------------------------------------------

		if (DEVICE_TYPE === 'smartphone') {
			// array_push($jsArr, Config::get('js_lastsidebar'), Config::get('js_jquery_trunk8'));

		// ---------------------------------------------
		//    タブレットのときに読み込む
		// ---------------------------------------------

        } else if (DEVICE_TYPE === 'tablet') {
			// array_push($jsArr, Config::get('js_lastsidebar'), Config::get('js_jquery_trunk8'), Config::get('js_aos'));

		// ---------------------------------------------
		//    PCのときに読み込む
		// ---------------------------------------------

        } else {
			// array_push($jsArr, Config::get('js_aos'));
		}



		// ---------------------------------------------
		//    本番環境では軽量バージョンを読み込む
		// ---------------------------------------------

		if (Fuel::$env == 'development') {
			// array_push($cssArr, URL_BASE . 'react/css/style.css');
            // array_push($jsArr, JS_JQUERY_CDN_ARR);
            // array_push($jsArr, JS_JQUERY_AUTO_HIDING_NAVIGATION);
			array_push($jsArr, ['src' => URL_BASE . 'react/js/root-bundle.min.js']);
		} else {
			// array_push($cssArr, Config::get('css_basic_min'), 'style.min.css', 'new.min.css');
			// array_push($jsArr, Config::get('js_basic_min'), 'sc.min.js');
		}




		// ---------------------------------------------
		//    コード出力
		// ---------------------------------------------

		$view = View::forge(APPPATH . 'classes/react/views/html');
		$view->set('language', LANGUAGE);
        $view->set('title', $title);
        $view->set('metaKeywords', $metaKeywords);
        $view->set('metaDescription', $metaDescription);
        $view->set('metaOgType', $metaOgType);
        $view->set('cssArr', $cssArr);
        $view->set('jsArr', $jsArr);
		$view->set_safe('initialStateArr', $this->initialStateArr);

		return Response::forge($view);

	}

}
