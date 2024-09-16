<?php
// 新規作成、更新、削除の処理は、ここを通る
// formのPOST先の役割を担う
require_once('./functions.php');

savePostedData($_POST);
header('Location: ./index.php');
