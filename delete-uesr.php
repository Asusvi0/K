<?php
session_start();
include 'db.php'; // เชื่อมต่อกับฐานข้อมูล

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $username_to_delete = htmlspecialchars($_POST['username_to_delete']);

    // ตรวจสอบให้แน่ใจว่าชื่อผู้ใช้ที่ต้องการลบไม่ใช่ผู้ใช้งานที่ล็อกอินอยู่
    if ($_SESSION['username'] === $username_to_delete) {
        $message = "Cannot delete your own account.";
    } else {
        $sql = "DELETE FROM users WHERE username='$username_to_delete'";
        if ($conn->query($sql) === TRUE) {
            $message = "User deleted successfully.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .container input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #dc3545;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .message {
            margin-top: 20px;
            color: #dc3545;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete User</h1>
        <form method="POST">
            <input type="text" name="username_to_delete" placeholder="Enter username to delete" required>
            <input type="submit" name="delete_user" value="Delete User">
        </form>
        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>