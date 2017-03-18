<?php

class Controller_Rest_Help extends Controller_Rest_Base
{


	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	//private $wiki_id = null;


	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $original_code_help = null;



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
		//   インスタンス作成
		// --------------------------------------------------

		$this->original_code_help = new Original\Code\Help();

	}






	/**
	* Setter / wiki_id
	*
	* @param string $argument
	*/
	// public function set_wiki_id($argument)
	// {
	// 	$this->wiki_id = $this->original_validation_common->wiki_id($argument);
	// }
	//
	// public function get_wiki_id()
	// {
	// 	return $this->wiki_id;
	// }






	/**
	* ヘルプ読み込み
	*
	* @return string HTMLコード
	*/
	public function post_read_help()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['first_load'] = null;
			$_POST['page'] = 1;
			$_POST['list'] = 'game';
			$_POST['content'] = 'game_about';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			$temp_arr['first_load'] = (Input::post('first_load')) ? Input::post('first_load') : null;
			$temp_arr['page'] = Input::post('page');
			$temp_arr['list'] = (Input::post('list')) ? Input::post('list') : null;
			$temp_arr['content'] = (Input::post('content')) ? Input::post('content') : null;

			$arr = $this->original_code_help->code_help($temp_arr);

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

			if (isset($arr)) \Debug::dump($arr);

		}
		else
		{
			return $this->response($arr);
		}

	}



	/**
	* お問い合わせ送信
	*
	* @return string HTMLコード
	*/
	public function post_send_inquiry()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['inquiry_name'] = '名前';
			$_POST['inquiry_email'] = 'rodinia@hotmail.co.jp';
			$_POST['inquiry_comment'] = 'コメント';
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
			// 	$arr['alert_color'] = 'warning';
			// 	$arr['alert_title'] = 'エラー';
			// 	$arr['alert_message'] = 'フォームが正しい経緯で送信されていません。フォームを送信する前に、他のページを開いた場合などにこのエラーが出ます。ページを再読み込みしてから、もう一度送信してください。';
			// 	throw new Exception('Error');
			// }


			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			// ------------------------------
			//    バリデーションルール設定
			// ------------------------------

			$val = Validation::forge();

			$val->add_field('inquiry_name', '名前（ハンドルネーム）', 'required|min_length[1]|max_length[50]');
			$val->add_field('inquiry_email', 'メールアドレス', 'valid_email');
			$val->add_field('inquiry_comment', 'お問い合わせ内容', 'required|min_length[1]|max_length[3000]');

			if ($val->run())
			{

				// --------------------------------------------------
				//   バリデーション後の値取得
				// --------------------------------------------------

				$validated_inquiry_name = $val->validated('inquiry_name');
				$validated_inquiry_email = ($val->validated('inquiry_email')) ? $val->validated('inquiry_email') : 'inquiry@gameusers.org';
				$validated_inquiry_comment = $val->validated('inquiry_comment');

				$validated_inquiry_comment .= "\n\n" . '--------------------' . "\n\n" . $this->host . "\n" . $this->user_agent . "\n\n" . '--------------------';



				// --------------------------------------------------
				//   共通処理
				// --------------------------------------------------

				// インスタンス作成
				$original_common_mail = new Original\Common\Mail();



				// --------------------------------------------------
				//    メール送信
				//    mail@gameusers.orgに送信するとなぜだか届かないので、sakura.ne.jp宛にする
				// --------------------------------------------------

				$result = $original_common_mail->to($validated_inquiry_email, $validated_inquiry_name, Config::get('inquiry_mail_address'), 'Game Users', 'お問い合わせ', $validated_inquiry_comment);




				if (isset($test))
				{
					//Debug::$js_toggle_open = true;

					echo '$validated_inquiry_name';
					var_dump($validated_inquiry_name);

					echo '$validated_inquiry_email';
					var_dump($validated_inquiry_email);

					echo '$validated_inquiry_comment';
					var_dump($validated_inquiry_comment);

				}
				//exit();


				// --------------------------------------------------
				//   アラート　成功
				// --------------------------------------------------

				$arr['alert_color'] = 'success';
				$arr['alert_title'] = 'OK';
				$arr['alert_message'] = 'お問い合わせを送信しました。';

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
				$arr['alert_title'] = 'エラー';
				$arr['alert_message'] = '送信できませんでした。' . $error_message;

				//$arr['test'] = 'エラー ' . $error_message;
				if (isset($test)) echo $error_message;

			}

		}
		catch (Exception $e) {
			//$arr['test'] = 'エラー ' . $e->getMessage();
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


}
