<?php 
include 'header.php'; 

// إصلاح مشكلة المتغير (Warning Fix)
// نتأكد هل الاسم موجود في السيشن؟ إذا لا، نستخدم كلمة Guest
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>

<style>
    /* تنسيقات عامة للصفحة الرئيسية */
    main {
      position: relative;
      z-index: 2;
      text-align: center;
      padding: 2rem;
      max-width: 1000px;
      margin: 0 auto;
    }

    /* قسم الكويز */
    .quiz-intro {
      background-color: #fffaf0;
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 4px 14px rgba(0,0,0,0.12);
      margin-bottom: 2rem;
      position: relative;
      z-index: 2;
      border: 1px solid #E6D7C3;
    }
    .quiz-intro h2 {
      font-family: "Playfair Display", serif;
      color: #124D43;
      font-size: 26px;
      margin-bottom: 10px;
    }
    .quiz-intro p {
      color: #5b4636;
      margin-bottom: 20px;
      font-size: 15px;
      max-width: 700px;
      margin-inline: auto;
    }
    .quiz-intro .btn {
      background-color: #124D43;
      color: #F4F1EC;
      font-weight: 600;
      padding: 0.8rem 1.6rem;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
      border: none;
      font-size: 15px;
    }
    .quiz-intro .btn:hover { background-color: #2F7566; }

    /* صناديق الميزات */
    .features {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 1.8rem 0 2rem;
      flex-wrap: wrap;
    }
    .feature {
      background: #FFFFFF;
      border: 1px solid #E6D7C3;
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.12);
      padding: 22px 18px;
      width: 270px;
      text-align: center;
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
      cursor: pointer;
    }
    .feature:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(0,0,0,.18);
      border-color: #D8C3A6;
    }
    .feature i { font-size: 30px; color: #2F7566; margin-bottom: 8px; }
    .feature h3 { color: #124D43; margin-bottom: 6px; font-size: 18px; font-weight: bold;}
    .feature p { color: #6b5a46; font-size: 14px; }
</style>

<main>
  <section class="quiz-intro">
    <h2><?php echo $isLoggedIn ? "Welcome back, " . htmlspecialchars($userName) . "!" : "What's Your Coffee Personality?"; ?></h2>
    
    <p>Take our short quiz to discover your unique coffee taste — whether you love bold espresso, sweet lattes, or aromatic brews.</p>
    
    <button class="btn" onclick="location.href='coffeeQuiz.php'">Start the Coffee Quiz</button>
  </section>

  <section class="features">
    <div class="feature" onclick="location.href='recipes.php'">
      <i class="fa-solid fa-mug-hot"></i>
      <h3>Coffee Recipes</h3>
      <p>Explore creative and classic recipes to suit every taste.</p>
    </div>

    <div class="feature" onclick="location.href='beans.php'">
      <i class="fa-solid fa-seedling"></i>
      <h3>Coffee Beans</h3>
      <p>Learn about the origins and flavors of your favorite beans.</p>
    </div>

    <div class="feature" onclick="location.href='brewing.php'">
      <i class="fa-solid fa-filter"></i>
      <h3>Brew Methods</h3>
      <p>Master the techniques behind every perfect cup.</p>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>