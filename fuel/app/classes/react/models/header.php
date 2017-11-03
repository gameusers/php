<?php

declare(strict_types=1);

namespace React\Models;

class Header extends \Model_Crud
{


    /**
    * ヘッダー用のデータを取得する
    * ヘッダーで表示する画像は2種類あり、大きな画像（ヒーローイメージ）を表示するタイプと、小さな画像（サムネイル）を表示するタイプに分かれる
    * ヒーローイメージがある場合はそれを表示し、ない場合はサムネイルを代わりに表示する
    *
    * コミュニティではヘッダー用のヒーローイメージをアップロードできるので、その画像がアップロードされている場合はそれを優先的に表示する
    * ヒーローイメージがアップロードされていない場合は、そのコミュニティが関連しているゲームのヒーローイメージ・サムネイルを表示する
    *
    * ゲームページではヒーローイメージが存在する場合は、そちらを優先し、存在しない場合はサムネイルを表示する
    *
    * @param array $arr
    * @return array
    */
    public function selectHeader(array $arr): array
    {

        // --------------------------------------------------
        //   検索に使用する値を代入する
        // --------------------------------------------------

        $language = $arr['language'] ?? 'ja';
        $communityNo = $arr['communityNo'] ?? null;
        $gameNoArr = $arr['gameNoArr'] ?? null;


        // --------------------------------------------------
        //   コミュニティの場合
        // --------------------------------------------------

        if ($communityNo) {


            // --------------------------------------------------
            //   ヒーローイメージの情報を取得
            // --------------------------------------------------

            $query = \DB::select(
                ['image_id', 'heroImageId'],// ヒーローイメージID
                ['renewal_date', 'heroImageRenewalDate'],// ヒーローイメージの更新日時
                ['community_no', 'communityNo']// コミュニティNo
            )->from('image');

            $query->where('on_off', '=', 1);
            $query->where('type', '=', 'hero_community');
            $query->where('community_no', '=', $communityNo);

            $query->limit(1);
            $query->offset(0);

            $heroImageArr = $query->execute()->current();


            // --------------------------------------------------
            //   コミュニティの情報を取得
            // --------------------------------------------------

            $query = \DB::select(
                ['renewal_date', 'communityRenewalDate'],// コミュニティの更新日時
                ['community_id', 'communityId'],// コミュニティID
                ['name', 'communityName']// コミュニティ名
            )->from('community');

            $query->where('community_no', '=', $communityNo);

            $query->limit(1);
            $query->offset(0);

            $communityArr = $query->execute()->current();


            // --------------------------------------------------
            //   配列合成
            // --------------------------------------------------

            if (! $heroImageArr) {
                $heroImageArr = [];
            }

            $headerArr = array_merge($heroImageArr, $communityArr);


        }



        // --------------------------------------------------
        //   コミュニティのヒーローイメージがアップロードされていない場合
        //   またはゲームページの場合
        // --------------------------------------------------

        if (empty($headerArr['heroImageId'])) {


            // --------------------------------------------------
            //   ヒーローイメージの情報を取得
            // --------------------------------------------------

            $query = \DB::select(
                ['image_id', 'heroImageId'],
                ['renewal_date', 'heroImageRenewalDate'],
                ['game_no', 'gameNo']
            )->from('image');

            $query->where('on_off', '=', 1);
            $query->where('type', '=', 'hero_game');
            if ($gameNoArr) {
                $query->where('game_no', 'in', $gameNoArr);
            }

            $query->order_by(\DB::expr('RAND()'));
            $query->limit(1);
            $query->offset(0);

            $heroImageArr = $query->execute()->current();


            // --------------------------------------------------
            //   Game No 設定
            // --------------------------------------------------

            $gameNo = $heroImageArr['gameNo'] ?? $gameNoArr[0];


            // --------------------------------------------------
            //   ゲーム情報取得
            // --------------------------------------------------

            $query = \DB::select(
                ['game_no', 'gameNo'],// ゲームNo
                ['renewal_date', 'gameRenewalDate'],// ゲーム情報の更新日時
                ['id', 'gameId'],// ゲームID
                ['name_' . $language, 'gameName'],// ゲーム名
                ['subtitle', 'gameSubtitle'],// ゲームのサブタイトル
                ['thumbnail', 'gameThumbnail'],// サムネイルの有無 / 1 or null
                ['hardware', 'gameHardwareList'],// このゲームがで発売されたハードウェア / ハードウェアNoがこの形式で保存されている /,1,2,3,4,5,/
                ['genre', 'gameGenreList'],// ゲームのジャンル / ジャンルNoがこの形式で保存されている /,1,2,3,4,5,/
                ['release_date_1', 'gameReleaseDate1'],// ゲームの発売日時 1　　ハードウェアNoの順番と各発売日が連動
                ['release_date_2', 'gameReleaseDate2'],// ゲームの発売日時 2
                ['release_date_3', 'gameReleaseDate3'],// ゲームの発売日時 3
                ['release_date_4', 'gameReleaseDate4'],// ゲームの発売日時 4
                ['release_date_5', 'gameReleaseDate5'],// ゲームの発売日時 5
                ['players_max', 'gamePlayersMax'],// プレイヤー最大数　1～○○人
                ['developer', 'gameDeveloperList']// 開発会社（メーカー） / ディベロッパーNoがこの形式で保存されている /,1,2,3,4,5,/
            )->from('game_data');

            $query->where('game_no', '=', $gameNo);

            $query->limit(1);
            $query->offset(0);

            $gameDataArr = $query->execute()->current();


            // --------------------------------------------------
            //   配列合成
            // --------------------------------------------------

            if (! $heroImageArr) {
                $heroImageArr = [];
            }

            $headerArr = array_merge($heroImageArr, $gameDataArr);


            // --------------------------------------------------
            //   型変換
            // --------------------------------------------------

            // if ($headerArr['gamePlayersMax']) $headerArr['gamePlayersMax'] = (int) $headerArr['gamePlayersMax'];


            // --------------------------------------------------
            //   ハードウェアNo・ジャンルNo・ディベロッパーNo処理
            //   /,1,2,3,4,5,/ この形式をPHPの配列に変換している、この配列は情報取得時に利用する
            // --------------------------------------------------

            $original_func_common = new \Original\Func\Common();
            $gameHardwareNoArr = $original_func_common->return_db_array('db_php', $headerArr['gameHardwareList']);
            $gameGenreNoArr = $original_func_common->return_db_array('db_php', $headerArr['gameGenreList']);
            $gameDeveloperNoArr = $original_func_common->return_db_array('db_php', $headerArr['gameDeveloperList']);


            // --------------------------------------------------
            //   ハードウェア情報を取得
            // --------------------------------------------------

            if (count($gameHardwareNoArr) > 0) {

                $query = \DB::select(
                    ['hardware_no', 'hardwareNo'],
                    ['name_' . $language, 'name'],
                    ['abbreviation_' . $language, 'abbreviation']
                )->from('hardware');

                $query->where('hardware_no', 'in', $gameHardwareNoArr);
                $dbArr = $query->execute()->as_array('hardwareNo');


                // --------------------------------------------------
                //   指定の順番通りに並び替え & 型変換
                // --------------------------------------------------

                $tempArr = [];

                foreach ($gameHardwareNoArr as $key => $value) {
                    if (isset($dbArr[$value])) {
                        $dbArr[$value]['hardwareNo'] = (int) $dbArr[$value]['hardwareNo'];
                        array_push($tempArr, $dbArr[$value]);
                    }
                }

                $headerArr['gameHardwareList'] = $tempArr;

            }


            // --------------------------------------------------
            //   ジャンル情報を取得
            // --------------------------------------------------

            if (count($gameGenreNoArr) > 0) {

                $query = \DB::select(
                    ['genre_no', 'genreNo'],
                    'name'
                )->from('data_genre');
                $query->where('genre_no', 'in', $gameGenreNoArr);
                $dbArr = $query->execute()->as_array('genreNo');


                // --------------------------------------------------
                //   指定の順番通りに並び替え & 型変換
                // --------------------------------------------------

                $tempArr = [];

                foreach ($gameGenreNoArr as $key => $value) {
                    if (isset($dbArr[$value])) {
                        $dbArr[$value]['genreNo'] = (int) $dbArr[$value]['genreNo'];
                        array_push($tempArr, $dbArr[$value]);
                    }
                }

                $headerArr['gameGenreList'] = $tempArr;

            }

            // \Debug::dump($gameGenreNoArr);
            // \Debug::dump($dbArr);
            // \Debug::dump($headerArr);
            // exit();


            // --------------------------------------------------
            //   開発（メーカー）情報を取得
            // --------------------------------------------------

            if (count($gameDeveloperNoArr) > 0) {

                $query = \DB::select(
                    ['developer_no', 'developerNo'],
                    'name',
                    'studio'
                )->from('data_developer');
                $query->where('developer_no', 'in', $gameDeveloperNoArr);
                $dbArr = $query->execute()->as_array('developerNo');


                // --------------------------------------------------
                //   指定の順番通りに並び替え & 型変換
                // --------------------------------------------------

                $tempArr = [];

                foreach ($gameDeveloperNoArr as $key => $value) {
                    if (isset($dbArr[$value])) {
                        $dbArr[$value]['developerNo'] = (int) $dbArr[$value]['developerNo'];
                        array_push($tempArr, $dbArr[$value]);
                    }
                }

                $headerArr['gameDeveloperList'] = $tempArr;

            }


            // --------------------------------------------------
            //   リンク情報（公式サイトや公式Twitterなど）を取得
            // --------------------------------------------------

            $query = \DB::select('type', 'name', 'url')->from('data_link');
            $query->where('game_no', '=', $gameNo);
            $dbLinkArr = $query->execute()->as_array();
            $headerArr['gameLinkList'] = $dbLinkArr;

            if (count($headerArr['gameLinkList']) === 0) {
                $headerArr['gameLinkList'] = null;
            }

        }


        // --------------------------------------------------
        //   型変換
        // --------------------------------------------------

        if (isset($headerArr['communityNo'])) {
            $headerArr['communityNo'] = (int) $headerArr['communityNo'];
        }
        if (isset($headerArr['gameNo'])) {
            $headerArr['gameNo'] = (int) $headerArr['gameNo'];
        }
        if (isset($headerArr['gameThumbnail'])) {
            $headerArr['gameThumbnail'] = (int) $headerArr['gameThumbnail'];
        }
        if (isset($headerArr['gamePlayersMax'])) {
            $headerArr['gamePlayersMax'] = (int) $headerArr['gamePlayersMax'];
        }

        // \Debug::dump($headerArr);


        return $headerArr;
    }
}
