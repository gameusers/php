<?php

declare(strict_types=1);

namespace React\Modules;

class Mail
{

    /**
     * クラスの初期化
     */
    public static function _init()
    {
        //パッケージの読み込み - FuelPHP の Email パッケージを使用
        \Package::load('email');
    }


    /**
    * TOでひとりにメール送信
    * @param string $from 自分のメールアドレス
    * @param string $fromName 自分の名前
    * @param string $to 相手のメールアドレス
    * @param string $toName 相手の名前
    * @param string $subject タイトル
    * @param string $body 本文
    * @param        $file 添付ファイル
    * @return string 成功した場合はtrue、失敗した場合はfalse
    */
    public function to($from, $fromName, $to, $toName, $subject, $body, $file, $configArr): array
    {


        // --------------------------------------------------
        //   戻り値の配列
        // --------------------------------------------------

        $returnArr['error'] = true;
        // \Debug::dump($_FILES);

        // --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

        $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD';

        if (empty($from) || !preg_match($pattern, $from)) {
            throw new \Exception('Validation: from');
        }

        if (empty($fromName) || mb_strlen($fromName) > 255) {
            throw new \Exception('Validation: fromName = ' . $fromName);
        }

        if (empty($to) || !preg_match($pattern, $to)) {
            throw new \Exception('Validation: to');
        }

        if (empty($toName) || mb_strlen($toName) > 255) {
            throw new \Exception('Validation: toName');
        }

        if (empty($subject) || mb_strlen($subject) > 255) {
            throw new \Exception('Validation: subject');
        }

        if (empty($body) || mb_strlen($body) > 3000) {
            throw new \Exception('Validation: body');
        }



        // --------------------------------------------------
        //   アップロードされたファイル
        // --------------------------------------------------

        if (count($_FILES) > 0) {


            // --------------------------------------------------
    		//   アップロード設定
    		// --------------------------------------------------

            if ($configArr) {
                \Upload::process($configArr);
            }


            // --------------------------------------------------
    		//   アップロードされたファイルを保存
    		// --------------------------------------------------

            if (\Upload::is_valid()) {

                // 画像を保存
                \Upload::save();

                $uploadArr = \Upload::get_files()[0];
                $uploadFileAddress = $uploadArr['saved_to'] . $uploadArr['saved_as'];
                // \Debug::dump($uploadArr, $uploadFileAddress);

            } else {

                // \Debug::dump(\Upload::get_errors());

                foreach (\Upload::get_errors() as $key1 => $value1) {
                    foreach ($value1['errors'] as $key => $value) {
                        if ($value['error'] !== 4) {
                            throw new \Exception('Upload: file');
                        }
                    }
                }

            }

        }




        // --------------------------------------------------
		//   メールを送信
		// --------------------------------------------------

        $email = \Email::forge();
        $email->from($from, $fromName);
        $email->to($to, $toName);
        $email->subject($subject);
        $email->body($body);
        if (isset($uploadFileAddress)) $email->attach($uploadFileAddress);
        $email->send();


        // --------------------------------------------------
        //   戻り値の配列を設定
        // --------------------------------------------------

        $returnArr['error'] = false;



        return $returnArr;

    }


    /**
    * BCCを利用して複数人にメール送信
    *
    * @param string $from 自分のメールアドレス
    * @param string $fromName 自分の名前
    * @param array $bcc 相手のメールアドレスを配列で
    * @param string $subject タイトル
    * @param string $body 本文
    * @return string 成功した場合はtrue、失敗した場合はfalse
    */
    // public function bcc($from, $fromName, $bcc_arr, $subject, $body)
    // {
    //
    //     // $email = Email::forge('jis');
    //     // $email->from($from, mb_encode_mimeheader($fromName, 'jis'));
    //     // $bcc_arr = array('private-leaf@k.vodafone.ne.jp', 'info@reaf.net');
    //     // $email->to($bcc_arr);
    //     // $email->subject(mb_encode_mimeheader($subject, 'jis'));
    //     // $email->body(mb_convert_encoding($body, 'jis'));
    //
    //     $email = \Email::forge();
    //     $email->from($from, $fromName);
    //     $email->bcc($bcc_arr);
    //     $email->subject($subject);
    //     $email->body($body);
    //
    //     //送信の試行
    //     try {
    //         $return = true;
    //         $email->send();
    //     } catch (\EmailValidationFailedException $e) {
    //         //送信先が正しいEmailアドレスでない場合
    //         $return = false;
    //     } catch (\EmailSendingFailedException $e) {
    //         //送信に失敗した場合
    //         $return = false;
    //     }
    //
    //     return $return;
    // }

}
