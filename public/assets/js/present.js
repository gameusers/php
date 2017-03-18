$(function() {
	
	// アプリでない場合、DOM読み込み後、初期化
	// アプリの場合、app.jsですべて読み込んだ後で初期化
	if(typeof appMode == "undefined") {
		initPresent(content_id);
	}
	
});



/**
* 初期処理
*/
function initPresent(contentId) {
	
	// --------------------------------------------------
	//   Bootstrapタブ
	// --------------------------------------------------
	
	$("#" + contentId + " #bsTab a").on('click', function(e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
}




/**
* 抽選エントリーユーザー読み込み
*/
function readPresentEntryUsers(arguThis, page, previous) {
	//alert('aaa');
	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------
	
	//var contentId = getContentId(arguThis);
	//var setLocation = "#" + contentId + " #present_lottery_user_list";
	var setLocation = $(arguThis).closest("#present_entry_users");
	
	
	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------
	
	loadingToggle(arguThis, null);
	
	
	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------
	
	var fileData = new FormData();
	
	fileData.append("page", page);
	if (previous) fileData.append("previous", previous);
	
	
	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------
	
	if(typeof appMode != "undefined") {
		fileData.append("app_mode", true);
	}
	
	//alert('aaa');
	//return;
	
	$.ajax({
		
		url: uri_base + "rest/present/read_present_entry_users.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false
		
	}).done(function(response) {
		//alert(response.code);
		if (response.code) {
			$(setLocation).html(response.code);
		}
		
	}).fail(function() {
		
		alert('error');
		
	}).always(function() {
		
		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------
		
		loadingToggle(arguThis, null);
		
	});
	
}




/**
* プレゼントユーザー抽選・編集フォーム読み込み
*/
function showPresentUserEditForm(arguThis, regiDate, type, userNo, profileNo) {
	
	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------
	
	var contentId = getContentId(arguThis);
	var setLocation = "#" + contentId;
	
	
	// --------------------------------------------------
	//   確認ダイアログ表示
	// --------------------------------------------------
	
	if (type == 'edit') {
		var confirm_text = 'Amazonギフト券のコード情報などが表示されます。よろしいですか？';
		
		 if ( ! window.confirm(confirm_text)) {
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
	
	if (regiDate) {
		fileData.append("regi_date", regiDate);
	} else {
		fileData.append("regi_date", $(setLocation + " #regi_date").val());
	}
	
	fileData.append("type", type);
	if (userNo) fileData.append("user_no", userNo);
	if (profileNo) fileData.append("profile_no", profileNo);
	//alert($("#" + contentId + " #regi_date").val());
	
	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------
	
	if(typeof appMode != "undefined") {
		fileData.append("app_mode", true);
	}
	
	//alert('aaa');
	//return;
	
	$.ajax({
		
		url: uri_base + "rest/present/show_present_user_edit_form.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false
		
	}).done(function(response) {
		//alert(response.code);
		if (response.code) {
			$(setLocation + " #present_user_edit").hide();
			$(setLocation + " #present_user_edit").html(response.code);
			$(setLocation + " #present_user_edit").show(1000);
		}
		
	}).fail(function() {
		
		alert('error');
		
	}).always(function() {
		
		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------
		
		loadingToggle(arguThis, null);
		
	});
	
}




/**
* 当選者情報保存・編集
* 管理者のみ
*/
function savePresentUser(arguThis, presentNo) {
	
	// --------------------------------------------------
	//   場所設定
	// --------------------------------------------------
	
	var contentId = getContentId(arguThis);
	var setLocation = "#" + contentId + " #present_user_edit";
	
	
	// --------------------------------------------------
	//   ローディング開始
	// --------------------------------------------------
	
	loadingToggle(arguThis, null);
	
	
	// --------------------------------------------------
	//   フォーム送信データ作成
	// --------------------------------------------------
	
	var fileData = new FormData();
	
	fileData.append("present_no", presentNo);
	
	fileData.append("type", $(setLocation + " #type").val());
	fileData.append("sum", $(setLocation + " #sum").val());
	fileData.append("unit", $(setLocation + " #unit").val());
	fileData.append("code", $(setLocation + " #code").val());
	//alert(presentNo + '/' + $(setLocation + " #type").val() + '/' + $(setLocation + " #sum").val() + '/' + $(setLocation + " #unit").val());
	
	// --------------------------------------------------
	//   App Mode
	// --------------------------------------------------
	
	if(typeof appMode != "undefined") {
		fileData.append("app_mode", true);
	}
	
	
	$.ajax({
		
		url: uri_base + "rest/present/save_present_user.json",
		dataType: "json",
		type: "POST",
		data: fileData,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false
		
	}).done(function() {
		
		// リダイレクト
		location.href = uri_current;
		
	}).fail(function() {
		
		alert('error');
		
	}).always(function() {
		//alert(presentNo);
		// --------------------------------------------------
		//   ローディング停止
		// --------------------------------------------------
		
		loadingToggle(arguThis, null);
		
	});
	
}
