
<?php
// === GET ALL POSTS ===
function getAllPosts() {
    global $conn;
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// === GET ONE POST BY ID ===
function getPostById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// === DELETE A POST ===
function deletePost($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// === CREATE A POST ===
function createPost($data, $file) {
    global $conn, $errors;

    $title = trim($data['title']);
    $body = trim($data['body']);
    $topic_id = intval($data['topic_id']);
    $user_id = $_SESSION['user']['id'];
    $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
    $published = 1;

    // Image upload
    $image = $file['featured_image']['name'];
    $target = "../static/images/" . basename($image);
    move_uploaded_file($file['featured_image']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, slug, image, body, published) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $title, $slug, $image, $body, $published);
    return $stmt->execute();
}

// === UPDATE A POST ===
function updatePost($id, $data, $file) {
    global $conn;

    $title = trim($data['title']);
    $body = trim($data['body']);
    $topic_id = intval($data['topic_id']);
    $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
    $image = $file['featured_image']['name'];

    if (!empty($image)) {
        $target = "../static/images/" . basename($image);
        move_uploaded_file($file['featured_image']['tmp_name'], $target);

        $stmt = $conn->prepare("UPDATE posts SET title=?, slug=?, image=?, body=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $slug, $image, $body, $id);
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title=?, slug=?, body=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $slug, $body, $id);
    }

    return $stmt->execute();
}