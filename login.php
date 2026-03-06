<?php
session_start();

// Redirect to home page if already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $username = $conn->real_escape_string($_POST['username']);
    $password = hash('sha256', $_POST['password']); // Match with the DB hash

    // Query to verify user credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Include Bootstrap CSS from a CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            font-size: 14px;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .header {
            background: #34495e;
            color: #fff;
            padding: 5px 0; /* Reduced padding for smaller header */
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .footer {
            background: #34495e;
            color: #fff;
            padding: 5px;
            width: 100%;
            font-size: 12px; /* Smaller font size for footer */
            text-align: center;
        }

        .logout {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
        }

        .login-form {
            width: 100%;
            max-width: 350px; /* Reduced form width */
            background-color: #fff;
            padding: 20px;
            border-radius: 8px; /* Slightly more rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #2E86C1;
        }

        .login-form h3 {
            font-size: 18px; /* Smaller header text */
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f1f1f1;
            color: #333;
            font-size: 14px;
        }

        .login-form input[type="submit"] {
            background-color: #2E86C1;
            color: #fff;
            font-size: 16px; /* Smaller button text */
            border: none;
            cursor: pointer;
        }

        .login-form input[type="submit"]:hover {
            background-color: #21618C;
        }

        .error {
            color: red;
            text-align: center;
            font-size: 14px;
        }

        .login-form label {
            color: #333;
            font-size: 14px;
        }

    </style>
</head>
<body>

    <div class="header">
        <div class="container">
        <h3> Admin Login</h3> 
        </div>
    </div>

    <div class="container">
        <div class="login-form">
           <!-- Smaller header text -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Login">
                </div>
            </form>
            <p class="error"><?= $error ?></p>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12"> &copy; <?php echo date("Y"); ?> Employee Management System
            </div>
        </div>
    </div>

</body>
</html>
