<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';

// جلب وصفات المستخدم
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM recipe WHERE UserID = ? ORDER BY RecipeID DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// تقسيم الوصفات إلى مصفوفات بناءً على الحالة
$pending = [];
$approved = [];
$rejected = [];

while ($row = $result->fetch_assoc()) {
    if ($row['Status'] == 'pending') {
        $pending[] = $row;
    } elseif ($row['Status'] == 'approved') {
        $approved[] = $row;
    } else {
        $rejected[] = $row;
    }
}
?>

<style>
    main { max-width: 1000px; margin: 40px auto 60px; padding: 0 20px; }
    
    .dashboard-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 40px; border-bottom: 1px solid #E6D7C3; padding-bottom: 20px;
    }
    .dashboard-header h2 { font-family: "Playfair Display", serif; color: #124D43; margin:0; }
    
    .add-new-btn {
        background-color: #124D43; color: #fff; padding: 10px 20px; border-radius: 8px;
        text-decoration: none; font-weight: bold; transition: 0.3s;
    }
    .add-new-btn:hover { background-color: #2F7566; }

    /* عناوين الأقسام */
    .section-title {
        font-family: "Playfair Display", serif; font-size: 24px; color: #124D43;
        margin-top: 40px; margin-bottom: 20px; border-bottom: 2px solid #E6D7C3;
        display: inline-block; padding-bottom: 5px;
    }

    /* كرت الوصفة */
    .recipe-card {
        background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 20px;
        border: 1px solid #eee; position: relative; overflow: hidden;
    }

    /* ألوان الحدود الجانبية حسب الحالة */
    .status-pending { border-left: 6px solid #f39c12; }
    .status-approved { border-left: 6px solid #27ae60; }
    .status-rejected { border-left: 6px solid #c0392b; }

    .recipe-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
    
    .recipe-info { flex-grow: 1; }
    .recipe-info h3 { margin: 0 0 5px; color: #124D43; font-family: "Playfair Display", serif; font-size: 20px; }
    .badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; color: #fff; font-weight: bold; }
    
    /* الأزرار */
    .actions { display: flex; flex-direction: column; gap: 8px; min-width: 100px; }
    .btn {
        text-align: center; padding: 8px 15px; border-radius: 6px; font-size: 13px;
        text-decoration: none; color: #fff; font-weight: 600; transition: 0.3s; display: block;
    }
    .btn-view { background-color: #95a5a6; }
    .btn-edit { background-color: #2980b9; }
    .btn-delete { background-color: #c0392b; }
    .btn:hover { opacity: 0.9; }

    .empty-msg { color: #777; font-style: italic; margin-bottom: 20px; }
</style>

<main>
    <div class="dashboard-header">
        <div>
            <h2>My Recipe Dashboard</h2>
            <p style="color:#6b5a46; margin-top:5px;">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
        </div>
        <a href="add-recipe.php" class="add-new-btn"><i class="fa-solid fa-plus"></i> Add New Recipe</a>
    </div>

    <h3 class="section-title">Pending Review</h3>
    <?php if (count($pending) > 0): ?>
        <?php foreach ($pending as $row): ?>
            <div class="recipe-card status-pending">
                <img src="<?php echo htmlspecialchars($row['Image']); ?>" class="recipe-img">
                <div class="recipe-info">
                    <h3><?php echo htmlspecialchars($row['Title']); ?> <span class="badge" style="background:#f39c12;">Pending</span></h3>
                    <p style="font-size:13px; color:#666; margin-top:5px;"><?php echo mb_substr($row['Taste'], 0, 60) . '...'; ?></p>
                    <small style="color:#999;">Submitted recently</small>
                </div>
                <div class="actions">
                    <a href="edit-recipe.php?id=<?php echo $row['RecipeID']; ?>" class="btn btn-edit"><i class="fa-solid fa-pencil"></i> Edit</a>
                    <a href="delete-recipe.php?id=<?php echo $row['RecipeID']; ?>" class="btn btn-delete" onclick="return confirm('Delete this recipe?');"><i class="fa-solid fa-trash"></i> Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty-msg">No pending recipes.</p>
    <?php endif; ?>

    <h3 class="section-title">Approved Recipes</h3>
    <?php if (count($approved) > 0): ?>
        <?php foreach ($approved as $row): ?>
            <div class="recipe-card status-approved">
                <img src="<?php echo htmlspecialchars($row['Image']); ?>" class="recipe-img">
                <div class="recipe-info">
                    <h3><?php echo htmlspecialchars($row['Title']); ?> <span class="badge" style="background:#27ae60;">Approved</span></h3>
                    <p style="font-size:13px; color:#666; margin-top:5px;"><?php echo mb_substr($row['Taste'], 0, 60) . '...'; ?></p>
                </div>
                <div class="actions">
                    <a href="view-recipe.php?id=<?php echo $row['RecipeID']; ?>" class="btn btn-view"><i class="fa-solid fa-eye"></i> View</a>
                    <a href="edit-recipe.php?id=<?php echo $row['RecipeID']; ?>" class="btn btn-edit"><i class="fa-solid fa-pencil"></i> Edit</a>
                    <a href="delete-recipe.php?id=<?php echo $row['RecipeID']; ?>" class="btn btn-delete" onclick="return confirm('Delete this recipe?');"><i class="fa-solid fa-trash"></i> Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty-msg">No approved recipes yet.</p>
    <?php endif; ?>

    <h3 class="section-title">Rejected Recipes</h3>
    <?php if (count($rejected) > 0): ?>
        <?php foreach ($rejected as $row): ?>
            <div class="recipe-card status-rejected">
                <img src="<?php echo htmlspecialchars($row['Image']); ?>" class="recipe-img">
                <div class="recipe-info">
                    <h3><?php echo htmlspecialchars($row['Title']); ?> <span class="badge" style="background:#c0392b;">Rejected</span></h3>
                    <p style="font-size:13px; color:#c0392b; margin-top:5px;"><i class="fa-solid fa-circle-exclamation"></i> Check requirements and edit to resubmit.</p>
                </div>
                <div class="actions">
                    <a href="edit-recipe.php?id=<?php echo $row['RecipeID']; ?>" class="btn btn-edit"><i class="fa-solid fa-rotate-right"></i> Edit & Resubmit</a>
                    <a href="delete-recipe.php?id=<?php echo $row['RecipeID']; ?>" class="btn btn-delete" onclick="return confirm('Delete this recipe?');"><i class="fa-solid fa-trash"></i> Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty-msg">No rejected recipes.</p>
    <?php endif; ?>

</main>

<?php include 'footer.php'; ?>