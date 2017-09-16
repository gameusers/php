<?php

// --------------------------------------------------
//   Composer のオートローダー読み込み
// --------------------------------------------------

require_once APPPATH . 'classes/react/vendor/autoload.php';


// --------------------------------------------------
//   アクセスしたデバイスの種類（PC・スマホ・タブレット）
//   OS の種類を判定
//   パッケージ Mobile Detect (MIT License) を利用
//   http://mobiledetect.net/
// --------------------------------------------------

$movileDetect = new Mobile_Detect;

if ($movileDetect->isMobile()) {
    define('DEVICE_TYPE', 'smartphone');
} else if ($movileDetect->isTablet()) {
    define('DEVICE_TYPE', 'tablet');
} else {
    define('DEVICE_TYPE', 'other');
}

if ($movileDetect->isiOS()) {
    define('DEVICE_OS', 'iOS');
} else if ($movileDetect->isAndroidOS()) {
    define('DEVICE_OS', 'Android');
} else {
    define('DEVICE_OS', 'other');
}


// --------------------------------------------------
//   ホスト ＆ ユーザーエージェント
// --------------------------------------------------

define('HOST', gethostbyaddr($_SERVER['REMOTE_ADDR']));
define('USER_AGENT', $_SERVER['HTTP_USER_AGENT']);


// --------------------------------------------------
//   ユーザーNo
// --------------------------------------------------

if (Auth::check()) {
    define('USER_NO', Auth::get_user_id());
} else {
    define('USER_NO', null);
}


// --------------------------------------------------
//   プレイヤーID
// --------------------------------------------------

if (USER_NO) {
    $modelsUser = new \React\Models\User();
    define('PLAYER_ID', $modelsUser->selectPlayerId(USER_NO));
} else {
    define('PLAYER_ID', null);
}


// --------------------------------------------------
//   言語
// --------------------------------------------------

if (Cookie::get('language') === null and isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $languagesArr = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    define('LANGUAGE', $languagesArr[0]);
} else {
    define('LANGUAGE', 'ja');
}


// --------------------------------------------------
//   URL
// --------------------------------------------------

define('URL_BASE', Uri::base(false));
define('URL_CURRENT', Uri::current());


// --------------------------------------------------
//   広告の非表示　管理者の場合、広告を表示しない
//   誤クリック防止
// --------------------------------------------------

if (USER_NO === 1 || Auth::member(100) || \Fuel::$env === 'development') {
    define('AD_BLOCK', true);
} else {
    define('AD_BLOCK', false);
}


// --------------------------------------------------
//   ページネーションの列数
// --------------------------------------------------

if (DEVICE_TYPE === 'smartphone') {
    define('PAGINATION_COLUMN', 5);
} else if (DEVICE_TYPE === 'tablet') {
    define('PAGINATION_COLUMN', 10);
} else {
    define('PAGINATION_COLUMN', 15);
}








// --------------------------------------------------
//   設定値を定数で記述する
// --------------------------------------------------

// --------------------------------------------------
//   通知
// --------------------------------------------------

// 通知の保存期間　$datetime->modify($string)の形式で指定 / default = '-3 months'
// define('LIMIT_NOTIFICATION_PRESERVATION_TERM', '-3 months');
define('LIMIT_NOTIFICATION_PRESERVATION_TERM', '-5 years');

// 通知の表示件数
define('LIMIT_NOTIFICATION_ARR', [
    'smartphone' => 5,
    'tablet' => 5,
    'other' => 1
]);


// --------------------------------------------------
//   フッター
// --------------------------------------------------

// 画面に表示するサムネイルカードの数
define('LIMIT_FOOTER_THUMBNAIL_CARDS_ARR', [
    'smartphone' => 30,
    'tablet' => 30,
    'other' => 30
]);



// --------------------------------------------------
//   初期ステート
// --------------------------------------------------

$this->initialStateArr['deviceType'] = DEVICE_TYPE;
$this->initialStateArr['deviceOs'] = DEVICE_OS;
$this->initialStateArr['host'] = HOST;
$this->initialStateArr['userAgent'] = USER_AGENT;
$this->initialStateArr['userNo'] = USER_NO;
$this->initialStateArr['playerId'] = PLAYER_ID;
$this->initialStateArr['language'] = LANGUAGE;
$this->initialStateArr['urlBase'] = URL_BASE;
$this->initialStateArr['adBlock'] = AD_BLOCK;
$this->initialStateArr['paginationColumn'] = PAGINATION_COLUMN;

$this->initialStateArr['notificationObj']['limitNotification'] = LIMIT_NOTIFICATION_ARR[DEVICE_TYPE];



// --------------------------------------------------
//   ライブラリーのパス
//   CDN の場合は CDN の URL
//   ローカルファイルの場合はファイルへの絶対パス
// --------------------------------------------------


// ---------------------------------------------
//   スタイルシート
// ---------------------------------------------

define('CSS_RESET_CDN_ARR', [
    'href' => 'https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'
]);

define('CSS_BOOTSTRAP_CDN_ARR', [
    'href' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'
]);

define('CSS_LADDA_BOOTSTRAP_CDN_ARR', [
    'href' => 'https://cdnjs.cloudflare.com/ajax/libs/ladda-bootstrap/0.9.4/ladda-themeless.min.css'
]);

define('CSS_JQUERY_MAGNIFIC_POPUP_CDN_ARR', [
    'href' => 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css'
]);

// define('CSS_JQUERY_LAST_SIDEBAR_ARR', [
//     'href' => URL_BASE . 'react/lib/last-sidebar/last-sidebar.css'
// ]);

// define('CSS_REACT_CONTEXTMENU_ARR', [
//     'href' => URL_BASE . 'react/lib/react-contextmenu/react-contextmenu.css'
// ]);



// ---------------------------------------------
//   Javascript
//   CDN に integrity（改ざん検知用）の値が用意されている場合は入力すること
//   その他、表示したい値がある場合は、配列形式（key => value）で入力すると <script> タグ内に追加で表示されます
// ---------------------------------------------

define('JS_JQUERY_CDN_ARR', [
    'src' => 'https://code.jquery.com/jquery-3.2.1.min.js',
    'integrity' => 'sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=',
    'crossorigin' => 'anonymous'
]);

define('JS_JQUERY_AUTO_HIDING_NAVIGATION_ARR', [
    'src' => URL_BASE . 'react/lib/auto-hiding-navigation/auto-hiding-navigation.js'
]);

define('JS_JQUERY_MAGNIFIC_POPUP_CDN_ARR', [
    'src' => 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js'
]);

// define('JS_JQUERY_STICKY_CDN_ARR', [
//     'src' => 'https://cdn.jsdelivr.net/jquery.sticky/1.0.1/jquery.sticky.min.js'
// ]);

// define('JS_JQUERY_LAST_SIDEBAR_MOBILE_EVENTS_ARR', [
//     'src' => URL_BASE . 'react/lib/last-sidebar/jquery.mobile.events.js'
// ]);

// define('JS_JQUERY_LAST_SIDEBAR_ARR', [
//     'src' => URL_BASE . 'react/lib/last-sidebar/jquery.last-sidebar.min.js'
// ]);

define('JS_LADDA_BOOTSTRAP_SPIN_CDN_ARR', [
    'src' => 'https://cdnjs.cloudflare.com/ajax/libs/ladda-bootstrap/0.9.4/spin.min.js'
]);

define('JS_LADDA_BOOTSTRAP_CDN_ARR', [
    'src' => 'https://cdnjs.cloudflare.com/ajax/libs/ladda-bootstrap/0.9.4/ladda.min.js'
]);
