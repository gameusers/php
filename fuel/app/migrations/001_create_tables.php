<?php

namespace Fuel\Migrations;

class Create_tables
{

	function up()
	{
		
		\DBUtil::create_table(
		'announcement', 
		array(
			'announcement_no' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'on_off' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'regi_date' => array('type' => 'datetime'),
			'renewal_date' => array('type' => 'datetime'),
			'community_no' => array('type' => 'int', 'constraint' => 11),
			'user_no' => array('type' => 'int', 'constraint' => 11),
			'profile_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'title' => array('type' => 'varchar', 'constraint' => 255),
			'comment' => array('type' => 'text'),
			'image' => array('type' => 'text', 'null' => true),
			'movie' => array('type' => 'text', 'null' => true),
			'good' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
		),array('announcement_no'));
		
		
		\DBUtil::create_table(
		'bbs_comment', 
		array(
			'bbs_comment_no' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'on_off' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'regi_date' => array('type' => 'datetime'),
			'renewal_date' => array('type' => 'datetime'),
			'sort_date' => array('type' => 'datetime'),
			'community_no' => array('type' => 'int', 'constraint' => 11),
			'bbs_thread_no' => array('type' => 'int', 'constraint' => 11),
			'user_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'profile_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'anonymity' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true),
			'handle_name' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'comment' => array('type' => 'text'),
			'image' => array('type' => 'text', 'null' => true),
			'movie' => array('type' => 'text', 'null' => true),
			'reply_total' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'good' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'host' => array('type' => 'varchar', 'constraint' => 255),
			'user_agent' => array('type' => 'varchar', 'constraint' => 255),
		),array('bbs_comment_no'));
		
		
		\DBUtil::create_table(
		'bbs_reply', 
		array(
			'bbs_reply_no' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'on_off' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'regi_date' => array('type' => 'datetime'),
			'renewal_date' => array('type' => 'datetime'),
			'sort_date' => array('type' => 'datetime'),
			'community_no' => array('type' => 'int', 'constraint' => 11),
			'bbs_thread_no' => array('type' => 'int', 'constraint' => 11),
			'bbs_comment_no' => array('type' => 'int', 'constraint' => 11),
			'user_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'profile_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'anonymity' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true),
			'handle_name' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'comment' => array('type' => 'text'),
			'image' => array('type' => 'text', 'null' => true),
			'movie' => array('type' => 'text', 'null' => true),
			'good' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'host' => array('type' => 'varchar', 'constraint' => 255),
			'user_agent' => array('type' => 'varchar', 'constraint' => 255),
		),array('bbs_reply_no'));
		
		
		\DBUtil::create_table(
		'bbs_thread', 
		array(
			'bbs_thread_no' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'on_off' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'regi_date' => array('type' => 'datetime'),
			'renewal_date' => array('type' => 'datetime'),
			'sort_date' => array('type' => 'datetime'),
			'community_no' => array('type' => 'int', 'constraint' => 11),
			'user_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'profile_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'anonymity' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true),
			'handle_name' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'title' => array('type' => 'varchar', 'constraint' => 255),
			'comment' => array('type' => 'text'),
			'image' => array('type' => 'text', 'null' => true),
			'movie' => array('type' => 'text', 'null' => true),
			'comment_total' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'good' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'host' => array('type' => 'varchar', 'constraint' => 255),
			'user_agent' => array('type' => 'varchar', 'constraint' => 255),
		),array('bbs_thread_no'));
		
		
		\DBUtil::create_table(
		'community', 
		array(
			'community_no' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'on_off' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'regi_date' => array('type' => 'datetime'),
			'renewal_date' => array('type' => 'datetime'),
			'sort_date' => array('type' => 'datetime'),
			'language' => array('type' => 'varchar', 'constraint' => 10),
			'community_id' => array('type' => 'varchar', 'constraint' => 255),
			'author_user_no' => array('type' => 'int', 'constraint' => 11),
			'name' => array('type' => 'varchar', 'constraint' => 255),
			'description' => array('type' => 'text'),
			'description_mini' => array('type' => 'varchar', 'constraint' => 255),
			'game_list' => array('type' => 'varchar', 'constraint' => 255),
			'tag' => array('type' => 'text', 'null' => true),
			'top_image' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'thumbnail' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true),
			'member' => array('type' => 'text'),
			'member_total' => array('type' => 'int', 'constraint' => 11, 'default' => 1),
			'provisional' => array('type' => 'text', 'null' => true),
			'ban' => array('type' => 'text', 'null' => true),
			'mail' => array('type' => 'text'),
			'bbs_thread_total' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'config' => array('type' => 'text'),
		),array('community_no'));
		
		\DB::query("ALTER TABLE `community` ADD UNIQUE(`community_id`)")->execute();
		
		
		\DBUtil::create_table(
		'game_data', 
		array(
			'game_no' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'on_off' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'approval' => array('type' => 'tinyint', 'constraint' => 4, 'default' => 0),
			'renewal_date' => array('type' => 'datetime'),
			'id' => array('type' => 'varchar', 'constraint' => 11),
			'name_ja' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'similarity_ja' => array('type' => 'text', 'null' => true),
			'name_en' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'similarity_en' => array('type' => 'text', 'null' => true),
			'user_no' => array('type' => 'int', 'constraint' => 11),
			'history' => array('type' => 'text'),
			'config' => array('type' => 'text'),
		),array('game_no'));
		
		\DB::query("ALTER TABLE `game_data` ADD UNIQUE(`id`)")->execute();
		
		
		\DBUtil::create_table(
		'good_log', 
		array(
			'regi_date' => array('type' => 'datetime'),
			'type' => array('type' => 'varchar', 'constraint' => 20),
			'no' => array('type' => 'int', 'constraint' => 11),
			'target_user_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'target_profile_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'user_no' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'host' => array('type' => 'varchar', 'constraint' => 255),
			'user_agent' => array('type' => 'varchar', 'constraint' => 255),
		));
		
		
		\DBUtil::create_table(
		'mail_all', 
		array(
			'id' => array('type' => 'varchar', 'constraint' => 32),
			'regi_date' => array('type' => 'datetime'),
			'status' => array('type' => 'varchar', 'constraint' => 30, 'default' => 'on'),
			'user_no' => array('type' => 'int', 'constraint' => 11),
			'mail_condition' => array('type' => 'text'),
			'subject' => array('type' => 'varchar', 'constraint' => 255),
			'body' => array('type' => 'text'),
			'page' => array('type' => 'int', 'constraint' => 11, 'default' => 1),
			'latest_sent_users' => array('type' => 'text', 'null' => true),
			'host' => array('type' => 'varchar', 'constraint' => 255),
			'user_agent' => array('type' => 'varchar', 'constraint' => 255),
		));
		
		\DB::query("ALTER TABLE `mail_all` ADD UNIQUE(`id`)")->execute();
		
		
		\DBUtil::create_table(
		'profile', 
		array(
			'profile_no' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'on_off' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'regi_date' => array('type' => 'datetime'),
			'renewal_date' => array('type' => 'datetime'),
			'access_date' => array('type' => 'datetime'),
			'author_user_no' => array('type' => 'int', 'constraint' => 11),
			'profile_title' => array('type' => 'varchar', 'constraint' => 255),
			'handle_name' => array('type' => 'varchar', 'constraint' => 255),
			'explanation' => array('type' => 'text', 'null' => true),
			'status' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'thumbnail' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true),
			'open_profile' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'game_list' => array('type' => 'text', 'null' => true),
			'good' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'level' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
		),array('profile_no'));
		
		
		\DBUtil::create_table(
		'provisional_mail', 
		array(
			'regi_date' => array('type' => 'datetime'),
			'user_no' => array('type' => 'int', 'constraint' => 11),
			'email' => array('type' => 'varchar', 'constraint' => 255),
			'hash' => array('type' => 'varchar', 'constraint' => 255),
			'host' => array('type' => 'varchar', 'constraint' => 255),
			'user_agent' => array('type' => 'varchar', 'constraint' => 255),
		));
		
		\DB::query("ALTER TABLE `provisional_mail` ADD UNIQUE(`hash`)")->execute();
		
		
		\DBUtil::create_table(
		'users_data', 
		array(
			'user_no' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'on_off' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true, 'default' => 1),
			'renewal_date' => array('type' => 'datetime'),
			'access_date' => array('type' => 'datetime'),
			'page_title' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'profile_title' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'handle_name' => array('type' => 'varchar', 'constraint' => 255),
			'explanation' => array('type' => 'text', 'null' => true),
			'status' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'top_image' => array('type' => 'text', 'null' => true),
			'thumbnail' => array('type' => 'tinyint', 'constraint' => 4, 'null' => true),
			'user_id' => array('type' => 'varchar', 'constraint' => 255),
			'good' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'participation_community' => array('type' => 'text', 'null' => true),
			'participation_community_secret' => array('type' => 'text', 'null' => true),
			'level' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'login_log' => array('type' => 'text', 'null' => true),
		),array('user_no'));
		
		\DB::query("ALTER TABLE `users_data` ADD UNIQUE(`user_id`)")->execute();
		
		
		\DBUtil::create_table(
		'users_login', 
		array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'username' => array('type' => 'varchar', 'constraint' => 50),
			'password' => array('type' => 'varchar', 'constraint' => 255),
			'group' => array('type' => 'int', 'constraint' => 11, 'default' => 1),
			'email' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'last_login' => array('type' => 'varchar', 'constraint' => 25),
			'login_hash' => array('type' => 'varchar', 'constraint' => 255),
			'profile_fields' => array('type' => 'text'),
			'created_at' => array('type' => 'int', 'constraint' => 11, 'unsigned' => true),
			'twitter_id' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
			'twitter_access_token' => array('type' => 'varchar', 'constraint' => 150, 'null' => true),
			'twitter_access_token_secret' => array('type' => 'varchar', 'constraint' => 150, 'null' => true),
			'auth_type1' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
			'auth_id1' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
			'auth_type2' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
			'auth_id2' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
			'auth_type3' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
			'auth_id3' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
		),array('id'));
		
		\DB::query("ALTER TABLE `users_login` ADD UNIQUE(`username`)")->execute();
		\DB::query("ALTER TABLE `users_login` ADD UNIQUE(`email`)")->execute();
		\DB::query("ALTER TABLE `users_login` ADD UNIQUE(`twitter_id`)")->execute();
		\DB::query("ALTER TABLE `users_login` ADD UNIQUE(`twitter_access_token`)")->execute();
		\DB::query("ALTER TABLE `users_login` ADD UNIQUE(`twitter_access_token_secret`)")->execute();
		\DB::query("ALTER TABLE `users_login` ADD UNIQUE(`auth_id1`)")->execute();
		\DB::query("ALTER TABLE `users_login` ADD UNIQUE(`auth_id2`)")->execute();
		\DB::query("ALTER TABLE `users_login` ADD UNIQUE(`auth_id3`)")->execute();
		
	}

	function down()
	{
		\DBUtil::drop_table('announcement');
		\DBUtil::drop_table('bbs_comment');
		\DBUtil::drop_table('bbs_reply');
		\DBUtil::drop_table('bbs_thread');
		\DBUtil::drop_table('community');
		\DBUtil::drop_table('game_data');
		\DBUtil::drop_table('good_log');
		\DBUtil::drop_table('mail_all');
		\DBUtil::drop_table('profile');
		\DBUtil::drop_table('provisional_mail');
		\DBUtil::drop_table('users_data');
		\DBUtil::drop_table('users_login');
	}
}