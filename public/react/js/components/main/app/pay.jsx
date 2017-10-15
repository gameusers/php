// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { Button, FormGroup, ControlLabel, FormControl, Checkbox, HelpBlock } from 'react-bootstrap';
import StripeCheckout from 'react-stripe-checkout';

import { Model } from '../../../models/model';

import '../../../../css/main/app/pay.css';



export default class MainAppPay extends React.Component {


  // --------------------------------------------------
  //   Lifecycle Methods
  // --------------------------------------------------

  // componentDidMount() {
  //   optionOutput();
  // }


  // --------------------------------------------------
  //   Validation State
  //   フォームの入力値ガ正しい場合は success（緑色）
  //   間違っている場合は error（赤色）
  // --------------------------------------------------

  validationStateShareButtonsWebSiteName() {
    let state = 'error';
    if (this.props.appPayShareButtonsWebSiteName !== '' && this.props.appPayShareButtonsWebSiteName.length <= 100) {
      state = 'success';
    }
    // console.log('this.props.appPayShareButtonsWebSiteName.length = ', this.props.appPayShareButtonsWebSiteName.length);
    return state;
  }

  validationStateShareButtonsWebSiteUrl() {
    let state = 'error';
    if (this.props.appPayShareButtonsWebSiteUrl.match(/^(https?)(:\/\/[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+)$/)) {
      state = 'success';
    }
    return state;
  }

  validationStateShareButtonsAgreement() {
    let state = 'error';
    if (this.props.appPayShareButtonsAgreement) {
      state = 'success';
    }
    return state;
  }



  render() {

    return (
      <article className="content">

        <div className="panel panel-success">
          <div className="panel-heading">プランについて</div>
          <div className="panel-body">
            <p>
              Game Users Share Buttons は商用・非商用、どちらの用途でもフリープランで利用することができます。<br /><br />

              フリープランではシェアボタンの右端にフリー画像（黒いネコ / テーマによって変わります）が表示され、Game Users Share Buttons 公式サイトへのリンク（こちらもテーマによって変わります）が自動的に貼られます。有料プランではそのフリー画像を編集する権利を得ることができます。<br /><br />

              <strong>1.</strong> 企業が運営するサイトで余計なリンクは表示したくない<br />
              <strong>2.</strong> 完全オリジナルのシェアボタンを作る予定でフリー画像も他の画像に差し替えたい<br />
              <strong>3.</strong> フリー画像がブログのデザインに馴染まないので非表示にしたい<br /><br />

              有料プランは上記のようなケースで利用できます。
            </p>
          </div>

          <table className="table table-striped" style={{ textAlign: 'center' }}>
            <thead>
              <tr>
                <th />
                <th style={{ textAlign: 'center' }}><strong>フリー</strong></th>
                <th style={{ textAlign: 'center' }}><strong>プレミアム</strong></th>
                <th style={{ textAlign: 'center' }}><strong>ビジネス</strong></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row" style={{ verticalAlign: 'middle' }}>
                  <img src={`${this.props.urlBase}dev/blog/wp-content/plugins/gameusers-share-buttons/img/free.png`} width="30" height="33" alt="フリー画像" /> 非表示
                </th>
                <td style={{ verticalAlign: 'middle' }}>-</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
              </tr>
              <tr>
                <th scope="row" style={{ verticalAlign: 'middle' }}>
                  <img src={`${this.props.urlBase}dev/blog/wp-content/plugins/gameusers-share-buttons/img/free.png`} width="30" height="33" alt="フリー画像" /> 画像入れ替え
                </th>
                <td style={{ verticalAlign: 'middle' }}>-</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
              </tr>
              <tr>
                <th scope="row" style={{ verticalAlign: 'middle' }}>
                  <img src={`${this.props.urlBase}dev/blog/wp-content/plugins/gameusers-share-buttons/img/free.png`} width="30" height="33" alt="フリー画像" /> URL変更
                </th>
                <td style={{ verticalAlign: 'middle' }}>-</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
              </tr>
              <tr>
                <th scope="row">商用利用</th>
                <td>○</td>
                <td>-</td>
                <td>○</td>
              </tr>
              <tr>
                <th scope="row">価格</th>
                <td>0 円</td>
                <td>1000 円</td>
                <td>3000 円</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div className="panel panel-danger">
          <div className="panel-heading">有料プランの注意事項</div>
          <div className="panel-body">
            <p>
              <strong>1.</strong> 有料プランはシェアボタンの動作保証をするものではありません。ご利用の環境で正常に動作するかどうかは、フリープランを利用して確認してください。<br /><br />
              <strong>2.</strong> シェアボタンに使われている画像の権利はそれぞれの作者が所有しています。それらの権利を買い取るものではありません。<br /><br />
              <strong>3.</strong> 有料プランの権利は購入者（個人・企業）に属し、その権利を譲渡したり売買することはできません。<br /><br />
              <strong>4.</strong> 有料プランの権利は購入時に入力するウェブサイトに属します。他のウェブサイトに移行・転用することはできません。<br /><br />
              <strong>5.</strong> 有料プランの権利を購入者以外の方に利用させることはできません。禁止例）ブログサービスを運営して、各ユーザーに有料プランの権利を利用させる。<br /><br />
              <strong>6.</strong> 非商用なサイトで利用する場合はプレミアムプラン、規模の大小に関わらず収益を得るチャンスがあるサイト（広告を貼っているブログなど）で利用する場合はビジネスプランを申し込んでください。<br /><br />
              <strong>7.</strong> 有料プランの権利は一度購入すると購入したサイトが続く限り、購入者が永続的に所有します。月間契約や年間契約ではありません。<br /><br />
              <strong>8.</strong> お支払いの決済は実績のある <a href="https://stripe.com/" target="_blank" rel="noopener noreferrer">Stripe（アメリカの大手オンライン決済企業）</a> を利用して行っており、入力した情報はすべて暗号化されて送信されるので安全です。
            </p>
          </div>
        </div>

        <div className="panel panel-info">
          <div className="panel-heading">有料プランに申し込む</div>
          <div className="panel-body">

            <div className="pay-form-group-margin">
              <FormGroup controlId="free-upload-image-url" bsSize="sm" validationState={this.validationStateShareButtonsWebSiteName()}>
                <ControlLabel>ウェブサイトの名前</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="100"
                  value={this.props.appPayShareButtonsWebSiteName}
                  onChange={e => this.props.funcAppPayShareButtonsWebSiteName(e.target.value)}
                />
                <HelpBlock>有料プランを申し込むウェブサイトの名前を入力してください。<br />例）Game Users</HelpBlock>
              </FormGroup>
            </div>

            <div className="pay-form-group-margin">
              <FormGroup controlId="free-upload-image-url" bsSize="sm" validationState={this.validationStateShareButtonsWebSiteUrl()}>
                <ControlLabel>ウェブサイトのURL</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.appPayShareButtonsWebSiteUrl}
                  onChange={e => this.props.funcAppPayShareButtonsWebSiteUrl(e.target.value)}
                />
                <HelpBlock>有料プランを申し込むウェブサイトのURL（トップページ）を入力してください。<br />例）https://gameusers.org/</HelpBlock>
              </FormGroup>
            </div>

            <div className="pay-form-group-margin">
              <FormGroup controlId="share-button" validationState={this.validationStateShareButtonsAgreement()}>
                <Checkbox
                  checked={this.props.appPayShareButtonsAgreement}
                  onChange={e => this.props.funcAppPayShareButtonsAgreement(e.target.checked)}
                >
                  「有料プランの注意事項」を読んで了承しました
                </Checkbox>
              </FormGroup>
            </div>

            <StripeCheckout
              token={e => this.props.funcInsertShareButtonsPay(
                this.props.stateModel,
                e
              )}
              stripeKey="pk_test_njyv70ZdCeEbK0nHEcF8YqDz"
              name="Game Users Share Buttons"
              description="プレミアムプラン申し込み"
              image={`${this.props.urlBase}dev/blog/wp-content/plugins/gameusers-share-buttons/img/free.png`}
              zipCode
              locale="auto"
              amount={1000}
              currency="JPY"
            >
              <Button className="btn btn-success btn-sm">プレミアムプランに申し込む</Button>
            </StripeCheckout>

          </div>
        </div>

      </article>
    );

  }

}

MainAppPay.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,

  urlBase: PropTypes.string.isRequired,

  appPayShareButtonsWebSiteName: PropTypes.string.isRequired,
  appPayShareButtonsWebSiteUrl: PropTypes.string.isRequired,
  appPayShareButtonsAgreement: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcAppPayShareButtonsWebSiteName: PropTypes.func.isRequired,
  funcAppPayShareButtonsWebSiteUrl: PropTypes.func.isRequired,
  funcAppPayShareButtonsAgreement: PropTypes.func.isRequired,
  funcInsertShareButtonsPay: PropTypes.func.isRequired,


};

MainAppPay.defaultProps = {

  // urlDirectory1: null,
  // urlDirectory2: null,
  // urlDirectory3: null,

};
