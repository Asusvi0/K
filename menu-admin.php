<?php
session_start();
include 'db.php';


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RLZXTEAM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #007bff;
            padding: 15px;
            color: white;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .button-group a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 15px 25px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            display: block;
            width: 150px;
        }
        .button-group a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Admin RLZXTEAM</h1>
    </div>
    <div class="container">
        <h1>Welcome  <?php echo htmlspecialchars($username); ?>!</h1>
        <div class="button-group">
            <a href="edit-user.php">แก้ไขผู้ใช้คุณ</a>
            <a href="delete-user.php">ลบผู้ใช้อื่น</a>
            <a href="register.php">สร้างรหัสใหม่</a>
            <a href="user-all.php">แสดงผู้ใช้ทั้งหมด</a>
            <a href="index.php">ค้นหา</a>
        </div>
    </div>
</body>
</html>