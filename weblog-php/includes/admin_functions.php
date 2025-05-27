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

function authorOnly() {
    if (!isLoggedIn() || $_SESSION['user']['role'] !== 'Author') {
        $_SESSION['error_msg'] = "Accès refusé : auteur requis.";
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
function getRoles() {
    global $conn;
    $sql = "SELECT * FROM roles";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Récupère tous les utilisateurs ayant un rôle Admin ou Author
function getUsers() {
    global $conn;
    $sql = "SELECT * FROM users WHERE role IN ('Admin', 'Author')";
    $result = mysqli_query($conn, $sql);
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


function createUser($data) {
    global $conn;
    $errors = [];

    $username = trim($data['username'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $confirm = $data['passwordConfirmation'] ?? '';
    $role = intval($data['role'] ?? 0);

    if (empty($username) || empty($email) || empty($password) || empty($confirm) || empty($role)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    } elseif ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email est déjà utilisé
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Un utilisateur avec cet email existe déjà.";
        } else {
            // Récupérer le nom du rôle à partir de son ID
            $stmt = $conn->prepare("SELECT name FROM roles WHERE id = ?");
            $stmt->bind_param("i", $role);
            $stmt->execute();
            $result = $stmt->get_result();
            $role_row = $result->fetch_assoc();

            if (!$role_row) {
                $errors[] = "Rôle invalide.";
            } else {
                $role = $role_row['name'];
                $hash = md5($password);

                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $hash, $role);
                $stmt->execute();

                $_SESSION['success_msg'] = "Utilisateur admin ajouté avec succès.";
                header("Location: users.php");
                exit;
            }
        }
    }
    return $errors;
}
    function getUserById($id) {
        global $conn;
        $sql = "SELECT id, username, email, role FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    

    
    function updateUser($data) {
        global $conn;
        $errors = [];
    
        $username = trim($data['username']);
        $email = trim($data['email']);
        $role_id = trim($data['role']) ;
        $admin_id = $data['admin_id'];
    
        if (empty($username)) { $errors[] = "Username is required"; }
        if (empty($email)) { $errors[] = "Email is required"; }
        if (empty($role_id)) { $errors[] = "Role is required"; }

        // Récupère le nom du rôle
        $stmt = $conn->prepare("SELECT name FROM roles WHERE id = ?");
        $stmt->bind_param("i", $role_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $role_row = $result->fetch_assoc();

        if (!$role_row) {
            $errors[] = "Rôle invalide.";
        } else {
            $role = $role_row['name'];
        }

        // Si le mot de passe est rempli, on le met à jour aussi
        if (!empty($data['password'])) {
            if ($data['password'] !== $data['passwordConfirmation']) {
                $errors[] = "Passwords do not match";
            } else {
                $password = password_hash($data['password'], PASSWORD_DEFAULT);
            }
        }
        
        if (empty($errors)) {
            if (!empty($password)) {
                $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
                $stmt->bind_param("ssssi", $username, $email, $password, $role, $admin_id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
                $stmt->bind_param("sssi", $username, $email, $role, $admin_id);
            }
            $stmt->execute();
        }
    
        return $errors;
    }
    
    
    function deleteUser($admin_id) {
        global $conn;
    
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
    
        // Vérifie si suppression réussie
        return $stmt->affected_rows > 0;
    }
    

    
  
