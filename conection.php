<?php
// DB接続に関する処理を記述するファイル
// 別のファイルに記述した変数や関数などを読み込むときに記述する
require_once('config.php');

/** DBに接続するための関数 */
function connectPdo()
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}

/** 
 * @desc データの登録処理
 * :todoTextはプレースホルダーprepare()でSQL文を実行する準備をした後、bindValue()でこのプレースホルダに値をセットしています
 */
function createTodoData($todoText)
{
    $dbh = connectPdo();
    $sql = 'insert into todos (content) values (:todoText)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':todoText', $todoText, PDO::PARAM_STR);
    $stmt->execute();
}

/** データの取得処理 */
function getAllRecords()
{
    $dbh = connectPdo();
    $sql = 'select * from todos where deleted_at IS NULL';
    return $dbh->query($sql)->fetchAll();
}

/** データの更新処理 */
function updateTodoData($post)
{
    $dbh = connectPdo();
    $sql = 'update todos set content = :todoText where id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':todoText', $post['content'], PDO::PARAM_STR);
    $stmt->bindValue(':id', (int) $post['id'], PDO::PARAM_INT);
    $stmt->execute();
}

/** 保存されているTODOを返す */
function getTodoTextById($id)
{
    $dbh = connectPdo();
    $sql = 'select * from todos where deleted_at is NULL AND id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch();
    return $data['content'];
}

/** データの削除処理 */
function deleteTodoData($id)
{
    $dbh = connectPdo();
    $now = date('Y-m-d H:i:s');
    // 自分で考えた実装
    $sql = "update todos set deleted_at = '$now' where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
