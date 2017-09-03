<?php

declare(strict_types=1);

namespace React\Modules;

class Security
{

    /**
     * CSRF対策、トークンを発行し、セッションとクッキーに保存する
     * トークンは以下のようなランダムな英数字の文字列が発行される
     * 92fab32bb41475af1a8896e24829479953c81ce4cdda318e099a39f10db3c6e51ee589c8138c65a1adbdd17cf306cb557b3e26fb28d3ace5fdd1ceee73764291
     */
	public function setCsrfToken() {
        $token = \Security::generate_token();
        \Session::set('csrfToken', $token);
        \Cookie::set('csrfToken', $token);
	}


    /**
     * CSRF対策、セッションに保存したトークンとクッキーに保存したトークンを比較する
     * 同じ場合は true を返し、違う場合は false を返す
     * またトークンを発行しなおす
     * @return boolean        [description]
     */
    public function ckeckCsrfToken(): bool {

        $sessionToken = \Session::get('csrfToken');
        $cookieToken = \Cookie::get('csrfToken');

        $this->setCsrfToken();

        if ($sessionToken === $cookieToken) {
            return true;
        } else {
            return false;
        }

    }

}
