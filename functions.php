<?php
// DBを操作する処理を呼び出す関数等をまとめたファイル
require_once('connection.php');
// PHPに用意されているsession_start()を使うことでセッションを使うことができる
session_start();

// DBからレコード全件取得
function getTodoList()
{
    return getAllRecords();
}

function getSelectedTodo($id)
{
    return getTodoTextById($id);
}


/** 
 * @desc SESSIONにTOKENを格納する
 * openssl_random_pseudo_bytes(16)で、ランダムな 16 文字のバイト文字列を生成
 * 上記で生成した文字列をbin2hexで16進数に変換
 * 生成された値を $_SESSION['token'] に格納
 */
function setToken()
{
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
}

/** 
 * @desc SESSIONに格納されたtokenのチェックを行い、SESSIONにエラー文を格納する
 * サーバー側とクライアント側のtokenの整合性をチェックする
 * 新規作成と更新でデータが送信された時に呼び出して確認する
 */

function checkToken($token)
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
    }
}

/** 
 * @desc SESSION のキーであるerrに格納したエラーメッセージを空文字にして、ブラウザ上にエラーメッセージを表示させないようにする
 */
function unsetError()
{
    $_SESSION['err'] = '';
}

function redirectToPostedPage()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

function savePostedData($post)
{
    checkToken($post['token']);
    validate($post);
    $path = getRefererPath();
    switch ($path) {
        case '/new.php':
            createTodoData($post['content']);
            break;
        case '/edit.php':
            updateTodoData($post);
            break;
        case '/index.php':
            deleteTodoData($post['id']);
            break;
        default:
            break;
    }
}

function getRefererPath()
{
    $urlArray = parse_url($_SERVER['HTTP_REFERER']);
    return $urlArray['path'];
}

function validate($post)
{
    if (isset($post['content']) && $post['content'] === '') {
        $_SESSION['err'] = '入力がありません';
        redirectToPostedPage();
    }
}

// エスケープ処理
function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
