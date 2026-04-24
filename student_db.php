<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$conn->query("CREATE DATABASE IF NOT EXISTS student_db");
$conn->select_db("student_db");

// Create database
$conn->query("CREATE TABLE IF NOT EXISTS Student_info (
    id VARCHAR(20) PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    age INT(2) NOT NULL,
    email VARCHAR(40) UNIQUE NOT NULL,
    image VARCHAR(255) NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS Student_program(
    program_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20),
    course VARCHAR(40) NOT NULL,
    year_level INT(1) NOT NULL,
    graduation_status BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (student_id) REFERENCES Student_info(id)
)");

$action = $_POST["action"] ?? "";

// Insert
if ($action === "insert") {
    $id = $_POST["sid"];
    $name = $_POST["sname"];
    $age = $_POST["age"];
    $email = $_POST["email"];
    $course = $_POST["scourse"];
    $level = $_POST["yearLevel"];
    $grad = isset($_POST["grad_status"]) ? 1 : 0;
    $img_path = "uploads/" . $_FILES["profile_img"]["name"];
    
    $sql1 = "INSERT INTO Student_info (id, name, age, email, image) VALUES ('$id', '$name', '$age', '$email', '$img_path')";
    $sql2 = "INSERT INTO Student_program (student_id, course, year_level, graduation_status) VALUES ('$id', '$course', '$level', '$grad')";
    
    if ($conn->query($sql1) && $conn->query($sql2)) {
        header("Location: student-reg.php?msg=" . urlencode("Student Registered!"));
    } else {
        header("Location: student-reg.php?msg=" . urlencode("Error: " . $conn->error));
    }
    exit();
}

// Search
if ($action === "search") {
    $search_id = $_POST["search"];
    header("Location: student-reg.php?search_id=" . urlencode($search_id));
    exit();
}

// Update
if ($action === "update") {
    $id = $_POST["search"];
    $name = $_POST["sname"];
    $course = $_POST["scourse"];
    $conn->query("UPDATE Student_info SET name='$name' WHERE id='$id'");
    $conn->query("UPDATE Student_program SET course='$course' WHERE student_id='$id'");
    header("Location: student-reg.php?msg=" . urlencode("Updated successfully!"));
    exit();
}

// Delete
if ($action === "delete") {
    $search_id = $_POST["search"];
    $conn->query("DELETE FROM Student_program WHERE student_id='$search_id'");
    $conn->query("DELETE FROM Student_info WHERE id='$search_id'");
    header("Location: student-reg.php?msg=" . urlencode("Deleted successfully!"));
    exit();
}

$conn->close();
?>
