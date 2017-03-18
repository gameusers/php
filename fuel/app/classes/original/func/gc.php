<?php

namespace Original\Func;

class Gc
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
	* 権限
	*
	* @param string $language 言語
	* @param integer $type タイプ
	* @param string $etc_title その他募集のタイトル
	* @return string
	*/
	public function get_recruitment_type($language, $type, $etc_title = null)
	{

		// if ($type == 1)
		// {
		// 	$recruitument_type = 'プレイヤー募集';
		// }
		// else if ($type == 2)
		// {
		// 	$recruitument_type = 'フレンド募集';
		// }
		// else if ($type == 3)
		// {
		// 	$recruitument_type = 'ギルド・クランメンバー募集';
		// }
		// else if ($type == 4)
		// {
		// 	$recruitument_type = '売買・交換相手募集';
		// }
		// else if ($type == 5)
		// {
		// 	$recruitument_type = $etc_title;
		// }
		$recruitument_type = $etc_title;


		return $recruitument_type;

	}



}
