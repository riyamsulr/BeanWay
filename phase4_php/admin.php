<?php
session_start();
require 'db_connect.php';

// حماية الصفحة
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// جلب الوصفات المعلقة
$sql = "SELECT r.*, u.Name as UserName FROM recipe r JOIN user u ON r.UserID = u.UserID WHERE r.Status = 'pending' ORDER BY r.RecipeID DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BeanWay | Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  
  <style>
      /* إعدادات الصفحة الأساسية والخلفية الداكنة */
      body {
          margin: 0; padding: 0; box-sizing: border-box;
          font-family: "Poppins", sans-serif;
          background-color: #0E3D34; /* لون أخضر غامق */
          background-image: url("images/coffee.jpg"); /* خلفية القهوة */
          background-size: cover; background-attachment: fixed; background-position: center;
          position: relative;
          color: #333;
      }
      /* طبقة التعتيم على الخلفية */
      body::before {
          content: ""; position: fixed; inset: 0; background: rgba(14, 61, 52, 0.7); z-index: -1;
      }

      /* الهيدر */
      header {
          background-color: #F4F1EC;
          padding: 15px 40px;
          display: flex; justify-content: center; align-items: center;
          position: relative; z-index: 2;
      }
      header img { height: 40px; display: block; margin-bottom:5px; }
      header .logo-text { font-family: "Playfair Display", serif; font-size: 28px; color: #124D43; text-decoration: none; text-align: center;}
      .logout-btn {
          position: absolute; right: 40px; top: 25px;
          background-color: #124D43; color: #fff; padding: 8px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; transition:0.3s;
      }
      .logout-btn:hover { background-color: #0E3D34; }

      /* قسم الترحيب */
      .hero-text {
          text-align: center; color: #F4F1EC; padding: 50px 20px; max-width: 800px; margin: 0 auto;
      }
      .hero-text h2 { font-family: "Playfair Display", serif; font-size: 36px; margin-bottom: 10px; color: rgba(255,255,255,0.9); }
      .hero-text p { color: rgba(255,255,255,0.7); font-size: 15px; line-height: 1.6; }

      /* الشبكة (Grid) للكروت */
      main {
          max-width: 1200px; margin: 0 auto 60px; padding: 0 20px;
          display: grid; grid-template-columns: repeat(2, 1fr); /* عمودين */
          gap: 30px;
      }
      @media (max-width: 900px) { main { grid-template-columns: 1fr; } }

      /* تصميم الكرت */
      .card {
          background: #fff; border-radius: 12px; padding: 25px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      }
      
      .card h3 { font-family: "Playfair Display", serif; font-size: 24px; color: #124D43; margin-bottom: 5px; }
      .meta { font-size: 13px; color: #2F7566; margin-bottom: 15px; font-weight: 500; }
      
      .card img {
          width: 100%; height: 250px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 15px;
      }
      
      .info-row { font-size: 14px; color: #555; margin-bottom: 5px; }
      
      .section-label { font-weight: bold; color: #124D43; margin-top: 15px; display: block; font-size: 15px; }
      ul { margin: 5px 0 15px 20px; padding: 0; font-size: 14px; color: #444; }
      li { margin-bottom: 4px; }
      
      /* صندوق التلميح (Tip Box) */
      .tip-box {
          background-color: #fcf8f2; border-left: 4px solid #124D43; padding: 10px 15px;
          margin: 15px 0; border-radius: 4px; font-size: 14px; color: #555;
      }

      /* صندوق الفيدباك */
      .feedback-area { margin-top: 20px; }
      .feedback-area textarea {
          width: 100%; height: 60px; padding: 10px; border: 1px solid #ccc; border-radius: 6px;
          font-family: "Poppins", sans-serif; font-size: 13px; resize: none;
      }
      
      /* الأزرار */
      .actions { display: flex; gap: 10px; margin-top: 15px; }
      .btn {
          flex: 1; padding: 10px; border: none; border-radius: 6px; cursor: pointer;
          color: #fff; font-weight: bold; font-size: 15px; transition: 0.3s;
          display: flex; align-items: center; justify-content: center; gap: 8px;
      }
      .btn-accept { background-color: #124D43; }
      .btn-accept:hover { background-color: #0E3D34; }
      
      .btn-reject { background-color: #c0392b; }
      .btn-reject:hover { background-color: #a93226; }

  </style>
</head>
<body>

  <header>
    <div>
        <img src="images/Logo.png" style="margin:0 auto 5px; display:block; height:35px;">
        <div class="logo-text">BeanWay</div>
    </div>
    <a href="logout.php" class="logout-btn">Logout</a>
  </header>

  <div class="hero-text">
    <h2>Welcome, Admin!</h2>
    <p>
      This is your review dashboard. Review each coffee creation carefully — ensure it meets our quality and clarity standards before publishing.
      Let’s keep BeanWay a community of excellence.
    </p>
  </div>

  <main>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($row['Title']); ?></h3>
                <div class="meta">Added by: <?php echo htmlspecialchars($row['UserName']); ?></div>
                
                <img src="<?php echo htmlspecialchars($row['Image']); ?>" alt="Recipe Image">
                
                <div class="info-row">
                    Prep: <?php echo $row['Time']; ?> min | Servings: <?php echo $row['Servings']; ?> | Calories: ~<?php echo $row['Calories']; ?> kcal
                </div>
                <div class="info-row">
                    Taste: <?php echo htmlspecialchars($row['Taste']); ?>
                </div>

                <span class="section-label">Ingredients:</span>
                <ul>
                    <?php 
                    $lines = explode("\n", $row['Ingredients']);
                    foreach($lines as $line) if(trim($line)) echo "<li>".htmlspecialchars($line)."</li>";
                    ?>
                </ul>

                <span class="section-label">Steps:</span>
                <ul>
                    <?php 
                    $lines = explode("\n", $row['Steps']);
                    foreach($lines as $line) if(trim($line)) echo "<li>".htmlspecialchars($line)."</li>";
                    ?>
                </ul>

                <?php if(!empty($row['Tip'])): ?>
                    <div class="tip-box">
                        <strong>Tip:</strong> <?php echo htmlspecialchars($row['Tip']); ?>
                    </div>
                <?php endif; ?>

                <form action="admin_action.php" method="POST">
                    <input type="hidden" name="recipe_id" value="<?php echo $row['RecipeID']; ?>">
                    
                    <div class="feedback-area">
                        <textarea name="feedback" placeholder="Write your feedback..."></textarea>
                    </div>

                    <div class="actions">
                        <button type="submit" name="action" value="approve" class="btn btn-accept">
                            Accept <i class="fa-solid fa-check-square"></i>
                        </button>
                        <button type="submit" name="action" value="reject" class="btn btn-reject">
                            Reject <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="grid-column: 1/-1; text-align: center; color: #fff; padding: 50px;">
            <i class="fa-solid fa-mug-hot" style="font-size: 50px; margin-bottom: 20px; opacity: 0.8;"></i>
            <h3>No pending recipes to review.</h3>
        </div>
    <?php endif; ?>
  </main>

</body>
</html>