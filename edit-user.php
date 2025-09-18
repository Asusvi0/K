<?php
session_start();
include 'db.php'; // เชื่อมต่อกับฐานข้อมูล

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_username'])) {
        $new_username = htmlspecialchars($_POST['new_username']);

        // ตรวจสอบไม่ให้เปลี่ยนเป็นชื่อผู้ใช้เดิม
        if ($username === $new_username) {
            $message = "New username cannot be the same as the old username.";
        } else {
            // อัปเดตชื่อผู้ใช้
            $sql = "UPDATE users SET username='$new_username' WHERE username='$username'";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['username'] = $new_username; // อัปเดตเซสชัน
                $message = "Username updated successfully.";
                $username = $new_username; // อัปเดตตัวแปร $username
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }

    if (isset($_POST['update_points'])) {
        $new_points = intval($_POST['new_points']);

        // อัปเดตแต้ม
        $sql = "UPDATE users SET points='$new_points' WHERE username='$username'";
        if ($conn->query($sql) === TRUE) {
            $message = "Points updated successfully.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// ดึงข้อมูลผู้ใช้เพื่อแสดงแต้มปัจจุบัน
$sql = "SELECT points FROM users WHERE username='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$current_points = $row['points'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Info</title>
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
        .container input[type="text"],
        .container input[type="number"] {
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
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .message {
            margin-top: 20px;
            text-align: center;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>แก้ไขข้อมูลของคุณ</h1>
        
        <form method="POST">
            <h3>Change Username</h3>
            <input type="text" name="new_username" placeholder="New Username" required>
            <input type="submit" name="change_username" value="Change Username">
        </form>

        <form method="POST">
            <h3>Update Points</h3>
            <input type="number" name="new_points" value="<?php echo htmlspecialchars($current_points); ?>" required>
            <input type="submit" name="update_points" value="Update Points">
        </form>
        
        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>