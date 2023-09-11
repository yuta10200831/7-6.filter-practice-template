<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$search = $_GET['search'] ?? '';
$order = $_GET['order'] ?? 'desc';

if (!in_array($order, ['asc', 'desc'], true)) {
  $order = 'desc';
}

$sql = "SELECT * FROM pages WHERE name LIKE :search OR contents LIKE :search ORDER BY created_at $order";
$statement = $pdo->prepare($sql);
$statement->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$statement->execute();
$pages = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>memo画面</title>
</head>
<body>
  <form action="memo.php" method="get">
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
    <div>
      <label>
        <input type="radio" name="order" value="desc" <?php if ($order === 'desc') echo 'checked'; ?>>
        <span>新しい順</span>
      </label>
      <label>
        <input type="radio" name="order" value="asc" <?php if ($order === 'asc') echo 'checked'; ?>>
        <span>古い順</span>
      </label>
    </div>
    <button type="submit">送信</button>
  </form>
  
  <table border="1">
    <?php foreach ($pages as $page): ?>
      <tr>
        <td><?php echo $page['name']; ?></td>
        <td><?php echo $page['contents']; ?></td>
        <td><?php echo $page['created_at']; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
