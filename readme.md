## 1. ソースコードの取得

ソースコードをGithubから取得

```
git pull https://github.com/twilioforkwc/[REPO]
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

## 4. Heroku Deploy

Herokuアプリへデプロイ

```
git push heroku master
```

ブラウザで開く

```
heroku open
```

## 画面共有を使用する(オプション)

使用手順を書く





[EOF]