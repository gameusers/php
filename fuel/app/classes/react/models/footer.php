<?php

declare(strict_types=1);

namespace React\Models;

class Footer extends \Model_Crud
{


	/**
	* フッターのサムネイルカードのデータを取得する
    * サムネイル画像とゲーム名が掲載させれている小さなカード
	* 各カードにはそれぞれのゲームページへのリンクが貼られている
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
        // $returnArr['cardTypeAAA'] = $cardType;
        // $returnArr['cardTypeBBB'] = $cardType;

		$gcAccessGameNo = \Cookie::get('gc_access', null);
		$gcAccessGameNoArr = ($gcAccessGameNo) ? explode(',', $gcAccessGameNo): null;

		$ucAccessCommunityNo = \Cookie::get('uc_access', null);
		$ucAccessCommunityNoArr = ($ucAccessCommunityNo) ? explode(',', $ucAccessCommunityNo): null;

        // $cardType = 'gameCommunityAccess';
        // $gcAccessGameNoArr = [500,1,2,3,4,5];
        //
        // $cardType = 'userCommunityAccess';
        // $ucAccessCommunityNoArr = [1,2,3,4,5];


        // --------------------------------------------------
		//   バリデーション
		// --------------------------------------------------

        $pattern = '/^(gameCommunityRenewal|gameCommunityAccess|userCommunityAccess)$/';

        if ( ! preg_match($pattern, $cardType)) {
            throw new Exception();
        }


        // ---------------------------------------------
		//   最近アクセスしたゲームコミュニティ
		// ---------------------------------------------

		if ($cardType === 'gameCommunityAccess' and $gcAccessGameNoArr)
		{

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

			foreach ($gcAccessGameNoArr as $key => $value)
			{
                // if (isset($dbArr[$value])) $tempArr[(string)$value . 'a'] = $dbArr[$value];
				if (isset($dbArr[$value])) array_push($tempArr, $dbArr[$value]);
			}

            // $type = 'gameCommunityAccess';
			$returnArr[$cardType . 'Arr'] = $tempArr;
            // $returnArr[$cardType . 'Arr'] = $dbArr;

		}

		// ---------------------------------------------
		//   最近アクセスしたユーザーコミュニティ
		// ---------------------------------------------

		else if ($cardType === 'userCommunityAccess' and $ucAccessCommunityNoArr)
		{

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

			foreach ($ucAccessCommunityNoArr as $key => $value)
			{
				if (isset($dbArr[$value])) array_push($tempArr, $dbArr[$value]);
			}

            // $type = 'userCommunityAccess';
			$returnArr[$cardType . 'Arr'] = $tempArr;

		}

		// ---------------------------------------------
		//   最近更新されたゲームコミュニティ
		// ---------------------------------------------

		else
		{

            $query = \DB::select(
                ['game_data.game_no', 'gameNo'],
                ['game_data.renewal_date', 'renewalDate'],
                ['game_data.id', 'gameId'],
                ['game_data.name_' . $language, 'name'],
                'game_data.thumbnail'
            )->from('game_data');

    		$query->join('game_community', 'LEFT');
    		$query->on('game_data.game_no', '=', 'game_community.game_no');

            // $query->where('game_data.game_no', 'in', $gcAccessGameNoArr);

            $query->order_by('game_community.sort_date','desc');
            $query->limit($limit);
    		$query->offset(0);

    		$dbArr = $query->execute()->as_array();

            // $type = 'gameCommunityRenewal';
			$returnArr[$cardType . 'Arr'] = $dbArr;

		}


        // --------------------------------------------------
        //   型変換
        // --------------------------------------------------

        foreach ($returnArr[$cardType . 'Arr'] as $key => &$value) {
            if (isset($value['gameNo'])) $value['gameNo'] = (int) $value['gameNo'];
            if (isset($value['thumbnail'])) $value['thumbnail'] = (int) $value['thumbnail'];
            if (isset($value['communityNo'])) $value['communityNo'] = (int) $value['communityNo'];
        }

        unset($value);



		// \Debug::dump($returnArr);
        // exit();

		return $returnArr;

	}


}
