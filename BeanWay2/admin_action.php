<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Access Denied");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipe_id = intval($_POST['recipe_id']);
    $action = $_POST['action'];
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : null;
    
    $status = ($action === 'approve') ? 'approved' : 'rejected';

    // تحديث الحالة + الفيدباك
    $stmt = $conn->prepare("UPDATE recipe SET Status = ?, AdminFeedback = ? WHERE RecipeID = ?");
    $stmt->bind_param("ssi", $status, $feedback, $recipe_id);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=updated");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>