<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

// 並び替えの順番を取得
$order = $_GET['order'] ?? 'desc';
$order = ($order === 'asc') ? 'asc' : 'desc';

$name = '%%';
$contents = '%%';

// 検索機能
if (isset($_GET['search'])) {
    $name = '%' . $_GET['search'] . '%';
    $contents = '%' . $_GET['search'] . '%';
}

$sql = "SELECT * FROM pages WHERE name LIKE :name OR contents LIKE :contents ORDER BY created_at $order";
$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $name, PDO::PARAM_STR);
$statement->bindValue(':contents', $contents, PDO::PARAM_STR);
$statement->execute();
$pages = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>top画面</title>
</head>

<body>
  <div>
    <div>
      <form action="index.php" method="GET">
            <input type="text" name="search"><br>
            <input type="submit">
      </form>
      <form action="privatepage.php" method="get">
        <div>
          <label>
            <input type="radio" name="order" value="desc" class="">
            <span>新着順</span>
          </label>
          <label>
            <input type="radio" name="order" value="asc" class="">
            <span>古い順</span>
          </label>
        </div>
        <button type="submit">送信</button>
      </form>
    </div>

    <div>
      <table border="1">
        <tr>
          <th>タイトル</th>
          <th>内容</th>
          <th>作成日時</th>
        </tr>
        <?php foreach ($pages as $page): ?>
          <tr>
            <td><?php echo $page['name']; ?></td>
            <td><?php echo $page['contents']; ?></td>
            <td><?php echo $page['created_at']; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</body>

</html>