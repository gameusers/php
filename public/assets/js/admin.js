// --------------------------------------------------
//   名前空間設定
// --------------------------------------------------

GAMEUSERS.namespace('GAMEUSERS.admin.wikiCopyTemplate');



$(function() {

	// テキストエリア自動リサイズ
	autosize($("textarea"));

});



/**
* Eメール削除
*/
function deleteEmail() {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = "#delete_email";


	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingStart(setLocation + " #submit");


	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	fileData.append("email", $(setLocation + " #email").val());
	//alert($(setLocation + " #email").val());

	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);


	$.ajax({

		url: uri_base + "/rest/admin/delete_email.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function(response) {

		if (response.alert_color == 'success') {
			$(setLocation + " #email").val('');
		}

	}).fail(function(response) {

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

		loadingStop(setLocation + " #submit");

	});

}





/**
* Wikiのテンプレートから各Wikiにコピーする　plugin、lib、skin、、pukiwiki.ini.phpなど
*/
GAMEUSERS.admin.wikiCopyTemplate = function(arguThis) {


	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------

	var setLocation = $(arguThis).closest("#wiki_copy_template");



	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------

	loadingToggle(arguThis, null);



	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------

	var fileData = new FormData();

	if ($(setLocation).find('#plugin').prop('checked')) {
		fileData.append('plugin', $(setLocation).find('#plugin').prop('checked'));
	}

	if ($(setLocation).find('#lib').prop('checked')) {
		fileData.append('lib', $(setLocation).find('#lib').prop('checked'));
	}

	if ($(setLocation).find('#skin').prop('checked')) {
		fileData.append('skin', $(setLocation).find('#skin').prop('checked'));
	}

	if ($(setLocation).find('#pukiwiki_ini').prop('checked')) {
		fileData.append('pukiwiki_ini', $(setLocation).find('#pukiwiki_ini').prop('checked'));
	}

	if ($(setLocation).find('#etc').prop('checked')) {
		fileData.append('etc', $(setLocation).find('#etc').prop('checked'));
	}


	//console.log($(setLocation).find('#plugin').prop('checked'));
	//return;

	// --------------------------------------------------
	//   CSRF Token
	// --------------------------------------------------

	var fuel_csrf_token = $("#form_fuel_csrf_token").val();
	fileData.append("fuel_csrf_token", fuel_csrf_token);



	$.ajax({

		url: uri_base + "/rest/admin/wiki_copy_template.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false

	}).done(function() {

		// if (response.alert_color == 'success') {
			// $(setLocation + " #email").val('');
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
