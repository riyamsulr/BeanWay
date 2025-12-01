<?php
$servername = "localhost";
$username = "root"; //
$password = "root"; //
$dbname = "beanway";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname, 8889);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>