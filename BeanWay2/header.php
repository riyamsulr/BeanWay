<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BeanWay</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  
  <style>
      body { font-family: "Poppins", sans-serif; margin: 0; padding: 0; background-color: #F4F1EC; color: #124D43; }
      
      /* تنسيق الهيدر */
      header {
          background-color: rgba(244, 241, 236, 0.95);
          box-shadow: 0 2px 10px rgba(0,0,0,0.08);
          padding: 10px 40px;
          position: relative; z-index: 100;
      }
      
      .nav-container {
          max-width: 1200px; margin: 0 auto;
          display: flex; justify-content: space-between; align-items: center;
      }

      /* القائمة اليمين واليسار */
      .nav-left, .nav-right { flex: 1; display: flex; align-items: center; }
      .nav-right { justify-content: flex-end; }
      
      .nav-left a {
          text-decoration: none; color: #124D43; margin-right: 25px;
          font-family: "Playfair Display", serif; font-size: 17px; transition: 0.3s;
      }
      .nav-left a:hover { color: #2F7566; }

      /* اللوقو في الوسط */
      .nav-center { flex: 0; text-align: center; }
      
      /* تنسيق صورة اللوقو عشان تكون مثل ما طلبت */
      .nav-center img {
          height: 75px; /* كبرنا الحجم شوي عشان يناسب التصميم الطولي */
          display: block;
          transition: transform 0.3s;
      }
      .nav-center img:hover { transform: scale(1.05); }

      /* زر الدخول والخروج */
      .auth-btn {
          background-color: #124D43; color: #fff; padding: 8px 20px;
          border-radius: 5px; text-decoration: none; font-size: 14px; transition: 0.3s;
      }
      .auth-btn:hover { background-color: #0E3D34; }
  </style>
</head>
<body>

<header>
  <div class="nav-container">
    
    <nav class="nav-left">
      <a href="beans.php">Coffee Beans</a>
      <a href="recipes.php">Recipes</a>
      <a href="brewing.php">Brewing</a>
      <?php if ($isLoggedIn): ?>
          <a href="profile.php">My Recipes</a>
      <?php endif; ?>
    </nav>

    <div class="nav-center">
      <a href="index.php">
          <img src="images/Logo.png" alt="BeanWay Logo">
      </a>
    </div>

    <div class="nav-right">
      <?php if ($isLoggedIn): ?>
          <a href="logout.php" class="auth-btn">Logout</a>
      <?php else: ?>
          <a href="login.php" class="auth-btn">Login</a>
      <?php endif; ?>
    </div>
    
  </div>
</header>