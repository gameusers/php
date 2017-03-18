<?php

namespace Original\Code;

class Card
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $original_validation_common = null;
	private $original_validation_fieldsetex = null;



	// --------------------------------------------------
	//   コンストラクター
	// --------------------------------------------------

	public function __construct()
	{

		// ------------------------------
		//   インスタンス作成
		// ------------------------------

		$this->original_validation_common = new \Original\Validation\Common();
		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();

	}




	/**
	* フィード
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function feed($arr)
	{


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$arr['type'] = 'all';
			$arr['page'] = 1;
		}


		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();

		$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(all|news|bbs|recruitment|community|amazon_menu)$/');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_type = $val->validated('type');
			$validated_page = (int) $val->validated('page');
		}
		else
		{
			$error_message = '';
			if (count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value)
				{
					$error_message .= $value;
				}
			}

			throw new \Exception($error_message);
		}




		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_card = new \Model_Card();
		$model_amazon = new \Model_Amazon();
		$original_common_date = new \Original\Common\Date();


		// --------------------------------------------------
		//   定数・変数
		// --------------------------------------------------

		$limit_feed_search = ($validated_type === 'all') ? (int) \Config::get('limit_feed_search_all') : (int) \Config::get('limit_feed_search');

		$limit_feed = (int) \Config::get('limit_feed');
		$limit_amazon = 70;

		$amazon_tracking_id = \Config::get('amazon_tracking_id');

		$datetime = $original_common_date->sql_format(\Config::get('limit_feed_search_term'));
		$datetime_amazon = $original_common_date->sql_format('-24hours');

		$language = 'ja';




		// --------------------------------------------------
		//   ゲームページ　BBS　スレッド
		// --------------------------------------------------

		$bbs_thread_gc_arr = [];

		if ($validated_type === 'all' or $validated_type === 'bbs')
		{
			$temp_arr = array(
				'language' => $language,
				'datetime' => $datetime,
				'page' => 1,
				'limit' => $limit_feed_search
			);

			$bbs_thread_gc_arr = $model_card->select_bbs_thread_gc($temp_arr);
		}


		// --------------------------------------------------
		//   ゲームページ　BBS　コメント
		// --------------------------------------------------

		$bbs_comment_gc_arr = [];

		if ($validated_type === 'all' or $validated_type === 'bbs')
		{
			$temp_arr = array(
				'language' => $language,
				'datetime' => $datetime,
				'page' => 1,
				'limit' => $limit_feed_search
			);

			$bbs_comment_gc_arr = $model_card->select_bbs_comment_gc($temp_arr);
		}


		// --------------------------------------------------
		//   ゲームページ　BBS　返信
		// --------------------------------------------------

		$bbs_reply_gc_arr = [];

		if ($validated_type === 'all' or $validated_type === 'bbs')
		{
			$temp_arr = array(
				'language' => $language,
				'datetime' => $datetime,
				'page' => 1,
				'limit' => $limit_feed_search
			);

			$bbs_reply_gc_arr = $model_card->select_bbs_reply_gc($temp_arr);
		}


		// --------------------------------------------------
		//   コミュニティ　BBS　スレッド
		// --------------------------------------------------

		$bbs_thread_uc_arr = [];

		if ($validated_type === 'all' or $validated_type === 'community')
		{
			$temp_arr = array(
				'language' => $language,
				'datetime' => $datetime,
				'page' => 1,
				'limit' => $limit_feed_search
			);

			$bbs_thread_uc_arr = $model_card->select_bbs_thread_uc($temp_arr);
		}


		// --------------------------------------------------
		//   コミュニティ　BBS　コメント
		// --------------------------------------------------

		$bbs_comment_uc_arr = [];

		if ($validated_type === 'all' or $validated_type === 'community')
		{
			$temp_arr = array(
				'language' => $language,
				'datetime' => $datetime,
				'page' => 1,
				'limit' => $limit_feed_search
			);

			$bbs_comment_uc_arr = $model_card->select_bbs_comment_uc($temp_arr);
		}


		// --------------------------------------------------
		//   コミュニティ　BBS　返信
		// --------------------------------------------------

		$bbs_reply_uc_arr = [];

		if ($validated_type === 'all' or $validated_type === 'community')
		{
			$temp_arr = array(
				'language' => $language,
				'datetime' => $datetime,
				'page' => 1,
				'limit' => $limit_feed_search
			);

			$bbs_reply_uc_arr = $model_card->select_bbs_reply_uc($temp_arr);
		}


		// --------------------------------------------------
		//   ゲームページ　募集　コメント
		// --------------------------------------------------

		$recruitment_comment_arr = [];

		if ($validated_type === 'all' or $validated_type === 'recruitment')
		{
			$temp_arr = array(
				'language' => $language,
				'datetime' => $datetime,
				'page' => 1,
				'limit' => $limit_feed_search
			);

			$recruitment_comment_arr = $model_card->select_recruitment_comment($temp_arr);
		}


		// --------------------------------------------------
		//   ゲームページ　募集　返信
		// --------------------------------------------------

		$recruitment_reply_arr = [];

		if ($validated_type === 'all' or $validated_type === 'recruitment')
		{
			$temp_arr = array(
				'language' => $language,
				'datetime' => $datetime,
				'page' => 1,
				'limit' => $limit_feed_search
			);

			$recruitment_reply_arr = $model_card->select_recruitment_reply($temp_arr);
		}


		// --------------------------------------------------
		//   Amazonデータ
		// --------------------------------------------------

		$temp_arr = array(
			'page' => 1,
			'limit' => $limit_feed,
			'datetime_past' => $datetime_amazon
		);

		$amazon_arr = $model_amazon->select_card_thumbnail($temp_arr);

		if ($validated_type === 'amazon_menu')
		{
			$amazon_menu_1_arr = array_splice($amazon_arr, 0, 4);
			$amazon_menu_1_arr = array_merge(array(array('type' => 'thumbnail_menu_box_start')), $amazon_menu_1_arr, array(array('type' => 'thumbnail_menu_box_end')));

			$amazon_menu_2_arr = array_splice($amazon_arr, 0, 4);
			$amazon_menu_2_arr = array_merge(array(array('type' => 'thumbnail_menu_box_start')), $amazon_menu_2_arr, array(array('type' => 'thumbnail_menu_box_end')));

			$amazon_menu_3_arr = array_splice($amazon_arr, 0, 4);
			$amazon_menu_3_arr = array_merge(array(array('type' => 'thumbnail_menu_box_start')), $amazon_menu_3_arr, array(array('type' => 'thumbnail_menu_box_end')));
		}



		// --------------------------------------------------
		//   フィードの作成
		// --------------------------------------------------

		if ($validated_type !== 'amazon_menu')
		{

			// --------------------------------------------------
			//   配列合成
			// --------------------------------------------------

			$merged_arr = array_merge($bbs_thread_gc_arr, $bbs_comment_gc_arr, $bbs_reply_gc_arr, $bbs_thread_uc_arr, $bbs_comment_uc_arr, $bbs_reply_uc_arr, $recruitment_comment_arr, $recruitment_reply_arr);

			$total = count($merged_arr);


			// --------------------------------------------------
			//   日付でソート　
			// --------------------------------------------------

			$temp_arr = [];
			foreach ($merged_arr as $key => $value) $temp_arr[] = strtotime($value['date']);
			array_multisort($temp_arr, SORT_DESC, $merged_arr);


			// --------------------------------------------------
			//   配列を必要な数だけ取り出す
			// --------------------------------------------------

			$offset = $limit_feed * ($validated_page - 1);
			$merged_arr = array_slice($merged_arr, $offset, $limit_feed);


			// --------------------------------------------------
			//   画像・動画情報をアンシリアライズ
			//   カード中がひとつだけのときは、サムネイルや広告を挿入する　（2つ並べないと隙間があくため）
			//   文字数カット
			// --------------------------------------------------

			$count = 0;
			$insert_key_arr = [];

			foreach ($merged_arr as $key => &$value)
			{

				if ($value['image']) $value['image'] = unserialize($value['image']);
				if ($value['movie']) $value['movie'] = unserialize($value['movie']);

				if ($value['image'] or $value['movie'])
				{
					if ($count === 2) $count = 0;
					$count++;
				}
				else if ($count === 1)
				{
					$count = 0;
					array_push($insert_key_arr, $key);
				}

				$value['comment'] = (mb_strlen($value['comment']) > 220) ? mb_substr($value['comment'], 0, 220, 'UTF-8') . '…' : $value['comment'];

			}

			unset($value);




			// --------------------------------------------------
			//   広告を挿入する
			// --------------------------------------------------

			$insert_key_arr = array_reverse($insert_key_arr);

			$amazon_last_arr = array_splice($amazon_arr, 0, 3);

			$adsense_count = 0;

			foreach ($insert_key_arr as $key => $value)
			{
				// アドセンス追加
				if ($value > 3 and $adsense_count < 2)
				{
					$adsense_count++;
					array_splice($merged_arr, $value, 0, array(array('type' => 'adsense_300x250')));
				}
				// アマゾンサムネイル追加
				else
				{
					$temp_arr = array_splice($amazon_arr, 0, 4);
					$temp_arr = array_merge(array(array('type' => 'thumbnail_box_start')), $temp_arr, array(array('type' => 'thumbnail_box_end')));
					array_splice($merged_arr, $value, 0, $temp_arr);
				}
			}

			// アドセンスが2個設置されていない場合は追加する
			while ($adsense_count < 2)
			{
				array_push($merged_arr, array('type' => 'adsense_300x250'));
				$adsense_count++;
			}


			// ---------------------------------------------
			//   スマホ　最後にAmazon広告を追加
			// ---------------------------------------------

			if (AGENT_TYPE === 'smartphone')
			{
				$merged_arr = array_merge($merged_arr, array(array('type' => 'amazon_last')), array(array('type' => 'thumbnail_box_start')), $amazon_last_arr, array(array('type' => 'thumbnail_box_end')));
			}

			// ---------------------------------------------
			//   タブレット　最後にAmazon広告を追加
			// ---------------------------------------------

			else if (AGENT_TYPE === 'tablet')
			{
				$merged_arr = array_merge($merged_arr, array(array('type' => 'amazon_last')), $amazon_last_arr);
			}

			// ---------------------------------------------
			//   PC　最後にAmazon広告を追加
			// ---------------------------------------------

			else
			{
				$merged_arr = array_merge($merged_arr, $amazon_arr, array(array('type' => 'amazon_last')), $amazon_last_arr);
			}



			$view_adsense = \View::forge('common/adsense_rectangle_ver2_view');
			$code_adsense_300x250 = $view_adsense->render();



			// --------------------------------------------------
			//    ページャー
			// --------------------------------------------------

			// ページャーの数字表示回数取得
			// $pagination_times = (AGENT_TYPE != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');



			if ($total > $limit_feed)
			{
				if ($validated_type === 'all')
				{
					//$url = ($validated_page === 1) ? URI_BASE : URI_BASE . 'in/feed';
					$url = URI_BASE . 'in/feed';
				}
				else if ($validated_type === 'bbs')
				{
					$url = URI_BASE . 'in/feed/bbs';
				}
				else if ($validated_type === 'recruitment')
				{
					$url = URI_BASE . 'in/feed/recruitment';
				}
				else if ($validated_type === 'community')
				{
					$url = URI_BASE . 'in/feed/community';
				}
				//\Debug::dump($url);

				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set('url', $url);
				$view_pagination->set('page', $validated_page);
				$view_pagination->set('total', $total);
				$view_pagination->set('limit', $limit_feed);
				$view_pagination->set('times', PAGINATION_TIMES);
				$view_pagination->set('function_name', 'GAMEUSERS.common.readFeed');
				$view_pagination->set('argument_arr', array("'$validated_type'", 1, 1, 1));
				$code_feed_pagination = $view_pagination->render();
			}
			else
			{
				$code_feed_pagination = null;
			}





			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$view = \View::forge('card/feed_view');
			$view->set_safe('about_amazon', true);
			$view->set('arr', $merged_arr);
			$view->set('amazon_tracking_id', $amazon_tracking_id);
			$view->set_safe('code_adsense_300x250', $code_adsense_300x250);
			$code_feed = $view->render();




			$add_page_url = ($validated_page === 1) ? null : '/' . $validated_page;
			$add_page_meta_title = ($validated_page === 1) ? null : ' Page ' . $validated_page;

			if ($validated_type === 'all')
			{
				$url = URI_BASE . 'in/feed' . $add_page_url;
				$meta_title = 'Game Users - フィード' . $add_page_meta_title;
				$meta_keywords = 'フィード,一覧';
				$meta_description = 'Game Usersのフィード一覧ページです。';
			}
			else if ($validated_type === 'bbs')
			{
				$url = URI_BASE . 'in/feed/bbs' . $add_page_url;
				$meta_title = 'Game Users - フィード・交流掲示板' . $add_page_meta_title;
				$meta_keywords = 'フィード,交流掲示板,一覧';
				$meta_description = '交流掲示板のフィード一覧ページです。';
			}
			else if ($validated_type === 'recruitment')
			{
				$url = URI_BASE . 'in/feed/recruitment' . $add_page_url;
				$meta_title = 'Game Users - フィード・募集掲示板' . $add_page_meta_title;
				$meta_keywords = 'フィード,募集掲示板,一覧';
				$meta_description = '募集掲示板のフィード一覧ページです。';
			}
			else if ($validated_type === 'community')
			{
				$url = URI_BASE . 'in/feed/community' . $add_page_url;
				$meta_title = 'Game Users - フィード・コミュニティ' . $add_page_meta_title;
				$meta_keywords = 'フィード,コミュニティ,一覧';
				$meta_description = 'コミュニティのフィード一覧ページです。';
			}

			$return_arr = array(
				'code_feed' => $code_feed,
				'code_feed_pagination' => $code_feed_pagination,
				'state' => [
					'group' => 'feed',
					'content' => 'index',
					'function' => 'readFeed',
					'page' => $validated_page,
					'type' => $validated_type
				],
				'url' => $url,
				'meta_title' => $meta_title,
				'meta_keywords' => $meta_keywords,
				'meta_description' => $meta_description
			);


			return $return_arr;


			// --------------------------------------------------
			//   リターン
			// --------------------------------------------------

			//return array('code_feed' => $code_feed, 'code_feed_pagination' => $code_feed_pagination);


		}

		// --------------------------------------------------
		//   サイドメニュー用　Amazonサムネイル広告（メニュー用）の作成
		// --------------------------------------------------

		else
		{

			$view = \View::forge('card/feed_view');
			$view->set_safe('amazon_thumbnail_menu', true);
			$view->set('arr', $amazon_menu_1_arr);
			$view->set('amazon_tracking_id', $amazon_tracking_id);
			$code_amazon_menu_1 = $view->render();

			$view = \View::forge('card/feed_view');
			$view->set_safe('amazon_thumbnail_menu', true);
			$view->set('arr', $amazon_menu_2_arr);
			$view->set('amazon_tracking_id', $amazon_tracking_id);
			$code_amazon_menu_2 = $view->render();

			$view = \View::forge('card/feed_view');
			$view->set_safe('amazon_thumbnail_menu', true);
			$view->set('arr', $amazon_menu_3_arr);
			$view->set('amazon_tracking_id', $amazon_tracking_id);
			$code_amazon_menu_3 = $view->render();


			// --------------------------------------------------
			//   リターン
			// --------------------------------------------------

			return array('code_amazon_menu_1' => $code_amazon_menu_1, 'code_amazon_menu_2' => $code_amazon_menu_2, 'code_amazon_menu_3' => $code_amazon_menu_3);

		}



		//$test = true;

		if (isset($test))
		{
			if (isset($validated_type)) echo '$validated_type = ' . $validated_type . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';
			if (isset($datetime)) echo '$datetime = ' . $datetime . '<br>';
			if (isset($total)) echo '$total = ' . $total . '<br>';


			if (isset($amazon_menu_1_arr))
			{
				echo '$amazon_menu_1_arr';
				\Debug::dump($amazon_menu_1_arr);
			}


			// echo $code_feed;
			// echo $code_feed_pagination;
			echo $code_amazon_menu_1;

			exit();
		}


	}




	/**
	* コミュニティ一覧
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function community_list(array $arr): array
	{

		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();

		$val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(all|participation|participation_secret)$/');
		$val->add_field('user_no', 'User No', 'match_pattern["^[1-9]\d*$"]');
		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_type = $val->validated('type');
			$validated_user_no = $val->validated('user_no') ? (int) $val->validated('user_no') : null;
			$validated_page = (int) $val->validated('page');
		}
		else
		{
			$error_message = '';
			if (count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value)
				{
					$error_message .= $value;
				}
			}

			throw new \Exception($error_message);
		}




		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_common = new \Model_Common();
		$model_user = new \Model_User();
		$model_game = new \Model_Game();


		// --------------------------------------------------
		//   定数・変数
		// --------------------------------------------------

		$limit = (AGENT_TYPE != 'smartphone') ? \Config::get('index_limit_community') : \Config::get('index_limit_community_sp');
		$language = 'ja';


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		if ($validated_user_no and ($validated_type === 'participation' or $validated_type === 'participation_secret'))
		{
			$db_users_data_arr = $model_user->get_user_data($validated_user_no, null);

			if ($validated_type === 'participation')
			{
				$game_list = $db_users_data_arr['participation_community'];
			}
			else if ($validated_user_no === USER_NO)
			{
				$game_list = $db_users_data_arr['participation_community_secret'];
			}
			else
			{
				exit();
			}

			$result_arr = $model_common->get_community_participation($game_list, $validated_page, $limit);
		}
		else
		{
			$result_arr = $model_common->get_community($validated_page, $limit);
		}

		$community_arr = $result_arr[0];
		$total = $result_arr[1];


		// --------------------------------------------------
		//   ゲーム名取得
		// --------------------------------------------------

		if (count($community_arr) > 0)
		{

			$game_no_arr = array();

			// ゲームNo取得
			foreach ($community_arr as $key => &$value)
			{
				$exploded_game_list_arr = explode(',', $value['game_list']);
				array_shift($exploded_game_list_arr);
				array_pop($exploded_game_list_arr);
				array_push($game_no_arr, $exploded_game_list_arr[0]);
				$value['game_no'] = $exploded_game_list_arr[0];
			}
			unset($value);

			// 重複番号削除
			$game_no_arr = array_unique($game_no_arr);

			// ゲーム名取得
			$game_names_arr = $model_game->get_game_name($language, $game_no_arr);

		}


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (count($community_arr) > 0)
		{

			$view = \View::forge('card/community_list_view');
			$view->set('community_arr', $community_arr);
			$view->set('game_names_arr', $game_names_arr);
			$code = $view->render() . "\n";


			// --------------------------------------------------
			//   ページャー
			// --------------------------------------------------

			if ($total > $limit)
			{
				$pagination_times = (AGENT_TYPE != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

				$code .= '<div class="margin_top_20">' . "\n";
				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set_safe('url', URI_BASE . 'in/community');
				$view_pagination->set_safe('page', $validated_page);
				$view_pagination->set_safe('total', $total);
				$view_pagination->set_safe('limit', $limit);
				$view_pagination->set_safe('times', $pagination_times);

				if (isset($validated_user_no))
				{
					$view_pagination->set_safe('function_name', 'GAMEUSERS.player.readParticipationCommunity');
					$view_pagination->set_safe('argument_arr', array($validated_user_no));
				}
				else
				{
					$view_pagination->set_safe('function_name', 'GAMEUSERS.common.readCommunityList');
					$view_pagination->set_safe('argument_arr', array("'$validated_type'", 'null', 1, 1, 1));
				}

				$code .= $view_pagination->render();
				$code .= '</div>' . "\n";

			}

		}


		//$test = true;

		if (isset($test))
		{
			if (isset($validated_type)) echo '$validated_type = ' . $validated_type . '<br>';
			if (isset($validated_user_no)) echo '$validated_user_no = ' . $validated_user_no . '<br>';
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';

			if (isset($community_arr))
			{
				echo '$community_arr';
				\Debug::dump($community_arr);
			}

			if (isset($total))
			{
				echo '$total';
				\Debug::dump($total);
			}

			if (isset($game_names_arr))
			{
				echo '$game_names_arr';
				\Debug::dump($game_names_arr);
			}


			echo $code;


			exit();
		}



		$add_page_url = ($validated_page === 1) ? null : '/' . $validated_page;
		$add_page_meta_title = ($validated_page === 1) ? null : ' Page ' . $validated_page;

		$return_arr = array(
			'code' => $code,
			'state' => [
				'group' => 'community',
				'content' => 'index',
				'function' => 'readCommunityList',
				'page' => $validated_page,
				'type' => $validated_type
			],
			'url' => URI_BASE . 'in/community' . $add_page_url,
			'meta_title' => 'Game Users - コミュニティ一覧' . $add_page_meta_title,
			'meta_keywords' => 'コミュニティ,一覧',
			'meta_description' => 'Game Usersのコミュニティ一覧ページです。'
		);


		return $return_arr;

	}




	/**
	* Wiki一覧
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function wiki_list(array $arr): array
	{

		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		$this->original_validation_fieldsetex->reset();

		$val = \Validation::forge();

		$val->add_field('page', 'Page', 'required|match_pattern["^[1-9]\d*$"]');

		if ($val->run($arr))
		{
			$validated_page = (int) $val->validated('page');
		}
		else
		{
			$error_message = '';
			if (count($val->error()) > 0)
			{
				foreach ($val->error() as $key => $value)
				{
					$error_message .= $value;
				}
			}

			throw new \Exception($error_message);
		}



		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_wiki = new \Model_Wiki();
		$model_game = new \Model_Game();


		// --------------------------------------------------
		//   定数・変数
		// --------------------------------------------------

		$limit = (AGENT_TYPE != 'smartphone') ? \Config::get('limit_wiki_list') : \Config::get('limit_wiki_list_sp');
		$language = 'ja';


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		$temp_arr = array(
			'limit' => $limit,
			'page' => $validated_page,
			'get_total' => true
		);

		$result_arr = $model_wiki->get_wiki($temp_arr);
		$db_wiki_arr = $result_arr['data_arr'];
		$total = $result_arr['total'];


		// --------------------------------------------------
		//   ゲーム名取得
		// --------------------------------------------------

		if (count($db_wiki_arr) > 0)
		{

			$game_no_arr = array();

			// ゲームNo取得
			foreach ($db_wiki_arr as $key => &$value)
			{
				$exploded_game_list_arr = explode(',', $value['game_list']);
				array_shift($exploded_game_list_arr);
				array_pop($exploded_game_list_arr);
				array_push($game_no_arr, $exploded_game_list_arr[0]);
				$value['game_no'] = $exploded_game_list_arr[0];
			}
			unset($value);

			// 重複番号削除
			$game_no_arr = array_unique($game_no_arr);

			// ゲーム名取得
			$game_names_arr = $model_game->get_game_name($language, $game_no_arr);

		}


		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (count($db_wiki_arr) > 0)
		{

			$view = \View::forge('card/wiki_list_view');
			$view->set('wiki_arr', $db_wiki_arr);
			$view->set('game_names_arr', $game_names_arr);
			$code = $view->render() . "\n";


			// --------------------------------------------------
			//   ページャー
			// --------------------------------------------------

			if ($total > $limit)
			{
				$pagination_times = (AGENT_TYPE != 'smartphone') ? \Config::get('pagination_times') : \Config::get('pagination_times_sp');

				$code .= '<div class="margin_top_20">' . "\n";
				$view_pagination = \View::forge('parts/pagination_view');
				$view_pagination->set_safe('url', URI_BASE . 'in/wiki');
				$view_pagination->set_safe('page', $validated_page);
				$view_pagination->set_safe('total', $total);
				$view_pagination->set_safe('limit', $limit);
				$view_pagination->set_safe('times', $pagination_times);
				$view_pagination->set_safe('function_name', 'GAMEUSERS.common.readWikiList');
				$view_pagination->set_safe('argument_arr', array(1, 1, 1));

				$code .= $view_pagination->render();
				$code .= '</div>' . "\n";

			}

		}



		//$test = true;

		if (isset($test))
		{
			if (isset($validated_page)) echo '$validated_page = ' . $validated_page . '<br>';

			if (isset($db_wiki_arr))
			{
				echo '$db_wiki_arr';
				\Debug::dump($db_wiki_arr);
			}

			if (isset($total))
			{
				echo '$total';
				\Debug::dump($total);
			}

			if (isset($game_names_arr))
			{
				echo '$game_names_arr';
				\Debug::dump($game_names_arr);
			}


			echo $code;


			exit();
		}


		$add_page_url = ($validated_page === 1) ? null : '/' . $validated_page;
		$add_page_meta_title = ($validated_page === 1) ? null : ' Page ' . $validated_page;

		$return_arr = array(
			'code' => $code,
			'state' => [
				'group' => 'wiki',
				'content' => 'index',
				'function' => 'readWikiList',
				'page' => $validated_page
			],
			'url' => URI_BASE . 'in/wiki' . $add_page_url,
			'meta_title' => 'Game Users - Wiki一覧' . $add_page_meta_title,
			'meta_keywords' => 'Wiki,一覧',
			'meta_description' => 'Game UsersのWiki一覧ページです。'
		);


		return $return_arr;

	}




	/**
	* footer
	*
	* @param array $arr
	* @return string HTMLコード
	*/
	public function footer($arr)
	{

		// --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

		// $this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		// $this->original_validation_fieldsetex->reset();
		//
		// $val = \Validation::forge();
		//
		// $val->add('type', 'Type')->add_rule('required')->add_rule('match_pattern', '/^(renewal_game|access_game|access_community)$/');
		//
		// if ($val->run($arr))
		// {
		// 	$validated_type = $val->validated('type');
		// }
		// else
		// {
		// 	$error_message = '';
		// 	if (count($val->error()) > 0)
		// 	{
		// 		foreach ($val->error() as $key => $value)
		// 		{
		// 			$error_message .= $value;
		// 		}
		// 	}
		// 	//echo $error_message;
		// 	throw new \Exception($error_message);
		// }



		// --------------------------------------------------
		//   インスタンス
		// --------------------------------------------------

		$model_card = new \Model_Card();
		//$model_game = new \Model_Game();


		// --------------------------------------------------
		//   定数・変数
		// --------------------------------------------------

		$limit = (AGENT_TYPE != 'smartphone') ? \Config::get('limit_footer_card') : \Config::get('limit_footer_card_sp');
		$language = 'ja';


		// --------------------------------------------------
		//   データ取得
		// --------------------------------------------------

		// ---------------------------------------------
		//   クッキー取得
		// ---------------------------------------------

		$cookie_footer_type = \Cookie::get('footer_type', 'gc_renewal');

		$cookie_gc_access_game_no = \Cookie::get('gc_access', null);
		$gc_access_game_no_arr = ($cookie_gc_access_game_no) ? explode(',', $cookie_gc_access_game_no): null;

		$cookie_uc_access_community_no = \Cookie::get('uc_access', null);
		$uc_access_community_no_arr = ($cookie_uc_access_community_no) ? explode(',', $cookie_uc_access_community_no): null;


		// ---------------------------------------------
		//   最近更新されたゲームページ
		// ---------------------------------------------

		if ($cookie_footer_type === 'gc_access' and $gc_access_game_no_arr)
		{

			$temp_arr = array(
				'language' => $language,
				'type' => $cookie_footer_type,
				'game_no_arr' => $gc_access_game_no_arr,
				'limit' => $limit
			);

			$data_arr = $model_card->select_footer_game_data($temp_arr);

		}

		// ---------------------------------------------
		//   最近アクセスしたコミュニティ
		// ---------------------------------------------

		else if ($cookie_footer_type === 'uc_access' and $uc_access_community_no_arr)
		{

			$temp_arr = array(
				'language' => $language,
				'type' => $cookie_footer_type,
				'community_no_arr' => $uc_access_community_no_arr,
				'limit' => $limit
			);

			$data_arr = $model_card->select_footer_community($temp_arr);

		}

		// ---------------------------------------------
		//   最近更新されたゲームページ
		// ---------------------------------------------

		else
		{

			$temp_arr = array(
				'language' => $language,
				'type' => $cookie_footer_type,
				'game_no_arr' => null,
				'limit' => $limit
			);

			$data_arr = $model_card->select_footer_game_data($temp_arr);

		}





		// --------------------------------------------------
		//   コード作成
		// --------------------------------------------------

		$code = null;

		if (count($data_arr) > 0)
		{
			$view = \View::forge('card/footer_view');
			$view->set_safe('type', $cookie_footer_type);
			$view->set('data_arr', $data_arr);
			$code = $view->render() . "\n";
		}



		//$test = true;

		if (isset($test))
		{
			if (isset($validated_type)) echo '$validated_type = ' . $validated_type . '<br>';

			if (isset($game_no_arr))
			{
				echo '$game_no_arr';
				\Debug::dump($game_no_arr);
			}

			if (isset($data_arr))
			{
				echo '$data_arr';
				\Debug::dump($data_arr);
			}

			if (isset($game_names_arr))
			{
				echo '$game_names_arr';
				\Debug::dump($game_names_arr);
			}


			echo $code;


			exit();
		}




		return array('code' => $code);

	}



}
