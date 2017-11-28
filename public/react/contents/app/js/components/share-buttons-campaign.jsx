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



export default class ContentsAppShareButtonsCampaign extends React.Component {


  render() {

    return (
      <article className="content">

        <div className="panel panel-info">
          <div className="panel-heading">ブログでシェアボタンの紹介記事を書こう！</div>
          <div className="panel-body">

            <p>
              ブログでシェアボタンの紹介記事を書いてくれた方に、ビジネスプラン（￥3000 相当）の利用券を差し上げます。シェアボタンの使用感・レビューや、おすすめ記事などを書いていただけるとありがたいです。力作を求めているわけではありませんので、どなたでも気軽に参加していただけます。ただしほんの数行だけの紹介文など、記事自体があまりにも手抜きな場合はキャンペーンを適用できません。<br /><br />

              参加前に<strong>キャンペーン対象外のブログについて</strong>に目を通してください。<br /><br />

              いろいろな方にシェアボタンを知ってもらいたいので、ぜひともご参加よろしくお願い致します。
            </p>

          </div>
        </div>


        <div className="panel panel-danger">
          <div className="panel-heading">キャンペーン対象外のブログについて</div>
          <div className="panel-body">

            <ul className="app-share-buttons-campaign-list-1">
              <li>できたばかりで、他にほとんど記事がない。</li>
              <li>主な記事が広告の紹介になっている。</li>
              <li className="li-bottom">公序良俗に反する内容が記載されている。</li>
            </ul>

          </div>
        </div>


        <div className="panel panel-success">
          <div className="panel-heading">応募フォーム</div>
          <div className="panel-body">

            <div className="app-share-buttons-campaign-form-group-margin">
              <FormGroup
                controlId="app-share-button-campaign-form-blog-name"
                validationState={this.props.contentsAppShareButtonsCampaignFormBlogNameValidationState}
              >
                <ControlLabel>ブログの名前</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsCampaignFormBlogName}
                  onChange={e => this.props.funcContentsAppShareButtonsCampaignFormBlogName(e.target.value)}
                />
                <HelpBlock>ビジネスプランを適用するブログの名前を入力してください。</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-campaign-form-group-margin">
              <FormGroup
                controlId="app-share-button-campaign-form-blog-url"
                validationState={this.props.contentsAppShareButtonsCampaignFormBlogUrlValidationState}
              >
                <ControlLabel>ブログのURL</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsCampaignFormBlogUrl}
                  onChange={e => this.props.funcContentsAppShareButtonsCampaignFormBlogUrl(e.target.value)}
                />
                <HelpBlock>ビジネスプランを適用するブログのURLを入力してください。<br />例）https://gameusers.org/</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-campaign-form-group-margin">
              <FormGroup
                controlId="app-share-button-campaign-form-article-url"
                validationState={this.props.contentsAppShareButtonsCampaignFormArticleUrlValidationState}
              >
                <ControlLabel>記事のURL</ControlLabel>
                <FormControl
                  type="text"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsCampaignFormArticleUrl}
                  onChange={e => this.props.funcContentsAppShareButtonsCampaignFormArticleUrl(e.target.value)}
                />
                <HelpBlock>紹介文やレビューなど、ブログ記事のURLを入力してください。<br />例）https://gameusers.org/</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-campaign-form-group-margin">
              <FormGroup
                controlId="app-share-button-campaign-form-mail"
                validationState={this.props.contentsAppShareButtonsCampaignFormMailValidationState}
              >
                <ControlLabel>メールアドレス</ControlLabel>
                <FormControl
                  type="email"
                  maxLength="255"
                  value={this.props.contentsAppShareButtonsCampaignFormMail}
                  onChange={e => this.props.funcContentsAppShareButtonsCampaignFormMail(e.target.value)}
                />
                <HelpBlock>mail@gameusers.org から連絡をさせていただきますので、ドメイン指定受信をされている方はメールを受け取れるように設定しておいてください。</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-campaign-form-group-margin">
              <FormGroup
                controlId="app-share-button-campaign-form-comment"
                validationState={this.props.contentsAppShareButtonsCampaignFormCommentValidationState}
              >
                <ControlLabel>コメント</ControlLabel>
                <FormControl
                  componentClass="textarea"
                  rows="5"
                  maxLength="2000"
                  value={this.props.contentsAppShareButtonsCampaignFormComment}
                  onChange={e => this.props.funcContentsAppShareButtonsCampaignFormComment(e.target.value)}
                />
                <HelpBlock>連絡事項などがある場合はこちらに記載してください。</HelpBlock>
              </FormGroup>
            </div>

            <div className="app-share-buttons-campaign-form-group-margin">
              <FormGroup
                controlId="app-share-button-campaign-form-agreement"
                validationState={this.props.contentsAppShareButtonsCampaignFormAgreementValidationState}
              >
                <Checkbox
                  checked={this.props.contentsAppShareButtonsCampaignFormAgreement}
                  onChange={e => this.props.funcContentsAppShareButtonsCampaignFormAgreement(e.target.checked)}
                >
                  「キャンペーン対象外のブログについて」を読んで了承しました
                </Checkbox>
              </FormGroup>
            </div>

            <Button
              bsStyle="success"
              className="ladda-button"
              data-style="slide-right"
              data-size="s"
              onClick={e => this.props.funcSendMailCampaign(this.props.stateModel, this.props.stateAppModel, e.currentTarget)}
            >
              <span className="ladda-label">送信する</span>
            </Button>

          </div>
        </div>

      </article>
    );

  }

}

ContentsAppShareButtonsCampaign.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,
  stateAppModel: PropTypes.instanceOf(ModelApp).isRequired,

  contentsAppShareButtonsCampaignFormBlogName: PropTypes.string.isRequired,
  contentsAppShareButtonsCampaignFormBlogUrl: PropTypes.string.isRequired,
  contentsAppShareButtonsCampaignFormArticleUrl: PropTypes.string.isRequired,
  contentsAppShareButtonsCampaignFormMail: PropTypes.string.isRequired,
  contentsAppShareButtonsCampaignFormComment: PropTypes.string.isRequired,
  contentsAppShareButtonsCampaignFormAgreement: PropTypes.bool.isRequired,

  contentsAppShareButtonsCampaignFormBlogNameValidationState: PropTypes.string,
  contentsAppShareButtonsCampaignFormBlogUrlValidationState: PropTypes.string,
  contentsAppShareButtonsCampaignFormArticleUrlValidationState: PropTypes.string,
  contentsAppShareButtonsCampaignFormMailValidationState: PropTypes.string,
  contentsAppShareButtonsCampaignFormCommentValidationState: PropTypes.string,
  contentsAppShareButtonsCampaignFormAgreementValidationState: PropTypes.string,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcContentsAppShareButtonsCampaignFormBlogName: PropTypes.func.isRequired,
  funcContentsAppShareButtonsCampaignFormBlogUrl: PropTypes.func.isRequired,
  funcContentsAppShareButtonsCampaignFormArticleUrl: PropTypes.func.isRequired,
  funcContentsAppShareButtonsCampaignFormMail: PropTypes.func.isRequired,
  funcContentsAppShareButtonsCampaignFormComment: PropTypes.func.isRequired,
  funcContentsAppShareButtonsCampaignFormAgreement: PropTypes.func.isRequired,

  funcSendMailCampaign: PropTypes.func.isRequired,


};

ContentsAppShareButtonsCampaign.defaultProps = {

  contentsAppShareButtonsCampaignFormBlogNameValidationState: null,
  contentsAppShareButtonsCampaignFormBlogUrlValidationState: null,
  contentsAppShareButtonsCampaignFormArticleUrlValidationState: null,
  contentsAppShareButtonsCampaignFormMailValidationState: null,
  contentsAppShareButtonsCampaignFormCommentValidationState: null,
  contentsAppShareButtonsCampaignFormAgreementValidationState: null,

};
