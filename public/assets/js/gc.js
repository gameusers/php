
// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.gc');



// --------------------------------------------------
//   プロパティ
// --------------------------------------------------



$(function() {


	// --------------------------------------------------
	//   BBSフォーム初期化
	// --------------------------------------------------

	formInit('#content_bbs_index');


	// --------------------------------------------------
	//  Sticky（サイドメニュー固定）を作動させる
	// --------------------------------------------------

	$('#menu_bbs, #content_bbs_index').imagesLoaded( function() {

		// --------------------------------------------------
		//  Sticky Kit　メニュー固定
		// --------------------------------------------------

		if (agent_type === '') {
			GAMEUSERS.common.stickyMenu('.menu .slide', 'rectangle');
		} else {
			GAMEUSERS.common.stickyMenu('.menu_s .slide', 'small');
		}


		// タブが正常に表示されないときがあるが、スクロールすると表示されるので、これを入れておく
		$(window).trigger('scroll');

	});


	// --------------------------------------------------
	//  スライドメニュー　スマホ用
	// --------------------------------------------------

	if ($('#slideMenu')[0]) {

		// 初期状態ではdisplay: noneになっているので、DOM読み込み後、表示する　最初に隠さないと読み込み時に一瞬表示されてしまうため
		$('#slideMenu').css('display', 'block');

		$('#slideMenu').lastSidebar({
			align: 'left',
		});

	}




	// --------------------------------------------------
	//   コンテンツを先読みする
	// --------------------------------------------------

	// ---------------------------------------------
	//   交流掲示板
	// ---------------------------------------------

	if ( ! $('#bbs_box')[0]) {
		readBbsThreadList(this, 1, 'gc', game_no, 0);
		readBbs(this, 1, 'gc', game_no, 0, 0, 0);
	}

	// ---------------------------------------------
	//   募集掲示板
	// ---------------------------------------------

	if ( ! $('#recruitment_box')[0]) {
		GAMEUSERS.gc.readRecruitmentMenu(game_no);
		readRecruitment(this, 1, game_no, null, 0, 0, 0);
	}



	// --------------------------------------------------
	//   ホバーでコンテンツを先読みする
	// --------------------------------------------------

	var eventHoverType = (agent_type === '') ? 'mouseenter' : 'touchstart';


	// ---------------------------------------------
	//   タブ　設定
	// ---------------------------------------------

	var configFirstLoad = true;

	$('#tab_config').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'config' && configFirstLoad) {
			readGcSelectProfileForm(this, 1, game_no);
			readEditGameIdForm(this, 1);
			configFirstLoad = false;

		}

	});


	// ---------------------------------------------
	//   タブ　ヘルプ
	// ---------------------------------------------

	var helpFirstLoad = true;

	$('#tab_help').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'help' && helpFirstLoad) {
			GAMEUSERS.common.readHelpVer2(this, 1, 'game', 'game_about');
			helpFirstLoad = false;
		}

	});




	// --------------------------------------------------
	//   pushState　ブラウザの戻るボタンを押したときにコンテンツを読み込む
	// --------------------------------------------------

	if (window.history && window.history.pushState) {
// console.log('aaa');
		$(window).on('popstate', function(e) {

			if ( !e.originalEvent.state) return; // 初回アクセス時に再読み込みしてしまう対策

			//GAMEUSERS.common.urlRewrite = false;


			var state = e.originalEvent.state;


			// ---------------------------------------------
			//   タブ切り替え
			// ---------------------------------------------

			GAMEUSERS.common.changeMenuContents(this, state.group, state.content);
			//console.log(state.group, state.content);


			// ---------------------------------------------
			//   コンテンツ読み込み
			// ---------------------------------------------

			if (state.function === 'readBbs') {

				readBbs(this, state.page, state.type, state.no, 0, 1, 0);

			} else if (state.function === 'readBbsComment') {

				readBbsComment(this, state.page, state.type, state.bbsThreadNo, 0, 1, 0);

			} else if (state.function === 'readBbsIndividual') {

				readBbsIndividual(this, state.type, state.no, state.bbsId, state.pageComment, 0, 1, 0);

			} else if (state.function === 'readRecruitment') {

				readRecruitment(this, state.page, state.gameNo, state.recruitmentId, 0, 0, 0);

			}

		});

	}


	// --------------------------------------------------
	//   募集検索　エンターキー
	// --------------------------------------------------

	$('#search_recruitment_keyword').keypress(function (e) {
		if (e.which == 13) {
			var searchRecruitmentGameNo = $(this).closest('#search_recruitment_box').data('game_no');
			readRecruitment(this, 1, searchRecruitmentGameNo, null, true, false);
			return false;
		}
	});


	// --------------------------------------------------
	//   最初にrecが読み込まれると、交流掲示板でも募集が表示されてしまう問題の解決策
	//   強引なやり方
	// --------------------------------------------------

	// if (contents_data.initial_load.group === 'rec') {
	// 	$('#recruitment_box').addClass('element_hidden');
	// 	// console.log('#content_bbs_index = ' + $('#content_bbs_index').is(':visible'));
	// 	// console.log('#content_rec_index = ' + $('#content_rec_index').is(':visible'));
	// 	// console.log('#recruitment_box = ' + $('#recruitment_box').is(':visible'));
	// 	// console.log('#recruitment_0y6mj6nvezd0rrg9 = ' + $('#recruitment_0y6mj6nvezd0rrg9').is(':visible'));
	// }


});





/**
* モーダル読み込み　通知受信
*/
function modalReadFormRecruitmentNotificationConfig(arguThis, gameNo) {
//console.log('aaa');

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = '#modal_box #' + gameNo + '_recruitment_notification_config';


	// --------------------------------------------------
	//   スマホの場合、スライドメニューを閉じる
	// --------------------------------------------------

	GAMEUSERS.common.closeSlideMenu();


	// --------------------------------------------------
	//   すでにフォームが存在する場合は再度読み込まない
	// --------------------------------------------------

	if ($(setLocation)[0]) {
		$(setLocation).modal();
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

	//fileData.append("content_id", contentId);
	fileData.append("type", 'gc');
	fileData.append("game_no", gameNo);



	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/form/modal_read_notification_recruitment_config.json",
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {



	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   コード反映
		// --------------------------------------------------

		if (response.code) {

			//console.log('formId = ' + formId);

			$('#modal_box').append(response.code);
			$(setLocation).modal();

			// BBSフォーム初期化
			//formInit(setLocation);
			//alert(response.code);
			// //showAlert(setLocation + " #alert", response.alert_color, response.alert_title, response.alert_message);
			// showAlert($(setLocation).find("#alert"), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}



/**
* モーダル読み込み　募集投稿フォーム
*/
function modalReadFormRecruitment(arguThis, gameNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = '#modal_box #' + gameNo + '_form_recruitment';


	// --------------------------------------------------
	//   スマホの場合、スライドメニューを閉じる
	// --------------------------------------------------

	GAMEUSERS.common.closeSlideMenu();


	// --------------------------------------------------
	//   すでにフォームが存在する場合は再度読み込まない
	// --------------------------------------------------

	if ($(setLocation)[0]) {
		$(setLocation).modal();
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

	//fileData.append("content_id", contentId);
	fileData.append("game_no", gameNo);
	fileData.append("type", 'gc');


	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	// if(typeof appMode != "undefined") {
	// 	fileData.append("app_mode", true);
	// }


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/form/modal_form_recruitment.json",
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {



	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   コード反映
		// --------------------------------------------------

		if (response.code) {

			//console.log('formId = ' + formId);

			$('#modal_box').append(response.code);
			$(setLocation).modal();


			// 隠す
			//$(setLocation).find("#help_text_login").hide();
			//$(setLocation + ' #help_text_login').hide();
			//console.log($(setLocation + ' #help_text_login'));

			// --------------------------------------------------
			//   募集投稿フォーム　初期化
			// --------------------------------------------------

			recruitmentFormInit(setLocation);

			// ID選択　初期化
			initFormRecruitmentSelectGameId(setLocation);

		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}







/**
* 新規募集が投稿されたときに通知を受けとるかの設定保存
*/
function saveGcNotification(arguThis, gameNo, type, onOff) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = "#" + gameNo + "_recruitment_notification_config";


	// --------------------------------------------------
	//   読み込み中は処理しない
	// --------------------------------------------------

	if ($(setLocation).data('loading')) {
		return;
	} else {
		$(setLocation).data('loading', true);
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   ボタン　アクティブ切り替え
	// --------------------------------------------------

	if (onOff) {
		$(setLocation + ' #gc_notification_recruitment_on').attr('class', 'btn btn-default ladda-button active');
		$(setLocation + ' #gc_notification_recruitment_off').attr('class', 'btn btn-default ladda-button');
	} else {
		$(setLocation + ' #gc_notification_recruitment_on').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #gc_notification_recruitment_off').attr('class', 'btn btn-default ladda-button active');
	}


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("game_no", gameNo);
	fileData.append("type", type);
	if (onOff) fileData.append("on", 1);


	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	// if(typeof appMode != "undefined") {
	// 	fileData.append("app_mode", true);
	// }


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/gc/save_gc_notification.json",
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {



	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		// if (response.alert_title) {
			// //showAlert(setLocation + " #alert", response.alert_color, response.alert_title, response.alert_message);
			// showAlert($(setLocation).find("#alert"), response.alert_color, response.alert_title, response.alert_message);
		// }

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


		// --------------------------------------------------
		//   読み込み中は処理しない
		// --------------------------------------------------

		$(setLocation).data('loading', false);

	});

}






/**
* 募集メニュー読み込み
*/
GAMEUSERS.gc.readRecruitmentMenu = function(gameNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = 'main';


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'recruitment_menu');
	fileData.append('game_no', gameNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);



	$.ajax({

		url: uri_base + 'rest/api/common.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {
			$(setLocation + ' #menu_rec').html(response.code);
		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

	});

};



/**
* 募集読み込み
*/
function readRecruitment(arguThis, page, gameNo, recruitmentId, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = 'main';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('game_no', gameNo);

	if (recruitmentId) {
		fileData.append('recruitment_id', recruitmentId);
	} else  {
		fileData.append('page', page);
	}


	// --------------------------------------------------
	//   募集検索フォーム読み込み
	// --------------------------------------------------

	// 募集の種類
	var searchRecruitmentType = $(setLocation + ' [id="search_recruitment_type"]:checked').map(function(){
		return $(this).val();
	}).get().join(',');

	if (searchRecruitmentType) fileData.append('search_recruitment_type', searchRecruitmentType);

	// IDの種類
	var searchRecruitmentHardwareIdNo = $(setLocation + ' [id="search_recruitment_hardware_id_no"]:checked').map(function(){
		return $(this).val();
	}).get().join(',');

	if (searchRecruitmentHardwareIdNo) fileData.append('search_recruitment_hardware_id_no', searchRecruitmentHardwareIdNo);

	if ($(setLocation + ' #search_recruitment_id_null:checked').val()) fileData.append("search_recruitment_id_null", 1);

	if ($(setLocation + ' #search_recruitment_keyword').val()) fileData.append('search_recruitment_keyword', $(setLocation + ' #search_recruitment_keyword').val());


	// console.log('gameNo = ' + gameNo);
	// console.log('recruitmentId = ' + recruitmentId);
	// console.log('page = ' + page);
	// console.log('searchRecruitmentType = ' + searchRecruitmentType);
	// console.log("$(setLocation + ' #search_recruitment_keyword').val() = " + $(setLocation + ' #search_recruitment_keyword').val());


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/gc/read_recruitment.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

//console.log(response);
		if (response.code) {


			$(setLocation + ' #content_rec_index').html(response.code);
			//hideNgRecruitment();


			// --------------------------------------------------
			//   スクロールする
			// --------------------------------------------------

			if (scroll) {
				GAMEUSERS.common.stickyStuck = false;
				scrollToAnchor(setLocation + ' #recruitment_box', -(GAMEUSERS.common.scrollMargin), 0);
			}



			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.rec_index.state = response.state;
			contents_data.rec_index.url = response.url;
			contents_data.rec_index.meta_title = response.meta_title;
			contents_data.rec_index.meta_keywords = response.meta_keywords;
			contents_data.rec_index.meta_description = response.meta_description;
// console.log(page, response.url);

			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) GAMEUSERS.common.changeUrlAndMeta('rec', 'index');


			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			// if (urlRewrite) {
			//
			// 	// var state = { 'group': 'rec', 'content': 'index', 'function': 'readRecruitment', 'page': page, 'gameNo': gameNo, 'recruitmentId': recruitmentId };
			// 	// var url = $(arguThis).attr('href');
			// 	// window.history.pushState(state, null, url);
			// 	//
			// 	// // タブのURLを変更する
			// 	// $('#tab_rec a').attr('href', url);
			// 	//
			// 	// // meta書き換え
			// 	// GAMEUSERS.common.rewriteMeta(response.meta_title, response.meta_keywords, response.meta_description);
			//
			// 	// ---------------------------------------------
			// 	//   URLとMetaを変更
			// 	// ---------------------------------------------
			//
			// 	GAMEUSERS.common.changeUrlAndMeta('rec', 'index');
			//
			// }






			// --------------------------------------------------
			//   タブのデータ書き換え
			// --------------------------------------------------

			// $('#tab_rec a').data('meta-title', response.meta_title);
			// $('#tab_rec a').data('meta-keywords', response.meta_keywords);
			// $('#tab_rec a').data('meta-description', response.meta_description);


			// --------------------------------------------------
			//   スマホの場合、スライドメニューを閉じる
			// --------------------------------------------------

			GAMEUSERS.common.closeSlideMenu();


		}


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		if (loading) loadingToggle(arguThis, null);


	});

}




/**
* 募集 > 返信　読み込み
*/
function readRecruitmentReply(arguThis, page, gameNo, recruitmentId) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = '#recruitment_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('page', page);
	fileData.append('game_no', gameNo);
	fileData.append('recruitment_id', recruitmentId);



	$.ajax({

		url: uri_base + 'rest/gc/read_recruitment_reply.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {

			$(setLocation + ' #recruitment_' + recruitmentId + ' #recruitment_reply_box').html(response.code);
			//hideNgRecruitment();
	 		scrollToAnchor(setLocation + ' #recruitment_' + recruitmentId + ' #recruitment_reply_box', scrollBlankSize, 0);

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


	});

}





/**
* NGの募集・返信を隠す
*/
// function hideNgRecruitment() {
// 	//alert('aaa');
//
//
// 	// --------------------------------------------------
// 	//   場所設定
// 	// --------------------------------------------------
//
// 	var children;
//
// 	children = $("#recruitment_box").children("[data-ng=1]");
// 	$(children).find("#recruitment_title").hide();
// 	$(children).find("#recruitment_content").hide();
// 	$(children).find("#recruitment_reply_box").hide();
//
// 	children = $("#recruitment_reply_box").children("[data-ng=1]");
// 	$(children).find("#gc_reply_content").hide();
//
// }


/**
* NGの募集・返信を表示する
*/
// function showNgRecruitment(arguThis, recruitmentId, recruitmentReplyId) {
//
// 	var setLocation;
//
// 	// --------------------------------------------------
// 	//   募集
// 	// --------------------------------------------------
//
// 	if (recruitmentId) {
//
// 		setLocation = $(arguThis).closest("#recruitment_" + recruitmentId);
// 		$(setLocation).find("#recruitment_title_ng").remove();
// 		$(setLocation).find("#recruitment_title").show();
// 		$(setLocation).find("#recruitment_content").show();
// 		$(setLocation).find("#recruitment_reply_box").show();
//
//
// 	// --------------------------------------------------
// 	//   返信
// 	// --------------------------------------------------
//
// 	} else {
//
// 		setLocation = $(arguThis).closest("#recruitment_reply_" + recruitmentReplyId);
// 		$(setLocation).find("#recruitment_reply_ng_content").remove();
// 		$(setLocation).find("#gc_reply_content").show();
//
// 	}
//
// }





/**
* 公開条件の説明文を表示・非表示
*/
function showIdExplanation(arguThis, type, id) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;
	//var contentId = getContentId(arguThis);

	if (type == 'recruitment') {
		setLocation = "#recruitment_" + id + " #collapseRecruitmentIdExplanation";
	} else {
		setLocation = "#recruitment_reply_" + id + " #collapseReplyIdExplanation";
	}

	$(setLocation).collapse('toggle');

}




/**
* 募集・返信　投稿・編集フォーム表示
*/
function showRecruitmentForm(arguThis, gameNo, formType, recruitmentId, recruitmentReplyId) {

	// --------------------------------------------------
	//   2度の表示は禁止
	// --------------------------------------------------

	// var contentId = getContentId(arguThis);
//
	// if ($("#" + contentId + " #tab_top_" + contentId + " #announcement_box #form_box")[0]) {
		// return;
	// }


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	// if (announcementNo) {
		// var setLocation = "#" + contentId + " #tab_top_" + contentId + " #announcement_box";
	// } else {
		// var setLocation = "#" + contentId + " #tab_top_" + contentId;
	// }
	var setLocation = $(arguThis).closest("#recruitment_" + recruitmentId);


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("game_no", gameNo);
	fileData.append("form_type", formType);
	fileData.append("recruitment_id", recruitmentId);
	if (recruitmentReplyId) fileData.append("recruitment_reply_id", recruitmentReplyId);
	//alert(recruitmentId);

	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	// if(typeof appMode != "undefined") {
	// 	fileData.append("app_mode", true);
	// }


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	//alert(uri_base + "rest/gc/show_recruitment_form.json");
	$.ajax({

		url: uri_base + "rest/gc/show_recruitment_form.json",
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		//alert(response.code);

		if (response.code) {

			if (formType == 'recruitment_edit') {

				$(setLocation).find("#recruitment_content").hide();
				$(setLocation).find("#recruitment_content").after(response.code);

				recruitmentFormInit(setLocation);

				// ID選択　初期化
				initFormRecruitmentSelectGameId(setLocation);

				scrollToAnchor(setLocation, scrollBlankSize, 0);

			} else if (formType == 'reply_new') {


				// --------------------------------------------------
				//   コード反映
				// --------------------------------------------------

				var code = '<div class="gc_reply_enclosure" id="form_reply_new">';
				code += response.code;
				code += '</div>';

				var replyFormObject;

				if (recruitmentReplyId === null) {
					replyFormObject = $(setLocation).find("#recruitment_reply_box").prepend(code);
				} else {
					replyFormObject = $(setLocation).find("#recruitment_reply_" + recruitmentReplyId).after(code);
				}


				// --------------------------------------------------
				//   フォーム初期化
				// --------------------------------------------------

				recruitmentFormInit(setLocation);


				// --------------------------------------------------
				//   スクロール
				// --------------------------------------------------

				if (recruitmentReplyId === null) {
					scrollToAnchor($(setLocation).find("#recruitment_reply_box"), scrollBlankSize, 0);
				} else {
					scrollToAnchor($(setLocation).find("#recruitment_reply_" + recruitmentReplyId).next(), scrollBlankSize, 0);
				}

				//console.log('replyFormObject = ' + replyFormObject);
				//console.log('recruitmentReplyId = ' + recruitmentReplyId);


				// --------------------------------------------------
				//   ID選択　初期化
				// --------------------------------------------------

				if (recruitmentReplyId === null) {
					initFormRecruitmentSelectGameId(replyFormObject);
				} else {
					initFormRecruitmentSelectGameId($(setLocation).find("#recruitment_reply_" + recruitmentReplyId).next());
				}


			} else if (formType == 'reply_edit') {

				$(setLocation).find("#recruitment_reply_" + recruitmentReplyId + " #gc_reply_content").hide();
				$(setLocation).find("#recruitment_reply_" + recruitmentReplyId + " #gc_reply_content").after(response.code);

				recruitmentFormInit(setLocation);

				// ID選択　初期化
				initFormRecruitmentSelectGameId($(setLocation).find("#recruitment_reply_" + recruitmentReplyId));

				scrollToAnchor($(setLocation).find("#recruitment_reply_" + recruitmentReplyId), scrollBlankSize, 0);

			}

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		// if (response.alert_title) {
			// showAlert(setLocation + " #alert_config_community", response.alert_color, response.alert_title, response.alert_message);
		// }
		//alert(response.alert_message);
		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}



/**
* 募集・返信　投稿・編集フォーム非表示
*/
function hideRecruitmentForm(arguThis, recruitmentId) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#form_recruitment");

	var formType = $(setLocation).data("form_type");
	//var gameNo = $(setLocation).data("game_no");
	recruitmentId = $(setLocation).data("recruitment_id");
	var recruitmentReplyId = $(setLocation).data("recruitment_reply_id");

	setLocation = $(arguThis).closest("#recruitment_" + recruitmentId);

	//alert(formType);

	if (formType == 'recruitment_edit') {

		$(setLocation).find("#recruitment_content").show();
		$(setLocation).find("#recruitment_content").next().remove();

		// スクロール
		scrollToAnchor(setLocation, scrollBlankSize, 0);

	} else if (formType == 'reply_new') {
		//alert(recruitmentReplyId);
		if ( ! recruitmentReplyId) {

			$(setLocation).find("#form_reply_new").remove();

			// スクロール
			scrollToAnchor(setLocation, scrollBlankSize, 0);

		} else {

			$(setLocation).find("#recruitment_reply_" + recruitmentReplyId).next().remove();

			// スクロール
			scrollToAnchor($(setLocation).find("#recruitment_reply_" + recruitmentReplyId), scrollBlankSize, 0);

		}



	} else if (formType == 'reply_edit') {

		$(setLocation).find("#recruitment_reply_" + recruitmentReplyId + " #gc_reply_content").show();
		$(setLocation).find("#recruitment_reply_" + recruitmentReplyId + " #form_recruitment").remove();

		// スクロール
		scrollToAnchor($(setLocation).find("#recruitment_reply_" + recruitmentReplyId), scrollBlankSize, 0);

	}


	// fileData.append("game_no", $(setLocation).data("game_no"));
	// fileData.append("recruitment_id", $(setLocation).data("recruitment_id"));
	// fileData.append("recruitment_reply_id", $(setLocation).data("recruitment_reply_id"));

}



/**
* 投稿編集フォーム　ID選択フォーム読み込み
*/
//function readFormRecruitmentSelectGameId(arguThis, page, gameNo, formType, recruitmentId, recruitmentReplyId) {
function readFormRecruitmentSelectGameId(arguThis, page) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = $(arguThis).closest("#form_recruitment");


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   data取得
	// --------------------------------------------------

	gameNo = $(setLocation).data('game_no');
	formType = $(setLocation).data('form_type');
	recruitmentId = $(setLocation).data('recruitment_id');
	recruitmentReplyId = $(setLocation).data('recruitment_reply_id');


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("page", page);
	fileData.append("game_no", gameNo);
	fileData.append("form_type", formType);
	if (recruitmentId) fileData.append("recruitment_id", recruitmentId);
	if (recruitmentReplyId) fileData.append("recruitment_reply_id", recruitmentReplyId);

	//alert($(setLocation).data('game_no'));

	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	// if(typeof appMode != "undefined") {
	// 	fileData.append("app_mode", true);
	// }


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	//alert(page + '/' + gameNo + '/' + formType + '/' + recruitmentId + '/' + recruitmentReplyId);
	//return;

	$.ajax({

		url: uri_base + "rest/gc/read_form_recruitment_select_game_id.json",
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {
		//alert(response.code);
		if (response.code) {

			$(setLocation).find("#gc_recruitment_form_select_id_box").html(response.code);


			// --------------------------------------------------
			//   ID選択　初期化
			// --------------------------------------------------

			initFormRecruitmentSelectGameId(setLocation);
			//initFormRecruitmentSelectGameId($(arguThis).closest("#gc_recruitment_form_select_id_box"));


		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}



/**
* ID選択　初期化
*/
function initFormRecruitmentSelectGameId(setLocation) {

	// --------------------------------------------------
	//   ページが変わってもチェック状況を維持する
	// --------------------------------------------------
	//console.log('aaa = ');
	var selectedIdArr = [];
	var selectedIdString = $(setLocation).find("#gc_recruitment_form_select_id_box").data("selected_id_arr");

	if (selectedIdString) {
		selectedIdArr = selectedIdString.split('/-*-/');
	}

	//console.log('selectedIdString = ' + selectedIdString);
	//console.log('selectedIdArr = ' + selectedIdArr);

	$.each($(setLocation).find("[id ^= id_select_checkbox_]"), function() {

		var hardware_no = $(this).data("hardware_no");
		var id = $(this).data("id");
		var hardware_no_id = hardware_no + '_' + id;

		if ($.inArray(hardware_no_id, selectedIdArr) != -1) {
			$(this).prop("checked", true);
		} else {
			$(this).prop("checked", false);
		}

		// キー番号取得
		var inArrayResultKeyNo = $.inArray(hardware_no_id, selectedIdArr);

		// 配列に存在していない場合は追加
		if (inArrayResultKeyNo == -1) {
			//console.log('配列に存在していない場合は追加');
			selectedIdArr.push(hardware_no_id);
		}

		// 配列に存在している場合は削除
		if (inArrayResultKeyNo != -1) {
			//console.log('配列に存在している場合は削除');
			selectedIdArr.splice(inArrayResultKeyNo, 1);
		}

		//console.log('チェック用 = ' + hardware_no_id);

	});


	// --------------------------------------------------
	//   チェックボックスイベント
	// --------------------------------------------------

	$(setLocation).find("[id ^= id_select_checkbox_]").change(function() {

		// 保存用データ取得
		var hardware_no = $(this).data("hardware_no");
		var id = $(this).data("id");
		var hardware_no_id = hardware_no + '_' + id;

		// 現状の配列取得
		var selectedIdString = $(setLocation).find("#gc_recruitment_form_select_id_box").data("selected_id_arr");
		var selectedIdArr = selectedIdString.split('/-*-/');
		//alert(selectedIdArr);

		// 配列が存在しない場合、新たに作成
		if (selectedIdArr === '') {
			//alert('new');
			selectedIdArr = new Array(hardware_no_id);
		}

		// キー番号取得
		var inArrayResultKeyNo = $.inArray(hardware_no_id, selectedIdArr);
		//alert(inArrayResultKeyNo);

		// チェックされた場合の処理
		if ($(this).prop('checked')) {

			//console.log('チェックされた');

			// 配列に存在していない場合は追加
			if (inArrayResultKeyNo == -1) {
				//console.log('配列に存在していない場合は追加');
				selectedIdArr.push(hardware_no_id);
			}

			// チェックを解除された場合の処理
		} else {

			//console.log('チェックを解除された');

			// 配列に存在している場合は削除
			if (inArrayResultKeyNo != -1) {
				//console.log('配列に存在している場合は削除');
				selectedIdArr.splice(inArrayResultKeyNo, 1);
			}

		}

		$(setLocation).find("#gc_recruitment_form_select_id_box").data("selected_id_arr", selectedIdArr.join('/-*-/'));

		//console.log('hardware_no_id = ' + hardware_no_id + ' / ' + inArrayResultKeyNo + ' / ' + selectedIdArr);
		//console.log('hardware_no_id = ' + hardware_no_id);
		//console.log('inArrayResultKeyNo = ' + inArrayResultKeyNo);
		//console.log('selectedIdArr = ' + $(setLocation).find("#gc_recruitment_form_select_id_box").data("selected_id_arr"));

	});

}



/**
* フォーム初期処理　共通バージョン
*/
function recruitmentFormInit(setLocation) {


	// --------------------------------------------------
	//   ヘルプ　ログイン・アプリについて
	// --------------------------------------------------

	$(setLocation).find("#help_text_login").hide();
//console.log('aaa');
	$(setLocation).find("#help_title_login").click(function () {
		//console.log('bbb');
		$(setLocation).find("#help_text_login").slideToggle();
	});


	// --------------------------------------------------
	//   募集投稿フォーム　読み込み時に隠す
	// --------------------------------------------------

	$(setLocation).find("#image").hide();
	$(setLocation).find("#movie").hide();
	//$(setLocation).find("#personal_box_anonymity").hide();

	// 匿名にするチェックボックスがチェックされている場合、匿名プロフィールを表示する
	if ($(setLocation).find('#anonymity').prop('checked')) {
		$(setLocation).find('#personal_box').hide();
		$(setLocation).find('#personal_box_anonymity').show();
		//console.log('チェック');
	} else {
		$(setLocation).find('#personal_box').show();
		$(setLocation).find('#personal_box_anonymity').hide();
		//console.log('チェックされてない');
	}

	$(setLocation).find("#recruitment_form_id_select_box").hide();
	$(setLocation).find("#recruitment_form_id_input_box").hide();
	$(setLocation).find("#recruitment_form_info_input_box").hide();

	$(setLocation).find("#recruitment_type_explanation_1").hide();
	$(setLocation).find("#recruitment_type_explanation_2").hide();
	$(setLocation).find("#recruitment_type_explanation_3").hide();
	$(setLocation).find("#recruitment_type_explanation_4").hide();
	$(setLocation).find("#recruitment_type_explanation_5").hide();
	var recruitment_type = $(setLocation).find("#recruitment_type option:selected").val();
	$(setLocation).find("#recruitment_type_explanation_" + recruitment_type).show();

	//$(setLocation).find("#etc_title_box").hide();

	$(setLocation).find("#recruitment_open_type_explanation_1").hide();
	$(setLocation).find("#recruitment_open_type_explanation_2").hide();
	$(setLocation).find("#recruitment_open_type_explanation_3").hide();
	var recruitment_open_type = $(setLocation).find("#recruitment_open_type option:selected").val();
	//alert(recruitment_open_type);
	$(setLocation).find("#recruitment_open_type_explanation_" + recruitment_open_type).show();


	// --------------------------------------------------
	//   募集投稿フォーム　画像アップロードボタン
	// --------------------------------------------------

	$(setLocation).find("#image_button").click(function () {
		//alert('aaa');
		$(setLocation).find("#image").toggle();
		$(setLocation).find("#movie").hide();
	});


	// --------------------------------------------------
	//   募集投稿フォーム　動画投稿ボタン
	// --------------------------------------------------

	$(setLocation).find("#movie_button").click(function () {
		//alert('bbb');
		$(setLocation).find("#image").hide();
		$(setLocation).find("#movie").toggle();
	});


	// --------------------------------------------------
	//   募集投稿フォーム　匿名にする
	// --------------------------------------------------

	$(setLocation).find("#anonymity").click(function() {
		if ($(this).is(':checked')) {
			$(setLocation).find("#personal_box").hide();
			$(setLocation).find("#personal_box_anonymity").show();
		} else {
			$(setLocation).find("#personal_box").show();
			$(setLocation).find("#personal_box_anonymity").hide();
		}
	});


	// --------------------------------------------------
	//   募集投稿フォーム　ID選択ボタン
	// --------------------------------------------------

	$(setLocation).find("#id_select_button").click(function () {
		$(setLocation).find("#recruitment_form_id_select_box").toggle();
		$(setLocation).find("#recruitment_form_id_input_box").hide();
		$(setLocation).find("#recruitment_form_info_input_box").hide();
	});


	// --------------------------------------------------
	//   募集投稿フォーム　ID入力ボタン
	// --------------------------------------------------

	$(setLocation).find("#id_input_button").click(function () {
		$(setLocation).find("#recruitment_form_id_select_box").hide();
		$(setLocation).find("#recruitment_form_id_input_box").toggle();
		$(setLocation).find("#recruitment_form_info_input_box").hide();
	});


	// --------------------------------------------------
	//   募集投稿フォーム　情報入力ボタン
	// --------------------------------------------------

	$(setLocation).find("#info_button").click(function () {
		$(setLocation).find("#recruitment_form_id_select_box").hide();
		$(setLocation).find("#recruitment_form_id_input_box").hide();
		$(setLocation).find("#recruitment_form_info_input_box").toggle();
	});



	// --------------------------------------------------
	//   募集投稿フォーム　タイプを変更すると説明文も切り替わる
	// --------------------------------------------------

	$(setLocation).find("#recruitment_type").change(function() {

		$(setLocation).find("#recruitment_type_explanation_1").hide();
		$(setLocation).find("#recruitment_type_explanation_2").hide();
		$(setLocation).find("#recruitment_type_explanation_3").hide();
		$(setLocation).find("#recruitment_type_explanation_4").hide();
		$(setLocation).find("#recruitment_type_explanation_5").hide();

		$(setLocation).find("#recruitment_type_explanation_" + $(this).val()).show();

		// if ($(this).val() == 5) {
			// $(setLocation).find("#etc_title_box").show();
		// } else {
			// $(setLocation).find("#etc_title_box").hide();
		// }

	});


	// --------------------------------------------------
	//   募集投稿フォーム　ID＆情報公開タイプを変更すると説明文も切り替わる
	// --------------------------------------------------

	$(setLocation).find("#recruitment_open_type").change(function() {

		$(setLocation).find("#recruitment_open_type_explanation_1").hide();
		$(setLocation).find("#recruitment_open_type_explanation_2").hide();
		$(setLocation).find("#recruitment_open_type_explanation_3").hide();

		$(setLocation).find("#recruitment_open_type_explanation_" + $(this).val()).show();

	});


	// --------------------------------------------------
	//   テキストエリア自動リサイズ
	// --------------------------------------------------

	$(setLocation).find("#comment").on('focus', function(){
		autosize($(this));
	});



	// --------------------------------------------------
	//   ID選択　初期化
	// --------------------------------------------------

	//initFormRecruitmentSelectGameId(setLocation);


}



/**
* 募集・返信保存
*/
function saveRecruitment(arguThis, close) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#form_recruitment");


	// --------------------------------------------------
	//   利用規約に同意しない場合は処理停止
	// --------------------------------------------------

	if ($(setLocation).find("#user_terms")[0]) {
		var check = $(setLocation).find("#user_terms").prop('checked');
		if ( ! check) {
			alert('利用規約に同意する必要があります');
			return;
		}
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);



	// console.log('game_no = ' + $(setLocation).data("game_no"));
	// return;

	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	fileData.append("form_type", $(setLocation).data("form_type"));
	fileData.append("game_no", $(setLocation).data("game_no"));
	fileData.append("recruitment_id", $(setLocation).data("recruitment_id"));
	fileData.append("recruitment_reply_id", $(setLocation).data("recruitment_reply_id"));


	fileData.append("type", $(setLocation).find("#recruitment_type option:selected").val());
	fileData.append("etc_title", $(setLocation).find("#etc_title").val());
	fileData.append("handle_name", $(setLocation).find("#handle_name").val());
	fileData.append("comment", $(setLocation).find("#comment").val());

	if ($(setLocation).find("#anonymity").prop('checked')) {
		fileData.append("anonymity", $(setLocation).find("#anonymity").prop('checked'));
	}


	// 画像アップロード
	if ($(setLocation).find("#image_1")[0]) {
		if ($(setLocation).find("#image_1").val() !== '') {
			fileData.append( "image_1", $(setLocation).find("#image_1").prop("files")[0]);
		}
	}

	if ($(setLocation).find("#image_1_delete")[0]) {
		if ($(setLocation).find("#image_1_delete").prop('checked')) {
			fileData.append("image_1_delete", $(setLocation).find("#image_1_delete").prop('checked'));
		}
	}

	// 動画登録
	if ($(setLocation).find("#movie_url")[0]) {
		fileData.append("movie_url", $(setLocation).find("#movie_url").val());
	}


	// ID選択
	/*
	if ($(setLocation).find("#id_select_checkbox_1").prop('checked')) {
		fileData.append("id_select_hardware_no_1", $(setLocation).find("#id_select_checkbox_1").data("hardware_no"));
		fileData.append("id_select_1", $(setLocation).find("#id_select_checkbox_1").data("id"));
	}

	if ($(setLocation).find("#id_select_checkbox_2").prop('checked')) {
		fileData.append("id_select_hardware_no_2", $(setLocation).find("#id_select_checkbox_2").data("hardware_no"));
		fileData.append("id_select_2", $(setLocation).find("#id_select_checkbox_2").data("id"));
	}

	if ($(setLocation).find("#id_select_checkbox_3").prop('checked')) {
		fileData.append("id_select_hardware_no_3", $(setLocation).find("#id_select_checkbox_3").data("hardware_no"));
		fileData.append("id_select_3", $(setLocation).find("#id_select_checkbox_3").data("id"));
	}
	*/


	var selectedId = $(setLocation).find("#gc_recruitment_form_select_id_box").data("selected_id_arr");
	if (selectedId) fileData.append("id_select", selectedId);





	// ID入力
	fileData.append("id_input_hardware_no_1", $(setLocation).find("#id_input_hardware_no_1 option:selected").val());
	fileData.append("id_input_1", $(setLocation).find("#id_input_1").val());

	fileData.append("id_input_hardware_no_2", $(setLocation).find("#id_input_hardware_no_2 option:selected").val());
	fileData.append("id_input_2", $(setLocation).find("#id_input_2").val());

	fileData.append("id_input_hardware_no_3", $(setLocation).find("#id_input_hardware_no_3 option:selected").val());
	fileData.append("id_input_3", $(setLocation).find("#id_input_3").val());


	// 情報入力
	fileData.append("info_title_1", $(setLocation).find("#info_title_1").val());
	fileData.append("info_1", $(setLocation).find("#info_1").val());

	fileData.append("info_title_2", $(setLocation).find("#info_title_2").val());
	fileData.append("info_2", $(setLocation).find("#info_2").val());

	fileData.append("info_title_3", $(setLocation).find("#info_title_3").val());
	fileData.append("info_3", $(setLocation).find("#info_3").val());

	fileData.append("info_title_4", $(setLocation).find("#info_title_4").val());
	fileData.append("info_4", $(setLocation).find("#info_4").val());

	fileData.append("info_title_5", $(setLocation).find("#info_title_5").val());
	fileData.append("info_5", $(setLocation).find("#info_5").val());


	// ID・情報の公開方法
	fileData.append("open_type", $(setLocation).find("#recruitment_open_type option:selected").val());


	// 募集期間
	fileData.append("limit_days", $(setLocation).find("#limit_days").val());
	fileData.append("limit_hours", $(setLocation).find("#limit_hours").val());
	fileData.append("limit_minutes", $(setLocation).find("#limit_minutes").val());


	// Twitter 締め切りの場合はTweetしない
	var twitter = $(setLocation).find("#recruitment_twitter").prop('checked');
	if (twitter && ! close) {
		fileData.append("twitter", twitter);
	}


	// 特定の返信者への返信　通知用
	fileData.append("specific_recruitment_reply_id", $(setLocation).data("specific_recruitment_reply_id"));
	//alert($(setLocation).data("specific_recruitment_reply_id"));
	//return;


	// 締め切り
	if (close) {
		fileData.append("close", 1);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + 'rest/gc/save_recruitment.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

// console.log(response);
		if (response.code) {

			//$("#recruitment_box").html(response.code);
			$('main #content_rec_index').html(response.code);


			var formId = '#modal_box #' + $(setLocation).data("game_no") + '_form_recruitment';

			// フォーム処理
			if ($(formId)[0]) {

				// モーダル非表示
				$(formId).modal('hide');

				// フォーム削除　内容をリセットする
				$(formId).on('hidden.bs.modal', function (e) {
					$(formId).remove();
				});

			}



			// --------------------------------------------------
			//   スクロールする
			// --------------------------------------------------

			GAMEUSERS.common.stickyStuck = false;
			scrollToAnchor('#recruitment_box', -(GAMEUSERS.common.scrollMargin), 0);


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.rec_index.state = response.state;
			contents_data.rec_index.url = response.url;
			contents_data.rec_index.meta_title = response.meta_title;
			contents_data.rec_index.meta_keywords = response.meta_keywords;
			contents_data.rec_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			GAMEUSERS.common.changeUrlAndMeta('rec', 'index');


			// // --------------------------------------------------
			// //   スクロール
			// // --------------------------------------------------
			//
			// GAMEUSERS.common.stickyStuck = false;
			// scrollToAnchor('#recruitment_box', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
			//
			//
			// // --------------------------------------------------
			// //   URL書き換え
			// // --------------------------------------------------
			//
			// var state = { 'group': 'rec', 'content': 'index', 'function': 'readRecruitment', 'page': 1, 'gameNo': game_no, 'recruitmentId': null };
			// var urlReplaced = uri_current.replace(uri_base, '');
			// var urlSplitArr = urlReplaced.split('/');
			// var url = uri_base + urlSplitArr[0] + '/' + urlSplitArr[1] + '/rec';
			// window.history.pushState(state, null, url);
			//
			// // タブのURLを変更する
			// $('#tab_rec a').attr('href', url);
			//
			// // meta書き換え
			// GAMEUSERS.common.rewriteMeta(response.meta_title, response.meta_keywords, response.meta_description);
			//
			//
			// // --------------------------------------------------
			// //   タブのデータ書き換え
			// // --------------------------------------------------
			//
			// $('#tab_rec a').data('meta-title', response.meta_title);
			// $('#tab_rec a').data('meta-keywords', response.meta_keywords);
			// $('#tab_rec a').data('meta-description', response.meta_description);


			// Google Adwords コンバージョン　募集の新規投稿のみで、アプリのときは動作しない
			// if ($(setLocation).data("form_type") == 'recruitment_new' && typeof appMode == "undefined") {
			// 	goog_report_conversion();
			// }



			// --------------------------------------------------
			//   Google Analytics イベント送信　ローカル、編集時には処理しない
			// --------------------------------------------------

			if (uri_base.indexOf('gameusers.org') !== -1) {
				if ($(setLocation).data("form_type") === 'recruitment_new') {
					ga('send', 'event',  'Game', 'Recruitment', 'Comment');
				} else if ($(setLocation).data("form_type") === 'reply_new') {
					ga('send', 'event',  'Game', 'Recruitment', 'Reply');
				}
			}

		}


		// PNotify 通知表示
		if (response.tweeted) {

			new PNotify({
				styling: 'bootstrap3',
				title: 'Twitter',
				text: 'ツイートしました。',
				type: 'success'
			});

		}


		// TwitterでツイートするためにOauth移行
		if (response.go_twitter) {

			// --------------------------------------------------
			//   App ソーシャルログイン　InAppBrowserで開く
			// --------------------------------------------------

			if(typeof appMode != "undefined") {

				//e.preventDefault();

				//var address = $(this).attr("href") + '?app=1';
				var address = uri_base + "login/auth/twitter?app=1";
				var ref = window.open(address, '_blank', 'location=yes,closebuttoncaption=閉じる,clearcache=no,clearsessioncache=no');
				ref.addEventListener('loadstop', function(e) {

					if(e.url.match(/^http.+login\/redirect\/.+$/)) {
						//alert('aad');
						ref.close();

						//var arr = e.url.split('/');
						// length = arr.length - 1;

						//alert(arr + '/' + length);
						//alert(arr[length - 1] + '/' + arr[length]);

						// 現在のページ以外を削除する
						//flipsnapDeleteItemsExceptCurrentItem();

						// デバイストークン取得
						//getDeviceToken();

						//appChangePage({'reload':'', 'header':'', 'contentsGc':{'id':arr[length]}});

						new PNotify({
							styling: 'bootstrap3',
							title: 'Twitter',
							text: 'ツイートしました。',
							type: 'success'
						});

					}

				});

			} else {
				document.location = uri_base + "login/auth/twitter";
			}

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert($(setLocation).find("#alert"), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}




/**
* 募集　ID・情報を公開する相手を承認
*/
function approvalRecruitment(arguThis, recruitmentId, userNo, profileNo) {

	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	var registration = $(arguThis).data("registration");
	//alert(registration);

	var confirm_text;

	if (registration) {
		confirm_text = 'このユーザーにID・情報を公開しますか？';
	} else {
		confirm_text = '現在、このユーザーにID・情報を公開しています。公開を取り消しますか？';
	}

    if ( ! window.confirm(confirm_text)) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = $(arguThis).find(".ladda-label");


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);
	//loadingStart("#aaa_submit");


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	fileData.append("recruitment_id", recruitmentId);
	if (userNo) fileData.append("user_no", userNo);
	if (profileNo) fileData.append("profile_no", profileNo);
	//alert(recruitmentId + '/' + userNo + '/' + profileNo);


	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	// if(typeof appMode != "undefined") {
	// 	fileData.append("app_mode", true);
	// }


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/gc/approval_recruitment.json",
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		//alert(response.code);
		if (response.label) {
			$(setLocation).html(response.label);

			if (registration) {
				$(arguThis).data("registration", 0);
			} else {
				$(arguThis).data("registration", 1);
			}
		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		// if (response.alert_title) {
			// showAlert($(setLocation).find("#alert"), response.alert_color, response.alert_title, response.alert_message);
		// }

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);
		//loadingStop("#aaa_submit");

	});

}






/**
* NGにする・解除する
*/
// function saveRecruitmentNgUserId(arguThis, type, id) {
//
//
// 	// --------------------------------------------------
// 	//   確認ダイアログ表示
// 	// --------------------------------------------------
//
// 	var confirm_text;
//
// 	if (type == 'save_recruitment' || type == 'save_reply') {
// 		confirm_text = 'NGにしますか？';
// 	} else if (type == 'delete_recruitment' || type == 'delete_reply') {
// 		confirm_text = 'NGを解除しますか？';
// 	}
//
//     if ( ! window.confirm(confirm_text)) {
// 		return;
// 	}
//
//
// 	// --------------------------------------------------
// 	//   場所設定
// 	// --------------------------------------------------
//
// 	//var contentId = getContentId(arguThis);
// 	var setLocation = "#recruitment_box";
//
//
// 	// --------------------------------------------------
// 	//   ローディング開始
// 	// --------------------------------------------------
//
// 	loadingToggle(arguThis, null);
//
//
// 	// --------------------------------------------------
// 	//   フォーム送信データ作成
// 	// --------------------------------------------------
//
// 	var fileData = new FormData();
//
//
// 	fileData.append("type", type);
// 	fileData.append("id", id);
//
//
// 	// --------------------------------------------------
// 	//   App Mode
// 	// --------------------------------------------------
//
// 	// if(typeof appMode != "undefined") {
// 	// 	fileData.append("app_mode", true);
// 	// }
//
//
// 	// --------------------------------------------------
// 	//   CSRF Token
// 	// --------------------------------------------------
//
// 	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
// 	fileData.append("fuel_csrf_token", fuel_csrf_token);
//
//
//
// 	$.ajax({
//
// 		url: uri_base + "rest/gc/save_ng_user_id.json",
// 		dataType: 'json',
// 		type: 'POST',
// 		data: fileData,
// 		enctype: 'multipart/form-data',
// 		processData: false,
// 		contentType: false
//
// 	}).done(function(response) {
//
// 		//alert(response.code);
// 		if (response.code) {
//
// 			$(setLocation + " #recruitment_" + response.recruitment_id).replaceWith(response.code);
//
// 			// NGタイトルは表示しない
// 			// if ($(setLocation + " #recruitment_" + response.recruitment_id + " #recruitment_title_ng")[0]) {
// 				// $(setLocation + " #recruitment_" + response.recruitment_id + " #recruitment_title_ng").hide();
// 			// }
//
// 			//hideNgRecruitment();
//
// 			//scrollToAnchor(setLocation + " #recruitment_" + response.recruitment_id, scrollBlankSize, 0);
//
// 			if (type == 'delete_recruitment' || type == 'delete_reply') {
// 				alert('NGを解除しました。');
// 			}
//
// 		}
//
// 	}).fail(function() {
//
// 		if (uri_base.indexOf('https://gameusers.org/') === -1) {
// 			alert('error');
// 		}
//
// 	}).always(function() {
//
// 		// --------------------------------------------------
// 		//   ローディング停止
// 		// --------------------------------------------------
//
// 		loadingToggle(arguThis, null);
//
// 	});
//
// }





/**
* 募集削除
*/
function deleteRecruitment(arguThis, recruitmentId, recruitmentReplyId) {

	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	var confirm_text;

	if (recruitmentReplyId) {
		confirm_text = 'この返信を削除しますか？';
	} else {
		confirm_text = 'この募集を削除しますか？';
	}

    if ( ! window.confirm(confirm_text)) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = $(arguThis).closest("#recruitment_" + recruitmentId);


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("recruitment_id", recruitmentId);
	if (recruitmentReplyId) fileData.append("recruitment_reply_id", recruitmentReplyId);



	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	// if(typeof appMode != "undefined") {
	// 	fileData.append("app_mode", true);
	// }


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/gc/delete_recruitment.json",
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.alert_color == 'success') {

			if (recruitmentReplyId) {
				//alert('aaa');
				$(setLocation).find("#recruitment_reply_" + recruitmentReplyId).slideUp("slow");
			} else {
				$(setLocation).slideUp("slow");
			}

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------
		//alert(response.alert_message);
		if (response.alert_title) {
			showAlert($(setLocation).find("#alert"), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}





/**
* 設定読み込み
*/
// function readGcConfig(arguThis, gameNo) {
//
// 	// --------------------------------------------------
// 	//   場所設定
// 	// --------------------------------------------------
//
// 	//var contentId = getContentId(arguThis);
// 	// var setLocation = 'main';
// 	//
// 	// var read = $(setLocation + " #tab_config_" + contentId).data("read");
// 	//
// 	// if ( ! read) {
// 	//
// 	// 	// プロフィール選択
// 	// 	readGcSelectProfileForm(arguThis, 1, gameNo);
// 	//
// 	// 	// ID登録・編集
// 	// 	readEditGameIdForm(arguThis, 1);
// 	//
// 	// 	$(setLocation + " #tab_config_" + contentId).data("read", true);
// 	// 	//alert(read);
// 	//
// 	// }
//
//
// 	// --------------------------------------------------
// 	//   プロフィール選択
// 	// --------------------------------------------------
//
// 	readGcSelectProfileForm(arguThis, 1, gameNo);
//
//
// 	// --------------------------------------------------
// 	//   ID登録・編集
// 	// --------------------------------------------------
//
// 	readEditGameIdForm(arguThis, 1);
//
//
// }





/**
* プロフィール選択フォーム読み込み
*/
function readGcSelectProfileForm(arguThis, page, gameNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = 'main';


	// --------------------------------------------------
	//   最初の読み込みかどうか
	// --------------------------------------------------

	var firstLoad = true;
	if ($(setLocation + " #gc_select_profile_form_content")[0]){
		firstLoad = false;
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if ( ! firstLoad) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("game_no", gameNo);
	fileData.append("page", page);

	// 全体を読み込むか一部を読み込むか
	var all = false;

	if ( ! $(setLocation + " #gc_select_profile_form_content")[0])
	{
		//alert('aaa');
		all = true;
		fileData.append("all", all);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + 'rest/gc/read_gc_select_profile_form.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		if (response.code) {

			if (all) {
				//$(setLocation + " #gc_select_profile_form_config_box").html(response.code);
				$(setLocation + " #content_config_index").prepend(response.code);
			} else {
				$(setLocation + " #gc_select_profile_form_content").html(response.code);
			}

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		if ( ! firstLoad) loadingToggle(arguThis, null);

	});

}




/**
* プロフィール選択保存
*/
function saveGcSelectProfile(arguThis, gameNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#gc_select_profile_form_config_box";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("game_no", gameNo);
	var selectProfile = $("input[name='select_profile']:checked").val();

	if (selectProfile == 'user') {
		fileData.append("user", 1);
	} else {
		fileData.append("profile_no", selectProfile);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);

	//alert('aaa');
	//return;

	$.ajax({

		url: uri_base + 'rest/gc/save_gc_select_profile.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合、ページ更新 -----

		if (response.alert_color == 'success') {
			location.href = uri_current;
		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert($(setLocation).find("#alert"), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}




/**
* ID登録フォーム読み込み
*/
function readEditGameIdForm(arguThis, page) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = 'main';


	// --------------------------------------------------
	//   最初の読み込みかどうか
	// --------------------------------------------------

	var firstLoad = true;
	if ($(setLocation + " #gc_game_id_form_content")[0]){
		firstLoad = false;
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if ( ! firstLoad) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	if (game_no) fileData.append("game_no", game_no);
	fileData.append("page", page);
	//alert('aaa');
	// 全体を読み込むか一部を読み込むか
	var all = false;

	if ( ! $(setLocation + " #gc_game_id_form_content")[0])
	{
		//alert('aaa');
		all = true;
		fileData.append("all", all);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);

	//alert('aaa');
	//return;

	$.ajax({

		url: uri_base + 'rest/gc/read_edit_game_id_form.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {
		//alert(response.code);
		if (response.code) {

			if (all) {
				// if ($('#panel_select_profile_form')[0]) {
				// 	$('#panel_select_profile_form').prepend(response.code);
				// } else {
				// 	$(setLocation + " #content_config_index").append(response.code);
				// }
				$(setLocation + " #content_config_index").append(response.code);
			} else {
				$(setLocation + " #gc_game_id_form_content").html(response.code);
			}
			//alert(all);
		}


		// --------------------------------------------------
		//   設定　ゲーム名検索　オートコンプリート読み込み
		// --------------------------------------------------

		var setLocationGame = "#gc_game_id_form_content";

		var engine = new Bloodhound({
			limit: 10,
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: uri_base + 'rest/common/search_game_name.json?keyword=%QUERY'
		});

		engine.initialize();

		$(setLocationGame + " #game_name").typeahead(null, {
			name: 'gameNameTypeahead',
			displayKey: 'value',
			source: engine.ttAdapter(),
			templates: {
				suggestion: function(data){
					return '<p class="needsclick">' + data.name + '</p>';
				}
			}
		}).bind('typeahead:selected', function(event, data) {

			$(this).data("game_no", data.game_no);

			var gameName = data.name;

			if (data.name.length > 20) {
				gameName = data.name.substr(0, 19) + "...";
			}

			$(this).closest(".gc_register_id_form_set").find("#simple_id").html("ID : " + gameName);

		});



		// --------------------------------------------------
		//   ゲーム名検索　非表示
		// --------------------------------------------------

		var gameName = $(setLocationGame + " [id=game_name]");

		$(gameName).each(function (i, val) {

			var gameNameGameNo = $(val).data("game_no");
			//alert(gameNameGameNo);

			if ( ! gameNameGameNo) {
				$(val).closest(".gc_register_id_form_set").find("#game_name_form_group").hide();
			}
			//alert(val);
		});



		// --------------------------------------------------
		//   募集投稿フォーム　ID＆情報公開タイプを変更すると説明文も切り替わる
		// --------------------------------------------------

		$(setLocationGame + " [id=hardware_no]").change(function() {
			//alert($(this).val());
			if ($(this).val()) {
				$(this).closest(".gc_register_id_form_set").find("#game_name_form_group").hide();
			} else {
				$(this).closest(".gc_register_id_form_set").find("#game_name_form_group").show();
			}

		});



		// --------------------------------------------------
		//   追加フォーム非表示
		// --------------------------------------------------

		$(setLocationGame + " #gc_add_game_id_form_box").hide();



	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		if ( ! firstLoad) loadingToggle(arguThis, null);

	});

}





/**
* ゲームID保存
*/
function saveGameId(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#gc_game_id_form_content";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();
	//var dataArr = new Array();

	for (var i=1; i <= 10; i++) {

		var formName = "form_" + i;
		var setLocationForm = setLocation + " #" + formName;

		var gameIdNo = $(setLocationForm).data('game_id_no');
		var sortNo = $(setLocationForm + " #sort_no").val();
		var hardwareNo = $(setLocationForm + " #hardware_no option:selected").val();
		var gameNo = $(setLocationForm + " #game_name").data("game_no");
		var delete_check;

		if ($(setLocationForm + " #delete")[0]) {
			delete_check = $(setLocationForm + " #delete").prop('checked');
		} else {
			delete_check = false;
		}

		var id = $(setLocationForm + " #id").val();

		if (id) {
			fileData.append(formName, [gameIdNo, sortNo, hardwareNo, gameNo, delete_check, id]);
		}

	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);

	//alert('aaa');
	//return;

	$.ajax({

		url: uri_base + "rest/gc/save_game_id.json",
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {

		// ----- 成功した場合、ページ更新 -----

		location.href = uri_current;

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert($("#gc_game_id_form_box #alert"), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}




/**
* ゲームID追加フォーム表示
*/
function addEditGameIdForm(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#gc_game_id_form_content";


	// --------------------------------------------------
	//   追加フォーム表示
	// --------------------------------------------------

	$(setLocation + " #gc_add_game_id_form_box").show();


	// --------------------------------------------------
	//   追加ボタン非表示
	// --------------------------------------------------

	$(arguThis).hide();


}
