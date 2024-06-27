<?php
session_start();

// Redirect to login page if username is not set in session
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root"; // Default XAMPP username
    $password = ""; // Default XAMPP password
    $dbname = "user_auth";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process and insert data into cvinfo table
    $username = $_SESSION['username'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $designation = $_POST['designation'];
    $address = $_POST['address'];
    $phoneno = $_POST['phoneno'];
    $summary = $_POST['summary'];
    $image = $_FILES['image']['name'];

    // Upload image
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $sql = "INSERT INTO cvinfo (username, email, firstname, middlename, lastname, designation, address, phoneno, summary, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $username, $email, $firstname, $middlename, $lastname, $designation, $address, $phoneno, $summary, $image);
    $stmt->execute();

    // Get the last inserted user_id
    $last_id = $stmt->insert_id;

    // Insert achievements
    if (isset($_POST['achievements'])) {
        foreach ($_POST['achievements'] as $achievement) {
            $title = $achievement['achieve_title'];
            $description = $achievement['achieve_description'];
            $sql = "INSERT INTO achievements (user_id, title, description) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $last_id, $title, $description);
            $stmt->execute();
        }
    }

    // Insert education
    if (isset($_POST['education'])) {
        foreach ($_POST['education'] as $education) {
            $school = $education['edu_school'];
            $degree = $education['edu_degree'];
            $city = $education['edu_city'];
            $start_date = $education['edu_start_date'];
            $end_date = $education['edu_graduation_date'];
            $description = $education['edu_description'];
            $sql = "INSERT INTO education (user_id, school, degree, city, start_date, end_date, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssss", $last_id, $school, $degree, $city, $start_date, $end_date, $description);
            $stmt->execute();
        }
    }

    // Insert experience
    if (isset($_POST['experience'])) {
        foreach ($_POST['experience'] as $experience) {
            $title = $experience['exp_title'];
            $organization = $experience['exp_organization'];
            $location = $experience['exp_location'];
            $start_date = $experience['exp_start_date'];
            $end_date = $experience['exp_end_date'];
            $description = $experience['exp_description'];
            $sql = "INSERT INTO experience (user_id, title, organization, location, start_date, end_date, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssss", $last_id, $title, $organization, $location, $start_date, $end_date, $description);
            $stmt->execute();
        }
    }

    // Insert projects
    if (isset($_POST['projects'])) {
        foreach ($_POST['projects'] as $project) {
            $proj_title = $project['proj_title'];
            $proj_link = $project['proj_link'];
            $description = $project['proj_description'];
            $sql = "INSERT INTO projects (user_id, proj_title, proj_link, description) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $last_id, $proj_title, $proj_link, $description);
            $stmt->execute();
        }
    }

    // Insert skills
    if (isset($_POST['skills'])) {
        foreach ($_POST['skills'] as $skill) {
            $skill_name = $skill['skill'];
            $sql = "INSERT INTO skills (user_id, skill) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $last_id, $skill_name);
            $stmt->execute();
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Display success message with JavaScript without clearing form inputs
    echo "<script>alert('CV information saved successfully!');</script>";
}
?>
