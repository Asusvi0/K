<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT points FROM users WHERE username='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$points = $row['points'];


$blacklist = ['0841835481', '0619927390', 'moss']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $api_key = htmlspecialchars($_POST['api_key']);

    foreach ($blacklist as $word) {
        if (stripos($phone_number, $word) !== false) {
            $error = "เบอร์โทรศัพทห้ามค้นหา";
            break;
        }
    }

    if (!isset($error)) {
        $url = "http://191.96.93.177/?phone=" . urlencode($phone_number) . "&apiKey=MEXE" . urlencode($api_key);

        
        $response = file_get_contents($url);

        if ($response === FALSE) {
            $error = "ไม่พบข้อมูล";
        } else {
            $data = json_decode($response, true);

            if (isset($data['data']) && count($data['data']) > 0) {
                $info = $data['data'][0];

                // Deduct points BY RLZXTEAM 
                $deduct_points = 1; 

                if ($points >= $deduct_points) {
                    $new_points = $points - $deduct_points;
                    $sql = "UPDATE users SET points = $new_points WHERE username='$username'";
                    if ($conn->query($sql) === TRUE) {
                        $message = "BY: RLZXTEAM";
                        $points = $new_points; 
                    } else {
                        $message = "Error: " . $conn->error;
                    }
                } else {
                    $message = "Not enough points.";
                    header("Location: login.php");
                    exit();
                }
            } else {
                $error = "ไม่พบข้อมูล";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BY RLZXTEAM</title>
    <style>
        /* CSS styles... */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            position: relative;
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
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .container input[type="submit"]:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .result {
            margin-top: 20px;
        }
        .result p {
            margin: 5px 0;
        }
        .points-display {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var points = <?php echo $points; ?>;
            var inputField = document.querySelector('input[name="phone_number"]');
            var submitButton = document.querySelector('input[type="submit"]');

            // Disable input field if points are zero or less
            if (points <= 0) {
                inputField.disabled = true;
                submitButton.disabled = true;
                inputField.placeholder = "ไม่สามารถใช้บริการได้";
                submitButton.value = "ไม่สามารถค้นหา";
            }
        });
    </script>
</head>
<body>
    <div class="points-display">
        <strong>Points:</strong> <?php echo htmlspecialchars($points); ?>
    </div>
    <div class="container">
        <h3>ค้นหาที่อยู่จากเบอร์</h3>
        <form method="POST">
            <input type="text" name="phone_number" placeholder="กรอกเบอร์โทรศัพท์" required>
            <input type="submit" value="ค้นหา">
        </form>

         <div class="result">
            <?php if (isset($info)): ?>
                <h3>Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($info['name']); ?></p>
                <p><strong>Country Code:</strong> <?php echo htmlspecialchars($info['country_code']); ?></p>
                <p><strong>Country Name:</strong> <?php echo htmlspecialchars($info['country_name']); ?></p>
                <p><strong>Province Code:</strong> <?php echo htmlspecialchars($info['province_code']); ?></p>
                <p><strong>Province Name:</strong> <?php echo htmlspecialchars($info['province_name']); ?></p>
                <p><strong>City Code:</strong> <?php echo htmlspecialchars($info['city_code']); ?></p>
                <p><strong>City Name:</strong> <?php echo htmlspecialchars($info['city_name']); ?></p>
                <p><strong>District Code:</strong> <?php echo htmlspecialchars($info['district_code']); ?></p>
                <p><strong>District Name:</strong> <?php echo htmlspecialchars($info['district_name']); ?></p>
                <p><strong>Detail Address:</strong> <?php echo htmlspecialchars($info['detail_address']); ?></p>
                <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($info['postal_code']); ?></p>
            <?php elseif (isset($error)): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>