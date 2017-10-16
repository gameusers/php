// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import { Switch } from 'react-router-dom';


// ---------------------------------------------
//   - 新たにページを追加する場合は
//   ここで追加ページのコンポーネントを import する
// ---------------------------------------------

import ContainerContentsApp from './app/js/containers/app';



/**
 * 新しいページを追加する場合は、ここでコンポーネントを追加する
 * @param {object} props props
 */
const Contents = props => (
  <Switch>
    <ContainerContentsApp {...props} />
  </Switch>
);

export default Contents;
