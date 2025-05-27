<?php include('config.php'); ?>
<?php include('includes/public/registration_login.php'); ?>

<!DOCTYPE html>
<html>
<head>
<title>MyWebSite | Login</title>
<?php include('includes/public/head_section.php'); ?>

</head>
<body>

<div class="container">

    <?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>

    <div style="width: 40%; margin: 20px auto;">
        <form method="post" action="login.php">
            <h2>Connexion</h2>
            <?php include(ROOT_PATH . '/includes/public/errors.php'); ?>

            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="Nom d'utilisateur">
            <input type="password" name="password" placeholder="Mot de passe">

            <button type="submit" class="btn" name="login_btn">Connexion</button>
            <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
        </form>
    </div>

</div>

<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
</body>
</html>
