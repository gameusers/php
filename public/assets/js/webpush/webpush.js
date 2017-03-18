
// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.webpush');


// --------------------------------------------------
//   プロパティ
// --------------------------------------------------

GAMEUSERS.webpush.endpoint = null;
GAMEUSERS.webpush.publicKey = null;
GAMEUSERS.webpush.authToken = null;



$(function() {

    if (webpush_type == 'player') {
        GAMEUSERS.webpush.startWebPush(webpush_type);
    }

//subscribe();

//unsubscribe();



});





/**
* ウェブプッシュスタート
*/
GAMEUSERS.webpush.startWebPush = function(type) {

    // --------------------------------------------------
    //   Service Wokerをサポートしているかチェック
    // --------------------------------------------------

    if ( !('serviceWorker' in navigator) ) {
        //console.log('Error: Service Worker未サポート');
    }


    // --------------------------------------------------
    //   プッシュ通知に対応しているかの判定
    // --------------------------------------------------

    if ( !('showNotification' in ServiceWorkerRegistration.prototype) ) {
        //console.log('Notifications aren\'t supported.');
        return;
    }


    // --------------------------------------------------
    //   プッシュ通知が拒否設定になっていないかを確認
    // --------------------------------------------------

    if ( Notification.permission === 'denied') {
        //console.log('The user has blocked notifications.');
        return;
    }


    // --------------------------------------------------
    //   プッシュ通知に対応しているかの判定2
    // --------------------------------------------------

    if ( !('PushManager' in window) ) {
        //console.log('Push messaging isn\'t supported.');
        return;
    }


    // --------------------------------------------------
    //   Service Wokerを登録（常駐）させる
    // --------------------------------------------------

    navigator.serviceWorker.register(uri_base + 'service_worker.min.js')
    .then(function(registration) {

        // --------------------------------------------------
        //   subscription（同意・署名）を得る
        // --------------------------------------------------

        return registration.pushManager.getSubscription()
        .then(function(subscription) {

            // If a subscription was found, return it.
            if (subscription) {
                return subscription;
            }

            // 別の方法で subscription を取得して返す
            // Otherwise, subscribe the user (userVisibleOnly allows to specify that we don't plan to
            // send notifications that don't have a visible effect for the user).
            return registration.pushManager.subscribe({ userVisibleOnly: true });

      });

    }).then(function(subscription) {

        // Retrieve the user's public key.
        var rawKey = subscription.getKey ? subscription.getKey('p256dh') : '';
        GAMEUSERS.webpush.publicKey = rawKey ? btoa(String.fromCharCode.apply(null, new Uint8Array(rawKey))) : '';

        var rawAuthSecret = subscription.getKey ? subscription.getKey('auth') : '';
        GAMEUSERS.webpush.authToken = rawAuthSecret ? btoa(String.fromCharCode.apply(null, new Uint8Array(rawAuthSecret))) : '';

        GAMEUSERS.webpush.endpoint = subscription.endpoint;

        //console.log('endpoint = ' + GAMEUSERS.webpush.endpoint);
        //console.log('publicKey = ' + GAMEUSERS.webpush.publicKey);
        //console.log('authToken = ' + GAMEUSERS.webpush.authToken);


        // プレイヤーページの場合、ブラウザ情報を送信する
        if (webpush_type == 'player') {
            GAMEUSERS.webpush.sendConfigBrowserInfo();
        }

    });

};




/**
* ブラウザ情報送信
*/
GAMEUSERS.webpush.sendConfigBrowserInfo = function() {


    // --------------------------------------------------
    //   情報チェック
    // --------------------------------------------------

    if ( ! GAMEUSERS.webpush.endpoint || ! GAMEUSERS.webpush.publicKey || ! GAMEUSERS.webpush.authToken)
    {
        return;
    }


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

    fileData.append("endpoint", GAMEUSERS.webpush.endpoint);
    fileData.append("public_key", GAMEUSERS.webpush.publicKey);
    fileData.append("auth_token", GAMEUSERS.webpush.authToken);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/user/save_notification_data.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {



	}).fail(function() {

		//alert('error');

	}).always(function(response) {

        // --------------------------------------------------
    	//   アラート表示
    	// --------------------------------------------------

        // try {
        //
        //     // 5秒間表示
        // 	PNotify.prototype.options.delay = 5000;
        //
        // 	if (response.alert_title && response.alert_message && response.alert_color)
        // 	{
        // 		new PNotify({
        // 			title: response.alert_title,
        // 			text: response.alert_message,
        // 			type: response.alert_color
        // 		});
        // 	}
        //
        // } catch(e) {}

	});

};



/**
* WebPush送信
*/
/*
GAMEUSERS.webpush.sendWebPush = function(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#webpush_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

    fileData.append("endpoint", GAMEUSERS.webpush.endpoint);
    fileData.append("public_key", GAMEUSERS.webpush.publicKey);
    fileData.append("auth_token", GAMEUSERS.webpush.authToken);

	fileData.append("title", $(setLocation + " #title").val());
	fileData.append("body", $(setLocation + " #body").val());
    fileData.append("icon", $(setLocation + " #icon").val());
    fileData.append("tag", $(setLocation + " #tag").val());
    fileData.append("url", $(setLocation + " #url").val());

	fileData.append("ttl", $(setLocation + " #ttl").val());
    fileData.append("urgency", $(setLocation + " #urgency").val());
    fileData.append("topic", $(setLocation + " #topic").val());

	//console.log(GAMEUSERS.webpush.endpoint, GAMEUSERS.webpush.publicKey, GAMEUSERS.webpush.authToken);
	// return;


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/notification/send_notification.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {



	}).fail(function() {

		alert('error');

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		try {
            if(response.alert_title) {
    			showAlert(setLocation + " #alert", response.alert_color, response.alert_title, response.alert_message);
    		}
        } catch(e) {}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};
*/
