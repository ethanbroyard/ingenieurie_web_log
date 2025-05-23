<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/post_functions.php'); ?>
<?php adminOnly(); ?>
<?php include(ROOT_PATH . '/admin/post_controller.php'); ?>

<?php


$posts = getAllPosts();
?>

<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>
<title>Admin | Gérer les articles</title>
</head>
<body>

<?php include(ROOT_PATH . '/includes/admin/header.php'); ?>

<div class="container content">
    <?php include(ROOT_PATH . '/includes/admin/menu.php'); ?>

    <div class="table-div">
        <h1 class="page-title">Gérer les articles</h1>

        <?php displayMessage(); ?>

        <a href="create_post.php" class="btn">➕ Ajouter un article</a>

        <table class="table">
            <thead>
                <th>#</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Publié ?</th>
                <th colspan="2">Actions</th>
            </thead>
            <tbody>
                <?php foreach ($posts as $key => $post): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= htmlspecialchars($post['title']) ?></td>
                        <td><?= htmlspecialchars($post['username']) ?></td>
                        <td><?= $post['published'] ? 'Oui' : 'Non' ?></td>
                        <td>
                            <a href="create_post.php?edit-post=<?= $post['id'] ?>" class="btn edit">✏️</a>
                        </td>
                        <td>
                            <a href="posts.php?delete-post=<?= $post['id'] ?>" class="btn delete" onclick="return confirm('Supprimer cet article ?')">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
