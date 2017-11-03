<?php

declare(strict_types=1);

namespace React\Models;

class Footer extends \Model_Crud
{


    /**
    * フッターのサムネイルカードのデータを取得する
    * サムネイル画像とゲーム名が掲載させれている小さなカード
    * 各カードにはそれぞれのゲームコミュニティへのリンクが貼られている
    *
    * サムネイルカードには3種類のタイプがある
    * ・最近更新されたゲームコミュニティ
    * ・最近アクセスしたゲームコミュニティ
    * ・最近アクセスしたユーザーコミュニティ
    *
    * @param array $arr
    * @return array
    */
    public function selectCard(array $arr): array
    {

        // --------------------------------------------------
        //   検索に使用する値を代入する
        // --------------------------------------------------

        $language = $arr['language'] ?? 'ja';
        $limit = LIMIT_FOOTER_THUMBNAIL_CARDS_ARR[DEVICE_TYPE];

        $cardType = $arr['cardType'] ?? \Cookie::get('footerCardType', 'gameCommunityRenewal');

        $gcAccessGameNo = \Cookie::get('gc_access', null);
        $gcAccessGameNoArr = ($gcAccessGameNo) ? explode(',', $gcAccessGameNo): null;

        $ucAccessCommunityNo = \Cookie::get('uc_access', null);
        $ucAccessCommunityNoArr = ($ucAccessCommunityNo) ? explode(',', $ucAccessCommunityNo): null;

        // $cardType = 'gameCommunityAccess';
        // $gcAccessGameNoArr = [1];
        //
        // $cardType = 'userCommunityAccess';
        // $ucAccessCommunityNoArr = [1,2,3,4,5];


        // --------------------------------------------------
        //   バリデーション
        // --------------------------------------------------

        // $pattern = '/^(gameCommunityRenewal|gameCommunityAccess|userCommunityAccess)$/';
        //
        // if ( ! preg_match($pattern, $cardType)) {
        //     throw new Exception();
        // }

        $pattern = '/^(ja)$/';

        if (! preg_match($pattern, $language)) {
            $language = 'ja';
        }


        // ---------------------------------------------
        //   最近アクセスしたゲームコミュニティ
        // ---------------------------------------------

        if ($cardType === 'gameCommunityAccess' and $gcAccessGameNoArr) {

            $query = \DB::select(
                ['game_data.game_no', 'gameNo'],
                ['game_data.renewal_date', 'renewalDate'],
                ['game_data.id', 'gameId'],
                ['game_data.name_' . $language, 'name'],
                'game_data.thumbnail'
            )->from('game_data');

            $query->join('game_community', 'LEFT');
            $query->on('game_data.game_no', '=', 'game_community.game_no');

            $query->where('game_data.game_no', 'in', $gcAccessGameNoArr);

            $query->limit($limit);
            $query->offset(0);

            $dbArr = $query->execute()->as_array('gameNo');


            // --------------------------------------------------
            //   クッキーの順番通りに並び替え
            // --------------------------------------------------

            $tempArr = [];

            foreach ($gcAccessGameNoArr as $key => $value) {
                if (isset($dbArr[$value])) {
                    array_push($tempArr, $dbArr[$value]);
                }
            }

            if (count($tempArr) > 0) {
                $returnArr['gameCommunityAccessList'] = $tempArr;
            }

        }

        // ---------------------------------------------
        //   最近アクセスしたユーザーコミュニティ
        // ---------------------------------------------

        elseif ($cardType === 'userCommunityAccess' and $ucAccessCommunityNoArr) {

            $query = \DB::select(
                ['community_no', 'communityNo'],
                ['renewal_date', 'renewalDate'],
                ['community_id', 'communityId'],
                'name',
                'thumbnail'
            )->from('community');

            $query->where('community_no', 'in', $ucAccessCommunityNoArr);

            $query->limit($limit);
            $query->offset(0);
            $dbArr = $query->execute()->as_array('communityNo');


            // --------------------------------------------------
            //   クッキーの順番通りに並び替え
            // --------------------------------------------------

            $tempArr = [];

            foreach ($ucAccessCommunityNoArr as $key => $value) {
                if (isset($dbArr[$value])) {
                    array_push($tempArr, $dbArr[$value]);
                }
            }

            if (count($tempArr) > 0) {
                $returnArr['userCommunityAccessList'] = $tempArr;
            }

        }


        // ---------------------------------------------
        //   最近更新されたゲームコミュニティ
        // ---------------------------------------------

        if (empty($dbArr)) {

            \Cookie::set('footerCardType', 'gameCommunityRenewal');
            $cardType = 'gameCommunityRenewal';

            $query = \DB::select(
                ['game_data.game_no', 'gameNo'],
                ['game_data.renewal_date', 'renewalDate'],
                ['game_data.id', 'gameId'],
                ['game_data.name_' . $language, 'name'],
                'game_data.thumbnail'
            )->from('game_data');

            $query->join('game_community', 'LEFT');
            $query->on('game_data.game_no', '=', 'game_community.game_no');

            $query->order_by('game_community.sort_date', 'desc');
            $query->limit($limit);
            $query->offset(0);

            $dbArr = $query->execute()->as_array();

            $returnArr['gameCommunityRenewalList'] = $dbArr;

        }



        // --------------------------------------------------
        //   型変換
        // --------------------------------------------------

        if (isset($returnArr[$cardType . 'List'])) {

            foreach ($returnArr[$cardType . 'List'] as $key => &$value) {
                if (isset($value['gameNo'])) {
                    $value['gameNo'] = (int) $value['gameNo'];
                }
                if (isset($value['thumbnail'])) {
                    $value['thumbnail'] = (int) $value['thumbnail'];
                }
                if (isset($value['communityNo'])) {
                    $value['communityNo'] = (int) $value['communityNo'];
                }
            }

            unset($value);

        }


        // \Debug::dump($returnArr);
        // exit();

        return $returnArr;

    }
}
