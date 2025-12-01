<?php
session_start();
require 'db_connect.php';

// حماية
if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}

if (isset($_GET['id'])) {
    $recipe_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // حذف الوصفة بشرط أن تكون تابعة للمستخدم الحالي (للحماية)
    $stmt = $conn->prepare("DELETE FROM recipe WHERE RecipeID = ? AND UserID = ?");
    $stmt->bind_param("ii", $recipe_id, $user_id);

    if ($stmt->execute()) {
        header("Location: profile.php?msg=deleted");
    } else {
        echo "Error deleting record.";
    }
}
?>