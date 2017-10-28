/**
 * アクセスしたデバイスの種類を返す
 * @return {string} デバイスのタイプ / smartphone / tablet / other
 */
// export const getDeviceType = () => {
//
//   const ua = navigator.userAgent;
//
//   let type = 'other';
//
//   if (ua.indexOf('iPhone') > 0 || ua.indexOf('iPod') > 0 || (ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0) || ua.indexOf('Windows Phone') > 0) {
//     type = 'smartphone';
//   } else if (ua.indexOf('iPad') > 0 || ua.indexOf('Android') > 0) {
//     type = 'tablet';
//   }
//
//   return type;
//
// };



/**
 * データベースから取得したDateTimeをフォーマットして返す
 * @param  {function(new: Date)} date 日付
 * @param  {string} formatA フォーマット
 * @return {string}         フォーマットされた日付
 */
export const formatDateTime = (date, formatA) => {
  // const date = new Date(dateTime);
  let format = formatA;

  // 第二引数なしならY/M/dで表示
  if (!format) format = 'Y/M/d';

  format = format.replace(/Y/g, date.getFullYear())
    .replace(/M/g, date.getMonth() + 1)
    .replace(/d/g, date.getDate());
  return format;

};



/**
* エスケープ解除
*/
export const unescape = str => (
  str.replace(/&#039;/g, "'")
  // return str.replace(/&amp;/g, '&')
  // .replace(/&lt;/g, '<')
  // .replace(/&gt;/g, '>')
  // .replace(/&quot;/g, '"')
  // .replace(/&amp;#039;/g, "BBB");
);
