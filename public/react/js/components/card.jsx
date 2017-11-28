// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import moment from 'moment';
import { List } from 'immutable';

import { substrAndAddLeader, nl2brForReact } from '../modules/text';



export default class Card extends React.Component {

  constructor() {
    super();

    // Momentの言語を ja に指定（相対的時間用）
    moment.locale('ja');
  }



  codeCards() {

    const codeArr = [];


    // --------------------------------------------------
    //   表示するデータ
    // --------------------------------------------------

    let list = [];

    if (this.props.type === 'notification') {

      if (this.props.notificationActiveType === 'unread') {
        list = this.props.notificationUnreadList;
      } else {
        list = this.props.notificationAlreadyReadList;
      }

    }




    // const list = fromJSOrdered(arr);

    // console.log('list = ', list.toJS());
    // console.log('list count = ', list.count());


    // console.log('list = ', list);

    // --------------------------------------------------
    //   ループ
    // --------------------------------------------------

    list.entrySeq().forEach((entry) => {


      // --------------------------------------------------
      //   Key & Value
      // --------------------------------------------------

      const key = entry[0];
      const value = entry[1];


      // --------------------------------------------------
      //   カードのサイズ指定 ＆ カードのクラス
      // --------------------------------------------------

      let cardSize = 'normal';
      let classCard = 'card-box';

      // 画像か動画がある場合は中サイズのカードにする
      if (value.get('imageArr') || value.get('movieArr')) {
        cardSize = 'medium';
        classCard = 'card-medium-box';
      }


      // --------------------------------------------------
      //   カードのクラス / 一番下のカードは margin-bottom を 0 にする
      // --------------------------------------------------

      if (list.count() === (key + 1)) {
        classCard += ' margin-bottom-0';
      }


      // --------------------------------------------------
      //   指定されたタイプによって変える値
      // --------------------------------------------------

      // 通知に表示するカードの場合、widthを100%にする
      if (this.props.type === 'notification') {
        classCard += ' width-100percent';
      }


      // --------------------------------------------------
      //   カテゴリー & URL
      // --------------------------------------------------

      let category = null;
      let individualUrl = null;
      let pageUrl = null;


      // ゲームコミュニティ
      const contentsType = value.get('contentsType');
      const deleted = value.get('deleted');

      if (contentsType.get(0) === 'gameCommunity') {

        pageUrl = `${this.props.urlBase}gc/${value.get('gameId')}`;

        if (deleted) {

          individualUrl = pageUrl;
          category = '-';

        } else if (contentsType.get(1) === 'bbs') {

          individualUrl = `${pageUrl}/bbs/${value.get('bbsId')}`;
          category = '交流掲示板';

        } else if (contentsType.get(1) === 'recruitment') {

          individualUrl = `${pageUrl}/rec/${value.get('recruitmentId')}`;
          category = '募集掲示板';

        }

      // ユーザーコミュニティ
      } else if (contentsType.get(0) === 'userCommunity') {

        pageUrl = `${this.props.urlBase}uc/${value.get('communityId')}`;

        if (deleted) {

          individualUrl = pageUrl;
          category = '-';

        } else if (contentsType.get(1) === 'announcement' || contentsType.get(1) === 'mail_all') {

          individualUrl = pageUrl;
          category = 'コミュニティ';

        } else if (contentsType.get(1) === 'bbs') {

          individualUrl = `${pageUrl}/bbs/${value.get('bbsId')}`;
          category = 'コミュニティ';

        }

      }


      // --------------------------------------------------
      //   サムネイル画像のURL
      // --------------------------------------------------

      let thumbnailUrl = null;

      if (value.get('gameThumbnail')) {

        thumbnailUrl = `${this.props.urlBase}assets/img/game/${value.get('gameNo')}/thumbnail.jpg`;

      } else if (value.get('communityThumbnail')) {

        thumbnailUrl = `${this.props.urlBase}assets/img/community/${value.get('communityNo')}/thumbnail.jpg`;

      } else {

        const datetime = new Date(value.get('datetime'));
        const second = datetime.getSeconds();
        thumbnailUrl = `${this.props.urlBase}react/img/common/thumbnail-none-${second}.png`;

      }


      // --------------------------------------------------
      //   コメント総数
      // --------------------------------------------------

      let codeTotal = null;

      if (value.get('commentReplyTotal')) {
        codeTotal = (
          <span>
            <span className="glyphicon glyphicon-comment margin-left-5px" aria-hidden="true" /> {value.get('commentReplyTotal')}
          </span>
        );
      }


      // --------------------------------------------------
      //   相対的時間
      // --------------------------------------------------

      const relativeTime = moment(value.get('datetime')).fromNow();


      // --------------------------------------------------
      //   アップロードされた画像
      // --------------------------------------------------

      let imageUrl = null;
      let codeImageOrMovie = null;

      if (value.get('imageArr')) {

        if (contentsType.get(0) === 'gameCommunity' && contentsType.get(1) === 'bbs' && contentsType.get(2) === 'thread') {

          imageUrl = `${this.props.urlBase}assets/img/bbs_gc/thread/${value.get('bbsThreadNo')}/image_1.jpg`;

        } else if (contentsType.get(0) === 'gameCommunity' && contentsType.get(1) === 'bbs' && contentsType.get(2) === 'comment') {

          imageUrl = `${this.props.urlBase}assets/img/bbs_gc/comment/${value.get('bbsCommentNo')}/image_1.jpg`;

        } else if (contentsType.get(0) === 'gameCommunity' && contentsType.get(1) === 'bbs' && contentsType.get(2) === 'reply') {

          imageUrl = `${this.props.urlBase}assets/img/bbs_gc/reply/${value.get('bbsReplyNo')}/image_1.jpg`;

        } else if (contentsType.get(0) === 'userCommunity' && contentsType.get(1) === 'bbs' && contentsType.get(2) === 'thread') {

          imageUrl = `${this.props.urlBase}assets/img/bbs_uc/thread/${value.get('bbsThreadNo')}/image_1.jpg`;

        } else if (contentsType.get(0) === 'userCommunity' && contentsType.get(1) === 'bbs' && contentsType.get(2) === 'comment') {

          imageUrl = `${this.props.urlBase}assets/img/bbs_uc/comment/${value.get('bbsCommentNo')}/image_1.jpg`;

        } else if (contentsType.get(0) === 'userCommunity' && contentsType.get(1) === 'bbs' && contentsType.get(2) === 'reply') {

          imageUrl = `${this.props.urlBase}assets/img/bbs_uc/reply/${value.get('bbsReplyNo')}/image_1.jpg`;

        } else if (contentsType.get(0) === 'gameCommunity' && contentsType.get(1) === 'recruitment' && contentsType.get(2) === 'comment') {

          imageUrl = `${this.props.urlBase}assets/img/recruitment/recruitment/${value.get('recruitmentId')}/image_1.jpg`;

        } else if (contentsType.get(0) === 'gameCommunity' && contentsType.get(1) === 'recruitment' && contentsType.get(2) === 'reply') {

          imageUrl = `${this.props.urlBase}assets/img/recruitment/reply/${value.get('recruitmentReplyId')}/image_1.jpg`;

        }


        // --------------------------------------------------
        //   大きい画像は縦幅、横幅を制限する
        // --------------------------------------------------

        const imageWidth = value.getIn(['imageArr', 0, 'width']);
        const imageHeight = value.getIn(['imageArr', 0, 'height']);

        let maxWidth = imageWidth;
        let maxHeight = imageHeight;

        if (imageWidth >= imageHeight && imageWidth > 500) {

          maxWidth = 500;
          maxHeight = Math.round((imageHeight / imageWidth) * 500);

        } else if (imageHeight > 500) {

          maxWidth = Math.round((imageWidth / imageHeight) * 500);
          maxHeight = 500;

        }

        codeImageOrMovie = (
          <div className="top">
            <img src={imageUrl} style={{ maxWidth, maxHeight }} alt="" />
          </div>
        );


      // --------------------------------------------------
      //   投稿された動画
      // --------------------------------------------------

      } else if (value.get('movieArr')) {

        const youtubeId = value.getIn(['movieArr', 0, 'YouTube']);
        // console.log('youtubeId = ', youtubeId);
        // const maxWidth = 640;
        // const maxHeight = 480;
        const maxWidth = 320;
        const maxHeight = 180;

        codeImageOrMovie = (
          <div className="top">
            <img src={`${this.props.urlBase}react/img/common/movie-play-button.png`} className="movie-play-button" style={{ maxWidth, maxHeight }} alt="" />
            <img src={`https://img.youtube.com/vi/${youtubeId}/mqdefault.jpg`} style={{ maxWidth, maxHeight }} alt="" />
          </div>
        );

      }


      // --------------------------------------------------
      //   コメントの処理
      // --------------------------------------------------

      const comment = nl2brForReact(substrAndAddLeader(value.get('comment'), 500));
      // console.log('comment = ', comment);
      // substrAndAddLeader(null, 500);
      // nl2brForReact(null);


      // --------------------------------------------------
      //   コード / ノーマルサイズ
      // --------------------------------------------------

      if (cardSize === 'normal') {


        // --------------------------------------------------
        //   PC
        // --------------------------------------------------

        if (this.props.deviceType === 'other') {

          codeArr.push(
            <section className={classCard} key={key}>
              <a href={individualUrl}>
                <div className="left"><img src={thumbnailUrl} width="128" height="128" alt="" /></div>
              </a>
              <div className="right">
                <a href={individualUrl} className="card-link">
                  <h2 className="title">{value.get('title')}{codeTotal}</h2>
                  <p className="comment">{comment}</p>
                </a>
                <div className="info">
                  <a href={individualUrl} className="card-link category-and-time">
                    <p className="category"><span className="glyphicon glyphicon-folder-open" aria-hidden="true" /> {category}</p>
                    <p className="time"><span className="glyphicon glyphicon-time" aria-hidden="true" /> {relativeTime}</p>
                  </a>
                  <a href={pageUrl}>
                    <div className="page-name">{value.get('pageName')}</div>
                  </a>
                </div>
              </div>
            </section>
          );




        // --------------------------------------------------
        //   スマートフォン・タブレット
        // --------------------------------------------------

        } else {

          codeArr.push(
            <section className="card-s" key={key}>
              <div className="top">
                <a href={individualUrl} className="card-link">
                  <div className="left"><img src={thumbnailUrl} width="96" height="96" alt="" /></div>
                </a>
                <div className="right">
                  <a href={individualUrl} className="card-link">
                    <div className="title"><h2>{value.get('title')}{codeTotal}</h2></div>
                  </a>
                  <div className="info">
                    <a href={pageUrl} className="card-link">
                      <div className="category-and-time">
                        <p className="category"><span className="glyphicon glyphicon-folder-open" aria-hidden="true" /> {category}</p>
                        <p className="time"><span className="glyphicon glyphicon-time" aria-hidden="true" /> {relativeTime}</p>
                      </div>
                      <div className="page-name">{value.get('pageName')}</div>
                    </a>
                  </div>
                </div>
              </div>
              <a href={individualUrl} className="card-link">
                <p className="bottom">{comment}</p>
              </a>
            </section>
          );

        }


      // --------------------------------------------------
      //   コード / 中サイズ
      // --------------------------------------------------

      } else if (cardSize === 'medium') {

        codeArr.push(
          <section className={classCard} key={key}>
            <a href={individualUrl} className="card-link">
              {codeImageOrMovie}
            </a>
            <div className="bottom">
              <a href={individualUrl} className="card-link">
                <h2 className="title">{value.get('title')}{codeTotal}</h2>
                <p className="comment">{comment}</p>
              </a>
              <div className="info">
                <a href={individualUrl} className="card-link category-and-time">
                  <p className="category"><span className="glyphicon glyphicon-folder-open" aria-hidden="true" /> {category}</p>
                  <p className="time"><span className="glyphicon glyphicon-time" aria-hidden="true" /> {relativeTime}</p>
                </a>
                <a href={pageUrl}>
                  <div className="page-name">{value.get('pageName')}</div>
                </a>
              </div>
            </div>
          </section>
        );

      }


    });



    // --------------------------------------------------
    //   Pagination
    // --------------------------------------------------

    // let paginationItems = 0;
    //
    // // let paginationTotal = 0;
    // let paginationActivePage = 1;
    //
    // if (this.props.notificationActiveType === 'unread') {
    //   // paginationTotal = this.props.notificationUnreadTotal;
    //   paginationItems = Math.ceil(this.props.notificationUnreadTotal / this.props.notificationLimitNotification);
    //   paginationActivePage = this.props.notificationUnreadActivePage;
    // } else {
    //   // paginationTotal = this.props.notificationUnreadTotal;
    //   paginationItems = Math.ceil(this.props.notificationAlreadyReadTotal / this.props.notificationLimitNotification);
    //   paginationActivePage = this.props.notificationAlreadyReadActivePage;
    // }
    //
    // // console.log('paginationItems = ', paginationItems);
    //
    // codeArr.push(
    //   <Pagination
    //     key="pagination"
    //     className="pagination-margin"
    //     prev
    //     next
    //     first
    //     last
    //     ellipsis={false}
    //     boundaryLinks
    //     items={paginationItems}
    //     maxButtons={this.props.paginationColumn}
    //     activePage={paginationActivePage}
    //     // onSelect={e => this.props.funcChangeShareButtonsList(this.props.stateObj, 'iconThemes', e)}
    //   />
    // );


    return codeArr;

  }



  render() {
    return (
      <div>
        {this.codeCards()}
      </div>
    );
  }

}

Card.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  // stateModel: PropTypes.instanceOf(Model).isRequired,
  deviceType: PropTypes.string.isRequired,
  urlBase: PropTypes.string.isRequired,
  // paginationColumn: PropTypes.number.isRequired,


  // --------------------------------------------------
  //   モーダル
  // --------------------------------------------------

  type: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   通知
  // --------------------------------------------------

  notificationActiveType: PropTypes.string.isRequired,
  // notificationUnreadTotal: PropTypes.number.isRequired,
  notificationUnreadList: PropTypes.instanceOf(List).isRequired,
  // notificationUnreadActivePage: PropTypes.number.isRequired,
  // notificationAlreadyReadTotal: PropTypes.number.isRequired,
  notificationAlreadyReadList: PropTypes.instanceOf(List).isRequired,
  // notificationAlreadyReadActivePage: PropTypes.number.isRequired,
  // notificationLimitNotification: PropTypes.number.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  // funcJavascriptLink: PropTypes.func.isRequired,


};

Card.defaultProps = {

  // footerCardGameCommunityRenewalList: null,
  // footerCardGameCommunityAccessList: null,
  // footerCardUserCommunityAccessList: null

};
