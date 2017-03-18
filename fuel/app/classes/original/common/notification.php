<?php

namespace Original\Common;

use Minishlink\WebPush\WebPush;


class Notification
{

	private $web_push_arr = array();


	/**
	 * クラスの初期化
	 */
	public static function _init()
	{
		//パッケージの読み込み
		\Package::load('pushnotification');

		//$this->gcm_api_key = \Config::get('gcm_api_key');
	}

	// public function before()
	// {
	// 	// initの中に入れるとエラーが起こるので、beforeと分けている
	// 	$this->gcm_api_key = \Config::get('gcm_api_key');
	// 	echo 'aaaaaaaaaaaaa';
	//
	// }


	/**
	* Androidに送信
	*
	* @param string $token トークン
	* @param string $message メッセージ
	* @param array $data_arr データ
	* @return string 成功した場合はtrue、失敗した場合はfalse
	*/
	public function send_android($token, $message, $data_arr = null) {

		$gcm = \Pushnotification::forge('gcm');
		$gcm->set_message($message);
		//$gcm->set_message('test message');

		//$gcm->add_recepient('APA91bGuZZREUUNYitp6_3quQPHHtJRt_u8hhFhEf-4YA25zuVF-g0nMuCDL77eBCOqqzhVl5GEN_P1x-uhn7vONw0eyX2omR2SaQ37966Vs9q8h2CPYuQlAAjLxJuxnXs_rbnkCJP9KZ_kwwnkWMdnOpOVzA4dJ9w');
		$gcm->add_recepient($token);
		//$gcm->set_recepients($token_arr); // you can also use an array (up to 1000 devices per send)

		// set additional data
		if (isset($data_arr))
		{
			$gcm->set_data($data_arr);
			/*
			 * $gcm->set_data(array(
			    'some_key' => 'some_val'
			));
			*/
		}


		// also you can add time to live
		//    $gcm->set_ttl(500);
		// and unset in further
		$gcm->set_ttl(false);

		// set group for messages if needed
		//    $gcm->set_group('Test');
		// or set to default
		$gcm->set_group(false);

		// then send
		// 正常に送れなかったトークンがあった場合、falseが返ってくる。すべて正常だとtrue
		$result = ($gcm->send()) ? true : false;
		// if($result)
		    // echo 'Success for all messages ';
		// else
		    // echo 'Some messages have errors ';

		// and see responses for more info
		//print_r($gcm->status);
		//print_r($gcm->messages_statuses);

		return $result;

	}



	/**
	* Androidに送信
	* 途中でメッセージが送れないトークンがあっても、送信をやめることはない。全部のトークンに送ってくれる。
	*
	* @param array $token_arr トークン
	* @param string $message メッセージ
	* @param array $data_arr データ
	* @return string 成功した場合はtrue、失敗した場合はfalse
	*/
	public function send_all_android($token_arr, $message, $data_arr = null) {

		$gcm = \Pushnotification::forge('gcm');
		$gcm->set_message($message);
		//$gcm->set_message('test message');

		//$gcm->add_recepient('APA91bGuZZREUUNYitp6_3quQPHHtJRt_u8hhFhEf-4YA25zuVF-g0nMuCDL77eBCOqqzhVl5GEN_P1x-uhn7vONw0eyX2omR2SaQ37966Vs9q8h2CPYuQlAAjLxJuxnXs_rbnkCJP9KZ_kwwnkWMdnOpOVzA4dJ9w');
		//$gcm->add_recepient($token);
		$gcm->set_recepients($token_arr); // you can also use an array (up to 1000 devices per send)

		// set additional data
		if (isset($data_arr))
		{
			$gcm->set_data($data_arr);
			/*
			 * $gcm->set_data(array(
				'some_key' => 'some_val'
			));
			*/
		}


		// also you can add time to live
		//    $gcm->set_ttl(500);
		// and unset in further
		$gcm->set_ttl(false);

		// set group for messages if needed
		//    $gcm->set_group('Test');
		// or set to default
		$gcm->set_group(false);

		// then send
		// 正常に送れなかったトークンがあった場合、falseが返ってくる。すべて正常だとtrue
		$result = ($gcm->send()) ? true : false;
		// if($result)
			// echo 'Success for all messages ';
		// else
			// echo 'Some messages have errors ';

		// and see responses for more info
		//print_r($gcm->status);
		//print_r($gcm->messages_statuses);

		return $result;

	}



	/**
	* iOSに送信
	*
	* @param string $token トークン
	* @param string $message メッセージ
	* @param integer $badge バッジ
	* @param array $data_arr データ
	* @return string 成功した場合はtrue、失敗した場合はfalse
	*/
	public function send_ios($token, $message, $badge, $data_arr = null) {

		$apns = \Pushnotification::forge('apns');
		//$apns->payload_method = 'enhance'; // you can turn on this method for debuggin purpose
		$apns->payload_method = 'enhance';
		$apns->connect_to_push();

		// adding custom variables to the notification
		if (isset($data_arr))
		{
			$apns->set_data($data_arr);
		}

		//$device_token = "db0431d255b307e5da9174e1eb1475234fdb2954fccc403e626158f926fae331";

		$send_result = $apns->send_message($token, $message, $badge, 'default'  );

		$result = ($send_result) ? true : false;
		// if($send_result)
		    // echo "apns send successful";
		// else
		    // echo $apns->error;

		$apns->disconnect_push();

		return $result;

	}




	/**
	* Web Puｓｈ （ブラウザへの通知）
	* Setter / 配列作成
	*
	* @param string $argument
	*/
	public function set_web_push_arr(array $arr)
	{

		$payload_json = json_encode(array(
			'title' => $arr['title'] ?? null,
			'body' => $arr['body'] ?? null,
			'icon' => $arr['icon'] ?? null,
			'tag' => $arr['tag'] ?? null,
			'url' => $arr['url'] ?? null,
			'vibrate' => $arr['vibrate'] ?? null
		));

		$key = ($arr['ttl'] ?? null) . ',' . ($arr['urgency'] ?? null) . ',' . ($arr['topic'] ?? null);

		if (empty($this->web_push_arr[$key])) $this->web_push_arr[$key] = array();


		//\Debug::dump($key);

		$temp_arr = array(
			'endpoint' => $arr['endpoint'],
			'payload' => $payload_json,
			'userPublicKey' => $arr['public_key'],
			'userAuthToken' => $arr['auth_token'],
		);

		array_push($this->web_push_arr[$key], $temp_arr);

	}

	public function get_web_push_arr() : array
	{
		return $this->web_push_arr;
	}



	/**
	* Web Puｓｈ （ブラウザへの通知）
	* まとめて送信することができる、違うメッセージ、違う条件でもまとめて送れる
	* 途中でメッセージが送れないエンドポイントがあっても、送信をやめることはない。全部のエンドポイントに送ってくれる。
	* タイトルの最大文字数は40文字？それ以上だと「…」が入って省略されるようだ
	* コメントの最大文字数は120文字？それ以上だと「…」が入って省略されるようだ
	*
	* @return bool 成功した場合はtrue、失敗した場合はfalse
	*/
	public function send_web_push() : bool {

		// --------------------------------------------------
		//   配列取得、ない場合は送信しない
		// --------------------------------------------------

		$web_push_arr = $this->get_web_push_arr();

		if (empty($web_push_arr)) exit();



		$apiKeys = array(
			'GCM' => \Config::get('gcm_api_key'),
		);

		$webPush = new WebPush($apiKeys);

		//$result = $webPush->sendNotification($endpoint, $payload, $userPublicKey, $userAuthToken, true);


		// --------------------------------------------------
		//   送信
		// --------------------------------------------------

		foreach ($web_push_arr as $key => $value) {

			$temp_arr = explode(',', $key);

			if ($temp_arr[0]) $options_arr['TTL'] = $temp_arr[0];
			if ($temp_arr[1]) $options_arr['urgency'] = $temp_arr[1];
			if ($temp_arr[2]) $options_arr['topic'] = $temp_arr[2];

			//\Debug::dump($key, $value, $temp_arr);
			// if (isset($options_arr))
			// {
			// 	echo '$options_arr';
			// 	\Debug::dump($options_arr);
			// }

			foreach ($value as $key2 => $value2) {

				//\Debug::dump($key2, $value2);

				$webPush->sendNotification(
					$value2['endpoint'],
					$value2['payload'], // optional (defaults null)
					$value2['userPublicKey'], // optional (defaults null)
					$value2['userAuthToken'] // optional (defaults null)
				);

			}

			if (isset($options_arr))
			{
				$webPush->setDefaultOptions($options_arr);
				unset($options_arr);
			}

			$result = $webPush->flush();

			//if ( ! \Auth::member(100)) return $result;

		}


		//$return_value = (isset($result['success'])) ? false : true;


		// 成否の判定で余計な処理能力を使うってしまうかもしれないため、判定はしないことにする
		return true;

	}

}
