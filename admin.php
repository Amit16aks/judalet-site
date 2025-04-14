<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Judalet’s Fire and Ice</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #fafafa;
        }
        .login-box {
            background: white;
            padding: 20px 40px;
            border: 1px solid #dbdbdb;
            border-radius: 5px;
            width: 350px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: var(--accent-color-2);
        }
        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #dbdbdb;
            border-radius: 3px;
            font-size: 1rem;
        }
        .login-box button {
            width: 100%;
            padding: 8px;
            background: var(--accent-color-1);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-box button:hover {
            background: var(--accent-color-2);
            color: #000;
        }
        .dashboard-cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .card-btn {
            background: white;
            padding: 20px;
            border: 1px solid #dbdbdb;
            border-radius: 5px;
            width: 200px;
            text-align: center;
            text-decoration: none;
            color: var(--accent-color-1);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        .card-btn:hover {
            background: var(--accent-color-1);
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include 'connect.php';

    if (!isset($_SESSION['admin_logged_in'])) {
        // Admin Login
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $conn->real_escape_string($_POST['username']);
            $password = $conn->real_escape_string($_POST['password']);
            $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $_SESSION['admin_logged_in'] = true;
                header("Location: admin.php");
                exit();
            } else {
                echo "<p style='color:red;'>Invalid credentials!</p>";
            }
        }
        ?>
        <div class="login-container">
            <div class="login-box">
                <h2>Admin Login</h2>
                <form method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Log In</button>
                </form>
            </div>
        </div>
        <?php
    } else {
        // Admin Dashboard
        ?>
        <div class="admin-panel">
            <h2>Admin Panel</h2>
            <div style="text-align: right; margin-bottom: 20px;">
                <a href="logout.php" style="color: var(--accent-color-1); text-decoration: none;">Logout</a>
            </div>
            <div class="dashboard-cards">
                <a href="admin_products.php" class="card-btn">Manage Products</a>
                <a href="admin_orders.php" class="card-btn">Manage Orders</a>
            </div>
        </div>
        <?php
    }

    $conn->close();
    ?>
</body>
</html>