// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { FormGroup, FormControl } from 'react-bootstrap';
import { List } from 'immutable';
import Masonry from 'react-masonry-component';
import { Model } from '../models/model';
import ModalNotification from './modal/notification';

import '../../css/style.css';





export default class Footer extends React.Component {

  // constructor() {
  //   super();
  //
  //   this.msnry = null;
  // }
  //
  //
  //
  // componentDidMount() {
  //
  //   console.log('componentDidMount');
  //
  //
  //   // --------------------------------------------------
  //   //   Masonry
  //   // --------------------------------------------------
  //
  //   // imagesLoaded('footer', () => {
  //   //   this.msnry = new Masonry('footer .content-card-box .cards', {
  //   //     itemSelector: '.card-link',
  //   //     transitionDuration: '2s'
  //   //   });
  //   // });
  //
  // }
  //
  // componentDidUpdate() {
  //
  //   console.log('componentDidUpdate');
  //
  //   // imagesLoaded('footer', () => {
  //   //   this.msnry.reloadItems();
  //   //   this.msnry.layout();
  //   // });
  //
  // }



  codeCard() {

    const codeArr = [];
    let thumbnailMap = {};


    if (this.props.footerCardType === 'gameCommunityAccess') {
      thumbnailMap = this.props.footerCardGameCommunityAccessList;
    } else if (this.props.footerCardType === 'userCommunityAccess') {
      thumbnailMap = this.props.footerCardUserCommunityAccessList;
    } else {
      thumbnailMap = this.props.footerCardGameCommunityRenewalList;
    }

    // console.log('this.props.footerCardType = ', this.props.footerCardType);
    // console.log('this.props.footerCardGameCommunityAccessList = ', this.props.footerCardGameCommunityAccessList);
    // console.log('this.props.footerCardUserCommunityAccessList = ', this.props.footerCardUserCommunityAccessList);
    // console.log('this.props.footerCardGameCommunityRenewalList = ', this.props.footerCardGameCommunityRenewalList);
    // console.log('thumbnailMap = ', thumbnailMap.toJS());


    thumbnailMap.entrySeq().forEach((e) => {

      const key = e[0];
      const value = e[1];
      // console.log("value.get('gameNo')", value.get('gameNo'));


      // --------------------------------------------------
      //   リンクのURL
      // --------------------------------------------------

      let linkUrl = null;

      if (value.get('communityNo')) {
        linkUrl = `${this.props.urlBase}uc/${value.get('communityId')}`;
      } else {
        linkUrl = `${this.props.urlBase}gc/${value.get('gameId')}`;
      }


      // --------------------------------------------------
      //   サムネイル画像のURL
      // --------------------------------------------------

      let thumbnailUrl = null;

      if (value.get('thumbnail')) {

        if (value.get('communityNo')) {
          thumbnailUrl = `${this.props.urlBase}assets/img/community/${value.get('communityNo')}/thumbnail.jpg`;
        } else if (value.get('gameNo')) {
          thumbnailUrl = `${this.props.urlBase}assets/img/game/${value.get('gameNo')}/thumbnail.jpg`;
        }

      } else {

        const renewalDate = new Date(value.get('renewalDate'));
        const second = renewalDate.getSeconds();
        thumbnailUrl = `${this.props.urlBase}react/img/common/thumbnail-none-${second}.png`;

      }


      codeArr.push(
        <a href={linkUrl} className="card-link" key={key}>
          <div className="card-game">
            <div className="image"><img src={thumbnailUrl} alt={value.get('name')} /></div>
            <div className="title">{value.get('name')}</div>
          </div>
        </a>
      );

    });


    const masonryOptions = {
      transitionDuration: '0.5s'
    };


    const code = (
      <div className="content-card-box" id="footer_content_card">

        <FormGroup className="select-type" validationState={null}>
          <FormControl
            componentClass="select"
            value={this.props.footerCardType}
            onChange={e => this.props.funcSelectFooterCardType(
              this.props.stateModel,
              e.target.value
            )}
          >
            <option value="gameCommunityRenewal">最近更新されたゲームコミュニティ</option>
            <option value="gameCommunityAccess">最近アクセスしたゲームコミュニティ</option>
            <option value="userCommunityAccess">最近アクセスしたユーザーコミュニティ</option>
          </FormControl>
        </FormGroup>

        <Masonry className="cards" options={masonryOptions}>
          {codeArr}
        </Masonry>

      </div>
    );

    return code;

  }



  render() {
    return (
      <footer>

        {this.codeCard()}

        <div className="copyright"><span className="glyphicon glyphicon-copyright-mark" aria-hidden="true" /> Game Users All Rights Reserved.</div>

        <ModalNotification {...this.props} />

      </footer>
    );
  }

}

Footer.propTypes = {

  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,

  urlDirectory1: PropTypes.string,
  urlDirectory2: PropTypes.string,

  urlBase: PropTypes.string.isRequired,

  // csrfToken: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   フッター
  // --------------------------------------------------

  footerCardType: PropTypes.string.isRequired,
  footerCardGameCommunityRenewalList: PropTypes.instanceOf(List),
  footerCardGameCommunityAccessList: PropTypes.instanceOf(List),
  footerCardUserCommunityAccessList: PropTypes.instanceOf(List),





  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcUrlDirectory: PropTypes.func.isRequired,

  funcSelectFooterCardType: PropTypes.func.isRequired,

};

Footer.defaultProps = {

  urlDirectory1: null,
  urlDirectory2: null,

  footerCardGameCommunityRenewalList: null,
  footerCardGameCommunityAccessList: null,
  footerCardUserCommunityAccessList: null

};
