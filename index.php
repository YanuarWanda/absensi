<?php 
    require_once("bantuan/functions.php");
    require_once("bantuan/links.php"); 
    
    checkSudahLogin();
?>

<!doctype html>
<html lang="en">
  <head>
    <?php headerHTML("Login | Absensi"); ?>
  </head>
  <body>
    <form method="POST" action="login.php" class="form-login">
        <img src="assets/img/logo.png" alt="Logo" class="logo">
        <h1 class="h3 font-weight-normal m-3">Login terlebih dahulu</h1>

        <label for="username" class="sr-only">Username</label>
        <input type="text" name="username" id="username" class="form-control rounded-0" placeholder="Username" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" id="password" class="form-control rounded-0" placeholder="Password" required>
        <button type="submit" class="btn btn-primary btn-lg w-100 m-3">Login</button>

        <p class="text-muted">&copy; 2020</p>
    </form>
    
    <?php 
        bootstrapFooter(); 
        pesan();
    ?>
  </body>
</html>