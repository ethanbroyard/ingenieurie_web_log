<?php
include('../config.php');
include(ROOT_PATH . '/includes/admin_functions.php');
include(ROOT_PATH . '/includes/author_functions.php');
authorOnly();
include(ROOT_PATH . '/includes/admin/head_section.php');

// Récupère les brouillons de l'auteur connecté
$user_id = $_SESSION['user']['id'];
$myPosts = getPostsByAuthor($user_id);
?>

<title>Auteur | Mes articles</title>
</head>
<body>

<?php include(ROOT_PATH . '/includes/author/header.php') ?>

<div class="container content">
	
	<h1 class="page-title">Mes brouillons</h1>

	<?php if (empty($myPosts)) : ?>
		<p>Vous n'avez pas encore proposé d'article.</p>
	<?php else : ?>
		<table class="table">
			<thead>
				<th>#</th>
				<th>Titre</th>
				<th>Date</th>
				<th>Status</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php foreach ($myPosts as $key => $post): ?>
					<tr>
						<td><?= $key + 1 ?></td>
						<td><?= htmlspecialchars($post['title']) ?></td>
						<td><?= date('d/m/Y', strtotime($post['created_at'])) ?></td>
						<td><?= $post['published'] ? 'Publié' : 'En attente' ?></td>
						<td>
							<a class="fa fa-pencil btn edit" href="create_post.php?edit-post=<?= $post['id'] ?>"></a>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php endif ?>
</div>

</body>
</html>
