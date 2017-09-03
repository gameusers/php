<?php

declare(strict_types=1);

namespace React\Modules\Validations;

class User
{

    /**
     * ログインチェック
     * @return bool [description]
     */
	public function login() {
        if ( ! USER_NO) {
            throw new \Exception('login');
        }
	}

}
