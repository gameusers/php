<?php

namespace Original\Func;

class Amazon
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// ------------------------------
	//   共通
	// ------------------------------
	/*
	private $agent_type = null;
	private $host = null;
	private $user_agent = null;
	private $user_no = null;
	private $language = null;
	private $uri_base = null;
	private $uri_current = null;
	private $app_mode = null;
	*/

	// ------------------------------
	//   クラス共通
	// ------------------------------

	// private $type = null;
	// private $page = null;
	// private $login_profile_data_arr = null;
	// private $datetime_now = null;
	// private $online_limit = null;
	// private $anonymity = null;



	// ------------------------------
	//   インスタンス
	// ------------------------------

	private $model_amazon = null;

	// private $model_game = null;
	// private $model_user = null;
	// private $model_co = null;
	// private $model_notifications = null;
	// private $model_present = null;
	// private $model_bbs = null;
	// private $original_func_common = null;
	// private $original_func_co = null;

	private $original_common_date = null;
	private $original_validation_common = null;


	public $test = true;




	// --------------------------------------------------
	//   コンストラクタ
	// --------------------------------------------------

	public function __construct()
	{

		// ------------------------------
		//   インスタンス作成
		// ------------------------------

		$this->model_amazon = new \Model_Amazon();

		/*
		$this->model_game->agent_type = AGENT_TYPE;
		$this->model_game->user_no = USER_NO;
		$this->model_game->language = LANGUAGE;
		$this->model_game->uri_base = URI_BASE;
		$this->model_game->uri_current = URI_CURRENT;



		$this->model_user = new \Model_User();
		$this->model_user->agent_type = AGENT_TYPE;
		$this->model_user->user_no = USER_NO;
		$this->model_user->language = LANGUAGE;
		$this->model_user->uri_base = URI_BASE;
		$this->model_user->uri_current = URI_CURRENT;

		$this->model_co = new \Model_Co();
		$this->model_co->agent_type = AGENT_TYPE;
		$this->model_co->user_no = USER_NO;
		$this->model_co->language = LANGUAGE;
		$this->model_co->uri_base = URI_BASE;
		$this->model_co->uri_current = URI_CURRENT;

		$this->model_common = new \Model_Common();
		$this->model_common->agent_type = AGENT_TYPE;
		$this->model_common->user_no = USER_NO;
		$this->model_common->language = LANGUAGE;
		$this->model_common->uri_base = URI_BASE;
		$this->model_common->uri_current = URI_CURRENT;

		$this->model_notifications = new \Model_Notifications();
		$this->model_notifications->agent_type = AGENT_TYPE;
		$this->model_notifications->user_no = USER_NO;
		$this->model_notifications->language = LANGUAGE;
		$this->model_notifications->uri_base = URI_BASE;
		$this->model_notifications->uri_current = URI_CURRENT;

		$this->model_present = new \Model_Present();
		$this->model_present->agent_type = AGENT_TYPE;
		$this->model_present->user_no = USER_NO;
		$this->model_present->language = LANGUAGE;
		$this->model_present->uri_base = URI_BASE;
		$this->model_present->uri_current = URI_CURRENT;

		$this->model_bbs = new \Model_Bbs();

		$this->original_func_common = new \Original\Func\Common();
		$this->original_func_common->app_mode = APP_MODE;
		$this->original_func_common->agent_type = AGENT_TYPE;
		$this->original_func_common->user_no = USER_NO;
		$this->original_func_common->language = LANGUAGE;
		$this->original_func_common->uri_base = URI_BASE;
		$this->original_func_common->uri_current = URI_CURRENT;

		$this->original_func_co = new \Original\Func\Co();
		$this->original_func_co->app_mode = APP_MODE;
		$this->original_func_co->agent_type = AGENT_TYPE;
		$this->original_func_co->user_no = USER_NO;
		$this->original_func_co->language = LANGUAGE;
		$this->original_func_co->uri_base = URI_BASE;
		$this->original_func_co->uri_current = URI_CURRENT;
		*/
		$this->original_validation_common = new \Original\Validation\Common();

		$this->original_common_date = new \Original\Common\Date();



		// ------------------------------
		//   定数設定
		// ------------------------------
		/*
		if (AGENT_TYPE != 'smartphone')
		{
			define("LIMIT_BBS_THREAD_LIST", (int) \Config::get('limit_bbs_thread_list'));
			define("LIMIT_BBS_THREAD", (int) \Config::get('limit_bbs_thread'));
			define("LIMIT_BBS_COMMENT", (int) \Config::get('limit_bbs_comment'));
			define("LIMIT_BBS_REPLY", (int) \Config::get('limit_bbs_reply'));
		}
		else
		{
			define("LIMIT_BBS_THREAD_LIST", (int) \Config::get('limit_bbs_thread_list_sp'));
			define("LIMIT_BBS_THREAD", (int) \Config::get('limit_bbs_thread_sp'));
			define("LIMIT_BBS_COMMENT", (int) \Config::get('limit_bbs_comment_sp'));
			define("LIMIT_BBS_REPLY", (int) \Config::get('limit_bbs_reply_sp'));
		}
		*/
	}







	// --------------------------------------------------
	//   共通
	// --------------------------------------------------


	/**
	* Setter / ゲームNo / 共通
	*
	* @param string $argument ゲームNo
	*/
	// public function set_game_no($argument)
	// {
		// $this->game_no = (int) $this->original_validation_common->game_no($argument);;
	// }
//
	// public function get_game_no()
	// {
		// return $this->game_no;
	// }




	// --------------------------------------------------
	//   コード取得
	// --------------------------------------------------


	/**
	* Amazon Product Advertising API
	*
	* @return string HTMLコード
	*/
	public function save_api_data()
	{




		// --------------------------------------------------
		//   実行時間を5分に伸ばす
		// --------------------------------------------------

		set_time_limit(300);



		// --------------------------------------------------
		//   Webスクレイピングで値引率の高いソフトを取得　（-50%以上）
		//   https://www.amazon.co.jp/s/ref=sr_st_featured-rank?keywords=%E3%82%B2%E3%83%BC%E3%83%A0%E3%82%BD%E3%83%95%E3%83%88&rh=n%3A637394%2Ck%3A%E3%82%B2%E3%83%BC%E3%83%A0%E3%82%BD%E3%83%95%E3%83%88&qid=1478330351&__mk_ja_JP=%E3%82%AB%E3%82%BF%E3%82%AB%E3%83%8A&sort=featured-rank&pct-off=50-
		// --------------------------------------------------

		require APPPATH . 'vendor/phpquery/phpQuery-onefile.php';


		$asin_arr = [];

		for ($i=1; $i <= 5; $i++)
		{
			//$html = file_get_contents('http://192.168.10.2/gameusers/public/amazon_test.html');
			$html = file_get_contents('https://www.amazon.co.jp/s/ref=sr_pg_' . $i . '?rh=n%3A637394%2Ck%3A%E3%82%B2%E3%83%BC%E3%83%A0%E3%82%BD%E3%83%95%E3%83%88%2Cp_8%3A50-&page=' . $i . '&sort=featured-rank&keywords=%E3%82%B2%E3%83%BC%E3%83%A0%E3%82%BD%E3%83%95%E3%83%88&ie=UTF8&qid=1478330406');

			//DOM取得
			$doc = \phpQuery::newDocument($html);

			// ASIN配列に追加
			foreach ($doc["#resultsCol"]->find("ul")->find("li") as $key => $value)
			{
				array_push($asin_arr, pq($value)->attr('data-asin'));
			}

			sleep(3);
		}

		$asin_arr = array_unique($asin_arr);

		//\Debug::dump($asin_arr);
		//exit();



		// --------------------------------------------------
		//   XMLを取得
		// --------------------------------------------------

		// Product Advertising APIアカウント作成後に取得するアクセスキーを設定します。
		$aws_access_key_id = \Config::get('aws_access_key');

		// Product Advertising APIアカウント作成後に取得するシークレットアクセスキーを設定します。
		$aws_secret_key = \Config::get('aws_secret_key');

		// アソシエイトタグ
		$amazon_tracking_id = \Config::get('amazon_tracking_id');

		// エンドポイントを指定します。
		$endpoint = "webservices.amazon.co.jp";

		$uri = "/onca/xml";


		$insert_arr = array();
		$update_arr = array();


		// --------------------------------------------------
		//   日時を取得
		// --------------------------------------------------

		$datetime_now = $this->original_common_date->sql_format();



		// --------------------------------------------------
		//   データベース更新用の配列を作成する
		// --------------------------------------------------

		for ($i=1; $i <= 20; $i++)
		{

			if ($i <= 10)
			{
				$params = array(
				    "Service" => "AWSECommerceService",
				    "AWSAccessKeyId" => $aws_access_key_id,
				    "AssociateTag" => $amazon_tracking_id,
					"ResponseGroup" => "ItemAttributes,Offers,SalesRank,Images",
				    "Operation" => "ItemSearch",
				    "SearchIndex" => "VideoGames",
				    "BrowseNode" => "637394",
				    "Sort" => "salesrank",
				    "ItemPage" => $i
				);
			}
			else if (count($asin_arr) > 0)
			{

				//\Debug::dump($asin_arr);

				// 分割してコンマ区切りのASIN文字列を作成する　AAA,BBB,CCC
				$temp_arr = array_splice($asin_arr, 0, 10);
				$asin = implode($temp_arr, ',');

				$params = array(
				    "Service" => "AWSECommerceService",
				    "AWSAccessKeyId" => $aws_access_key_id,
				    "AssociateTag" => $amazon_tracking_id,
					"ResponseGroup" => "ItemAttributes,Offers,SalesRank,Images",
					"Operation" => "ItemLookup",
					"IdType" => "ASIN",
					"ItemId" => $asin,
					// "Sort" => "price",
					// "MinimumPrice" => "980",
					// "MaximumPrice" => "3000",
				    "ItemPage" => $i
				);


			} else {
				break;
			}

			//\Debug::dump($i);



			// タイムスタンプを追加します。
			if (!isset($params["Timestamp"])) {
			    $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
			}

			// キーを基準にパラメータをソートします。
			ksort($params);

			$pairs = array();

			// パラメータを key=value の形式に編集します。
			// 同時にURLエンコードを行います。
			foreach ($params as $key => $value) {
			    array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
			}

			// パラメータを&で連結します。
			$canonical_query_string = join("&", $pairs);

			// 署名に必要な文字列を先頭に追加します。
			$string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

			// RFC2104準拠のHMAC-SHA256ハッシュアルゴリズムの計算を行います。
			// これがSignatureの値になります。
			$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));

			// Siginatureの値のURLエンコードを行い、リクエストの最後に追加します。
			$request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

			//\Debug::dump($request_url);
			// \Debug::dump(date('Y-m-d H:i:s'));
			// \Debug::dump($params["Timestamp"]);
			//exit();
			//continue;


			$xml_object = @simplexml_load_file($request_url);

			//\Debug::dump($xml_object);
			//exit();

			sleep(1);

			if ($xml_object)
			{
				//\Debug::dump($xml_object);
				$xml_arr = json_decode(json_encode($xml_object), TRUE);
			}
			else
			{
				if ($i == 1) echo '<br><br><br><br>';
				echo 'Error ItemPage = ' . $i . '<br>';
				continue;
			}





			// --------------------------------------------------
			//   挿入する配列を作成
			// --------------------------------------------------

			$check_arr = array();

			if (isset($xml_arr['Items']['Item']))
			{

				foreach ($xml_arr['Items']['Item'] as $key => $value)
				{
					$asin = $value['ASIN'] ?? null;
					$sales_rank = $value['SalesRank'] ?? null;
					$title = $value['ItemAttributes']['Title'] ?? null;
					$list_price = $value['ItemAttributes']['ListPrice']['Amount'] ?? null;
					$price = $value['Offers']['Offer']['OfferListing']['Price']['Amount'] ?? null;
					$discount_rate = ($list_price and $price and ($list_price > $price)) ? 100 - round($price / $list_price * 100) : null;
					$pre_image = $value['SmallImage']['URL'] ?? null;

					if ($pre_image)
					{
						$pre_image_arr = explode('https://images-fe.ssl-images-amazon.com/images/I/', $pre_image);
						//\Debug::dump($pre_image_arr);
						//$image = null;
						if (isset($pre_image_arr[1]))
						{
							$pre_image_arr = explode('.', $pre_image_arr[1]);
							$image_id = $pre_image_arr[0];
						}
						else
						{
							$image_id = null;
						}
					}
					else
					{
						$image_id = null;
					}
					// echo $asin . "<br>";
					// echo $sales_rank . "<br>";
					// echo $title . "<br>";
					// echo $price . "<br><br>";
					//echo $image_id . "<br><br>";


					// Insert
					$temp_arr = array(
						'regi_date' => $datetime_now,
						'renewal_date' => $datetime_now,
						'asin' => $asin,
						'title' => $title,
						'list_price' => $list_price,
						'price' => $price,
						'discount_rate' => $discount_rate,
						'sales_rank' => $sales_rank,
						'image_id' => $image_id
					);

					array_push($insert_arr, $temp_arr);


					// Update
					$temp_arr = array(
						'renewal_date' => $datetime_now,
						'asin' => $asin,
						'list_price' => $list_price,
						'price' => $price,
						'discount_rate' => $discount_rate,
						'sales_rank' => $sales_rank,
						'image_id' => $image_id
					);

					array_push($update_arr, $temp_arr);


					array_push($check_arr, $asin);

				}

			}

			//
			//\Debug::dump($result_arr);

			//echo '$check_arr';
			//\Debug::dump($check_arr);



			// --------------------------------------------------
			//   すでにデータが存在しているかチェック
			// --------------------------------------------------

			$result_arr = $this->model_amazon->check_data($check_arr);

			//echo '$result_arr';
			//\Debug::dump($result_arr);


			$existence_asin_arr = array();

			foreach ($result_arr as $key => $value)
			{
				array_push($existence_asin_arr, $value['asin']);
			}

			//echo '$existence_asin_arr';
			//\Debug::dump($existence_asin_arr);


			foreach ($insert_arr as $key => $value)
			{
				//\Debug::dump($value['asin']);
				//echo $key . "<br>";
				// Insert
				if (array_search($value['asin'], $existence_asin_arr) !== false)
				{
					unset($insert_arr[$key]);


					//array_push($update_arr, $value);
				}
				// Update
				else
				{
					unset($update_arr[$key]);
					//echo 'aaaaaaa';
					//array_push($update_arr, $value);
				}
			}

		}


		//echo "Signed URL: \"".$request_url."\"";



		// --------------------------------------------------
		//   データベース　Insert
		// --------------------------------------------------

		if (count($insert_arr) > 0) $result_arr = $this->model_amazon->insert_data(array('insert_arr' => $insert_arr));


		// --------------------------------------------------
		//   データベース　Update
		// --------------------------------------------------

		if (count($update_arr) > 0) $result_arr = $this->model_amazon->update_data(array('update_arr' => $update_arr));




		// echo '$insert_arr';
		// \Debug::dump($insert_arr);
//
		// echo '$update_arr';
		// \Debug::dump($update_arr);


	}


}
