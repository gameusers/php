// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.uc');


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
	//   メニュー　クリック
	// --------------------------------------------------

	$('[id=menu_card]').on('click', function(e) {


		// ----------------------------------------
		//   タイプ取得
		// ----------------------------------------

		var group = $(this).data('group');
		var content = $(this).data('content');

		//console.log(group, content);

		GAMEUSERS.common.changeMenuContents(this, group, content);
// console.log(contents_data, group, content);


		if (group === 'config') {

			// ---------------------------------------------
			//   URLとMetaを変更
			// ---------------------------------------------

			GAMEUSERS.common.changeUrlAndMeta(group, content);

		}


		// ----------------------------------------
		//   メニューのウェーブアイコン表示・非表示
		// ----------------------------------------

		if (agent_type === '') {

			$('[id=menu_card][data-group=' + group + ']').find('.selected_icon').addClass('element_hidden');
			$(this).find('.selected_icon').removeClass('element_hidden');

		} else {

			$('[id=menu_card][data-group=' + group + '] span').removeClass('selected');
			$(this).find('span').addClass('selected');


			// --------------------------------------------------
			//   スマホの場合、スライドメニューを閉じる
			// --------------------------------------------------

			GAMEUSERS.common.closeSlideMenu();

		}

	});



	// --------------------------------------------------
	//   ホバーでコンテンツを先読みする
	// --------------------------------------------------

	var eventHoverType = (agent_type === '') ? 'mouseenter' : 'touchstart';
	var changeMetaSwitch = (agent_type === '') ? 0 : 1;
	// console.log(changeMetaSwitch);


	// ---------------------------------------------
	//   タブ　トップ
	// ---------------------------------------------

	var bbsFirstLoad = true;

	$('#tab_bbs').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'bbs' && contents_data.initial_load.group !== undefined && bbsFirstLoad) {
			GAMEUSERS.uc.readAnnouncement(this, 1, community_no, 0, 0);
			readBbsThreadList(this, 1, 'uc', community_no, 0);
			readBbs(this, 1, 'uc', community_no, 0, 0, changeMetaSwitch);
			bbsFirstLoad = false;
		}

	});


	// ---------------------------------------------
	//   タブ　メンバー
	// ---------------------------------------------

	var memberFirstLoad = true;

	$('#tab_member').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'member' && memberFirstLoad) {
			GAMEUSERS.uc.readMember(this, 1, community_no, 'all', 0, 0, 0, 0);
			memberFirstLoad = false;
		}

	});


	// ---------------------------------------------
	//   タブ　データ
	// ---------------------------------------------

	var dataFirstLoad = true;

	$('#tab_data').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'data' && dataFirstLoad) {
			GAMEUSERS.uc.readData(this, community_no, 0, 0, changeMetaSwitch);
			dataFirstLoad = false;
		}

	});


	// ---------------------------------------------
	//   タブ　通知
	// ---------------------------------------------

	var notificationFirstLoad = true;

	$('#tab_notification').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'notification' && notificationFirstLoad) {
			GAMEUSERS.uc.readNotification(this, community_no, 0, 0, changeMetaSwitch);
			notificationFirstLoad = false;
		}

	});


	// ---------------------------------------------
	//   タブ　設定
	// ---------------------------------------------

	var configFirstLoad = true;

	$('#tab_config').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'config' && configFirstLoad) {
			GAMEUSERS.uc.readConfig(this, community_no, 0, 0, changeMetaSwitch);
			configFirstLoad = false;
		}

	});


	// ---------------------------------------------
	//   タブ　ヘルプ
	// ---------------------------------------------

	var helpFirstLoad = true;

	$('#tab_help').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'help' && helpFirstLoad) {
			GAMEUSERS.common.readHelpVer2(this, 1, 'community', 'community_about');
			helpFirstLoad = false;
		}

	});



	// --------------------------------------------------
	//   pushState　ブラウザの戻るボタンを押したときにコンテンツを読み込む
	// --------------------------------------------------

	if (window.history && window.history.pushState) {

		$(window).on('popstate', function(e) {

			if ( !e.originalEvent.state) return; // 初回アクセス時に再読み込みしてしまう対策

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

			} else if (state.function === 'readMember') {
				//console.log('pushState');
// console.log(state.page, state.communityNo, state.type);
				GAMEUSERS.uc.readMember(this, state.page, state.communityNo, state.type, 0, 1, 0, 1);

				// GAMEUSERS.common.rewriteMeta(contents_data.member_index.meta_title, contents_data.member_index.meta_keywords, contents_data.member_index.meta_description);

			}

		});

	}


});




/*
 * 告知編集フォーム読み込み
 */
GAMEUSERS.uc.modalReadFormAnnouncement = function(arguThis, communityNo){


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#modal_box #' + communityNo + '_announcement';


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

	fileData.append('type', 'uc');
	fileData.append('community_no', communityNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/form/modal_announcement.json',
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


			// テキストエリア自動リサイズ
			// $(setLocation + ' textarea').on('focus', function(){
			// 	autosize($(this));
			// });

			// フォーム初期化
			formInit(setLocation);

		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* 告知編集フォーム表示　編集用　新規の時はモーダル
*/
GAMEUSERS.uc.showAnnouncementForm = function(arguThis, communityNo, announcementNo) {


	// --------------------------------------------------
	//   2度の表示は禁止
	// --------------------------------------------------

	if ($('#announcement_box #form_box')[0]) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	if (announcementNo) {
		var setLocation = '#announcement_box';
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	if (announcementNo) fileData.append('announcement_no', announcementNo);
	if ( ! $('#announcement_box')[0]) fileData.append('create_announcement_box', true);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);



	$.ajax({

		url: uri_base + 'rest/co/show_announcement_form.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {

			if ($(setLocation + ' #announcement_content_box')[0]) {
				$(setLocation + ' #announcement_content_box').hide();
				$(setLocation + ' #announcement_content_box').after(response.code);
			}

			// テキストエリア自動リサイズ
			// $(setLocation + ' textarea').on('focus', function(){
			// 	autosize($(this));
			// });

			// フォーム初期化
			formInit(setLocation);

			// スクロール
			scrollToAnchor(setLocation, -130, 0);

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
			showAlert(setLocation + ' #alert_config_community', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* 告知編集フォーム削除
*/
GAMEUSERS.uc.removeAnnouncementForm = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#announcement_box';


	// --------------------------------------------------
	//   告知の存在チェック
	// --------------------------------------------------

	var existence = $('#submit_create_announcement').data('existence');


	// --------------------------------------------------
	//   フォーム削除、テキスト表示
	// --------------------------------------------------

	if (existence) {
		$(setLocation + ' #form_box').remove();
		$(setLocation + ' #announcement_content_box').show();
	} else {
		$(setLocation).remove();
	}

};



/**
* 告知編集
*/
GAMEUSERS.uc.saveAnnouncement = function(arguThis, communityNo, announcementNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if (announcementNo) {
		setLocation = '#announcement_box #form_box';
	} else {
		setLocation = '#modal_box #' + communityNo + '_announcement';
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("community_no", communityNo);
	if (announcementNo) fileData.append("announcement_no", announcementNo);
	fileData.append("title", $(setLocation + " #title").val());
	fileData.append("comment", $(setLocation + " #comment").val());

	// アップロードファイルが設定されていれば追加
	if ($(setLocation + " #image_1").val() !== '') {
		fileData.append("image_1", $(setLocation + " #image_1").prop("files")[0]);
	}

	if ($(setLocation + " #image_1_delete").prop('checked')) {
		fileData.append("image_1_delete", $(setLocation + " #image_1_delete").prop('checked'));
	}

	fileData.append("movie_url", $(setLocation + " #movie_url").val());


	//console.log($(setLocation + " #image_1").prop("files")[0]);
	//return;


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);



	$.ajax({

		url: uri_base + 'rest/co/save_announcement.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {
// console.log(response);
		if (response.code) {

			$('#announcement_box').replaceWith(response.code);

			// モーダルを閉じる
			if ( ! announcementNo) {
				$(setLocation).modal('hide');
			}

			// スクロール
			GAMEUSERS.common.stickyStuck = false;
			scrollToAnchor('#announcement_box', -(GAMEUSERS.common.scrollMargin), 0);

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
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* 告知削除
*/
GAMEUSERS.uc.deleteAnnouncement = function(arguThis, announcementNo) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

    if ( ! window.confirm('告知を削除しますか？')) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#announcement_box #form_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('announcement_no', announcementNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);



	$.ajax({

		url: uri_base + 'rest/co/delete_announcement.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		$('#announcement_box').replaceWith(response.code);

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};







/**
* 告知読み込み
*/
GAMEUSERS.uc.readAnnouncement = function(arguThis, page, communityNo, loading, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#announcement_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'community_announcement');
	fileData.append('page', page);
	fileData.append('community_no', communityNo);


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

			if ($(setLocation)[0]) {
				$(setLocation).replaceWith(response.code);
			} else {
				$('#content_bbs_index').prepend(response.code);
			}


			// --------------------------------------------------
			//   スクロール
			// --------------------------------------------------

			if (loading) {
				GAMEUSERS.common.stickyStuck = false;
				scrollToAnchor('#announcement_box', -(GAMEUSERS.common.scrollMargin), 0);
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

		if (loading) loadingToggle(arguThis, null);

	});

};









/**
* メンバー・プロフィール表示
*/
GAMEUSERS.uc.showMemberProfile = function(arguThis, keep) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#panel_member';


	var state = $(setLocation + ' #community_member_show_profile_button').data('state');

	if (state) {

		if (keep) {
			$(setLocation + ' [id=personal_box_explanation]').removeClass('element_hidden');
		} else {
			$(setLocation + ' [id=personal_box_explanation]').addClass('element_hidden');
			$(setLocation + ' #community_member_show_profile_button').data('state', false);
			$(setLocation + ' #community_member_show_profile_button_text').text('プロフィールコメントを表示する');
		}

	} else {

		if (keep) {
			$(setLocation + ' [id=personal_box_explanation]').addClass('element_hidden');
		} else {
			$(setLocation + ' [id=personal_box_explanation]').removeClass('element_hidden');
			$(setLocation + ' #community_member_show_profile_button').data('state', true);
			$(setLocation + ' #community_member_show_profile_button_text').text('プロフィールコメントを非表示にする');
		}

	}

};


/**
* メンバー読み込み
*/
GAMEUSERS.uc.readMember = function(arguThis, page, communityNo, type, loading, scroll, urlRewrite, metaRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#panel_member';


	// --------------------------------------------------
	//   ページ設定
	// --------------------------------------------------

	if (page) {

		if (type == 'all') {
			$(setLocation + ' #read_member_all').data('page', page);
		} else if (type == 'moderator') {
			$(setLocation + ' #read_member_moderator').data('page', page);
		} else if (type == 'administrator') {
			$(setLocation + ' #read_member_administrator').data('page', page);
		} else if (type == 'provisional') {
			$(setLocation + ' #read_member_provisional').data('page', page);
		} else if (type == 'ban') {
			$(setLocation + ' #read_member_ban').data('page', page);
		}

	} else {

		if (type == 'all') {
			page = $(setLocation + ' #read_member_all').data('page');
		} else if (type == 'moderator') {
			page = $(setLocation + ' #read_member_moderator').data('page');
		} else if (type == 'administrator') {
			page = $(setLocation + ' #read_member_administrator').data('page');
		} else if (type == 'provisional') {
			page = $(setLocation + ' #read_member_provisional').data('page');
		} else if (type == 'ban') {
			page = $(setLocation + ' #read_member_ban').data('page');
		}

	}


	// --------------------------------------------------
	//   ボタンアクティブ
	// --------------------------------------------------

	if (type == 'all') {

		$(setLocation + ' #read_member_all').attr('class', 'btn btn-default ladda-button active');
		$(setLocation + ' #read_member_moderator').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_administrator').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_ban').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_provisional').attr('class', 'btn btn-default ladda-button');

	} else if (type == 'moderator') {

		$(setLocation + ' #read_member_all').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_moderator').attr('class', 'btn btn-default ladda-button active');
		$(setLocation + ' #read_member_administrator').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_ban').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_provisional').attr('class', 'btn btn-default ladda-button');

	} else if (type == 'administrator') {

		$(setLocation + ' #read_member_all').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_moderator').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_administrator').attr('class', 'btn btn-default ladda-button active');
		$(setLocation + ' #read_member_ban').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_provisional').attr('class', 'btn btn-default ladda-button');

	} else if (type == 'ban') {

		$(setLocation + ' #read_member_all').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_moderator').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_administrator').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_ban').attr('class', 'btn btn-default ladda-button active');
		$(setLocation + ' #read_member_provisional').attr('class', 'btn btn-default ladda-button');

	} else if (type == 'provisional') {

		$(setLocation + ' #read_member_all').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_moderator').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_administrator').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_ban').attr('class', 'btn btn-default ladda-button');
		$(setLocation + ' #read_member_provisional').attr('class', 'btn btn-default ladda-button active');

	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   現在開いているページ設定
	// --------------------------------------------------

	$(setLocation).data('member-type', type);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	if ( ! $(setLocation)[0]) fileData.append('first_load', 1);
	fileData.append('api_type', 'community_member');
	fileData.append('page', page);
	fileData.append('community_no', communityNo);
	fileData.append('type', type);


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
//console.log(response.code);
		if (response.code) {

			if ($(setLocation)[0]) {
				$(setLocation + ' #member_box').html(response.code);
			} else {
				$('#content_member_index').prepend(response.code);
			}

			GAMEUSERS.uc.showMemberProfile(arguThis, true);


			// --------------------------------------------------
			//   スクロール
			// --------------------------------------------------

			if (loading) {
				GAMEUSERS.common.stickyStuck = false;
				scrollToAnchor('#content_member_index', -(GAMEUSERS.common.scrollMargin), 0);
			}


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.member_index.state = response.state;
			contents_data.member_index.url = response.url;
			contents_data.member_index.meta_title = response.meta_title;
			contents_data.member_index.meta_keywords = response.meta_keywords;
			contents_data.member_index.meta_description = response.meta_description;
// console.log(contents_data, response.state, response.url, response.meta_title, response.meta_keywords, response.meta_description, urlRewrite);


			// --------------------------------------------------
			//   URL変更
			// --------------------------------------------------

			if (urlRewrite) {
				GAMEUSERS.common.changeUrl('member', 'index');
			}


			// --------------------------------------------------
			//   Meta変更
			// --------------------------------------------------

			if (metaRewrite) {
				GAMEUSERS.common.changeMeta('member', 'index');
			}


		} else {
			$(setLocation + ' #member_box').html('');
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

};



/**
* BANメンバー読み込み
*/
// GAMEUSERS.uc.readMemberBan = function(arguThis, page, communityNo) {
//
//
// 	// --------------------------------------------------
// 	//   場所設定
// 	// --------------------------------------------------
//
// 	var setLocation = '#panel_member';
//
//
// 	// --------------------------------------------------
// 	//   ページ設定
// 	// --------------------------------------------------
//
// 	if (page) {
// 		$(setLocation + ' #read_member_ban').data('page', page);
// 	} else {
// 		page = $(setLocation + ' #read_member_ban').data('page');
// 	}
//
//
// 	// --------------------------------------------------
// 	//   ボタンアクティブ
// 	// --------------------------------------------------
//
// 	$(setLocation + ' #read_member_all').attr('class', 'btn btn-default ladda-button');
// 	$(setLocation + ' #read_member_moderator').attr('class', 'btn btn-default ladda-button');
// 	$(setLocation + ' #read_member_administrator').attr('class', 'btn btn-default ladda-button');
// 	$(setLocation + ' #read_member_ban').attr('class', 'btn btn-default ladda-button active');
// 	$(setLocation + ' #read_member_provisional').attr('class', 'btn btn-default ladda-button');
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
// 	//   現在開いているページ設定
// 	// --------------------------------------------------
//
// 	$(setLocation).data('member_type', 'ban');
//
//
// 	// --------------------------------------------------
// 	//   フォーム送信データ作成
// 	// --------------------------------------------------
//
// 	var fileData = new FormData();
//
// 	fileData.append('page', page);
// 	fileData.append('community_no', communityNo);
//
//
// 	// --------------------------------------------------
// 	//   CSRF Token
// 	// --------------------------------------------------
//
// 	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
// 	fileData.append('fuel_csrf_token', fuel_csrf_token);
//
//
// 	$.ajax({
//
// 		url: uri_base + 'rest/co/read_member_ban.json',
// 		dataType: 'json',
// 		type: 'POST',
// 		data: fileData,
// 		enctype: 'multipart/form-data',
// 		processData: false,
// 		contentType: false
//
// 	}).done(function(response) {
//
// 		if (response.code) {
// 			$(setLocation + ' #member_box').html(response.code);
// 			GAMEUSERS.uc.showMemberProfile(arguThis, true);
// 		} else {
// 			$(setLocation + ' #member_box').html('');
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
// };



/**
* 仮申請メンバー読み込み
*/
// GAMEUSERS.uc.readMemberProvisional = function(arguThis, page, communityNo) {
//
//
// 	// --------------------------------------------------
// 	//   場所設定
// 	// --------------------------------------------------
//
// 	var setLocation = '#panel_member';
//
//
// 	// --------------------------------------------------
// 	//   ページ設定
// 	// --------------------------------------------------
//
// 	if (page) {
// 		$(setLocation + ' #read_member_provisional').data('page', page);
// 	} else {
// 		page = $(setLocation + ' #read_member_provisional').data('page');
// 	}
//
//
// 	// --------------------------------------------------
// 	//   ボタンアクティブ
// 	// --------------------------------------------------
//
// 	$(setLocation + ' #read_member_all').attr('class', 'btn btn-default ladda-button');
// 	$(setLocation + ' #read_member_moderator').attr('class', 'btn btn-default ladda-button');
// 	$(setLocation + ' #read_member_administrator').attr('class', 'btn btn-default ladda-button');
// 	$(setLocation + ' #read_member_ban').attr('class', 'btn btn-default ladda-button');
// 	$(setLocation + ' #read_member_provisional').attr('class', 'btn btn-default ladda-button active');
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
// 	//   現在開いているページ設定
// 	// --------------------------------------------------
//
// 	$(setLocation).data('member_type', 'provisional');
//
//
// 	// --------------------------------------------------
// 	//   フォーム送信データ作成
// 	// --------------------------------------------------
//
// 	var fileData = new FormData();
//
// 	fileData.append('page', page);
// 	fileData.append('community_no', communityNo);
//
//
// 	// --------------------------------------------------
// 	//   CSRF Token
// 	// --------------------------------------------------
//
// 	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
// 	fileData.append('fuel_csrf_token', fuel_csrf_token);
//
//
// 	$.ajax({
//
// 		url: uri_base + 'rest/co/read_member_provisional.json',
// 		dataType: 'json',
// 		type: 'POST',
// 		data: fileData,
// 		enctype: 'multipart/form-data',
// 		processData: false,
// 		contentType: false
//
// 	}).done(function(response) {
//
// 		if (response.code) {
// 			$(setLocation + ' #member_box').html(response.code);
// 			GAMEUSERS.uc.showMemberProfile(arguThis, true);
// 		} else {
// 			$(setLocation + ' #member_box').html('');
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
// };




/**
* データ読み込み
*/
GAMEUSERS.uc.readData = function(arguThis, communityNo, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#content_data_index';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	//if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'community_data');
	fileData.append('community_no', communityNo);


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

			$(setLocation).html(response.code);



			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			if (scroll) {
				$(setLocation).imagesLoaded( function() {
					GAMEUSERS.common.stickyStuck = false;
					scrollToAnchor(setLocation, -(GAMEUSERS.common.scrollMargin), 0);
				});
			}


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.data_index.state = response.state;
			contents_data.data_index.url = response.url;
			contents_data.data_index.meta_title = response.meta_title;
			contents_data.data_index.meta_keywords = response.meta_keywords;
			contents_data.data_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) GAMEUSERS.common.changeUrlAndMeta('data', 'index');


			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			// if (urlRewrite) {
			//
			// 	var state = { "group": "data", "content": "index" };
			// 	var url = $(arguThis).attr('href');
			// 	window.history.pushState(state, null, url);
			// 	//console.log(state, url);
			//
			// 	// タブのURLを変更する
			// 	$('#tab_data a').attr('href', url);
			//
			// 	// meta書き換え
			// 	GAMEUSERS.common.rewriteMeta(response.meta_title, response.meta_keywords, response.meta_description);
			//
			//
			// 	// --------------------------------------------------
			// 	//   上までスクロールする
			// 	// --------------------------------------------------
			//
			// 	$(setLocation).imagesLoaded( function() {
			// 		GAMEUSERS.common.stickyStuck = false;
			// 		scrollToAnchor(setLocation, -(GAMEUSERS.common.scrollMargin), 0);
			// 	});
			//
			// }
			//
			//
			// // --------------------------------------------------
			// //   タブのデータ書き換え
			// // --------------------------------------------------
			//
			// $('#tab_data a').data('meta-title', response.meta_title);
			// $('#tab_data a').data('meta-keywords', response.meta_keywords);
			// $('#tab_data a').data('meta-description', response.meta_description);

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		//if (loading) loadingToggle(arguThis, null);

	});

};




/**
* データ読み込み
*/
GAMEUSERS.uc.readNotification = function(arguThis, communityNo, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#content_notification_index';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	//if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'community_notification');
	fileData.append('community_no', communityNo);


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

			$(setLocation).html(response.code);


			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			if (scroll) {
				$(setLocation).imagesLoaded( function() {
					GAMEUSERS.common.stickyStuck = false;
					scrollToAnchor(setLocation, -(GAMEUSERS.common.scrollMargin), 0);
				});
			}


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.notification_index.state = response.state;
			contents_data.notification_index.url = response.url;
			contents_data.notification_index.meta_title = response.meta_title;
			contents_data.notification_index.meta_keywords = response.meta_keywords;
			contents_data.notification_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) GAMEUSERS.common.changeUrlAndMeta('notification', 'index');


			// // --------------------------------------------------
			// //   URL書き換え
			// // --------------------------------------------------
			//
			// if (urlRewrite) {
			//
			// 	var state = { "group": "notification", "content": "index" };
			// 	var url = $(arguThis).attr('href');
			// 	window.history.pushState(state, null, url);
			// 	//console.log(state, url);
			//
			// 	// タブのURLを変更する
			// 	$('#tab_notification a').attr('href', url);
			//
			// 	// meta書き換え
			// 	GAMEUSERS.common.rewriteMeta(response.meta_title, response.meta_keywords, response.meta_description);
			//
			//
			// 	// --------------------------------------------------
			// 	//   上までスクロールする
			// 	// --------------------------------------------------
			//
			// 	$(setLocation).imagesLoaded( function() {
			// 		GAMEUSERS.common.stickyStuck = false;
			// 		scrollToAnchor(setLocation, -(GAMEUSERS.common.scrollMargin), 0);
			// 	});
			//
			// }


			// --------------------------------------------------
			//   タブのデータ書き換え
			// --------------------------------------------------

			// $('#tab_notification a').data('meta-title', response.meta_title);
			// $('#tab_notification a').data('meta-keywords', response.meta_keywords);
			// $('#tab_notification a').data('meta-description', response.meta_description);

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		//if (loading) loadingToggle(arguThis, null);

	});

};











/**
* コミュニティに参加する
*/
GAMEUSERS.uc.joinCommunity = function(arguThis, communityNo, type) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#join_community';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('select_profile', $("input[name='select_profile']:checked").val());
	fileData.append('type', type);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/join_community.json',
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
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* コミュニティから退会する
*/
GAMEUSERS.uc.withdrawCommunity = function(arguThis, communityNo, type) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	var confirmText;

	if (type == 1) {
		confirmText = 'コミュニティを退会しますか？';
	} else {
		confirmText = 'コミュニティへの参加申請を取り消しますか？';
	}

    if ( ! window.confirm(confirmText)) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#withdraw_community';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('type', type);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/withdraw_community.json',
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
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* コミュニティから退会・BANさせる　　メンバーページ
*/
GAMEUSERS.uc.withdrawBanCommunityMember = function(arguThis, communityNo, type, no, ban) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	var confirmText;

	if (ban) {
		confirm_text = 'このユーザーをBANしますか？\nBANを行った場合、このユーザーはコミュニティから退会させられ、以後、参加することができなくなります。';
	} else {
		confirm_text = 'このユーザーをコミュニティから退会させますか？';
	}

    if ( ! window.confirm(confirm_text)) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#member_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('type', type);
	fileData.append('no', no);
	if (ban) fileData.append('ban', ban);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/withdraw_ban_community_member.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_color == 'success') {
			showAlert(setLocation + ' #personal_box_' + type + '_' + no, response.alert_color, response.alert_title, response.alert_message);
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
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};


/**
* モデレーター認定・解除する　メンバーページ
*/
GAMEUSERS.uc.setModeratorMember = function(arguThis, communityNo, type, no, on_off) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	var confirmText;

	if (on_off) {
		confirmText = 'モデレーターに認定しますか？';
	} else {
		confirmText = 'モデレーターを解除しますか？';
	}

    if ( ! window.confirm(confirmText)) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#member_box';

//var test = $('#panel_member button[id^=read_member_] + #panel_member button[class=active]').attr('id');
//var test = $('#panel_member [onclick^="GAMEUSERS.uc.readMember"]').attr('id');
// var test = $('#panel_member [class^="active"]').attr('id');
//
//
// $.each($('#panel_member button[id^=read_member_]'), function() {
// 	if ($(this).hasClass('active')) {
// 		var test = $(this).attr('id');
// 		console.log(test);
// 	}
// });
//
//
// return;
	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('type', type);
	fileData.append('no', no);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/set_moderator_member.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {
//console.log(response);
		if (response.code) {


			// --------------------------------------------------
			//   現在読み込んでいるメンバーのタイプを取得する
			// --------------------------------------------------

			var readMemberType = null;

			$.each($('#panel_member button[id^=read_member_]'), function() {
				if ($(this).hasClass('active')) {
					readMemberType = $(this).attr('id');
					console.log(readMemberType);
				}
			});


			// --------------------------------------------------
			//   モデレーターが表示されている場合は削除
			//   すべてが表示されている場合は、コード入れ替え
			// --------------------------------------------------

			if (readMemberType == 'read_member_moderator') {
				$(setLocation + ' #personal_box_' + type + '_' + no).remove();
			} else {
				$(setLocation + ' #personal_box_' + type + '_' + no).replaceWith(response.code);
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
		/*
		if (response.alert_title) {
			showAlert(setLocation + " #alert", response.alert_color, response.alert_title, response.alert_message);
		}
		*/

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* メンバーを承認する　メンバーページ
*/
GAMEUSERS.uc.approvalMember = function(arguThis, communityNo, type, no, on_off) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	var confirmText;

	if (on_off) {
		confirmText = 'このユーザーをコミュニティのメンバーに加えますか？';
	} else {
		confirmText = 'このユーザーをコミュニティのメンバーに加えません。';
	}

    if ( ! window.confirm(confirmText)) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#member_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('type', type);
	fileData.append('no', no);
	if (on_off) fileData.append('on_off', on_off);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/approval_member.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.alert_color == 'success') {
			$(setLocation + ' #personal_box_' + type + '_' + no).remove();
			$('#read_member_provisional .badge').text(response.provisional_member_total);
		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------
		/*
		if (response.alert_title) {
			showAlert(setLocation + " #alert", response.alert_color, response.alert_title, response.alert_message);
		}
		*/

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* BANを解除する　メンバーページ
*/
GAMEUSERS.uc.liftBanMember = function(arguThis, communityNo, type, no) {
// function liftBanMember(arguThis, communityNo, type, no) {

	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

    if ( ! window.confirm('このユーザーのBANを解除しますか？')) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#member_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('type', type);
	fileData.append('no', no);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/lift_ban_member.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.alert_color == 'success') {
			$(setLocation + ' #personal_box_' + type + '_' + no).remove();
		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------
		/*
		if (response.alert_title) {
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}
		*/

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* 通知の一斉送信 ＆ 保存　同じ関数で処理している
*/
GAMEUSERS.uc.sendSaveNotification = function(arguThis, communityNo, mailNo, send) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	if (send) {
		if ( ! window.confirm('通知を一斉送信しますか？')) {
			return;
		}
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#content_notification_index';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('mail_no', mailNo);
	fileData.append('subject', $(setLocation + ' #mail_subject_' + mailNo).val());
	fileData.append('body', $(setLocation + ' #mail_body_' + mailNo).val());
	if (send) fileData.append('send', true);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/send_mail_all.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.alert_color == 'success') {

			// 残り送信回数変更
			if (send) {
				var mailLimit = $(setLocation + ' #mail_limit').text() - 1;
				$(setLocation + ' #mail_limit').text(mailLimit);
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
			showAlert(setLocation + ' #alert_mail_all_' + mailNo, response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


		// --------------------------------------------------
		//   ボタン送信禁止
		// --------------------------------------------------

		if ($(setLocation + ' #mail_limit').text() < 1) {
			$(setLocation + ' [id^=submit_send_mail_all_]').attr('disabled', 'disabled');
			$(setLocation + ' [id^=submit_send_mail_all_]').text('送信できません');
		}

	});

};






/**
* 設定読み込み
*/
GAMEUSERS.uc.readConfig = function(arguThis, communityNo, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#content_config_index';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	//if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'community_config');
	fileData.append('community_no', communityNo);


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

// console.log(response);
		if (response.code_profile) {

			$('#content_config_index').html(response.code_profile);
			$('#content_config_notification').html(response.code_nofitication);
			$('#content_config_basic').html(response.code_basic);
			$('#content_config_community').html(response.code_community);
			$('#content_config_authority_read').html(response.code_authority_read);
			$('#content_config_authority_operate').html(response.code_authority_operate);
			$('#content_config_delete').html(response.code_delete);


			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			if (scroll) {
				$(setLocation).imagesLoaded( function() {
					GAMEUSERS.common.stickyStuck = false;
					scrollToAnchor(setLocation, -(GAMEUSERS.common.scrollMargin), 0);
				});
			}


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.config_index.state = response.state;
			contents_data.config_index.url = response.url;
			contents_data.config_index.meta_title = response.meta_title;
			contents_data.config_index.meta_keywords = response.meta_keywords;
			contents_data.config_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) GAMEUSERS.common.changeUrlAndMeta('config', 'index');


			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			// if (urlRewrite) {
			//
			// 	var state = { "group": "config", "content": "index" };
			// 	var url = $(arguThis).attr('href');
			// 	window.history.pushState(state, null, url);
			// 	//console.log(state, url);
			//
			// 	// タブのURLを変更する
			// 	$('#tab_config a').attr('href', url);
			//
			// 	// meta書き換え
			// 	GAMEUSERS.common.rewriteMeta(response.meta_title, response.meta_keywords, response.meta_description);
			//
			//
			// 	// --------------------------------------------------
			// 	//   上までスクロールする
			// 	// --------------------------------------------------
			//
			// 	$(setLocation).imagesLoaded( function() {
			// 		GAMEUSERS.common.stickyStuck = false;
			// 		scrollToAnchor(setLocation, -(GAMEUSERS.common.scrollMargin), 0);
			// 	});
			//
			// }
			//
			//
			// // --------------------------------------------------
			// //   タブのデータ書き換え
			// // --------------------------------------------------
			//
			// $('#tab_config a').data('meta-title', response.meta_title);
			// $('#tab_config a').data('meta-keywords', response.meta_keywords);
			// $('#tab_config a').data('meta-description', response.meta_description);


			// --------------------------------------------------
			//   関連ゲーム
			// --------------------------------------------------

			GAMEUSERS.common.searchGameListNo(null);

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		//if (loading) loadingToggle(arguThis, null);

	});

};







/**
* プロフィール選択フォーム読み込み
*/
GAMEUSERS.uc.readConfigSelectProfile = function(arguThis, page, communityNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#select_profile_form_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('page', page);
	fileData.append('community_no', communityNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/read_select_profile_form.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		$(setLocation + ' #select_profile_form_content').replaceWith(response.code);

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

};



/**
* プロフィール選択保存
*/
GAMEUSERS.uc.saveConfigSelectProfile = function(arguThis, communityNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#select_profile_form_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('select_profile', $("input[name='select_profile']:checked").val());


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/save_select_profile.json',
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
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}


		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* 通知設定保存
*/
GAMEUSERS.uc.saveConfigNotification = function(arguThis, communityNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#config_notification';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	if ($(setLocation + ' #mail_all').prop('checked')) fileData.append('mail_all', 1);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/save_config_mail.json',
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
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* 基本設定保存
*/
GAMEUSERS.uc.saveConfigCommunityBasis = function(arguThis, communityNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#config_community_basis';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('community_name', $(setLocation + ' #community_name').val());
	fileData.append('community_description', $(setLocation + ' #community_description').val());
	fileData.append('community_description_mini', $(setLocation + ' #community_description_mini').val());
	fileData.append('community_id', $(setLocation + ' #community_id').val());


	// ---------------------------------------------
	//    トップ画像削除
	// ---------------------------------------------

	if ($(setLocation + ' #delete_image').prop('checked')) {
		var deleteImageId = $(setLocation + ' #delete_image').data('id');
		fileData.append('delete_image_ids', deleteImageId);
	}


	// ---------------------------------------------
	//    トップ画像アップロード
	// ---------------------------------------------

	if ($(setLocation + ' [id^=image_]').val() !== '') {
		var imageId = $(setLocation + ' [id^=image_]').attr('id');
		fileData.append( imageId, $(setLocation + ' [id^=image_]').prop('files')[0]);
	}

	// if ($(setLocation + ' [id=image_]').val() !== '') {
	// 	console.log($(setLocation + ' [id^=image_]').val());
	// 	console.log($(setLocation + ' [id^=image_]').prop('files'));
	// 	console.log($(setLocation + ' [id=image_aealreqvi4c349hj]').prop('files'));
	// 	console.log($(setLocation + ' #image_aealreqvi4c349hj').prop('files'));
	// 	console.log($(setLocation + ' [id^=image_]').attr('id'));
	// }
	//return;


	// ---------------------------------------------
	//    サムネイル画像削除
	// ---------------------------------------------

	if ($(setLocation + ' #thumbnail_delete').prop('checked')) {
		fileData.append('thumbnail_delete', $(setLocation + ' #top_image_delete').prop('checked'));
	}


	// ---------------------------------------------
	//    サムネイル画像アップロード
	// ---------------------------------------------

	if ($(setLocation + ' #thumbnail').val() !== '') {
		fileData.append( 'thumbnail', $(setLocation + ' #thumbnail').prop('files')[0]);
	}


	// ---------------------------------------------
	//    関連ゲーム
	// ---------------------------------------------

	var gameList = $(setLocation + ' #game_list').data('game-list');
	if (gameList) {
		fileData.append('game_list', gameList);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/save_config_community.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {
// console.log(response);
		// ----- 成功した場合、ページ更新 -----

		if (response.alert_color == 'success') {
			location.href = uri_base + 'uc/' + $(setLocation + ' #community_id').val() + '/config/basic';
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
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};




/**
* コミュニティ設定保存
*/
GAMEUSERS.uc.saveConfigCommunity = function(arguThis, communityNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#config_community';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);
	fileData.append('online_limit', $(setLocation + ' #online_limit').val());
	fileData.append('participation_type', $(setLocation + ' input[name="participation_type"]:checked').val());

	if ($(setLocation + ' input[name="open"]:checked').val() == 1) {
		fileData.append('open', 1);
	}

	// 匿名化
	if ($(setLocation + ' #anonymity').prop('checked')) {
		fileData.append('anonymity', $(setLocation + ' #anonymity').prop('checked'));
	}



	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/save_config_community.json',
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
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};





/**
* 閲覧権限保存
*/
GAMEUSERS.uc.saveConfigAuthorityRead = function(arguThis, communityNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#config_authority_read';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);

	// 告知
	if ($(setLocation + ' #read_announcement_1').prop('checked')) {
		fileData.append('read_announcement_1', $(setLocation + ' #read_announcement_1').prop('checked'));
	}
	if ($(setLocation + ' #read_announcement_2').prop('checked')) {
		fileData.append('read_announcement_2', $(setLocation + ' #read_announcement_2').prop('checked'));
	}
	if ($(setLocation + ' #read_announcement_3').prop('checked')) {
		fileData.append('read_announcement_3', $(setLocation + ' #read_announcement_3').prop('checked'));
	}

	// 掲示板
	if ($(setLocation + ' #read_bbs_1').prop('checked')) {
		fileData.append('read_bbs_1', $(setLocation + ' #read_bbs_1').prop('checked'));
	}
	if ($(setLocation + ' #read_bbs_2').prop('checked')) {
		fileData.append('read_bbs_2', $(setLocation + ' #read_bbs_2').prop('checked'));
	}
	if ($(setLocation + ' #read_bbs_3').prop('checked')) {
		fileData.append('read_bbs_3', $(setLocation + ' #read_bbs_3').prop('checked'));
	}

	// メンバー
	if ($(setLocation + ' #read_member_1').prop('checked')) {
		fileData.append('read_member_1', $(setLocation + ' #read_member_1').prop('checked'));
	}
	if ($(setLocation + ' #read_member_2').prop('checked')) {
		fileData.append('read_member_2', $(setLocation + ' #read_member_2').prop('checked'));
	}
	if ($(setLocation + ' #read_member_3').prop('checked')) {
		fileData.append('read_member_3', $(setLocation + ' #read_member_3').prop('checked'));
	}

	// コミュニティ情報　その他の情報
	if ($(setLocation + ' #read_additional_info_1').prop('checked')) {
		fileData.append('read_additional_info_1', $(setLocation + ' #read_additional_info_1').prop('checked'));
	}
	if ($(setLocation + ' #read_additional_info_2').prop('checked')) {
		fileData.append('read_additional_info_2', $(setLocation + ' #read_additional_info_2').prop('checked'));
	}
	if ($(setLocation + ' #read_additional_info_3').prop('checked')) {
		fileData.append('read_additional_info_3', $(setLocation + ' #read_additional_info_3').prop('checked'));
	}



	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/save_config_read.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {

		//alert(response.test);

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};


/**
* 操作権限保存
*/
GAMEUSERS.uc.saveConfigAuthorityOperate = function(arguThis, communityNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#config_authority_operate';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('community_no', communityNo);

	// 告知投稿
	if ($(setLocation + ' #operate_announcement_3').prop('checked')) {
		fileData.append('operate_announcement_3', $(setLocation + ' #operate_announcement_3').prop('checked'));
	}

	// 掲示板　コメント書き込み
	if ($(setLocation + ' #operate_bbs_comment_1').prop('checked')) {
		fileData.append('operate_bbs_comment_1', $(setLocation + ' #operate_bbs_comment_1').prop('checked'));
	}
	if ($(setLocation + ' #operate_bbs_comment_2').prop('checked')) {
		fileData.append('operate_bbs_comment_2', $(setLocation + ' #operate_bbs_comment_2').prop('checked'));
	}
	if ($(setLocation + ' #operate_bbs_comment_3').prop('checked')) {
		fileData.append('operate_bbs_comment_3', $(setLocation + ' #operate_bbs_comment_3').prop('checked'));
	}

	// 掲示板　コメント削除
	if ($(setLocation + ' #operate_bbs_delete_3').prop('checked')) {
		fileData.append('operate_bbs_delete_3', $(setLocation + ' #operate_bbs_delete_3').prop('checked'));
	}

	// 掲示板　スレッド作成
	if ($(setLocation + ' #operate_bbs_thread_1').prop('checked')) {
		fileData.append('operate_bbs_thread_1', $(setLocation + ' #operate_bbs_thread_1').prop('checked'));
	}
	if ($(setLocation + ' #operate_bbs_thread_2').prop('checked')) {
		fileData.append('operate_bbs_thread_2', $(setLocation + ' #operate_bbs_thread_2').prop('checked'));
	}
	if ($(setLocation + ' #operate_bbs_thread_3').prop('checked')) {
		fileData.append('operate_bbs_thread_3', $(setLocation + ' #operate_bbs_thread_3').prop('checked'));
	}

	// メンバー承認・退会
	if ($(setLocation + ' #operate_member_3').prop('checked')) {
		fileData.append('operate_member_3', $(setLocation + ' #operate_member_3').prop('checked'));
	}

	// メール一斉送信
	if ($(setLocation + ' #operate_send_all_mail_3').prop('checked')) {
		fileData.append('operate_send_all_mail_3', $(setLocation + ' #operate_send_all_mail_3').prop('checked'));
	}

	// コミュニティ設定
	if ($(setLocation + ' #operate_config_community_3').prop('checked')) {
		fileData.append('operate_config_community_3', $(setLocation + ' #operate_config_community_3').prop('checked'));
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/save_config_operate.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {

		//alert(response.test);
		// console.log(contents_data);

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};


/**
* ユーザーコミュニティ削除
*/
GAMEUSERS.uc.deleteCommunity = function(arguThis, communityNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#config_delete_community';


	// --------------------------------------------------
	//   データ取得
	// --------------------------------------------------

	var verification = $(setLocation + ' #delete_community_verification').val();
	//alert(verification);

	// --------------------------------------------------
	//   誤動作防止　確認キーワード
	// --------------------------------------------------

	if (verification != 'delete') {
		showAlert(setLocation + ' #alert', 'warning', 'エラー', '確認キーワードが違っています。');
		return;
	}


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

    if ( ! window.confirm('コミュニティを削除してもよろしいですか？')) {
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

	fileData.append('community_no', communityNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/co/delete_user_community.json',
		dataType: 'json',
		type: 'POST',
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

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
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
// function deleteGameListNo(arguThis, gameNo) {
//
// 	var contentId = getContentId(arguThis);
// 	var setLocation = "#" + contentId + " #tab_config_" + contentId + " #config_community_basis";
//
// 	var gameList = $(setLocation + " #game_list").data("game-list");
//
// 	$.each(gameList, function(i, val) {
// 		if (val == gameNo) {
// 			gameList.splice(i,1);
// 			//alert(val);
// 		}
// 	});
//
// 	$(setLocation + " #game_list #game_no_" + gameNo).remove();
//
// }
