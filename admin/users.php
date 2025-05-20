<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>
<?php include(ROOT_PATH . '/includes/public/messages.php'); ?>

<?php adminOnly(); ?>

<?php
$username = '';
$email = '';
$isEditingUser = false;
$admin_id = 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_admin'])) {
    $errors = createUser($_POST);
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($errors)) {
        $_SESSION['message'] = "Admin ajouté avec succès.";
        $_SESSION['type'] = "success";
        header("Location: " . BASE_URL . "admin/users.php");
        exit(0);
    }
}

if (isset($_GET['edit-admin'])) {
    $admin_id = $_GET['edit-admin'];
    $admin = getUserById($admin_id);

    if ($admin) {
        $isEditingUser = true;
        $username = $admin['username'];
        $email = $admin['email'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_admin'])) {
    $errors = updateUser($_POST);

    if (empty($errors)) {
        $_SESSION['message'] = "Admin mis à jour avec succès.";
        $_SESSION['type'] = "success";
        header("Location: " . BASE_URL . "admin/users.php");
        exit(0);
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $admin_id = $_POST['admin_id'];
    $isEditingUser = true;
}

if (isset($_GET['delete-admin'])) {
    $admin_id = $_GET['delete-admin'];
    deleteUser($admin_id);
    $_SESSION['message'] = "Admin supprimé avec succès.";
    $_SESSION['type'] = "success";
    header("Location: " . BASE_URL . "admin/users.php");
    exit(0);
}

$roles = getRoles();
$admins = getUsers();
?>

<title>Admin | Manage users</title>
</head>

<body>
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>
	<?php include(ROOT_PATH . '/includes/public/messages.php') ?>

	<div class="container content">
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<div class="action">
			<h1 class="page-title">
				<?php echo $isEditingUser ? "Modifier l'utilisateur" : "Créer un utilisateur"; ?>
			</h1>

			<form method="post" action="<?php echo BASE_URL . 'admin/users.php'; ?>">
				<?php include(ROOT_PATH . '/includes/public/errors.php') ?>

				<?php if ($isEditingUser === true) : ?>
					<input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
				<?php endif ?>

				<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username">
				<input type="email" name="email" value="<?php echo $email ?>" placeholder="Email">
				<input type="password" name="password" placeholder="Password">
				<input type="password" name="passwordConfirmation" placeholder="Password confirmation">

				<select name="role">
					<option value="" disabled <?php echo !$isEditingUser ? 'selected' : ''; ?>>Assign role</option>
					<?php foreach ($roles as $role) : ?>
						<option value="<?php echo $role['id']; ?>" 
							<?php
							if ($isEditingUser && isset($admin['role']) && $admin['role'] == $role['name']) echo 'selected';
							elseif (isset($_POST['role']) && $_POST['role'] == $role['id']) echo 'selected';
							?> >
							<?php echo $role['name']; ?>
						</option>
					<?php endforeach ?>
				</select>

				<?php if ($isEditingUser === true) : ?>
					<button type="submit" class="btn" name="update_admin">UPDATE</button>
				<?php else : ?>
					<button type="submit" class="btn" name="create_admin">Save User</button>
				<?php endif ?>
			</form>
		</div>

		<div class="table-div">
			<?php if (empty($admins)) : ?>
				<h1>No admins in the database.</h1>
			<?php else : ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Admin</th>
						<th>Role</th>
						<th colspan="2">Action</th>
					</thead>
					<tbody>
						<?php foreach ($admins as $key => $admin) : ?>
							<tr>
								<td><?php echo $key + 1; ?></td>
								<td>
									<?php echo $admin['username']; ?>, &nbsp;
									<?php echo $admin['email']; ?>
								</td>
								<td><?php echo $admin['role']; ?></td>
								<td>
									<a class="fa fa-pencil btn edit" href="users.php?edit-admin=<?php echo $admin['id'] ?>"></a>
								</td>
								<td>
									<a class="fa fa-trash btn delete" href="users.php?delete-admin=<?php echo $admin['id'] ?>" onclick="return confirm('Supprimer cet administrateur ?')"></a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
	</div>
</body>
</html>
