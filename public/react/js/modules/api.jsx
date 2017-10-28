// --------------------------------------------------
//   API 関数
// --------------------------------------------------



/**
 * Promise / APIにアクセスしてJSONで取得したオブジェクトを返す
 * @param  {string}   urlBase  基本のURL
 * @param  {FormData} formData new FormData()で作成したインスタンス
 * @return {object}            オブジェクト
 */
// export const promiseReactJsonPost = (urlBase, formData) => new Promise((resolve) => {
//
//   fetch(`${urlBase}api/react.json`, {
//     method: 'POST',
//     credentials: 'include',
//     mode: 'same-origin',
//     body: formData
//   })
//     .then((response) => {
//       if (response.ok) {
//         return response.json();
//       }
//     })
//     .then((jsonObj) => {
//       resolve(jsonObj);
//     });
//
// });




/**
 * APIにアクセスしてJSONで取得したオブジェクトを返す
 * @param  {string} url         API の URL
 * @param  {string} method      POST / GET
 * @param  {string} credentials クッキーを送信するか - omit：決してクッキーを送信しない / same-origin：URL が呼び出し元のスクリプトと同一オリジンだった場合のみ、クッキーを送信する / include：クロスオリジンの呼び出しであっても、常にクッキーを送信する
 * @param  {string} mode        モード - cors / no-cors / same-origin
 * @param  {FormData} formData  new FormData で作成したオブジェクト
 * @return {Promise}            Promise オブジェクト
 */
export const fetchApi = (url, method, credentials, mode, formData) => new Promise((resolve) => {

  const optionObj = {
    method,
    credentials,
    mode
  };

  if (method === 'POST') {
    optionObj.body = formData;
  }


  fetch(url, optionObj)
    .then((response) => {
      if (response.ok) {
        return response.json();
      }
    })
    .then((jsonObj) => {
      resolve(jsonObj);
    });

});



export default fetchApi;
