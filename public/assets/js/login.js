$(function() {

	// アプリでない場合、DOM読み込み後、初期化
	// アプリの場合、app.jsですべて読み込んだ後で初期化
	if(typeof appMode == "undefined") {
		initLogin(content_id);
	}

});


/**
* 初期処理
*/
function initLogin(contentId) {

	// --------------------------------------------------
	//   多言語化
	// --------------------------------------------------

	if(typeof appMode == "undefined") {
		/*
		Globalize.culture(language);

	    Globalize.addCultureInfo( "ja", {
	        messages: {
	        	"error": "エラー",
	            "passwordsDoNotMatch": "パスワードが再入力したパスワードと違っています。"
	        }
	    });

	    Globalize.addCultureInfo( "en", {
	        messages: {
	        	"error": "Error",
	            "passwordsDoNotMatch": "Passwords do not match."
	        }
	    });
	    */
	}

	// --------------------------------------------------
	//   多言語化
	// --------------------------------------------------

	i18n.init({ lng: language, resGetPath: uri_base + 'locales/' + language + '/__ns__.json' });
	//alert(language);
	//i18n.init({ lng: language });
	//i18n.init({ lng: language, resGetPath: uri_base + 'locales/__lng__/__ns__.json' });

	// i18n.init(function(t) {
		// // translate nav
		// //$(".nav").i18n();
//
		// // programatical access
		// var appName = t("common.error");
		// alert(appName);
	// });


	// --------------------------------------------------
	//   初期処理　表示する＆隠す
	// --------------------------------------------------

	$("#" + contentId + " #change_category_login1_button").attr("class", "btn btn-default active");
	$("#" + contentId + " #login1").show();
	$("#" + contentId + " #login2").hide();
	$("#" + contentId + " #registration").hide();



	// --------------------------------------------------
	//   表示カテゴリー変更
	// --------------------------------------------------

	// login1
	$("#" + contentId + " #change_category_login1_button").on("click", function() {
		$("#" + contentId + " #change_category_login1_button").attr("class", "btn btn-default active");
		$("#" + contentId + " #change_category_login2_button").attr("class", "btn btn-default");
		$("#" + contentId + " #change_category_registration_button").attr("class", "btn btn-default");
		$("#" + contentId + " #login1").show();
		$("#" + contentId + " #login2").hide();
		$("#" + contentId + " #registration").hide();
	});

	// login2
	$("#" + contentId + " #change_category_login2_button").on("click", function() {
		$("#" + contentId + " #change_category_login1_button").attr("class", "btn btn-default");
		$("#" + contentId + " #change_category_login2_button").attr("class", "btn btn-default active");
		$("#" + contentId + " #change_category_registration_button").attr("class", "btn btn-default");
		$("#" + contentId + " #login1").hide();
		$("#" + contentId + " #login2").show();
		$("#" + contentId + " #registration").hide();
	});

	// registration
	$("#" + contentId + " #change_category_registration_button").on("click", function() {
		$("#" + contentId + " #change_category_login1_button").attr("class", "btn btn-default");
		$("#" + contentId + " #change_category_login2_button").attr("class", "btn btn-default");
		$("#" + contentId + " #change_category_registration_button").attr("class", "btn btn-default active");
		$("#" + contentId + " #login1").hide();
		$("#" + contentId + " #login2").hide();
		$("#" + contentId + " #registration").show();
	});





	// --------------------------------------------------
	//   ソーシャルログイン
	// --------------------------------------------------

	$("#" + contentId + " #login_social_button").on("click", function(e) {

		// 処理を停止
		e.preventDefault();


		// --------------------------------------------------
		//   利用規約に同意しない場合は処理停止
		// --------------------------------------------------

		if ($('#' + contentId + ' #login1 #user_terms')[0]) {
			var check = $('#' + contentId + ' #login1 #user_terms').prop('checked');
			if ( ! check) {
				alert('利用規約に同意する必要があります');
				return;
			}
		}


		// --------------------------------------------------
		//   利用規約同意のクッキー設定
		// --------------------------------------------------

		//$.cookie("user_terms_version", user_terms_version, { expires: 365 });


		// --------------------------------------------------
		//   ブラウザで開く
		// --------------------------------------------------

		if (typeof appMode == "undefined") {

			var address = $(this).attr("href");
			location.href = address;


		// --------------------------------------------------
		//   App InAppBrowserで開く
		// --------------------------------------------------

		} else if (typeof appMode != "undefined") {

			var address = $(this).attr("href") + '?app=1';
			var ref = window.open(address, '_blank', 'location=yes,closebuttoncaption=閉じる,clearcache=no,clearsessioncache=no');
			ref.addEventListener('loadstop', function(e) {

				if(e.url.match(/^http.+login\/redirect\/.+$/)) {
					ref.close();
					var arr = e.url.split('/');
					var length = arr.length - 1;
					//alert(arr + '/' + length);
					//alert(arr[length - 1] + '/' + arr[length]);

					// 現在のページ以外を削除する
					//flipsnapDeleteItemsExceptCurrentItem();

					// デバイストークン取得
					getDeviceToken();

					// コンテンツ再読み込み
					appReadHeader();
					appReadContentsIndex();

					// if (arr[length - 1] == 'uc') {
						// appChangePage({'reload':'', 'header':'', 'contentsUc':{'communityId':arr[length]}});
					// } else {
						// appChangePage({'reload':'', 'header':'', 'contentsPlayer':{'userId':arr[length]}});
					// }

				}

			});

		}

	});




	// --------------------------------------------------
	//   はてなでログイン
	// --------------------------------------------------

	$("#" + contentId + " #hatena_button").on("click", function() {


		// --------------------------------------------------
		//   はてなIDが入力されていない場合は処理停止
		// --------------------------------------------------

		var hatena_id = $("#" + contentId + " #auth_hatena").val();

		if ( ! hatena_id) {
			return;
		}


		// --------------------------------------------------
		//   利用規約に同意しない場合は処理停止
		// --------------------------------------------------

		if ($('#' + contentId + ' #login1 #user_terms')[0]) {
			var check = $('#' + contentId + ' #login1 #user_terms').prop('checked');
			if ( ! check) {
				alert('利用規約に同意する必要があります');
				return;
			}
		}


		// --------------------------------------------------
		//   利用規約同意のクッキー設定
		// --------------------------------------------------

		//$.cookie("user_terms_version", user_terms_version, { expires: 365 });


		var hatena_url = testChangeUrl(uri_base) + 'login/auth/openid/hatena/' + hatena_id;


		// --------------------------------------------------
		//   ブラウザで開く
		// --------------------------------------------------

		if (typeof appMode == "undefined") {

			location.href = hatena_url;


		// --------------------------------------------------
		//   App InAppBrowserで開く
		// --------------------------------------------------

		} else if (typeof appMode != "undefined") {

			var address = hatena_url + '?app=1';
			//alert(address);
			var ref = window.open(address, '_blank', 'location=yes,closebuttoncaption=閉じる,clearcache=no,clearsessioncache=no');
			ref.addEventListener('loadstop', function(e) {

				if(e.url.match(/^http.+login\/redirect$/)) {
					ref.close();

					var arr = e.url.split('/');
					var length = arr.length - 1;
					//alert(arr + '/' + length);
					//alert(arr[length - 1] + '/' + arr[length]);

					// 現在のページ以外を削除する
					//flipsnapDeleteItemsExceptCurrentItem();

					// デバイストークン取得
					getDeviceToken();

					// コンテンツ再読み込み
					appReadHeader();
					appReadContentsIndex();

					// if (arr[length - 1] == 'uc') {
						// appChangePage({'reload':'', 'header':'', 'contentsUc':{'communityId':arr[length]}});
					// } else {
						// appChangePage({'reload':'', 'header':'', 'contentsPlayer':{'userId':arr[length]}});
					// }

				}
			});

		} else {
			location.href = hatena_url;
		}

	});




	// --------------------------------------------------
	//   IDとパスワードでログイン
	// --------------------------------------------------

	$("#" + contentId + " #form_login2").submit(function(e) {


		// --------------------------------------------------
		//   フォーム送信キャンセル
		// --------------------------------------------------

		e.preventDefault();


		// --------------------------------------------------
		//   利用規約に同意しない場合は処理停止
		// --------------------------------------------------

		if ($('#' + contentId + ' #login2 #user_terms')[0]) {
			var check = $('#' + contentId + ' #login2 #user_terms').prop('checked');
			if ( ! check) {
				alert('利用規約に同意する必要があります');
				return;
			}
		}


		// --------------------------------------------------
		//   ローディング開始
		// --------------------------------------------------

		loadingToggle(null, "#" + contentId + " #submit_login2");


		// --------------------------------------------------
		//   フォーム送信データ作成
		// --------------------------------------------------

		var fileData = new FormData();

		fileData.append("login_username", $("#" + contentId + " #login_username").val());
		fileData.append("login_password", $("#" + contentId + " #login_password").val());


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

			url: uri_base + "login/try.php",
			dataType: "json",
			type: "POST",
			data: fileData,
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false

		}).done(function(response) {

			// console.log(response.user_no);
			// return;

			// --------------------------------------------------
			//   移動
			// --------------------------------------------------

			if (response.user_no) {

				if(typeof appMode == "undefined") {

					document.location = uri_base + "login/redirect";

				} else {

					// デバイストークン取得
					getDeviceToken();

					// コンテンツ再読み込み
					appReadHeader();
					appReadContentsIndex();

				}

			}

		}).fail(function() {

			alert('error');

		}).always(function(response) {

			// --------------------------------------------------
			//   アラート表示
			// --------------------------------------------------

			if (response.alert_title) {
				showAlert("#" + contentId + " #alert_login2", response.alert_color, response.alert_title, response.alert_message);
			}


			// --------------------------------------------------
			//   ローディング停止
			// --------------------------------------------------

			loadingToggle(null, "#" + contentId + " #submit_login2");

		});

	});



	// --------------------------------------------------
	//   アカウント作成
	// --------------------------------------------------

	$("#" + contentId + " #submit_registration").on("click", function() {


		// --------------------------------------------------
		//   利用規約に同意しない場合は処理停止
		// --------------------------------------------------

		if ($('#' + contentId + ' #registration #user_terms')[0]) {
			var check = $('#' + contentId + ' #registration #user_terms').prop('checked');
			if ( ! check) {
				alert('利用規約に同意する必要があります');
				return;
			}
		}


		// --------------------------------------------------
		//   利用規約同意のクッキー設定
		// --------------------------------------------------

		//$.cookie("user_terms_version", user_terms_version, { expires: 365 });


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		var registration_username = $("#" + contentId + " #registration_username").val();
		var registration_password = $("#" + contentId + " #registration_password").val();
		var registration_password_verification = $("#" + contentId + " #registration_password_verification").val();


		// --------------------------------------------------
		//   パスワードと再入力の比較
		// --------------------------------------------------

		if (registration_password != registration_password_verification) {
			//showAlert("#" + contentId + " #alert_registration", 'warning', Globalize.localize('error', Globalize.culture()), Globalize.localize('passwordsDoNotMatch', Globalize.culture()));
			showAlert("#" + contentId + " #alert_registration", 'warning', i18n.t("common.error"), i18n.t("login.passwordsDoNotMatch"));
			//alert(i18n.t("common.error"));
			// i18n.init(function(t) {
		// // programatical access
		// var appName = t("common.error");
		// alert(appName);
	//});
			return;
		}


		// --------------------------------------------------
		//   ローディング開始
		// --------------------------------------------------

		var arguThis = this;
		loadingToggle(arguThis, null);
		//loadingToggle(null, "#" + contentId + " #submit_registration");


		// --------------------------------------------------
		//   フォーム送信データ作成
		// --------------------------------------------------

		var fileData = new FormData();

		fileData.append("registration_username", registration_username);
		fileData.append("registration_password", registration_password);


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

			url: uri_base + "login/registration.php",
			dataType: "json",
			type: "POST",
			data: fileData,
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false

		}).done(function(response) {


			// --------------------------------------------------
			//   移動
			// --------------------------------------------------

			if (response.user_no) {
				if(typeof appMode == "undefined") {
					document.location = uri_base + "login/redirect";
				} else {

					// デバイストークン取得
					getDeviceToken();

					appChangePage({"reload":'', "header":"", "contentsPlayer":{"userId":response.user_id}});
				}
			}

		}).fail(function() {

			alert('error');

		}).always(function(response) {

			// --------------------------------------------------
			//   アラート表示
			// --------------------------------------------------

			if (response.alert_title) {
				showAlert("#alert_registration", response.alert_color, response.alert_title, response.alert_message);
			}


			// --------------------------------------------------
			//   ローディング停止
			// --------------------------------------------------

			loadingToggle(arguThis, null);
			//loadingToggle(null, "#" + contentId + " #submit_registration");

		});

	});

}



/**
* はてな送信チェック　IDが入力されていない場合は送信しない
* @return {boolean}
*/
/*
function checkHatena() {

	var id = $("#auth_hatena").val();

	if (id) {
		$("#hatena_openid_url").val('http://www.hatena.ne.jp/' + id + "/");
		return true;
	} else {
		return false;
	}

}
*/
