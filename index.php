<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Initialize search variables
$search_name = $_GET['search_name'] ?? '';
$search_job = $_GET['search_job'] ?? '';
$search_department = $_GET['search_department'] ?? '';
$search_salary = $_GET['search_salary'] ?? '';
$search_age = $_GET['search_age'] ?? '';
$search_id = $_GET['search_id'] ?? '';

// Build dynamic query
$query = "SELECT employees.*, departments.name AS department 
          FROM employees 
          LEFT JOIN departments ON employees.department_id = departments.id 
          WHERE 1=1";

if (!empty($search_name)) {
    $query .= " AND employees.name LIKE '%" . $conn->real_escape_string($search_name) . "%'";
}
if (!empty($search_job)) {
    $query .= " AND employees.job_title LIKE '%" . $conn->real_escape_string($search_job) . "%'";
}
if (!empty($search_department)) {
    $query .= " AND departments.name LIKE '%" . $conn->real_escape_string($search_department) . "%'";
}
if (!empty($search_salary)) {
    $query .= " AND employees.salary = '" . $conn->real_escape_string($search_salary) . "'";
}
if (!empty($search_age)) {
    $query .= " AND employees.age = '" . $conn->real_escape_string($search_age) . "'";
}
if (!empty($search_id)) {
    $query .= " AND employees.id = '" . $conn->real_escape_string($search_id) . "'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header, footer { background-color: #333; color: white; padding: 10px; text-align: center; }
        nav ul { list-style: none; padding: 0; }
        nav ul li { display: inline; margin-right: 10px; }
        nav ul li a { color: white; text-decoration: none; }
        .container { padding: 15px; }
        .search-bar input { margin: 5px; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        .edit-btn, .delete-btn { text-decoration: none; color: blue; }
        .delete-btn { color: red;text-decoration: none; color: blue; }
    </style>
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add_employee.php">Add Employee</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h2>Employee Records</h2>
    <form method="GET" class="search-bar">
        <input type="text" name="search_id" placeholder="Search by ID" value="<?= htmlspecialchars($search_id) ?>">
        <input type="text" name="search_name" placeholder="Search by Name" value="<?= htmlspecialchars($search_name) ?>">
        <input type="text" name="search_job" placeholder="Search by Job Title" value="<?= htmlspecialchars($search_job) ?>">
        <input type="text" name="search_department" placeholder="Search by Department" value="<?= htmlspecialchars($search_department) ?>">
        <input type="text" name="search_salary" placeholder="Search by Salary" value="<?= htmlspecialchars($search_salary) ?>">
        <input type="text" name="search_age" placeholder="Search by Age" value="<?= htmlspecialchars($search_age) ?>">
        <input type="submit" value="Search">
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Job Title</th>
            <th>Department</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['age']) ?></td>
            <td><?= htmlspecialchars($row['job_title']) ?></td>
            <td><?= htmlspecialchars($row['department']) ?></td>
            <td><?= htmlspecialchars($row['salary']) ?></td>
            <td>
                <a class="edit-btn" href="edit_employee.php?id=<?= $row['id'] ?>">Edit</a> |
                <a class="delete-btn" href="delete_employee.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<footer>
    <p>&copy; 2025 Employee Management System</p>
</footer>

</body>
</html>
