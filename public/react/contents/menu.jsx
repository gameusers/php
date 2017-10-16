// --------------------------------------------------
//   新しくページを追加した場合は各メニューの内容を指定すること
// --------------------------------------------------

const menuObj = {


  // --------------------------------------------------
  //   ヘッダーメニュー（ヘッダー部分の紺色の帯内のリンク文字）
  //
  //   メニューのリンク文字をクリックして、移動する URL を指定します
  //   https://gameusers.org/urlDirectory1/urlDirectory2/activeUrlDirectory3
  //
  //   activeUrlDirectory3 は null で固定です
  //   メインメニューでクリックされたページを記憶しておくための要素
  // --------------------------------------------------

  headerMap: {

    app: {
      'share-buttons': {
        urlDirectory1: 'app',
        urlDirectory2: 'share-buttons',
        activeUrlDirectory3: null,
        text: 'シェアボタン'
      },
      pay: {
        urlDirectory1: 'app',
        urlDirectory2: 'pay',
        activeUrlDirectory3: null,
        text: '購入'
      }
    }

  },


  // --------------------------------------------------
  //   メインメニュー（PCの場合はサイドメニュー / モバイルの場合はドロワーメニュー）
  //
  //   メニューのリンク文字をクリックして、移動する URL を指定します
  //   https://gameusers.org/urlDirectory1/urlDirectory2/urlDirectory3
  //
  //   Material Icons はこちらから選択 / https://material.io/icons/
  //   <i class="material-icons">assignment_ind</i>
  //   ICON FONT のタグの中身を入力すること
  // --------------------------------------------------

  mainMap: {

    app: {
      'share-buttons': [
        {
          urlDirectory1: 'app',
          urlDirectory2: 'share-buttons',
          urlDirectory3: null,
          materialIcon: 'share',
          text: 'シェアボタン'
        },
      ],
      pay: [
        {
          urlDirectory1: 'app',
          urlDirectory2: 'pay',
          urlDirectory3: null,
          materialIcon: 'payment',
          text: '購入'
        },
        {
          urlDirectory1: 'app',
          urlDirectory2: 'pay',
          urlDirectory3: 'info',
          materialIcon: 'announcement',
          text: '特定商取引法に基づく表記'
        },
      ]
    }

  }

};

export default menuObj;
