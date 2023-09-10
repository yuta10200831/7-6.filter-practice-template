<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$params = [];
$sql = "SELECT * FROM pages WHERE 1=1";

if (isset($_GET['search'])) {
  $name = '%' . $_GET['search'] . '%';
  $contents = '%' . $_GET['search'] . '%';
  $sql .= ' AND (name LIKE :name OR contents LIKE :contents)';
  $params[':name'] = $name;
  $params[':contents'] = $contents;
}

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
  $start_date = $_GET['start_date'] . " 00:00:00";
  $end_date = $_GET['end_date'] . " 23:59:59";
  $sql .= ' AND (created_at BETWEEN :start_date AND :end_date)';
  $params[':start_date'] = $start_date;
  $params[':end_date'] = $end_date;
}

$statement = $pdo->prepare($sql);
$statement->execute($params);
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
      <form action="mypage.php" method="get">
            <input type="date" name="start_date">
            <input type="date" name="end_date">
            <button type="submit">期間で絞り込む</button>
      </form>
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