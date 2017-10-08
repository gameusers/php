// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';

import optionOutput from '../../../../../dev/blog/wp-content/plugins/gameusers-share-buttons/js/entry-option';

import '../../../../../dev/blog/wp-content/plugins/gameusers-share-buttons/css/option.css';



export default class MainAppShareButtons extends React.Component {


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

          {/* <strong>Main / {this.props.urlDirectory1} / {this.props.urlDirectory2} / {this.props.urlDirectory3}</strong> */}

          <div className="gameusers-share-buttons-option" style={{ padding: 0 }} id="gameusers-share-buttons-option">share-buttons</div>

        </article>
      );

    }



    // --------------------------------------------------
    //   スマートフォン・タブレット
    // --------------------------------------------------

    return (
      <article className="content">

        <div className="gameusers-share-buttons-option" style={{ margin: 0, padding: 0 }} id="gameusers-share-buttons-option">share-buttons</div>

      </article>
    );

  }

}

MainAppShareButtons.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  // stateModel: PropTypes.instanceOf(Model).isRequired,

  deviceType: PropTypes.string.isRequired,
  // urlDirectory1: PropTypes.string,
  // urlDirectory2: PropTypes.string,
  // urlDirectory3: PropTypes.string,

  // urlBase: PropTypes.string.isRequired,


};

MainAppShareButtons.defaultProps = {

  // urlDirectory1: null,
  // urlDirectory2: null,
  // urlDirectory3: null,

};
