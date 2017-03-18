// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.player');


// --------------------------------------------------
//   プロパティ
// --------------------------------------------------

//var swiperBbs;



/**
* 初期処理
*/
$(function() {


	// --------------------------------------------------
	//   タブ
	// --------------------------------------------------

	$('#bsTab #bs_tab_a').on('click', function(e) {
		e.preventDefault();
		$(this).tab('show');
	});


	// --------------------------------------------------
	//   タブ ： ホバーで先読みする
	// --------------------------------------------------

	var eventType = (agent_type === '') ? 'mouseenter' : 'touchstart';

	$('#bsTab li a').on(eventType, function () {

		var href = $(this).attr("href");

		//console.log(GAMEUSERS.common.openedContent);

		// 基本設定
		if (href === '#tab_config' && ! $("#tab_config").html()) {
			GAMEUSERS.player.readConfig();

		// 通知設定
		} else if (href === '#tab_config_notification' && ! $("#config_notification").html()) {
			GAMEUSERS.player.readConfigNotification();

		// 広告設定
		} else if (href === '#tab_config_advertisement' && ! $('#tab_config_advertisement').html()) {
			GAMEUSERS.player.readConfigAdvertisement();

		// Wiki設定
		// } else if (href === '#tab_wiki' && ! $('#tab_wiki').html()) {
		// 	GAMEUSERS.player.readConfigWiki();

		// ヘルプ
		} else if (href === '#tab_help' && ! $('#tab_help').html()) {
			GAMEUSERS.common.readHelp(this, 1, 'player', 'player_about');
		}

	});


	// ----------------------------------------
	//   タブ ： コンテンツが表示されたときの処理
	// ----------------------------------------

	$('#bsTab li a').on('shown.bs.tab', function (e) {

		var href = $(this).attr("href");
		GAMEUSERS.common.openedContent = href.split('_')[1];

		// PCの場合の処理
		if (agent_type === '') {

			if (GAMEUSERS.common.openedContent === 'help') {
				GAMEUSERS.common.contentFixed.setUp();
			}

		// スマホ・タブレット
		} else {

			if (GAMEUSERS.common.openedContent === 'help') {
				GAMEUSERS.common.swiperHelp.update(true);
			}

		}

		// スクロール
		scrollToAnchor(href, -(GAMEUSERS.common.headerBsTabMarginSize), 0);

	});



	// --------------------------------------------------
	//   最初に表示するコンテンツ指定
	// --------------------------------------------------

	var read_type = $.cookie('read_type');

	if (read_type == 'prof') {

		$.removeCookie("read_type", { path: "/" });

		setTimeout(function () {
			scrollToAnchor('#profile_box', -70, 0);
		}, 500);

	}


	// --------------------------------------------------
	//   テキストエリア自動リサイズ
	// --------------------------------------------------

	// $('textarea').on('focus', function(){
	// 	autosize($(this));
	// });


	// --------------------------------------------------
	//   非公開参加コミュニティを隠す
	// --------------------------------------------------

	$('#tab_participation_community #participation_community_secret_box').hide();


});





/**
* プロフィール読み込み
*/
//function readProfile(arguThis, page, userNo) {
GAMEUSERS.player.readProfile = function(arguThis, page, userNo) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	// var contentId = getContentId(arguThis);
	// var setLocation = "#" + contentId;


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("page", page);
	fileData.append("user_no", userNo);


	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	// if(typeof appMode != "undefined") {
	// 	fileData.append("app_mode", true);
	// }


	$.ajax({

		url: uri_base + "rest/user/read_profile.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		$("#profile_box").html(response.profile);
		$("#profile_pagination").html(response.pagination);
		scrollToAnchor("#profile_box", -(GAMEUSERS.common.headerBsTabMarginSize), 0);

		// URL書き換え
		rewriteUrl();

	}).fail(function() {

		alert('error');

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* プロフィール編集フォーム表示
*/
GAMEUSERS.player.showEditProfileForm = function(arguThis, userNo, profileNo) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if (userNo) {
		setLocation = "#profile_box_user";
	} else if (profileNo) {
		setLocation = "#profile_box_" + profileNo;
	} else {
		setLocation = "#add_profile_form";
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (userNo || profileNo) {
		loadingToggle(arguThis, null);
	} else {
		loadingToggle(null, "#submit_show_edit_profile_form_add");
	}


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	if (userNo) {
		fileData.append("user_no", userNo);
	}

	if (profileNo) {
		fileData.append("profile_no", profileNo);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/user/show_edit_profile_form.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (userNo || profileNo) {
			$(setLocation + " h2").hide();
			$(setLocation + " div").hide();
			$(setLocation).append(response.code);
		} else {
			$(setLocation).html(response.code);
		}

		// テキストエリア自動リサイズ
		// $(setLocation + " textarea").on('focus', function(){
		// 	autosize($(this));
		// });


		// ゲーム選択
		if (profileNo) {
			profLocation = "#profile_box_" + profileNo;
		} else {
			profLocation = "#add_profile_form";
			profileNo = null;
		}


		// --------------------------------------------------
		//   ゲーム名　オートコンプリート読み込み
		// --------------------------------------------------

		var engine = new Bloodhound({
			limit: 10,
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: uri_base + 'rest/common/search_game_name.json?keyword=%QUERY'
		});

		engine.initialize();

		$(profLocation + " #game_name").typeahead(null, {
			name: 'gameNameTypeahead',
			displayKey: 'value',
			source: engine.ttAdapter(),
			templates: {
				suggestion: function(data){
					return '<p class="needsclick">' + data.name + '</p>';
				}
			}
		}).bind('typeahead:selected', function(event, data) {

			var gameList = $(profLocation + " #game_list").data("game-list");

			if ( ! gameList) {
				gameList = [];
			}

			if ($.inArray(parseInt(data.game_no), gameList) == -1) {
				gameList.push(data.game_no);
				$(profLocation + " #game_list").append('<div class="original_label_game bgc_lightseagreen cursor_pointer" id="game_no_' + data.game_no + '" onclick="deleteGameListNo(this, ' + profileNo + ', ' + data.game_no + ')">' + data.name + '</div>');
			}

		});

	}).fail(function() {

		alert('error');

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		if (userNo || profileNo) {
			loadingToggle(arguThis, null);
		} else {
			loadingToggle(null, "#submit_show_edit_profile_form_add");
		}

	});

};


/**
* プロフィール編集フォーム削除
*/
GAMEUSERS.player.hideEditProfileForm = function(arguThis, userNo, profileNo) {

	if (userNo) {

		$("#profile_box_user h2").show();
		$("#profile_box_user div").show();
		$("#edit_profile_form_user_" + userNo).remove();
		scrollToAnchor("#profile_box_user", -70, 0);

	} else if (profileNo) {

		$("#profile_box_" + profileNo + " h2").show();
		$("#profile_box_" + profileNo + " div").show();
		$("#edit_profile_form_profile_" + profileNo).remove();
		scrollToAnchor("#profile_box_" + profileNo, -70, 0);

	} else {
		$("#add_profile_form").html('');
	}

};



/**
* プロフィール保存
*/
GAMEUSERS.player.saveProfile = function(arguThis, userNo, profileNo) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if (userNo) {
		setLocation = "#profile_box_user";
	} else if (profileNo) {
		setLocation = "#profile_box_" + profileNo;
	} else {
		setLocation = "#add_profile_form";
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	if (userNo) {
		fileData.append("user_no", userNo);
	}

	if (profileNo) {
		fileData.append("profile_no", profileNo);
	}

	fileData.append("profile_title", $(setLocation + " #profile_title").val());
	fileData.append("handle_name", $(setLocation + " #handle_name").val());
	fileData.append("explanation", $(setLocation + " #explanation").val());
	fileData.append("status", $(setLocation + " #status").val());

	// アップロードファイルが設定されていれば追加
	if ($(setLocation + " #thumbnail").val() !== '') {
		fileData.append( "thumbnail", $(setLocation + " #thumbnail").prop("files")[0]);
	}

	if ($(setLocation + " #thumbnail_delete").prop('checked')) {
		fileData.append("thumbnail_delete", $(setLocation + " #thumbnail_delete").prop('checked'));
	}

	if ( ! $(setLocation + " #open_profile").prop('checked')) {
		fileData.append("open_profile", 1);
	}

	var gameList = $(setLocation + " #game_list").data("game-list");

	if (gameList) {
		fileData.append("game_list", gameList);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/user/save_profile.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合だけコードを反映させる -----

		if (response.code) {

			if (userNo || profileNo) {
				$(setLocation).replaceWith(response.code);
				scrollToAnchor(setLocation, -70, 0);
			} else {
				location.href = uri_current;
			}

		}

	}).fail(function() {

		alert('error');

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + " #alert_edit_profile", response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* プロフィール削除
*/
GAMEUSERS.player.deleteProfile = function(arguThis, profileNo) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

    if ( ! window.confirm('プロフィールを削除しますか？')) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#profile_box_" + profileNo;


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("profile_no", profileNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/user/delete_profile.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {

		$(setLocation).replaceWith('');

	}).fail(function() {

		alert('error');

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + " #alert_edit_profile", response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* ゲーム削除
*/
GAMEUSERS.player.deleteGameListNo = function(arguThis, profileNo, gameNo) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if (profileNo) {
		setLocation = "#profile_box_" + profileNo;
	} else {
		setLocation = "#add_profile_form";
	}


	var gameList = $(setLocation + " #game_list").data("game-list");

	$.each(gameList, function(i, val) {
		if (val == gameNo) {
			gameList.splice(i,1);
			//alert(val);
		}
	});

	$(setLocation + " #game_list #game_no_" + gameNo).remove();

};



/**
* 参加コミュニティ読み込み
*/
GAMEUSERS.player.readParticipationCommunity = function(arguThis, page, userNo) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = '#tab_participation_community';


	// --------------------------------------------------
	//   タイプ取得
	// --------------------------------------------------

	var clickedButtonType = $(arguThis).attr("id");
	var type;

	if (clickedButtonType == 'read_participation_community_open') {
		type = 'open';
		$(setLocation).data('type', type);
	} else if (clickedButtonType == 'read_participation_community_close') {
		type = 'close';
		$(setLocation).data('type', type);
	} else {
		type = $(setLocation).data('type');
	}


	// --------------------------------------------------
	//   ボタンアクティブ
	// --------------------------------------------------

	if ($(setLocation + " #read_participation_button")[0]) {

		if (type == 'open') {

			$(setLocation + " #read_participation_community_open").attr('class', 'btn btn-default ladda-button active');
			$(setLocation + " #read_participation_community_close").attr('class', 'btn btn-default ladda-button');
			$(setLocation + " #participation_community_box").show();
			$(setLocation + " #participation_community_secret_box").hide();

		} else {

			$(setLocation + " #read_participation_community_open").attr('class', 'btn btn-default ladda-button');
			$(setLocation + " #read_participation_community_close").attr('class', 'btn btn-default ladda-button active');
			$(setLocation + " #participation_community_box").hide();
			$(setLocation + " #participation_community_secret_box").show();

		}

	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("page", page);
	fileData.append("user_no", userNo);
	fileData.append("type", type);



	$.ajax({

		url: uri_base + "rest/user/read_participation_community.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合だけコードを反映させる -----

		if (response.code) {

			if (type == 'open') {
				$(setLocation + ' #participation_community_box').html(response.code);
			} else {
				$(setLocation + ' #participation_community_secret_box').html(response.code);
			}

		}

	}).fail(function() {

		alert('error');

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* ページ設定保存
*/
GAMEUSERS.player.saveConfigPage = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = "#config_page";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	// ----- サムネイルアップロード -----

	if ($(setLocation + " #top_image_1").val() !== '') {
		fileData.append( "top_image_1", $(setLocation + " #top_image_1").prop("files")[0]);
	}

	if ($(setLocation + " #top_image_2").val() !== '') {
		fileData.append( "top_image_2", $(setLocation + " #top_image_2").prop("files")[0]);
	}

	if ($(setLocation + " #top_image_3").val() !== '') {
		fileData.append( "top_image_3", $(setLocation + " #top_image_3").prop("files")[0]);
	}

	// ----- サムネイル 削除 -----

	if ($(setLocation + " #top_image_1_delete").prop('checked')) {
		fileData.append("top_image_1_delete", 1);
	}

	if ($(setLocation + " #top_image_2_delete").prop('checked')) {
		fileData.append("top_image_2_delete", 1);
	}

	if ($(setLocation + " #top_image_3_delete").prop('checked')) {
		fileData.append("top_image_3_delete", 1);
	}


	fileData.append("page_title", $(setLocation + " #page_title").val());
	fileData.append("user_id", $(setLocation + " #user_id").val());


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/user/save_config_page.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合、ページ更新 -----

		if (response.alert_color == 'success') {
			location.href = uri_base + 'pl/' + $(setLocation + " #user_id").val();
		}

	}).fail(function() {

		alert('error');

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + " #alert_config_page", response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};





/**
* ページ設定保存
*/
GAMEUSERS.player.saveConfigNotification = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#config_notification");


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	// ----------------------------------------
	//   通知のオンオフ
	// ----------------------------------------

	// On Off
	var onOff = $(setLocation).find("input[name='config_notification_on_off']:checked").val();
	if(onOff) fileData.append("on_off", onOff);

	// ON ブラウザー
	var onOffBrowser;
	if ($(setLocation).find("#config_notification_on_off_browser")[0]) {
		onOffBrowser = ($(setLocation).find("#config_notification_on_off_browser").prop("checked")) ? 'on' : 'off';
		fileData.append("on_off_browser", onOffBrowser);
	}

	// ON アプリ
	var onOffApp;
	if ($(setLocation).find("#config_notification_on_off_app")[0]) {
		onOffApp = ($(setLocation).find("#config_notification_on_off_app").prop("checked")) ? 'on' : 'off';
		fileData.append("on_off_app", onOffApp);
	}

	// ON メール
	var onOffMail;
	if ($(setLocation).find("#config_notification_on_off_mail")[0]) {
		onOffMail = ($(setLocation).find("#config_notification_on_off_mail").prop("checked")) ? 'on' : 'off';
		fileData.append("on_off_mail", onOffMail);
	}



	// ----------------------------------------
	//   ブラウザ選択
	// ----------------------------------------

	// Receive Browser
	var receiveBrowserArr = [];
	$(setLocation).find("[id=config_notification_receive_browser]").each(function(i, elem) {
		if ($(elem).is(':checked')) {
			receiveBrowserArr.push($(elem).val());
		}
	});

	if (receiveBrowserArr.length > 3) {
		showAlert($(setLocation).find("#alert"), 'warning', 'エラー', '選択できるのは3つまでです。');
		loadingToggle(arguThis, null);
		return;
	}

	if (receiveBrowserArr.length > 0) {
		fileData.append("receive_browser", receiveBrowserArr);
	}

	// Delete Browser
	var deleteBrowserArr = [];
	$(setLocation).find("[id=config_notification_browser_delete]").each(function(i, elem) {
		if ($(elem).is(':checked')) {
			deleteBrowserArr.push($(elem).val());
		}
	});

	if (deleteBrowserArr.length > 0) {
		fileData.append("delete_browser", deleteBrowserArr);
	}

	//console.log(receiveBrowserArr, deleteBrowserArr);



	// ----------------------------------------
	//   アプリ デバイス選択
	// ----------------------------------------

	// Receive Device
	var receiveDevice = $(setLocation).find("input[name='config_notification_receive_device']:checked").val();
	if (receiveDevice) fileData.append("receive_device", receiveDevice);

	// Delete Device
	var deleteDeviceArr = [];
	$(setLocation).find("[id=config_notification_app_device_delete]").each(function(i, elem) {
		if ($(elem).is(':checked')) {
			deleteDeviceArr.push($(elem).val());
		}
	});

	if (deleteDeviceArr.length > 0) {
		fileData.append("delete_device", deleteDeviceArr);
	}


	// console.log('onOff = ' + onOff);
	// console.log('onOffBrowser = ' + onOffBrowser);
	// console.log('onOffApp = ' + onOffApp);
	// console.log('onOffMail = ' + onOffMail);
	//
	// console.log('receiveDevice = ' + receiveDevice);
	// console.log('deleteDevice = ' + deleteDevice);
	// console.log('deleteDevice.length = ' + deleteDevice.length);
	//
	// return;


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/user/save_notification_data.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {

		// ブラウザを削除した場合は、テーブルのtrを消す
		if (deleteBrowserArr.length > 0) {
			$.each(deleteBrowserArr,
				function(i, key) {
					$("#tr_" + key).fadeOut('slow');
				}
			);
		}

		// デバイスを削除した場合は、テーブルのtrを消す
		if (deleteDeviceArr.length > 0) {
			$.each(deleteDeviceArr,
				function(i, key) {
					$("#tr_" + key).fadeOut('slow');
				}
			);
		}

	}).fail(function() {

		alert('error');

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------
// console.log(response.alert_title);
		if (response.alert_title) {
			showAlert($(setLocation).find("#alert"), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


	});

};





/*
function printProperties(obj) {
	var properties = "";
	for (var prop in obj) {
		properties += prop + "=" + obj[prop] + "\n";
	}
	alert(properties);
}
*/




/**
* Eメールを変更する
*/
GAMEUSERS.player.saveEmail = function(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#config_notification";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	var email = $(setLocation + " #email").val();
	var saveOrDelete;

	if (email) {
		saveOrDelete = 'save';
	} else {

		saveOrDelete = 'delete';

		// --------------------------------------------------
		//   確認ダイアログ
		// --------------------------------------------------

		if ( ! window.confirm('Eメールを削除してもよろしいですか？')) {

			// ローディング停止
			loadingToggle(arguThis, null);

			return;

		}

	}


	fileData.append("email", email);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/user/" + saveOrDelete + "_email.json",
		dataType: "json",
		type: "POST",
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

		if (response.alert_title) {
			showAlert(setLocation + " #alert_email", response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* IDを変更する
*/
GAMEUSERS.player.saveLoginUsername = function(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#config_account";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("login_username", $(setLocation + " #login_username").val());


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/user/save_login_username.json",
		dataType: "json",
		type: "POST",
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

		if (response.alert_title) {
			showAlert(setLocation + " #alert_login_username", response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};


/**
* パスワードを変更する
*/
GAMEUSERS.player.saveLoginPassword = function(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#config_account";


	// --------------------------------------------------
	//   データ取得
	// --------------------------------------------------

	var login_password = $(setLocation + " #login_password").val();
	var login_password_verification = $(setLocation + " #login_password_verification").val();


	// --------------------------------------------------
	//   パスワードと再入力の比較
	// --------------------------------------------------

	if (login_password != login_password_verification) {
		showAlert(setLocation + " #alert_login_password", 'warning', 'エラー', 'パスワードが再入力したパスワードと違っています。');
		return;
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("login_password", login_password);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/user/save_login_password.json",
		dataType: "json",
		type: "POST",
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

		if (response.alert_title) {
			showAlert(setLocation + " #alert_login_password", response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* アカウントを削除する
*/
GAMEUSERS.player.deletePlayerAccount = function(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#config_delete_player_account";


	// --------------------------------------------------
	//   データ取得
	// --------------------------------------------------

	var verification = $(setLocation + " #delete_player_account_verification").val();


	// --------------------------------------------------
	//   誤動作防止　確認キーワード
	// --------------------------------------------------

	if (verification != 'delete') {
		showAlert(setLocation + " #alert", 'warning', 'エラー', '確認キーワードが違っています。');
		return;
	}


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	if ( ! window.confirm('アカウントを削除してもよろしいですか？')) {
		return;
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/user/delete_player_account.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合、ページ移動 -----

		if (response.alert_color == 'success') {
			location.href = uri_base;
		}

	}).fail(function() {

		alert('error');

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + " #alert", response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};







/**
* 広告編集フォームを読み込む
*/
GAMEUSERS.player.readFormAdvertisement = function(arguThis, page) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest('#config_advertisement_form_box');


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('page', page);


	// console.log(page);
	// return;



	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "/rest/user/read_form_advertisement.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		// --------------------------------------------------
		//   コード反映
		// --------------------------------------------------

		if (response.code) {
			//console.log('aaa');
			$(setLocation).html(response.code);
		}


	}).fail(function() {

		alert('error');

	}).always(function(response) {


		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert($(setLocation).find('#alert'), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


	});

};




/**
* AmazonトラッキングID保存
*/
GAMEUSERS.player.saveAmazonTrackingId = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#config_advertisement_amazon");



	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	// console.log($(setLocation).find("#tracking_id").val());
	// return;


	fileData.append('tracking_id', $(setLocation).find("#tracking_id").val());


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "/rest/user/save_amazon_tracking_id.json",
		dataType: "json",
		type: "POST",
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

		if (response.alert_title) {
			showAlert($(setLocation).find('#alert'), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* 広告保存
*/
GAMEUSERS.player.saveAdvertisement = function(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#ad_box");



	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();



	// console.log($(setLocation).data('advertisement_no'));
	// return;



	var advertisementNo = $(setLocation).data('advertisement_no');
	if (advertisementNo) fileData.append('advertisement_no', advertisementNo);

	fileData.append('name', $(setLocation).find('#name').val());
	fileData.append('code', $(setLocation).find('#code').val());
	fileData.append('code_sp', $(setLocation).find('#code_sp').val());
	fileData.append('comment', $(setLocation).find('#comment').val());

	if ($(setLocation).find('#hide_myself').prop('checked')) {
		fileData.append('hide_myself', $(setLocation).find('#hide_myself').prop('checked'));
	}

	var approval = $(setLocation).find('#approval option:selected').val();
	if (approval) fileData.append('approval', approval);


	// var adDefault = $(setLocation).find('#ad_default option:selected').val();
	// fileData.append('ad_default', adDefault);

//console.log(fileData);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "/rest/user/save_advertisement.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// --------------------------------------------------
		//   コードを変更した場合、非承認にする
		// --------------------------------------------------

		if (response.label_approval_code) {
			//console.log('aaa');
			$(setLocation).find('#label_approval').html(response.label_approval_code);
		}


		// --------------------------------------------------
		//   他のad_defaultをクリアする
		// --------------------------------------------------

		// if (adDefault) {
			// $(arguThis).closest("#config_advertisement_form_box").find('#ad_default [value=' + adDefault + ']').prop('selected', false);
			// $(setLocation).find('#ad_default [value=' + adDefault + ']').prop('selected', true);
		// }


	}).fail(function() {

		alert('error');

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert($(setLocation).find('#alert'), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* 広告を表示する
*/
GAMEUSERS.player.showAdvertisement = function(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#ad_box");


	// --------------------------------------------------
	//   現在、広告が表示されてる場合は、非表示にして処理停止
	// --------------------------------------------------

	var showing = $(setLocation).data('showing');

	if (showing) {
		// console.log(showing);
		$(setLocation).find('#test_box').hide();
		$(setLocation).find('#test_box_sp').hide();

		$(setLocation).data('showing', false);

		return;
	}


	// --------------------------------------------------
	//   広告を表示する
	// --------------------------------------------------

	var code = $(setLocation).find('#code').val();
	var codeSp = $(setLocation).find('#code_sp').val();

	if (code) {
		$(setLocation).find('#test_box').addClass('padding_bottom_20').html(code).show();
	}

	if (codeSp) {
		$(setLocation).find('#test_box_sp').addClass('padding_bottom_20').html(codeSp).show();
	}

	$(setLocation).data('showing', true);

};



/**
* 基本設定読み込み
*/
GAMEUSERS.player.readConfig = function() {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#tab_config";


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("api_type", "config_player_basic");


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/api/common.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {
			$(setLocation).html(response.code);
		}

	}).fail(function() {

		alert('error');

	}).always(function() {

	});

};



/**
* 通知設定読み込み
*/
GAMEUSERS.player.readConfigNotification = function() {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#tab_config_notification";


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("api_type", "config_notification");


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/api/common.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {
			$(setLocation).html(response.code);
		}

	}).fail(function() {

		alert('error');

	}).always(function() {

	});

};



/**
* 広告設定読み込み
*/
GAMEUSERS.player.readConfigAdvertisement = function() {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#tab_config_advertisement";


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("api_type", "config_advertisement");


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/api/common.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {
			$(setLocation).html(response.code);
		}

	}).fail(function() {

		alert('error');

	}).always(function() {

	});

};



/**
* Wiki設定読み込み
*/
/*
GAMEUSERS.player.readConfigWiki = function() {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#tab_wiki";


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("api_type", "config_wiki");
	fileData.append("type", "player");


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/api/common.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {

			$(setLocation).html(response.code);

			// Wiki　作成フォーム読み込み
			GAMEUSERS.wiki_config.readWikiCreate();

			// Wiki　編集読み込み
			GAMEUSERS.wiki_config.readWikiList(null, 1, true);

		}

	}).fail(function() {

		alert('error');

	}).always(function() {

	});

};
*/
