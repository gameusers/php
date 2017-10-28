// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { Glyphicon, Button, FormGroup, ControlLabel, FormControl, Checkbox, HelpBlock, Alert } from 'react-bootstrap';
import StripeCheckout from 'react-stripe-checkout';

import { Model } from '../../../../js/models/model';
import { ModelApp } from '../models/model';

import '../../css/pay.css';



export default class ContentsAppPay extends React.Component {


  // --------------------------------------------------
  //   Lifecycle Methods
  // --------------------------------------------------

  // componentDidMount() {
  //   console.log('this.props = ', this.props);
  // }


  buttonDisabled() {

    let disabled = false;

    if (this.props.contentsAppPayFormShareButtonsWebSiteNameError || this.props.contentsAppPayFormShareButtonsWebSiteUrlError || this.props.contentsAppPayFormShareButtonsAgreementError) {
      disabled = true;
    }

    return disabled;

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
                  <img src={`${this.props.urlBase}react/contents/app/img/free.png`} width="30" height="33" alt="フリー画像" /> 非表示
                </th>
                <td style={{ verticalAlign: 'middle' }}>-</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
              </tr>
              <tr>
                <th scope="row" style={{ verticalAlign: 'middle' }}>
                  <img src={`${this.props.urlBase}react/contents/app/img/free.png`} width="30" height="33" alt="フリー画像" /> 画像入れ替え
                </th>
                <td style={{ verticalAlign: 'middle' }}>-</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
                <td style={{ verticalAlign: 'middle' }}>○</td>
              </tr>
              <tr>
                <th scope="row" style={{ verticalAlign: 'middle' }}>
                  <img src={`${this.props.urlBase}react/contents/app/img/free.png`} width="30" height="33" alt="フリー画像" /> URL変更
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
              <strong>7.</strong> 有料プランは WordPress のプラグイン、公式サイトの両方で利用することができます。<br /><br />
              <strong>8.</strong> 有料プランの権利は一度購入すると購入したサイトが続く限り、購入者が永続的に所有します。月間契約や年間契約ではありません。<br /><br />
              <strong>9.</strong> お支払いの決済は実績のある <a href="https://stripe.com/" target="_blank" rel="noopener noreferrer">Stripe（アメリカの大手オンライン決済企業）</a> を利用して行っており、入力した情報はすべて暗号化されて送信されます。
            </p>
          </div>
        </div>



        <div className="panel panel-info">
          <div className="panel-heading">有料プランに申し込む</div>
          <div className="panel-body">

            <div className="app-pay-form-group-margin">
              <FormGroup controlId="free-upload-image-url" bsSize="sm" validationState={this.props.contentsAppPayFormShareButtonsWebSiteNameValidationState}>
                <ControlLabel>ウェブサイトの名前</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="100"
                  value={this.props.contentsAppPayFormShareButtonsWebSiteName}
                  onChange={e => this.props.funcContentsAppPayFormShareButtonsWebSiteName(e.target.value)}
                />
                <HelpBlock>有料プランを申し込むウェブサイトの名前を入力してください。<br />例）Game Users</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-pay-form-group-margin">
              <FormGroup controlId="free-upload-image-url" bsSize="sm" validationState={this.props.contentsAppPayFormShareButtonsWebSiteUrlValidationState}>
                <ControlLabel>ウェブサイトのURL</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.contentsAppPayFormShareButtonsWebSiteUrl}
                  onChange={e => this.props.funcContentsAppPayFormShareButtonsWebSiteUrl(e.target.value)}
                />
                <HelpBlock>有料プランを申し込むウェブサイトのURL（トップページ）を入力してください。<br />例）https://gameusers.org/</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-pay-form-group-margin-bottom">
              <FormGroup controlId="share-button" validationState={this.props.contentsAppPayFormShareButtonsAgreementValidationState}>
                <Checkbox
                  checked={this.props.contentsAppPayFormShareButtonsAgreement}
                  onChange={e => this.props.funcContentsAppPayFormShareButtonsAgreement(e.target.checked)}
                >
                  「有料プランの注意事項」を読んで了承しました
                </Checkbox>
              </FormGroup>
            </div>

            <StripeCheckout
              token={e => this.props.funcInsertShareButtonsPaidPlan(
                this.props.stateModel,
                this.props.stateAppModel,
                'premium',
                e
              )}
              stripeKey={this.props.stripePublishableKey}
              name="Game Users Share Buttons"
              description="プレミアムプラン申し込み"
              image={`${this.props.urlBase}react/contents/app/img/free.png`}
              zipCode
              locale="auto"
              amount={1000}
              currency="JPY"
            >
              <Button className="btn btn-info btn-sm app-pay-form-stripe-button-margin" disabled={this.buttonDisabled()}>
                <Glyphicon glyph="leaf" /> プレミアムプランに申し込む
              </Button>
            </StripeCheckout>

            <StripeCheckout
              token={e => this.props.funcInsertShareButtonsPaidPlan(
                this.props.stateModel,
                this.props.stateAppModel,
                'business',
                e
              )}
              stripeKey={this.props.stripePublishableKey}
              name="Game Users Share Buttons"
              description="ビジネスプラン申し込み"
              image={`${this.props.urlBase}react/contents/app/img/free.png`}
              zipCode
              locale="auto"
              amount={3000}
              currency="JPY"
            >
              <Button className="btn btn-success btn-sm app-pay-form-stripe-button-margin" disabled={this.buttonDisabled()}>
                <Glyphicon glyph="euro" /> ビジネスプランに申し込む
              </Button>
            </StripeCheckout>


            {this.props.contentsAppPayFormShareButtonsPurchased &&
              <Alert bsStyle="success" className="app-pay-form-alert">
                <strong>有料プランが利用可能です：</strong> ありがとうございます。有料プランへのお申し込みが完了しました。WordPressのプラグイン、または公式ページでシェアボタンを作成する際に有料プランを選択できます。プランタブでお申し込みのプランを選択してからシェアボタンを作成してみてください。<br /><br />今後とも Game Users Share Buttons をよろしくお願い致します。
              </Alert>
            }

          </div>
        </div>

      </article>
    );

  }

}

ContentsAppPay.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,
  stateAppModel: PropTypes.instanceOf(ModelApp).isRequired,

  urlBase: PropTypes.string.isRequired,
  stripePublishableKey: PropTypes.string.isRequired,

  contentsAppPayFormShareButtonsWebSiteName: PropTypes.string.isRequired,
  contentsAppPayFormShareButtonsWebSiteNameValidationState: PropTypes.string.isRequired,
  contentsAppPayFormShareButtonsWebSiteNameError: PropTypes.bool.isRequired,

  contentsAppPayFormShareButtonsWebSiteUrl: PropTypes.string.isRequired,
  contentsAppPayFormShareButtonsWebSiteUrlValidationState: PropTypes.string.isRequired,
  contentsAppPayFormShareButtonsWebSiteUrlError: PropTypes.bool.isRequired,

  contentsAppPayFormShareButtonsAgreement: PropTypes.bool.isRequired,
  contentsAppPayFormShareButtonsAgreementValidationState: PropTypes.string.isRequired,
  contentsAppPayFormShareButtonsAgreementError: PropTypes.bool.isRequired,

  contentsAppPayFormShareButtonsPurchased: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcContentsAppPayFormShareButtonsWebSiteName: PropTypes.func.isRequired,
  funcContentsAppPayFormShareButtonsWebSiteUrl: PropTypes.func.isRequired,
  funcContentsAppPayFormShareButtonsAgreement: PropTypes.func.isRequired,
  funcInsertShareButtonsPaidPlan: PropTypes.func.isRequired,


};

ContentsAppPay.defaultProps = {

};
