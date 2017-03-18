<?php

class Controller_Rest_Wiki extends Controller_Rest_Base
{


	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	private $wiki_id = null;


	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $model_gc = null;
	private $model_wiki = null;
	private $model_advertisement = null;

	private $original_validation_common = null;
	private $original_validation_fieldsetex = null;

	private $original_func_common = null;

	private $original_code_advertisement = null;
	private $original_original_code_wiki = null;
	private $original_wiki_set = null;



	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		$this->model_gc = new Model_Gc();
		$this->model_wiki = new Model_Wiki();
		$this->model_advertisement = new Model_Advertisement();

		$this->original_validation_common = new Original\Validation\Common();
		$this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();

		$this->original_func_common = new \Original\Func\Common();

		$this->original_code_advertisement = new \Original\Code\Advertisement();
		$this->original_code_wiki = new Original\Code\Wiki();

	}






	/**
	* Setter / wiki_id
	*
	* @param string $argument
	*/
	public function set_wiki_id($argument)
	{
		$this->wiki_id = $this->original_validation_common->wiki_id($argument);
	}

	public function get_wiki_id()
	{
		return $this->wiki_id;
	}






	// --------------------------------------------------
	//   Game Usersに関わるコード
	// --------------------------------------------------


	/**
	* Wiki一覧読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_wiki_list()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['page'] = 1;
			$_POST['edit'] = 1;
		}


		$arr = array();

		try
		{

			$temp_arr['edit'] = (Input::post('edit')) ? true : false;

			$this->original_code_wiki->set_page(Input::post('page'));
			$code = $this->original_code_wiki->wiki_list($temp_arr);


			$arr['code'] = $code;

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			//\Debug::dump($arr);

			if (isset($code)) echo $code;

		}
		else
		{
			return $this->response($arr);
		}

	}



	/**
	* Wiki作成フォーム読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_wiki_create()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['page'] = 1;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$code = $this->original_code_wiki->create(null);


			$arr['code'] = $code;

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			//\Debug::dump($arr);

			if (isset($code)) echo $code;

		}
		else
		{
			return $this->response($arr);
		}

	}






	/**
	* Wiki作成・編集
	*
	* @return string HTMLコード
	*/
	public function post_save_wiki()
	{

		$arr = array();

		try
		{

			// --------------------------------------------------
			//   テスト変数
			// --------------------------------------------------

			//$test = true;

			if (isset($test))
			{
				$_POST['wiki_no'] = 3;
				$_POST['wiki_id'] = 'last-wiki-1';
				$_POST['wiki_name'] = '最終テスト 名前1';
				$_POST['wiki_comment'] = '最終テスト　コメント1';
				$_POST['wiki_password'] = 'lastwiki1';
				$_POST['game_list'] = '30';
			}


			// --------------------------------------------------
			//   インスタンス作成
			// --------------------------------------------------

			$this->original_wiki_set = new Original\Wiki\Set();


			// --------------------------------------------------
			//   値セット
			// --------------------------------------------------

			$this->original_wiki_set->set_wiki_no(Input::post('wiki_no'));
			$this->original_wiki_set->set_wiki_id(Input::post('wiki_id'));
			$this->original_wiki_set->set_wiki_name(Input::post('wiki_name'));
			$this->original_wiki_set->set_wiki_comment(Input::post('wiki_comment'));
			$this->original_wiki_set->set_wiki_password(Input::post('wiki_password'));
			$this->original_wiki_set->set_game_list(Input::post('game_list'));


			// --------------------------------------------------
			//   Wiki 作成
			// --------------------------------------------------

			$result_copy_wiki =  $this->original_wiki_set->save_wiki();



			// --------------------------------------------------
			//   アラート　成功
			// --------------------------------------------------

			$arr['alert_color'] = 'success';
			$arr['alert_title'] = 'OK';
			$arr['alert_message'] = '保存しました。';



			if (isset($test))
			{

				\Debug::$js_toggle_open = true;

				// echo '$save_arr';
				// \Debug::dump($save_arr);

			}

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			\Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}





	/**
	* Wiki広告設定保存
	*
	* @return string HTMLコード
	*/
	public function post_save_wiki_advertisement()
	{

		$arr = array();

		try
		{

			// --------------------------------------------------
			//   テスト変数
			// --------------------------------------------------

			//$test = true;

			if (isset($test))
			{
				$_POST['wiki_no'] = 10;
				$_POST['wiki_1'] = 'user5-1';
				$_POST['wiki_2'] = 'user5-9';
				//$_POST['amazon_slide'] = 1;
			}


			// --------------------------------------------------
			//   インスタンス作成
			// --------------------------------------------------

			$this->original_wiki_set = new Original\Wiki\Set();


			// --------------------------------------------------
			//   値セット
			// --------------------------------------------------

			$this->original_wiki_set->set_wiki_no(Input::post('wiki_no'));


			// --------------------------------------------------
			//   Wiki 作成
			// --------------------------------------------------

			$wiki_1 = (Input::post('wiki_1')) ? Input::post('wiki_1') : null;
			$wiki_2 = (Input::post('wiki_2')) ? Input::post('wiki_2') : null;
			$amazon_slide = (Input::post('amazon_slide')) ? 1 : null;

			$temp_arr = array(
				'wiki_1' => $wiki_1,
				'wiki_2' => $wiki_2,
				'amazon_slide' => $amazon_slide
			);

			$result =  $this->original_wiki_set->save_advertisement($temp_arr);



			// --------------------------------------------------
			//   アラート　成功
			// --------------------------------------------------

			$arr['alert_color'] = 'success';
			$arr['alert_title'] = 'OK';
			$arr['alert_message'] = '保存しました。';



			if (isset($test))
			{

				\Debug::$js_toggle_open = true;

				// echo '$save_arr';
				// \Debug::dump($save_arr);

			}

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			\Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}





	/**
	* Wiki 削除
	*
	* @return string HTMLコード
	*/
	public function post_delete_wiki()
	{

		$arr = array();

		try
		{

			// --------------------------------------------------
			//   テスト変数
			// --------------------------------------------------

			//$test = true;

			if (isset($test))
			{
				$_POST['wiki_no'] = 9;
			}


			// --------------------------------------------------
			//   インスタンス作成
			// --------------------------------------------------

			$this->original_wiki_set = new Original\Wiki\Set();


			// --------------------------------------------------
			//   値セット
			// --------------------------------------------------

			$this->original_wiki_set->set_wiki_no(Input::post('wiki_no'));


			// --------------------------------------------------
			//   Wiki 削除
			// --------------------------------------------------

			$result =  $this->original_wiki_set->delete_wiki();



			// --------------------------------------------------
			//   アラート　成功
			// --------------------------------------------------

			$arr['alert_color'] = 'success';
			$arr['alert_title'] = 'OK';
			$arr['alert_message'] = '削除しました。';



			if (isset($test))
			{

				\Debug::$js_toggle_open = true;

				// echo '$save_arr';
				// \Debug::dump($save_arr);

			}

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			\Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}






	// --------------------------------------------------
	//   Wiki本体に関わるコード
	// --------------------------------------------------


	/**
	* BBSスレッド一覧読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_bbs_code()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			//$_POST['wiki_id'] = 'hearthstone';
			$_POST['wiki_id'] = 'test5';
			//$_POST['search_type'] = 1;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   Wikiデータ取得
			// --------------------------------------------------

			$this->set_wiki_id(Input::post('wiki_id'));

			$temp_arr = array(
				'wiki_id' => $this->get_wiki_id(),
				'limit' => 1,
				'page', 1
			);

			$db_wiki_arr = $this->model_wiki->get_wiki($temp_arr);

			if (isset($db_wiki_arr['data_arr'][0]['game_list']))
			{
				$game_list = $db_wiki_arr['data_arr'][0]['game_list'];
				$game_list = $this->original_func_common->return_db_array('db_php', $game_list);
				$game_no = $game_list[0];
			}
			else
			{
				return $this->response(array('code' => null));
			}


			//\Debug::$js_toggle_open = true;
			//\Debug::dump($temp_arr, $db_wiki_arr, $game_list, $game_no);



			//exit();


			// --------------------------------------------------
			//   BBSデータ取得
			// --------------------------------------------------

			$language = 'ja';

			$result_arr = $this->model_gc->get_wiki_read_bbs($game_no, $language);
			$result_arr = Security::htmlentities($result_arr);

			$gc_base_url = URI_BASE . 'gc/' . $result_arr['game_data_id'];


			//\Debug::dump($result_arr);
			//exit();



			// 交流掲示板
			$code = '<p><a href="' . $gc_base_url . '">交流掲示板</a></p>' . "\n";
			$code .= '<ul>' . "\n";
			foreach ($result_arr['bbs_list_arr'] as $key => $value)
			{
				$code .= '<li><a href="' . $gc_base_url . '/bbs/' . $value['bbs_id'] . '">' . $value['title'] . '</a> (' . ($value['comment_total'] + $value['reply_total']) . ')</li>' . "\n";
			}
			$code .= '</ul><br>' . "\n";


			// 募集掲示板
			$code .= '<p><a href="' . $gc_base_url . '/rec">募集掲示板</a></p>' . "\n";
			$code .= '<ul>' . "\n";
			if ($result_arr['type1']) $code .= '<li><a href="' . $gc_base_url . '/rec/player">プレイヤー</a> (' . $result_arr['type1'] . ')</li>' . "\n";
			if ($result_arr['type2']) $code .= '<li><a href="' . $gc_base_url . '/rec/friend">フレンド</a> (' . $result_arr['type2'] . ')</li>' . "\n";
			if ($result_arr['type3']) $code .= '<li><a href="' . $gc_base_url . '/rec/member">ギルド・クランメンバー</a> (' . $result_arr['type3'] . ')</li>' . "\n";
			if ($result_arr['type4']) $code .= '<li><a href="' . $gc_base_url . '/rec/trade">売買・交換相手</a> (' . $result_arr['type4'] . ')</li>' . "\n";
			if ($result_arr['type5']) $code .= '<li><a href="' . $gc_base_url . '/rec/etc">その他</a> (' . $result_arr['type5'] . ')</li>' . "\n";
			$code .= '</ul>' . "\n";


			$arr['code'] = $code;


		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}





	/**
	* 広告読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_advertisement_code()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['wiki_id'] = 'hearthstone';
			//$_POST['wiki_id'] = 'test_wiki1';
			//$_POST['wiki_id'] = 'user5';
			$_POST['ad_name'] = 'adsense_link';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   Wikiデータ取得
			// --------------------------------------------------

			$this->set_wiki_id(Input::post('wiki_id'));

			$temp_arr = array(
				'wiki_id' => $this->get_wiki_id(),
				'limit' => 1,
				'page', 1
			);

			$db_wiki_arr = $this->model_wiki->get_wiki($temp_arr);

			if (isset($db_wiki_arr['data_arr'][0]['game_list']))
			{
				$user_no = $db_wiki_arr['data_arr'][0]['user_no'];
			}
			else
			{
				return $this->response(array('code' => null));
			}



			// --------------------------------------------------
			//   広告データ取得
			// --------------------------------------------------

			// ----------------------------------------
			//   デフォルト広告
			// ----------------------------------------

			$db_wiki_advertisement_admin_arr = $this->model_advertisement->get_advertisement_wiki_admin_default(null);


			// ----------------------------------------
			//   ユーザー広告
			// ----------------------------------------

			// #gu_ad()で個別に呼び出した広告
			$ad_name_arr = (Input::post('ad_name')) ? explode(',', Input::post('ad_name')) : array();


			// ユーザーが設定したデフォルト広告
			$user_amazon_slide = null;
			$user_wiki_1 = null;
			$user_wiki_2 = null;
			$user_default_advertisement_arr = array();


			if (isset($db_wiki_arr['data_arr'][0]['wiki_user_advertisement']))
			{

				// アンシリアライズ
				$user_default_advertisement_arr = unserialize($db_wiki_arr['data_arr'][0]['wiki_user_advertisement']);


				if (isset($user_default_advertisement_arr['amazon_slide']))
				{
					$user_amazon_slide = $user_default_advertisement_arr['amazon_slide'];
				}

				if (isset($user_default_advertisement_arr['wiki_1']))
				{
					$user_wiki_1 = $user_default_advertisement_arr['wiki_1'];
					array_push($ad_name_arr, $user_wiki_1);
				}

				if (isset($user_default_advertisement_arr['wiki_2']))
				{
					$user_wiki_2 = $user_default_advertisement_arr['wiki_2'];
					array_push($ad_name_arr, $user_wiki_2);
				}

				// 重複要素削除
				$ad_name_arr = array_unique($ad_name_arr);

			}

			$temp_arr = array(
				'ad_name_arr' => $ad_name_arr,
				'user_no' => $user_no
			);

			$db_wiki_advertisement_user_arr = $this->model_advertisement->get_advertisement_wiki_user_default($temp_arr);



			// --------------------------------------------------
			//   広告配列作成
			// --------------------------------------------------

			$default_arr = array();
			$user_arr = array();


			// ----------------------------------------
			//   デフォルト広告
			// ----------------------------------------

			$default_arr[1] = (AGENT_TYPE == 'smartphone' and $db_wiki_advertisement_admin_arr['wiki_1']['code_sp']) ? $db_wiki_advertisement_admin_arr['wiki_1']['code_sp'] : $db_wiki_advertisement_admin_arr['wiki_1']['code'];
			$default_arr[2] = (AGENT_TYPE == 'smartphone' and $db_wiki_advertisement_admin_arr['wiki_2']['code_sp']) ? $db_wiki_advertisement_admin_arr['wiki_2']['code_sp'] : $db_wiki_advertisement_admin_arr['wiki_2']['code'];
			$default_arr[3] = (AGENT_TYPE == 'smartphone' and $db_wiki_advertisement_admin_arr['wiki_3']['code_sp']) ? $db_wiki_advertisement_admin_arr['wiki_3']['code_sp'] : $db_wiki_advertisement_admin_arr['wiki_3']['code'];


			// ----------------------------------------
			//   ユーザー広告
			// ----------------------------------------

			// アドセンスは3つ以上貼れない
			//$adsense_count = 0;

			foreach ($db_wiki_advertisement_user_arr as $key => $value)
			{

				if ($value['name'] === $user_wiki_1)
				{
					$default_arr[1] = (AGENT_TYPE == 'smartphone' and $value['code_sp']) ? $value['code_sp'] : $value['code'];
					if ($value['hide_myself'] and $user_no == USER_NO) $default_arr[1] = null;
					//if (strpos($default_arr[1], '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js') !== false) $adsense_count++;
				}
				else if ($value['name'] === $user_wiki_2)
				{
					$default_arr[2] = (AGENT_TYPE == 'smartphone' and $value['code_sp']) ? $value['code_sp'] : $value['code'];
					if ($value['hide_myself'] and $user_no == USER_NO) $default_arr[2] = null;
					//if (strpos($default_arr[2], '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js') !== false) $adsense_count++;
				}
				else
				{
					$user_arr[$value['name']] = (AGENT_TYPE == 'smartphone' and $value['code_sp']) ? $value['code_sp'] : $value['code'];
					if ($value['hide_myself'] and $user_no == USER_NO) $user_arr[$value['name']] = null;
					//if ($adsense_count > 3) $user_arr[$value['name']] = null;
				}

			}


			$arr['default_arr'] = $default_arr;
			$arr['user_arr'] = $user_arr;

			//\Debug::$js_toggle_open = true;
			//\Debug::dump($temp_arr, $db_wiki_arr, $user_no, $db_wiki_advertisement_admin_arr, $db_wiki_advertisement_user_arr, $arr);








			// --------------------------------------------------
			//   Amazonスライド広告
			// --------------------------------------------------

			$arr['amazon_slide'] = null;

			if ($user_amazon_slide)
			{
				$temp_arr = array(
					'user_no' => $user_no
				);

				$code_ad_amazon_slide = $this->original_code_advertisement->code_ad_amazon_slide($temp_arr);

				$arr['amazon_slide'] = $code_ad_amazon_slide;
			}





			// --------------------------------------------------
			//   広告を表示しない
			// --------------------------------------------------

			if (AD_BLOCK)
			{
				$arr['default_arr'] = null;
				$arr['user_arr'] = null;
			}



			if (isset($test))
			{

				\Debug::$js_toggle_open = true;


				echo '$db_wiki_arr';
				\Debug::dump($db_wiki_arr);

				if (isset($user_default_advertisement_arr))
				{
					echo '$user_default_advertisement_arr';
					\Debug::dump($user_default_advertisement_arr);
				}


				echo '$ad_name_arr';
				\Debug::dump($ad_name_arr);


				echo '$db_wiki_advertisement_admin_arr';
				\Debug::dump($db_wiki_advertisement_admin_arr);

				echo '$db_wiki_advertisement_user_arr';
				\Debug::dump($db_wiki_advertisement_user_arr);

				echo '$default_arr';
				\Debug::dump($default_arr);

				echo '$user_arr';
				\Debug::dump($user_arr);
//
				// echo '$user_arr';
				// \Debug::dump($user_arr);

				//exit();

			}

		}
		catch (Exception $e)
		{
			if (isset($test)) echo $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			\Debug::dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}


}
