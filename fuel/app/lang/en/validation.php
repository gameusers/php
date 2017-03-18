<?php

return array(
	'required'        => 'The field :label is required and must contain a value.',
	'min_length'      => 'The field :label has to contain at least :param:1 characters.',
	'max_length'      => 'The field :label may not contain more than :param:1 characters.',
	'exact_length'    => 'The field :label must contain exactly :param:1 characters.',
	'match_value'     => 'The field :label must contain the value :param:1.',
	'match_pattern'   => 'The field :label must match the pattern.',
	'match_field'     => 'The field :label must match the field :param:1.',
	'valid_email'     => 'The field :label must contain a valid email address.',
	'valid_emails'    => 'The field :label must contain a list of valid email addresses.',
	'valid_url'       => 'The field :label must contain a valid URL.',
	'valid_ip'        => 'The field :label must contain a valid IP address.',
	'numeric_min'     => 'The minimum numeric value of :label must be :param:1',
	'numeric_max'     => 'The maximum numeric value of :label must be :param:1',
	'numeric_between' => 'The field :label must contain a numeric value between :param:1 and :param:2',
	'valid_string'    => 'The valid string rule :rule(:param:1) failed for field :label',
	'required_with'   => 'The field :label must contain a value if :param:1 contains a value.',
	'valid_date'      => 'The field :label must contain a valid formatted date.',
	
	// 共通
	'check_url' => 'You cannot add an URL if you are not logged in.',
	
	// Game Users
	'login_username_duplication' => '入力したIDは使用することができません。',
	'email_duplication_users_login' => 'そのメールアドレスはすでに登録されています。',
	'email_duplication_provisional_mail' => 'そのメールアドレスに対して、すでに仮登録メールが送信されています。',
	
	// Cod Ghosts
	'cod_ghosts_secondary' => 'Secondary Error',
	'cod_ghosts_primary_attachment' => 'Primary Attachment Error',
	'cod_ghosts_secondary_attachment' => 'Secondary Attachment Error',
	'cod_ghosts_perk' => 'Perk Error',
	'cod_ghosts_strike_package_type' => 'Strike Package Type Error',
	'cod_ghosts_strike_package' => 'Strike Package Error',
	'cod_ghosts_strike_package_specialist' => 'Strike Package Specialist Error',
	'cod_ghosts_rule' => 'Rule Error',
	'cod_ghosts_title' => 'You cannot add an URL to the name of the loadout.',
	'cod_ghosts_explanation' => 'You cannot add an URL to the description if you are not logged in.',
	'cod_ghosts_user_info_type' => 'User Info Type Error',
	
	'cod_ghosts_equipment_no' => 'Loadout Number Error',
	'cod_ghosts_comment_no' => 'Comment Number Error',
	'cod_ghosts_reply_no' => 'Reply Number Error',
	'cod_ghosts_write_comment_duplication' => 'Cannot post comment as it is a duplicate.',
	'cod_ghosts_user_comment_existence' => 'Edit Comment Error',
	'cod_ghosts_reply_comment_duplication' => 'Cannot post reply as it is a duplicate.',
	'cod_ghosts_edit_reply_comment_existence' => 'Edit Reply Comment Error'
);
