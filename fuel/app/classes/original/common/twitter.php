<?php

namespace Original\Common;

//class Original_Common_Twitter {
class Twitter
{
	
	/**
	 * クラスの初期化
	 */
	public static function _init()
	{
		require_once(APPPATH . 'vendor/twitteroauth/twitteroauth.php');
	}
	
	
	/**
	* リクエストトークン、承認URLを取得
	*
	* @param string $consumer_key コンシューマーキー
	* @param string $consumer_secret コンシューマーシークレット
	* @param string $callback_url コールバックURL
	* @return array
	*/
	public function get_authorize_url($consumer_key, $consumer_secret, $callback_url) {
		
		$request_token = null;
		$request_token_secret = null;
		$authorize_url = null;
		
		// OAuthオブジェクトの生成
		$connect = new \TwitterOAuth($consumer_key, $consumer_secret);
		
		// リクエストトークンの取得
		$request_token_arr = $connect->getRequestToken($callback_url);
		
		$request_token = $request_token_arr['oauth_token'];
		$request_token_secret = $request_token_arr['oauth_token_secret'];
		
		// 認証用URLの取得
		$authorize_url = $connect->getAuthorizeURL($request_token_arr);
		
		return array('request_token' => $request_token, 'request_token_secret' => $request_token_secret, 'authorize_url' => $authorize_url);
	}
	
	
	/**
	* アクセストークン、ユーザーIDを取得
	*
	* @param string $consumer_key コンシューマーキー
	* @param string $consumer_secret コンシューマーシークレット
	* @param string $request_token リクエストトークン
	* @param string $request_token_secret リクエストトークンシークレット
	* @param string $verifier ベリファイア
	* @return array
	*/
	public function get_user_data($consumer_key, $consumer_secret, $request_token, $request_token_secret, $verifier) {
		
		$access_token = null;
		$access_token_secret = null;
		$user_id = null;
		
		// OAuthオブジェクトの生成
		$connect = new \TwitterOAuth($consumer_key, $consumer_secret, $request_token, $request_token_secret);
		
		// ユーザー情報の取得
		$access_token_arr = $connect->getAccessToken($verifier);
		
		$access_token = $access_token_arr['oauth_token'];
		$access_token_secret = $access_token_arr['oauth_token_secret'];
		$user_id = $access_token_arr['user_id'];
		
		return array('access_token' => $access_token, 'access_token_secret' => $access_token_secret, 'user_id' => $user_id);
	}
	
	
	/**
	* ツイートする
	*
	* @param string $consumer_key コンシューマーキー
	* @param string $consumer_secret コンシューマーシークレット
	* @param string $access_token アクセストークン
	* @param string $access_token_secret アクセストークンシークレット
	* @param string $message ツイートするメッセージ
	* @return array
	*/
	public function post_message($consumer_key, $consumer_secret, $access_token, $access_token_secret, $message) {
		
		// OAuthオブジェクトの生成
		$connect = new \TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
		
		// ツイートする
		$api_url = "https://api.twitter.com/1.1/statuses/update.json";
		$method = "POST";
		$result_arr = json_decode($connect->OAuthRequest($api_url, $method, array("status"=>$message)), true);
		
		//var_dump($result_arr);
		
		if (isset($result_arr['errors']))
		{
			return false;
		}
		else
		{
			return true;
		}
		//var_dump($result_arr['errors']);
	}

}