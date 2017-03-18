//var onEventType;


//$(function() {

	// --------------------------------------------------
	//   クリックとタッチ
	// --------------------------------------------------
	/*
	if(typeof appMode != "undefined" || agent_type != "") {
		onEventType = 'touchstart touchmove touchend';
	} else {
		onEventType = 'click';
	}


	// --------------------------------------------------
	//   イベント追加
	// --------------------------------------------------

	// ページ移動
	$(document).on(onEventType, "#change_page", function(e) {

		// アプリ
		if (typeof appMode != "undefined") {
			alert('app');
			e.preventDefault();
			var argument = $(this).data("argument");

			if (spClickTouch(this, e)) {
				changePage(argument);
			}

		// モバイル機器の場合、移動
		} else if (agent_type != "") {
			//alert('mobile');
			e.preventDefault();
			if (spClickTouch(this, e)) {
				//alert($(this).attr('href'));
				location.href = $(this).attr('href');
			}

		// PCの場合、処理しない
		} else {
			alert('pc');
			return;
		}

	});
	*/
//});



/**
* ローディング開始
* @param {string} id_name ID名
*/
function loadingStart(selector) {
	try{
		//alert(document.querySelector(selector));
		var l = Ladda.create(document.querySelector(selector));
		//alert($(selector));
		//var l = Ladda.create($(selector));
		//var l = Ladda.create(document.querySelectorAll(selector));
		l.start();

	} catch(e) {}
}


/**
* ローディング停止
* @param {string} id_name ID名
*/
function loadingStop(selector) {
	try{
		var l = Ladda.create(document.querySelector(selector));

		l.stop();
	} catch(e) {}
}


/**
* ローディング開始・停止
* @param {string} arguThis this
* @param {string} id_name ID名
*/

var laddaInstance = null;

function loadingToggle(arguThis, selector) {

	try{

		if ( ! arguThis && ! selector) return;

		if (laddaInstance === null) {

			if (arguThis) {
				laddaInstance = Ladda.create(arguThis);
			} else if (selector) {
				laddaInstance = Ladda.create(document.querySelector(selector));
			}

			laddaInstance.start();

		} else {

			laddaInstance.stop();
			laddaInstance = null;

		}

	} catch(e) {}
	
}



/**
* アラート表示
* @param {string} selector セレクター
* @param {string} color 色指定
* @param {string} title アラートタイトル
* @param {string} message アラートメッセージ
*/
function showAlert(selector, color, title, message) {

    var code = '<div class="alert alert-' + color + ' fade in">';
    code += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    code += '<strong>' + title + ' : </strong> ' + message;
    code += '</div>';

    $(selector).html(code);

}


/**
* スクロール
* @param {string} selector セレクター
*/
function scrollToAnchor(selector, blankSize, durationSize) {

    var offset = $(selector).offset().top + blankSize;
    $('html,body').animate({ scrollTop: offset }, {duration:durationSize, easing:"easeOutQuart"});

    return false;

}


/**
 * 快適なタッチ操作
 * @param {string} arguThis this
 * @param {string} e イベント
 */
/*
function spClickTouch(arguThis, e) {
	//
	if (e.type == 'click') {
		return true;
	}

	var isTouch = $(arguThis).data('isTouch');

	if (e.type == 'touchstart') {
		$(arguThis).data('isTouch', true);
		//$("#testpaper").text('touchstart/' + isTouch);
		return false;
	}

	if (e.type == 'touchmove') {
		$(arguThis).data('isTouch', false);
		//$("#testpaper").text('touchmove/' + isTouch);
		return false;
	}

	if (e.type == 'touchend' && isTouch) {
		//$("#testpaper").text('touchend/' + isTouch);
		return true;
	}

}
*/

/**
* テスト用　URL置き換え　localhostのままだとPhoneGapで画像がエラーになる
* @param {string} url URL
*/
/*
function changePage(url) {

    if(typeof testMode != "undefined"){
    	url = url.replace(/localhost/g, '192.168.10.2');
    }

    return url;

}
*/

/**
* テスト用　URL置き換え　localhostのままだとPhoneGapで画像がエラーになる
* @param {string} url URL
*/
function testChangeUrl(url) {

    if(typeof testMode != "undefined"){
    	url = url.replace(/localhost/g, '192.168.10.2');
    }

    return url;

}
