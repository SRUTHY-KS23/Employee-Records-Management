<?php
include 'db.php';

// Fetch Employee Details for Editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT e.*, d.name AS department_name FROM employees e LEFT JOIN departments d ON e.department_id = d.id WHERE e.id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

// Handle Form Submission (Update Employee)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $age = $_POST["age"];
    $job_title = $_POST["job_title"];
    $department_id = $_POST["department_id"];
    $salary = $_POST["salary"];

    $sql = "UPDATE employees SET name=?, age=?, job_title=?, department_id=?, salary=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisidi", $name, $age, $job_title, $department_id, $salary, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Employee updated successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error updating employee!');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px; /* reduced width */
            margin: 40px auto;
            background-color: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            color: #444;
        }

        input, select {
            padding: 10px;
            font-size: 15px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #007bff; /* blue button */
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Employee</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="age">Age</label>
            <input type="number" name="age" id="age" value="<?= $row['age'] ?>" required>
        </div>

        <div class="form-group">
            <label for="job_title">Job Title</label>
            <input type="text" name="job_title" id="job_title" value="<?= htmlspecialchars($row['job_title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="department_id">Department</label>
            <select name="department_id" id="department_id" required>
                <?php
                $department_query = "SELECT * FROM departments";
                $department_result = $conn->query($department_query);
                while ($department = $department_result->fetch_assoc()) {
                    $selected = ($department['id'] == $row['department_id']) ? 'selected' : '';
                    echo "<option value='{$department['id']}' $selected>{$department['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="salary">Salary</label>
            <input type="number" step="0.01" name="salary" id="salary" value="<?= $row['salary'] ?>" required>
        </div>

        <input type="submit" value="Update Employee">
        <style>
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
</style>

    </form>

</div>
<!-- Bottom Navigation Bar -->
<div class="bottom-nav">
        <a href="index.php">Home</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

</body>
</html>
