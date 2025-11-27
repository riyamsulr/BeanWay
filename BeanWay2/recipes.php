<?php
require 'db_connect.php'; 
include 'header.php'; 

// منطق البحث البسيط
$search = "";
$sql = "SELECT * FROM recipe WHERE Status = 'approved'";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    // نبحث في العنوان أو المكونات أو الطعم
    $sql .= " AND (Title LIKE '%$search%' OR Ingredients LIKE '%$search%' OR Taste LIKE '%$search%')";
}

$sql .= " ORDER BY RecipeID DESC"; // الأحدث أولاً
$result = $conn->query($sql);
?>

<style>
    /* تنسيقات صفحة الوصفات */
    
    /* Intro */
    .hero { text-align: center; padding: 50px 20px 20px; }
    .hero h2 { font-family: "Playfair Display", serif; font-size: 30px; color: #124D43; }
    .hero p { color: #6b5a46; }

    /* Search Bar */
    .search {
      max-width: 900px; margin: 18px auto 30px; padding: 0 20px;
      display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;
    }
    .search input {
      flex: 1 1 320px; padding: 12px 15px;
      border: 1px solid #2F7566; border-radius: 8px; background: #fff;
      font-size: 14px;
    }
    .btn-search {
     background-color: #124D43; color: #F4F1EC; padding: 0.8rem 1.4rem; 
     border: 0; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s;
    }
    .btn-search:hover { background-color: #2F7566; }

    /* Grid Layout */
    main { max-width: 1100px; margin: 20px auto 60px; padding: 0 20px; }
    .grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 25px;
    }
    @media (max-width: 1000px) { .grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }

    /* Card Styling */
    .card {
      background: #FFFFFF;
      border: 1px solid #E6D7C3;
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform .25s ease, box-shadow .25s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* Image Styling (Fixes the messy look) */
    .thumb {
      width: 100%;
      height: 220px; /* ارتفاع ثابت للصورة */
      display: block;
      object-fit: cover; /* قص الصورة لتناسب الإطار */
      background: #f1e6d6;
      border-bottom: 1px solid #E6D7C3;
    }

    /* Content Styling */
    .content { padding: 15px 18px 18px; flex-grow: 1; display: flex; flex-direction: column; }
    
    .title {
      font-family: "Playfair Display", serif;
      font-size: 20px;
      color: #124D43;
      margin-bottom: 8px;
      font-weight: bold;
    }
    
    .info-line {
      font-size: 13px;
      color: #6b5a46;
      font-weight: 500;
      margin-bottom: 5px;
    }
    
    /* Tags styling */
    .actions { margin-top: auto; padding-top: 15px; display: flex; gap: 8px; flex-wrap: wrap; }
    .tag {
      font-size: 11px;
      padding: 5px 10px;
      border-radius: 20px;
      background: #f8f5f2;
      border: 1px solid #D8C3A6;
      color: #6b5a46;
      font-weight: 600;
    }
</style>

  <section class="hero">
    <h2>Coffee Recipes</h2>
    <p>From classics to creative twists — browse, brew, and enjoy.</p>
  </section>

  <form class="search" method="GET" action="">
    <input type="text" name="search" placeholder="Search recipes... e.g., cappuccino" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
  </form>

  <main>
    <div class="grid">
      <?php
      if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              // نحتاج لرابط view-recipe مع رقم الوصفة
              $link = "view-recipe.php?id=" . $row['RecipeID'];
              
              echo '<article class="card" onclick="location.href=\'' . $link . '\'">';
              
              // الصورة
              echo '<img class="thumb" src="' . htmlspecialchars($row['Image']) . '" alt="' . htmlspecialchars($row['Title']) . '">';
              
              echo '<div class="content">';
              echo '<div class="title">' . htmlspecialchars($row['Title']) . '</div>';
              
              // المعلومات (وقت، حصص، سعرات)
              echo '<div class="info-line">Prep: ' . $row['Time'] . ' min | Servings: ' . $row['Servings'] . '</div>';
              echo '<div class="info-line">Calories: ~' . $row['Calories'] . ' kcal</div>';
              echo '<div class="info-line" style="color:#2F7566;">Taste: ' . htmlspecialchars($row['Taste']) . '</div>';
              
              // التاقات (سنقوم بإنشائها يدوياً بناء على الكلمات المفتاحية كشكل جمالي)
              echo '<div class="actions">';
              echo '<span class="tag">#coffee</span>';
              echo '<span class="tag">#beanway</span>';
              echo '</div>';
              
              echo '</div>'; // end content
              echo '</article>';
          }
      } else {
          echo '<p style="grid-column: 1/-1; text-align:center; font-size:18px;">No approved recipes found yet. Be the first to add one!</p>';
      }
      ?>
    </div>
  </main>

<?php include 'footer.php'; ?>