<?php

return array(
	'required' => ':labelが入力されていません。',
	'min_length' => ':labelが:param:1文字未満です。',
	'max_length' => ':labelが:param:1文字を超えています。',
	'exact_length' => ':labelが:param:1文字ではありません。',
	'match_value' => ':labelが:param:1と一致しません。',
	'match_pattern' => ':labelが一致するパターンではありません。',
	'match_field' => ':labelが:param:1と異なります。',
	'valid_email' => '正しいメールアドレスではありません。',
	'valid_emails' => ':labelに不正なメールアドレスが含まれています。',
	'valid_url' => ':labelは正しいURLではありません。',
	'valid_ip' => ':labelは正しいIPアドレスではありません。',
	'numeric_min' => ':labelの値が:param:1未満です。',
	'numeric_max' => ':labelの値が:param:1を超えています。',
	'numeric_between' => ':labelの値は :param:1 ～ :param:2の間の数字でなければなりません。',
	'valid_string' => ':labelに不正な文字が含まれています。',
	'required_with'   => 'The field :label must contain a value if :param:1 contains a value.',
	'valid_date'      => 'The field :label must contain a valid formatted date.',
	
	// 共通
	'check_url' => 'ログインしていない場合、URLを含めることはできません。',
	
	// Game Users
	'login_username_duplication' => '入力したIDは使用することができません。',
	'email_duplication_users_login' => 'そのメールアドレスはすでに登録されています。',
	'email_duplication_provisional_mail' => 'すでに仮登録メールが送信されています。仮登録メールを送信できるのは一日一回までです。',
	
	'community_id_duplication' => 'そのコミュニティIDは使えません。',
	'user_id_duplication' => 'そのプレイヤーIDは使えません。',
	
	// Cod Ghosts
	'cod_ghosts_secondary' => 'Secondary Error',
	'cod_ghosts_primary_attachment' => 'Primary Attachment Error',
	'cod_ghosts_secondary_attachment' => 'Secondary Attachment Error',
	'cod_ghosts_perk' => 'Perk Error',
	'cod_ghosts_strike_package_type' => 'Strike Package Type Error',
	'cod_ghosts_strike_package' => 'Strike Package Error',
	'cod_ghosts_strike_package_specialist' => 'Strike Package Specialist Error',
	'cod_ghosts_rule' => 'Rule Error',
	'cod_ghosts_title' => 'タイトルにURLを含めることはできません。',
	'cod_ghosts_explanation' => 'ログインしていない場合、説明文にURLを含めることはできません。',
	'cod_ghosts_user_info_type' => 'User Info Type Error',
	
	'cod_ghosts_equipment_no' => 'Loadout Number Error',
	'cod_ghosts_comment_no' => 'Comment Number Error',
	'cod_ghosts_reply_no' => 'Reply Number Error',
	'cod_ghosts_write_comment_duplication' => 'コメントの内容が重複しています。',
	'cod_ghosts_user_comment_existence' => 'Edit Comment Error',
	'cod_ghosts_reply_comment_duplication' => '返信の内容が重複しています。',
	'cod_ghosts_edit_reply_comment_existence' => 'Edit Reply Comment Error'
);
