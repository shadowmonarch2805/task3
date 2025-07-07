<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$conn = new mysqli('localhost', 'root', '', 'blog');
$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchSQL = $search ? "WHERE title LIKE '%$search%' OR content LIKE '%$search%'" : "";
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total_result = $conn->query("SELECT COUNT(*) as total FROM posts $searchSQL");
$total_posts = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $limit);
$query = "SELECT * FROM posts $searchSQL ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM posts WHERE id=$id");
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html><head><title>Dashboard</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container my-5">
<h2>Dashboard</h2>
<a href="create.php" class="btn btn-success">Create New Post</a>
<a href="logout.php" class="btn btn-secondary float-end">Logout</a>
<form method="get" class="my-3">
    <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
</form>
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
            <?php if (!empty($row['image_path'])): ?>
                <img src="<?= htmlspecialchars($row['image_path']) ?>" class="img-fluid mb-2" style="max-width: 200px;">
            <?php endif; ?>
            <p class="card-text"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
            <p><small class="text-muted"><?= $row['created_at'] ?></small></p>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this post?')" class="btn btn-danger btn-sm">Delete</a>
        </div>
    </div>
<?php endwhile; ?>
<nav><ul class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
        </li>
    <?php endfor; ?>
</ul></nav>
</body></html>
