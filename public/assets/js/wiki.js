var uriBase;
var gameDataId;


$(function() {

	var splitArr = location.href.split("/wiki/");
	var splitArr2 = splitArr[1].split("/");
	uriBase = splitArr[0];
	wikiId = splitArr2[0];

	// console.log(gameDataId);
	// return;


	// --------------------------------------------------
	//   ソーシャルボタン
	// --------------------------------------------------

	if ($('#swiper_container_social_button')[0]) {

		swiperSocialButton = new Swiper('#swiper_container_social_button', {
			slidesPerView: 'auto',
			freeMode: true
		});

		// カウント取得
		var url = location.href;

		socialCountTwitter(url);
		socialCountFacebook(url);
		socialCountGooglePlus(url);
		socialCountHatena(url);
		socialCountPocket(url);

	}


	// --------------------------------------------------
	//   BBSを読み込む
	// --------------------------------------------------

	readBbs();


	// --------------------------------------------------
	//   広告を読み込む
	// --------------------------------------------------

	readAdvertisement();

});






/**
* ソーシャル　シェア送信
*/
socialShare = function(arguThis) {


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

	if (uriBase.indexOf('gameusers.org') !== -1) {
		ga('send', 'social', siNetwork, siAction, "'" + location.href + "'");
	}


};




/**
* ソーシャルボタン　カウント　Twitter
*/
socialCountTwitter = function(url) {


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
		dataType: "jsonp",
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
socialCountFacebook = function(url) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#swiper_container_social_button #facebook span';


	$.ajax({

		url: 'https://graph.facebook.com/',
		dataType: "jsonp",
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
socialCountGooglePlus = function(url) {


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

		url: uriBase + "/rest/social/count_google_plus.json",
		dataType: "json",
		type: "POST",
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
socialCountHatena = function(url) {

//url = 'https://gameusers.org/';
	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#swiper_container_social_button #hatena span';


	$.ajax({

		url: 'https://b.hatena.ne.jp/entry.count',
		dataType: "jsonp",
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
socialCountPocket = function(url) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#swiper_container_social_button #pocket span';


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append('url', url);



	$.ajax({

		url: uriBase + "/rest/social/count_pocket.json",
		dataType: "json",
		type: "POST",
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
* ポップアップ　動画
*/
function popupMovie(arguThis) {

	var url = $(arguThis).data("url");

	$.magnificPopup.open({
		items: {
			src: url
		},
		type: 'iframe'
	});

}



/**
* BBSを読み込む
*/
function readBbs() {


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();
	fileData.append('wiki_id', wikiId);


	$.ajax({

		url: uriBase + "/rest/wiki/read_bbs_code.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.code) {
			$("#gu_menu_read_bbs").html(response.code);
		}

	}).fail(function() {

		//alert('error');

	}).always(function() {



	});

}



/**
* 広告を読み込む
*/
function readAdvertisement() {


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();
	fileData.append('wiki_id', wikiId);


	var adNameArr = [];

	$.each($('.gu_ad_user'), function() {
		//console.log($(this).attr('id'));

		var splitArr = $(this).attr('id').split('gu_ad_user_');
		adNameArr.push(splitArr[1]);

		//console.log(splitArr);

	});

	fileData.append('ad_name', adNameArr);

	//console.log(adNameArr);



	$.ajax({

		url: uriBase + "/rest/wiki/read_advertisement_code.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		//console.log(response.default_arr);

		// --------------------------------------------------
		//   デフォルト広告
		// --------------------------------------------------

		if (response.default_arr) {

			$.each(response.default_arr, function(i, val) {
				$("#gu_ad_default_" + i).html(val);

			});

		}

		// --------------------------------------------------
		//   ユーザー広告
		// --------------------------------------------------

		if (response.user_arr) {
			//$("#gu_ad_4").html('aaaaa');

			$.each(response.user_arr, function(i, val) {
				$("#gu_ad_user_" + i).html(val);

			});
		}


		// --------------------------------------------------
		//   Amazonスライド広告
		// --------------------------------------------------

		if (response.amazon_slide) {

			$("#gu_ad_amazon_slide").html(response.amazon_slide);

			if ($('#swiper_container_slide_amazon_ad')[0]) {

				swiperSlideAmazonAd = new Swiper('#swiper_container_slide_amazon_ad', {
					slidesPerView: 'auto',
					freeMode: true,
					freeModeMomentumRatio: 0.5,
					autoplay: 2500,
					autoplayDisableOnInteraction: false,
					loop: true
			    });

				$('[data-toggle="tooltip"]').tooltip({
					container: 'body'
				});

			}

		}

	}).fail(function() {

		//alert('error');

	}).always(function() {



	});

}
