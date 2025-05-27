<?php
require_once 'config.php';
require_once 'vendor/autoload.php';
require_once 'includes/public/registration_login.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php include('includes/public/head_section.php'); ?>
    <title>MyWebSite | Register</title>
</head>
<body>

<div class="container">

    <?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>

    <div style="width: 40%; margin: 20px auto;">
        <form method="post" action="register.php">
            <h2>Créer un compte</h2>
            <?php include(ROOT_PATH . '/includes/public/errors.php'); ?>
            <select name="role">
                <option value="Author" selected>Author</option>
                <option value="Admin">Admin</option>
            </select>

            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="Nom d'utilisateur">
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Email">
            <input type="password" name="password" placeholder="Mot de passe">
            <input type="password" name="confirm" placeholder="Confirmation">

            <button type="submit" class="btn" name="register_btn">S'inscrire</button>
            <p>Déjà membre ? <a href="login.php">Connexion</a></p>
        </form>
    </div>

</div>

<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
</body>
</html>
