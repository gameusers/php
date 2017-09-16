// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';

import '../../../css/style.css';



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
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" />
        <ins
          className="adsbygoogle"
          style={{ display: 'inline-block', width: 300, height: 250 }}
          data-ad-client="ca-pub-8883929243875711"
          data-ad-slot="1930071119"
        />
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
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
