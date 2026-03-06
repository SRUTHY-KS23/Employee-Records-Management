<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Check if employee exists before deleting
    $checkQuery = "SELECT * FROM employees WHERE id=?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $sql = "DELETE FROM employees WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Employee deleted successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error deleting employee!');</script>";
        }
    } else {
        echo "<script>alert('Employee not found!'); window.location.href='index.php';</script>";
    }
    $stmt->close();
}
?>
