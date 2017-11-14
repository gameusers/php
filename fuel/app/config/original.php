<?php
/**
 * オリジナル設定
 */

return array(

	// --------------------------------------------------
	//    テストモード
	//    本番環境では必ずfalseにすること
	// --------------------------------------------------

	// 環境をtestに切り替える
	'test_mode' => false,


	// --------------------------------------------------
	//    CSRFチェック　falseにするとチェックしない
	//    本番環境では必ずtrueにすること
	// --------------------------------------------------

	'check_csrf_token' => true,


	// --------------------------------------------------
	//    共通
	// --------------------------------------------------

	// メンテナンス
	// 0 : 通常運転
	// 1 : 書き込み禁止
	// 2 : 読み込み書き込み禁止
	'maintenance' => 0,


	// 管理者のID / default =
	'admin_id_arr' => array('8fzzzp0h5zcpagepfdl9y3i95'),

	// オンラインリミット / default = 24
	'online_limit' => 24,

	// ページャーの数字表示回数 / default = 10 / 5
	'pagination_times' => 10,
	'pagination_times_sp' => 5,


	// Acccess Dateの更新間隔 $datetime->modify($string)の形式で指定 / default = '-1minutes'
	'renew_access_date_interval' => '-1minutes',


	// ゲーム登録の更新ログ　保存件数 / default = 50
	'limit_registration_game_data_log' => 50,

	// 通知・メール一斉送信　一回の処理人数　Cron / default = 300
	'limit_mail_task' => 300,

	// 利用規約最新バージョン / default = 20150803
	'user_terms_version' => '20150803',



	// お知らせの保存期間　$datetime->modify($string)の形式で指定 / default = '-3 months'
	'limit_notification_time' => '-3 months',
	// 'limit_notification_time' => '-3 years',

	// お知らせの表示件数 / default = 3
	'limit_notification' => 3,
	'limit_notification_sp' => 3,
	'limit_notification_app' => 3,


	// スライドゲームリスト / default = 30
	'limit_slide_game_list' => 30,
	'limit_slide_game_list_sp' => 30,


	// フィード検索件数　すべてのフィード / default = 100
	'limit_feed_search_all' => 100,

	// フィード検索件数　個別のフィード / default = 500
	'limit_feed_search' => 500,

	// フィード検索期間 / default = '-1 year'
	'limit_feed_search_term' => '-1 year',

	// フィード表示件数 / default = 50
	'limit_feed' => 50,






	// --------------------------------------------------
	//    BBS
	// --------------------------------------------------

	// BBSのスレッド一覧表示件数 / default = 10
	'limit_bbs_thread_list' => 10,
	'limit_bbs_thread_list_sp' => 10,

	// BBSのスレッド表示件数 / default = 3
	'limit_bbs_thread' => 3,
	'limit_bbs_thread_sp' => 3,

	// BBSのコメント表示件数 / default = 10
	'limit_bbs_comment' => 10,
	'limit_bbs_comment_sp' => 10,

	// BBSの返信表示件数 / default = 10
	'limit_bbs_reply' => 10,
	'limit_bbs_reply_sp' => 10,


	// BBSのスレッド　画像表示件数 / default = 1
	'limit_bbs_thread_image' => 1,

	// BBSのコメント　画像表示件数 / default = 1
	'limit_bbs_comment_image' => 1,

	// BBSの返信　画像表示件数 / default = 1
	'limit_bbs_reply_image' => 1,


	// BBSのスレッド　動画表示件数 / default = 1
	'limit_bbs_thread_movie' => 1,

	// BBSのコメント　動画表示件数 / default = 1
	'limit_bbs_comment_movie' => 1,

	// BBSの返信　動画表示件数 / default = 1
	'limit_bbs_reply_movie' => 1,


	// --------------------------------------------------
	//    トップ
	// --------------------------------------------------

	// 募集の表示件数 / default = 20
	'index_limit_recruitment' => 20,
	'index_limit_recruitment_sp' => 20,

	// GC交流掲示板の表示件数 / default = 20
	'index_limit_bbs' => 20,
	'index_limit_bbs_sp' => 20,

	// ゲーム一覧の表示件数 / default = 50
	'index_search_game_list' => 50,
	'index_search_game_list_sp' => 50,

	// コミュニティの表示件数 / default = 20
	'index_limit_community' => 20,
	'index_limit_community_sp' => 20,

	// ゲーム登録フォームの表示件数 / default = 5
	'index_search_game_data_form' => 5,
	'index_search_game_data_form_sp' => 5,


	// --------------------------------------------------
	//    プレイヤー
	// --------------------------------------------------

	// 画像表示件数 / default = 3
	'limit_player_top_image' => 3,

	// プロフィールの表示件数 / default = 5
	'limit_profile' => 5,
	'limit_profile_sp' => 5,

	// 当選者発表　表示期限 $datetime->modify($string)の形式で指定 / default = '-1minutes'
	'limit_announcement_winner_date' => '-3 months',

	// 広告コードの1ページあたりの表示件数 / default = 10
	'limit_edit_advertisement' => 10,
	'limit_edit_advertisement_sp' => 10,

	// 広告コードの保存できる最大数 / default = 10
	'limit_advertisement' => 10,


	// --------------------------------------------------
	//    ユーザーコミュニティ
	// --------------------------------------------------

	// 告知　画像・動画表示件数 / default = 1
	'limit_announcement_image' => 1,
	'limit_announcement_movie' => 1,

	// メンバーの表示件数 / default = 20
	'limit_member' => 20,
	'limit_member_sp' => 20,


	// プロフィール選択の表示件数 / default = 5
	'limit_select_profile_form' => 5,
	'limit_select_profile_form_sp' => 5,


	// メール一斉送信　一日に送信できる回数 / default = 3
	'limit_mail_all' => 3,

	// メール一斉送信　メール送信者のログ保存件数 / default = 20
	'limit_mail_all_send_user_log' => 20,

	// 基本設定　関連ゲーム限界数 / default = 10
	'limit_regist_game' => 10,



	// --------------------------------------------------
	//    ゲームコミュニティ
	// --------------------------------------------------

	// 募集　募集表示件数 / default = 10
	'limit_recruitment' => 10,
	'limit_recruitment_sp' => 10,

	// 募集　返信表示件数 / default = 10
	'limit_recruitment_reply' => 10,
	'limit_recruitment_reply_sp' => 10,

	// 募集　画像表示件数 / default = 1
	'limit_recruitment_image' => 1,

	// 募集　動画表示件数 / default = 1
	'limit_recruitment_movie' => 1,

	// ID登録・編集フォームの一覧で表示する数 / default = 5
	'limit_edit_game_id_form' => 5,
	'limit_edit_game_id_form_sp' => 5,

	// 募集投稿フォームで表示する選択IDの数 / default = 10
	'limit_form_select_id' => 10,
	'limit_form_select_id_sp' => 10,



	// --------------------------------------------------
	//    プレゼント
	// --------------------------------------------------

	// 抽選ユーザー表示件数 / default = 30
	'limit_present_users' => 30,
	'limit_present_users_sp' => 30,

	// ポイント付与　ゲーム登録 / default = 2
	'present_point_register_game' => 2,

	// ポイント付与　募集投稿 / default = 5
	'present_point_recruitment' => 5,

	// ポイント付与　募集に返信 / default = 1
	'present_point_recruitment_reply' => 1,

	// ポイント付与　GC　交流BBSスレッド作成 / default = 1
	'present_point_gc_bbs_thread' => 1,

	// ポイント付与　GC　交流BBSコメント投稿 / default = 1
	'present_point_gc_bbs_comment' => 1,

	// ポイント付与　GC　交流BBS返信投稿 / default = 1
	'present_point_gc_bbs_reply' => 1,



	// --------------------------------------------------
	//    広告
	// --------------------------------------------------

	// インストールアプリ広告、表示件数 / default = 15 / 5
	'limit_app_install' => 15,
	'limit_app_install_sp' => 5,

	// アマゾンスライド広告 / default = 30 / 20
	'limit_ad_amazon_slide' => 30,
	'limit_ad_amazon_slide_sp' => 20,



	// --------------------------------------------------
	//    Wiki
	// --------------------------------------------------

	// Wikiの一覧 / default = 20
	'limit_wiki_list' => 20,
	'limit_wiki_list_sp' => 20,

	// サイドメニューのBBS情報 / default = 5
	'limit_wiki_read_bbs' => 5,
	'limit_wiki_read_bbs_sp' => 5,



	// --------------------------------------------------
	//    フッター
	// --------------------------------------------------

	// フッターのカード数 / default = 30
	'limit_footer_card' => 30,
	'limit_footer_card_sp' => 30,




	// ----- スタイルシート -----

	// CSS Bootstrap デザイン
	'css_bootstrap' => 'bootstrap/bootstrap-3.3.7.css',

	// CSS ボタンにローディング機能
	'css_ladda' => 'ladda/ladda-themeless.min.css',

	// CSS typeahead オートコンプリート
	'css_typeahead' => 'bootstrap/typeahead.css',

	// CSS 通知 / PNotify
	'css_jquery_pnotify' => 'jquery/pnotify.custom.min.css',

	// CSS モーダルウィンドウ / Magnific Popup
	'css_jquery_magnific_popup' => 'jquery/magnific-popup.css',

	// CSS Swiper / 横スクロール
	'css_jquery_swiper' => 'jquery/swiper.min.css',

	// CSS Swiper / divスクロール
	'css_jquery_perfect_scrollbar' => 'jquery/perfect-scrollbar.min.css',

	// CSS Auto-Hiding Navigation / タブ
	'css_jquery_auto_hiding_navigation' => 'jquery/auto-hiding-navigation.min.css',

	// CSS contextMenu / コンテキストメニュー
	'css_jquery_contextMenu' => 'jquery/jquery.contextMenu.min.css',

	// CSS スマホ用サイドメニュー
	'css_lastsidebar' => 'lastsidebar/last-sidebar.css',

	// CSS AOS / カードの動き
	'css_aos' => 'aos/aos.css',





	// CSS リセット
	'css_reset_min' => 'reset.min.css',

	// CSS Basic デザイン
	'css_basic' => 'basic.css',

	// CSS Basic デザイン 圧縮
	'css_basic_min' => 'basic.min.css',



	// ----- Javascript -----

	// Javascript jQuery
	'js_jquery' => 'jquery/jquery-3.1.1.min.js',

	// Javascript jQuery Cookie
	'js_jquery_cookie' => 'jquery/jquery.cookie.min.js',

	// Javascript Bootstrap デザイン
	'js_bootstrap' => 'bootstrap/bootstrap-3.3.7.min.js',

	// Javascript イージング機能
	'js_jquery_easing' => 'jquery/jquery.easing.1.3.js',

	// Javascript フリックスライドショー
	'js_jquery_flipsnap' => 'jquery/flipsnap.min.js',

	// Javascript テキストエリアの高さ自動調整
	'js_jquery_autosize' => 'jquery/autosize.min.js',

	// Javascript FastClick
	'js_jquery_fastclick' => 'jquery/fastclick.js',

	// Javascript 通知 / PNotify
	'js_jquery_pnotify' => 'jquery/pnotify.custom.min.js',

	// Javascript モーダルウィンドウ / Magnific Popup
	'js_jquery_magnific_popup' => 'jquery/jquery.magnific-popup.min.js',

	// Javascript 画像読み込み後に処理をする　Divの高さを正確に取得するため
	'js_jquery_imagesloaded' => 'jquery/imagesloaded.pkgd.min.js',

	// Javascript Swiper / 横スクロール
	'js_jquery_swiper' => 'jquery/swiper.jquery.min.js',

	// Javascript Swiper / divスクロール
	'js_jquery_perfect_scrollbar' => 'jquery/perfect-scrollbar.jquery.min.js',

	// Javascript Auto-Hiding Navigation / タブ
	'js_jquery_auto_hiding_navigation' => 'jquery/auto-hiding-navigation.js',

	// Javascript スマホ用サイドメニュー
	'js_lastsidebar' => 'lastsidebar/jquery.last-sidebar.min.js',

	// Javascript jRumble / 要素を震わせることができる
	'js_jquery_jrumble' => 'jquery/jquery.jrumble.1.3.min.js',

	// Javascript sticky-kit / サイドメニュー固定
	'js_jquery_sticky-kit' => 'jquery/sticky-kit.min.js',

	// Javascript trunk8 / 文字を…に省略
	'js_jquery_trunk8' => 'jquery/trunk8.min.js',

	// Javascript contextMenu / コンテキストメニュー
	'js_jquery_contextMenu' => 'jquery/jquery.contextMenu.min.js',

	// Javascript contextMenu / UIポジション
	'js_jquery_ui_position' => 'jquery/jquery.ui.position.min.js',


	// Javascript Inputオートコンプリート
	'js_typeahead' => 'typeahead/typeahead.bundle.min.js',

	// Javascript ボタンにローディング機能 1
	'js_ladda_spin' => 'ladda/spin.min.js',

	// Javascript ボタンにローディング機能 2
	'js_ladda' => 'ladda/ladda.min.js',

	// Javascript 多国語化1
	'js_globalize' => 'globalize/globalize.js',

	// Javascript 多言語化2
	'js_globalize_culture_ja' => 'globalize/globalize.culture.ja.js',

	// Javascript 多言語化
	'js_i18next' => 'i18next/i18next-1.7.7.min.js',

	// Javascript AOS / カードの動き
	'js_aos' => 'aos/aos.js',

	// Javascript Masonry / カードを並べる
	'js_masonry' => 'masonry/masonry.pkgd.min.js',





	// Javascript 基本
	'js_basic' => 'basic.js',

	// Javascript 基本 圧縮
	'js_basic_min' => 'basic.min.js',

	// Javascript 共通
	'js_common' => 'common.js',

	// Javascript 共通 圧縮
	'js_common_min' => 'common.min.js',


	// Javascript Web Push
	'js_webpush' => 'webpush/webpush.js',

	// Javascript 共通 圧縮
	'js_webpush_min' => 'webpush/webpush.min.js',



	// Javascript Google Adwords / コンバージョン　募集投稿
	'js_adwords_post_recruitment' => 'adwords/post_recruitment.js',

	// Javascript Google Adwords / コンバージョン　基本
	'js_adwords_conversion_async' => 'https://www.googleadservices.com/pagead/conversion_async.js',




	// ----- その他 -----

	// お問い合わせ・通報先メールアドレス
	'inquiry_mail_address' => '',

	// Twitter gameusersorg
	'twitter_consumer_key' => '',
	'twitter_consumer_secret' => '',
	'twitter_access_token' => '',
	'twitter_access_token_secret' => '',

	// Twitter ハッシュタグ
	'twitter_hashtag' => 'GameUsers',

	// Amazon ウェブサービス
	'aws_access_key' => '',
	'aws_secret_key' => '',
	'amazon_tracking_id' => 'gameusers-22',

	// Google Cloud Messaging - APIキー
	'gcm_api_key' => '',

	// 暗号化キー
	'crypter_key' => '7iicaYODGE5omMxKiQlwM0AunuoleKfo',

	// Open SSL 暗号化キー
	'openssl_encryption_key' => 'Fk9W4y2jwCEJ6mWkUq11gkuo25DFgpr3',


	// Stripe Publishable key / Test Mode
	'stripe_publishable_key_test_mode' => 'pk_test_njyv70ZdCeEbK0nHEcF8YqDz',

    // Stripe Secret key / Test Mode
	'stripe_secret_key_test_mode' => '',

    // Stripe Publishable key
	'stripe_publishable_key' => '',

    // Stripe Publishable key
	'stripe_secret_key' => ''

);
