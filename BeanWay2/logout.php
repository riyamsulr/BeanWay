<?php
// 1. بدء الجلسة للوصول إليها
session_start();

// 2. تفريغ جميع متغيرات الجلسة (مثل user_id, user_name)
$_SESSION = array();

// 3. تدمير الجلسة نهائياً
session_destroy();

// 4. إعادة التوجيه للصفحة الرئيسية
header("Location: index.php");
exit();
?>