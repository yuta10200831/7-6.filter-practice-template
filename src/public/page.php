<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$sql = 'SELECT * FROM pages';
$params = [];

$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

if ($start_date && $end_date) {
    $sql .= ' WHERE created_at BETWEEN :start_date AND :end_date';
    $params[':start_date'] = $start_date . ' 00:00:00';  // 日付の始まり
    $params[':end_date'] = $end_date . ' 23:59:59';  // 日付の終わり
}

$statement = $pdo->prepare($sql);

foreach ($params as $key => $value) {
    $statement->bindValue($key, $value, PDO::PARAM_STR);
}

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
      <form action="index.php" method="get">
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