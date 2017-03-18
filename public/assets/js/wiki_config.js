// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.wiki_config');




$(function() {

	// --------------------------------------------------
	//   コンテンツ読み込み　旧デザインの場合のみ
	// --------------------------------------------------

	if ($("#tab_wiki")[0]) {

		// Wiki　作成フォーム読み込み
		//GAMEUSERS.wiki_config.readWikiCreate();

		// Wiki　編集読み込み
		//GAMEUSERS.wiki_config.readWikiList(null, 1, true);

	}

});







/**
* Wiki一覧読み込み　もう使ってない？
*/
GAMEUSERS.wiki_config.readWikiList = function(arguThis, page, edit) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation;

	if ( ! edit) {
		setLocation = $('#change_contents_wiki_list');
	} else {
		setLocation = $('#change_contents_wiki_edit');
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	//console.log($(setLocation).data('wiki_no'));
	//return;

	fileData.append('page', page);
	if (edit) fileData.append('edit', 1);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/wiki/read_wiki_list.json",
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
			//console.log(response);
			$(setLocation).html(response.code);

			scrollToAnchor('#tab_wiki', -(GAMEUSERS.common.headerBsTabMarginSize), 0);

			// 関連ゲーム
			if (edit) {
				GAMEUSERS.common.searchGameListNo(setLocation.find('#scrollable-dropdown-menu .typeahead'));
			}

			// if (edit) {
				// $('#button_change_contents_wiki_edit').show();
			// }

		} else {

			// --------------------------------------------------
			//   編集するWikiがない場合は、編集ボタンを隠す
			// --------------------------------------------------

			if (edit) {
				$('#button_change_contents_wiki_edit').hide();
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

};






/**
* Wiki編集読み込み　2017/2/9 新型
*/
GAMEUSERS.wiki_config.readWikiListEdit = function(arguThis, page) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $('#content_wiki_edit');


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'content_wiki_edit');
	fileData.append('page', page);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



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

			$(setLocation).html(response.code);


			// --------------------------------------------------
			//   スクロール
			// --------------------------------------------------

			scrollToAnchor(setLocation, -(GAMEUSERS.common.headerBsTabMarginSize), 0);


			// --------------------------------------------------
			//   関連ゲーム
			// --------------------------------------------------

			GAMEUSERS.common.searchGameListNo(setLocation.find('#scrollable-dropdown-menu .typeahead'));


		} else {

			// --------------------------------------------------
			//   編集するWikiがない場合は、編集ボタンを隠す
			// --------------------------------------------------

			// if (edit) {
			// 	$('#button_change_contents_wiki_edit').hide();
			// }
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

};






/**
* Wiki作成フォーム読み込み　もう使ってない？
*/
GAMEUSERS.wiki_config.readWikiCreate = function() {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $('#change_contents_wiki_create');
	//console.log('aaa');


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

		url: uri_base + "rest/wiki/read_wiki_create.json",
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

			// 関連ゲーム
			GAMEUSERS.common.searchGameListNo(setLocation.find('#scrollable-dropdown-menu .typeahead'));

		}


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

	});

};




/**
* Wiki保存
*/
GAMEUSERS.wiki_config.saveWiki = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#wiki_edit_box");


	// --------------------------------------------------
	//   パスワードの確認
	// --------------------------------------------------

	var wiki_password = $(setLocation).find('#wiki_password').val();
	var wiki_password_confirm = $(setLocation).find('#wiki_password_confirm').val();

	if (wiki_password !== wiki_password_confirm) {
		showAlert($(setLocation).find('#alert'), 'warning', 'エラー', 'パスワードが再入力したパスワードと違っています。');
		return;
	}
	//console.log('aaa');

	// --------------------------------------------------
	//   利用規約の確認
	// --------------------------------------------------

	if ($(setLocation).find('#user_terms_approval')[0]) {
		if ( ! $(setLocation).find('#user_terms_approval').prop('checked')) {
			showAlert($(setLocation).find('#alert'), 'warning', 'エラー', 'Wiki使用ルールに同意する必要があります。');
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



	//console.log($(setLocation).find("#default option:selected").val());
	//return;


	var wikiNo = $(setLocation).data('wiki_no');
	if (wikiNo) fileData.append('wiki_no', wikiNo);

	fileData.append('wiki_id', $(setLocation).find('#wiki_id').val());
	fileData.append('wiki_name', $(setLocation).find('#wiki_name').val());
	fileData.append('wiki_comment', $(setLocation).find('#wiki_comment').val());
	fileData.append('wiki_password', $(setLocation).find('#wiki_password').val());
	fileData.append('game_list', $(setLocation).find('#game_list').data('game-list'));


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/wiki/save_wiki.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 保存に成功した場合、一覧を読み込み直す -----

		if (response.alert_color === 'success') {

			// 新規作成の場合はリロードする
			if ( ! wikiNo) {
				window.location.reload();
			} else {
				// GAMEUSERS.wiki_config.readWikiList(arguThis, 1, false);
				// GAMEUSERS.wiki_config.readWikiList(arguThis, 1, true);
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

};



/**
* Wiki保存
*/
GAMEUSERS.wiki_config.saveWikiAdvertisement = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#wiki_edit_advertisement_box");


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	// console.log($(setLocation).find('#amazon_slide').prop('checked'));
	// return;


	var wikiNo = $(setLocation).data('wiki_no');
	if (wikiNo) fileData.append('wiki_no', wikiNo);

	fileData.append('wiki_1', $(setLocation).find('#wiki_1').val());
	fileData.append('wiki_2', $(setLocation).find('#wiki_2').val());

	if ($(setLocation).find('#amazon_slide').prop('checked')) {
		fileData.append('amazon_slide', $(setLocation).find('#amazon_slide').prop('checked'));
	}



	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/wiki/save_wiki_advertisement.json",
		dataType: "json",
		type: "POST",
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
			showAlert($(setLocation).find('#alert'), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* Wikiを削除する
*/
GAMEUSERS.wiki_config.deleteWiki = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#wiki_edit_box");


	// --------------------------------------------------
	//   誤動作防止　確認キーワード
	// --------------------------------------------------

	var verification = window.prompt('Wikiを削除する場合は delete と入力してください。', '');

	if (verification != 'delete') {
		showAlert($(setLocation).find('#alert'), 'warning', 'エラー', '確認キーワードが違っています。');
		return;
	}


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	if ( ! window.confirm('本当に削除してもよろしいですか？一度、削除すると元には戻せません。')) {
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

	var wikiNo = $(setLocation).data('wiki_no');
	fileData.append('wiki_no', wikiNo);


	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	if(typeof appMode != "undefined") {
		fileData.append("app_mode", true);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "rest/wiki/delete_wiki.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {


		// --------------------------------------------------
		//   成功した場合、ページ更新
		// --------------------------------------------------

		if (response.alert_color == 'success') {
			window.location.reload();
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

};
