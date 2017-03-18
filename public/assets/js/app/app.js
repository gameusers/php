//var appEnvironment = 'phonegap_desktop';
//var appEnvironment = 'app_test';
var appEnvironment = 'production';

if (appEnvironment == 'phonegap_desktop') {

	var uri_base = "http://localhost/gameusers/public/";
	var uri_current = "http://localhost/gameusers/public/";
	var testMode = true;

} else if (appEnvironment == 'app_test') {

	var uri_base = "http://192.168.10.2/gameusers/public/";
	var uri_current = "http://192.168.10.2/gameusers/public/";

} else if (appEnvironment == 'production') {

	var uri_base = "https://gameusers.org/";
	var uri_current = "https://gameusers.org/";

}


var language = "ja";
var appMode = true;


var loadedIndex = false;
var loadedHeader = false;
var loadedFooter = false;

var appUserTermsApproval = false;


var pushNotification;

// メンテナンスする場合はtrueにすること
var maintenance = false;



/**
* 初期処理
*/
function initApp() {


	// --------------------------------------------------
	//   コンテンツ読み込み
	// --------------------------------------------------

	if ( ! maintenance) {
		appReadHeader();
		appReadFooter();
		appReadContentsIndex();
	}



	// --------------------------------------------------
	//   イベント設定
	// --------------------------------------------------

	// ページ移動
	$(document).on('click', "#external_link", function(e) {

		e.preventDefault();

		var target = '_system';
		var address = $(this).attr("href");

		window.open(address, target);

	});


	// --------------------------------------------------
	//   アプリがバックグラウンドから復帰したときに、アプリを更新しなおす
	// --------------------------------------------------

	document.addEventListener("resume", function() {

		var dateNow = +new Date();
		var plusMilliSecond;

		// 更新しなおす時間をこのミリ秒数で調整
		if (maintenance) {
			plusMilliSecond = 1000;
		} else {
			plusMilliSecond = 1000 * 60 * 60 * 24;
			//var plusMilliSecond = 1000;
		}


		if (dateFirstAccess + plusMilliSecond < dateNow) {
			window.location.reload();
		} else {
			//alert('バックグラウンド復帰');
			// お知らせ未読件数表示
			readNotificationsUnreadTotal();

		}

		// テスト用　毎回バックグラウンドから復帰したときに、アプリを更新しなおす
		// if (adBlock) {
			// window.location.reload();
		// }

	});


	// --------------------------------------------------
	//   メンテナンス中であることを通知
	// --------------------------------------------------

	if (maintenance) {
		alert('Game Usersは現在、メンテナンス中です');
		return;
	}


	// --------------------------------------------------
	//   Google Analytics
	// --------------------------------------------------

	//window.analytics.startTrackerWithId('UA-65833410-1');


}




/**
* デバイストークンを取得する
*/
function getDeviceToken() {

	//alert('getDeviceToken');

	pushNotification = window.plugins.pushNotification;

	//alert('1');

	if ( device.platform == 'android' || device.platform == 'Android' ){
		//alert('2');
		pushNotification.register(
		successHandler,
		errorHandler,
		{
			"senderID":"735599708800",
			"ecb":"onNotificationGCM"
		});
		//alert('3');
	} else {

		pushNotification.register(
		tokenHandler,
		errorHandler,
		{
			"badge":"true",
			"sound":"true",
			"alert":"true",
			"ecb":"onNotificationAPN"
		});

	}

}



// result contains any message sent from the plugin call
//function successHandler (result) {
function successHandler() {
	//alert('success = ' + result);
}

// result contains any error description text returned from the plugin call
//function errorHandler (error) {
function errorHandler() {
	//alert('error = ' + error);
}


// Method to handle device registration for Android.
//window.onNotificationGCM = function(e) {
//var onNotificationGCM = function(e) {
function onNotificationGCM (e) {
	//alert('aaa');
	switch( e.event ) {
		case 'registered':
		if ( e.regid.length > 0 ) {
			//console.log("Regid " + e.regid);
			//alert('registration id = '+e.regid);
			saveDeviceInfo(e.regid, null);
		}
		break;

		case 'message':
		// this is the actual push notification. its format depends on the data model from the push server
		//alert('message = '+e.message+' msgcnt = '+e.msgcnt);

		// お知らせ未読件数表示
		readNotificationsUnreadTotal();

		break;

		case 'error':
		//alert('GCM error = '+e.msg);
		break;

		default:
		//alert('An unknown GCM event has occurred');
		break;
	}

}


// iOS
function onNotificationAPN (event) {

	//alert('onNotificationAPN');


	// iOSはアプリが動作中に通知を受けると処理ができない？本番環境では動作する？
	// 動作中の場合、navigator.notification.alert(event.alert);ここで止まるので、未読件数表示を上に持ってくる
	// eventの下に置くのはやめること

	// お知らせ未読件数表示
	readNotificationsUnreadTotal();


	if ( event.alert ) {
		navigator.notification.alert(event.alert);
	}

	if ( event.sound ) {
		var snd = new Media(event.sound);
		snd.play();
	}

	if ( event.badge ) {
		pushNotification.setApplicationIconBadgeNumber(successHandler, errorHandler, event.badge);
	}

}

function tokenHandler (result) {
	// Your iOS push server needs to know the token before it can push to this device
	// here is where you might want to send it the token for later use.
	//alert('device token = ' + result);

	saveDeviceInfo(result, null);
}




/**
* デバイス情報保存
*/
function saveDeviceInfo(token, newDevice) {

	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	if (newDevice) {

		// --------------------------------------------------
		//   確認ダイアログ表示
		// --------------------------------------------------

		if ( ! window.confirm('このデバイスで通知を受けますか？')) {
			return;
		}
		//alert('return');
		fileData.append("on_off_app", 'on');
		fileData.append("receive_device", device.uuid);

	} else {

		fileData.append("type", device.platform);
		fileData.append("id", device.uuid);
		fileData.append("name", device.model + ' / ' + device.platform + ' ' + device.version);
		fileData.append("token", token);

	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + 'rest/user/save_notification_data.json',
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		//alert('success');

		if (response.new_device) {
			//alert('new_device');
			saveDeviceInfo(null, true);
		}

		// デバイスを新規登録した場合はプレイヤーページを更新
		// if (response.saved) {
		//
		// 	// ロードタイプ取得
		// 	var $items = $flipsnap.find(".flipsnap_item");
		// 	var loadingType = $items.eq(flipsnap.currentPoint).data('loading_type');
		//
		// 	if (loadingType == 'contentsPlayer') {
		// 		appChangePage({'reload':'thisPage'});
		// 	}
		//
		// }

	}).fail(function() {

		alert('error');

	}).always(function() {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------
		//alert('aaa');
		//alert(response);
		// if (response.alert_title) {
			// alert(response.alert_message);
		// }


	});

}






/**
* 読み込み　ヘッダー
*/
function appReadHeader() {

	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	$.ajax({

		url: uri_base + 'rest/app/header.json',
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		$("#app_header").html(response.code);

		// ロード完了
		loadingComplete(true, false, false);

	}).fail(function() {

		alert('error');

	}).always(function() {

	});

}


/**
* 読み込み　フッター
*/
function appReadFooter() {

	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	$.ajax({

		url: uri_base + 'rest/app/footer.json',
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		$("#app_footer").html(response.code);

		var fuel_csrf_token = $("#form_fuel_csrf_token").val();

		$.cookie("fuel_csrf_token", fuel_csrf_token);

		// イベント追加　トップへ戻る
		$("#app_go_to_top").on("click", function() {
			scrollToAnchor("#app_header", 0, 0);
		});

		// ロード完了
		loadingComplete(false, true, false);



		// --------------------------------------------------
		//   デバイストークン取得
		// --------------------------------------------------

		getDeviceToken();


	}).fail(function() {

	}).always(function() {

	});

}




/**
* 読み込み　index
*/
function appReadContentsIndex() {

	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();
	fileData.append("app_mode", 1);


	$.ajax({

		url: uri_base + 'rest/app/contents_index.json',
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		//　コード反映
		$('#app_container').html(response.code);

		// 初期化
		initLogin(response.content_id);

		// お知らせ未読件数表示
		readNotificationsUnreadTotal();

		// ロード完了
		loadingComplete(false, false, true);


		// --------------------------------------------------
		//   Google Analytics
		// --------------------------------------------------

		//window.analytics.trackView('index');


	}).fail(function() {

	}).always(function() {

	});

}



/**
* 読み込み　login
*/
function appReadContentsLogin() {

	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();
	fileData.append("app_mode", 1);


	$.ajax({

		url: uri_base + 'rest/app/contents_login.json',
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		//　コード反映
		$('#app_container').html(response.code);

		// 初期化
		initLogin(response.content_id);

		// お知らせ未読件数表示
		readNotificationsUnreadTotal();


		// --------------------------------------------------
		//   Google Analytics
		// --------------------------------------------------

		//window.analytics.trackView('login');


	}).fail(function() {

	}).always(function() {

	});

}



/**
* 読み込み　logout
*/
function appReadContentsLogout() {

	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();
	fileData.append("app_mode", 1);


	$.ajax({

		url: uri_base + 'rest/app/contents_logout.json',
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		//　コード反映
		$('#app_container').html(response.code);

		// 初期化
		initLogout(response.content_id);

		// お知らせ未読件数表示
		readNotificationsUnreadTotal();


		// --------------------------------------------------
		//   Google Analytics
		// --------------------------------------------------

		//window.analytics.trackView('logout');


	}).fail(function() {

	}).always(function() {

	});

}





/**
* 最初のページ読み込み完了　スプラッシュスクリーンを非表示にするため
*/
function loadingComplete(header, footer, index) {

	if (header) {
		loadedHeader = true;
		//alert('header');
	}

	if (footer) {
		loadedFooter = true;
		//alert('footer');
	}

	if (index) {
		loadedIndex = true;
		//alert('index');
	}

	if (loadedHeader && loadedFooter && loadedIndex) {
		navigator.splashscreen.hide();
		//alert('loadingComplete');
	}

}
