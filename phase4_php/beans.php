<?php
require 'db_connect.php'; 
include 'header.php';     
?>

<style>
    /* تنسيق صفحة البن */
    .intro-section {
      background: url("images/coffee.jpg") center/cover no-repeat;
      position: relative; color: #fff; text-align: center; padding: 110px 20px;
      margin-bottom: 60px;
    }
    .intro-section::before {
      content: ""; position: absolute; inset: 0; background: rgba(14, 61, 52, 0.6); z-index: 0;
    }
    .intro-content { position: relative; z-index: 1; max-width: 800px; margin: auto; }
    .intro-content h2 { font-family: "Playfair Display", serif; font-size: 32px; margin-bottom: 20px; color:#fff; }
    .intro-content p { font-size: 16px; color: #EDEDED; }

    main { max-width: 1000px; margin: 0 auto 60px; padding: 0 20px; }

    /* تنسيق العنصر الواحد */
    .bean-section {
      display: flex;
      align-items: center;
      gap: 30px;
      margin-bottom: 60px;
      background-color: #F4F1EC; /* لون الخلفية */
      color: #124D43;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    /* عكس الاتجاه للعناصر الزوجية */
    .bean-section:nth-child(even) { flex-direction: row-reverse; }

    /* تنسيق الصورة */
    .bean-section img {
      width: 280px;
      height: 280px;
      object-fit: cover;
      border-radius: 12px;
      border: 2px solid #124D43;
      flex-shrink: 0; /* منع الصورة من الانكماش */
    }

    .bean-section h3 {
      font-family: "Playfair Display", serif;
      font-size: 24px;
      margin-bottom: 10px;
    }
    .bean-section ul { margin-left: 20px; list-style-type: disc; margin-top:10px; }
    .bean-section li { margin-bottom: 5px; }

    hr {
      border: none; height: 1px; background-color: #124D43; margin: 50px auto; opacity: 0.3; width: 80%;
    }
    
    @media (max-width: 768px) {
        .bean-section, .bean-section:nth-child(even) { flex-direction: column; text-align: center; }
        .bean-section ul { text-align: left; }
    }
</style>

  <section class="intro-section">
    <div class="intro-content">
      <h2>Coffee Bean Types</h2>
      <p>
        Coffee is more than just a drink—it's a world of flavor, culture, and craftsmanship. <br>
        Discover the major coffee bean types that define the global coffee industry.
      </p>
    </div>
  </section>

  <main>
    <?php
    $sql = "SELECT * FROM beans";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $count = 0;
        while($row = $result->fetch_assoc()) {
            $count++;
            echo '<div class="bean-section">';
            
            // الصورة
            echo '<img src="' . htmlspecialchars($row['Image']) . '" alt="' . htmlspecialchars($row['Name']) . '">';
            
            echo '<div>';
            echo '<h3>' . $count . '. ' . htmlspecialchars($row['Name']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['Description']) . '</p>';
            
            echo '<ul>';
            if(!empty($row['Shape'])) echo '<li><strong>Shape:</strong> ' . htmlspecialchars($row['Shape']) . '</li>';
            if(!empty($row['Taste'])) echo '<li><strong>Taste:</strong> ' . htmlspecialchars($row['Taste']) . '</li>';
            if(!empty($row['Caffeine'])) echo '<li><strong>Caffeine:</strong> ' . htmlspecialchars($row['Caffeine']) . '</li>';
            if(!empty($row['Aroma'])) echo '<li><strong>Aroma:</strong> ' . htmlspecialchars($row['Aroma']) . '</li>';
            if(!empty($row['BestBrewing'])) echo '<li><strong>Best Brewing:</strong> ' . htmlspecialchars($row['BestBrewing']) . '</li>';
            echo '</ul>';
            
            echo '</div>'; 
            echo '</div>'; 
            
            // إضافة خط فاصل إذا لم يكن العنصر الأخير (اختياري)
            echo '<hr>';
        }
    } else {
        echo "<p style='text-align:center;'>No coffee beans found in the database.</p>";
    }
    ?>

    <div style="text-align:center; margin-top:40px;">
      <h3>Conclusion</h3>
      <p style="max-width:700px; margin:10px auto;">
        At <b>BeanWay</b>, we invite you to explore them all and discover your perfect brew.
      </p>
    </div>

  </main>

<?php include 'footer.php'; ?>