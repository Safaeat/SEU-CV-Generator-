<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // Assuming you are passing the ID to update
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $designation = $_POST['designation'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phoneno = $_POST['phoneno'];
    $summary = $_POST['summary'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image = $target_file;
    } else {
        $image = $_POST['existing_image']; // Use existing image if no new file is uploaded
    }

    $achievements = json_encode($_POST['group-a']);
    $experiences = json_encode($_POST['group-b']);
    $education = json_encode($_POST['group-c']);
    $projects = json_encode($_POST['group-d']);
    $skills = json_encode($_POST['group-e']);

    // Update data in database
    $sql = "UPDATE cv_data SET firstname='$firstname', middlename='$middlename', lastname='$lastname', image='$image', designation='$designation', address='$address', email='$email', phoneno='$phoneno', summary='$summary', achievements='$achievements', experiences='$experiences', education='$education', projects='$projects', skills='$skills' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $conn->close();
}
?>
