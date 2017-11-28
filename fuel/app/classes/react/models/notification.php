<?php

declare(strict_types=1);

namespace React\Models;

class Notification extends \Model_Crud
{



    /**
     * 通知の未読数を取得する / 要ログイン
     * @param  array $arr [description]
     * @return array      [description]
     */
	public function selectUnreadCount(array $arr): array
	{

        // --------------------------------------------------
        //   ログインチェック
        // --------------------------------------------------

        $modulesValidationsUser = new \React\Modules\Validations\User();
        $modulesValidationsUser->login();


        // --------------------------------------------------
        //   返り値の配列
        // --------------------------------------------------

        $returnArr = [];


		// --------------------------------------------------
		//   検索用データ取得
		//   参加しているコミュニティ情報と既読の通知IDを取得
		// --------------------------------------------------

        $query = \DB::select(
            ['participation_community', 'participationCommunity'],
            ['participation_community_secret', 'participationCommunitySecret'],
            ['notifications_already_read_id', 'notificationsAlreadyReadId']
        )->from('users_data');

		$query->where('user_no', '=', USER_NO);
		$query->where('on_off', '=', 1);
		$dbUsersDataArr = $query->execute()->current();


        // --------------------------------------------------
        //   通知の既読IDを処理する
        //   既読IDの情報はシリアライズされて以下の形式で保存されています
        //   ['id' => "8craskew3nfg1gh", 'regi_date' => "2017-08-26 17:48:39"]
        //   これを以下のようなIDだけが入っている配列にします
        //   ['0zj0pnd2vlw2eex', '2rwgyd1sbzyu5ub']
        // --------------------------------------------------

        $alreadyReadIdArr = [];

        if ($dbUsersDataArr['notificationsAlreadyReadId']) {

            // アンシリアライズ
            $oldAlreadyReadIdArr = unserialize($dbUsersDataArr['notificationsAlreadyReadId']);
            // \Debug::dump($oldAlreadyReadIdArr);

            // 日付を削除した配列を作る
            foreach ($oldAlreadyReadIdArr as $key => $value) {
                array_push($alreadyReadIdArr, $value['id']);
            }

        }


        // --------------------------------------------------
        //   検索用データ取得
        //   参加しているユーザーコミュニティの No を普通の配列に変換する
        //   参加しているユーザーコミュニティは、公開と非公開の2種類があります
        //   （非公開は他のユーザーに秘密で参加しているユーザーコミュニティです）
        //   例）(string) /1,2,3,4,5/ → (array) [1,2,3,4,5]
        // --------------------------------------------------

        $modulesFormat = new \React\Modules\Format();

        $participationCommunityNoArr = [];
        $participationCommunitySecretNoArr = [];

        // 公開
        if ($dbUsersDataArr['participationCommunity']) {
            $participationCommunityNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersDataArr['participationCommunity']);
        }

        // 非公開
        if ($dbUsersDataArr['participationCommunitySecret']) {
            $participationCommunitySecretNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersDataArr['participationCommunitySecret']);
        }

        // 公開と非公開を合成
        $mergedParticipationCommunityNoArr = array_merge($participationCommunityNoArr, $participationCommunitySecretNoArr);



        // --------------------------------------------------
        //   検索用データ取得
        //   新規募集があったときに通知を受ける設定にしているゲームNoを取得
        //   例）(string) /1,2,3,4,5/ → (array) [1,2,3,4,5]
        // --------------------------------------------------

        $query = \DB::select(
            ['notification_recruitment', 'notificationRecruitment']
        )->from('users_game_community');
        $query->where('user_no', '=', USER_NO);
        $dbUsersGameCommunityArr = $query->execute()->current();


        $notificationRecruitmentNoArr = [];

        if ($dbUsersGameCommunityArr['notificationRecruitment']) {
            $notificationRecruitmentNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersGameCommunityArr['notificationRecruitment']);
        }



        // --------------------------------------------------
        //   検索用データ取得
        //   ○○前の日時を取得 / 通知の保存期間
        // --------------------------------------------------

        $modulesDatetime = new \React\Modules\Datetime();
        $preservationTerm = $modulesDatetime->databaseFormat(LIMIT_NOTIFICATION_PRESERVATION_TERM);



        // --------------------------------------------------
        //   未読数取得
        // --------------------------------------------------

        $query = \DB::select(\DB::expr('COUNT(*) as unreadCount'))->from('notifications');
        $query->join('users_data', 'LEFT');
        $query->on('notifications.user_no', '=', 'users_data.user_no');
        $query->join('community', 'LEFT');
        $query->on('notifications.community_no', '=', 'community.community_no');
        $query->join('game_data', 'LEFT');
        $query->on('notifications.game_no', '=', 'game_data.game_no');


        // 一定期間以上過ぎた古い通知は読み込まない
        $query->where('notifications.regi_date', '>', $preservationTerm);


        // User No　自分のNo以外（他人のアクションを通知）　または　null（ログインしていないユーザーのアクションを通知）
        $query->and_where_open();
        $query->and_where('notifications.user_no', '!=', USER_NO);
        $query->or_where('notifications.user_no', '=', null);
        $query->and_where_close();


        // 参加してるコミュニティの通知
        $query->and_where_open();

        $query->and_where('notifications.target_user_no', '=', USER_NO);
        if (count($mergedParticipationCommunityNoArr) > 0) {
            $query->or_where('notifications.community_no', 'in', $mergedParticipationCommunityNoArr);
        }

        // 通知を受けとる設定にしてるゲームコミュニティの通知
        if (count($notificationRecruitmentNoArr) > 0) {
            $query->or_where_open();
            $query->and_where('notifications.type1', '=', 'gc');
            $query->and_where('notifications.type2', '=', 'recruitment');
            $query->and_where('notifications.game_no', 'in', $notificationRecruitmentNoArr);
            $query->or_where_close();
        }

        $query->and_where_close();


        // 既読の通知は除く
        if (count($alreadyReadIdArr) > 0) {
            $query->and_where('notifications.id', 'not in', $alreadyReadIdArr);
        }


        $returnArr = $query->execute()->current();



        // \Debug::dump($participationCommunityNoArr);
        // \Debug::dump($participationCommunitySecretNoArr);
        // \Debug::dump($participationCommunityNoArr);

        // \Debug::dump($notificationRecruitmentNoArr);

        // \Debug::dump($returnArr);
        // exit();


        // --------------------------------------------------
        //   型変換
        // --------------------------------------------------

        if (isset($returnArr['unreadCount'])) $returnArr['unreadCount'] = (int) $returnArr['unreadCount'];



		return $returnArr;

	}



    /**
     * 通知を取得する / 要ログイン
     * @param  array $arr language / page / readType
     * @return array      [description]
     */
    public function selectNotification(array $arr): array
    {

        // --------------------------------------------------
        //   ログインチェック
        // --------------------------------------------------

        $modulesValidationsUser = new \React\Modules\Validations\User();
        $modulesValidationsUser->login();


        // --------------------------------------------------
        //   返り値の配列
        // --------------------------------------------------

        $returnArr = [];


        // --------------------------------------------------
		//   検索に使用する値を代入する
		// --------------------------------------------------

        $language = $arr['language'] ?? 'ja';
        $limit = LIMIT_NOTIFICATION_ARR[DEVICE_TYPE];
        $page = $arr['page'] ?? 1;
        $offset = $limit * ($page - 1);
        $readType = $arr['readType'] ?? 'unread';

        // echo '$readType';
        // \Debug::dump($readType);


        // --------------------------------------------------
        //   検索用データ取得
        //   参加しているコミュニティ情報と既読の通知IDを取得
        // --------------------------------------------------

        $query = \DB::select(
            ['participation_community', 'participationCommunity'],
            ['participation_community_secret', 'participationCommunitySecret'],
            ['notifications_already_read_id', 'notificationsAlreadyReadId']
        )->from('users_data');

        $query->where('user_no', '=', USER_NO);
        $query->where('on_off', '=', 1);
        $dbUsersDataArr = $query->execute()->current();


        // --------------------------------------------------
        //   通知の既読IDを処理する
        //   既読IDの情報はシリアライズされて以下の形式で保存されています
        //   ['id' => "8craskew3nfg1gh", 'regi_date' => "2017-08-26 17:48:39"]
        //   これを以下のようなIDだけが入っている配列にします
        //   ['0zj0pnd2vlw2eex', '2rwgyd1sbzyu5ub']
        // --------------------------------------------------

        $alreadyReadIdArr = [];

        if ($dbUsersDataArr['notificationsAlreadyReadId']) {

            // アンシリアライズ
            $oldAlreadyReadIdArr = unserialize($dbUsersDataArr['notificationsAlreadyReadId']);
            // \Debug::dump($oldAlreadyReadIdArr);

            // 日付を削除した配列を作る
            foreach ($oldAlreadyReadIdArr as $key => $value) {
                array_push($alreadyReadIdArr, $value['id']);
            }

        }


        // --------------------------------------------------
        //   既読で既読IDがない場合は処理停止
        // --------------------------------------------------

        if ($readType === 'alreadyRead' && count($alreadyReadIdArr) === 0) {
            $returnArr['dataArr'] = [];
            $returnArr['total'] = 0;
            return $returnArr;
        }


        // --------------------------------------------------
        //   検索用データ取得
        //   参加しているユーザーコミュニティの No を普通の配列に変換する
        //   参加しているユーザーコミュニティは、公開と非公開の2種類があります
        //   （非公開は他のユーザーに秘密で参加しているユーザーコミュニティです）
        //   例）(string) /1,2,3,4,5/ → (array) [1,2,3,4,5]
        // --------------------------------------------------

        $modulesFormat = new \React\Modules\Format();

        $participationCommunityNoArr = [];
        $participationCommunitySecretNoArr = [];

        // 公開
        if ($dbUsersDataArr['participationCommunity']) {
            $participationCommunityNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersDataArr['participationCommunity']);
        }

        // 非公開
        if ($dbUsersDataArr['participationCommunitySecret']) {
            $participationCommunitySecretNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersDataArr['participationCommunitySecret']);
        }

        // 公開と非公開を合成
        $mergedParticipationCommunityNoArr = array_merge($participationCommunityNoArr, $participationCommunitySecretNoArr);


        // --------------------------------------------------
        //   検索用データ取得
        //   新規募集があったときに通知を受ける設定にしているゲームNoを取得
        //   例）(string) /1,2,3,4,5/ → (array) [1,2,3,4,5]
        // --------------------------------------------------

        $query = \DB::select(
            ['notification_recruitment', 'notificationRecruitment']
        )->from('users_game_community');
        $query->where('user_no', '=', USER_NO);
        $dbUsersGameCommunityArr = $query->execute()->current();


        $notificationRecruitmentNoArr = [];

        if ($dbUsersGameCommunityArr['notificationRecruitment']) {
            $notificationRecruitmentNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersGameCommunityArr['notificationRecruitment']);
        }


        // --------------------------------------------------
        //   検索用データ取得
        //   ○○前の日時を取得 / 通知の保存期間
        // --------------------------------------------------

        $modulesDatetime = new \React\Modules\Datetime();
        $preservationTerm = $modulesDatetime->databaseFormat(LIMIT_NOTIFICATION_PRESERVATION_TERM);


        // --------------------------------------------------
        //   データ取得
        // --------------------------------------------------

        $query = \DB::select(
            ['notifications.id', 'notificationId'],
            ['notifications.regi_date', 'regiDate'],
            ['notifications.community_no', 'communityNo'],
            ['notifications.game_no', 'gameNo'],
            'notifications.type1',
            'notifications.type2',
            'notifications.argument',
						'notifications.comment',
            ['users_data.handle_name', 'userHandleName'],
            ['users_data.thumbnail', 'userThumbnail'],
            ['profile.handle_name', 'profileHandleName'],
            ['profile.thumbnail', 'profileThumbnail'],
            ['community.name', 'communityName'],
            ['community.community_id', 'communityId'],
            ['community.thumbnail', 'communityThumbnail'],
            ['community.member', 'communityMember'],
            ['game_data.name_' . $language, 'gameName'],
            ['game_data.id', 'gameId'],
            ['game_data.thumbnail', 'gameThumbnail']
        )->from('notifications');

        $query->join('users_data', 'LEFT');
        $query->on('notifications.user_no', '=', 'users_data.user_no');
        $query->join('profile', 'LEFT');
        $query->on('notifications.profile_no', '=', 'profile.profile_no');
        $query->join('community', 'LEFT');
        $query->on('notifications.community_no', '=', 'community.community_no');
        $query->join('game_data', 'LEFT');
        $query->on('notifications.game_no', '=', 'game_data.game_no');


        // 一定期間以上過ぎた古い通知は読み込まない
        $query->where('notifications.regi_date', '>', $preservationTerm);


        // User No　自分のNo以外（他人のアクションを通知）　または　null（ログインしていないユーザーのアクションを通知）
        $query->and_where_open();
        $query->and_where('notifications.user_no', '!=', USER_NO);
        $query->or_where('notifications.user_no', '=', null);
        $query->and_where_close();


        // 参加してるコミュニティの通知
        $query->and_where_open();

        $query->and_where('notifications.target_user_no', '=', USER_NO);
        if (count($mergedParticipationCommunityNoArr) > 0) {
            $query->or_where('notifications.community_no', 'in', $mergedParticipationCommunityNoArr);
        }

        // 通知を受けとる設定にしてるゲームコミュニティの通知
        if (count($notificationRecruitmentNoArr) > 0) {
            $query->or_where_open();
            $query->and_where('notifications.type1', '=', 'gc');
            $query->and_where('notifications.type2', '=', 'recruitment');
            $query->and_where('notifications.game_no', 'in', $notificationRecruitmentNoArr);
            $query->or_where_close();
        }

        $query->and_where_close();


        // 既読・未読の切り替え
        if (count($alreadyReadIdArr) > 0) {
            if ($readType === 'unread') {// 未読の通知を読み込む
                // echo '未読の通知';
                $query->and_where('notifications.id', 'not in', $alreadyReadIdArr);
            } else {// 既読の通知を読み込む
                // echo '既読の通知';
                $query->and_where('notifications.id', 'in', $alreadyReadIdArr);
            }
        }

        $query->order_by('regiDate','desc');
        $query->limit($limit);
        $query->offset($offset);

        $dataArr = $query->execute()->as_array();
        $returnArr['total'] = \DB::count_last_query();





        // --------------------------------------------------
        //   未読で通知がない場合は処理停止
        // --------------------------------------------------

        if ($readType === 'unread' && count($dataArr) === 0) {
            $returnArr['dataArr'] = [];
            $returnArr['total'] = 0;
            return $returnArr;
        }



        // echo '$alreadyReadIdArr';
        // \Debug::dump($alreadyReadIdArr);
        //
        // echo '$dataArr';
        // \Debug::dump($dataArr);


        // $time_start = microtime(true);


        // --------------------------------------------------
        //   画像と動画のデータを取得
        //   設計が悪いため、繰り返しデータベースにアクセスすることになっている
        //   ひどすぎる作り…
        // --------------------------------------------------

        $notificationArr = [];
        $reservationIdArr = [];

        foreach ($dataArr as $key => $value) {

            // unset($dbArr);
            $tempArr = [];

            $tempArr['notificationId'] = $value['notificationId'];
            $tempArr['datetime'] = $value['regiDate'];


            // 未読のIDを配列に入れる
            if ($readType === 'unread') {
                array_push($reservationIdArr, $value['notificationId']);
            }



            if ($value['type1'] === 'uc' && $value['type2'] === 'announcement') {


                // --------------------------------------------------
                //   データ入力
                // --------------------------------------------------

                $tempArr['contentsType'] = ['userCommunity', 'announcement'];
                $tempArr['pageName'] = $value['communityName'];
                $tempArr['communityNo'] = (int) $value['communityNo'];
                $tempArr['communityId'] = $value['communityId'];
                $tempArr['communityThumbnail'] = $value['communityThumbnail'] ? true : false;
                $tempArr['title'] = '告知';
                $tempArr['comment'] = $value['comment'];


            } else if ($value['type1'] === 'uc' && $value['type2'] === 'mail_all') {


                // --------------------------------------------------
                //   データ入力
                // --------------------------------------------------

                $tempArr['contentsType'] = ['userCommunity', 'mail_all'];
                $tempArr['pageName'] = $value['communityName'];
                $tempArr['communityNo'] = (int) $value['communityNo'];
                $tempArr['communityId'] = $value['communityId'];
                $tempArr['communityThumbnail'] = $value['communityThumbnail'] ? true : false;
                $tempArr['title'] = '告知';
                $tempArr['comment'] = $value['comment'];


            } else if ($value['argument']) {

                $argumentArr = unserialize($value['argument']);

                $bbsId = $argumentArr['bbs_id'] ?? null;
                $bbsThreadNo = $argumentArr['bbs_thread_no'] ?? null;
                $bbsCommentNo = $argumentArr['bbs_comment_no'] ?? null;
                $bbsReplyNo = $argumentArr['bbs_reply_no'] ?? null;

                $recruitmentId = $argumentArr['recruitment_id'] ?? null;



                if (strpos($value['type2'], 'bbs_') !== false) {

                    // if ($value['type1'] === 'gc' && $value['type2'] === 'bbs_thread') {
                    //
                    //     // {
                    //     // contentsType: ['gameCommunity', 'bbs', 'comment'],
                    //     // datetime: '2017-08-29 15:41:40',
                    //     // pageName: 'アサシンクリードユニティ',
                    //     // gameThumbnail: 1,
                    //     // gameNo: 1,
                    //     // gameId: 'assassins-creed-unity',
                    //     // title: 'Assassin\'s Creed Unityについて語ろう！',
                    //     // comment: 'Game Usersとは？Game Users（ゲームユーザーズ）はゲームユーザーのためのSNS・コミュニティサイトです。ゲームに興味のある人たちが集まって、交流がしやすくなるような様々な機能を用意しています',
                    //     // imageArr: null,
                    //     // movieArr: null,
                    //     // bbsId: 'ffoa79pspg11zxvn',
                    //     // commentReplyTotal: 15
                    //     // },
                    //
                    //
                    //     // --------------------------------------------------
                    //     //   データ取得
                    //     // --------------------------------------------------
                    //
                    //     $query = \DB::select(
                    //         ['bbs_thread_no', 'bbsThreadNo'],
                    //         ['bbs_id', 'bbsId'],
                    //         'title',
                    //         'comment',
                    //         'image',
                    //         'movie',
                    //         ['comment_total', 'commentTotal'],
                    //         ['reply_total', 'replyTotal']
                    //     )->from('bbs_thread_gc');
                    //
                    //     $query->where('bbs_thread_no', '=', $bbsThreadNo);
                    //     $query->where('on_off', '=', 1);
                    //
                    //     $dbArr = $query->execute()->current();
                    //
                    //
                    //     // --------------------------------------------------
                    //     //   データ入力
                    //     // --------------------------------------------------
                    //
                    //     $tempArr['contentsType'] = ['gameCommunity', 'bbs', 'thread'];
                    //     $tempArr['pageName'] = $value['gameName'];
                    //     $tempArr['gameNo'] = (int) $value['gameNo'];
                    //     $tempArr['gameId'] = $value['gameId'];
                    //     $tempArr['gameThumbnail'] = $value['gameThumbnail'] ? true : false;
                    //     $tempArr['title'] = $dbArr['title'];
                    //     $tempArr['comment'] = $dbArr['comment'];
                    //     $tempArr['bbsId'] = $dbArr['bbsId'];
                    //     $tempArr['bbsThreadNo'] = (int) $dbArr['bbsThreadNo'];
                    //     $tempArr['commentReplyTotal'] = $dbArr['commentTotal'] + $dbArr['replyTotal'];
                    //
                    //
                    // } else if ($value['type1'] === 'gc' && $value['type2'] === 'bbs_comment') {
                    //     $tableName = 'bbs_comment_gc';
                    // } else if ($value['type1'] === 'gc' && $value['type2'] === 'bbs_reply') {
                    //     $tableName = 'bbs_reply_gc';
                    // } else


                    if ($value['type1'] === 'uc' && $value['type2'] === 'bbs_thread') {


                        // --------------------------------------------------
                        //   データ取得
                        // --------------------------------------------------

                        $query = \DB::select(
                            ['bbs_thread_no', 'bbsThreadNo'],
                            ['bbs_id', 'bbsId'],
                            'title',
                            'comment',
                            'image',
                            'movie',
                            ['comment_total', 'commentTotal'],
                            ['reply_total', 'replyTotal']
                        )->from('bbs_thread');

                        $query->where('bbs_thread_no', '=', $bbsThreadNo);
                        $query->where('on_off', '=', 1);

                        $dbArr = $query->execute()->current();


                        // --------------------------------------------------
                        //   データ入力
                        // --------------------------------------------------

                        $tempArr['contentsType'] = ['userCommunity', 'bbs', 'thread'];
                        $tempArr['pageName'] = $value['communityName'];
                        $tempArr['communityNo'] = (int) $value['communityNo'];
                        $tempArr['communityId'] = $value['communityId'];
                        $tempArr['communityThumbnail'] = $value['communityThumbnail'] ? true : false;
                        $tempArr['title'] = $dbArr['title'];
                        $tempArr['comment'] = $dbArr['comment'];
                        $tempArr['bbsId'] = $dbArr['bbsId'];
                        $tempArr['bbsThreadNo'] = (int) $dbArr['bbsThreadNo'];
                        $tempArr['commentReplyTotal'] = $dbArr['commentTotal'] + $dbArr['replyTotal'];


                    } else if ($value['type1'] === 'uc' && $value['type2'] === 'bbs_comment') {


                        // --------------------------------------------------
                        //   データ取得
                        // --------------------------------------------------

                        $query = \DB::select(
                            ['bbs_comment.bbs_comment_no', 'bbsCommentNo'],
                            ['bbs_comment.bbs_thread_no', 'bbsThreadNo'],
                            ['bbs_comment.bbs_id', 'bbsId'],
                            'bbs_comment.comment',
                            'bbs_comment.image',
                            'bbs_comment.movie',
                            'bbs_thread.title',
                            ['bbs_thread.comment_total', 'commentTotal'],
                            ['bbs_thread.reply_total', 'replyTotal']
                        )->from('bbs_comment');

                        $query->join('bbs_thread', 'LEFT');
                        $query->on('bbs_comment.bbs_thread_no', '=', 'bbs_thread.bbs_thread_no');

                        $query->where('bbs_comment.bbs_comment_no', '=', $bbsCommentNo);
                        $query->where('bbs_comment.on_off', '=', 1);

                        $dbArr = $query->execute()->current();


                        // --------------------------------------------------
                        //   データ入力
                        // --------------------------------------------------

                        $tempArr['contentsType'] = ['userCommunity', 'bbs', 'comment'];
                        $tempArr['pageName'] = $value['communityName'];
                        $tempArr['communityNo'] = (int) $value['communityNo'];
                        $tempArr['communityId'] = $value['communityId'];
                        $tempArr['communityThumbnail'] = $value['communityThumbnail'] ? true : false;
                        $tempArr['title'] = $dbArr['title'];
                        $tempArr['comment'] = $dbArr['comment'];
                        $tempArr['bbsId'] = $dbArr['bbsId'];
                        $tempArr['bbsThreadNo'] = (int) $dbArr['bbsThreadNo'];
                        $tempArr['bbsCommentNo'] = (int) $dbArr['bbsCommentNo'];
                        $tempArr['commentReplyTotal'] = $dbArr['commentTotal'] + $dbArr['replyTotal'];


                    } else if ($value['type1'] === 'uc' && $value['type2'] === 'bbs_reply') {


                        // --------------------------------------------------
                        //   データ取得
                        // --------------------------------------------------

                        $query = \DB::select(
                            ['bbs_reply.bbs_thread_no', 'bbsThreadNo'],
                            ['bbs_reply.bbs_comment_no', 'bbsCommentNo'],
                            ['bbs_reply.bbs_reply_no', 'bbsReplyNo'],
                            ['bbs_reply.bbs_id', 'bbsId'],
                            'bbs_reply.comment',
                            'bbs_reply.image',
                            'bbs_reply.movie',
                            'bbs_thread.title',
                            ['bbs_thread.comment_total', 'commentTotal'],
                            ['bbs_thread.reply_total', 'replyTotal']
                        )->from('bbs_reply');

                        $query->join('bbs_thread', 'LEFT');
                        $query->on('bbs_reply.bbs_thread_no', '=', 'bbs_thread.bbs_thread_no');

                        $query->where('bbs_reply.bbs_reply_no', '=', $bbsReplyNo);
                        $query->where('bbs_reply.on_off', '=', 1);

                        $dbArr = $query->execute()->current();



                        // --------------------------------------------------
                        //   データ入力
                        // --------------------------------------------------

                        $tempArr['contentsType'] = ['userCommunity', 'bbs', 'reply'];
                        $tempArr['pageName'] = $value['communityName'];
                        $tempArr['communityNo'] = (int) $value['communityNo'];
                        $tempArr['communityId'] = $value['communityId'];
                        $tempArr['communityThumbnail'] = $value['communityThumbnail'] ? true : false;
                        $tempArr['title'] = $dbArr['title'];
                        $tempArr['comment'] = $dbArr['comment'];
                        $tempArr['bbsId'] = $dbArr['bbsId'];
                        $tempArr['bbsThreadNo'] = (int) $dbArr['bbsThreadNo'];
                        $tempArr['bbsCommentNo'] = (int) $dbArr['bbsCommentNo'];
                        $tempArr['bbsReplyNo'] = (int) $dbArr['bbsReplyNo'];
                        $tempArr['commentReplyTotal'] = $dbArr['commentTotal'] + $dbArr['replyTotal'];

                    }


                } else if ($value['type2'] === 'recruitment') {


                    // --------------------------------------------------
                    //   データ取得
                    // --------------------------------------------------

                    $query = \DB::select(
                        ['recruitment.recruitment_id', 'recruitmentId'],
                        'recruitment.image',
                        'recruitment.movie',
                        ['recruitment.etc_title', 'title'],
                        ['recruitment.comment', 'comment'],
                        ['game_community.recruitment_total_' . $language, 'commentReplyTotal']
                    )->from('recruitment');

                    $query->join('game_community', 'LEFT');
                    $query->on('recruitment.game_no', '=', 'game_community.game_no');

                    $query->where('recruitment.recruitment_id', '=', $recruitmentId);
                    $query->where('recruitment.on_off', '=', 1);

                    $dbArr = $query->execute()->current();


                    // --------------------------------------------------
                    //   データ入力
                    // --------------------------------------------------

                    $tempArr['contentsType'] = ['gameCommunity', 'recruitment', 'recruitment'];
                    $tempArr['pageName'] = $value['gameName'];
                    $tempArr['gameNo'] = (int) $value['gameNo'];
                    $tempArr['gameId'] = $value['gameId'];
                    $tempArr['gameThumbnail'] = $value['gameThumbnail'] ? true : false;
                    $tempArr['title'] = $dbArr['title'];
                    $tempArr['comment'] = $dbArr['comment'];
                    $tempArr['recruitmentId'] = $dbArr['recruitmentId'];
                    $tempArr['commentReplyTotal'] = (int) $dbArr['commentReplyTotal'];


				} else if ($value['type2'] === 'recruitment_reply') {

					// テーブル notifications の argument に recruitment_reply_id がないため
					// 返信を読み込み直すことができない
					// notifications の comment をそのまま表示しているので投稿後の編集は反映されない
					// また画像や動画も表示されない


                    // --------------------------------------------------
                    //   データ取得
                    // --------------------------------------------------

                    $query = \DB::select(
                        ['recruitment.recruitment_id', 'recruitmentId'],
                        // 'recruitment.image',
                        // 'recruitment.movie',
                        ['recruitment.etc_title', 'title'],
                        ['recruitment.comment', 'comment'],
                        ['game_community.recruitment_total_' . $language, 'commentReplyTotal']
                    )->from('recruitment');

                    $query->join('game_community', 'LEFT');
                    $query->on('recruitment.game_no', '=', 'game_community.game_no');

                    $query->where('recruitment.recruitment_id', '=', $recruitmentId);
                    $query->where('recruitment.on_off', '=', 1);

                    $dbArr = $query->execute()->current();


                    // --------------------------------------------------
                    //   データ入力
                    // --------------------------------------------------

                    $tempArr['contentsType'] = ['gameCommunity', 'recruitment', 'recruitment'];
                    $tempArr['pageName'] = $value['gameName'];
                    $tempArr['gameNo'] = (int) $value['gameNo'];
                    $tempArr['gameId'] = $value['gameId'];
                    $tempArr['gameThumbnail'] = $value['gameThumbnail'] ? true : false;
                    $tempArr['title'] = $dbArr['title'];
                    $tempArr['comment'] = $value['comment'];
                    $tempArr['recruitmentId'] = $dbArr['recruitmentId'];
                    $tempArr['commentReplyTotal'] = (int) $dbArr['commentReplyTotal'];


				} else {
                    continue;
                }



                if (isset($dbArr['image'])) {

                    $imageArr = unserialize($dbArr['image']);
                    // \Debug::dump($imageArr);

                    $tempArr['imageArr'][0] = [
                        'width' => $imageArr['image_1']['width'],
                        'height' => $imageArr['image_1']['height']
                    ];

                } else if (isset($dbArr['movie'])) {

                    $movieArr = unserialize($dbArr['movie']);
                    // \Debug::dump($movieArr);

                    $tempArr['movieArr'][0] = [
                        'YouTube' => $movieArr[0]['youtube']
                    ];

                }



                // echo '$argumentArr';
                // \Debug::dump($argumentArr);

                // if (isset($dbArr)) {
                //     echo '$dbArr';
                //     \Debug::dump($dbArr);
                // }

            }



            // --------------------------------------------------
            //   配列に追加
            // --------------------------------------------------

            if (count($tempArr) > 0) {


                // --------------------------------------------------
                //   削除されていた場合の処理
                // --------------------------------------------------

                if ( ! $tempArr['comment']) {
                    $tempArr['deleted'] = true;
                    $tempArr['comment'] = 'Deleted Comment';
                }


                array_push($notificationArr, $tempArr);
            }


        }



        // --------------------------------------------------
        //   一度、表示した未読の通知を既読の通知に変更するために
        //   通知のIDを保存しておく
        //   再度、ページにアクセスしたタイミング、またはモーダルを閉じたタイミングで
        //   予約IDの配列から既読IDの配列に追加し直して保存する
        // --------------------------------------------------

        if ($readType === 'unread') {


            // --------------------------------------------------
            //   既読の予約通知IDを取得
            // --------------------------------------------------

            $query = \DB::select(
                ['notifications_reservation_id', 'notificationsReservationId']
            )->from('users_data');

            $query->where('user_no', '=', USER_NO);
            $query->where('on_off', '=', 1);
            $dbUsersDataArr = $query->execute()->current();

            $dbReservationIdArr = [];

            if ($dbUsersDataArr['notificationsReservationId']) {
                $dbReservationIdArr = unserialize($dbUsersDataArr['notificationsReservationId']);
            }


            // echo '$reservationIdArr';
            // \Debug::dump($reservationIdArr);
            //
            // echo '$dbReservationIdArr';
            // \Debug::dump($dbReservationIdArr);



            // --------------------------------------------------
            //   データベースの予約IDと新たな予約IDの配列を合成
            //   重複も削除
            // --------------------------------------------------

            $mergedArr = array_merge($reservationIdArr, $dbReservationIdArr);
            $mergedArr = array_unique($mergedArr);
            $mergedArr = array_values($mergedArr);

            // echo '$mergedArr';
            // \Debug::dump($mergedArr);



            // --------------------------------------------------
            //   新たな予約IDと既存の予約IDが違う場合に、データベースに保存する
            // --------------------------------------------------

            if ($mergedArr !== $dbReservationIdArr) {


                // --------------------------------------------------
                //   保存用配列作成
                // --------------------------------------------------

                $saveArr['notifications_reservation_id'] = serialize($mergedArr);


                // --------------------------------------------------
                //   予約IDを保存する
                // --------------------------------------------------

                $query = \DB::update('users_data');
                $query->set($saveArr);
                $query->where('user_no', '=', USER_NO);
                $query->execute();

                // echo '$saveArr';
                // \Debug::dump($saveArr);


            }


        }


        // $time = microtime(true) - $time_start;
        // echo "{$time} 秒<br><br>";


		$returnArr['dataArr'] = $notificationArr;

        // echo '$returnArr';
        // \Debug::dump($returnArr);
        //
        // exit();


        return $returnArr;

    }



    /**
     * 予約IDを既読IDにする / 要ログイン
     * @param  array $arr [description]
     * @return array      [description]
     */
    public function updateReservationIdToAlreadyReadId(array $arr): array
    {

        // --------------------------------------------------
        //   ログインチェック
        // --------------------------------------------------

        $modulesValidationsUser = new \React\Modules\Validations\User();
        $modulesValidationsUser->login();


        // --------------------------------------------------
        //   返り値の配列
        // --------------------------------------------------

        $returnArr = [];


        // --------------------------------------------------
        //   予約IDを取得する
        // --------------------------------------------------

        $query = \DB::select(
            ['notifications_reservation_id', 'notificationsReservationId']
        )->from('users_data');

        $query->where('user_no', '=', USER_NO);
        $query->where('on_off', '=', 1);
        $dbUsersDataArr = $query->execute()->current();
        // \Debug::dump($dbUsersDataArr);


        // --------------------------------------------------
        //   予約IDを処理する / アンシリアライズ
        //   ['8rodcn2jtuvmcar', '2rwgyd1sbzyu5ub']
        // --------------------------------------------------

        $reservationIdArr = [];

        if ($dbUsersDataArr['notificationsReservationId']) {
            $reservationIdArr = unserialize($dbUsersDataArr['notificationsReservationId']);
        }


        // --------------------------------------------------
        //   予約IDがない場合は処理停止
        // --------------------------------------------------

        if (count($reservationIdArr) === 0) {
            return $returnArr;
        }


        // --------------------------------------------------
        //   予約通知のデータを取得
        // --------------------------------------------------

        $query = \DB::select(
            'id',
            'regi_date'
        )->from('notifications');

        $query->where('id', 'in', $reservationIdArr);
        $query->where('on_off', '=', 1);
        $dbReservationIdArr = $query->execute()->as_array();


        // --------------------------------------------------
        //   既読の通知ID取得
        // --------------------------------------------------

        $query = \DB::select(
            ['notifications_already_read_id', 'notificationsAlreadyReadId']
        )->from('users_data');

        $query->where('user_no', '=', USER_NO);
        $query->where('on_off', '=', 1);
        $dbUsersDataArr = $query->execute()->current();

        $alreadyReadIdArr = [];

        if ($dbUsersDataArr['notificationsAlreadyReadId']) {
            $alreadyReadIdArr = unserialize($dbUsersDataArr['notificationsAlreadyReadId']);
        }


        // --------------------------------------------------
        //   予約と既読の配列を合成
        // --------------------------------------------------

        $mergedArr = array_merge($dbReservationIdArr, $alreadyReadIdArr);


        // --------------------------------------------------
        //   古いIDを削除するためにDateTimeオブジェクトを作成
        // --------------------------------------------------

        $limitDatetime = new \DateTime();
        $limitDatetime->modify(LIMIT_NOTIFICATION_PRESERVATION_TERM);
        // $limitDatetime->modify('-5 day');


        // --------------------------------------------------
        //   通知IDが重複している場合、通知の日付が古い場合、削除
        // --------------------------------------------------

        $tempArr = $resultArr = [];

        foreach ($mergedArr as $key => $value) {

            $regiDatetime = new \DateTime($value['regi_date']);

            if ( ! in_array($value['id'], $tempArr) && ($limitDatetime < $regiDatetime)) {
                $tempArr[] = $value['id'];
                $resultArr[] = $value;
            }

        }


        // --------------------------------------------------
        //   保存用配列作成
        // --------------------------------------------------

        $saveArr['notifications_reservation_id'] = null;
        $saveArr['notifications_already_read_id'] = serialize($resultArr);


        // --------------------------------------------------
        //   既読IDを保存する
        // --------------------------------------------------

        $query = \DB::update('users_data');
        $query->set($saveArr);
        $query->where('user_no', '=', USER_NO);
        $query->execute();



        // echo '$reservationIdArr';
        // \Debug::dump($reservationIdArr);
        //
        // echo '$dbReservationIdArr';
        // \Debug::dump($dbReservationIdArr);
        //
        // echo '$alreadyReadIdArr';
        // \Debug::dump($alreadyReadIdArr);
        //
        // echo '$mergedArr';
        // \Debug::dump($mergedArr);
        //
        // echo '$returnArr';
        // \Debug::dump($returnArr);
        //
        // echo '$saveArr';
        // \Debug::dump($saveArr);
        //
        // exit();



        return $returnArr;

    }



    /**
     * すべての未読を既読にする / 要ログイン
     * @param  array $arr [description]
     * @return array      [description]
     */
    public function updateAllUnreadToAlreadyRead(array $arr): array
    {

        // --------------------------------------------------
        //   ログインチェック
        // --------------------------------------------------

        $modulesValidationsUser = new \React\Modules\Validations\User();
        $modulesValidationsUser->login();


        // --------------------------------------------------
        //   返り値の配列
        // --------------------------------------------------

        $returnArr = [];


        // --------------------------------------------------
        //   検索用データ取得
        //   参加しているコミュニティ情報と既読の通知IDを取得
        // --------------------------------------------------

        $query = \DB::select(
            ['participation_community', 'participationCommunity'],
            ['participation_community_secret', 'participationCommunitySecret'],
            ['notifications_already_read_id', 'notificationsAlreadyReadId']
        )->from('users_data');

        $query->where('user_no', '=', USER_NO);
        $query->where('on_off', '=', 1);
        $dbUsersDataArr = $query->execute()->current();


        // --------------------------------------------------
        //   通知の既読IDを処理する
        //   既読IDの情報はシリアライズされて以下の形式で保存されています
        //   ['id' => "8craskew3nfg1gh", 'regi_date' => "2017-08-26 17:48:39"]
        //   これを以下のようなIDだけが入っている配列にします
        //   ['0zj0pnd2vlw2eex', '2rwgyd1sbzyu5ub']
        // --------------------------------------------------

        $alreadyReadIdArr = [];

        if ($dbUsersDataArr['notificationsAlreadyReadId']) {

            // アンシリアライズ
            $oldAlreadyReadIdArr = unserialize($dbUsersDataArr['notificationsAlreadyReadId']);
            // \Debug::dump($oldAlreadyReadIdArr);

            // 日付を削除した配列を作る
            foreach ($oldAlreadyReadIdArr as $key => $value) {
                array_push($alreadyReadIdArr, $value['id']);
            }

        }


        // --------------------------------------------------
        //   検索用データ取得
        //   参加しているユーザーコミュニティの No を普通の配列に変換する
        //   参加しているユーザーコミュニティは、公開と非公開の2種類があります
        //   （非公開は他のユーザーに秘密で参加しているユーザーコミュニティです）
        //   例）(string) /1,2,3,4,5/ → (array) [1,2,3,4,5]
        // --------------------------------------------------

        $modulesFormat = new \React\Modules\Format();

        $participationCommunityNoArr = [];
        $participationCommunitySecretNoArr = [];

        // 公開
        if ($dbUsersDataArr['participationCommunity']) {
            $participationCommunityNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersDataArr['participationCommunity']);
        }

        // 非公開
        if ($dbUsersDataArr['participationCommunitySecret']) {
            $participationCommunitySecretNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersDataArr['participationCommunitySecret']);
        }

        // 公開と非公開を合成
        $mergedParticipationCommunityNoArr = array_merge($participationCommunityNoArr, $participationCommunitySecretNoArr);



        // --------------------------------------------------
        //   検索用データ取得
        //   新規募集があったときに通知を受ける設定にしているゲームNoを取得
        //   例）(string) /1,2,3,4,5/ → (array) [1,2,3,4,5]
        // --------------------------------------------------

        $query = \DB::select(
            ['notification_recruitment', 'notificationRecruitment']
        )->from('users_game_community');
        $query->where('user_no', '=', USER_NO);
        $dbUsersGameCommunityArr = $query->execute()->current();


        $notificationRecruitmentNoArr = [];

        if ($dbUsersGameCommunityArr['notificationRecruitment']) {
            $notificationRecruitmentNoArr = $modulesFormat->convertDatabaseData('dbformat into array', $dbUsersGameCommunityArr['notificationRecruitment']);
        }



        // --------------------------------------------------
        //   検索用データ取得
        //   ○○前の日時を取得 / 通知の保存期間
        // --------------------------------------------------

        $modulesDatetime = new \React\Modules\Datetime();
        $preservationTerm = $modulesDatetime->databaseFormat(LIMIT_NOTIFICATION_PRESERVATION_TERM);



        // --------------------------------------------------
        //   未読数取得
        // --------------------------------------------------

        $query = \DB::select(
            'notifications.id',
            'notifications.regi_date'
        )->from('notifications');
        $query->join('users_data', 'LEFT');
        $query->on('notifications.user_no', '=', 'users_data.user_no');
        $query->join('community', 'LEFT');
        $query->on('notifications.community_no', '=', 'community.community_no');
        $query->join('game_data', 'LEFT');
        $query->on('notifications.game_no', '=', 'game_data.game_no');


        // 一定期間以上過ぎた古い通知は読み込まない
        $query->where('notifications.regi_date', '>', $preservationTerm);


        // User No　自分のNo以外（他人のアクションを通知）　または　null（ログインしていないユーザーのアクションを通知）
        $query->and_where_open();
        $query->and_where('notifications.user_no', '!=', USER_NO);
        $query->or_where('notifications.user_no', '=', null);
        $query->and_where_close();


        // 参加してるコミュニティの通知
        $query->and_where_open();

        $query->and_where('notifications.target_user_no', '=', USER_NO);
        if (count($mergedParticipationCommunityNoArr) > 0) {
            $query->or_where('notifications.community_no', 'in', $mergedParticipationCommunityNoArr);
        }

        // 通知を受けとる設定にしてるゲームコミュニティの通知
        if (count($notificationRecruitmentNoArr) > 0) {
            $query->or_where_open();
            $query->and_where('notifications.type1', '=', 'gc');
            $query->and_where('notifications.type2', '=', 'recruitment');
            $query->and_where('notifications.game_no', 'in', $notificationRecruitmentNoArr);
            $query->or_where_close();
        }

        $query->and_where_close();


        // 既読の通知は除く
        if (count($alreadyReadIdArr) > 0) {
            $query->and_where('notifications.id', 'not in', $alreadyReadIdArr);
        }


        $unreadIdArr = $query->execute()->as_array();



        // --------------------------------------------------
		//   未読の通知がない場合は処理停止
		// --------------------------------------------------

        if (count($unreadIdArr) === 0) {
            return [];
        }



        // --------------------------------------------------
		//   既読の通知ID取得
		// --------------------------------------------------

        $query = \DB::select(
            ['notifications_already_read_id', 'notificationsAlreadyReadId']
        )->from('users_data');

		$query->where('user_no', '=', USER_NO);
		$query->where('on_off', '=', 1);
		$dbUsersDataArr = $query->execute()->current();

        $alreadyReadIdArr = [];

        if ($dbUsersDataArr['notificationsAlreadyReadId']) {
            $alreadyReadIdArr = unserialize($dbUsersDataArr['notificationsAlreadyReadId']);
        }


        // --------------------------------------------------
		//   未読と既読の配列を合成
		// --------------------------------------------------

        $mergedArr = array_merge($unreadIdArr, $alreadyReadIdArr);

        // array_push($mergedArr, [
        //     'id' => '7pby9s1lner2km2',
        //     'regi_date' => '2017-03-17 15:42:24'
        // ]);


        // --------------------------------------------------
		//   古いIDを削除するためにDateTimeオブジェクトを作成
		// --------------------------------------------------

        $limitDatetime = new \DateTime();
        $limitDatetime->modify(LIMIT_NOTIFICATION_PRESERVATION_TERM);
        // $limitDatetime->modify('-5 day');


        // --------------------------------------------------
		//   通知IDが重複している場合、通知の日付が古い場合、削除
		// --------------------------------------------------

        $tempArr = $resultArr = [];

        foreach ($mergedArr as $key => $value) {

            $regiDatetime = new \DateTime($value['regi_date']);

            if ( ! in_array($value['id'], $tempArr) && ($limitDatetime < $regiDatetime)) {
                $tempArr[] = $value['id'];
                $resultArr[] = $value;
            }

            // echo $limitDatetime->format('Y-m-d H:i:s') . ' < ' . $regiDatetime->format('Y-m-d H:i:s') . '<br><br>';


            // echo '$limitDatetime';
            // \Debug::dump($limitDatetime);
            //
            // echo '$regiDatetime';
            // \Debug::dump($regiDatetime);
            //
            // echo '$limitDatetime < $regiDatetime';
            // \Debug::dump($limitDatetime < $regiDatetime);

        }


        // --------------------------------------------------
        //   保存用配列作成
        // --------------------------------------------------

        $saveArr['notifications_reservation_id'] = null;
        $saveArr['notifications_already_read_id'] = serialize($resultArr);


        // --------------------------------------------------
        //   既読IDを保存する
        // --------------------------------------------------

        $query = \DB::update('users_data');
        $query->set($saveArr);
        $query->where('user_no', '=', USER_NO);
        $query->execute();



        // echo '$unreadIdArr';
        // \Debug::dump($unreadIdArr);
        //
        // echo '$alreadyReadIdArr';
        // \Debug::dump($alreadyReadIdArr);

        // echo '$mergedArr';
        // \Debug::dump($mergedArr);
        //
        // echo '$limitDatetime';
        // \Debug::dump($limitDatetime);
        //
        // echo '$saveArr';
        // \Debug::dump($saveArr);
        //
        //
        // exit();



        return [];

    }







}
