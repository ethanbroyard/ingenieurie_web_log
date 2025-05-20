<?php if (isset($_SESSION['message'])) : ?>
  <div class="message <?php echo $_SESSION['type']; ?>">
    <p>
      <?php 
        echo $_SESSION['message']; 
        unset($_SESSION['message']);
        unset($_SESSION['type']);
      ?>
    </p>
  </div>
<?php endif ?>
