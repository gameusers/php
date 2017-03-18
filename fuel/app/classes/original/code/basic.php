<?php

namespace Original\Code;

class Basic
{

	// --------------------------------------------------
	//   プロパティ
	// --------------------------------------------------

	// PC・スマホ・タブレット
	public $agent_type = null;

	// ホスト
	public $host = null;

	// ユーザーエージェント
	public $user_agent = null;

	// ユーザーNo
	public $user_no = null;

	// 言語
	public $language = null;

	// URI
	public $uri_base = null;
	public $uri_current = null;

	// アプリモード
	public $app_mode = null;



	/**
	* アラート
	*
	* @param string $color 色 primary success info warning danger
	* @param string $title タイトル
	* @param string $message エラーメッセージ
	* @return string HTMLコード
	*/
	public function alert($color, $title, $message, $add_class = null)
	{

		// エスケープ
		$color = \Security::htmlentities($color);
		$title = \Security::htmlentities($title);
		$message = \Security::htmlentities($message);
		$add_class = \Security::htmlentities($add_class);

		if ($add_class) $add_class = ' ' . $add_class;

		$code = '    <div class="alert alert-' . $color . ' fade in' . $add_class . '">' . "\n";
	    $code .= '      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . "\n";
	    $code .= '      <strong>' . $title . ' : </strong> ' . $message . "\n";
	    $code .= '    </div>' . "\n\n";

		return $code;

	}



	/**
	* コード作成　ページャー
	*
	* @param array $arr 採点の配列
	* @return string HTMLコード
	*/
	public function pagination($page, $total, $limit, $times, $function_name, $argument_arr = array())
	{

		// エスケープ
		$page = \Security::htmlentities($page);
		$total = \Security::htmlentities($total);
		$limit = \Security::htmlentities($limit);
		$function_name = \Security::htmlentities($function_name);
		$argument_arr = \Security::htmlentities($argument_arr);


		// ページャーの数字表示回数
		//$times = 5;


		// 全ページ数計算
		$sum = floor($total / $limit);
		if($total % $limit > 0) {
			$sum++;
		}


		// 引数作成
		$argument = '';
		if (count($argument_arr) > 0)
		{
			$argument = ',' . implode(',', $argument_arr);
		}

		$code = '    <ul class="pagination pagination_box">' . "\n";


		// ----- 最初に戻るボタン-----

		if ($page == 1)
		{
			$code .= '      <li class="disabled"><a href="javascript:void(0)">&laquo;</a></li>' . "\n";
		}
		else
		{
			$code .= '     <li><a href="javascript:void(0)" onclick="' . $function_name . '(1' . $argument . ')">&laquo;</a></li>' . "\n";
		}


		// ----- ナンバーボタン -----

		$page_first = $page;
		if (($sum - $times + 1) < $page) $page_first = $sum - $times + 1;
		if ($page_first < 1) $page_first = 1;

		//var_dump($sum);

		for ($i = 0; $i < $times; $i++)
		{
			$page_number = $i + $page_first;
			//var_dump($page_number);

			if ($page_number == $page)
			{
				$code .= '      <li class="active"><a href="javascript:void(0)">' . $page_number . '</a></li>' . "\n";
			}
			else if ($sum >= $page_number)
			{
				$code .= '      <li><a href="javascript:void(0)" onclick="' . $function_name . '(' . $page_number . $argument . ')">' . $page_number . '</a></li>' . "\n";
			}
		}


		// ----- 最後まで進むボタン -----

		if ($page >= $sum)
		{
			$code .= '      <li class="disabled"><a href="javascript:void(0)">&raquo;</a></li>' . "\n";
		}
		else
		{
			$code .= '      <li><a href="javascript:void(0)" onclick="' . $function_name . '(' . $sum . $argument . ')">&raquo;</a></li>' . "\n";
		}

		$code .= '    </ul>' . "\n";

		return $code;

	}


	/**
	* コード作成　ページャー
	*
	* @param array $arr 採点の配列
	* @return string HTMLコード
	*/
	public function pagination_center($page, $total, $limit, $function_name, $argument_arr = array())
	{

		// エスケープ
		$page = \Security::htmlentities($page);
		$total = \Security::htmlentities($total);
		$limit = \Security::htmlentities($limit);
		$function_name = \Security::htmlentities($function_name);
		$argument_arr = \Security::htmlentities($argument_arr);


		// 現ナンバーの両サイトに並ぶ数字の数
		$side = 2;
		$argument = '';

		// 計算
		$sum = floor($total / $limit);
		if($total % $limit > 0) {
			$sum++;
		}

		// 引数作成
		if (count($argument_arr) > 0)
		{
			$argument = ',' . implode(',', $argument_arr);
		}

		$code = '        <ul class="pagination pagination_box">' . "\n";


		// 最初に戻るボタン
		if ($page == 1)
		{
			$code .= '          <li class="disabled"><a href="javascript:void(0)">&laquo;</a></li>' . "\n";
		}
		else
		{
			$code .= '         <li><a href="javascript:void(0)" onclick="' . $function_name . '(1' . $argument . ')">&laquo;</a></li>' . "\n";
		}

		// ナンバーボタン
		$times = 1 + $side * 2;

		if ($times > $sum)
		{
			$times = $sum;
		}

		if ($page < 1 + $side)
		{
			$page_first = 1;
		}
		else if ($page > $sum - $side)
		{
			$page_first = $sum - $side * 2;
		}
		else
		{
			$page_first = $page - $side;
		}

		if ($page_first < 1)
		{
			$page_first = 1;
		}

		for ($i = 0; $i < $times; $i++)
		{
			$page_number = $page_first + $i;

			if ($page_number == $page)
			{
				$code .= '          <li class="active"><a href="javascript:void(0)">' . $page_number . '</a></li>' . "\n";
			}
			else
			{
				$code .= '          <li><a href="javascript:void(0)" onclick="' . $function_name . '(' . $page_number . $argument . ')">' . $page_number . '</a></li>' . "\n";
			}

			if ($page_number == $sum)
			{
				break;
			}
		}

		// 最後まで進むボタン
		if ($page >= $sum)
		{
			$code .= '          <li class="disabled"><a href="javascript:void(0)">&raquo;</a></li>' . "\n";
		}
		else
		{
			$code .= '          <li><a href="javascript:void(0)" onclick="' . $function_name . '(' . $sum . $argument . ')">&raquo;</a></li>' . "\n";
		}

		$code .= '        </ul>' . "\n";

		return $code;

	}


	/**
	* コード作成　Javascript
	*
	* @param array $arr 変数名 => 値
	* @return string HTMLコード
	*/
	public function javascript($arr)
	{
		// エスケープ
		$arr = \Security::htmlentities($arr);

		$code = '<script type="text/javascript">' . "\n";

		foreach ($arr as $key => $value) {

			if (is_array($value))
			{
				$code_arr = array();

				foreach ($value as $key2 => $value2) {
					array_push($code_arr, '"' . $value2 . '"');
				}

				$code .= $key . ' = [' . implode(',', $code_arr) . '];' . "\n";
			}
			else
			{
				$code .= $key . ' = "' . $value . '";' . "\n";
			}

		}

	    $code .= '</script>' . "\n";

		return $code;
    }



	/**
	* コード作成　Javascript
	*
	* @param array $arr 変数名 => 値
	* @return string HTMLコード
	*/
	public function javascript_json_encode($arr)
	{
		// エスケープ
		$arr = \Security::htmlentities($arr);

		$code = '<script type="text/javascript">' . "\n";

		foreach ($arr as $key => $value) {

			if (is_array($value))
			{
				$code .= $key . ' = ' . json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ";\n";
			}
			else
			{
				$code .= $key . ' = "' . $value . '";' . "\n";
			}

		}

	    $code .= '</script>' . "\n";

		return $code;
    }



	/**
	* コード作成　Javascript
	*
	* @param array $arr 変数名 => 値
	* @return string HTMLコード
	*/
	public function javascript_json_encode_non_escape($arr)
	{
		// エスケープ
		// $arr = \Security::htmlentities($arr);

		$code = '<script type="text/javascript">' . "\n";

		foreach ($arr as $key => $value) {

			if (is_array($value))
			{
				$code .= $key . ' = ' . json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ";\n";
			}
			else
			{
				$code .= $key . ' = "' . $value . '";' . "\n";
			}

		}

		$code .= '</script>' . "\n";

		return $code;
	}



	/**
	* コード作成　Aタグ
	*
	* @param array $arr 変数名 => 値
	* @param boolean $no_href aタグの場合 false / それ以外の時はtrue
	* @return string HTMLコード
	*/
	public function change_page_tag($arr, $no_href = false)
	{

		// エスケープ
		$arr = \Security::htmlentities($arr);


		$tag = '';
		$argument_arr = array();

		foreach ($arr['page'] as $key => $value) {

			// ヘッダー
			if ($value['type'] == 'header')
			{
				array_push($argument_arr, '"header":""');
			}

			// フッター
			if ($value['type'] == 'footer')
			{
				array_push($argument_arr, '"footer":""');
			}

			// ログイン
			if ($value['type'] == 'login')
			{
				// すでにログインしている場合はlogoutのリンクに変更する
				if (\Auth::check())
				{
					$value['type'] = 'logout';
				}
				else if ($this->app_mode)
				{
					if (isset($value['redirect_type'], $value['redirect_id']))
					{
						array_push($argument_arr, '"contentsLogin":{"redirectType":"' . $value['redirect_type'] . '","redirectId":"' . $value['redirect_id'] . '"}');
						//var_dump('"contentsLogin":{"redirectType":"' . $value['redirect_type'] . '","redirectId":"' . $value['redirect_id'] . '"}');
					}
					else
					{
						array_push($argument_arr, '"contentsLogin":""');
					}
				}
				else
				{
					if (isset($value['redirect_type'], $value['redirect_id']))
					{
						$tag = ' href="' . $arr['uri_base'] . 'login?tp=' . $value['redirect_type'] . '&id=' . $value['redirect_id'] . '"';
					}
					else
					{
						$tag = ' href="' . $arr['uri_base'] . 'login"';
					}
				}
			}

			// ログアウト
			if ($value['type'] == 'logout')
			{
				if ($this->app_mode)
				{
					array_push($argument_arr, '"contentsLogout":""');
				}
				else
				{
					$tag = ' href="' . $arr['uri_base'] . 'logout"';
				}
			}

			// コンテンツ　index
			if ($value['type'] == 'index')
			{
				if ($this->app_mode)
				{
					array_push($argument_arr, '"contentsIndex":""');

					//$add_value = (isset($value['gcr'])) ? ',"gcr":"true"' : null;
					//array_push($argument_arr, '"contentsIndex":"{' . $add_value . '}"');
				}
				else
				{
					$tag = ' href="' . $arr['uri_base'] . '"';

					//$add_value = (isset($value['gcr'])) ? '/gcr' : null;
					//$tag = ' href="' . $arr['uri_base'] . $add_value . '"';
				}
			}

			// コンテンツ　present
			if ($value['type'] == 'present')
			{
				if ($this->app_mode)
				{
					array_push($argument_arr, '"contentsPresent":""');
				}
				else
				{
					$tag = ' href="' . $arr['uri_base'] . 'present"';
				}
			}

			// コンテンツ　player
			if ($value['type'] == 'pl')
			{
				//var_dump($value);
				if ($this->app_mode)
				{
					$add_value = (isset($value['notifications'])) ? ',"notifications":"true"' : null;
					$add_value .= (isset($value['profile_no'])) ? ',"profileNo":"' . $value['profile_no'] . '"' : null;
					array_push($argument_arr, '"contentsPlayer":{"userId":"' . $value['user_id'] . '"' . $add_value . '}');
				}
				else
				{
					$add_value = (isset($value['notifications'])) ? '/notifications' : null;
					if (isset($value['profile_no']))
					{
						$tag = ' href="' . $arr['uri_base'] . $value['type'] . '/' . $value['user_id'] . $add_value . '/prof/' . $value['profile_no'] .'"';
					}
					else
					{
						$tag = ' href="' . $arr['uri_base'] . $value['type'] . '/' . $value['user_id'] . $add_value . '"';
					}
				}
			}
//\Debug::dump($value);
			// コンテンツ　gc
			if ($value['type'] == 'gc')
			{

				$add_value = null;
// echo 'aaaaaaaaaaaaaaaaa';
				if (isset($value['recruitment_id']))
				{
					$add_value = '/rec/' . $value['recruitment_id'];
				}
				else if (isset($value['bbs_id']))
				{
					// echo 'bbbbbbbbbbbbbbbbbbbbb';
					$add_value = '/bbs/' . $value['bbs_id'];
				}
				else if (isset($value['bbs_thread_no']))
				{
					$add_value = '/bbs';
				}

				$tag = ' href="' . $arr['uri_base'] . $value['type'] . '/' . $value['id'] . $add_value . '"';
			}

			// コンテンツ　uc
			if ($value['type'] == 'uc')
			{

				$add_value = null;

				if (isset($value['bbs_id']))
				{
					$add_value = '/bbs/' . $value['bbs_id'];
				}
				else if (isset($value['bbs_thread_no']))
				{
					$add_value = '/bbs';
				}

				$tag = ' href="' . $arr['uri_base'] . $value['type'] . '/' . $value['community_id'] . $add_value . '"';
			}

		}

		if ($this->app_mode)
		{
			// if ($no_href == false) $tag .= ' href="#"';
			// $tag .= " onclick='appChangePage({" . implode(',', $argument_arr) . "})'";
			$tag .= ' target="_system" id="external_link"';
		}
		// else
		// {
			// $tag .= ' id="app_change_page"';
		// }


		return $tag;
    }



}
