// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';



export default class ContentsAppPayVendor extends React.Component {


  // --------------------------------------------------
  //   Lifecycle Methods
  // --------------------------------------------------

  // componentDidMount() {
  //   console.log('this.props = ', this.props);
  // }




  render() {

    return (
      <article className="content">

        <div className="panel panel-warning">
          <div className="panel-heading">特定商取引法に基づく表記</div>
          <div className="panel-body">
            <p>
              販売者についての情報を記載しています。
            </p>
          </div>

          <table className="table table-striped">
            <thead>
              <tr>
                <th />
                <th><strong>情報</strong></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">
                  事業者名
                </th>
                <td>Game Users</td>
              </tr>
              <tr>
                <th scope="row">
                  所在地
                </th>
                <td>〒540-0004 大阪市中央区玉造1丁目4番14号</td>
              </tr>
              <tr>
                <th scope="row">
                  連絡先
                </th>
                <td>ページ上部メニューのヘルプ ＞お問い合わせフォームよりご連絡ください。</td>
              </tr>
              <tr>
                <th scope="row">商品等の販売価格</th>
                <td>販売ページに記載しています。</td>
              </tr>
              <tr>
                <th scope="row">商品代金以外の付帯費用</th>
                <td>請求時に表示される代金のみです。</td>
              </tr>
              <tr>
                <th scope="row">代金の支払時期</th>
                <td>ご利用のお支払い方法によります。</td>
              </tr>
              <tr>
                <th scope="row">代金の支払方法</th>
                <td>デビットカード・クレジットカード（VISA / Mastercard /American Express‎）</td>
              </tr>
              <tr>
                <th scope="row">商品等の引き渡し時期</th>
                <td>商品の購入完了後、すぐに利用できます。</td>
              </tr>
              <tr>
                <th scope="row">返金の可否と条件</th>
                <td>購入者理由、動作不良（アップデート後の動作も含む）による返金は行えません。<br />それ以外の理由による返金をお求めの場合は、購入後7日以内にお問い合わせフォームよりご連絡ください。<br />期限を過ぎた場合は、いかなる理由であっても返金は行えませんのでご了承ください。</td>
              </tr>
            </tbody>
          </table>
        </div>

      </article>
    );

  }

}

ContentsAppPayVendor.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------



  // --------------------------------------------------
  //   関数
  // --------------------------------------------------


};

ContentsAppPayVendor.defaultProps = {

};
