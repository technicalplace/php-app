# PHP App ① レビュー

## 全般

### 以下のaタグのリンクを押下した際にedit.phpの$_GETにどんな値が格納されるか説明してください。

```html
<a href="edit.php?todo_id=123&todo_content=焼肉">更新</a>
```
クエリーパラメーターの左辺がキー、右辺が値となる連想配列が格納される。<br>
上記の場合だと、`todo_id` がキーで値が `123`、`todo_content` がキーで値が`焼肉`の連想配列が格納される

### 以下のフォームの送信ボタンを押下した際にstore.phpの$_POSTにどんな値が格納されるか説明してください。

```html
<form action="store.php" method="post">
    <input type="text" name="id" value="123">
		<textarea　name="content">焼肉</textarea>
    <button type="submit">送信</button>
</form>
```

### `require_once()` は何のために記述しているか説明してください。
他のファイルに定義された変数や関数を読み込むため<br>
他にも `require` 関数などがあるが `require_once()`の場合は一度だけ読み込みを行う。

### `savePostedData($post)`は何をしているか説明してください。
リクエスト下の URL に応じて処理を振り分けている。<br>
まず `getRefererPath` 関数を実行し、その結果を変数 path に格納する。<br>
変数 path の値が新規作成ページの場合は `createTodoData` 関数を実行、編集ページの場合は `updateTodoData` 関数を実行している

### `header('location: ./index.php')`は何をしているか説明してください。
`header` 関数を使用して `index.html` に遷移する処理を実行している

### `getRefererPath()`は何をしているか説明してください。
`parse_url` 関数で URL の構成要素を取得し、変数 `urlArray` に格納する<br>
連想配列である `urlArray` の path 部分を返している

### `connectPdo()` の返り値は何か、またこの記述は何をするための記述か説明してください。
PDO インスタンスが返される<br>
DB に接続するための記述

### `try catch`とは何か説明してください。
例外処理を実装する際に使用する構文<br>
try の中に通常の処理を記述し、例外が発生したときは catch の中の処理が実行される

### Pdoクラスをインスタンス化する際に`try catch`が必要な理由を説明してください。
DB 接続時に発生しうるエラーを処理するために必要<br>
サーバーがダウンしていたり、接続時の情報が間違っていると接続エラーになり `PDOException` という例外を投げる。<br>
php8.0.0以降では`PDO::ERRMODE_EXCEPTION`モードがデフォルトであり、エラー発生時に自動的にPDOExceptionをThrowする<br>
これを catch のなかで`$e`として受取り、エラーメッセージなどを表示する<br>

↓ 公式
https://www.php.net/manual/ja/pdo.connections.php

## 新規作成

### `createTodoData($post)`は何をしているか説明してください。

## 一覧

### `getTodoList()`の返り値について説明してください。
`getAllRecords` の実行結果を返す<br>
`getAllRecords` では `deleted_at` カラムが `NULL` ではないレコードをすべて取得している<br>
つまり削除されていないデータを取得し、配列で返す（対象データが空だった場合は空配列を返す）

### `<?= ?>`は何の省略形か説明してください。

## 更新

### `getSelectedTodo($_GET['id'])`の返り値は何か、またなぜ`$_GET['id']` を引数に渡すのか説明してください。

### `updateTodoData($post)`は何をしているか説明してください。

## 削除

### `deleteTodoData($id)`は何をしているか説明してください。

### `deleted_at`を現在時刻で更新すると一覧画面からToDoが非表示になる理由を説明してください。
一覧画面で TODO を表示している処理は sql で `deleted_at` が `null` の値を取得している<br>
そのため `deleted_at` を現在時刻で更新すると `null` ではなくなり、非表示となる

### 今回のように実際のデータを削除せずに非表示にすることで削除されたように扱うことを〇〇削除というか。
論理削除

### 実際にデータを削除することを〇〇削除というか。
物理削除

### 前問のそれぞれの削除のメリット・デメリットについて説明してください。
- 論理削除<br>
メリット：データの復元が可能、また削除した日時のデータを保持するので削除履歴を保持できる<br>
デメリット：データベースからは削除しないのでデータベースが肥大化する

- 論理削除<br>
メリット：データベースから完全に削除するためデータベースのサイズが軽くなる<br>
デメリット：データを復元できない、データベースから削除するため履歴が残らずデータの追跡ができない
