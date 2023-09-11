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
$order = $_GET['order'] ?? 'desc';

if (!in_array($order, ['asc', 'desc'], true)) {
  $order = 'desc';
}

$sql = "SELECT * FROM pages WHERE (name LIKE :search OR contents LIKE :search)";
if (!empty($start_date)) {
    $sql .= " AND created_at >= :start_date";

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
if (!empty($end_date)) {
    $sql .= " AND created_at <= :end_date";
}
$sql .= " ORDER BY created_at $order";

$statement = $pdo->prepare($sql);
$statement->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
if (!empty($start_date)) {
    $statement->bindValue(':start_date', $start_date . " 00:00:00", PDO::PARAM_STR);
}
if (!empty($end_date)) {
    $statement->bindValue(':end_date', $end_date . " 23:59:59", PDO::PARAM_STR);
}
$statement->execute();

$sql = "SELECT * FROM pages WHERE name LIKE :name OR contents LIKE :contents ORDER BY created_at $order";
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
      <form action="mypage.php" method="GET">
        <input type="text" name="search"><br>
        <input type="date" name="start_date">
        <input type="date" name="end_date">
        <input type="submit">
      </form>
      <form action="mytop.php" method="GET">
        <input type="text" name="search"><br>
        <input type="date" name="date"><br>
      <form action="index.php" method="GET">
        <input type="text" name="search"><br>
        <input type="submit">
      </form>
      <form action="mypage.php" method="get">

      <div>
        <form action="top.php" method="GET">
          <input type="date" name="date"><br>
          <input type="submit" value="日付で検索">
        </form>
      </div>
      <form action="page.php" method="get">
        <input type="date" name="start_date">
        <input type="date" name="end_date">
        <button type="submit">期間で絞り込む</button>
      </form>
      <form action="memo.php" method="GET">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        <label>
          <input type="radio" name="order" value="desc">
          <span>新着順</span>
        </label>
        <label>
          <input type="radio" name="order" value="asc">
          <span>古い順</span>
        </label>
        <input type="submit">
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
