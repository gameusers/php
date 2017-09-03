// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { Pagination } from 'react-bootstrap';
import moment from 'moment';
// import { ContextMenu, MenuItem, ContextMenuTrigger } from 'react-contextmenu';
import { List } from 'immutable';
// import Masonry from 'react-masonry-component';
// import { ContextMenuProvider, ContextMenu, Item, Separator, IconFont } from 'react-contexify';
// import 'react-contexify/dist/ReactContexify.min.css';
import { Model, fromJSOrdered } from '../models/model';

import '../../css/style.css';





export default class Card extends React.Component {

  constructor() {
    super();

    // Momentの言語を ja に指定
    moment.locale('ja');
  }


  // componentDidMount() {
  //
  //   console.log('componentDidMount');
  //
  //   // $.contextMenu({
  //   //   selector: '#jslink',
  //   //   callback(key, options) {
  //   //   // callback: function(key, options) {
  //   //     // const target = '_target';
  //   //     // const url = $(this).data('jslink');
  //   //
  //   //     // console.log('$(this).text() = ', $(this).text());
  //   //     // console.log('key = ', key);
  //   //     // console.log('options = ', options);
  //   //     // console.log('this = ', this);
  //   //     // console.log('url = ', url);
  //   //     // window.open(url, target);
  //   //   },
  //   //   items: {
  //   //     edit: { name: '新しいタブで開く' }
  //   //   }
  //   // });
  //
  //   // $.contextMenu({
  //   //   selector: '#jslink',
  //   //   build($trigger, e) {
  //   //     // e.preventDefault();
  //   //     return {
  //   //       callback() {
  //   //         // const target = '_target';
  //   //         // const url = $(this).data('jslink');
  //   //         // console.log('url = ', url);
  //   //       },
  //   //       items: {
  //   //         new: { name: '新しいタブで開く' }
  //   //       }
  //   //     };
  //   //   }
  //   // });
  //
  // }


  codeBox() {

    const codeArr = [];

    // pageType: 'gameCommunity',
    // contentType: 'bbs',
    // tabType: 'recruitment',

    const arr = [
      {
        contentsType: ['gameCommunity', 'bbs', 'comment'],
        datetime: '2017-08-29 15:41:40',
        pageName: 'アサシンクリードユニティ',
        gameThumbnail: 1,
        gameNo: 1,
        gameId: 'assassins-creed-unity',
        title: 'Assassin\'s Creed Unityについて語ろう！',
        comment: 'Game Usersとは？Game Users（ゲームユーザーズ）はゲームユーザーのためのSNS・コミュニティサイトです。ゲームに興味のある人たちが集まって、交流がしやすくなるような様々な機能を用意しています',
        imageArr: null,
        movieArr: null,
        bbsId: 'ffoa79pspg11zxvn',
        commentReplyTotal: 15
      },
      {
        contentsType: ['userCommunity', 'bbs', 'reply'],
        datetime: '2017-03-17 15:33:35',
        pageName: 'User2のコミュニティ012345678901234567890123456789012345678901234567890123456789',
        communityThumbnail: 1,
        communityNo: 1,
        communityId: 'community',
        title: '雑談スレッド01234567890123456789012345678901234567890123456789012345678901234567890123456789',
        comment: 'コンテンツについて Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。',
        imageArr: null,
        movieArr: null,
        bbsId: 'doo4rqjid8kbn713',
        commentReplyTotal: 100
      },
      {
        contentsType: ['userCommunity', 'bbs', 'comment'],
        datetime: '2017-02-10 18:00:01',
        pageName: 'User2のコミュニティ',
        communityThumbnail: 1,
        communityNo: 1,
        communityId: 'community',
        title: 'スレッド1',
        comment: '画像テスト',
        imageArr: [
          {
            width: 1000,
            height: 563
          }
        ],
        movieArr: null,
        bbsId: 'm8lxk167l3r9zepc',
        bbsThreadNo: 1,
        bbsCommentNo: 4,
        commentReplyTotal: 1
      },
      {
        contentsType: ['gameCommunity', 'bbs', 'comment'],
        datetime: '2017-01-03 11:11:50',
        pageName: 'Grand Theft Auto V',
        gameThumbnail: 1,
        gameNo: 3,
        gameId: 'gta5',
        title: 'Grand Theft Auto Vについて語ろう！',
        comment: '縦長画像',
        imageArr: [
          {
            width: 284,
            height: 600
          }
        ],
        movieArr: null,
        bbsId: '6albgf1af7caw0ct',
        bbsThreadNo: 3,
        bbsCommentNo: 7,
        commentReplyTotal: 88
      },
      {
        contentsType: ['gameCommunity', 'bbs', 'comment'],
        datetime: '2017-01-02 11:11:50',
        pageName: 'Grand Theft Auto V / グランセフトオート5',
        gameThumbnail: 1,
        gameNo: 3,
        gameId: 'gta5',
        title: 'Grand Theft Auto Vについて語ろう！',
        comment: '小さい画像',
        imageArr: [
          {
            width: 128,
            height: 128
          }
        ],
        movieArr: null,
        bbsId: '6albgf1af7caw0ct',
        bbsThreadNo: 3,
        bbsCommentNo: 8,
        commentReplyTotal: 888
      },
      {
        contentsType: ['gameCommunity', 'bbs', 'comment'],
        datetime: '2016-12-02 10:10:10',
        pageName: 'Grand Theft Auto V 01234567890123456789012345678901234567890123456789',
        gameThumbnail: 1,
        gameNo: 3,
        gameId: 'gta5',
        title: 'Grand Theft Auto Vについて語ろう！012345678901234567890123456789',
        comment: 'YouTube動画テスト 0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789',
        imageArr: null,
        movieArr: [
          {
            YouTube: 'M8-vje-bq9c'
          }
        ],
        bbsId: 'tjlk62tztyzvmr9g',
        bbsThreadNo: 3,
        bbsCommentNo: 9,
        commentReplyTotal: 8888
      },
    ];


    const list = fromJSOrdered(arr);

    // console.log('list = ', list.toJS());
    // console.log('list count = ', list.count());


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
      //   カードタイプ
      // --------------------------------------------------

      let cardType = 'normal';
      let classCard = 'card-box';

      if (value.get('imageArr') || value.get('movieArr')) {
        cardType = 'medium';
        classCard = 'card-medium-box';
      }


      // --------------------------------------------------
      //   カテゴリー & URL
      // --------------------------------------------------

      let category = null;
      let individualUrl = null;
      let pageUrl = null;


      // ゲームコミュニティ
      const contentsType = value.get('contentsType');

      if (contentsType.get(0) === 'gameCommunity') {

        pageUrl = `${this.props.urlBase}gc/${value.get('gameId')}`;

        if (contentsType.get(1) === 'bbs') {

          individualUrl = `${pageUrl}/bbs/${value.get('bbsId')}`;
          category = '交流掲示板';

        } else if (contentsType.get(1) === 'recruitment') {

          individualUrl = `${pageUrl}/rec/${value.get('recruitmentId')}`;
          category = '募集掲示板';

        }

      // ユーザーコミュニティ
      } else if (contentsType.get(0) === 'userCommunity') {

        pageUrl = `${this.props.urlBase}uc/${value.get('communityId')}`;

        if (contentsType.get(1) === 'bbs') {

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
      //   コメントトータル
      // --------------------------------------------------

      let codeTotal = null;

      if (value.get('commentReplyTotal')) {
        codeTotal = (
          <span>
            <span className="glyphicon glyphicon-comment margin-left-5px" aria-hidden="true" /> {value.get('commentReplyTotal')}
          </span>
        );
      }

      // let codeTitle = null;
      //
      // if (value.get('commentReplyTotal')) {
      //   codeTitle = (
      //     <h2 className="title">
      //       {value.get('title')} <span className="glyphicon glyphicon-comment margin-left-5px" aria-hidden="true" /> {value.get('commentReplyTotal')}
      //     </h2>
      //   );
      // } else {
      //   codeTitle = (
      //     <h2 className="title">
      //       {value.get('title')}
      //     </h2>
      //   );
      // }


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
      //   カードのクラス
      // --------------------------------------------------

      // 一番下のカードはmargin-bottomを0にする
      if (list.count() === (key + 1)) {
        classCard += ' margin-bottom-0';
      }

      // 通知に表示するカードの場合、widthを100%にする
      if (this.props.dataType === 'notification') {
        classCard += ' width-100percent';
      }


      // --------------------------------------------------
      //   コード / ノーマルサイズ
      // --------------------------------------------------

      if (cardType === 'normal') {


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
                  <p className="comment">{value.get('comment')}</p>
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
                <p className="bottom">{value.get('comment')}</p>
              </a>
            </section>
          );

        }


      // --------------------------------------------------
      //   コード / 中サイズ
      // --------------------------------------------------

      } else if (cardType === 'medium') {

        codeArr.push(
          <section className={classCard} key={key}>
            <a href={individualUrl} className="card-link">
              {codeImageOrMovie}
            </a>
            <div className="bottom">
              <a href={individualUrl} className="card-link">
                <h2 className="title">{value.get('title')}{codeTotal}</h2>
                <p className="comment">{value.get('comment')}</p>
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

    let paginationItems = 0;

    // let paginationTotal = 0;
    let paginationActivePage = 1;

    if (this.props.notificationActiveType === 'unread') {
      // paginationTotal = this.props.notificationUnreadTotal;
      paginationItems = Math.ceil(this.props.notificationUnreadTotal / this.props.notificationLimitNotification);
      paginationActivePage = this.props.notificationUnreadActivePage;
    } else {
      // paginationTotal = this.props.notificationUnreadTotal;
      paginationItems = Math.ceil(this.props.notificationAlreadyReadTotal / this.props.notificationLimitNotification);
      paginationActivePage = this.props.notificationAlreadyReadActivePage;
    }

    // console.log('paginationItems = ', paginationItems);

    codeArr.push(
      <Pagination
        key="pagination"
        className="pagination-margin"
        prev
        next
        first
        last
        ellipsis={false}
        boundaryLinks
        items={paginationItems}
        maxButtons={this.props.paginationColumn}
        activePage={paginationActivePage}
        // onSelect={e => this.props.funcChangeShareButtonsList(this.props.stateObj, 'iconThemes', e)}
      />
    );


    return codeArr;

  }



  render() {
    return (
      <div>
        {this.codeBox()}
      </div>
    );
  }

}

Card.propTypes = {

  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,
  deviceType: PropTypes.string.isRequired,
  urlBase: PropTypes.string.isRequired,
  paginationColumn: PropTypes.number.isRequired,


  // --------------------------------------------------
  //   モーダル
  // --------------------------------------------------

  dataType: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   通知
  // --------------------------------------------------

  notificationActiveType: PropTypes.string.isRequired,
  notificationUnreadTotal: PropTypes.number.isRequired,
  notificationUnreadList: PropTypes.instanceOf(List).isRequired,
  notificationUnreadActivePage: PropTypes.number.isRequired,
  notificationAlreadyReadTotal: PropTypes.number.isRequired,
  notificationAlreadyReadList: PropTypes.instanceOf(List).isRequired,
  notificationAlreadyReadActivePage: PropTypes.number.isRequired,
  notificationLimitNotification: PropTypes.number.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcJavascriptLink: PropTypes.func.isRequired,
  // funcModalNotificationShow: PropTypes.func.isRequired,

};

Card.defaultProps = {

  // footerCardGameCommunityRenewalList: null,
  // footerCardGameCommunityAccessList: null,
  // footerCardUserCommunityAccessList: null

};
