<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    // استقبال البيانات
    $comment = trim($_POST['comment']);
    $recipe_id = intval($_POST['recipe_id']); // استقبال رقم الوصفة
    $user_id = $_SESSION['user_id'];

    // ---------------------------------------------------------
    // هذا هو الشرط الذي طلبته (التحقق من الطول)
    // ---------------------------------------------------------
    if (mb_strlen($comment, 'UTF-8') > 150) {
        // إذا كان أطول من 150 حرف، نرجعه للصفحة مع رسالة خطأ
        header("Location: view-recipe.php?id=$recipe_id&error=long_comment");
        exit();
    }
    // ---------------------------------------------------------
    
    // التحقق أن التعليق ليس فارغاً
    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comment (Text, UserID, RecipeID) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $comment, $user_id, $recipe_id);
        
        if ($stmt->execute()) {
            // نجاح: نرجعه للصفحة مع رسالة نجاح
            header("Location: view-recipe.php?id=$recipe_id&msg=added");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // لو كان فارغاً نرجعه بدون فعل شيء
        header("Location: view-recipe.php?id=$recipe_id");
    }
} else {
    // محاولة دخول غير شرعية
    header("Location: login.php");
    exit();
}
?>