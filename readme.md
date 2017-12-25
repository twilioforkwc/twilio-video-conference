## 概要

Twilioを使用したテレカンツールのサンプルプロジェクトです。
Laravelフレームワーク上で動作し、Herokuアプリへデプロイします。
Herokuアカウントをお持ちでない場合は、ngrokでローカル環境を一時的に公開します。

## 1. ソースコードの取得

ソースコードをGithubから取得

```
git clone https://github.com/twilioforkwc/[REPO]
```

## 2. Heroku Appのセットアップ

Herokuのインストール

```
https://signup.heroku.com/
```

Heroku toolbeltをインストール

```
https://toolbelt.heroku.com/

```

アカウント認証
```
$ heroku login
Enter your Heroku credentials.
Email: twilio@example.com
Password (typing will be hidden):
Authentication successful.
```

Herokuにアプリを作成
```
$ heroku create
Creating app... done, stack is cedar-14
https://xxxxxxxxxx.herokuapp.com/ | https://git.heroku.com/xxxxxxxxxx.git
```

## 3. Laravel Appのセットアップ

Configファイルの設定

[.env]の編集

```
...(省略)...
APP_URL=https://[作成したHerokuAppID].herokuapp.com
DB_CONNECTION=sqlite
...(省略)...
```

マイグレーション実行
```
$ php artisan migrate
```

キャッシュをクリアする
```
$ php artisan config:cache
```

## 4. Heroku Deploy

Herokuアプリへデプロイ

```
git push heroku master
```

ブラウザで開く

```
heroku open
```

## Heroku ボタンでデプロイ

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

## 画面共有を使用する(オプション)

画面共有 Add-On をダウンロードする

```
https://twilio-video-conference.herokuapp.com/screen_share.zip
```

インストールする

* screen_share.zip を解凍する
* chrome://extensions/ を開き「デベロッパーモード」をチェックする
* 「パッケージ化されていない拡張機能を取り込む」をクリックする
* 解凍したディレクトリを読み込む

画面共有を使う

* 読み込んだ Add-On の ID をコピーする
* ビデオチャットルームの「Enter Extension ID」にペーストする
* 画面共有ボタンをクリックする

## Herokuを使用せずに機能を試す

LaravelAppをローカルサーバーで起動する

```
php artisan serve
```

ngrokのインストール

```
https://ngrok.com/
```

ngrokでローカルへトンネルを作成する

```
ngrok http 8000
```




[EOF]