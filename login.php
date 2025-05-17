<?php
$host = "localhost";
$dbname = "shop";
$dbUsername = "root";
$dbPassword = "";
//ANCHOR

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "success <br/>";

  if (isset($_COOKIE['username'])) {
    $dbCheck = $pdo->prepare("SELECT * FROM users WHERE username = :username ");
    $dbCheck->execute([':username' => $_COOKIE['username']]);
    $dbCheckRes = $dbCheck->fetch(PDO::FETCH_ASSOC);
    if ($dbCheckRes != FALSE && isset($_COOKIE['username']) && $dbCheckRes['remember_me']) {
      $dbUpdate = $pdo->prepare("UPDATE `users` SET `register_date` = :register_date WHERE username = :username ");
      $dbUpdate->execute([
        ':username' => $_COOKIE['username'],
        ':register_date' => time(),
      ]);
      setcookie("isLogin", true, time() + (86400 * 1), "/");
      header('Location: dashboard.php');
      exit;
    }
    # code...
  }
} catch (PDOException $e) {
  // echo "Error" . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require('./partial/head.php'); ?>
  <title>LOGIN FORM</title>
</head>

<body>
  <?php require('./partial/switch_mode.php'); ?>
  <div class="wrapper">
    <h1>Login</h1>
    <p id="error-message"></p>
    <form action="validation.php" method="POST" id="form">
      <input type="hidden" name="form_type" value="login">
      <div>
        <label for="username">
          <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z" />
          </svg>
        </label>
        <input type="text" name="username" id="username" placeholder="Username" required>
      </div>
      <div>
        <label for="password-input">
          <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
          </svg>
        </label>
        <input type="password" name="password-input" id="password-input" placeholder="Password" required>
      </div>
      <div>
        <label id="checkbox">
          <input type="checkbox" name="remember_me" id="remember_me" />
          remember me
        </label>
      </div>
      <div id="loginMessage"></div>
      <button type="submit">Login</button>
    </form>
    <p>New here? <a href="index.php">Create an Account</a></p>
  </div>

</body>

</html>