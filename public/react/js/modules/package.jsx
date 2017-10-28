// --------------------------------------------------
//   使用パッケージの補足・機能追加用関数
// --------------------------------------------------

// --------------------------------------------------
//   Import
// --------------------------------------------------

import { Seq } from 'immutable';



/**
 * Immutable fromJSOrdered
 * Immutable.js ではオブジェクトの並び順を維持したまま
 * Immutable.js の Map型に変換する関数がないため、オリジナルで作成
 * @param  {object|array} data オブジェクトか配列
 * @return {OrderedMap}        順番を維持したMap
 */
export const fromJSOrdered = (data) => {

  if (typeof data !== 'object' || data === null) {
    return data;
  }

  if (Array.isArray(data)) {
    return Seq(data).map(fromJSOrdered).toList();
  }

  return Seq(data).map(fromJSOrdered).toOrderedMap();

};

export default fromJSOrdered;
