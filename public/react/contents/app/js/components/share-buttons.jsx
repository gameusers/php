// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';

import optionOutput from '../../../../../dev/blog/wp-content/plugins/game-users-share-buttons/js/entry-option';

import '../../../../../dev/blog/wp-content/plugins/game-users-share-buttons/css/option.css';



export default class ContentsAppShareButtons extends React.Component {


  // --------------------------------------------------
  //   Lifecycle Methods
  // --------------------------------------------------

  componentDidMount() {
    optionOutput();
  }


  render() {


    // --------------------------------------------------
    //   PC
    // --------------------------------------------------

    if (this.props.deviceType === 'other') {

      return (
        <article className="content">
          <div className="game-users-share-buttons-option" style={{ padding: 0 }} id="game-users-share-buttons-option" />
        </article>
      );

    }



    // --------------------------------------------------
    //   スマートフォン・タブレット
    // --------------------------------------------------

    return (
      <article className="content">
        <div className="game-users-share-buttons-option" style={{ margin: 0, padding: 0 }} id="game-users-share-buttons-option" />
      </article>
    );

  }

}

ContentsAppShareButtons.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  deviceType: PropTypes.string.isRequired,


};

ContentsAppShareButtons.defaultProps = {

};
