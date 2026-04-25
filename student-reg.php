<?php
$message = $_GET['msg'] ?? "";
$result_html = "";

if (isset($_GET['search_id'])) {
    $search_id = $_GET['search_id'];
    $conn = new mysqli("localhost", "root", "", "student_db");

    if (!$conn->connect_error) {
        $sql = "SELECT * FROM Student_info 
                JOIN Student_program ON Student_info.id = Student_program.student_id 
                WHERE Student_info.id = '$search_id'";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $status = $row['graduation_status'] ? "Graduating" : "Enrolled";
            $result_html = "
                <div class='stuNum'>Result for: {$row['id']}</div>
                <div style='margin: 10px 0;'>
                    <img src='{$row['image']}' alt='Profile' style='width: 150px; height: 150px; object-fit: cover; border: 1px solid #ccc;'>
                </div>
                <strong>Name:</strong> {$row['name']}<br>
                <strong>Age:</strong> {$row['age']}<br>
                <strong>Email:</strong> {$row['email']}<br>
                <strong>Course:</strong> {$row['course']}<br>
                <strong>Year Level:</strong> {$row['year_level']}<br>
                <strong>Graduation Status:</strong> $status
            ";
        } else {
            $result_html = "No student found with ID: $search_id";
        }
        $conn->close();
    }
}

if (isset($_GET['update_id'])) {
    $update_id = $_GET['update_id'];
    $conn = new mysqli("localhost", "root", "", "student_db");

    if (!$conn->connect_error) {
        $update_id_safe = $conn->real_escape_string($update_id);
        $sql = "SELECT * FROM Student_info 
                JOIN Student_program ON Student_info.id = Student_program.student_id 
                WHERE Student_info.id = '$update_id_safe'";
        $res = $conn->query($sql);

        // FIXED FR
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $result_html = "
                <div class='stuNum' style='margin-bottom: 10px;'>Update Record: {$row['id']}</div>
                <form method='POST' action='student_db.php' enctype='multipart/form-data' style='text-align: left; padding: 15px; border: 1px solid #ccc; border-radius: 5px; background: #fdfdfd;'>
                    <input type='hidden' name='action' value='update_submit'>
                    <input type='hidden' name='target_id' value='{$row['id']}'>
                    
                    <label style='display:block; margin-bottom: 5px; font-weight: bold;'>Name:</label>
                    <input type='text' name='uname' value='{$row['name']}' required style='width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;'>
                    
                    <label style='display:block; margin-bottom: 5px; font-weight: bold;'>Age:</label>
                    <input type='number' name='uage' value='{$row['age']}' required style='width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;'>
                    
                    <label style='display:block; margin-bottom: 5px; font-weight: bold;'>Email:</label>
                    <input type='email' name='uemail' value='{$row['email']}' required style='width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;'>
                    
                    <label style='display:block; margin-bottom: 5px; font-weight: bold;'>Course:</label>
                    <input type='text' name='ucourse' value='{$row['course']}' required style='width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;'>
                    
                    <label style='display:block; margin-bottom: 5px; font-weight: bold;'>Year Level:</label>
                    <select name='uyearLevel' style='width: 100%; padding: 8px; margin-bottom: 15px; box-sizing: border-box;'>
                        <option value='1' ".($row['year_level'] == 1 ? 'selected' : '').">First Year</option>
                        <option value='2' ".($row['year_level'] == 2 ? 'selected' : '').">Second Year</option>
                        <option value='3' ".($row['year_level'] == 3 ? 'selected' : '').">Third Year</option>
                        <option value='4' ".($row['year_level'] == 4 ? 'selected' : '').">Fourth Year</option>
                    </select>

                    <label style='display:block; margin-bottom: 5px; font-weight: bold;'>Profile Photo:</label>
                    <div class='drop-zone' onclick='document.getElementById(\"file-upload-update\").click();' style='border: 2px dashed #ccc; padding: 20px; text-align: center; cursor: pointer;'>
                        <img id='preview-update' src='#' alt='Preview' style='display:none; width: 100px; height: 100px; object-fit: contain; margin: 0 auto 10px; border-radius: 4px;'>
                        <p id='label-update' style='margin: 0; font-size: 14px; color: #666;'>Click to change photo</p>
                        <input type='file' id='file-upload-update' name='uprofile_img' accept='image/*' style='display:none;' onchange='previewUpdateImage(this)'>
                    </div>
                    <p style='font-size: 11px; color: #888; margin-bottom: 15px;'>Leave empty to keep current photo.</p>
                    
                    <button type='submit' style='background: #0056b3; color: white; padding: 10px; border: none; cursor: pointer; width: 100%; border-radius: 4px; font-weight: bold;'>Save Updates</button>
                </form>
            ";
        } else {
            $result_html = "No student found with ID: " . htmlspecialchars($update_id) . " to update.";
        }
        $conn->close();
    }
}

if (isset($_GET['update_success'])) {
    $success_msg = htmlspecialchars($_GET['update_success']);
    $result_html = "
        <div style='padding: 20px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; font-weight: bold;'>
            $success_msg
        </div>";
}

if (isset($_GET['delete_msg'])) {
    $del_msg = htmlspecialchars($_GET['delete_msg']);
    
    $result_html = "
        <div style='padding: 20px; background-color: #edd4d4; color: #571515; border: 1px solid #c3e6cb; border-radius: 5px; font-weight: bold;'>
            $del_msg
        </div>";
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Student Registry</title>
        <link rel="stylesheet" href="lab7.css">
    </head>

    <body>
        <header>
            <h1 id="title">Student Registration</h1>
            <h2>All fields marked with <span style="color: #b22222;">*</span> are required</h2>
            <?php if(isset($_GET['msg'])): ?>
                <div id="message"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>
        </header>
        
        <div class="container">
            <form id="studentRegistry" method="POST" action="student_db.php" enctype="multipart/form-data" novalidate onsubmit="return validate_input()">
                <input type="hidden" name="action" value="insert">
            
                <div class="personal-info">
                    <h3>PERSONAL INFORMATION</h3>
                        <label class="required-label">Student ID </label>
                        <input type="text" name="sid" required placeholder="2024-00000"><br>

                        <label class="required-label">Name </label>
                        <input type="text" id="sname" name="sname" placeholder="Juan Dela Cruz" required><br>

                        <label class="required-label">Age </label>
                        <input type="number" id="sage" name="age" placeholder="0-99" required><br>

                        <label class="required-label">Email </label>
                        <input type="email" id="semail" name="email" placeholder="e.g. jdcruz@up.edu.ph" required>
                        <p style="font-size: 11px; color: #666;">Must be a valid email address (max 40 chars).</p> 
                </div>

                <div class="acad-info">
                    <h3>ACADEMIC INFORMATION</h3>
                    <div class="row">
                        <div class="course">
                            <label class="required-label">Course </label>
                            <input type="text" id="scourse" name="scourse" placeholder="e.g. BS Computer Science" required> 
                        </div> 
                        
                        <div class="level">
                            <label class="required-label">Year Level </label>
                            <select name="yearLevel" id="yearLevel">
                                <option value="1">First Year</option>
                                <option value="2">Second Year</option>
                                <option value="3">Third Year</option>
                                <option value="4">Fourth Year</option>
                            </select>
                        </div>

                        <div style="margin-top:15px; display: flex; align-items: center; gap: 10px;">
                            <label class="required-label" style="margin: 0;">Graduating this year?</label>
                            <input type="checkbox" name="grad_status" id="grad_status" value="1" style="width: auto; margin: 0;">
                            <span style="font-weight: normal; color: #333;">Yes</span>
                        </div>
                    </div>
                </div>

                <div class="profile-pic full-width">
                    <h3>PROFILE PHOTO</h3>
                    <label class="required-label">Profile Image </label><br>

                    <div class="drop-zone">
                        <label for="file-upload" class="drop-zone-content"> 
                            <img id="placeholder-icon" class="img-icn" src="img_icon.png" alt="icon"> 
                            
                            <img id="preview" src="#" alt="Preview" style="display:none; width: 200px; height: 200px; object-fit: contain; margin: 10px auto; border-radius: 8px;">
                            
                            <p><span id="choose">Choose a file</span></p>
                            <span id="fileType">JPG, PNG, GIF, WEBP accepted</span>
                        </label>
                        <input type="file" id="file-upload" name="profile_img" accept="image/*" required style="display:none;">
                    </div>
                </div>

                <div class="submit">
                    <button id="submitBtn" type="submit">SUBMIT</button>
                 </div>
            </form>

            <hr style="width: 100%; border: 0; border-top: 1px solid #ccc; margin: 20px 0;">

            <div class="records full-width" style="text-align: center;">
                <h3>RECORD MANAGEMENT</h3>
                <p>LOOK UP BY STUDENT ID</p>
                <input type="text" id="search" placeholder="Enter Student ID (e.g. 2024-00123)">
                
                <div class="buttons">
                    <form method="POST" action="student_db.php" style="display:inline;">
                        <input type="hidden" name="action" value="search">
                        <input type="hidden" name="search" id="searchVal">
                        <button type="submit" id="searchBtn" onclick="document.getElementById('searchVal').value = document.getElementById('search').value;">Search</button>
                    </form>

                    <form method="GET" action="student-reg.php" style="display:inline;">
                        <input type="hidden" name="update_id" id="updateVal">
                        <button type="submit" id="updBtn" onclick="document.getElementById('updateVal').value = document.getElementById('search').value;">Update</button>
                    </form>

                    <form method="POST" action="student_db.php" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="search" id="deleteVal">
                        <button type="submit" id="delBtn" onclick="document.getElementById('deleteVal').value = document.getElementById('search').value;">Delete</button>
                    </form>
                </div>

                <?php if($result_html): ?>
                    <div id="result">
                        <?= $result_html ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script src="lab7_scripts.js"></script>
    </body>
</html>
