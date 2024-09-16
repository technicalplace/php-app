<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <?php
  require_once('./functions.php');
  header('Set-Cookie: userId=123');
  setToken();
  ?>
  <?php if (!empty($_SESSION['err'])) : ?>
    <p><?= $_SESSION['err']; ?></p>
  <?php endif; ?>
  <div>
    <a href="./new.php">
      <p>新規作成</p>
    </a>
  </div>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>内容</th>
        <th>更新</th>
        <th>削除</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach (getTodoList() as $todo) : ?>
        <tr>
          <td><?= e($todo['id']); ?></td>
          <td><?= e($todo['content']); ?></td>
          <td>
            <a href="./edit.php?id=<?= e($todo['id']); ?>">更新</a>
          </td>
          <td>
            <form action="store.php" method="post">
              <input type="hidden" name="id" value="<?= e($todo['id']); ?>">
              <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
              <button type="submit">削除</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php unsetError(); ?>
</body>

</html>