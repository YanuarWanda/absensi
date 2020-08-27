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
        <?php
          if($_SERVER['REMOTE_ADDR']=="5.189.147.47"){
        ?>
          <table class="table">
            <thead>
              <tr>
                <th></th>
                <th>Username</th>
                <th>Password</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Admin</td>
                <td>admin</td>
                <td>admin123</td>
              </tr>
              <tr>
                <td>Guru</td>
                <td>yudisubekti</td>
                <td>yudi123</td>
              </tr>
              <tr>
                <td>Murid</td>
                <td>yanuarwanda</td>
                <td>yanuar123</td>
              </tr>
            </tbody>
          </table>
        <?php
          }
        ?>
    </form>
    
    <?php 
        bootstrapFooter(); 
        pesan();
    ?>
  </body>
</html>