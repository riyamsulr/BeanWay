<?php
session_start();
require 'db_connect.php';

// حماية
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// 1. جلب البيانات الحالية للوصفة
$sql = "SELECT * FROM recipe WHERE RecipeID = ? AND UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $recipe_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Recipe not found or access denied.");
}

$recipe = $result->fetch_assoc();

// 2. معالجة التحديث عند الضغط على زر الحفظ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $prep_time = $_POST['prep_time'];
    $servings = $_POST['servings'];
    $calories = $_POST['calories'];
    $taste = $_POST['taste'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $tip = $_POST['tip'];
    
    // إذا رفع صورة جديدة نحدثها، وإلا نبقي القديمة
    $image_path = $recipe['Image']; 
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "images/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    // تحديث البيانات في الداتا بيس (نعيد الحالة إلى pending للمراجعة)
    $update_sql = "UPDATE recipe SET Title=?, Time=?, Servings=?, Calories=?, Taste=?, Ingredients=?, Steps=?, Tip=?, Image=?, Status='pending' WHERE RecipeID=? AND UserID=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("siiisssssii", $title, $prep_time, $servings, $calories, $taste, $ingredients, $steps, $tip, $image_path, $recipe_id, $user_id);
    
    if ($update_stmt->execute()) {
        header("Location: profile.php"); // رجوع للبروفايل بعد التعديل
        exit();
    } else {
        $error = "Error updating recipe.";
    }
}
?>

<?php include 'header.php'; ?>

<main style="max-width:800px; margin:40px auto; padding:20px;">
    <h2 style="font-family:'Playfair Display',serif; text-align:center;">Edit Recipe</h2>
    
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="" method="POST" enctype="multipart/form-data" style="background:#fff; padding:30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
        
        <label style="display:block; margin-bottom:5px; font-weight:bold;">Recipe Name:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['Title']); ?>" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
        
        <div style="display:flex; gap:10px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Prep Time (min):</label>
                <input type="number" name="prep_time" value="<?php echo $recipe['Time']; ?>" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Servings:</label>
                <input type="number" name="servings" value="<?php echo $recipe['Servings']; ?>" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Calories:</label>
                <input type="number" name="calories" value="<?php echo $recipe['Calories']; ?>" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>
        </div>

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Taste Profile:</label>
        <input type="text" name="taste" value="<?php echo htmlspecialchars($recipe['Taste']); ?>" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Ingredients:</label>
        <textarea name="ingredients" rows="5" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;"><?php echo htmlspecialchars($recipe['Ingredients']); ?></textarea>

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Steps:</label>
        <textarea name="steps" rows="5" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;"><?php echo htmlspecialchars($recipe['Steps']); ?></textarea>
        
        <label style="display:block; margin-bottom:5px; font-weight:bold;">Pro Tip:</label>
        <input type="text" name="tip" value="<?php echo htmlspecialchars($recipe['Tip']); ?>" style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Update Image (Optional):</label>
        <input type="file" name="image" style="margin-bottom:20px;">
        <p style="font-size:12px; color:#666;">Current Image: <a href="<?php echo $recipe['Image']; ?>" target="_blank">View</a></p>
        
        <button type="submit" style="background:#2980b9; color:#fff; padding:12px 20px; border:none; cursor:pointer; width:100%; border-radius:5px; font-size:16px;">Save Changes</button>
    </form>
</main>

<?php include 'footer.php'; ?>