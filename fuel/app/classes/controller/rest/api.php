<?php

class Controller_Rest_Api extends Controller_Rest_Base
{

	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();
	}



	/**
	* API共通
	*
	* @return string HTMLコード
	*/
	public function post_common()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			Debug::$js_toggle_open = true;

			$_POST['api_type'] = 'feed';
			// // $_POST['game_no'] = 1;
			// $_POST['bbs_id'] = 'lnntfuztqvqbqwqb';
			// $_POST['game_no'] = 1;

			//$_POST['page'] = 1;

			//$_POST['keyword'] = '';
			//$_POST['game_no'] = 386;
			//$_POST['history_no'] = 0;

			// $_POST['keyword'] = '';
			// $_POST['page'] = 1;
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   CSRFチェック
			// --------------------------------------------------

			$original_validation_common = new Original\Validation\Common();
			$original_validation_common->csrf(Input::post('fuel_csrf_token'));



			// --------------------------------------------------
			//   カード / フィード
			// --------------------------------------------------

			if (Input::post('api_type') === 'feed')
			{
				$original_code_card = new Original\Code\Card();
				$arr = $original_code_card->feed(Input::post());
			}


			// --------------------------------------------------
			//   ゲーム登録フォーム / 全体　ホバーで読み込む
			// --------------------------------------------------

			else if (Input::post('api_type') === 'content_feed_register_game')
			{
				// $view = \View::forge('content/register_game_view');
				// $arr['code'] = $view->render();
				$original_code_index = new Original\Code\Index();
				$arr = $original_code_index->form_register_game(Input::post());
			}


			// --------------------------------------------------
			//   ゲーム登録フォーム
			// --------------------------------------------------

			else if (Input::post('api_type') === 'register_game')
			{
				$original_code_index = new Original\Code\Index();
				$arr = $original_code_index->register_game(Input::post());
			}


			// --------------------------------------------------
			//   運営用　開発登録フォーム
			// --------------------------------------------------

			else if (Input::post('api_type') === 'form_developer')
			{
				$original_code_index = new Original\Code\Index();
				$arr = $original_code_index->form_developer(Input::post());
			}


			// --------------------------------------------------
			//   運営用　開発登録　save_developer
			// --------------------------------------------------

			else if (Input::post('api_type') === 'save_developer')
			{
				$original_code_index = new Original\Code\Index();
				$arr = $original_code_index->save_developer(Input::post());
			}


			// --------------------------------------------------
			//   運営用　ジャンル登録フォーム
			// --------------------------------------------------

			else if (Input::post('api_type') === 'form_genre')
			{
				$original_code_index = new Original\Code\Index();
				$arr = $original_code_index->form_genre(Input::post());
			}


			// --------------------------------------------------
			//   運営用　ジャンル登録　save_genre
			// --------------------------------------------------

			else if (Input::post('api_type') === 'save_genre')
			{
				$original_code_index = new Original\Code\Index();
				$arr = $original_code_index->save_genre(Input::post());
			}


			// --------------------------------------------------
			//   コミュニティ一覧
			// --------------------------------------------------

			else if (Input::post('api_type') === 'community_list')
			{
				$original_code_card = new Original\Code\Card();
				$arr = $original_code_card->community_list(Input::post());
			}


			// --------------------------------------------------
			//   コミュニティ作成フォーム　ホバーで読み込む
			// --------------------------------------------------

			else if (Input::post('api_type') === 'content_community_create')
			{
				// $view = \View::forge('content/community_create_view');
				// $arr['code'] = $view->render();
				$original_code_index = new Original\Code\Index();
				$arr = $original_code_index->form_community_create([]);
			}


			// --------------------------------------------------
			//   Wiki一覧
			// --------------------------------------------------

			else if (Input::post('api_type') === 'wiki_list')
			{
				$original_code_card = new Original\Code\Card();
				$arr = $original_code_card->wiki_list(Input::post());
			}


			// --------------------------------------------------
			//   Wiki作成フォーム　ホバーで読み込む
			// --------------------------------------------------

			else if (Input::post('api_type') === 'content_wiki_create')
			{
				// $view = \View::forge('wiki/wiki_create_view');
				// $arr['code'] = $view->render();

				$original_code_wiki = new Original\Code\Wiki();
				$arr = $original_code_wiki->form_create([]);
			}


			// --------------------------------------------------
			//   Wiki編集フォーム　ホバーで読み込む
			// --------------------------------------------------

			else if (Input::post('api_type') === 'content_wiki_edit')
			{
				// $temp_arr = array(
				// 	'edit' => true
				// );

				$original_code_wiki = new Original\Code\Wiki();
				$arr = $original_code_wiki->form_edit(Input::post());
			}




			// --------------------------------------------------
			//   ゲームページ / 交流掲示板 / 個別読み込み BBS ID
			// --------------------------------------------------

			else if (Input::post('api_type') === 'bbs_read_individual_gc')
			{
				$original_code_bbs = new Original\Code\Bbs();
				$arr = $original_code_bbs->get_code_bbs_individual_gc(Input::post());
			}


			// --------------------------------------------------
			//   募集掲示板 / メニュー
			// --------------------------------------------------

			else if (Input::post('api_type') === 'recruitment_menu')
			{
				$original_code_gc = new Original\Code\Gc();
				$arr = $original_code_gc->recruitment_menu(Input::post());
			}






			// --------------------------------------------------
			//   コミュニティ / 告知
			// --------------------------------------------------

			else if (Input::post('api_type') === 'community_announcement')
			{
				$original_code_co = new Original\Code\Co();
				$arr = $original_code_co->announcement(Input::post());
			}


			// --------------------------------------------------
			//   コミュニティ / 掲示板 / 個別読み込み BBS ID
			// --------------------------------------------------

			else if (Input::post('api_type') === 'bbs_read_individual_uc')
			{
				$original_code_bbs = new Original\Code\Bbs();
				$arr = $original_code_bbs->get_code_bbs_individual_uc(Input::post());
			}


			// --------------------------------------------------
			//   コミュニティ / メンバー読み込み
			// --------------------------------------------------

			else if (Input::post('api_type') === 'community_member')
			{
				$original_code_co = new Original\Code\Co();
				$arr = $original_code_co->member(Input::post());
			}


			// --------------------------------------------------
			//   コミュニティ / データ読み込み
			// --------------------------------------------------

			else if (Input::post('api_type') === 'community_data')
			{
				$original_code_co = new Original\Code\Co();
				$arr = $original_code_co->data(Input::post());
			}


			// --------------------------------------------------
			//   コミュニティ / 通知読み込み
			// --------------------------------------------------

			else if (Input::post('api_type') === 'community_notification')
			{
				$original_code_co = new Original\Code\Co();
				$arr = $original_code_co->notification(Input::post());
			}


			// --------------------------------------------------
			//   コミュニティ / 設定
			// --------------------------------------------------

			else if (Input::post('api_type') === 'community_config')
			{
				$original_code_co = new Original\Code\Co();
				$arr = $original_code_co->config(Input::post());
			}




			// --------------------------------------------------
			//   プレイヤーページ / 基本設定
			// --------------------------------------------------

			else if (Input::post('api_type') === 'config_player_basic')
			{
				$original_code_player = new Original\Code\Player();
				$arr = $original_code_player->config_basic(Input::post());
			}


			// --------------------------------------------------
			//   プレイヤーページ / 通知設定
			// --------------------------------------------------

			else if (Input::post('api_type') === 'config_notification')
			{
				$original_code_player = new Original\Code\Player();
				$arr = $original_code_player->config_notification(Input::post());
			}

			// --------------------------------------------------
			//   プレイヤーページ / 広告設定
			// --------------------------------------------------

			else if (Input::post('api_type') === 'config_advertisement')
			{
				$original_code_player = new Original\Code\Player();
				$arr = $original_code_player->config_advertisement(Input::post());
			}

			// --------------------------------------------------
			//   プレイヤーページ / Wiki設定
			// --------------------------------------------------

			// else if (Input::post('api_type') === 'config_wiki')
			// {
			// 	$original_code_wiki = new Original\Code\Wiki();
			// 	$arr = $original_code_wiki->tab(array(Input::post()));
			// }




			// --------------------------------------------------
			//   フッター / カード
			// --------------------------------------------------

			else if (Input::post('api_type') === 'footer_card')
			{
				$original_code_card = new Original\Code\Card();
				$arr = $original_code_card->footer(Input::post());
			}


			// --------------------------------------------------
			//   フッター / 音訓索引
			// --------------------------------------------------

			else if (Input::post('api_type') === 'game_index')
			{
				$original_code_index = new Original\Code\Index();
				$arr = $original_code_index->game_index(Input::post());
			}


		}
		catch (Exception $e)
		{
			if (isset($test)) \Debug::dump($e);

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'エラー';
			$arr['alert_message'] = 'Error: ' . $e->getMessage();
		}


		// --------------------------------------------------
		//   出力
		// --------------------------------------------------

		if (isset($test))
		{
			\Debug::dump($arr);

			if (isset($arr['code'])) echo $arr['code'];
		}
		else
		{
			return $this->response($arr);
		}

	}

}
