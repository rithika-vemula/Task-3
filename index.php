<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Advanced Blog</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">📝 My Blog</h2>

    <!-- 🔍 Search Form -->
    <form method="GET" class="d-flex mb-4">
        <input type="text" name="search" class="form-control me-2" placeholder="Search posts..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <?php
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $limit = 3;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $where = $search ? "WHERE title LIKE '%$search%' OR content LIKE '%$search%'" : '';
    $total_sql = "SELECT COUNT(*) as total FROM posts $where";
    $total_result = $conn->query($total_sql);
    $total = $total_result->fetch_assoc()['total'];
    $total_pages = ceil($total / $limit);

    $sql = "SELECT * FROM posts $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    ?>

    <!-- 📝 Posts List -->
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($row['title']) ?></h4>
                    <p class="card-text"><?= substr(htmlspecialchars($row['content']), 0, 150) ?>...</p>
                    <a href="post_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                    <div class="text-muted mt-2"><?= $row['created_at'] ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="alert alert-warning">No posts found.</p>
    <?php endif; ?>

    <!-- 📄 Pagination -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

</body>
</html>
