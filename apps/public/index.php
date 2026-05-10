<?php
$host = getenv('DB_HOST') ?: 'db';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'sample_db';
$user = getenv('DB_USER') ?: 'postgres';
$pass = getenv('DB_PASS') ?: 'postgres';

$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $connected = true;
    $error = null;
} catch (PDOException $e) {
    $connected = false;
    $users = [];
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PostgreSQL 接続確認</title>
    <style>
        body { font-family: sans-serif; max-width: 860px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 8px; }
        .status { padding: 12px 16px; border-radius: 4px; margin: 16px 0; font-weight: bold; }
        .ok  { background: #e6f4ea; color: #1e7e34; border: 1px solid #b7dfbf; }
        .err { background: #fce8e6; color: #c62828; border: 1px solid #f5b7b1; }
        table { border-collapse: collapse; width: 100%; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 10px 14px; text-align: left; }
        th { background: #f4f4f4; }
        tr:nth-child(even) td { background: #fafafa; }
        .meta { color: #666; font-size: 0.9em; margin-top: 24px; }
    </style>
</head>
<body>
    <h1>PostgreSQL 接続確認</h1>

    <?php if ($connected): ?>
        <div class="status ok">&#10004; 接続成功 &mdash; <?= htmlspecialchars($dbname) ?> @ <?= htmlspecialchars($host) ?>:<?= htmlspecialchars($port) ?></div>

        <h2>users テーブル (<?= count($users) ?> 件)</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>名前</th><th>メール</th><th>作成日時</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="status err">&#10008; 接続失敗</div>
        <pre><?= htmlspecialchars($error) ?></pre>
    <?php endif; ?>

    <p class="meta">PHP <?= phpversion() ?> &nbsp;|&nbsp; Apache <?= apache_get_version() ?></p>
</body>
</html>
