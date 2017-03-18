/*jshint scripturl:true*/


/**
* BBSの初期処理
*/
function formInit(setLocation) {


	// --------------------------------------------------
	//   BBS コメントする　読み込み時に隠す
	// --------------------------------------------------

	$(setLocation + " [id=image]").hide();
	$(setLocation + " [id=movie]").hide();

	// 匿名にするチェックボックスがチェックされている場合、匿名プロフィールを表示する
	if ($(setLocation + ' #anonymity').prop('checked')) {
		$(setLocation + " #personal_box").hide();
		$(setLocation + " #personal_box_anonymity").show();
		//console.log('チェック');
	} else {
		$(setLocation + " #personal_box").show();
		$(setLocation + " #personal_box_anonymity").hide();
		//console.log('チェックされてない');
	}


	// --------------------------------------------------
	//   BBS コメントする　画像アップロードボタン
	// --------------------------------------------------

	$(setLocation + " [id=image_button]").click(function () {
		var index = $(setLocation + " [id=image_button]").index(this);
		$(setLocation + " [id=image]").eq(index).toggle();
		$(setLocation + " [id=movie]").eq(index).hide();
	});


	// --------------------------------------------------
	//   BBS コメントする　動画投稿ボタン
	// --------------------------------------------------

	$(setLocation + " [id=movie_button]").click(function () {
		var index = $(setLocation + " [id=movie_button]").index(this);
		$(setLocation + " [id=image]").eq(index).hide();
		$(setLocation + " [id=movie]").eq(index).toggle();
	});


	// --------------------------------------------------
	//   BBS コメントする　匿名にするチェックボックス
	// --------------------------------------------------

	$(setLocation + " [id=anonymity]").click(function() {

		var index = $(setLocation + " [id=anonymity]").index(this);

		if ($(this).is(':checked')) {
			$(setLocation + " [id=personal_box]").eq(index).hide();
			$(setLocation + " [id=personal_box_anonymity]").eq(index).show();
		} else {
			$(setLocation + " [id=personal_box]").eq(index).show();
			$(setLocation + " [id=personal_box_anonymity]").eq(index).hide();
		}

	});


	// テキストエリア自動リサイズ
	// $(setLocation + " textarea").on('focus', function(){
	// 	autosize($(this));
	// });

}




/**
* BBSスレッド一覧読み込み
*/
function readBbsThreadList(arguThis, page, type, no, loading) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_thread_list_box";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("page", page);
	fileData.append("type", type);
	fileData.append("no", no);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/read_bbs_thread_list.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		$(setLocation).replaceWith(response.code);

		// スクロール
		//scrollToAnchor(setLocation, scrollBlankSize, 0);

		// 上までスクロールする
		// GAMEUSERS.common.stickyStuck = false;
		// scrollToAnchor(setLocation, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);

		// 読み込んだコンテンツの高さを再取得
		// if (agent_type == 'smartphone') {
		// 	swiperBbs.update(true);
		// }

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
* BBS 個別に読み込み
*/
function readBbsIndividual(arguThis, type, no, bbsId, pageComment, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#bbs_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(null, '#link_button_' + bbsId);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	fileData.append('type', type);
	fileData.append('bbs_id', bbsId);
	fileData.append('page_comment', pageComment);

	if (type === 'gc') {
		fileData.append('api_type', 'bbs_read_individual_gc');
		fileData.append('game_no', no);
	} else {
		fileData.append('api_type', 'bbs_read_individual_uc');
		fileData.append('community_no', no);
	}


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
		if (response.code) {


			// --------------------------------------------------
			//   コード反映
			// --------------------------------------------------

			$(setLocation).replaceWith(response.code);


			// --------------------------------------------------
			//   BBSコメントフォーム初期化
			// --------------------------------------------------

			formInit(setLocation);


			// --------------------------------------------------
			//   コメントや返信の場所までスクロールする
			// --------------------------------------------------

			if (scroll) {
				$(setLocation).imagesLoaded( function() {
					GAMEUSERS.common.stickyStuck = false;
					scrollToAnchor('[data-anchor="' + bbsId + '"]', -(GAMEUSERS.common.scrollMargin), 0);
				});
			}


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.bbs_index.state = response.state;
			contents_data.bbs_index.url = response.url;
			contents_data.bbs_index.meta_title = response.meta_title;
			contents_data.bbs_index.meta_keywords = response.meta_keywords;
			contents_data.bbs_index.meta_description = response.meta_description;
// console.log(response.state, response.url);

			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) GAMEUSERS.common.changeUrlAndMeta('bbs', 'index');


			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			// if (urlRewrite) {
			//
			// 	var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbsIndividual', 'type': type, 'no': no, 'bbsId': bbsId, 'pageComment': pageComment };
			// 	var url = $(arguThis).attr('href');
			// 	window.history.pushState(state, null, url);
			//
			// 	// ヘッダーのURLを変更する
			// 	$('#tab_bbs a').attr('href', url);
			//
			// 	// meta書き換え
			// 	GAMEUSERS.common.rewriteMeta(response.meta_title, response.meta_keywords, response.meta_description);
			//
			// }
			//
			//
			// // --------------------------------------------------
			// //   タブのデータ書き換え
			// // --------------------------------------------------
			//
			// $('#tab_bbs a').data('meta-title', response.meta_title);
			// $('#tab_bbs a').data('meta-keywords', response.meta_keywords);
			// $('#tab_bbs a').data('meta-description', response.meta_description);

		}


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		if (loading) loadingToggle(null, '#link_button_' + bbsId);

	});

}




/**
* BBS読み込み
*/
function readBbs(arguThis, page, type, no, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#bbs_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("type", type);
	fileData.append("no", no);
	fileData.append("page", page);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/read_bbs.json',
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
			//   コード反映
			// --------------------------------------------------

			if ($(setLocation)[0]) {
				$(setLocation).replaceWith(response.code);
			} else if ($('#announcement_box')[0]) {
				$('#announcement_box').after(response.code);
			} else {
				$('#content_bbs_index').prepend(response.code);
			}


			// --------------------------------------------------
			//   BBSコメントフォーム初期化
			// --------------------------------------------------

			formInit(setLocation);


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

			contents_data.bbs_index.state = response.state;
			contents_data.bbs_index.url = response.url;
			contents_data.bbs_index.meta_title = response.meta_title;
			contents_data.bbs_index.meta_keywords = response.meta_keywords;
			contents_data.bbs_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) GAMEUSERS.common.changeUrlAndMeta('bbs', 'index');


			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			// if (urlRewrite) {
			//
			// 	var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbs', 'page': page, 'type': type, 'no': no };
			// 	var url = $(arguThis).attr('href');
			//
			// 	window.history.pushState(state, null, url);
			//
			// 	//console.log(state, url);
			//
			// 	// タブのURLを変更する
			// 	$('#tab_bbs a').attr('href', url);
			//
			// 	// meta書き換え
			// 	GAMEUSERS.common.rewriteMeta(response.meta_title, response.meta_keywords, response.meta_description);
			//
			// }
			//
			//
			// // --------------------------------------------------
			// //   タブのデータ書き換え
			// // --------------------------------------------------
			//
			// $('#tab_bbs a').data('meta-title', response.meta_title);
			// $('#tab_bbs a').data('meta-keywords', response.meta_keywords);
			// $('#tab_bbs a').data('meta-description', response.meta_description);


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


// function createImgObj(img_elm) {
//     num = img_elm.length - 1; // 最後の画像のindexをもっておく
//     alert(num);
//
//     var imgObj;
//
//     img_elm.each(function(i, elm) {
//         imgObj[i] = new Image();
//         imgObj[i].src = elm.src;
//     });
//
// }




/**
* BBS コメント読み込み
*/
function readBbsComment(arguThis, page, type, bbsThreadNo, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_thread_" + bbsThreadNo;


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("page", page);
	fileData.append("type", type);
	fileData.append("bbs_thread_no", bbsThreadNo);
	if (GAMEUSERS.common.urlParam3 && GAMEUSERS.common.urlParam3.length === 16) fileData.append('individual', 1);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/read_bbs_comment.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {
//console.log(response);
		if (response.code) {

			// コード反映
			$(setLocation + " #bbs_comment_box").replaceWith(response.code);


			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			if (scroll) {
				$(setLocation + ' #bbs_comment_box').imagesLoaded( function() {
					GAMEUSERS.common.stickyStuck = false;
					scrollToAnchor(setLocation + ' #bbs_comment_box', -(GAMEUSERS.common.scrollMargin), 0);
				});
			}


			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			if (urlRewrite) {

				var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbsComment', 'page': page, 'type': type, 'bbsThreadNo': bbsThreadNo };
				var url = $(arguThis).attr('href');
				//window.history.pushState(state, null, url);
				//console.log(state, url);

				if (url === '#') {
					window.history.pushState(state, null, location.href);
				} else {
					window.history.pushState(state, null, url);
				}

				// タブのURLを変更する
				$('#tab_bbs a').attr('href', url);

				// meta書き換え
				GAMEUSERS.common.rewriteMeta(response.meta_title, response.meta_keywords, response.meta_description);

			}


			// --------------------------------------------------
			//   タブのデータ書き換え
			// --------------------------------------------------

			$('#tab_bbs a').data('meta-title', response.meta_title);
			$('#tab_bbs a').data('meta-keywords', response.meta_keywords);
			$('#tab_bbs a').data('meta-description', response.meta_description);

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
* BBS 返信読み込み
*/
function readBbsReply(arguThis, page, type, bbsCommentNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_comment_" + bbsCommentNo + " #bbs_reply_box";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("page", page);
	fileData.append("type", type);
	fileData.append("bbs_comment_no", bbsCommentNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/read_bbs_reply.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {

			// コード反映
			$(setLocation).html(response.code);


			$(setLocation).imagesLoaded( function() {

				// スクロール
				//scrollToAnchor(setLocation, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
				GAMEUSERS.common.stickyStuck = false;
				scrollToAnchor(setLocation, -(GAMEUSERS.common.scrollMargin), 0);

			});

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
* BBSスレッド編集フォーム表示
*/
function showEditBbsThreadForm(arguThis, type, bbsThreadNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_thread_" + bbsThreadNo + " #bbs_thread_box";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("type", type);
	fileData.append("bbs_thread_no", bbsThreadNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/show_edit_bbs_thread_form.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		if (response.code) {
			$(setLocation + " #bbs_thread_content").hide();
			$(setLocation).append(response.code);
		}

		// テキストエリア自動リサイズ
		// $("textarea").on('focus', function(){
		// 	autosize($(this));
		// });

		// フォーム初期化
		formInit(setLocation);

		// スクロール
		scrollToAnchor('#bbs_thread_' + bbsThreadNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {


		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert(setLocation + " #alert_config_community", response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


	});

}



/**
* BBSコメント編集フォーム表示
*/
function showEditBbsCommentForm(arguThis, type, bbsCommentNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_comment_" + bbsCommentNo + " #bbs_comment_only";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("type", type);
	fileData.append("bbs_comment_no", bbsCommentNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/show_edit_bbs_comment_form.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		if (response.code) {
			$(setLocation + " #bbs_comment_content").hide();
			$(setLocation + " #bbs_comment_content").after(response.code);
		}

		// テキストエリア自動リサイズ
		// $("textarea").on('focus', function(){
		// 	autosize($(this));
		// });

		// フォーム初期化
		formInit(setLocation);

		// スクロール
		scrollToAnchor('#bbs_comment_' + bbsCommentNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

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

}



/**
* BBS返信編集フォーム表示
*/
function showEditBbsReplyForm(arguThis, type, bbsReplyNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_reply_" + bbsReplyNo;


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("type", type);
	fileData.append("bbs_reply_no", bbsReplyNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/show_edit_bbs_reply_form.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		if (response.code) {
			$(setLocation + " #bbs_reply_content").hide();
			$(setLocation).append(response.code);
		}

		// テキストエリア自動リサイズ
		// $("textarea").on('focus', function(){
		// 	autosize($(this));
		// });

		// フォーム初期化
		formInit(setLocation);

		// スクロール
		scrollToAnchor(setLocation, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

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

}




/**
* BBS返信投稿フォーム表示
*/
function showWriteBbsReplyForm(arguThis, type, bbsCommentNo, bbsReplyNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_comment_" + bbsCommentNo;


	// --------------------------------------------------
	//   フォームを表示している場合は再クリックで削除
	// --------------------------------------------------

	if ($(setLocation + " #bbs_reply_box .bbs_reply_enclosure_form")[0]) {
		removeWriteBbsReplyForm(bbsCommentNo);
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

	fileData.append("type", type);
	fileData.append("bbs_comment_no", bbsCommentNo);
	if (bbsReplyNo) fileData.append("bbs_reply_no", bbsReplyNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/show_write_bbs_reply_form.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		// --------------------------------------------------
		//   コード反映
		// --------------------------------------------------

		if (response.code) {

			if ( ! bbsReplyNo) {
				$(setLocation + " #bbs_reply_box").prepend(response.code);
			} else {
				$(setLocation + " #bbs_reply_box #bbs_reply_" + bbsReplyNo).after(response.code);
			}

			// テキストエリア自動リサイズ
			// $("textarea").on('focus', function(){
			// 	autosize($(this));
			// });

			// フォーム初期化
			formInit(setLocation);

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
			showAlert(setLocation + " #alert", response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


	});

}




/**
* BBSスレッド編集フォーム削除
*/
function removeEditBbsThreadForm(arguThis, type, no, bbsThreadNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_thread_" + bbsThreadNo + " #bbs_thread_box";


	// --------------------------------------------------
	//   フォーム削除、テキスト表示
	// --------------------------------------------------

	$(setLocation + " #form_box").remove();
	$(setLocation + " #bbs_thread_content").show();


	// スクロール
	scrollToAnchor('#bbs_thread_' + bbsThreadNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


}



/**
* BBSコメント編集フォーム削除
*/
function removeEditBbsCommentForm(arguThis, type, bbsCommentNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_comment_" + bbsCommentNo + " #bbs_comment_only";


	// --------------------------------------------------
	//   フォーム削除、テキスト表示
	// --------------------------------------------------

	$(setLocation + " #form_box").remove();
	$(setLocation + " #bbs_comment_content").show();


}



/**
* BBS返信編集フォーム削除
*/
function removeEditBbsReplyForm(arguThis, type, bbsReplyNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_reply_" + bbsReplyNo;


	// --------------------------------------------------
	//   フォーム削除、テキスト表示
	// --------------------------------------------------

	$(setLocation + " #form_box").remove();
	$(setLocation + " #bbs_reply_content").show();


}



/**
* BBS返信投稿フォーム削除
*/
function removeWriteBbsReplyForm(arguThis, type, bbsCommentNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_comment_" + bbsCommentNo + " #bbs_reply_box .bbs_reply_enclosure_form";


	// --------------------------------------------------
	//   フォーム削除
	// --------------------------------------------------

	$(setLocation).remove();


}












/**
* BBSスレッド保存
*/
function saveBbsThread(arguThis, type, no, bbsThreadNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if (bbsThreadNo) {
		setLocation = $(arguThis).closest('#bbs_thread_' + bbsThreadNo);
	} else {
		setLocation = $(arguThis).closest('#bbs_create_thread');
	}


	// --------------------------------------------------
	//   利用規約に同意しない場合は処理停止
	// --------------------------------------------------

	if ($(setLocation).find('#user_terms')[0]) {
		var check = $(setLocation).find('#user_terms').prop('checked');
		if ( ! check) {
			alert('利用規約に同意する必要があります');
			return;
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

	fileData.append("type", type);
	fileData.append("no", no);

	if (bbsThreadNo) {
		fileData.append("bbs_thread_no", bbsThreadNo);
	}

	fileData.append("handle_name", $(setLocation).find("#handle_name").val());
	fileData.append("title", $(setLocation).find("#title").val());
	fileData.append("comment", $(setLocation).find("#comment").val());

	if ($(setLocation).find("#anonymity").prop('checked')) {
		fileData.append("anonymity", $(setLocation).find("#anonymity").prop('checked'));
	}

	// アップロードファイルが設定されていれば追加
	if ($(setLocation).find("#image_1").val() !== '') {
		fileData.append( "image_1", $(setLocation).find("#image_1").prop("files")[0]);
	}

	if ($(setLocation).find("#image_1_delete").prop('checked')) {
		fileData.append("image_1_delete", $(setLocation).find("#image_1_delete").prop('checked'));
	}

	fileData.append("movie_url", $(setLocation).find("#movie_url").val());


	// console.log('title = ' + $(setLocation).find("#title").val());
	// console.log(setLocation);
	// return;


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/save_bbs_thread.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

// console.log(response);
		if (response.code_bbs_thread_list && response.code_bbs.code) {

			// コード反映　BBSスレッド一覧
			if ($('#bbs_thread_list_box')[0]) {
				$('#bbs_thread_list_box').replaceWith(response.code_bbs_thread_list);
			} else {
				$('#collapse_bbs_thread_list_box').after(response.code_bbs_thread_list);
			}

			// コード反映　BBS
			if ($('#bbs_box')[0]) {
				$('#bbs_box').replaceWith(response.code_bbs.code);
			} else {
				$('#bbs_thread_list_box').after(response.code_bbs.code);
			}


			// フォーム処理
			if ( ! bbsThreadNo) {

				var formId = '#modal_box #bbs_create_thread';

				// モーダル非表示
				$(formId).modal('hide');

				// フォーム削除　内容をリセットする
				$(formId).on('hidden.bs.modal', function (e) {
					$(formId).remove();
				});

			}


			// BBSコメントフォーム初期化
			formInit('#bbs_write_form');


// console.log(response.bbs_id);
			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			$(setLocation).imagesLoaded( function() {
				GAMEUSERS.common.stickyStuck = false;
				scrollToAnchor('[data-anchor="' + response.bbs_id + '"]', -(GAMEUSERS.common.scrollMargin), 0);
			});


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.bbs_index.state = response.state;
			contents_data.bbs_index.url = response.url;
			contents_data.bbs_index.meta_title = response.meta_title;
			contents_data.bbs_index.meta_keywords = response.meta_keywords;
			contents_data.bbs_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			GAMEUSERS.common.changeUrlAndMeta('bbs', 'index');


			// スクロール
			// $(setLocation).imagesLoaded( function() {
			// 	scrollToAnchor('#bbs_box', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
			// });
			//
			//
			// // --------------------------------------------------
			// //   URL書き換え
			// // --------------------------------------------------
			//
			// var stateNo;
			// if (type === 'gc') {
			// 	stateNo = game_no;
			// }
			//
			// var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbs', 'page': 1, 'type': type, 'no': stateNo };
			// var urlReplaced = uri_current.replace(uri_base, '');
			// var urlSplitArr = urlReplaced.split('/');
			// var url = uri_base + urlSplitArr[0] + '/' + urlSplitArr[1];
			// window.history.pushState(state, null, url);
			// //console.log(state, url);
			//
			// // タブのURLを変更する
			// $('#tab_bbs a').attr('href', url);
			//
			// // meta書き換え
			// GAMEUSERS.common.rewriteMeta(response.code_bbs.meta_title, response.code_bbs.meta_keywords, response.code_bbs.meta_description);
			//
			//
			// // --------------------------------------------------
			// //   タブのデータ書き換え
			// // --------------------------------------------------
			//
			// $('#tab_bbs a').data('meta-title', response.code_bbs.meta_title);
			// $('#tab_bbs a').data('meta-keywords', response.code_bbs.meta_keywords);
			// $('#tab_bbs a').data('meta-description', response.code_bbs.meta_description);


			// --------------------------------------------------
			//   Google Analytics イベント送信　ローカル、編集時には処理しない
			// --------------------------------------------------

			if (uri_base.indexOf('gameusers.org') !== -1 && ! bbsThreadNo) {
				if (type === 'gc') {
					ga('send', 'event',  'Game', 'BBS', 'Thread');
				} else {
					ga('send', 'event',  'Community', 'BBS', 'Thread');
				}
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
			showAlert($(setLocation).find('#alert'), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


	});

}




/**
* BBSコメント投稿・更新
*/
function saveBbsComment(arguThis, type, bbsThreadNo, bbsCommentNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if (bbsThreadNo && ! bbsCommentNo) {
		setLocation = $(arguThis).parents('#bbs_write_form');
	} else {
		setLocation = $(arguThis).parents('#form_box');
	}


	// --------------------------------------------------
	//   利用規約に同意しない場合は処理停止
	// --------------------------------------------------

	if ($(setLocation).find('#user_terms')[0]) {
		var check = $(setLocation).find('#user_terms').prop('checked');
		if ( ! check) {
			alert('利用規約に同意する必要があります');
			return;
		}
	}
	// console.log('contentId = ' + contentId);
	// console.log('bbsThreadNo = ' + bbsThreadNo);
	// console.log('bbsCommentNo = ' + bbsCommentNo);
	// console.log('setLocation = ' + setLocation);
	// console.log('#comment = ' + $(setLocation).find("#comment").val());
	// console.log('$(setLocation + " #movie_url").val() = ' + $(setLocation + " #movie_url").val());
	// console.log('$("#" + contentId)[0] = ' + $('#' + contentId)[0]);
	// console.log('$("#" + content_id)[0] = ' + $('#' + content_id)[0]);
	// console.log('$(".content_box")[0] = ' + $('.content_box')[0]);
	// console.log('$(setLocation)[0] = ' + $(setLocation)[0]);



	//console.log('$("#comment").val() = ' + $(arguThis).parents('#bbs_write_form').find("#comment").val());

	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("type", type);

	if (bbsThreadNo) {
		fileData.append("bbs_thread_no", bbsThreadNo);
	}

	if (bbsCommentNo) {
		fileData.append("bbs_comment_no", bbsCommentNo);
	}

	fileData.append("handle_name", $(setLocation).find("#handle_name").val());
	fileData.append("comment", $(setLocation).find("#comment").val());

	if ($(setLocation).find("#anonymity").prop('checked')) {
		fileData.append("anonymity", $(setLocation).find("#anonymity").prop('checked'));
	}

	// アップロードファイルが設定されていれば追加
	if ($(setLocation).find("#image_1").val() !== '') {
		fileData.append( "image_1", $(setLocation).find("#image_1").prop("files")[0]);
	}

	if ($(setLocation).find("#image_1_delete").prop('checked')) {
		fileData.append("image_1_delete", $(setLocation).find("#image_1_delete").prop('checked'));
	}

	fileData.append("movie_url", $(setLocation).find("#movie_url").val());


	// console.log('handle_name = ' + $(setLocation).find("#handle_name").val());
	// console.log('comment = ' + $(setLocation).find("#comment").val());
	// console.log('anonymity = ' + $(setLocation).find("#anonymity").prop('checked'));
	// console.log('image_1 = ' + $(setLocation).find("#image_1").prop("files"));
	// console.log('image_1_delete = ' + $(setLocation).find("#image_1_delete").prop('checked'));
	// console.log('movie_url = ' + $(setLocation).find("#movie_url").val());
//
	// console.log('top = ' + $(arguThis).parents('#' + contentId).find("#bbs_thread_" + bbsThreadNo).offset().top);

	//return;


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/save_bbs_comment.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

// console.log(response);
		if (response.code_bbs.code) {

			// コード反映
			$("#bbs_box").replaceWith(response.code_bbs.code);

			// BBSコメントフォーム初期化
			formInit("#bbs_write_form");

			// スレッド一覧　コード反映
			$("#bbs_thread_list_box").replaceWith(response.code_bbs_thread_list);



			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			$('#bbs_box').imagesLoaded( function() {
				scrollToAnchor('[data-anchor="' + response.bbs_id + '"]', -(GAMEUSERS.common.scrollMargin), 0);
			});


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			// contents_data.bbs_index.state = response.state;
			// contents_data.bbs_index.url = response.url;
			// contents_data.bbs_index.meta_title = response.meta_title;
			// contents_data.bbs_index.meta_keywords = response.meta_keywords;
			// contents_data.bbs_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			//if (urlRewrite) GAMEUSERS.common.changeUrlAndMeta('bbs', 'index');


			// $(setLocation).imagesLoaded( function() {
			//
			// 	// スクロール
			// 	if (bbsThreadNo && ! bbsCommentNo) {
			//
			// 	} else {
			// 		scrollToAnchor('#bbs_comment_' + bbsCommentNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
			// 	}
			//
			// });
			//
			//
			// // --------------------------------------------------
			// //   URL書き換え
			// // --------------------------------------------------
			//
			// var stateNo;
			// if (type === 'gc') {
			// 	stateNo = game_no;
			// }
			//
			// var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbs', 'page': 1, 'type': type, 'no': stateNo };
			// var urlReplaced = uri_current.replace(uri_base, '');
			// var urlSplitArr = urlReplaced.split('/');
			// var url = uri_base + urlSplitArr[0] + '/' + urlSplitArr[1];
			// window.history.pushState(state, null, url);
			// //console.log(state, url);
			//
			// // タブのURLを変更する
			// $('#tab_bbs a').attr('href', url);
			//
			// // meta書き換え
			// GAMEUSERS.common.rewriteMeta(response.code_bbs.meta_title, response.code_bbs.meta_keywords, response.code_bbs.meta_description);
			//
			//
			// // --------------------------------------------------
			// //   タブのデータ書き換え
			// // --------------------------------------------------
			//
			// $('#tab_bbs a').data('meta-title', response.code_bbs.meta_title);
			// $('#tab_bbs a').data('meta-keywords', response.code_bbs.meta_keywords);
			// $('#tab_bbs a').data('meta-description', response.code_bbs.meta_description);


			// --------------------------------------------------
			//   Google Analytics イベント送信　ローカル、編集時には処理しない
			// --------------------------------------------------

			if (uri_base.indexOf('gameusers.org') !== -1 && ! bbsCommentNo) {
				if (type === 'gc') {
					ga('send', 'event',  'Game', 'BBS', 'Comment');
				} else {
					ga('send', 'event',  'Community', 'BBS', 'Comment');
				}
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
* BBS返信投稿・更新
*/
function saveBbsReply(arguThis, type, bbsCommentNo, bbsReplyNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if (bbsCommentNo && ! bbsReplyNo) {
		setLocation = $(arguThis).parents(".bbs_reply_enclosure_form");
	} else {
		setLocation = $(arguThis).parents("#form_box");
	}


	// --------------------------------------------------
	//   利用規約に同意しない場合は処理停止
	// --------------------------------------------------

	if ($(setLocation).find('#user_terms')[0]) {
		var check = $(setLocation).find('#user_terms').prop('checked');
		if ( ! check) {
			alert('利用規約に同意する必要があります');
			return;
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

	fileData.append("type", type);

	if (bbsCommentNo) {
		fileData.append("bbs_comment_no", bbsCommentNo);
	}

	if (bbsReplyNo) {
		fileData.append("bbs_reply_no", bbsReplyNo);
	}



	fileData.append("handle_name", $(setLocation).find("#handle_name").val());
	fileData.append("comment", $(setLocation).find("#comment").val());

	if ($(setLocation).find("#anonymity").prop('checked')) {
		fileData.append("anonymity", $(setLocation).find("#anonymity").prop('checked'));
	}

	// アップロードファイルが設定されていれば追加
	if ($(setLocation).find("#image_1").val() !== '') {
		fileData.append( "image_1", $(setLocation).find("#image_1").prop("files")[0]);
	}

	if ($(setLocation).find("#image_1_delete").prop('checked')) {
		fileData.append("image_1_delete", $(setLocation).find("#image_1_delete").prop('checked'));
	}

	fileData.append("movie_url", $(setLocation).find("#movie_url").val());



	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + 'rest/bbs/save_bbs_reply.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		if (response.code_bbs.code) {

			// コード反映
			$("#bbs_box").replaceWith(response.code_bbs.code);

			// BBSコメントフォーム初期化
			formInit("#bbs_write_form");

			// スレッド一覧　コード反映
			$("#bbs_thread_list_box").replaceWith(response.code_bbs_thread_list);



			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			$('#bbs_box').imagesLoaded( function() {
				scrollToAnchor('[data-anchor="' + response.bbs_id + '"]', -(GAMEUSERS.common.scrollMargin), 0);
			});

			// $(setLocation).imagesLoaded( function() {
			//
			// 	// スクロール
			// 	if ( ! bbsReplyNo) {
			// 		scrollToAnchor('#bbs_comment_' + bbsCommentNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
			// 	} else {
			// 		scrollToAnchor('#bbs_reply_' + bbsReplyNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
			// 	}
			//
			// });


			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			// var stateNo;
			// if (type === 'gc') {
			// 	stateNo = game_no;
			// }
			//
			// var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbs', 'page': 1, 'type': type, 'no': stateNo };
			// var urlReplaced = uri_current.replace(uri_base, '');
			// var urlSplitArr = urlReplaced.split('/');
			// var url = uri_base + urlSplitArr[0] + '/' + urlSplitArr[1];
			// window.history.pushState(state, null, url);
			//
			// // タブのURLを変更する
			// $('#tab_bbs a').attr('href', url);
			//
			// // meta書き換え
			// GAMEUSERS.common.rewriteMeta(response.code_bbs.meta_title, response.code_bbs.meta_keywords, response.code_bbs.meta_description);
			//
			//
			// // --------------------------------------------------
			// //   タブのデータ書き換え
			// // --------------------------------------------------
			//
			// $('#tab_bbs a').data('meta-title', response.code_bbs.meta_title);
			// $('#tab_bbs a').data('meta-keywords', response.code_bbs.meta_keywords);
			// $('#tab_bbs a').data('meta-description', response.code_bbs.meta_description);


			// --------------------------------------------------
			//   Google Analytics イベント送信　ローカル、編集時には処理しない
			// --------------------------------------------------

			if (uri_base.indexOf('gameusers.org') !== -1 && ! bbsReplyNo) {
				if (type === 'gc') {
					ga('send', 'event',  'Game', 'BBS', 'Reply');
				} else {
					ga('send', 'event',  'Community', 'BBS', 'Reply');
				}
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
* BBSスレッド削除
*/
function deleteBbsThread(arguThis, type, bbsThreadNo) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

    if ( ! window.confirm('スレッドを削除しますか？')) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#bbs_thread_" + bbsThreadNo + " #bbs_thread_box";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("type", type);
	fileData.append("bbs_thread_no", bbsThreadNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/delete_bbs_thread.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		// コード反映　BBSスレッド一覧
		if ($("#bbs_thread_list_box")[0]) {
			$("#bbs_thread_list_box").replaceWith(response.code_bbs_thread_list);
		} else {
			$("#bbs_create_thread_box").after(response.code_bbs_thread_list);
		}

		// コード反映　BBS
		if ($("#bbs_box")[0]) {
			$("#bbs_box").replaceWith(response.code_bbs.code);
		} else {
			$("#bbs_thread_list_box").after(response.code_bbs.code);
		}

		// BBSコメントフォーム初期化
		formInit("#bbs_write_form");


		// スクロール
		$(setLocation).imagesLoaded( function() {
			scrollToAnchor('#bbs_box', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
		});


		// --------------------------------------------------
		//   URL書き換え
		// --------------------------------------------------

		var stateNo;
		if (type === 'gc') {
			stateNo = game_no;
		}

		var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbs', 'page': 1, 'type': type, 'no': stateNo };
		var urlReplaced = uri_current.replace(uri_base, '');
		var urlSplitArr = urlReplaced.split('/');
		var url = uri_base + urlSplitArr[0] + '/' + urlSplitArr[1];
		window.history.pushState(state, null, url);

		// タブのURLを変更する
		$('#tab_bbs a').attr('href', url);

		// meta書き換え
		GAMEUSERS.common.rewriteMeta(response.code_bbs.meta_title, response.code_bbs.meta_keywords, response.code_bbs.meta_description);


		// --------------------------------------------------
		//   タブのデータ書き換え
		// --------------------------------------------------

		$('#tab_bbs a').data('meta-title', response.code_bbs.meta_title);
		$('#tab_bbs a').data('meta-keywords', response.code_bbs.meta_keywords);
		$('#tab_bbs a').data('meta-description', response.code_bbs.meta_description);


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

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

}




/**
* BBSコメント削除
*/
function deleteBbsComment(arguThis, type, bbsCommentNo) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

    if ( ! window.confirm('コメントを削除しますか？')) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if ($('#bbs_comment_' + bbsCommentNo + ' #bbs_comment_only #form_box')[0]) {
		setLocation = '#bbs_comment_' + bbsCommentNo + ' #bbs_comment_only #form_box';
	} else {
		setLocation = '#bbs_comment_' + bbsCommentNo + ' #bbs_comment_content #control_comment_menu_main';
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('type', type);
	fileData.append('bbs_comment_no', bbsCommentNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/delete_bbs_comment.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		if (response.code_bbs.code) {

			// コード反映
			$('#bbs_thread_list_box').replaceWith(response.code_bbs_thread_list);
			$('#bbs_box').replaceWith(response.code_bbs.code);

			// BBSコメントフォーム初期化
			formInit('#bbs_write_form');

			// スクロール
			$(setLocation).imagesLoaded( function() {
				scrollToAnchor('#bbs_box', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
			});


			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			var stateNo;
			if (type === 'gc') {
				stateNo = game_no;
			}

			var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbs', 'page': 1, 'type': type, 'no': stateNo };
			var urlReplaced = uri_current.replace(uri_base, '');
			var urlSplitArr = urlReplaced.split('/');
			var url = uri_base + urlSplitArr[0] + '/' + urlSplitArr[1];
			window.history.pushState(state, null, url);

			// タブのURLを変更する
			$('#tab_bbs a').attr('href', url);

			// meta書き換え
			GAMEUSERS.common.rewriteMeta(response.code_bbs.meta_title, response.code_bbs.meta_keywords, response.code_bbs.meta_description);


			// --------------------------------------------------
			//   タブのデータ書き換え
			// --------------------------------------------------

			$('#tab_bbs a').data('meta-title', response.code_bbs.meta_title);
			$('#tab_bbs a').data('meta-keywords', response.code_bbs.meta_keywords);
			$('#tab_bbs a').data('meta-description', response.code_bbs.meta_description);

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

}



/**
* BBS返信削除
*/
function deleteBbsReply(arguThis, type, bbsReplyNo) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

    if ( ! window.confirm('返信を削除しますか？')) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if ($('#bbs_reply_' + bbsReplyNo + ' #form_box')[0]) {
		setLocation = '#bbs_reply_' + bbsReplyNo;
	} else {
		setLocation = '#bbs_reply_' + bbsReplyNo + ' #bbs_reply_menu';
	}

	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('type', type);
	fileData.append('bbs_reply_no', bbsReplyNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/bbs/delete_bbs_reply.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		if (response.code_bbs.code) {

			// コード反映
			$('#bbs_thread_list_box').replaceWith(response.code_bbs_thread_list);
			$('#bbs_box').replaceWith(response.code_bbs.code);

			// BBSコメントフォーム初期化
			formInit('#bbs_write_form');

			// スクロール
			$(setLocation).imagesLoaded( function() {
				scrollToAnchor('#bbs_box', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
			});



			// --------------------------------------------------
			//   URL書き換え
			// --------------------------------------------------

			var stateNo;
			if (type === 'gc') {
				stateNo = game_no;
			}

			var state = { 'group': 'bbs', 'content': 'index', 'function': 'readBbs', 'page': 1, 'type': type, 'no': stateNo };
			var urlReplaced = uri_current.replace(uri_base, '');
			var urlSplitArr = urlReplaced.split('/');
			var url = uri_base + urlSplitArr[0] + '/' + urlSplitArr[1];
			window.history.pushState(state, null, url);

			// タブのURLを変更する
			$('#tab_bbs a').attr('href', url);

			// meta書き換え
			GAMEUSERS.common.rewriteMeta(response.code_bbs.meta_title, response.code_bbs.meta_keywords, response.code_bbs.meta_description);


			// --------------------------------------------------
			//   タブのデータ書き換え
			// --------------------------------------------------

			$('#tab_bbs a').data('meta-title', response.code_bbs.meta_title);
			$('#tab_bbs a').data('meta-keywords', response.code_bbs.meta_keywords);
			$('#tab_bbs a').data('meta-description', response.code_bbs.meta_description);

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

}






/**
* モーダル読み込み　BBSスレッド作成
*/
function modalReadFormBbsCreateThread(arguThis, no, type) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#modal_box #bbs_create_thread';


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

	if (type == 'gc') {
		fileData.append('game_no', no);
	} else {
		fileData.append('community_no', no);
	}

	fileData.append('type', type);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/form/modal_read_bbs_create_thread.json',
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

			//console.log('response.code = ' + response.code);

			$('#modal_box').append(response.code);
			$(setLocation).modal();

			// BBSフォーム初期化
			formInit(setLocation);

		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);


	});

}
