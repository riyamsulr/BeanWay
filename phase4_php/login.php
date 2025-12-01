<?php
session_start();
require 'db_connect.php';

$error = "";
$success_msg = "";

// إذا جاء من صفحة التسجيل
if (isset($_GET['status']) && $_GET['status'] == 'created') {
    $success_msg = "Account created successfully! Please login.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // البحث عن المستخدم
    $stmt = $conn->prepare("SELECT UserID, Name, Password, Role FROM user WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // التحقق من الباسوورد
        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['user_name'] = $user['Name'];
            $_SESSION['user_role'] = $user['Role'];

            // التوجيه: الأدمن يروح لصفحة الأدمن، واليوزر لصفحة الاندكس
            if ($user['Role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect Password.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BeanWay - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif; }
    body {
      min-height: 100vh; display: flex; flex-direction: column; justify-content: space-between;
      background-color: #0E3D34;
      background-image: url("images/coffee.jpg"); /* تأكد أن المسار صحيح */
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

    .login-box {
      position: relative; z-index: 2; background-color: #F4F1EC; width: 350px; margin: 60px auto; padding: 35px 45px;
      border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4); text-align: center;
    }
    .login-box h2 { color: #124D43; margin-bottom: 25px; font-weight: 600; font-family: "Playfair Display", serif; }
    
    input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 6px; border: 1px solid #2F7566; font-size: 14px; background: #fff; }
    input:focus { border-color: #0E3D34; outline: none; }
    
    button { width: 100%; padding: 12px; background-color: #124D43; color: #fff; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; margin-top: 15px; font-weight: bold; transition: 0.3s; }
    button:hover { background-color: #0E3D34; }
    
    .signup-link { margin-top: 15px; font-size: 13px; color: #2F7566; }
    .signup-link a { color: #124D43; text-decoration: none; font-weight: 600; }
    .signup-link a:hover { text-decoration: underline; }
    
    .msg { padding: 10px; margin-bottom: 10px; border-radius: 5px; font-size: 13px; }
    .error { background: #ffe6e6; color: #c0392b; border: 1px solid #c0392b; }
    .success { background: #eafaf1; color: #27ae60; border: 1px solid #27ae60; }

    footer { background-color: #0E3D34; color: #F4F1EC; text-align: center; padding: 20px 10px; font-size: 14px; z-index: 2; position: relative; }
    footer::before { content: ""; display: block; width: 60%; margin: 0 auto 10px auto; border-top: 1px solid #FFFFFF; opacity: 0.8; }
  </style>
</head>
<body>

  <header>
    <img src="images/Logo.png" alt="BeanWay Logo">
    <a href="index.php"><h1>BeanWay</h1></a>
  </header>

  <div class="login-box">
    <h2>Login</h2>
    
    <?php if($error): ?>
        <div class="msg error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if($success_msg): ?>
        <div class="msg success"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <div class="signup-link">
      New user? <a href="signup.php">Create an account</a>
    </div>
  </div>

  <footer>
    <div class="copyright">© 2025 BeanWay Coffee. All Rights Reserved.</div>
  </footer>

</body>
</html>