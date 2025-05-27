<?php include('config.php');  ?>
<?php include('includes/public/head_section.php');  ?>
<?php include('includes/all_functions.php');  ?>
<?php include('includes/public/registration_login.php'); ?>

<?php if (!empty($_GET['search']) || !empty($_GET['topic_id'])) {
	$posts = searchPublishedPosts($conn, $_GET);
} else {
	$posts = getPublishedPosts($conn);
}

 ?>

<title>MyWebSite | Home </title>

</head>

<body>

	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->

		<!-- Banner -->
		<?php include(ROOT_PATH . '/includes/public/banner.php'); ?>
		<!-- // Banner -->

		<!-- Messages -->
		
		<!-- // Messages -->

		<!-- content -->
		<div class="content">
			<h2 class="content-title">Recent Articles</h2>
			<hr>
			<form method="GET" action="index.php" style="margin-bottom: 20px;">
				<input type="text" name="search" placeholder="Rechercher un article..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
				<select name="topic_id">
					<option value="">-- Tous les thèmes --</option>
					<?php
					$topics = getAllTopics();
					foreach ($topics as $topic): ?>
						<option value="<?= $topic['id'] ?>" <?= (isset($_GET['topic_id']) && $_GET['topic_id'] == $topic['id']) ? 'selected' : '' ?>>
							<?= htmlspecialchars($topic['name']) ?>
						</option>
					<?php endforeach ?>
				</select>
				<button type="submit" class="btn">Filtrer</button>
			</form>

			<?php foreach ($posts as $post): ?>
				<div class="post" style="margin-bottom: 30px;">
					<img src="static/images/<?= htmlspecialchars($post['image']) ?>" style="width:100%; max-height:200px; object-fit:cover;">
					<h3>
						<a href="single_post.php?slug=<?= urlencode($post['slug']) ?>">
							<?= htmlspecialchars($post['title']) ?>
						</a>
					</h3>
					<p class="info">
						Publié par <?= htmlspecialchars($post['username']) ?> le <?= date('d/m/Y', strtotime($post['created_at'])) ?>
					</p>
					<p><?= mb_strimwidth(strip_tags($post['body']), 0, 150, '...') ?></p>
					<a href="single_post.php?slug=<?= urlencode($post['slug']) ?>">
					<?= htmlspecialchars($post['title']) ?>
				</a>

				</div>
			<?php endforeach; ?>

			<?php if (empty($posts)): ?>
				<p>Aucun article publié pour l’instant.</p>
			<?php endif; ?>
		</div>
		<!-- // content -->


	</div>
	<!-- // container -->


	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->