<?php
require_once __DIR__ . '/../../config.php';
require_once(ROOT_PATH . '/includes/admin/send_mail.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$errors = [];

// Traitement de l'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_btn'])) {
   
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    } elseif ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Un compte existe déjà avec cet email.";
        } else {
            $hash = md5($password); 
            $role = ($_POST['role'] === 'Admin') ? 'Admin' : 'Author';
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hash, $role);
            $stmt->execute();

            $_SESSION['user'] = ['username' => $username, 'email' => $email, 'role' => $role];
            $_SESSION['success'] = "Inscription réussie !";
           
            sendWelcomeEmail($email, $username);

            header('Location: index.php');
            exit;
        }
    }
}

// Traitement du login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            $errors[] = "Erreur interne : " . $stmt->error;
        } else {
            $user = $result->fetch_assoc();

            if ($user && $user['password'] === md5($password)) {
                $_SESSION['user'] = $user;
                $_SESSION['success'] = "Connexion réussie !";

                if ($user['role'] === 'Admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $errors[] = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
    }
}


