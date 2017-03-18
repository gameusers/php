<?php

class Controller_Rest_Notification extends Controller_Rest_Base
{


	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	//private $wiki_id = null;


	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $original_common_notification = null;



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

		//$this->original_code_help = new Original\Code\Help();
		$this->original_common_notification = new \Original\Common\Notification();

	}





	/**
	* 通知送信
	*
	* @return string HTMLコード
	*/
	public function post_send_notification()
	{


		// --------------------------------------------------
		//   管理者チェック
		// --------------------------------------------------

		if ( ! Auth::member(100))
		{
			throw new HttpNotFoundException;
		}


		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{

			\Debug::$js_toggle_open = true;

			$_POST['endpoint'] = '';
			$_POST['public_key'] = '';
			$_POST['auth_token'] = '';

			$_POST['title'] = 'タイトル';
			$_POST['body'] = '本文';

			// $_POST['ttl'] = 20;
			// $_POST['urgency'] = 'normal';
			// $_POST['topic'] = 'test';

		}


		$arr = array();

		// $arr['alert_color'] = '';
		// $arr['alert_title'] = '';
		// $arr['alert_message'] = '';

		try
		{

			// --------------------------------------------------
			//   バリデーション
			// --------------------------------------------------

			$val = \Validation::forge();

			$val->add_field('endpoint', 'endpoint', 'required');
			$val->add_field('public_key', 'public_key', 'required|exact_length[88]');
			$val->add_field('auth_token', 'auth_token', 'required|exact_length[24]');

			$val->add_field('title', 'title', 'required|min_length[1]|max_length[50]');
			$val->add_field('body', 'body', 'required|min_length[1]|max_length[300]');
			$val->add_field('icon', 'icon', 'valid_url');
			$val->add_field('tag', 'tag', 'required|min_length[1]|max_length[300]');
			$val->add_field('url', 'url', 'valid_url');

			$val->add_field('ttl', 'TTL', 'valid_string[numeric]');
			$val->add('urgency', 'urgency')->add_rule('match_pattern', '/^(very-low|low|normal|high)$/');
			$val->add_field('topic', 'topic', 'valid_string[alpha,lowercase,numeric,dashes]');

			if ($val->run($arr))
			{
				$validated_endpoint = $val->validated('endpoint');
				$validated_public_key = $val->validated('public_key');
				$validated_auth_token = $val->validated('auth_token');

				$validated_title = $val->validated('title');
				$validated_body = $val->validated('body');
				$validated_icon = ($val->validated('icon')) ? $val->validated('icon') : null;
				$validated_tag = ($val->validated('tag')) ? $val->validated('tag') : null;
				$validated_url = ($val->validated('url')) ? $val->validated('url') : null;

				$validated_ttl = ($val->validated('ttl')) ? $val->validated('ttl') : null;
				$validated_urgency = ($val->validated('urgency')) ? $val->validated('urgency') : null;
				$validated_topic = ($val->validated('topic')) ? $val->validated('topic') : null;
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
				//echo $error_message;
				throw new \Exception($error_message);
			}


			// --------------------------------------------------
			//   送信用配列作成
			// --------------------------------------------------

			$this->original_common_notification->set_web_push_arr(array(
				'endpoint' => $validated_endpoint,
				'public_key' => $validated_public_key,
				'auth_token' => $validated_auth_token,
				'title' => $validated_title,
				'body' => $validated_body,
				'icon' => $validated_icon,
				'tag' => $validated_tag,
				'url' => $validated_url,
				'ttl' => $validated_ttl,
				'urgency' => $validated_urgency,
				'topic' => $validated_topic,
			));



			// --------------------------------------------------
			//   送信データランダム生成
			// --------------------------------------------------

			// $original_common_text = new \Original\Common\Text();
			//
			// $random_number = mt_rand(1, 5);
			//
			// for ($i=0; $i < $random_number; $i++)
			// {
			// 	$this->original_common_notification->set_web_push_arr(array(
			// 		'endpoint' => $validated_endpoint,
			// 		'public_key' => $validated_public_key,
			// 		'auth_token' => $validated_auth_token,
			// 		'title' => $original_common_text->random_text_lowercase(10),
			// 		'body' => $original_common_text->random_text_lowercase(30),
			// 		'ttl' => mt_rand(100, 102),
			// 		'urgency' => $validated_urgency,
			// 		//'topic' => $original_common_text->random_text_lowercase(10),
			// 		// 'ttl' => $validated_ttl,
			// 		// 'urgency' => $validated_urgency,
			// 		// 'topic' => $validated_topic,
			// 	));
			// }

			//\Debug::dump($this->original_common_notification->get_web_push_arr());


			// --------------------------------------------------
			//   送信する
			// --------------------------------------------------

			$result = $this->original_common_notification->send_web_push();



			if (isset($test))
			{

				// echo '$validated_endpoint';
				// \Debug::dump($validated_endpoint);
				//
				// echo '$validated_public_key';
				// \Debug::dump($validated_public_key);
				//
				// echo '$validated_auth_token';
				// \Debug::dump($validated_auth_token);
				//
				// echo '$validated_title';
				// \Debug::dump($validated_title);
				//
				// echo '$validated_body';
				// \Debug::dump($validated_body);
				//
				// echo '$validated_ttl';
				// \Debug::dump($validated_ttl);
				//
				// echo '$validated_urgency';
				// \Debug::dump($validated_urgency);
				//
				// echo '$validated_topic';
				// \Debug::dump($validated_topic);

			}


			// --------------------------------------------------
			//   コード作成
			// --------------------------------------------------

			// $temp_arr['first_load'] = (Input::post('first_load')) ? Input::post('first_load') : null;
			// $temp_arr['page'] = Input::post('page');
			// $temp_arr['list'] = (Input::post('list')) ? Input::post('list') : null;
			// $temp_arr['content'] = (Input::post('content')) ? Input::post('content') : null;
			//
			// $arr = $this->original_code_help->code_help($temp_arr);

		}
		catch (Exception $e)
		{

			$arr['alert_color'] = 'warning';
			$arr['alert_title'] = 'エラー';
			$arr['alert_message'] = '保存できませんでした。' . $e->getMessage();

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

}
