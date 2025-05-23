<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/post_functions.php'); ?>
<?adminOnly();?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); 
?>

<?php 
	// Get all topics
	$topics = getAllTopics();	
	$errors = [];
	$title = '';
	$body = '';
	$topic_id = '';
	$isEditingPost = false;

	// Traitement de l'envoi du formulaire
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
		$title = $_POST['title'] ?? '';
		$body = $_POST['body'] ?? '';
		$topic_id = $_POST['topic_id'] ?? '';

		// Validation
		if (empty($title) || empty($body) || empty($topic_id) || empty($_FILES['featured_image']['name'])) {
			$errors[] = "Tous les champs sont obligatoires.";
		}

		// Si tout est bon, on appelle la fonction
		if (empty($errors)) {
			if (createPost($_POST, $_FILES)) {
				$_SESSION['success_msg'] = "Article créé avec succès.";
				header("Location: posts.php");
				exit;
			} else {
				$errors[] = "Erreur lors de la création de l'article.";
			}
		}
	}
	// Traitement du POST (update)
	if (isset($_GET['edit-post'])) {
		$post_id = intval($_GET['edit-post']);
		$isEditingPost = true;
	
		$post = getPostById($post_id);
		if (!$post) {
			$_SESSION['error_msg'] = "Article introuvable.";
			header("Location: posts.php");
			exit;
		}
	
		$title = $post['title'];
		$body = $post['body'];
		$slug = $post['slug'];
		$image = $post['image'];
	
		// Récupère le topic lié
		$result = mysqli_query($conn, "SELECT topic_id FROM post_topic WHERE post_id = $post_id LIMIT 1");
		$row = mysqli_fetch_assoc($result);
		$topic_id = $row['topic_id'] ?? '';
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
		$isEditingPost = true; // pour que le bon bouton s'affiche même après erreur
		global $conn, $errors;

		// Vérifie que le slug n'est pas utilisé par un autre post
		$check = $conn->prepare("SELECT id FROM posts WHERE slug = ? AND id != ?");
		$check->bind_param("si", $slug, $id);
		$check->execute();
		$res = $check->get_result();
		if ($res->num_rows > 0) {
			$errors[] = "Un autre article utilise déjà ce titre.";
			return false; // Empêche la mise à jour
		}
		$post_id = intval($_POST['post_id']);
		$title = $_POST['title'] ?? '';
		$body = $_POST['body'] ?? '';
		$topic_id = $_POST['topic_id'] ?? '';
	
		if (empty($title) || empty($body) || empty($topic_id)) {
			$errors[] = "Tous les champs sont obligatoires.";
		}
	
		if (empty($errors)) {
			if (updatePost($post_id, $_POST, $_FILES)) {
				$_SESSION['success_msg'] = "Article mis à jour avec succès.";
				header("Location: posts.php");
				exit;
			} else {
				$errors[] = "Erreur lors de la mise à jour de l'article.";
			}
		}
	}
	

?>

	<title>Admin | Create Post</title>
</head>
<body>

	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>

	<div class="container content">
		
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<!-- Middle form - to create and edit  -->
		<div class="action create-post-div">
			<h1 class="page-title">Create/Edit Post</h1>

			<form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_post.php'; ?>" >

				<!-- validation errors for the form -->
				<?php include(ROOT_PATH . '/includes/public/errors.php') ?>

				<!-- if editing post, the id is required to identify that post -->
				<?php if ($isEditingPost === true): ?>
					<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
					<div style="margin: 10px 0;">
						<p>Image actuelle :</p>
						<img src="<?php echo BASE_URL . 'static/images/' . htmlspecialchars($image); ?>" style="max-height: 150px;">
					</div>

				<?php endif ?>

				<input 
					type="text"
					name="title"
					value="<?php echo $title; ?>" 
					placeholder="Title">

				<label style="float: left; margin: 5px auto 5px;">Featured image</label>
				<input 
					type="file"
					name="featured_image"
					>

				<textarea name="body" id="body" cols="30" rows="10"><?php echo $body; ?></textarea>
				
				<select name="topic_id">
					<option value="" selected disabled>Choose topic</option>
					<?php foreach ($topics as $topic): ?>
						<option value="<?php echo $topic['id']; ?>" <?php if ($topic['id'] == $topic_id) echo 'selected'; ?>>
							<?php echo $topic['name']; ?>
						</option>
					<?php endforeach ?>
				</select>
				
				<!-- if editing post, display the update button instead of create button -->
				<?php if ($isEditingPost === true): ?> 
					<button type="submit" class="btn" name="update_post">UPDATE</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_post">Save Post</button>
				<?php endif ?>

			</form>
		</div>
		<!-- // Middle form - to create and edit -->

	</div>

</body>
</html>

<script>
	CKEDITOR.replace('body');
</script>
