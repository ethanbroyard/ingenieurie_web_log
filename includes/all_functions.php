<?php
function getPublishedPosts($conn) {
    $sql = "SELECT posts.*, users.username FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.published = 1 
            ORDER BY posts.created_at DESC";
    $result = mysqli_query($conn, $sql);

    $posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
    return $posts;
}

function getPostBySlug($conn, $slug) {
    $sql = "SELECT posts.*, users.username FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.slug = ? AND posts.published = 1 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function searchPublishedPosts($conn, $filters) {
	$search = trim($filters['search'] ?? '');
	$topic_id = intval($filters['topic_id'] ?? 0);

	$sql = "SELECT posts.*, users.username 
	        FROM posts 
	        JOIN users ON posts.user_id = users.id 
	        LEFT JOIN post_topic ON posts.id = post_topic.post_id 
	        WHERE posts.published = 1";

	$params = [];
	$types = "";

	if ($search !== '') {
		$sql .= " AND (posts.title LIKE ? OR posts.body LIKE ?)";
		$params[] = "%$search%";
		$params[] = "%$search%";
		$types .= "ss";
	}

	if ($topic_id > 0) {
		$sql .= " AND post_topic.topic_id = ?";
		$params[] = $topic_id;
		$types .= "i";
	}

	$sql .= " GROUP BY posts.id ORDER BY posts.created_at DESC";

	$stmt = $conn->prepare($sql);
	if (!empty($params)) {
		$stmt->bind_param($types, ...$params);
	}
	$stmt->execute();
	$result = $stmt->get_result();
	return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllTopics() {
	global $conn;
	$result = mysqli_query($conn, "SELECT * FROM topics ORDER BY name ASC");
	return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
