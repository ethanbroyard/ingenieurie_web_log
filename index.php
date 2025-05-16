<?php include('config.php');  ?>
<?php include('includes/public/head_section.php');  ?>
<?php include('includes/all_functions.php');  ?>
<?php include('includes/public/registration_login.php'); ?>

<?php $posts = getPublishedPosts($conn); ?>

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