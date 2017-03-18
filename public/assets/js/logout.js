$(function() {

	// アプリでない場合、DOM読み込み後、初期化
	// アプリの場合、app.jsですべて読み込んだ後で初期化
	if(typeof appMode == "undefined") {
		initLogout(content_id);
	}

});


/**
* 初期処理
*/
function initLogout(contentId) {


	// --------------------------------------------------
	//   多言語化
	// --------------------------------------------------

	i18n.init({ lng: language, resGetPath: uri_base + 'locales/' + language + '/__ns__.json' });


	// --------------------------------------------------
	//   ログアウト
	// --------------------------------------------------

	$("#" + contentId + " #submit_logout").click(function() {

		// --------------------------------------------------
		//   ローディング開始
		// --------------------------------------------------

		loadingToggle(this, null);


		// --------------------------------------------------
		//   フォーム送信データ作成
		// --------------------------------------------------

		var fileData = new FormData();


		// --------------------------------------------------
		//   App Mode
		// --------------------------------------------------

		if(typeof appMode != "undefined") {
			fileData.append("app_mode", true);
		}


		// --------------------------------------------------
		//   CSRF Token
		// --------------------------------------------------

		fileData.append("fuel_csrf_token", $("#form_fuel_csrf_token").val());


		$.ajax({

			url: uri_base + "logout/try.php",
			dataType: "json",
			type: "POST",
			data: fileData,
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false

		}).done(function() {

			if(typeof appMode != "undefined") {

				// スプラッシュスクリーン表示
				//navigator.splashscreen.show();

				// アプリ再読み込み
				//window.location.reload();

				// アプリの場合、ヘッダー読み込み直し
				//appChangePage({"header":""});


				// コンテンツ再読み込み
				appReadHeader();
				appReadContentsIndex();


			} else {

				// ヘッダーログイン・ログアウト書き換え
				$("#header_login_logout").html('<a href="' + uri_base + 'login">ログイン</a>');

			}

			// 説明文書き換え
			$("#" + contentId + " .explanation").html(i18n.t("logout.logoutComplete"));

			// ボタンを消す
			$("#" + contentId + " #submit_logout").remove();


		}).fail(function() {

			alert('error');

		}).always(function(response) {

			// --------------------------------------------------
			//   アラート表示
			// --------------------------------------------------

			if (response.alert_title) {
				showAlert("#alert_logout", response.alert_color, response.alert_title, response.alert_message);
			}


			// --------------------------------------------------
			//   ローディング停止
			// --------------------------------------------------

			loadingToggle(this, null);

		});

	});

}
