<?php

class Controller_Rest_Social extends Controller_Rest_Base
{


	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	//private $wiki_id = null;


	// ------------------------------
	//   インスタンス
	// ------------------------------

	// private $model_gc = null;
	// private $model_wiki = null;
	// private $model_advertisement = null;
	//
	// private $original_validation_common = null;
	// private $original_validation_fieldsetex = null;
	//
	// private $original_func_common = null;
	//
	// private $original_code_advertisement = null;
	// private $original_original_code_wiki = null;
	// private $original_wiki_set = null;



	/**
	* 事前処理
	*/
	public function before()
	{
		parent::before();


		// --------------------------------------------------
		//   インスタンス作成
		// --------------------------------------------------

		// $this->model_gc = new Model_Gc();
		// $this->model_wiki = new Model_Wiki();
		// $this->model_advertisement = new Model_Advertisement();
		//
		// $this->original_validation_common = new Original\Validation\Common();
		// $this->original_validation_fieldsetex = new \Original\Validation\Fieldsetex();
		//
		// $this->original_func_common = new \Original\Func\Common();
		//
		// $this->original_code_advertisement = new \Original\Code\Advertisement();
		// $this->original_code_wiki = new Original\Code\Wiki();

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
	* Google Plusのカウント取得
	*
	* @return string HTMLコード
	*/
	public function post_count_google_plus()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['url'] = 'http://www.yahoo.co.jp/';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   URL設定
			// --------------------------------------------------

			$url = (Input::post('url')) ? Input::post('url') : null;
			//$url = 'http://www.yahoo.co.jp/';

			// --------------------------------------------------
			//   URLがない場合は処理停止
			// --------------------------------------------------

			if ( ! $url) exit();


			// --------------------------------------------------
			//   カウント取得
			// --------------------------------------------------

			// リクエストURL
			$request_url = "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ" ;

			// データをJSON形式で取得する (cURLを使用)
			$curl = curl_init() ;
			curl_setopt( $curl, CURLOPT_URL, $request_url ) ;
			curl_setopt( $curl, CURLOPT_POST, 1 ) ;
			curl_setopt( $curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]' ) ;
			curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-type: application/json' ) ) ;
			curl_setopt( $curl, CURLOPT_HEADER, 1 ) ;						// ヘッダーを取得する
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false ) ;			// 証明書の検証を行わない
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ) ;			// curl_execの結果を文字列で返す
			curl_setopt( $curl, CURLOPT_FOLLOWLOCATION , true ) ;			// リダイレクト先を追跡するか？
			curl_setopt( $curl, CURLOPT_MAXREDIRS, 5 ) ;					// 追跡する回数
			curl_setopt( $curl, CURLOPT_TIMEOUT, 15 ) ;						// タイムアウトの秒数
			$res1 = curl_exec( $curl ) ;
			$res2 = curl_getinfo( $curl ) ;
			curl_close( $curl ) ;

			// 取得したデータの整理
			$json = substr( $res1, $res2['header_size'] ) ;					// 取得したデータ(JSONなど)
			$header = substr( $res1, 0, $res2['header_size'] ) ;			// レスポンスヘッダー (検証に利用したい場合にどうぞ)

			// JSONデータからカウント数を取得
			$array = json_decode( $json, true ) ;

			// カウント(データが存在しない場合は0扱い)
			if( isset($array[0]['result']['metadata']['globalCounts']['count']) ) {
				$count = (int)$array[0]['result']['metadata']['globalCounts']['count'] ;
			} else {
				$count = 0 ;
			}

			$arr['count'] = $count;

			//echo $count;

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
	* Pocketのカウント取得
	*
	* @return string HTMLコード
	*/
	public function post_count_pocket()
	{

		// --------------------------------------------------
		//   テスト変数
		// --------------------------------------------------

		//$test = true;

		if (isset($test))
		{
			$_POST['url'] = 'http://www.yahoo.co.jp/';
		}


		$arr = array();

		try
		{

			// --------------------------------------------------
			//   URL設定
			// --------------------------------------------------

			$url = (Input::post('url')) ? Input::post('url') : null;
			

			// --------------------------------------------------
			//   URLがない場合は処理停止
			// --------------------------------------------------

			if ( ! $url) exit();


			// --------------------------------------------------
			//   カウント取得
			// --------------------------------------------------

			//指定されたURLのPocketボタンを取得するクエリ
			$query = 'https://widgets.getpocket.com/v1/button?v=1&count=horizontal&url=' . $url . '&src=' . $url;

			//HTTPでアクセスし、Pocketボタンのソースを取得
			$html = file_get_contents($query);

			//DOMパーサーを作成
			$dom = new DOMDocument('1.0', 'UTF-8');

			// 余分な空白は削除
			$dom->preserveWhiteSpace = false;

			//PocketボタンのHTMLソースを読み込む
			$dom->loadHTML($html);

			//XPathパーサーを作成
			$xpath = new DOMXPath($dom);
// \Debug::dump($query, $html, $xpath);
			//XPathでブックマーク数にあたる要素を取得
			$result = $xpath->query('//em[@id = "cnt"]')->item(0);

			// カウント(データが存在しない場合は0扱い)
			if (isset($result->nodeValue)) {
				$count = $result->nodeValue;
			} else {
				$count = 0 ;
			}

			$arr['count'] = $count;

			//echo $count;

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

}
