<?php


// Vérifie si un utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Vérifie si un utilisateur est admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'Admin';
}

// Empêche l'accès à une page si l'utilisateur n'est pas admin
function adminOnly() {
    if (!isAdmin()) {
        $_SESSION['error_msg'] = "Accès refusé : administrateur requis.";
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

// Affiche les erreurs du tableau $errors[]
function displayErrors($errors) {
    if (count($errors) > 0): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach ?>
        </div>
    <?php endif;
}

// Affiche un message flash si défini en session
function displayMessage() {
    if (isset($_SESSION['success_msg'])) {
        echo "<div class='message success'>" . $_SESSION['success_msg'] . "</div>";
        unset($_SESSION['success_msg']);
    }
    if (isset($_SESSION['error_msg'])) {
        echo "<div class='message error'>" . $_SESSION['error_msg'] . "</div>";
        unset($_SESSION['error_msg']);
    }
}

// Récupère tous les rôles depuis la table `roles`
function getAdminRoles() {
    global $conn;
    $sql = "SELECT * FROM roles";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Récupère tous les utilisateurs ayant un rôle Admin ou Author
function getAdminUsers() {
    global $conn;
    $sql = "SELECT * FROM users WHERE role IN ('Admin', 'Author')";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getAllTopics() {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM topics ORDER BY name ASC");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Renvoie le nombre total d'utilisateurs
function countUsers() {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM users";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result)['total'] ?? 0;
}

// Renvoie le nombre total de posts publiés
function countPublishedPosts() {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM posts WHERE published = 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result)['total'] ?? 0;
}



