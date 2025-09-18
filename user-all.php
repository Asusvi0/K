<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงผู้ใช้และแต้มทั้งหมด</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    
    <div class="container">
        <?php
        // รวมไฟล์เชื่อมต่อฐานข้อมูล
        include 'db.php';

        // คำสั่ง SQL เพื่อดึงข้อมูล
        $sql = "SELECT username, points FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Username</th>
                        <th>Points</th>
                    </tr>";

            // แสดงข้อมูลแต่ละแถว
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["username"]) . "</td>
                        <td>" . htmlspecialchars($row["points"]) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>ไม่มีข้อมูล</p>";
        }

        // ปิดการเชื่อมต่อ
        $conn->close();
        ?>
    </div>
</body>
</html>