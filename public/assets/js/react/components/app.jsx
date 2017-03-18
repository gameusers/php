import React from 'react';
import { BrowserRouter, Route } from 'react-router-dom';
// import Header from './header';
import ContainerHeader from '../containers/header';


const Home = () => (
  <div>
    <h2>Home</h2>
  </div>
);

const About = () => (
  <div>
    <h2>About</h2>
  </div>
);

// const Topics = ({ match }) => (
//   <div>
//     <h2>Topics</h2>
//     <ul>
//       <li>
//         <Link to={`${match.url}/rendering`}>
//           Rendering with React
//         </Link>
//       </li>
//       <li>
//         <Link to={`${match.url}/components`}>
//           Components
//         </Link>
//       </li>
//       <li>
//         <Link to={`${match.url}/props-v-state`}>
//           Props v. State
//         </Link>
//       </li>
//     </ul>
//
//     <Route path={`${match.url}/:topicId`} component={Topic}/>
//     <Route exact path={match.url} render={() => (
//       <h3>Please select a topic.</h3>
//     )}/>
//   </div>
// );
//
// const Topic = ({ match }) => (
//   <div>
//     <h3>{match.params.topicId}</h3>
//   </div>
// );

class App extends React.Component {
  render() {
    // console.log(`this.props.playerId = ${this.props.playerId}`);
    // const localBasename = process.env.NODE_ENV === 'development' ? '/gameusers/public' : '';
    const localBasename = location.href.indexOf('gameusers.org') !== 1 ? '/gameusers/public' : '';

    return (
      <BrowserRouter basename={localBasename}>
        <div>
          {/* <ContainerHeader playerId={this.props.playerId} urlBase={this.props.urlBase} /> */}
          {/* <ContainerHeader /> */}
          <Route path="/:segment" component={ContainerHeader} />

          <hr />

          <Route exact path="/sc" component={Home} />
          <Route path="/about" component={About} />
          {/* <Route path="/topics" component={Topics} /> */}
        </div>
      </BrowserRouter>
    );
  }
}
App.propTypes = {
  playerId: React.PropTypes.string,
  urlBase: React.PropTypes.string.isRequired
};
App.defaultProps = {
  playerId: null,
  urlBase: 'https://gameusers.org/'
};



export default App;
