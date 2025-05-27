<?php include(ROOT_PATH . '/admin/post_functions.php'); ?>
<?php 

function createAuthorPost($data, $file) {
	global $conn;

	$title = trim($data['title']);
	$body = trim($data['body']);
	$slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
	$user_id = $_SESSION['user']['id'];
	$image = $file['featured_image']['name'];
	$target = "../static/images/" . basename($image);
	move_uploaded_file($file['featured_image']['tmp_name'], $target);

	$published = 0; // non publié par défaut

	$stmt = $conn->prepare("INSERT INTO posts (user_id, title, slug, image, body, published) VALUES (?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("issssi", $user_id, $title, $slug, $image, $body, $published);
	return $stmt->execute();
}

function getPostsByAuthor($user_id) {
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


// Récupération d'un post existant
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