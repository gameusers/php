<?php

namespace Original\Common;

class Crypter
{

	/**
	* 暗号化・復合化して結果データを取得
	*
	* @param array or string $data 暗号化・復合化するデータ
	* @return array or string 暗号化・復合化された結果データ
	*/
	public function encrypt($data) {
		
		// 暗号モジュールをオープンします
		$td = mcrypt_module_open('rijndael-128', '', 'ecb', '');
		
	    // IV を作成し、キー長を定義します。Windows では、かわりにMCRYPT_RAND を使用します　本番環境でMCRYPT_DEV_RANDOMを使うとなぜか重いため、MCRYPT_DEV_URANDOMに変更。
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_URANDOM);
		$ks = mcrypt_enc_get_key_size($td);
		
		// キーを作成します
		$key = substr(md5(\Config::get('crypter_key')), 0, $ks);
		
		// 暗号化・復号化処理を初期化します
		mcrypt_generic_init($td, $key, $iv);
		
		// データを暗号化します
		if (gettype($data) == 'array')
		{
			foreach ($data as &$value) {
				$value = base64_encode(mcrypt_generic($td, $value));
			}
			unset($value);
			$result = $data;
		} else
		{
			$result = base64_encode(mcrypt_generic($td, $data));
		}
		
		// 復号ハンドルを終了し、モジュールを閉じます
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		return $result;
		
	}
	
	public function decrypt($data) {
		
		// 暗号モジュールをオープンします
		$td = mcrypt_module_open('rijndael-128', '', 'ecb', '');
		
	    // IV を作成し、キー長を定義します。Windows では、かわりにMCRYPT_RAND を使用します
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_URANDOM);
		$ks = mcrypt_enc_get_key_size($td);
		
		// キーを作成します
		$key = substr(md5(\Config::get('crypter_key')), 0, $ks);
		
		// 暗号化・復号化処理を初期化します
		mcrypt_generic_init($td, $key, $iv);
		
		// 暗号化された文字列を復号します ＆ rtrimを使って末尾の「\0」を削除
		if (gettype($data) == 'array')
		{
			foreach ($data as &$value) {
				$value = rtrim(mdecrypt_generic($td, base64_decode($value)), "\0");
			}
			unset($value);
			$result = $data;
		} else
		{
			$result = rtrim(mdecrypt_generic($td, base64_decode($data)), "\0");
		}
		
		// 復号ハンドルを終了し、モジュールを閉じます
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		return $result;
		
	}

}