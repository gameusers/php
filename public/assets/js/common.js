// --------------------------------------------------
//   参考　http://qiita.com/KENJU/items/c7fad62a12cc2809b507
// --------------------------------------------------

var GAMEUSERS = GAMEUSERS || {};


// --------------------------------------------------
//   名前空間を設定するメソッド
// --------------------------------------------------

GAMEUSERS.namespace = function(ns_string) {
	var parts = ns_string.split('.'), // . で区切った配列
		parent = GAMEUSERS, // グローバルオブジェクトのアプリ名
		i;

	// 先頭のグローバルを取り除く
	if ( parts[0] === 'GAMEUSERS') {
		parts = parts.slice(1); // 先頭を削除
	}

	for ( i = 0; i < parts.length; i += 1) {
		// プロパティが存在しなければ作成する
		if ( typeof parent[parts[i]] === 'undefined') {
			parent[parts[i]] = {}; // モジュールのオブジェクト生成
		}
		parent = parent[parts[i]];
	}
};


// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.common');


// --------------------------------------------------
//   プロパティ
// --------------------------------------------------

GAMEUSERS.common.openedContent = null;
GAMEUSERS.common.headerHeight = null;
GAMEUSERS.common.bsTabHeight = null;
GAMEUSERS.common.scrollMargin = null;
GAMEUSERS.common.headerBsTabMarginSize = null;

GAMEUSERS.common.swiperSocialButton = null;
GAMEUSERS.common.swiperHelp = null;

GAMEUSERS.common.masonryFeed = null;
GAMEUSERS.common.masonryFooter = null;





//var swiperSocialButton;
var swiperSlideGameList;
var swiperSlideAmazonAd;
var scrollBlankSize = -117;



$(function() {


// var uri_base2 = 'https://gameusers.org/gc/gundam-online';
// console.log(uri_base2.indexOf('https://gameusers.org/'));
// 	if (uri_base2.indexOf('https://gameusers.org/') === -1) {
// 		alert('error');
// 	}

// console.log(contents_data);



	// --------------------------------------------------
	//  最初に読み込んだコンテンツを特定
	// --------------------------------------------------

	var tempArr = window.location.href.split(uri_base)[1];
	tempArr = tempArr.split('/');

	GAMEUSERS.common.urlParam1 = tempArr[1];
	GAMEUSERS.common.urlParam2 = tempArr[2];
	GAMEUSERS.common.urlParam3 = tempArr[3];
	GAMEUSERS.common.urlParam4 = tempArr[4];
	//console.log(GAMEUSERS.common.urlParam1, GAMEUSERS.common.urlParam2, GAMEUSERS.common.urlParam3, GAMEUSERS.common.urlParam4);


	// --------------------------------------------------
	//  最初に開いたページの履歴をつける
	// --------------------------------------------------

	if (typeof(contents_data) !== 'undefined') {
		window.history.pushState(contents_data[contents_data.initial_load.group + '_' + contents_data.initial_load.content].state, null, uri_current);
	}



	// --------------------------------------------------
	//   FastClick適用
	// --------------------------------------------------

	FastClick.attach(document.body);


	// --------------------------------------------------
	//   多言語化
	// --------------------------------------------------

	//i18n.init({ lng: language, resGetPath: uri_base + 'locales/' + language + '/__ns__.json' });


	// --------------------------------------------------
	//   お知らせ未読件数表示
	// --------------------------------------------------

	// デザインVer.2用
	if ($('.cd-auto-hide-header')[0]) {
		GAMEUSERS.common.readNotificationsUnreadTotal();
	} else {
		readNotificationsUnreadTotal();
	}


	// --------------------------------------------------
	//  ヘッダーとタブの高さ取得　スクロール時に使う　デザインVer.2用
	// --------------------------------------------------

	GAMEUSERS.common.headerHeightVer2 = $('.cd-auto-hide-header').outerHeight(true);
	GAMEUSERS.common.tabHeightVer2 = $('.cd-secondary-nav').outerHeight(true);
	GAMEUSERS.common.scrollMargin = GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2 + 20;


	// --------------------------------------------------
	//   ヘッダー　ベルをクリックしてお知らせを開く
	// --------------------------------------------------

	$(document).on('click', '#header_notifications', function() {

		// 未読お知らせ読み込み
		var userNo = $(this).data('user_no');
		GAMEUSERS.common.readNotifications(this, 1, userNo);

		// モーダルを開く
		$('#notifications_modal').modal('toggle');
		return false;

	});


	// --------------------------------------------------
	//   ソーシャルボタン
	// --------------------------------------------------

	if ($('#swiper_container_social_button')[0]) {

		GAMEUSERS.common.swiperSocialButton = new Swiper('#swiper_container_social_button', {
			slidesPerView: 'auto',
			freeMode: true
		});

		// カウント取得
		var url = location.href;

		GAMEUSERS.common.socialCountTwitter(url);
		GAMEUSERS.common.socialCountFacebook(url);
		GAMEUSERS.common.socialCountGooglePlus(url);
		GAMEUSERS.common.socialCountHatena(url);
		GAMEUSERS.common.socialCountPocket(url);


		// ボタンを震わせる処理　デザインVer.2用
		var socialButtonVersion = $('#swiper_container_social_button').data('version');

		if (socialButtonVersion == 2) {

			$('#twitter, #facebook, #google_plus, #hatena, #pocket, #line, #email').jrumble({
				x: 2,
				y: 2,
				rotation: 1,
				speed: 20
			});

			$('#twitter, #facebook, #google_plus, #hatena, #pocket, #line, #email').hover(function(){
				$(this).trigger('startRumble');
			}, function(){
				$(this).trigger('stopRumble');
			});

		}

	}


	// --------------------------------------------------
	//   スライドゲームリスト
	// --------------------------------------------------

	if ($('#swiper_container_slide_game_list')[0]) {

		swiperSlideGameList = new Swiper('#swiper_container_slide_game_list', {
			slidesPerView: 'auto',
			freeMode: true,
			freeModeMomentumRatio: 0.5,
			autoplay: 2500,
			autoplayDisableOnInteraction: true,
			loop: true
	    });

		$('#slide_game_list_select_menu').change( function(){
			slideGameList(content_id);
		});

	}


	// --------------------------------------------------
	//   Amazonスライド広告
	// --------------------------------------------------

	if ($('#swiper_container_slide_amazon_ad')[0]) {

		swiperSlideAmazonAd = new Swiper('#swiper_container_slide_amazon_ad', {
			slidesPerView: 'auto',
			freeMode: true,
			freeModeMomentumRatio: 0.5,
			autoplay: 2500,
			autoplayDisableOnInteraction: true,
			loop: true
	    });

		$('[data-toggle="tooltip"]').tooltip({
			container: 'body'
		});

	}


	// --------------------------------------------------
	//   タブ　スクロールで固定
	// --------------------------------------------------

	if ($('#bsTab')[0]) {
		GAMEUSERS.common.bsTabFixed.setUp();
	}


	// --------------------------------------------------
	//   ヘッダーとタブの高さを取得してマージン計算　
	// --------------------------------------------------

	GAMEUSERS.common.headerHeight = $('header').outerHeight(true);
	GAMEUSERS.common.bsTabHeight = $('#bsTab').height();
	GAMEUSERS.common.headerBsTabMarginSize = GAMEUSERS.common.headerHeight + GAMEUSERS.common.bsTabHeight + 10;


//console.log(contents_data);

	// --------------------------------------------------
	//   タブ　デザインVer.2用
	// --------------------------------------------------

	$('.cd-secondary-nav ul li a').on('click', function(e) {

// console.log(contents_data);
		// ---------------------------------------------
		//   リンクの処理停止
		// ---------------------------------------------

		e.preventDefault();

		var group = $(this).data('group');
		var content = contents_data.opened_content[group];
		//var content = $(this).data('content');
// console.log(group, content);

		// ---------------------------------------------
		//   URL変更
		// ---------------------------------------------

		var state = contents_data[group + '_' + content].state;
		var url = contents_data[group + '_' + content].url;
		window.history.pushState(state, null, url);
//console.log(state, url);

		// ---------------------------------------------
		//   コンテンツ変更
		// ---------------------------------------------

		GAMEUSERS.common.changeMenuContents(this, group, null);


		// ---------------------------------------------
		//   meta書き換え
		// ---------------------------------------------

		var metaTitle = contents_data[group + '_' + content].meta_title;
		var metaKeywords = contents_data[group + '_' + content].meta_keywords;
		var metaDescription = contents_data[group + '_' + content].meta_description;

		if (metaTitle && metaKeywords && metaDescription) {
			GAMEUSERS.common.rewriteMeta(metaTitle, metaKeywords, metaDescription);
		}

		//console.log(metaTitle, metaKeywords, metaDescription);

		// タブを切り替えてからリサイズすると、戻したときにぐちゃぐちゃになっているため、レイアウトしなおす必要がある
		if (group === 'feed' && agent_type === '') GAMEUSERS.common.masonryFeed.masonry();

	});



	// --------------------------------------------------
	//   最初に表示するコンテンツ
	// --------------------------------------------------

	$('[id^=change_contents_]').hide();
	$('[id^=change_contents_]' + '.active').show();



	// --------------------------------------------------
	//   最初に隠すタグ
	// --------------------------------------------------

	//$('[data-initial-hide=true]').hide();



	// --------------------------------------------------
	//   関連ゲーム
	// --------------------------------------------------

	GAMEUSERS.common.searchGameListNo(null);



	// --------------------------------------------------
	//   ヘルプメニュー切り替え
	// --------------------------------------------------

	$(document).on('change', '#help_menu', function () {

		var value = $(this).val().split('/');
		var list = value[0];
		var content = value[1];
		//console.log(list, content);

		// デザインVer.2用
		if ($('#content_help_index')[0]) {
			GAMEUSERS.common.readHelpVer2(this, 1, list, content);
		} else {
			GAMEUSERS.common.readHelp(this, 1, list, content);
		}

	});






	// --------------------------------------------------
	//   Javascrpitのリンク　デザインVer.2用
	//   <a> の中に <a> を入れると表示がおかしくなるため、内部のリンクはJavascriptで処理する
	// --------------------------------------------------

	$(document).on('click', '#jslink', function(e) {

		e.preventDefault();

		var target = '_self';
		var address = $(this).data('jslink');

		window.open(address, target);

	});






	// --------------------------------------------------
	//   Amazon広告について　デザインVer.2用
	// --------------------------------------------------

	if ($('#about_amazon_ad')[0])
	{

		// 5秒間表示
		PNotify.prototype.options.delay = 5000;

		$(document).on('click', '#about_amazon_ad', function() {

			var pnotifyTitle = $(this).data('title');
			var pnotifyText = $(this).data('text');

			new PNotify({
				styling: 'bootstrap3',
				title: pnotifyTitle,
				text: pnotifyText,
				// type: 'success'
			});
		});

	}



	// --------------------------------------------------
	//  Masonry フッター　デザインVer.2用
	// --------------------------------------------------

	if ($('#footer_content_card')[0]) {

		$('footer').imagesLoaded( function() {
			GAMEUSERS.common.masonryFooter = $('footer .card_box').masonry({
				itemSelector: '.card_game'
			});
		});

	}



	// --------------------------------------------------
	//   フッター　カード変更　デザインVer.2用
	// --------------------------------------------------

	$('#footer_select_box').change(function() {

		var option = $('#footer_select_box option:selected').val();

		// クッキー設定
		$.cookie('footer_type', option);

		// カード読み込み
		GAMEUSERS.common.readFooterCard(this);

	});



	// ---------------------------------------------
	//   フッター　音訓索引　デザインVer.2用
	// ---------------------------------------------

	var footerCardFirstLoad = true;

	$('footer').on('mouseenter', function () {

		if (footerCardFirstLoad) {
			GAMEUSERS.common.readGameIndex(this, 1);
			footerCardFirstLoad = false;
		}

	});


	// --------------------------------------------------
	//   フッター　コンテンツ変更　デザインVer.2用
	// --------------------------------------------------

	$('[id^=footer_button_]').on('click', function () {

		// ボタンの選択状況変更
		$('[id^=footer_button_]').removeClass('active');
		$(this).addClass('active');

		// コンテンツ変更
		var footerContentType = $(this).data('type');
		$('[id^=footer_content_]').addClass('element_hidden');
		$('#footer_content_' + footerContentType).removeClass('element_hidden');


		if (footerCardFirstLoad) {
			GAMEUSERS.common.readGameIndex(this, 1);
			footerCardFirstLoad = false;
		}

	});




	// --------------------------------------------------
	//   スマホ・タブレット用メニュー　トップへ移動　デザインVer.2用
	// --------------------------------------------------

	$('#menu_to_top').on('click', function () {

		GAMEUSERS.common.stickyStuck = false;
		scrollToAnchor('main', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);

	});

	// --------------------------------------------------
	//   スマホ・タブレット用メニュー　下へ移動　デザインVer.2用
	// --------------------------------------------------

	$('#menu_to_bottom').on('click', function () {

		GAMEUSERS.common.stickyStuck = false;
		scrollToAnchor('#swiper_container_social_button', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);

	});



	// --------------------------------------------------
	//   画像モーダル表示
	// --------------------------------------------------

	$('body').magnificPopup({
		delegate: '[id=modal_image]',
		type:'image'
	});


	// --------------------------------------------------
	//   テキストエリア自動リサイズ
	// --------------------------------------------------

	$(document).on('focus', 'textarea', function(e) {
		autosize($(this));
	});


	// --------------------------------------------------
	//   リンクを無効にする　デザインVer.2用
	// --------------------------------------------------

	$(document).on('click', '[data-invalid-link=true]', function (e) {
		e.preventDefault();
	});

});





/**
* サイドメニュー固定
*/

// セレクター
GAMEUSERS.common.stickySelector = null;

// サイドメニューが固定されているかどうかを表す変数　trueの場合固定されている状態
GAMEUSERS.common.stickyStuck = false;

// アニメーションが動作中かどうかを表す変数　falseの場合動作中
GAMEUSERS.common.stickyAnimationDone = true;

// 一度、固定したメニューを記憶しておくための配列
GAMEUSERS.common.stickyExistenceArr = [];


GAMEUSERS.common.stickyMenu = function(selector, adType) {

	// セレクター設定
	GAMEUSERS.common.stickySelector = $(selector);

	// サイドメニューを初期位置に戻す
	GAMEUSERS.common.stickyMenuAnimation(0, 0);
	// GAMEUSERS.common.stickySelector.animate({
	// 	'paddingTop': '0px'
	// }, {
	// 	duration: 0
	// });


	// 一度、処理したものは二度処理しない
	if ($.inArray(selector, GAMEUSERS.common.stickyExistenceArr) !== -1) {
		//console.log('処理停止');
		return;
	} else {
		GAMEUSERS.common.stickyExistenceArr.push(selector);
		//console.log(GAMEUSERS.common.stickyExistenceArr);
	}


	// オフセット設定
	var offsetTop = 0;

	if (adType === 'rectangle') {
		offsetTop = -180;
	} else if (adType === 'none') {
		offsetTop = 100;
	} else if (adType === 'small') {
		offsetTop = 100;
	}



	// Stickyeセット　メニューのカーソル判定がなくなるため、spacer: falseは必要
	GAMEUSERS.common.stickySelector.stick_in_parent({
		offset_top: offsetTop,
		spacer: false
	})
	.on('sticky_kit:stick', function(e) {
		//console.log('has stuck!', e.target);
		GAMEUSERS.common.stickyStuck = true;
	})
	.on('sticky_kit:unstick', function(e) {
		//console.log('has unstuck!', e.target);
		GAMEUSERS.common.stickyStuck = false;

		// サイドメニューを初期位置に戻す
		GAMEUSERS.common.stickyMenuAnimation(0, 600);
		// GAMEUSERS.common.stickySelector.stop();
		// GAMEUSERS.common.stickySelector.animate({
		// 	'paddingTop': '0px'
		// }, {
		// 	duration: 600
		// });

		//console.log('サイドメニューを初期位置に戻す');

	})
	.on('sticky_kit:bottom', function(e) {
		//console.log('bottom');
		//GAMEUSERS.common.stickyStuck = true;
	})
	.on('sticky_kit:unbottom', function(e) {
		//console.log('unbottom');
		//GAMEUSERS.common.stickyStuck = true;
	});



	// --------------------------------------------------
	//  サイドメニューの広告を固定表示しないための処理
	//  アドセンスを固定表示すると規約に違反するため
	//  ナビが出てくるとメニューがその分下がる、ナビが引っ込むとその分上がる
	// --------------------------------------------------

	//var done = true;// 処理中かどうかの判定変数
	var before = $(window).scrollTop();

	$(window).on('scroll', function () {



		var menuPaddingTop = parseInt(GAMEUSERS.common.stickySelector.css('padding-top'));

		var after = $(window).scrollTop();


		// メニューの方が高い場合は処理停止　50足しているのは高さを取得するとメニューの方が高くならないケースがあるため
		// ※ いい加減な処理
		if ((GAMEUSERS.common.stickySelector.height() + 50) > $('main article:visible').height()) {

		}

		// 上向きのスクロール　サイドメニュー固定中、パディング0、処理中じゃない場合、最上部メニューの分だけ少し移動させる
		else if (before > after && GAMEUSERS.common.stickyStuck && menuPaddingTop === 0 && GAMEUSERS.common.stickyAnimationDone) {

			GAMEUSERS.common.stickyAnimationDone = false;
			GAMEUSERS.common.stickyMenuAnimation(GAMEUSERS.common.headerHeightVer2, 450);

		}

		// 下向きのスクロール　サイドメニュー固定中、パディング50、処理中じゃない場合、デフォルトの固定位置に戻す
		else if (before < after && GAMEUSERS.common.stickyStuck && menuPaddingTop === 50 && GAMEUSERS.common.stickyAnimationDone) {

			GAMEUSERS.common.stickyAnimationDone = false;
			GAMEUSERS.common.stickyMenuAnimation(0, 280);

		}

		before = after;

		// console.log('GAMEUSERS.common.stickyStuck = ' + GAMEUSERS.common.stickyStuck);
		// console.log('menuPaddingTop = ' + menuPaddingTop);
		// console.log('GAMEUSERS.common.stickyAnimationDone = ' + GAMEUSERS.common.stickyAnimationDone);

		//console.log('menu height = ' + GAMEUSERS.common.stickySelector.outerHeight(true));
		//console.log('menu height = ' + $('.menu').outerHeight(true));
		// console.log('content height = ' + $('main').outerHeight(true));

		//console.log('content height = ' + $('main article:visible').attr('id') + $('main article:visible').outerHeight(true));

		//console.log('menu height = ' + GAMEUSERS.common.stickySelector.height());
		//console.log($('main article:visible').attr('id') + ' height = '+ $('main article:visible').height());

	});

};

GAMEUSERS.common.stickyMenuAnimation = function(paddingTopValue, durationValue) {

	if (GAMEUSERS.common.stickySelector) {

		GAMEUSERS.common.stickySelector.stop();
		GAMEUSERS.common.stickySelector.animate({
			'paddingTop': paddingTopValue + 'px'
		}, {
			duration: durationValue,
			complete: function() {
				GAMEUSERS.common.stickyAnimationDone = true;
				//console.log('上');
			},
		});

	}

};





/**
* メニューコンテンツ切り替え Ver.2
* @param {string} group コンテンツが所属する大きなグループ
* @param {string} content 各コンテンツ
*/

GAMEUSERS.common.openedGroupObj = {};

GAMEUSERS.common.changeMenuContents = function(arguThis, group, content) {

	// console.log('group = ' + group);
	// console.log('content = ' + content);


	// console.log(GAMEUSERS.common.openedGroupObj);

	// ----------------------------------------
	//   contentが指定されている場合、そのコンテンツを表示
	//   contentがnullの場合、すでに開かれているコンテンツを表示（タブの切り替え時）
	// ----------------------------------------

	if (content) {
		contents_data.opened_content[group] = content;
	} else {
		content = contents_data.opened_content[group];
	}


	// ----------------------------------------
	//   タブ部分のアクティブ切り替え
	// ----------------------------------------

	$('.cd-secondary-nav ul li a').removeClass('active');
	$('.cd-secondary-nav [data-group=' + group + ']').addClass('active');


	// ----------------------------------------
	//   コンテンツ
	// ----------------------------------------

	$('[id^=content_]').addClass('element_hidden');
	$('#content_' + group + '_' + content).removeClass('element_hidden');
// console.log('#content_' + group + '_' + content);
// console.log('#content_bbs_index = ' + $('#content_bbs_index').is(':visible'));
// console.log('#content_rec_index = ' + $('#content_rec_index').is(':visible'));
// console.log('#recruitment_box = ' + $('#recruitment_box').is(':visible'));
// console.log('#recruitment_0y6mj6nvezd0rrg9 = ' + $('#recruitment_0y6mj6nvezd0rrg9').is(':visible'));

// $('#recruitment_box').addClass('element_hidden');



	// ----------------------------------------
	//   メニュー
	// ----------------------------------------

	$('[id^=menu_]').addClass('element_hidden');
	$('#menu_' + group).removeClass('element_hidden');

	// マージン調整
	$('main .menu').removeClass(function(index, className) {
		return (className.match(/\menu_margin_\S+/g) || []).join(' ');
	});
	$('main .menu').addClass('menu_margin_' + group);



	// サイドメニューを初期位置に戻す
	GAMEUSERS.common.stickyMenuAnimation(0, 600);

	// 上までスクロールする
	GAMEUSERS.common.stickyStuck = false;
	scrollToAnchor('main', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


};




/**
* フィード　読み込み
*/
GAMEUSERS.common.readFeed = function(arguThis, page, type, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#feed_card_box';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'feed');
	fileData.append('type', type);
	fileData.append('page', page);

	// console.log('type = ' + type);
	// console.log('page = ' + page);
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

//console.log(response);

		// --------------------------------------------------
		//   フィード反映
		// --------------------------------------------------

		if (response.code_feed) {
// alert(response.code_feed);

			// --------------------------------------------------
			//   ページを保存する
			// --------------------------------------------------

			$('[id="menu_card"][data-feed="' + type + '"]').data('page', page);
			// console.log($('[id="menu_card"][data-feed="community"]').data('page'));


			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			if (scroll) scrollToAnchor('main', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


			// --------------------------------------------------
			//   コード反映
			// --------------------------------------------------

			$(setLocation).html(response.code_feed);


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.feed_index.state = response.state;
			contents_data.feed_index.url = response.url;
			contents_data.feed_index.meta_title = response.meta_title;
			contents_data.feed_index.meta_keywords = response.meta_keywords;
			contents_data.feed_index.meta_description = response.meta_description;
			// contents_data['feed_' + type].state = response.state;
			// contents_data['feed_' + type].url = response.url;
			// contents_data['feed_' + type].meta_title = response.meta_title;
			// contents_data['feed_' + type].meta_keywords = response.meta_keywords;
			// contents_data['feed_' + type].meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) {

				GAMEUSERS.common.changeUrlAndMeta('feed', 'index');

				// タブのURLを変更する
				$('#tab_feed a').attr('href', response.url);

			}


			// --------------------------------------------------
			//   PC・タブレット版の処理
			// --------------------------------------------------

			if (agent_type !== 'smartphone') {

				// サムネイル広告を隠す　ウィンドウのサイズが1125未満の場合
				GAMEUSERS.common.thumbnailAdHideShow();

				// ページ入れ替え処理
				$(setLocation).not('.card_thumbnail').imagesLoaded( function() {

					GAMEUSERS.common.masonryFeed.masonry('reloadItems');
					GAMEUSERS.common.masonryFeed.masonry('layout');

					// AOS停止
					AOS.init({
						disable: true
					});

					// サイドメニューを初期位置に戻す
					GAMEUSERS.common.stickyMenuAnimation(0, 600);

				});

			}

		}


		// --------------------------------------------------
		//   ページャー反映
		// --------------------------------------------------

		if (response.code_feed_pagination) {
			$('#feed_pagination').html(response.code_feed_pagination);
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
* コミュニティ一覧　読み込み
*/
GAMEUSERS.common.readCommunityList = function(arguThis, page, type, userNo, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#community_list';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'community_list');
	fileData.append('type', type);
	if (userNo) fileData.append('user_no', userNo);
	fileData.append('page', page);


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

//console.log(type, response);
		if (response.code) {


			// --------------------------------------------------
			//   コード反映
			// --------------------------------------------------

			$(setLocation).html(response.code);


			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			if (scroll) scrollToAnchor('main', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.community_index.state = response.state;
			contents_data.community_index.url = response.url;
			contents_data.community_index.meta_title = response.meta_title;
			contents_data.community_index.meta_keywords = response.meta_keywords;
			contents_data.community_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) {

				GAMEUSERS.common.changeUrlAndMeta('community', 'index');

				// タブのURLを変更する
				$('#tab_community a').attr('href', response.url);

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
* Wiki一覧　読み込み
*/
GAMEUSERS.common.readWikiList = function(arguThis, page, loading, scroll, urlRewrite) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#wiki_list';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (loading) loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'wiki_list');
	fileData.append('page', page);


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


			// --------------------------------------------------
			//   コード反映
			// --------------------------------------------------

			$(setLocation).html(response.code);


			// --------------------------------------------------
			//   上までスクロールする
			// --------------------------------------------------

			if (loading) scrollToAnchor('main', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);


			// --------------------------------------------------
			//   content_data書き換え
			// --------------------------------------------------

			contents_data.wiki_index.state = response.state;
			contents_data.wiki_index.url = response.url;
			contents_data.wiki_index.meta_title = response.meta_title;
			contents_data.wiki_index.meta_keywords = response.meta_keywords;
			contents_data.wiki_index.meta_description = response.meta_description;


			// --------------------------------------------------
			//   URLとMetaを変更
			// --------------------------------------------------

			if (urlRewrite) {

				GAMEUSERS.common.changeUrlAndMeta('wiki', 'index');

				// タブのURLを変更する
				$('#tab_wiki a').attr('href', response.url);

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
* フッター　カード　読み込み
*/
GAMEUSERS.common.readFooterCard = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = 'footer .card_box';


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'footer_card');


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

			// コード反映
			$(setLocation).html(response.code);

			$(setLocation).imagesLoaded( function() {
				GAMEUSERS.common.masonryFooter.masonry('reloadItems');
				GAMEUSERS.common.masonryFooter.masonry('layout');
			});

		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

	});

};





/**
* ゲームコミュニティ　音訓索引読み込み
*/
GAMEUSERS.common.readGameIndex = function(arguThis, page) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocationMenu = '#footer_game_index_menu';
	var setLocation = '#footer_game_index';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('api_type', 'game_index');
	if ( ! $('.index_game_list_search_keyword_button')[0]) fileData.append('first_load', true);
	fileData.append('page', page);

	var keyword = $(arguThis).data('keyword');
	if (keyword) fileData.append('keyword', keyword);


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

		if (response.code_menu) {
			$(setLocationMenu).html(response.code_menu);
		}

		if (response.code) {
			$(setLocation).html(response.code);
			if (keyword) $(setLocation + ' #collapseListGroup0').collapse('show');
		}

		// スクロール
		// if ($('#footer_game_index_menu').html()){
		// 	//console.log(response.code_menu, response.code);
		// 	console.log('スクロール');
		// 	scrollToAnchor('.copyright', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);
		// }


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
* ウィンドウのサイズが1125未満の場合は、サムネイル広告を隠す。それ以上なら表示する
*/
GAMEUSERS.common.thumbnailAdHideShow = function(arguThis) {
	var windowWidth = $(window).width();
	if (windowWidth < 1125) {
		$('[data-thumbnail-hide=true]').hide();
	} else {
		$('[data-thumbnail-hide=true]').show();
	}
};



/**
* ソーシャル　シェア送信
*/
GAMEUSERS.common.socialShare = function(arguThis) {


	// --------------------------------------------------
	//   データ取得・設定
	// --------------------------------------------------

	var type = $(arguThis).attr('id');

	var openAddress = null;

	var text = encodeURIComponent($('title').text());
	var url = encodeURIComponent(location.href);

	var siNetwork = '';
	var siAction = 'Share';


	// --------------------------------------------------
	//   アドレス設定
	// --------------------------------------------------

	if (type === 'twitter') {
		openAddress = 'https://twitter.com/share?url=' + url + '&text=' + text;
		siNetwork = 'Twitter';
	}
	else if (type === 'facebook') {
		openAddress = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
		siNetwork = 'Facebook';
	}
	else if (type === 'google_plus') {
		openAddress = 'https://plus.google.com/share?url=' + url;
		siNetwork = 'Google+';
	}
	else if (type === 'hatena') {
		openAddress = 'http://b.hatena.ne.jp/entry/' + url;
		siNetwork = 'Hatena';
	}
	else if (type === 'pocket') {
		openAddress = 'http://getpocket.com/edit?url=' + url;
		siNetwork = 'Pocket';
	}
	else if (type === 'line') {
		var textUrl = encodeURIComponent($('title').text() + ' ' + location.href);
		openAddress = 'http://line.me/R/msg/text/?' + textUrl;
		siNetwork = 'LINE';
	}
	else if (type === 'email') {
        var subject = 'Game Users';
        var body = $('title').text() + '%0A' + location.href;

        location.href = 'mailto:?subject=' + subject + '&body=' + body;

		siNetwork = 'Email';
	}


	// --------------------------------------------------
	//   新規ウィンドウで開く
	// --------------------------------------------------

	if (openAddress) {
		window.open(openAddress, '_blank');
	}


	// --------------------------------------------------
	//   Google Analytics ソーシャルインタラクション　ローカル、編集時には処理しない
	//   ソーシャルボタンのクリック数を調べる
	//   https://developers.google.com/analytics/devguides/collection/analyticsjs/social-interactions?hl=ja
	// --------------------------------------------------

	if (uri_base.indexOf('gameusers.org') !== -1) {
		ga('send', 'social', siNetwork, siAction, "'" + uri_current + "'");
	}


};




/**
* ソーシャルボタン　カウント　Twitter
*/
GAMEUSERS.common.socialCountTwitter = function(url) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#swiper_container_social_button #twitter span';
//url = 'http://www.itmedia.co.jp/news/articles/1607/20/news126.html';


	// --------------------------------------------------
	//   ローカルの場合は処理停止
	// --------------------------------------------------

	if (url.indexOf('gameusers.org') === -1) {
		//console.log('含んでいない');
		return;
	}
	// return;
	// console.log(url);

	$.ajax({

		url: 'https://jsoon.digitiminimi.com/twitter/count.json',
		dataType: 'jsonp',
		data:{
			url:url
		}

	}).done(function(response) {

		if (response.count) {
			$(setLocation).html(response.count);
		}
		else
		{
			$(setLocation).html(0);
		}
		//console.log(url);

	}).fail(function() {
	}).always(function() {
	});


};




/**
* ソーシャルボタン　カウント　Facebook
*/
GAMEUSERS.common.socialCountFacebook = function(url) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#swiper_container_social_button #facebook span';


	$.ajax({

		url: 'https://graph.facebook.com/',
		dataType: 'jsonp',
		data:{
			id:url
		}

	}).done(function(response) {

		try {
			if (response.share.share_count) {
				$(setLocation).html(response.share.share_count);
			}
			else
			{
				$(setLocation).html(0);
			}
		} catch (e) {
			$(setLocation).html(0);
		}
		//console.log(url);

	}).fail(function() {
	}).always(function() {
	});


};



/**
* ソーシャルボタン　カウント　Google Plus
*/
GAMEUSERS.common.socialCountGooglePlus = function(url) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#swiper_container_social_button #google_plus span';


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('url', url);



	$.ajax({

		url: uri_base + 'rest/social/count_google_plus.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.count) {
			$(setLocation).html(response.count);
		}
		else
		{
			$(setLocation).html(0);
		}
		//console.log(url);

	}).fail(function() {
	}).always(function() {
	});


};




/**
* ソーシャルボタン　カウント　Facebook
*/
GAMEUSERS.common.socialCountHatena = function(url) {

//url = 'https://gameusers.org/';
	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#swiper_container_social_button #hatena span';


	$.ajax({

		url: 'https://b.hatena.ne.jp/entry.count',
		dataType: 'jsonp',
		data:{
			url:url
		}

	}).done(function(response) {

		if (response) {
			$(setLocation).html(response);
		}
		else
		{
			$(setLocation).html(0);
		}
		//console.log(response);

	}).fail(function() {
	}).always(function() {
	});


};



/**
* ソーシャルボタン　カウント　Pocket
*/
GAMEUSERS.common.socialCountPocket = function(url) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#swiper_container_social_button #pocket span';


	// --------------------------------------------------
	//   2016/11/21　カウントが表示されなくなったので、yahooapisを利用する
	// --------------------------------------------------
//b
	// $.ajax({
	//
	// 	type: "get", dataType: "xml",
	// 	url: "https://query.yahooapis.com/v1/public/yql",
	// 	data: {
	// 		// q: "SELECT content FROM data.headers WHERE url='https://widgets.getpocket.com/v1/button?label=pocket&count=vertical&v=1&url=" + url + "' and ua='#Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36'",
	// 		q: "SELECT content FROM data.headers WHERE url='https://widgets.getpocket.com/v1/button?v=1&count=horizontal&url=https://gameusers.org/&src=https://gameusers.org/' and ua='#Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36'",
	// 		// q: "http://widgets.getpocket.com/v1/button?v=1&count=horizontal&url=https://gameusers.org/&src=https://gameusers.org/",
	// 		format: "xml",
	// 		env: "http://datatables.org/alltables.env"
	// 	}
	//
	// }).done(function(data) {
	//
	// 	var content = jQuery(data).find("content").text();
	// 	var match = content.match(/<em id="cnt">(\d+)<\/em>/i);
	// 	var count = (match !== null) ? match[1] : 0;
	//
	// 	$(setLocation).html(count);
	//
	// }).fail(function() {
	// }).always(function() {
	// });




	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	// var fileData = new FormData();
	//
	// fileData.append('q', "SELECT content FROM data.headers WHERE url='https://widgets.getpocket.com/v1/button?label=pocket&count=vertical&v=1&url=" + url + "' and ua='#Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36'");
	// fileData.append('format', 'xml');
	// fileData.append('env', 'http://datatables.org/alltables.env');
	//
	//
	// $.ajax({
	//
	// 	url: 'https://query.yahooapis.com/v1/public/yql',
	// 	dataType: 'xml',
	// 	type: 'POST',
	// 	data: fileData,
	// 	enctype: 'multipart/form-data',
	// 	processData: false,
	// 	contentType: false
	//
	// }).done(function(response) {
	//
	// 	var content = jQuery(data).find("content").text();
	// 	var match = content.match(/<em id="cnt">(\d+)<\/em>/i);
	// 	var count = (match !== null) ? match[1] : 0;
	//
	// }).fail(function() {
	// }).always(function() {
	// });



	// --------------------------------------------------
	//   2017/2/10　カウントが表示されなくなったので、pocket公式ボタンからの取得に戻す　PHP経由
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('url', url);


	$.ajax({

		url: uri_base + 'rest/social/count_pocket.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.count) {
			$(setLocation).html(response.count);
		}
		else
		{
			$(setLocation).html(0);
		}
		//console.log(url, response.count);

	}).fail(function() {
	}).always(function() {
	});


};






/*
 * 開いてるコンテンツをセット・ゲット
 */
// GAMEUSERS.common.setOpenedContent = (function(argument){
	// GAMEUSERS.common.openedContent = argument;
// }());
//
// GAMEUSERS.common.getOpenedContent = (function(){
	// return GAMEUSERS.common.openedContent;
// }());



/*
 * タブ固定表示
 */
GAMEUSERS.common.bsTabFixed = (function(){

	var bsTab;
	var bsTabOffset;
	var bsTabWidth;


	setUp = function(){

		var bsTab = $('#bsTab');
		var bsTabOffset = bsTab.offset();
		var bsTabWidth = $('.content_box').width();

		$(window).on('scroll resize', function () {

			var ws = $(window).scrollTop();

			bsTabWidth = $('.content_box').width();

			if(ws > (bsTabOffset.top - 65)) {

				bsTab.addClass('fixed');
				$('body').css('margin-top', bsTab.height());
				$('.bsTab.fixed').css('width', bsTabWidth + 'px');
				//console.log(bsTab.height());
			} else {

				bsTab.removeClass('fixed');
				$('body').css('margin-top','0px');
				$('.bsTab').css('width', '100%');
				//console.log('bbb');
			}

		});

		$(window).trigger('scroll');

	};


	// public API
	return {
		setUp: setUp
	};

}());



/*
 * サイドのメニューを固定する
 */
GAMEUSERS.common.contentFixed = (function(){

	var count = 0;

	var navi;
	var main;

	var bsTabHeight = 0;
	var headerHeight = 0;
	var marginSize = 0;

	var navi_offset_top;
	var main_offset_top;
	var scroll_limit;

	var openedContent;
	var existenceCheckArr = [];

	var contentLeftBoxHeightObj = {};


	setUp = function(){

		$('.content_box').imagesLoaded( function() {


			// サイドバーの固定するレイヤー
			navi = $('#content_' + GAMEUSERS.common.openedContent + ' #content_left_box');

			// メインのレイヤー
			main = $('#content_' + GAMEUSERS.common.openedContent + ' #content_right_box');

			// スクロールする上限
			scroll_limit = main.offset().top + main.outerHeight(true) - navi.outerHeight(true) - parseInt(navi.css('margin-top'),10);


			// 最初の読み込み時だけイベントセット、それ以外はリフレッシュ
			if ($.inArray(GAMEUSERS.common.openedContent, existenceCheckArr) !== -1) {
				refresh();
				return;
			}


			// ナビレイヤーの初期位置
			navi_offset_top = navi.offset().top - parseInt(navi.css('margin-top'),10);

			// メインレイヤーの初期位置
			main_offset_top = main.offset().top - parseInt(main.css('margin-top'),10);

			// メニューの高さ取得
			contentLeftBoxHeightObj[GAMEUSERS.common.openedContent] = navi.height();


			// スクロール、リサイズしたときにメニューとコンテンツの横幅を適切なサイズに調整する
			$(window).on('scroll resize', function (){

				var contentMainBoxWidth = $('.content_box').width();

				var contentRightBoxWidth = contentMainBoxWidth * 0.7;
				var contentLeftBoxWidth = contentMainBoxWidth - contentRightBoxWidth;

				navi.css('width', contentLeftBoxWidth);
				main.css('width', contentRightBoxWidth);

			});


			// ヘッダーとタブの高さを取得してマージンを計算（これがないとメニューがズレる）
			bsTabHeight = $('#bsTab').height();
			headerHeight = $('header').outerHeight(true);
			marginSize = bsTabHeight + headerHeight + 10;


			navi.perfectScrollbar(
				{
					wheelPropagation: true,
					swipePropagation: true
				}
			);

			// メニューにスクロール機能を追加
			$(window).on('resize', function () {

				if (navi.outerHeight(true) > main.outerHeight(true)) {

				} else if ($(window).height() > (navi.height() + marginSize)) {
					navi.height(contentLeftBoxHeightObj[GAMEUSERS.common.openedContent]);
					// console.log('windowの方が大きい');
				} else {
					navi.height($(window).height() - marginSize);
					//console.log('windowの方が小さい');
				}

				navi.perfectScrollbar('update');

			});



			if (navi.outerHeight(true) + navi_offset_top < main.outerHeight(true) + main_offset_top) {

				$(window).scroll(function () {

					var ws = $(window).scrollTop();

					//contentLeftBoxHeightObj[GAMEUSERS.common.openedContent] = 567;

					// console.log('---------------------------');
					// console.log('main.offset().top = ' + main.offset().top);
					// console.log('main.outerHeight(true) = ' + main.outerHeight(true));
					// console.log('navi.outerHeight(true) = ' + navi.outerHeight(true));
					// console.log('parseInt(navi.css(\'margin-top\'),10) = ' + parseInt(navi.css('margin-top'),10));
					//
					// console.log('scroll_limit(main_offset_top + main.outerHeight(true) - navi.outerHeight(true) - parseInt(navi.css(\'margin-top\'),10)) = ' + scroll_limit);
					//
					// console.log('navi_offset_top = ' + navi_offset_top);
					// console.log('main_offset_top = ' + main_offset_top);
					//
					// console.log('ws = ' + ws);
					// console.log('marginSize = ' + marginSize);
					//
					// console.log('navi.height() = ' + navi.height());
					// console.log('GAMEUSERS.common.openedContent = ' + GAMEUSERS.common.openedContent);
					//
					// console.log('contentLeftBoxHeightObj[GAMEUSERS.common.openedContent] = ' + contentLeftBoxHeightObj[GAMEUSERS.common.openedContent]);
					//
					// console.log('ws > (scroll_limit - marginSize) = ' + ws + ' > (' + scroll_limit + ' - ' + marginSize + ')');


					if (navi.outerHeight(true) > main.outerHeight(true)) {
						navi.css({position:'static'});
						//console.log('処理しない');
					} else if (ws > (scroll_limit - marginSize)) {
						navi.css({position:'fixed', top: scroll_limit - ws + 'px'});
						//console.log('下');
					} else if(ws > (navi_offset_top - marginSize)) {
						navi.css({position:'fixed', top: marginSize + 'px'});
						//console.log('中');
					} else {
						navi.css({position:'relative', top: '0px'});
						//console.log('上');
					}

				});

			}

			// 存在確認用配列に追加
			existenceCheckArr.push(GAMEUSERS.common.openedContent);

			// リフレッシュ
			refresh();

		});

	};

	refresh = function(){

		// メニューの高さ取得
		navi.css('height', 'auto');
		contentLeftBoxHeightObj[GAMEUSERS.common.openedContent] = navi.height();
		//contentLeftBoxHeightObj[GAMEUSERS.common.openedContent] = 487;

		// スクロールする上限
		scroll_limit = main.offset().top + main.outerHeight(true) - navi.outerHeight(true) - parseInt(navi.css('margin-top'),10);

		//console.log('リフレッシュ');
		//console.log('navi.height() = ' + navi.height());
		// console.log('navi_offset_top = ' + navi_offset_top);
		// console.log('main_offset_top = ' + main_offset_top);
		// console.log('scroll_limit = ' + scroll_limit);
		//console.log('GAMEUSERS.common.openedContent = ' + GAMEUSERS.common.openedContent);
		//console.log($('#content_' + GAMEUSERS.common.openedContent + ' #content_left_box').height());

		//console.log('contentLeftBoxHeightObj[GAMEUSERS.common.openedContent] = ' + contentLeftBoxHeightObj[GAMEUSERS.common.openedContent]);


		$(window).trigger('scroll');
		$(window).trigger('resize');

	};


	// public API（外部から使用できる関数をここに掲載する）
	return {
		setUp: setUp
	};


}());













/**
* コンテンツ切り替え
*/
GAMEUSERS.common.changeContents = function(arguThis, group, content) {


	// --------------------------------------------------
	//   ボタンのアクティブ切り替え
	// --------------------------------------------------

	$(arguThis).siblings().removeClass('active');
	$(arguThis).addClass('active');


	// --------------------------------------------------
	//   コンテンツの切り替え
	// --------------------------------------------------

	$('[id^=change_contents_' + group + ']').hide();
	$('#change_contents_' + group + '_' + content).show();


};





/**
* 関連ゲーム　ゲーム検索　auto complete
*/
GAMEUSERS.common.searchGameListNo = function(selector) {

	var gameListSelector;

	if (selector) {
		gameListSelector = $(selector);
	} else {
		gameListSelector = $('#game_list_form_box #scrollable-dropdown-menu .typeahead');
	}

	var engine = new Bloodhound({
		limit: 10,
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: uri_base + 'rest/common/search_game_name.json?keyword=%QUERY'
	});

	engine.initialize();

	gameListSelector.typeahead(null, {
		name: 'gameNameTypeahead',
		displayKey: 'value',
		source: engine.ttAdapter(),
		templates: {
			suggestion: function(data){
				return '<p class="needsclick">' + data.name + '</p>';
			}
		}
	}).bind('typeahead:selected', function(event, data) {

		var setLocationGameList = $(this).closest('#game_list_form_box').find('#game_list');

		var gameList = setLocationGameList.data('game-list');

		//console.log('aaa');
		if ( ! gameList) {
			gameList = [];
		}

		if ($.inArray(parseInt(data.game_no), gameList) == -1) {

			gameList.push(data.game_no);

			$(setLocationGameList).append('<div class="original_label_game bgc_lightseagreen cursor_pointer" id="game_list_no_' + data.game_no + '" onclick="GAMEUSERS.common.deleteGameListNo(this, ' + data.game_no + ')">' + data.name + '</div>');

		}

	});

};



/**
* 関連ゲーム　ゲーム削除
*/
GAMEUSERS.common.deleteGameListNo = function(arguThis, gameNo) {

	var setLocation = $(arguThis).closest('#game_list');

	var gameList = $(setLocation).data("game-list");

	//console.log(gameList);

	$.each(gameList, function(i, val) {
		if (val == gameNo) {
			gameList.splice(i,1);
			//alert(val);
		}
	});

	$(setLocation).find('#game_list_no_' + gameNo).remove();

};





/**
* スライドゲームリスト
*/
function slideGameList(contentId) {

	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("type", $('#slide_game_list_select_menu').val());



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

		url: uri_base + "rest/common2/slide_game_list.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合、ページ更新 -----

		if (response.code) {

			$("#swiper_container_slide_game_list div").html(response.code);
			swiperSlideGameList.update(true);
			//alert(response.code);

			//$('#slide_game_list_flipsnap').css('width', 90 * response.count);

			// slideGameListFlipsnap = Flipsnap('#slide_game_list_flipsnap', {
				// transitionDuration: 500
			// });

			//slideGameListFlipsnap.moveToPoint(0);
		}

	}).fail(function() {

		//alert('error');

	}).always(function() {



	});

}




/**
* コンテンツIDを取得
*/
function getContentId(arguThis) {
	return $(arguThis).parents('.content_box').attr('id');
}



/**
* 言語切り替え
* @param {string} str 言語
*/
function changeLanguage(str) {
	//alert('aaa');
	// クッキー保存
	if (uri_base.indexOf('gameusers.org') != -1) {
		$.cookie('language', str, { expires: 365, path: '/', domain: 'gameusers.org', secure: true });
	} else {
		$.cookie('language', str);
	}

	// リダイレクト
	location.href = uri_current;

}


/**
* グッドボタン
* @param {string} type タイプ
* @param {integer} noId ナンバーもしくはID
*/
function plusGood(arguThis, type, noId) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var setLocation = "#good_button_" + type + "_" + noId;
	var setLocation = arguThis;


	// --------------------------------------------------
	//   ポップオーバー表示　一度チェックしたものは再度押させない
	// --------------------------------------------------

	if ($(setLocation).data("checked")) {
		$(setLocation).popover("toggle");
		return;
	}


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingStart(setLocation);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("type", type);

	if (type == 'recruitment' || type == 'recruitment_reply') {
		fileData.append("id", noId);
	} else {
		fileData.append("no", noId);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/common/plus_good.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.good) {

			// レベル反映 User
			if (response.level_id_user)
			{
				$("[id=" + response.level_id_user + "]").text(response.level_user);
			}

			// レベル反映 Profile
			if (response.level_id_profile)
			{
				$("[id=" + response.level_id_profile + "]").text(response.level_profile);
			}

			// Good反映
			//$("#good_" + type + "_" + noId).text(response.good);
			$(setLocation).children("#good_" + type + "_" + noId).text(response.good);

		} else {

			// --------------------------------------------------
			//   ポップオーバー表示
			// --------------------------------------------------

			$(setLocation).popover({
				html: false,
				trigger: 'manual',
				placement: "bottom",
				title: "ERROR",
				content: response.alert_message
			});

			$(setLocation).popover("toggle");
			$(setLocation).data("checked", true);


			// 数秒でポップオーバーを自動的に削除
			$(setLocation).on('shown.bs.popover', function () {
				goodStopTimeout = setTimeout(function() {
					$(setLocation).popover("hide");
				},2000);
			}).on('hidden.bs.popover', function () {
				clearTimeout(goodStopTimeout);
			});

			// 他の場所をクリックしてもポップオーバーを削除
			$('body').on('click', function (e) {
				$(setLocation).each(function () {
					if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
						$(this).popover('hide');
					}
				});
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

		loadingStop(setLocation);

	});

}





/**
* お知らせ読み込み
* ほぼreadNotificationsと同じだが、向こうはappで使われているので削除する場合は、appの方の確認も必要になる
*/
GAMEUSERS.common.readNotifications = function(arguThis, page, userNo) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#notifications_box";


	// --------------------------------------------------
	//   未読ボタンを押したのか、既読ボタンを押したのか、ベルを押したのか
	//   header_notifications / read_notifications_unread / read_notifications_already
	// --------------------------------------------------

	var button_id = $(arguThis).attr('id');


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	if (button_id !== 'header_notifications') loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("page", page);
	fileData.append("user_no", userNo);


	var type;

	if (button_id == 'read_notifications_unread') {

		type = 'unread';
		$(setLocation).data('type', 'unread');
		$("#read_notifications_unread").attr('class', 'btn btn-default ladda-button active');
		$("#read_notifications_already").attr('class', 'btn btn-default ladda-button');

		$("#notifications_change_all_unread_button").show();

	} else if (button_id == 'read_notifications_already') {

		type = 'already';
		$(setLocation).data('type', 'already');
		$("#read_notifications_unread").attr('class', 'btn btn-default ladda-button');
		$("#read_notifications_already").attr('class', 'btn btn-default ladda-button active');

		$("#notifications_change_all_unread_button").hide();

	} else {

		type = $(setLocation).data("type");

	}

	fileData.append("type", type);



	$.ajax({

		url: uri_base + 'rest/user/read_notifications.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合だけコードを反映させる -----

		if (response.code) {

			// コード反映
			$(setLocation).html(response.code);

			// 初期化
			//initPlayerNotifications(contentId);


			// --------------------------------------------------
			//   未読件数更新
			//   チェック済みの未読のお知らせIDをdataに保存して
			//   新しいお知らせが表示された場合は、チェック済みであるかどうかを検証して
			//   チェック済みでない場合は、dataに追加して、未読数を減らす
			// --------------------------------------------------

			// 現在の未読数を取得
			var unreadTotal = $('#header_notifications_unread_total').text();

			// チェック済みの未読お知らせのIDを取得
			var checkedUnreadId = $('#header_notifications_unread_total').data('unread_id');

			// チェック済みの未読お知らせ　存在する場合は配列に変換、存在しない場合は空の配列を作成
			var checkedUnreadIdArr;

			if (checkedUnreadId) {
				checkedUnreadIdArr = checkedUnreadId.split(',');
			} else {
				checkedUnreadIdArr = [];
			}

			// 現在表示している未読のお知らせ　存在する場合は配列に変換、存在しない場合は空の配列を作成
			var unreadIdArr;

			if (response.unread_id) {
				unreadIdArr = response.unread_id.split(',');
			} else {
				unreadIdArr = [];
			}


			//var unreadTotalRenewal = unreadTotal - response.unread_count;
			//if (unreadTotalRenewal < 0) unreadTotalRenewal = 0;
			//$('#header_notifications_unread_total').text(unreadTotalRenewal);

			// すでにチェック済みのIDかどうかを検証する、チェックしていないIDの場合はチェック済み配列に追加
			// チェックしていない場合、未読数を減らす数を増加させる
			var minusCount = 0;

			$.each(unreadIdArr, function(key, value) {
				//console.log(value);
				if ($.inArray(value, checkedUnreadIdArr) == -1) {
					checkedUnreadIdArr.push(value);
					minusCount++;
				}

			});


			// --------------------------------------------------
			//   未読数を減らす
			// --------------------------------------------------

			var unreadTotalCount = 0;

			if (unreadTotal - minusCount < 0) {
				unreadTotalCount = 0;
			} else {
				unreadTotalCount = unreadTotal - minusCount;
			}

			$('#header_notifications_unread_total').text(unreadTotalCount);


			// チェック済み配列を文字列に変換してからdataに保存
			checkedUnreadId = checkedUnreadIdArr.join(',');
			$('#header_notifications_unread_total').data('unread_id', checkedUnreadId);


			// console.log('unreadTotal = ' + unreadTotal);
			// console.log('checkedUnreadIdArr = ' + checkedUnreadIdArr);
			// console.log('response.unread_id = ' + response.unread_id);
			// console.log('unreadIdArr = ' + unreadIdArr);


		} else {
			$(setLocation).html('');
		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		if (button_id !== 'header_notifications') loadingToggle(arguThis, null);


		// --------------------------------------------------
		//   すべて既読にボタンの表示・非表示
		// --------------------------------------------------

		if (button_id == 'read_notifications_unread') {
			$("#notifications_change_all_unread_button").show();
		} else if (button_id == 'read_notifications_already') {
			$("#notifications_change_all_unread_button").hide();
		}

	});

};





/**
* お知らせ読み込み
*/
function readNotifications(arguThis, page, userNo) {
	//alert('bbb');
	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var contentId = getContentId(arguThis);
	var setLocation = "#notifications_box";


	// --------------------------------------------------
	//   未読ボタンを押したのか、既読ボタンを押したのか、ベルを押したのか
	// --------------------------------------------------

	var button_id = $(arguThis).attr('id');
	//console.log(button_id);

	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	//if (button_id == 'read_notifications_unread' || button_id == 'read_notifications_already') loadingToggle(arguThis, null);
	if (button_id !== 'header_notifications') loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("page", page);
	fileData.append("user_no", userNo);


	// 未読・既読
	//var button_id = $(arguThis).attr('id');

	var type;

	if (button_id == 'read_notifications_unread') {

		type = 'unread';
		$(setLocation).data('type', 'unread');
		$("#read_notifications_unread").attr('class', 'btn btn-default ladda-button active');
		$("#read_notifications_already").attr('class', 'btn btn-default ladda-button');

		$("#notifications_change_all_unread_button").show();

	} else if (button_id == 'read_notifications_already') {

		type = 'already';
		$(setLocation).data('type', 'already');
		$("#read_notifications_unread").attr('class', 'btn btn-default ladda-button');
		$("#read_notifications_already").attr('class', 'btn btn-default ladda-button active');

		$("#notifications_change_all_unread_button").hide();

	} else {

		type = $(setLocation).data("type");

	}
	//alert(type);
	fileData.append("type", type);



	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	if(typeof appMode != "undefined") {
		fileData.append("app_mode", appMode);
	}


	$.ajax({

		url: uri_base + "rest/user/read_notifications.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {
		//alert(response.code);
		// ----- 成功した場合だけコードを反映させる -----

		if (response.code) {

			// コード反映
			$(setLocation).html(response.code);

			// 初期化
			initPlayerNotifications(contentId);


			// --------------------------------------------------
			//   未読件数更新
			//   チェック済みの未読のお知らせIDをdataに保存して
			//   新しいお知らせが表示された場合は、チェック済みであるかどうかを検証して
			//   チェック済みでない場合は、dataに追加して、未読数を減らす
			// --------------------------------------------------

			// 現在の未読数を取得
			var unreadTotal = $('#header_notifications_unread_total').text();

			// チェック済みの未読お知らせのIDを取得
			var checkedUnreadId = $('#header_notifications_unread_total').data('unread_id');

			// チェック済みの未読お知らせ　存在する場合は配列に変換、存在しない場合は空の配列を作成
			var checkedUnreadIdArr;

			if (checkedUnreadId) {
				checkedUnreadIdArr = checkedUnreadId.split(',');
			} else {
				checkedUnreadIdArr = [];
			}

			// 現在表示している未読のお知らせ　存在する場合は配列に変換、存在しない場合は空の配列を作成
			var unreadIdArr;

			if (response.unread_id) {
				unreadIdArr = response.unread_id.split(',');
			} else {
				unreadIdArr = [];
			}


			//var unreadTotalRenewal = unreadTotal - response.unread_count;
			//if (unreadTotalRenewal < 0) unreadTotalRenewal = 0;
			//$('#header_notifications_unread_total').text(unreadTotalRenewal);

			// すでにチェック済みのIDかどうかを検証する、チェックしていないIDの場合はチェック済み配列に追加
			// チェックしていない場合、未読数を減らす数を増加させる
			var minusCount = 0;

			$.each(unreadIdArr, function(key, value) {
				//console.log(value);
				if ($.inArray(value, checkedUnreadIdArr) == -1) {
					checkedUnreadIdArr.push(value);
					minusCount++;
				}

			});


			// --------------------------------------------------
			//   未読数を減らす
			// --------------------------------------------------

			var unreadTotalCount = 0;

			if (unreadTotal - minusCount < 0) {
				unreadTotalCount = 0;
			} else {
				unreadTotalCount = unreadTotal - minusCount;
			}

			$('#header_notifications_unread_total').text(unreadTotalCount);


			// チェック済み配列を文字列に変換してからdataに保存
			checkedUnreadId = checkedUnreadIdArr.join(',');
			$('#header_notifications_unread_total').data('unread_id', checkedUnreadId);


			// console.log('unreadTotal = ' + unreadTotal);
			// console.log('checkedUnreadIdArr = ' + checkedUnreadIdArr);
			// console.log('response.unread_id = ' + response.unread_id);
			// console.log('unreadIdArr = ' + unreadIdArr);



			//alert(response.unread_count);
			// 未読件数表示
			//readNotificationsUnreadTotal();

		} else {
			$(setLocation).html('');
		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		//if (button_id == 'read_notifications_unread' || button_id == 'read_notifications_already') loadingToggle(arguThis, null);
		if (button_id !== 'header_notifications') loadingToggle(arguThis, null);


		// --------------------------------------------------
		//   すべて既読にボタンの表示・非表示
		// --------------------------------------------------

		if (button_id == 'read_notifications_unread') {
			$("#notifications_change_all_unread_button").show();
		} else if (button_id == 'read_notifications_already') {
			$("#notifications_change_all_unread_button").hide();
		}

	});

}



/**
* お知らせ　初期化
*/
function initPlayerNotifications(contentId) {

	// --------------------------------------------------
	//   お知らせの長文を隠す
	// --------------------------------------------------

	if ($("#" + contentId + " #tab_notifications_" + contentId)[0]) {
		$("#" + contentId + " #tab_notifications_" + contentId + " #player_notifications_long").hide();
	}


	// --------------------------------------------------
	//   お知らせ　全文表示ボタン
	// --------------------------------------------------

	$("#" + contentId + " #tab_notifications_" + contentId + " #player_notifications_read_all_button").on('click', function() {
		$(this).parents("#player_notifications_box").find("#player_notifications_long").toggle();
		$(this).parents("#player_notifications_box").find("#player_notifications_short").toggle();
	});

}



/**
* お知らせ　予約ID保存
*/
/*
function saveNotificationsIdReservation() {
	//alert('bbb');
	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = "#unread_id_reservation";


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	var unread_id = $(setLocation).data('unread_id');

	//alert(unread_id);
	//return;

	fileData.append("id", unread_id);


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

		url: uri_base + "rest/user/save_notifications_id_reservation.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {

		// ----- 成功した場合だけコードを反映させる -----

		// if (response.code) {
			// $(setLocation).html(response.code);
			// initPlayerNotifications(contentId);
		// } else {
			// $(setLocation).html('');
		// }

	}).fail(function() {

		alert('error');

	}).always(function() {

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		//loadingToggle(arguThis, null);

	});

}
*/






/**
* お知らせ未読件数読み込み
* ほぼreadNotificationsUnreadTotalと同じだが、向こうはappで使われているので削除する場合は、appの方の確認も必要になる
*/
GAMEUSERS.common.readNotificationsUnreadTotal = function() {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#header_notifications_unread_total";


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	$.ajax({

		url: uri_base + 'rest/common/read_notifications_unread_total.json',
		dataType: 'json',
		type: 'POST',
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.total) {
			$(setLocation).html(response.total);
		}

	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

	});

};




/**
* お知らせ未読件数読み込み
*/
function readNotificationsUnreadTotal() {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#header_notifications_unread_total";


	// --------------------------------------------------
	//   ヘッダーが読み込まれてない場合は処理しない
	// --------------------------------------------------

	if ( ! $(setLocation)[0]) {
		//alert('no_header');
		return;
	}

	// --------------------------------------------------
	//   読み込み中は処理しない
	// --------------------------------------------------

	if ($(setLocation).data('reading')) {
		//alert('reading');
		return;
	}
	//alert('readNotificationsUnreadTotal');




	// --------------------------------------------------
	//   読み込み中
	// --------------------------------------------------

	$(setLocation).data('reading', true);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	if(typeof appMode != "undefined") {
		fileData.append("app_mode", appMode);
	}


	$.ajax({

		url: uri_base + "rest/common/read_notifications_unread_total.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合だけコードを反映させる -----
		//alert(response.total);
		if (response.total) {
			$(setLocation).html(response.total);
		} else {
			$(setLocation).html('-');
		}


		// --------------------------------------------------
		//   読み込み中解除
		// --------------------------------------------------

		$(setLocation).data('reading', false);


	}).fail(function() {

		//alert('error');

	}).always(function() {

	});

}




/**
* お知らせ　すべての未読を既読にする
*/
function changeAllUnreadToAlready(arguThis) {


	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------

	var confirm_text = '未読のお知らせをすべて既読にします。よろしいですか？';

    if ( ! window.confirm(confirm_text)) {
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#header_notifications_unread_total";



	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);



	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();


	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	if(typeof appMode != "undefined") {
		fileData.append("app_mode", appMode);
	}


	$.ajax({

		url: uri_base + "rest/common/change_all_unread_to_already.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		// ----- 成功した場合だけコードを反映させる -----
		//alert(response.total);
		if (response.success) {
			$('#notifications_box').html('');
			$(setLocation).html('0');
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
* 通報フォーム表示・非表示
*/
//function reportInformation() {
	//alert('abba');
	// $('#report_information_modal').modal('toggle');
//
	// $('#report_information_modal').on('hidden.bs.modal', function () {
		// alert('aaa');
		// $("#report_comment").val("");
		// $("#report_information_modal #alert").html("");
	// });
//}


/**
* 通報
*/
/*
function sendReport(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	//var contentId = getContentId(arguThis);
	var setLocation = '#report_information_modal';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	if (typeof appMode != "undefined") {

		var $items = $flipsnap.find(".flipsnap_item");
		var loadingType = $items.eq(flipsnap.currentPoint).data('loading_type');
		var argument = $items.eq(flipsnap.currentPoint).data('argument');

		var argumentToString = '';
		if (argument) {
			for (var prop in argument) {
				argumentToString += prop + "=" + argument[prop] + "\n";
			}
		}

		fileData.append("report_page_information", loadingType + ' / ' + argumentToString);

	} else {

		fileData.append("report_page_information", uri_current);
		//alert(uri_current);

	}

	fileData.append("report_comment", $(setLocation + " #report_comment").val());
	//alert($(setLocation + " #report_comment").val());



	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------

	if (typeof appMode != "undefined") {
		fileData.append("app_mode", true);
	}


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/common/send_report.json",
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
			showAlert(setLocation + " #alert", response.alert_color, response.alert_title, response.alert_message);
		}

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		loadingToggle(arguThis, null);

	});

}
*/



/**
* ポップアップ　動画
*/
function popupMovie(arguThis) {

	var url = $(arguThis).data("url");
	//alert(url);
	//url = 'http://www.youtube.com/watch?v=zvU-stFPUGI';
	$.magnificPopup.open({
		items: {
			src: url
		},
		type: 'iframe'
	});

}



/**
* URL書き換え
*/
function rewriteUrl() {

	// アプリじゃない場合のみ
	if (typeof appMode == "undefined" && window.history && window.history.pushState) {

		var urlReplaced = uri_current.replace(uri_base, '');
		var urlSplitArr = urlReplaced.split('/');
		//alert(urlSplitArr);

		// タイトル変更
		// if (typeof title_base != "undefined") {
			// //document.title = title_base.text();
			// document.title = title_base;
		// }

		window.history.pushState('', '', uri_base + urlSplitArr[0] + '/' + urlSplitArr[1]);

		// ブラウザで戻った場合、再読み込み
		$(window).on('popstate', function() {
			location.href = uri_current;
		});

	}

}




/**
* URLとMetaを書き換え　読み込み前で必要な情報が存在しない場合は処理しない
*/
GAMEUSERS.common.changeUrlAndMeta = function(group, content) {


	// ---------------------------------------------
	//   URL変更
	// ---------------------------------------------
// console.log(contents_data, group, content);
	var state = contents_data[group + '_' + content].state;
	var url = contents_data[group + '_' + content].url;

	if (state && url) {
		window.history.pushState(state, null, url);
	}

	// console.log(contents_data.community_create.state);
	// console.log(contents_data, group, content);
	// console.log(state, url);

	// ---------------------------------------------
	//   meta書き換え
	// ---------------------------------------------

	var metaTitle = contents_data[group + '_' + content].meta_title;
	var metaKeywords = contents_data[group + '_' + content].meta_keywords;
	var metaDescription = contents_data[group + '_' + content].meta_description;

	if (metaTitle && metaKeywords && metaDescription) {
		GAMEUSERS.common.rewriteMeta(metaTitle, metaKeywords, metaDescription);
	}

	// console.log(metaTitle, metaKeywords, metaDescription);


};



/**
* URLを書き換え
*/
GAMEUSERS.common.changeUrl = function(group, content) {

	var state = contents_data[group + '_' + content].state;
	var url = contents_data[group + '_' + content].url;

	if (state && url) {
		window.history.pushState(state, null, url);
	}

};



/**
* Metaを書き換え
*/
GAMEUSERS.common.changeMeta = function(group, content) {

	var metaTitle = contents_data[group + '_' + content].meta_title;
	var metaKeywords = contents_data[group + '_' + content].meta_keywords;
	var metaDescription = contents_data[group + '_' + content].meta_description;

	if (metaTitle && metaKeywords && metaDescription) {
		document.title = GAMEUSERS.common.escapeHTML(metaTitle);
		$('meta[name=keywords]').attr('content', GAMEUSERS.common.escapeHTML(metaKeywords));
		$('meta[name=description]').attr('content', GAMEUSERS.common.escapeHTML(metaDescription));
	}

};




/**
* Meta書き換え
*/
GAMEUSERS.common.rewriteMeta = function(title, keywords, description) {

	//console.log(GAMEUSERS.common.escapeHTML(title));
	document.title = GAMEUSERS.common.escapeHTML(title);
	$('meta[name=keywords]').attr('content', GAMEUSERS.common.escapeHTML(keywords));
	$('meta[name=description]').attr('content', GAMEUSERS.common.escapeHTML(description));

	// $('meta[property=og:title]').attr('content', 'a');
	// $('meta[property=og:description]').attr('content', description);

};




/**
* アプリインストール広告変更
*/
function changeAdAppInstall(number, contentId) {

	if (contentId) {
		content_id = contentId;
	}

	if (number === 0) {
		$("#" + content_id + " [id^=ad_app_install_main_]").hide();
		$("#" + content_id + " [id^=ad_app_install_main_]").eq(0).show();
	} else {
		$("#" + content_id + " [id^=ad_app_install_main_]").hide();
		$("#" + content_id + " #ad_app_install_main_" + number).show();
	}

}





/**
* ヘルプ読み込み
*/
GAMEUSERS.common.readHelp = function(arguThis, page, list, content) {


	// --------------------------------------------------
	//   新しいデザインの場合　Ver2に送る　暫定処理　デザインがすべて対応したら削除すること
	// --------------------------------------------------

	if ($('#content_help_index')[0]) {
		GAMEUSERS.common.readHelpVer2(this, 1, list, content);
		return;
	}


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#tab_help';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	//loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	var first_load = false;
	if ( ! $(setLocation + ' #content_left_box')[0]) first_load = true;
	if (first_load) fileData.append('first_load', 1);

	fileData.append('page', page);
	if (list) fileData.append('list', list);
	if (content) fileData.append('content', content);

	//if ( ! $('#content_left_box')[0]) console.log('on');
	//console.log(list, content);
	// return;


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "rest/help/read_help.json",
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

		if (response.code_all) {
			$(setLocation).html(response.code_all);
		}

		if (response.code_list) {
			$(setLocation + ' #content_left_box').html(response.code_list);
		}

		if (response.code_content) {
			$(setLocation + ' #content_right_box').html(response.code_content);
		}


		// --------------------------------------------------
		//   画像の読み込み完了後処理
		// --------------------------------------------------

		$(setLocation).imagesLoaded( function() {


			// --------------------------------------------------
			//   swiper （スマートフォン、タブレット）
			// --------------------------------------------------

			if (agent_type !== '' && first_load) {

				GAMEUSERS.common.swiperHelp = new Swiper ('#swiper_container_help', {
					autoHeight: true,
					touchRatio: 1,
					touchAngle: 10,
					shortSwipes: false,
					longSwipesRatio: 0.1,
					longSwipesMs: 50,
					prevButton: '#content_prev_button_help',
					nextButton: '#content_next_button_help'
				});

				GAMEUSERS.common.swiperHelp.on('slideChangeStart', function () {
					scrollToAnchor('#swiper_container_help', -123, 0);
				});


				// 最初にコンテンツを表示するために、ページを進める
				GAMEUSERS.common.swiperHelp.slideNext();

			}


			// --------------------------------------------------
			//   左メニューの高さリセット
			// --------------------------------------------------

			if (agent_type === '') {

				if (GAMEUSERS.common.openedContent === 'help')
				{
					GAMEUSERS.common.contentFixed.setUp();

					// スクロール
					scrollToAnchor(setLocation + ' #content_right_box', -120, 0);
				}
				//

//console.log('first_load = ' + first_load);

				//scrollToAnchor(setLocation + ' #content_right_box', -(GAMEUSERS.common.headerBsTabMarginSize), 0);

			} else {

				GAMEUSERS.common.swiperHelp.update(true);

				// コンテンツだけ更新して、現在リストが表示されている場合は、次のスライドに進める
				if ( ! response.code_list && response.code_content && GAMEUSERS.common.swiperHelp.activeIndex === 0) {
					GAMEUSERS.common.swiperHelp.slideNext();
				}

			}

		});


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function(response) {

		// --------------------------------------------------
		//   アラート表示
		// --------------------------------------------------

		// if (response.alert_title) {
		// 	showAlert($(setLocation).find('#alert'), response.alert_color, response.alert_title, response.alert_message);
		// }

		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------

		//loadingToggle(arguThis, null);

	});

};







/**
* ヘルプ読み込み
*/
GAMEUSERS.common.readHelpVer2 = function(arguThis, page, list, content) {


	// --------------------------------------------------
	//   最初の読み込みの場合はスクロールしない
	// --------------------------------------------------

	var firstLoad = true;

	if ($('#help').html() !== '') {
		firstLoad = false;
		//console.log('help = ' + $('#help').html());
	}


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('page', page);
	if (list) fileData.append('list', list);
	if (content) fileData.append('content', content);


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $('#form_fuel_csrf_token').val();
	fileData.append('fuel_csrf_token', fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/help/read_help.json',
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

		// if (response.code_all) {
		// 	$(setLocation).html(response.code_all);
		// }

		if (response.code_list) {
			$('#menu_help').html(response.code_list);
		}

		if (response.code_content) {
			$('#help').html(response.code_content);
			//console.log($('#help'));
		}


		if ( ! firstLoad) {

			// サイドメニューを初期位置に戻す
			GAMEUSERS.common.stickyMenuAnimation(0, 600);

			// 上までスクロールする
			GAMEUSERS.common.stickyStuck = false;
			scrollToAnchor('main', -(GAMEUSERS.common.headerHeightVer2 + GAMEUSERS.common.tabHeightVer2), 0);

		}


		// --------------------------------------------------
		//   スマホの場合、スライドメニューを閉じる
		// --------------------------------------------------

		GAMEUSERS.common.closeSlideMenu();


	}).fail(function() {

		if (uri_base.indexOf('https://gameusers.org/') === -1) {
			alert('error');
		}

	}).always(function() {

	});

};



/**
* スマホ版のスライドメニューを閉じる
*/
GAMEUSERS.common.closeSlideMenu = function() {

	if (agent_type !== '' && $('#lastOverlay').is(':visible')) {
		//console.log('aaa');
		$('#lastOverlay').trigger('click');
	}
	//console.log($('#lastOverlay'));

};



/**
* エスケープ解除
*/
GAMEUSERS.common.escapeHTML = function(str) {
	//console.log(str, str.replace(/&#039;/g, "'"));

	return str.replace(/&#039;/g, "'");

	// return str.replace(/&amp;/g, '&')
	// 		  .replace(/&lt;/g, '<')
	// 		  .replace(/&gt;/g, '>')
	// 		  .replace(/&quot;/g, '"')
	// 		  .replace(/&amp;#039;/g, "BBB");
};



//
// function printProperties(obj) {
//     var properties = '';
//     for (var prop in obj) {
//         properties += prop + "=" + obj[prop] + "\n";
//     }
//     alert(properties);
// }
