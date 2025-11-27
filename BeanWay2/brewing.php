<?php
require 'db_connect.php'; 
include 'header.php'; 
?>

<style>
    /* تنسيق خاص بصفحة البروينق */
    
    /* خلفية المقدمة */
    .intro {
      background: url("images/coffee.jpg") center/cover no-repeat; /* تأكد أن مسار الصورة صحيح */
      position: relative; color: #fff; text-align: center; padding: 110px 20px;
      margin-bottom: 40px;
    }
    .intro::before {
      content: ""; position: absolute; inset: 0; background: rgba(14,61,52,0.6); z-index: 0;
    }
    .intro-content { position: relative; z-index: 1; max-width: 800px; margin: auto; }
    .intro-content h2 { font-family: "Playfair Display", serif; font-size: 32px; margin-bottom: 10px; color:#fff;}
    .intro-content p { color: #EDEDED; }

    /* الشبكة (Grid) */
    main { max-width: 1100px; margin: 0 auto 60px; padding: 0 20px; }
    .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
    
    /* في الشاشات الصغيرة تصير عمود واحد */
    @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }

    /* البطاقة */
    .card {
      background: #FFFFFF;
      border: 1px solid #E6D7C3;
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.12);
      text-align: center;
      overflow: hidden;
      transition: transform .25s ease, box-shadow .25s ease;
    }
    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(0,0,0,.18);
    }
    /* أهم جزء: تحديد حجم الصورة */
    .card img {
      width: 100%; 
      height: 200px; /* طول ثابت للصورة */
      object-fit: cover; /* لقص الصورة بشكل مناسب دون مطها */
      display: block;
    }
    .card h3 {
      font-family: "Playfair Display", serif;
      color: #124D43;
      margin-top: 15px;
      font-size: 20px;
    }
    .card p {
      color: #6b5a46;
      font-size: 14px;
      padding: 0 14px 18px;
    }
</style>

  <section class="intro">
    <div class="intro-content">
      <h2>Coffee Brewing Methods</h2>
      <p>Explore popular brewing techniques — each brings out unique notes, aromas, and textures in your coffee.</p>
    </div>
  </section>

  <main>
    <div class="grid">
      <?php
      $sql = "SELECT * FROM brewing_methods";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo '<div class="card">';
              // تأكد أن مسار الصور في الداتا بيس صحيح (مثلاً images/v60.jpg)
              echo '<img src="' . htmlspecialchars($row['Image']) . '" alt="' . htmlspecialchars($row['Name']) . '">';
              echo '<h3>' . htmlspecialchars($row['Name']) . '</h3>';
              echo '<p>' . htmlspecialchars($row['Description']) . '</p>';
              echo '</div>';
          }
      } else {
          echo "<p style='grid-column: 1/-1; text-align:center;'>No brewing methods found.</p>";
      }
      ?>
    </div>
  </main>

<?php include 'footer.php'; ?>