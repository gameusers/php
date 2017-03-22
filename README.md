# [Game Users](https://gameusers.org/)
![Game Users Banner](https://gameusers.org/assets/img/social/ogp_image.jpg)

ゲームユーザーズはゲームユーザーのためのコミュニティサイトです。現在、開発者を広く募集しています。初心者でもWebを触ったことのないプログラマーでもOK！ゲーム関係でこういう機能があったらいいのにな、というアイデアがあれば、ぜひこの場所を使って実装してください。

ゲームを好きな開発者がなんでも作れる砂場のような場所にしたい！

## 必要な開発環境

PHP 7.1以上  
Apache 2.4以上  
MySQL 5.4以上 または MariaDB 10.1以上

PHPは7以上でないと正常に動作しません。Windows版のXAMPPで開発していますので、開発環境を簡単に用意したい場合は、XAMPPのPHP7バージョンをインストールしてもらえば同じように動作すると思います。

[XAMPP](https://www.apachefriends.org/jp/index.html)

## インストール

トップページが以下のアドレスでアクセスできる場所に、Clone（ダウンロード）したファイルを配置してください。例）CドライブにXAMPPをインストールした場合は、C:\xampp\htdocs\gameusers 以下にファイルを置くと正常に表示されます。

https://localhost/gameusers/public/

### php.iniの変更（XAMPPの場合 C:\xampp\php\php.ini）

;extension=php_fileinfo.dll  
↓  
extension=php_fileinfo.dll

;extension=php_gmp.dll  
↓  
extension=php_gmp.dll

### データベースの作成

一番上の階層に create_gameusers.sql というSQLファイルがあります。これをデータベースにimportしてもらえば、gameusersというデータベースが作成されます。

### データベースへのアクセス

fuel/app/config/db.phpを開いて、データベースのユーザー名、データベースのパスワード部分に開発環境のデータベースの情報を入力して保存してください。

	return array(
		'default' => array(
			'connection'  => array(
				'dsn'        => 'mysql:dbname=gameusers; host=127.0.0.1',
				'username'   => 'データベースのユーザー名',
				'password'   => 'データベースのパスワード',
			),
			'charset' => 'utf8',
			'profiling' => true,
		),
	);

## アカウントについて

データベースをimportすると、予めアカウントが3つ用意されています。以下の情報でログインできますので、機能をテストしたい場合などに利用してください。
- 管理者アカウント / ログインID admin / パスワード 0123456a
- ユーザーアカウント / ログインID user2 / パスワード 0123456a
- ユーザーアカウント / ログインID user3 / パスワード 0123456a

## 動作しない機能

SNSのConsumer KeyやAccess Tokenなどは公開できないため、それを利用している機能は動作しません。

- TwitterやGoogleなどのアカウントを利用してのログイン
- 掲示板に書き込んだ際にTweetする
- WebPush機能
- お問い合わせの送信

## インストールについて

GitHubの知識があまりなく、プロジェクトを公開するのも初めてなため、解説している方法で問題なく環境が構築できるのかわかりません。XAMPPについて言及していたり、node_modulesディレクトリをそのまま公開していたり、もしかすると非常にバカバカしい構成になっているかもしれませんが、高スキルなプログラマーではないため、生暖かい目で見ていただけるとありがたいです。

## ライセンス

Game Users プロジェクトに帰属します。

独自のWebサイトの開発って、どういうライセンスでやるのが普通なんだろう？量産されるようなものではないため、オープンなライセンスにする意味がないと思うんですが、公開されてるリポジトリでクローズドなライセンスってなんか変ですね。

## 開発情報

開発についての情報をブログにまとめていきます。React & Node.jsで開発していくので、参考になる情報も掲載していきたいです。

[Game Users 開発ノート](https://gameusers.org/dev/blog/)
