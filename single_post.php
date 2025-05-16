<?php
include('config.php');
include('includes/all_functions.php');

if (!isset($_GET['slug'])) {
    header('Location: index.php');
    exit;
}

$post = getPostBySlug($conn, $_GET['slug']);

if (!$post) {
    echo "<h2>Article introuvable.</h2>";
    exit;
}
?>
<?php include('includes/public/head_section.php'); ?>
<title><?= htmlspecialchars($post['title']) ?> | MyWebSite</title>
</head>
<body>

<?php include('includes/public/navbar.php'); ?>

<div class="container content">
    <h2 class="content-title"><?= htmlspecialchars($post['title']) ?></h2>
    <hr>
    <p class="info">Par <?= htmlspecialchars($post['username']) ?> le <?= date('d/m/Y', strtotime($post['created_at'])) ?></p>
    
    <?php if (!empty($post['image'])): ?>
        <img src="static/images/<?= htmlspecialchars($post['image']) ?>" style="width:100%; max-height:300px; object-fit:cover; margin-bottom:20px;">
    <?php endif; ?>

    <div class="post-body">
        <?= $post['body'] ?>
    </div>
</div>

<?php include('includes/public/footer.php'); ?>
</body>
</html>