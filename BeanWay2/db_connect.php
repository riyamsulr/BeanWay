<?php
$servername = "localhost";
$username = "root"; // المستخدم الافتراضي في XAMPP/MAMP
$password = "root"; // الباسوورد (في ويندوز غالباً فارغ ""، في ماك "root")
$dbname = "beanway";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>