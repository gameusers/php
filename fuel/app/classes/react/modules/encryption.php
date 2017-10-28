<?php

declare(strict_types=1);

namespace React\Modules;

class Encryption
{

    // --------------------------------------------------
    //   参考サイト
    //   http://php-archive.net/php/openssl-encrypt/
    //   https://tech.mktime.com/entry/451
    // --------------------------------------------------

    private $encryptionKey = null;
    private $method = 'aes-256-cbc';
    private $ivLength  = null;
    private $options = 0;



    /**
	* コンストラクター
	*/
	public function __construct()
	{

        // 暗号化キー取得
		$this->encryptionKey = \Config::get('openssl_encryption_key');

        // 方式に応じたIV(初期化ベクトル)に必要な長さを取得
        $this->ivLength = openssl_cipher_iv_length($this->method);

        // $this->iv = \Config::get('openssl_encryption_iv');

	}



	/**
	* 暗号化する
	* @param string $data 暗号化するデータ
	* @return string 暗号データと初期化ベクトルが合成された文字列を返す
	*/
	public function encrypt(string $data): string {

        // IV を生成
        $modulesId = new \React\Modules\Id();
        $iv = $modulesId->createRandomId($this->ivLength);

        $encryptedData = openssl_encrypt($data, $this->method, $this->encryptionKey, $this->options, $iv);
        // \Debug::dump($this->method, $this->encryptionKey, $this->options, $iv, $data, $encryptedData);

        // 暗号データと初期化ベクトルを合成して返す
		return $iv . $encryptedData;

	}



    /**
	* 暗号化された文字列を復号する
	* @param string $data 復号するデータ
	* @return string 復号した文字列を返す
	*/
	public function decrypt(string $data): string {

        // 暗号データと初期化ベクトルを分ける
        $iv = substr($data, 0, $this->ivLength);
        $encryptedData = substr($data, $this->ivLength);
        // \Debug::dump($iv);
        // \Debug::dump($encryptedData);

        $decryptedData = openssl_decrypt($encryptedData, $this->method, $this->encryptionKey, $this->options, $iv);
        // \Debug::dump($this->method, $this->encryptionKey, $this->options, $iv, $encryptedData, $decryptedData);

        // $decryptedData = 'aaa';

		return $decryptedData;

	}

}
