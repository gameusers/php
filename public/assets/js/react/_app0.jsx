import React from 'react';
import ReactDOM from 'react-dom';
import { createStore } from 'redux';
import { Provider, connect } from 'react-redux';
// import Counter from './public/assets/js/components/Counter';
// import counter from './public/assets/js/reducers';



// () => {
//
// }










// class MyComponent extends React.Component {
//   render() {
//     return (
//       <h1>{this.props.children}</h1>// childrenにするとタグの中身を取得できる
//       // <h1>{this.props.test1 + this.props.test2}</h1>
//     );
//   }
// }
//
// MyComponent.propTypes = {
//   children: React.PropTypes.string.isRequired
//   // test1: React.PropTypes.string.isRequired,
//   // test2: React.PropTypes.string.isRequired
// };
//
// ReactDOM.render(
//   <MyComponent>hogehoge</MyComponent>,
//   // <MyComponent test1="hoge" test2="fuga" />,
//   document.getElementById('react_test')
// );





// class CommentList extends React.Component {
//   render() {
//     // alert(Array.isArray(this.props.comments));
//     // console.log(this.props.comments);
//     const comments = this.props.comments.map(comment =>
//       <li key={comment.id}>{comment}</li>
//     );
//     return (
//       <ul className="comment-list">{comments}</ul>
//     );
//   }
// }
// CommentList.propTypes = {
//   comments: React.PropTypes.arrayOf(
//     React.PropTypes.string.isRequired
//   ).isRequired
// };
//
//
// // const tempArr = ['AAA', 'BBB', 'CCC'];
//
// class CommentApp extends React.Component {
//
//   constructor(props) {
//     super(props);
//     this.state = {
//       todoArr: ['AAA', 'BBB', 'CCC'],
//       inputValue: null
//     };
//
//     this.handleSubmit = this.handleSubmit.bind(this);
//     this.handleChange = this.handleChange.bind(this);
//     // console.log(this.tempArr);
//   }
//
//   handleSubmit(e) {
//     e.preventDefault();
//
//     const stateTodoArr = this.state.todoArr;
//     const stateInputValue = this.state.inputValue;
//     stateTodoArr.push(stateInputValue);
//
//     // console.log(stateInputValue, stateTodoArr);
//
//     this.setState({
//       todoArr: stateTodoArr,
//       inputValue: stateInputValue
//     });
//     // this.setState({ inputValue: 'Hello' });
//   }
//
//   handleChange(e) {
//     this.setState({
//       inputValue: e.target.value
//     });
//   }
//
//   render() {
//     return (
//       <div>
//         <form className="comment-form" onSubmit={this.handleSubmit}>
//           <input type="text" value={this.state.inputValue} onChange={this.handleChange} />
//           <input type="submit" value="add" />
//         </form>
//         <CommentList comments={this.state.todoArr} />
//       </div>
//     );
//   }
//
// }
//
// // <form className="comment-form" onSubmit={this.handleSubmit}>
// //   <input type="text" value={this.state.inputValue} onChange={this.handleChange} />
// //   <input type="submit" value="add" />
// // </form>
//
// ReactDOM.render(
//   <CommentApp>hogehoge</CommentApp>,
//   // <MyComponent test1="hoge" test2="fuga" />,
//   document.getElementById('root')
// );







/* Actionsの実装 */

// Action名の定義
const SEND = 'SEND';

// Action Creators
function send(value) {
  // Action
  return {
    type: SEND,
    value,
  };
}


/* Reducersの実装 */

function formReducer(state, action) {
  switch (action.type) {
    case 'SEND':
      return Object.assign({}, state, {
        value: action.value,
      });
    default:
      return state;
  }
}


/* Storeの実装 */

// 初期state変数（initialState）の作成
const initialState = {
  value: null,
};
// createStore（）メソッドを使ってStoreの作成
const store = createStore(formReducer, initialState);



/* Viewの実装 */

// View (Container Components)
class FormApp extends React.Component {
  render() {
    return (
      <div>
        <FormInput handleClick={this.props.onClick} />
        <FormDisplay data={this.props.value} />
      </div>
    );
  }
}
FormApp.propTypes = {
  onClick: React.PropTypes.func.isRequired,
  value: React.PropTypes.string.isRequired,
};

// Connect to Redux
function mapStateToProps(state) {
  return {
    value: state.value,
  };
}
function mapDispatchToProps(dispatch) {
  return {
    onClick(value) {
      dispatch(send(value));
    },
  };
}
const AppContainer = connect(
  mapStateToProps,
  mapDispatchToProps
)(FormApp);




// View (Presentational Components)
class FormInput extends React.Component {
  send(e) {
    e.preventDefault();
    this.props.handleClick(this.myInput.value.trim());
    this.myInput.value = '';
    // return;
  }
  render() {
    return (
      <form>
        <input type="text" ref={ref => (this.myInput = ref)} defaultValue="" />
        <button onClick={event => this.send(event)}>Send</button>
      </form>
    );
  }
}
FormInput.propTypes = {
  handleClick: React.PropTypes.func.isRequired,
};

// View (Presentational Components)
class FormDisplay extends React.Component {
  render() {
    return (
      <div>{this.props.data}</div>
    );
  }
}
FormDisplay.propTypes = {
  data: React.PropTypes.string.isRequired,
};






// Rendering
ReactDOM.render(
  <Provider store={store}>
    <AppContainer />
  </Provider>,
  document.querySelector('#root')
);







// const store = createStore(() => {
//   'Hello Redux';
// });
//
// const contents = document.getElementById('react_test');
// contents.innerHTML = store.getState();
// console.log(store);



// class MyComponent extends React.Component {
//   render() {
//     return <h1>Hello World 4 {store.getState()}</h1>;
//   }
// }
//
// ReactDOM.render(
//   <MyComponent />,
//   document.getElementById('react_test')
// );
//
// console.log(store.getState());

// class ProductCategoryRow extends React.Component {
//   render() {
//     return <tr><th colSpan="2">{this.props.category}</th></tr>;
//   }
// }
//
// ProductCategoryRow.propTypes = {
//   category: React.PropTypes.string.isRequired,
// };
//
//
// class ProductRow extends React.Component {
//   render() {
//     const name = this.props.product.stocked ? this.props.product.name : (
//       <span style={{ color: 'red' }}>
//         {this.props.product.name}
//       </span>
//     );
//     return (
//       <tr>
//         <td>{name}</td>
//         <td>{this.props.product.price}</td>
//       </tr>
//     );
//   }
// }
//
// ProductRow.propTypes = {
//   product: React.PropTypes.string.isRequired,
//   stocked: React.PropTypes.string.isRequired,
// };
//
// class ProductTable extends React.Component {
//   render() {
//     const rows = [];
//     let lastCategory = null;
//     this.props.products.forEach(function(product) {
//       if (product.category !== lastCategory) {
//         rows.push(<ProductCategoryRow category={product.category} key={product.category} />);
//       }
//       rows.push(<ProductRow product={product} key={product.name} />);
//       lastCategory = product.category;
//     });
//     return (
//       <table>
//         <thead>
//           <tr>
//             <th>Name</th>
//             <th>Price</th>
//           </tr>
//         </thead>
//         <tbody>{rows}</tbody>
//       </table>
//     );
//   }
// }
//
// class SearchBar extends React.Component {
//   render() {
//     return (
//       <form>
//         <input type="text" placeholder="Search..." />
//         <p>
//           <input type="checkbox" />
//           {' '}
//           Only show products in stock
//         </p>
//       </form>
//     );
//   }
// }
//
// class FilterableProductTable extends React.Component {
//   render() {
//     return (
//       <div>
//         <SearchBar />
//         <ProductTable products={this.props.products} />
//       </div>
//     );
//   }
// }
//
//
// const PRODUCTS = [
//   { category: 'Sporting Goods', price: '$49.99', stocked: true, name: 'Football' },
//   { category: 'Sporting Goods', price: '$9.99', stocked: true, name: 'Baseball' },
//   { category: 'Sporting Goods', price: '$29.99', stocked: false, name: 'Basketball' },
//   { category: 'Electronics', price: '$99.99', stocked: true, name: 'iPod Touch' },
//   { category: 'Electronics', price: '$399.99', stocked: false, name: 'iPhone 5' },
//   { category: 'Electronics', price: '$199.99', stocked: true, name: 'Nexus 7' }
// ];
//
// ReactDOM.render(
//   <FilterableProductTable products={PRODUCTS} />,
//   document.getElementById('container')
// );


$(() => {
  // class MyComponent extends React.Component {
  //   render() {
  //     return <div>Hello World</div>;
  //   }
  // }

  // const MyComponent = ({ hello, name }) => (
  //   <div className="commentBox">
  //     {hello}! I am a {name}.
  //   </div>
  // );
  // class MyComponent extends React.Component {
  //   render() {
  //     return (
  //       <div className="shopping-list">
  //         <h1>Shopping List for {this.props.name}</h1>
  //         <ul>
  //           <li>Instagram</li>
  //           <li>WhatsApp</li>
  //           <li>Oculus</li>
  //         </ul>
  //       </div>
  //     );
  //   }
  // }


  // const MyComponent = ({ text }) => (
  //   <div className="app-component">
  //     {text}
  //   </div>
  // );

  // class MyComponent extends React.Component {
  //   render() {
  //     if (!this.props.foo) {
  //       return null
  //     }
  //     return <div>{this.props.foo}</div>;
  //   }
  // }

  // class App extends React.Component {
  //   render() {
  //       return <div>Hello</div>
  //   }
  // }

  // ReactDOM.render(
  //   <MyComponent>hogehoge</MyComponent>,
  //   document.getElementById('react_test'),
  // );
});
