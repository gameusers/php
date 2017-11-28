// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import FormGroup from 'alias-node-modules/react-bootstrap/lib/FormGroup';
import ControlLabel from 'alias-node-modules/react-bootstrap/lib/ControlLabel';
import FormControl from 'alias-node-modules/react-bootstrap/lib/FormControl';
import Button from 'alias-node-modules/react-bootstrap/lib/Button';
import Checkbox from 'alias-node-modules/react-bootstrap/lib/Checkbox';
import HelpBlock from 'alias-node-modules/react-bootstrap/lib/HelpBlock';

import { Model } from '../../../../js/models/model';
import { ModelApp } from '../models/model';

import '../../css/share-buttons.css';



export default class ContentsAppShareButtonsRecruitment extends React.Component {


  render() {

    return (
      <article className="content">

        <div className="panel panel-info">
          <div className="panel-heading">テーマ募集！</div>
          <div className="panel-body">

            <p>
              オリジナルのテーマを提供してくれる方を募集しています。テーマとして採用された方には、ビジネスプラン（￥3000 相当）の利用券を差し上げます。すべて自作の画像（あなたが権利を保有している）を利用したテーマを作成して、編集タブからダウンロードした game-users-share-buttons.zip ファイルを以下のフォームに添付して送ってください。<br /><br />

              <strong>提供用のテーマを作成する場合は、一時的にビジネスプランを利用してください（プランを購入する必要はありません）。</strong>プランタブでビジネスプランに変更すると、黒猫の画像が編集できるようになりますので、自作のアイコンに変更したり、自サイトへのリンクを貼ることができます。作成したテーマを利用する人が出てくると、ユーザーの各ブログ記事からあなたのサイトへのリンクが貼られることになりますので、<strong>宣伝効果も非常に大きいです！</strong><br /><br />

              絵が描けたり、デザインが行える方は、ぜひともご参加よろしくお願いします。<br /><br />

              ※ テーマに利用する画像を作成する前に、編集タブ &gt; シェアボタン新規作成ボタン &gt; 画像アップロードフォームの下にある「モバイル環境で綺麗に表示するには？」を必ずチェックしてください。
            </p>

          </div>
        </div>


        <div className="panel panel-danger">
          <div className="panel-heading">テーマ応募時の了承事項</div>
          <div className="panel-body">

            <ol className="app-share-buttons-recruitment-list-1">
              <li>シェアボタンに利用する画像は自分で作成し、すべての権利を所有しているものを利用してください。権利に関する問題が起こった場合、Game Users はその責を負えません。</li>
              <li>テーマの提供後もテーマに使われている画像の権利はその作者が所有します。</li>
              <li>テーマを利用するユーザーが金銭の支払いを求められることなく、無期限に使い続けられることを約束してください。</li>
              <li>Game Users がシェアボタン紹介用にテーマを利用することを了承してください。例えばこのような画像に含めて紹介・宣伝時に利用させてもらいます。<a href="https://gameusers.org/react/img/github/banner.jpg" target="_blank" rel="noopener noreferrer">https://gameusers.org/react/img/github/banner.jpg</a></li>
              <li>一度、公開されたテーマは Game Users Share Buttons のページから削除することはできますが、テーマを利用している各ユーザーのウェブサイトから削除することはできません。</li>
              <li>シェアボタンの画像、設定値はユーザーが自由に変更することができます。また有料プランを利用しているユーザーは、フリー画像を非表示にする、違う画像に置き換える、リンクを違うアドレスに変更することができます。</li>
              <li className="li-bottom">提供していただいたテーマに表示上の問題があった場合は、Game Users 側で設定の調整を行うことがあります。</li>
            </ol>

          </div>
        </div>


        <div className="panel panel-success">
          <div className="panel-heading">応募フォーム</div>
          <div className="panel-body">

            <div className="app-share-buttons-recruitment-form-group-margin">
              <FormGroup
                controlId="app-share-button-recruitment-form-author-name"
                validationState={this.props.contentsAppShareButtonsRecruitmentFormAuthorNameValidationState}
              >
                <ControlLabel>作者名</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsRecruitmentFormAuthorName}
                  onChange={e => this.props.funcContentsAppShareButtonsRecruitmentFormAuthorName(e.target.value)}
                />
                <HelpBlock>テーマ一覧に掲載された際に Author の項目に表示されます。ハンドルネームやサイト名など、公開してもいい名前を入力してください。</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-recruitment-form-group-margin">
              <FormGroup
                controlId="app-share-button-recruitment-form-author-url"
                validationState={this.props.contentsAppShareButtonsRecruitmentFormAuthorUrlValidationState}
              >
                <ControlLabel>作者ウェブサイトのURL</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsRecruitmentFormAuthorUrl}
                  onChange={e => this.props.funcContentsAppShareButtonsRecruitmentFormAuthorUrl(e.target.value)}
                />
                <HelpBlock>テーマ一覧に掲載された際に Web Site の項目に表示されます。公開してもいいウェブサイトのURLを入力してください。公開したいウェブサイトがない場合は空欄にしてください。<br />例）https://gameusers.org/</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-recruitment-form-group-margin">
              <FormGroup
                controlId="app-share-button-recruitment-form-file"
                validationState={this.props.contentsAppShareButtonsRecruitmentFormFileValidationState}
              >
                <ControlLabel>テーマアップロード</ControlLabel>
                <FormControl
                  type="file"
                  onChange={e => this.props.funcContentsAppShareButtonsRecruitmentFormFile(e.target.files[0])}
                />
                <HelpBlock>編集タブでダウンロードしたZIPファイルを添付してください。作成したシェアボタンをダウンロードする際に、応募するテーマだけをチェックしてダウンロードしてください。</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-recruitment-form-group-margin">
              <FormGroup
                controlId="app-share-button-recruitment-form-mail"
                validationState={this.props.contentsAppShareButtonsRecruitmentFormMailValidationState}
              >
                <ControlLabel>メールアドレス（非公開）</ControlLabel>
                <FormControl
                  type="email"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsRecruitmentFormMail}
                  onChange={e => this.props.funcContentsAppShareButtonsRecruitmentFormMail(e.target.value)}
                />
                <HelpBlock>mail@gameusers.org から連絡をさせていただきますので、ドメイン指定受信をされている方はメールを受け取れるように設定しておいてください。</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-recruitment-form-group-margin">
              <FormGroup
                controlId="app-share-button-recruitment-form-website-name"
                validationState={this.props.contentsAppShareButtonsRecruitmentFormWebSiteNameValidationState}
              >
                <ControlLabel>ウェブサイトの名前（非公開）</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsRecruitmentFormWebSiteName}
                  onChange={e => this.props.funcContentsAppShareButtonsRecruitmentFormWebSiteName(e.target.value)}
                />
                <HelpBlock>テーマが採用された際にビジネスプランを適用するウェブサイトの名前を入力してください。基本的にご自分が管理・所有しているサイトに適用してください。他の方のサイトに適用していただくこともできますが、その場合はご自分と関係が深い方のウェブサイトを指定してください。シェアボタンやビジネスプランについてはテーマ提供者様から先方にお伝えください。</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-recruitment-form-group-margin">
              <FormGroup
                controlId="app-share-button-recruitment-form-website-url"
                validationState={this.props.contentsAppShareButtonsRecruitmentFormWebSiteUrlValidationState}
              >
                <ControlLabel>ウェブサイトのURL（非公開）</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsRecruitmentFormWebSiteUrl}
                  onChange={e => this.props.funcContentsAppShareButtonsRecruitmentFormWebSiteUrl(e.target.value)}
                />
                <HelpBlock>テーマが採用された際にビジネスプランを適用するウェブサイトのURLを入力してください。ピジネスプランを適用したいウェブサイトがない場合は、「ウェブサイトの名前フォーム」と一緒に空欄にしてください。後日、ウェブサイトが用意できたときに連絡していただければ、そちらに適用させていただきます。<br />例）https://gameusers.org/</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-recruitment-form-group-margin">
              <FormGroup
                controlId="app-share-button-recruitment-form-comment"
                validationState={this.props.contentsAppShareButtonsRecruitmentFormCommentValidationState}
              >
                <ControlLabel>コメント（非公開）</ControlLabel>
                <FormControl
                  componentClass="textarea"
                  rows="5"
                  maxLength="2000"
                  value={this.props.contentsAppShareButtonsRecruitmentFormComment}
                  onChange={e => this.props.funcContentsAppShareButtonsRecruitmentFormComment(e.target.value)}
                />
                <HelpBlock>連絡事項などがある場合はこちらに記載してください。</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-recruitment-form-group-margin">
              <FormGroup
                controlId="app-share-button-recruitment-form-agreement"
                validationState={this.props.contentsAppShareButtonsRecruitmentFormAgreementValidationState}
              >
                <Checkbox
                  checked={this.props.contentsAppShareButtonsRecruitmentFormAgreement}
                  onChange={e => this.props.funcContentsAppShareButtonsRecruitmentFormAgreement(e.target.checked)}
                >
                  「テーマ応募時の了承事項」を読んで了承しました
                </Checkbox>
              </FormGroup>
            </div>

            <Button
              bsStyle="success"
              className="ladda-button"
              data-style="slide-right"
              data-size="s"
              onClick={e => this.props.funcSendMailRecruitmentTheme(this.props.stateModel, this.props.stateAppModel, e.currentTarget)}
            >
              <span className="ladda-label">送信する</span>
            </Button>

          </div>
        </div>

      </article>
    );

  }

}

ContentsAppShareButtonsRecruitment.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,
  stateAppModel: PropTypes.instanceOf(ModelApp).isRequired,

  contentsAppShareButtonsRecruitmentFormAuthorName: PropTypes.string.isRequired,
  contentsAppShareButtonsRecruitmentFormAuthorUrl: PropTypes.string.isRequired,
  contentsAppShareButtonsRecruitmentFormMail: PropTypes.string.isRequired,
  contentsAppShareButtonsRecruitmentFormWebSiteName: PropTypes.string.isRequired,
  contentsAppShareButtonsRecruitmentFormWebSiteUrl: PropTypes.string.isRequired,
  contentsAppShareButtonsRecruitmentFormComment: PropTypes.string.isRequired,
  contentsAppShareButtonsRecruitmentFormAgreement: PropTypes.bool.isRequired,

  contentsAppShareButtonsRecruitmentFormAuthorNameValidationState: PropTypes.string,
  contentsAppShareButtonsRecruitmentFormAuthorUrlValidationState: PropTypes.string,
  contentsAppShareButtonsRecruitmentFormFileValidationState: PropTypes.string,
  contentsAppShareButtonsRecruitmentFormMailValidationState: PropTypes.string,
  contentsAppShareButtonsRecruitmentFormWebSiteNameValidationState: PropTypes.string,
  contentsAppShareButtonsRecruitmentFormWebSiteUrlValidationState: PropTypes.string,
  contentsAppShareButtonsRecruitmentFormCommentValidationState: PropTypes.string,
  contentsAppShareButtonsRecruitmentFormAgreementValidationState: PropTypes.string,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcContentsAppShareButtonsRecruitmentFormAuthorName: PropTypes.func.isRequired,
  funcContentsAppShareButtonsRecruitmentFormAuthorUrl: PropTypes.func.isRequired,
  funcContentsAppShareButtonsRecruitmentFormFile: PropTypes.func.isRequired,
  funcContentsAppShareButtonsRecruitmentFormMail: PropTypes.func.isRequired,
  funcContentsAppShareButtonsRecruitmentFormWebSiteName: PropTypes.func.isRequired,
  funcContentsAppShareButtonsRecruitmentFormWebSiteUrl: PropTypes.func.isRequired,
  funcContentsAppShareButtonsRecruitmentFormComment: PropTypes.func.isRequired,
  funcContentsAppShareButtonsRecruitmentFormAgreement: PropTypes.func.isRequired,

  funcSendMailRecruitmentTheme: PropTypes.func.isRequired,


};

ContentsAppShareButtonsRecruitment.defaultProps = {

  contentsAppShareButtonsRecruitmentFormAuthorNameValidationState: null,
  contentsAppShareButtonsRecruitmentFormAuthorUrlValidationState: null,
  contentsAppShareButtonsRecruitmentFormFileValidationState: null,
  contentsAppShareButtonsRecruitmentFormMailValidationState: null,
  contentsAppShareButtonsRecruitmentFormWebSiteNameValidationState: null,
  contentsAppShareButtonsRecruitmentFormWebSiteUrlValidationState: null,
  contentsAppShareButtonsRecruitmentFormCommentValidationState: null,
  contentsAppShareButtonsRecruitmentFormAgreementValidationState: null,

};
