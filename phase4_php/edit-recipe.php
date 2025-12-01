<?php
session_start();
require 'db_connect.php';

// Protection
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// 1. Fetch current recipe data
$sql = "SELECT * FROM recipe WHERE RecipeID = ? AND UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $recipe_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Recipe not found or access denied.");
}

$recipe = $result->fetch_assoc();

// 2. Fetch current tags for this recipe
$tags_sql = "SELECT t.Name FROM tag t 
             JOIN category c ON t.TagID = c.TagID 
             WHERE c.RecipeID = ?";
$tag_stmt = $conn->prepare($tags_sql);
$tag_stmt->bind_param("i", $recipe_id);
$tag_stmt->execute();
$tag_result = $tag_stmt->get_result();

$current_tags = [];
while($row = $tag_result->fetch_assoc()) {
    $current_tags[] = $row['Name'];
}
$tags_string = implode(", ", $current_tags);

// 3. Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $prep_time = $_POST['prep_time'];
    $servings = $_POST['servings'];
    $calories = $_POST['calories'];
    $taste = $_POST['taste'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $tip = $_POST['tip'];
    $tags_input = $_POST['tags']; // New tags input
    
    // Image handling
    $image_path = $recipe['Image']; 
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "images/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    // Update Recipe Table
    $update_sql = "UPDATE recipe SET Title=?, Time=?, Servings=?, Calories=?, Taste=?, Ingredients=?, Steps=?, Tip=?, Image=?, Status='pending' WHERE RecipeID=? AND UserID=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("siiisssssii", $title, $prep_time, $servings, $calories, $taste, $ingredients, $steps, $tip, $image_path, $recipe_id, $user_id);
    
    if ($update_stmt->execute()) {
        
        // Update Tags Logic
        // 1. Remove existing links for this recipe
        $del_cat = $conn->prepare("DELETE FROM category WHERE RecipeID = ?");
        $del_cat->bind_param("i", $recipe_id);
        $del_cat->execute();
        $del_cat->close();

        // 2. Add new tags
        if (!empty($tags_input)) {
            $tags_array = explode(',', $tags_input);
            foreach ($tags_array as $tag_name) {
                $tag_name = trim($tag_name);
                if (empty($tag_name)) continue;

                // Check if tag exists
                $check_tag = $conn->prepare("SELECT TagID FROM tag WHERE Name = ?");
                $check_tag->bind_param("s", $tag_name);
                $check_tag->execute();
                $tag_res = $check_tag->get_result();

                if ($tag_res->num_rows > 0) {
                    $tag_id = $tag_res->fetch_assoc()['TagID'];
                } else {
                    // Create new tag
                    $insert_tag = $conn->prepare("INSERT INTO tag (Name) VALUES (?)");
                    $insert_tag->bind_param("s", $tag_name);
                    $insert_tag->execute();
                    $tag_id = $conn->insert_id;
                    $insert_tag->close();
                }
                $check_tag->close();

                // Link to Recipe
                $link_stmt = $conn->prepare("INSERT INTO category (RecipeID, TagID) VALUES (?, ?)");
                $link_stmt->bind_param("ii", $recipe_id, $tag_id);
                $link_stmt->execute();
                $link_stmt->close();
            }
        }

        header("Location: profile.php"); 
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

        <label style="display:block; margin-bottom:5px; font-weight:bold;">Tags (Separate with commas)(Optional):</label>
        <input type="text" name="tags" value="<?php echo htmlspecialchars($tags_string); ?>" style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">

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