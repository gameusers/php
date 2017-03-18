
// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.index');



$(function() {


	// --------------------------------------------------
	//  サムネイル広告を隠す　ウィンドウのサイズが1125未満の場合
	// --------------------------------------------------

	GAMEUSERS.common.thumbnailAdHideShow();

	$(window).on('resize', function () {
		GAMEUSERS.common.thumbnailAdHideShow();
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
	//  Masonry & AOS & Sticky（サイドメニュー固定）を作動させる
	// --------------------------------------------------

	$('#menu_feed, #content_feed_index').not('.card_thumbnail').imagesLoaded( function() {

		if (agent_type !== 'smartphone') {

			GAMEUSERS.common.masonryFeed = $('#feed_card_box').masonry({
				itemSelector: '#feed_card',
				columnWidth: 157
			});

		}


		// --------------------------------------------------
		//  AOS　カードやソーシャルボタンの動き　PCのみ
		// --------------------------------------------------

		if (agent_type !== 'smartphone') {

			AOS.init();


			// --------------------------------------------------
			//  ウィンドウをリサイズしたときは、AOSを使えなくする
			//  Masonryの移動後、処理させるとごちゃごちゃするため
			// --------------------------------------------------

			var resizeOnce = true;

			$(window).on('resize', function () {
				if (resizeOnce) {
					AOS.init({
						disable: true
					});

					resizeOnce = false;
				}
			});

		}


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
	//   メニュー　クリック
	// --------------------------------------------------

	$('[id=menu_card]').on('click', function(e) {


		// ----------------------------------------
		//   タイプ取得
		// ----------------------------------------

		var group = $(this).data('group');
		var content = $(this).data('content');


		// ----------------------------------------
		//   フィード
		// ----------------------------------------

		// if (group === 'feed' && (content === 'index' || content === 'bbs' || content === 'recruitment' || content === 'community')) {
		if (group === 'feed' && content === 'index') {
//console.log(group, content);
			var feed = $(this).data('feed');
			var page = $(this).data('page');
			// console.log('page = ' + page);

			GAMEUSERS.common.readFeed(this, page, feed, 0, 1, 1);
			GAMEUSERS.common.changeMenuContents(this, group, content);


		// ----------------------------------------
		//   Wiki編集
		// ----------------------------------------

		} else if (group === 'wiki' && content === 'edit' &&  ! $('#content_wiki_edit').html()) {

			// 3秒間表示
			PNotify.prototype.options.delay = 3000;

			var pnotifyTitle = '注意';
			var pnotifyText = '編集できるWikiがありません。';

			new PNotify({
				styling: 'bootstrap3',
				title: pnotifyTitle,
				text: pnotifyText,
				// type: 'success'
			});

			return;


		// ----------------------------------------
		//  その他
		// ----------------------------------------

		} else {
//console.log('aaa');
			GAMEUSERS.common.changeMenuContents(this, group, content);


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

		}


		// --------------------------------------------------
		//   スマホの場合、スライドメニューを閉じる
		// --------------------------------------------------

		GAMEUSERS.common.closeSlideMenu();


	});



	// --------------------------------------------------
	//   メニュー　ホバーで先読み
	// --------------------------------------------------

	var eventHoverType = (agent_type === '') ? 'mouseenter' : 'touchstart';

	$('[data-look-ahead=true]').on(eventHoverType, function(e) {


		// ----------------------------------------
		//   タイプ取得
		// ----------------------------------------

		var group = $(this).data('group');
		var content = $(this).data('content');

		// console.log('group = ' + group);
		// console.log('content = ' + content);


		// ----------------------------------------
		//   先読み
		// ----------------------------------------

		GAMEUSERS.index.readLookAhead(group, content);


	});



	// --------------------------------------------------
	//   ホバーでコンテンツを先読みする　デザインVer.2用
	// --------------------------------------------------

	// ---------------------------------------------
	//   タブ　ヘルプ
	// ---------------------------------------------

	var helpFirstLoad = true;

	$('#tab_help').on(eventHoverType, function () {

		if (contents_data.initial_load.group !== 'help' && helpFirstLoad) {
			GAMEUSERS.common.readHelpVer2(this, 1, 'top', 'top_about');
			helpFirstLoad = false;
		}

	});



	// --------------------------------------------------
	//   コンテンツ　読み込み
	// --------------------------------------------------

	// フィード一覧
	if (contents_data.initial_load.group !== 'feed' || contents_data.initial_load.content !== 'index') {
		GAMEUSERS.common.readFeed(this, 1, 'all', 0, 0, 0);
	}

	// コミュニティ一覧
	if (contents_data.initial_load.group !== 'community' || contents_data.initial_load.content !== 'index') {
		GAMEUSERS.common.readCommunityList(this, 1, 'all', null, 0, 0, 0);
	}

	// Wiki一覧
	if (contents_data.initial_load.group !== 'wiki' || contents_data.initial_load.content !== 'index') {
		GAMEUSERS.common.readWikiList(this, 1, 0, 0, 0);
	}






	// --------------------------------------------------
	//  文字省略　…　カードのタイトル　スマホ・タブレット用
	// --------------------------------------------------

	if (agent_type !== '') {
		$('.card_s .top .right .title h2').trunk8({
			lines: 2
		});

		$(window).resize(function (event) {
			$('.card_s .top .right .title h2').trunk8({
				lines: 2
			});
		});
	}



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
			console.log(state.group, state.content, state.function);


			// ---------------------------------------------
			//   コンテンツ読み込み
			// ---------------------------------------------

			if (state.function === 'readFeed') {

				GAMEUSERS.common.readFeed(this, state.page, state.type, 0, 1, 0);

			} else if (state.function === 'readCommunityList') {

				GAMEUSERS.common.readCommunityList(this, state.page, state.type, null, 0, 1, 0);

			} else if (state.function === 'readWikiList') {

				GAMEUSERS.common.readWikiList(this, state.page, 0, 1, 0);

			}

		});

	}




	// --------------------------------------------------
	//   Javascrpitのリンク　デザインVer.2用
	//   右クリックのコンテキストメニュー
	// --------------------------------------------------

	$.contextMenu({
		selector: '#jslink',
		callback: function(key, options) {
			var target = '_target';
			var address = $(this).data('jslink');

			window.open(address, target);
		},
		items: {
			"new": {name: "新しいタブで開く"}
		}
	});



	// --------------------------------------------------
	//   開発検索　auto complete
	// --------------------------------------------------

	if (contents_data.initial_load.group === 'feed' && contents_data.initial_load.content !== 'register_game') {
		GAMEUSERS.index.searchDeveloperAutoComplete();
	}


	// --------------------------------------------------
	//   ゲーム登録
	// --------------------------------------------------

	// ----------------------------------------
	//   ゲーム検索　エンターキー
	// ----------------------------------------

	$(document).on('keypress', '#form_register_game #keyword', function(e) {

		if (e.which == 13) {
			GAMEUSERS.index.searchGameData(this, 1);
			return false;
		}

	});


	// ----------------------------------------
	//   開発検索　エンターキー
	// ----------------------------------------

	$(document).on('keypress', '#form_register_developer #keyword', function(e) {

		if (e.which == 13) {
			GAMEUSERS.index.searchDeveloperForm(this, 1);
			return false;
		}

	});


	// ----------------------------------------
	//   ジャンル検索　エンターキー
	// ----------------------------------------

	$(document).on('keypress', '#form_register_genre #keyword', function(e) {

		if (e.which == 13) {
			GAMEUSERS.index.searchGenreForm(this, 1);
			return false;
		}

	});


});





/**
* コンテンツ　ホバーで先読み
*/
GAMEUSERS.index.readLookAheadArr = [];

GAMEUSERS.index.readLookAhead = function(group, content) {


	// --------------------------------------------------
	//   すでに読み込まれているコンテンツは読み込まない
	// --------------------------------------------------

	var id = group + '_' + content;
	if (GAMEUSERS.index.readLookAheadArr.indexOf(id) !== -1) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#content_' + id;


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'content_' + id);
	if (id === 'wiki_edit') fileData.append('page', 1);


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

// console.log(response, id);

		if (response.code) {

			// --------------------------------------------------
			//   コード反映
			// --------------------------------------------------

			$(setLocation).html(response.code);


			if (id === 'community_create') {


				// --------------------------------------------------
				//   関連ゲーム検索 auto complete
				// --------------------------------------------------

				GAMEUSERS.common.searchGameListNo('#config_community_basis .typeahead');


				// --------------------------------------------------
				//   content_data書き換え
				// --------------------------------------------------

				contents_data.community_create.state = response.state;
				contents_data.community_create.url = response.url;
				contents_data.community_create.meta_title = response.meta_title;
				contents_data.community_create.meta_keywords = response.meta_keywords;
				contents_data.community_create.meta_description = response.meta_description;


			} else if (id === 'wiki_create') {

// console.log(response.state);
				// --------------------------------------------------
				//   content_data書き換え
				// --------------------------------------------------

				contents_data.wiki_create.state = response.state;
				contents_data.wiki_create.url = response.url;
				contents_data.wiki_create.meta_title = response.meta_title;
				contents_data.wiki_create.meta_keywords = response.meta_keywords;
				contents_data.wiki_create.meta_description = response.meta_description;
// console.log(contents_data);

			} else if (id === 'wiki_edit') {


				// --------------------------------------------------
				//   関連ゲーム検索 auto complete
				// --------------------------------------------------

				GAMEUSERS.common.searchGameListNo('#wiki_edit_box .typeahead');


				// --------------------------------------------------
				//   content_data書き換え
				// --------------------------------------------------

				contents_data.wiki_edit.state = response.state;
				contents_data.wiki_edit.url = response.url;
				contents_data.wiki_edit.meta_title = response.meta_title;
				contents_data.wiki_edit.meta_keywords = response.meta_keywords;
				contents_data.wiki_edit.meta_description = response.meta_description;


			} else if (id === 'feed_register_game') {


				// --------------------------------------------------
				//   開発検索　auto complete
				// --------------------------------------------------

				GAMEUSERS.index.searchDeveloperAutoComplete();


				// --------------------------------------------------
				//   content_data書き換え
				// --------------------------------------------------

				contents_data.feed_register_game.state = response.state;
				contents_data.feed_register_game.url = response.url;
				contents_data.feed_register_game.meta_title = response.meta_title;
				contents_data.feed_register_game.meta_keywords = response.meta_keywords;
				contents_data.feed_register_game.meta_description = response.meta_description;

			}


			// ---------------------------------------------
			//   URLとMetaを変更　スマホ・タブレットの時のみ
			// ---------------------------------------------

			if (agent_type !=='') GAMEUSERS.common.changeUrlAndMeta(group, content);


		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		if (GAMEUSERS.index.readLookAheadArr.indexOf(id) === -1) {
			GAMEUSERS.index.readLookAheadArr.push(id);
		}

		//console.log(GAMEUSERS.index.readLookAheadArr);

	});

};





/**
* コミュニティ作成
*/
//function createUserCommunity(arguThis) {
GAMEUSERS.index.createCommunity = function(arguThis) {


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

	fileData.append('community_name', $(setLocation + ' #community_name').val());
	fileData.append('community_description', $(setLocation + ' #community_description').val());
	fileData.append('community_description_mini', $(setLocation + ' #community_description_mini').val());
	fileData.append('community_id', $(setLocation + ' #community_id').val());
	fileData.append('game_list', $(setLocation + ' #game_list').data('game-list'));

// console.log($(setLocation + ' #community_name').val());
// return;

	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/index/create_user_community.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// コミュニティができたら移動する
		if (response.alert_color == 'success') {
			location.href = uri_base + 'uc/' + $(setLocation + ' #community_id').val();
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
* ゲーム検索
*/
GAMEUSERS.index.searchGameData = function(arguThis, page) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	//var setLocation = '#content_feed_register_game';
	var setLocation = '#form_register_game';



	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	var selfId = $(arguThis).attr('id');

	if (selfId == 'keyword') {
		loadingToggle(null, setLocation + ' #search_game_data');
	} else {
		loadingToggle(arguThis, null);
	}


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'register_game');
	fileData.append('page', page);
	fileData.append('keyword', $(setLocation + ' #keyword').val());
	//console.log($(setLocation + ' #keyword').val());


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/api/common.json',
		//url: uri_base + 'rest/index/search_game_data.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {
			$(setLocation + ' #game_data_box').html(response.code);
		} else {
			$(setLocation + ' #game_data_box').html('');
		}

		// スクロール
		scrollToAnchor(setLocation + ' #search_game', -70, 0);

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

		if (selfId == 'keyword') {
			loadingToggle(null, setLocation + ' #search_game_data');
		} else {
			loadingToggle(arguThis, null);
		}

	});

};



/**
* ゲーム読み込み
*/
GAMEUSERS.index.readGameData = function(arguThis, gameNo, historyNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '[id=content_feed_register_game] #game_no_' + gameNo;


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'register_game');
	fileData.append('page', 1);
	fileData.append('game_no', gameNo);
	if (historyNo !== null) fileData.append('history_no', historyNo);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		//url: uri_base + 'rest/index/read_game_data.json',
		url: uri_base + 'rest/api/common.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {
			$(setLocation).replaceWith(response.code);

			// イベント追加
			// addEventSearchGameData(contentId);
			// addEventrReadGameData(contentId);
			// addEventrSaveGameData(contentId);
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
* ゲーム登録保存
*/
GAMEUSERS.index.saveGameData = function(arguThis, gameNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = null;

	if (gameNo) {
		setLocation = '#content_feed_register_game #game_no_' + gameNo;
	} else {
		setLocation = '#content_feed_register_game #game_no_new';
	}



	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	if (gameNo) fileData.append('game_no', gameNo);

	fileData.append('name', $(setLocation + ' #name').val());
	fileData.append('subtitle', $(setLocation + ' #subtitle').val());



	// --------------------------------------------------
	//   管理者の場合
	// --------------------------------------------------

	if ($(setLocation + ' #id')[0]) {


		// --------------------------------------------------
		//   基本情報
		// --------------------------------------------------

		var i;

		for (i=0; i < 20; i++) {
			if ($(setLocation + ' #similarity_' + i).val()) fileData.append('similarity_' + i, $(setLocation + ' #similarity_' + i).val());
		}

		fileData.append('id', $(setLocation + ' #id').val());
		fileData.append('kana', $(setLocation + ' #kana').val());
		fileData.append('twitter_hashtag_ja', $(setLocation + ' #twitter_hashtag_ja').val());


		// --------------------------------------------------
		//   サムネイル
		// --------------------------------------------------

		if ($(setLocation + ' #thumbnail').val() !== '') {
			fileData.append('thumbnail', $(setLocation + ' #thumbnail').prop('files')[0]);
			//console.log($(setLocation + ' #thumbnail').prop('files')[0]);
		}

		if ($(setLocation + ' #thumbnail_delete').prop('checked')) {
			fileData.append('thumbnail_delete', $(setLocation + ' #thumbnail_delete').prop('checked'));
		}

		// if (gameNo) {
		//
		// 	if ($(setLocation + ' #on_off_advertisement').prop('checked')) fileData.append('on_off_advertisement', $(setLocation + ' #on_off_advertisement').prop('checked'));
		//
		// 	fileData.append('blurb', $(setLocation + ' #blurb').val());
		// 	fileData.append('android_link', $(setLocation + ' #android_link').val());
		// 	fileData.append('android_image', $(setLocation + ' #android_image').val());
		// 	fileData.append('ios_link', $(setLocation + ' #ios_link').val());
		// 	fileData.append('ios_image', $(setLocation + ' #ios_image').val());
		//
		// }

		if (gameNo) {


			// --------------------------------------------------
			//   追加情報
			// --------------------------------------------------

			for (i=1; i <= 5; i++) {

				if ($(setLocation + ' #hardware_' + i).val()) {
					fileData.append('hardware_' + i, $(setLocation + ' #hardware_' + i).val());
					//console.log('hardware_' + i + ' = ' + $(setLocation + ' #hardware_' + i).val());
				}

				if ($(setLocation + ' #release_date_' + i).val()) {
					fileData.append('release_date_' + i, $(setLocation + ' #release_date_' + i).val());
					//console.log('release_date_' + i + ' = ' + $(setLocation + ' #release_date_' + i).val());
				}

				if ($(setLocation + ' #genre_' + i).val()) {
					fileData.append('genre_' + i, $(setLocation + ' #genre_' + i).val());
					//console.log('genre_' + i + ' = ' + $(setLocation + ' #genre_' + i).val());
				}

			}

			if ($(setLocation + ' #players_max').val() !== 0) {
				fileData.append('players_max', $(setLocation + ' #players_max').val());
			}

			fileData.append('developer', $(setLocation + ' #developer_list').data('list'));


			// --------------------------------------------------
			//   ヒーローイメージ
			// --------------------------------------------------

			if ($(setLocation + ' .upload [id^=image_]')[0]) {
				$.each($(setLocation + ' .upload [id^=image_]'), function(i, val) {
					fileData.append($(this).attr('id'), $(this).prop('files')[0]);
				});
			}

			if ($(setLocation + ' .delete [id^=image_]')[0]) {

				var deleteImageIdsArr = [];

				$.each($(setLocation + ' .delete [id^=image_delete_]'), function(i, val) {
					if ($(this).prop('checked')) {
						deleteImageIdsArr.push($(this).data('id'));
					}
				});

				var deleteImageIds = deleteImageIdsArr.join(',');
				if (deleteImageIds) fileData.append('delete_image_ids', deleteImageIds);

				//console.log(deleteImageIds);
			}


			// --------------------------------------------------
			//   リンク
			// --------------------------------------------------

			for (i=1; i <= 20; i++) {

				if ($(setLocation + ' #type_' + i).val()) {

					fileData.append('link_type_' + i, $(setLocation + ' #type_' + i).val());
					//console.log('type_' + i + ' = ' + $(setLocation + ' #type_' + i).val());

					fileData.append('link_country_' + i, $(setLocation + ' #country_' + i).val());
					//console.log('country_' + i + ' = ' + $(setLocation + ' #country_' + i).val());

					fileData.append('link_name_' + i, $(setLocation + ' #name_' + i).val());
					//console.log('name_' + i + ' = ' + $(setLocation + ' #name_' + i).val());

					fileData.append('link_url_' + i, $(setLocation + ' #url_' + i).val());
					//console.log('url_' + i + ' = ' + $(setLocation + ' #url_' + i).val());
				}

			}

		}


		// --------------------------------------------------
		//   交流スレッド作成
		// --------------------------------------------------

		if ($(setLocation + ' #first_bbs_thread').prop('checked')) {
			fileData.append('first_bbs_thread', $(setLocation + ' #first_bbs_thread').prop('checked'));
		}

	}


	// console.log(gameNo);
	// console.log('name = ' + $(setLocation + ' #name').val());
	// console.log('subtitle = ' + $(setLocation + ' #subtitle').val());
	// console.log('players_max = ' + $(setLocation + ' #players_max').val());
	// console.log('developer = ' + $(setLocation + ' #developer_list').data('list'));
	//
	// console.log('id = ' + $(setLocation + " #id").val());
	// console.log('kana = ' + $(setLocation + " #kana").val());
	// console.log('twitter_hashtag_ja = ' + $(setLocation + " #twitter_hashtag_ja").val());
	//
	// return;



	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/index/save_game_data.json',
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
//console.log(response);
		if (response.code) {

			$(setLocation).replaceWith(response.code);

			// スクロール
			if (gameNo) scrollToAnchor('#game_no_' + gameNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


			// --------------------------------------------------
			//   管理者の場合
			// --------------------------------------------------

			if ($(setLocation + ' #id')[0]) {
				GAMEUSERS.index.searchDeveloperAutoComplete();
			}


			// PNotify 通知表示
			if ( ! gameNo) {
				new PNotify({
					styling: 'bootstrap3',
					title: 'ゲーム登録',
					text: '完了しました。',
					type: 'success'
				});
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
			showAlert(setLocation + ' #alert', response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};



/**
* 開発検索　auto complete
*/
GAMEUSERS.index.searchDeveloperAutoComplete = function() {
// console.log('aaa');
	var setLocation = $('#game_data_box #scrollable-dropdown-menu .typeahead');

	var engine = new Bloodhound({
		limit: 10,
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: uri_base + 'rest/index/search_developer.json?keyword=%QUERY'
	});

	engine.initialize();

	setLocation.typeahead(null, {
		name: 'developerTypeahead',
		displayKey: 'value',
		source: engine.ttAdapter(),
		templates: {
			suggestion: function(data){
				return '<p class="needsclick">' + data.name + ' / ' + data.studio + '</p>';
			}
		}
	}).bind('typeahead:selected', function(event, data) {

		var setLocationList = $(this).closest('#scrollable-dropdown-menu').find('#developer_list');
		var list = setLocationList.data('list');
		if ( ! list) list = [];

		if ($.inArray(parseInt(data.developer_no), list) == -1) {

			list.push(data.developer_no);

			$(setLocationList).append('<div class="original_label_game bgc_lightseagreen cursor_pointer" id="developer_no_' + data.developer_no + '" onclick="GAMEUSERS.index.deleteDeveloperAutoComplete(this, ' + data.developer_no + ')">' + data.name + ' / ' + data.studio + '</div>');

			setLocationList.data('list', list);
			//console.log(list);

		}

	});

};



/**
* 開発削除　auto complete　
*/
GAMEUSERS.index.deleteDeveloperAutoComplete = function(arguThis, developerNo) {

	var setLocation = $(arguThis).closest('#developer_list');
	var list = $(setLocation).data("list");

	//console.log(gameList);

	$.each(list, function(i, val) {
		if (val == developerNo) {
			list.splice(i,1);
			//alert(val);
		}
	});

	var setLocationList = $(arguThis).closest('#scrollable-dropdown-menu').find('#developer_list');
	setLocationList.data('list', list);

	$(setLocation).find('#developer_no_' + developerNo).remove();

	//console.log(list);

};





/**
* 開発検索　登録フォーム用
*/
GAMEUSERS.index.searchDeveloperForm = function(arguThis, page) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $('#form_register_developer');


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'form_developer');

	fileData.append('keyword', $(setLocation).find('#keyword').val());
	fileData.append('page', page);



	// console.log('page = ' + page);
	// console.log('keyword = ' + $(setLocation).find('#keyword').val());
	//
	// return;



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

		// --------------------------------------------------
		//   コード反映
		// --------------------------------------------------
//console.log(response);
		if (response.code) {

			$(setLocation).replaceWith(response.code);

			// スクロール
			scrollToAnchor('#form_register_developer', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);

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
* 開発登録保存
*/
GAMEUSERS.index.saveDeveloper = function(arguThis, developerNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest('#register_developer');


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'save_developer');

	if (developerNo) fileData.append('developer_no', developerNo);

	fileData.append('name', $(setLocation).find('#name').val());
	fileData.append('abbreviation', $(setLocation).find('#abbreviation').val());
	fileData.append('studio', $(setLocation).find('#studio').val());
	fileData.append('abbreviation', $(setLocation).find('#abbreviation').val());



	// console.log('developerNo = ' + developerNo);
	// console.log('name = ' + $(setLocation).find('#name').val());
	// console.log('abbreviation = ' + $(setLocation).find('#abbreviation').val());
	// console.log('studio = ' + $(setLocation).find('#studio').val());
	// console.log('abbreviation_studio = ' + $(setLocation).find('#abbreviation_studio').val());
	//
	// return;



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

		// --------------------------------------------------
		//   コード反映
		// --------------------------------------------------
//console.log(response);
		// if (response.code) {
		//
		// 	$(setLocation).replaceWith(response.code);
		//
		// 	// スクロール
		// 	if (gameNo) scrollToAnchor('#game_no_' + gameNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
		//
		// }

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
* ジャンル検索　登録フォーム用
*/
GAMEUSERS.index.searchGenreForm = function(arguThis, page) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $('#form_register_genre');


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'form_genre');

	fileData.append('keyword', $(setLocation).find('#keyword').val());
	fileData.append('page', page);



	// console.log('page = ' + page);
	// console.log('keyword = ' + $(setLocation).find('#keyword').val());
	//
	// return;



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

		// --------------------------------------------------
		//   コード反映
		// --------------------------------------------------
//console.log(response);
		if (response.code) {

			$(setLocation).replaceWith(response.code);

			// スクロール
			scrollToAnchor('#form_register_genre', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);

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
* ジャンル登録保存
*/
GAMEUSERS.index.saveGenre = function(arguThis, genreNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest('#register_genre');


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'save_genre');

	if (genreNo) fileData.append('genre_no', genreNo);

	fileData.append('sort', $(setLocation).find('#sort').val());
	fileData.append('name', $(setLocation).find('#name').val());



	// console.log('genreNo = ' + genreNo);
	// console.log('sort = ' + $(setLocation).find('#sort').val());
	// console.log('name = ' + $(setLocation).find('#name').val());
	//
	// return;



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

		// --------------------------------------------------
		//   コード反映
		// --------------------------------------------------
//console.log(response);
		// if (response.code) {
		//
		// 	$(setLocation).replaceWith(response.code);
		//
		// 	// スクロール
		// 	if (gameNo) scrollToAnchor('#game_no_' + gameNo, -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
		//
		// }

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		if (response.alert_title) {
			showAlert($(arguThis).closest('#form_register_genre').find('#alert'), response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

};
