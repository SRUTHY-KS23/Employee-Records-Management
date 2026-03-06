<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $age = $_POST["age"];
    $job_title = $_POST["job_title"];
    $department_id = !empty($_POST["department_id"]) ? $_POST["department_id"] : NULL;
    $salary = $_POST["salary"];

    // Insert employee only if the department exists
    $check_department = "SELECT id FROM departments WHERE id = ?";
    $stmt = $conn->prepare($check_department);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0 || $department_id === NULL) {
        $stmt->close();
        
        $sql = "INSERT INTO employees (name, age, job_title, department_id, salary) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisid", $name, $age, $job_title, $department_id, $salary);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Invalid Department ID!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Display</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 20px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            background-color: #2E86C1;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #21618C;
        }

        .logout {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }

        .logout:hover {
            background-color: #c0392b;
        }
        .bottom-nav {
        text-align: center;
        margin-top: 20px;
    }

    .bottom-nav a {
        display: inline-block;
        margin: 10px 10px 0 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        font-weight: bold;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .bottom-nav a:hover {
        background-color: #0056b3;
    }

    .bottom-nav .logout {
        background-color: #dc3545;
    }

    .bottom-nav .logout:hover {
        background-color: #b52a37;
    }
        /* Bottom Navigation Bar
        .bottom-nav {
            width: 100%;
            background-color: #2E86C1;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            text-align: center;
            font-size: 14px;
        }

        .bottom-nav a {
            color: white;
            margin: 0 20px;
            text-decoration: none;
        }

        .bottom-nav a:hover {
            color: #d5d5d5;
        } */
    </style>
</head>
<body>

    <div class="container">
        <h2>Add Employee</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="number" name="age" placeholder="Age" required>
            <input type="text" name="job_title" placeholder="Job Title" required>
            <select name="department_id">
                <option value="">Select Department</option>
                <?php
                $query = "SELECT * FROM departments";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
                ?>
            </select>
            <input type="number" step="0.01" name="salary" placeholder="Salary" required>
            <input type="submit" value="Add Employee">
        </form>
    </div>
    <div class="bottom-nav">
        <a href="index.php">Home</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

</body>
</html>
