<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$search = $_GET['search'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$order = $_GET['order'] ?? 'desc'; // 新着順がデフォルト

$sql = "SELECT * FROM pages WHERE (name LIKE :search OR contents LIKE :search)";
if (!empty($start_date)) {
    $sql .= " AND created_at >= :start_date";
}
if (!empty($end_date)) {
    $sql .= " AND created_at <= :end_date";
}
$sql .= " ORDER BY created_at " . $order;

$statement = $pdo->prepare($sql);
$statement->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
if (!empty($start_date)) {
    $statement->bindValue(':start_date', $start_date . ' 00:00:00', PDO::PARAM_STR);
}
if (!empty($end_date)) {
    $statement->bindValue(':end_date', $end_date . ' 23:59:59', PDO::PARAM_STR);
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