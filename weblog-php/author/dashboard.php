<?php
include('../config.php');
include(ROOT_PATH . '/includes/admin_functions.php');
authorOnly();
include(ROOT_PATH . '/includes/admin/head_section.php');
?>

<title>Auteur | Tableau de bord</title>
</head>
<body>
	<div class="header">
		<div class="logo">
			<a href="<?php echo BASE_URL . 'author/dashboard.php' ?>">
				<h1>MyWebSite - Auteur</h1>
			</a>
		</div>
		<?php if (isset($_SESSION['user'])) : ?>
			<div class="user-info">
				<span><?php echo $_SESSION['user']['username'] ?></span> &nbsp;
				<a href="<?php echo BASE_URL . 'logout.php'; ?>" class="logout-btn">Déconnexion</a>
			</div>
		<?php endif ?>
	</div>

	<div class="container dashboard">
		<h1>Bienvenue Auteur</h1>
		<div class="buttons">
			<a href="create_post.php">Proposer un article</a>
			<a href="my_posts.php">Mes brouillons</a>
		</div>
	</div>
</body>
</html>
