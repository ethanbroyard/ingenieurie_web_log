<?php
include('../config.php');
include(ROOT_PATH . '/includes/admin_functions.php');
include(ROOT_PATH . '/includes/all_functions.php');
adminOnly();

$errors = [];
$topics = getAllTopics(); // à implémenter dans admin_functions.php ou topic_functions.php

if (isset($_POST['create_topic'])) {
    $name = trim($_POST['name']);
    $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $name));
    $stmt = $conn->prepare("INSERT INTO topics (name, slug) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $slug);
    $stmt->execute();
    $_SESSION['success_msg'] = "Topic ajouté avec succès.";
    header("Location: topics.php");
    exit;
}

if (isset($_GET['delete-topic'])) {
    $id = intval($_GET['delete-topic']);
    $conn->query("DELETE FROM topics WHERE id = $id");
    $_SESSION['success_msg'] = "Topic supprimé.";
    header("Location: topics.php");
    exit;
}
?>

<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>
<title>Admin | Manage Topics</title>
</head>
<body>

<?php include(ROOT_PATH . '/includes/admin/header.php'); ?>

<div class="container content">
    <?php include(ROOT_PATH . '/includes/admin/menu.php'); ?>

    <div class="action">
        <h1 class="page-title">Ajouter un thème</h1>
        <?php displayErrors($errors); ?>

        <form method="post" action="topics.php">
            <input type="text" name="name" placeholder="Nom du thème" required>
            <button type="submit" class="btn" name="create_topic">Ajouter</button>
        </form>
    </div>

    <div class="table-div">
        <h1 class="page-title">Liste des thèmes</h1>
        <?php displayMessage(); ?>

        <table class="table">
            <thead>
                <th>#</th>
                <th>Nom</th>
                <th>Slug</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php foreach ($topics as $key => $topic): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= htmlspecialchars($topic['name']) ?></td>
                        <td><?= htmlspecialchars($topic['slug']) ?></td>
                        <td><a class="btn delete" href="topics.php?delete-topic=<?= $topic['id'] ?>">🗑️</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
