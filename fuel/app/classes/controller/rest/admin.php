<?php

class Controller_Rest_Admin extends Controller_Rest_Base
{


	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// ------------------------------
	//   インスタンス
	// ------------------------------

	//private $model_wiki = null;

	private $original_wiki_set = null;




	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();


		// --------------------------------------------------
		//   CSRFチェック
		// --------------------------------------------------

		$original_validation_common = new Original\Validation\Common();
		$original_validation_common->csrf(Input::post('fuel_csrf_token'));


		// --------------------------------------------------
		//   管理者チェック
		// --------------------------------------------------

		if ( ! Auth::member(100))
		{
			throw new HttpNotFoundException;
		}


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		//$this->model_wiki = new \Model_Wiki();

		$this->original_wiki_set = new Original\Wiki\Set();

	}



	/**
	* プロフィール読み込み
	*
	* @return string HTMLコード
	*/
	public function post_delete_email()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['email'] = 'rodinia@hotmail.co.jp';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   フォームが正しい経緯で送信されていません。
			// --------------------------------------------------

			// $cookie_csrf_token = Input::cookie(Config::get('security.csrf_token_key', 'fuel_csrf_token'));
			// $post_csrf_token = Input::post('fuel_csrf_token');
//
			// if (Config::get('check_csrf_token') and ( ! $cookie_csrf_token or ! $post_csrf_token or $cookie_csrf_token != $post_csrf_token))
			// {
				// $arr['alert_color'] = 'warning';
				// $arr['alert_title'] = 'Error';
				// $arr['alert_message'] = 'Error';
				// throw new Exception('Error');
			// }


			// --------------------------------------------------
			//   管理者チェック。
			// --------------------------------------------------

			// if ( ! Auth::member(100))
			// {
				// $arr['alert_color'] = 'danger';
				// $arr['alert_title'] = 'Error';
				// $arr['alert_message'] = 'Error';
				// throw new Exception('Error');
			// }


			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();
			$val->add_callable('Original_Rule_User');
			$val->add_field('email', 'E-Mail', 'required|valid_email|email_existence_users_login');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_email = $val->validated('email');


				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$model_mail = new Model_Mail();


				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '<br>$validated_email';
					var_dump($validated_email);

				}
				//exit();


				// --------------------------------------------------
				//   メール削除
				// --------------------------------------------------

				$model_mail->delete_mail_search_address($validated_email);


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = '削除しました。';

			}
			else
			{

				// --------------------------------------------------
				//   アラート　エラー
				// --------------------------------------------------

				$error_message = '';
				if (count($val->error()) > 0)
				{
					foreach ($val->error() as $key => $value) {
						$error_message .= $value;
					}
				}
				$arr['alert_color'] = 'warning';
				$arr['alert_title'] = 'Error';
				$arr['alert_message'] = 'Error' . $error_message;

				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
			if (isset($test)) echo $e->getMessage();
		}


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
	* Wikiのテンプレートから各Wikiにコピーする　plugin、lib、skin、、pukiwiki.ini.phpなど
	*
	* @return string HTMLコード
	*/
	public function post_wiki_copy_template()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		// $test = true;

		if (isset($test))
		{
			// $_POST['plugin'] = 1;
			// $_POST['lib'] = 1;
			// $_POST['skin'] = 1;
			// $_POST['pukiwiki_ini'] = 1;
			$_POST['etc'] = 1;
		}


		$arr = array();

		try
		{


			// --------------------------------------------------
			//   コピー
			// --------------------------------------------------

			$temp_arr['plugin'] = (Input::post('plugin')) ? 1 : null;
			$temp_arr['lib'] = (Input::post('lib')) ? 1 : null;
			$temp_arr['skin'] = (Input::post('skin')) ? 1 : null;
			$temp_arr['pukiwiki_ini'] = (Input::post('pukiwiki_ini')) ? 1 : null;
			$temp_arr['etc'] = (Input::post('etc')) ? 1 : null;

			$result =  $this->original_wiki_set->copy_template($temp_arr);


			$arr['alert_color'] = 'success';
			$arr['alert_title'] = 'OK';
			$arr['alert_message'] = 'コピーしました。';



			if (isset($test))
			{

				\Debug::$js_toggle_open = true;


				// echo '$_POST';
				// \Debug::dump($_POST);
//
				// echo 'Input::post()';
				// \Debug::dump(Input::post());

			}

		}
		catch (Exception $e) {

			if (isset($test)) echo $e->getMessage();

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'Error';
			$arr['alert_message'] = $e->getMessage();

		}


		if (isset($test))
		{
			var_dump($arr);
		}
		else
		{
			return $this->response($arr);
		}

	}

}
