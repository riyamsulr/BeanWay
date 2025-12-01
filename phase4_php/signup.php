<?php
require 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {
        // التحقق من الإيميل
        $stmt = $conn->prepare("SELECT UserID FROM user WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // التشفير والحفظ (دائماً Role = 'user')
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO user (Name, Email, Password, Role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php?status=created");
                exit();
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BeanWay - Sign Up</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap" rel="stylesheet">
  <style>
    /* نفس ستايل اللوجن لتوحيد الشكل */
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif; }
    body {
      min-height: 100vh; display: flex; flex-direction: column; justify-content: space-between;
      background-color: #0E3D34;
      background-image: url("images/coffee.jpg"); 
      background-size: cover; background-position: center; position: relative;
    }
    body::before { content: ""; position: absolute; inset: 0; background: rgba(14, 61, 52, 0.6); z-index: 0; }
    
    header {
      position: relative; background-color: rgba(244, 241, 236, 0.95); padding: 15px 0; text-align: center; z-index: 2;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    header img { height: 50px; display: block; margin: 0 auto 5px auto; }
    header h1 { color: #124D43; font-size: 28px; font-family: "Playfair Display", serif; text-decoration: none; }
    header a { text-decoration: none; }

    .signup-box {
      position: relative; z-index: 2; background-color: #F4F1EC; width: 350px; margin: 60px auto; padding: 35px 45px;
      border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4); text-align: center;
    }
    .signup-box h2 { color: #124D43; margin-bottom: 25px; font-weight: 600; font-family: "Playfair Display", serif; }
    
    input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 6px; border: 1px solid #2F7566; font-size: 14px; background: #fff; }
    input:focus { border-color: #0E3D34; outline: none; }
    
    button { width: 100%; padding: 12px; background-color: #124D43; color: #fff; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; margin-top: 15px; font-weight: bold; transition: 0.3s; }
    button:hover { background-color: #0E3D34; }
    
    .login-link { margin-top: 15px; font-size: 13px; color: #2F7566; }
    .login-link a { color: #124D43; text-decoration: none; font-weight: 600; }
    .login-link a:hover { text-decoration: underline; }

    .error { background: #ffe6e6; color: #c0392b; border: 1px solid #c0392b; padding: 10px; margin-bottom: 10px; border-radius: 5px; font-size: 13px;}

    footer { background-color: #0E3D34; color: #F4F1EC; text-align: center; padding: 20px 10px; font-size: 14px; z-index: 2; position: relative; }
    footer::before { content: ""; display: block; width: 60%; margin: 0 auto 10px auto; border-top: 1px solid #FFFFFF; opacity: 0.8; }
  </style>
</head>
<body>

  <header>
    <img src="images/Logo.png" alt="BeanWay Logo">
    <a href="index.php"><h1>BeanWay</h1></a>
  </header>

  <div class="signup-box">
    <h2>Create Account</h2>
    
    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm" placeholder="Confirm Password" required>
      <button type="submit">Sign Up</button>
    </form>

    <div class="login-link">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>

  <footer>
    <div class="copyright">© 2025 BeanWay Coffee. All Rights Reserved.</div>
  </footer>

</body>
</html>