<?php

ob_start(); 
require_once '../config.php';
require_once ROOT_PATH . '/includes/admin_functions.php';
require_once ROOT_PATH . '/admin/post_functions.php';

$errors = [];
$title = '';
$body = '';
$topic_id = '';
$image = '';
$post_id = 0;
$isEditingPost = false;

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

// Création d'un post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    $topic_id = $_POST['topic_id'] ?? '';

    if (empty($title) || empty($body) || empty($topic_id) || empty($_FILES['featured_image']['name'])) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

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

// Mise à jour d'un post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
    $isEditingPost = true;
    $post_id = intval($_POST['post_id']);
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    $topic_id = $_POST['topic_id'] ?? '';
    $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));

    // Vérifie l’unicité du slug (hors post actuel)
    $check = $conn->prepare("SELECT id FROM posts WHERE slug = ? AND id != ?");
    $check->bind_param("si", $slug, $post_id);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows > 0) {
        $errors[] = "Un autre article utilise déjà ce titre.";
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

// Gérer la suppression si demandée
if (isset($_GET['delete-post'])) {
    $post_id = intval($_GET['delete-post']);
    deletePost($post_id);
    $_SESSION['success_msg'] = "Article supprimé avec succès.";
    header("Location: posts.php");
    exit;
}

//Gérer la publication si du post 
if (isset($_GET['publish-post'])) {
    $id = intval($_GET['publish-post']);
    $stmt = $conn->prepare("UPDATE posts SET published = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['success_msg'] = "Article publié.";
    header('Location: posts.php');
    exit;
}


ob_end_flush();
?>