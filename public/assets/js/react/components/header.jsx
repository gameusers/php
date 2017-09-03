import React from 'react';
import PropTypes from 'prop-types';
// import { Button, FormGroup, Radio } from 'react-bootstrap';
// import { List, Map } from 'immutable';
// import { Model } from '../models/model';



export default class Header extends React.Component {

  componentWillReceiveProps(nextProps) {
    // console.log('componentWillReceiveProps / nextProps = ', nextProps);
  }


  // validationStateRssUrl() {
  //   let state = 'error';
  //   if (!this.props.rssUrl || this.props.rssUrl.match(/^(https?)(:\/\/[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+)$/)) {
  //     state = 'success';
  //   }
  //   return state;
  // }


  render() {

    // let checkedFree = false;
    // let checkedPremium = false;
    // let checkedBusiness = false;
    //
    // if (this.props.plan === 'free') {
    //   checkedFree = true;
    // } else if (this.props.plan === 'premium') {
    //   checkedPremium = true;
    // } else {
    //   checkedBusiness = true;
    // }


    return (
      <div>
        <header className="cd-auto-hide-header">

          <div className="logo">
            <a href={this.props.urlBasis}><img src={`${this.props.urlBasis}assets/img/react/common/gameusers-logo.png`} alt="Game Users" /></a>
            <div className="bell_box" id="header_notifications" data-user_no={this.props.userNo}>
              <div className="bell"><span className="glyphicon glyphicon-bell" aria-hidden="true" /></div>
              <div className="bell_number"><span className="badge" id="header_notifications_unread_total" data-unread_id="">-</span></div>
            </div>
          </div>

          <nav className="cd-primary-nav">
            <a href="#cd-navigation" className="nav-trigger">
              <span>
                <em aria-hidden="true" />
                Menu
              </span>
            </a>

            <ul id="cd-navigation">
              {/* {codePlayer} */}
              <li><a href={`${this.props.urlBasis}help`}><span className="glyphicon glyphicon-question-sign" aria-hidden="true" /> ヘルプ</a></li>
              {/* {codeLogin}
              {codeLogout} */}
            </ul>
          </nav>

        </header>

        {/* <HeroImage {...this.props} />
        <Tab {...this.props} /> */}
      </div>
    );
  }

}

Header.propTypes = {
  // stateObj: PropTypes.instanceOf(Model).isRequired,
  //
  urlBasis: PropTypes.string,
  userNo: PropTypes.string,
  // urlBasis: PropTypes.string.isRequired,
  //
  // funcAjaxChangePlan: PropTypes.func.isRequired
};

Header.defaultProps = {
  urlBasis: null,
  userNo: null,
};


// export default Header;
