// --------------------------------------------------
//   API 関数
// --------------------------------------------------



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
