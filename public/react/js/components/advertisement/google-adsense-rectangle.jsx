// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import AdSense from 'react-adsense';



export default class AdGoogleAdsenseRectangle extends React.Component {

  render() {


    // --------------------------------------------------
    //   疑似広告
    // --------------------------------------------------

    if (this.props.adBlock) {

      return (
        <aside className="google-adsense-rectangle">
          <img src={`${this.props.urlBase}react/img/common/adsense-sample-300x250.png`} width="300" height="250" alt="Google Adsense Rectangle" />
        </aside>
      );

    }

    // --------------------------------------------------
    //   広告
    // --------------------------------------------------

    return (
      <aside className="google-adsense-rectangle">
        <AdSense.Google
          client="ca-pub-8883929243875711"
          slot="1930071119"
          style={{ display: 'inline-block', width: 300, height: 250 }}
          format=""
        />
      </aside>
    );

  }

}

AdGoogleAdsenseRectangle.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  urlBase: PropTypes.string.isRequired,
  adBlock: PropTypes.bool.isRequired,


};

AdGoogleAdsenseRectangle.defaultProps = {

};
