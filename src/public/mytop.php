<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$search = $_GET['search'] ?? '';
$date = $_GET['date'] ?? '';
$order = $_GET['order'] ?? 'desc';

if (!in_array($order, ['asc', 'desc'], true)) {
  $order = 'desc';
}

$sql = "SELECT * FROM pages WHERE (name LIKE :search OR contents LIKE :search)";
if (!empty($date)) {
    $sql .= " AND DATE(created_at) = :date";
}
$sql .= " ORDER BY created_at $order";

$statement = $pdo->prepare($sql);
$statement->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
if (!empty($date)) {
    $statement->bindValue(':date', $date, PDO::PARAM_STR);
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
        <input type="text" name="search"><br>
        <input type="date" name="start_date">
        <input type="date" name="end_date">
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
