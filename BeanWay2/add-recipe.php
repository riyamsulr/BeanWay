<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $prep_time = $_POST['prep_time'];
    $servings = $_POST['servings'];
    $calories = $_POST['calories'];
    $taste = $_POST['taste'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $tip = $_POST['tip'];
    $user_id = $_SESSION['user_id'];
    
    // رفع الصورة
    $target_dir = "images/";
    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO recipe (Title, Time, Servings, Calories, Taste, Ingredients, Steps, Tip, Image, Status, UserID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
        $stmt->bind_param("siiisssssi", $title, $prep_time, $servings, $calories, $taste, $ingredients, $steps, $tip, $target_file, $user_id);
        
        if ($stmt->execute()) {
            // بعد النجاح نرجعه لصفحة البروفايل
            header("Location: profile.php");
            exit();
        } else {
            $error = "Database Error: " . $conn->error;
        }
    } else {
        $error = "Error uploading image.";
    }
}
?>
<?php include 'header.php'; ?>

<main style="max-width:800px; margin:40px auto; padding:20px;">
    <h2 style="font-family:'Playfair Display',serif; text-align:center;">Add New Recipe</h2>
    
    <form action="" method="POST" enctype="multipart/form-data" style="background:#fff; padding:30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
        
        <label style="display:block; margin-bottom:5px; font-weight:bold;">Recipe Name:</label>
        <input type="text" name="title" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
        
        <div style="display:flex; gap:10px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Prep Time (min):</label>
                <input type="number" name="prep_time" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Servings:</label>
                <input type="number" name="servings" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Calories:</label>
                <input type="number" name="calories" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>
        </div>

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Taste Profile (e.g. Sweet, Creamy):</label>
        <input type="text" name="taste" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Ingredients (Line by line):</label>
        <textarea name="ingredients" rows="5" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;"></textarea>

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Steps (Line by line):</label>
        <textarea name="steps" rows="5" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;"></textarea>
        
        <label style="display:block; margin-bottom:5px; font-weight:bold;">Pro Tip (Optional):</label>
        <input type="text" name="tip" style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Recipe Image:</label>
        <input type="file" name="image" required style="margin-bottom:20px;">
        
        <button type="submit" style="background:#124D43; color:#fff; padding:12px 20px; border:none; cursor:pointer; width:100%; border-radius:5px; font-size:16px;">Submit Recipe</button>
    </form>
</main>

<?php include 'footer.php'; ?>