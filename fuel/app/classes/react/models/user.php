<?php

declare(strict_types=1);

namespace React\Models;

class User extends \Model_Crud
{

    /**
     * Player IDを取得
     * ややこしいのですがデータベース上では user_id という名前になっています
     * 後日、名称を変えたため、こうなりました
     * @param  int    $userNo [description]
     * @return string         [description]
     */
    public function selectPlayerId(int $userNo): string
    {
        $query = \DB::select('user_id')->from('users_data');
        $query->where('user_no', '=', $userNo);
        $query->where('on_off', '=', 1);
        $arr = $query->execute()->current();

        return $arr['user_id'];
    }

}
