// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
// import PropTypes from 'prop-types';
// import { Tabs, Tab } from 'react-bootstrap';
import Header from './header';



export default class Root extends React.Component {

  componentWillMount() {
    // console.log('Content / componentWillMount');
    // this.props.funcInitialAsynchronous(this.props.stateObj);
  }

  render() {
    return (
      <div>
        <Header {...this.props} />
      </div>
    );
  }

}


// --------------------------------------------------
//   PropTypes
// --------------------------------------------------

Root.propTypes = {
  // stateObj: PropTypes.instanceOf(Model).isRequired,
  //
  // funcInitialAsynchronous: PropTypes.func.isRequired,
};


// export default Root;
