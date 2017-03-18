// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.help');


// --------------------------------------------------
//   プロパティ
// --------------------------------------------------

GAMEUSERS.common.openedContent = 'help';

//var swiperHelp;


$(function() {


	// --------------------------------------------------
	//   Bootstrapタブ
	// --------------------------------------------------

	$("#bsTab a").on('click', function(e) {
		e.preventDefault();
		$(this).tab('show');
	});


	// --------------------------------------------------
	//   タブをクリックした時、タブ上部に移動する
	// --------------------------------------------------

	if ($('#bsTab')[0]) {

		$('#bsTab li a').on('shown.bs.tab', function (e) {

			var href = $(this).attr("href");
			GAMEUSERS.common.openedContent = href.split('_')[1];


			// PCの場合の処理
			if (agent_type === '') {

				// コンテンツ固定　スクロール
				if (GAMEUSERS.common.openedContent == 'bbs' || GAMEUSERS.common.openedContent == 'recruitment') {

					GAMEUSERS.common.contentFixed.setUp();

					scrollToAnchor('#content_' + GAMEUSERS.common.openedContent + ' #content_right_box', -120, 0);
				}

			} else {
				if (GAMEUSERS.common.openedContent == 'recruitment') {
					swiperRecruitment.update(true);
				}
			}

		});

	}



	// --------------------------------------------------
	//   メニューを固定　 （PC）
	// --------------------------------------------------

	if (agent_type === '') {
		GAMEUSERS.common.contentFixed.setUp();
	}



	// --------------------------------------------------
	//   swiper （スマートフォン、タブレット）
	// --------------------------------------------------

	if (agent_type !== '') {

		GAMEUSERS.common.swiperHelp = new Swiper ('#swiper_container_help', {
			// loop: true,
			// initialSlide: 1,
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
	//   画像モーダル表示
	// --------------------------------------------------

	// $('.content_box').magnificPopup({
	// 	delegate: '[id=modal_image]',
	// 	type:'image'
	// });



	// --------------------------------------------------
	//   テキストエリア自動リサイズ
	// --------------------------------------------------

	// $('textarea').on('focus', function(){
	// 	autosize($(this));
	// });






});








/**
* お問い合わせ送信
*/
GAMEUSERS.help.sendInquiry = function(arguThis) {

	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = '#tab_inquiry';


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("inquiry_name", $(setLocation + " #inquiry_name").val());
	fileData.append("inquiry_email", $(setLocation + " #inquiry_email").val());
	fileData.append("inquiry_comment", $(setLocation + " #inquiry_comment").val());

	//console.log($(setLocation + " #inquiry_name").val());
	//return;


	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + 'rest/help/send_inquiry.json',
		dataType: 'json',
		type: 'POST',
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

};
