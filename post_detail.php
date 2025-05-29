<?php
include 'db.php';
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $post ? htmlspecialchars($post['title']) : 'Post Not Found' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <?php if ($post): ?>
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($post['title']) ?></h2>
                <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small class="text-muted">Posted on <?= $post['created_at'] ?></small>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger mt-4">❌ Post not found.</div>
    <?php endif; ?>
    <a href="index.php" class="btn btn-secondary mt-4">⬅ Back</a>
</div>
</body>
</html>

