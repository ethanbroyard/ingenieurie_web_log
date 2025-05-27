<?php
include('../config.php');
include(ROOT_PATH . '/includes/admin_functions.php');
include(ROOT_PATH . '/includes/author_functions.php');
authorOnly();
include(ROOT_PATH . '/includes/admin/head_section.php');

// Initialisation des variables
$errors = [];
$title = '';
$body = '';
$slug = '';

// Traitement de la création de post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
	$title = trim($_POST['title'] ?? '');
	$body = trim($_POST['body'] ?? '');
	$slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));

	// Validation simple
	if (empty($title) || empty($body) || empty($_FILES['featured_image']['name'])) {
		$errors[] = "Tous les champs sont obligatoires.";
	}

	// Enregistrement si pas d'erreurs
	if (empty($errors)) {
		if (createAuthorPost($_POST, $_FILES)) {
			$_SESSION['success_msg'] = "Article soumis pour validation.";
			header("Location: dashboard.php");
			exit;
		} else {
			$errors[] = "Erreur lors de la soumission.";
		}
	}
}
?>

<title>Auteur | Proposer un article</title>
</head>
<body>
	<?php include(ROOT_PATH . '/includes/author/header.php') ?>

	<div class="container content">
		
		<div class="action create-post-div">
			<h1 class="page-title">Proposer un article</h1>

			<form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'author/create_post.php'; ?>">
				<?php include(ROOT_PATH . '/includes/public/errors.php') ?>

				<input type="text" name="title" value="<?= htmlspecialchars($title) ?>" placeholder="Titre">

				<label style="float: left;">Image à la une</label>
				<input type="file" name="featured_image">

				<textarea name="body" id="body" cols="30" rows="10"><?= htmlspecialchars($body) ?></textarea>

				<button type="submit" class="btn" name="create_post">Soumettre</button>
			</form>
		</div>
	</div>
</body>
</html>

<script>
	CKEDITOR.replace('body');
</script>
