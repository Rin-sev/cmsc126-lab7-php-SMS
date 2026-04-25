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

    $target_dir = "uploads/";
    $file_name = basename($_FILES["profile_img"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
        $sql1 = "INSERT INTO Student_info (id, name, age, email, image) VALUES ('$id', '$name', '$age', '$email', '$target_file')";
        $sql2 = "INSERT INTO Student_program (student_id, course, year_level, graduation_status) VALUES ('$id', '$course', '$level', '$grad')";
        
        if ($conn->query($sql1) && $conn->query($sql2)) {
            header("Location: student-reg.php?msg=" . urlencode("Student Registered!"));
        } else {
            header("Location: student-reg.php?msg=" . urlencode("DB Error: " . $conn->error));
        }
    } else {
        header("Location: student-reg.php?msg=" . urlencode("Upload an image"));
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
if ($action === "update_submit") {
    $id = $conn->real_escape_string($_POST["target_id"]);
    $name = $conn->real_escape_string($_POST["uname"]);
    $age = (int)$_POST["uage"];
    $email = $conn->real_escape_string($_POST["uemail"]);
    $course = $conn->real_escape_string($_POST["ucourse"]);
    $level = (int)$_POST["uyearLevel"];

    $sql_info = "UPDATE Student_info SET name='$name', age=$age, email='$email' WHERE id='$id'";

    if (!empty($_FILES["uprofile_img"]["name"])) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES["uprofile_img"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["uprofile_img"]["tmp_name"], $target_file)) {
            $sql_info = "UPDATE Student_info SET name='$name', age=$age, email='$email', image='$target_file' WHERE id='$id'";
        }
    }

    $conn->query($sql_info);
    $conn->query("UPDATE Student_program SET course='$course', year_level=$level WHERE student_id='$id'");

    header("Location: student-reg.php?update_success=" . urlencode("Student $id updated successfully!"));
    exit();
}

// Delete
if ($action === "delete") {
    $search_id = $conn->real_escape_string($_POST["search"]);
    
    $conn->query("DELETE FROM Student_program WHERE student_id='$search_id'");
    $conn->query("DELETE FROM Student_info WHERE id='$search_id'");

    if ($conn->affected_rows > 0) {
        $msg = "Student $search_id deleted successfully!";
    } else {
        $msg = "No student found with ID $search_id.";
    }

    header("Location: student-reg.php?delete_msg=" . urlencode($msg) . "&status=$status");
    exit();
}

$conn->close();
?>
