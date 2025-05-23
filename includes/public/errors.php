<?php if (!empty($errors) && is_array($errors) && count($errors) > 0): ?>
    <div class="error">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach ?>
    </div>
<?php endif ?>
