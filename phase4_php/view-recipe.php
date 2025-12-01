<?php
// 1. استدعاء ملف الاتصال بقاعدة البيانات (ضروري جداً لحل مشكلة $conn)
require 'db_connect.php';

// 2. استدعاء الهيدر (ضروري لحل مشكلة $isLoggedIn)
include 'header.php';

// 3. التحقق من وجود رقم الوصفة في الرابط (ID)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // تحويله لرقم للأمان

    // جلب بيانات الوصفة من قاعدة البيانات
    $sql = "SELECT r.*, u.Name as AuthorName FROM recipe r JOIN user u ON r.UserID = u.UserID WHERE r.RecipeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
    } else {
        echo "<div style='text-align:center; padding:50px;'><h2>Recipe not found!</h2></div>";
        include 'footer.php';
        exit();
    }
} else {
    echo "<div style='text-align:center; padding:50px;'><h2>No recipe selected!</h2></div>";
    include 'footer.php';
    exit();
}
?>

<style>
    /* تنسيقات صفحة عرض الوصفة */
    main { max-width: 900px; margin: 60px auto 40px auto; padding: 0 20px; }
    
    .recipe-container {
      background-color: #FFFFFF;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.12);
      padding: 30px 40px;
      border: 1px solid #E6D7C3;
    }
    
    .recipe-header h2 { font-family: "Playfair Display", serif; font-size: 30px; color: #124D43; margin-bottom: 5px; }
    .recipe-meta { font-size: 14px; color: #2F7566; margin-bottom: 20px; }
    
    .recipe-img {
      width: 100%; height: 400px; border-radius: 10px; object-fit: cover; margin-bottom: 20px; border: 2px solid #124D43;
    }

    .info-line { font-size: 15px; color: #2F7566; margin-top: 4px; }
    
    .section-title {
      font-weight: 600; font-size: 18px; font-family: "Playfair Display", serif;
      margin-top: 25px; margin-bottom: 10px; color: #124D43;
      border-bottom: 1px solid #E6D7C3; padding-bottom: 5px;
    }

    ul, ol { margin-left: 20px; line-height: 1.7; font-size: 15px; color: #333; }
    
    .tips {
      background-color: #F4F1EC; border-left: 4px solid #124D43; padding: 12px 15px;
      margin-top: 20px; border-radius: 6px; font-size: 14px; color: #124D43;
    }
    
    /* تنسيقات التعليقات */
    .comments-section { max-width: 900px; margin: 40px auto 60px auto; padding: 0 20px; }
    .comments-container {
      background-color: #FFFFFF; border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.12); padding: 30px 40px; border: 1px solid #E6D7C3;
    }
    .comments-container h3 { font-family: "Playfair Display", serif; color: #124D43; border-bottom: 1px solid #E6D7C3; padding-bottom: 10px; margin-bottom: 20px; }
    
    textarea { width: 100%; min-height: 80px; padding: 10px; border: 1px solid #2F7566; border-radius: 6px; margin-bottom: 10px; }
    .post-btn { background-color: #D2B48C; color: #4a3b28; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
    .post-btn:hover { background-color: #C2A475; }

    .comment-item { display: flex; gap: 15px; background-color: #F4F1EC; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
    .comment-content b { color: #124D43; }
</style>

<main>
    <div class="recipe-container">
        <div class="recipe-header"><h2><?php echo htmlspecialchars($recipe['Title']); ?></h2></div>
        <div class="recipe-meta">Added by: <?php echo htmlspecialchars($recipe['AuthorName']); ?></div>
        
        <img src="<?php echo htmlspecialchars($recipe['Image']); ?>" class="recipe-img" alt="<?php echo htmlspecialchars($recipe['Title']); ?>">

        <div class="info-line"><b>Prep:</b> <?php echo $recipe['Time']; ?> min | <b>Servings:</b> <?php echo $recipe['Servings']; ?> | <b>Calories:</b> ~<?php echo $recipe['Calories']; ?> kcal</div>
        <div class="info-line"><b>Taste:</b> <?php echo htmlspecialchars($recipe['Taste']); ?></div>

        <div class="section-title">Ingredients:</div>
        <ul>
            <?php 
            // تحويل النص إلى قائمة
            $ingredients = explode("\n", $recipe['Ingredients']);
            foreach($ingredients as $ing) {
                if(trim($ing)) echo "<li>" . htmlspecialchars($ing) . "</li>";
            }
            ?>
        </ul>

        <div class="section-title">Steps:</div>
        <ol>
            <?php 
            $steps = explode("\n", $recipe['Steps']);
            foreach($steps as $step) {
                if(trim($step)) echo "<li>" . htmlspecialchars($step) . "</li>";
            }
            ?>
        </ol>

        <?php if(!empty($recipe['Tip'])): ?>
            <div class="tips">
                <b>Tip:</b> <?php echo htmlspecialchars($recipe['Tip']); ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<section class="comments-section">
  <div class="comments-container">
    <h3>Comments</h3>

    <?php 
    if (isset($_GET['error']) && $_GET['error'] == 'long_comment') {
        echo '<p style="color:red; font-weight:bold;">⚠️ Error: Comment must be less than 150 characters.</p>';
    }
    if (isset($_GET['msg']) && $_GET['msg'] == 'added') {
        echo '<p style="color:green; font-weight:bold;">✅ Comment added successfully!</p>';
    }
    ?>

    <?php if ($isLoggedIn): ?>
        <form method="POST" action="add_comment.php">
          <input type="hidden" name="recipe_id" value="<?php echo $id; ?>"> 
          <textarea name="comment" placeholder="Write your comment (Max 150 chars)..." required></textarea>
          <button type="submit" class="post-btn">Post Comment</button>
        </form>
    <?php else: ?>
        <p>Please <a href="login.php" style="color:#124D43; font-weight:bold;">login</a> to leave a comment.</p>
    <?php endif; ?>
    
    <div style="margin-top:20px;">
        <?php
        // جلب التعليقات
        $sql_comments = "SELECT c.Text, u.Name, c.Time FROM comment c JOIN user u ON c.UserID = u.UserID WHERE c.RecipeID = ? ORDER BY c.Time DESC";
        $stmt_c = $conn->prepare($sql_comments);
        $stmt_c->bind_param("i", $id);
        $stmt_c->execute();
        $res_c = $stmt_c->get_result();

        if ($res_c->num_rows > 0) {
            while($row_c = $res_c->fetch_assoc()) {
                echo '<div class="comment-item">';
                echo '<div><i class="fa-solid fa-user-circle" style="font-size:24px; color:#2F7566;"></i></div>';
                echo '<div class="comment-content">';
                echo '<b>' . htmlspecialchars($row_c['Name']) . '</b> <span style="font-size:12px; color:#777;">(' . $row_c['Time'] . ')</span>';
                echo '<p>' . htmlspecialchars($row_c['Text']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p style="color:#777;">No comments yet.</p>';
        }
        ?>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>