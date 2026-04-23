<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    
    $conn = new mysqli($servername, $username, $password);
    
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully!";

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS student_db";
    if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
    } else {
    echo "Error creating database: " . $conn->error;
    }

    $conn->select_db ("student_db");

    $sql_info = "CREATE TABLE IF NOT EXISTS Student_info (
        id VARCHAR(20) PRIMARY KEY,
        name VARCHAR(40) NOT NULL,
        age int(2) NOT NULL,
        email VARCHAR(40) UNIQUE NOT NULL,
        image VARCHAR(255) NOT NULL
    )";

    if ($conn->query($sql_info) === TRUE) {
        echo "Table Student_info created successfully<br>";
    } else {
        echo "Error creating Student_info: " . $conn->error . "<br>";
    }

    $sql_prog = "CREATE TABLE IF NOT EXISTS Student_program(
        program_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(20),
        course VARCHAR(40) NOT NULL,
        year_level INT(1) NOT NULL,
        graduation_status BOOLEAN,
        FOREIGN KEY (student_id) REFERENCES Student_info(id)
    )";

    if ($conn->query($sql_prog) === TRUE) {
        echo "Table Student_program created successfully<br>";
    } else {
        echo "Error creating Student_program: " . $conn->error . "<br>";
    }

    $conn->close()

?>

